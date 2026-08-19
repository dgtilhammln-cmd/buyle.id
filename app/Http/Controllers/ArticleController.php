<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()->latest()->paginate(9);

        $categories = Cache::remember('articles.categories', 3600, function () {
            return Article::published()
                ->select('category')
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category');
        });

        $popular = Cache::remember('articles.popular', 3600, function () {
            return Article::published()->orderByDesc('views')->limit(5)->get();
        });

        $settings = Setting::getAllAsArray();
        $siteName = $settings['site_name'] ?? 'buyle.id';

        $currentPage = request()->get('page', 1);
        $seo = [
            'title'       => ($currentPage > 1)
                ? ($settings['meta_title_articles'] ?? 'Artikel & Tips | ' . $siteName) . ' — Halaman ' . $currentPage
                : ($settings['meta_title_articles'] ?? 'Artikel & Tips | ' . $siteName),
            'description' => $settings['meta_desc_articles']  ?? 'Kumpulan artikel informatif, tips, dan panduan seputar produk buyle.id tangga.',
            'keywords'    => $settings['meta_keywords_articles'] ?? 'artikel buyle.id, tips rumah, panduan produk',
            'og_image'    => !empty($settings['og_image_default']) ? asset('storage/'.$settings['og_image_default']) : asset('images/og-default.jpg'),
            // Canonical per-page: page 1 = clean URL, page 2+ = with ?page=N
            'canonical'   => $currentPage > 1
                ? route('articles') . '?page=' . $currentPage
                : route('articles'),
            // Paginated pages beyond 1 should not be independently indexed (canonical handles it)
            'robots'      => $currentPage > 1 ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
        ];

        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('home')],
            ['name' => 'Artikel', 'url' => route('articles')],
        ];

        return view('articles.index', compact('articles', 'categories', 'popular', 'settings', 'seo', 'breadcrumbs'));
    }

    public function show(string $slug)
    {
        $article = Cache::remember("article.{$slug}", 3600, function () use ($slug) {
            return Article::published()
                ->with(['authorRel'])
                ->where('slug', $slug)
                ->firstOrFail();
        });

        $article->incrementViews();

        $related = Cache::remember("article.related.{$article->id}", 3600, function () use ($article) {
            $q   = Article::published()->where('id', '!=', $article->id);
            $rel = (clone $q)->where('category', $article->category)->latest()->limit(3)->get();
            if ($rel->count() < 3) {
                $rel = $q->latest()->limit(3)->get();
            }
            return $rel;
        });

        $settings = Setting::getAllAsArray();
        $siteName = $settings['site_name'] ?? 'buyle.id';
        $appUrl   = rtrim(config('app.url'), '/');

        $ogImg = $article->getRawOriginal('og_image')
            ? $appUrl . '/storage/' . $article->getRawOriginal('og_image')
            : ($article->getRawOriginal('image')
                ? $appUrl . '/storage/' . $article->getRawOriginal('image')
                : (!empty($settings['og_image_default'])
                    ? $appUrl . '/storage/' . $settings['og_image_default']
                    : $appUrl . '/images/og-default.jpg'));

        $wordCount = str_word_count(strip_tags($article->content ?? ''));
        $readTime  = max(1, (int) ceil($wordCount / 200));

        $seo = [
            'title'             => $article->meta_title ?: ($article->title . ' | ' . $siteName),
            'description'       => $article->meta_desc  ?: Str::limit(strip_tags($article->excerpt ?? $article->content ?? ''), 155),
            'keywords'          => $article->meta_keywords ?: ($settings['meta_keywords_articles'] ?? 'artikel buyle.id, tips rumah, panduan produk'),
            'og_image'          => $ogImg,
            'canonical'         => route('articles.show', ['slug' => $slug]),
            'og_type'           => 'article',
            'article_published' => $article->published_at?->toIso8601String(),
            'article_modified'  => $article->updated_at->toIso8601String(),
            'article_author'    => $article->authorRel?->name ?? 'Tim ' . $siteName,
            'article_section'   => $article->category ?? 'Artikel',
            'robots'            => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
        ];

        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('home')],
            ['name' => 'Artikel', 'url' => route('articles')],
            ['name' => $article->title, 'url' => route('articles.show', ['slug' => $slug])],
        ];

        $authorData = ['@type' => 'Organization', 'name' => 'Tim ' . $siteName];
        if ($article->authorRel) {
            $authorData = [
                '@type' => 'Person',
                'name'  => $article->authorRel->name,
            ];
            if ($article->authorRel->photo) {
                $authorData['image'] = $appUrl . '/storage/' . $article->authorRel->photo;
            }
        }

        $faqItems = is_array($article->faqs) ? $article->faqs : [];
        $validFaqs = array_filter($faqItems, fn($f) => !empty($f['q']) && !empty($f['a']));

        $schemas = [
            [
                '@context'         => 'https://schema.org',
                '@type'            => 'Article',
                'headline'         => $article->title,
                'description'      => $article->excerpt,
                'image'            => $ogImg,
                'datePublished'    => $article->published_at?->toIso8601String(),
                'dateModified'     => $article->updated_at->toIso8601String(),
                'wordCount'        => $wordCount,
                'articleSection'   => $article->category ?? 'Artikel',
                'inLanguage'       => 'id-ID',
                'author'           => $authorData,
                'publisher'        => [
                    '@type'  => 'Organization',
                    'name'   => $siteName,
                    '@id'    => $appUrl . '/#organization',
                    'logo'   => ['@type' => 'ImageObject', 'url' => $appUrl . '/images/logo.png'],
                ],
                'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => route('articles.show', ['slug' => $slug])],
                'url'              => route('articles.show', ['slug' => $slug]),
            ],
        ];

        if (!empty($validFaqs)) {
            $schemas[] = [
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => array_values(array_map(fn($f) => [
                    '@type'          => 'Question',
                    'name'           => $f['q'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
                ], $validFaqs)),
            ];
        }

        $schema = json_encode(
            ['@context' => 'https://schema.org', '@graph' => array_map(
                fn($s) => array_diff_key($s, ['@context' => '']),
                $schemas
            )],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        // For view compatibility, pass $trans as alias to $article
        $trans = $article;
        $locale = 'id';

        return view('articles.show', compact('article', 'trans', 'related', 'settings', 'seo', 'schema', 'breadcrumbs', 'readTime', 'wordCount', 'locale'));
    }
}
