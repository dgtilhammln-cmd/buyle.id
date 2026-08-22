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
        
        // Jika sudah jadi seller, arahkan ke dashboard
        if ($user->role === 'seller' || $user->role === 'admin') {
            return redirect()->route('creator.dashboard');
        }

        $profile = $user->creatorProfile ?? new CreatorProfile();
        
        return view('creator.onboarding', compact('user', 'profile'));
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
            'address' => 'required|string|max:255',
            'province_id' => 'required|integer',
            'city_id' => 'required|integer',
            'subdistrict_id' => 'required|integer',
            'avatar' => 'nullable|image|max:10240',
        ]);

        $profile = CreatorProfile::updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'store_name', 'store_slug', 'store_description',
                'address', 'province_id', 'city_id', 'subdistrict_id'
            ])
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
