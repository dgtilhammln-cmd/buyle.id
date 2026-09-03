<?php

namespace App\Http\Controllers;

use App\Models\CreatorProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatorOnboardingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Change user role to seller instantly if they are buyer
        if ($user->role === 'buyer') {
            $user->role = 'seller';
            $user->save();
        }

        $profile = $user->creatorProfile ?? CreatorProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['store_name' => $user->name, 'store_slug' => '']
        );

        if (!$profile->bio_role) {
            return view('creator.bio.role_picker', compact('profile'));
        }

        if ($profile->bio_role === 'affiliator') {
            return redirect()->route('creator.bio.index');
        }

        return redirect()->route('creator.profile.edit')->with('warning', 'Yuk, lengkapi profil Anda terlebih dahulu untuk membuka akses ke semua fitur Creator.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Jika belum ada nama toko tapi sudah ada slug, biarkan divalidasi
        if (empty($request->store_slug) && !empty($request->store_name)) {
            $request->merge(['store_slug' => \Illuminate\Support\Str::slug($request->store_name)]);
        }

        $request->validate([
            'store_name' => 'required|string|max:30', // Max 30 chars per user feedback
            'store_slug' => 'required|string|max:30|regex:/^[a-z0-9\-]+$/|unique:creator_profiles,store_slug,' . ($user->creatorProfile->id ?? 'NULL'),
            'store_description' => 'required|string|max:60', // Max 60 chars
            'creator_type' => 'required|string|max:100',
            'social_links' => 'required|array',
            'social_links.instagram' => 'required|string|max:255',
            'social_links.tiktok' => 'required|string|max:255',
            'social_links.*' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'province_id' => 'required|integer',
            'city_id' => 'required|integer',
            'subdistrict_id' => 'required|integer',
            'avatar' => 'nullable|image|max:10240',
        ], [
            'creator_type.required' => 'Pilih peran / tipe Creator Anda.',
            'social_links.instagram.required' => 'Akun Instagram wajib diisi.',
            'social_links.tiktok.required' => 'Akun TikTok wajib diisi.',
        ]);

        $profile = CreatorProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'store_name' => $request->store_name,
                'store_slug' => $request->store_slug,
                'store_description' => $request->store_description,
                'creator_type' => $request->creator_type,
                'social_links' => array_filter($request->input('social_links', [])),
                'address' => $request->address,
                'province_id' => $request->province_id,
                'city_id' => $request->city_id,
                'subdistrict_id' => $request->subdistrict_id,
            ]
        );

        // SEO fallback during onboarding (can be changed later in full profile settings)
        if (empty($profile->meta_title)) {
            $profile->meta_title = $request->store_name . ' | buyle.id';
        }
        if (empty($profile->meta_desc)) {
            $profile->meta_desc = $request->store_description;
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid()) {
                if ($user->avatar) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $file->store('avatars', 'public');
            }
        }

        // Change user role to seller
        $user->role = 'seller';
        $user->save();
        $profile->save();

        return redirect()->route('creator.dashboard')->with('success', 'Selamat! Toko Anda berhasil dibuat. Silakan tambahkan produk pertama Anda.');
    }
}
