<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\WaSetting;
use App\Models\Testimonial;
use App\Models\CreatorProfile;

class ServiceController extends Controller
{
    public function index()
    {
        $query = Product::active()->ordered();
        
        // Smart Keyword Search
        $suggestion        = null;
        $suggestionApplied = false;
        $foundCreators     = collect();

        if (request()->filled('q')) {
            $rawQ = trim(request('q'));

            // Search for Creators
            $foundCreators = CreatorProfile::where('store_name', 'like', '%' . $rawQ . '%')
                                ->orWhere('store_slug', 'like', '%' . $rawQ . '%')
                                ->with(['user.products' => function($q) {
                                    $q->active()->ordered()->take(4);
                                }])
                                ->get()
                                ->sortByDesc(function ($creator) use ($rawQ) {
                                    // Hitung kemiripan untuk mengurutkan yang paling relevan di atas
                                    similar_text(mb_strtolower($rawQ), mb_strtolower($creator->store_name), $pct);
                                    return $pct;
                                })->values();

            // Pass 1: Exact LIKE
            $exactQuery = (clone $query)->where(function($q) use ($rawQ) {
                $q->where('name', 'like', '%' . $rawQ . '%')
                  ->orWhere('description', 'like', '%' . $rawQ . '%')
                  ->orWhere('short_desc', 'like', '%' . $rawQ . '%');
            });

            if ($exactQuery->count() > 0) {
                $query = $exactQuery;
            } else {
                // Pass 2: Fuzzy fallback
                $allNames = Product::active()->pluck('name');
                [$bestKeyword, $bestScore] = $this->findBestMatch($rawQ, $allNames->toArray());

                if ($bestScore >= 40 && $bestKeyword) {
                    $suggestion = $bestKeyword;
                    $tokens     = $this->tokenize($bestKeyword);
                    $query->where(function ($q) use ($tokens, $rawQ) {
                        foreach ($tokens as $t) {
                            $q->orWhere('name',       'like', '%' . $t . '%')
                              ->orWhere('short_desc', 'like', '%' . $t . '%');
                        }
                        foreach ($this->tokenize($rawQ) as $t) {
                            $q->orWhere('name',       'like', '%' . $t . '%')
                              ->orWhere('short_desc', 'like', '%' . $t . '%');
                        }
                    });
                    $suggestionApplied = true;
                } else {
                    $query->whereRaw('0 = 1');
                }
            }
        }

        // Category filter (multiple) - support both 'category' and 'kategori' params
        $categoryParam = request()->filled('category') ? request('category') : request('kategori');
        if ($categoryParam) {
            $cats = is_array($categoryParam) ? $categoryParam : explode(',', $categoryParam);
            $cats = array_filter(array_map('trim', $cats));
            if (!empty($cats)) {
                $query->whereHas('category', function($q) use ($cats) {
                    $q->whereIn('slug', $cats);
                });
            }
        }

        // Price range filter
        if (request()->filled('price_min')) {
            $query->where(function($q) {
                $q->where('price', '>=', (float) request('price_min'))
                  ->orWhere(function($q2) { $q2->whereNull('price')->orWhere('price', 0); });
            });
        }
        if (request()->filled('price_max') && request('price_max') > 0) {
            $query->where(function($q) {
                $q->where('price', '<=', (float) request('price_max'))
                  ->orWhere(function($q2) { $q2->whereNull('price')->orWhere('price', 0); });
            });
        }

        // Type filter: produk / jasa
        if (request('type') === 'produk') {
            $query->where('price', '>', 0);
        } elseif (request('type') === 'jasa') {
            $query->where(function($q) { $q->whereNull('price')->orWhere('price', 0); });
        }

        // Sorting
        switch (request('sort', 'default')) {
            case 'price_asc':
                $query->orderByRaw('CASE WHEN price IS NULL OR price = 0 THEN 1 ELSE 0 END, price ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('CASE WHEN price IS NULL OR price = 0 THEN 1 ELSE 0 END, price DESC');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'name_az':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->ordered();
                break;
        }
        
        $services   = $query->get();
        $settings   = Setting::getAllAsArray();
        $siteName   = $settings['site_name'] ?? 'buyle.id';
        $wa         = WaSetting::primary();

        // Categories with counts
        $categories = \App\Models\ProductCategory::where('is_active', true)
            ->orderBy('order')
            ->withCount(['products' => function($q) { $q->where('is_active', true); }])
            ->get()
            ->map(function($cat) {
                $cat->services_count = $cat->products_count;
                return $cat;
            });

        // Price range for slider
        $maxPrice = Product::active()->where('price', '>', 0)->max('price') ?? 5000000;

        $hasFilters = request()->hasAny(['q', 'category', 'kategori', 'price_min', 'price_max', 'type', 'sort']);
        $seo = [
            'title'       => $settings['meta_title_services'] ?? 'Produk & Layanan | ' . $siteName,
            'description' => $settings['meta_desc_services']  ?? 'Temukan berbagai produk buyle.id tangga berkualitas dan layanan jasa profesional di ' . $siteName . '.',
            'keywords'    => $settings['meta_keywords_services'] ?? 'buyle.id tangga, produk, layanan jasa, pemasangan',
            'og_image'    => !empty($settings['og_image_default']) ? asset('storage/'.$settings['og_image_default']) : (!empty($settings['logo']) ? asset('storage/'.$settings['logo']) : asset('images/og-default.jpg')),
            'canonical'   => route('products'),
            'robots'      => $hasFilters ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
        ];

        $schema = json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'ItemList',
            'name'     => 'Produk & Layanan ' . $siteName,
            'url'      => route('products'),
            'itemListElement' => $services->map(function($s, $i) {
                return [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'name'     => $s->name,
                    'url'      => route('products.show', ['slug' => $s->slug]),
                ];
            })->toArray(),
        ]);

        return view('services.index', compact(
            'services', 'settings', 'seo', 'schema', 'categories', 'wa', 'maxPrice',
            'suggestion', 'suggestionApplied', 'foundCreators'
        ));
    }

    // ── Fuzzy Search Helpers ────────────────────────────────────────
    private function findBestMatch(string $query, array $names): array
    {
        $queryLower = mb_strtolower($query);
        $bestName   = null;
        $bestScore  = 0;
        foreach ($names as $name) {
            $nameLower = mb_strtolower($name);
            similar_text($queryLower, $nameLower, $pct1);
            $pct2  = $this->tokenOverlapScore($queryLower, $nameLower);
            $pct3  = $this->soundexScore($queryLower, $nameLower);
            $score = max($pct1, $pct2 * 100, $pct3 * 100);
            if ($score > $bestScore) { $bestScore = $score; $bestName = $name; }
        }
        return [$bestName, $bestScore];
    }

    private function tokenOverlapScore(string $a, string $b): float
    {
        $tokA = $this->tokenize($a); $tokB = $this->tokenize($b);
        if (empty($tokA) || empty($tokB)) return 0.0;
        $matched = 0;
        foreach ($tokA as $ta) {
            foreach ($tokB as $tb) {
                similar_text($ta, $tb, $pct);
                if ($pct >= 65) { $matched++; break; }
            }
        }
        return $matched / max(count($tokA), count($tokB));
    }

    private function soundexScore(string $a, string $b): float
    {
        $tokA = $this->tokenize($a); $tokB = $this->tokenize($b);
        if (empty($tokA) || empty($tokB)) return 0.0;
        $soundexB = array_map('soundex', $tokB); $matched = 0;
        foreach ($tokA as $ta) { if (in_array(soundex($ta), $soundexB, true)) $matched++; }
        return $matched / max(count($tokA), count($tokB));
    }

    private function tokenize(string $str): array
    {
        $str = mb_strtolower(preg_replace('/[^a-zA-Z0-9\s]/', ' ', $str));
        $tokens = preg_split('/\s+/', trim($str), -1, PREG_SPLIT_NO_EMPTY);
        return array_values(array_filter($tokens, fn($t) => mb_strlen($t) >= 2));
    }

    public function show(string $slug)
    {
        $service      = Product::where('slug', $slug)->where('is_active', true)->with('seller.creatorProfile')->firstOrFail();
        $service->increment('views_count');
        $settings     = Setting::getAllAsArray();
        $wa           = WaSetting::primary();
        $related      = Product::active()->ordered()->where('id', '!=', $service->id)->limit(4)->get();
        $testimonials = Testimonial::active()->ordered()->get()->unique('name');
        $siteName     = $settings['site_name'] ?? 'buyle.id';
        $appUrl       = rtrim(config('app.url'), '/');

        // ── Seller / Creator info (multi-tenant) ──
        $seller         = $service->seller;
        $sellerName     = $seller?->creatorProfile?->store_name ?? $seller?->name ?? $siteName;
        $sellerUrl      = $seller?->creatorProfile?->slug
                            ? $appUrl . '/c/' . $seller->creatorProfile->slug
                            : $appUrl;
        $sellerAvatar   = $seller?->creatorProfile?->avatar
                            ? $appUrl . '/storage/' . $seller->creatorProfile->avatar
                            : null;

        // ── Product images (all gallery for rich snippet) ──
        $productUrl    = route('products.show', ['slug' => $slug]);
        $productImages = [];
        if (!empty($service->image)) {
            $productImages[] = $appUrl . '/storage/' . ltrim($service->image, '/');
        }
        if (is_array($service->gallery)) {
            foreach ($service->gallery as $g) {
                $productImages[] = $appUrl . '/storage/' . ltrim($g, '/');
            }
        }
        if (empty($productImages)) {
            $productImages[] = !empty($settings['og_image_default'])
                ? $appUrl . '/storage/' . $settings['og_image_default']
                : $appUrl . '/images/og-default.jpg';
        }
        $ogImage = $productImages[0];

        // ── SEO meta (canonical per-product, og:type=product) ──
        $finalPrice = $service->sale_price > 0 && $service->sale_price < $service->price
                        ? $service->sale_price
                        : $service->price;

        $seo = [
            'title'       => $service->meta_title ?: ($service->name . ' — ' . $sellerName . ' | ' . $siteName),
            'description' => $service->meta_desc  ?: ($service->short_desc ?: strip_tags($service->description ?? '')),
            'keywords'    => $service->meta_keywords ?: ($service->name . ', ' . $sellerName . ', ' . $siteName),
            'og_image'    => $ogImage,
            'og_type'     => 'product',
            'canonical'   => $productUrl,
            'robots'      => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
        ];

        // ── FAQ ──
        $faq = is_array($service->faqs) ? $service->faqs : [];
        if (empty($faq)) {
            $faq = [
                ['q' => 'Bagaimana cara memesan produk ini?',         'a' => 'Klik tombol "Beli Sekarang" atau "Masukkan Keranjang", lalu ikuti langkah checkout. Anda juga bisa chat langsung dengan creator via WhatsApp.'],
                ['q' => 'Apakah produk ini asli dari creator ' . $sellerName . '?', 'a' => 'Ya, produk ini dijual langsung oleh ' . $sellerName . ' melalui platform buyle.id.'],
                ['q' => 'Apakah tersedia garansi produk?',            'a' => 'Setiap produk dilengkapi garansi sesuai kebijakan seller. Hubungi ' . $sellerName . ' untuk detail lebih lanjut.'],
            ];
        }

        // ── JSON-LD: Product (unique @id = product URL, dynamic per product) ──
        $productSchema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            '@id'         => $productUrl . '#product',
            'name'        => $service->name,
            'image'       => $productImages,
            'description' => strip_tags($service->short_desc ?: ($service->description ?? $service->name)),
            'sku'         => $service->sku ?? ('SKU-' . str_pad($service->id, 4, '0', STR_PAD_LEFT)),
            'url'         => $productUrl,
            'brand'       => [
                '@type' => 'Brand',
                'name'  => $sellerName,
            ],
            'seller'      => [
                '@type'  => 'Organization',
                '@id'    => $sellerUrl . '#seller',
                'name'   => $sellerName,
                'url'    => $sellerUrl,
                'logo'   => $sellerAvatar,
            ],
            'offers'      => [
                '@type'           => 'Offer',
                'priceCurrency'   => 'IDR',
                'price'           => number_format($finalPrice ?? 0, 0, '', ''),
                'priceValidUntil' => now()->addYear()->format('Y-m-d'),
                'availability'    => ($service->is_available ?? true)
                                        ? 'https://schema.org/InStock'
                                        : 'https://schema.org/OutOfStock',
                'url'             => $productUrl,
                'seller'          => [
                    '@type' => 'Organization',
                    'name'  => $sellerName,
                    'url'   => $sellerUrl,
                ],
            ],
        ];

        // Tambahkan aggregateRating jika ada data rating
        if (!empty($service->rating) && $service->rating > 0) {
            $productSchema['aggregateRating'] = [
                '@type'       => 'AggregateRating',
                'ratingValue' => (string) round($service->rating, 1),
                'bestRating'  => '5',
                'worstRating' => '1',
                'ratingCount' => (string) ($service->review_count ?? 1),
            ];
        }

        // JSON-LD: FAQPage
        $faqSchema = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => collect($faq)->map(fn($item) => [
                '@type'          => 'Question',
                'name'           => $item['q'] ?? '',
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a'] ?? ''],
            ])->toArray(),
        ];

        $schema = json_encode([$productSchema, $faqSchema], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $breadcrumbs = [
            ['name' => 'Beranda',          'url' => route('home')],
            ['name' => 'Produk & Layanan', 'url' => route('products')],
            ['name' => $service->name,     'url' => $productUrl],
        ];

        return view('services.show', compact('service', 'settings', 'related', 'wa', 'seo', 'schema', 'faq', 'breadcrumbs', 'testimonials', 'sellerName', 'sellerUrl'));
    }
}
