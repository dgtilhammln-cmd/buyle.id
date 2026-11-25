<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaPartner;
use App\Models\MediaPartnerCommissionLog;
use App\Models\User;
use App\Services\MediaPartnerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminMediaPartnerController extends Controller
{
    use HandlesImageUpload;

    /**
     * Tampilkan daftar master Media Partner & ringkasan komisi.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $syncUserId = $request->input('sync_user_id');

        $query = MediaPartner::with(['user'])
            ->withCount('creators')
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

        // Tarik list semua creator/user untuk fitur sinkronisasi profil & rekening
        $creatorsList = User::with(['creatorProfile:id,user_id,store_slug'])
            ->select('id', 'name', 'username', 'email', 'phone', 'avatar', 'bank_name', 'bank_account_number', 'bank_account_name')
            ->orderBy('name')
            ->get()
            ->map(function ($u) {
                // Samakan slug alias username saat ini: prioritaskan slug /bio publik aktif (store_slug), lalu username
                $effectiveSlug = !empty($u->creatorProfile?->store_slug)
                    ? trim($u->creatorProfile->store_slug)
                    : (!empty($u->username) ? trim($u->username) : Str::slug($u->name, ''));

                $avatarUrl = null;
                if ($u->avatar) {
                    $avatarUrl = filter_var($u->avatar, FILTER_VALIDATE_URL) ? $u->avatar : Storage::url($u->avatar);
                }
                return [
                    'id'                  => $u->id,
                    'name'                => $u->name,
                    'username'            => $effectiveSlug,
                    'slug'                => $effectiveSlug,
                    'email'               => $u->email,
                    'phone'               => $u->phone ?? '',
                    'avatar_url'          => $avatarUrl,
                    'bank_name'           => $u->bank_name ?? '',
                    'bank_account_number' => $u->bank_account_number ?? '',
                    'bank_account_name'   => $u->bank_account_name ?? '',
                ];
            });

        return view('admin.media-partners.index', compact(
            'mediaPartners',
            'search',
            'totalPartnersCount',
            'totalReferredCreators',
            'totalEarnedOverall',
            'totalPaidOverall',
            'totalBalanceOverall',
            'creatorsList',
            'syncUserId'
        ));
    }

    /**
     * Simpan Media Partner Baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'             => 'nullable|exists:users,id',
            'name'                => 'required|string|max:120',
            'code'                => 'nullable|string|max:30|unique:media_partners,code',
            'pic_name'            => 'nullable|string|max:100',
            'pic_phone'           => 'nullable|string|max:30',
            'pic_email'           => 'nullable|email|max:150',
            'commission_rate'     => 'required|numeric|min:0|max:100',
            'bank_name'           => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name'   => 'nullable|string|max:100',
            'notes'               => 'nullable|string|max:500',
            'target_url'          => 'nullable|string|max:1000',
            'show_on_bio'         => 'nullable|boolean',
            'badge_image'         => 'nullable|image|max:5120',
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

        $badgePath = null;
        if ($request->hasFile('badge_image')) {
            $badgePath = $this->uploadImage($request->file('badge_image'), 'media-partners/badges', 800, null, 90);
        }

        $mediaPartner = MediaPartner::create([
            'user_id'             => $validated['user_id'] ?? null,
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
            'badge_image'         => $badgePath,
            'target_url'          => $validated['target_url'] ?? null,
            'show_on_bio'         => $request->boolean('show_on_bio', true),
            'is_active'           => true,
            'total_commission_earned' => 0,
            'total_commission_paid'   => 0,
            'balance'                 => 0,
        ]);

        // Sinkronisasi dua arah jika akun User dipilih: username, store_slug, dan rekening
        if (!empty($validated['user_id'])) {
            $linkedUser = User::with('creatorProfile')->find($validated['user_id']);
            if ($linkedUser) {
                $userUpdates = [];

                // Hanya update username jika belum sama dan tidak bentrok dengan akun lain
                if ($linkedUser->username !== $code) {
                    $isConflict = User::where('username', $code)
                        ->where('id', '!=', $linkedUser->id)
                        ->exists();

                    if (!$isConflict) {
                        $userUpdates['username'] = $code;
                        $userUpdates['referral_code'] = $code;
                    }
                }

                if (empty($linkedUser->bank_name) && !empty($validated['bank_name'])) {
                    $userUpdates['bank_name'] = $validated['bank_name'];
                }
                if (empty($linkedUser->bank_account_number) && !empty($validated['bank_account_number'])) {
                    $userUpdates['bank_account_number'] = $validated['bank_account_number'];
                }
                if (empty($linkedUser->bank_account_name) && !empty($validated['bank_account_name'])) {
                    $userUpdates['bank_account_name'] = $validated['bank_account_name'];
                }

                if (!empty($userUpdates)) {
                    try {
                        $linkedUser->update($userUpdates);
                    } catch (\Throwable $e) {
                        unset($userUpdates['username'], $userUpdates['referral_code']);
                        if (!empty($userUpdates)) {
                            $linkedUser->update($userUpdates);
                        }
                    }
                }

                if ($linkedUser->creatorProfile && $linkedUser->creatorProfile->store_slug !== $code) {
                    $slugConflict = \App\Models\CreatorProfile::where('store_slug', $code)
                        ->where('user_id', '!=', $linkedUser->id)
                        ->exists();
                    if (!$slugConflict) {
                        $linkedUser->creatorProfile->update(['store_slug' => $code]);
                    }
                }
            }
        }

        return back()->with('success', 'Media Partner ' . $validated['name'] . ' berhasil didaftarkan dengan komisi ' . $validated['commission_rate'] . '%.');
    }

    /**
     * Tampilkan Detail Media Partner: List EO, Transaksi Tiket, & Riwayat Komisi.
     */
    public function show(MediaPartner $mediaPartner)
    {
        $mediaPartner->load(['user'])->loadCount('creators');

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
            'user_id'             => 'nullable|exists:users,id',
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
            'target_url'          => 'nullable|string|max:1000',
            'show_on_bio'         => 'nullable|boolean',
            'badge_image'         => 'nullable|image|max:5120',
        ]);

        $badgePath = $mediaPartner->badge_image;
        if ($request->hasFile('badge_image')) {
            if ($badgePath && Storage::disk('public')->exists($badgePath)) {
                Storage::disk('public')->delete($badgePath);
            }
            $badgePath = $this->uploadImage($request->file('badge_image'), 'media-partners/badges', 800, null, 90);
        } elseif ($request->boolean('remove_badge_image')) {
            if ($badgePath && Storage::disk('public')->exists($badgePath)) {
                Storage::disk('public')->delete($badgePath);
            }
            $badgePath = null;
        }

        $mediaPartner->update([
            'user_id'             => $validated['user_id'] ?? $mediaPartner->user_id,
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
            'badge_image'         => $badgePath,
            'target_url'          => $validated['target_url'] ?? null,
            'show_on_bio'         => $request->boolean('show_on_bio', true),
        ]);

        $targetUserId = $validated['user_id'] ?? $mediaPartner->user_id;
        if (!empty($targetUserId)) {
            $linkedUser = User::with('creatorProfile')->find($targetUserId);
            if ($linkedUser) {
                $userUpdates = [];

                if ($linkedUser->username !== $mediaPartner->code) {
                    $isConflict = User::where('username', $mediaPartner->code)
                        ->where('id', '!=', $linkedUser->id)
                        ->exists();

                    if (!$isConflict) {
                        $userUpdates['username'] = $mediaPartner->code;
                        $userUpdates['referral_code'] = $mediaPartner->code;
                    }
                }

                if (empty($linkedUser->bank_name) && !empty($validated['bank_name'])) {
                    $userUpdates['bank_name'] = $validated['bank_name'];
                }
                if (empty($linkedUser->bank_account_number) && !empty($validated['bank_account_number'])) {
                    $userUpdates['bank_account_number'] = $validated['bank_account_number'];
                }
                if (empty($linkedUser->bank_account_name) && !empty($validated['bank_account_name'])) {
                    $userUpdates['bank_account_name'] = $validated['bank_account_name'];
                }

                if (!empty($userUpdates)) {
                    try {
                        $linkedUser->update($userUpdates);
                    } catch (\Throwable $e) {
                        unset($userUpdates['username'], $userUpdates['referral_code']);
                        if (!empty($userUpdates)) {
                            $linkedUser->update($userUpdates);
                        }
                    }
                }

                if ($linkedUser->creatorProfile && $linkedUser->creatorProfile->store_slug !== $mediaPartner->code) {
                    $slugConflict = \App\Models\CreatorProfile::where('store_slug', $mediaPartner->code)
                        ->where('user_id', '!=', $linkedUser->id)
                        ->exists();
                    if (!$slugConflict) {
                        $linkedUser->creatorProfile->update(['store_slug' => $mediaPartner->code]);
                    }
                }
            }
        }

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

    /**
     * Hapus Media Partner.
     */
    public function destroy(MediaPartner $mediaPartner)
    {
        $name = $mediaPartner->name;

        // Lepaskan kaitan user/creator jika ada
        User::where('media_partner_id', $mediaPartner->id)->update(['media_partner_id' => null]);

        $mediaPartner->delete();

        return back()->with('success', 'Media Partner ' . $name . ' berhasil dihapus.');
    }
}
