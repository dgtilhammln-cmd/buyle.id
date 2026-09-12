<?php

namespace App\Http\Controllers;

use App\Models\CreatorBioBlock;
use App\Models\CreatorProfile;
use App\Models\Product;
use Illuminate\Http\Request;

class BioProductController extends Controller
{
    public function show($username, $identifier)
    {
        $profile = CreatorProfile::where('store_slug', $username)->firstOrFail();

        // Search by block ID, slug in data_json, or matching product slug
        $block = CreatorBioBlock::where('creator_id', $profile->id)
            ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
            ->where('is_active', true)
            ->where(function ($query) use ($identifier) {
                if (is_numeric($identifier)) {
                    $query->where('id', $identifier);
                } else {
                    $query->where('data_json->slug', $identifier)
                          ->orWhere('id', $identifier);
                }
            })
            ->first();

        if (!$block) {
            // Fallback: search by product slug or title slug
            $blocks = CreatorBioBlock::where('creator_id', $profile->id)
                ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
                ->where('is_active', true)
                ->get();

            foreach ($blocks as $b) {
                $bSlug = $b->data_json['slug'] ?? null;
                if (!$bSlug && !empty($b->data_json['product_id'])) {
                    $p = Product::find($b->data_json['product_id']);
                    if ($p && $p->slug === $identifier) {
                        $block = $b;
                        break;
                    }
                }
                if (!$bSlug) {
                    $bSlug = \Illuminate\Support\Str::slug($b->title);
                }
                if ($bSlug === $identifier) {
                    $block = $b;
                    break;
                }
            }
        }

        if (!$block) {
            abort(404);
        }

        $product = null;
        if (!empty($block->data_json['product_id'])) {
            $product = Product::find($block->data_json['product_id']);
        }

        $config = $profile->bio_config ?? [];
        $theme  = $profile->bio_theme ?? 'theme1';

        return view('bio.product_show', compact('profile', 'block', 'config', 'theme', 'username', 'product'));
    }
}