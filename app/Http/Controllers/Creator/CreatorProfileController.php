<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorProfile;
use Illuminate\Http\Request;

class CreatorProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->creatorProfile ?? new CreatorProfile();
        
        return view('creator.profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'store_name' => 'nullable|string|max:100',
            'store_slug' => 'nullable|string|max:100|regex:/^[a-z0-9\-]+$/|unique:creator_profiles,store_slug,' . ($user->creatorProfile->id ?? 'NULL'),
            'store_description' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
            'province_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'subdistrict_id' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:70',
            'meta_desc' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        CreatorProfile::updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'store_name', 'store_slug', 'store_description',
                'address', 'province_id', 'city_id', 'subdistrict_id',
                'meta_title', 'meta_desc', 'meta_keywords'
            ])
        );

        return redirect()->route('creator.profile.edit')->with('success', 'Profil toko & SEO berhasil diperbarui!');
    }
}
