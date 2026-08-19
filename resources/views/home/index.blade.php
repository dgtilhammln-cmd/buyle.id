@extends('layouts.app')

@section('content')
    @push('styles')
        @if(isset($heroSlides) && $heroSlides->count() > 0)
            <link rel="preload" as="image" href="{{ asset('storage/' . $heroSlides->first()->image) }}">
        @elseif(!empty($settings['hero_main_image']))
            <link rel="preload" as="image" href="{{ asset('storage/' . $settings['hero_main_image']) }}">
        @else
            <link rel="preload" as="image" href="{{ asset('images/amn/hero-main.png') }}">
        @endif
    @endpush
    {{-- ════════════════════════════════════════════════
      HOME PAGE — buyle.id
      Toko Online buyle.id Tangga Terbaik
    ════════════════════════════════════════════════ --}}

    {{-- ════ APP-LIKE PRELOADER ════ --}}
    @push('styles')
    <style>
        #cv-app-preloader {
            position: fixed; inset: 0; z-index: 999999;
            background: #ffffff;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            transition: opacity 0.5s cubic-bezier(0.22, 1, 0.36, 1), visibility 0.5s cubic-bezier(0.22, 1, 0.36, 1);
        }
        #cv-app-preloader.fade-out { opacity: 0; visibility: hidden; }
        .preloader-logo-capsule {
            height: 48px; width: auto;
            border-radius: 999px; /* Rounded pill effect */
            overflow: hidden;
            animation: preloaderPulse 1.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
        @keyframes preloaderPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(0.95); opacity: 0.8; }
        }
        @media (max-width: 768px) {
            .hide-on-mobile { display: none !important; }
        }
    </style>
    @endpush

    <div id="cv-app-preloader">
        @php $logo = \App\Models\Setting::get('logo'); @endphp
        @if($logo)
            <img src="{{ asset('storage/'.$logo) }}" alt="Loading" class="preloader-logo-capsule">
        @else
            <span style="font-weight:700;color:#0EA5E9;font-size:1.5rem;font-family:'Montserrat',sans-serif;animation:preloaderPulse 1.5s infinite;">buyle.id</span>
        @endif
    </div>
    
    @push('scripts')
    <script>
        window.addEventListener('load', function() {
            const preloader = document.getElementById('cv-app-preloader');
            if(preloader) {
                setTimeout(() => {
                    preloader.classList.add('fade-out');
                    setTimeout(() => { preloader.remove(); }, 500);
                }, 200);
            }
        });
    </script>
    @endpush


    <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="print" onload="this.media='all'" />
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /></noscript>
    <style>
    .cv-hero-grid {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        width: 100%;
        display: grid;
        grid-template-columns: 2.3fr 1fr;
        gap: 1.25rem;
        align-items: stretch;
        position: relative;
        z-index: 1;
    }
    .cv-hero-slider-col {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        height: 440px;
    }
    .cv-hero-img-main {
        width: 100%; height: 100%;
        object-fit: cover;
    }
    .hero-swiper { width:100%; height:100%; }
    .hero-swiper-slide { width:100%; height:100%; display:block; text-decoration:none; }

    /* Right column banners — curved corners matching main banner */
    .cv-hero-static-col {
        flex: 1;
        min-height: 0;
        height: auto;
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        display: block;
        text-decoration: none;
        transition: transform 0.3s ease;
    }
    .cv-hero-static-col:hover { transform: scale(1.01); }
    
    /* ── SKELETON LOADING ANIMATION ── */
    @keyframes cvSkeletonShimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    .cv-skeleton-bg {
        background: #e2e8f0;
        background-image: linear-gradient(90deg, #e2e8f0 0px, #f1f5f9 50%, #e2e8f0 100%);
        background-size: 1000px 100%;
        animation: cvSkeletonShimmer 2s infinite linear;
    }
    
    .cv-hero-slider-col, .cv-hero-static-col {
        /* apply skeleton base so it shows before images load */
        background: #e2e8f0;
        background-image: linear-gradient(90deg, #e2e8f0 0px, #f1f5f9 50%, #e2e8f0 100%);
        background-size: 1000px 100%;
        animation: cvSkeletonShimmer 2s infinite linear;
    }

    .cv-hero-img-sec {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
    }

    @media (max-width: 991px) {
        .cv-hero-grid { grid-template-columns: 1fr !important; padding: 0 0.75rem !important; gap: 0 !important; }
        /* Use aspect-ratio 16/9 for a natural banner look */
        .cv-hero-slider-col { 
            position: relative !important; 
            width: 100% !important; 
            height: auto !important;
            aspect-ratio: 2 / 1 !important;
            border-radius: 16px !important; 
            overflow: hidden !important; 
            box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
        }
        /* The .swiper container must fill its parent */
        .cv-hero-slider-col > .hero-swiper { 
            position: absolute !important; 
            top: 0 !important; left: 0 !important;
            width: 100% !important; 
            height: 100% !important; 
        }
        /* swiper-wrapper must be full height for loop clones to work */
        .cv-hero-slider-col .swiper-wrapper { 
            height: 100% !important; 
        }
        /* Each swiper-slide must be full height */
        .cv-hero-slider-col .swiper-slide { 
            height: 100% !important;
            position: relative !important;
        }
        /* The <a> hero-swiper-slide inside each swiper-slide */
        .cv-hero-slider-col .hero-swiper-slide { 
            position: absolute !important; 
            inset: 0 !important;
            display: block !important; 
            width: 100% !important; 
            height: 100% !important; 
        }
        /* Image inside slide */
        .cv-hero-slider-col .hero-swiper-slide img,
        .cv-hero-slider-col .cv-hero-img-main { 
            position: absolute !important; 
            inset: 0 !important; 
            width: 100% !important; 
            height: 100% !important; 
            object-fit: cover !important; 
        }
        .cv-hero-static-col { display: none !important; }
    }
    .cv-hero-modern {
        background-color: #ffffff;
        padding-top: calc(46px + 2.5rem);
        padding-bottom: 0;
        position: relative;
        overflow: hidden;
    }
    @media (max-width: 991px) {
        .cv-hero-modern {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            background: transparent !important;
        }
    }
    @media (max-width: 480px) {
        .cv-hero-modern {
            padding-top: 0.5rem;
        }
    }
    .cv-hero-oval-bg {
        position: absolute;
        top: -20%;
        left: -10%;
        width: 120%;
        height: 120%;
        border-radius: 0 0 50% 50% / 0 0 30% 30%;
        z-index: 0;
        transition: background 0.5s ease;
        background: linear-gradient(180deg, #1eb349 0%, #a5cf37 100%);
    }
    @media (max-width: 991px) {
        .cv-hero-oval-bg {
            display: none !important;
        }
        .cv-hero-bg-layer {
            display: none !important;
        }
    }
    .cv-hero-bg-layer {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        opacity: 0.18;
        z-index: 0;
        mix-blend-mode: overlay;
    }
        
    
    /* Left Column: Slider */
    
    .cv-hero-img-main {
        width: 100%; height: 100%;
        object-fit: cover;
    }
    .hero-swiper { width:100%; height:100%; }
    .hero-swiper-slide { width:100%; height:100%; display:block; text-decoration:none; }
    .hero-swiper-pagination { position: absolute; bottom: 20px !important; left: 20px !important; text-align: left; width: auto !important; z-index: 10; display:flex; gap:6px; align-items:center; }
    .hero-swiper-pagination .swiper-pagination-bullet { background: #fff; opacity: 0.5; width: 10px; height: 10px; transition: all 0.3s; margin:0 !important; }
    .hero-swiper-pagination .swiper-pagination-bullet-active { background: #fff; opacity: 1; width: 30px; border-radius: 5px; }

    /* Right Column: Static Banner */
    
    .cv-hero-img-sec {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .cv-hero-static-col:hover .cv-hero-img-sec {
        transform: scale(1.03);
    }

    /* ── MARQUEE CLIENTS BAR ──────────── */
    .cv-clients-bar {
        background: #F8FAFF;
        border-top: 1px solid rgba(56,189,248,0.1);
        border-bottom: 1px solid rgba(56,189,248,0.1);
        padding: 1.5rem 0;
        overflow: hidden;
    }
    .cv-clients-label {
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: #94A3B8;
        white-space: nowrap;
        flex-shrink: 0;
        padding-right: 2rem;
    }
    .cv-client-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #fff;
        border: 1px solid rgba(56,189,248,0.15);
        border-radius: 6px;
        padding: 0.5rem 1.125rem;
        font-size: 0.8rem;
        font-weight: 500;
        color: #334155;
        white-space: nowrap;
        margin: 0 0.5rem;
    }
    .cv-client-chip::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #38BDF8;
        flex-shrink: 0;
    }

    /* ── EXPLAINER ────────────────────── */
    .cv-explainer { background: var(--bg-1); }
    .cv-explainer-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 5rem;
        align-items: center;
    }
    .cv-explainer-steps {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        margin-top: 2rem;
    }
    .cv-explainer-step {
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
    }
    .cv-step-num {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, #0EA5E9, #38BDF8);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 900;
        color: #fff;
        flex-shrink: 0;
    }
    .cv-step-title {
        font-size: 0.9375rem;
        font-weight: 300;
        color: var(--text-1);
        margin-bottom: 0.25rem;
    }
    .cv-step-desc {
        font-size: 0.8125rem;
        font-weight: 300;
        color: var(--text-3);
        line-height: 1.6;
    }
    .cv-explainer-visual {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cv-explainer-card {
        background: linear-gradient(135deg, #38BDF8, #0EA5E9);
        border-radius: 20px;
        padding: 2.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 24px 80px rgba(14,165,233,0.25);
    }
    .cv-explainer-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 80% 60% at 50% 20%, rgba(255,255,255,0.15) 0%, transparent 70%);
    }

    /* ── PRODUCTS ─────────────────────── */
    .cv-products { background: var(--bg-base); }
    .cv-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }
    .cv-product-card {
        background: var(--bg-base);
        border: 1.5px solid var(--border-1);
        border-radius: 16px;
        padding: 2rem 1.75rem;
        text-decoration: none;
        display: block;
        transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(56,189,248,0.05);
    }
    .cv-product-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #0EA5E9, #38BDF8, #7DD3FC);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }
    .cv-product-card:hover {
        border-color: #38BDF8;
        transform: translateY(-8px);
        box-shadow: 0 32px 80px rgba(56,189,248,0.15), 0 0 0 1px rgba(56,189,248,0.1);
    }
    .cv-product-card:hover::after { transform: scaleX(1); }
    .cv-product-type {
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #0EA5E9;
        margin-bottom: 0.375rem;
    }
    .cv-product-name {
        font-size: 1.375rem;
        font-weight: 900;
        color: var(--text-1);
        letter-spacing: -0.02em;
        margin-bottom: 0.25rem;
    }
    .cv-product-size {
        font-size: 0.8125rem;
        font-weight: 400;
        color: var(--text-3);
        margin-bottom: 1.25rem;
    }
    .cv-product-specs {
        border-top: 1px solid var(--border-1);
        padding-top: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.625rem;
    }
    .cv-spec-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .cv-spec-key {
        font-size: 0.75rem;
        font-weight: 400;
        color: var(--text-3);
    }
    .cv-spec-val {
        font-size: 0.8125rem;
        font-weight: 700;
        color: var(--text-1);
    }
    .cv-spec-val.highlight { color: #0284C7; }
    .cv-product-cta {
        margin-top: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #0EA5E9;
        transition: gap 0.2s;
    }
    .cv-product-card:hover .cv-product-cta { gap: 0.625rem; }

    /* ── ADVANTAGES ───────────────────── */
    .cv-advantages { background: var(--bg-1); }
    .cv-adv-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }
    .cv-adv-card {
        background: var(--bg-base);
        border: 1px solid var(--border-2);
        border-radius: 12px;
        padding: 1.75rem;
        transition: all 0.3s ease;
        color: var(--text-1);
    }
    .cv-adv-card:hover {
        background: var(--bg-2);
        border-color: var(--accent);
        transform: translateY(-4px);
    }
    .cv-adv-icon {
        width: 48px; height: 48px;
        background: var(--bg-2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        color: var(--accent-dark);
    }
    .cv-adv-title {
        font-size: 1rem;
        font-weight: 300;
        color: var(--text-1);
        margin-bottom: 0.5rem;
    }
    .cv-adv-desc {
        font-size: 0.8125rem;
        font-weight: 300;
        color: var(--text-3);
        line-height: 1.65;
    }

    /* ── APPLICATIONS ─────────────────── */
    .cv-apps { background: var(--bg-1); }
    .cv-apps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-top: 3rem;
    }
    .cv-app-card {
        background: var(--bg-base);
        border: 1.5px solid var(--border-1);
        border-radius: 12px;
        padding: 1.75rem 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    .cv-app-card:hover {
        border-color: var(--accent);
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(56,189,248,0.12);
    }
    .cv-app-emoji {
        font-size: 2.25rem;
        display: block;
        margin-bottom: 0.875rem;
        line-height: 1;
    }
    .cv-app-title {
        font-size: 0.9375rem;
        font-weight: 300;
        color: var(--text-1);
        margin-bottom: 0.375rem;
    }
    .cv-app-desc {
        font-size: 0.75rem;
        font-weight: 300;
        color: var(--text-3);
        line-height: 1.5;
    }

    /* ── GALLERY PREVIEW ──────────────── */
    .cv-gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-template-rows: repeat(2, 220px);
        gap: 1rem;
        margin-top: 3rem;
    }
    .cv-gallery-grid .gallery-item:first-child {
        grid-column: span 2;
        grid-row: span 2;
    }
    .cv-gallery-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--bg-2), var(--bg-3));
        border-radius: 10px;
    }
    .cv-gallery-placeholder-inner {
        text-align: center;
        color: var(--text-3);
    }

    /* ── TESTIMONIALS ─────────────────── */
    .cv-testimonials { background: var(--bg-dark); }
    .cv-testi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }
    .cv-testi-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 16px;
        padding: 2rem;
        position: relative;
    }
    .cv-testi-quote {
        font-size: 2.5rem;
        color: #38BDF8;
        opacity: 0.3;
        line-height: 1;
        margin-bottom: 0.5rem;
        font-family: Georgia, serif;
    }
    .cv-testi-text {
        font-size: 0.875rem;
        font-weight: 300;
        color: rgba(255,255,255,0.65);
        line-height: 1.75;
        margin-bottom: 1.5rem;
        font-style: italic;
    }
    .cv-testi-author {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .cv-testi-avatar {
        width: 44px; height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0EA5E9, #38BDF8);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
    }
    .cv-testi-name {
        font-size: 0.9375rem;
        font-weight: 300;
        color: #fff;
    }
    .cv-testi-company {
        font-size: 0.75rem;
        font-weight: 300;
        color: rgba(255,255,255,0.45);
    }
    .cv-testi-stars {
        display: flex;
        gap: 2px;
        margin-bottom: 1rem;
    }

    /* ── COVERAGE MAP ─────────────────── */
    .cv-coverage { background: var(--bg-2); }
    .cv-coverage-areas {
        display: flex;
        flex-wrap: wrap;
        gap: 0.625rem;
        margin-top: 2rem;
    }
    .cv-area-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: var(--bg-base);
        border: 1px solid var(--border-1);
        border-radius: 20px;
        padding: 0.4rem 0.875rem;
        font-size: 0.8rem;
        font-weight: 400;
        color: var(--text-2);
        transition: all 0.2s;
    }
    .cv-area-chip:hover {
        border-color: var(--accent);
        color: var(--accent-deep);
        background: var(--accent-glow);
    }
    .cv-area-chip::before {
        content: '';
        width: 5px; height: 5px;
        border-radius: 50%;
        background: #38BDF8;
        flex-shrink: 0;
    }

    /* ── CTA SECTION ──────────────────── */
    .cv-cta {
        background: linear-gradient(135deg, #38BDF8 0%, #0EA5E9 50%, #0284C7 100%);
        position: relative;
        overflow: hidden;
    }
    .cv-cta::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 60% 80% at 80% 50%, rgba(255,255,255,0.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .cv-cta-inner {
        max-width: 860px;
        margin: 0 auto;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    /* ── ARTICLES ─────────────────────── */
    .cv-articles { background: var(--bg-1); }
    .cv-articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }
    .cv-article-card {
        background: var(--bg-base);
        border: 1.5px solid var(--border-1);
        border-radius: 14px;
        overflow: hidden;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
    }
    .cv-article-card:hover {
        border-color: var(--accent);
        transform: translateY(-6px);
        box-shadow: 0 20px 56px rgba(56,189,248,0.12);
    }
    .cv-article-thumb {
        height: 180px;
        background: linear-gradient(135deg, var(--bg-2), var(--bg-3));
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-4);
        font-size: 0.8rem;
    }
    .cv-article-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .cv-article-card:hover .cv-article-thumb img { transform: scale(1.06); }
    .cv-article-body { padding: 1.5rem; }
    .cv-article-cat {
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #0EA5E9;
        margin-bottom: 0.5rem;
    }
    .cv-article-title {
        font-size: 1rem;
        font-weight: 300;
        color: var(--text-1);
        line-height: 1.4;
        margin-bottom: 0.625rem;
    }
    .cv-article-excerpt {
        font-size: 0.8125rem;
        font-weight: 300;
        color: var(--text-3);
        line-height: 1.65;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .cv-article-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.75rem;
        color: var(--text-4);
    }

    /* ── PREMIUM CLIENT BAR ──────────── */
    .cv-clients-section {
        background: #ffffff;
        padding: 3.5rem 0;
        border-top: 1px solid rgba(14,165,233,0.08);
        border-bottom: 1px solid rgba(14,165,233,0.08);
        overflow: hidden;
        position: relative;
    }
    .cv-clients-header {
        max-width: 1200px;
        margin: 0 auto 2.5rem;
        padding: 0 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 0.5rem;
    }
    .cv-clients-label {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: #64748B;
    }
    .cv-clients-count {
        font-size: 0.7rem;
        font-weight: 600;
        color: #0EA5E9;
        background: #F0F9FF;
        padding: 0.35rem 1rem;
        border-radius: 20px;
        border: 1px solid rgba(14,165,233,0.15);
    }

    /* Marquee Container */
    .cv-marquee-container {
        width: 100%;
        overflow: hidden;
        position: relative;
        display: flex;
    }
    /* Fade edges */
    .cv-marquee-container::before,
    .cv-marquee-container::after {
        content: '';
        position: absolute;
        top: 0; bottom: 0;
        width: 150px;
        z-index: 2;
        pointer-events: none;
    }
    .cv-marquee-container::before {
        left: 0;
        background: linear-gradient(to right, #ffffff, transparent);
    }
    .cv-marquee-container::after {
        right: 0;
        background: linear-gradient(to left, #ffffff, transparent);
    }

    /* Infinite Animation */
    @keyframes scrollLeft {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .cv-marquee-track {
        display: flex;
        gap: 2rem;
        padding: 1.5rem 1rem;
        width: max-content;
        animation: scrollLeft 40s linear infinite;
    }
    .cv-marquee-container:hover .cv-marquee-track {
        animation-play-state: paused;
    }

    /* Card Design */
    .cv-client-logo-card {
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        border-radius: 16px;
        padding: 1rem 1.75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        min-width: 180px;
        max-width: 220px;
        height: 110px;
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .cv-client-logo-card:hover {
        border-color: #38BDF8;
        background: #ffffff;
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 30px rgba(14,165,233,0.12);
    }
    .cv-client-logo-img {
        max-height: 52px;
        max-width: 160px;
        width: auto;
        height: auto;
        object-fit: contain;
        filter: grayscale(80%) opacity(0.8);
        transition: all 0.3s;
        display: block;
    }
    .cv-client-logo-card:hover .cv-client-logo-img { filter: grayscale(0%) opacity(1); }
    .cv-client-logo-name {
        font-size: 0.7rem;
        font-weight: 600;
        color: #64748B;
        text-align: center;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 130px;
    }
    /* Text-only chip */
    .cv-client-chip-v2 {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        border-radius: 16px;
        padding: 0 2rem;
        height: 100px;
        min-width: 160px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        white-space: nowrap;
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .cv-client-chip-v2::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #38BDF8;
        flex-shrink: 0;
    }
    .cv-client-chip-v2:hover {
        border-color: #38BDF8;
        background: #ffffff;
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 30px rgba(14,165,233,0.12);
    }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
        
        
        
        .cv-explainer-grid { grid-template-columns: 1fr; }
        .cv-gallery-grid { grid-template-columns: repeat(2, 1fr); grid-template-rows: auto; }
        .cv-gallery-grid .gallery-item:first-child { grid-column: 1; grid-row: 1; }
    }
    @media (max-width: 640px) {
        .cv-hero-center { height: 320px; }
        .cv-gallery-grid { grid-template-columns: 1fr; }
        .cv-products-grid, .cv-adv-grid, .cv-apps-grid { grid-template-columns: 1fr; }
    }
    /* ── USP BAR (Premium Style) ── */
    .cv-usp-bar {
        background: #ffffff;
        padding: 1rem 0 0.5rem;
        position: relative;
        z-index: 10;
    }
    .cv-usp-inner {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2.5rem;
        flex-wrap: wrap;
    }
    .cv-usp-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        transition: transform 0.2s;
        text-decoration: none;
    }
    .cv-usp-item:hover { transform: translateY(-2px); }
    .cv-usp-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px; height: 36px;
        background: #F0F9FF;
        border-radius: 50%;
        color: #0EA5E9;
    }
    .cv-usp-icon img { width: 20px; height: 20px; object-fit: contain; }
    .cv-usp-icon svg { width: 16px; height: 16px; }
    .cv-usp-label { color: #0f172a; font-weight: 700; }
    
    @media (max-width: 768px) {
        .cv-usp-bar { padding: 0.5rem 1rem 1rem; border-bottom: none; box-shadow: none; margin-top: 0; background: transparent; }
        .cv-usp-inner {
            flex-wrap: nowrap;
            justify-content: center;
            gap: 1rem;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .cv-usp-inner::-webkit-scrollbar { display: none; }
        .cv-usp-item { gap: 0.35rem; white-space: nowrap; flex: 1; justify-content: center; flex-direction: row; }
        .cv-usp-icon { width: 26px; height: 26px; background: transparent; border-radius: 50%; box-shadow: none; margin: 0; }
        .cv-usp-icon img { width: 18px; height: 18px; }
        .cv-usp-label { font-size: 0.65rem; font-weight: 700; line-height: normal; }
    }

    /* ── CATEGORY SWIPER (Premium Style) ── */
    .cv-cats-section {
        background: #ffffff;
        padding: 1.25rem 0 2rem;
    }
    @media (max-width: 991px) {
        .cv-cats-section { padding: 0.5rem 0 0.75rem !important; }
        .cv-cats-inner { padding: 0 1rem !important; }
    }
    .cv-cats-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 4rem;
        position: relative;
    }
    .cats-swiper { overflow: hidden; padding: 1rem 0; margin: -1rem 0; }
    .cats-swiper .swiper-slide { width: auto !important; }
    .cv-cat-slide {
        display: flex !important;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        cursor: pointer;
        width: 100px !important;
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .cv-cat-slide:hover { transform: translateY(-5px); }
    .cv-cat-icon-wrap {
        width: 72px; height: 72px;
        background: #ffffff;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        box-shadow: 0 10px 25px rgba(14,165,233,0.08), 0 4px 10px rgba(14,165,233,0.03);
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .cv-cat-slide:hover .cv-cat-icon-wrap {
        box-shadow: 0 14px 35px rgba(14,165,233,0.18), 0 6px 15px rgba(14,165,233,0.08);
        transform: translateY(-3px);
    }
    .cv-cat-icon-wrap img {
        width: 44px; height: 44px; object-fit: contain; transition: transform 0.3s;
    }
    .cv-cat-slide:hover .cv-cat-icon-wrap img { transform: scale(1.1); }
    .cv-cat-badge {
        position: absolute;
        top: -8px; right: -8px;
        font-size: 0.6rem;
        font-weight: 800;
        color: #fff;
        padding: 3px 8px;
        border-radius: 999px;
        font-family: 'Montserrat', sans-serif;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border: 2px solid #fff;
    }
    .cv-cat-name {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        text-align: center;
        line-height: 1.3;
        transition: color 0.2s;
    }
    .cv-cat-slide:hover .cv-cat-name { color: #0EA5E9; }
    
    .cv-cats-nav {
        position: absolute;
        top: 50%; transform: translateY(-50%);
        width: 38px; height: 38px;
        background: #ffffff;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: #475569;
        transition: all 0.2s;
        z-index: 10;
    }
    .cv-cats-nav:hover { background: #0EA5E9; color: #fff; border-color: #0EA5E9; box-shadow: 0 6px 16px rgba(14,165,233,0.25); }
    .cv-cats-prev { left: 0; }
    .cv-cats-next { right: 0; }

    /* Mobile single row category grid */
    @media (max-width: 768px) {
        .cv-cats-section { padding: 0.5rem 0 1rem; border-bottom: none; }
        .cv-cats-inner { padding: 0 0.5rem; }
        .cv-cat-slide { width: 72px !important; gap: 0.25rem; }
        .cv-cat-icon-wrap { width: 50px; height: 50px; border-radius: 14px; box-shadow: none; border: 1.5px solid #F1F5F9; }
        .cv-cat-icon-wrap img { width: 28px; height: 28px; }
        .cv-cat-icon-wrap svg { width: 22px; height: 22px; }
        .cv-cat-name { font-size: 0.65rem; font-weight: 600; line-height: 1.2; text-wrap: balance; color: #334155; }
        .cv-cats-nav { display: none; } /* Hide navigation arrows on mobile */
        
        .cats-swiper-mobile-grid {
            display: flex; /* Single row */
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 0.5rem;
            padding: 0.25rem 0;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }
        .cats-swiper-mobile-grid::-webkit-scrollbar { display: none; }
    }
    </style>

    {{-- ════ NEW MODERN HERO ════ --}}
    <section class="cv-hero-modern" id="home" itemscope itemtype="https://schema.org/Organization">
        <meta itemprop="name" content="buyle.id">
        <meta itemprop="description" content="Toko online buyle.id tangga terlengkap di Surabaya. Temukan berbagai produk berkualitas dengan harga terbaik.">
        <meta itemprop="address" content="Surabaya, Jawa Timur, Indonesia">
        {{-- Visible H1 moved below Hero Section --}}
        @php
            $initialBgColor = \App\Models\Setting::get('hero_oval_gradient', 'linear-gradient(180deg, #1e3a8a 0%, #3b82f6 100%)');
        @endphp
        <div class="cv-hero-oval-bg" id="heroOvalBg" style="background: {{ $initialBgColor }};"></div>
        
        @if(!empty($settings['hero_bg_image']))
            <div class="cv-hero-bg-layer" style="background-image:url('{{ asset('storage/' . $settings['hero_bg_image']) }}')"></div>
        @endif
        

        <div class="cv-hero-grid">
            {{-- Left Column: Slider --}}
            <div class="cv-hero-slider-col">
                @if(isset($heroSlides) && $heroSlides->count() > 0)
                    <div class="swiper hero-swiper">
                        <div class="swiper-wrapper">
                            @foreach($heroSlides as $slide)
                                <div class="swiper-slide hero-swiper-slide" data-bg-color="{{ $slide->bg_color ?? 'linear-gradient(180deg, #1e3a8a 0%, #3b82f6 100%)' }}">
                                    <a href="{{ $slide->button_url ?? '#' }}" style="display:block; width:100%; height:100%; text-decoration:none;" aria-label="{{ $slide->title }}">
                                        <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}" class="cv-hero-img-main" width="800" height="440" fetchpriority="high">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination hero-swiper-pagination"></div>
                    </div>
                @elseif(!empty($settings['hero_main_image']))
                    <img src="{{ asset('storage/' . $settings['hero_main_image']) }}" alt="Promo Utama" class="cv-hero-img-main" width="800" height="440" fetchpriority="high">
                @else
                    <div style="width:100%; height:100%; min-height:300px; border-radius:16px; background:#F1F5F9; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#64748B; border:2px dashed #CBD5E1;">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <span style="font-family:'Montserrat',sans-serif; font-weight:600; font-size:1.1rem; color:#475569;">Banner Utama Kosong</span>
                        <span style="font-size:0.8rem; margin-top:0.25rem; color:#94A3B8;">(Upload di menu Banner Hero)</span>
                    </div>
                @endif
            </div>
            {{-- Right Column: Dynamic Banners (utama + samping) --}}
            <div class="cv-hero-static-wrapper" style="display:flex; flex-direction:column; gap:0.75rem; height:440px;">
                <style>
                    @media (max-width: 1024px) {
                        .cv-hero-static-wrapper { display: none !important; }
                    }
                </style>
                {{-- Top Banner: utama --}}
                @if(isset($utamaBanners) && $utamaBanners->count() > 0)
                    @php $utama = $utamaBanners->first(); @endphp
                    <a href="{{ $utama->button_url ?? route('products') }}" class="cv-hero-static-col">
                        <img src="{{ asset('storage/' . $utama->image) }}" alt="{{ $utama->title }}" class="cv-hero-img-sec" loading="lazy">
                    </a>
                @elseif(!empty($settings['hero_secondary_image']))
                    <a href="{{ route('products') }}" class="cv-hero-static-col">
                        <img src="{{ asset('storage/' . $settings['hero_secondary_image']) }}" alt="Promo" class="cv-hero-img-sec" loading="eager">
                    </a>
                @else
                    <div style="flex:1; min-height:0; border-radius:16px; background:#F1F5F9; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#64748B; border:2px dashed #CBD5E1;">
                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:0.5rem;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <span style="font-size:0.75rem; color:#94A3B8; text-align:center; padding:0 0.5rem;">Banner Utama Kosong</span>
                    </div>
                @endif

                {{-- Bottom Banner: samping --}}
                @if(isset($sampingBanners) && $sampingBanners->count() > 0)
                    @php $samping = $sampingBanners->first(); @endphp
                    <a href="{{ $samping->button_url ?? route('products') }}" class="cv-hero-static-col">
                        <img src="{{ asset('storage/' . $samping->image) }}" alt="{{ $samping->title }}" class="cv-hero-img-sec" loading="lazy">
                    </a>
                @elseif(!empty($settings['hero_third_image']))
                    <a href="{{ route('products') }}" class="cv-hero-static-col">
                        <img src="{{ asset('storage/' . $settings['hero_third_image']) }}" alt="Promo 2" class="cv-hero-img-sec" loading="lazy">
                    </a>
                @else
                    <div style="flex:1; min-height:0; border-radius:16px; background:#F8FAFC; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#64748B; border:2px dashed #CBD5E1;">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:0.5rem;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        <span style="font-size:0.75rem; color:#94A3B8; text-align:center; padding:0 0.5rem;">Banner Samping Kosong</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ════ VISIBLE H1 & SEO SECTION ════ --}}
    <div style="display:none;" class="hide-on-mobile">
        <div style="max-width:1000px; margin:0 auto;">
            <h1 style="font-size:1.4rem; font-weight:600; color:#1E293B; margin:0 0 0.25rem 0; font-family:'Montserrat', sans-serif;">
                buyle.id - Toko buyle.id Tangga Terlengkap di Surabaya
            </h1>
            <p style="font-size:0.9rem; color:#64748B; line-height:1.5; margin:0; font-family:'Montserrat', sans-serif;">
                Temukan ribuan produk berkualitas dari merek-merek ternama dengan harga terbaik dan pengiriman cepat ke seluruh Indonesia.
            </p>
        </div>
    </div>
    {{-- ════ USP BAR ════ --}}
    @if($uspItems->count())
    <section class="cv-usp-bar" aria-label="Keunggulan buyle.id">
        <div class="cv-usp-inner">
            @foreach($uspItems as $usp)
                <div class="cv-usp-item">
                    <span class="cv-usp-icon">
                        @if($usp->icon_type === 'upload' && $usp->icon_value)
                            <img src="{{ asset('storage/' . $usp->icon_value) }}" alt="{{ $usp->label }}" loading="lazy" width="24" height="24" style="object-fit:contain;">
                        @else
                            {{ $usp->icon_value }}
                        @endif
                    </span>
                    <span class="cv-usp-label">{{ $usp->label }}</span>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ════ CATEGORY SWIPER ════ --}}
    @if($categoryItems->count())
    <section class="cv-cats-section" aria-label="Kategori Produk">
        <div class="cv-cats-inner" style="flex-direction: column; align-items: flex-start; padding: 1rem 1.5rem 1.25rem;">
            <h2 style="font-size:1.15rem; font-weight:600; color:#1E293B; margin:0 0 1rem 0; font-family:'Montserrat', sans-serif;">
                Kategori Pilihan
            </h2>
            <div class="swiper cats-swiper hidden md:block" id="catsSwiperDesktop" style="width: 100%;">
                <div class="swiper-wrapper">
                    @foreach($categoryItems as $cat)
                        <div class="swiper-slide">
                            <a href="{{ $cat->url ?? route('products') }}" class="cv-cat-slide" aria-label="{{ $cat->name }}">
                                <div class="cv-cat-icon-wrap">
                                    @if($cat->icon_type === 'upload' && $cat->icon_value)
                                        <img src="{{ asset('storage/' . $cat->icon_value) }}" alt="" loading="lazy" aria-hidden="true">
                                    @else
                                        @php
                                            $icons = [
                                                'home'=>'<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
                                                'box'=>'<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>',
                                                'tool'=>'<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
                                                'truck'=>'<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
                                                'shopping-cart'=>'<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
                                                'shopping-bag'=>'<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>',
                                                'zap'=>'<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
                                                'star'=>'<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
                                                'monitor'=>'<rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
                                                'smartphone'=>'<rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>',
                                                'gift'=>'<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/>',
                                                'heart'=>'<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
                                                'briefcase'=>'<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
                                                'award'=>'<circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>',
                                                'droplet'=>'<path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/>',
                                                'wind'=>'<path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"/>',
                                                'camera'=>'<path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>',
                                                'shield'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
                                                'hexagon'=>'<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>',
                                            ];
                                            $svg = $icons[$cat->icon_value] ?? $icons['box'];
                                        @endphp
                                        <svg width="28" height="28" fill="none" stroke="#3B82F6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">{!! $svg !!}</svg>
                                    @endif
                                    
                                    @if($cat->badge)
                                        <span class="cv-cat-badge" style="background:{{ $cat->badge_color ?? '#ef4444' }}">{{ $cat->badge }}</span>
                                    @endif
                                </div>
                                <span class="cv-cat-name">{{ $cat->name }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <button class="cv-cats-nav cv-cats-prev" id="catsPrev" aria-label="Kategori sebelumnya">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button class="cv-cats-nav cv-cats-next" id="catsNext" aria-label="Next">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        </div>
    </section>
    @endif

    {{-- ════ PROMO SECTIONS (Dynamic) ════ --}}
    @if(isset($promoSections) && $promoSections->count())
    <style>
    .cv-promo-wrap {
        background: #ffffff;
        padding: 0.625rem 0 0;
        border-bottom: none;
    }
    .cv-promo-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.875rem 1.5rem 2rem;
        border-bottom: none;
    }
    .cv-promo-section:last-child { border-bottom: none; }

    /* Flash Sale Override: gradient only on card-sized container (same width as regular sections) */
    .cv-promo-section[style*="linear-gradient"] {
        border-radius: 20px;
        border-bottom: none;
        max-width: 1200px; /* SAME as regular sections — not full width */
        margin: 0 auto 1rem;
        padding: 1.25rem 1.5rem 1.5rem;
    }
    .cv-promo-section[style*="linear-gradient"] .cv-promo-card {
        background: #fff;
        border: none;
        box-shadow: 0 2px 16px rgba(0,0,0,.12);
    }
    .cv-promo-section[style*="linear-gradient"] .cv-promo-card-name { color: #1E293B; }
    .cv-promo-section[style*="linear-gradient"] .cv-promo-banner-wrap {
        border-radius: 14px;
        background: rgba(0,0,0,.18);
    }
    .cv-promo-section[style*="linear-gradient"] .cv-promo-nav {
        background: #fff;
        color: #EF4444;
        box-shadow: 0 2px 10px rgba(0,0,0,.2);
    }
    .cv-promo-section.is-flash-section .cv-promo-title { color: #ffffff; }
    .cv-promo-section.is-flash-section .cv-promo-subtitle { color: rgba(255,255,255,0.85); }
    .cv-promo-section.is-flash-section .cv-promo-view-all { color: #ffffff; opacity: 0.9; }
    .cv-promo-section.is-flash-section .cv-promo-view-all:hover { opacity: 1; }

    /* Timer compact styling */
    .cv-flash-timer { gap: .25rem; }
    .cv-flash-timer .timer-box {
        font-family: 'Montserrat', monospace;
        font-weight: 800;
        font-size: .95rem;
        background: rgba(0,0,0,.5);
        color: #fff;
        padding: .15rem .45rem;
        border-radius: 5px;
        min-width: 30px;
        text-align: center;
        letter-spacing: .03em;
    }
    .cv-flash-timer .timer-sep {
        color: rgba(255,255,255,.9);
        font-weight: 800;
        font-size: 1rem;
    }


    .cv-section-title-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .cv-promo-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text, #1E293B);
        line-height: 1.2;
    }
    .cv-promo-subtitle {
        font-size: .8rem;
        color: #64748B;
        margin-top: .15rem;
    }
    .cv-promo-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .cv-promo-view-all {
        font-size: .825rem;
        font-weight: 600;
        color: var(--accent, #0EA5E9);
        text-decoration: none;
        white-space: nowrap;
        flex-shrink: 0;
        transition: opacity .2s;
    }
    .cv-promo-view-all:hover { opacity: .7; }

    /* Grid: banner left + swiper right */
    .cv-promo-grid {
        display: flex;
        gap: 1rem;
        align-items: stretch;
    }

    /* Banner */
    .cv-promo-banner-wrap {
        border-radius: 14px;
        overflow: hidden;
        position: relative;
        width: 200px;
        min-height: 200px; /* Prevent CLS */
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        /* apply skeleton to banner */
        background: #e2e8f0;
        background-image: linear-gradient(90deg, #e2e8f0 0px, #f1f5f9 50%, #e2e8f0 100%);
        background-size: 1000px 100%;
        animation: cvSkeletonShimmer 2s infinite linear;
    }
    .cv-promo-banner-wrap.mobile-only { display: none !important; }
    
    .cv-promo-banner-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .cv-promo-banner-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        color: rgba(255,255,255,.5);
        font-size: .75rem;
        text-align: center;
        padding: 1rem;
    }

    /* Swiper container */
    .cv-promo-swiper-container {
        position: relative;
        flex: 1;
        min-width: 0;
    }
    .cv-promo-swiper {
        width: 100%;
        height: 100%;
        padding: 12px 4px; /* padding for shadow */
        overflow: hidden; /* restore clipping for swipe */
    }
    .cv-promo-swiper .swiper-wrapper {
        align-items: stretch;
    }
    .cv-promo-swiper .swiper-slide {
        height: auto;
        width: 172px;
        display: flex;
        align-items: stretch;
    }
    
    @media (max-width: 768px) {
        .cv-promo-section { padding: 1.25rem 0 1.25rem 1rem; margin-bottom: 0; border-bottom: none; }
        .cv-promo-section.is-flash-section { 
            margin-bottom: 0; 
            border-radius: 0; 
        }
        .cv-promo-grid { gap: 0; }
        .cv-promo-swiper { overflow: hidden; padding: 4px; }
        .cv-promo-banner-wrap.desktop-only { display: none; }
        .cv-promo-banner-wrap.mobile-only { 
            display: flex !important; 
            width: 155px !important; 
            margin-right: 0.5rem; 
            border-radius: 8px; 
            height: auto;
        }
        .cv-promo-swiper .swiper-slide { width: 142px !important; }
        .cv-promo-swiper .swiper-slide.cv-promo-banner-wrap { width: 155px !important; }
        .cv-promo-card-body { padding: 0.65rem; }
        .cv-promo-card-name { font-size: 0.75rem; }
        .cv-promo-card-price { font-size: 0.85rem; }
        .cv-promo-nav { display: none !important; }
        
        .cv-promo-header { align-items: center; margin-bottom: 0.5rem; padding-right: 1rem; }
        .cv-promo-title { font-size: 1rem; font-weight: 600; }
        .cv-promo-title.is-flash { 
            font-style: italic; 
            text-transform: uppercase; 
            font-size: 1.1rem; 
        }
        .cv-promo-subtitle { display: none; }
        .cv-promo-view-all { font-size: 0.75rem; }
        
        /* Compact timers for Flash Sale */
        .cv-flash-timer .timer-box { font-size: 0.75rem; padding: 0.15rem 0.35rem; border-radius: 4px; min-width: 24px; background: #334155; }
        .cv-flash-timer .timer-sep { font-size: 0.8rem; }
    }

    /* Product Card */
    .cv-promo-card {
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1.5px solid #F1F5F9;
        border-radius: 14px;
        overflow: hidden;
        height: 100%;
        text-decoration: none;
        transition: border-color .25s, box-shadow .25s, transform .25s;
    }
    .cv-promo-card:hover {
        border-color: var(--accent, #0EA5E9);
        box-shadow: 0 8px 24px rgba(14,165,233,.1);
        transform: translateY(-3px);
    }
    .cv-promo-card-img {
        width: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
        display: block;
        /* apply skeleton to product image container */
        background: #e2e8f0;
        background-image: linear-gradient(90deg, #e2e8f0 0px, #f1f5f9 50%, #e2e8f0 100%);
        background-size: 1000px 100%;
        animation: cvSkeletonShimmer 2s infinite linear;
    }
    .cv-promo-card-img-placeholder {
        width: 100%;
        aspect-ratio: 1/1;
        background: #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cv-promo-card-body {
        padding: .75rem .875rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: .25rem;
    }
    .cv-promo-card-badge {
        display: inline-flex;
        align-items: center;
        align-self: flex-start;
        font-size: .65rem;
        font-weight: 700;
        padding: .2rem .5rem;
        border-radius: 100px;
        background: rgba(245, 158, 11, 0.1);
        color: #D97706;
        margin-bottom: .25rem;
        line-height: 1;
        letter-spacing: .02em;
    }
    .cv-promo-card-badge.service { background: rgba(59, 130, 246, 0.1); color: #2563EB; }
    .cv-promo-card-name {
        font-size: .8125rem;
        font-weight: 600;
        color: #1E293B;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .cv-promo-card-price {
        font-size: .9rem;
        font-weight: 700;
        color: #1E293B;
        margin-top: auto;
    }
    .cv-promo-card-price-old {
        font-size: .7rem;
        color: #94A3B8;
        text-decoration: line-through;
        font-weight: 400;
    }
    .cv-promo-card-discount {
        display: inline-block;
        font-size: .65rem;
        font-weight: 700;
        color: #DC2626;
        background: #FEE2E2;
        padding: .1rem .35rem;
        border-radius: 4px;
    }
    .cv-promo-card-meta {
        display: flex;
        align-items: center;
        gap: .3rem;
        font-size: .7rem;
        color: #94A3B8;
        margin-top: .25rem;
    }
    .cv-promo-card-star { color: #FBBF24; }

    /* Nav buttons */
    .cv-promo-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        box-shadow: 0 2px 8px rgba(0,0,0,.1);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #1E293B;
        transition: all .2s;
    }
    .cv-promo-nav:hover { background: var(--accent, #0EA5E9); border-color: var(--accent, #0EA5E9); color: #fff; }
    .cv-promo-nav-prev { right: auto; left: -16px; }
    .cv-promo-nav-next { left: auto; right: -16px; }

    @media (max-width: 768px) {
        .cv-promo-grid { grid-template-columns: 140px 1fr; }
        .cv-promo-banner-wrap { min-height: 200px; }
        .cv-promo-swiper .swiper-slide { width: 140px !important; }
        .cv-promo-section { padding: 1.25rem 1rem; }
    }
    @media (max-width: 480px) {
        .cv-promo-grid { grid-template-columns: 120px 1fr; }
        .cv-promo-banner-wrap { min-height: 180px; }
        .cv-promo-swiper .swiper-slide { width: 130px !important; }
    }
    </style>

    <div class="cv-promo-wrap">
    @foreach($promoSections as $promo)
    @if($promo->dynamic_services->count() > 0)
    @php
        $promoItems = $promo->dynamic_services;
        $isFlashSale = !empty($promo->bg_color_1);
        $bgStyle = "";
        if ($isFlashSale) {
            $c1 = $promo->bg_color_1;
            $c2 = !empty($promo->bg_color_2) ? $promo->bg_color_2 : $c1;
            /* Remove inline padding/margin/border-radius so it respects CSS classes */
            $bgStyle = "background: linear-gradient(90deg, {$c1} 0%, {$c2} 100%);";
        }
    @endphp
    <div class="cv-promo-section {{ $isFlashSale ? 'is-flash-section' : '' }}" style="{{ $bgStyle }}">
        {{-- Header --}}
        <div class="cv-promo-header" style="align-items:center;">
            <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
                @if($promo->logo)
                    <img src="{{ asset('storage/'.$promo->logo) }}" alt="{{ $promo->title }}" loading="lazy" style="max-height:30px; object-fit:contain;">
                @else
                    <div>
                        <div class="cv-promo-title">{{ $promo->title }}</div>
                        @if($promo->subtitle)<div class="cv-promo-subtitle">{{ $promo->subtitle }}</div>@endif
                    </div>
                @endif
                
                {{-- Timer --}}
                @if($promo->end_time && $promo->end_time > now())
                <div class="cv-flash-timer" data-endtime="{{ $promo->end_time->toIso8601String() }}" style="display:flex;gap:.25rem;align-items:center;">
                    <div class="timer-box">00</div>
                    <span class="timer-sep">:</span>
                    <div class="timer-box">00</div>
                    <span class="timer-sep">:</span>
                    <div class="timer-box">00</div>
                </div>
                @endif
            </div>
            @if($promo->view_all_url)
                <a href="{{ $promo->view_all_url }}" class="cv-promo-view-all">Lihat semua →</a>
            @endif
        </div>

        {{-- Grid --}}
        <div class="cv-promo-grid">
            {{-- Banner Left (Desktop Only) --}}
            <div class="cv-promo-banner-wrap desktop-only">
                @if($promo->banner)
                                <img src="{{ asset('storage/'.$promo->banner) }}" alt="{{ $promo->title }}" loading="lazy" class="cv-promo-banner-img">
                @else
                    <div class="cv-promo-banner-placeholder">
                        <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        <span>{{ $promo->title }}</span>
                    </div>
                @endif
            </div>

            {{-- Products Swiper --}}
            <div class="cv-promo-swiper-container" style="position:relative;">
                <div class="swiper cv-promo-swiper" id="promoSwiper{{ $promo->id }}">
                    <div class="swiper-wrapper">
                        {{-- Banner Mobile (Inside Swiper) --}}
                        <div class="swiper-slide cv-promo-banner-wrap mobile-only">
                            @if($promo->banner)
                                            <img src="{{ asset('storage/'.$promo->banner) }}" alt="{{ $promo->title }}" loading="lazy" class="cv-promo-banner-img">
                            @elseif($isFlashSale)
                                <div class="cv-promo-banner-placeholder" style="background: linear-gradient(135deg, #ef4444, #f97316); display:flex; flex-direction:column; align-items:center; justify-content:center; color:#fff; width:100%; height:100%;">
                                    <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                                    <span style="font-size:1rem; font-weight:800; font-style:italic; margin-top:0.5rem; text-align:center;">FLASH<br>SALE</span>
                                </div>
                            @endif
                        </div>

                        @foreach($promoItems as $svc)
                        @php
                            $effPrice = ($svc->sale_price > 0 && $svc->sale_price < $svc->price) ? $svc->sale_price : $svc->price;
                            $discount = ($svc->sale_price > 0 && $svc->sale_price < $svc->price)
                                ? round((($svc->price - $svc->sale_price)/$svc->price)*100) : 0;
                        @endphp
                        <div class="swiper-slide">
                            <a href="{{ route('products.show', $svc->slug) }}" class="cv-promo-card">
                                @if($svc->image)
                                    <img src="{{ asset('storage/'.$svc->image) }}" alt="{{ $svc->name }}" class="cv-promo-card-img" loading="lazy">
                                @else
                                    <div class="cv-promo-card-img-placeholder">
                                        <svg width="32" height="32" fill="none" stroke="#CBD5E1" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                    </div>
                                @endif
                                <div class="cv-promo-card-body">
                                    <span class="cv-promo-card-badge {{ $svc->type === 'service' ? 'service' : '' }}">
                                        {{ $svc->type === 'service' ? 'Jasa' : 'Produk' }}
                                    </span>
                                    <div class="cv-promo-card-name">{{ $svc->name }}</div>
                                    @if($svc->price > 0)
                                    <div>
                                        @if($discount > 0)
                                        <div class="cv-promo-card-price-old">Rp{{ number_format($svc->price,0,',','.') }}</div>
                                        @endif
                                        <div style="display:flex;align-items:center;gap:.3rem;flex-wrap:wrap;">
                                            <span class="cv-promo-card-price">Rp{{ number_format($effPrice,0,',','.') }}</span>
                                            @if($discount > 0)
                                                <span class="cv-promo-card-discount">{{ $discount }}%</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @if($svc->rating > 0 || $svc->sold_count > 0)
                                    <div class="cv-promo-card-meta">
                                        @if($svc->rating > 0)
                                            <span class="cv-promo-card-star">★</span>
                                            <span>{{ number_format($svc->rating,1) }}</span>
                                        @endif
                                        @if($svc->sold_count > 0)
                                            <span>· {{ $svc->sold_count >= 1000 ? number_format($svc->sold_count/1000,1).'rb' : $svc->sold_count }} {{ $svc->type === 'service' ? 'dipesan' : 'terjual' }}</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @if($promoItems->count() > 5)
                <button class="cv-promo-nav cv-promo-nav-prev" id="promoPrev{{ $promo->id }}" aria-label="Sebelumnya">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <button class="cv-promo-nav cv-promo-nav-next" id="promoNext{{ $promo->id }}" aria-label="Berikutnya">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
    @endforeach
    </div> <!-- close cv-promo-wrap -->
    @endif
    
    {{-- ════ KATALOG SEMUA PRODUK ════ --}}
    @if(isset($allProducts) && $allProducts->count() > 0)
    <div style="background: #fff; padding: 2rem 0 3rem; border-top: none; box-shadow: none;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;" class="cv-rekomendasi-container">
        <style>
            @media (max-width: 768px) {
                .cv-rekomendasi-container { padding: 0 1rem !important; }
                .cv-rekomendasi-title { font-size: 1rem !important; }
                .cv-rekomendasi-subtitle { display: none; }
            }
        </style>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; border-top: none;">
            <div>
                <h2 class="cv-rekomendasi-title" style="font-family:'Montserrat',sans-serif; font-size:1.125rem; font-weight:800; color:#1E293B; letter-spacing:-0.02em; margin:0 0 0.2rem;">Rekomendasi Untukmu</h2>
                <div class="cv-rekomendasi-subtitle" style="font-size:0.8rem; color:#64748B;">Temukan berbagai produk pilihan terbaik dari buyle.id</div>
            </div>
            <a href="{{ route('products') }}" style="font-size:0.75rem; font-weight:600; color:#0EA5E9; text-decoration:none; white-space:nowrap;">Lihat Semua &rarr;</a>
        </div>
        
        <div class="catalog-grid" style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.875rem;">
            @foreach($allProducts->take(30) as $svc)
            @php
                $effPrice = ($svc->sale_price > 0 && $svc->sale_price < $svc->price) ? $svc->sale_price : $svc->price;
                $discount = ($svc->sale_price > 0 && $svc->sale_price < $svc->price)
                    ? round((($svc->price - $svc->sale_price)/$svc->price)*100) : 0;
            @endphp
            <a href="{{ route('products.show', $svc->slug) }}" class="catalog-card">
                @if($svc->image)
                    <div class="catalog-card-img-wrap">
                        <img src="{{ asset('storage/'.$svc->image) }}" alt="{{ $svc->name }}" class="catalog-card-img" loading="lazy">
                        @if($discount > 0)
                        <span class="catalog-card-badge-discount">{{ $discount }}%</span>
                        @endif
                    </div>
                @else
                    <div class="catalog-card-img-wrap catalog-card-no-img">
                        <svg width="36" height="36" fill="none" stroke="#CBD5E1" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        @if($discount > 0)
                        <span class="catalog-card-badge-discount">{{ $discount }}%</span>
                        @endif
                    </div>
                @endif
                <div class="catalog-card-body">
                    <div class="catalog-card-name">{{ $svc->name }}</div>
                    @if($svc->price > 0)
                    <div class="catalog-card-price-wrap">
                        @if($discount > 0)
                        <div class="catalog-card-price-old">Rp{{ number_format($svc->price,0,',','.') }}</div>
                        @endif
                        <div class="catalog-card-price">Rp{{ number_format($effPrice,0,',','.') }}</div>
                    </div>
                    @endif
                    @if($svc->rating > 0 || $svc->sold_count > 0)
                    <div class="catalog-card-meta">
                        @if($svc->rating > 0)
                            <span style="color:#FBBF24;">★</span>
                            <span>{{ number_format($svc->rating,1) }}</span>
                        @endif
                        @if($svc->sold_count > 0)
                            <span>· {{ $svc->sold_count >= 1000 ? number_format($svc->sold_count/1000,1).'rb' : $svc->sold_count }} terjual</span>
                        @endif
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        
        <div style="text-align: center; margin-top: 2.5rem;">
            <a href="{{ route('products') }}" class="catalog-btn-more">
                Lihat Katalog Lengkap
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="vertical-align:middle;"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
    </div>
    </div>
    @endif

    <style>
        /* ── Catalog Cards ── */
        .catalog-card {
            display: flex;
            flex-direction: column;
            background: #fff;
            border: 1.5px solid #F1F5F9;
            border-radius: 14px;
            overflow: hidden;
            text-decoration: none;
            transition: border-color .25s, box-shadow .25s, transform .25s;
        }
        .catalog-card:hover {
            border-color: #0EA5E9;
            box-shadow: 0 8px 24px rgba(14,165,233,.12);
            transform: translateY(-3px);
        }
        .catalog-card-img-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 1/1;
            background: #F8FAFC;
            overflow: hidden;
        }
        .catalog-card-no-img {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .catalog-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }
        .catalog-card:hover .catalog-card-img { transform: scale(1.04); }
        .catalog-card-badge-discount {
            position: absolute;
            top: 8px; left: 8px;
            background: #ef4444;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 5px;
        }
        .catalog-card-body {
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            flex: 1;
        }
        .catalog-card-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #1E293B;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .catalog-card-price-wrap { margin-top: 0.2rem; }
        .catalog-card-price-old {
            font-size: 0.68rem;
            color: #94A3B8;
            text-decoration: line-through;
        }
        .catalog-card-price {
            font-size: 0.875rem;
            font-weight: 700;
            color: #1E293B;
        }
        .catalog-card-meta {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.68rem;
            color: #94A3B8;
            margin-top: 0.2rem;
        }
        .catalog-btn-more {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.75rem 2.5rem;
            background: linear-gradient(135deg, #0EA5E9, #0369a1);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            border-radius: 999px;
            text-decoration: none;
            transition: opacity 0.2s, transform 0.2s;
            box-shadow: 0 4px 16px rgba(14,165,233,0.3);
        }
        .catalog-btn-more:hover { opacity: 0.9; transform: translateY(-1px); }
        @media (max-width: 1024px) {
            .catalog-grid { grid-template-columns: repeat(4, 1fr) !important; }
        }
        @media (max-width: 768px) {
            .catalog-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 0.5rem !important; }
        }
    </style>

    @if($clients->count())
        {{-- Clients section: REMOVED per user request - only keep promo + footer --}}
    @endif

@include('components.lightbox-assets')

<script>
    // Load Swiper lazily after page is interactive — does NOT block LCP
    function loadSwiper(cb) {
        if (window.Swiper) { cb(); return; }
        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
        s.onload = cb;
        document.head.appendChild(s);
    }
    (window.requestIdleCallback || function(fn){ setTimeout(fn, 200); })(function() {
        loadSwiper(initSwipers);
    });
    function initSwipers() {
        // ── Hero Slider with dynamic oval bg color ──
        var heroOvalBg = document.getElementById('heroOvalBg');
        if(document.querySelector('.hero-swiper')) {
            var heroSwiper = new Swiper('.hero-swiper', {
                loop: true,
                autoplay: {
                    delay: 4500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.hero-swiper-pagination',
                    clickable: true,
                },
                keyboard: { enabled: true },
                grabCursor: true,
                observer: true,
                observeParents: true,
                resizeObserver: true,
            });
        }

        // ── Category Swiper (Desktop) ──
        if(document.querySelector('#catsSwiperDesktop')) {
            const catsSwiper = new Swiper('#catsSwiperDesktop', {
                slidesPerView: 'auto',
                spaceBetween: 12,
                grabCursor: true,
                freeMode: true,
                mousewheel: {
                    forceToAxis: true,
                },
                navigation: {
                    nextEl: '#catsNext',
                    prevEl: '#catsPrev',
                },
            });
        }

        // ── Promo Section Swipers ──
        document.querySelectorAll('.cv-promo-swiper').forEach(function(el) {
            const id = el.id.replace('promoSwiper', '');
            new Swiper('#promoSwiper' + id, {
                slidesPerView: 'auto',
                spaceBetween: 10,
                grabCursor: true,
                freeMode: false,
                navigation: {
                    nextEl: '#promoNext' + id,
                    prevEl: '#promoPrev' + id,
                },
            });
        });
    } // end initSwipers
    // Flash Sale Timer Logic
    document.addEventListener('DOMContentLoaded', function() {
        const timers = document.querySelectorAll('.cv-flash-timer');
        if(timers.length === 0) return;

        function updateTimers() {
            const now = new Date().getTime();
            timers.forEach(timer => {
                const endTimeStr = timer.getAttribute('data-endtime');
                if(!endTimeStr) return;
                const endTime = new Date(endTimeStr).getTime();
                const distance = endTime - now;

                const boxes = timer.querySelectorAll('.timer-box');
                if(boxes.length !== 3) return;

                if(distance < 0) {
                    boxes[0].innerText = '00';
                    boxes[1].innerText = '00';
                    boxes[2].innerText = '00';
                    return;
                }

                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                boxes[0].innerText = hours < 10 ? '0' + hours : hours;
                boxes[1].innerText = minutes < 10 ? '0' + minutes : minutes;
                boxes[2].innerText = seconds < 10 ? '0' + seconds : seconds;
            });
        }
        
        updateTimers();
        setInterval(updateTimers, 1000);
    });
</script>

@endsection

