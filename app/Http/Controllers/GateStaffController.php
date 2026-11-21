<?php

namespace App\Http\Controllers;

use App\Models\CreatorNotification;
use App\Models\GateStaff;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GateStaffController extends Controller
{
    /**
     * Ambil daftar nama staff untuk dropdown login panitia (public, tanpa auth).
     * Hanya mengembalikan ID + nama, TIDAK ada PIN.
     */
    public function publicList(Request $request, string $key): JsonResponse
    {
        $event = Product::where('gate_scanner_key', $key)
            ->where('product_type', 'ticket')
            ->first();

        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event tidak ditemukan.'], 404);
        }

        $staff = GateStaff::where('product_id', $event->id)
            ->where('is_active', true)
            ->orderBy('gate_number')
            ->orderBy('name')
            ->get(['id', 'name', 'gate_number']);

        return response()->json(['success' => true, 'staff' => $staff]);
    }

    /**
     * Login PIN Panitia di Gate (public, tanpa auth Buyle).
     * POST /gate-scanner/{key}/staff-auth
     */
    public function auth(Request $request, string $key): JsonResponse
    {
        $event = Product::where('gate_scanner_key', $key)
            ->where('product_type', 'ticket')
            ->first();

        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event tidak ditemukan.'], 404);
        }

        $staffId = $request->input('staff_id');
        $rawPin  = trim($request->input('pin', ''));

        if (empty($staffId) || empty($rawPin)) {
            return response()->json(['success' => false, 'message' => 'Pilih nama dan masukkan PIN/Password.'], 422);
        }

        $staff = GateStaff::where('id', $staffId)
            ->where('product_id', $event->id)
            ->where('is_active', true)
            ->first();

        if (!$staff) {
            return response()->json(['success' => false, 'message' => 'Staff tidak ditemukan atau sudah dinonaktifkan.'], 404);
        }

        if (!$staff->verifyPin($rawPin)) {
            return response()->json(['success' => false, 'message' => 'PIN / Password salah.'], 401);
        }

        // Simpan sesi staff untuk event ini
        session([
            'gate_staff_' . $event->id => [
                'id'         => $staff->id,
                'name'       => $staff->name,
                'gate'       => $staff->gate_number,
                'event_id'   => $event->id,
                'event_name' => $event->name,
            ]
        ]);

        return response()->json([
            'success'    => true,
            'message'    => "Selamat datang, {$staff->name}! Anda masuk sebagai panitia Gate.",
            'staff_name' => $staff->name,
            'gate'       => $staff->gate_number,
        ]);
    }

    /**
     * Cari username creator/user buyle.id untuk pendaftaran panitia (Creator only).
     * GET /creator/scanner/check-staff-username?username=...&product_id=...
     */
    public function checkUsername(Request $request): JsonResponse
    {
        $rawUsername = trim($request->query('username', ''));
        $productId   = $request->query('product_id');
        $username    = ltrim($rawUsername, '@');

        if (empty($username)) {
            return response()->json([
                'success' => false,
                'valid'   => false,
                'message' => 'Masukkan username untuk mencari akun.',
            ], 422);
        }

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
                'message' => "Akun @{$username} tidak ditemukan di Buyle.id.",
            ], 404);
        }

        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'valid'   => false,
                'message' => 'Anda adalah penyelenggara event ini, tidak dapat mendaftarkan akun sendiri sebagai panitia.',
            ], 422);
        }

        // Cek jika sudah terdaftar di event ini
        if ($productId) {
            $already = GateStaff::where('product_id', $productId)
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('name', $user->name);
                })
                ->first();

            if ($already) {
                return response()->json([
                    'success' => false,
                    'valid'   => false,
                    'message' => "User @{$user->username} sudah terdaftar sebagai panitia ({$already->role_position}) di event ini.",
                ], 422);
            }
        }

        $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : null;
        $storeName = $user->creatorProfile?->store_name ?? $user->name;

        return response()->json([
            'success' => true,
            'valid'   => true,
            'user'    => [
                'id'       => $user->id,
                'name'     => $user->name,
                'username' => $user->username ?? $user->email,
                'avatar'   => $avatarUrl,
                'store'    => $storeName,
                'role'     => $user->role,
            ],
            'message' => "User @{$user->username} ({$user->name}) ditemukan.",
        ]);
    }

    /**
     * Daftar seluruh staff panitia milik Creator di semua event tiketnya.
     * GET /creator/scanner/all-staff
     */
    public function allStaff(Request $request): JsonResponse
    {
        $creatorId = auth()->id();
        $staff = GateStaff::where('creator_id', $creatorId)
            ->with(['product:id,name,event_date', 'user:id,name,username,avatar'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'staff'   => $staff,
            'count'   => $staff->count(),
        ]);
    }

    /**
     * Daftar staff event tertentu (Creator only).
     * GET /creator/scanner/staff/{product}
     */
    public function index(Request $request, Product $product): JsonResponse
    {
        $this->authorizeCreator($product);

        $staff = GateStaff::where('product_id', $product->id)
            ->with(['product:id,name', 'user:id,name,username,avatar'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(['success' => true, 'staff' => $staff, 'count' => $staff->count()]);
    }

    /**
     * Tambah staff panitia baru via Username & Posisi/Divisi (Creator only).
     * POST /creator/scanner/staff/{product}
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        $this->authorizeCreator($product);

        $existing = GateStaff::where('product_id', $product->id)->count();
        if ($existing >= 30) {
            return response()->json(['success' => false, 'message' => 'Maksimal 30 panitia per event tiket.'], 422);
        }

        $rawUsername  = trim($request->input('username', ''));
        $username     = ltrim($rawUsername, '@');
        $rolePosition = trim($request->input('role_position', 'Gate Scanner'));
        $gate         = (int) $request->input('gate_number', 1);

        if (empty($rolePosition)) {
            $rolePosition = 'Gate Scanner';
        }

        if (empty($username)) {
            return response()->json(['success' => false, 'message' => 'Username panitia wajib diisi.'], 422);
        }

        // Cari user di database
        $user = User::where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => "Akun @{$username} tidak ditemukan di Buyle.id."], 404);
        }

        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Anda adalah penyelenggara event ini, tidak dapat mendaftarkan diri sendiri sebagai panitia.'], 422);
        }

        // Cek duplikasi
        $already = GateStaff::where('product_id', $product->id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('name', $user->name);
            })
            ->first();

        if ($already) {
            return response()->json(['success' => false, 'message' => "User @{$user->username} sudah terdaftar sebagai panitia ({$already->role_position}) di event ini."], 422);
        }

        // Simpan staff
        $staff = GateStaff::create([
            'product_id'    => $product->id,
            'creator_id'    => auth()->id(),
            'user_id'       => $user->id,
            'name'          => $user->name,
            'gate_number'   => $gate ?: 1,
            'role_position' => $rolePosition,
            'pin'           => $user->password ?? Hash::make(Str::random(16)),
            'is_active'     => true,
        ]);

        // Kirim Notifikasi ke Creator yang di-invite
        try {
            CreatorNotification::create([
                'user_id'    => $user->id,
                'type'       => 'event_staff_invited',
                'title'      => 'Penugasan Panitia Event: ' . $product->name,
                'message'    => "Anda telah ditugaskan sebagai {$rolePosition} untuk event \"{$product->name}\" oleh " . (auth()->user()->name ?? 'Penyelenggara') . ".",
                'action_url' => route('creator.ticket.scanner', ['tab' => 'scanner', 'event_id' => $product->id]),
                'data'       => [
                    'event_id'      => $product->id,
                    'event_name'    => $product->name,
                    'role_position' => $rolePosition,
                    'invited_by'    => auth()->user()->name,
                ],
                'is_read'    => false,
            ]);
            Cache::forget('creator_unread_notif_' . $user->id);
        } catch (\Throwable $e) {
            \Log::warning('Gagal membuat notifikasi panitia: ' . $e->getMessage());
        }

        return response()->json([
            'success'    => true,
            'message'    => "Panitia @{$user->username} ({$rolePosition}) berhasil didaftarkan dan notifikasi telah dikirimkan.",
            'staff_id'   => $staff->id,
            'staff_name' => $staff->name,
            'staff'      => [
                'id'            => $staff->id,
                'name'          => $staff->name,
                'username'      => $user->username ?? $user->email,
                'role_position' => $staff->role_position,
                'product_id'    => $product->id,
                'event_name'    => $product->name,
                'is_active'     => $staff->is_active,
                'created_at'    => $staff->created_at ? $staff->created_at->format('d M Y H:i') : null,
            ],
        ]);
    }

    /**
     * Aktifkan / Nonaktifkan staff (Creator only).
     * PUT /creator/scanner/staff/{product}/{gateStaff}
     */
    public function toggle(Request $request, Product $product, GateStaff $gateStaff): JsonResponse
    {
        $this->authorizeCreator($product);

        if ($gateStaff->product_id !== $product->id && $gateStaff->creator_id !== auth()->id()) {
            abort(403);
        }

        $gateStaff->update(['is_active' => !$gateStaff->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $gateStaff->is_active,
            'message'   => $gateStaff->is_active
                ? "Panitia {$gateStaff->name} diaktifkan."
                : "Panitia {$gateStaff->name} dinonaktifkan.",
        ]);
    }

    /**
     * Hapus staff (Creator only).
     * DELETE /creator/scanner/staff/{product}/{gateStaff}
     */
    public function destroy(Request $request, Product $product, GateStaff $gateStaff): JsonResponse
    {
        $this->authorizeCreator($product);

        if ($gateStaff->product_id !== $product->id && $gateStaff->creator_id !== auth()->id()) {
            abort(403);
        }

        $name = $gateStaff->name;
        $gateStaff->delete();

        return response()->json(['success' => true, 'message' => "Panitia {$name} berhasil dihapus."]);
    }

    /**
     * Logout sesi staff panitia di gate scanner.
     * POST /gate-scanner/{key}/staff-logout
     */
    public function logout(Request $request, string $key): JsonResponse
    {
        $event = Product::where('gate_scanner_key', $key)
            ->where('product_type', 'ticket')
            ->first();

        if ($event) {
            session()->forget('gate_staff_' . $event->id);
            session()->forget('gate_staff_active');
        }

        return response()->json(['success' => true, 'message' => 'Anda telah keluar dari sesi panitia.']);
    }

    private function authorizeCreator(Product $product): void
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403);
        }
    }
}
