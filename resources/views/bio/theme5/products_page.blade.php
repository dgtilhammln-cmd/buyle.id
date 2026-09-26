<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $t5SeoTitle = !empty($config['theme5_seo_products_title']) ? $config['theme5_seo_products_title'] : $seoTitle;
        $t5SeoDesc  = !empty($config['theme5_seo_products_desc'])  ? $config['theme5_seo_products_desc']  : $seoDesc;
        $t5SeoKw    = $config['theme5_seo_products_keywords'] ?? '';
    @endphp
    <title>{{ $t5SeoTitle }}</title>
    <meta name="description" content="{{ $t5SeoDesc }}">
    @if(!empty($t5SeoKw))<meta name="keywords" content="{{ $t5SeoKw }}">@endif
    <link rel="canonical" href="{{ $canonical }}">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">

    {{-- Favicon (Custom Theme 5 jika ada, fallback ke default) --}}
    @if(!empty($config['theme5_favicon']))
        <link rel="icon" href="{{ asset('storage/' . $config['theme5_favicon']) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=4">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=4">
    @endif

    <meta property="og:title" content="{{ $t5SeoTitle }}">
    <meta property="og:description" content="{{ $t5SeoDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $t5SeoTitle }}">
    <meta name="twitter:description" content="{{ $t5SeoDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @php
        $bioName = $config['name'] ?? $profile->store_name ?? $username;
        $avatarUrl = null;
        if (!empty($config['avatar']))
            $avatarUrl = asset('storage/' . $config['avatar']);
        elseif (!empty($config['_user_avatar'])) {
            $ua = $config['_user_avatar'];
            $avatarUrl = \Illuminate\Support\Str::startsWith($ua, ['http://', 'https://']) ? $ua : asset('storage/' . $ua);
        }
    @endphp

    @php
        $schemaOrg = \App\Services\BioSchemaBuilder::buildSchema(
            $profile,
            $config,
            $products ?? null,
            $canonical ?? null,
            $seoDesc ?? null,
            $ogImage ?? null
        );
    @endphp
    <script type="application/ld+json">
    {!! json_encode($schemaOrg, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    <style>
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
            --t5-shadow-lg: 0 16px 40px rgba(15, 23, 42, 0.12);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            font-size: 16px;
        }

        body {
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--t5-bg-main);
            color: var(--t5-slate-800);
            line-height: 1.6;
            overflow-x: hidden;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
        }

        /* HEADER & NAVBAR STYLES (MATCH HOME TEMA 5) */
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
        }

        .t5-header-container {
            max-width: 1340px;
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
            gap: 0.65rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .t5-brand-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--t5-emerald-border);
        }

        .t5-brand-avatar-fallback {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--t5-emerald), #15803d);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .t5-brand-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--t5-slate-900);
            letter-spacing: -0.02em;
        }

        .t5-nav-desktop {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .t5-nav-link {
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--t5-slate-600);
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .t5-nav-link:hover,
        .t5-nav-link.active {
            color: var(--t5-emerald-dark);
            font-weight: 600;
        }

        .t5-header-actions {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .t5-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.42rem 0.85rem;
            border-radius: var(--t5-radius-sm);
            border: 1px solid var(--t5-slate-200);
            background: #fff;
            color: var(--t5-slate-700);
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .t5-action-btn:hover {
            border-color: var(--t5-emerald);
            color: var(--t5-emerald-dark);
        }

        .t5-btn-wa {
            background: var(--t5-emerald-light);
            color: var(--t5-emerald-dark);
            border-color: var(--t5-emerald-border);
        }

        .t5-mobile-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--t5-slate-700);
        }

        /* BREADCRUMB BAR */
        .t5-breadcrumb-wrap {
            max-width: 1340px;
            margin: 0 auto;
            padding: 1.25rem 1.5rem 0;
        }

        .t5-breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            color: var(--t5-slate-500);
            flex-wrap: wrap;
        }

        .t5-breadcrumb a {
            color: var(--t5-slate-600);
            text-decoration: none;
            font-weight: 400;
            transition: color 0.2s;
        }

        .t5-breadcrumb a:hover {
            color: var(--t5-emerald);
        }

        .t5-breadcrumb-sep {
            color: var(--t5-slate-400);
        }

        .t5-breadcrumb-current {
            color: var(--t5-slate-900);
            font-weight: 600;
        }

        /* MAIN PAGE CONTAINER & LAYOUT */
        .pp-page-wrap {
            max-width: 1340px;
            margin: 1rem auto 3.5rem;
            padding: 0 1.5rem;
        }

        .pp-main-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 1.75rem;
            align-items: start;
        }

        /* LEFT SIDEBAR CARD (KATEGORI PRODUK) */
        .pp-sidebar-card {
            background: #ffffff;
            border: 1.5px solid var(--t5-slate-200);
            border-radius: 20px;
            padding: 1.35rem 1.2rem;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
            position: sticky;
            top: 90px;
        }

        .pp-sidebar-heading {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--t5-slate-900);
            margin-bottom: 1.1rem;
            padding-left: 0.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pp-sidebar-subheading {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--t5-slate-400);
            margin: 1.25rem 0 0.65rem 0.2rem;
        }

        .pp-sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .pp-sidebar-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 14px;
            width: 100%;
            border: none;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.22s cubic-bezier(0.22, 1, 0.36, 1);
            text-align: left;
            background: #f8fafc;
            color: var(--t5-slate-700);
            font-weight: 500;
            font-size: 0.88rem;
        }

        .pp-sidebar-item:hover {
            background: #f1f5f9;
            color: var(--t5-slate-900);
            transform: translateX(3px);
        }

        .pp-sidebar-item.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 6px 18px rgba(30, 179, 73, 0.28);
            transform: none;
        }

        .pp-item-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }

        .pp-item-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            padding: 0 0.45rem;
            border-radius: 999px;
            background: #e2e8f0;
            color: var(--t5-slate-700);
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .pp-sidebar-item.active .pp-item-badge {
            background: rgba(0, 0, 0, 0.2);
            color: #ffffff;
        }

        /* MAIN CONTENT AREA (RIGHT SIDE) */
        .pp-main-content {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            min-width: 0;
        }

        /* CONTROLS (SEARCH & SORT BAR) */
        .pp-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            background: #ffffff;
            border: 1.5px solid var(--t5-slate-200);
            padding: 0.85rem 1rem;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.02);
        }

        .pp-search-wrap {
            position: relative;
            flex: 1;
            min-width: 220px;
        }

        .pp-search-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--t5-slate-400);
            pointer-events: none;
        }

        .pp-search-input {
            width: 100%;
            padding: 0.62rem 1rem 0.62rem 2.5rem;
            border: 1.5px solid var(--t5-slate-200);
            border-radius: var(--t5-radius-md);
            background: var(--t5-white);
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 400;
            color: var(--t5-slate-800);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .pp-search-input:focus {
            border-color: var(--t5-emerald);
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.1);
        }

        .pp-search-input::placeholder {
            color: var(--t5-slate-400);
        }

        .pp-sort-select {
            padding: 0.62rem 2.2rem 0.62rem 1rem;
            border: 1.5px solid var(--t5-slate-200);
            border-radius: var(--t5-radius-md);
            background: var(--t5-white);
            font-family: 'Montserrat', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--t5-slate-700);
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s;
            appearance: none;
            -webkit-appearance: none;
            min-width: 140px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%2394a3b8' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
        }

        .pp-sort-select:focus {
            border-color: var(--t5-emerald);
        }

        .pp-submit-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.62rem 1.15rem;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #fff;
            border: none;
            border-radius: var(--t5-radius-md);
            font-family: 'Montserrat', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.25);
        }

        .pp-submit-btn:hover {
            background: linear-gradient(135deg, #179b3e 0%, #94bc2e 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(30, 179, 73, 0.35);
        }

        .pp-results-info {
            font-size: 0.82rem;
            font-weight: 400;
            color: var(--t5-slate-500);
            padding-left: 0.2rem;
        }

        .pp-results-info strong {
            font-weight: 600;
            color: var(--t5-slate-700);
        }

        /* GRID (4 COLUMNS DESKTOP INSIDE MAIN CONTENT AREA) */
        .pp-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.1rem;
        }

        .pp-card {
            background: var(--t5-white);
            border-radius: var(--t5-radius-lg);
            border: 1.5px solid var(--t5-slate-200);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .pp-card:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 16px 36px rgba(30, 179, 73, 0.18);
            border-color: #1eb349;
        }

        .pp-card-img-wrap {
            position: relative;
            aspect-ratio: 4/3;
            overflow: hidden;
            background: var(--t5-slate-200);
            display: block;
            text-decoration: none;
        }

        .pp-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .pp-card:hover .pp-card-img {
            transform: scale(1.08);
        }

        .pp-badge-disc {
            position: absolute;
            top: 0.6rem;
            left: 0.6rem;
            background: #ef4444;
            color: #fff;
            font-size: 0.68rem;
            font-weight: 600;
            padding: 0.18rem 0.48rem;
            border-radius: 4px;
            letter-spacing: 0.02em;
        }

        .pp-card-body {
            padding: 0.85rem 0.9rem;
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            flex: 1;
        }

        .pp-card-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pp-rating {
            display: inline-flex;
            align-items: center;
            gap: 0.22rem;
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--t5-amber);
        }

        .pp-verified {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--t5-emerald);
        }

        .pp-card-name {
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--t5-slate-900);
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pp-card-name a {
            color: inherit;
            text-decoration: none;
        }

        .pp-card-name a:hover {
            color: var(--t5-emerald-dark);
        }

        .pp-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.4rem;
            margin-top: auto;
            padding-top: 0.4rem;
            border-top: 1px solid var(--t5-slate-200);
        }

        .pp-price-wrap {
            display: flex;
            flex-direction: column;
            gap: 0.05rem;
        }

        .pp-price-old {
            font-size: 0.68rem;
            font-weight: 400;
            color: var(--t5-slate-400);
            text-decoration: line-through;
        }

        .pp-price-main {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--t5-emerald-dark);
        }

        .pp-buy-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.38rem 0.75rem;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #fff;
            border-radius: var(--t5-radius-sm);
            font-family: 'Montserrat', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            flex-shrink: 0;
            white-space: nowrap;
            box-shadow: 0 3px 10px rgba(30, 179, 73, 0.25);
        }

        .pp-buy-btn:hover {
            background: linear-gradient(135deg, #179b3e 0%, #94bc2e 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(30, 179, 73, 0.35);
            color: #fff;
        }

        /* EMPTY STATE */
        .pp-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 1rem;
            color: var(--t5-slate-500);
        }

        .pp-empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            background: var(--t5-slate-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--t5-slate-400);
        }

        .pp-empty h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--t5-slate-700);
            margin-bottom: 0.4rem;
        }

        .pp-empty p {
            font-size: 0.85rem;
            font-weight: 400;
        }

        .pp-empty-clear {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1.2rem;
            background: var(--t5-emerald-light);
            color: var(--t5-emerald-dark);
            border-radius: var(--t5-radius-sm);
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid var(--t5-emerald-border);
            transition: all 0.2s;
        }

        .pp-empty-clear:hover {
            background: var(--t5-emerald);
            color: #fff;
        }

        /* FLOATING WA BUTTON */
        .t5-floating-wa {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-decoration: none;
        }

        .t5-floating-wa:hover {
            transform: scale(1.1) translateY(-2px);
            box-shadow: 0 12px 30px rgba(37, 211, 102, 0.55);
            color: #ffffff;
        }

        .t5-wa-pulse {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid #25D366;
            animation: t5WaPulse 2s infinite;
            pointer-events: none;
        }

        @keyframes t5WaPulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1.4);
                opacity: 0;
            }
        }

        /* FOOTER STYLES (MATCH HOME TEMA 5) */
        .t5-footer {
            background: #0d0d0d;
            color: #ffffff;
            margin-top: 3rem;
            padding: 3rem 1.5rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
        }

        .t5-footer-container {
            max-width: 1340px;
            margin: 0 auto;
        }

        .t5-footer-main {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 2.5rem;
            margin-bottom: 2.5rem;
            padding-bottom: 2.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .t5-footer-title {
            display: block;
            font-size: 1.3rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.2rem;
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
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.12);
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

        .t5-footer-social {
            margin-top: 1rem;
        }

        .t5-footer-social .social-row {
            justify-content: flex-start !important;
            margin: 0 !important;
        }

        .t5-footer-social .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: background 0.2s, border-color 0.2s;
            flex-shrink: 0;
        }

        .t5-footer-social .social-icon:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .t5-footer-social .social-icon svg {
            display: block;
            filter: brightness(0) invert(1);
        }

        .t5-footer-social .social-icon img {
            display: block;
            filter: none !important;
        }

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
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.05);
            max-height: 160px;
        }

        .t5-footer-map-wrap iframe {
            width: 100% !important;
            height: 150px !important;
            border: 0 !important;
            display: block;
        }

        /* RESPONSIVE LAYOUT BREAKPOINTS (MOBILE COMPACT & PROPORTIONAL) */
        @media (max-width: 1200px) {
            .pp-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 991px) {
            .pp-main-layout {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .pp-sidebar-card {
                position: static;
                padding: 0.75rem 0.85rem;
                border-radius: 14px;
                box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
            }

            .pp-sidebar-heading {
                font-size: 0.85rem;
                margin-bottom: 0.4rem;
            }

            .pp-sidebar-menu {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                overflow-x: auto;
                gap: 0.4rem;
                padding-bottom: 0.2rem;
                -webkit-overflow-scrolling: touch;
            }

            .pp-sidebar-menu::-webkit-scrollbar {
                display: none;
            }

            .pp-sidebar-item {
                flex-shrink: 0;
                width: auto;
                padding: 0.45rem 0.8rem;
                font-size: 0.78rem;
                border-radius: 999px;
            }

            .pp-sidebar-item:hover {
                transform: none;
            }

            .pp-sidebar-subheading {
                display: none;
            }

            .pp-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.85rem;
            }
        }

        @media (max-width: 768px) {
            .t5-breadcrumb-wrap {
                padding: 0.75rem 0.85rem 0;
            }

            .t5-breadcrumb {
                font-size: 0.78rem;
            }

            .pp-page-wrap {
                padding: 0 0.85rem;
                margin: 0.75rem auto 2.5rem;
            }

            .pp-controls {
                padding: 0.65rem 0.75rem;
                border-radius: 14px;
                gap: 0.5rem;
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
            }

            .pp-search-wrap {
                flex: 1 1 180px;
                min-width: 0;
            }

            .pp-search-input {
                padding: 0.5rem 0.75rem 0.5rem 2.2rem;
                font-size: 0.8rem;
            }

            .pp-search-icon {
                left: 0.65rem;
            }

            .pp-sort-select {
                padding: 0.5rem 1.8rem 0.5rem 0.65rem;
                font-size: 0.78rem;
                min-width: 110px;
                flex: 1 1 110px;
            }

            .pp-submit-btn {
                padding: 0.5rem 0.85rem;
                font-size: 0.78rem;
            }

            .pp-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .pp-card {
                border-radius: 14px;
            }

            .pp-card-img-wrap {
                aspect-ratio: 1 / 1;
            }

            .pp-card-body {
                padding: 0.65rem;
                gap: 0.3rem;
            }

            .pp-card-name {
                font-size: 0.82rem;
                line-height: 1.3;
            }

            .pp-price-main {
                font-size: 0.85rem;
            }

            .pp-price-old {
                font-size: 0.65rem;
            }

            .pp-buy-btn {
                padding: 0.35rem 0.6rem;
                font-size: 0.72rem;
                border-radius: 6px;
            }

            .t5-footer-main {
                grid-template-columns: 1fr;
                gap: 1.75rem;
            }
        }

        @media (max-width: 480px) {
            .pp-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.55rem;
            }

            .pp-card-body {
                padding: 0.55rem;
            }

            .pp-card-name {
                font-size: 0.78rem;
            }

            .pp-price-main {
                font-size: 0.82rem;
            }

            .pp-buy-btn {
                padding: 0.3rem 0.5rem;
                font-size: 0.68rem;
            }
        }
    </style>
</head>

<body>

    {{-- HEADER TEMA 5 --}}
    @include('bio.theme5.header', ['products' => $products, 'blocks' => $blocks, 'config' => $config, 'profile' => $profile, 'username' => $username])

    @php
        $homeUrl = !empty($profile->custom_domain) ? 'https://' . rtrim($profile->custom_domain, '/') : url('/' . $username);
        $productsUrl = !empty($profile->custom_domain) ? 'https://' . rtrim($profile->custom_domain, '/') . '/produk' : url('/' . $username . '/produk');
    @endphp

    <div class="t5-breadcrumb-wrap">
        <div class="t5-breadcrumb">
            <a href="{{ $homeUrl }}">Beranda</a>
            <span class="t5-breadcrumb-sep">&rsaquo;</span>
            <span class="t5-breadcrumb-current">Produk / Layanan</span>
        </div>
    </div>

    @php
        $catCounts = [
            'all' => $allProducts->count(),
            'digital' => 0,
            'physical' => 0,
            'service' => 0,
            'makanan' => 0,
            'ticket' => 0,
        ];
        foreach ($allProducts as $pItem) {
            $t = strtolower($pItem['product_type'] ?? 'digital');
            if (in_array($t, ['barang', 'fisik', 'physical']))
                $catCounts['physical']++;
            elseif (in_array($t, ['jasa', 'service', 'layanan']))
                $catCounts['service']++;
            elseif (in_array($t, ['makanan', 'fnb', 'kuliner', 'food']))
                $catCounts['makanan']++;
            elseif (in_array($t, ['ticket', 'tiket']))
                $catCounts['ticket']++;
            else
                $catCounts['digital']++;
        }
    @endphp

    {{-- MAIN PAGE LAYOUT (LEFT SIDEBAR + MAIN CONTENT) --}}
    <div class="pp-page-wrap">
        <div class="pp-main-layout">

            {{-- LEFT SIDEBAR: KATEGORI PRODUK CARD (MATCHES /c/hvmdigital/) --}}
            <aside class="pp-sidebar">
                <div class="pp-sidebar-card">
                    <div class="pp-sidebar-heading">Kategori Produk</div>
                    <div class="pp-sidebar-menu">
                        <button type="button" class="pp-sidebar-item active" onclick="filterGroup('all', this)"
                            title="Tampilkan Semua Produk">
                            <span class="pp-item-title">Semua Produk</span>
                            <span class="pp-item-badge">{{ $allProducts->count() }}</span>
                        </button>

                        {{-- Creator Product Groups from /creator/groups --}}
                        @if(isset($creatorGroups) && $creatorGroups->count() > 0)
                            @foreach($creatorGroups as $cGroup)
                                @php
                                    $gCount = $allProducts->filter(function ($p) use ($cGroup) {
                                        return (string) ($p['creator_group_id'] ?? '') === (string) $cGroup->id;
                                    })->count();
                                @endphp
                                <button type="button" class="pp-sidebar-item"
                                    onclick="filterGroup('group-{{ $cGroup->id }}', this)" title="{{ $cGroup->name }}">
                                    <span class="pp-item-title">{{ $cGroup->name }}</span>
                                    <span class="pp-item-badge">{{ $gCount }}</span>
                                </button>
                            @endforeach
                        @endif

                    </div>
                </div>
            </aside>

            {{-- MAIN CONTENT AREA --}}
            <main class="pp-main-content">
                {{-- CONTROLS BAR (SEARCH & SORT) --}}
                <form method="GET" action="{{ $productsUrl }}" id="pp-form">
                    <div class="pp-controls">
                        <div class="pp-search-wrap">
                            <span class="pp-search-icon">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg>
                            </span>
                            <input type="text" name="q" id="pp-search" class="pp-search-input"
                                placeholder="Cari produk..." value="{{ $search }}" autocomplete="off">
                        </div>
                        <select name="sort" class="pp-sort-select" id="pp-sort"
                            onchange="document.getElementById('pp-form').submit()">
                            <option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="terlama" {{ $sort === 'terlama' ? 'selected' : '' }}>Terlama</option>
                            <option value="terpopuler" {{ $sort === 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                        </select>
                        <button type="submit" class="pp-submit-btn">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"
                                viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                            Cari
                        </button>
                    </div>
                </form>

                <div class="pp-results-info">
                    @if(!empty($search))
                        Menampilkan <strong id="pp-count-notice">{{ $allProducts->count() }}</strong> hasil untuk
                        &ldquo;<strong>{{ e($search) }}</strong>&rdquo;
                    @else
                        Menampilkan <strong id="pp-count-notice">{{ $allProducts->count() }}</strong> produk/layanan
                        tersedia
                    @endif
                </div>

                {{-- PRODUCTS GRID (4 COLUMNS DESKTOP) --}}
                <div class="pp-grid">
                    @forelse($allProducts as $prod)
                        @php
                            $hasDisc = $prod['has_discount'];
                            $discPct = $prod['discount_pct'];
                            $effPrice = $prod['effective_price'];
                            $oldPrice = $prod['price'];
                            $pType = strtoupper($prod['product_type'] ?? '');
                        @endphp
                        <div class="pp-card" data-type="{{ strtolower($prod['product_type'] ?? 'digital') }}"
                            data-group-id="group-{{ $prod['creator_group_id'] ?? 'none' }}">
                            <a href="{{ $prod['product_url'] }}" class="pp-card-img-wrap">
                                <img src="{{ $prod['image_url'] }}" alt="{{ $prod['name'] }}" loading="lazy"
                                    class="pp-card-img" draggable="false">
                                @if($hasDisc)
                                    <span class="pp-badge-disc">-{{ $discPct }}%</span>
                                @endif
                            </a>
                            <div class="pp-card-body">
                                <div class="pp-card-meta">
                                    <span class="pp-rating">
                                        <svg width="12" height="12" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"
                                            viewBox="0 0 24 24">
                                            <polygon
                                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                        </svg>
                                        {{ $prod['rating'] }}
                                    </span>
                                    <span class="pp-verified">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                            <polyline points="22 4 12 14.01 9 11.01" />
                                        </svg>
                                        Verified
                                    </span>
                                </div>
                                <h3 class="pp-card-name">
                                    <a href="{{ $prod['product_url'] }}">{{ $prod['name'] }}</a>
                                </h3>
                                <div class="pp-card-footer">
                                    <div class="pp-price-wrap">
                                        @if($hasDisc)
                                            <span class="pp-price-old">Rp{{ number_format($oldPrice, 0, ',', '.') }}</span>
                                        @endif
                                        <span class="pp-price-main">Rp{{ number_format($effPrice, 0, ',', '.') }}</span>
                                    </div>
                                    <a href="{{ $prod['product_url'] }}" class="pp-buy-btn">
                                        Lihat
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <line x1="5" y1="12" x2="19" y2="12" />
                                            <polyline points="12 5 19 12 12 19" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="pp-empty">
                            <div class="pp-empty-icon">
                                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                </svg>
                            </div>
                            @if(!empty($search))
                                <h3>Tidak ada produk / Layanan ditemukan</h3>
                                <p>Tidak ada produk/Layanan yang cocok dengan pencarian Anda.</p>
                                <a href="{{ $productsUrl }}" class="pp-empty-clear">Tampilkan Semua</a>
                            @else
                                <h3>Belum ada produk / Layanan</h3>
                                <p>Produk / Layanan akan segera hadir.</p>
                            @endif
                        </div>
                    @endforelse
                </div>
            </main>

        </div>
    </div>

    {{-- FLOATING WHATSAPP BUTTON --}}
    @php
        $waRaw = $config['wa'] ?? ($profile->user->phone ?? '');
        $waFloatNum = preg_replace('/[^0-9]/', '', $waRaw);
    @endphp
    @if($waFloatNum)
        <a href="https://wa.me/{{ Str::startsWith($waFloatNum, '62') ? $waFloatNum : '62' . ltrim($waFloatNum, '0') }}"
            target="_blank" rel="noopener noreferrer" class="t5-floating-wa" title="Chat via WhatsApp">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                <path
                    d="M12 0C5.373 0 0 5.373 0 12c0 2.124.556 4.117 1.528 5.849L0 24l6.335-1.508A11.948 11.948 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.65-.52-5.154-1.422l-.37-.218-3.764.896.924-3.667-.243-.381A9.953 9.953 0 0 1 2 12c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10z" />
            </svg>
            <span class="t5-wa-pulse"></span>
        </a>
    @endif

    {{-- FOOTER TEMA 5 --}}
    @include('bio.theme5.footer', ['products' => $products, 'config' => $config, 'profile' => $profile, 'username' => $username])

    <script>
        function toggleT5Drawer() {
            var drawer = document.getElementById('t5MobileDrawer');
            if (drawer) drawer.classList.toggle('active');
        }

        function filterGroup(cat, el) {
            document.querySelectorAll('.pp-sidebar-item').forEach(function (c) { c.classList.remove('active'); });
            if (el) el.classList.add('active');

            var items = document.querySelectorAll('.pp-card');
            var visibleCount = 0;

            items.forEach(function (item) {
                var itemCat = (item.getAttribute('data-type') || 'digital').toLowerCase();
                var itemGroupId = item.getAttribute('data-group-id') || '';

                var show = false;
                if (cat === 'all') {
                    show = true;
                } else if (cat.startsWith('group-')) {
                    show = (itemGroupId === cat);
                } else if (itemCat === cat || (cat === 'physical' && (itemCat === 'barang' || itemCat === 'fisik')) || (cat === 'service' && (itemCat === 'jasa' || itemCat === 'layanan')) || (cat === 'makanan' && (itemCat === 'fnb' || itemCat === 'kuliner'))) {
                    show = true;
                }

                if (show) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            var countNotice = document.getElementById('pp-count-notice');
            if (countNotice) {
                countNotice.textContent = visibleCount;
            }
        }

        var searchTimer = null;
        var searchInput = document.getElementById('pp-search');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () {
                    document.getElementById('pp-form').submit();
                }, 650);
            });
        }
        // Lock image dragging
        document.addEventListener('dragstart', function (e) {
            if (e.target && e.target.tagName === 'IMG') {
                e.preventDefault();
                return false;
            }
        }, true);
    </script>
</body>

</html>