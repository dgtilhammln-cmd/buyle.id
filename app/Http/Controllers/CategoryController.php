<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Permanent 301 Redirect for legacy 2-level category URLs (/kategori/{categorySlug}/{subcategorySlug})
     * Redirects to clean single-level URL (/kategori/{subcategorySlug}) to prevent SEO duplicate content.
     */
    public function redirectLegacySubcategory($categorySlug, $subcategorySlug)
    {
        $queryString = request()->getQueryString();
        $targetUrl = route('category.show', ['slug' => $subcategorySlug]);
        if ($queryString) {
            $targetUrl .= '?' . $queryString;
        }
        return redirect($targetUrl, 301);
    }

    /**
     * Display the specified category or subcategory with clean single slug (/kategori/{slug}).
     */
    public function show($slug)
    {
        $subcategory = ProductSubCategory::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if ($subcategory) {
            $category = $subcategory->category;
        } else {
            $category = ProductCategory::where('slug', $slug)
                ->active()
                ->firstOrFail();
        }

        $query = Product::active()->ordered()->with(['seller.creatorProfile', 'category']);

        if ($subcategory) {
            $query->where('product_sub_category_id', $subcategory->id);
        } else {
            $query->where('product_category_id', $category->id);
        }
        
        // Sorting
        $sort = request('sort', 'terbaru');
        switch ($sort) {
            case 'termurah':
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'termahal':
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_az':
                $query->orderBy('name', 'asc');
                break;
            case 'terbaru':
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(24)->withQueryString();
        $categories = ProductCategory::active()
            ->orderBy('order')
            ->withCount(['products' => function($q) { $q->where('is_active', true); }])
            ->with(['subCategories' => function($q) {
                $q->where('is_active', true)
                  ->orderBy('order')
                  ->withCount(['products' => function($pq) { $pq->where('is_active', true); }]);
            }])
            ->get();

        $settings = \App\Models\Setting::getAllAsArray();
        $siteName = $settings['site_name'] ?? 'buyle.id';

        // Dynamic Meta Title, Description & Keywords for Categories & Subcategories
        if ($subcategory) {
            $seoTitle = 'Cari ' . $subcategory->name . ' di ' . $siteName . ' - Digital Creator Center';
            $seoDesc  = 'Temukan pilihan ' . $subcategory->name . ' terbaik dalam kategori ' . $category->name . ' di ' . $siteName . '. Dapatkan produk digital, template, dan layanan jasa berkualitas.';
            $seoKeywords = $subcategory->name . ', ' . $category->name . ', produk digital ' . $subcategory->name . ', ' . $siteName . ', digital creator marketplace';
        } else {
            $seoTitle = 'Cari ' . $category->name . ' di ' . $siteName . ' - Digital Creator Center';
            $seoDesc  = 'Cari dan beli produk ' . $category->name . ' terlengkap di ' . $siteName . '. Pilihan terbanyak produk digital, ebook, lisensi, dan layanan jasa dari kreator terpercaya.';
            $seoKeywords = $category->name . ', produk digital ' . $category->name . ', ' . $siteName . ', digital creator marketplace';
        }

        $ogImage = !empty($category->image) 
            ? asset('storage/' . $category->image) 
            : (!empty($settings['logo']) ? asset('storage/' . $settings['logo']) : asset('images/og-default.jpg'));

        $seo = [
            'title'       => $seoTitle,
            'description' => $seoDesc,
            'keywords'    => $seoKeywords,
            'og_image'    => $ogImage,
            'canonical'   => url()->current(),
            'robots'      => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
        ];

        return view('categories.show', compact('category', 'subcategory', 'products', 'categories', 'seo', 'settings'));
    }
}
