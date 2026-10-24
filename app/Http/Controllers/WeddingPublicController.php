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
     * Halaman Utama Landing Page Buyle Wedding (https://buyle.id/wedding).
     */
    public function landing()
    {
        return view('wedding.landing');
    }

    /**
     * Tampilkan Undangan Digital Publik (Mobile-first).
     * Dilengkapi Fitur Anti-Nakal (Jika parameter ?to diubah sembarangan, redirect ke domain utama).
     */
    public function show(string $slug, Request $request)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        $guest = null;
        $guestName = 'Tamu Undangan';

        // Fitur Anti-Nakal: Periksa parameter 'to'
        if ($request->has('to')) {
            $rawTo = trim($request->query('to'));

            if (!empty($rawTo)) {
                // Cari di database apakah nama tamu ini benar-benar terdaftar di undangan ini
                $matchedGuest = WeddingGuest::where('wedding_id', $wedding->id)
                    ->where(function ($q) use ($rawTo, $request) {
                        $q->where('name', $rawTo)
                          ->orWhere('name', urldecode($rawTo));

                        if ($request->filled('code')) {
                            $q->orWhere('qr_code', trim($request->query('code')))
                              ->orWhere('qr_token', trim($request->query('code')));
                        }
                    })->first();

                // ANTI-NAKAL GUARD: Jika nama tidak ditemukan (tamu mengedit URL sembarangan)
                // Langsung redirect ke domain wedding mempelai utama tanpa query nakal!
                if (!$matchedGuest) {
                    return redirect()->route('wedding.show', ['slug' => $wedding->slug]);
                }

                $guest = $matchedGuest;
                $guestName = $matchedGuest->name;
            }
        }

        // List Ucapan Doa Terbaru
        $wishes = $wedding->wishes()
            ->where('is_approved', true)
            ->latest()
            ->take(30)
            ->get();

        $citySuggestions = \App\Services\CityCoordinateService::getCitySuggestions();

        return view('wedding.show', compact('wedding', 'guestName', 'guest', 'wishes', 'citySuggestions'));
    }

    /**
     * Kirim Doa & Konfirmasi Kehadiran Tamu (RSVP) beserta Kota Domisili.
     */
    public function submitWish(string $slug, Request $request)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'sender_name'       => ['required', 'string', 'max:150'],
            'sender_city'       => ['nullable', 'string', 'max:100'],
            'message'           => ['required', 'string', 'max:1000'],
            'attendance_status' => ['required', 'in:hadir,tidak_hadir,ragu'],
            'guest_id'          => ['nullable', 'exists:wedding_guests,id'],
        ]);

        $city = trim($validated['sender_city'] ?? '');
        $lat = null;
        $lng = null;

        if (!empty($city)) {
            $coords = \App\Services\CityCoordinateService::lookup($city);
            if ($coords) {
                $lat = $coords['lat'];
                $lng = $coords['lng'];
                // Bersihkan nama kota agar rapi jika ada format "Sidoarjo (Jawa Timur)"
                $cleanName = explode('(', $city)[0];
                $city = trim($cleanName);
            }
        }

        // Cari atau validasi guest_id
        $guestId = $validated['guest_id'] ?? null;
        if (!$guestId) {
            $matched = WeddingGuest::where('wedding_id', $wedding->id)
                ->where('name', $validated['sender_name'])
                ->first();
            if ($matched) {
                $guestId = $matched->id;
            }
        }

        $wish = $wedding->wishes()->create([
            'wedding_id'        => $wedding->id,
            'guest_id'          => $guestId,
            'sender_name'       => $validated['sender_name'],
            'sender_city'       => !empty($city) ? $city : 'Indonesia',
            'message'           => $validated['message'],
            'attendance_status' => $validated['attendance_status'],
            'latitude'          => $lat,
            'longitude'         => $lng,
            'is_approved'       => true,
            'ip_address'        => $request->ip(),
            'user_agent'        => $request->userAgent(),
        ]);

        return back()->with('wish_success', 'Terima kasih ' . e($validated['sender_name']) . ', doa dan konfirmasi kehadiran Anda berhasil dikirim!');
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
                      ->orWhere('qr_token', $code)
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
