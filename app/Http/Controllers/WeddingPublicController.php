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
     * Web-based Live Camera Photobooth dengan Frame Wedding 9:16 untuk InstaStory.
     * Tamu scan barcode di venue -> open to camera -> pilih aneka frame photobooth -> jepret foto & share ke InstaStory.
     */
    public function photobooth(string $slug, Request $request)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();
        $guestName = $request->query('to', 'Tamu Spesial');

        return view('wedding.photobooth', compact('wedding', 'guestName'));
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

        // Normalisasi status kehadiran (support 'attendance' maupun 'attendance_status')
        $attendance = $request->input('attendance_status') ?? $request->input('attendance', 'hadir');
        if ($attendance === 'tidak') {
            $attendance = 'tidak_hadir';
        }
        if (!in_array($attendance, ['hadir', 'tidak_hadir', 'ragu'])) {
            $attendance = 'hadir';
        }

        // Normalisasi kota
        $city = trim($request->input('sender_city') ?? $request->input('city', ''));

        $validated = $request->validate([
            'sender_name' => ['required', 'string', 'max:150'],
            'message'     => ['required', 'string', 'max:1000'],
            'guest_id'    => ['nullable', 'exists:wedding_guests,id'],
        ]);

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

        $envelopeAmount = (float) ($request->input('amount') ?? $request->input('envelope_amount', 0));
        $midtransOrderId = $request->input('midtrans_order_id');
        $paymentStatus = $envelopeAmount > 0 ? ($request->input('payment_status', 'settlement')) : null;

        $wish = $wedding->wishes()->create([
            'wedding_id'        => $wedding->id,
            'guest_id'          => $guestId,
            'sender_name'       => $validated['sender_name'],
            'sender_city'       => !empty($city) ? $city : 'Indonesia',
            'message'           => $validated['message'],
            'attendance_status' => $attendance,
            'envelope_amount'   => $envelopeAmount,
            'payment_status'    => $paymentStatus,
            'midtrans_order_id' => $midtransOrderId,
            'latitude'          => $lat,
            'longitude'         => $lng,
            'is_approved'       => true,
            'ip_address'        => $request->ip(),
            'user_agent'        => $request->userAgent(),
        ]);

        if ($envelopeAmount > 0) {
            if ($midtransOrderId) {
                WeddingGift::updateOrCreate(
                    ['midtrans_order_id' => $midtransOrderId],
                    [
                        'wedding_id'        => $wedding->id,
                        'guest_id'          => $guestId,
                        'sender_name'       => $validated['sender_name'],
                        'amount'            => $envelopeAmount,
                        'payment_method'    => 'midtrans',
                        'payment_status'    => 'settlement',
                        'message'           => $validated['message'],
                    ]
                );
            } else {
                $wedding->gifts()->create([
                    'guest_id'          => $guestId,
                    'sender_name'       => $validated['sender_name'],
                    'amount'            => $envelopeAmount,
                    'payment_method'    => 'midtrans',
                    'payment_status'    => 'settlement',
                    'message'           => $validated['message'],
                ]);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Terima kasih ' . e($validated['sender_name']) . ', doa dan konfirmasi kehadiran Anda berhasil dikirim!'
            ]);
        }

        return back()->with('wish_success', 'Terima kasih ' . e($validated['sender_name']) . ', doa dan konfirmasi kehadiran Anda berhasil dikirim!');
    }

    /**
     * Generate Midtrans Snap Token untuk Amplop Digital Tamu Undangan.
     */
    public function getEnvelopeSnapToken(string $slug, Request $request)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'amount'      => ['required', 'numeric', 'min:1000'],
            'sender_name' => ['nullable', 'string', 'max:150'],
        ]);

        $amount = (int) $validated['amount'];
        $senderName = trim($validated['sender_name'] ?? '') ?: 'Tamu Undangan';

        $midtransOrderId = 'AMPLOP-' . $wedding->id . '-' . time() . '-' . rand(100, 999);

        $midtransService = app(\App\Services\MidtransService::class);
        $snapToken = $midtransService->createWeddingEnvelopeSnapToken($wedding, $senderName, $amount, $midtransOrderId);

        if (!$snapToken) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat token pembayaran Midtrans. Silakan coba beberapa saat lagi.'
            ], 500);
        }

        // Catat gift awal dengan status pending
        $gift = $wedding->gifts()->create([
            'wedding_id'        => $wedding->id,
            'sender_name'       => $senderName,
            'amount'            => $amount,
            'payment_method'    => 'midtrans',
            'payment_status'    => 'pending',
            'midtrans_order_id' => $midtransOrderId,
            'snap_token'        => $snapToken,
        ]);

        return response()->json([
            'success'           => true,
            'snap_token'        => $snapToken,
            'midtrans_order_id' => $midtransOrderId,
            'gift_id'           => $gift->id,
        ]);
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

    /**
     * Simpan hasil foto photobooth (Base64 JPEG/PNG).
     * Disimpan ke storage/app/public/wedding/{slug}/photobooth/
     * dan dicatat di data_json['photobooth_photos'].
     */
    public function savePhotoboothPhoto(string $slug, Request $request)
    {
        $wedding = Wedding::where('slug', $slug)->firstOrFail();

        $request->validate([
            'image' => ['required', 'string'],
            'shots' => ['nullable', 'integer'],
        ]);

        $imageRaw = $request->input('image');
        $type = 'jpg';
        if (preg_match('/^data:image\/(\w+);base64,/', $imageRaw, $matches)) {
            $imageRaw = substr($imageRaw, strpos($imageRaw, ',') + 1);
            $ext = strtolower($matches[1]);
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $type = $ext === 'jpeg' ? 'jpg' : $ext;
            }
        }

        $imageBinary = base64_decode($imageRaw);
        if ($imageBinary === false) {
            return response()->json(['success' => false, 'message' => 'Format gambar tidak valid.'], 422);
        }

        $filename = 'pb_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $type;
        $relPath  = 'wedding/' . $wedding->slug . '/photobooth/' . $filename;

        \Illuminate\Support\Facades\Storage::disk('public')->put($relPath, $imageBinary);

        // Mirror ke public_html/storage jika folder fisik public_html ada
        $publicHtmlStorageDir = base_path('public_html/storage/' . dirname($relPath));
        if (is_dir(base_path('public_html/storage'))) {
            @mkdir($publicHtmlStorageDir, 0775, true);
            @file_put_contents(base_path('public_html/storage/' . $relPath), $imageBinary);
        }

        $data   = $wedding->data_json ?? [];
        $photos = $data['photobooth_photos'] ?? [];

        $newPhoto = [
            'id'         => uniqid('pb_'),
            'path'       => $relPath,
            'url'        => \Illuminate\Support\Facades\Storage::disk('public')->url($relPath),
            'shots'      => (int) ($request->input('shots') ?? 1),
            'guest_name' => $request->input('guest_name', 'Tamu Spesial'),
            'created_at' => now()->toIso8601String(),
        ];

        // Foto terbaru selalu di urutan teratas
        array_unshift($photos, $newPhoto);

        // Maksimal simpan 200 riwayat foto photobooth
        if (count($photos) > 200) {
            $photos = array_slice($photos, 0, 200);
        }

        $data['photobooth_photos'] = $photos;
        $wedding->update(['data_json' => $data]);

        return response()->json([
            'success'   => true,
            'message'   => 'Foto berhasil disimpan ke galeri photobooth.',
            'photo_url' => $newPhoto['url'],
        ]);
    }
}
