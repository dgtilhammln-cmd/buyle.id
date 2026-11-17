<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaPartner;
use App\Models\MediaPartnerCommissionLog;
use App\Models\User;
use App\Services\MediaPartnerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminMediaPartnerController extends Controller
{
    /**
     * Tampilkan daftar master Media Partner & ringkasan komisi.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $query = MediaPartner::withCount('creators')
            ->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('pic_phone', 'like', "%{$search}%")
                  ->orWhere('pic_email', 'like', "%{$search}%");
            });
        }

        $mediaPartners = $query->paginate(20)->withQueryString();

        // Statistik Keseluruhan
        $allPartners = MediaPartner::all();
        $totalPartnersCount = $allPartners->count();
        $totalReferredCreators = User::whereNotNull('media_partner_id')->count();
        $totalEarnedOverall = (float) $allPartners->sum('total_commission_earned');
        $totalPaidOverall = (float) $allPartners->sum('total_commission_paid');
        $totalBalanceOverall = (float) $allPartners->sum('balance');

        return view('admin.media-partners.index', compact(
            'mediaPartners',
            'search',
            'totalPartnersCount',
            'totalReferredCreators',
            'totalEarnedOverall',
            'totalPaidOverall',
            'totalBalanceOverall'
        ));
    }

    /**
     * Simpan Media Partner Baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:120',
            'code'                => 'nullable|string|max:60|unique:media_partners,code',
            'pic_name'            => 'nullable|string|max:100',
            'pic_phone'           => 'nullable|string|max:30',
            'pic_email'           => 'nullable|email|max:150',
            'commission_rate'     => 'required|numeric|min:0|max:100',
            'bank_name'           => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name'   => 'nullable|string|max:100',
            'notes'               => 'nullable|string|max:500',
        ]);

        $code = !empty($validated['code'])
            ? Str::slug($validated['code'])
            : Str::slug($validated['name']);

        // Pastikan code unik
        $baseCode = $code;
        $counter = 1;
        while (MediaPartner::where('code', $code)->exists()) {
            $code = $baseCode . '-' . $counter++;
        }

        MediaPartner::create([
            'name'                => $validated['name'],
            'code'                => $code,
            'pic_name'            => $validated['pic_name'] ?? null,
            'pic_phone'           => $validated['pic_phone'] ?? null,
            'pic_email'           => $validated['pic_email'] ?? null,
            'commission_rate'     => $validated['commission_rate'],
            'bank_name'           => $validated['bank_name'] ?? null,
            'bank_account_number' => $validated['bank_account_number'] ?? null,
            'bank_account_name'   => $validated['bank_account_name'] ?? null,
            'notes'               => $validated['notes'] ?? null,
            'is_active'           => true,
            'total_commission_earned' => 0,
            'total_commission_paid'   => 0,
            'balance'                 => 0,
        ]);

        return back()->with('success', 'Media Partner ' . $validated['name'] . ' berhasil didaftarkan dengan komisi ' . $validated['commission_rate'] . '%.');
    }

    /**
     * Tampilkan Detail Media Partner: List EO, Transaksi Tiket, & Riwayat Komisi.
     */
    public function show(MediaPartner $mediaPartner)
    {
        $mediaPartner->loadCount('creators');

        // Daftar creator / EO yang dibawa oleh media partner ini
        $creators = User::where('media_partner_id', $mediaPartner->id)
            ->with(['creatorProfile', 'products' => fn($p) => $p->where('product_type', 'ticket')])
            ->withCount(['orders', 'ticketPasses'])
            ->latest()
            ->paginate(15, ['*'], 'creators_page');

        // Riwayat log komisi
        $commissionLogs = MediaPartnerCommissionLog::where('media_partner_id', $mediaPartner->id)
            ->with(['creator.creatorProfile', 'order'])
            ->latest()
            ->paginate(20, ['*'], 'logs_page');

        return view('admin.media-partners.show', compact('mediaPartner', 'creators', 'commissionLogs'));
    }

    /**
     * Update data Media Partner.
     */
    public function update(Request $request, MediaPartner $mediaPartner)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:120',
            'code'                => 'required|string|max:60|unique:media_partners,code,' . $mediaPartner->id,
            'pic_name'            => 'nullable|string|max:100',
            'pic_phone'           => 'nullable|string|max:30',
            'pic_email'           => 'nullable|email|max:150',
            'commission_rate'     => 'required|numeric|min:0|max:100',
            'bank_name'           => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name'   => 'nullable|string|max:100',
            'notes'               => 'nullable|string|max:500',
        ]);

        $mediaPartner->update([
            'name'                => $validated['name'],
            'code'                => Str::slug($validated['code']),
            'pic_name'            => $validated['pic_name'] ?? null,
            'pic_phone'           => $validated['pic_phone'] ?? null,
            'pic_email'           => $validated['pic_email'] ?? null,
            'commission_rate'     => $validated['commission_rate'],
            'bank_name'           => $validated['bank_name'] ?? null,
            'bank_account_number' => $validated['bank_account_number'] ?? null,
            'bank_account_name'   => $validated['bank_account_name'] ?? null,
            'notes'               => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Data Media Partner ' . $mediaPartner->name . ' berhasil diperbarui.');
    }

    /**
     * Proses pencairan komisi ke Media Partner.
     */
    public function payout(Request $request, MediaPartner $mediaPartner)
    {
        $validated = $request->validate([
            'amount'           => 'required|numeric|min:1000|max:' . $mediaPartner->balance,
            'reference_number' => 'nullable|string|max:100',
            'notes'            => 'nullable|string|max:300',
        ]);

        try {
            MediaPartnerService::processPayout(
                $mediaPartner,
                (float) $validated['amount'],
                $validated['reference_number'] ?? null,
                $validated['notes'] ?? null
            );

            return back()->with('success', 'Pencairan komisi sebesar Rp ' . number_format($validated['amount'], 0, ',', '.') . ' ke ' . $mediaPartner->name . ' berhasil dicatat.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memproses pencairan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status aktif/nonaktif Media Partner.
     */
    public function toggleActive(MediaPartner $mediaPartner)
    {
        $mediaPartner->is_active = !$mediaPartner->is_active;
        $mediaPartner->save();

        $statusText = $mediaPartner->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'Status Media Partner ' . $mediaPartner->name . ' berhasil ' . $statusText . '.');
    }
}
