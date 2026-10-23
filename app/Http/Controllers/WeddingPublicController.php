<?php

namespace App\Http\Controllers;

use App\Models\Wedding;
use App\Models\WeddingGuest;
use App\Models\WeddingGift;
use App\Models\WeddingWish;
use Illuminate\Http\Request;

class WeddingPublicController extends Controller
{
    /**
     * Tampilkan Undangan Digital Publik (Mobile-first).
     */
    public function show(string $slug, Request $request)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        // Nama tamu personal dari query parameter (?to=Nama+Tamu)
        $guestName = $request->query('to', 'Tamu Undangan');

        // Cari tamu jika terdaftar
        $guest = WeddingGuest::where('wedding_id', $wedding->id)
            ->where('name', $guestName)
            ->first();

        // List Ucapan Doa Terbaru
        $wishes = $wedding->wishes()
            ->where('is_approved', true)
            ->latest()
            ->take(30)
            ->get();

        return view('wedding.show', compact('wedding', 'guestName', 'guest', 'wishes'));
    }

    /**
     * Kirim Doa & Konfirmasi Kehadiran Tamu.
     */
    public function submitWish(string $slug, Request $request)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'sender_name'       => ['required', 'string', 'max:150'],
            'message'           => ['required', 'string', 'max:1000'],
            'attendance_status' => ['required', 'in:hadir,tidak_hadir,ragu'],
        ]);

        $wedding->wishes()->create([
            'sender_name'       => $validated['sender_name'],
            'message'           => $validated['message'],
            'attendance_status' => $validated['attendance_status'],
            'is_approved'       => true,
            'ip_address'        => $request->ip(),
            'user_agent'        => $request->userAgent(),
        ]);

        return back()->with('wish_success', 'Terima kasih atas doa dan ucapan hangat Anda!');
    }

    /**
     * Konfirmasi Kirim Amplop Digital (Manual Transfer / QRIS).
     */
    public function submitGift(string $slug, Request $request)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'sender_name'    => ['required', 'string', 'max:150'],
            'amount'         => ['required', 'numeric', 'min:10000'],
            'payment_method' => ['required', 'string', 'max:50'],
            'message'        => ['nullable', 'string', 'max:500'],
        ]);

        $wedding->gifts()->create([
            'sender_name'    => $validated['sender_name'],
            'amount'         => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'settlement', // Manual transfer / QRIS self-confirmed
            'message'        => $validated['message'],
        ]);

        return back()->with('gift_success', 'Terima kasih atas hadiah tanda kasih yang telah Anda kirimkan!');
    }

    /**
     * Halaman Portal Meja Penerima Tamu (Reception Gate) dengan Proteksi PIN.
     */
    public function reception(string $slug, Request $request)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        // Cek PIN dari Session
        $sessionPin = session("wedding_reception_auth_{$wedding->id}");
        $isAuthorized = ($sessionPin === $wedding->reception_pin);

        // Jika request POST masukkan PIN
        if ($request->isMethod('post') && $request->has('pin')) {
            if ($request->pin === $wedding->reception_pin) {
                session(["wedding_reception_auth_{$wedding->id}" => $request->pin]);
                return redirect()->route('wedding.reception', $wedding->slug);
            } else {
                return back()->with('pin_error', 'PIN Penerima Tamu salah. Silakan coba lagi.');
            }
        }

        // Jika terotorisasi, ambil data tamu untuk autocomplete & status
        $totalGuests    = $wedding->guests()->count();
        $checkedInCount = $wedding->guests()->where('is_checked_in', true)->count();
        $guests = $isAuthorized ? $wedding->guests()->orderBy('name', 'asc')->get() : collect();

        return view('wedding.reception', compact('wedding', 'isAuthorized', 'guests', 'totalGuests', 'checkedInCount'));
    }

    /**
     * API Check-in Tamu oleh Panitia Resepsi (Bisa via QR atau Search Nama).
     */
    public function submitReceptionCheckin(string $slug, Request $request)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        // Verifikasi PIN
        $sessionPin = session("wedding_reception_auth_{$wedding->id}");
        $reqPin = $request->input('pin');

        if ($sessionPin !== $wedding->reception_pin && $reqPin !== $wedding->reception_pin) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: PIN salah.'], 403);
        }

        $code = trim($request->input('code', ''));
        $guestId = $request->input('guest_id');

        $guest = WeddingGuest::where('wedding_id', $wedding->id)
            ->where(function ($q) use ($code, $guestId) {
                if ($guestId) {
                    $q->where('id', $guestId);
                } elseif ($code) {
                    $q->where('qr_code', $code)
                      ->orWhere('name', 'like', "%{$code}%");
                }
            })->first();

        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'Tamu tidak ditemukan dalam daftar undangan.'], 404);
        }

        $actualPax = $request->input('actual_pax', $guest->pax);

        $guest->update([
            'is_checked_in' => true,
            'checked_in_at' => now(),
            'checked_in_by' => 'Meja Resepsi',
            'actual_pax'    => $actualPax,
            'notes'         => $request->input('notes', $guest->notes),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Check-in berhasil untuk {$guest->name}!",
            'guest'   => $guest,
        ]);
    }
}
