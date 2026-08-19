<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\WaSetting;
use App\Models\Testimonial;

class ServiceController extends Controller
{
    public function index()
    {
        $query = Product::active()->ordered();
        
        // Keyword search
        if (request()->filled('q')) {
            $searchTerm = request('q');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('short_desc', 'like', '%' . $searchTerm . '%');
            });
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

        return view('services.index', compact('services', 'settings', 'seo', 'schema', 'categories', 'wa', 'maxPrice'));
    }

    public function show(string $slug)
    {
        $service      = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $service->increment('views_count');
        $settings     = Setting::getAllAsArray();
        $wa           = WaSetting::primary();
        $related      = Product::active()->ordered()->where('id', '!=', $service->id)->limit(4)->get();
        $testimonials = Testimonial::active()->ordered()->get()->unique('name');
        $siteName     = $settings['site_name'] ?? 'buyle.id';

        $seo = [
            'title'       => $service->meta_title ?: ($service->name . ' | ' . $siteName),
            'description' => $service->meta_desc  ?: $service->short_desc,
            'keywords'    => $service->meta_keywords,
            'og_image'    => !empty($service->image) ? asset('storage/'.$service->image) : (!empty($settings['og_image_default']) ? asset('storage/'.$settings['og_image_default']) : asset('images/og-default.jpg')),
            'canonical'   => route('products.show', ['slug' => $slug]),
        ];

        $faq = is_array($service->faqs) ? $service->faqs : [];

        // Fallback FAQs jika kosong
        if (empty($faq)) {
            $faq = [
                ['q' => 'Bagaimana cara memesan produk ini?', 'a' => 'Anda dapat memesan melalui tombol "Tambah ke Keranjang" atau menghubungi tim kami melalui WhatsApp untuk konsultasi lebih lanjut.'],
                ['q' => 'Apakah tersedia layanan pengiriman ke seluruh Indonesia?', 'a' => 'Ya, kami melayani pengiriman ke seluruh wilayah Indonesia.'],
                ['q' => 'Apakah tersedia garansi produk?', 'a' => 'Setiap produk dilengkapi dengan garansi sesuai ketentuan yang berlaku. Hubungi kami untuk informasi lebih lanjut.'],
            ];
        }

        $serviceImage = !empty($service->og_image)
            ? rtrim(config('app.url'), '/') . '/storage/' . $service->og_image
            : (!empty($service->image)
                ? rtrim(config('app.url'), '/') . '/storage/' . $service->image
                : rtrim(config('app.url'), '/') . '/images/og-default.jpg');

        $schema = json_encode([
            [
                '@context'    => 'https://schema.org',
                '@type'       => 'Product',
                'name'        => $service->name,
                'image'       => [$serviceImage],
                'description' => strip_tags($service->short_desc ?: $service->name),
                'sku'         => $service->sku ?? 'SKU-' . str_pad($service->id, 4, '0', STR_PAD_LEFT),
                'url'         => route('products.show', ['slug' => $slug]),
                'brand'       => ['@type' => 'Brand', 'name' => $siteName],
                'offers'      => [
                    '@type'         => 'Offer',
                    'priceCurrency' => 'IDR',
                    'price'         => (string) ($service->final_price ?? 0),
                    'availability'  => $service->is_available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                    'url'           => route('products.show', ['slug' => $slug]),
                ],
            ],
            [
                '@context'   => 'https://schema.org',
                '@type'      => 'FAQPage',
                'mainEntity' => collect($faq)->map(fn($item) => [
                    '@type'          => 'Question',
                    'name'           => $item['q'] ?? '',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a'] ?? ''],
                ])->toArray(),
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $breadcrumbs = [
            ['name' => 'Beranda',          'url' => route('home')],
            ['name' => 'Produk & Layanan', 'url' => route('products')],
            ['name' => $service->name,     'url' => route('products.show', ['slug' => $slug])],
        ];

        return view('services.show', compact('service', 'settings', 'related', 'wa', 'seo', 'schema', 'faq', 'breadcrumbs', 'testimonials'));
    }
}
