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

class HomeController extends Controller
{
    public function index()
    {
        $settings     = Setting::getAllAsArray();
        $products     = Product::marketplace()->latest()->limit(6)->get();
        $allProducts  = Product::marketplace()->latest()->limit(30)->get();
        
        // Return empty collections for legacy sections to prevent view crashes
        $gallery      = collect();
        $articles     = Article::published()->with('translations')->latest()->limit(4)->get();
        
        // Fetch ONLY Ticket, Event, Workshop, Wisata & Webinar Products (10 items)
        $ticketProducts = Product::marketplace()
            ->with(['seller.creatorProfile', 'category'])
            ->where(function($q) {
                $q->whereHas('category', function($catQ) {
                    $catQ->where('name', 'LIKE', '%tik%')
                         ->orWhere('name', 'LIKE', '%event%')
                         ->orWhere('name', 'LIKE', '%wisata%')
                         ->orWhere('name', 'LIKE', '%webinar%')
                         ->orWhere('name', 'LIKE', '%workshop%')
                         ->orWhere('slug', 'LIKE', '%tik%')
                         ->orWhere('slug', 'LIKE', '%event%')
                         ->orWhere('slug', 'LIKE', '%wisata%')
                         ->orWhere('slug', 'LIKE', '%webinar%');
                })
                ->orWhere('product_type', 'LIKE', '%ticket%')
                ->orWhere('product_type', 'LIKE', '%event%')
                ->orWhere('name', 'LIKE', '%tiket%')
                ->orWhere('name', 'LIKE', '%event%')
                ->orWhere('name', 'LIKE', '%konser%')
                ->orWhere('name', 'LIKE', '%wisata%')
                ->orWhere('name', 'LIKE', '%webinar%')
                ->orWhere('name', 'LIKE', '%workshop%');
            })
            ->latest()
            ->limit(10)
            ->get();

        $retargetProducts = Product::marketplace()
            ->with(['seller.creatorProfile', 'category'])
            ->latest()
            ->limit(12)
            ->get();

        // Realtime Produk Terlaris berdasarkan data order_items (total_qty_sold) & sold_count
        $bestsellerProducts = Product::marketplace()
            ->with(['seller.creatorProfile', 'category'])
            ->withCount(['orderItems as total_qty_sold' => function($q) {
                $q->select(\Illuminate\Support\Facades\DB::raw('COALESCE(SUM(qty), 0)'));
            }])
            ->orderByDesc('sold_count')
            ->orderByDesc('total_qty_sold')
            ->latest()
            ->limit(12)
            ->get();

        // Produk Terbaru (10 items untuk 2 baris grid)
        $latestProducts = Product::marketplace()
            ->with(['seller.creatorProfile', 'category'])
            ->latest()
            ->limit(10)
            ->get();

        // Creator Terpopuler (hanya yang mengaktifkan toggle di profil creator & maksimal 10 items)
        $popularCreators = \App\Models\User::whereHas('creatorProfile')
            ->whereNotIn('role', ['admin', 'super_admin', 'admin_super'])
            ->where('name', 'NOT LIKE', '%copywriter%')
            ->whereDoesntHave('creatorProfile', function($q) {
                $q->where('store_name', 'LIKE', '%copywriter%')
                  ->orWhere('store_slug', 'LIKE', '%copywriter%');
            })
            ->with(['creatorProfile'])
            ->withCount('products')
            ->orderByRaw("
                CASE 
                    WHEN name LIKE '%HVM Digital%' OR username LIKE '%hvmdigital%' OR id IN (SELECT user_id FROM creator_profiles WHERE store_name LIKE '%HVM Digital%' OR store_slug LIKE '%hvmdigital%') THEN 1
                    WHEN name LIKE '%Ilham Maulana%' OR username LIKE '%ilham%' OR id IN (SELECT user_id FROM creator_profiles WHERE store_name LIKE '%Ilham Maulana%' OR store_slug LIKE '%ilham%') THEN 2
                    ELSE 3
                END ASC
            ")
            ->orderByDesc('products_count')
            ->latest()
            ->get()
            ->filter(fn($u) => $u->creatorProfile && $u->creatorProfile->isStoreActive())
            ->take(10)
            ->values();

        $clients      = collect();
        $testimonials = collect();
        $uspItems       = UspItem::active()->get();
        $categoryItems  = \App\Models\ProductCategory::active()
            ->with(['subCategories' => function($q) { $q->where('is_active', true)->orderBy('order'); }])
            ->orderBy('order')
            ->get();
        $promoSections  = PromoSection::active()->get();
        
        $wa           = WaSetting::primary();
        $heroSlides     = HeroSlide::active()->ordered()->where('position', 'hero')->get();
        $utamaBanners   = HeroSlide::active()->ordered()->where('position', 'utama')->limit(2)->get();
        $sampingBanners = HeroSlide::active()->ordered()->where('position', 'samping')->limit(2)->get();

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

