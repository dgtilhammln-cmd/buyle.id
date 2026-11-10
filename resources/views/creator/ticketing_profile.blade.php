@extends('creator.layout')

@section('title', 'Menu & Profil Penyelenggara – Buyle.id Ticketing')
@section('page_title', 'Menu & Profil')
@section('page_subtitle', 'Pusat operasional tiket event, verifikasi penyelenggara, dan rekening pencairan.')

@section('styles')
    <style>
        :root {
            --pos-green: #1eb349;
            --pos-green-dark: #16933b;
            --pos-gradient: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        }

        /* ── Reset Global Layout Wrapper (Responsive Mobile, Tablet & Desktop) ── */
        @media (max-width: 1024px) {
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

        @media (min-width: 768px) and (max-width: 1024px) {
            .cr-main-canvas {
                margin: 0 auto !important;
                padding: 1.25rem 1.75rem 100px 1.75rem !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                background: #ffffff !important;
                box-shadow: none !important;
            }
            .cr-main-wrapper {
                background: #f8fafc !important;
            }
            .menu-hub-container {
                width: 100% !important;
                max-width: 720px !important;
                margin: 0 auto !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }
        }

        @media (min-width: 1025px) {
            body {
                background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            }
            .cr-sidebar {
                display: flex !important;
                transform: none !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 240px !important;
                z-index: 50 !important;
            }
            .cr-main-wrapper {
                margin-left: 240px !important;
                padding: 1.25rem 0 0 0 !important;
                width: calc(100% - 240px) !important;
                max-width: calc(100% - 240px) !important;
                box-sizing: border-box !important;
                overflow-x: hidden !important;
                background: transparent !important;
            }
            .cr-main-canvas {
                margin: 0 !important;
                padding: 1.5rem 2rem 100px 2rem !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                background: #ffffff !important;
                border-radius: 40px 0 0 0 !important;
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.08) !important;
            }
            .menu-hub-container {
                width: 100% !important;
                max-width: 780px !important;
                margin: 0 auto !important;
                padding: 0 !important;
                box-sizing: border-box !important;
            }
        }

        /* Top Navigation Bar Header */
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

        .profile-role-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.68rem;
            font-weight: 800;
            background: #dcfce7;
            color: #166534;
            padding: 2px 8px;
            border-radius: 999px;
            margin-top: 4px;
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

        /* ── iOS Grouped Cards List ── */
        .menu-group-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            margin-bottom: 1rem;
        }

        .menu-group-header-row {
            padding: 0.95rem 1.15rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
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
            min-width: 0;
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

        .menu-item-sub {
            font-size: 0.7rem;
            color: #64748b;
            margin-top: 1px;
        }

        .menu-item-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .menu-item-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.15rem 0.55rem;
            border-radius: 10px;
            background: #f1f5f9;
            color: #64748b;
            white-space: nowrap;
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

        /* ── Floating Mobile / Hub Bottom Navigation Bar (Identical to /creator/event-attendance) ── */
        .scanner-bottom-nav-wrap {
            display: flex !important;
            position: fixed !important;
            bottom: 12px !important;
            left: 12px !important;
            right: 12px !important;
            max-width: 420px !important;
            margin: 0 auto !important;
            z-index: 10000 !important;
            pointer-events: none !important;
            justify-content: center !important;
            background: transparent !important;
            padding: 0 !important;
        }

        .scanner-bottom-nav-bar {
            pointer-events: auto !important;
            width: 100% !important;
            height: 56px !important;
            background: #ffffff !important;
            border-radius: 999px !important;
            display: grid !important;
            grid-template-columns: 1fr 1fr 52px 1fr 1fr !important;
            align-items: center !important;
            justify-items: center !important;
            padding: 0 0.5rem !important;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.04) !important;
            border: 1.5px solid #f1f5f9 !important;
            box-sizing: border-box !important;
        }

        .scanner-nav-item {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border: none !important;
            background: transparent !important;
            cursor: pointer !important;
            color: #64748b !important;
            padding: 0 !important;
            outline: none !important;
            width: 44px !important;
            height: 44px !important;
            border-radius: 50% !important;
            text-decoration: none !important;
            -webkit-tap-highlight-color: transparent !important;
            transition: none !important;
            position: relative !important;
            flex-shrink: 0 !important;
        }

        .scanner-nav-item.active:not(.scanner-nav-center) {
            background: transparent !important;
            color: #16a34a !important;
            box-shadow: none !important;
            width: 44px !important;
            height: 44px !important;
            padding: 0 !important;
        }

        .scanner-nav-item.active:not(.scanner-nav-center)::after {
            content: '';
            position: absolute;
            bottom: 5px;
            width: 4px;
            height: 4px;
            background: #16a34a;
            border-radius: 50%;
        }

        .scanner-nav-center {
            width: 52px !important;
            height: 52px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            margin-top: -18px !important;
            box-shadow: 0 8px 20px rgba(30, 179, 73, 0.45) !important;
            border: 3.5px solid #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            text-decoration: none !important;
            transition: none !important;
        }

        .scanner-nav-center:hover {
            color: #ffffff !important;
        }

        /* ── MODAL STYLES (Clean & Closeable) ── */
        .profile-custom-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            z-index: 999999;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            box-sizing: border-box;
        }

        .profile-custom-modal-sheet {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: 24px;
            padding: 1.35rem 1.45rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.18);
            position: relative;
            box-sizing: border-box;
            max-height: 88vh;
            overflow-y: auto;
            animation: modalPop 0.22s ease-out;
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.96) translateY(8px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
@endsection

@section('content')
    <div class="menu-hub-container">

        {{-- 1. Top Bar Header --}}
        <div class="menu-header-bar">
            <a href="{{ route('creator.ticket.scanner') }}" class="menu-icon-btn" title="Kembali ke Dashboard Event">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
            <h2 class="menu-header-title">Menu & Profil Penyelenggara</h2>
            <a href="{{ route('creator.profile.edit') }}" class="menu-icon-btn" title="Pengaturan Akun">
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
        <a href="{{ route('creator.profile.edit') }}" class="profile-touch-card" title="Edit Profil Organizer">
            <div class="profile-info-group">
                <div class="profile-avatar-wrap">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $seller->name }}" class="profile-avatar-img">
                    @else
                        {{ strtoupper(substr($seller->name ?? 'P', 0, 2)) }}
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
                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="tier-title-text">Total Penjualan Tiket:</div>
                    <div class="tier-sub-text">Rp {{ number_format($ticketRevenue ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <a href="{{ route('creator.ticket.scanner', ['tab' => 'data']) }}" class="tier-pill-btn">
                Detail Laporan
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        </div>

        {{-- Alert Notifikasi Hasil Simpan Rekening / KYC --}}
        @if(session('bank_success'))
            <div style="background:#f0fdf4; border:1.5px solid #bbf7d0; color:#166534; padding:0.85rem 1.15rem; border-radius:16px; font-weight:700; font-size:0.82rem; margin-bottom:1rem; display:flex; align-items:center; gap:0.6rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg>
                <span>{{ session('bank_success') }}</span>
            </div>
        @endif
        @if(session('bank_error'))
            <div style="background:#fef2f2; border:1.5px solid #fca5a5; color:#991b1b; padding:0.85rem 1.15rem; border-radius:16px; font-weight:700; font-size:0.82rem; margin-bottom:1rem; display:flex; align-items:center; gap:0.6rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><line x1="15" y1="9" x2="9" y2="15" /><line x1="9" y1="9" x2="15" y2="15" /></svg>
                <span>{{ session('bank_error') }}</span>
            </div>
        @endif

        {{-- ── Status Verifikasi Penyelenggara (e-KYC) — TAMPIL INLINE TANPA POPUP ── --}}
        <div class="menu-group-card" style="border: 1.5px solid #dcfce7;">
            <div class="menu-group-header-row">
                <div class="menu-item-left">
                    <div class="menu-item-icon highlight">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <circle cx="12" cy="11" r="3"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:0.88rem; font-weight:800; color:#0f172a; line-height:1.2;">Status Verifikasi Identitas (e-KYC)</div>
                        <div style="font-size:0.7rem; color:#64748b; margin-top:2px;">Kepatuhan legalitas penyelenggara tiket</div>
                    </div>
                </div>
                <div>
                    @if(isset($kyc) && $kyc && $kyc->status === 'verified')
                        <span class="menu-item-badge badge-green" style="font-weight:800; display:inline-flex; align-items:center; gap:4px;">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Terverifikasi Resmi
                        </span>
                    @elseif(isset($kyc) && $kyc && $kyc->status === 'pending')
                        <span class="menu-item-badge" style="background:#fef3c7; color:#b45309; font-weight:800; display:inline-flex; align-items:center; gap:4px;">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Menunggu Review
                        </span>
                    @else
                        <span class="menu-item-badge" style="background:#fee2e2; color:#dc2626; font-weight:800; display:inline-flex; align-items:center; gap:4px;">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Belum Verifikasi
                        </span>
                    @endif
                </div>
            </div>

            <div style="padding: 1rem 1.15rem;">
                @if(isset($kyc) && $kyc && $kyc->status === 'verified')
                    <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:14px; padding:0.95rem 1.05rem;">
                        <p style="margin:0 0 0.85rem; font-size:0.76rem; color:#166534; line-height:1.5;">
                            Akun Anda telah memenuhi standar kepatuhan e-KYC Buyle.id. Seluruh fitur penjualan event tiket berbayar dan pencairan saldo telah aktif secara penuh.
                        </p>
                        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr)); gap:0.65rem; background:#ffffff; padding:0.85rem 1rem; border-radius:12px; border:1px solid #dcfce7;">
                            <div>
                                <span style="color:#64748b; font-size:0.68rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em; display:block; margin-bottom:2px;">Nama KTP</span>
                                <strong style="font-size:0.84rem; color:#0f172a;">{{ $kyc->full_name ?? $seller->name }}</strong>
                            </div>
                            <div>
                                <span style="color:#64748b; font-size:0.68rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em; display:block; margin-bottom:2px;">NIK</span>
                                <strong style="font-size:0.84rem; color:#0f172a; font-family:monospace;">{{ isset($kyc->nik) ? substr($kyc->nik, 0, 6) . '******' . substr($kyc->nik, -4) : '-' }}</strong>
                            </div>
                            <div>
                                <span style="color:#64748b; font-size:0.68rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em; display:block; margin-bottom:2px;">Provinsi</span>
                                <strong style="font-size:0.84rem; color:#0f172a;">{{ $kyc->province ?? '-' }}</strong>
                            </div>
                            <div>
                                <span style="color:#64748b; font-size:0.68rem; font-weight:600; text-transform:uppercase; letter-spacing:0.04em; display:block; margin-bottom:2px;">Diverifikasi Pada</span>
                                <strong style="font-size:0.84rem; color:#0f172a;">{{ $kyc->reviewed_at ? $kyc->reviewed_at->format('d M Y H:i') : ($kyc->created_at ? $kyc->created_at->format('d M Y H:i') : '-') }} WIB</strong>
                            </div>
                        </div>
                    </div>
                @elseif(isset($kyc) && $kyc && $kyc->status === 'pending')
                    <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:14px; padding:0.95rem 1.05rem;">
                        <p style="margin:0 0 0.85rem; font-size:0.76rem; color:#b45309; line-height:1.5;">
                            Permohonan verifikasi e-KYC Anda telah kami terima dan saat ini sedang ditinjau oleh tim verifikator Buyle.id (maksimal 1x24 jam kerja).
                        </p>
                        <div style="background:#ffffff; padding:0.75rem 0.95rem; border-radius:10px; border:1px solid #fde68a; font-size:0.78rem; color:#0f172a;">
                            Diajukan atas nama: <strong>{{ $kyc->full_name ?? $seller->name }}</strong>
                        </div>
                    </div>
                @else
                    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:14px; padding:0.95rem 1.05rem;">
                        <p style="margin:0 0 0.85rem; font-size:0.76rem; color:#b91c1c; line-height:1.5;">
                            Untuk melindungi transaksi pembeli dan mengaktifkan pencairan saldo penjualan tiket, silakan lengkapi verifikasi KTP resmi Anda.
                        </p>
                        <a href="{{ route('creator.profile.edit') }}#kyc" style="display:inline-flex; align-items:center; gap:0.4rem; background:linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); color:#ffffff; font-size:0.78rem; font-weight:700; padding:0.5rem 1rem; border-radius:10px; text-decoration:none; box-shadow:0 3px 10px rgba(30,179,73,0.25);">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Lengkapi e-KYC Sekarang</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── 6. Group 3: Finansial & Rekening Pencairan (Payout) ── --}}
        <div class="menu-group-card">
            {{-- Saldo & Penarikan Dana --}}
            <a href="{{ route('creator.payout.settings', ['from' => 'ticketing']) }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                            <line x1="2" y1="10" x2="22" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <span class="menu-item-title">Saldo Tersedia & Payout</span>
                        <div class="menu-item-sub">Tarik saldo hasil penjualan tiket event</div>
                    </div>
                </div>
                <div class="menu-item-right">
                    <span class="menu-item-badge">Rp {{ number_format($availableBalance ?? 0, 0, ',', '.') }}</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>

            {{-- Rekening Bank Pencairan --}}
            <div class="menu-group-item" style="cursor: pointer;" onclick="openRekeningModal()">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <span class="menu-item-title">Rekening Bank Pencairan</span>
                        <div class="menu-item-sub">
                            @if($seller->bank_name)
                                {{ $seller->bank_name }} · {{ substr($seller->bank_account_number, 0, 4) . '****' . substr($seller->bank_account_number, -4) }} (a.n {{ $seller->bank_account_name }})
                            @else
                                Belum diatur (klik untuk menambahkan)
                            @endif
                        </div>
                    </div>
                </div>
                <div class="menu-item-right">
                    @if($seller->bank_name)
                        <span class="menu-item-badge badge-green">Tersambung</span>
                    @else
                        <span class="menu-item-badge" style="background:#fee2e2; color:#dc2626;">Atur Rekening</span>
                    @endif
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </div>

            {{-- Riwayat Mutasi & Payout --}}
            <a href="{{ route('creator.payout.settings', ['from' => 'ticketing']) }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <span class="menu-item-title">Riwayat Mutasi & Payout</span>
                </div>
                <div class="menu-item-right">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
        </div>

        {{-- ── 7. Group 4: Notifikasi & Navigasi Ekosistem Buyle ── --}}
        <div class="menu-group-card">
            {{-- Push Notifikasi Transaksi Tiket --}}
            <div class="menu-group-item" style="cursor: pointer;" onclick="openPushModal()">
                <div class="menu-item-left">
                    <div class="menu-item-icon" style="background: #eff6ff; color: #2563eb;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                        </svg>
                    </div>
                    <div>
                        <span class="menu-item-title" style="font-weight: 600;">Push Notifikasi Tiket & Gate</span>
                        <div class="menu-item-sub">Notifikasi order tiket baru & check-in gate</div>
                    </div>
                </div>
                <div class="menu-item-right">
                    <span class="menu-item-badge" id="ticketingPushBadge" style="background: #e0f2fe; color: #0284c7;">Cek Status</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </div>

            {{-- Lihat Storefront E-Ticketing Publik --}}
            <a href="{{ route('tix.info') }}" class="menu-group-item" target="_blank">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="2" y1="12" x2="22" y2="12"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="menu-item-title">Halaman E-Ticketing Buyle.id</span>
                        <div class="menu-item-sub">Katalog event & pembelian tiket publik</div>
                    </div>
                </div>
                <div class="menu-item-right">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                </div>
            </a>

            {{-- Creator Studio Dashboard --}}
            <a href="{{ route('creator.dashboard') }}" class="menu-group-item">
                <div class="menu-item-left">
                    <div class="menu-item-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                        </svg>
                    </div>
                    <div>
                        <span class="menu-item-title">Creator Studio Dashboard</span>
                        <div class="menu-item-sub">Pusat seluruh produk toko fisik & digital</div>
                    </div>
                </div>
                <div class="menu-item-right">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </a>
        </div>

        {{-- ── 8. Group 5: Keluar Akun (Logout) ── --}}
        <div class="menu-group-card">
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="menu-logout-btn">
                    <div class="menu-item-left">
                        <div class="menu-logout-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
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

    {{-- ── 5-BUTTON BOTTOM NAVIGATION BAR (IDENTICAL TO /creator/event-attendance) ── --}}
    <div class="scanner-bottom-nav-wrap" id="scannerBottomNavWrap">
        <div class="scanner-bottom-nav-bar">
            {{-- 1. Home --}}
            <a href="{{ route('creator.ticket.scanner', ['tab' => 'home']) }}" class="scanner-nav-item" title="Home">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </a>

            {{-- 2. Data Kehadiran (Clipboard Checklist) --}}
            <a href="{{ route('creator.ticket.scanner', ['tab' => 'data']) }}" class="scanner-nav-item" title="Data Kehadiran">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="2"/>
                    <path d="m9 14 2 2 4-4"/>
                </svg>
            </a>

            {{-- 3. Center Scanner (Floating Circle) --}}
            <a href="{{ route('creator.ticket.scanner', ['tab' => 'scanner']) }}" class="scanner-nav-item scanner-nav-center" title="Live QR Scanner">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                    <path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/>
                    <rect x="7" y="7" width="10" height="10" rx="1"/>
                </svg>
            </a>

            {{-- 4. Gate Staff (Gate Door) --}}
            <a href="{{ route('creator.ticket.scanner', ['tab' => 'gate']) }}" class="scanner-nav-item" title="Gate Staff">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/>
                    <path d="M2 20h20"/>
                    <circle cx="14" cy="12" r="1" fill="currentColor"/>
                </svg>
            </a>

            {{-- 5. Profile (ACTIVE dengan Dot Indicator) --}}
            <a href="{{ route('creator.ticket.profile') }}" class="scanner-nav-item active" title="Profil Organizer">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- ── MODAL REKENING PENCAIRAN (CLOSEABLE & CLEAN) ── --}}
    <div id="rekeningModal" class="profile-custom-modal" style="display: none;" onclick="handleRekeningBackdrop(event)">
        <div class="profile-custom-modal-sheet" id="rekeningModalSheet">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; border-bottom:1px solid #f1f5f9; padding-bottom:0.75rem;">
                <div style="display:flex; align-items:center; gap:0.6rem;">
                    <div style="width:36px; height:36px; border-radius:10px; background:#f0fdf4; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; color:#16a34a;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin:0; font-size:0.95rem; font-weight:800; color:#0f172a;">Rekening Pencairan Dana</h4>
                        <p style="margin:2px 0 0; font-size:0.72rem; color:#64748b;">Tersinkron dengan pencairan saldo tiket</p>
                    </div>
                </div>
                <button type="button" onclick="closeRekeningModal()" style="border:none; background:#f1f5f9; width:32px; height:32px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center; color:#64748b;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('creator.ticket.bank.update') }}">
                @csrf
                <div style="display:flex; flex-direction:column; gap:0.95rem;">
                    <div>
                        <label style="display:block; font-size:0.76rem; font-weight:700; color:#374151; margin-bottom:0.35rem;">Bank / E-Wallet</label>
                        <select name="bank_name" required style="width:100%; height:42px; padding:0 0.75rem; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.84rem; color:#0f172a; background:#fff; font-family:inherit; outline:none;">
                            <option value="">— Pilih Bank / E-Wallet —</option>
                            <optgroup label="Bank Nasional">
                                @foreach(['BCA', 'Mandiri', 'BRI', 'BNI', 'CIMB Niaga', 'Danamon', 'Permata', 'BSI', 'BTN', 'Maybank', 'Panin', 'OCBC NISP', 'Bank Neo Commerce', 'Jenius (SMBC)'] as $bank)
                                    <option value="{{ $bank }}" {{ $seller->bank_name === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="E-Wallet">
                                @foreach(['GoPay', 'OVO', 'Dana', 'ShopeePay', 'LinkAja', 'Flip'] as $ewallet)
                                    <option value="{{ $ewallet }}" {{ $seller->bank_name === $ewallet ? 'selected' : '' }}>{{ $ewallet }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:0.76rem; font-weight:700; color:#374151; margin-bottom:0.35rem;">Nomor Rekening / No. HP</label>
                        <input type="text" name="bank_account_number" inputmode="numeric" value="{{ old('bank_account_number', $seller->bank_account_number) }}" required placeholder="Contoh: 1234567890" style="width:100%; height:42px; padding:0 0.75rem; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.84rem; color:#0f172a; outline:none; font-family:inherit; box-sizing:border-box;">
                    </div>

                    <div>
                        <label style="display:block; font-size:0.76rem; font-weight:700; color:#374151; margin-bottom:0.35rem;">
                            Nama Pemilik Rekening <span style="font-weight:400; color:#64748b; font-size:0.72rem;">(harus sesuai nama akun)</span>
                        </label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $seller->bank_account_name ?? $seller->name) }}" required style="width:100%; height:42px; padding:0 0.75rem; border:1.5px solid #e2e8f0; border-radius:12px; font-size:0.84rem; color:#0f172a; outline:none; font-family:inherit; box-sizing:border-box;">
                        <span style="font-size:0.7rem; color:#64748b; margin-top:0.25rem; display:block;">Nama terdaftar: <strong>{{ $seller->name }}</strong></span>
                    </div>

                    <div style="display:flex; gap:0.65rem; margin-top:0.5rem;">
                        <button type="button" onclick="closeRekeningModal()" style="flex:1; height:44px; background:#f1f5f9; color:#475569; border:none; border-radius:12px; font-size:0.82rem; font-weight:700; cursor:pointer;">
                            Batal
                        </button>
                        <button type="submit" style="flex:2; height:44px; background:linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); color:#ffffff; border:none; border-radius:12px; font-size:0.84rem; font-weight:800; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.4rem; box-shadow:0 3px 12px rgba(30,179,73,0.25);">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            <span>Simpan Rekening</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL PUSH NOTIFIKASI TIKET ── --}}
    <div id="pushModal" class="profile-custom-modal" style="display: none;" onclick="handlePushBackdrop(event)">
        <div class="profile-custom-modal-sheet" id="pushModalSheet">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem;">
                <div style="display:flex; align-items:center; gap:0.6rem;">
                    <div style="width:38px; height:38px; border-radius:12px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                        </svg>
                    </div>
                    <div>
                        <h3 style="margin:0; font-size:1.05rem; font-weight:700; color:#0f172a;">Push Notifikasi Tiket</h3>
                        <div style="font-size:0.75rem; color:#64748b;">Notifikasi live langsung ke layar HP & PC</div>
                    </div>
                </div>
                <button type="button" onclick="closePushModal()" style="background:none; border:none; font-size:1.5rem; color:#94a3b8; cursor:pointer; padding:0 4px;">&times;</button>
            </div>

            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:16px; padding:1rem; margin-bottom:1.25rem;">
                <div style="display:flex; align-items:center; justify-content:space-between;">
                    <div>
                        <div style="font-weight:700; font-size:0.88rem; color:#0f172a;">Izin Notifikasi Layar</div>
                        <div id="modalPushPermDesc" style="font-size:0.72rem; color:#64748b; margin-top:2px;">Memeriksa status peramban...</div>
                    </div>
                    <button type="button" id="btnEnablePush" onclick="requestPushPermission()" style="padding:0.45rem 0.9rem; border-radius:999px; font-size:0.75rem; font-weight:700; border:none; background:#1eb349; color:#fff; cursor:pointer;">
                        Aktifkan
                    </button>
                </div>
            </div>

            <div style="font-size:0.75rem; color:#475569; line-height:1.5; margin-bottom:1.25rem; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:14px; padding:0.85rem;">
                <div style="font-weight:700; color:#166534; margin-bottom:4px;">✨ Notifikasi Real-time Meliputi:</div>
                <ul style="margin:0; padding-left:1.2rem; color:#15803d;">
                    <li>Pembelian tiket event baru oleh pengunjung</li>
                    <li>Notifikasi status check-in scan tiket di gate</li>
                    <li>Pemberitahuan pencairan saldo penjualan tiket</li>
                </ul>
            </div>

            <button type="button" onclick="closePushModal()" style="width:100%; height:44px; border-radius:14px; background:#0f172a; color:#ffffff; font-weight:700; font-size:0.85rem; border:none; cursor:pointer;">
                Selesai
            </button>
        </div>
    </div>

    <script>
        function openRekeningModal() {
            const m = document.getElementById('rekeningModal');
            if (m) m.style.display = 'flex';
        }

        function closeRekeningModal() {
            const m = document.getElementById('rekeningModal');
            if (m) m.style.display = 'none';
        }

        function handleRekeningBackdrop(e) {
            if (e.target.id === 'rekeningModal') {
                closeRekeningModal();
            }
        }

        function openPushModal() {
            const m = document.getElementById('pushModal');
            if (m) {
                m.style.display = 'flex';
                updatePushPermissionUI();
            }
        }

        function closePushModal() {
            const m = document.getElementById('pushModal');
            if (m) m.style.display = 'none';
        }

        function handlePushBackdrop(e) {
            if (e.target.id === 'pushModal') {
                closePushModal();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRekeningModal();
                closePushModal();
            }
        });

        function updatePushPermissionUI() {
            const desc = document.getElementById('modalPushPermDesc');
            const btn = document.getElementById('btnEnablePush');
            const badge = document.getElementById('ticketingPushBadge');
            if (!('Notification' in window)) {
                if (desc) desc.textContent = 'Peramban tidak mendukung notifikasi push.';
                if (btn) btn.style.display = 'none';
                if (badge) { badge.textContent = 'Tidak Didukung'; badge.style.background = '#f1f5f9'; badge.style.color = '#94a3b8'; }
                return;
            }
            if (Notification.permission === 'granted') {
                if (desc) desc.textContent = 'Notifikasi telah aktif di perangkat ini.';
                if (btn) { btn.textContent = 'Aktif ✓'; btn.style.background = '#15803d'; btn.disabled = true; }
                if (badge) { badge.textContent = 'Aktif ✓'; badge.style.background = '#dcfce7'; badge.style.color = '#15803d'; }
            } else if (Notification.permission === 'denied') {
                if (desc) desc.textContent = 'Izin notifikasi diblokir di setelan browser.';
                if (btn) { btn.textContent = 'Diblokir'; btn.style.background = '#dc2626'; btn.disabled = true; }
                if (badge) { badge.textContent = 'Diblokir'; badge.style.background = '#fee2e2'; badge.style.color = '#dc2626'; }
            } else {
                if (desc) desc.textContent = 'Klik aktifkan untuk menerima pemberitahuan live.';
                if (btn) { btn.textContent = 'Aktifkan'; btn.style.background = '#1eb349'; btn.disabled = false; }
                if (badge) { badge.textContent = 'Belum Aktif'; badge.style.background = '#e0f2fe'; badge.style.color = '#0284c7'; }
            }
        }

        async function requestPushPermission() {
            if (!('Notification' in window)) return;
            try {
                const res = await Notification.requestPermission();
                updatePushPermissionUI();
            } catch (e) {}
        }

        document.addEventListener('DOMContentLoaded', function() {
            updatePushPermissionUI();
        });
    </script>
@endsection

