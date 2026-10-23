<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Wedding;
use App\Models\WeddingGuest;
use App\Models\WeddingGift;
use App\Models\WeddingWish;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WeddingController extends Controller
{
    /**
     * Dapatkan atau buat Wedding instance milik user yang sedang login.
     */
    protected function getOrCreateWedding(): Wedding
    {
        $user = auth()->user();
        $wedding = Wedding::where('user_id', $user->id)->first();

        if (!$wedding) {
            $baseSlug = Str::slug($user->name ?: 'wedding');
            $slug = $baseSlug;
            $counter = 1;
            while (Wedding::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $wedding = Wedding::create([
                'user_id'                  => $user->id,
                'slug'                     => $slug,
                'event_title'              => 'The Wedding of',
                'groom_name'               => 'Mempelai Pria',
                'groom_nickname'           => 'Pria',
                'bride_name'               => 'Mempelai Wanita',
                'bride_nickname'           => 'Wanita',
                'reception_pin'            => '1234',
                'event_date'               => now()->addMonths(2)->setTime(9, 0, 0),
                'ceremony_date'            => now()->addMonths(2)->setTime(8, 0, 0),
                'event_location_name'      => 'Gedung Serbaguna',
                'event_address'            => 'Jl. Bahagia Selalu No. 123',
                'digital_envelope_enabled' => true,
                'is_active'                => true,
                'theme'                    => 'classic_gold',
            ]);
        }

        return $wedding;
    }

    /**
     * Dashboard Utama Modul Wedding (Tab-Based).
     */
    public function index(Request $request)
    {
        $wedding = $this->getOrCreateWedding();

        // Data & Statistik
        $totalGuests    = $wedding->guests()->count();
        $checkedInCount = $wedding->guests()->where('is_checked_in', true)->count();
        $totalGifts     = $wedding->gifts()->where('payment_status', 'settlement')->sum('amount');
        $totalWishes    = $wedding->wishes()->count();

        // List Tamu
        $guestsQuery = $wedding->guests();
        if ($request->filled('q')) {
            $q = trim($request->q);
            $guestsQuery->where('name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('group_name', 'like', "%{$q}%");
        }
        $guests = $guestsQuery->paginate(25)->withQueryString();

        // List Ucapan
        $wishes = $wedding->wishes()->paginate(20, ['*'], 'wishes_page');

        // List Amplop Digital
        $gifts = $wedding->gifts()->paginate(20, ['*'], 'gifts_page');

        return view('creator.wedding.index', compact(
            'wedding',
            'totalGuests',
            'checkedInCount',
            'totalGifts',
            'totalWishes',
            'guests',
            'wishes',
            'gifts'
        ));
    }

    /**
     * Update Informasi Acara & Mempelai (Tab 1).
     */
    public function updateInfo(Request $request)
    {
        $wedding = $this->getOrCreateWedding();

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
            'ceremony_date'          => ['nullable', 'date'],
            'ceremony_location_name' => ['nullable', 'string', 'max:255'],
            'ceremony_address'       => ['nullable', 'string'],
            'reception_pin'          => ['required', 'string', 'min:4', 'max:10'],
            'theme'                  => ['nullable', 'string', 'max:50'],
            'music_url'              => ['nullable', 'string', 'max:500'],
            'music_file'             => ['nullable', 'file', 'mimes:mp3,wav,m4a,ogg,audio/mpeg', 'max:20480'],
        ]);

        // Upload Cover Photo
        if ($request->hasFile('cover_photo')) {
            $path = $request->file('cover_photo')->store('weddings/covers', 'public');
            $validated['cover_photo'] = $path;
        }

        // Upload Foto Groom
        if ($request->hasFile('groom_photo')) {
            $path = $request->file('groom_photo')->store('weddings/couples', 'public');
            $validated['groom_photo'] = $path;
        }

        // Upload Foto Bride
        if ($request->hasFile('bride_photo')) {
            $path = $request->file('bride_photo')->store('weddings/couples', 'public');
            $validated['bride_photo'] = $path;
        }

        // Upload Music File
        if ($request->hasFile('music_file')) {
            $musicPath = $request->file('music_file')->store('weddings/music', 'public');
            $validated['music_url'] = asset('storage/' . $musicPath);
        }

        $wedding->update($validated);

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-info'])
            ->with('success', 'Informasi acara, pasangan mempelai, dan musik berhasil disimpan!');
    }

    /**
     * Tambah 1 Tamu Undangan (Tab 2/3).
     */
    public function storeGuest(Request $request)
    {
        $wedding = $this->getOrCreateWedding();

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:150'],
            'phone'      => ['nullable', 'string', 'max:30'],
            'group_name' => ['nullable', 'string', 'max:50'],
            'pax'        => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $validated['wedding_id'] = $wedding->id;
        $validated['pax']        = $validated['pax'] ?? 1;

        WeddingGuest::create($validated);

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-guests'])
            ->with('success', 'Tamu undangan "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    /**
     * Import Tamu Sekaligus via File CSV / Excel / Textarea.
     */
    public function importGuests(Request $request)
    {
        $wedding = $this->getOrCreateWedding();

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
            return redirect()->route('creator.wedding.index', ['tab' => 'tab-guests'])
                ->with('error', 'Silakan pilih file CSV/Excel atau masukkan data tamu.');
        }

        // Normalisasi baris
        $lines = preg_split('/\r\n|\r|\n/', trim($rawContent));
        $count = 0;

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Deteksi pemisah: koma, titik koma, atau tab
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

            // Lewati header jika baris pertama adalah nama kolom
            if ($index === 0 && (strtolower($name) === 'nama' || strtolower($name) === 'nama tamu' || strtolower($name) === 'name')) {
                continue;
            }

            if (!empty($name)) {
                WeddingGuest::create([
                    'wedding_id' => $wedding->id,
                    'name'       => $name,
                    'phone'      => $phone,
                    'pax'        => $pax > 0 ? $pax : 1,
                    'group_name' => $group,
                ]);
                $count++;
            }
        }

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-guests'])
            ->with('success', "Berhasil mengimpor {$count} tamu undangan secara instan!");
    }

    /**
     * Hapus Tamu Undangan.
     */
    public function destroyGuest(WeddingGuest $guest)
    {
        $wedding = $this->getOrCreateWedding();
        if ($guest->wedding_id !== $wedding->id) {
            abort(403);
        }

        $name = $guest->name;
        $guest->delete();

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-guests'])
            ->with('success', "Tamu '{$name}' telah dihapus.");
    }

    /**
     * Check-in Tamu dari Dashboard Creator.
     */
    public function checkinGuest(Request $request)
    {
        $wedding = $this->getOrCreateWedding();
        $guest = WeddingGuest::where('wedding_id', $wedding->id)
            ->where(function ($q) use ($request) {
                if ($request->filled('guest_id')) {
                    $q->where('id', $request->guest_id);
                }
                if ($request->filled('code')) {
                    $q->orWhere('qr_code', trim($request->code));
                }
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

    /**
     * Update Pengaturan Amplop Digital & Rekening (Tab 4).
     */
    public function updateEnvelope(Request $request)
    {
        $wedding = $this->getOrCreateWedding();

        $validated = $request->validate([
            'digital_envelope_enabled' => ['nullable'],
            'bank_name'                => ['nullable', 'string', 'max:100'],
            'bank_account_number'      => ['nullable', 'string', 'max:50'],
            'bank_account_name'        => ['nullable', 'string', 'max:150'],
        ]);

        $validated['digital_envelope_enabled'] = $request->has('digital_envelope_enabled');

        if ($request->hasFile('qris_image')) {
            $path = $request->file('qris_image')->store('weddings/qris', 'public');
            $validated['qris_image'] = $path;
        }

        $wedding->update($validated);

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-envelope'])
            ->with('success', 'Pengaturan Amplop Digital & Rekening berhasil diperbarui!');
    }

    /**
     * Panic Button: Export Daftar Tamu ke PDF untuk Buku Tamu Fisik Cadangan Meja Resepsi.
     */
    public function exportPdf()
    {
        $wedding = $this->getOrCreateWedding();
        $guests = $wedding->guests()->orderBy('name', 'asc')->get();

        $pdf = Pdf::loadView('creator.wedding.panic_pdf', compact('wedding', 'guests'));
        $filename = 'Buku_Tamu_Cadangan_' . Str::slug($wedding->groom_nickname . '_' . $wedding->bride_nickname) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Hapus Ucapan Doa.
     */
    public function destroyWish(WeddingWish $wish)
    {
        $wedding = $this->getOrCreateWedding();
        if ($wish->wedding_id !== $wedding->id) {
            abort(403);
        }

        $wish->delete();

        return redirect()->route('creator.wedding.index', ['tab' => 'tab-wishes'])
            ->with('success', 'Ucapan doa berhasil dihapus.');
    }
}
