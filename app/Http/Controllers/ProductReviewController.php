<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    /**
     * Get paginated reviews for a product (JSON for lazy load pagination).
     */
    public function index($slug, Request $request)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            // Also try finding by numeric ID if needed
            $product = is_numeric($slug) ? Product::find($slug) : null;
        }

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        }

        $query = ProductRating::with('user')
            ->where('product_id', $product->id)
            ->where('is_approved', true);

        // Filter: with media / photos only
        if ($request->filled('filter') && $request->filter === 'media') {
            $query->whereNotNull('review_images');
        } elseif ($request->filled('filter') && in_array((int)$request->filter, [1, 2, 3, 4, 5])) {
            $query->where('rating', (int)$request->filter);
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'highest') {
            $query->orderByDesc('rating')->orderByDesc('created_at');
        } elseif ($sort === 'lowest') {
            $query->orderBy('rating')->orderByDesc('created_at');
        } else {
            // Priority: reviews with text and photos first, then by latest
            $query->orderByRaw('CASE WHEN review_images IS NOT NULL THEN 1 WHEN review_text IS NOT NULL THEN 2 ELSE 3 END ASC')
                  ->orderByDesc('created_at');
        }

        $perPage = (int) $request->input('per_page', 6);
        $paginator = $query->paginate($perPage);

        // Format items
        $reviews = $paginator->getCollection()->map(function ($r) {
            $imageUrls = [];
            if (is_array($r->review_images)) {
                foreach ($r->review_images as $img) {
                    $imageUrls[] = asset('storage/' . ltrim($img, '/'));
                }
            }

            return [
                'id'            => $r->id,
                'rating'        => (int) $r->rating,
                'review_text'   => $r->review_text,
                'review_images' => $imageUrls,
                'display_name'  => $r->display_name,
                'avatar_url'    => $r->avatar_url,
                'created_at'    => $r->created_at ? $r->created_at->diffForHumans() : 'Baru saja',
                'date'          => $r->created_at ? $r->created_at->translatedFormat('d M Y') : date('d M Y'),
                'is_verified'   => true,
            ];
        });

        // Compute overall statistics
        $allRatings = ProductRating::where('product_id', $product->id)
            ->where('is_approved', true)
            ->get();

        $totalCount = $allRatings->count();
        $avgScore   = $totalCount > 0 ? round($allRatings->avg('rating'), 1) : 0;
        $mediaCount = $allRatings->filter(fn($r) => !empty($r->review_images))->count();

        $breakdown = [
            5 => $allRatings->where('rating', 5)->count(),
            4 => $allRatings->where('rating', 4)->count(),
            3 => $allRatings->where('rating', 3)->count(),
            2 => $allRatings->where('rating', 2)->count(),
            1 => $allRatings->where('rating', 1)->count(),
        ];

        return response()->json([
            'success'      => true,
            'data'         => $reviews,
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'total'        => $paginator->total(),
            'has_more'     => $paginator->hasMorePages(),
            'stats'        => [
                'average'          => $avgScore,
                'total_count'      => $totalCount,
                'with_media_count' => $mediaCount,
                'breakdown'        => $breakdown,
            ],
        ]);
    }
}
