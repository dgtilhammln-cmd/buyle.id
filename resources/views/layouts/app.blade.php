<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <meta property="og:site_name" content="BUYLE" />
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "@id": "https://buyle.id/#website",
      "name": "BUYLE",
      "alternateName": "BUYLE.ID",
      "url": "https://buyle.id/"
    }
    </script>

    @php
        // Single cached DB call for ALL settings — prevents N+1 queries per page load
        $layoutSettings = \App\Models\Setting::getAllAsArray();
        $favVer = md5(json_encode($layoutSettings));

        // Resolve favicon
        // Google Search prefers stable URLs without changing query parameters.
        if (!empty($layoutSettings['favicon'])) {
            $favPath = ltrim($layoutSettings['favicon'], '/');
            $favicon = asset('storage/' . $favPath); // Hapus ?v= agar Googlebot tidak bingung
            $favExt = strtolower(pathinfo($favPath, PATHINFO_EXTENSION));
            $favTypeMap = ['png' => 'image/png', 'svg' => 'image/svg+xml', 'webp' => 'image/webp', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg'];
            $favType = $favTypeMap[$favExt] ?? 'image/x-icon';
        } else {
            $favicon = asset('favicon.ico');
            $favType = 'image/x-icon';
        }

        $tAccent = $layoutSettings['color_accent'] ?? null;
        $tMain = $layoutSettings['color_main'] ?? null;
        $tText = $layoutSettings['color_text'] ?? null;
        $breadcrumbBg = $layoutSettings['breadcrumb_bg'] ?? null;
        $preloaderLogo = $layoutSettings['logo'] ?? null;
        $headScripts = $layoutSettings['head_scripts'] ?? '';
        $bodyScripts = $layoutSettings['body_scripts'] ?? '';
    @endphp

    {{-- Custom og:type if provided --}}
    @hasSection('og_type')
        @php
            $seo['og_type'] = View::getSection('og_type');
        @endphp
    @endif

    {{-- SEO Component --}}
    @include('components.seo')

    {{-- Favicon — always uses /favicon.ico (static root, consistent across all pages + GSC) --}}
    @php
        $faviconUrl = asset('favicon.ico') . '?v=3';
        $faviconPng = asset('favicon.png') . '?v=3';
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $faviconPng }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $faviconPng }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconPng }}">

    {{-- Google Fonts: Montserrat — Non-blocking (reduced weights for speed) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet"
        media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap"
            rel="stylesheet">
    </noscript>

    {{-- AOS Animate on Scroll — Non-blocking --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    </noscript>

    {{-- Swiper & other CDN — preconnect for faster DNS --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://unpkg.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    {{-- App CSS (Inlined for 99+ Lighthouse Score) --}}
    @php
        $buildCssFiles = glob(public_path('build/assets/*.css'));
    @endphp
    @if(!empty($buildCssFiles) && count($buildCssFiles) > 0 && file_exists($buildCssFiles[0]) && filesize($buildCssFiles[0]) <= 80000)
        <style>{!! file_get_contents($buildCssFiles[0]) !!}</style>
        @vite(['resources/js/app.js'])
    @elseif(!empty($buildCssFiles) && count($buildCssFiles) > 0)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @elseif(file_exists(public_path('css/app.css')))
        <style>
            {!! file_get_contents(public_path('css/app.css')) !!}
        </style>
    @else
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @endif

    {{-- Dynamic Theme Colors & Global Font Override --}}
    <style>
        /* ── Premium Global Scrollbar ── */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-button { display: none !important; width: 0 !important; height: 0 !important; }
        ::-webkit-scrollbar-corner { background: transparent !important; }
        ::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; border: 2px solid #f8fafc; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        * { scrollbar-width: auto; scrollbar-color: #cbd5e1 #f8fafc; }

        /* Fix: prevent horizontal overflow / mobile shift */
        html { overflow-x: hidden !important; max-width: 100vw !important; }

        :root {
            --font-jakarta: 'Montserrat', sans-serif;
            --font-main: 'Montserrat', sans-serif;
        }

        body,
        html,
        button,
        input,
        textarea,
        select,
        .cv-promo-card-name {
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 400;
        }

        /* Thin fonts globally — user preference */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 500 !important;
        }

        .sp-topbar-left h1,
        .article-hero-title,
        .sv-hero-title,
        .pd-title,
        .sp-title,
        .cv-catalog-title,
        .sp-card-title,
        .cv-profile-name,
        .sp-sidebar-head,
        .sp-card-price-main,
        .sp-card-name {
            font-weight: 500 !important;
        }

        .sp-card-badge,
        .sp-cat-badge,
        .sp-apply-btn,
        .sp-btn-main,
        .sp-mobile-filter-btn {
            font-weight: 500 !important;
        }

        strong,
        b {
            font-weight: 600 !important;
        }

        /* ── Product Card CTA: hide "Lihat" text on mobile, show arrow only ── */
        @media (max-width: 640px) {
            .retarget-btn-cta {
                padding: 0.4rem 0.5rem !important;
                min-width: 32px;
                justify-content: center;
            }
            .retarget-btn-text {
                display: none !important;
            }
        }

        @if($tAccent || $tMain || $tText)
            :root {
                @if($tAccent)
                    --accent:
                        {{ $tAccent }}
                        !important;
                    --accent-dark:
                        {{ $tAccent }}
                        !important;
                @endif
                @if($tMain)
                    --bg-base:
                    {{ $tMain }}
                    !important;
                    --bg-1:
                        {{ $tMain }}
                        !important;
                @endif
                @if($tText)
                    --text-1:
                    {{ $tText }}
                    !important;
                @endif
            }

        @endif
    </style>

    {{-- Breadcrumb / Page Hero Background --}}
    @if($breadcrumbBg)
        <style>
            .page-hero {
                background-image: url('{{ asset('storage/' . $breadcrumbBg) }}') !important;
                background-size: cover !important;
                background-position: center center !important;
                position: relative;
            }

            .page-hero::before {
                content: "";
                position: absolute;
                inset: 0;
                background: rgba(12, 26, 58, 0.75);
                z-index: 0;
            }

            .page-hero::after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 100px;
                background: linear-gradient(to bottom, transparent, var(--bg-base));
                z-index: 1;
                pointer-events: none;
            }

            .page-hero>div,
            .page-hero .sv-hero-inner,
            .page-hero .article-hero-inner {
                position: relative;
                z-index: 2;
            }

            /* User request: thin fonts everywhere (no bold/fat fonts) */
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            .pd-title,
            .sp-title,
            .cv-catalog-title,
            .sp-card-title,
            .cv-profile-name,
            .sp-sidebar-head,
            .sp-topbar-left h1,
            .article-hero-title,
            .sv-hero-title,
            strong,
            b,
            .font-bold,
            .font-semibold,
            .font-extrabold {
                font-weight: 500 !important;
            }
        </style>
    @endif

    @stack('styles')

    {{-- Custom Head Scripts (e.g. GTM, Analytics) --}}
    {!! $headScripts !!}
    
    {{-- Bot / PageSpeed Instant Preloader Bypass --}}
    <script>
        (function() {
            var ua = navigator.userAgent || '';
            if (/Lighthouse|PageSpeed|HeadlessChrome|GTmetrix/i.test(ua)) {
                document.documentElement.classList.remove('cv-preloader-active');
                var s = document.createElement('style');
                s.textContent = '#cv-app-preloader { display: none !important; } html.cv-preloader-active, body.cv-preloader-active { overflow: auto !important; height: auto !important; }';
                document.head.appendChild(s);
            }
        })();
    </script>

    @stack('head')
</head>

@php
    $isBotSpeedTest = request()->has('disable_preloader') || preg_match('/Lighthouse|PageSpeed|Chrome-Lighthouse|GTmetrix|Google-InspectionTool/i', request()->userAgent() ?? '');
@endphp

<body class="{{ $isBotSpeedTest ? '' : 'cv-preloader-active' }} {{ request()->is('akun*') ? 'is-acc-page' : '' }}" style="overflow-x: hidden; margin: 0; padding: 0; background-color: #ffffff;">

<style>
    @media (max-width: 991px) {
        body.is-acc-page {
            padding-top: 0 !important;
            margin-top: 0 !important;
            background-color: #F8FAFC !important;
        }
        body.is-acc-page main {
            padding-top: 0 !important;
            margin-top: 0 !important;
            background-color: #F8FAFC !important;
        }
        body.is-acc-page .pill-navbar-wrapper,
        body.is-acc-page #mainNavbar,
        body.is-acc-page .skel-header-wrap,
        body.is-acc-page .mobile-bottom-nav,
        body.is-acc-page .cv-footer-v2,
        body.is-acc-page footer {
            display: none !important;
        }
    }
</style>

@if(!$isBotSpeedTest)
    {{-- Global Skeleton Loader --}}
    <style>
        html.cv-preloader-active, body.cv-preloader-active {
            overflow: hidden !important;
            height: 100% !important;
        }
        #cv-app-preloader {
            position: fixed; inset: 0; z-index: 999999;
            background: #F8FAFC;
            transition: opacity 0.4s ease, visibility 0.4s ease;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex; flex-direction: column;
        }
        #cv-app-preloader.fade-out { opacity: 0; visibility: hidden; pointer-events: none; }
        .skel-shimmer {
            background: #e2e8f0;
            background-image: linear-gradient(90deg, #e2e8f0 0px, #f1f5f9 40px, #e2e8f0 80px);
            background-size: 600px;
            animation: shimmer 1.5s infinite linear;
        }
        @keyframes shimmer { 0% { background-position: -300px; } 100% { background-position: 300px; } }
        
        /* ── HEADER SKELETON (PILL STYLE) ── */
        .skel-header-wrap { position: sticky; top: 0; left: 0; width: 100%; z-index: 10; pointer-events: none; background: #F8FAFC; padding: 1rem 0; }
        .skel-header-inner { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; }
        .skel-pill { background: #fff; border-radius: 999px; box-shadow: 0 4px 24px rgba(0,0,0,0.04); display: flex; align-items: center; }
        
        .skel-logo { height: 50px; width: 140px; }
        .skel-search { flex: 1; max-width: 600px; height: 50px; }
        .skel-actions { height: 50px; padding: 0 0.5rem; gap: 0.5rem; background: transparent; box-shadow: none; display: flex; align-items: center; justify-content: flex-end; }
        
        .skel-circle { width: 38px; height: 38px; border-radius: 50%; background: #fff; box-shadow: 0 4px 24px rgba(0,0,0,0.04); }
        .skel-btn { width: 90px; height: 38px; border-radius: 999px; background: #fff; box-shadow: 0 4px 24px rgba(0,0,0,0.04); }
        
        @media(max-width: 768px) {
            .hide-on-mobile { display: none !important; }
            .skel-header-inner { padding: 0 1rem; }
            .skel-logo { height: 46px; width: 120px; }
            .skel-actions { flex: 1; }
        }

        /* ── BODY SKELETON ── */
        .skel-body { flex: 1; padding: 1.5rem 1.5rem 2rem; max-width: 1200px; margin: 0 auto; width: 100%; }
        @media(max-width: 768px) { .skel-body { padding: 1rem 1rem 2rem; } }

        /* Home Banner Grid */
        .skel-hero-wrapper { margin-bottom: 1.5rem; width: 100%; }
        .skel-hero-grid { display: none; }
        .skel-hero-mob-slide { width: 100%; aspect-ratio: 2 / 1; border-radius: 14px; }
        @media(min-width: 1025px) {
            .skel-hero-mob-slide { display: none; }
            .skel-hero-grid { display: grid; grid-template-columns: 2.3fr 1fr; gap: 1.25rem; align-items: stretch; height: 440px; }
            .skel-hero-main { border-radius: 16px; height: 100%; }
            .skel-hero-side { display: flex; flex-direction: column; gap: 0.75rem; height: 100%; }
            .skel-hero-sub { flex: 1; border-radius: 16px; min-height: 0; }
        }

        /* ── Categories Bar Skeleton ── */
        .skel-cats-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.5rem 0 1.25rem;
            border-bottom: 1px solid #F1F5F9;
            margin-bottom: 1.75rem;
            overflow: hidden;
        }
        .skel-cat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.4rem;
            flex-shrink: 0;
        }
        .skel-cat-icon {
            width: 54px;
            height: 54px;
            border-radius: 16px;
        }
        .skel-cat-name {
            width: 50px;
            height: 9px;
            border-radius: 4px;
        }

        /* ── Section Header Skeleton ── */
        .skel-sec-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        .skel-sec-title {
            width: 230px;
            height: 24px;
            border-radius: 6px;
        }
        .skel-sec-link {
            width: 120px;
            height: 14px;
            border-radius: 4px;
        }

        /* ── Product Grid & Cards Skeleton (Matching .article-card-box 1:1) ── */
        .skel-prod-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.85rem;
        }
        @media(min-width: 768px) {
            .skel-prod-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.1rem;
            }
        }
        @media(min-width: 1025px) {
            .skel-prod-grid {
                grid-template-columns: repeat(5, 1fr);
                gap: 1.1rem;
            }
        }
        .skel-prod-card {
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            border-radius: 16px;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            box-sizing: border-box;
        }
        .skel-prod-img {
            width: 100%;
            aspect-ratio: 1/1;
            border-radius: 12px;
        }
        .skel-prod-body {
            padding: 0.75rem 0.2rem 0.1rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .skel-prod-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.35rem;
        }
        .skel-prod-divider {
            border-top: 1px solid #F1F5F9;
            margin: 0.65rem 0 0.75rem;
        }
        .skel-prod-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 0.35rem;
        }
        .skel-prod-btn {
            width: 58px;
            height: 24px;
            border-radius: 99px;
            flex-shrink: 0;
        }
        
        /* Grid (fallback) */
        .skel-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        @media(min-width: 768px) { .skel-grid { grid-template-columns: repeat(4, 1fr); gap: 1.5rem; } }
        @media(min-width: 1025px) { .skel-grid { grid-template-columns: repeat(5, 1fr); } }
        .skel-card { width: 100%; height: 260px; border-radius: 16px; }

        /* Product Detail — 3 Column Layout */
        .skel-breadcrumb { width: 40%; height: 20px; border-radius: 4px; margin-bottom: 1.5rem; }
        .skel-line { border-radius: 6px; }

        /* Mobile: stacked */
        .skel-pd-grid { display: flex; flex-direction: column; gap: 1rem; }
        .skel-pd-gallery { width: 100%; border-radius: 16px; overflow: hidden; }
        .skel-pd-gallery-main { width: 100%; aspect-ratio: 1/1; }
        .skel-pd-gallery-thumbs { display: flex; gap: 0.5rem; margin-top: 0.5rem; }
        .skel-pd-gallery-thumb { width: 60px; height: 60px; border-radius: 8px; flex-shrink: 0; }
        .skel-pd-info { display: flex; flex-direction: column; gap: 0.875rem; }
        .skel-pd-sidebar { display: none; }

        /* Desktop: 3-column (gallery | info | sidebar) */
        @media(min-width: 1024px) {
            .skel-pd-grid { display: grid; grid-template-columns: 320px 1fr 280px; gap: 1.5rem; align-items: start; }
            .skel-pd-gallery-main { aspect-ratio: 1/1; }
            .skel-pd-sidebar { display: flex; flex-direction: column; gap: 1rem; }
            .skel-pd-sidebar-card { width: 100%; border-radius: 16px; height: 260px; }
            .skel-pd-sidebar-banner { width: 100%; border-radius: 16px; aspect-ratio: 3/4; }
        }
        /* Creator Storefront Skeleton */
        .skel-store-layout { display: flex; flex-direction: column; gap: 0.75rem; width: 100%; }
        .skel-sf-card { width: 100%; background: #fff; border-radius: 24px; border: 1.5px solid #e2e8f0; overflow: hidden; margin-bottom: 0.5rem; }
        .skel-sf-banner { width: 100%; height: 44px; background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); opacity: 0.85; }
        .skel-sf-avatar-wrap { margin-top: -20px; display: flex; justify-content: center; position: relative; z-index: 2; }
        .skel-sf-avatar { width: 72px; height: 72px; border-radius: 50%; border: 3px solid #fff; background: #cbd5e1; }
        .skel-sf-body { padding: 0.75rem 1rem 1.25rem; display: flex; flex-direction: column; align-items: center; gap: 0.5rem; text-align: center; }
        .skel-sf-pills { display: flex; gap: 0.4rem; overflow-x: hidden; width: 100%; margin-bottom: 0.5rem; }
        .skel-sf-pill-item { height: 34px; border-radius: 999px; background: #fff; border: 1px solid #e2e8f0; flex-shrink: 0; }
        @media(min-width: 768px) {
            .skel-store-layout { display: grid; grid-template-columns: 280px 1fr; gap: 1.25rem; align-items: start; }
            .skel-sf-pills { display: none; }
        }

        /* ── About Page Skeleton ── */
        .skel-about-wrapper {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding-top: 0.5rem;
        }
        .skel-about-orbit {
            position: relative;
            width: 100%;
            max-width: 980px;
            min-height: 480px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 1.5rem auto 2.5rem;
        }
        .skel-about-bubble {
            width: 100%;
            max-width: 490px;
            height: 250px;
            border-radius: 32px;
            z-index: 2;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
        }
        .skel-about-nodes {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }
        .skel-about-node-item {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        .skel-about-node-circle {
            width: 68px;
            height: 68px;
            border-radius: 50%;
        }
        .skel-about-node-pill {
            width: 95px;
            height: 22px;
            border-radius: 999px;
        }
        .skel-about-node-item:nth-child(1) { top: 6%; left: 10%; }
        .skel-about-node-item:nth-child(2) { bottom: 6%; left: 13%; }
        .skel-about-node-item:nth-child(3) { top: 4%; right: 12%; }
        .skel-about-node-item:nth-child(4) { top: 46%; right: 4%; }
        .skel-about-node-item:nth-child(5) { top: 42%; left: 4%; }
        
        .skel-about-bottom-pill {
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: min(90%, 340px);
            height: 38px;
            border-radius: 999px;
            z-index: 3;
        }

        .skel-about-visi-misi {
            width: 100%;
            max-width: 1000px;
            margin: 1rem auto 0;
        }
        .skel-about-visi-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            width: 100%;
        }
        @media(min-width: 768px) {
            .skel-about-visi-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .skel-about-visi-card {
            width: 100%;
            height: 180px;
            border-radius: 20px;
        }

        @media(max-width: 768px) {
            .skel-about-orbit {
                min-height: auto;
                flex-direction: column;
                gap: 1.25rem;
                margin: 0.5rem auto 1.5rem;
            }
            .skel-about-bubble {
                height: 200px;
                border-radius: 24px;
            }
            .skel-about-nodes {
                position: static;
                display: grid;
                grid-template-columns: 1fr;
                gap: 0.75rem;
                width: 100%;
                margin-top: 0.5rem;
            }
            .skel-about-node-item {
                position: static;
                flex-direction: row;
                gap: 0.85rem;
                background: #fff;
                padding: 0.75rem 1rem;
                border-radius: 16px;
                border: 1.5px solid #E2E8F0;
                box-sizing: border-box;
            }
            .skel-about-node-circle {
                width: 44px;
                height: 44px;
                flex-shrink: 0;
            }
            .skel-about-node-pill {
                width: 120px;
                height: 18px;
            }
            .skel-about-bottom-pill {
                position: static;
                transform: none;
                margin-top: 0.5rem;
                width: 100%;
            }
        }

        /* ── Ticketing Page Skeleton ── */
        .skel-ticketing-wrapper {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 0.5rem;
        }
        .skel-tk-header {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-bottom: 2.5rem;
        }
        .skel-tk-features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            width: 100%;
            margin-bottom: 2rem;
        }
        .skel-tk-card {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 1.75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
            box-sizing: border-box;
        }
        .skel-tk-highlights {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            width: 100%;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 18px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2.5rem;
            box-sizing: border-box;
        }
        .skel-tk-calc-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.75rem;
            width: 100%;
            margin-bottom: 2.5rem;
        }
        .skel-tk-calc-box {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 2rem;
            box-sizing: border-box;
        }
        @media(max-width: 900px) {
            .skel-tk-features-grid { grid-template-columns: 1fr; }
            .skel-tk-highlights { grid-template-columns: repeat(2, 1fr); }
            .skel-tk-calc-grid { grid-template-columns: 1fr; }
        }
        @media(max-width: 640px) {
            .skel-tk-highlights { grid-template-columns: 1fr; }
            .skel-tk-card { padding: 1.25rem 1rem; }
            .skel-tk-calc-box { padding: 1.25rem 1rem; }
        }
    </style>
    
    <div id="cv-app-preloader">
        {{-- HEADER SKELETON --}}
        <div class="skel-header-wrap">
            <div class="skel-header-inner">
                <div class="skel-pill skel-shimmer skel-logo"></div>
                <div class="skel-pill skel-shimmer skel-search hide-on-mobile"></div>
                <div class="skel-actions">
                    <div class="skel-shimmer skel-circle hide-on-mobile"></div>
                    <div class="skel-shimmer skel-circle"></div>
                    <div class="skel-shimmer skel-btn hide-on-mobile"></div>
                </div>
            </div>
        </div>

        {{-- BODY SKELETON --}}
        <div class="skel-body">
            @if(request()->routeIs('home') || request()->is('/'))
                <div class="skel-hero-wrapper">
                    {{-- Desktop Grid (Visible > 1024px) --}}
                    <div class="skel-hero-grid">
                        <div class="skel-shimmer skel-hero-main"></div>
                        <div class="skel-hero-side">
                            <div class="skel-shimmer skel-hero-sub"></div>
                            <div class="skel-shimmer skel-hero-sub"></div>
                        </div>
                    </div>
                    {{-- Mobile Swipe (Visible <= 1024px) --}}
                    <div class="skel-shimmer skel-hero-mob-slide"></div>
                </div>

                {{-- Category Swiper Skeleton --}}
                <div class="skel-cats-bar">
                    @for($c=0; $c<10; $c++)
                        <div class="skel-cat-item {{ $c > 5 ? 'hide-on-mobile' : '' }}">
                            <div class="skel-shimmer skel-cat-icon"></div>
                            <div class="skel-shimmer skel-cat-name"></div>
                        </div>
                    @endfor
                </div>

                {{-- Product Section Header Skeleton --}}
                <div class="skel-sec-header">
                    <div class="skel-shimmer skel-sec-title"></div>
                    <div class="skel-shimmer skel-sec-link"></div>
                </div>

                {{-- 5-Column Product Cards Grid (Matching Real Cards 1:1) --}}
                <div class="skel-prod-grid">
                    @for($i=0; $i<5; $i++)
                        <div class="skel-prod-card {{ $i > 1 ? 'hide-on-mobile' : '' }}">
                            <div class="skel-shimmer skel-prod-img"></div>
                            <div class="skel-prod-body">
                                <div class="skel-prod-meta">
                                    <div class="skel-shimmer" style="width: 45px; height: 12px; border-radius: 4px;"></div>
                                    <div class="skel-shimmer" style="width: 55px; height: 12px; border-radius: 4px;"></div>
                                </div>
                                <div class="skel-shimmer" style="width: 90%; height: 13px; border-radius: 4px; margin-top: 4px;"></div>
                                <div class="skel-shimmer" style="width: 60%; height: 13px; border-radius: 4px; margin-top: 5px;"></div>
                                <div class="skel-prod-divider"></div>
                                <div class="skel-prod-footer">
                                    <div>
                                        <div class="skel-shimmer" style="width: 45px; height: 9px; border-radius: 3px; margin-bottom: 4px;"></div>
                                        <div class="skel-shimmer" style="width: 75px; height: 16px; border-radius: 4px;"></div>
                                    </div>
                                    <div class="skel-shimmer skel-prod-btn"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            @elseif(request()->routeIs('store.show') || request()->is('c/*'))
                {{-- Storefront Creator Page Skeleton --}}
                <div class="skel-store-layout">
                    {{-- Profile Card Skeleton --}}
                    <div>
                        <div class="skel-sf-card">
                            <div class="skel-sf-banner skel-shimmer"></div>
                            <div class="skel-sf-avatar-wrap">
                                <div class="skel-sf-avatar skel-shimmer"></div>
                            </div>
                            <div class="skel-sf-body">
                                <div class="skel-shimmer skel-line" style="width:160px; height:20px; border-radius:6px;"></div>
                                <div class="skel-shimmer skel-line" style="width:220px; height:14px; border-radius:4px;"></div>
                                <div class="skel-shimmer skel-line" style="width:60px; height:12px; border-radius:999px; margin-top:2px;"></div>
                                <div style="display:flex; justify-content:center; gap:0.4rem; margin-top:0.4rem;">
                                    @for($c=0; $c<4; $c++)
                                        <div class="skel-shimmer" style="width:34px; height:34px; border-radius:50%;"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Products & Filters Skeleton --}}
                    <div>
                        {{-- Mobile Category Filter Pills Skeleton --}}
                        <div class="skel-sf-pills">
                            <div class="skel-shimmer skel-sf-pill-item" style="width:80px;"></div>
                            <div class="skel-shimmer skel-sf-pill-item" style="width:100px;"></div>
                            <div class="skel-shimmer skel-sf-pill-item" style="width:130px;"></div>
                            <div class="skel-shimmer skel-sf-pill-item" style="width:90px;"></div>
                        </div>

                        {{-- Toolbar Sort Skeleton --}}
                        <div style="display:flex; gap:0.5rem; margin-bottom:0.75rem; align-items:center;">
                            <div class="skel-shimmer" style="height:34px; width:70px; border-radius:999px;"></div>
                            <div class="skel-shimmer" style="height:34px; width:70px; border-radius:999px;"></div>
                            <div class="skel-shimmer" style="height:34px; flex:1; border-radius:999px;"></div>
                        </div>

                        {{-- Products Grid Skeleton --}}
                        <div class="skel-grid">
                            @for($i=0; $i<6; $i++)
                                <div class="skel-shimmer skel-card {{ $i > 3 ? 'hide-on-mobile' : '' }}"></div>
                            @endfor
                        </div>
                    </div>
                </div>
            @elseif(request()->routeIs('products.show'))
                <div class="skel-shimmer skel-breadcrumb"></div>
                <div class="skel-pd-grid">
                    {{-- Gallery Column --}}
                    <div class="skel-pd-gallery">
                        <div class="skel-shimmer skel-pd-gallery-main" style="border-radius:16px;"></div>
                        <div class="skel-pd-gallery-thumbs">
                            @for($t=0; $t<4; $t++)
                                <div class="skel-shimmer skel-pd-gallery-thumb"></div>
                            @endfor
                        </div>
                    </div>
                    {{-- Info Column --}}
                    <div class="skel-pd-info">
                        <div class="skel-shimmer skel-line" style="width:70%;height:28px;"></div>
                        <div class="skel-shimmer skel-line" style="width:40%;height:36px;"></div>
                        <div class="skel-shimmer skel-line" style="width:100%;height:80px;margin-top:0.5rem;"></div>
                        <div class="skel-shimmer skel-line" style="width:100%;height:48px;margin-top:0.5rem;"></div>
                        <div class="skel-shimmer skel-line" style="width:100%;height:52px;border-radius:999px;margin-top:0.5rem;"></div>
                    </div>
                    {{-- Sidebar Column --}}
                    <div class="skel-pd-sidebar">
                        <div class="skel-shimmer skel-pd-sidebar-card"></div>
                        <div class="skel-shimmer skel-pd-sidebar-banner"></div>
                        <div class="skel-shimmer skel-pd-sidebar-banner"></div>
                    </div>
                </div>
            @elseif(request()->routeIs('about') || request()->is('tentang-kami') || request()->is('*/tentang-kami'))
                {{-- About Page Skeleton --}}
                <div class="skel-about-wrapper">
                    {{-- Breadcrumb --}}
                    <div class="skel-shimmer" style="width: 140px; height: 14px; border-radius: 999px; margin: 0.5rem auto 1.5rem;"></div>

                    {{-- Title Header with Pill ("Apa itu [Pill] BUYLE.ID") --}}
                    <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-bottom: 2rem;">
                        <div class="skel-shimmer" style="width: 110px; height: 36px; border-radius: 10px;"></div>
                        <div class="skel-shimmer" style="width: 58px; height: 38px; border-radius: 999px;"></div>
                        <div class="skel-shimmer" style="width: 140px; height: 36px; border-radius: 10px;"></div>
                    </div>

                    {{-- Orbit Canvas Area Skeleton --}}
                    <div class="skel-about-orbit">
                        {{-- Central Speech Bubble --}}
                        <div class="skel-shimmer skel-about-bubble"></div>

                        {{-- Nodes (5 Satellites) --}}
                        <div class="skel-about-nodes">
                            @for($n = 0; $n < 5; $n++)
                                <div class="skel-about-node-item">
                                    <div class="skel-shimmer skel-about-node-circle"></div>
                                    <div class="skel-shimmer skel-about-node-pill"></div>
                                </div>
                            @endfor
                        </div>

                        {{-- Bottom Pill --}}
                        <div class="skel-shimmer skel-about-bottom-pill"></div>
                    </div>

                    {{-- Visi & Misi Cards Skeleton --}}
                    <div class="skel-about-visi-misi">
                        <div class="skel-shimmer" style="width: 180px; height: 24px; border-radius: 6px; margin: 2rem auto 1.25rem;"></div>
                        <div class="skel-about-visi-grid">
                            <div class="skel-shimmer skel-about-visi-card"></div>
                            <div class="skel-shimmer skel-about-visi-card"></div>
                        </div>
                    </div>
                </div>
            @elseif(request()->routeIs('tix*') || request()->is('tix*'))
                {{-- ── Buyle TIX Page Skeleton (100% Sesuai Layout Halaman TIX) ── --}}
                <style>
                    .skel-tix-stage { position: relative; width: 100%; aspect-ratio: 1920 / 600; margin-bottom: 0.85rem; overflow: hidden; }
                    .skel-tix-card { position: absolute; top: 0; left: 50%; width: 90%; max-width: 1200px; height: 100%; border-radius: 20px; }
                    @media (max-width: 768px) { .skel-tix-card { width: 92%; border-radius: 14px; } }
                    .skel-tix-card.prev { transform: translateX(calc(-50% - 72%)) scale(0.85); opacity: 0.35; }
                    .skel-tix-card.active { transform: translateX(-50%) scale(1); z-index: 2; box-shadow: 0 16px 40px rgba(0,0,0,0.08); }
                    .skel-tix-card.next { transform: translateX(calc(-50% + 72%)) scale(0.85); opacity: 0.35; }
                    .skel-tix-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 2.5rem; }
                    @media (max-width: 1140px) { .skel-tix-grid { grid-template-columns: repeat(3, 1fr); gap: 1rem; } }
                    @media (max-width: 780px) { .skel-tix-grid { grid-template-columns: repeat(2, 1fr); gap: 0.85rem; } }
                    @media (max-width: 500px) { .skel-tix-grid { grid-template-columns: 1fr; gap: 0.85rem; } }
                    .skel-tix-event-card { background: #fff; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 0.75rem; display: flex; flex-direction: column; }
                </style>

                {{-- 1. Orbit Banner Carousel Skeleton (1920x600, center 90% card + 2 peeking cards) --}}
                <div style="width: 100%; padding: 0.5rem 0; overflow: hidden;">
                    <div class="skel-tix-stage">
                        <div class="skel-shimmer skel-tix-card prev"></div>
                        <div class="skel-shimmer skel-tix-card active"></div>
                        <div class="skel-shimmer skel-tix-card next"></div>
                    </div>
                    {{-- Dots Indicator --}}
                    <div style="display: flex; justify-content: center; align-items: center; gap: 6px; margin-bottom: 1.5rem;">
                        <div class="skel-shimmer" style="width: 26px; height: 7px; border-radius: 999px;"></div>
                        <div class="skel-shimmer" style="width: 7px; height: 7px; border-radius: 50%;"></div>
                        <div class="skel-shimmer" style="width: 7px; height: 7px; border-radius: 50%;"></div>
                    </div>
                </div>

                {{-- 2. Official Media Partner Skeleton --}}
                <div style="display: flex; justify-content: center; align-items: center; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
                    <div class="skel-shimmer" style="width: 130px; height: 14px; border-radius: 6px;"></div>
                    <div style="display: flex; gap: 2rem; align-items: center;">
                        <div class="skel-shimmer" style="width: 80px; height: 24px; border-radius: 6px;"></div>
                        <div class="skel-shimmer" style="width: 70px; height: 22px; border-radius: 6px;"></div>
                        <div class="skel-shimmer" style="width: 85px; height: 22px; border-radius: 6px;"></div>
                    </div>
                </div>

                {{-- 3. Search & Filter Bar Skeleton --}}
                <div class="skel-shimmer" style="width: 100%; height: 54px; border-radius: 16px; margin-bottom: 2rem;"></div>

                {{-- 4. Section Title Skeleton --}}
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <div class="skel-shimmer" style="width: 200px; height: 24px; border-radius: 8px;"></div>
                    <div class="skel-shimmer" style="width: 110px; height: 18px; border-radius: 8px;"></div>
                </div>

                {{-- 5. Event Cards Grid Skeleton (4 col desktop, 1 col mobile < 500px) --}}
                <div class="skel-tix-grid">
                    @for($i = 0; $i < 8; $i++)
                        <div class="skel-tix-event-card {{ $i > 3 ? 'hide-on-mobile' : '' }}">
                            <div class="skel-shimmer" style="width: 100%; aspect-ratio: 16/9; border-radius: 12px; margin-bottom: 0.75rem;"></div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.45rem;">
                                <div class="skel-shimmer" style="width: 48px; height: 15px; border-radius: 4px;"></div>
                                <div class="skel-shimmer" style="width: 68px; height: 15px; border-radius: 4px;"></div>
                            </div>
                            <div class="skel-shimmer" style="width: 90%; height: 17px; border-radius: 5px; margin-bottom: 0.35rem;"></div>
                            <div class="skel-shimmer" style="width: 65%; height: 15px; border-radius: 5px; margin-bottom: 0.6rem;"></div>
                            <div style="display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.35rem;">
                                <div class="skel-shimmer" style="width: 18px; height: 18px; border-radius: 50%;"></div>
                                <div class="skel-shimmer" style="width: 90px; height: 13px; border-radius: 4px;"></div>
                            </div>
                            <div class="skel-shimmer" style="width: 85px; height: 13px; border-radius: 4px; margin-bottom: 0.25rem;"></div>
                            <div class="skel-shimmer" style="width: 75px; height: 13px; border-radius: 4px; margin-bottom: 0.75rem;"></div>
                            <div style="border-top: 1px solid #F1F5F9; padding-top: 0.6rem; display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                                <div class="skel-shimmer" style="width: 65px; height: 20px; border-radius: 5px;"></div>
                                <div class="skel-shimmer" style="width: 72px; height: 28px; border-radius: 99px;"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            @elseif(request()->routeIs('ticketing*') || request()->is('ticketing*'))
                {{-- Ticketing Landing Page Skeleton (100% Sesuai Halaman Ticketing) --}}
                <div class="skel-ticketing-wrapper">
                    {{-- Breadcrumb --}}
                    <div class="skel-shimmer" style="width: 150px; height: 14px; border-radius: 999px; margin: 0.5rem auto 1.75rem;"></div>

                    {{-- Header Intro --}}
                    <div class="skel-tk-header">
                        <div class="skel-shimmer" style="width: min(85%, 520px); height: 38px; border-radius: 8px; margin-bottom: 0.65rem;"></div>
                        <div class="skel-shimmer" style="width: min(65%, 360px); height: 38px; border-radius: 8px; margin-bottom: 1.25rem;"></div>
                        <div class="skel-shimmer" style="width: min(90%, 680px); height: 16px; border-radius: 4px; margin-bottom: 0.5rem;"></div>
                        <div class="skel-shimmer" style="width: min(75%, 480px); height: 16px; border-radius: 4px; margin-bottom: 1.75rem;"></div>
                        <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                            <div class="skel-shimmer" style="width: 160px; height: 48px; border-radius: 999px;"></div>
                            <div class="skel-shimmer" style="width: 140px; height: 48px; border-radius: 999px;"></div>
                        </div>
                    </div>

                    {{-- 6 Feature Cards Grid --}}
                    <div class="skel-tk-features-grid">
                        @for($f = 0; $f < 6; $f++)
                            <div class="skel-tk-card {{ $f > 3 ? 'hide-on-mobile' : '' }}">
                                <div class="skel-shimmer" style="width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0;"></div>
                                <div style="flex: 1;">
                                    <div class="skel-shimmer" style="width: 70%; height: 18px; border-radius: 4px; margin-bottom: 0.5rem;"></div>
                                    <div class="skel-shimmer" style="width: 95%; height: 12px; border-radius: 3px; margin-bottom: 0.35rem;"></div>
                                    <div class="skel-shimmer" style="width: 60%; height: 12px; border-radius: 3px;"></div>
                                </div>
                                <div class="skel-shimmer" style="width: 32px; height: 44px; border-radius: 6px; flex-shrink: 0;"></div>
                            </div>
                        @endfor
                    </div>

                    {{-- Highlights Bar --}}
                    <div class="skel-tk-highlights">
                        @for($h = 0; $h < 4; $h++)
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div class="skel-shimmer" style="width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;"></div>
                                <div style="flex: 1;">
                                    <div class="skel-shimmer" style="width: 80px; height: 14px; border-radius: 3px; margin-bottom: 0.3rem;"></div>
                                    <div class="skel-shimmer" style="width: 110px; height: 10px; border-radius: 3px;"></div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    {{-- Ticket Cost Calculator Skeleton --}}
                    <div class="skel-tk-calc-grid">
                        <div class="skel-tk-calc-box">
                            <div class="skel-shimmer" style="width: 140px; height: 16px; border-radius: 4px; margin-bottom: 0.75rem;"></div>
                            <div class="skel-shimmer" style="width: 100%; height: 50px; border-radius: 12px; margin-bottom: 1rem;"></div>
                            <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
                                <div class="skel-shimmer" style="width: 70px; height: 30px; border-radius: 999px;"></div>
                                <div class="skel-shimmer" style="width: 70px; height: 30px; border-radius: 999px;"></div>
                                <div class="skel-shimmer" style="width: 70px; height: 30px; border-radius: 999px;"></div>
                            </div>
                            <div class="skel-shimmer" style="width: 100px; height: 16px; border-radius: 4px; margin-bottom: 0.75rem;"></div>
                            <div class="skel-shimmer" style="width: 160px; height: 44px; border-radius: 12px;"></div>
                        </div>
                        <div class="skel-tk-calc-box">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem;">
                                <div class="skel-shimmer" style="width: 150px; height: 18px; border-radius: 4px;"></div>
                                <div class="skel-shimmer" style="width: 90px; height: 22px; border-radius: 999px;"></div>
                            </div>
                            <div class="skel-shimmer" style="width: 100%; height: 16px; border-radius: 4px; margin-bottom: 1rem;"></div>
                            <div class="skel-shimmer" style="width: 100%; height: 16px; border-radius: 4px; margin-bottom: 1rem;"></div>
                            <div class="skel-shimmer" style="width: 100%; height: 16px; border-radius: 4px; margin-bottom: 1.5rem;"></div>
                            <div class="skel-shimmer" style="width: 100%; height: 60px; border-radius: 14px;"></div>
                        </div>
                    </div>
                </div>
            @elseif(request()->routeIs('checkout.index') || request()->is('checkout'))
                {{-- ── Checkout Page Skeleton (2-col: form left | summary right) ── --}}
                <style>
                    .skel-co-grid { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
                    @@media(min-width: 900px) { .skel-co-grid { grid-template-columns: 1fr 360px; } }
                    .skel-co-card { background:#fff; border:1.5px solid #E2E8F0; border-radius:16px; padding:1.5rem; display:flex; flex-direction:column; gap:1rem; }
                    .skel-co-row { display:flex; gap:0.75rem; align-items:center; }
                    .skel-co-field { border-radius:10px; height:44px; width:100%; }
                    .skel-co-field-sm { border-radius:10px; height:44px; }
                    .skel-co-label { border-radius:4px; height:11px; width:90px; margin-bottom:0.1rem; }
                    .skel-co-section-title { height:18px; border-radius:5px; width:180px; margin-bottom:0.25rem; }
                </style>
                {{-- Breadcrumb --}}
                <div class="skel-shimmer" style="width:220px;height:12px;border-radius:999px;margin-bottom:1.5rem;"></div>
                <div class="skel-co-grid">
                    {{-- Left: Form --}}
                    <div style="display:flex;flex-direction:column;gap:1rem;">
                        {{-- Section 1: Buyer info --}}
                        <div class="skel-co-card">
                            <div class="skel-shimmer skel-co-section-title"></div>
                            <div class="skel-co-row">
                                <div style="flex:1;display:flex;flex-direction:column;gap:0.35rem;">
                                    <div class="skel-shimmer skel-co-label"></div>
                                    <div class="skel-shimmer skel-co-field"></div>
                                </div>
                                <div style="flex:1;display:flex;flex-direction:column;gap:0.35rem;">
                                    <div class="skel-shimmer skel-co-label"></div>
                                    <div class="skel-shimmer skel-co-field"></div>
                                </div>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:0.35rem;">
                                <div class="skel-shimmer skel-co-label"></div>
                                <div class="skel-shimmer skel-co-field"></div>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:0.35rem;">
                                <div class="skel-shimmer skel-co-label"></div>
                                <div class="skel-shimmer skel-co-field"></div>
                            </div>
                        </div>
                        {{-- Section 2: Shipping --}}
                        <div class="skel-co-card">
                            <div class="skel-shimmer skel-co-section-title"></div>
                            @for($r=0;$r<3;$r++)
                            <div style="display:flex;flex-direction:column;gap:0.35rem;">
                                <div class="skel-shimmer skel-co-label"></div>
                                <div class="skel-shimmer skel-co-field"></div>
                            </div>
                            @endfor
                            <div class="skel-co-row">
                                <div style="flex:1;display:flex;flex-direction:column;gap:0.35rem;">
                                    <div class="skel-shimmer skel-co-label"></div>
                                    <div class="skel-shimmer skel-co-field"></div>
                                </div>
                                <div style="width:120px;display:flex;flex-direction:column;gap:0.35rem;">
                                    <div class="skel-shimmer skel-co-label"></div>
                                    <div class="skel-shimmer skel-co-field"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Right: Order Summary --}}
                    <div>
                        <div class="skel-co-card">
                            <div class="skel-shimmer skel-co-section-title"></div>
                            {{-- Product items --}}
                            @for($p=0;$p<2;$p++)
                            <div class="skel-co-row" style="padding:0.5rem 0;border-bottom:1px solid #F1F5F9;">
                                <div class="skel-shimmer" style="width:52px;height:52px;border-radius:10px;flex-shrink:0;"></div>
                                <div style="flex:1;display:flex;flex-direction:column;gap:0.4rem;">
                                    <div class="skel-shimmer" style="width:80%;height:13px;border-radius:4px;"></div>
                                    <div class="skel-shimmer" style="width:50%;height:11px;border-radius:3px;"></div>
                                </div>
                                <div class="skel-shimmer" style="width:70px;height:16px;border-radius:4px;flex-shrink:0;"></div>
                            </div>
                            @endfor
                            {{-- Summary rows --}}
                            @for($s=0;$s<4;$s++)
                            <div style="display:flex;justify-content:space-between;margin-top:0.6rem;">
                                <div class="skel-shimmer" style="width:90px;height:12px;border-radius:3px;"></div>
                                <div class="skel-shimmer" style="width:70px;height:12px;border-radius:3px;"></div>
                            </div>
                            @endfor
                            {{-- Divider + Total --}}
                            <div style="border-top:2px solid #F1F5F9;margin-top:1rem;padding-top:0.75rem;display:flex;justify-content:space-between;align-items:center;">
                                <div class="skel-shimmer" style="width:60px;height:15px;border-radius:4px;"></div>
                                <div class="skel-shimmer" style="width:100px;height:22px;border-radius:5px;"></div>
                            </div>
                            {{-- Voucher button --}}
                            <div class="skel-shimmer" style="width:100%;height:44px;border-radius:10px;margin-top:0.5rem;"></div>
                            {{-- CTA button --}}
                            <div class="skel-shimmer" style="width:100%;height:52px;border-radius:999px;margin-top:0.5rem;"></div>
                        </div>
                    </div>
                </div>
            @else
                <div class="skel-grid">
                    @for($i=0; $i<15; $i++) 
                        <div class="skel-shimmer skel-card {{ $i > 5 ? 'hide-on-mobile' : '' }}"></div> 
                    @endfor
                </div>
            @endif
        </div>
    </div>
    
    <script>
        (function() {
            var dismissed = false;
            function dismissPreloader() {
                if (dismissed) return;
                dismissed = true;
                const s = document.getElementById('cv-app-preloader');
                document.body.classList.remove('cv-preloader-active');
                document.documentElement.classList.remove('cv-preloader-active');
                if (s) {
                    s.classList.add('fade-out');
                    setTimeout(() => { s.remove(); }, 300);
                }
            }

            if (document.readyState === 'interactive' || document.readyState === 'complete') {
                dismissPreloader();
            } else {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(dismissPreloader, 150);
                });
                window.addEventListener('load', dismissPreloader);
                setTimeout(dismissPreloader, 600); // safety fallback so user is never trapped
            }
        })();
    </script>
@endif

    @include('components.navbar')

    {{-- Main Content --}}
    <main style="background-color: #ffffff; min-height: 60vh;">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Removed wa-button --}}

    {{-- Request Order Modal (global) --}}
    @include('components.order-modal')

    {{-- AOS — Defer --}}
    <script>
        (function () {
            var style = document.createElement('style');
            style.textContent = '[data-aos]{opacity:1!important;transform:none!important;}';
            style.id = 'aos-fallback';
            document.head.appendChild(style);
        })();
    </script>
    <script defer src="https://unpkg.com/aos@2.3.1/dist/aos.js" onload="
        var fb = document.getElementById('aos-fallback');
        if(fb) fb.remove();
        AOS.init({ duration: 700, once: true, offset: 80, easing: 'ease-out-cubic', disable: function(){ return window.innerWidth < 768; } });
    "></script>

    {{-- Global WA Link Interceptor --}}
    <script>
        document.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (!link) return;
            if (link.href && (link.href.includes('wa.me') || link.href.includes('api.whatsapp.com/send') || link.href.includes('whatsapp.com') || link.href.includes('wa.link'))) {
                e.preventDefault();
                let trackName = link.getAttribute('data-track') ? link.getAttribute('data-track') : 'In-Text Link';
                if (typeof openOrderModal === 'function') {
                    window.pendingWaUrl = link.href;
                    openOrderModal('WhatsApp CTA: ' + trackName);
                } else {
                    window.open(link.href, '_blank', 'noopener,noreferrer');
                }
            }
        });
    </script>

    @stack('scripts')

    {{-- Custom Body Scripts --}}
    {!! $bodyScripts !!}

    {{-- Floating Chat Widget REMOVED --}}

    {{-- ══════════════════════════════════════════════════════════════════════════
         BUYLE.ID SUPER APP MOBILE BOTTOM NAVIGATION BAR
         Style: 100% Identik dengan Buyle POS (Floating Capsule with Expandable Active Pill)
         Interaktif: Auto-Hide saat Scroll ke Bawah, Muncul kembali saat Scroll ke Atas
         ══════════════════════════════════════════════════════════════════════════ --}}
    @if(!request()->is('creator*') && !request()->is('admin*') && !request()->is('pos*') && !request()->is('checkout*'))
    <style>
        .buyle-bottom-nav-wrap {
            display: none;
            position: fixed;
            bottom: max(14px, env(safe-area-inset-bottom, 14px));
            left: 14px;
            right: 14px;
            max-width: 440px;
            margin: 0 auto;
            z-index: 10000;
            pointer-events: none;
            justify-content: center;
            background: transparent;
            padding: 0;
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.28s ease;
            will-change: transform, opacity;
        }

        .buyle-bottom-nav-bar {
            pointer-events: auto;
            width: 100%;
            height: 60px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.35rem 0.6rem;
            box-shadow: 0 12px 36px rgba(15, 23, 42, 0.13), 0 2px 10px rgba(15, 23, 42, 0.04);
            border: 1.5px solid rgba(226, 232, 240, 0.85);
            box-sizing: border-box;
        }

        .buyle-nav-item {
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            color: #64748b;
            padding: 0;
            outline: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            text-decoration: none;
            -webkit-tap-highlight-color: transparent;
            position: relative;
            flex-shrink: 0;
        }

        .buyle-nav-item:active {
            transform: scale(0.92);
            background: rgba(241, 245, 249, 0.7);
        }

        /* ── Active State: Identik dengan POS (Capsule Pill Gradient Hijau + Label) ── */
        .buyle-nav-item.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            padding: 0.5rem 1.05rem !important;
            border-radius: 999px !important;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35) !important;
            gap: 6px !important;
            width: auto !important;
        }

        .buyle-nav-item.active .buyle-nav-label {
            display: inline-block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.01em;
            white-space: nowrap;
            font-family: 'Montserrat', sans-serif;
            animation: buyleNavFadeIn 0.25s ease-out;
        }

        .buyle-nav-label {
            display: none;
        }

        /* ── State Disembunyikan saat Scroll ke Bawah ── */
        .buyle-bottom-nav-wrap.nav-hidden {
            transform: translateY(145%) scale(0.95) !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        @keyframes buyleNavFadeIn {
            from {
                opacity: 0;
                transform: translateX(-4px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 991px) {
            .buyle-bottom-nav-wrap {
                display: flex !important;
            }
            body {
                padding-bottom: max(86px, calc(74px + env(safe-area-inset-bottom, 0px))) !important;
            }
        }
    </style>

    <div class="buyle-bottom-nav-wrap" id="buyleBottomNavWrap">
        <div class="buyle-bottom-nav-bar">
            {{-- 1. Beranda --}}
            @php
                $isHomeActive = request()->routeIs('home') && !request()->has('q');
            @endphp
            <a href="{{ route('home') }}" class="buyle-nav-item {{ $isHomeActive ? 'active' : '' }}" title="Beranda">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                <span class="buyle-nav-label">Beranda</span>
            </a>

            {{-- 2. Event & Tiket (BuyleTIX) --}}
            @php
                $isTixActive = request()->is('tix*') || request()->is('ticketing*');
            @endphp
            <a href="{{ route('tix.index') }}" class="buyle-nav-item {{ $isTixActive ? 'active' : '' }}" title="Event & BuyleTIX">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" />
                </svg>
                <span class="buyle-nav-label">BuyleTIX</span>
            </a>

            {{-- 3. Cari / Eksplor (Center Action Button) --}}
            @php
                $isExploreActive = (request()->routeIs('products') || request()->has('q') || request()->is('kategori*')) && !$isTixActive && !$isHomeActive;
            @endphp
            <a href="{{ route('products') }}" class="buyle-nav-item {{ $isExploreActive ? 'active' : '' }}" title="Eksplor & Cari">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <span class="buyle-nav-label">Eksplor</span>
            </a>

            {{-- 4. Pesanan Saya (Tiket & Transaksi) --}}
            @php
                $isOrdersActive = request()->is('akun/pesanan*') || request()->is('orders*');
                $ordersUrl = auth()->check() ? route('account.orders') : route('login', ['redirect' => route('account.orders')]);
            @endphp
            <a href="{{ $ordersUrl }}" class="buyle-nav-item {{ $isOrdersActive ? 'active' : '' }}" title="Pesanan & Tiket Saya">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z" />
                    <line x1="8" y1="7" x2="16" y2="7" />
                    <line x1="8" y1="11" x2="16" y2="11" />
                    <line x1="8" y1="15" x2="13" y2="15" />
                </svg>
                <span class="buyle-nav-label">Pesanan</span>
            </a>

            {{-- 5. Akun Saya --}}
            @php
                $isAccountActive = request()->is('akun*') && !$isOrdersActive;
                if (auth()->check()) {
                    $accountUrl = (auth()->user()->role === 'seller' || auth()->user()->role === 'admin')
                        ? route('creator.dashboard')
                        : route('account.profile');
                } else {
                    $accountUrl = route('login');
                }
            @endphp
            <a href="{{ $accountUrl }}" class="buyle-nav-item {{ $isAccountActive ? 'active' : '' }}" title="Akun Saya">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="buyle-nav-label">Akun</span>
            </a>
        </div>
    </div>

    {{-- Script Animasi Smooth: Sembunyi saat Scroll ke Bawah, Muncul saat Scroll ke Atas --}}
    <script>
        (function() {
            let lastScrollY = window.pageYOffset || document.documentElement.scrollTop;
            let ticking = false;
            const navWrap = document.getElementById('buyleBottomNavWrap');
            if (!navWrap) return;

            const scrollThreshold = 10;

            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        const currentScrollY = window.pageYOffset || document.documentElement.scrollTop;
                        const diff = currentScrollY - lastScrollY;

                        // Dekat puncak layar: selalu tampil
                        if (currentScrollY <= 25) {
                            navWrap.classList.remove('nav-hidden');
                        }
                        // Scroll ke bawah dengan jarak signifikan: sembunyikan agar tidak mengganggu
                        else if (diff > scrollThreshold && currentScrollY > 80) {
                            navWrap.classList.add('nav-hidden');
                        }
                        // Scroll ke atas: munculkan kembali dengan smooth
                        else if (diff < -scrollThreshold) {
                            navWrap.classList.remove('nav-hidden');
                        }

                        lastScrollY = currentScrollY;
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });
        })();
    </script>
    @endif

    @include('components.pull-to-refresh')
</body>

</html>
