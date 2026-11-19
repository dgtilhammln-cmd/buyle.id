<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Selamat Datang di buyle.id – Pengaturan Akun Anda</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --primary: #1eb349;
            --primary-dark: #16933b;
            --primary-light: #eafaf0;
            --primary-gradient: linear-gradient(135deg, #1eb349 0%, #15803d 100%);
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-600: #475569;
            --slate-400: #94a3b8;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --bg-page: #f8fafc;
            --card-border: #e2e8f0;
            --font-main: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        html, body {
            min-height: 100vh;
            min-height: 100dvh;
            background-color: var(--bg-page);
            font-family: var(--font-main);
            color: var(--slate-900);
            overflow-x: hidden;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            position: relative;
        }

        /* Background ambient lights */
        .ambient-bg {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background:
                radial-gradient(circle at 10% 10%, rgba(30, 179, 73, 0.08) 0%, transparent 45%),
                radial-gradient(circle at 90% 90%, rgba(14, 165, 233, 0.07) 0%, transparent 45%);
        }

        /* Main Container: Mobile-first portrait, Responsive Landscape on Tablet & Desktop */
        .wizard-container {
            width: 100%;
            max-width: 100%;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 10;
            background: #ffffff;
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.07);
        }

        @media (min-width: 640px) {
            .wizard-container {
                min-height: auto;
                max-width: 760px;
                margin: 28px auto 40px;
                border-radius: 24px;
                border: 1px solid var(--slate-200);
                overflow: hidden;
                box-shadow: 0 16px 40px -12px rgba(15, 23, 42, 0.09);
            }
        }

        @media (min-width: 1024px) {
            .wizard-container {
                max-width: 940px;
                margin: 40px auto 60px;
                border-radius: 28px;
                box-shadow: 0 24px 60px -15px rgba(15, 23, 42, 0.12);
            }
        }

        /* Top Header */
        .wizard-header {
            padding: 1.25rem 1.25rem 0.75rem;
            border-bottom: 1px solid #f1f5f9;
            background: #ffffff;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        @media (min-width: 640px) {
            .wizard-header {
                padding: 1.5rem 2rem 1rem;
            }
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-logo-img {
            height: 36px;
            width: auto;
            max-height: 40px;
            object-fit: contain;
            display: block;
        }

        .brand-text {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.5px;
        }

        .brand-text span {
            color: var(--primary);
        }

        .step-pill {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary);
            background: var(--primary-light);
            padding: 5px 12px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }

        /* Progress Stepper */
        .progress-track {
            width: 100%;
            height: 6px;
            background: #f1f5f9;
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            width: 33.33%;
            background: var(--primary-gradient);
            border-radius: 999px;
            transition: width 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Content Area */
        .wizard-body {
            flex: 1;
            padding: 1.25rem;
            padding-bottom: 95px; /* Space for sticky bottom bar */
        }

        @media (min-width: 640px) {
            .wizard-body {
                padding: 1.75rem 2rem 100px;
            }
        }

        @media (min-width: 1024px) {
            .wizard-body {
                padding: 2.25rem 2.5rem 110px;
            }
        }

        .step-section {
            display: none;
            animation: fadeIn 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .step-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-title {
            font-size: 1.28rem;
            font-weight: 800;
            color: var(--slate-900);
            line-height: 1.35;
            margin-bottom: 6px;
        }

        @media (min-width: 640px) {
            .section-title {
                font-size: 1.48rem;
                margin-bottom: 8px;
            }
        }

        .section-desc {
            font-size: 0.85rem;
            color: var(--slate-600);
            line-height: 1.45;
            margin-bottom: 1.25rem;
        }

        @media (min-width: 640px) {
            .section-desc {
                font-size: 0.92rem;
                margin-bottom: 1.6rem;
            }
        }

        /* Grid Cards (Step 1: Persona) - Responsive 2 col (mobile) to 4 col (tablet/desktop) */
        .persona-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        @media (min-width: 640px) {
            .persona-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 12px;
            }
        }

        @media (min-width: 1024px) {
            .persona-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 14px;
            }
        }

        .persona-card {
            border: 1.5px solid var(--card-border);
            border-radius: 16px;
            padding: 14px 12px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 8px;
            user-select: none;
        }

        @media (min-width: 640px) {
            .persona-card {
                padding: 16px 14px;
                gap: 10px;
                border-radius: 18px;
            }
        }

        .persona-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }

        .persona-card.selected {
            border-color: var(--primary);
            background: #f4fbf6;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.15);
        }

        .card-icon-wrap {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--slate-800);
            transition: all 0.2s ease;
        }

        @media (min-width: 640px) {
            .card-icon-wrap {
                width: 42px;
                height: 42px;
                border-radius: 14px;
            }
        }

        .persona-card.selected .card-icon-wrap {
            background: var(--primary);
            color: #ffffff;
        }

        .card-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .card-title {
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--slate-900);
            line-height: 1.25;
        }

        @media (min-width: 640px) {
            .card-title {
                font-size: 0.92rem;
            }
        }

        .card-desc {
            font-size: 0.72rem;
            color: var(--slate-600);
            line-height: 1.3;
        }

        @media (min-width: 640px) {
            .card-desc {
                font-size: 0.76rem;
            }
        }

        .check-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(30, 179, 73, 0.4);
        }

        .persona-card.selected .check-badge {
            display: flex;
        }

        /* Step 2 Styles: Source & Media Partner */
        .source-segment-tabs {
            display: flex;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 14px;
            gap: 4px;
            margin-bottom: 14px;
        }

        .tab-btn {
            flex: 1;
            padding: 10px 8px;
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--slate-600);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .tab-btn.active {
            background: #ffffff;
            color: var(--slate-900);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
        }

        .source-tab-content {
            display: none;
        }

        .source-tab-content.active {
            display: block;
        }

        /* Medsos Chips Grid - 2 col (mobile), 3 col (tablet), 6 col (desktop) */
        .chips-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }

        @media (min-width: 640px) {
            .chips-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
            }
        }

        @media (min-width: 1024px) {
            .chips-grid {
                grid-template-columns: repeat(6, 1fr);
                gap: 10px;
            }
        }

        .chip-card {
            border: 1.5px solid var(--card-border);
            border-radius: 14px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            background: #ffffff;
            transition: all 0.2s ease;
            position: relative;
        }

        @media (min-width: 1024px) {
            .chip-card {
                flex-direction: column;
                text-align: center;
                justify-content: center;
                padding: 14px 10px;
                gap: 8px;
            }
        }

        .chip-card:hover {
            border-color: #cbd5e1;
        }

        .chip-card.selected {
            border-color: var(--primary);
            background: #f4fbf6;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.12);
        }

        .chip-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .chip-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--slate-900);
        }

        .chip-card.selected .check-badge {
            display: flex;
            width: 18px;
            height: 18px;
            top: 8px;
            right: 8px;
        }

        /* Media Partner Cards - 1 col (mobile), 2 col (tablet/desktop) */
        .partner-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 280px;
            overflow-y: auto;
            padding-right: 4px;
            margin-bottom: 16px;
        }

        @media (min-width: 640px) {
            .partner-list {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                max-height: 300px;
            }
        }

        .partner-card {
            border: 1.5px solid var(--card-border);
            border-radius: 14px;
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .partner-card:hover {
            border-color: #cbd5e1;
        }

        .partner-card.selected {
            border-color: var(--primary);
            background: #f4fbf6;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.12);
        }

        .partner-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .partner-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.95rem;
            color: var(--primary);
            flex-shrink: 0;
            overflow: hidden;
        }

        .partner-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .partner-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .partner-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--slate-900);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .partner-tag {
            font-size: 0.7rem;
            color: var(--slate-600);
        }

        .partner-badge {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--primary);
            background: var(--primary-light);
            padding: 3px 8px;
            border-radius: 6px;
            white-space: nowrap;
        }

        /* Referral Code Input Box */
        .referral-box {
            background: #f8fafc;
            border: 1px dashed var(--slate-200);
            border-radius: 14px;
            padding: 14px 16px;
        }

        .referral-box-header {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .referral-input-wrap {
            display: flex;
            gap: 8px;
            max-width: 420px;
        }

        .referral-input {
            flex: 1;
            padding: 10px 14px;
            font-size: 0.85rem;
            font-family: inherit;
            border: 1.5px solid var(--slate-200);
            border-radius: 10px;
            background: #ffffff;
            color: var(--slate-900);
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .referral-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15);
        }

        /* Step 3: Goals Cards - 1 col (mobile), 2 col (tablet/desktop) */
        .goal-cards-stack {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        @media (min-width: 640px) {
            .goal-cards-stack {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .goal-card-span-2 {
                grid-column: span 2;
            }
        }

        @media (min-width: 1024px) {
            .goal-cards-stack {
                gap: 14px;
            }
        }

        .goal-card {
            border: 1.5px solid var(--card-border);
            border-radius: 16px;
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            background: #ffffff;
            transition: all 0.2s ease;
            position: relative;
        }

        @media (min-width: 640px) {
            .goal-card {
                padding: 16px 18px;
                border-radius: 18px;
            }
        }

        .goal-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }

        .goal-card.selected {
            border-color: var(--primary);
            background: #f4fbf6;
            box-shadow: 0 4px 16px rgba(30, 179, 73, 0.15);
        }

        .goal-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--slate-800);
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        @media (min-width: 640px) {
            .goal-icon-box {
                width: 48px;
                height: 48px;
                border-radius: 14px;
            }
        }

        .goal-card.selected .goal-icon-box {
            background: var(--primary);
            color: #ffffff;
        }

        .goal-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .goal-header-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .goal-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--slate-900);
        }

        @media (min-width: 640px) {
            .goal-title {
                font-size: 0.96rem;
            }
        }

        .popular-pill {
            font-size: 0.65rem;
            font-weight: 800;
            color: #d97706;
            background: #fef3c7;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .goal-desc {
            font-size: 0.75rem;
            color: var(--slate-600);
            line-height: 1.35;
        }

        @media (min-width: 640px) {
            .goal-desc {
                font-size: 0.78rem;
            }
        }

        .goal-arrow {
            color: var(--slate-400);
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .goal-card.selected .goal-arrow {
            color: var(--primary);
            transform: translateX(3px);
        }

        /* Sticky Bottom Bar */
        .wizard-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-top: 1px solid var(--slate-200);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            z-index: 30;
        }

        @media (min-width: 640px) {
            .wizard-footer {
                position: absolute;
                bottom: 0;
                left: 0;
                transform: none;
                width: 100%;
                border-bottom-left-radius: 24px;
                border-bottom-right-radius: 24px;
                padding: 16px 28px;
            }
        }

        @media (min-width: 1024px) {
            .wizard-footer {
                border-bottom-left-radius: 28px;
                border-bottom-right-radius: 28px;
                padding: 18px 36px;
            }
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--slate-200);
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--slate-600);
            cursor: pointer;
            padding: 12px 18px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-ghost:hover {
            background: #f1f5f9;
            color: var(--slate-900);
            border-color: #cbd5e1;
        }

        .btn-primary-action {
            flex: 1;
            background: var(--primary-gradient);
            color: #ffffff;
            border: none;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 700;
            padding: 13px 24px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.3);
            transition: all 0.2s ease;
        }

        @media (min-width: 640px) {
            .btn-primary-action {
                max-width: 280px;
                margin-left: auto;
            }
        }

        .btn-primary-action:hover:not(:disabled) {
            filter: brightness(1.05);
            transform: translateY(-1px);
        }

        .btn-primary-action:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Loading Spinner */
        .spinner {
            width: 18px;
            height: 18px;
            border: 2.5px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="ambient-bg"></div>

    <div class="wizard-container">
        <!-- Sticky Header with Progress Bar -->
        <header class="wizard-header">
            <div class="header-top">
                <a href="{{ url('/') }}" class="brand-logo" title="buyle.id">
                    @php $navLogo = \App\Models\Setting::get('logo'); @endphp
                    @if($navLogo)
                        <img src="{{ asset('storage/' . $navLogo) }}" alt="buyle.id" class="brand-logo-img">
                    @else
                        <span class="brand-text">buyle<span>.id</span></span>
                    @endif
                </a>
                <span class="step-pill" id="stepPill">Langkah 1 dari 3</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </header>

        <!-- Main Wizard Body -->
        <main class="wizard-body">
            <!-- Hidden inputs state -->
            <input type="hidden" id="selectedPersona" value="">
            <input type="hidden" id="selectedSourceType" value="social_media">
            <input type="hidden" id="selectedSourceName" value="instagram">
            <input type="hidden" id="selectedPartnerName" value="">
            <input type="hidden" id="selectedPartnerId" value="">
            <input type="hidden" id="selectedGoal" value="">

            <!-- STEP 1: Segmentasi Persona Bisnis -->
            <section class="step-section active" id="step1">
                <h1 class="section-title">Apa tipe bisnis atau aktivitas Anda?</h1>
                <p class="section-desc">Pilih kategori yang paling sesuai agar kami dapat menyiapkan fitur terbaik untuk Anda.</p>

                <div class="persona-grid">
                    <!-- 1. Creator Digital -->
                    <div class="persona-card" onclick="selectPersona('creator_digital', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Produk Digital</div>
                            <div class="card-desc">E-book, template, preset, karya digital</div>
                        </div>
                    </div>

                    <!-- 2. Jasa & Freelance -->
                    <div class="persona-card" onclick="selectPersona('jasa_digital', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Jasa & Freelance</div>
                            <div class="card-desc">Desain, konsultan, dev, jasa kreatif</div>
                        </div>
                    </div>

                    <!-- 3. FnB & Kafe / Resto -->
                    <div class="persona-card" onclick="selectPersona('fnb_resto', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
                                <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                                <line x1="6" y1="1" x2="6" y2="4"/>
                                <line x1="10" y1="1" x2="10" y2="4"/>
                                <line x1="14" y1="1" x2="14" y2="4"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">FnB & Resto / Kafe</div>
                            <div class="card-desc">Kasir POS, menu digital, pesanan resto</div>
                        </div>
                    </div>

                    <!-- 4. Event Organizer & Promotor -->
                    <div class="persona-card" onclick="selectPersona('event_organizer', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Event Organizer & Promotor</div>
                            <div class="card-desc">Konser, festival, seminar & tiket online</div>
                        </div>
                    </div>

                    <!-- Vendor Acara & Perlengkapan -->
                    <div class="persona-card" onclick="selectPersona('vendor', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 3h18v18H3z"/>
                                <path d="M3 9h18"/>
                                <path d="M9 21V9"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Vendor & Supplier</div>
                            <div class="card-desc">Sound, panggung, souvenir, persewaan & perlengkapan</div>
                        </div>
                    </div>

                    <!-- Musisi & Artis -->
                    <div class="persona-card" onclick="selectPersona('musisi_artis', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18V5l12-2v13"/>
                                <circle cx="6" cy="18" r="3"/>
                                <circle cx="18" cy="16" r="3"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Musisi, Band & Artis</div>
                            <div class="card-desc">Lagu, konser, DJ, merchandise band & audio</div>
                        </div>
                    </div>

                    <!-- Fotografer & Videografer -->
                    <div class="persona-card" onclick="selectPersona('fotografer_videografer', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Fotografer & Videografer</div>
                            <div class="card-desc">Dokumentasi event, foto studio, shooting & editing</div>
                        </div>
                    </div>

                    <!-- 5. Undangan & Wedding -->
                    <div class="persona-card" onclick="selectPersona('wedding_organizer', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Undangan Digital</div>
                            <div class="card-desc">Pernikahan, khitan, RSVP & QR tamu</div>
                        </div>
                    </div>

                    <!-- 6. Content Creator -->
                    <div class="persona-card" onclick="selectPersona('content_creator', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 7l-7 5 7 5V7z"/>
                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Content Creator</div>
                            <div class="card-desc">Bio link, monetize link, portofolio</div>
                        </div>
                    </div>

                    <!-- 7. Media Partner & Agency -->
                    <div class="persona-card" onclick="selectPersona('media_partner', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="2" y1="12" x2="22" y2="12"/>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Media Partner</div>
                            <div class="card-desc">Media info, promotor, agency event</div>
                        </div>
                    </div>

                    <!-- Penulis & Edukator -->
                    <div class="persona-card" onclick="selectPersona('penulis_edukator', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Penulis & Edukator</div>
                            <div class="card-desc">Buku, kursus online, mentor & coach</div>
                        </div>
                    </div>

                    <!-- UMKM & Brand Owner -->
                    <div class="persona-card" onclick="selectPersona('umkm_brand', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                <line x1="7" y1="7" x2="7.01" y2="7"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">UMKM & Brand Owner</div>
                            <div class="card-desc">Fashion, retail, craft & produk fisik</div>
                        </div>
                    </div>

                    <!-- Komunitas & Organisasi -->
                    <div class="persona-card" onclick="selectPersona('komunitas_organisasi', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Komunitas & Organisasi</div>
                            <div class="card-desc">Komunitas kreatif, hobi, yayasan & club</div>
                        </div>
                    </div>

                    <!-- Affiliate Marketer -->
                    <div class="persona-card" onclick="selectPersona('affiliate_marketer', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"/>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Affiliate Marketer</div>
                            <div class="card-desc">Afiliasi, reviewer produk & komisi</div>
                        </div>
                    </div>

                    <!-- 8. B2B / Korporasi / Ritel -->
                    <div class="persona-card" onclick="selectPersona('b2b_corporate', this)">
                        <div class="check-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div class="card-icon-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 21h18"/>
                                <path d="M3 7v14"/>
                                <path d="M13 7v14"/>
                                <path d="M21 7v14"/>
                                <path d="M3 7l9-4 9 4"/>
                            </svg>
                        </div>
                        <div class="card-info">
                            <div class="card-title">Bisnis & Ritel</div>
                            <div class="card-desc">B2B, korporasi, toko offline & online</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- STEP 2: Tahu buyle.id dari mana? (Tracking Affiliator & Media Partner) -->
            <section class="step-section" id="step2">
                <h1 class="section-title">Dari mana Anda tahu buyle.id?</h1>
                <p class="section-desc">Pilihan ini membantu menghubungkan atribusi partner dan memaksimalkan benefit akun Anda.</p>

                <!-- Switcher Tabs -->
                <div class="source-segment-tabs">
                    <button type="button" class="tab-btn active" id="tabBtnMedsos" onclick="switchSourceTab('medsos')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                        Media Sosial & Web
                    </button>
                    <button type="button" class="tab-btn" id="tabBtnPartner" onclick="switchSourceTab('partner')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Media Partner Resmi
                    </button>
                </div>

                <!-- Tab 1: Medsos Grid -->
                <div class="source-tab-content active" id="tabMedsos">
                    <div class="chips-grid">
                        <div class="chip-card selected" onclick="selectSocial('instagram', this)">
                            <div class="check-badge">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="chip-icon" style="background:#fce7f3;color:#db2777;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                            </div>
                            <span class="chip-title">Instagram</span>
                        </div>

                        <div class="chip-card" onclick="selectSocial('tiktok', this)">
                            <div class="check-badge">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="chip-icon" style="background:#0f172a;color:#ffffff;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.82 4.48 6.3 6.3 0 0 0 1.86-4.47V8.75a8.2 8.2 0 0 0 4.91 1.63V6.93a4.7 4.7 0 0 1-1-.24z"/></svg>
                            </div>
                            <span class="chip-title">TikTok</span>
                        </div>

                        <div class="chip-card" onclick="selectSocial('youtube', this)">
                            <div class="check-badge">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="chip-icon" style="background:#fee2e2;color:#dc2626;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
                            </div>
                            <span class="chip-title">YouTube</span>
                        </div>

                        <div class="chip-card" onclick="selectSocial('google', this)">
                            <div class="check-badge">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="chip-icon" style="background:#e0f2fe;color:#0284c7;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            </div>
                            <span class="chip-title">Google / Web</span>
                        </div>

                        <div class="chip-card" onclick="selectSocial('twitter', this)">
                            <div class="check-badge">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="chip-icon" style="background:#f1f5f9;color:#0f172a;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </div>
                            <span class="chip-title">X (Twitter)</span>
                        </div>

                        <div class="chip-card" onclick="selectSocial('friend', this)">
                            <div class="check-badge">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="chip-icon" style="background:#eafaf0;color:#1eb349;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            </div>
                            <span class="chip-title">Teman / Komunitas</span>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Partner List -->
                <div class="source-tab-content" id="tabPartner">
                    <div class="partner-list">
                        @foreach($partners as $partner)
                            <div class="partner-card" onclick="selectPartner({{ $partner['id'] }}, '{{ addslashes($partner['name']) }}', this)">
                                <div class="partner-left">
                                    <div class="partner-avatar">
                                        @if(!empty($partner['logo']))
                                            <img src="{{ $partner['logo'] }}" alt="{{ $partner['name'] }}" onerror="this.style.display='none'; this.parentNode.textContent='{{ substr($partner['name'], 0, 1) }}'">
                                        @else
                                            {{ substr($partner['name'], 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="partner-info">
                                        <div class="partner-name">{{ $partner['name'] }}</div>
                                        <div class="partner-tag">Official Media Partner</div>
                                    </div>
                                </div>
                                <span class="partner-badge" style="display:flex;align-items:center;gap:3px;">
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    Terverifikasi
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Referral / Affiliator Code (Optional) -->
                <div class="referral-box">
                    <div class="referral-box-header">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        Punya Kode Referral Affiliator? (Opsional)
                    </div>
                    <div class="referral-input-wrap">
                        <input type="text" id="inputReferralCode" class="referral-input" placeholder="CONTOH: BUYLEVIP" value="{{ $defaultRefCode }}">
                    </div>
                    @if(!empty($referrerInfo))
                        <div style="margin-top:6px; font-size:0.72rem; color:#15803d; font-weight:600; display:flex; align-items:center; gap:4px;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            Terkoneksi dengan referral @<span>{{ $referrerInfo['username'] }}</span> ({{ $referrerInfo['name'] }})
                        </div>
                    @endif
                </div>
            </section>

            <!-- STEP 3: Tujuan Utama & Auto-Direct -->
            <section class="step-section" id="step3">
                <h1 class="section-title">Mau mulai dari mana sekarang?</h1>
                <p class="section-desc">Pilih fitur pertama yang ingin Anda jelajahi langsung setelah ini.</p>

                <div class="goal-cards-stack">
                    <!-- Goal 1: Products -->
                    <div class="goal-card" onclick="selectGoal('products', this)">
                        <div class="goal-icon-box">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                        </div>
                        <div class="goal-info">
                            <div class="goal-header-row">
                                <div class="goal-title">Jual Produk & Jasa Digital</div>
                                <span class="popular-pill">Populer</span>
                            </div>
                            <div class="goal-desc">Upload e-book, source code, kursus, atau layanan jasa Anda.</div>
                        </div>
                        <div class="goal-arrow">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </div>
                    </div>

                    <!-- Goal 2: Bio Link -->
                    <div class="goal-card" onclick="selectGoal('bio', this)">
                        <div class="goal-icon-box">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
                                <line x1="12" y1="18" x2="12.01" y2="18"/>
                            </svg>
                        </div>
                        <div class="goal-info">
                            <div class="goal-header-row">
                                <div class="goal-title">Buat Bio Link & Portofolio</div>
                            </div>
                            <div class="goal-desc">Bangun link pintar di bio Instagram & TikTok untuk semua katalog.</div>
                        </div>
                        <div class="goal-arrow">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </div>
                    </div>

                    <!-- Goal 3: POS Cashier -->
                    <div class="goal-card" onclick="selectGoal('pos', this)">
                        <div class="goal-icon-box">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                <line x1="8" y1="21" x2="16" y2="21"/>
                                <line x1="12" y1="17" x2="12" y2="21"/>
                            </svg>
                        </div>
                        <div class="goal-info">
                            <div class="goal-header-row">
                                <div class="goal-title">Sistem Kasir POS & Resto</div>
                            </div>
                            <div class="goal-desc">Aplikasi kasir cepat, scan barcode, cetak struk, dan meja resto.</div>
                        </div>
                        <div class="goal-arrow">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </div>
                    </div>

                    <!-- Goal 4: Event & Ticketing -->
                    <div class="goal-card" onclick="selectGoal('ticketing', this)">
                        <div class="goal-icon-box">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/>
                                <line x1="12" y1="7" x2="12" y2="17"/>
                            </svg>
                        </div>
                        <div class="goal-info">
                            <div class="goal-header-row">
                                <div class="goal-title">Jual Tiket Acara & QR Check-in</div>
                            </div>
                            <div class="goal-desc">Buat landing page tiket konser/seminar dengan scanner tiket.</div>
                        </div>
                        <div class="goal-arrow">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </div>
                    </div>

                    <!-- Goal 5: Wedding Invitation -->
                    <div class="goal-card goal-card-span-2" onclick="selectGoal('wedding', this)">
                        <div class="goal-icon-box">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </div>
                        <div class="goal-info">
                            <div class="goal-header-row">
                                <div class="goal-title">Undangan Digital & Wedding</div>
                            </div>
                            <div class="goal-desc">Desain undangan pernikahan online elegan dengan RSVP & QR tamu.</div>
                        </div>
                        <div class="goal-arrow">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Sticky Footer Navigation -->
        <footer class="wizard-footer">
            <button type="button" class="btn-ghost" id="btnBack" onclick="prevStep()" style="display: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali
            </button>

            <button type="button" class="btn-primary-action" id="btnNext" onclick="nextStep()" disabled>
                <span id="btnNextText">Lanjut</span>
                <svg id="btnNextIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                <div class="spinner" id="btnSpinner"></div>
            </button>
        </footer>
    </div>

    <!-- Wizard Super App Script -->
    <script>
        let currentStep = 1;

        // Auto-select initial state
        document.addEventListener('DOMContentLoaded', () => {
            // Auto-fill referral dari localStorage jika input masih kosong (Anti-Lepas layer 3)
            try {
                const refInput = document.getElementById('inputReferralCode');
                if (refInput && !refInput.value) {
                    const savedRef = localStorage.getItem('buyle_referral');
                    if (savedRef) {
                        refInput.value = savedRef.toUpperCase();
                    }
                }
            } catch (e) {}

            updateButtons();
        });

        function updateProgress() {
            const fill = document.getElementById('progressFill');
            const pill = document.getElementById('stepPill');
            const pct = (currentStep / 3) * 100;
            fill.style.width = pct + '%';
            pill.textContent = `Langkah ${currentStep} dari 3`;

            // Back button visibility
            const btnBack = document.getElementById('btnBack');
            btnBack.style.display = currentStep > 1 ? 'inline-flex' : 'none';

            // Next button text
            const btnNextText = document.getElementById('btnNextText');
            if (currentStep === 3) {
                btnNextText.textContent = 'Mulai Sekarang';
            } else {
                btnNextText.textContent = 'Lanjut';
            }

            updateButtons();
        }

        function updateButtons() {
            const btnNext = document.getElementById('btnNext');
            if (currentStep === 1) {
                const persona = document.getElementById('selectedPersona').value;
                btnNext.disabled = !persona;
            } else if (currentStep === 2) {
                const sourceType = document.getElementById('selectedSourceType').value;
                const sourceName = document.getElementById('selectedSourceName').value;
                const partnerName = document.getElementById('selectedPartnerName').value;
                btnNext.disabled = (sourceType === 'social_media' && !sourceName) || (sourceType === 'media_partner' && !partnerName);
            } else if (currentStep === 3) {
                const goal = document.getElementById('selectedGoal').value;
                btnNext.disabled = !goal;
            }
        }

        // Persona Selection
        function selectPersona(val, el) {
            document.querySelectorAll('.persona-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            document.getElementById('selectedPersona').value = val;
            updateButtons();

            // Auto-advance ke step 2 setelah 250ms untuk feel super app yang responsif
            setTimeout(() => {
                if (currentStep === 1) nextStep();
            }, 250);
        }

        // Source Tab Switching
        function switchSourceTab(tab) {
            document.getElementById('tabBtnMedsos').classList.toggle('active', tab === 'medsos');
            document.getElementById('tabBtnPartner').classList.toggle('active', tab === 'partner');
            document.getElementById('tabMedsos').classList.toggle('active', tab === 'medsos');
            document.getElementById('tabPartner').classList.toggle('active', tab === 'partner');

            if (tab === 'medsos') {
                document.getElementById('selectedSourceType').value = 'social_media';
            } else {
                document.getElementById('selectedSourceType').value = 'media_partner';
            }
            updateButtons();
        }

        // Social Selection
        function selectSocial(val, el) {
            document.querySelectorAll('.chip-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            document.getElementById('selectedSourceName').value = val;
            document.getElementById('selectedSourceType').value = 'social_media';
            updateButtons();
        }

        // Partner Selection
        function selectPartner(id, name, el) {
            document.querySelectorAll('.partner-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            document.getElementById('selectedPartnerId').value = id;
            document.getElementById('selectedPartnerName').value = name;
            document.getElementById('selectedSourceType').value = 'media_partner';
            updateButtons();
        }

        // Goal Selection
        function selectGoal(val, el) {
            document.querySelectorAll('.goal-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            document.getElementById('selectedGoal').value = val;
            updateButtons();
        }

        // Step Navigation
        function nextStep() {
            if (currentStep < 3) {
                document.getElementById(`step${currentStep}`).classList.remove('active');
                currentStep++;
                document.getElementById(`step${currentStep}`).classList.add('active');
                updateProgress();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (currentStep === 3) {
                submitOnboarding();
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                document.getElementById(`step${currentStep}`).classList.remove('active');
                currentStep--;
                document.getElementById(`step${currentStep}`).classList.add('active');
                updateProgress();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        // Submit Onboarding to Backend
        function submitOnboarding() {
            const btnNext = document.getElementById('btnNext');
            const btnNextText = document.getElementById('btnNextText');
            const btnNextIcon = document.getElementById('btnNextIcon');
            const btnSpinner = document.getElementById('btnSpinner');

            btnNext.disabled = true;
            btnNextText.textContent = 'Menyiapkan Dashboard...';
            btnNextIcon.style.display = 'none';
            btnSpinner.style.display = 'inline-block';

            const payload = {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                business_persona: document.getElementById('selectedPersona').value,
                referral_source_type: document.getElementById('selectedSourceType').value,
                referral_source_name: document.getElementById('selectedSourceName').value,
                media_partner_name: document.getElementById('selectedPartnerName').value,
                media_partner_id: document.getElementById('selectedPartnerId').value,
                referral_code: document.getElementById('inputReferralCode').value.trim(),
                primary_goal: document.getElementById('selectedGoal').value,
            };

            fetch("{{ route('onboarding.save') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": payload._token
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message || 'Terjadi kesalahan saat menyimpan preferensi. Mengalihkan...');
                    window.location.href = "{{ route('creator.dashboard') }}";
                }
            })
            .catch(err => {
                console.error(err);
                // Fallback direct
                window.location.href = "{{ route('creator.dashboard') }}";
            });
        }
    </script>
</body>
</html>
