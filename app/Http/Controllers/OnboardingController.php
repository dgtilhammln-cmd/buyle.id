<?php

namespace App\Http\Controllers;

use App\Models\CreatorProfile;
use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Tampilkan Wizard Onboarding Super App (3 Langkah).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Admin & POS Cashier tidak perlu onboarding
        if (in_array($user->role, ['super_admin', 'admin', 'pos_cashier'])) {
            return redirect()->to(url('/'));
        }

        // Jika user sudah menyelesaikan onboarding, arahkan ke dashboard
        if ($user->onboarding_completed_at) {
            return redirect()->route('creator.dashboard');
        }

        // Ambil data Media Partner dari database media_partners terlebih dahulu
        $dbMediaPartners = \App\Models\MediaPartner::with('user')->where('is_active', true)->orderBy('name')->get();

        if ($dbMediaPartners->isNotEmpty()) {
            $partners = $dbMediaPartners->map(function ($p) {
                return [
                    'id'       => $p->id,
                    'name'     => $p->name,
                    'logo'     => $p->avatar_url,
                    'category' => 'Official Media Partner',
                    'tag'      => 'Official Media Partner',
                ];
            })->toArray();
        } else {
            // Ambil data Media Partner dari database (HeroSlide position=partner)
            $dbPartners = HeroSlide::where('position', 'partner')
                ->where('is_active', true)
                ->orderBy('order')
                ->get(['id', 'title', 'image', 'button_url']);

            if ($dbPartners->isNotEmpty()) {
                $partners = $dbPartners->map(function ($p) {
                    return [
                        'id'       => $p->id,
                        'name'     => $p->title,
                        'logo'     => $p->image ? asset('storage/' . $p->image) : null,
                        'category' => 'Official Partner',
                        'tag'      => 'Partner Resmi',
                    ];
                })->toArray();
            } else {
                // Curated official partner list fallback
                $partners = [
                    ['id' => 1, 'name' => 'Event Jawa Timur', 'logo' => null, 'category' => 'Event & Ticketing', 'tag' => 'Media Partner'],
                    ['id' => 2, 'name' => 'Info Surabaya', 'logo' => null, 'category' => 'City Media', 'tag' => 'Media Partner'],
                    ['id' => 3, 'name' => 'Creative Hub ID', 'logo' => null, 'category' => 'Creator Community', 'tag' => 'Komunitas'],
                    ['id' => 4, 'name' => 'Prambors Network', 'logo' => null, 'category' => 'Broadcast & Radio', 'tag' => 'Official Partner'],
                    ['id' => 5, 'name' => 'Konser & Festival ID', 'logo' => null, 'category' => 'Music & Concert', 'tag' => 'Media Partner'],
                    ['id' => 6, 'name' => 'Affiliator VIP Buyle', 'logo' => null, 'category' => 'Affiliate Network', 'tag' => 'Affiliator'],
                ];
            }
        }

        // Cek kode referral dari query, session, cookie, atau profil user
        $defaultRefCode = $request->query('ref') 
            ?? $request->query('aff') 
            ?? session('referral_code') 
            ?? $request->cookie('buyle_referral')
            ?? $user->referral_code
            ?? '';

        $referrerInfo = null;
        if (!empty($defaultRefCode)) {
            $refOwner = User::where('username', strtolower($defaultRefCode))
                ->orWhere('referral_code', strtolower($defaultRefCode))
                ->first();
            if ($refOwner) {
                $referrerInfo = [
                    'id'       => $refOwner->id,
                    'username' => $refOwner->username,
                    'name'     => $refOwner->name,
                ];
            }
        }

        return view('auth.onboarding', compact('user', 'partners', 'defaultRefCode', 'referrerInfo'));
    }

    /**
     * Simpan Pilihan Onboarding & Auto-Direct sesuai tujuan.
     */
    public function save(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Sesi login telah berakhir.'], 401);
        }

        $validated = $request->validate([
            'business_persona'     => ['required', 'string', 'max:50'],
            'referral_source_type' => ['required', 'string', 'max:50'],
            'referral_source_name' => ['nullable', 'string', 'max:100'],
            'media_partner_name'   => ['nullable', 'string', 'max:100'],
            'media_partner_id'     => ['nullable', 'integer'],
            'referral_code'        => ['nullable', 'string', 'max:50'],
            'primary_goal'         => ['required', 'string', 'max:50'],
        ]);

        // Tentukan nama sumber referensi
        $sourceName = $validated['referral_source_name'] ?? null;
        if (empty($sourceName)) {
            $sourceName = $validated['media_partner_name'] ?? $validated['referral_source_type'];
        }

        // Tentukan referrer_id jika ada kode referral
        $referrerId = $user->referrer_id;
        $refCode = $validated['referral_code'] ?? null;
        $mediaPartnerId = $validated['media_partner_id'] ?? null;
        $mediaPartnerName = $validated['media_partner_name'] ?? null;

        if (!empty($refCode)) {
            $refCode = strtolower(trim($refCode));
            // Cek apakah refCode adalah kode Media Partner
            $mp = \App\Models\MediaPartner::where('code', $refCode)->where('is_active', true)->first();
            if ($mp) {
                $mediaPartnerId = $mp->id;
                $mediaPartnerName = $mp->name;
            } elseif (!$referrerId) {
                $refOwner = User::where('username', $refCode)->orWhere('referral_code', $refCode)->first();
                if ($refOwner && $refOwner->id !== $user->id) {
                    $referrerId = $refOwner->id;
                }
            }
        }

        if (!empty($mediaPartnerId) && empty($mediaPartnerName)) {
            $mp = \App\Models\MediaPartner::find($mediaPartnerId);
            if ($mp) {
                $mediaPartnerName = $mp->name;
            }
        }

        // Update profil user
        $user->update([
            'business_persona'        => $validated['business_persona'],
            'referral_source_type'    => $mediaPartnerId ? 'media_partner' : $validated['referral_source_type'],
            'referral_source_name'    => $mediaPartnerName ?: $sourceName,
            'media_partner_name'      => $mediaPartnerName,
            'media_partner_id'        => $mediaPartnerId,
            'referral_code'           => $refCode,
            'referrer_id'             => $referrerId,
            'primary_goal'            => $validated['primary_goal'],
            'onboarding_completed_at' => now(),
        ]);

        // Upgrade ke role seller otomatis agar semua fitur creator langsung aktif
        if ($user->role === 'buyer') {
            $user->update(['role' => 'seller']);
        }

        // Pastikan CreatorProfile terbuat dan tersinkron peran creator
        try {
            $creatorProfile = CreatorProfile::getOrCreateForUser($user);
            if ($creatorProfile) {
                $creatorProfile->creator_type = $validated['business_persona'];
                $creatorProfile->save();
            }
        } catch (\Throwable $e) {
            \Log::warning('CreatorProfile auto-create on onboarding error: ' . $e->getMessage());
        }

        // Auto-Direct Mapping
        $redirectUrl = match ($validated['primary_goal']) {
            'products'  => url('/creator/products'),
            'bio'       => url('/creator/bio'),
            'pos'       => url('/creator/pos'),
            'ticketing' => url('/creator/ticket/profile'),
            'wedding'   => url('/creator/wedding'),
            default     => url('/creator'),
        };

        return response()->json([
            'success'      => true,
            'redirect_url' => $redirectUrl,
            'message'      => 'Profil berhasil disimpan! Menyiapkan dashboard Anda...',
        ]);
    }

    /**
     * Lewati Onboarding.
     */
    public function skip(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->update([
                'onboarding_completed_at' => now(),
            ]);

            if ($user->role === 'buyer') {
                $user->update(['role' => 'seller']);
            }

            try {
                CreatorProfile::getOrCreateForUser($user);
            } catch (\Throwable $e) {}
        }

        return redirect()->route('creator.dashboard')
            ->with('info', 'Anda dapat melengkapi preferensi bisnis kapan saja di menu Pengaturan.');
    }
}
