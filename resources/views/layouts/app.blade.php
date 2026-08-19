<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    @php
        // Single cached DB call for ALL settings — prevents N+1 queries per page load
        $layoutSettings = \App\Models\Setting::getAllAsArray();
        $favVer         = md5(json_encode($layoutSettings));
        
        // Resolve favicon
        if (!empty($layoutSettings['favicon'])) {
            $favPath = ltrim($layoutSettings['favicon'], '/');
            $favicon = asset('storage/' . $favPath) . '?v=' . $favVer;
            $favExt  = strtolower(pathinfo($favPath, PATHINFO_EXTENSION));
            $favTypeMap = ['png' => 'image/png', 'svg' => 'image/svg+xml', 'webp' => 'image/webp', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg'];
            $favType = $favTypeMap[$favExt] ?? 'image/x-icon';
        } else {
            $favicon = asset('favicon.ico') . '?v=' . $favVer;
            $favType = 'image/x-icon';
        }

        $tAccent        = $layoutSettings['color_accent'] ?? null;
        $tMain          = $layoutSettings['color_main']   ?? null;
        $tText          = $layoutSettings['color_text']   ?? null;
        $breadcrumbBg   = $layoutSettings['breadcrumb_bg'] ?? null;
        $preloaderLogo  = $layoutSettings['logo'] ?? null;
        $headScripts    = $layoutSettings['head_scripts']  ?? '';
        $bodyScripts    = $layoutSettings['body_scripts']  ?? '';
    @endphp

    {{-- SEO Component --}}
    @include('components.seo')

    {{-- Favicon --}}
    <link rel="icon" type="{{ $favType }}" href="{{ $favicon }}">
    <link rel="shortcut icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">

    {{-- Google Fonts: Montserrat — Non-blocking (reduced weights for speed) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet"></noscript>

    {{-- AOS Animate on Scroll — Non-blocking --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"></noscript>

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
        body, html, button, input, textarea, select, .cv-promo-card-name {
            font-family: 'Montserrat', sans-serif !important;
        }
        
    @if($tAccent || $tMain || $tText)
        :root {
            @if($tAccent) --accent: {{ $tAccent }} !important; --accent-dark: {{ $tAccent }} !important; @endif
            @if($tMain)   --bg-base: {{ $tMain }} !important; --bg-1: {{ $tMain }} !important; @endif
            @if($tText)   --text-1: {{ $tText }} !important; @endif
        }
    @endif
    </style>

    {{-- Breadcrumb / Page Hero Background --}}
    @if($breadcrumbBg)
    <style>
        .page-hero {
            background-image: url('{{ asset('storage/'.$breadcrumbBg) }}') !important;
            background-size: cover !important;
            background-position: center center !important;
            position: relative;
        }
        .page-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(12,26,58, 0.75);
            z-index: 0;
        }
        .page-hero::after {
            content: "";
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 100px;
            background: linear-gradient(to bottom, transparent, var(--bg-base));
            z-index: 1;
            pointer-events: none;
        }
        .page-hero > div,
        .page-hero .sv-hero-inner,
        .page-hero .article-hero-inner {
            position: relative;
            z-index: 2;
        }
    </style>
    @endif

    @stack('styles')

    {{-- Custom Head Scripts (e.g. GTM, Analytics) --}}
    {!! $headScripts !!}
</head>
<body style="overflow-x: hidden; margin: 0; padding: 0; background-color: #ffffff;">

    @include('components.navbar')

    {{-- Main Content --}}
    <main style="background-color: #ffffff; min-height: 60vh;">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Floating WA Button --}}
    @include('components.wa-button')

    {{-- Request Order Modal (global) --}}
    @include('components.order-modal')

    {{-- AOS — Defer --}}
    <script>
    (function() {
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
    document.addEventListener('click', function(e) {
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
    .fc-widget { position: fixed; bottom: 30px; right: 30px; z-index: 9999; font-family: 'Montserrat', sans-serif !important; }
    .fc-btn {
        background: linear-gradient(135deg, #0EA5E9, #0369A1); color: #fff; border-radius: 99px; padding: .75rem 1.25rem;
        display: flex; align-items: center; gap: .75rem; box-shadow: 0 4px 14px rgba(14,165,233,0.35);
        cursor: pointer; transition: all .3s cubic-bezier(0.4, 0, 0.2, 1); border: none; font-weight: 700; font-size: 1rem;
        font-family: 'Montserrat', sans-serif !important;
    }
    .fc-btn:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(14,165,233,0.45); }
    .fc-btn svg { width: 24px; height: 24px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    
    .fc-panel {
        position: absolute; bottom: calc(100% + 20px); right: 0; width: 340px; background: #fff;
        border-radius: 20px; box-shadow: 0 15px 50px rgba(0,0,0,0.15); border: 1.5px solid #F1F5F9;
        overflow: hidden; opacity: 0; pointer-events: none; transform: translateY(20px) scale(0.95);
        transition: all .3s cubic-bezier(0.4, 0, 0.2, 1); transform-origin: bottom right;
    }
    .fc-widget.open .fc-panel { opacity: 1; pointer-events: auto; transform: translateY(0) scale(1); }
    
    .fc-head { background: linear-gradient(135deg, #0EA5E9, #0369A1); padding: 1.5rem; color: #fff; position: relative; }
    .fc-head h3 { margin: 0 0 .25rem; font-size: 1.1rem; font-weight: 800; }
    .fc-head p { margin: 0; font-size: .8rem; opacity: 0.9; font-weight: 500; line-height: 1.4; }
    .fc-close { position: absolute; top: 1rem; right: 1rem; background: transparent; border: none; color: #fff; cursor: pointer; opacity: 0.7; transition: opacity .2s; }
    .fc-close:hover { opacity: 1; }
    
    .fc-body { padding: 1.5rem; }
    .fc-form-group { margin-bottom: 1.25rem; }
    .fc-label { display: block; font-size: .75rem; font-weight: 700; color: #64748B; margin-bottom: .4rem; text-transform: uppercase; letter-spacing: .03em; }
    .fc-input, .fc-textarea {
        width: 100%; padding: .75rem 1rem; border: 1.5px solid #E2E8F0; border-radius: 12px;
        font-family: 'Montserrat', sans-serif; font-size: .85rem; outline: none; transition: all .2s; background: #F8FAFC; color: #0F172A;
    }
    .fc-textarea { resize: vertical; min-height: 80px; }
    .fc-input:focus, .fc-textarea:focus { border-color: #0EA5E9; background: #fff; box-shadow: 0 0 0 3px rgba(14,165,233,0.1); }
    .fc-submit {
        width: 100%; padding: .85rem; background: #0F172A; color: #fff; font-family: 'Montserrat', sans-serif;
        font-weight: 800; font-size: .9rem; border: none; border-radius: 12px; cursor: pointer; transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: .5rem;
    }
    .fc-submit:hover { background: #1E293B; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(15,23,42,0.2); }
    </style>

    <div class="fc-widget" id="fcWidget">
        <button class="fc-btn" onclick="document.getElementById('fcWidget').classList.toggle('open')">
            <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Chat Admin
        </button>
        <div class="fc-panel">
            <div class="fc-head">
                <button class="fc-close" aria-label="Tutup chat" onclick="document.getElementById('fcWidget').classList.remove('open')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
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
                        <textarea name="message" class="fc-textarea" placeholder="Tuliskan pertanyaan Anda di sini..." required></textarea>
                    </div>
                    <button type="submit" class="fc-submit">
                        Kirim Pesan
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Mobile Bottom Navbar (Capsule Style) --}}
    <style>
    .mobile-bottom-nav { display: none; }
    @media (max-width: 768px) {
        .fc-widget { bottom: 90px !important; } /* Move chat widget up */
        .mobile-bottom-nav {
            display: flex; position: fixed; bottom: 15px; left: 50%; transform: translateX(-50%);
            width: 92%; max-width: 400px; background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            border-radius: 999px; box-shadow: 0 10px 40px rgba(0,0,0,0.1), 0 1px 3px rgba(0,0,0,0.05);
            padding: 0.5rem 1rem; justify-content: space-between; align-items: center;
            z-index: 99999; border: 1px solid rgba(255,255,255,0.6);
        }
        .nav-item {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-decoration: none; color: #64748B; font-size: 0.65rem; font-weight: 600; gap: 4px;
            padding: 0.25rem 0.5rem; transition: all 0.3s ease; position: relative;
            font-family: 'Montserrat', sans-serif;
        }
        .nav-item.active { color: #0EA5E9; }
        .nav-item svg { width: 22px; height: 22px; stroke-width: 2; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .nav-item.active svg { transform: translateY(-3px); stroke-width: 2.5; }
        .nav-badge {
            position: absolute; top: -2px; right: 2px; background: #EF4444; color: white;
            font-size: 0.55rem; font-weight: bold; padding: 0.1rem 0.3rem; border-radius: 999px;
            min-width: 14px; text-align: center; border: 1.5px solid white; line-height: 1;
        }
    }
    </style>

    @if(!request()->routeIs('checkout.*'))
    <div class="mobile-bottom-nav">
        <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Beranda</span>
        </a>
        <a href="{{ route('products') }}" class="nav-item {{ request()->routeIs('products*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            <span>Kategori</span>
        </a>
        <a href="{{ route('cart.index') }}" class="nav-item {{ request()->routeIs('cart*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <span>Keranjang</span>
            @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
            @if($cartCount > 0)
                <span class="nav-badge">{{ $cartCount }}</span>
            @endif
        </a>
        <a href="{{ route('account.overview') }}" class="nav-item {{ request()->routeIs('account*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>Akun</span>
        </a>
    </div>
    @endif

</body>
</html>
