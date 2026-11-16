@php
    $logo = \App\Models\Setting::get('logo');
    $currentRole = request('role', $initialRole ?? 'event_creator'); // 'event_creator', 'buyle_creator', 'buyer'
    $currentMode = request('mode', $initialMode ?? 'register'); // 'register' (daftar) atau 'login'
    $waNumber = '085179982373';
    $waUrl = 'https://wa.me/6285179982373?text=' . urlencode('Halo Buyle TIX, saya ingin konsultasi dan kerjasama ticketing event.');
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Masuk &amp; Daftar · Buyle TIX</title>
    <meta name="description" content="Portal Autentikasi Buyle TIX untuk Event Creator, Buyle Creator, dan Pembeli. Masuk instan via Google.">
    <meta name="theme-color" content="#1db349">
    <link rel="icon" type="image/png" href="/favicon.png">

    <!-- Fonts: Montserrat (Official Buyle Font) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-green: #1db349;
            --brand-green-light: #84ce00;
            --brand-gradient: linear-gradient(135deg, #1db349 0%, #84ce00 100%);
            --brand-gradient-hover: linear-gradient(135deg, #169b3e 0%, #75b700 100%);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg-page: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif !important;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            min-height: 100%;
            background-color: var(--bg-page);
            color: var(--text-main);
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
            background: radial-gradient(circle at 50% 0%, rgba(29, 179, 73, 0.08) 0%, rgba(248, 250, 252, 0) 50%), #f8fafc;
            padding-bottom: 90px;
        }

        /* ── TOP HEADER / NAVBAR (DESKTOP & MOBILE) ── */
        .tix-auth-topbar {
            width: 100%;
            height: 72px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            box-sizing: border-box;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        .tix-auth-topbar-inner {
            max-width: 1120px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .tix-auth-logo-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            transition: opacity 0.2s ease;
        }

        .tix-auth-logo-link:hover {
            opacity: 0.9;
        }

        .tix-auth-logo-img {
            height: 32px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .tix-auth-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            padding: 7px 14px;
            border-radius: 999px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .tix-auth-back-link:hover {
            border-color: var(--brand-green);
            color: var(--brand-green);
            background: #f0fdf4;
        }

        /* ── AUTH CONTAINER / CARD ── */
        .tix-auth-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem 3rem;
            box-sizing: border-box;
        }

        .tix-auth-card {
            width: 100%;
            max-width: 500px;
            background: #ffffff;
            border-radius: 28px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.06), 0 2px 10px rgba(0, 0, 0, 0.02);
            padding: 2.25rem 2rem;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        /* ── BRAND LOGO INSIDE CARD ── */
        .tix-card-logo-head {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .tix-card-logo-img {
            height: 38px;
            width: auto;
            max-width: 180px;
            object-fit: contain;
            margin-bottom: 0.5rem;
        }

        .tix-card-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.72rem;
            font-weight: 700;
            color: #15803d;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 3px 10px;
            border-radius: 999px;
            letter-spacing: 0.02em;
        }

        /* ── DUAL SWITCH 1: 3-WAY ROLE SELECTOR (Event Creator | Buyle Creator | Pembeli) ── */
        .role-switch-container {
            margin-bottom: 1.25rem;
        }

        .role-switch-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .role-switch-pills {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            background: #f1f5f9;
            padding: 5px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .role-pill-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            padding: 8px 4px;
            border-radius: 12px;
            border: none;
            background: transparent;
            color: #64748b;
            font-size: 0.76rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-align: center;
            line-height: 1.2;
        }

        .role-pill-btn svg {
            width: 18px;
            height: 18px;
            transition: transform 0.2s ease;
        }

        .role-pill-btn.active {
            background: #ffffff;
            color: #15803d;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.07);
        }

        .role-pill-btn.active svg {
            color: #16a34a;
            transform: scale(1.08);
        }

        /* ── DUAL SWITCH 2: 2-WAY MODE SELECTOR (Daftar vs Login) ── */
        .mode-switch-pills {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
            background: #f8fafc;
            padding: 4px;
            border-radius: 999px;
            border: 1.5px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }

        .mode-pill-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 999px;
            border: none;
            background: transparent;
            color: #64748b;
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .mode-pill-btn.active {
            background: var(--brand-gradient);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(29, 179, 73, 0.28);
        }

        /* ── DYNAMIC HEADLINE & DESTINATION NOTICE ── */
        .auth-heading-box {
            text-align: center;
            margin-bottom: 1.35rem;
        }

        .auth-main-title {
            font-size: 1.22rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .auth-sub-title {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.45;
        }

        .dest-banner {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 8px 12px;
            border-radius: 12px;
            font-size: 0.74rem;
            font-weight: 600;
            color: #166534;
            margin-bottom: 1.35rem;
            line-height: 1.35;
        }

        .dest-banner svg {
            flex-shrink: 0;
            color: #16a34a;
        }

        /* ── HERO GOOGLE BUTTON ("Semua via Google") ── */
        .google-auth-btn {
            width: 100%;
            height: 52px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 0.92rem;
            font-weight: 700;
            color: #0f172a;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            cursor: pointer;
        }

        .google-auth-btn:hover {
            border-color: #16a34a;
            box-shadow: 0 8px 24px rgba(22, 163, 74, 0.16);
            transform: translateY(-2px);
            background: #ffffff;
        }

        .google-auth-btn:active {
            transform: translateY(0);
        }

        .google-icon-svg {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
        }

        /* ── DIVIDER ── */
        .auth-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.35rem 0;
            color: #94a3b8;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .auth-divider span {
            padding: 0 10px;
        }

        /* ── EMAIL FORM (ACCORDION / CLEAN TOGGLE) ── */
        .email-form-toggle-btn {
            width: 100%;
            background: none;
            border: none;
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 6px;
            transition: color 0.15s ease;
        }

        .email-form-toggle-btn:hover {
            color: #15803d;
        }

        .email-form-box {
            display: none;
            margin-top: 1rem;
            animation: fadeIn 0.25s ease;
        }

        .email-form-box.show {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 0.95rem;
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 4px;
        }

        .form-input {
            width: 100%;
            height: 44px;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            padding: 0 14px;
            font-size: 0.85rem;
            color: #0f172a;
            outline: none;
            transition: all 0.2s ease;
            box-sizing: border-box;
            background: #ffffff;
        }

        .form-input:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15);
        }

        .btn-submit-auth {
            width: 100%;
            height: 46px;
            border-radius: 12px;
            border: none;
            background: var(--brand-gradient);
            color: #ffffff;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(29, 179, 73, 0.28);
            transition: all 0.2s ease;
            margin-top: 0.5rem;
        }

        .btn-submit-auth:hover {
            background: var(--brand-gradient-hover);
            transform: translateY(-1px);
        }

        /* ── FOOTER TRUST INFOS ── */
        .auth-trust-footer {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
            text-align: center;
            font-size: 0.72rem;
            color: #94a3b8;
            line-height: 1.45;
        }

        .auth-trust-footer a {
            color: #15803d;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-trust-footer a:hover {
            text-decoration: underline;
        }

        /* ── ALERTS / FLASH MESSAGES ── */
        .auth-alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            font-size: 0.78rem;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        /* ══════════════════════════════════════════════════════════════
           MOBILE BOTTOM NAVIGATION BAR (Identical to buyle.id/tix)
           ══════════════════════════════════════════════════════════════ */
        .tix-bottom-nav {
            display: none;
        }

        @media (max-width: 991px) {
            .tix-auth-card {
                border-radius: 24px;
                padding: 1.75rem 1.25rem;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            }

            .tix-auth-wrapper {
                padding: 1.5rem 1rem 2rem;
            }

            .tix-bottom-nav {
                display: flex !important;
                position: fixed !important;
                bottom: 14px !important;
                left: 14px !important;
                right: 14px !important;
                max-width: 440px !important;
                margin: 0 auto !important;
                height: 62px !important;
                background: rgba(255, 255, 255, 0.96) !important;
                backdrop-filter: blur(16px) !important;
                -webkit-backdrop-filter: blur(16px) !important;
                border-radius: 999px !important;
                box-shadow: 0 10px 32px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.04) !important;
                border: 1.5px solid rgba(226, 232, 240, 0.9) !important;
                z-index: 99998 !important;
                align-items: center !important;
                justify-content: space-around !important;
                padding: 0 0.5rem !important;
                box-sizing: border-box !important;
                font-family: 'Montserrat', sans-serif !important;
                pointer-events: auto !important;
            }

            .tix-bottom-nav-item {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 3px !important;
                padding: 5px 8px !important;
                border-radius: 14px !important;
                text-decoration: none !important;
                color: #64748b !important;
                font-size: 0.67rem !important;
                font-weight: 600 !important;
                line-height: 1.1 !important;
                letter-spacing: -0.01em !important;
                font-family: 'Montserrat', sans-serif !important;
                transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
                -webkit-tap-highlight-color: transparent !important;
                flex: 1 !important;
                max-width: 95px !important;
                height: 50px !important;
                box-sizing: border-box !important;
                position: relative !important;
            }

            .tix-bottom-nav-item svg {
                width: 20px !important;
                height: 20px !important;
                display: block !important;
                transition: transform 0.2s ease, color 0.2s ease !important;
                flex-shrink: 0 !important;
                color: #64748b !important;
            }

            .tix-bottom-nav-item:hover {
                color: #15803d !important;
            }

            .tix-bottom-nav-item:active {
                transform: scale(0.92) !important;
            }

            .tix-bottom-nav-item.active {
                color: #15803d !important;
                font-weight: 700 !important;
            }

            .tix-bottom-nav-item.active svg {
                color: #16a34a !important;
                stroke-width: 2.5 !important;
                transform: translateY(-2px) !important;
            }

            .tix-bottom-nav-item.active::before {
                content: '' !important;
                position: absolute !important;
                top: 2px !important;
                width: 18px !important;
                height: 3px !important;
                background: linear-gradient(135deg, #1db349 0%, #84ce00 100%) !important;
                border-radius: 999px !important;
            }

            .tix-bottom-nav-label {
                font-size: 0.67rem !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                display: block !important;
            }
        }
    </style>
</head>

<body>

    {{-- ── DESKTOP & MOBILE TOPBAR ── --}}
    <header class="tix-auth-topbar">
        <div class="tix-auth-topbar-inner">
            <a href="{{ route('tix.index') }}" class="tix-auth-logo-link" title="Buyle TIX">
                <picture>
                    <source srcset="{{ asset('images/logobuyletix.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logobuyletix.png') }}" alt="Buyle TIX" class="tix-auth-logo-img">
                </picture>
            </a>

            <a href="{{ route('tix.index') }}" class="tix-auth-back-link">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                <span>Ke Beranda TIX</span>
            </a>
        </div>
    </header>

    {{-- ── MAIN AUTH WRAPPER ── --}}
    <main class="tix-auth-wrapper">
        <div class="tix-auth-card">

            {{-- Brand Logo in Card --}}
            <div class="tix-card-logo-head">
                <picture>
                    <source srcset="{{ asset('images/logobuyletix.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logobuyletix.png') }}" alt="Buyle TIX" class="tix-card-logo-img">
                </picture>
                <div class="tix-card-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    Portal Resmi Masuk &amp; Daftar
                </div>
            </div>

            {{-- Flash Messages / Errors --}}
            @if ($errors->any())
                <div class="auth-alert-error">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="dest-banner" style="background:#f0fdf4; color:#15803d; border-color:#bbf7d0;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            {{-- ── DUAL SWITCH 1: PILIH PERAN (Event Creator | Buyle Creator | Pembeli) ── --}}
            <div class="role-switch-container">
                <div class="role-switch-label">
                    <span>1. Pilih Tipe Akun Anda</span>
                    <span id="roleBadgeIndicator" style="font-size:0.7rem; color:#16a34a; font-weight:700;">Event Creator</span>
                </div>
                <div class="role-switch-pills" role="tablist">
                    {{-- 1. Event Creator --}}
                    <button type="button" class="role-pill-btn active" id="btnRoleEvent" onclick="switchRole('event_creator')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"></path>
                            <line x1="13" y1="5" x2="13" y2="19" stroke-dasharray="2 2"></line>
                        </svg>
                        <span>Event Creator</span>
                    </button>

                    {{-- 2. Buyle Creator --}}
                    <button type="button" class="role-pill-btn" id="btnRoleBuyle" onclick="switchRole('buyle_creator')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                        <span>Buyle Creator</span>
                    </button>

                    {{-- 3. Pembeli --}}
                    <button type="button" class="role-pill-btn" id="btnRoleBuyer" onclick="switchRole('buyer')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Pembeli</span>
                    </button>
                </div>
            </div>

            {{-- ── DUAL SWITCH 2: PILIH MODE (Daftar vs Login) ── --}}
            <div class="mode-switch-pills" role="tablist">
                <button type="button" class="mode-pill-btn active" id="btnModeRegister" onclick="switchMode('register')">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    <span id="labelModeRegister">Daftar</span>
                </button>
                <button type="button" class="mode-pill-btn" id="btnModeLogin" onclick="switchMode('login')">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" y1="12" x2="3" y2="12"></line>
                    </svg>
                    <span id="labelModeLogin">Login</span>
                </button>
            </div>

            {{-- Dynamic Heading & Subtitle --}}
            <div class="auth-heading-box">
                <h1 class="auth-main-title" id="authHeadline">Daftar Event Creator</h1>
                <p class="auth-sub-title" id="authSubheadline">
                    Mulai jual tiket konser, festival, &amp; event Anda dengan gate scanner kilat dan settlement instan.
                </p>
            </div>

            {{-- Destination Notice Banner --}}
            <div class="dest-banner" id="destBanner">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span id="destBannerText">Setelah berhasil, otomatis langsung diarahkan ke Dashboard Event &amp; Kehadiran</span>
            </div>

            {{-- ── HERO GOOGLE BUTTON ("Semua via Google") ── --}}
            <a href="#" class="google-auth-btn" id="btnGoogleAuth">
                <svg class="google-icon-svg" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                </svg>
                <span id="btnGoogleText">Daftar Event Creator via Google</span>
            </a>

            {{-- ── DIVIDER ── --}}
            <div class="auth-divider">
                <span>atau login dengan email</span>
            </div>

            {{-- Toggle Button for Email Form --}}
            <button type="button" class="email-form-toggle-btn" id="btnToggleEmailForm" onclick="toggleEmailForm()">
                <span id="emailToggleLabel">Tampilkan form email &amp; kata sandi</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>

            {{-- ── EMAIL & PASSWORD FORMS ── --}}
            <div class="email-form-box" id="emailFormBox">

                {{-- FORM 1: LOGIN (EMAIL + PASSWORD) --}}
                <form action="{{ route('tix.auth.login') }}" method="POST" id="formLoginEmail" style="display:none;">
                    @csrf
                    <input type="hidden" name="target_role" id="loginTargetRole" value="event_creator">
                    <input type="hidden" name="redirect_url" id="loginRedirectUrl" value="{{ url('/creator/event-attendance') }}">

                    <div class="form-group">
                        <label class="form-label">Email atau WhatsApp</label>
                        <input type="text" name="email" class="form-input" placeholder="nama@email.com atau 08xxxxxxxx" required value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kata Sandi</label>
                        <input type="password" name="password" class="form-input" placeholder="Masukkan kata sandi akun" required>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem; font-size:0.75rem;">
                        <label style="display:inline-flex; align-items:center; gap:6px; color:#475569; cursor:pointer;">
                            <input type="checkbox" name="remember" value="1" checked> Ingat saya
                        </label>
                        <a href="{{ route('password.request') }}" style="color:#16a34a; text-decoration:none; font-weight:600;">Lupa Sandi?</a>
                    </div>

                    <button type="submit" class="btn-submit-auth" id="btnSubmitLogin">
                        Masuk ke Akun
                    </button>
                </form>

                {{-- FORM 2: REGISTER (NAME + EMAIL + PHONE + PASSWORD) --}}
                <form action="{{ route('tix.auth.register') }}" method="POST" id="formRegisterEmail">
                    @csrf
                    <input type="hidden" name="target_role" id="regTargetRole" value="event_creator">
                    <input type="hidden" name="redirect_url" id="regRedirectUrl" value="{{ url('/creator/event-attendance') }}">

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-input" placeholder="Nama Anda atau Nama Penyelenggara" required value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Email Aktif</label>
                        <input type="email" name="email" class="form-input" placeholder="nama@email.com" required value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp</label>
                        <input type="text" name="phone" class="form-input" placeholder="Contoh: 08123456789" required value="{{ old('phone') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kata Sandi</label>
                        <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required minlength="6">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi kata sandi" required minlength="6">
                    </div>

                    <button type="submit" class="btn-submit-auth" id="btnSubmitRegister">
                        Daftar Akun Sekarang
                    </button>
                </form>
            </div>

            {{-- Trust & Terms Footer --}}
            <div class="auth-trust-footer">
                Dengan melanjutkan, Anda menyetujui <a href="{{ url('/syarat-ketentuan') }}" target="_blank">Syarat &amp; Ketentuan</a> serta <a href="{{ url('/kebijakan-privasi') }}" target="_blank">Kebijakan Privasi</a> Buyle TIX Indonesia.
            </div>

        </div>
    </main>

    {{-- ══════════════════════════════════════════════════════════════
       MOBILE BOTTOM NAVIGATION BAR (Identical to buyle.id/tix)
       Items: Home, About Buyle, Kerjasama, Profile (active)
       ══════════════════════════════════════════════════════════════ --}}
    <nav class="tix-bottom-nav" id="tixBottomNav" aria-label="Navigasi Bawah Buyle TIX">
        {{-- 1. Home --}}
        <a href="{{ route('tix.index') }}" class="tix-bottom-nav-item" title="Home Buyle TIX">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            <span class="tix-bottom-nav-label">Home</span>
        </a>

        {{-- 2. About Buyle --}}
        <a href="{{ route('tix.info') }}" class="tix-bottom-nav-item" title="About Buyle TIX">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="16" x2="12" y2="12" />
                <line x1="12" y1="8" x2="12.01" y2="8" />
            </svg>
            <span class="tix-bottom-nav-label">About Buyle</span>
        </a>

        {{-- 3. Kerjasama --}}
        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="tix-bottom-nav-item" title="Kerjasama Event Organizer (WhatsApp)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            <span class="tix-bottom-nav-label">Kerjasama</span>
        </a>

        {{-- 4. Profile (Active) --}}
        <a href="{{ url('/tix/auth') }}" class="tix-bottom-nav-item active" title="Profile / Portal Tiket Buyle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            <span class="tix-bottom-nav-label">Profile</span>
        </a>
    </nav>

    {{-- Interactive State Engine --}}
    <script>
        // State Store
        const AUTH_STATE = {
            role: "{{ $currentRole }}", // 'event_creator' | 'buyle_creator' | 'buyer'
            mode: "{{ $currentMode }}"  // 'register' | 'login'
        };

        // Configuration Map per Role
        const ROLE_CONFIGS = {
            event_creator: {
                label: 'Event Creator',
                destUrl: "{{ url('/creator/event-attendance') }}",
                destText: 'Setelah berhasil, otomatis langsung diarahkan ke Dashboard Event & Kehadiran (buyle.id/creator/event-attendance)',
                googleRole: 'seller',
                registerTitle: 'Daftar Event Creator',
                registerSubtitle: 'Mulai jual tiket konser, festival, & event Anda dengan gate scanner kilat dan settlement instan.',
                loginTitle: 'Login Event Creator',
                loginSubtitle: 'Masuk ke akun penyelenggara event untuk kelola tiket, pantau penjualan, dan manajemen gate.',
                googleBtnRegister: 'Daftar Event Creator via Google',
                googleBtnLogin: 'Masuk Event Creator via Google',
                submitRegister: 'Daftar Event Creator',
                submitLogin: 'Masuk Event Creator'
            },
            buyle_creator: {
                label: 'Buyle Creator',
                destUrl: "{{ url('/creator/dashboard') }}",
                destText: 'Setelah berhasil, otomatis langsung diarahkan ke Creator Dashboard (buyle.id/creator/dashboard)',
                googleRole: 'seller',
                registerTitle: 'Daftar Buyle Creator',
                registerSubtitle: 'Buat toko online, bio link, jual produk digital, dan kelola order dalam satu platform.',
                loginTitle: 'Login Buyle Creator',
                loginSubtitle: 'Kelola web builder, kasir POS, katalog produk digital, dan pencairan saldo Anda.',
                googleBtnRegister: 'Daftar Buyle Creator via Google',
                googleBtnLogin: 'Masuk Buyle Creator via Google',
                submitRegister: 'Daftar Buyle Creator',
                submitLogin: 'Masuk Buyle Creator'
            },
            buyer: {
                label: 'Pembeli',
                destUrl: "{{ url('/akun') }}",
                destText: 'Setelah berhasil, otomatis langsung diarahkan ke Akun & Pesanan Saya (buyle.id/akun/)',
                googleRole: 'buyer',
                registerTitle: 'Daftar Akun Pembeli',
                registerSubtitle: 'Beli tiket event, simpan e-tiket QR, dan cek riwayat transaksi Anda dengan mudah.',
                loginTitle: 'Login Akun Pembeli',
                loginSubtitle: 'Akses e-tiket konser Anda, download invoice, dan kelola profil pengguna.',
                googleBtnRegister: 'Daftar Pembeli via Google',
                googleBtnLogin: 'Masuk Pembeli via Google',
                submitRegister: 'Daftar Akun Pembeli',
                submitLogin: 'Masuk Akun Pembeli'
            }
        };

        function switchRole(roleKey) {
            AUTH_STATE.role = roleKey;

            // Update Role Pills UI
            document.querySelectorAll('.role-pill-btn').forEach(btn => btn.classList.remove('active'));
            if (roleKey === 'event_creator') document.getElementById('btnRoleEvent').classList.add('active');
            if (roleKey === 'buyle_creator') document.getElementById('btnRoleBuyle').classList.add('active');
            if (roleKey === 'buyer') document.getElementById('btnRoleBuyer').classList.add('active');

            renderUI();
        }

        function switchMode(modeKey) {
            AUTH_STATE.mode = modeKey;

            // Update Mode Pills UI
            document.querySelectorAll('.mode-pill-btn').forEach(btn => btn.classList.remove('active'));
            if (modeKey === 'register') {
                document.getElementById('btnModeRegister').classList.add('active');
                document.getElementById('formRegisterEmail').style.display = 'block';
                document.getElementById('formLoginEmail').style.display = 'none';
            } else {
                document.getElementById('btnModeLogin').classList.add('active');
                document.getElementById('formRegisterEmail').style.display = 'none';
                document.getElementById('formLoginEmail').style.display = 'block';
            }

            renderUI();
        }

        function renderUI() {
            const cfg = ROLE_CONFIGS[AUTH_STATE.role] || ROLE_CONFIGS.event_creator;
            const isReg = AUTH_STATE.mode === 'register';

            // Indicator Badge
            document.getElementById('roleBadgeIndicator').textContent = cfg.label;

            // Headline & Subheadline
            document.getElementById('authHeadline').textContent = isReg ? cfg.registerTitle : cfg.loginTitle;
            document.getElementById('authSubheadline').textContent = isReg ? cfg.registerSubtitle : cfg.loginSubtitle;

            // Destination text
            document.getElementById('destBannerText').textContent = cfg.destText;

            // Google Button Link & Text
            const googleRedirectUrl = "{{ route('auth.google') }}?role=" + encodeURIComponent(cfg.googleRole) + "&redirect=" + encodeURIComponent(cfg.destUrl);
            document.getElementById('btnGoogleAuth').href = googleRedirectUrl;
            document.getElementById('btnGoogleText').textContent = isReg ? cfg.googleBtnRegister : cfg.googleBtnLogin;

            // Hidden Form Inputs
            document.getElementById('loginTargetRole').value = AUTH_STATE.role;
            document.getElementById('loginRedirectUrl').value = cfg.destUrl;
            document.getElementById('regTargetRole').value = AUTH_STATE.role;
            document.getElementById('regRedirectUrl').value = cfg.destUrl;

            // Submit Button Labels
            document.getElementById('btnSubmitRegister').textContent = cfg.submitRegister;
            document.getElementById('btnSubmitLogin').textContent = cfg.submitLogin;

            // Sub-mode tab text
            document.getElementById('labelModeRegister').textContent = 'Daftar ' + (AUTH_STATE.role === 'buyer' ? 'Pembeli' : (AUTH_STATE.role === 'event_creator' ? 'Event' : 'Creator'));
            document.getElementById('labelModeLogin').textContent = 'Login ' + (AUTH_STATE.role === 'buyer' ? 'Pembeli' : (AUTH_STATE.role === 'event_creator' ? 'Event' : 'Creator'));
        }

        function toggleEmailForm() {
            const formBox = document.getElementById('emailFormBox');
            const toggleLabel = document.getElementById('emailToggleLabel');
            if (formBox.classList.contains('show')) {
                formBox.classList.remove('show');
                toggleLabel.textContent = 'Tampilkan form email & kata sandi';
            } else {
                formBox.classList.add('show');
                toggleLabel.textContent = 'Sembunyikan form email & kata sandi';
            }
        }

        // Initialize UI on load
        document.addEventListener('DOMContentLoaded', function () {
            switchRole(AUTH_STATE.role);
            switchMode(AUTH_STATE.mode);
        });
    </script>
</body>

</html>
