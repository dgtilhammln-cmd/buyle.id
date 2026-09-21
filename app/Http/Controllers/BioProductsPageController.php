<?php

namespace App\Http\Controllers;

use App\Models\CreatorProfile;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BioProductsPageController extends Controller
{
    public function index(Request $request, string $username)
    {
        $profile = CreatorProfile::with(['user', 'bioBlocks' => function ($q) {
            $q->where('is_active', true)
              ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
              ->orderBy('order', 'asc')
              ->orderBy('id', 'asc');
        }])->where('store_slug', $username)->firstOrFail();

        $config = $profile->bio_config ?? [];
        $blocks = $profile->bioBlocks;

        $allProducts = collect();

        foreach ($blocks as $block) {
            $data    = $block->data_json ?? [];
            $product = null;
            if (!empty($data['product_id'])) {
                $product = Product::find($data['product_id']);
            }

            $name  = $block->title ?? ($product->name ?? 'Produk');
            $price = $data['price'] ?? $data['custom_price'] ?? ($product ? ($product->price ?? 0) : 0);

            $image = null;
            if (!empty($data['images'][0]))              $image = $data['images'][0];
            elseif (!empty($data['image']))              $image = $data['image'];
            elseif ($product && !empty($product->image)) $image = $product->image;

            if ($image && !Str::startsWith($image, ['http://', 'https://']))
                $imageUrl = asset('storage/' . ltrim($image, '/'));
            else
                $imageUrl = $image ?: asset('images/buyle-placeholder.svg');

            $slug = $data['slug'] ?? null;
            if (!$slug && $product) $slug = $product->slug;
            if (!$slug) $slug = Str::slug($name) ?: (string)$block->id;

            if (!empty($profile->custom_domain))
                $productUrl = 'https://' . rtrim($profile->custom_domain, '/') . '/produk/' . $slug;
            else
                $productUrl = route('bio.product.show', ['username' => $username, 'identifier' => $slug]);

            $ratingVal = ($product && !empty($product->rating) && $product->rating > 0)
                ? number_format($product->rating, 1) : '5.0';

            $productType = $data['category'] ?? ($product->product_type ?? '');

            $salePrice = $data['sale_price'] ?? null;
            if (!$salePrice && $product && !empty($product->sale_price))
                $salePrice = $product->sale_price;

            $hasDiscount    = $salePrice && $salePrice < $price;
            $discountPct    = $hasDiscount ? round((($price - $salePrice) / $price) * 100) : 0;
            $effectivePrice = $hasDiscount ? $salePrice : $price;

            $allProducts->push([
                'block_id'        => $block->id,
                'product_id'      => $product->id ?? null,
                'name'            => $name,
                'slug'            => $slug,
                'image_url'       => $imageUrl,
                'price'           => $price,
                'sale_price'      => $salePrice,
                'effective_price' => $effectivePrice,
                'has_discount'    => $hasDiscount,
                'discount_pct'    => $discountPct,
                'product_url'     => $productUrl,
                'rating'          => $ratingVal,
                'product_type'    => $productType,
                'created_at'      => $block->created_at,
            ]);
        }

        $search = trim($request->get('q', ''));
        if (!empty($search)) {
            $allProducts = $allProducts->filter(function ($p) use ($search) {
                return Str::contains(strtolower($p['name']), strtolower($search));
            })->values();
        }

        $sort = $request->get('sort', 'terbaru');
        if ($sort === 'terlama') {
            $allProducts = $allProducts->sortBy('created_at')->values();
        } elseif ($sort === 'terpopuler') {
            $allProducts = $allProducts->sortByDesc(function ($p) {
                return [
                    (float)($p['rating'] ?? 5.0),
                    (int)($p['has_discount'] ? 1 : 0),
                    (int)($p['price'] > 0 ? 1 : 0),
                    $p['block_id']
                ];
            })->values();
        } else {
            $allProducts = $allProducts->sortByDesc('created_at')->values();
        }

        $bioName  = $config['name'] ?? $profile->store_name ?? $username;
        $seoTitle = 'Semua Produk - ' . $bioName . (!empty($profile->custom_domain) ? '' : ' | buyle.id');
        $seoDesc  = 'Temukan semua produk dan layanan dari ' . $bioName . '.';

        $canonical = !empty($profile->custom_domain)
            ? 'https://' . rtrim($profile->custom_domain, '/') . '/produk'
            : url('/' . $username . '/produk');

        $ogImage = asset('images/buyle-og.png');
        $products = $blocks;

        return view('bio.theme5.products_page', compact(
            'profile', 'config', 'username',
            'allProducts', 'search', 'sort',
            'seoTitle', 'seoDesc', 'canonical', 'ogImage', 'bioName',
            'blocks', 'products'
        ));
    }
}
