<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seoTitle ?? (($config['name'] ?? $profile->store_name) . ' - Professional Digital Store') }}</title>
    <meta name="description" content="{{ $seoDesc ?? ($profile->store_description ?? 'Situs resmi dan katalog produk digital terpercaya.') }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=4">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=4">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $seoTitle ?? ($profile->store_name . ' - Professional Digital Store') }}">
    <meta property="og:description" content="{{ $seoDesc ?? '' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/buyle-og.png') }}">
    <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
    <meta property="og:type" content="website">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle ?? ($profile->store_name . ' - Professional Digital Store') }}">
    <meta name="twitter:description" content="{{ $seoDesc ?? '' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/buyle-og.png') }}">

    {{-- Google Fonts: Montserrat (Slim weights 300, 400, 500, 600) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Dynamic Schema.org JSON-LD Structured Data --}}
    @php
        $bioName = $config['name'] ?? $profile->store_name ?? 'Digital Creator';
        $bioRole = $profile->bio_role ?? 'business';
        $roleTitleMap = [
            'content_creator' => 'Content Creator',
            'affiliator'      => 'Affiliator',
            'business'        => 'Digital Store & Service',
        ];
        $roleTitle = $roleTitleMap[$bioRole] ?? 'Digital Store';
        $sameAs = array_values(array_filter([
            !empty($config['ig']) ? 'https://instagram.com/' . ltrim($config['ig'], '@') : null,
            !empty($config['tiktok']) ? 'https://tiktok.com/@' . ltrim($config['tiktok'], '@') : null,
            !empty($config['youtube']) ? $config['youtube'] : null,
            !empty($config['wa']) ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $config['wa']) : null,
        ]));

        $schemaType = ($bioRole === 'business') ? 'ProfessionalService' : 'Person';
        $canonUrl = $canonical ?? url()->current();

        $schemaGraph = [
            [
                '@type' => 'WebSite',
                '@id' => $canonUrl . '#website',
                'url' => $canonUrl,
                'name' => $bioName,
                'description' => $seoDesc ?? '',
                'inLanguage' => 'id-ID',
            ],
            [
                '@type' => $schemaType,
                '@id' => $canonUrl . '#identity',
                'name' => $bioName,
                'url' => $canonUrl,
                'image' => $ogImage ?? asset('images/buyle-og.png'),
                'logo' => $ogImage ?? asset('images/buyle-og.png'),
                'description' => $seoDesc ?? '',
                'sameAs' => $sameAs,
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => $canonUrl . '#breadcrumb',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Beranda',
                        'item' => $canonUrl,
                    ]
                ]
            ]
        ];

        if (isset($products) && count($products) > 0) {
            $itemList = [];
            $pos = 1;
            foreach ($products as $p) {
                $prodImage = !empty($p->image) ? asset('storage/' . $p->image) : ($ogImage ?? asset('images/buyle-og.png'));
                $prodUrl = !empty($profile->custom_domain) 
                    ? 'https://' . rtrim($profile->custom_domain, '/') . '/p/' . ($p->slug ?? $p->id)
                    : url('/' . $profile->store_slug . '/p/' . ($p->slug ?? $p->id));
                $itemList[] = [
                    '@type' => 'ListItem',
                    'position' => $pos++,
                    'item' => [
                        '@type' => 'Product',
                        'name' => $p->name,
                        'image' => $prodImage,
                        'description' => strip_tags($p->description ?? $p->name),
                        'offers' => [
                            '@type' => 'Offer',
                            'price' => (string) ($p->price ?? 0),
                            'priceCurrency' => 'IDR',
                            'availability' => 'https://schema.org/InStock',
                            'url' => $prodUrl,
                        ]
                    ]
                ];
            }
            $schemaGraph[] = [
                '@type' => 'ItemList',
                '@id' => $canonUrl . '#catalog',
                'name' => 'Katalog Produk Digital ' . $bioName,
                'itemListElement' => $itemList,
            ];
        }

        $schemaOrg = [
            '@context' => 'https://schema.org',
            '@graph' => $schemaGraph
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($schemaOrg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    <style>
        /* ════════════════════════════════════════════════════════════
           THEME 5: PROFESSIONAL WEB THEME DESIGN SYSTEM (ISOLATED)
        ════════════════════════════════════════════════════════════ */
        :root {
            --t5-emerald: #1eb349;
            --t5-emerald-dark: #15803d;
            --t5-emerald-light: #f0fdf4;
            --t5-emerald-border: #bbf7d0;
            --t5-slate-900: #0f172a;
            --t5-slate-800: #1e293b;
            --t5-slate-700: #334155;
            --t5-slate-600: #475569;
            --t5-slate-500: #64748b;
            --t5-slate-400: #94a3b8;
            --t5-slate-200: #e2e8f0;
            --t5-bg-main: #f8fafc;
            --t5-white: #ffffff;
            --t5-amber: #f59e0b;
            --t5-radius-sm: 6px;
            --t5-radius-md: 10px;
            --t5-radius-lg: 16px;
            --t5-radius-xl: 20px;
            --t5-shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.04);
            --t5-shadow-md: 0 8px 24px rgba(15, 23, 42, 0.08);
            --t5-shadow-lg: 0 16px 40px rgba(15, 23, 42, 0.12);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            font-size: 16px;
        }

        body.t5-body {
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--t5-bg-main);
            color: var(--t5-slate-800);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── HEADER NAVBAR ── */
        .t5-header {
            position: sticky;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--t5-slate-200);
            transition: all 0.3s ease;
        }
        .t5-header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .t5-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }
        .t5-brand-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--t5-emerald);
            box-shadow: 0 2px 10px rgba(30, 179, 73, 0.2);
        }
        .t5-brand-avatar-fallback {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--t5-emerald), #15803d);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.05rem;
        }
        .t5-brand-info {
            display: flex;
            flex-direction: column;
        }
        .t5-brand-title {
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--t5-slate-900);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }
        .t5-live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--t5-emerald);
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.2);
            animation: t5Pulse 2s infinite;
        }
        @keyframes t5Pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(30, 179, 73, 0.4); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(30, 179, 73, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(30, 179, 73, 0); }
        }

        .t5-nav-desktop {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            max-width: 60%;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .t5-nav-desktop::-webkit-scrollbar {
            display: none;
        }
        .t5-nav-link {
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            color: #94a3b8;
            transition: color 0.2s ease;
            position: relative;
            padding: 0.4rem 0;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .t5-nav-link:hover, .t5-nav-link.active {
            color: var(--t5-emerald);
        }
        .t5-header-actions {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-shrink: 0;
        }
        .t5-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1rem;
            border-radius: 8px; /* RECTANGULAR Sleek Rounded Corner, NO CAPSULE 999px */
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
        }
        .t5-btn-wa {
            background: #25d366;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.3);
        }
        .t5-btn-wa:hover {
            background: #20ba5a;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
        }
        .t5-btn-share {
            background: var(--t5-slate-700);
            color: #ffffff;
            padding: 0.55rem 0.65rem;
            border-radius: 8px; /* RECTANGULAR Sleek Rounded Corner */
        }
        .t5-btn-share:hover {
            background: var(--t5-emerald-light);
            color: var(--t5-emerald);
            transform: translateY(-2px);
        }
        .t5-mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: #ffffff;
            cursor: pointer;
            padding: 0.4rem;
        }

        /* ── HERO BANNER SLIDER ── */
        .t5-hero-slider-section {
            padding: 1.5rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .t5-hero-slider-container {
            position: relative;
            width: 100%;
            border-radius: var(--t5-radius-lg);
            overflow: hidden;
            box-shadow: var(--t5-shadow-lg);
            background: var(--t5-slate-900);
            aspect-ratio: 2.8 / 1;
        }
        @media (max-width: 768px) {
            .t5-hero-slider-container {
                aspect-ratio: 1.6 / 1;
                border-radius: var(--t5-radius-md);
            }
        }

        .t5-slider-track {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .t5-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.6s ease, transform 0.6s ease;
            transform: scale(1.02);
            display: flex;
            align-items: center;
        }
        .t5-slide.active {
            opacity: 1;
            visibility: visible;
            transform: scale(1);
            z-index: 2;
        }
        .t5-slide-bg-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }
        .t5-slide-bg-gradient {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .t5-slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.45) 50%, rgba(15,23,42,0.1) 100%);
            z-index: 2;
        }
        .t5-slide-content {
            position: relative;
            z-index: 3;
            max-width: 650px;
            padding: 2.5rem 3rem;
            color: #ffffff;
        }
        @media (max-width: 768px) {
            .t5-slide-content {
                padding: 1.25rem 1.5rem;
            }
        }
        .t5-slide-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.68rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: rgba(30, 179, 73, 0.25);
            color: #4ade80;
            border: 1px solid rgba(74, 222, 128, 0.4);
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            margin-bottom: 0.75rem;
            backdrop-filter: blur(4px);
        }
        .t5-tag-badge {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #4ade80;
        }
        .t5-slide-title {
            font-size: 2rem;
            font-weight: 600;
            line-height: 1.2;
            letter-spacing: -0.03em;
            margin-bottom: 0.6rem;
            color: #ffffff;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        @media (max-width: 768px) {
            .t5-slide-title {
                font-size: 1.15rem;
                margin-bottom: 0.35rem;
            }
        }
        .t5-slide-desc {
            font-size: 0.88rem;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.5;
            margin-bottom: 1.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        @media (max-width: 768px) {
            .t5-slide-desc {
                font-size: 0.75rem;
                line-height: 1.4;
                margin-bottom: 0.85rem;
                -webkit-line-clamp: 1;
            }
        }
        .t5-slide-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .t5-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--t5-emerald) 0%, #a5cf37 100%);
            color: #ffffff;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.65rem 1.35rem;
            border-radius: 999px;
            box-shadow: 0 4px 16px rgba(30, 179, 73, 0.4);
            transition: all 0.25s ease;
        }
        .t5-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(30, 179, 73, 0.5);
        }
        .t5-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.65rem 1.1rem;
            border-radius: 999px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.25s ease;
        }
        .t5-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .t5-btn-primary, .t5-btn-secondary {
                padding: 0.45rem 0.85rem;
                font-size: 0.75rem;
            }
        }

        .t5-slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(15, 23, 42, 0.65);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .t5-slider-arrow:hover {
            background: var(--t5-emerald);
            color: #ffffff;
            border-color: var(--t5-emerald);
            transform: translateY(-50%) scale(1.1);
        }
        .t5-arrow-prev { left: 1.25rem; }
        .t5-arrow-next { right: 1.25rem; }
        @media (max-width: 768px) {
            .t5-slider-arrow { width: 32px; height: 32px; }
            .t5-arrow-prev { left: 0.5rem; }
            .t5-arrow-next { right: 0.5rem; }
        }

        .t5-slider-dots {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.35rem 0.75rem;
            background: rgba(15, 23, 42, 0.5);
            border-radius: 999px;
            backdrop-filter: blur(6px);
        }
        .t5-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .t5-dot.active {
            width: 24px;
            border-radius: 999px;
            background: var(--t5-emerald);
        }

        /* ── SECTION HEADER COMMON ── */
        .t5-section-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .t5-section-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            color: var(--t5-emerald-dark);
            background: var(--t5-emerald-light);
            border: 1px solid var(--t5-emerald-border);
            padding: 0.3rem 0.8rem;
            border-radius: 999px;
            margin-bottom: 0.5rem;
        }
        .t5-section-title {
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--t5-slate-900);
            letter-spacing: -0.03em;
        }
        .t5-section-sub {
            font-size: 0.88rem;
            color: var(--t5-slate-500);
            margin-top: 0.25rem;
        }

        /* ── PRODUCTS SECTION ── */
        .t5-products-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }
        .t5-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.5rem;
        }
        .t5-product-card {
            background: var(--t5-white);
            border: 1px solid var(--t5-slate-200);
            border-radius: var(--t5-radius-md);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: var(--t5-shadow-sm);
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .t5-product-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--t5-shadow-md);
            border-color: var(--t5-emerald-border);
        }
        .t5-product-img-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 3;
            background: #f1f5f9;
            overflow: hidden;
            display: block;
        }
        .t5-product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .t5-product-card:hover .t5-product-img {
            transform: scale(1.06);
        }
        .t5-discount-tag {
            position: absolute;
            top: 0.65rem;
            left: 0.65rem;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 500;
            padding: 0.2rem 0.5rem;
            border-radius: var(--t5-radius-sm);
            z-index: 2;
        }
        .t5-type-tag {
            position: absolute;
            top: 0.65rem;
            right: 0.65rem;
            background: rgba(15, 23, 42, 0.75);
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            padding: 0.2rem 0.5rem;
            border-radius: var(--t5-radius-sm);
            backdrop-filter: blur(4px);
            z-index: 2;
        }
        .t5-product-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .t5-product-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        .t5-rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--t5-slate-700);
        }
        .t5-verified-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--t5-emerald-dark);
        }
        .t5-product-name {
            font-size: 0.95rem;
            font-weight: 600;
            line-height: 1.35;
            margin-bottom: 0.85rem;
            flex: 1;
        }
        .t5-product-name a {
            color: var(--t5-slate-900);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .t5-product-name a:hover {
            color: var(--t5-emerald);
        }
        .t5-product-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-top: auto;
            padding-top: 0.75rem;
            border-top: 1px solid var(--t5-slate-200);
        }
        .t5-price-wrap {
            display: flex;
            flex-direction: column;
        }
        .t5-price-old {
            font-size: 0.72rem;
            color: var(--t5-slate-400);
            text-decoration: line-through;
            font-weight: 400;
        }
        .t5-price-main {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--t5-emerald-dark);
        }
        .t5-buy-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: linear-gradient(135deg, var(--t5-emerald) 0%, #a5cf37 100%);
            color: #ffffff;
            text-decoration: none;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.45rem 0.9rem;
            border-radius: 999px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 10px rgba(30, 179, 73, 0.3);
        }
        .t5-buy-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.4);
        }

        /* ── BLOCKS SECTION ── */
        .t5-blocks-section {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }
        .t5-blocks-grid {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }
        .t5-block-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--t5-white);
            border: 1px solid var(--t5-slate-200);
            border-radius: var(--t5-radius-md);
            padding: 1rem 1.25rem;
            text-decoration: none;
            color: var(--t5-slate-800);
            box-shadow: var(--t5-shadow-sm);
            transition: all 0.25s ease;
        }
        .t5-block-card:hover {
            transform: translateX(4px);
            border-color: var(--t5-emerald-border);
            box-shadow: var(--t5-shadow-md);
            background: var(--t5-emerald-light);
        }
        .t5-block-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--t5-emerald-light);
            color: var(--t5-emerald);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .t5-block-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .t5-block-title {
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--t5-slate-900);
        }
        .t5-block-sub {
            font-size: 0.78rem;
            color: var(--t5-slate-500);
        }
        .t5-block-arrow {
            color: var(--t5-slate-400);
            transition: transform 0.2s ease;
        }
        .t5-block-card:hover .t5-block-arrow {
            color: var(--t5-emerald);
            transform: translateX(4px);
        }
        .t5-block-banner-card {
            display: block;
            border-radius: var(--t5-radius-md);
            overflow: hidden;
            box-shadow: var(--t5-shadow-sm);
            transition: transform 0.25s ease;
        }
        .t5-block-banner-card:hover {
            transform: translateY(-4px);
        }
        .t5-block-banner-card img {
            width: 100%;
            height: auto;
            display: block;
        }
        .t5-block-heading {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--t5-slate-900);
            margin: 1rem 0 0.5rem;
            border-left: 4px solid var(--t5-emerald);
            padding-left: 0.75rem;
        }

        /* ── ABOUT SECTION ── */
        .t5-about-section {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }
        .t5-about-card {
            background: var(--t5-white);
            border: 1px solid var(--t5-slate-200);
            border-radius: var(--t5-radius-lg);
            padding: 2rem;
            box-shadow: var(--t5-shadow-md);
        }
        .t5-about-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--t5-slate-200);
        }
        @media (max-width: 640px) {
            .t5-about-header {
                flex-direction: column;
                text-align: center;
            }
        }
        .t5-about-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--t5-emerald);
            box-shadow: 0 4px 16px rgba(30, 179, 73, 0.25);
            flex-shrink: 0;
        }
        .t5-about-avatar-fallback {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--t5-emerald), #a5cf37);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 2rem;
            flex-shrink: 0;
        }
        .t5-about-meta {
            display: flex;
            flex-direction: column;
        }
        .t5-about-badges {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.4rem;
        }
        .t5-role-badge, .t5-verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            font-weight: 500;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
        }
        .t5-role-badge {
            background: var(--t5-emerald-light);
            color: var(--t5-emerald-dark);
            border: 1px solid var(--t5-emerald-border);
        }
        .t5-verified-badge {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        .t5-about-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--t5-slate-900);
            letter-spacing: -0.02em;
        }
        .t5-about-loc {
            font-size: 0.82rem;
            color: var(--t5-slate-500);
            display: flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.2rem;
        }
        .t5-about-bio {
            font-size: 0.95rem;
            line-height: 1.7;
            color: var(--t5-slate-700);
            margin-bottom: 1.5rem;
        }

        /* ── FOOTER ── */
        .t5-footer {
            background: #0d0d0d;
            color: #ffffff;
            margin-top: 3rem;
            padding: 3rem 1.5rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .t5-footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .t5-footer-main {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 2.5rem;
            margin-bottom: 2.5rem;
            padding-bottom: 2.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        @media (max-width: 768px) {
            .t5-footer-main {
                grid-template-columns: 1fr;
                gap: 1.75rem;
            }
        }
        .t5-footer-title {
            display: block;
            font-size: 1.3rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.2rem;
        }
        .t5-footer-subtitle {
            font-size: 0.78rem;
            color: var(--t5-emerald);
            font-weight: 500;
            display: block;
            margin-bottom: 0.75rem;
        }
        .t5-footer-desc {
            font-size: 0.82rem;
            color: var(--t5-slate-400);
            line-height: 1.6;
            max-width: 400px;
        }
        .t5-footer-heading {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #ffffff;
            margin-bottom: 1rem;
        }
        .t5-footer-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .t5-footer-link {
            color: var(--t5-slate-400);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s ease;
        }
        .t5-footer-link:hover {
            color: var(--t5-emerald);
        }
        .t5-footer-location-text {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.82rem;
            color: var(--t5-slate-300);
            margin-bottom: 0.85rem;
            line-height: 1.5;
        }
        .t5-footer-location-icon {
            color: var(--t5-emerald);
            flex-shrink: 0;
            margin-top: 0.15rem;
        }
        .t5-footer-map-wrap {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.05);
            max-height: 160px;
        }
        .t5-footer-map-wrap iframe {
            width: 100% !important;
            height: 150px !important;
            border: 0 !important;
            display: block;
        }
        .t5-footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--t5-slate-400);
            flex-wrap: wrap;
            gap: 1rem;
        }
        .t5-footer-bottom a {
            color: var(--t5-emerald);
            text-decoration: none;
            font-weight: 500;
        }
        .t5-back-to-top {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255,255,255,0.1);
            color: #ffffff;
            border: 1px solid rgba(255,255,255,0.12);
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .t5-back-to-top:hover {
            background: var(--t5-emerald);
            transform: translateY(-2px);
        }

        /* ── MOBILE DRAWER ── */
        .t5-mobile-drawer {
            position: fixed;
            inset: 0;
            z-index: 9999;
            pointer-events: none;
        }
        .t5-mobile-drawer.active {
            pointer-events: auto;
        }
        .t5-drawer-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .t5-mobile-drawer.active .t5-drawer-overlay {
            opacity: 1;
        }
        .t5-drawer-content {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 280px;
            background: var(--t5-white);
            box-shadow: -4px 0 20px rgba(0,0,0,0.15);
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        .t5-mobile-drawer.active .t5-drawer-content {
            transform: translateX(0);
        }
        .t5-drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--t5-slate-200);
            margin-bottom: 1.25rem;
        }
        .t5-drawer-title {
            font-weight: 600;
            font-size: 1rem;
            color: var(--t5-slate-900);
        }
        .t5-drawer-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--t5-slate-500);
            cursor: pointer;
        }
        .t5-drawer-nav {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .t5-drawer-link {
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--t5-slate-700);
            padding: 0.65rem 0.85rem;
            border-radius: var(--t5-radius-sm);
            transition: background 0.2s, color 0.2s;
        }
        .t5-drawer-link:hover {
            background: var(--t5-emerald-light);
            color: var(--t5-emerald);
        }
        @media (max-width: 991px) {
            .t5-nav-desktop { display: none; }
            .t5-mobile-toggle { display: block; }
        }
        /* ── FOOTER BRAND & SOCIAL STYLES ── */
        .t5-footer-brand-head {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.85rem;
        }
        .t5-footer-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--t5-emerald);
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.25);
            flex-shrink: 0;
        }
        .t5-footer-avatar-fallback {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--t5-emerald), #15803d);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .t5-footer-brand-meta {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }
        .t5-footer-verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.72rem;
            font-weight: 500;
            color: #4ade80;
            background: rgba(30, 179, 73, 0.15);
            border: 1px solid rgba(30, 179, 73, 0.35);
            padding: 0.15rem 0.55rem;
            border-radius: 6px;
        }
        .t5-footer-social {
            margin-top: 1rem;
        }
        .t5-footer-social .social-row {
            justify-content: flex-start !important;
            margin: 0 !important;
        }
        /* Force all social icons in footer to uniform white */
        .t5-footer-social .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            transition: background 0.2s, border-color 0.2s;
            flex-shrink: 0;
        }
        .t5-footer-social .social-icon:hover {
            background: rgba(255,255,255,0.18);
            border-color: rgba(255,255,255,0.3);
        }
        /* Turn ALL svg icons white via CSS filter — works on any color/gradient */
        .t5-footer-social .social-icon svg {
            display: block;
            filter: brightness(0) invert(1);
        }
        /* Buyle.id favicon is an <img> — keep original colors as requested */
        .t5-footer-social .social-icon img {
            display: block;
            filter: none !important;
        }

        /* FLOATING WHATSAPP BUTTON */
        .t5-floating-wa {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: #ffffff; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-decoration: none;
        }
        .t5-floating-wa:hover {
            transform: scale(1.1) translateY(-2px);
            box-shadow: 0 12px 30px rgba(37, 211, 102, 0.55); color: #ffffff;
        }
        .t5-wa-pulse {
            position: absolute; inset: 0; border-radius: 50%;
            border: 2px solid #25D366; animation: t5WaPulse 2s infinite; pointer-events: none;
        }
        @keyframes t5WaPulse {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.4); opacity: 0; }
        }
        @media (max-width: 640px) {
            .t5-floating-wa { bottom: 18px; right: 18px; width: 50px; height: 50px; }
        }
    </style>

</head>
<body class="t5-body">

    {{-- 1. Header Navbar --}}
    @include('bio.theme5.header')

    <main>
        {{-- 2. Hero Banner Slider Section --}}
        @include('bio.theme5.hero_slider')

        {{-- 3. About Us Section (Tema 5) --}}
        @include('bio.theme5.about_section')

        {{-- 4. Products Catalog Grid --}}
        @include('bio.theme5.products_grid')
    </main>

    {{-- FLOATING WHATSAPP BUTTON --}}
    @if(!empty($config['wa']))
        @php $waFloatNum = preg_replace('/[^0-9]/', '', $config['wa']); @endphp
        @if($waFloatNum)
            <a href="https://wa.me/{{ Str::startsWith($waFloatNum, '62') ? $waFloatNum : '62' . ltrim($waFloatNum, '0') }}"
               target="_blank" rel="noopener noreferrer" class="t5-floating-wa" title="Chat via WhatsApp">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.556 4.117 1.528 5.849L0 24l6.335-1.508A11.948 11.948 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.65-.52-5.154-1.422l-.37-.218-3.764.896.924-3.667-.243-.381A9.953 9.953 0 0 1 2 12c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10z"/>
                </svg>
                <span class="t5-wa-pulse"></span>
            </a>
        @endif
    @endif

    {{-- 6. Footer --}}
    @include('bio.theme5.footer')

    {{-- Modals --}}
    @include('partials.report_modal', ['reportType' => 'bio', 'targetName' => $bioName])
    @include('partials.share_modal', [
        'shareUrl' => $canonical,
        'shareUrlEncoded' => urlencode($canonical),
        'shareTitle' => $seoTitle,
        'shareText' => urlencode('Lihat profil ' . $bioName . ' di buyle.id'),
        'shareName' => $bioName
    ])

    {{-- ════════════════════════════════════════════════════════════
       VANILLA JS CAROUSEL SLIDER SCRIPT WITH TOUCH SWIPE SUPPORT
    ════════════════════════════════════════════════════════════ --}}
    <script>
        let currentSlideIdx = 0;
        let slideTimer = null;
        let touchStartX = 0;
        let touchEndX = 0;

        function initT5Slider() {
            const track = document.getElementById('t5SliderTrack');
            if (!track) return;
            const slides = track.querySelectorAll('.t5-slide');
            if (slides.length <= 1) return;

            startSlideTimer();

            // Touch Swipe Support
            track.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            track.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                handleTouchSwipe();
            }, { passive: true });
        }

        function handleTouchSwipe() {
            const swipeThreshold = 40;
            if (touchEndX < touchStartX - swipeThreshold) {
                nextT5Slide();
            } else if (touchEndX > touchStartX + swipeThreshold) {
                prevT5Slide();
            }
        }

        function showT5Slide(idx) {
            const slides = document.querySelectorAll('.t5-slide');
            const dots = document.querySelectorAll('.t5-dot');
            if (!slides.length) return;

            if (idx >= slides.length) currentSlideIdx = 0;
            else if (idx < 0) currentSlideIdx = slides.length - 1;
            else currentSlideIdx = idx;

            slides.forEach((s, i) => {
                if (i === currentSlideIdx) s.classList.add('active');
                else s.classList.remove('active');
            });

            dots.forEach((d, i) => {
                if (i === currentSlideIdx) d.classList.add('active');
                else d.classList.remove('active');
            });
        }

        function nextT5Slide() {
            showT5Slide(currentSlideIdx + 1);
            resetSlideTimer();
        }

        function prevT5Slide() {
            showT5Slide(currentSlideIdx - 1);
            resetSlideTimer();
        }

        function goToT5Slide(idx) {
            showT5Slide(idx);
            resetSlideTimer();
        }

        function startSlideTimer() {
            stopSlideTimer();
            slideTimer = setInterval(() => {
                nextT5Slide();
            }, 5000);
        }

        function stopSlideTimer() {
            if (slideTimer) clearInterval(slideTimer);
        }

        function resetSlideTimer() {
            startSlideTimer();
        }

        function toggleT5Drawer() {
            const drawer = document.getElementById('t5MobileDrawer');
            if (drawer) drawer.classList.toggle('active');
        }

        document.addEventListener('DOMContentLoaded', () => {
            initT5Slider();
        });
    </script>
</body>
</html>
