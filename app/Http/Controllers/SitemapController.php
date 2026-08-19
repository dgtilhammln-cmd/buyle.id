<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Article;
use App\Models\Author;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index(Request $request)
    {
        // Single sitemap with image support for Google Image Search
        $services = \App\Models\Service::active()->ordered()->get(['id','slug','name','image','updated_at']);
        $articles = \App\Models\Article::published()->latest()->get(['slug','title','image','updated_at']);
        $authors  = \App\Models\Author::whereNotNull('slug')->get(['slug','name','updated_at']);

        $appUrl = rtrim(config('app.url'), '/');

        $staticPages = [
            ['url' => route('home'),     'priority' => '1.0', 'changefreq' => 'weekly',  'lastmod' => now()->toDateString(), 'images' => []],
            ['url' => route('about'),    'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => now()->toDateString(), 'images' => []],
            ['url' => route('products'), 'priority' => '0.9', 'changefreq' => 'weekly',  'lastmod' => now()->toDateString(), 'images' => []],
            ['url' => route('articles'), 'priority' => '0.8', 'changefreq' => 'daily',   'lastmod' => now()->toDateString(), 'images' => []],
            ['url' => route('contact'),  'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->toDateString(), 'images' => []],
        ];

        $serviceUrls = $services->map(function($s) use ($appUrl) {
            $images = [];
            if (!empty($s->image)) {
                $images[] = [
                    'loc'     => $appUrl . '/storage/' . ltrim($s->image, '/'),
                    'title'   => $s->name,
                    'caption' => $s->name,
                ];
            }
            return [
                'url'        => route('products.show', ['slug' => $s->slug]),
                'priority'   => '0.85',
                'changefreq' => 'monthly',
                'lastmod'    => $s->updated_at->toDateString(),
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

        $urls    = array_merge($staticPages, $serviceUrls, $articleUrls, $authorUrls);
        $content = view('sitemap', compact('urls'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    // localeSitemap kept for backward compat but now just redirects to main sitemap
    public function localeSitemap(Request $request, string $sitemapLocale)
    {
        return redirect('/sitemap.xml', 301);
    }
}
