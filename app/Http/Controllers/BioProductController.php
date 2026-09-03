<?php

namespace App\Http\Controllers;

use App\Models\CreatorBioBlock;
use App\Models\CreatorProfile;
use Illuminate\Http\Request;

class BioProductController extends Controller
{
    public function show($username, $identifier)
    {
        $profile = CreatorProfile::where('store_slug', $username)->firstOrFail();

        // Search by block ID or slug inside data_json
        $block = CreatorBioBlock::where('creator_id', $profile->id)
            ->where('type', 'custom_product')
            ->where('is_active', true)
            ->where(function ($query) use ($identifier) {
                if (is_numeric($identifier)) {
                    $query->where('id', $identifier);
                } else {
                    $query->where('data_json->slug', $identifier)
                          ->orWhere('id', $identifier);
                }
            })
            ->firstOrFail();

        $config = $profile->bio_config ?? [];
        $theme  = $profile->bio_theme ?? 'theme1';

        return view('bio.product_show', compact('profile', 'block', 'config', 'theme', 'username'));
    }
}