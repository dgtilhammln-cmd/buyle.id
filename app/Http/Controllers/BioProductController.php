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
            if ($product && !$product->is_active) {
                abort(404, 'Produk ini sedang tidak tersedia atau diarsipkan.');
            }
            if (!$product) {
                // Produk pernah ditautkan namun sudah dihapus dari database oleh creator.
                // Hapus blok bio terkait agar tidak menimbulkan inkonsistensi. JANGAN auto-recreate!
                $block->delete();
                abort(404, 'Produk ini telah dihapus oleh penjual.');
            }
        } else {
            // Jika blok belum memiliki product_id di data_json
            $pSlug = $block->data_json['slug'] ?? null;
            if ($pSlug) {
                $product = Product::where('seller_id', $profile->user_id)->where('slug', $pSlug)->first();
            }
            if (!$product) {
                abort(404, 'Produk tidak ditemukan atau telah dihapus.');
            }
            if (!$product->is_active) {
                abort(404, 'Produk ini sedang tidak tersedia atau diarsipkan.');
            }
        }

        // ── AFFILIATE TRACKING: Rekam identitas affiliator ke Session & Cookie 30 hari ──
        $affiliateUserId = null;
        if ($product && (int)$profile->user_id !== (int)$product->seller_id) {
            $affiliateUserId = $profile->user_id;
            session(['buyle_aff_' . $product->id => $affiliateUserId]);
            cookie()->queue('buyle_aff_' . $product->id, $affiliateUserId, 60 * 24 * 30);
        }

        // ── REDIRECT KE SLUG ASLI BUYLE (/tix/{slug} atau /produk/{slug}) ──
        // Mengarahkan ke core marketplace Buyle yang paling up-to-date
        if ($product && !empty($product->slug)) {
            $isTicket = method_exists($product, 'isTicketProduct')
                ? $product->isTicketProduct()
                : (($product->product_type ?? '') === 'ticket');

            $targetRoute = $isTicket ? 'tix.show' : 'products.show';
            $queryParams = request()->query();

            return redirect()->route($targetRoute, array_merge(['slug' => $product->slug], $queryParams));
        }

        $config = $profile->bio_config ?? [];
        $theme  = $profile->bio_theme ?? 'theme5';
        $blocks = $profile->bioBlocks;
        $products = CreatorBioBlock::where('creator_id', $profile->id)
            ->where('is_active', true)
            ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
            ->get();

        // Route to theme-specific product show view
        // Theme 5 is exclusive and has its own product show with its own header/footer
        // Themes 1-4 use the shared product_show view (no theme5 header)
        $view = ($theme === 'theme5') ? 'bio.theme5.product_show' : 'bio.product_show';

        return view($view, compact('profile', 'block', 'config', 'theme', 'username', 'product', 'blocks', 'products', 'affiliateUserId'));
    }
}