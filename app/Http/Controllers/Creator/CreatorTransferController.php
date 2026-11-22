<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorNotification;
use App\Models\InternalTransfer;
use App\Models\User;
use App\Services\SellerBalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CreatorTransferController extends Controller
{
    // Maks percobaan PIN salah sebelum kunci (buylepay.md 7.4)
    private const PIN_MAX_ATTEMPTS = 3;
    // Durasi kunci akun setelah brute-force PIN (menit)
    private const PIN_LOCK_MINUTES = 30;

    /**
     * AJAX: Validasi username tujuan & ambil profil ringkas.
     */
    public function checkUser(Request $request): JsonResponse
    {
        $rawUsername = trim($request->query('username', ''));
        $username = ltrim($rawUsername, '@');

        if (empty($username)) {
            return response()->json([
                'success' => false,
                'valid'   => false,
                'message' => 'Silakan masukkan username penerima.',
            ], 422);
        }

        $currentUser = Auth::user();

        // Cari user berdasarkan username atau email
        $user = User::with('creatorProfile')
            ->where(function ($q) use ($username) {
                $q->where('username', $username)
                  ->orWhere('email', $username);
            })
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'valid'   => false,
                'message' => 'Username @' . $username . ' tidak ditemukan di Buyle.id.',
            ], 404);
        }

        $currentUserId = Auth::id();
        if ($currentUserId && (int)$user->id === (int)$currentUserId) {
            return response()->json([
                'success' => false,
                'valid'   => false,
                'message' => 'Anda tidak dapat mentransfer saldo ke akun Anda sendiri.',
            ], 422);
        }

        // Resolve Avatar
        $avatarUrl = null;
        if (!empty($user->avatar)) {
            $avatarUrl = str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar);
        } elseif ($user->creatorProfile && !empty($user->creatorProfile->avatar)) {
            $avatarUrl = str_starts_with($user->creatorProfile->avatar, 'http') ? $user->creatorProfile->avatar : asset('storage/' . $user->creatorProfile->avatar);
        } elseif ($user->creatorProfile && !empty($user->creatorProfile->logo)) {
            $avatarUrl = str_starts_with($user->creatorProfile->logo, 'http') ? $user->creatorProfile->logo : asset('storage/' . $user->creatorProfile->logo);
        }

        $storeName = $user->creatorProfile?->store_name ?? $user->name;

        return response()->json([
            'success'    => true,
            'valid'      => true,
            'user_id'    => $user->id,
            'name'       => $user->name,
            'username'   => $user->username ?: $user->email,
            'store_name' => $storeName,
            'avatar_url' => $avatarUrl,
            'initial'    => strtoupper(substr($storeName ?: $user->name, 0, 1)),
        ]);
    }

    /**
     * AJAX: Verifikasi PIN Buyle Pay sebelum transfer (step 1 — cek PIN saja).
     * Mengembalikan token sementara jika PIN valid.
     * Implementasi buylepay.md Seksi 7.4 (brute-force protection).
     */
    public function verifyTransferPin(Request $request): JsonResponse
    {
        $request->validate([
            'pin' => 'required|string|digits:6',
        ], [
            'pin.required' => 'PIN wajib diisi.',
            'pin.digits'   => 'PIN harus tepat 6 digit angka.',
        ]);

        $user = Auth::user();

        // --- Rate limiting berbasis IP (buylepay.md 7.4: maks 5 req/menit per IP) ---
        $ipKey = 'transfer_pin_' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 5)) {
            $seconds = RateLimiter::availableIn($ipKey);
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan. Silakan coba lagi dalam ' . $seconds . ' detik.',
            ], 429);
        }
        RateLimiter::hit($ipKey, 60);

        // --- Cek apakah PIN sudah dikunci (brute-force) ---
        $pinLockedUntil = $user->pin_locked_until ?? null;
        if ($pinLockedUntil && now()->lt(Carbon::parse($pinLockedUntil))) {
            $remaining = now()->diffInMinutes(Carbon::parse($pinLockedUntil), false);
            return response()->json([
                'success'    => false,
                'pin_locked' => true,
                'message'    => 'Akun Anda terkunci karena terlalu banyak percobaan PIN salah. Coba lagi dalam ' . max(1, $remaining) . ' menit.',
            ], 423);
        }

        // --- Pastikan PIN sudah di-set ---
        $storedPin = $user->security_pin ?? $user->pos_pin ?? null;
        if (empty($storedPin)) {
            return response()->json([
                'success'      => false,
                'pin_required' => true,
                'message'      => 'Anda belum mengatur PIN keamanan. Silakan atur PIN terlebih dahulu di Pengaturan Keamanan.',
            ], 422);
        }

        // --- Verifikasi PIN (bcrypt atau plaintext legacy) ---
        $isValid = Hash::check($request->pin, $storedPin) || ($request->pin === $storedPin);

        if (!$isValid) {
            // Tambah counter percobaan gagal
            $newAttempts = ((int)($user->pin_attempts ?? 0)) + 1;
            $updateData  = ['pin_attempts' => $newAttempts];

            if ($newAttempts >= self::PIN_MAX_ATTEMPTS) {
                // Kunci akun 30 menit (buylepay.md 7.4)
                $updateData['pin_locked_until'] = now()->addMinutes(self::PIN_LOCK_MINUTES);
                $updateData['pin_attempts']     = 0; // reset counter
                try { $user->update($updateData); } catch (\Throwable $e) {}

                return response()->json([
                    'success'    => false,
                    'pin_locked' => true,
                    'message'    => 'Terlalu banyak percobaan PIN salah. Akun Anda dikunci selama ' . self::PIN_LOCK_MINUTES . ' menit.',
                ], 423);
            }

            try { $user->update($updateData); } catch (\Throwable $e) {}

            $remaining = self::PIN_MAX_ATTEMPTS - $newAttempts;
            return response()->json([
                'success' => false,
                'message' => 'PIN tidak sesuai. ' . $remaining . ' percobaan tersisa sebelum akun dikunci.',
            ], 422);
        }

        // PIN valid — reset counter percobaan
        try {
            $user->update(['pin_attempts' => 0, 'pin_locked_until' => null]);
        } catch (\Throwable $e) {}

        // Bersihkan rate limiter IP juga
        RateLimiter::clear($ipKey);

        // Kembalikan token sementara (session-based, berlaku 5 menit)
        $pinToken = Str::random(40);
        session()->put('buylepaytransfer_pin_token', $pinToken);
        session()->put('buylepaytransfer_pin_expires', now()->addMinutes(5)->timestamp);

        return response()->json([
            'success'   => true,
            'pin_token' => $pinToken,
            'message'   => 'PIN terverifikasi.',
        ]);
    }

    /**
     * AJAX / POST: Proses transfer saldo internal Buyle Pay.
     * Implementasi buylepay.md Seksi 7.1 (Pessimistic Lock + Idempotency).
     */
    public function processTransfer(Request $request): JsonResponse
    {
        $request->validate([
            'username'        => 'required|string',
            'amount'          => 'required|numeric|integer|min:1000|max:10000000',
            'notes'           => 'nullable|string|max:200',
            'pin_token'       => 'required|string',
            'idempotency_key' => 'nullable|string|max:100',
        ], [
            'username.required'  => 'Username penerima wajib diisi.',
            'amount.required'    => 'Nominal transfer wajib diisi.',
            'amount.min'         => 'Minimal transfer saldo adalah Rp 1.000.',
            'amount.max'         => 'Maksimal transfer per transaksi adalah Rp 10.000.000.',
            'amount.integer'     => 'Nominal transfer harus berupa angka bulat.',
            'pin_token.required' => 'Verifikasi PIN wajib dilakukan terlebih dahulu.',
        ]);

        // Validasi nominal positif (buylepay.md 7.2 — cegah negative value exploit)
        if ((float)$request->amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Nominal tidak valid.'], 422);
        }

        $sender = Auth::user();

        // --- Verifikasi PIN Token (buylepay.md 7.4) ---
        $sessionToken   = session('buylepaytransfer_pin_token');
        $sessionExpires = session('buylepaytransfer_pin_expires');

        if (
            empty($sessionToken)
            || $request->pin_token !== $sessionToken
            || empty($sessionExpires)
            || now()->timestamp > $sessionExpires
        ) {
            return response()->json([
                'success'      => false,
                'pin_expired'  => true,
                'message'      => 'Sesi verifikasi PIN kedaluwarsa. Harap verifikasi PIN ulang.',
            ], 422);
        }

        // Hapus token setelah digunakan sekali (single-use)
        session()->forget(['buylepaytransfer_pin_token', 'buylepaytransfer_pin_expires']);

        // --- Cek Transfer Cooldown setelah Reset PIN (buylepay.md 7.5) ---
        $cooldownUntil = $sender->transfer_cooldown_until ?? null;
        if ($cooldownUntil && now()->lt(Carbon::parse($cooldownUntil))) {
            $remaining = now()->diffInHours(Carbon::parse($cooldownUntil), false);
            return response()->json([
                'success'  => false,
                'cooldown' => true,
                'message'  => 'Transfer dibekukan sementara selama ' . max(1, $remaining) . ' jam setelah reset PIN. Ini untuk keamanan akun Anda.',
            ], 403);
        }

        $amount          = (float) $request->input('amount');
        $notes           = trim($request->input('notes', ''));
        $idempotencyKey  = trim($request->input('idempotency_key', ''));

        // --- Cek Idempotency (buylepay.md 7.1 — cegah double transfer) ---
        if (!empty($idempotencyKey) && Schema::hasColumn('internal_transfers', 'idempotency_key')) {
            $existing = InternalTransfer::where('idempotency_key', $idempotencyKey)->first();
            if ($existing) {
                return response()->json([
                    'success'       => true,
                    'idempotent'    => true,
                    'message'       => 'Transfer ini sudah diproses sebelumnya.',
                    'transfer_code' => $existing->transfer_code,
                ]);
            }
        }

        // --- Cari penerima ---
        $cleanUsername = ltrim(trim($request->input('username')), '@');
        $receiver = User::where('username', $cleanUsername)
            ->orWhere('email', $cleanUsername)
            ->first();

        if (!$receiver) {
            return response()->json([
                'success' => false,
                'message' => 'Penerima dengan username @' . $cleanUsername . ' tidak ditemukan.',
            ], 404);
        }

        if ((int)$receiver->id === (int)$sender->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mentransfer saldo ke akun sendiri.',
            ], 422);
        }

        // --- DB Transaction + Pessimistic Lock (buylepay.md 7.1) ---
        return DB::transaction(function () use ($sender, $receiver, $amount, $notes, $idempotencyKey) {

            // Kunci baris sender untuk baca saldo terkini (anti race-condition)
            $lockedSender = User::where('id', $sender->id)->lockForUpdate()->firstOrFail();

            // Hitung saldo aktual setelah lock
            $balanceData      = SellerBalanceService::calculateBalance($lockedSender);
            $availableBalance = (float) ($balanceData['available_balance'] ?? 0);

            if ($amount > $availableBalance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo Buyle Pay tidak mencukupi. Tersedia: Rp ' . number_format($availableBalance, 0, ',', '.') . ', diminta: Rp ' . number_format($amount, 0, ',', '.'),
                ], 422);
            }

            // Generate kode transaksi unik
            $transferCode = 'TRF-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $transferData = [
                'transfer_code' => $transferCode,
                'sender_id'     => $sender->id,
                'receiver_id'   => $receiver->id,
                'amount'        => $amount,
                'notes'         => $notes ?: null,
                'status'        => 'success',
                'ip_address'    => request()->ip(),
            ];

            // Simpan idempotency_key jika kolom tersedia
            if (!empty($idempotencyKey) && Schema::hasColumn('internal_transfers', 'idempotency_key')) {
                $transferData['idempotency_key'] = $idempotencyKey;
            }

            $transfer = InternalTransfer::create($transferData);

            // Catat buku besar double-entry (buylepay.md Seksi 6)
            try {
                \App\Services\WalletLedgerService::recordTransfer($sender, $receiver, $amount, $notes, $idempotencyKey, $transferCode);
            } catch (\Throwable $e) {
                \Log::warning('WalletLedgerService recordTransfer warning: ' . $e->getMessage());
            }

            $senderDisplay   = $sender->username ? ('@' . $sender->username) : $sender->name;
            $receiverDisplay = $receiver->username ? ('@' . $receiver->username) : $receiver->name;

            // Notifikasi ke penerima
            try {
                CreatorNotification::create([
                    'user_id'    => $receiver->id,
                    'type'       => 'transfer_received',
                    'title'      => 'Saldo Masuk (Transfer Buyle Pay)',
                    'message'    => 'Anda menerima transfer saldo sebesar Rp ' . number_format($amount, 0, ',', '.') . ' dari ' . $senderDisplay . ($notes ? ' ("' . $notes . '")' : ''),
                    'action_url' => route('creator.dashboard'),
                    'is_read'    => false,
                ]);
            } catch (\Throwable $e) {}

            // Notifikasi ke pengirim
            try {
                CreatorNotification::create([
                    'user_id'    => $sender->id,
                    'type'       => 'transfer_sent',
                    'title'      => 'Transfer Saldo Berhasil',
                    'message'    => 'Berhasil mentransfer saldo Rp ' . number_format($amount, 0, ',', '.') . ' ke ' . $receiverDisplay . ' (Ref: ' . $transferCode . ').',
                    'action_url' => route('creator.dashboard'),
                    'is_read'    => false,
                ]);
            } catch (\Throwable $e) {}

            $newBalanceData      = SellerBalanceService::calculateBalance($sender);
            $newAvailableBalance = (float) ($newBalanceData['available_balance'] ?? 0);

            return response()->json([
                'success'               => true,
                'message'               => 'Transfer sebesar Rp ' . number_format($amount, 0, ',', '.') . ' ke ' . $receiverDisplay . ' berhasil diproses!',
                'transfer_code'         => $transferCode,
                'amount'                => $amount,
                'formatted_amount'      => 'Rp ' . number_format($amount, 0, ',', '.'),
                'new_balance'           => $newAvailableBalance,
                'formatted_new_balance' => 'Rp ' . number_format($newAvailableBalance, 0, ',', '.'),
                'receiver'              => [
                    'name'     => $receiver->name,
                    'username' => $receiver->username ?: $receiver->email,
                ],
            ]);
        });
    }
}
