<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="referrer" content="no-referrer">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    @php
        $bioName   = $config['name'] ?? $profile->store_name ?? $username;
        $pageTitle = $bioName . ' | Official Digital Portal';
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonical }}">

    <meta property="og:type" content="profile">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:site_name" content="buyle.id">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    @php
        $sameAsLinks = array_values(array_filter([
            !empty($config['ig']) ? 'https://instagram.com/' . ltrim(ltrim($config['ig'], '@'), '/') : null,
            !empty($config['tiktok']) ? 'https://tiktok.com/@' . ltrim(ltrim($config['tiktok'], '@'), '/') : null,
            !empty($config['youtube']) ? $config['youtube'] : null,
            $canonical ?? url()->current(),
        ]));

        $personSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => (string) ($config['name'] ?? $profile->store_name ?? $username),
            'url' => (string) ($canonical ?? url()->current()),
            'image' => (string) ($ogImage ?? asset('favicon.png')),
            'description' => (string) ($seoDesc ?? ''),
            'sameAs' => $sameAsLinks,
        ];
        if (!empty($config['location'])) {
            $personSchema['address'] = [
                '@type' => 'PostalAddress',
                'addressLocality' => (string) $config['location'],
                'addressCountry' => 'ID',
            ];
        }
    @endphp
    <script type="application/ld+json">
    {!! json_encode($personSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">

    <style>
        :root {
            --accent: #0f172a;
            --accent-rgb: 15, 23, 42;
            --bg: #f8fafc;
            --card: #ffffff;
            --card-border: #e2e8f0;
            --text: #0f172a;
            --text-sub: #64748b;
            --btn: #0f172a;
            --btn-text: #ffffff;
            --radius-lg: 24px;
            --radius-md: 16px;
            --shadow-sm: 0 4px 14px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 12px 28px -6px rgba(15, 23, 42, 0.08);
            --shadow-hover: 0 20px 36px -8px rgba(15, 23, 42, 0.12);
        }

        /* Scrollbar Abu-Abu Cerah Clean */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', 'Montserrat', sans-serif;
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        .theme5-wrapper {
            max-width: 1140px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 5rem;
        }

        /* Anti-Mainstream Asymmetric Grid Layout */
        .theme5-grid {
            display: grid;
            grid-template-columns: 370px 1fr;
            gap: 2rem;
            align-items: start;
        }

        .left-profile-sticky {
            position: sticky;
            top: 2rem;
            z-index: 20;
        }

        /* Profile Card - Motia Clean Style */
        .motia-card {
            background: var(--card);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .motia-card:hover {
            box-shadow: var(--shadow-hover);
        }

        /* Header Cover Banner */
        .motia-header-banner {
            height: 145px;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            background-size: cover;
            background-position: center;
            position: relative;
            padding: 1.25rem 1.25rem 0.75rem;
            display: flex;
            justify-content: flex-end;
            align-items: flex-start;
        }

        /* Social Icons inside Header (Motia Style: Clean White Monochrome Vectors) */
        .motia-header-socials {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .motia-header-socials a {
            color: #ffffff;
            opacity: 0.88;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s;
        }

        .motia-header-socials a:hover {
            opacity: 1;
            transform: scale(1.15);
        }

        .motia-header-socials svg {
            width: 18px;
            height: 18px;
            fill: #ffffff;
        }

        /* Avatar: Large Rounded Square Overlapping Header */
        .motia-avatar-wrap {
            padding: 0 1.5rem;
            margin-top: -46px;
            position: relative;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
        }

        .motia-avatar {
            width: 92px;
            height: 92px;
            border-radius: 22px;
            border: 4px solid var(--card);
            background: #0f172a;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            flex-shrink: 0;
        }

        .motia-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Profile Details Body */
        .motia-card-body {
            padding: 1.25rem 1.5rem 1.75rem;
        }

        .motia-name {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .motia-handle {
            font-size: 0.85rem;
            color: var(--text-sub);
            margin-top: 4px;
            font-weight: 500;
        }

        .motia-bio {
            font-size: 0.88rem;
            color: var(--text-sub);
            margin-top: 0.85rem;
            line-height: 1.6;
        }

        /* Inline Stats Bar (Motia Clean Style: No Boxes, No Badges) */
        .motia-stats-row {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-top: 1.25rem;
            padding-top: 1rem;
            border-top: 1px solid var(--card-border);
            font-size: 0.88rem;
            color: var(--text-sub);
        }

        .motia-stats-row strong {
            color: var(--text);
            font-weight: 800;
        }

        /* Direct Domain Link */
        .motia-domain-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 1rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-sub);
            text-decoration: none;
            transition: color 0.2s;
        }

        .motia-domain-link:hover {
            color: var(--accent);
        }

        /* Right Content Showcase Area */
        .right-showcase-area {
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        /* Top Hero Card */
        .landing-top-hero {
            background: var(--card);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 1.5rem 1.75rem;
            box-shadow: var(--shadow-sm);
        }

        .hero-welcome-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.01em;
        }

        .hero-welcome-sub {
            font-size: 0.85rem;
            color: var(--text-sub);
            margin-top: 4px;
            line-height: 1.5;
        }

        /* Clean Search Bar */
        .search-box-landing {
            position: relative;
            width: 100%;
        }

        .search-box-landing input {
            width: 100%;
            padding: 0.85rem 1.25rem 0.85rem 2.8rem;
            border-radius: 14px;
            background: var(--card);
            border: 1px solid var(--card-border);
            color: var(--text);
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 500;
            outline: none;
            transition: all 0.2s;
            box-shadow: var(--shadow-sm);
        }

        .search-box-landing input:focus {
            border-color: #94a3b8;
            box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.15);
        }

        .search-box-landing i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-sub);
            font-size: 0.95rem;
        }

        /* Section Title Block */
        .section-header-landing {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.85rem;
        }

        .section-title-text {
            font-size: 0.88rem;
            font-weight: 800;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title-text::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 14px;
            background: var(--text);
            border-radius: 999px;
        }

        /* Link Stack - Bento Clean Style */
        .bento-link-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .bento-link-card {
            background: var(--card);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            padding: 1.1rem 1.3rem;
            text-decoration: none;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .bento-link-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--text);
            opacity: 0;
            transition: opacity 0.25s;
        }

        .bento-link-card:hover {
            transform: translateX(4px);
            border-color: #cbd5e1;
            box-shadow: var(--shadow-md);
        }

        .bento-link-card:hover::before {
            opacity: 1;
        }

        .bento-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--bg);
            border: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--text);
            font-size: 1.2rem;
            overflow: hidden;
        }

        .bento-icon-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .bento-body {
            flex: 1;
            min-width: 0;
        }

        .bento-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
        }

        .bento-desc {
            font-size: 0.76rem;
            color: var(--text-sub);
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .bento-arrow-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-sub);
            font-size: 0.85rem;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .bento-link-card:hover .bento-arrow-btn {
            background: var(--text);
            color: #fff;
            transform: translateX(2px);
        }

        /* Products Showcase Grid */
        .landing-products-grid {
            display: grid;
            grid-template-columns: repeat( auto-fill, minmax(210px, 1fr) );
            gap: 16px;
        }

        .landing-prod-card {
            background: var(--card);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            overflow: hidden;
            text-decoration: none;
            color: var(--text);
            display: flex;
            flex-direction: column;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
        }

        .landing-prod-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: #cbd5e1;
        }

        .landing-prod-img {
            width: 100%;
            padding-top: 100%;
            position: relative;
            background: var(--bg);
            overflow: hidden;
        }

        .landing-prod-img img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .landing-prod-card:hover .landing-prod-img img {
            transform: scale(1.06);
        }

        .prod-badge-num {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(4px);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 6px;
            z-index: 5;
        }

        .landing-prod-info {
            padding: 12px 14px 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .landing-prod-title {
            font-size: 0.82rem;
            font-weight: 700;
            line-height: 1.4;
            color: var(--text);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .landing-prod-footer {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 6px;
        }

        .landing-prod-price {
            font-size: 0.88rem;
            font-weight: 800;
            color: var(--text);
        }

        .landing-prod-btn {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-sub);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .landing-prod-card:hover .landing-prod-btn {
            color: var(--text);
        }

        /* TikTok Highlights Slider */
        .tiktok-highlights-wrap {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            padding-bottom: 0.75rem;
            scrollbar-width: thin;
            scroll-snap-type: x mandatory;
        }

        .tiktok-card-item {
            width: 140px;
            height: 220px;
            border-radius: var(--radius-md);
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
            scroll-snap-align: start;
            background: #000;
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            text-decoration: none;
        }

        .tiktok-card-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .tiktok-card-item:hover img {
            transform: scale(1.05);
        }

        .tiktok-card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7) 0%, transparent 60%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 10px;
            color: #fff;
        }

        .tt-brand-icon {
            font-size: 1.1rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
        }

        .tt-play-lbl {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Map Container */
        .map-frame-wrap {
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-sm);
            background: var(--card);
        }
        .map-frame-wrap iframe {
            width: 100% !important;
            display: block;
            border: none !important;
        }

        /* Footer */
        .footer-landing {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--card-border);
        }

        .footer-landing a {
            color: var(--text-sub);
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            transition: color 0.2s;
        }

        .footer-landing a:hover {
            color: var(--text);
        }

        /* Mobile Responsive Optimization */
        @media (max-width: 991px) {
            .theme5-wrapper {
                padding: 1rem 1rem 3rem;
            }

            .theme5-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .left-profile-sticky {
                position: relative;
                top: 0;
            }

            .landing-products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .landing-top-hero {
                padding: 1.25rem;
            }
        }
    </style>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    {{-- Custom Background & Colors Overrides --}}
    <style>
        @php
            $bgType  = $config['bg_type'] ?? 'color';
            $bgImg   = $config['bg_image'] ?? null;
            $colorBg = $config['color_bg'] ?? null;
        @endphp

        @if($bgType === 'image' && !empty($bgImg))
            body {
                background: url("{{ asset('storage/' . $bgImg) }}") center center / cover no-repeat fixed !important;
            }
        @elseif(!empty($colorBg))
            body {
                background: {{ $colorBg }} !important;
            }
        @endif

        @if(!empty($config['color_text']))
            body, .motia-name, .hero-welcome-title, .bento-title, .landing-prod-title, .section-title-text {
                color: {{ $config['color_text'] }} !important;
            }
            .motia-bio, .motia-handle, .hero-welcome-sub, .bento-desc, .motia-stats-row {
                color: {{ $config['color_text'] }} !important;
                opacity: 0.8;
            }
        @endif

        @if(!empty($config['color_accent']))
            :root {
                --accent: {{ $config['color_accent'] }} !important;
            }
        @endif

        @if(!empty($config['color_card']))
            .motia-card, .landing-top-hero, .bento-link-card, .landing-prod-card, .map-frame-wrap, .search-box-landing input {
                background: {{ $config['color_card'] }} !important;
            }
        @endif

        @if(!empty($config['color_btn']))
            .bento-link-card {
                background: {{ $config['color_btn'] }} !important;
                border-color: transparent !important;
            }
        @endif

        @if(!empty($config['color_btn_text']))
            .bento-title, .bento-desc, .bento-arrow-btn {
                color: {{ $config['color_btn_text'] }} !important;
            }
        @endif
    </style>
</head>

<body>

    @php
        $sl               = $profile->social_links ?? [];
        $s_wa             = $sl['wa']        ?? $config['wa']        ?? null;
        $s_ig             = $sl['instagram'] ?? $config['ig']        ?? null;
        $s_tt             = $sl['tiktok']    ?? $config['tiktok']    ?? null;
        $s_yt             = $sl['youtube']   ?? $config['youtube']   ?? null;
        $s_fb             = $sl['facebook']  ?? $config['facebook']  ?? null;
        $s_x              = $sl['x']        ?? $config['x']         ?? null;
        $s_li             = $sl['linkedin']  ?? $config['linkedin']  ?? null;
        $s_web            = $sl['website']   ?? $config['website']   ?? null;

        $ig_url           = !empty($s_ig) ? (Str::startsWith($s_ig, 'http') ? $s_ig : 'https://instagram.com/' . ltrim(ltrim($s_ig, '@'), '/')) : null;
        $tt_url           = !empty($s_tt) ? (Str::startsWith($s_tt, 'http') ? $s_tt : 'https://tiktok.com/@' . ltrim(ltrim($s_tt, '@'), '/')) : null;
        $yt_url           = !empty($s_yt) ? (Str::startsWith($s_yt, 'http') ? $s_yt : 'https://youtube.com/@' . ltrim(ltrim($s_yt, '@'), '/')) : null;
        $wa_url           = !empty($s_wa) ? 'https://wa.me/62' . preg_replace('/^(62|0)/', '', $s_wa) : null;

        $linkBlocks       = $blocks->whereIn('type', ['link', 'pdf', 'image']);
        $tiktokBlocks     = $blocks->where('type', 'tiktok');
        $affBlocks        = $blocks->whereIn('type', ['shopee', 'affiliate'])->sortByDesc('created_at')->values();
        $buyleBlocks      = $blocks->where('type', 'buyle_product');
        $customProdBlocks = $blocks->where('type', 'custom_product');
        $totalLinks       = $linkBlocks->count();
        $totalProds       = $affBlocks->count() + $buyleBlocks->count() + $customProdBlocks->count();
    @endphp

    <div class="theme5-wrapper">
        <div class="theme5-grid">
            
            {{-- Left Column: Sticky Motia Profile Card (Matches Image 1) --}}
            <div class="left-profile-sticky">
                <div class="motia-card">
                    
                    {{-- Header Cover Banner with Clean Bare Minimalist Vector Social Icons (Motia Style) --}}
                    <div class="motia-header-banner" @if(!empty($config['cover'])) style="background-image:url('{{ asset('storage/' . $config['cover']) }}');" @endif>
                        <div class="motia-header-socials">
                            @if($wa_url)
                                <a href="{{ $wa_url }}" target="_blank" title="WhatsApp">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.556 4.117 1.528 5.849L0 24l6.335-1.508A11.948 11.948 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.65-.52-5.154-1.422l-.37-.218-3.764.896.924-3.667-.243-.381A9.953 9.953 0 0 1 2 12c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10z"/></svg>
                                </a>
                            @endif
                            @if($ig_url)
                                <a href="{{ $ig_url }}" target="_blank" title="Instagram">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor"/></svg>
                                </a>
                            @endif
                            @if($tt_url)
                                <a href="{{ $tt_url }}" target="_blank" title="TikTok">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.86 4.43 6.2 6.2 0 0 0 1.91-4.42V8.92a8.28 8.28 0 0 0 4.82 1.55v-3.47a4.91 4.91 0 0 1-1-.31z"/></svg>
                                </a>
                            @endif
                            @if($yt_url)
                                <a href="{{ $yt_url }}" target="_blank" title="YouTube">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>
                                </a>
                            @endif
                            @if($s_web)
                                <a href="{{ $s_web }}" target="_blank" title="Website">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Avatar (Large Rounded Square Image Overlapping Header like Motia) --}}
                    <div class="motia-avatar-wrap">
                        <div class="motia-avatar">
                            @if(!empty($config['avatar']))
                                <img src="{{ asset('storage/' . $config['avatar']) }}" alt="{{ $config['name'] ?? '' }}">
                            @else
                                <div style="width:100%;height:100%;background:#0f172a;display:flex;align-items:center;justify-content:center;font-size:2.4rem;font-weight:900;color:#fff;">
                                    {{ strtoupper(substr($config['name'] ?? $username, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Card Details Body --}}
                    <div class="motia-card-body">
                        <h1 class="motia-name">{{ $config['name'] ?? $profile->store_name ?? $username }}</h1>
                        <div class="motia-handle">{{ '@' . $username }}</div>

                        @if(!empty($config['bio']))
                            <p class="motia-bio">{{ $config['bio'] }}</p>
                        @endif

                        {{-- Inline Stats Row (Motia Style: Clean Inline Numbers) --}}
                        <div class="motia-stats-row">
                            <div><strong>{{ $totalLinks }}</strong> Links</div>
                            <div><strong>{{ $totalProds }}</strong> Produk</div>
                        </div>

                        {{-- Direct Domain Link --}}
                        <a href="{{ canonical ?? url()->current() }}" class="motia-domain-link">
                            <i class="fas fa-link" style="font-size:11px;"></i>
                            buyle.id/{{ $username }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right Column: Showcase Content Area --}}
            <div class="right-showcase-area">
                
                {{-- Clean Top Banner Header (NO Emojis) --}}
                <div class="landing-top-hero">
                    <div class="hero-welcome-title">Official Digital Portal</div>
                    <div class="hero-welcome-sub">Temukan tautan resmi & rekomendasi produk terbaik dari {{ $config['name'] ?? $profile->store_name ?? $username }}.</div>
                </div>

                {{-- Clean Search Box --}}
                <div class="search-box-landing">
                    <i class="fas fa-search"></i>
                    <input type="text" id="bioSearchInput" placeholder="Cari link, produk, atau konten..." onkeyup="filterBioItems(this.value)">
                </div>

                <script>
                function filterBioItems(query) {
                    const q = query.toLowerCase().trim();
                    document.querySelectorAll('.search-item').forEach(item => {
                        const text = (item.innerText + ' ' + (item.dataset.title || '') + ' ' + (item.dataset.url || '')).toLowerCase();
                        if (!q || text.includes(q)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }
                </script>

                {{-- Custom Links Bento Section --}}
                @if($linkBlocks->isNotEmpty())
                    <div>
                        <div class="section-header-landing">
                            <span class="section-title-text">Featured Links & Content</span>
                        </div>
                        <div class="bento-link-grid">
                            @foreach($linkBlocks as $block)
                                <a href="{{ $block->url }}" target="_blank" class="bento-link-card search-item" data-title="{{ $block->title }}">
                                    <div class="bento-icon-box">
                                        @if(!empty($block->data_json['icon_class']))
                                            <i class="{{ $block->data_json['icon_class'] }}"></i>
                                        @elseif(!empty($block->data_json['image']))
                                            <img src="{{ Str::startsWith($block->data_json['image'], 'http') ? $block->data_json['image'] : asset('storage/' . $block->data_json['image']) }}" alt="">
                                        @elseif($block->type === 'pdf')
                                            <i class="fas fa-file-pdf"></i>
                                        @else
                                            <i class="fas fa-link"></i>
                                        @endif
                                    </div>
                                    <div class="bento-body">
                                        <div class="bento-title">{{ $block->title }}</div>
                                        @if(!empty($block->data_json['description']))
                                            <div class="bento-desc">{{ Str::limit($block->data_json['description'], 65) }}</div>
                                        @endif
                                    </div>
                                    <div class="bento-arrow-btn">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- TikTok Highlights Slider --}}
                @if($tiktokBlocks->isNotEmpty())
                    <div>
                        <div class="section-header-landing">
                            <span class="section-title-text">TikTok Highlights</span>
                        </div>
                        <div class="tiktok-highlights-wrap">
                            @foreach($tiktokBlocks as $b)
                                <a href="{{ $b->url }}" target="_blank" class="tiktok-card-item tt-fetch search-item" data-title="TikTok Video" data-url="{{ $b->url }}">
                                    <img src="" alt="TikTok" class="tt-thumb" style="opacity:0; transition:opacity 0.3s;">
                                    <div class="tiktok-card-overlay">
                                        <span class="tt-brand-icon"><i class="fab fa-tiktok"></i></span>
                                        <span class="tt-play-lbl"><i class="fas fa-play" style="font-size:9px;"></i> Tonton</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Products Showcase Section (Shopee/Affiliate) --}}
                @if($affBlocks->isNotEmpty())
                    <div>
                        <div class="section-header-landing">
                            <span class="section-title-text">Rekomendasi Produk Affiliate</span>
                        </div>
                        <div class="landing-products-grid">
                            @foreach($affBlocks as $index => $block)
                                <a href="{{ $block->url }}" target="_blank" class="landing-prod-card search-item" data-title="{{ $block->title }}">
                                    <div class="landing-prod-img">
                                        <div class="prod-badge-num">#{{ sprintf('%02d', $index + 1) }}</div>
                                        @if(!empty($block->data_json['image']))
                                            <img src="{{ Str::startsWith($block->data_json['image'], 'http') ? $block->data_json['image'] : asset('storage/' . $block->data_json['image']) }}"
                                                alt="{{ $block->title }}"
                                                onerror="this.src='https://placehold.co/400x400/fff/cbd5e1?text=Product'">
                                        @else
                                            <img src="https://placehold.co/400x400/fff/cbd5e1?text=Product" alt="No Image">
                                        @endif
                                    </div>
                                    <div class="landing-prod-info">
                                        <h3 class="landing-prod-title">{{ $block->title }}</h3>
                                        <div class="landing-prod-footer">
                                            <span class="landing-prod-price">Cek Detail</span>
                                            <span class="landing-prod-btn">Beli <i class="fas fa-arrow-right"></i></span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- UMKM Custom Products --}}
                @if($customProdBlocks->isNotEmpty())
                    <div>
                        <div class="section-header-landing">
                            <span class="section-title-text">Katalog Toko {{ $config['name'] ?? $username }}</span>
                        </div>
                        <div class="landing-products-grid">
                            @foreach($customProdBlocks as $block)
                                @php 
                                    $imgs = $block->data_json['images'] ?? []; 
                                    $img = !empty($imgs[0]) ? asset('storage/' . $imgs[0]) : (!empty($block->data_json['image']) ? asset('storage/' . $block->data_json['image']) : 'https://placehold.co/400x400/222/555?text=Product');
                                    $prodUrl = route('bio.product.show', [$username, $block->data_json['slug'] ?? $block->id]);
                                    $price = $block->data_json['price'] ?? 0;
                                    $origPrice = $block->data_json['original_price'] ?? null;
                                @endphp
                                <a href="{{ $prodUrl }}" class="landing-prod-card search-item" data-title="{{ $block->title }} {{ $price }}">
                                    <div class="landing-prod-img">
                                        <img src="{{ $img }}" alt="{{ $block->title }}">
                                    </div>
                                    <div class="landing-prod-info">
                                        <h3 class="landing-prod-title">{{ $block->title }}</h3>
                                        <div class="landing-prod-footer">
                                            <div style="display:flex; flex-direction:column;">
                                                @if(!empty($origPrice) && $origPrice > $price)
                                                    <span style="text-decoration:line-through; opacity:0.5; font-size:0.7rem;">Rp {{ number_format($origPrice, 0, ',', '.') }}</span>
                                                @endif
                                                <span class="landing-prod-price">Rp {{ number_format($price, 0, ',', '.') }}</span>
                                            </div>
                                            <span class="landing-prod-btn">Pesan <i class="fas fa-chevron-right"></i></span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Digital Buyle Products --}}
                @if($buyleBlocks->isNotEmpty())
                    <div>
                        <div class="section-header-landing">
                            <span class="section-title-text">Produk Digital Saya</span>
                        </div>
                        <div class="landing-products-grid">
                            @foreach($buyleBlocks as $block)
                                @php $prod = $products[$block->data_json['product_id'] ?? 0] ?? null; @endphp
                                @if($prod)
                                    <a href="{{ $block->url }}" target="_blank" class="landing-prod-card search-item" data-title="{{ $prod->name }}">
                                        <div class="landing-prod-img">
                                            @if($prod->image)
                                                <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}">
                                            @else
                                                <img src="https://placehold.co/400x400/fff/cbd5e1?text=Product" alt="No Image">
                                            @endif
                                        </div>
                                        <div class="landing-prod-info">
                                            <h3 class="landing-prod-title">{{ $prod->name }}</h3>
                                            <div class="landing-prod-footer">
                                                <span class="landing-prod-price">Rp {{ number_format($prod->price, 0, ',', '.') }}</span>
                                                <span class="landing-prod-btn">Beli <i class="fas fa-chevron-right"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Embed Map / Location Section --}}
                @if(!empty($config['embed_location']))
                    <div>
                        <div class="section-header-landing">
                            <span class="section-title-text">Lokasi & Alamat Kami</span>
                        </div>
                        <div class="map-frame-wrap">
                            {!! $config['embed_location'] !!}
                        </div>
                    </div>
                @endif

                {{-- Footer --}}
                <div class="footer-landing">
                    <a href="{{ url('/') }}" target="_blank">Powered by buyle.id</a>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // TikTok Thumbnail Fetcher via oEmbed
            document.querySelectorAll('.tt-fetch').forEach(card => {
                const url = card.dataset.url;
                const img = card.querySelector('.tt-thumb');
                if (!url) return;
                fetch(`https://www.tiktok.com/oembed?url=${encodeURIComponent(url)}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.thumbnail_url) { img.src = data.thumbnail_url; img.style.opacity = '1'; }
                    })
                    .catch(() => { img.src = 'https://placehold.co/140x220/111/fff?text=TikTok'; img.style.opacity = '1'; });
            });
        });
    </script>
    @include('partials.report_modal', ['reportType' => 'link_in_bio', 'targetName' => $config['name'] ?? $profile->store_name ?? $username])
</body>

</html>
