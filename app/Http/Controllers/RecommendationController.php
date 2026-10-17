<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RecommendationController extends Controller
{
    /**
     * Smart Dynamic Retargeting & Recommendations Engine
     * Generates 5 hyper-personalized product recommendations based on user browsing history.
     */
    public function getRetargetedProducts(Request $request): JsonResponse
    {
        $viewedIds   = array_filter(array_map('intval', (array)$request->input('viewed_ids', [])));
        $categoryIds = array_filter(array_map('intval', (array)$request->input('category_ids', [])));
        $sellerIds   = array_filter(array_map('intval', (array)$request->input('seller_ids', [])));

        $recommended = collect();

        // ── 1. RETARGETING: Produk yang baru saja dilihat pengguna (Maksimal 2 item untuk dorong checkout) ──
        if (!empty($viewedIds)) {
            $viewedProducts = Product::marketplace()
                ->whereIn('id', array_slice($viewedIds, 0, 4))
                ->with(['seller.creatorProfile', 'category'])
                ->get()
                ->sortBy(function ($p) use ($viewedIds) {
                    return array_search($p->id, $viewedIds);
                })
                ->take(2);

            $recommended = $recommended->merge($viewedProducts);
        }

        // ── 2. RELATED CATEGORY: Produk dalam kategori yang baru saja dijelajahi ──
        if (!empty($categoryIds) && $recommended->count() < 5) {
            $needed = 5 - $recommended->count();
            $existingIds = $recommended->pluck('id')->toArray();

            $categoryProducts = Product::marketplace()
                ->whereIn('product_category_id', $categoryIds)
                ->whereNotIn('id', array_merge($existingIds, $viewedIds))
                ->with(['seller.creatorProfile', 'category'])
                ->orderByDesc('rating')
                ->orderByDesc('sold_count')
                ->orderByDesc('views_count')
                ->limit($needed)
                ->get();

            $recommended = $recommended->merge($categoryProducts);
        }

        // ── 3. RELATED CREATOR: Produk dari Creator/Seller yang tokonya dikunjungi ──
        if (!empty($sellerIds) && $recommended->count() < 5) {
            $needed = 5 - $recommended->count();
            $existingIds = $recommended->pluck('id')->toArray();

            $creatorProducts = Product::marketplace()
                ->whereIn('seller_id', $sellerIds)
                ->whereNotIn('id', array_merge($existingIds, $viewedIds))
                ->with(['seller.creatorProfile', 'category'])
                ->orderByDesc('sold_count')
                ->limit($needed)
                ->get();

            $recommended = $recommended->merge($creatorProducts);
        }

        // ── 4. FALLBACK / COLD-START: Produk rating tertinggi & terlaris di Buyle ──
        if ($recommended->count() < 5) {
            $needed = 5 - $recommended->count();
            $existingIds = $recommended->pluck('id')->toArray();

            $fallbackProducts = Product::marketplace()
                ->whereNotIn('id', $existingIds)
                ->with(['seller.creatorProfile', 'category'])
                ->orderByDesc('rating')
                ->orderByDesc('sold_count')
                ->orderByDesc('views_count')
                ->latest()
                ->limit($needed)
                ->get();

            $recommended = $recommended->merge($fallbackProducts);
        }

        // Format to exact 5 clean card data
        $finalList = $recommended->unique('id')->take(5)->values()->map(function ($p) {
            $price      = $p->sale_price > 0 && $p->sale_price < $p->price ? $p->sale_price : $p->effective_price;
            $origPrice  = $p->sale_price > 0 && $p->sale_price < $p->price ? $p->price : null;
            $hasDisc    = !empty($origPrice) && $origPrice > $price;
            $discPct    = $hasDisc ? round((($origPrice - $price) / $origPrice) * 100) : 0;
            $isFree     = $price <= 0;

            $sellerCp   = $p->seller?->creatorProfile;
            $isVerified = $sellerCp ? (bool)$sellerCp->is_verified : false;
            $ratingVal  = $p->getEffectiveRating();
            $rating     = $ratingVal ? number_format((float)$ratingVal, 1, '.', '') : null;

            $image = $p->image ? asset('storage/' . ltrim($p->image, '/')) : asset('images/buyle-og.png');

            return [
                'id'                   => $p->id,
                'name'                 => $p->name,
                'slug'                 => $p->slug,
                'url'                  => route_locale('products.show', $p->slug),
                'image'                => $image,
                'price'                => $price,
                'price_formatted'      => $isFree ? 'GRATIS' : 'Rp' . number_format($price, 0, ',', '.'),
                'orig_price_formatted' => $hasDisc ? 'Rp' . number_format($origPrice, 0, ',', '.') : null,
                'is_free'              => $isFree,
                'has_discount'         => $hasDisc,
                'discount_pct'         => $discPct,
                'rating'               => $rating,
                'is_verified'          => $isVerified,
                'category_id'          => $p->product_category_id,
            ];
        });

        return response()->json([
            'status'   => 'success',
            'is_personalized' => !empty($viewedIds) || !empty($categoryIds),
            'products' => $finalList,
        ]);
    }
}
