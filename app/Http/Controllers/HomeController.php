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
        $products     = Product::active()->latest()->limit(6)->get();
        $allProducts  = Product::active()->latest()->limit(30)->get();
        
        // Return empty collections for legacy sections to prevent view crashes
        $gallery      = collect();
        $articles     = Article::published()->latest()->limit(4)->get();
        
        // Fetch Ticket & Event Products
        $ticketProducts = Product::active()
            ->with(['user.creatorProfile', 'category'])
            ->where(function($q) {
                $q->whereHas('category', function($catQ) {
                    $catQ->where('name', 'LIKE', '%tik%')
                         ->orWhere('name', 'LIKE', '%event%')
                         ->orWhere('slug', 'LIKE', '%tik%')
                         ->orWhere('slug', 'LIKE', '%event%');
                })->orWhere('name', 'LIKE', '%tiket%')
                  ->orWhere('name', 'LIKE', '%event%')
                  ->orWhere('name', 'LIKE', '%konser%');
            })
            ->latest()
            ->limit(4)
            ->get();

        if ($ticketProducts->count() < 4) {
            $needed = 4 - $ticketProducts->count();
            $existingIds = $ticketProducts->pluck('id')->toArray();
            $moreProds = Product::active()->with(['user.creatorProfile', 'category'])->whereNotIn('id', $existingIds)->latest()->limit($needed)->get();
            $ticketProducts = $ticketProducts->concat($moreProds);
        }

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

        return view('home.index', compact('settings', 'products', 'allProducts', 'gallery', 'articles', 'ticketProducts', 'clients', 'testimonials', 'wa', 'seo', 'heroSlides', 'utamaBanners', 'sampingBanners', 'uspItems', 'categoryItems', 'promoSections'));
    }
}

