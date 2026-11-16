@php
    $waUrl = 'https://wa.me/6285179982373?text=' . urlencode('Halo Buyle TIX, saya ingin konsultasi dan kerjasama ticketing event.');
    $activeRole = request('role', 'event_creator'); // 'event_creator' | 'buyle_creator' | 'buyer'
    $activeMode = request('mode', 'register');      // 'register' | 'login'
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Masuk &amp; Daftar · Buyle TIX</title>
    <meta name="description" content="Portal autentikasi Buyle TIX untuk Event Creator, Buyle Creator, dan Pembeli. Masuk instan via Google.">
    <meta name="theme-color" content="#020704">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="icon" type="image/png" href="/favicon.png">

    <!-- Fonts: Montserrat (Official Buyle Font) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --buyle-green: #1eb349;
            --buyle-green-light: #a5cf37;
            --buyle-gradient: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            --buyle-gradient-h: linear-gradient(135deg, #18983e 0%, #95bd30 100%);
            --bg-dark: #020704;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif !important;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            height: 100dvh;
            max-height: 100dvh;
            overflow: hidden !important;
            background: #020704;
            color: #ffffff;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* ── AMBIENT BACKGROUND GLOW ── */
        .ambient-bg {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 1;
            background:
                radial-gradient(circle at 50% 20%, rgba(30,179,73,0.22) 0%, rgba(5,18,9,0) 55%),
                radial-gradient(circle at 85% 70%, rgba(165,207,55,0.12) 0%, rgba(2,7,4,0) 50%),
                radial-gradient(circle at 15% 80%, rgba(16,185,129,0.10) 0%, rgba(2,7,4,0) 50%),
                #020704;
        }

        /* ── MAIN CANVAS (Mockup container) ── */
        .auth-canvas {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            height: 100dvh;
            max-height: 840px;
            background: #020704;
            border-radius: 40px;
            border: 1.5px solid rgba(255,255,255,0.1);
            box-shadow: 0 30px 90px rgba(0,0,0,0.85), 0 0 60px rgba(30,179,73,0.18);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
        }

        @media (max-width: 768px) {
            .auth-canvas {
                max-width: 100%;
                height: 100dvh;
                max-height: 100dvh;
                border-radius: 0;
                border: none;
                box-shadow: none;
            }
        }

        /* ── HERO CONCERT SLIDESHOW (Takes top half) ── */
        .hero-wrap {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 48%;
            overflow: hidden;
            z-index: 2;
            pointer-events: none;
        }

        .hero-img {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center top;
            opacity: 0;
            transform: scale(1.05);
            transition: opacity 0.65s cubic-bezier(0.16,1,0.3,1), transform 1.2s cubic-bezier(0.16,1,0.3,1);
            filter: contrast(1.05) saturate(1.1);
        }

        .hero-img.active {
            opacity: 1;
            transform: scale(1);
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(2,7,4,0.15) 0%, rgba(2,7,4,0.55) 48%, #020704 100%),
                radial-gradient(circle at 50% 20%, rgba(30,179,73,0.25) 0%, transparent 65%);
        }

        /* ── CONTENT CONTAINER ── */
        .canvas-content {
            position: relative;
            z-index: 5;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            padding: 1.15rem 1.4rem calc(56px + 28px + env(safe-area-inset-bottom, 0px));
            box-sizing: border-box;
        }

        @media (min-width: 769px) {
            .canvas-content {
                padding: 1.5rem 1.75rem 2rem;
            }
        }

        /* ── BRAND LOGO TOP ── */
        .brand-header-row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            user-select: none;
        }

        .brand-logo-img {
            height: 30px;
            width: auto;
            display: block;
            filter: drop-shadow(0 2px 10px rgba(0,0,0,0.6));
        }

        /* ── BOTTOM CONTENT GROUP (Pinned to bottom of canvas) ── */
        .bottom-content-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: auto;
        }

        /* Title & Subtitle */
        .auth-title {
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.22;
            color: #ffffff;
            letter-spacing: -0.02em;
            margin: 0;
        }

        .auth-title .hl {
            background: linear-gradient(135deg, #22c55e 0%, #a3e635 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .auth-subtitle {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.72);
            font-weight: 500;
            line-height: 1.45;
            margin-top: 0.25rem;
        }

        /* Slide Dots */
        .hero-dots {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 0.5rem;
            margin-bottom: 0.15rem;
        }

        .hero-dot {
            height: 4.5px;
            width: 7px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.25);
            border: none;
            padding: 0;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
        }

        .hero-dot.active {
            width: 28px;
            background: var(--buyle-gradient);
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.6);
        }

        /* ── ROLE SWITCHER (3 Tabs) ── */
        .role-switcher {
            display: flex;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 3px;
            gap: 3px;
        }

        .role-tab {
            flex: 1;
            padding: 0.52rem 0.35rem;
            border-radius: 11px;
            font-size: 0.72rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.55);
            background: transparent;
            border: none;
            cursor: pointer;
            text-align: center;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
            line-height: 1.25;
            white-space: nowrap;
        }

        .role-tab.active {
            background: var(--buyle-gradient);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.45);
        }

        /* ── MODE SWITCHER (Daftar / Masuk) ── */
        .mode-switcher {
            display: flex;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 999px;
            padding: 3px;
            gap: 3px;
        }

        .mode-tab {
            flex: 1;
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            background: transparent;
            border: none;
            cursor: pointer;
            text-align: center;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .mode-tab.active {
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.22);
        }

        /* ── GOOGLE CTA BUTTON ── */
        .btn-google-cta {
            width: 100%;
            height: 52px;
            border-radius: 999px;
            background: var(--buyle-gradient);
            color: #ffffff;
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 28px rgba(30, 179, 73, 0.42), 0 2px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-google-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 34px rgba(30, 179, 73, 0.55);
            background: var(--buyle-gradient-h);
        }

        .btn-google-cta:active {
            transform: scale(0.98);
        }

        /* ══════════════════════════════════════════
           MOBILE BOTTOM NAVBAR (Capsule Icon-Only)
           Exact Style as buyle.id/tix
           ══════════════════════════════════════════ */
        .tix-bottom-nav {
            display: none;
        }

        @media (max-width: 768px) {
            .tix-bottom-nav {
                display: flex !important;
                position: fixed !important;
                bottom: 12px !important;
                left: 14px !important;
                right: 14px !important;
                max-width: 360px !important;
                margin: 0 auto !important;
                height: 56px !important;
                background: rgba(255, 255, 255, 0.96) !important;
                backdrop-filter: blur(16px) !important;
                -webkit-backdrop-filter: blur(16px) !important;
                border-radius: 999px !important;
                box-shadow: 0 10px 32px rgba(0, 0, 0, 0.22), 0 2px 8px rgba(0, 0, 0, 0.08) !important;
                border: 1.5px solid rgba(226, 232, 240, 0.9) !important;
                z-index: 99998 !important;
                align-items: center !important;
                justify-content: space-around !important;
                padding: 0 0.5rem !important;
                box-sizing: border-box !important;
            }

            .tix-bottom-nav-item {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 8px !important;
                border-radius: 999px !important;
                text-decoration: none !important;
                color: #64748b !important;
                transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
                flex: 1 !important;
                max-width: 72px !important;
                height: 42px !important;
                box-sizing: border-box !important;
                position: relative !important;
            }

            .tix-bottom-nav-item svg {
                width: 22px !important;
                height: 22px !important;
                display: block !important;
                transition: transform 0.2s ease, color 0.2s ease !important;
                flex-shrink: 0 !important;
                color: #64748b !important;
            }

            .tix-bottom-nav-item:hover {
                color: #15803d !important;
            }

            .tix-bottom-nav-item:hover svg {
                color: #15803d !important;
            }

            .tix-bottom-nav-item:active {
                transform: scale(0.92) !important;
            }

            .tix-bottom-nav-item.active {
                color: #15803d !important;
            }

            .tix-bottom-nav-item.active svg {
                color: #16a34a !important;
                stroke-width: 2.5 !important;
                transform: translateY(-1px) !important;
            }

            .tix-bottom-nav-item.active::before {
                content: '' !important;
                position: absolute !important;
                bottom: 4px !important;
                width: 14px !important;
                height: 3px !important;
                background: linear-gradient(135deg, #1db349 0%, #84ce00 100%) !important;
                border-radius: 999px !important;
            }
        }
    </style>
</head>

<body>
    {{-- Ambient Desktop Glow --}}
    <div class="ambient-bg"></div>

    {{-- Main Auth Canvas --}}
    <main class="auth-canvas">

        {{-- Hero Concert Slideshow (Top 52%) --}}
        <div class="hero-wrap" id="heroWrap">
            <div class="hero-img active" style="background-image: url('{{ asset('images/ticketing/concert1.jpg') }}');"></div>
            <div class="hero-img" style="background-image: url('{{ asset('images/ticketing/concert2.jpg') }}');"></div>
            <div class="hero-img" style="background-image: url('{{ asset('images/ticketing/concert3.jpg') }}');"></div>
            <div class="hero-overlay"></div>
        </div>

        {{-- Content inside Canvas (flex column space-between) --}}
        <div class="canvas-content">

            {{-- 1. Logo at Top Left --}}
            <div class="brand-header-row">
                <a href="{{ route('tix.index') }}" title="Buyle TIX">
                    <picture>
                        <source srcset="{{ asset('images/logobuyletix.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logobuyletix.png') }}" alt="Buyle TIX" class="brand-logo-img" onerror="this.src='{{ asset('images/buyle-logo.png') }}'">
                    </picture>
                </a>
            </div>

            {{-- 2. Bottom Content Group (Text, Dots, Switchers, CTA) --}}
            <div class="bottom-content-group">

                {{-- Title & Subtitle --}}
                <div id="authTitleWrap">
                    <h1 class="auth-title" id="authTitle">
                        Kelola Tiket Event,<br><span class="hl">Semudah 1 Klik.</span>
                    </h1>
                    <p class="auth-subtitle" id="authSubtitle">
                        Platform e-ticketing modern untuk konser, festival, dan seminar.
                    </p>
                </div>

                {{-- Slide Dots --}}
                <div class="hero-dots">
                    <button class="hero-dot active" onclick="goSlide(0)" aria-label="Slide 1"></button>
                    <button class="hero-dot" onclick="goSlide(1)" aria-label="Slide 2"></button>
                    <button class="hero-dot" onclick="goSlide(2)" aria-label="Slide 3"></button>
                </div>

                {{-- Role Switcher --}}
                <div class="role-switcher" role="tablist" aria-label="Pilih peran">
                    <button class="role-tab active" id="tab-event_creator" role="tab" onclick="switchRole('event_creator')">
                        Event Creator
                    </button>
                    <button class="role-tab" id="tab-buyle_creator" role="tab" onclick="switchRole('buyle_creator')">
                        Buyle Creator
                    </button>
                    <button class="role-tab" id="tab-buyer" role="tab" onclick="switchRole('buyer')">
                        Pembeli
                    </button>
                </div>

                {{-- Mode Switcher --}}
                <div class="mode-switcher" role="tablist" aria-label="Daftar atau Masuk">
                    <button class="mode-tab active" id="mtab-register" role="tab" onclick="switchMode('register')">
                        Daftar
                    </button>
                    <button class="mode-tab" id="mtab-login" role="tab" onclick="switchMode('login')">
                        Masuk
                    </button>
                </div>

                {{-- Google CTA Button --}}
                <a href="#" class="btn-google-cta" id="btnGoogleCta">
                    <svg width="20" height="20" viewBox="0 0 24 24">
                        <path fill="#fff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#fff" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#fff" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.85z"/>
                        <path fill="#fff" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span id="btnGoogleText">Daftar Event Creator via Google</span>
                </a>

            </div>{{-- /bottom-content-group --}}

        </div>{{-- /canvas-content --}}

    </main>

    {{-- Floating Bottom Navigation (Exact Style as buyle.id/tix - Icons Only) --}}
    <nav class="tix-bottom-nav" aria-label="Navigasi Bawah">
        {{-- Home --}}
        <a href="{{ url('/tix') }}"
           class="tix-bottom-nav-item {{ request()->is('tix') ? 'active' : '' }}"
           title="Home Buyle TIX">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </a>

        {{-- About Buyle --}}
        <a href="{{ url('/tix/info') }}"
           class="tix-bottom-nav-item {{ request()->is('tix/info*') ? 'active' : '' }}"
           title="About Buyle TIX">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
        </a>

        {{-- Kerjasama --}}
        <a href="{{ $waUrl }}" target="_blank" rel="noopener"
           class="tix-bottom-nav-item"
           title="Kerjasama Event Organizer (WhatsApp)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </a>

        {{-- Profile --}}
        <a href="{{ url('/tix/auth') }}"
           class="tix-bottom-nav-item {{ (request()->is('tix/auth*') || request()->is('login-ticketing*')) ? 'active' : '' }}"
           title="Profile / Masuk">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
        </a>
    </nav>

    <script>
        // ── DYNAMIC ROLES DATA ──
        const ROLES = {
            event_creator: {
                title:    'Kelola Tiket Event,<br><span class="hl">Semudah 1 Klik.</span>',
                subtitle: 'Platform e-ticketing modern untuk konser, festival, dan seminar.',
                register: {
                    btnText:  'Daftar Event Creator via Google',
                    url:      '{{ route("auth.google", ["role"=>"seller","redirect"=>url("/creator/event-attendance"),"mode"=>"register"]) }}',
                },
                login: {
                    btnText:  'Masuk Event Creator via Google',
                    url:      '{{ route("auth.google", ["role"=>"seller","redirect"=>url("/creator/event-attendance"),"mode"=>"login"]) }}',
                },
            },
            buyle_creator: {
                title:    'Jadi Buyle Creator,<br><span class="hl">Raih Penghasilan.</span>',
                subtitle: 'Kelola konten, link bio, dan monetisasi audiens Anda bersama Buyle.',
                register: {
                    btnText:  'Daftar Buyle Creator via Google',
                    url:      '{{ route("auth.google", ["role"=>"seller","redirect"=>url("/creator/dashboard"),"mode"=>"register"]) }}',
                },
                login: {
                    btnText:  'Masuk Buyle Creator via Google',
                    url:      '{{ route("auth.google", ["role"=>"seller","redirect"=>url("/creator/dashboard"),"mode"=>"login"]) }}',
                },
            },
            buyer: {
                title:    'Beli Tiket Event,<br><span class="hl">Tanpa Antri.</span>',
                subtitle: 'Temukan dan pesan tiket konser & festival favoritmu dalam hitungan detik.',
                register: {
                    btnText:  'Daftar Pembeli via Google',
                    url:      '{{ route("auth.google", ["role"=>"buyer","redirect"=>url("/akun/"),"mode"=>"register"]) }}',
                },
                login: {
                    btnText:  'Masuk Pembeli via Google',
                    url:      '{{ route("auth.google", ["role"=>"buyer","redirect"=>url("/akun/"),"mode"=>"login"]) }}',
                },
            },
        };

        let currentRole = '{{ $activeRole }}';
        let currentMode = '{{ $activeMode }}';

        function switchRole(role) {
            currentRole = role;
            document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
            const tab = document.getElementById('tab-' + role);
            if (tab) tab.classList.add('active');
            renderContent();
        }

        function switchMode(mode) {
            currentMode = mode;
            document.querySelectorAll('.mode-tab').forEach(t => t.classList.remove('active'));
            const mtab = document.getElementById('mtab-' + mode);
            if (mtab) mtab.classList.add('active');
            renderContent();
        }

        function renderContent() {
            const r = ROLES[currentRole];
            const m = r[currentMode];

            document.getElementById('authTitle').innerHTML = r.title;
            document.getElementById('authSubtitle').textContent = r.subtitle;

            document.getElementById('btnGoogleCta').href = m.url;
            document.getElementById('btnGoogleText').textContent = m.btnText;
        }

        document.addEventListener('DOMContentLoaded', function() {
            switchRole(currentRole);
            switchMode(currentMode);
        });

        // ── HERO SLIDESHOW ──
        let heroSlide = 0;
        const heroImgs = document.querySelectorAll('.hero-img');
        const heroDots = document.querySelectorAll('.hero-dot');
        let heroTimer = null;

        function goSlide(idx) {
            heroImgs.forEach(i => i.classList.remove('active'));
            heroDots.forEach(d => d.classList.remove('active'));
            heroSlide = (idx + heroImgs.length) % heroImgs.length;
            if (heroImgs[heroSlide]) heroImgs[heroSlide].classList.add('active');
            if (heroDots[heroSlide]) heroDots[heroSlide].classList.add('active');
            clearInterval(heroTimer);
            heroTimer = setInterval(() => goSlide(heroSlide + 1), 5000);
        }

        heroTimer = setInterval(() => goSlide(heroSlide + 1), 5000);
    </script>
</body>

</html>
