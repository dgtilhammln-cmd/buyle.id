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

        // Auto-heal: If custom_product missing product_id or product model, create Product entry now
        if (!$product && in_array($block->type, ['custom_product', 'buyle_product'])) {
            $baseSlug = ($block->data_json['slug'] ?? \Illuminate\Support\Str::slug($block->title)) ?: 'produk';
            $slug     = $baseSlug;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . \Illuminate\Support\Str::random(4);
            }
            $stock    = isset($block->data_json['stock']) && $block->data_json['stock'] !== '' && $block->data_json['stock'] !== null ? (int)$block->data_json['stock'] : null;
            $sellerId = $profile->user_id;
            $product  = Product::create([
                'seller_id'    => $sellerId,
                'name'         => $block->title,
                'slug'         => $slug,
                'price'        => $block->data_json['price'] ?? 0,
                'stock'        => $stock,
                'description'  => $block->data_json['description'] ?? '',
                'image'        => !empty($block->data_json['images'][0]) ? $block->data_json['images'][0] : ($block->data_json['image'] ?? null),
                'is_active'    => true,
                'product_type' => 'external_link',
            ]);
            $data = $block->data_json ?? [];
            $data['product_id'] = $product->id;
            $block->data_json = $data;
            $block->save();
        }

        $config = $profile->bio_config ?? [];
        $theme  = $profile->bio_theme ?? 'theme1';

        return view('bio.product_show', compact('profile', 'block', 'config', 'theme', 'username', 'product'));
    }
}