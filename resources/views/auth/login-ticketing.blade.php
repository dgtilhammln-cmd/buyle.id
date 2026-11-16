@php
    $waUrl = 'https://wa.me/6285179982373?text=' . urlencode('Halo Buyle TIX, saya ingin konsultasi dan kerjasama ticketing event.');
    $activeRole = request('role', 'event_creator'); // 'event_creator' | 'buyle_creator' | 'buyer'
    $activeMode = request('mode', 'register');      // 'register' | 'login'

    // Redirect destinations per role
    $redirectMap = [
        'event_creator' => url('/creator/event-attendance'),
        'buyle_creator' => url('/creator/dashboard'),
        'buyer'         => url('/akun/'),
    ];
    // Google OAuth role param mapping
    $googleRoleMap = [
        'event_creator' => 'seller',
        'buyle_creator' => 'seller',
        'buyer'         => 'buyer',
    ];

    $googleUrl = function($role, $mode) use ($redirectMap, $googleRoleMap) {
        return route('auth.google', [
            'role'     => $googleRoleMap[$role] ?? 'seller',
            'redirect' => $redirectMap[$role] ?? url('/'),
            'mode'     => $mode,
        ]);
    };
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

    <!-- Fonts: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --buyle-green:        #1eb349;
            --buyle-green-light:  #a5cf37;
            --buyle-emerald:      #10b981;
            --buyle-gradient:     linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            --buyle-gradient-h:   linear-gradient(135deg, #18983e 0%, #95bd30 100%);
            --bg-dark:            #020704;
            --text-pure:          #ffffff;
            --text-sec:           rgba(255,255,255,0.72);
            --text-muted:         rgba(255,255,255,0.42);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif !important;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            background: var(--bg-dark);
            color: var(--text-pure);
            min-height: 100dvh;
            overflow-x: hidden;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 0;
            position: relative;
        }

        /* ── AMBIENT BACKGROUND GLOW ── */
        .ambient-bg {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background:
                radial-gradient(circle at 50% 20%, rgba(30,179,73,0.22) 0%, rgba(5,18,9,0) 55%),
                radial-gradient(circle at 85% 70%, rgba(165,207,55,0.12) 0%, rgba(2,7,4,0) 50%),
                radial-gradient(circle at 15% 80%, rgba(16,185,129,0.10) 0%, rgba(2,7,4,0) 50%),
                #020704;
        }

        /* ── MAIN CANVAS (phone mockup on desktop, full on mobile) ── */
        .auth-canvas {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            min-height: calc(100dvh - 80px); /* leaves room for bottom nav */
            background: #020704;
            border-radius: 40px;
            border: 1.5px solid rgba(255,255,255,0.09);
            box-shadow:
                0 32px 90px rgba(0,0,0,0.85),
                0 0 65px rgba(30,179,73,0.16);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            margin: 2.5rem auto 6rem;
        }

        @media (max-width: 520px) {
            .auth-canvas {
                max-width: 100%;
                min-height: auto;
                border-radius: 0;
                border: none;
                box-shadow: none;
                margin: 0 0 5rem 0;
            }
        }

        /* ── CONCERT HERO (top visual) ── */
        .hero-wrap {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            flex-shrink: 0;
        }

        @media (max-width: 520px) {
            .hero-wrap { height: 200px; }
        }

        .hero-img {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center top;
            opacity: 0;
            transform: scale(1.05);
            transition: opacity 0.65s cubic-bezier(0.16,1,0.3,1), transform 1.2s cubic-bezier(0.16,1,0.3,1);
        }
        .hero-img.active { opacity: 1; transform: scale(1); }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(2,7,4,0.1) 0%, rgba(2,7,4,0.5) 50%, #020704 100%),
                radial-gradient(circle at 50% 20%, rgba(30,179,73,0.22) 0%, transparent 65%);
            pointer-events: none;
        }

        /* ── BRAND LOGO (over hero) ── */
        .brand-row {
            position: absolute;
            top: 1.2rem;
            left: 1.5rem;
            z-index: 5;
        }
        .brand-pill {
            display: inline-flex;
            align-items: center;
            background: rgba(255,255,255,0.95);
            padding: 6px 14px;
            border-radius: 999px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.4);
            text-decoration: none;
        }
        .brand-pill img {
            height: 24px;
            width: auto;
            display: block;
        }

        /* ── SLIDE INDICATORS ── */
        .hero-dots {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 5px;
            z-index: 5;
        }
        .hero-dot {
            height: 4px;
            width: 7px;
            border-radius: 999px;
            background: rgba(255,255,255,0.3);
            border: none;
            padding: 0;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
        }
        .hero-dot.active {
            width: 28px;
            background: var(--buyle-gradient);
            box-shadow: 0 0 10px rgba(30,179,73,0.6);
        }

        /* ── CONTENT BODY ── */
        .canvas-body {
            padding: 1.5rem 1.65rem 2rem;
            display: flex;
            flex-direction: column;
            gap: 1.4rem;
            flex: 1;
        }

        @media (max-width: 520px) {
            .canvas-body { padding: 1.25rem 1.2rem 2rem; gap: 1.2rem; }
        }

        /* ── TITLE ── */
        .auth-title {
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1.25;
            color: #fff;
            letter-spacing: -0.02em;
        }
        .auth-title .hl {
            background: linear-gradient(135deg, #22c55e 0%, #a3e635 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .auth-subtitle {
            font-size: 0.82rem;
            color: var(--text-sec);
            margin-top: 0.3rem;
            font-weight: 500;
            line-height: 1.5;
        }

        /* ── ROLE SWITCHER (3 tabs) ── */
        .role-switcher {
            display: flex;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 4px;
            gap: 3px;
        }
        .role-tab {
            flex: 1;
            padding: 0.55rem 0.4rem;
            border-radius: 10px;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-muted);
            background: transparent;
            border: none;
            cursor: pointer;
            text-align: center;
            transition: all 0.22s cubic-bezier(0.16,1,0.3,1);
            line-height: 1.3;
            white-space: nowrap;
        }
        .role-tab.active {
            background: var(--buyle-gradient);
            color: #fff;
            box-shadow: 0 4px 14px rgba(30,179,73,0.4);
        }
        .role-tab:hover:not(.active) {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        /* ── MODE SWITCHER (2 tabs: Daftar / Login) ── */
        .mode-switcher {
            display: flex;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 999px;
            padding: 3px;
            gap: 3px;
        }
        .mode-tab {
            flex: 1;
            padding: 0.5rem 0.75rem;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-muted);
            background: transparent;
            border: none;
            cursor: pointer;
            text-align: center;
            transition: all 0.22s cubic-bezier(0.16,1,0.3,1);
        }
        .mode-tab.active {
            background: rgba(255,255,255,0.14);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.18);
        }
        .mode-tab:hover:not(.active) {
            color: rgba(255,255,255,0.75);
        }

        /* ── INFO CARD (role description) ── */
        .role-info-card {
            background: rgba(30,179,73,0.08);
            border: 1px solid rgba(30,179,73,0.22);
            border-radius: 14px;
            padding: 0.9rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
        }
        .role-info-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(30,179,73,0.18);
            border: 1px solid rgba(34,197,94,0.3);
            color: #4ade80;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .role-info-icon svg { width: 18px; height: 18px; }
        .role-info-text h4 {
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.2rem;
        }
        .role-info-text p {
            font-size: 0.75rem;
            color: var(--text-sec);
            line-height: 1.5;
        }

        /* ── GOOGLE CTA BUTTON ── */
        .btn-google-cta {
            width: 100%;
            height: 54px;
            border-radius: 999px;
            background: var(--buyle-gradient);
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 28px rgba(30,179,73,0.42), 0 2px 6px rgba(0,0,0,0.2);
            transition: all 0.22s cubic-bezier(0.16,1,0.3,1);
        }
        .btn-google-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 36px rgba(30,179,73,0.56);
            background: var(--buyle-gradient-h);
        }
        .btn-google-cta:active { transform: scale(0.97); }
        .btn-google-cta svg { flex-shrink: 0; }

        /* ── DIVIDER ── */
        .divider-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 600;
        }
        .divider-row::before,
        .divider-row::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }

        /* ── TOS NOTE ── */
        .tos-note {
            text-align: center;
            font-size: 0.72rem;
            color: var(--text-muted);
            line-height: 1.6;
        }
        .tos-note a {
            color: #4ade80;
            text-decoration: none;
            font-weight: 600;
        }
        .tos-note a:hover { text-decoration: underline; }

        /* ── DESTINATION BADGE ── */
        .dest-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 999px;
            padding: 0.35rem 0.85rem;
            font-size: 0.72rem;
            color: var(--text-sec);
            font-weight: 600;
            margin: 0 auto;
        }
        .dest-badge span { color: #4ade80; }
        .dest-row {
            display: flex;
            justify-content: center;
        }

        /* ══════════════════════════════════════════
           MOBILE BOTTOM NAVIGATION BAR
           ══════════════════════════════════════════ */
        .tix-auth-bottom-nav {
            display: none;
        }
        .tix-auth-bottom-nav-spacer {
            display: none;
        }

        @media (max-width: 768px) {
            .tix-auth-bottom-nav {
                display: flex;
                position: fixed;
                bottom: 1rem;
                left: 50%;
                transform: translateX(-50%);
                z-index: 9999;
                background: rgba(10, 20, 12, 0.92);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255,255,255,0.12);
                border-radius: 999px;
                padding: 0.4rem 0.75rem;
                gap: 0;
                box-shadow:
                    0 8px 32px rgba(0,0,0,0.6),
                    0 0 20px rgba(30,179,73,0.15);
                min-width: 280px;
                max-width: calc(100vw - 2rem);
            }

            .tix-auth-bottom-nav-spacer {
                display: block;
                height: 90px;
            }

            .tix-auth-bnav-item {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 3px;
                padding: 0.5rem 0.4rem;
                border-radius: 999px;
                text-decoration: none;
                color: rgba(255,255,255,0.45);
                transition: all 0.2s ease;
                min-width: 56px;
            }

            .tix-auth-bnav-item svg {
                width: 20px;
                height: 20px;
                stroke-width: 2;
                transition: all 0.2s ease;
            }

            .tix-auth-bnav-item span {
                font-size: 0.6rem;
                font-weight: 700;
                letter-spacing: 0.01em;
                line-height: 1;
            }

            .tix-auth-bnav-item.active {
                color: #1eb349;
            }

            .tix-auth-bnav-item.active svg {
                filter: drop-shadow(0 0 6px rgba(30,179,73,0.7));
            }

            .tix-auth-bnav-item:hover:not(.active) {
                color: rgba(255,255,255,0.75);
            }
        }

        /* ── Error alert ── */
        .alert-err {
            background: rgba(239,68,68,0.15);
            border: 1px solid rgba(239,68,68,0.3);
            color: #fca5a5;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.65rem 0.9rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="ambient-bg"></div>

    <!-- ══════ MAIN AUTH CANVAS ══════ -->
    <main class="auth-canvas" id="authCanvas">

        <!-- HERO IMAGES SLIDESHOW -->
        <div class="hero-wrap" id="heroWrap">
            <div class="hero-img active" style="background-image: url('{{ asset('images/ticketing/concert1.jpg') }}');"></div>
            <div class="hero-img" style="background-image: url('{{ asset('images/ticketing/concert2.jpg') }}');"></div>
            <div class="hero-img" style="background-image: url('{{ asset('images/ticketing/concert3.jpg') }}');"></div>
            <div class="hero-overlay"></div>

            <!-- Brand Logo -->
            <div class="brand-row">
                <a href="{{ route('tix.index') }}" class="brand-pill" title="Buyle TIX">
                    <picture>
                        <source srcset="{{ asset('images/logobuyletix.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logobuyletix.png') }}" alt="Buyle TIX" onerror="this.src='{{ asset('images/buyle-logo.png') }}'">
                    </picture>
                </a>
            </div>

            <!-- Slide Dots -->
            <div class="hero-dots">
                <button class="hero-dot active" onclick="goSlide(0)" aria-label="Slide 1"></button>
                <button class="hero-dot" onclick="goSlide(1)" aria-label="Slide 2"></button>
                <button class="hero-dot" onclick="goSlide(2)" aria-label="Slide 3"></button>
            </div>
        </div>

        <!-- BODY CONTENT -->
        <div class="canvas-body">

            @if(session('error'))
            <div class="alert-err">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <!-- Title (dynamic per role) -->
            <div id="authTitleWrap">
                <h1 class="auth-title" id="authTitle">
                    Kelola Tiket Event,<br><span class="hl">Semudah 1 Klik.</span>
                </h1>
                <p class="auth-subtitle" id="authSubtitle">
                    Platform e-ticketing modern untuk konser, festival, dan seminar.
                </p>
            </div>

            <!-- ROLE SWITCHER -->
            <div class="role-switcher" role="tablist" aria-label="Pilih peran">
                <button class="role-tab active" id="tab-event_creator" role="tab" onclick="switchRole('event_creator')">
                    Event<br>Creator
                </button>
                <button class="role-tab" id="tab-buyle_creator" role="tab" onclick="switchRole('buyle_creator')">
                    Buyle<br>Creator
                </button>
                <button class="role-tab" id="tab-buyer" role="tab" onclick="switchRole('buyer')">
                    Pembeli
                </button>
            </div>

            <!-- MODE SWITCHER -->
            <div class="mode-switcher" role="tablist" aria-label="Daftar atau Login">
                <button class="mode-tab active" id="mtab-register" role="tab" onclick="switchMode('register')">
                    Daftar
                </button>
                <button class="mode-tab" id="mtab-login" role="tab" onclick="switchMode('login')">
                    Masuk
                </button>
            </div>

            <!-- ROLE INFO CARD -->
            <div class="role-info-card" id="roleInfoCard">
                <div class="role-info-icon" id="roleInfoIcon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                        <path d="M9 12h6M9 16h4"/>
                    </svg>
                </div>
                <div class="role-info-text">
                    <h4 id="roleInfoTitle">Event Creator</h4>
                    <p id="roleInfoDesc">Kelola event, tiket, & laporan penjualan. Akses dashboard penyelenggara secara instan.</p>
                </div>
            </div>

            <!-- GOOGLE CTA BUTTON -->
            <a href="#" class="btn-google-cta" id="btnGoogleCta">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#fff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#fff" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#fff" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.85z"/>
                    <path fill="#fff" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span id="btnGoogleText">Daftar Event Creator via Google</span>
            </a>

            <!-- Destination indicator -->
            <div class="dest-row">
                <div class="dest-badge" id="destBadge">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                    Diarahkan ke <span id="destLabel">Dashboard Event Creator</span>
                </div>
            </div>

            <div class="divider-row">atau</div>

            <!-- TOS -->
            <p class="tos-note">
                Dengan melanjutkan, Anda menyetujui
                <a href="{{ route('tix.info') }}#terms">Syarat & Ketentuan</a>
                serta <a href="{{ route('tix.info') }}#privacy">Kebijakan Privasi</a> Buyle TIX.
            </p>

        </div>{{-- /canvas-body --}}
    </main>

    <div class="tix-auth-bottom-nav-spacer"></div>

    <!-- ══════ MOBILE BOTTOM NAVBAR ══════ -->
    <nav class="tix-auth-bottom-nav" aria-label="Navigasi bawah">
        {{-- Home --}}
        <a href="{{ url('/tix') }}"
           class="tix-auth-bnav-item {{ request()->is('tix') ? 'active' : '' }}"
           title="Beranda TIX">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span>Home</span>
        </a>

        {{-- About Buyle --}}
        <a href="{{ url('/tix/info') }}"
           class="tix-auth-bnav-item {{ request()->is('tix/info*') ? 'active' : '' }}"
           title="Tentang Buyle TIX">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
            <span>About</span>
        </a>

        {{-- Kerjasama --}}
        <a href="{{ $waUrl }}" target="_blank" rel="noopener"
           class="tix-auth-bnav-item"
           title="Kerjasama dengan Buyle TIX">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span>Kerjasama</span>
        </a>

        {{-- Profile --}}
        <a href="{{ url('/tix/auth') }}"
           class="tix-auth-bnav-item {{ (request()->is('tix/auth*') || request()->is('login-ticketing*')) ? 'active' : '' }}"
           title="Masuk / Daftar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
            <span>Profile</span>
        </a>
    </nav>

    <script>
        // ── DYNAMIC DATA ──
        const ROLES = {
            event_creator: {
                title:    'Kelola Tiket Event,<br><span class="hl">Semudah 1 Klik.</span>',
                subtitle: 'Platform e-ticketing modern untuk konser, festival, dan seminar.',
                infoTitle: 'Event Creator',
                infoDesc:  'Kelola event, tiket, & laporan penjualan. Akses dashboard penyelenggara secara instan.',
                iconSvg:   `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                <rect x="9" y="3" width="6" height="4" rx="1"/>
                                <path d="M9 12h6M9 16h4"/>
                            </svg>`,
                register: {
                    btnText:  'Daftar Event Creator via Google',
                    destText: 'Dashboard Event Creator',
                    url:      '{{ route("auth.google", ["role"=>"seller","redirect"=>url("/creator/event-attendance"),"mode"=>"register"]) }}',
                },
                login: {
                    btnText:  'Masuk sebagai Event Creator via Google',
                    destText: 'Dashboard Event Creator',
                    url:      '{{ route("auth.google", ["role"=>"seller","redirect"=>url("/creator/event-attendance"),"mode"=>"login"]) }}',
                },
            },
            buyle_creator: {
                title:    'Jadi Buyle Creator,<br><span class="hl">Raih Penghasilan.</span>',
                subtitle: 'Kelola konten, link bio, dan monetisasi audiens Anda bersama Buyle.',
                infoTitle: 'Buyle Creator',
                infoDesc:  'Buat biolink profesional, integrasikan produk & event, dan lacak performa konten Anda.',
                iconSvg:   `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M8 12h8M12 8v8"/>
                            </svg>`,
                register: {
                    btnText:  'Daftar Buyle Creator via Google',
                    destText: 'Dashboard Buyle Creator',
                    url:      '{{ route("auth.google", ["role"=>"seller","redirect"=>url("/creator/dashboard"),"mode"=>"register"]) }}',
                },
                login: {
                    btnText:  'Masuk sebagai Buyle Creator via Google',
                    destText: 'Dashboard Buyle Creator',
                    url:      '{{ route("auth.google", ["role"=>"seller","redirect"=>url("/creator/dashboard"),"mode"=>"login"]) }}',
                },
            },
            buyer: {
                title:    'Beli Tiket Event,<br><span class="hl">Tanpa Antri.</span>',
                subtitle: 'Temukan dan beli tiket konser, festival, seminar favoritmu dalam detik.',
                infoTitle: 'Pembeli Tiket',
                infoDesc:  'Pesan tiket, simpan e-ticket di akunmu, dan tunjukkan QR saat gate terbuka.',
                iconSvg:   `<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 12v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h6"/>
                                <path d="M12 12l9-9M15 3h6v6"/>
                            </svg>`,
                register: {
                    btnText:  'Daftar sebagai Pembeli via Google',
                    destText: 'Akun Saya',
                    url:      '{{ route("auth.google", ["role"=>"buyer","redirect"=>url("/akun/"),"mode"=>"register"]) }}',
                },
                login: {
                    btnText:  'Masuk sebagai Pembeli via Google',
                    destText: 'Akun Saya',
                    url:      '{{ route("auth.google", ["role"=>"buyer","redirect"=>url("/akun/"),"mode"=>"login"]) }}',
                },
            },
        };

        let currentRole = '{{ $activeRole }}';
        let currentMode = '{{ $activeMode }}';

        function switchRole(role) {
            currentRole = role;
            // Update role tabs
            document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-' + role).classList.add('active');
            renderContent();
        }

        function switchMode(mode) {
            currentMode = mode;
            // Update mode tabs
            document.querySelectorAll('.mode-tab').forEach(t => t.classList.remove('active'));
            document.getElementById('mtab-' + mode).classList.add('active');
            renderContent();
        }

        function renderContent() {
            const r = ROLES[currentRole];
            const m = r[currentMode];

            // Title
            document.getElementById('authTitle').innerHTML = r.title;
            document.getElementById('authSubtitle').textContent = r.subtitle;

            // Info card
            document.getElementById('roleInfoIcon').innerHTML = r.iconSvg;
            document.getElementById('roleInfoTitle').textContent = r.infoTitle;
            document.getElementById('roleInfoDesc').textContent  = r.infoDesc;

            // CTA button
            document.getElementById('btnGoogleCta').href = m.url;
            document.getElementById('btnGoogleText').textContent = m.btnText;

            // Destination badge
            document.getElementById('destLabel').textContent = m.destText;
        }

        // Initialize on load
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
            heroImgs[heroSlide].classList.add('active');
            heroDots[heroSlide].classList.add('active');
            clearInterval(heroTimer);
            heroTimer = setInterval(() => goSlide(heroSlide + 1), 5000);
        }

        heroTimer = setInterval(() => goSlide(heroSlide + 1), 5000);

        // Touch swipe on hero
        const heroWrap = document.getElementById('heroWrap');
        let hTouchX = 0;
        if (heroWrap) {
            heroWrap.addEventListener('touchstart', e => { hTouchX = e.changedTouches[0].screenX; }, { passive: true });
            heroWrap.addEventListener('touchend', e => {
                const diff = e.changedTouches[0].screenX - hTouchX;
                if (Math.abs(diff) > 40) goSlide(heroSlide + (diff < 0 ? 1 : -1));
            }, { passive: true });
        }
    </script>
</body>

</html>
