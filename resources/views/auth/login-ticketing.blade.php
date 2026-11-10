@php
    $logo = \App\Models\Setting::get('logo');
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Portal Tiket & Gatekeeper · Buyle.id</title>
    <meta name="description"
        content="Platform e-ticketing modern untuk konser, webinar, dan festival. Masuk sebagai penyelenggara event atau petugas gate scanner.">
    <meta name="theme-color" content="#020704">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="icon" type="image/png" href="/favicon.png">

    <!-- Fonts: Montserrat (Official Buyle.id Font) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;1,700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --buyle-green: #1eb349;
            --buyle-green-light: #a5cf37;
            --buyle-emerald: #10b981;
            --buyle-gradient: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            --buyle-gradient-hover: linear-gradient(135deg, #18983e 0%, #95bd30 100%);
            --bg-dark: #020704;
            --text-pure: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.75);
            --text-muted: rgba(255, 255, 255, 0.45);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif !important;
            -webkit-tap-highlight-color: transparent;
        }

        html,
        body {
            height: 100%;
            background-color: var(--bg-dark);
            color: var(--text-pure);
            overflow-x: hidden;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100dvh;
            background: #020704;
            position: relative;
        }

        /* ── AMBIENT BACKGROUND GLOW (DESKTOP CANVAS) ── */
        .ambient-bg-glow {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 1;
            background:
                radial-gradient(circle at 50% 25%, rgba(30, 179, 73, 0.22) 0%, rgba(5, 18, 9, 0) 55%),
                radial-gradient(circle at 85% 75%, rgba(165, 207, 55, 0.12) 0%, rgba(2, 7, 4, 0) 50%),
                radial-gradient(circle at 15% 85%, rgba(16, 185, 129, 0.12) 0%, rgba(2, 7, 4, 0) 50%),
                #020704;
        }

        /* ── SMARTPHONE APP CANVAS / MOCKUP CONTAINER ── */
        .phone-canvas {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            height: 100dvh;
            max-height: 860px;
            background: #020704;
            border-radius: 46px;
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            box-shadow:
                0 30px 90px rgba(0, 0, 0, 0.85),
                0 0 60px rgba(30, 179, 73, 0.18);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            box-sizing: border-box;
        }

        @media (max-width: 480px) {
            .phone-canvas {
                max-width: 100%;
                height: 100dvh;
                max-height: 100dvh;
                border-radius: 0;
                border: none;
                box-shadow: none;
            }
        }

        /* ── CONCERT HERO IMAGES (SLIDE BACKGROUND PICTURES) ── */
        .concert-hero-wrap {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 55%;
            overflow: hidden;
            z-index: 2;
        }

        .concert-hero-img {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center top;
            opacity: 0;
            transform: scale(1.05);
            transition: opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1), transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
            filter: contrast(1.05) saturate(1.1);
        }

        .concert-hero-img.active {
            opacity: 1;
            transform: scale(1);
        }

        .concert-hero-overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(2, 7, 4, 0.15) 0%, rgba(2, 7, 4, 0.45) 45%, #020704 100%),
                radial-gradient(circle at 50% 20%, rgba(30, 179, 73, 0.25) 0%, transparent 65%);
            pointer-events: none;
        }

        .canvas-content-wrap {
            position: relative;
            z-index: 5;
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
            padding: 1.5rem 1.65rem 1.5rem;
            box-sizing: border-box;
        }

        @media (max-width: 480px) {
            .canvas-content-wrap {
                padding: max(env(safe-area-inset-top, 0px), 1.25rem) 1.45rem max(env(safe-area-inset-bottom, 0px), 1.25rem);
            }
        }

        /* ── BRAND LOGO PILL (Exact Logo in Header Buyle.id) ── */
        .brand-header-row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: auto;
            user-select: none;
        }

        .brand-logo-pill {
            display: inline-flex;
            align-items: center;
            background: #ffffff;
            padding: 7px 15px;
            border-radius: 999px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.35);
            text-decoration: none;
            transition: transform 0.18s ease;
        }

        .brand-logo-pill:hover {
            transform: scale(1.03);
        }

        .brand-logo-img {
            height: 25px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        /* ── CAROUSEL SLIDES WRAPPER ── */
        .slider-viewport {
            position: relative;
            overflow: hidden;
            touch-action: pan-y;
            user-select: none;
            margin-top: auto;
        }

        .slides-container {
            display: flex;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            width: 300%;
        }

        .slide-item {
            width: 33.333333%;
            flex-shrink: 0;
            padding-right: 0.5rem;
            box-sizing: border-box;
        }

        .slide-headline {
            font-size: 1.85rem;
            font-weight: 700;
            line-height: 1.22;
            letter-spacing: -0.02em;
            color: #ffffff;
            margin-bottom: 0.65rem;
        }

        @media (max-width: 380px) {
            .slide-headline {
                font-size: 1.65rem;
            }
        }

        .hl-green {
            background: linear-gradient(135deg, #22c55e 0%, #a3e635 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline;
        }

        .slide-subtitle {
            font-size: 0.86rem;
            line-height: 1.55;
            color: var(--text-secondary);
            font-weight: 500;
            min-height: 48px;
        }

        /* ── SLIDE DOT INDICATORS (Pill shape) ── */
        .slide-indicators-row {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 1.15rem;
            margin-bottom: 1.85rem;
        }

        .indicator-dot {
            height: 4.5px;
            width: 7px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.22);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border: none;
            padding: 0;
        }

        .indicator-dot.active {
            width: 30px;
            background: var(--buyle-gradient);
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.6);
        }

        /* ── THREE ACTION BUTTONS ── */
        .actions-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
        }

        /* Button 1: Daftar Penyelenggara (Primary Green Curved Button) */
        .btn-register-organizer {
            width: 100%;
            height: 52px;
            border-radius: 999px;
            background: var(--buyle-gradient);
            color: #ffffff;
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
            box-shadow: 0 10px 25px rgba(30, 179, 73, 0.42), 0 2px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-register-organizer:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(30, 179, 73, 0.55);
            background: var(--buyle-gradient-hover);
        }

        .btn-register-organizer:active {
            transform: scale(0.98);
        }

        /* Button 2: Login Penyelenggara (Frosted Dark Glass Button) */
        .btn-login-organizer {
            width: 100%;
            height: 48px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-login-organizer:hover {
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        .btn-login-organizer:active {
            transform: scale(0.98);
        }

        /* Button 3: Login Scanner (Sleek Minimal Text Link) */
        .btn-login-scanner {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            cursor: pointer;
            padding: 0.4rem;
            text-decoration: none;
            transition: color 0.18s ease;
            margin-top: 0.15rem;
        }

        .btn-login-scanner strong {
            color: #4ade80;
            font-weight: 700;
        }

        .btn-login-scanner:hover {
            color: #ffffff;
        }

        /* ── SCANNER STAFF LOGIN MODAL / BOTTOM SHEET ── */
        .scanner-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 1000;
            display: none;
            align-items: flex-end;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .scanner-modal-backdrop.active {
            display: flex;
            opacity: 1;
        }

        @media (min-width: 640px) {
            .scanner-modal-backdrop {
                align-items: center;
                padding: 1rem;
            }
        }

        .scanner-sheet {
            width: 100%;
            max-width: 440px;
            background: #09130c;
            border: 1.5px solid rgba(34, 197, 94, 0.3);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.9), 0 0 40px rgba(30, 179, 73, 0.25);
            border-radius: 32px 32px 0 0;
            padding: 1.25rem 1.65rem 2rem;
            box-sizing: border-box;
            transform: translateY(100%);
            transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @media (min-width: 640px) {
            .scanner-sheet {
                border-radius: 32px;
                transform: scale(0.95) translateY(20px);
                transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            }
        }

        .scanner-modal-backdrop.active .scanner-sheet {
            transform: translateY(0);
        }

        @media (min-width: 640px) {
            .scanner-modal-backdrop.active .scanner-sheet {
                transform: scale(1) translateY(0);
            }
        }

        .sheet-drag-handle {
            width: 36px;
            height: 4px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.22);
            margin: 0 auto 1.15rem;
        }

        .sheet-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .sheet-title-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sheet-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(30, 179, 73, 0.18);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #4ade80;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sheet-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            line-height: 1.25;
        }

        .sheet-sub {
            font-size: 0.74rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .btn-sheet-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-sheet-close:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
        }

        /* Form Inputs */
        .sheet-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .field-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.85);
        }

        .field-input-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .field-input {
            width: 100%;
            height: 46px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.06);
            border: 1.5px solid rgba(255, 255, 255, 0.14);
            padding: 0 1rem 0 2.6rem;
            color: #ffffff;
            font-size: 0.88rem;
            font-weight: 600;
            outline: none;
            transition: all 0.18s ease;
            box-sizing: border-box;
        }

        .field-input:focus {
            border-color: #22c55e;
            background: rgba(30, 179, 73, 0.08);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
        }

        .field-icon-left {
            position: absolute;
            left: 0.85rem;
            color: #64748b;
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .btn-toggle-pin {
            position: absolute;
            right: 0.75rem;
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 4px;
        }

        .btn-toggle-pin:hover {
            color: #ffffff;
        }

        .btn-sheet-submit {
            width: 100%;
            height: 48px;
            border-radius: 999px;
            background: var(--buyle-gradient);
            color: #ffffff;
            font-size: 0.92rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 8px 20px rgba(30, 179, 73, 0.35);
            transition: all 0.2s ease;
            margin-top: 0.5rem;
        }

        .btn-sheet-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(30, 179, 73, 0.45);
        }

        .alert-error-sheet {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            font-size: 0.76rem;
            font-weight: 600;
            padding: 0.65rem 0.85rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
    </style>
</head>

<body>

    {{-- Atmospheric Background for Desktop View --}}
    <div class="ambient-bg-glow"></div>

    {{-- Main Smartphone Canvas --}}
    <main class="phone-canvas">

        {{-- 3 Concert Hero Images (Top Visual Anchor) --}}
        <div class="concert-hero-wrap">
            <div class="concert-hero-img slide-img-0 active" style="background-image: url('{{ asset('images/ticketing/concert1.jpg') }}');"></div>
            <div class="concert-hero-img slide-img-1" style="background-image: url('{{ asset('images/ticketing/concert2.jpg') }}');"></div>
            <div class="concert-hero-img slide-img-2" style="background-image: url('{{ asset('images/ticketing/concert3.jpg') }}');"></div>
            <div class="concert-hero-overlay"></div>
        </div>

        <div class="canvas-content-wrap">

            {{-- 1. Official Buyle.id Header Logo (Replacing text) --}}
            <div class="brand-header-row">
                <a href="{{ route('tix.info') }}" class="brand-logo-pill" title="Buyle.id">
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="Buyle.id" class="brand-logo-img">
                    @else
                        <img src="{{ asset('images/buyle-logo.png') }}" alt="Buyle.id" class="brand-logo-img">
                    @endif
                </a>
            </div>

            {{-- 2. Carousel: 3 Slide Informasi Copywriting Indonesia To-The-Point & Bervalue --}}
            <div class="slider-viewport" id="sliderViewport">
                <div class="slides-container" id="slidesContainer">

                    {{-- SLIDE 1: PENJUALAN TIKET --}}
                    <article class="slide-item">
                        <h1 class="slide-headline">
                            Kelola Tiket Event,<br>
                            <span class="hl-green">Semudah 1 Klik.</span>
                        </h1>
                        <p class="slide-subtitle">
                            Platform e-ticketing modern untuk konser, festival, dan seminar. Atur kuota, kategori tiket, dan publikasi instan tanpa ribet.
                        </p>
                    </article>

                    {{-- SLIDE 2: SCANNER & GATEKEEPER --}}
                    <article class="slide-item">
                        <h1 class="slide-headline">
                            Scan Kilat 0,1 Detik,<br>
                            <span class="hl-green">100% Bebas Antre.</span>
                        </h1>
                        <p class="slide-subtitle">
                            Validasi QR code & barcode super cepat di gate panitia. Sistem proteksi anti-duplikasi menjamin tiket palsu tidak tembus.
                        </p>
                    </article>

                    {{-- SLIDE 3: PENCAIRAN SALDO INSTAN --}}
                    <article class="slide-item">
                        <h1 class="slide-headline">
                            Pencairan Saldo Instan,<br>
                            <span class="hl-green">Aman & Terpercaya.</span>
                        </h1>
                        <p class="slide-subtitle">
                            Pantau arus kas tiket secara real-time dari HP Anda. Payout otomatis langsung ke rekening bank & e-wallet kapan saja.
                        </p>
                    </article>

                </div>

                {{-- Pill-shaped 3 Dots Indicator --}}
                <div class="slide-indicators-row" id="slideIndicators">
                    <button type="button" class="indicator-dot active" onclick="goToSlide(0)" aria-label="Slide 1"></button>
                    <button type="button" class="indicator-dot" onclick="goToSlide(1)" aria-label="Slide 2"></button>
                    <button type="button" class="indicator-dot" onclick="goToSlide(2)" aria-label="Slide 3"></button>
                </div>
            </div>

            {{-- 3. Three Action Buttons --}}
            <div class="actions-group">

                {{-- BUTTON 1: DAFTAR PENYELENGGARA (Direct Google OAuth -> Dashboard Ticketing) --}}
                <a href="{{ route('auth.google', ['role' => 'seller', 'redirect' => route('creator.ticket.scanner')]) }}"
                   class="btn-register-organizer" id="btnDaftarPenyelenggara" title="Daftar sebagai Penyelenggara Event">
                    {{-- Google Icon --}}
                    <svg width="19" height="19" viewBox="0 0 24 24">
                        <path fill="#ffffff" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#ffffff" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#ffffff" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                        <path fill="#ffffff" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                    </svg>
                    <span>Daftar Penyelenggara</span>
                </a>

                {{-- BUTTON 2: LOGIN PENYELENGGARA (Direct Google OAuth -> Dashboard Ticketing) --}}
                <a href="{{ route('auth.google', ['role' => 'seller', 'redirect' => route('creator.ticket.scanner')]) }}"
                   class="btn-login-organizer" id="btnLoginPenyelenggara" title="Login Penyelenggara Event">
                    <span>Login Penyelenggara</span>
                </a>

                {{-- BUTTON 3: LOGIN SCANNER (Opens Staff Login Form Modal) --}}
                <button type="button" class="btn-login-scanner" onclick="openScannerModal()" id="btnOpenScannerModal">
                    Petugas Gate? <strong>Login Scanner &rarr;</strong>
                </button>

            </div>

        </div>
    </main>

    {{-- ══ SCANNER STAFF LOGIN BOTTOM SHEET / MODAL ══ --}}
    <div class="scanner-modal-backdrop {{ ($errors->has('staff_name') || $errors->has('staff_password')) ? 'active' : '' }}"
         id="scannerModalBackdrop" onclick="handleBackdropClick(event)">
        <div class="scanner-sheet" id="scannerSheet">
            <div class="sheet-drag-handle"></div>

            <div class="sheet-header">
                <div class="sheet-title-group">
                    <div class="sheet-icon-box">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                            <path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2" />
                            <rect x="7" y="7" width="10" height="10" rx="1" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="sheet-title">Login Petugas Scanner</h3>
                        <p class="sheet-sub">Validasi tiket gate konser & event</p>
                    </div>
                </div>
                <button type="button" class="btn-sheet-close" onclick="closeScannerModal()" aria-label="Tutup">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            {{-- Error alerts --}}
            @if($errors->has('staff_name') || $errors->has('staff_password'))
                <div class="alert-error-sheet">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>{{ $errors->first('staff_name') ?: $errors->first('staff_password') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('ticketing.login.staff') }}" class="sheet-form">
                @csrf
                <div class="form-field">
                    <label class="field-label" for="staffNameInput">Nama Petugas / Panitia</label>
                    <div class="field-input-box">
                        <span class="field-icon-left">
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input type="text" id="staffNameInput" name="name" class="field-input"
                               placeholder="Contoh: Gate 1 - Budi" value="{{ old('name') }}" required autocomplete="off">
                    </div>
                </div>

                <div class="form-field">
                    <label class="field-label" for="staffPinInput">Password / PIN Gate</label>
                    <div class="field-input-box">
                        <span class="field-icon-left">
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input type="password" id="staffPinInput" name="password" class="field-input"
                               placeholder="Masukkan PIN gate panitia" required autocomplete="off">
                        <button type="button" class="btn-toggle-pin" onclick="togglePinVisibility()" title="Lihat PIN">
                            <svg id="eyeIcon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-sheet-submit">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2" />
                        <rect x="7" y="7" width="10" height="10" rx="1" />
                    </svg>
                    <span>Masuk Layar Scanner</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Interactive Carousel & Modal Logic --}}
    <script>
        // 3-Slide Carousel Logic with Syncing Concert Hero Images
        let currentSlide = 0;
        const totalSlides = 3;
        const slidesContainer = document.getElementById('slidesContainer');
        const dots = document.querySelectorAll('.indicator-dot');
        const heroImages = document.querySelectorAll('.concert-hero-img');
        let autoSlideTimer = null;

        function goToSlide(index) {
            currentSlide = (index + totalSlides) % totalSlides;
            if (slidesContainer) {
                slidesContainer.style.transform = `translateX(-${currentSlide * 33.333333}%)`;
            }
            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === currentSlide);
            });
            heroImages.forEach((img, idx) => {
                img.classList.toggle('active', idx === currentSlide);
            });
            resetAutoSlide();
        }

        function resetAutoSlide() {
            clearInterval(autoSlideTimer);
            autoSlideTimer = setInterval(() => {
                goToSlide(currentSlide + 1);
            }, 5000);
        }
        resetAutoSlide();

        // Touch Swipe Support for Mobile
        const viewport = document.getElementById('sliderViewport');
        let touchStartX = 0;
        let touchEndX = 0;

        if (viewport) {
            viewport.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            viewport.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipeGesture();
            }, { passive: true });
        }

        function handleSwipeGesture() {
            const threshold = 40;
            if (touchEndX < touchStartX - threshold) {
                goToSlide(currentSlide + 1);
            } else if (touchEndX > touchStartX + threshold) {
                goToSlide(currentSlide - 1);
            }
        }

        // Scanner Modal Open / Close
        function openScannerModal() {
            const backdrop = document.getElementById('scannerModalBackdrop');
            if (backdrop) {
                backdrop.classList.add('active');
                setTimeout(() => {
                    const input = document.getElementById('staffNameInput');
                    if (input) input.focus();
                }, 300);
            }
        }

        function closeScannerModal() {
            const backdrop = document.getElementById('scannerModalBackdrop');
            if (backdrop) {
                backdrop.classList.remove('active');
            }
        }

        function handleBackdropClick(e) {
            if (e.target.id === 'scannerModalBackdrop') {
                closeScannerModal();
            }
        }

        // Toggle PIN Visibility
        function togglePinVisibility() {
            const pinInput = document.getElementById('staffPinInput');
            const eyeIcon = document.getElementById('eyeIcon');
            if (!pinInput) return;

            if (pinInput.type === 'password') {
                pinInput.type = 'text';
                eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                pinInput.type = 'password';
                eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }

        // Close on Escape Key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeScannerModal();
            }
        });
    </script>
</body>

</html>
