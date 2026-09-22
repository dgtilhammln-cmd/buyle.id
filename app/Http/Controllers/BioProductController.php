<?php

namespace App\Http\Controllers;

use App\Models\CreatorBioBlock;
use App\Models\CreatorProfile;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BioProductController extends Controller
{
    public function show($username, $identifier)
    {
        $profile = CreatorProfile::where('store_slug', $username)->firstOrFail();

        // 1. Primary lookup by block ID or data_json->slug
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

        // 2. Lookup via linked Product model slug (e.g. e-testgo-cbt-digital)
        if (!$block && !is_numeric($identifier)) {
            $productBySlug = Product::where('slug', $identifier)->first();
            if ($productBySlug) {
                $block = CreatorBioBlock::where('creator_id', $profile->id)
                    ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
                    ->where('is_active', true)
                    ->where(function ($q) use ($productBySlug) {
                        $q->where('data_json->product_id', $productBySlug->id)
                          ->orWhere('data_json->product_id', (string)$productBySlug->id);
                    })
                    ->first();

                if (!$block) {
                    // Try finding block by title matching product name
                    $block = CreatorBioBlock::where('creator_id', $profile->id)
                        ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
                        ->where('is_active', true)
                        ->where(function ($q) use ($productBySlug) {
                            $q->where('title', 'LIKE', '%' . $productBySlug->name . '%')
                              ->orWhere('data_json->title', 'LIKE', '%' . $productBySlug->name . '%');
                        })
                        ->first();
                }

                // 2b. Product exists but NO linked block — auto-create block from Product
                if (!$block && $productBySlug->seller_id === $profile->user_id) {
                    $block = new CreatorBioBlock();
                    $block->creator_id = $profile->id;
                    $block->type       = 'buyle_product';
                    $block->is_active  = true;
                    $block->title      = $productBySlug->name;
                    $block->data_json  = [
                        'product_id'  => $productBySlug->id,
                        'slug'        => $productBySlug->slug,
                        'price'       => $productBySlug->price,
                        'description' => $productBySlug->description ?? '',
                        'image'       => $productBySlug->image ?? null,
                    ];
                    $block->save();
                }
            }
        }

        // 3. Fallback iteration over creator's bio blocks
        if (!$block) {
            $blocks = CreatorBioBlock::where('creator_id', $profile->id)
                ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
                ->where('is_active', true)
                ->get();

            foreach ($blocks as $b) {
                $bData = is_array($b->data_json) ? $b->data_json : (json_decode($b->data_json ?? '[]', true) ?: []);

                // Check if linked product has matching slug
                if (!empty($bData['product_id'])) {
                    $p = Product::find($bData['product_id']);
                    if ($p && ($p->slug === $identifier || Str::slug($p->name) === $identifier)) {
                        $block = $b;
                        break;
                    }
                }

                // Match data_json slug
                $bSlug = $bData['slug'] ?? null;
                if ($bSlug === $identifier) {
                    $block = $b;
                    break;
                }

                // Match by block title column
                $titleSlug = Str::slug($b->title ?? '');
                if ($titleSlug && $titleSlug === $identifier) {
                    $block = $b;
                    break;
                }

                // Match by data_json title or name
                $djTitle = $bData['title'] ?? $bData['name'] ?? null;
                if ($djTitle) {
                    $djTitleSlug = Str::slug($djTitle);
                    if ($djTitleSlug && $djTitleSlug === $identifier) {
                        $block = $b;
                        break;
                    }
                }

                // Prefix / substring matching (minimum 5 chars to avoid false positives)
                if (!is_numeric($identifier) && $titleSlug && strlen($titleSlug) >= 5) {
                    if (Str::startsWith($titleSlug, $identifier) || Str::startsWith($identifier, $titleSlug)) {
                        $block = $b;
                        break;
                    }
                }
            }

            // Auto-persist slug into data_json so next lookup hits Step 1 fast
            if ($block) {
                $dj = $block->data_json ?? [];
                if (empty($dj['slug'])) {
                    $dj['slug'] = $identifier;
                    $block->data_json = $dj;
                    $block->save();
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
            $baseSlug = ($block->data_json['slug'] ?? Str::slug($block->title ?? '')) ?: 'produk';
            $slug     = $baseSlug;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . Str::random(4);
            }
            $stock    = isset($block->data_json['stock']) && $block->data_json['stock'] !== '' && $block->data_json['stock'] !== null ? (int)$block->data_json['stock'] : null;
            $sellerId = $profile->user_id;
            $catLower = strtolower($block->data_json['category'] ?? '');
            $resolvedProductType = 'digital';
            if (in_array($catLower, ['barang', 'physical', 'fisik'])) {
                $resolvedProductType = 'physical';
            } elseif (in_array($catLower, ['jasa', 'service', 'layanan'])) {
                $resolvedProductType = 'service';
            } elseif (in_array($catLower, ['makanan', 'fnb', 'kuliner', 'food'])) {
                $resolvedProductType = 'makanan';
            } elseif ($catLower === 'ticket' || $catLower === 'tiket') {
                $resolvedProductType = 'ticket';
            }

            $product  = Product::create([
                'seller_id'    => $sellerId,
                'name'         => $block->title,
                'slug'         => $slug,
                'price'        => $block->data_json['price'] ?? 0,
                'stock'        => $stock,
                'description'  => $block->data_json['description'] ?? '',
                'image'        => !empty($block->data_json['images'][0]) ? $block->data_json['images'][0] : ($block->data_json['image'] ?? null),
                'is_active'    => true,
                'product_type' => $resolvedProductType,
            ]);
            $data = $block->data_json ?? [];
            $data['product_id'] = $product->id;
            $block->data_json = $data;
            $block->save();
        }

        $config = $profile->bio_config ?? [];
        $theme  = $profile->bio_theme ?? 'theme5';
        $blocks = $profile->bioBlocks;
        $products = CreatorBioBlock::where('creator_id', $profile->id)
            ->where('is_active', true)
            ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
            ->get();

        return view('bio.product_show', compact('profile', 'block', 'config', 'theme', 'username', 'product', 'blocks', 'products'));
    }
}