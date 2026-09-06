<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="referrer" content="no-referrer">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    @php
        $roleTitleMap = [
            'content_creator' => 'Content Creator',
            'affiliator'      => 'Affiliator',
            'business'        => 'Business',
        ];
        $roleTitle = $roleTitleMap[$profile->bio_role ?? ''] ?? 'Creator';
        $bioName   = $config['name'] ?? $profile->store_name ?? $username;
        $pageTitle = $bioName . ' - ' . $roleTitle . ' | buyle.id';
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
            !empty($config['ig']) ? 'https://instagram.com/' . ltrim($config['ig'], '@') : null,
            !empty($config['tiktok']) ? 'https://tiktok.com/@' . ltrim($config['tiktok'], '@') : null,
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
            --accent: #2563eb;
            --accent-rgb: 37, 99, 235;
            --bg: #f8fafc;
            --card: #ffffff;
            --card-border: #e2e8f0;
            --text: #0f172a;
            --text-sub: #64748b;
            --btn: #0f172a;
            --btn-text: #ffffff;
            --radius-lg: 24px;
            --radius-md: 16px;
            --shadow-sm: 0 4px 12px rgba(15, 23, 42, 0.03);
            --shadow-md: 0 12px 28px -6px rgba(15, 23, 42, 0.06);
            --shadow-hover: 0 20px 36px -8px rgba(15, 23, 42, 0.12);
        }

        /* Custom Scrollbar - Bright Light Gray */
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 5rem;
        }

        /* Anti-Mainstream Asymmetric Landing Grid */
        .theme5-grid {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 2rem;
            align-items: start;
        }

        /* Sticky Left Profile Card */
        .left-profile-sticky {
            position: sticky;
            top: 2rem;
            z-index: 20;
        }

        .profile-hero-card {
            background: var(--card);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .profile-hero-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .hero-cover-banner {
            height: 140px;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .profile-avatar-container {
            padding: 0 1.5rem;
            margin-top: -55px;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .avatar-ring {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            border: 4px solid var(--card);
            background: #fff;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            position: relative;
            flex-shrink: 0;
        }

        .avatar-ring img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .status-badge-online {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .status-badge-online .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulseGreen 2s infinite;
        }

        @keyframes pulseGreen {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        .profile-card-body {
            padding: 1.25rem 1.5rem 1.5rem;
        }

        .profile-name-heading {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1.25;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            flex-wrap: wrap;
        }

        .verified-icon {
            color: var(--accent);
            font-size: 1.1rem;
        }

        .role-pill {
            display: inline-block;
            background: rgba(var(--accent-rgb), 0.08);
            color: var(--accent);
            font-weight: 700;
            font-size: 0.72rem;
            padding: 3px 10px;
            border-radius: 6px;
            margin-top: 0.4rem;
            letter-spacing: 0.02em;
        }

        .profile-bio-text {
            font-size: 0.85rem;
            color: var(--text-sub);
            margin-top: 0.75rem;
            line-height: 1.6;
        }

        .location-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            color: var(--text-sub);
            margin-top: 0.6rem;
            background: var(--bg);
            padding: 4px 10px;
            border-radius: 8px;
            border: 1px solid var(--card-border);
        }

        /* Creator Quick Stats Bar */
        .creator-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            background: var(--bg);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 10px;
            margin-top: 1.25rem;
            text-align: center;
        }

        .stat-item-val {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text);
        }

        .stat-item-lbl {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--text-sub);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Social Row */
        .social-icons-wrapper {
            margin-top: 1.25rem;
            border-top: 1px dashed var(--card-border);
            padding-top: 1rem;
        }

        .social-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--bg);
            border: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text);
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
        }

        .social-icon:hover {
            background: var(--accent);
            color: #fff !important;
            border-color: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(var(--accent-rgb), 0.25);
        }

        /* Right Content Showcase */
        .right-showcase-area {
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        /* Landing Top Bar / Welcome Hero */
        .landing-top-hero {
            background: var(--card);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 1.5rem 1.75rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .landing-top-hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 180px;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(var(--accent-rgb), 0.05));
            pointer-events: none;
        }

        .hero-welcome-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text);
        }

        .hero-welcome-sub {
            font-size: 0.83rem;
            color: var(--text-sub);
            margin-top: 4px;
        }

        /* Search Bar */
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
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(var(--accent-rgb), 0.15);
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
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title-text::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 14px;
            background: var(--accent);
            border-radius: 999px;
        }

        /* Link Stack - Bento Anti-Mainstream Style */
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
            background: var(--accent);
            opacity: 0;
            transition: opacity 0.25s;
        }

        .bento-link-card:hover {
            transform: translateX(4px);
            border-color: rgba(var(--accent-rgb), 0.3);
            box-shadow: var(--shadow-md);
        }

        .bento-link-card:hover::before {
            opacity: 1;
        }

        .bento-icon-box {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: var(--bg);
            border: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--accent);
            font-size: 1.25rem;
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
            font-size: 0.95rem;
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
            width: 36px;
            height: 36px;
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
            background: var(--accent);
            color: #fff;
            transform: translateX(2px);
        }

        /* Products Showcase Grid */
        .landing-products-grid {
            display: grid;
            grid-template-columns: repeat( auto-fill, minmax(220px, 1fr) );
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
            border-color: rgba(var(--accent-rgb), 0.3);
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
            color: var(--accent);
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
            color: var(--accent);
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
            color: var(--accent);
        }

        /* Mobile Optimization */
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

    {{-- Dynamic Custom Color & Background Overrides --}}
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
            body, .profile-name-heading, .hero-welcome-title, .bento-title, .landing-prod-title, .section-title-text, .stat-item-val {
                color: {{ $config['color_text'] }} !important;
            }
            .profile-bio-text, .hero-welcome-sub, .bento-desc, .stat-item-lbl, .location-pill {
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
            .profile-hero-card, .landing-top-hero, .bento-link-card, .landing-prod-card, .map-frame-wrap, .search-box-landing input {
                background: {{ $config['color_card'] }} !important;
            }
        @endif

        @if(!empty($config['color_btn']))
            .bento-link-card, .social-icon {
                background: {{ $config['color_btn'] }} !important;
                border-color: transparent !important;
            }
        @endif

        @if(!empty($config['color_btn_text']))
            .bento-title, .bento-desc, .bento-arrow-btn, .social-icon, .social-icon i, .social-icon svg {
                color: {{ $config['color_btn_text'] }} !important;
            }
        @endif
    </style>
</head>

<body>

    @php
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
            
            {{-- Left Column: Sticky Profile Card --}}
            <div class="left-profile-sticky">
                <div class="profile-hero-card">
                    {{-- Cover Header --}}
                    <div class="hero-cover-banner" @if(!empty($config['cover'])) style="background-image:url('{{ asset('storage/' . $config['cover']) }}');" @endif></div>

                    {{-- Avatar & Online Badge --}}
                    <div class="profile-avatar-container">
                        <div class="avatar-ring">
                            @if(!empty($config['avatar']))
                                <img src="{{ asset('storage/' . $config['avatar']) }}" alt="{{ $config['name'] ?? '' }}">
                            @else
                                <div style="width:100%;height:100%;background:linear-gradient(135deg,var(--accent),#3b82f6);display:flex;align-items:center;justify-content:center;font-size:2.4rem;font-weight:900;color:#fff;">
                                    {{ strtoupper(substr($config['name'] ?? $username, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="status-badge-online">
                            <span class="pulse-dot"></span> Online
                        </div>
                    </div>

                    {{-- Card Details --}}
                    <div class="profile-card-body">
                        <h1 class="profile-name-heading">
                            {{ $config['name'] ?? $profile->store_name ?? $username }}
                            <i class="fas fa-check-circle verified-icon" title="Verified Creator"></i>
                        </h1>

                        <div class="role-pill">
                            {{ $roleTitleMap[$profile->bio_role ?? ''] ?? 'Official Creator' }}
                        </div>

                        @if(!empty($config['bio']))
                            <p class="profile-bio-text">{{ $config['bio'] }}</p>
                        @endif

                        @if(!empty($config['location']))
                            <div class="location-pill">
                                <i class="fas fa-map-marker-alt" style="color:var(--accent); font-size:10px;"></i>
                                {{ $config['location'] }}
                            </div>
                        @endif

                        {{-- Quick Creator Stats Bar --}}
                        <div class="creator-stats-grid">
                            <div>
                                <div class="stat-item-val">{{ $totalLinks }}</div>
                                <div class="stat-item-lbl">Links</div>
                            </div>
                            <div>
                                <div class="stat-item-val">{{ $totalProds }}</div>
                                <div class="stat-item-lbl">Produk</div>
                            </div>
                            <div>
                                <div class="stat-item-val"><i class="fas fa-shield-alt" style="color:#22c55e;"></i></div>
                                <div class="stat-item-lbl">Verified</div>
                            </div>
                        </div>

                        {{-- Social Links Row --}}
                        <div class="social-icons-wrapper">
                            @include('bio._social_icons')
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Showcase Content Area --}}
            <div class="right-showcase-area">
                
                {{-- Welcome Banner --}}
                <div class="landing-top-hero">
                    <div>
                        <div class="hero-welcome-title">Official Digital Portal 👋</div>
                        <div class="hero-welcome-sub">Temukan tautan resmi & rekomendasi produk terbaik dari {{ $config['name'] ?? $username }}.</div>
                    </div>
                </div>

                {{-- Live Search Box --}}
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
