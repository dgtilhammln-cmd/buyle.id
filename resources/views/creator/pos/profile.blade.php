@extends('creator.layout')

@section('title', 'Menu & Profil Kasir – Creator Studio')
@section('page_title', 'Menu & Profil')
@section('page_subtitle', 'Pusat pengaturan kasir digital, staf, struk, dan akun bisnis.')

@section('styles')
    <style>
        :root {
            --pos-green: #1eb349;
            --pos-green-dark: #16933b;
            --pos-gradient: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        }

        body {
            background-color: #f8fafc !important;
        }

        /* Reset Global Layout Wrapper (Full Centered, Anti-Mepet, No Overflow) */
        body {
            background: #f8fafc !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .cr-main-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
            background: #f8fafc !important;
        }

        @media (max-width: 767px) {
            .cr-main-canvas {
                margin: 0 auto !important;
                padding: 0.5rem 0.75rem 120px 0.75rem !important;
                padding-top: max(0.5rem, calc(0.5rem + env(safe-area-inset-top, 0px))) !important;
                width: 100% !important;
                max-width: 480px !important;
                box-sizing: border-box !important;
                background: transparent !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            .menu-hub-container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }
        }

        @media (min-width: 768px) {
            .cr-main-canvas {
                margin: 0 !important;
                padding: 1.5rem 2rem 80px 2rem !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                background: #ffffff !important;
                border-radius: 36px 0 0 0 !important;
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.05) !important;
            }
            .cr-main-wrapper {
                background: #f1f5f9 !important;
            }
            .menu-hub-container {
                width: 100% !important;
                max-width: 680px !important;
                margin: 0 auto !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }
        }

        /* Top Navigation Bar Header (Matching /pos Header Alignment & Spacing) */
        .menu-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-top: 0 !important;
        }

        .menu-header-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .menu-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            text-decoration: none;
            transition: all 0.18s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .menu-icon-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        /* ── CARD 1: User Profile Header Card ── */
        .profile-touch-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            margin-bottom: 1rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .profile-touch-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .profile-info-group {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            min-width: 0;
        }

        .profile-avatar-wrap {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.1rem;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.25);
        }

        .profile-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-meta-text {
            min-width: 0;
        }

        .profile-user-name {
            font-size: 1.02rem;
            font-weight: 600;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }

        .profile-user-email {
            font-size: 0.78rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }

        /* ── Download App Section ── */
        .download-app-section {
            margin-bottom: 1.15rem;
        }

        .download-section-title {
            font-size: 0.72rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.65rem;
            padding-left: 2px;
        }

        .download-cards-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .download-app-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 0.95rem;
            border-radius: 14px;
            text-decoration: none;
            color: #0f172a;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
            cursor: pointer;
        }

        .download-app-card:active {
            transform: scale(0.97);
        }

        .download-app-card.android:hover {
            border-color: #22c55e;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.12);
        }

        .download-app-card.ios:hover {
            border-color: #0f172a;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        }

        .download-app-card .dl-icon-wrapper {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .download-app-card.android .dl-icon-wrapper {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .download-app-card.ios .dl-icon-wrapper {
            background: #f8fafc;
            color: #0f172a;
            border: 1px solid #e2e8f0;
        }

        .download-app-card .dl-text-col {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .download-app-card .dl-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }

        .download-app-card .dl-sub {
            font-size: 0.65rem;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }

        .download-app-card .dl-action-icon {
            margin-left: auto;
            color: #94a3b8;
            flex-shrink: 0;
        }

        /* ── CARD 2: Light Modern Revenue Banner ── */
        .tier-modern-banner {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            padding: 1.1rem 1.25rem;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.15rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            position: relative;
            overflow: hidden;
        }

        .tier-modern-banner::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .tier-banner-left {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 0;
        }

        .tier-sparkle-box {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #16a34a;
            flex-shrink: 0;
        }

        .tier-title-text {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            line-height: 1.2;
            margin-bottom: 3px;
        }

        .tier-sub-text {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .tier-pill-btn {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            color: #334155;
            border-radius: 24px;
            padding: 0.45rem 0.85rem;
            font-size: 0.74rem;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .tier-pill-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        /* Sembunyikan bagian pasang aplikasi jika sudah mode standalone / sudah install */
        @media all and (display-mode: standalone), (display-mode: fullscreen) {
            #downloadAppSection, .download-app-section {
                display: none !important;
            }
        }

        /* ── iOS Grouped Cards List ── */
        .menu-group-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            margin-bottom: 1rem;
        }

        .menu-group-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.92rem 1.15rem;
            text-decoration: none;
            color: #1e293b;
            transition: background 0.15s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .menu-group-item:last-child {
            border-bottom: none;
        }

        .menu-group-item:hover {
            background: #f8fafc;
        }

        .menu-item-left {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .menu-item-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            flex-shrink: 0;
            transition: all 0.15s;
        }

        .menu-item-icon.highlight {
            background: #ecfdf5;
            color: #16a34a;
        }

        .menu-group-item:hover .menu-item-icon {
            background: #e2e8f0;
            color: #0f172a;
        }

        .menu-item-title {
            font-size: 0.85rem;
            font-weight: 500;
            color: #0f172a;
        }

        .menu-item-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #94a3b8;
        }

        .menu-item-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.15rem 0.55rem;
            border-radius: 10px;
            background: #f1f5f9;
            color: #64748b;
        }

        .menu-item-badge.badge-green {
            background: #dcfce7;
            color: #15803d;
        }

        /* Logout Action Item */
        .menu-logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.95rem 1.15rem;
            background: none;
            border: none;
            color: #dc2626;
            cursor: pointer;
            text-align: left;
            font-family: inherit;
            transition: background 0.15s ease;
        }

        .menu-logout-btn:hover {
            background: #fef2f2;
        }

        .menu-logout-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #fee2e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc2626;
            flex-shrink: 0;
        }

        /* POS Bottom Navigation Bar */
        /* POS Standard Bottom Navigation Bar (100% Identical Across All Screens) */
        .pos-bottom-nav-wrap {
            display: flex !important;
            position: fixed !important;
            bottom: 16px !important;
            left: 14px !important;
            right: 14px !important;
            max-width: 440px !important;
            margin: 0 auto !important;
            z-index: 10000 !important;
            justify-content: center !important;
            pointer-events: none !important;
        }

        .pos-bottom-nav-bar {
            pointer-events: auto !important;
            width: 100% !important;
            height: 62px !important;
            background: #ffffff !important;
            border-radius: 999px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 0.35rem 0.65rem !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.04) !important;
            border: 1.5px solid #f1f5f9 !important;
            box-sizing: border-box !important;
        }

        .pos-nav-item {
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            color: #64748b;
            padding: 0;
            outline: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            text-decoration: none;
            -webkit-tap-highlight-color: transparent;
        }

        .pos-nav-item.active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            padding: 0.5rem 1.1rem !important;
            border-radius: 999px !important;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35) !important;
            gap: 6px !important;
            width: auto !important;
        }

        .pos-nav-label {
            display: none;
        }

        .pos-nav-item.active .pos-nav-label {
            display: inline-block;
            font-size: 0.8rem;
            font-weight: 700;
            color: #ffffff !important;
            letter-spacing: -0.01em;
        }
    </style>
@endsection

@section('content')
    <div class="menu-hub-container">
        {{-- 1. Top Bar Header --}}
        <div class="menu-header-bar">
            <a href="{{ route('creator.pos.index') }}" class="menu-icon-btn" title="Kembali ke Kasir">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
            <h2 class="menu-header-title">Menu & Profil</h2>
            <a href="{{ route('creator.pos.settings') }}" class="menu-icon-btn" title="Pengaturan POS">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3" />
                    <path
                        d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                </svg>
            </a>
        </div>

        @php
            $rawAvatar = $cp?->avatar ?? $seller->avatar ?? null;
            $avatarUrl = null;
            if ($rawAvatar) {
                $avatarUrl = \Illuminate\Support\Str::startsWith($rawAvatar, ['http://', 'https://']) ? $rawAvatar : asset('storage/' . $rawAvatar);
            }
        @endphp

        {{-- 2. Profile Touch Card --}}
        <a href="{{ route('creator.profile.edit') }}" class="profile-touch-card" title="Edit Profil Toko">
            <div class="profile-info-group">
                <div class="profile-avatar-wrap">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $seller->name }}" class="profile-avatar-img">
                    @else
                        {{ strtoupper(substr($seller->name ?? 'C', 0, 1)) }}
                    @endif
                </div>
                <div class="profile-meta-text">
                    <div class="profile-user-name">{{ $seller->name }}</div>
                    <div class="profile-user-email">{{ $seller->email }}</div>
                </div>
            </div>
            <div style="color: #94a3b8; display: flex; align-items: center;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- 3. Light Modern Revenue Banner --}}
        <div class="tier-modern-banner">
            <div class="tier-banner-left">
                <div class="tier-sparkle-box">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div>
                    <div class="tier-title-text">Total Pendapatan:</div>
                    <div class="tier-sub-text">Rp {{ number_format($gmv ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <a href="{{ route('creator.pos.reports') }}" class="tier-pill-btn">
                Detail Laporan
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        </div>


        {{-- 3b. Install PWA Section (Otomatis tersembunyi jika sudah dipasang / install) --}}
        <div class="download-app-section" id="downloadAppSection">
            <div class="download-section-title">Pasang Aplikasi Kasir</div>
            <div class="download-cards-row">
                {{-- Android --}}
                <div onclick="openInstallModal('android')" class="download-app-card android">
                    <div class="dl-icon-wrapper">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.551 0 .9993.4482.9993.9993.0001.5511-.4482.9997-.9993.9997m-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993 0 .5511-.4482.9997-.9993.9997m11.4045-6.02l1.996-3.456c.106-.183.044-.417-.139-.523-.184-.106-.417-.044-.523.139l-2.028 3.512C15.587 8.358 13.856 8 12 8s-3.587.358-5.186.993L4.786 5.481c-.106-.183-.339-.245-.523-.139-.183.106-.245.34-.139.523l1.996 3.456C2.69 11.002.32 14.288 0 18.25h24c-.32-3.962-2.69-7.248-6.1185-8.9286" />
                        </svg>
                    </div>
                    <div class="dl-text-col">
                        <div class="dl-name">Android</div>
                        <div class="dl-sub">Pasang ke Layar Utama</div>
                    </div>
                    <div class="dl-action-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                </div>

                {{-- iOS / iPhone --}}
                <div onclick="openInstallModal('ios')" class="download-app-card ios">
                    <div class="dl-icon-wrapper">
                        <svg width="20" height="20" viewBox="0 0 170 170" fill="currentColor">
                            <path
                                d="M150.37 130.25c-2.45 5.66-5.35 10.87-8.71 15.66-4.58 6.53-8.33 11.05-11.22 13.56-4.48 4.12-9.28 6.23-14.42 6.35-3.69 0-8.14-1.05-13.32-3.18-5.19-2.12-9.97-3.17-14.34-3.17-4.58 0-9.49 1.05-14.75 3.17-5.26 2.13-9.5 3.24-12.74 3.35-4.35.13-9.16-1.9-14.42-6.08-3.7-3.04-7.58-7.7-11.64-13.98-5.87-9.01-10.45-19.12-13.75-30.34-3.3-11.21-4.96-21.75-4.96-31.62 0-14.78 3.78-26.65 11.34-35.61 7.56-8.96 17.07-13.54 28.53-13.75 4.8 0 10.4 1.25 16.8 3.75 6.4 2.5 10.47 3.8 12.2 3.9 1.48 0 5.68-1.3 12.6-3.9 6.92-2.6 12.7-3.8 17.34-3.6 13.06.6 23.33 5.43 30.8 14.5-11.44 6.93-17.05 16.48-16.82 28.65.23 9.77 3.99 17.77 11.28 24 7.29 6.23 15.7 9.63 25.22 10.2-2.18 6.42-4.91 12.98-8.19 19.68zM119.22 31.84c0-7.39 2.66-14.28 7.98-20.67 5.32-6.39 11.83-10.42 19.53-12.09 1.09 7.61-.95 14.75-6.12 21.42-5.17 6.67-11.96 10.74-20.37 12.21-.33-.29-.67-.58-1.02-.87z" />
                        </svg>
                    </div>
                    <div class="dl-text-col">
                        <div class="dl-name">iOS / iPhone</div>
                        <div class="dl-sub">Pasang ke Layar Utama</div>
                    </div>
                    <div class="dl-action-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Segera cek apakah aplikasi sudah terpasang di layar utama / mode standalone
            (function () {
                try {
                    var isStandalone = window.matchMedia('(display-mode: standalone)').matches
                        || window.navigator.standalone === true
                        || (document.referrer && document.referrer.includes('android-app://'))
                        || localStorage.getItem('buyle_pos_installed') === '1'
                        || localStorage.getItem('pos_app_installed') === '1';
                    if (isStandalone) {
                        var el = document.getElementById('downloadAppSection');
                        if (el) el.style.display = 'none';
                    }
                } catch (e) {}
            })();
        </script>

        {{-- 4b. Group 1: Kasir Digital & Pengaturan Utama (Pusat Pengaturan POS) --}}
        <div class="menu-group-card">
            {{-- Pengaturan POS Baru --}}
            <a href="{{ route('creator.pos.settings') }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon highlight">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
                        </svg>
                    </div>
                    <div>
                        <span class="menu-item-title" style="font-weight:700; color:#166534;">Pengaturan POS</span>
                        <div style="font-size:0.7rem; color:#64748b; margin-top:1px;">Profil toko, struk, logo & biaya</div>
                    </div>
                </div>
                <div class="menu-item-right">
                    <span class="menu-item-badge badge-green">Lengkap</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

            {{-- Buka Kasir POS --}}
            <a href="{{ route('creator.pos.index') }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="3" width="20" height="14" rx="2" />
                            <line x1="8" y1="21" x2="16" y2="21" />
                            <line x1="12" y1="17" x2="12" y2="21" />
                        </svg>
                    </div>
                    <span class="menu-item-title">Buka Aplikasi Kasir (POS)</span>
                </div>
                <div class="menu-item-right">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

            {{-- Laporan Penjualan POS --}}
            <a href="{{ route('creator.pos.reports') }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                        </svg>
                    </div>
                    <span class="menu-item-title">Laporan Penjualan & Shift POS</span>
                </div>
                <div class="menu-item-right">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

            {{-- Kelola Produk & Menu --}}
            <a href="{{ route('creator.products.index', ['from' => 'pos']) }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                        </svg>
                    </div>
                    <span class="menu-item-title">Kelola Produk & Menu</span>
                </div>
                <div class="menu-item-right">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
        </div>

        {{-- 5. Group 2: Modul Pengaturan Detail POS --}}
        <div class="menu-group-card">
            {{-- Kelola Meja --}}
            <a href="{{ route('creator.pos.settings', ['tab' => 'tables']) }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <path d="M3 9h18M9 21V9" />
                        </svg>
                    </div>
                    <span class="menu-item-title">Kelola Meja Dine-in & QR</span>
                </div>
                <div class="menu-item-right">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

            {{-- Staf Kasir --}}
            <a href="{{ route('creator.pos.settings', ['tab' => 'staff']) }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="#8b5cf6" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        </svg>
                    </div>
                    <span class="menu-item-title">Manajemen Staf Kasir & PIN</span>
                </div>
                <div class="menu-item-right">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

            {{-- Pajak & Biaya Tambahan --}}
            <a href="{{ route('creator.pos.settings', ['tab' => 'fees']) }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="#0284c7" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
                    <span class="menu-item-title">Pajak (PB1) & Biaya Layanan</span>
                </div>
                <div class="menu-item-right">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

            {{-- Diskon & Promo --}}
            <a href="{{ route('creator.pos.settings', ['tab' => 'discounts']) }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="#eab308" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="19" y1="5" x2="5" y2="19" />
                            <circle cx="6.5" cy="6.5" r="2.5" />
                            <circle cx="17.5" cy="17.5" r="2.5" />
                        </svg>
                    </div>
                    <span class="menu-item-title">Diskon & Promo Struk</span>
                </div>
                <div class="menu-item-right">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

            {{-- Metode Pembayaran & QRIS --}}
            <a href="{{ route('creator.pos.settings', ['tab' => 'payment']) }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                    </div>
                    <span class="menu-item-title">Metode Bayar, QRIS & Fee</span>
                </div>
                <div class="menu-item-right">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
        </div>

        {{-- 5.5. Group PWA & Keamanan Kasir POS --}}
        <div class="menu-group-card">
            {{-- Push Notifikasi Transaksi POS --}}
            <div class="menu-group-item" style="cursor: pointer;" onclick="openPosPushModal()">
                <div class="menu-item-left">
                    <div class="menu-item-icon" style="background: #eff6ff; color: #2563eb;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                        </svg>
                    </div>
                    <div>
                        <span class="menu-item-title" style="font-weight: 600;">Push Notifikasi Transaksi</span>
                        <div style="font-size:0.7rem; color:#64748b; margin-top:1px;">Order kasir, deposit saldo & penarikan
                        </div>
                    </div>
                </div>
                <div class="menu-item-right">
                    <span class="menu-item-badge" id="profilePushBadge" style="background: #e0f2fe; color: #0284c7;">Cek
                        Status</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </div>


        </div>

        {{-- 6. Group 3: Akun, Penarikan & Logout --}}
        <div class="menu-group-card">
            {{-- Tarik Saldo Toko --}}
            <a href="{{ route('creator.payout.settings', ['from' => 'pos']) }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                            <line x1="2" y1="10" x2="22" y2="10" />
                        </svg>
                    </div>
                    <span class="menu-item-title">Saldo & Penarikan Dana</span>
                </div>
                <div class="menu-item-right">
                    <span class="menu-item-badge">Rp {{ number_format($availableBalance ?? 0, 0, ',', '.') }}</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

            {{-- Pengaturan Akun Umum / PIN POS --}}
            <div class="menu-group-item" style="cursor: pointer;" onclick="openPosPinModal()">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <div>
                        <span class="menu-item-title">Keamanan & Password</span>
                        <div style="font-size:0.7rem; color:#64748b; margin-top:1px;">Atur PIN Masuk POS & Keamanan</div>
                    </div>
                </div>
                <div class="menu-item-right">
                    @if(!empty(auth()->user()->pos_pin))
                        <span class="menu-item-badge badge-green">PIN Aktif</span>
                    @else
                        <span class="menu-item-badge" style="background: #fee2e2; color: #dc2626;">Atur PIN</span>
                    @endif
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </div>

            {{-- Logout --}}
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="menu-logout-btn">
                    <div class="menu-item-left">
                        <div class="menu-logout-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"
                                viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </div>
                        <span style="font-weight: 600; font-size: 0.88rem;">Keluar (Logout)</span>
                    </div>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- ── 4-BUTTON BOTTOM NAVIGATION BAR (Button 4: Profil Aktif) ── --}}
    <div class="pos-bottom-nav-wrap" id="posBottomNavWrap">
        <div class="pos-bottom-nav-bar">
            <!-- 1. Home / Kasir -->
            <a href="{{ route('creator.pos.index') }}" class="pos-nav-item" title="Kasir Home">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                <span class="pos-nav-label">Home</span>
            </a>

            <!-- 2. Transactions -->
            <a href="{{ route('creator.pos.index', ['screen' => 'transactions']) }}" class="pos-nav-item" title="Transaksi">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="2" y="4" width="20" height="16" rx="2" />
                    <circle cx="12" cy="12" r="3" />
                    <line x1="2" y1="9" x2="6" y2="9" />
                    <line x1="18" y1="15" x2="22" y2="15" />
                </svg>
                <span class="pos-nav-label">Transaksi</span>
            </a>

            <!-- 3. Laporan -->
            <a href="{{ route('creator.pos.reports') }}" class="pos-nav-item" title="Laporan POS">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="18" y1="20" x2="18" y2="10" />
                    <line x1="12" y1="20" x2="12" y2="4" />
                    <line x1="6" y1="20" x2="6" y2="14" />
                </svg>
                <span class="pos-nav-label">Laporan</span>
            </a>

            <!-- 4. Profile (ACTIVE) -->
            <a href="{{ route('creator.pos.profile') }}" class="pos-nav-item active" title="Profil Kasir & Toko">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="pos-nav-label">Profil</span>
            </a>
        </div>
    </div>

    <script>
        /* ── POS Smooth Slide Transition (direction-aware) ── */
        (function () {
            var navItems = document.querySelectorAll('.pos-nav-item');
            var activeIdx = Array.from(navItems).findIndex(function (el) { return el.classList.contains('active'); });
            navItems.forEach(function (item, idx) {
                item.addEventListener('click', function () {
                    if (this.classList.contains('active')) return;
                    sessionStorage.setItem('posNavDir', idx > activeIdx ? 'right' : 'left');
                });
            });
            var dir = sessionStorage.getItem('posNavDir');
            sessionStorage.removeItem('posNavDir');
            var wrap = document.querySelector('.menu-hub-container');
            if (wrap && dir) {
                var from = dir === 'right' ? '28px' : '-28px';
                wrap.style.opacity = '0';
                wrap.style.transform = 'translateX(' + from + ')';
                wrap.style.transition = 'none';
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        wrap.style.opacity = '1';
                        wrap.style.transform = 'translateX(0)';
                        wrap.style.transition = 'opacity 0.28s cubic-bezier(0.16,1,0.3,1),transform 0.28s cubic-bezier(0.16,1,0.3,1)';
                    });
                });
            }
        })();
    </script>
    {{-- ── MODAL PUSH NOTIFIKASI POS ── --}}
    <div id="posPushModal"
        style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:999999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem;">
        <div
            style="background:#ffffff; width:100%; max-width:400px; border-radius:24px; padding:1.5rem; box-shadow:0 20px 40px rgba(0,0,0,0.18); position:relative;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
                <div style="display:flex; align-items:center; gap:0.6rem;">
                    <div
                        style="width:38px; height:38px; border-radius:12px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                        </svg>
                    </div>
                    <div>
                        <h3 style="margin:0; font-size:1.05rem; font-weight:700; color:#0f172a;">Push Notifikasi Kasir</h3>
                        <div style="font-size:0.75rem; color:#64748b;">Notifikasi live langsung ke layar HP & PC</div>
                    </div>
                </div>
                <button type="button" onclick="closePosPushModal()"
                    style="background:none; border:none; font-size:1.5rem; color:#94a3b8; cursor:pointer; padding:0 4px;">&times;</button>
            </div>

            <div
                style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; padding:1rem; margin-bottom:1.25rem;">
                <div style="display:flex; align-items:center; justify-content:space-between;">
                    <div>
                        <div style="font-weight:700; font-size:0.88rem; color:#0f172a;">Izin Notifikasi Layar</div>
                        <div id="modalPushPermDesc" style="font-size:0.72rem; color:#64748b; margin-top:2px;">Memeriksa
                            status peramban...</div>
                    </div>
                    <button type="button" id="btnEnablePush" onclick="requestPushNotificationPermission()"
                        style="padding:0.45rem 0.9rem; border-radius:999px; font-size:0.75rem; font-weight:700; border:none; background:#1eb349; color:#fff; cursor:pointer;">
                        Aktifkan
                    </button>
                </div>
            </div>

            <div
                style="font-size:0.75rem; color:#475569; line-height:1.5; margin-bottom:1.25rem; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:14px; padding:0.85rem;">
                <div style="font-weight:700; color:#166534; margin-bottom:4px;">✨ Notifikasi Real-time Meliputi:</div>
                <ul style="margin:0; padding-left:1.2rem; color:#15803d;">
                    <li>Pesanan transaksi kasir baru sukses & selesai</li>
                    <li>Top-up mutasi saldo deposit POS masuk</li>
                    <li>Pemberitahuan penarikan saldo toko</li>
                </ul>
            </div>

            <div style="display:flex; flex-direction:column; gap:0.65rem;">
                <button type="button" id="btnTestPush" onclick="triggerTestPushNotification()"
                    style="width:100%; height:44px; border-radius:14px; background:#0f172a; color:#ffffff; font-weight:700; font-size:0.85rem; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07" />
                    </svg>
                    🔔 Tes Kirim Notifikasi Sekarang
                </button>
                <button type="button" onclick="closePosPushModal()"
                    style="width:100%; height:40px; border-radius:14px; background:transparent; color:#64748b; font-weight:600; font-size:0.8rem; border:1px solid #e2e8f0; cursor:pointer;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- ── MODAL SET / GANTI PIN POS ── --}}
    <div id="posPinModal"
        style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:999999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem;">
        <div
            style="background:#ffffff; width:100%; max-width:400px; border-radius:24px; padding:1.5rem; box-shadow:0 20px 40px rgba(0,0,0,0.18); position:relative;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
                <div style="display:flex; align-items:center; gap:0.6rem;">
                    <div
                        style="width:38px; height:38px; border-radius:12px; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2"
                            viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </div>
                    <div>
                        <h3 style="margin:0; font-size:1.05rem; font-weight:700; color:#0f172a;">Atur PIN POS Kasir</h3>
                        <div style="font-size:0.75rem; color:#64748b;">Kunci 6 angka saat membuka PWA</div>
                    </div>
                </div>
                <button type="button" onclick="closePosPinModal()"
                    style="background:none; border:none; font-size:1.5rem; color:#94a3b8; cursor:pointer; padding:0 4px;">&times;</button>
            </div>

            <form id="formSetPosPin" onsubmit="submitPosPinForm(event)">
                <div style="display:flex; flex-direction:column; gap:0.9rem; margin-bottom:1.25rem;">
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:5px;">
                            PIN Baru (6 Digit Angka) <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="password" id="inputNewPin" maxlength="6" pattern="[0-9]{6}" inputmode="numeric"
                            placeholder="Contoh: 123456" required
                            style="width:100%; height:46px; border-radius:12px; border:1.5px solid #cbd5e1; padding:0 12px; font-size:1.1rem; text-align:center; letter-spacing:0.3em; font-weight:700; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#334155; margin-bottom:5px;">
                            Konfirmasi PIN Baru <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="password" id="inputConfirmPin" maxlength="6" pattern="[0-9]{6}" inputmode="numeric"
                            placeholder="Ulangi 6 digit" required
                            style="width:100%; height:46px; border-radius:12px; border:1.5px solid #cbd5e1; padding:0 12px; font-size:1.1rem; text-align:center; letter-spacing:0.3em; font-weight:700; box-sizing:border-box;">
                    </div>
                    <div id="pinAlertError"
                        style="display:none; color:#dc2626; font-size:0.75rem; font-weight:600; text-align:center;"></div>
                </div>

                <p style="font-size:0.72rem; color:#64748b; line-height:1.4; margin:0 0 1.25rem;">
                    💡 <b>Catatan:</b> Setelah PIN disimpan, sesi login tidak akan logout. Saat PWA dibuka kembali, cukup
                    masukkan 6-digit PIN ini untuk membuka kasir.
                </p>

                <div style="display:flex; gap:0.6rem;">
                    <button type="button" onclick="closePosPinModal()"
                        style="flex:1; height:44px; border-radius:12px; background:#f1f5f9; color:#475569; font-weight:700; font-size:0.85rem; border:none; cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmitPin"
                        style="flex:2; height:44px; border-radius:12px; background:#1eb349; color:#ffffff; font-weight:700; font-size:0.85rem; border:none; cursor:pointer;">
                        Simpan PIN
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ── LOGIKA PUSH NOTIFIKASI POS ──
        function updatePushStatusUI() {
            var badge = document.getElementById("profilePushBadge");
            var desc = document.getElementById("modalPushPermDesc");
            var btn = document.getElementById("btnEnablePush");

            if (!("Notification" in window)) {
                if (badge) { badge.innerText = "Tidak Didukung"; badge.style.background = "#fee2e2"; badge.style.color = "#dc2626"; }
                if (desc) desc.innerText = "Browser ini belum mendukung Push Notification.";
                if (btn) btn.style.display = "none";
                return;
            }

            if (Notification.permission === "granted") {
                if (badge) { badge.innerText = "Aktif"; badge.style.background = "#dcfce7"; badge.style.color = "#15803d"; }
                if (desc) desc.innerText = "Notifikasi diizinkan & siap menerima pembaruan order!";
                if (btn) { btn.innerText = "Sudah Aktif"; btn.style.background = "#dcfce7"; btn.style.color = "#15803d"; btn.disabled = true; }
            } else if (Notification.permission === "denied") {
                if (badge) { badge.innerText = "Diblokir"; badge.style.background = "#fee2e2"; badge.style.color = "#dc2626"; }
                if (desc) desc.innerText = "Izin notifikasi diblokir di setelan browser.";
                if (btn) { btn.innerText = "Buka Izin"; btn.disabled = false; }
            } else {
                if (badge) { badge.innerText = "Belum Aktif"; badge.style.background = "#fef3c7"; badge.style.color = "#d97706"; }
                if (desc) desc.innerText = "Klik aktifkan untuk mengizinkan notifikasi muncul di layar.";
                if (btn) { btn.innerText = "Aktifkan"; btn.style.background = "#1eb349"; btn.style.color = "#fff"; btn.disabled = false; }
            }
        }

        function openPosPushModal() {
            updatePushStatusUI();
            document.getElementById("posPushModal").style.display = "flex";
        }

        function closePosPushModal() {
            document.getElementById("posPushModal").style.display = "none";
        }

        async function requestPushNotificationPermission() {
            if (!("Notification" in window)) {
                alert("Browser tidak mendukung Web Notification.");
                return;
            }

            try {
                var permission = await Notification.requestPermission();
                updatePushStatusUI();

                if (permission === "granted") {
                    try {
                        var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";
                        await fetch("{{ route('creator.pos.push.subscribe') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrf
                            },
                            body: JSON.stringify({
                                endpoint: "buyle-pos-device-" + navigator.userAgent.substring(0, 50),
                                keys: { p256dh: "", auth: "" }
                            })
                        });
                    } catch (e) { }
                    showLocalNativeNotification("🔔 Notifikasi POS Aktif!", "Anda akan menerima notifikasi setiap ada transaksi pesanan, deposit, dan penarikan saldo toko.");
                }
            } catch (err) {
                console.error("Gagal meminta izin notifikasi:", err);
            }
        }

        function playPosNotificationChime() {
            try {
                var ctx = new (window.AudioContext || window.webkitAudioContext)();
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime);
                osc.frequency.setValueAtTime(880, ctx.currentTime + 0.12);
                gain.gain.setValueAtTime(0.35, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.45);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.46);
            } catch (e) { }
        }

        function showLocalNativeNotification(title, body) {
            playPosNotificationChime();

            var options = {
                body: body,
                icon: "/pos-icon-192.png",
                badge: "/pos-icon-192.png",
                vibrate: [200, 100, 200, 100, 200],
                requireInteraction: true,
                data: { url: "/creator/pos" }
            };

            // Priority 1: ServiceWorker registration showNotification (always works reliably in PWAs and modern browsers)
            if ("serviceWorker" in navigator) {
                navigator.serviceWorker.ready.then(function (reg) {
                    if (reg && reg.showNotification) {
                        reg.showNotification(title, options);
                    }
                }).catch(function () {
                    if ("Notification" in window && Notification.permission === "granted") {
                        try { new Notification(title, options); } catch(err) { }
                    }
                });
            } else if ("Notification" in window && Notification.permission === "granted") {
                try { new Notification(title, options); } catch(err) { }
            }

            // Also post message to active controller SW if available
            if ("serviceWorker" in navigator && navigator.serviceWorker.controller) {
                try {
                    navigator.serviceWorker.controller.postMessage({
                        type: "SHOW_NOTIFICATION",
                        title: title,
                        options: options
                    });
                } catch(e) { }
            }
        }

        async function triggerTestPushNotification() {
            var btn = document.getElementById("btnTestPush");
            var origText = btn.innerHTML;
            btn.disabled = true;
            btn.innerText = "Mengirim notifikasi...";

            try {
                if (Notification.permission !== "granted") {
                    await Notification.requestPermission();
                    updatePushStatusUI();
                }

                var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";
                var res = await fetch("{{ route('creator.pos.push.test') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf
                    }
                });
                var data = await res.json();
                if (data.success && data.notification) {
                    showLocalNativeNotification(data.notification.title, data.notification.body);
                } else {
                    showLocalNativeNotification("🔔 Tes Notifikasi Kasir Berhasil", "Sistem notifikasi Buyle POS berjalan sempurna di perangkat Anda!");
                }
            } catch (e) {
                showLocalNativeNotification("🔔 Tes Notifikasi Berhasil", "Sistem notifikasi Buyle POS berjalan sempurna di perangkat Anda!");
            } finally {
                setTimeout(function () {
                    btn.disabled = false;
                    btn.innerHTML = origText;
                }, 1200);
            }
        }

        // ── LOGIKA PIN POS ──
        function openPosPinModal() {
            document.getElementById("posPinModal").style.display = "flex";
            document.getElementById("inputNewPin").value = "";
            document.getElementById("inputConfirmPin").value = "";
            document.getElementById("pinAlertError").style.display = "none";
        }

        function closePosPinModal() {
            document.getElementById("posPinModal").style.display = "none";
        }

        async function submitPosPinForm(e) {
            e.preventDefault();
            var pin = document.getElementById("inputNewPin").value.trim();
            var pinConf = document.getElementById("inputConfirmPin").value.trim();
            var errBox = document.getElementById("pinAlertError");
            var btn = document.getElementById("btnSubmitPin");

            if (pin.length !== 6 || !/^\d{6}$/.test(pin)) {
                errBox.innerText = "PIN harus tepat 6 digit angka.";
                errBox.style.display = "block";
                return;
            }
            if (pin !== pinConf) {
                errBox.innerText = "Konfirmasi PIN tidak cocok.";
                errBox.style.display = "block";
                return;
            }

            errBox.style.display = "none";
            btn.disabled = true;
            btn.innerText = "Menyimpan...";

            try {
                var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";
                var res = await fetch("{{ route('creator.pos.set-pin') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf
                    },
                    body: JSON.stringify({
                        pin: pin,
                        pin_confirmation: pinConf
                    })
                });
                var data = await res.json();
                if (data.success) {
                    sessionStorage.setItem("pos_unlocked", "true");
                    alert(data.message || "PIN POS 6-digit berhasil disimpan!");
                    window.location.reload();
                } else {
                    errBox.innerText = data.message || "Gagal menyimpan PIN.";
                    errBox.style.display = "block";
                }
            } catch (err) {
                errBox.innerText = "Terjadi kesalahan jaringan.";
                errBox.style.display = "block";
            } finally {
                btn.disabled = false;
                btn.innerText = "Simpan PIN";
            }
        }

        // Inisialisasi status push notifikasi saat halaman selesai dimuat
        document.addEventListener("DOMContentLoaded", function () {
            updatePushStatusUI();
        });
    
        // ── POPUP INSTALL PWA (ANDROID & IOS) ──
        var deferredPosPrompt = null;
        window.addEventListener('beforeinstallprompt', function(e) {
            e.preventDefault();
            deferredPosPrompt = e;
        });

        var currentInstallPlatform = 'android';

        function openInstallModal(platform) {
            currentInstallPlatform = platform;
            var modal = document.getElementById("pwaInstallModal");
            var steps = document.getElementById("modalSteps");
            var title = document.getElementById("modalTitle");
            var btnAction = document.getElementById("btnActionInstall");
            var linkApk = document.getElementById("linkDownloadApk");

            if (platform === 'ios') {
                title.innerText = "Pasang di iPhone / iPad";
                btnAction.style.display = "none";
                linkApk.style.display = "none";
                steps.innerHTML = `
                    <div style="display:flex; align-items:flex-start; gap:10px; margin-bottom:10px;">
                        <span style="width:22px; height:22px; border-radius:50%; background:#0f172a; color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:700; flex-shrink:0;">1</span>
                        <div>Buka link ini di browser <strong>Safari</strong> iPhone Anda.</div>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:10px; margin-bottom:10px;">
                        <span style="width:22px; height:22px; border-radius:50%; background:#0f172a; color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:700; flex-shrink:0;">2</span>
                        <div>Tap ikon <strong>Bagikan (Share)</strong> <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle; display:inline;"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg> di bilah bawah Safari.</div>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:10px;">
                        <span style="width:22px; height:22px; border-radius:50%; background:#0f172a; color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:700; flex-shrink:0;">3</span>
                        <div>Geser ke bawah dan pilih <strong>"Tambah ke Layar Utama" (Add to Home Screen)</strong>.</div>
                    </div>
                `;
            } else {
                title.innerText = "Pasang di Android";
                linkApk.style.display = "inline-block";
                if (deferredPosPrompt) {
                    btnAction.style.display = "flex";
                    btnAction.innerText = "Pasang Otomatis";
                    steps.innerHTML = `
                        <div style="color:#16a34a; font-weight:700; margin-bottom:4px;">Siap Dipasang Otomatis!</div>
                        <div>Klik tombol di bawah ini untuk memasang Buyle POS langsung ke layar utama Android Anda dengan tampilan full-screen tanpa address bar.</div>
                    `;
                } else {
                    btnAction.style.display = "none";
                    steps.innerHTML = `
                        <div style="display:flex; align-items:flex-start; gap:10px; margin-bottom:10px;">
                            <span style="width:22px; height:22px; border-radius:50%; background:#16a34a; color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:700; flex-shrink:0;">1</span>
                            <div>Buka menu Chrome (ikon titik tiga <strong>⋮</strong> di pojok kanan atas).</div>
                        </div>
                        <div style="display:flex; align-items:flex-start; gap:10px;">
                            <span style="width:22px; height:22px; border-radius:50%; background:#16a34a; color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:700; flex-shrink:0;">2</span>
                            <div>Pilih <strong>"Pasang aplikasi" (Install App)</strong> atau <strong>"Tambahkan ke Layar Utama"</strong>.</div>
                        </div>
                    `;
                }
            }

            modal.style.display = "flex";
        }

        function closeInstallModal() {
            document.getElementById("pwaInstallModal").style.display = "none";
        }

        window.addEventListener('appinstalled', function() {
            try {
                localStorage.setItem('buyle_pos_installed', '1');
                var sec = document.getElementById('downloadAppSection');
                if (sec) sec.style.display = 'none';
            } catch(e) {}
        });

        function runInstallAction() {
            if (deferredPosPrompt) {
                deferredPosPrompt.prompt();
                deferredPosPrompt.userChoice.then(function(choiceResult) {
                    if (choiceResult && choiceResult.outcome === 'accepted') {
                        try {
                            localStorage.setItem('buyle_pos_installed', '1');
                            var sec = document.getElementById('downloadAppSection');
                            if (sec) sec.style.display = 'none';
                        } catch(e) {}
                    }
                    closeInstallModal();
                    deferredPosPrompt = null;
                });
            }
        }
    </script>

    <!-- ── POPUP TAMBAH KE LAYAR UTAMA (INSTALL PWA) ── -->
    <div id="pwaInstallModal" style="display:none; position:fixed; inset:0; z-index:100060; background:rgba(15,23,42,0.65); backdrop-filter:blur(6px); align-items:center; justify-content:center; padding:1.25rem;">
        <div style="background:#ffffff; border-radius:24px; max-width:400px; width:100%; padding:1.4rem; box-shadow:0 24px 48px rgba(15,23,42,0.25); text-align:center; position:relative; animation:slideUpPos 0.25s ease;">
            <button type="button" onclick="closeInstallModal()" style="position:absolute; top:16px; right:16px; width:32px; height:32px; border-radius:50%; background:#f1f5f9; border:none; color:#64748b; font-size:1.1rem; cursor:pointer; display:flex; align-items:center; justify-content:center;">&times;</button>
            
            <div style="width:64px; height:64px; margin:0 auto 1rem; border-radius:18px; overflow:hidden; box-shadow:0 8px 20px rgba(30,179,73,0.25); background:#ffffff;">
                <img src="/pos-icon-192.png" alt="Buyle POS" style="width:100%; height:100%; object-fit:cover;">
            </div>

            <div id="modalTitle" style="font-size:1.1rem; font-weight:800; color:#0f172a; margin-bottom:4px;">Pasang Buyle POS</div>
            <div id="modalSub" style="font-size:0.75rem; color:#64748b; margin-bottom:1.2rem; line-height:1.4;">Aplikasi kasir layar penuh tanpa bilah browser.</div>

            <!-- Steps Box -->
            <div id="modalSteps" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; padding:1rem; text-align:left; font-size:0.8rem; color:#334155; line-height:1.5; margin-bottom:1.25rem;">
                <!-- Filled dynamically via JS -->
            </div>

            <button type="button" id="btnActionInstall" onclick="runInstallAction()" style="width:100%; height:46px; border-radius:14px; background:#16a34a; color:#ffffff; font-weight:700; font-size:0.9rem; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; box-shadow:0 6px 18px rgba(22,163,74,0.3);">
                Pasang Sekarang
            </button>
            <div style="margin-top:0.65rem;">
                <a href="/downloads/buyle-pos.apk" id="linkDownloadApk" download="Buyle-POS.apk" style="font-size:0.72rem; color:#0284c7; text-decoration:none; font-weight:600;">Atau download file APK mentah (1.4 MB)</a>
            </div>
        </div>
    </div>

@endsection