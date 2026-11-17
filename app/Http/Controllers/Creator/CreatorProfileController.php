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
        
        // Pastikan username user dan store_slug selalu sinkron dengan store_slug yang valid
        if ($profile->exists && !empty($profile->store_slug) && $user->username !== $profile->store_slug) {
            $user->username = $profile->store_slug;
            $user->referral_code = $profile->store_slug;
            $user->saveQuietly();
        }

        return view('creator.profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // 1. Validasi & Penggantian Username (hanya boleh 1x dalam 1 bulan / 30 hari)
        if ($request->filled('username')) {
            $newUsername = strtolower(trim($request->input('username')));

            if ($newUsername !== strtolower($user->username ?? '')) {
                if (!$user->canChangeUsername()) {
                    return back()->withErrors([
                        'username' => 'Mengganti username hanya boleh 1x dalam 1 bulan. Anda dapat menggantinya kembali pada tanggal ' . $user->nextUsernameChangeDate() . ' (' . $user->daysUntilUsernameChange() . ' hari lagi).'
                    ])->withInput();
                }

                $request->validate([
                    'username' => [
                        'required',
                        'string',
                        'min:3',
                        'max:30',
                        'regex:/^[a-z0-9._]+$/',
                        'unique:users,username,' . $user->id,
                    ],
                ], [
                    'username.required' => 'Username wajib diisi.',
                    'username.min'      => 'Username minimal 3 karakter.',
                    'username.max'      => 'Username maksimal 30 karakter.',
                    'username.regex'    => 'Username hanya boleh huruf kecil, angka, titik, dan garis bawah (_).',
                    'username.unique'   => 'Username ini sudah digunakan akun lain.',
                ]);

                // Pastikan tidak tabrakan dengan store_slug creator lain
                $slugConflict = CreatorProfile::where('store_slug', $newUsername)
                    ->where('user_id', '!=', $user->id)
                    ->exists();
                if ($slugConflict) {
                    return back()->withErrors([
                        'username' => 'Username ini sudah dipakai oleh creator lain sebagai URL toko.'
                    ])->withInput();
                }

                // Update username & waktu perubahan
                $user->username = $newUsername;
                $user->username_changed_at = now();
                if (!empty($user->referral_code)) {
                    $user->referral_code = $newUsername;
                }
                $user->save();

                // Singkronkan turunannya secara otomatis:
                // a. Singkronkan kode media partner jika akun ini terdaftar sebagai Media Partner
                \App\Models\MediaPartner::where('user_id', $user->id)->update([
                    'code' => $newUsername
                ]);
            }
        }

        // Slug toko selalu otomatis mengikuti username akun secara terpusat
        $storeSlug = $user->username ?: ($request->filled('store_slug') ? \Illuminate\Support\Str::slug($request->store_slug) : \Illuminate\Support\Str::slug($request->store_name ?: 'store'));
        $request->merge(['store_slug' => $storeSlug]);

        // 2. Validasi Akun Sosial Media (Wajib diisi minimal 1 dan maksimal 3 akun)
        $rawSocial = $request->input('social_links', []);
        $filteredSocial = is_array($rawSocial) ? array_filter($rawSocial, function ($val) {
            return !is_null($val) && trim((string) $val) !== '';
        }) : [];

        if (count($filteredSocial) < 1) {
            return back()->withErrors([
                'social_links' => 'Akun media sosial wajib diisi minimal 1 akun utama (misal: WhatsApp, Instagram, atau TikTok).'
            ])->withInput();
        }

        if (count($filteredSocial) > 3) {
            return back()->withErrors([
                'social_links' => 'Akun media sosial maksimal hanya diperbolehkan 3 akun.'
            ])->withInput();
        }

        // 3. Fallback nama provinsi & kota dari profil yang tersimpan jika form select tidak terpicu ulang
        $existingProfile = $user->creatorProfile;
        if (empty($request->province_name) && !empty($existingProfile?->province_name)) {
            $request->merge(['province_name' => $existingProfile->province_name]);
        }
        if (empty($request->city_name) && !empty($existingProfile?->city_name)) {
            $request->merge(['city_name' => $existingProfile->city_name]);
        }
        if (empty($request->address) && !empty($existingProfile?->address)) {
            $request->merge(['address' => $existingProfile->address]);
        }

        $request->validate([
            'store_name' => 'nullable|string|max:100',
            'store_slug' => 'required|string|max:100|regex:/^[a-z0-9\._\-]+$/|unique:creator_profiles,store_slug,' . ($user->creatorProfile->id ?? 'NULL'),
            'store_description' => 'required|string|min:40|max:300',
            'creator_type' => 'nullable|string|max:100',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|string|max:255',
            'address' => 'required|string|min:5|max:255',
            'province_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'raja_city_id' => 'nullable|string|max:20',
            'subdistrict_id' => 'nullable|integer',
            'raja_district_id' => 'nullable|string|max:20',
            'province_name' => 'required|string|max:100',
            'city_name' => 'required|string|max:100',
            'subdistrict_name' => 'nullable|string|max:100',
            'village_name' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|string|max:50',
            'longitude' => 'nullable|string|max:50',
            'detected_ip' => 'nullable|string|max:50',
            'meta_title' => 'nullable|string|max:70',
            'meta_desc' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:10240',
            'store_banner_1' => 'nullable|image|max:10240',
            'store_banner_2' => 'nullable|image|max:10240',
        ], [
            'store_description.required' => 'Deskripsi toko wajib diisi (minimal 40 karakter).',
            'store_description.min' => 'Deskripsi toko minimal 40 karakter.',
            'store_description.max' => 'Deskripsi toko maksimal 300 karakter.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'address.min' => 'Alamat lengkap minimal 5 karakter.',
            'province_name.required' => 'Provinsi wajib dipilih.',
            'city_name.required' => 'Kabupaten / Kota wajib dipilih.',
        ]);

        $detectedIp = $request->input('detected_ip') ?: $request->ip();

        $profile = CreatorProfile::updateOrCreate(
            ['user_id' => $user->id],
            array_merge(
                $request->only([
                    'store_name', 'store_slug', 'store_description',
                    'creator_type',
                    'address', 'province_id', 'city_id', 'raja_city_id',
                    'subdistrict_id', 'raja_district_id',
                    'province_name', 'city_name', 'subdistrict_name', 'village_name', 'postal_code',
                    'latitude', 'longitude',
                    'meta_title', 'meta_desc', 'meta_keywords'
                ]),
                [
                    'detected_ip'  => $detectedIp,
                    'social_links' => array_filter($request->input('social_links', []))
                ]
            )
        );

        // Server-side IP Geolocation fallback if lat/lon is missing
        if (empty($profile->latitude) || empty($profile->longitude)) {
            $userIp = $detectedIp;
            if (!empty($userIp) && $userIp !== '127.0.0.1') {
                try {
                    $ipResponse = \Illuminate\Support\Facades\Http::timeout(3)->get("http://ip-api.com/json/{$userIp}")->json();
                    if ($ipResponse && isset($ipResponse['status']) && $ipResponse['status'] === 'success') {
                        $profile->latitude = (string)($ipResponse['lat'] ?? '');
                        $profile->longitude = (string)($ipResponse['lon'] ?? '');
                        if (empty($profile->city_name)) $profile->city_name = $ipResponse['city'] ?? null;
                        if (empty($profile->province_name)) $profile->province_name = $ipResponse['regionName'] ?? null;
                        $profile->save();
                    }
                } catch (\Exception $e) {
                    // Ignore background IP lookup errors
                }
            }
        }

        // Handle is_store_active toggle setting in bio_config
        $bioConfig = $profile->bio_config ?? [];
        $bioConfig['is_store_active'] = $request->has('is_store_active') ? $request->boolean('is_store_active') : false;
        // Sync WhatsApp from social_links to bio_config for backward compat
        $socialLinks = $request->input('social_links', []);
        if (!empty($socialLinks['wa'])) {
            $bioConfig['wa'] = preg_replace('/^0+/', '', $socialLinks['wa']);
        } else {
            unset($bioConfig['wa']);
        }
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
