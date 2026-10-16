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
            return response()->json(['success' => false, 'message' => 'Pilih nama dan masukkan PIN.'], 422);
        }

        $staff = GateStaff::where('id', $staffId)
            ->where('product_id', $event->id)
            ->where('is_active', true)
            ->first();

        if (!$staff) {
            return response()->json(['success' => false, 'message' => 'Staff tidak ditemukan atau sudah dinonaktifkan.'], 404);
        }

        if (!$staff->verifyPin($rawPin)) {
            return response()->json(['success' => false, 'message' => 'PIN salah. Periksa kembali PIN Anda.'], 401);
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
            'message'    => "Selamat datang, {$staff->name}! Anda masuk sebagai panitia Gate {$staff->gate_number}.",
            'staff_name' => $staff->name,
            'gate'       => $staff->gate_number,
        ]);
    }

    /**
     * Daftar staff event (Creator only).
     * GET /creator/scanner/staff/{product}
     */
    public function index(Request $request, Product $product): JsonResponse
    {
        $this->authorizeCreator($product);

        $staff = GateStaff::where('product_id', $product->id)
            ->orderBy('gate_number')
            ->orderBy('name')
            ->get(['id', 'name', 'gate_number', 'is_active', 'created_at']);

        return response()->json(['success' => true, 'staff' => $staff, 'count' => $staff->count()]);
    }

    /**
     * Tambah staff baru (Creator only). PIN di-generate dan dikembalikan SEKALI.
     * POST /creator/scanner/staff/{product}
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        $this->authorizeCreator($product);

        $existing = GateStaff::where('product_id', $product->id)->count();
        if ($existing >= 10) {
            return response()->json(['success' => false, 'message' => 'Maksimal 10 panitia per event.'], 422);
        }

        $name = trim($request->input('name', ''));
        $gate = (int) $request->input('gate_number', 1);

        if (empty($name)) {
            return response()->json(['success' => false, 'message' => 'Nama panitia wajib diisi.'], 422);
        }
        if ($gate < 1 || $gate > 10) {
            return response()->json(['success' => false, 'message' => 'Nomor gate harus antara 1–10.'], 422);
        }

        // Generate PIN 6 digit
        $rawPin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $staff = GateStaff::create([
            'product_id'  => $product->id,
            'creator_id'  => auth()->id(),
            'name'        => $name,
            'gate_number' => $gate,
            'pin'         => Hash::make($rawPin),
            'is_active'   => true,
        ]);

        return response()->json([
            'success'    => true,
            'message'    => "Panitia {$name} (Gate {$gate}) berhasil ditambahkan.",
            'staff_id'   => $staff->id,
            'staff_name' => $staff->name,
            'gate'       => $staff->gate_number,
            // PIN dikembalikan SEKALI dan tidak pernah disimpan/ditampilkan lagi
            'pin_once'   => $rawPin,
        ]);
    }

    /**
     * Aktifkan / Nonaktifkan staff (Creator only).
     * PUT /creator/scanner/staff/{product}/{staff}
     */
    public function toggle(Request $request, Product $product, GateStaff $gateStaff): JsonResponse
    {
        $this->authorizeCreator($product);

        if ($gateStaff->product_id !== $product->id) {
            abort(403);
        }

        $gateStaff->update(['is_active' => !$gateStaff->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $gateStaff->is_active,
            'message'   => $gateStaff->is_active
                ? "{$gateStaff->name} diaktifkan kembali."
                : "{$gateStaff->name} dinonaktifkan (akses gate dicabut).",
        ]);
    }

    /**
     * Hapus staff (Creator only).
     * DELETE /creator/scanner/staff/{product}/{staff}
     */
    public function destroy(Request $request, Product $product, GateStaff $gateStaff): JsonResponse
    {
        $this->authorizeCreator($product);

        if ($gateStaff->product_id !== $product->id) {
            abort(403);
        }

        $name = $gateStaff->name;
        $gateStaff->delete();

        return response()->json(['success' => true, 'message' => "Panitia {$name} dihapus."]);
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
