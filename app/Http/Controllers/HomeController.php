<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Product;
use App\Models\GalleryProject;
use App\Models\Article;
use App\Models\Client;
use App\Models\Testimonial;
use App\Models\WaSetting;
use App\Models\HeroSlide;
use App\Models\UspItem;
use App\Models\CategoryItem;
use App\Models\PromoSection;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Setting::getAllAsArray();

        // ── CACHE SEMUA DATA BERAT HOMEPAGE (TTL: 5 menit) ──
        [
            $products,
            $allProducts,
            $retargetProducts,
            $bestsellerProducts,
            $latestProducts,
            $ticketProducts,
            $popularCreators,
            $articles,
            $heroSlides,
            $utamaBanners,
            $sampingBanners,
            $uspItems,
            $categoryItems,
            $promoSections,
        ] = Cache::remember('home_page_data_v3', 300, function () {
            // Semua produk marketplace sekaligus — cukup 1 query besar, dibagi di PHP
            $allMp = Product::marketplace()
                ->with(['seller:id,name,username,avatar', 'seller.creatorProfile:id,user_id,store_name,store_slug,is_verified,store_banner_1', 'category:id,name,slug'])
                ->select(['id','name','slug','image','gallery','price','sale_price','stock','sold_count','views_count','rating','product_category_id','seller_id','product_type','is_active','created_at'])
                ->latest()
                ->limit(40)
                ->get();

            $products           = $allMp->take(6);
            $allProducts        = $allMp->take(30);
            $retargetProducts   = $allMp->take(12);
            $latestProducts     = $allMp->take(10);

            // Bestseller — sort by sold_count (already in query, no extra withCount)
            $bestsellerProducts = $allMp->sortByDesc('sold_count')->take(12)->values();

            // Ticket/Event produk — from subset of $allMp (already loaded)
            $ticketKeywords = ['tiket','event','konser','wisata','webinar','workshop','seminar'];
            $ticketProducts = $allMp->filter(function($p) use ($ticketKeywords) {
                $name = strtolower($p->name ?? '');
                $type = strtolower($p->product_type ?? '');
                $catName = strtolower($p->category->name ?? '');
                foreach ($ticketKeywords as $kw) {
                    if (str_contains($name, $kw) || str_contains($type, $kw) || str_contains($catName, $kw)) return true;
                }
                return false;
            })->take(10)->values();

            // Creator populer — 1 query dengan filter DB, tanpa PHP loop order-items per creator
            $popularCreators = \App\Models\User::whereHas('creatorProfile', function($q) {
                    $q->where('store_name', 'NOT LIKE', '%copywriter%')
                      ->where('store_slug', 'NOT LIKE', '%copywriter%');
                })
                ->where('is_active', true)
                ->whereNotIn('role', ['admin', 'super_admin', 'admin_super'])
                ->where('name', 'NOT LIKE', '%copywriter%')
                ->with(['creatorProfile:id,user_id,store_name,store_slug,is_verified,store_banner_1,bio_config'])
                ->withCount('products')
                ->orderByRaw("
                    CASE
                        WHEN username LIKE '%hvmdigital%' OR id IN (SELECT user_id FROM creator_profiles WHERE store_slug LIKE '%hvmdigital%') THEN 1
                        WHEN username LIKE '%ilham%' THEN 2
                        ELSE 3
                    END ASC
                ")
                ->orderByDesc('products_count')
                ->limit(10)
                ->get()
                ->filter(fn($u) => $u->creatorProfile && $u->creatorProfile->isStoreActive())
                ->values();

            $articles       = Article::published()->with('translations')->latest()->limit(4)->get();
            $heroSlides     = HeroSlide::active()->ordered()->where('position', 'hero')->get();
            $utamaBanners   = HeroSlide::active()->ordered()->where('position', 'utama')->limit(2)->get();
            $sampingBanners = HeroSlide::active()->ordered()->where('position', 'samping')->limit(2)->get();
            $uspItems       = UspItem::active()->get();
            $categoryItems  = \App\Models\ProductCategory::active()
                ->with(['subCategories' => function($q) { $q->where('is_active', true)->orderBy('order'); }])
                ->orderBy('order')
                ->get();
            $promoSections  = PromoSection::active()->get();

            return [
                $products, $allProducts, $retargetProducts, $bestsellerProducts,
                $latestProducts, $ticketProducts, $popularCreators,
                $articles, $heroSlides, $utamaBanners, $sampingBanners,
                $uspItems, $categoryItems, $promoSections,
            ];
        });

        // Non-cacheable (user-specific atau selalu fresh)
        $gallery      = collect();
        $clients      = collect();
        $testimonials = collect();
        $wa           = WaSetting::primary();
        
        // (replaced by cached block above)

        $siteName = $settings['site_name'] ?? 'buyle.id';

        $seo = [
            'title'       => $settings['meta_title_home'] ?? $siteName . ' - The Multi-Creator Marketplace',
            'description' => $settings['meta_desc_home']  ?? 'Beli berbagai produk digital premium dengan mudah.',
            'keywords'    => $settings['meta_keywords_home'] ?? 'produk digital, marketplace, buyle.id',
            'og_image'    => !empty($settings['og_image_default']) ? asset('storage/'.$settings['og_image_default']) : (!empty($settings['logo']) ? asset('storage/'.$settings['logo']) : asset('favicon.ico')),
            'canonical'   => route('home'),
        ];

        return view('home.index', compact('settings', 'products', 'allProducts', 'retargetProducts', 'bestsellerProducts', 'latestProducts', 'popularCreators', 'gallery', 'articles', 'ticketProducts', 'clients', 'testimonials', 'wa', 'seo', 'heroSlides', 'utamaBanners', 'sampingBanners', 'uspItems', 'categoryItems', 'promoSections'));
    }
}

