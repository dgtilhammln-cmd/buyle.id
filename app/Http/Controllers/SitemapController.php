<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CreatorProfile;
use App\Models\Article;
use App\Models\Author;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index(Request $request)
    {
        // Fetch data for sitemap
        $products = Product::where('is_active', true)->get(['id','slug','name','image','updated_at']);
        $creators = CreatorProfile::with('user')->get(['id','store_slug','store_name','updated_at', 'user_id']);
        $articles = Article::published()->latest()->get(['slug','title','image','updated_at']);
        $authors  = Author::whereNotNull('slug')->get(['slug','name','updated_at']);

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

        $urls    = array_merge($staticPages, $productUrls, $creatorUrls, $articleUrls, $authorUrls);
        $content = view('sitemap', compact('urls'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    // localeSitemap kept for backward compat but now just redirects to main sitemap
    public function localeSitemap(Request $request, string $sitemapLocale)
    {
        return redirect('/sitemap.xml', 301);
    }
}
