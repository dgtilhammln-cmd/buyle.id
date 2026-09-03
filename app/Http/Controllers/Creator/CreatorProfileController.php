<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorProfile;
use Illuminate\Http\Request;

class CreatorProfileController extends Controller
{
    use \App\Http\Controllers\Admin\HandlesImageUpload;

    public function edit()
    {
        $user = auth()->user();
        $profile = $user->creatorProfile ?? new CreatorProfile();
        
        return view('creator.profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if (empty($request->store_slug) && !empty($request->store_name)) {
            $request->merge(['store_slug' => \Illuminate\Support\Str::slug($request->store_name)]);
        }

        $request->validate([
            'store_name' => 'nullable|string|max:100',
            'store_slug' => 'nullable|string|max:100|regex:/^[a-z0-9\-]+$/|unique:creator_profiles,store_slug,' . ($user->creatorProfile->id ?? 'NULL'),
            'store_description' => 'nullable|string|max:500',
            'creator_type' => 'nullable|string|max:100',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'province_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'subdistrict_id' => 'nullable|integer',
            'province_name' => 'nullable|string|max:100',
            'city_name' => 'nullable|string|max:100',
            'meta_title' => 'nullable|string|max:70',
            'meta_desc' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:10240',
            'store_banner_1' => 'nullable|image|max:10240',
            'store_banner_2' => 'nullable|image|max:10240',
        ]);

        $profile = CreatorProfile::updateOrCreate(
            ['user_id' => $user->id],
            array_merge(
                $request->only([
                    'store_name', 'store_slug', 'store_description',
                    'creator_type',
                    'address', 'province_id', 'city_id', 'subdistrict_id',
                    'province_name', 'city_name',
                    'meta_title', 'meta_desc', 'meta_keywords'
                ]),
                [
                    'social_links' => array_filter($request->input('social_links', []))
                ]
            )
        );

        // Handle is_store_active toggle setting in bio_config
        $bioConfig = $profile->bio_config ?? [];
        $bioConfig['is_store_active'] = $request->has('is_store_active') ? $request->boolean('is_store_active') : false;
        $profile->bio_config = $bioConfig;
        $profile->save();

        if ($request->hasFile('store_banner_1')) {
            $b1 = $request->file('store_banner_1');
            if ($b1->isValid()) {
                if ($profile->store_banner_1) \Illuminate\Support\Facades\Storage::disk('public')->delete($profile->store_banner_1);
                $profile->store_banner_1 = $b1->store('banners', 'public');
            }
        }

        if ($request->hasFile('store_banner_2')) {
            $b2 = $request->file('store_banner_2');
            if ($b2->isValid()) {
                if ($profile->store_banner_2) \Illuminate\Support\Facades\Storage::disk('public')->delete($profile->store_banner_2);
                $profile->store_banner_2 = $b2->store('banners', 'public');
            }
        }
        $profile->save();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid()) {
                if ($user->avatar) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $file->store('avatars', 'public');
                $user->save();
            }
        }

        return redirect()->route('creator.profile.edit')->with('success', 'Profil toko & SEO berhasil diperbarui!');
    }
}
