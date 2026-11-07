<?php

namespace App\Http\Controllers;

use App\Models\GateStaff;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * Daftar seluruh staff panitia milik Creator di semua event tiketnya.
     * GET /creator/scanner/all-staff
     */
    public function allStaff(Request $request): JsonResponse
    {
        $creatorId = auth()->id();
        $staff = GateStaff::where('creator_id', $creatorId)
            ->with('product:id,name,event_date')
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
            ->with('product:id,name')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(['success' => true, 'staff' => $staff, 'count' => $staff->count()]);
    }

    /**
     * Tambah staff panitia baru dengan Nama & Password kustom (Creator only).
     * POST /creator/scanner/staff/{product}
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        $this->authorizeCreator($product);

        $existing = GateStaff::where('product_id', $product->id)->count();
        if ($existing >= 30) {
            return response()->json(['success' => false, 'message' => 'Maksimal 30 panitia per event tiket.'], 422);
        }

        $name     = trim($request->input('name', ''));
        $password = trim($request->input('password', ''));
        $gate     = (int) $request->input('gate_number', 1);

        if (empty($name)) {
            return response()->json(['success' => false, 'message' => 'Nama panitia wajib diisi.'], 422);
        }
        if (empty($password)) {
            return response()->json(['success' => false, 'message' => 'Password panitia wajib diisi.'], 422);
        }
        if (strlen($password) < 4) {
            return response()->json(['success' => false, 'message' => 'Password panitia minimal 4 karakter.'], 422);
        }

        // Simpan password dalam bentuk hash di kolom pin
        $staff = GateStaff::create([
            'product_id'  => $product->id,
            'creator_id'  => auth()->id(),
            'name'        => $name,
            'gate_number' => $gate,
            'pin'         => Hash::make($password),
            'is_active'   => true,
        ]);

        return response()->json([
            'success'    => true,
            'message'    => "Panitia {$name} berhasil didaftarkan.",
            'staff_id'   => $staff->id,
            'staff_name' => $staff->name,
            'staff'      => [
                'id'         => $staff->id,
                'name'       => $staff->name,
                'product_id' => $product->id,
                'event_name' => $product->name,
                'is_active'  => $staff->is_active,
                'created_at' => $staff->created_at ? $staff->created_at->format('d M Y H:i') : null,
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
