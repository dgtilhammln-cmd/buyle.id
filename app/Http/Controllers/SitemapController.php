<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\CreatorProfile;
use App\Models\Article;
use App\Models\Author;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index(Request $request)
    {
        // Fetch data for sitemap
        $products     = Product::where('is_active', true)->get(['id','slug','name','image','updated_at']);
        $creators     = CreatorProfile::with('user')->get(['id','store_slug','store_name','updated_at', 'user_id']);
        $articles     = Article::published()->latest()->get();
        $authors      = Author::whereNotNull('slug')->get();
        $categories   = ProductCategory::active()->with('subCategories')->orderBy('order')->get();

        $appUrl = rtrim(config('app.url'), '/');

        $staticPages = [
            ['url' => route('home'),     'priority' => '1.0', 'changefreq' => 'weekly',  'lastmod' => now()->toDateString(), 'images' => []],
            ['url' => route('about'),    'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => now()->toDateString(), 'images' => []],
            ['url' => route('products'), 'priority' => '0.9', 'changefreq' => 'weekly',  'lastmod' => now()->toDateString(), 'images' => []],
            ['url' => route('articles'), 'priority' => '0.8', 'changefreq' => 'daily',   'lastmod' => now()->toDateString(), 'images' => []],
            ['url' => route('contact'),  'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->toDateString(), 'images' => []],
        ];

        $productUrls = $products->map(function($p) use ($appUrl) {
            $images = [];
            if (!empty($p->image)) {
                $images[] = [
                    'loc'     => $appUrl . '/storage/' . ltrim($p->image, '/'),
                    'title'   => $p->name,
                    'caption' => $p->name,
                ];
            }
            return [
                'url'        => route('products.show', ['slug' => $p->slug]),
                'priority'   => '0.85',
                'changefreq' => 'monthly',
                'lastmod'    => $p->updated_at ? $p->updated_at->toDateString() : now()->toDateString(),
                'images'     => $images,
            ];
        })->toArray();

        $creatorUrls = $creators->map(function($c) use ($appUrl) {
            $images = [];
            if ($c->user && !empty($c->user->avatar)) {
                $images[] = [
                    'loc'     => $appUrl . '/storage/' . ltrim($c->user->avatar, '/'),
                    'title'   => $c->store_name,
                    'caption' => $c->store_name,
                ];
            }
            return [
                'url'        => route('store.show', ['slug' => $c->store_slug]),
                'priority'   => '0.9',
                'changefreq' => 'weekly',
                'lastmod'    => $c->updated_at ? $c->updated_at->toDateString() : now()->toDateString(),
                'images'     => $images,
            ];
        })->toArray();

        $articleUrls = $articles->map(function($a) use ($appUrl) {
            $images = [];
            if (!empty($a->image)) {
                $images[] = [
                    'loc'     => $appUrl . '/storage/' . ltrim($a->image, '/'),
                    'title'   => $a->title,
                    'caption' => $a->title,
                ];
            }
            return [
                'url'        => route('articles.show', ['slug' => $a->slug]),
                'priority'   => '0.7',
                'changefreq' => 'monthly',
                'lastmod'    => $a->updated_at->toDateString(),
                'images'     => $images,
            ];
        })->toArray();

        $authorUrls = $authors->map(fn($a) => [
            'url'        => route('author.show', ['slug' => $a->slug]),
            'priority'   => '0.6',
            'changefreq' => 'monthly',
            'lastmod'    => $a->updated_at->toDateString(),
            'images'     => [],
        ])->toArray();

        // Category URLs (priority 0.9 — paling penting setelah beranda, strategi seperti Shopee/Tokopedia)
        $categoryUrls = [];
        foreach ($categories as $cat) {
            $categoryUrls[] = [
                'url'        => route('category.show', ['categorySlug' => $cat->slug]),
                'priority'   => '0.9',
                'changefreq' => 'weekly',
                'lastmod'    => $cat->updated_at ? $cat->updated_at->toDateString() : now()->toDateString(),
                'images'     => [],
            ];
            // Sub-kategori juga dimasukkan: /kategori/{slug}/{sub-slug}
            foreach ($cat->subCategories as $sub) {
                $categoryUrls[] = [
                    'url'        => route('category.show', ['categorySlug' => $cat->slug, 'subcategorySlug' => $sub->slug]),
                    'priority'   => '0.8',
                    'changefreq' => 'weekly',
                    'lastmod'    => $sub->updated_at ? $sub->updated_at->toDateString() : now()->toDateString(),
                    'images'     => [],
                ];
            }
        }

                $creatorBioUrls = [];
        $bioBlocks = \App\Models\CreatorBioBlock::where('type', 'custom_product')
            ->where('is_active', true)
            ->get();

        foreach ($creators as $c) {
            if (empty($c->store_slug)) continue;
            $creatorBioUrls[] = [
                'url'        => route('bio.public', ['username' => $c->store_slug]),
                'priority'   => '0.95',
                'changefreq' => 'daily',
                'lastmod'    => $c->updated_at ? $c->updated_at->toDateString() : now()->toDateString(),
                'images'     => [],
            ];
        }

        $creatorMap = $creators->keyBy('id');
        $customProductUrls = [];
        foreach ($bioBlocks as $b) {
            $creator = $creatorMap[$b->creator_id] ?? null;
            if (!$creator || empty($creator->store_slug)) continue;
            $slug = $b->data_json['slug'] ?? $b->id;
            $images = [];
            if (!empty($b->data_json['images'][0])) {
                $images[] = [
                    'loc'     => $appUrl . '/storage/' . ltrim($b->data_json['images'][0], '/'),
                    'title'   => $b->title,
                    'caption' => $b->title,
                ];
            }
            $customProductUrls[] = [
                'url'        => route('bio.product.show', ['username' => $creator->store_slug, 'identifier' => $slug]),
                'priority'   => '0.9',
                'changefreq' => 'weekly',
                'lastmod'    => $b->updated_at ? $b->updated_at->toDateString() : now()->toDateString(),
                'images'     => $images,
            ];
        }

        $urls    = array_merge($staticPages, $categoryUrls, $productUrls, $creatorUrls, $creatorBioUrls, $customProductUrls, $articleUrls, $authorUrls);
        $content = view('sitemap', compact('urls'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    // localeSitemap kept for backward compat but now just redirects to main sitemap
    public function localeSitemap(Request $request, string $sitemapLocale)
    {
        return redirect('/sitemap.xml', 301);
    }
}
