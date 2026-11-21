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
use Illuminate\Support\Str;

class CreatorTransferController extends Controller
{
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
     * AJAX / POST: Proses transfer saldo internal Buyle Pay.
     */
    public function processTransfer(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'amount'   => 'required|numeric|min:1000',
            'notes'    => 'nullable|string|max:200',
        ], [
            'username.required' => 'Username penerima wajib diisi.',
            'amount.required'   => 'Nominal transfer wajib diisi.',
            'amount.min'        => 'Minimal transfer saldo adalah Rp 1.000.',
        ]);

        $sender = Auth::user();

        // Wajib set PIN keamanan sebelum transfer saldo
        if (empty($sender->security_pin) && empty($sender->pos_pin)) {
            return response()->json([
                'success'      => false,
                'message'      => 'Anda wajib mengatur PIN keamanan terlebih dahulu sebelum melakukan transfer saldo.',
                'pin_required' => true,
            ], 422);
        }

        $amount = (float) $request->input('amount');
        $notes  = trim($request->input('notes', ''));

        // Cek saldo terkini pengirim
        $balanceData = SellerBalanceService::calculateBalance($sender);
        $availableBalance = (float) ($balanceData['available_balance'] ?? 0);

        if ($amount > $availableBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo Buyle Pay Anda tidak mencukupi. Tersedia: Rp ' . number_format($availableBalance, 0, ',', '.') . ', diminta: Rp ' . number_format($amount, 0, ',', '.'),
            ], 422);
        }

        // Cek penerima
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

        return DB::transaction(function () use ($sender, $receiver, $amount, $notes, $availableBalance) {
            // Generate kode transaksi unik
            $transferCode = 'TRF-' . date('Ymd') . '-' . strtoupper(Str::random(6));

            $transfer = InternalTransfer::create([
                'transfer_code' => $transferCode,
                'sender_id'     => $sender->id,
                'receiver_id'   => $receiver->id,
                'amount'        => $amount,
                'notes'         => $notes ?: null,
                'status'        => 'success',
            ]);

            $senderDisplay = $sender->username ? ('@' . $sender->username) : $sender->name;
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

            $newBalanceData = SellerBalanceService::calculateBalance($sender);
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
