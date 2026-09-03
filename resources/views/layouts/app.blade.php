<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    {{-- Favicon --}}
    <link rel="icon" type="{{ $favType }}" href="{{ $favicon }}?v={{ $favVer }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $favicon }}?v={{ $favVer }}">
    <link rel="shortcut icon" href="{{ $favicon }}?v={{ $favVer }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}?v={{ $favVer }}">

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
    @if(file_exists(public_path('build/assets')) && count(glob(public_path('build/assets/*.css'))) > 0)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @if(file_exists(public_path('css/app.css')))
            <style>
                {!! file_get_contents(public_path('css/app.css')) !!}
            </style>
        @else
            <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        @endif
    @endif

    {{-- Dynamic Theme Colors & Global Font Override --}}
    <style>
        :root {
            --font-jakarta: 'Montserrat', sans-serif !important;
            --font-main: 'Montserrat', sans-serif !important;
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
    
    @stack('head')
</head>

<body style="overflow-x: hidden; margin: 0; padding: 0; background-color: #ffffff;">

    {{-- Global Skeleton Loader --}}
    <style>
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
        .skel-hero-wrapper { margin-bottom: 2rem; width: 100%; }
        .skel-hero-grid { display: none; }
        .skel-hero-mob-slide { width: 100%; aspect-ratio: 2 / 1; border-radius: 14px; }
        @media(min-width: 1025px) {
            .skel-hero-mob-slide { display: none; }
            .skel-hero-grid { display: grid; grid-template-columns: 2.3fr 1fr; gap: 1.25rem; align-items: stretch; height: 440px; }
            .skel-hero-main { border-radius: 16px; height: 100%; }
            .skel-hero-side { display: flex; flex-direction: column; gap: 0.75rem; height: 100%; }
            .skel-hero-sub { flex: 1; border-radius: 16px; min-height: 0; }
        }
        
        /* Grid */
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
        @media(min-width: 768px) and (max-width: 1023px) {
            .skel-pd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
            .skel-pd-sidebar { display: none; }
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
                <div class="skel-grid">
                    @for($i=0; $i<10; $i++) 
                        <div class="skel-shimmer skel-card {{ $i > 3 ? 'hide-on-mobile' : '' }}"></div> 
                    @endfor
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
        window.addEventListener('load', function() {
            const s = document.getElementById('cv-app-preloader');
            if(s) { s.classList.add('fade-out'); setTimeout(() => { s.remove(); }, 400); }
        });
    </script>

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

    {{-- Floating Chat Widget --}}
    <style>
        .fc-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            font-family: 'Montserrat', sans-serif !important;
        }

        .fc-btn {
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
            border-radius: 99px;
            padding: .75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
            cursor: pointer;
            transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            font-weight: 700;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif !important;
        }

        .fc-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(30, 179, 73, 0.45);
        }

        .fc-btn svg {
            width: 24px;
            height: 24px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .fc-panel {
            position: absolute;
            bottom: calc(100% + 20px);
            right: 0;
            width: 340px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
            border: 1.5px solid #F1F5F9;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
            transform: translateY(20px) scale(0.95);
            transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: bottom right;
        }

        .fc-widget.open .fc-panel {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }

        .fc-head {
            background: #fff;
            padding: 1.5rem;
            position: relative;
            border-bottom: 1px solid #F1F5F9;
        }

        .fc-head h3 {
            margin: 0 0 .25rem;
            font-size: 1.1rem;
            font-weight: 800;
            color: #1E293B;
        }

        .fc-head p {
            margin: 0;
            font-size: .8rem;
            color: #64748B;
            font-weight: 500;
            line-height: 1.4;
        }

        .fc-close {
            position: absolute;
            top: 1.2rem;
            right: 1.2rem;
            background: #F1F5F9;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            color: #64748B;
            cursor: pointer;
            transition: all .2s;
        }

        .fc-close:hover {
            background: #E2E8F0;
            color: #1E293B;
        }

        .fc-body {
            padding: 1.5rem;
            background: #fafafa;
        }

        .fc-form-group {
            margin-bottom: 1.25rem;
        }

        .fc-label {
            display: block;
            font-size: .75rem;
            font-weight: 700;
            color: #64748B;
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .fc-input,
        .fc-textarea {
            width: 100%;
            padding: .75rem 1rem;
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: .85rem;
            outline: none;
            transition: all .2s;
            background: #fff;
            color: #0F172A;
        }

        .fc-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .fc-input:focus,
        .fc-textarea:focus {
            border-color: #1eb349;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.1);
        }

        .fc-submit {
            width: 100%;
            padding: .85rem;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: .9rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }

        .fc-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(30, 179, 73, 0.3);
        }
    </style>

    <div class="fc-widget" id="fcWidget">
        <button class="fc-btn" onclick="document.getElementById('fcWidget').classList.toggle('open')">
            <svg viewBox="0 0 24 24">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
            Chat Buyle.id
        </button>
        <div class="fc-panel">
            <div class="fc-head">
                <button class="fc-close" aria-label="Tutup chat"
                    onclick="document.getElementById('fcWidget').classList.remove('open')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
                <h3>Customer Service</h3>
                <p>Ada pertanyaan? Jangan ragu untuk menghubungi tim kami via WhatsApp.</p>
            </div>
            <div class="fc-body">
                <form action="{{ route('chat.store') }}" method="POST">
                    @csrf
                    @guest
                        <div class="fc-form-group">
                            <label class="fc-label">Nama Lengkap</label>
                            <input type="text" name="name" class="fc-input" placeholder="Masukkan nama..." required>
                        </div>
                        <div class="fc-form-group">
                            <label class="fc-label">Nomor WhatsApp</label>
                            <input type="text" name="phone" class="fc-input" placeholder="Contoh: 0812..." required>
                        </div>
                    @endguest
                    <div class="fc-form-group">
                        <label class="fc-label">Pesan / Kebutuhan</label>
                        <textarea name="message" class="fc-textarea" placeholder="Tuliskan pertanyaan Anda di sini..."
                            required></textarea>
                    </div>
                    <button type="submit" class="fc-submit">
                        Kirim Pesan
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <line x1="22" y1="2" x2="11" y2="13" />
                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Mobile Bottom Navbar (Capsule Style) --}}
    <style>
        .mobile-bottom-nav {
            display: none;
        }

        @media (max-width: 768px) {
            .fc-widget {
                @if(request()->routeIs('products.show'))
                bottom: 160px !important;
                @else
                bottom: 90px !important;
                @endif
            }

            /* Move chat widget up */
            .mobile-bottom-nav {
                display: flex;
                position: fixed;
                bottom: 15px;
                left: 50%;
                transform: translateX(-50%);
                width: 92%;
                max-width: 400px;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border-radius: 999px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.05);
                padding: 0.5rem 1rem;
                justify-content: space-between;
                align-items: center;
                z-index: 99999;
                border: 1px solid rgba(255, 255, 255, 0.6);
            }

            .nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                color: #64748B;
                font-size: 0.65rem;
                font-weight: 600;
                gap: 4px;
                padding: 0.25rem 0.5rem;
                transition: all 0.3s ease;
                position: relative;
                font-family: 'Montserrat', sans-serif;
            }

            .nav-item.active {
                color: #1eb349;
            }

            .nav-item svg {
                width: 22px;
                height: 22px;
                stroke-width: 2;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .nav-item.active svg {
                transform: translateY(-3px);
                stroke-width: 2.5;
            }

            .nav-badge {
                position: absolute;
                top: -2px;
                right: 2px;
                background: #EF4444;
                color: white;
                font-size: 0.55rem;
                font-weight: bold;
                padding: 0.1rem 0.3rem;
                border-radius: 999px;
                min-width: 14px;
                text-align: center;
                border: 1.5px solid white;
                line-height: 1;
            }
        }
    </style>




</body>

</html>