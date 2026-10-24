<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use App\Models\WeddingGuest;
use App\Models\WeddingGift;
use App\Models\WeddingWish;
use App\Services\MidtransService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WeddingController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helper: resolve wedding aktif berdasarkan session atau default ke pertama
    // ──────────────────────────────────────────────────────────────────────────
    protected function resolveActiveWedding(Request $request = null): ?Wedding
    {
        $user = auth()->user();
        $allWeddings = Wedding::where('user_id', $user->id)->latest()->get();

        if ($allWeddings->isEmpty()) {
            return null;
        }

        // Cek jika user memilih pasangan dari URL ?w=ID
        if ($request && $request->filled('w')) {
            $wId = (int) $request->w;
            $wedding = $allWeddings->firstWhere('id', $wId);
            if ($wedding) {
                session(['active_wedding_id' => $wedding->id]);
                return $wedding;
            }
        }

        // Cek dari session
        $sessionId = session('active_wedding_id');
        if ($sessionId) {
            $wedding = $allWeddings->firstWhere('id', $sessionId);
            if ($wedding) return $wedding;
        }

        // Default: pasangan pertama
        $first = $allWeddings->first();
        session(['active_wedding_id' => $first->id]);
        return $first;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helper: kalkulasi harga paket
    // ──────────────────────────────────────────────────────────────────────────
    public static function computePrice(int $guestLimit): array
    {
        $baseGuests = 150;
        $basePrice  = 250000;
        $extraRate  = 1000; // Rp 1.000 / tamu tambahan

        $guestLimit = max($baseGuests, $guestLimit);
        $extraGuests = $guestLimit - $baseGuests;
        $extraAmount = $extraGuests * $extraRate;
        $total = $basePrice + $extraAmount;

        return [
            'guest_limit'  => $guestLimit,
            'base_guests'  => $baseGuests,
            'base_price'   => $basePrice,
            'extra_guests' => $extraGuests,
            'extra_rate'   => $extraRate,
            'extra_amount' => $extraAmount,
            'total'        => $total,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API: kalkulasi harga (AJAX from modal)
    // ──────────────────────────────────────────────────────────────────────────
    public function calculatePrice(Request $request)
    {
        $guestLimit = (int) ($request->guest_limit ?? 150);
        return response()->json(self::computePrice($guestLimit));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // POST: order pasangan baru → create Wedding pending + return snap_token
    // ──────────────────────────────────────────────────────────────────────────
    public function orderCouple(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'groom_name'  => ['required', 'string', 'max:150'],
            'bride_name'  => ['required', 'string', 'max:150'],
            'guest_limit' => ['required', 'integer', 'min:150', 'max:5000'],
            'slug'        => ['required', 'string', 'max:100', 'unique:weddings,slug'],
        ]);

        $pricing = self::computePrice((int) $request->guest_limit);

        // Buat record wedding dengan payment_status = 'pending'
        $wedding = Wedding::create([
            'user_id'                  => $user->id,
            'slug'                     => Str::slug($request->slug),
            'event_title'              => 'The Wedding of',
            'groom_name'               => $request->groom_name,
            'groom_nickname'           => explode(' ', $request->groom_name)[0],
            'bride_name'               => $request->bride_name,
            'bride_nickname'           => explode(' ', $request->bride_name)[0],
            'reception_pin'            => '1234',
            'event_date'               => now()->addMonths(2)->setTime(9, 0, 0),
            'ceremony_date'            => now()->addMonths(2)->setTime(8, 0, 0),
            'event_location_name'      => 'Gedung Serbaguna',
            'event_address'            => 'Jl. Bahagia Selalu No. 123',
            'digital_envelope_enabled' => true,
            'is_active'                => false, // aktif setelah bayar
            'theme'                    => 'classic_gold',
            'payment_status'           => 'pending',
            'guest_limit'              => $pricing['guest_limit'],
            'paid_amount'              => $pricing['total'],
        ]);

        // Minta Snap Token ke Midtrans
        $snapToken = $this->midtrans->createWeddingSnapToken(
            $wedding,
            $user,
            $pricing['total'],
            $pricing['guest_limit']
        );

        if (!$snapToken) {
            $wedding->delete();
            return response()->json(['success' => false, 'message' => 'Gagal mendapatkan token pembayaran Midtrans.'], 500);
        }

        return response()->json([
            'success'    => true,
            'snap_token' => $snapToken,
            'wedding_id' => $wedding->id,
            'pricing'    => $pricing,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // POST: Callback manual setelah Midtrans Snap sukses (frontend JS)
    // ──────────────────────────────────────────────────────────────────────────
    public function paymentSuccess(Request $request, Wedding $wedding)
    {
        if ($wedding->user_id !== auth()->id()) {
            abort(403);
        }

        // Aktifkan jika belum (webhook sudah handle, ini fallback frontend)
        if ($wedding->payment_status !== 'paid') {
            $wedding->update([
                'payment_status' => 'paid',
                'is_active'      => true,
                'paid_at'        => now(),
            ]);
        }

        session(['active_wedding_id' => $wedding->id]);

        return response()->json([
            'success'     => true,
            'redirect_url'=> route('creator.wedding.index') . '?w=' . $wedding->id,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Dashboard Utama Modul Wedding (multi-couple, Tab-Based).
    // ──────────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = auth()->user();
        $allWeddings = Wedding::where('user_id', $user->id)->latest()->get();
        $wedding     = $this->resolveActiveWedding($request);

        if (!$wedding) {
            // Belum ada wedding sama sekali — tampilkan halaman kosong dengan modal buat pasangan
            return view('creator.wedding.index', [
                'wedding'        => null,
                'allWeddings'    => collect(),
                'totalGuests'    => 0,
                'checkedInCount' => 0,
                'totalGifts'     => 0,
                'totalWishes'    => 0,
                'guests'         => collect()->paginate(25),
                'wishes'         => collect()->paginate(20),
                'gifts'          => collect()->paginate(20),
            ]);
        }

        $totalGuests    = $wedding->guests()->count();
        $checkedInCount = $wedding->guests()->where('is_checked_in', true)->count();
        $totalGifts     = $wedding->gifts()->where('payment_status', 'settlement')->sum('amount');
        $totalWishes    = $wedding->wishes()->count();

        $guestsQuery = $wedding->guests();
        if ($request->filled('q')) {
            $q = trim($request->q);
            $guestsQuery->where('name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('group_name', 'like', "%{$q}%");
        }
        $guests = $guestsQuery->paginate(25)->withQueryString();
        $wishes = $wedding->wishes()->paginate(20, ['*'], 'wishes_page');
        $gifts  = $wedding->gifts()->paginate(20, ['*'], 'gifts_page');

        // Peta Interaktif Sebaran Tamu & RSVP berdasarkan Kota Asal
        $mapGuests = $wedding ? $wedding->wishes()
            ->select(['id', 'sender_name', 'sender_city', 'attendance_status', 'message', 'latitude', 'longitude', 'created_at'])
            ->get()
            ->map(function ($w) {
                if ((!$w->latitude || !$w->longitude) && !empty($w->sender_city)) {
                    $coords = \App\Services\CityCoordinateService::lookup($w->sender_city);
                    if ($coords) {
                        $w->latitude = $coords['lat'];
                        $w->longitude = $coords['lng'];
                        \App\Models\WeddingWish::where('id', $w->id)->update([
                            'latitude'  => $coords['lat'],
                            'longitude' => $coords['lng'],
                        ]);
                    }
                }
                return [
                    'id'      => $w->id,
                    'name'    => $w->sender_name,
                    'city'    => $w->sender_city ?: 'Indonesia',
                    'status'  => $w->attendance_status,
                    'message' => $w->message,
                    'lat'     => (float) $w->latitude,
                    'lng'     => (float) $w->longitude,
                    'time'    => $w->created_at ? $w->created_at->diffForHumans() : '',
                ];
            })
            ->filter(fn($item) => !empty($item['lat']) && !empty($item['lng']))
            ->values() : collect();

        $cityStats = $mapGuests->groupBy('city')->map(function ($group, $cityName) {
            return [
                'city'        => $cityName,
                'total'       => $group->count(),
                'hadir'       => $group->where('status', 'hadir')->count(),
                'tidak_hadir' => $group->where('status', 'tidak_hadir')->count(),
                'ragu'        => $group->where('status', 'ragu')->count(),
                'lat'         => $group->first()['lat'],
                'lng'         => $group->first()['lng'],
            ];
        })->sortByDesc('total')->values();

        $midtransClientKey = \App\Models\Setting::get('midtrans_client_key') ?: config('midtrans.client_key');
        $midtransIsProduction = \App\Models\Setting::get('midtrans_is_production') ?? config('midtrans.is_production', false);

        return view('creator.wedding.index', compact(
            'wedding',
            'allWeddings',
            'totalGuests',
            'checkedInCount',
            'totalGifts',
            'totalWishes',
            'guests',
            'wishes',
            'gifts',
            'mapGuests',
            'cityStats',
            'midtransClientKey',
            'midtransIsProduction'
        ));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Update Informasi Acara & Mempelai (Tab 1).
    // ──────────────────────────────────────────────────────────────────────────
    public function updateInfo(Request $request)
    {
        $wedding = $this->resolveActiveWedding($request);
        if (!$wedding || $wedding->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'slug'                   => ['required', 'string', 'max:100', 'unique:weddings,slug,' . $wedding->id],
            'event_title'            => ['required', 'string', 'max:150'],
            'groom_name'             => ['required', 'string', 'max:150'],
            'groom_nickname'         => ['nullable', 'string', 'max:50'],
            'groom_parents'          => ['nullable', 'string', 'max:255'],
            'groom_instagram'        => ['nullable', 'string', 'max:100'],
            'bride_name'             => ['required', 'string', 'max:150'],
            'bride_nickname'         => ['nullable', 'string', 'max:50'],
            'bride_parents'          => ['nullable', 'string', 'max:255'],
            'bride_instagram'        => ['nullable', 'string', 'max:100'],
            'event_date'             => ['nullable', 'date'],
            'event_location_name'    => ['nullable', 'string', 'max:255'],
            'event_address'          => ['nullable', 'string'],
            'event_map_url'          => ['nullable', 'string', 'max:500'],
            'event_latitude'         => ['nullable', 'numeric'],
            'event_longitude'        => ['nullable', 'numeric'],
            'ceremony_date'          => ['nullable', 'date'],
            'ceremony_location_name' => ['nullable', 'string', 'max:255'],
            'ceremony_address'       => ['nullable', 'string'],
            'reception_pin'          => ['required', 'string', 'min:4', 'max:10'],
            'theme'                  => ['nullable', 'string', 'max:50'],
            'music_url'              => ['nullable', 'string', 'max:500'],
            'music_file'             => ['nullable', 'file', 'mimes:mp3,wav,m4a,ogg,audio/mpeg', 'max:20480'],
        ]);

        if ($request->hasFile('cover_photo')) {
            $validated['cover_photo'] = $request->file('cover_photo')->store('weddings/covers', 'public');
        }
        if ($request->hasFile('groom_photo')) {
            $validated['groom_photo'] = $request->file('groom_photo')->store('weddings/couples', 'public');
        }
        if ($request->hasFile('bride_photo')) {
            $validated['bride_photo'] = $request->file('bride_photo')->store('weddings/couples', 'public');
        }
        if ($request->hasFile('music_file')) {
            $musicPath = $request->file('music_file')->store('weddings/music', 'public');
            $validated['music_url'] = asset('storage/' . $musicPath);
        }

        $wedding->update($validated);

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-info', 'w' => $wedding->id])
            ->with('success', 'Informasi acara & mempelai berhasil disimpan!');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Tambah 1 Tamu Undangan (Tab Tamu).
    // ──────────────────────────────────────────────────────────────────────────
    public function storeGuest(Request $request)
    {
        $wedding = $this->resolveActiveWedding($request);
        if (!$wedding || $wedding->user_id !== auth()->id()) abort(403);

        // Guard kuota tamu
        if (!$wedding->canAddGuests(1)) {
            return redirect()->route('creator.wedding.index', ['tab' => 'tab-guests', 'w' => $wedding->id])
                ->with('error', "Kuota tamu penuh ({$wedding->guest_limit} tamu). Silakan tambah kuota melalui tombol Upgrade Kuota.");
        }

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:150'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'group_name' => ['nullable', 'string', 'max:50'],
            'pax'        => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        WeddingGuest::create([
            'wedding_id' => $wedding->id,
            'name'       => $validated['name'],
            'phone'      => $validated['phone'] ?? null,
            'group_name' => $validated['group_name'] ?? 'Tamu Undangan',
            'pax'        => max(1, (int) ($validated['pax'] ?? 1)),
        ]);

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-guests', 'w' => $wedding->id])
            ->with('success', 'Tamu undangan "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Import Tamu via File CSV / Excel.
    // ──────────────────────────────────────────────────────────────────────────
    public function importGuests(Request $request)
    {
        $wedding = $this->resolveActiveWedding($request);
        if (!$wedding || $wedding->user_id !== auth()->id()) abort(403);

        $request->validate([
            'file'      => ['nullable', 'file', 'mimes:csv,txt,xls,xlsx', 'max:10240'],
            'bulk_data' => ['nullable', 'string'],
        ]);

        $rawContent = '';

        if ($request->hasFile('file')) {
            $rawContent = file_get_contents($request->file('file')->getRealPath());
        } elseif ($request->filled('bulk_data')) {
            $rawContent = $request->bulk_data;
        } else {
            return redirect()->route('creator.wedding.index', ['tab' => 'tab-guests', 'w' => $wedding->id])
                ->with('error', 'Silakan pilih file CSV/Excel atau masukkan data tamu.');
        }

        $lines = preg_split('/\r\n|\r|\n/', trim($rawContent));
        $count = 0;
        $skipped = 0;

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $delimiter = ',';
            if (strpos($line, ';') !== false && substr_count($line, ';') >= substr_count($line, ',')) {
                $delimiter = ';';
            } elseif (strpos($line, "\t") !== false) {
                $delimiter = "\t";
            }

            $parts = array_map('trim', explode($delimiter, $line));
            $name  = $parts[0] ?? null;
            $phone = $parts[1] ?? null;
            $pax   = isset($parts[2]) && is_numeric($parts[2]) ? (int) $parts[2] : 1;
            $group = $parts[3] ?? 'Tamu Undangan';

            // Skip header row
            if ($index === 0 && (strtolower($name) === 'nama' || strtolower($name) === 'nama tamu' || strtolower($name) === 'name')) {
                continue;
            }

            if (!empty($name)) {
                // Guard kuota
                if (!$wedding->canAddGuests(1)) {
                    $skipped++;
                    continue;
                }

                WeddingGuest::create([
                    'wedding_id' => $wedding->id,
                    'name'       => $name,
                    'phone'      => $phone,
                    'pax'        => $pax > 0 ? $pax : 1,
                    'group_name' => $group,
                ]);
                $count++;
                $wedding->refresh();
            }
        }

        $msg = "Berhasil mengimpor {$count} tamu undangan!";
        if ($skipped > 0) {
            $msg .= " {$skipped} baris dilewati karena kuota penuh (limit: {$wedding->guest_limit}).";
        }

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-guests', 'w' => $wedding->id])
            ->with('success', $msg);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Hapus Tamu Undangan.
    // ──────────────────────────────────────────────────────────────────────────
    public function destroyGuest(WeddingGuest $guest)
    {
        $wedding = $this->resolveActiveWedding();
        if (!$wedding || $guest->wedding_id !== $wedding->id || $wedding->user_id !== auth()->id()) {
            abort(403);
        }

        $name = $guest->name;
        $guest->delete();

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-guests', 'w' => $wedding->id])
            ->with('success', "Tamu '{$name}' telah dihapus.");
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Check-in Tamu dari Dashboard Creator.
    // ──────────────────────────────────────────────────────────────────────────
    public function checkinGuest(Request $request)
    {
        $wedding = $this->resolveActiveWedding($request);
        if (!$wedding || $wedding->user_id !== auth()->id()) abort(403);

        $guest = WeddingGuest::where('wedding_id', $wedding->id)
            ->where(function ($q) use ($request) {
                if ($request->filled('guest_id')) $q->where('id', $request->guest_id);
                if ($request->filled('code')) $q->orWhere('qr_code', trim($request->code));
            })->first();

        if (!$guest) {
            return response()->json(['success' => false, 'message' => 'Data tamu tidak ditemukan.'], 404);
        }

        $guest->update([
            'is_checked_in' => true,
            'checked_in_at' => now(),
            'checked_in_by' => 'Dashboard Host',
            'actual_pax'    => $request->input('actual_pax', $guest->pax),
            'notes'         => $request->input('notes', $guest->notes),
        ]);

        return response()->json([
            'success' => true,
            'message' => "{$guest->name} berhasil check-in!",
            'guest'   => $guest,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Update Amplop Digital & Rekening (Tab Amplop).
    // ──────────────────────────────────────────────────────────────────────────
    public function updateEnvelope(Request $request)
    {
        $wedding = $this->resolveActiveWedding($request);
        if (!$wedding || $wedding->user_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'digital_envelope_enabled' => ['nullable'],
            'bank_name'                => ['nullable', 'string', 'max:100'],
            'bank_account_number'      => ['nullable', 'string', 'max:50'],
            'bank_account_name'        => ['nullable', 'string', 'max:150'],
        ]);

        $validated['digital_envelope_enabled'] = $request->has('digital_envelope_enabled');

        if ($request->hasFile('qris_image')) {
            $validated['qris_image'] = $request->file('qris_image')->store('weddings/qris', 'public');
        }

        $wedding->update($validated);

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-envelope', 'w' => $wedding->id])
            ->with('success', 'Pengaturan Amplop Digital & Rekening berhasil diperbarui!');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Export Buku Tamu ke PDF (Panic Button).
    // ──────────────────────────────────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        $wedding = $this->resolveActiveWedding($request);
        if (!$wedding || $wedding->user_id !== auth()->id()) abort(403);

        $guests  = $wedding->guests()->orderBy('name', 'asc')->get();
        $pdf     = Pdf::loadView('creator.wedding.panic_pdf', compact('wedding', 'guests'));
        $filename = 'Buku_Tamu_Cadangan_' . Str::slug($wedding->groom_nickname . '_' . $wedding->bride_nickname) . '.pdf';

        return $pdf->download($filename);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Hapus Ucapan Doa.
    // ──────────────────────────────────────────────────────────────────────────
    public function destroyWish(WeddingWish $wish)
    {
        $wedding = $this->resolveActiveWedding();
        if (!$wedding || $wish->wedding_id !== $wedding->id || $wedding->user_id !== auth()->id()) {
            abort(403);
        }

        $wish->delete();

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-wishes', 'w' => $wedding->id])
            ->with('success', 'Ucapan doa berhasil dihapus.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // POST: Upload Custom Photobooth Frame PNG (9:16 InstaStory)
    // Disimpan di storage/app/public/wedding/{slug}/frames/
    // URL-nya dimasukkan ke data_json['photobooth_frames'] (max 6 frames).
    // ──────────────────────────────────────────────────────────────────────────
    public function uploadPhotoboothFrame(Request $request)
    {
        $wedding = $this->resolveActiveWedding($request);
        if (!$wedding || $wedding->user_id !== auth()->id()) abort(403);

        $request->validate([
            'frame' => ['required', 'image', 'mimes:png,webp,jpg,jpeg', 'max:4096'],
        ], [
            'frame.required' => 'Pilih file gambar terlebih dahulu.',
            'frame.image'    => 'File harus berupa gambar (PNG/WebP/JPG).',
            'frame.mimes'    => 'Format gambar harus PNG, WebP, atau JPG.',
            'frame.max'      => 'Ukuran file maksimal 4 MB.',
        ]);

        // Ambil frame yang sudah ada (max 6)
        $data   = $wedding->data_json ?? [];
        $frames = $data['photobooth_frames'] ?? [];
        if (count($frames) >= 6) {
            return back()->with('error', 'Maksimal 6 custom frame per pasangan. Hapus salah satu terlebih dahulu.');
        }

        // Simpan file ke storage/public/wedding/{slug}/frames/
        $dir  = 'wedding/' . $wedding->slug . '/frames';
        $path = $request->file('frame')->store($dir, 'public');

        $frames[] = $path;
        $data['photobooth_frames'] = $frames;
        $wedding->update(['data_json' => $data]);

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-photobooth', 'w' => $wedding->id])
            ->with('success', 'Frame custom berhasil diupload dan siap digunakan tamu di photobooth.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DELETE: Hapus satu Custom Photobooth Frame by index
    // ──────────────────────────────────────────────────────────────────────────
    public function deletePhotoboothFrame(Request $request, int $index)
    {
        $wedding = $this->resolveActiveWedding($request);
        if (!$wedding || $wedding->user_id !== auth()->id()) abort(403);

        $data   = $wedding->data_json ?? [];
        $frames = $data['photobooth_frames'] ?? [];

        if (!isset($frames[$index])) {
            return back()->with('error', 'Frame tidak ditemukan.');
        }

        // Hapus file fisik dari storage
        \Illuminate\Support\Facades\Storage::disk('public')->delete($frames[$index]);

        array_splice($frames, $index, 1);
        $data['photobooth_frames'] = $frames;
        $wedding->update(['data_json' => $data]);

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-photobooth', 'w' => $wedding->id])
            ->with('success', 'Frame custom berhasil dihapus.');
    }
}
