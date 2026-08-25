<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::published()->latest()->paginate(9);

        $categories = Cache::remember('faqs.categories', 3600, function () {
            return Faq::published()
                ->select('category')
                ->whereNotNull('category')
                ->distinct()
                ->pluck('category');
        });

        $popular = Cache::remember('faqs.popular', 3600, function () {
            return Faq::published()->orderByDesc('views')->limit(5)->get();
        });

        $settings = Setting::getAllAsArray();
        $siteName = $settings['site_name'] ?? 'buyle.id';

        $currentPage = request()->get('page', 1);
        $seo = [
            'title'       => ($currentPage > 1)
                ? ($settings['meta_title_faqs'] ?? 'Panduan & Tutorial Kreator | ' . $siteName) . ' — Halaman ' . $currentPage
                : ($settings['meta_title_faqs'] ?? 'Panduan & Tutorial Kreator | ' . $siteName),
            'description' => $settings['meta_desc_faqs']  ?? 'Panduan lengkap, tutorial teknis, dan dokumentasi resmi untuk kreator buyle.id. Pelajari cara upload produk, setting toko, dan memaksimalkan penjualan digital.',
            'keywords'    => $settings['meta_keywords_faqs'] ?? 'panduan kreator buyle.id, tutorial jual produk digital, cara daftar kreator, tips berjualan digital, dokumentasi buyle.id',
            'og_image'    => !empty($settings['og_image_default']) ? asset('storage/'.$settings['og_image_default']) : asset('images/og-default.jpg'),
            // Canonical per-page: page 1 = clean URL, page 2+ = with ?page=N
            'canonical'   => $currentPage > 1
                ? route('faqs') . '?page=' . $currentPage
                : route('faqs'),
            // Paginated pages beyond 1 should not be independently indexed (canonical handles it)
            'robots'      => $currentPage > 1 ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
        ];

        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('home')],
            ['name' => 'FAQ', 'url' => route('faqs')],
        ];

        return view('faqs.index', compact('faqs', 'categories', 'popular', 'settings', 'seo', 'breadcrumbs'));
    }

    public function show(string $slug)
    {
        $faq = Cache::remember("article.{$slug}", 3600, function () use ($slug) {
            return Faq::published()
                ->with(['authorRel'])
                ->where('slug', $slug)
                ->firstOrFail();
        });

        $faq->incrementViews();

        $related = Cache::remember("article.related.{$faq->id}", 3600, function () use ($faq) {
            $q   = Faq::published()->where('id', '!=', $faq->id);
            $rel = (clone $q)->where('category', $faq->category)->latest()->limit(3)->get();
            if ($rel->count() < 3) {
                $rel = $q->latest()->limit(3)->get();
            }
            return $rel;
        });

        $settings = Setting::getAllAsArray();
        $siteName = $settings['site_name'] ?? 'buyle.id';
        $appUrl   = rtrim(config('app.url'), '/');

        $ogImg = $faq->getRawOriginal('og_image')
            ? $appUrl . '/storage/' . $faq->getRawOriginal('og_image')
            : ($faq->getRawOriginal('image')
                ? $appUrl . '/storage/' . $faq->getRawOriginal('image')
                : (!empty($settings['og_image_default'])
                    ? $appUrl . '/storage/' . $settings['og_image_default']
                    : $appUrl . '/images/og-default.jpg'));

        $wordCount = str_word_count(strip_tags($faq->content ?? ''));
        $readTime  = max(1, (int) ceil($wordCount / 200));

        $seo = [
            'title'             => $faq->meta_title ?: ($faq->title . ' | ' . $siteName),
            'description'       => $faq->meta_desc  ?: Str::limit(strip_tags($faq->excerpt ?? $faq->content ?? ''), 155),
            'keywords'          => $faq->meta_keywords ?: ($settings['meta_keywords_faqs'] ?? 'artikel buyle.id, tips rumah, panduan produk'),
            'og_image'          => $ogImg,
            'canonical'         => route('faqs.show', ['slug' => $slug]),
            'og_type'           => 'article',
            'article_published' => $faq->published_at?->toIso8601String(),
            'article_modified'  => $faq->updated_at->toIso8601String(),
            'article_author'    => $faq->authorRel?->name ?? 'Tim ' . $siteName,
            'article_section'   => $faq->category ?? 'FAQ',
            'robots'            => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
        ];

        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('home')],
            ['name' => 'FAQ', 'url' => route('faqs')],
            ['name' => $faq->title, 'url' => route('faqs.show', ['slug' => $slug])],
        ];

        $authorData = ['@type' => 'Organization', 'name' => 'Tim ' . $siteName];
        if ($faq->authorRel) {
            $authorData = [
                '@type' => 'Person',
                'name'  => $faq->authorRel->name,
            ];
            if ($faq->authorRel->photo) {
                $authorData['image'] = $appUrl . '/storage/' . $faq->authorRel->photo;
            }
        }

        $faqItems = is_array($faq->faqs) ? $faq->faqs : [];
        $validFaqs = array_filter($faqItems, fn($f) => !empty($f['q']) && !empty($f['a']));

        $schemas = [
            [
                '@context'         => 'https://schema.org',
                '@type'            => 'TechArticle',
                'headline'         => $faq->title,
                'description'      => $faq->excerpt,
                'image'            => $ogImg,
                'datePublished'    => $faq->published_at?->toIso8601String(),
                'dateModified'     => $faq->updated_at->toIso8601String(),
                'wordCount'        => $wordCount,
                'articleSection'   => $faq->category ?? 'Tutorial Kreator',
                'inLanguage'       => 'id-ID',
                'author'           => $authorData,
                'publisher'        => [
                    '@type'  => 'Organization',
                    'name'   => $siteName,
                    '@id'    => $appUrl . '/#organization',
                    'logo'   => ['@type' => 'ImageObject', 'url' => $appUrl . '/images/logo.png'],
                ],
                'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => route('faqs.show', ['slug' => $slug])],
                'url'              => route('faqs.show', ['slug' => $slug]),
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

        // For view compatibility
        $article = $faq;
        $trans = $faq;
        $locale = 'id';

        return view('faqs.show', compact('article', 'faq', 'trans', 'related', 'settings', 'seo', 'schema', 'breadcrumbs', 'readTime', 'wordCount', 'locale'));
    }
}


