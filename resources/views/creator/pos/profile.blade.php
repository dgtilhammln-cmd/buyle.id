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

    .cr-main-canvas {
        margin: 0 auto !important;
        padding: 0.85rem 1rem 120px 1rem !important;
        width: 100% !important;
        max-width: 460px !important;
        box-sizing: border-box !important;
        background: transparent !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    .cr-topbar-header-row {
        display: none !important;
    }

    .menu-hub-container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto !important;
        padding: 0 !important;
        box-sizing: border-box !important;
    }

    /* Top Navigation Bar Header */
    .menu-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.1rem;
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

    /* ── CARD 2: Dark Modern Tier Banner ── */
    .tier-modern-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 20px;
        padding: 1.15rem 1.25rem;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.15rem;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.15);
        position: relative;
        overflow: hidden;
    }

    .tier-modern-banner::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(30, 179, 73, 0.35) 0%, transparent 70%);
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
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #38bdf8;
        flex-shrink: 0;
    }

    .tier-title-text {
        font-size: 0.92rem;
        font-weight: 600;
        color: #f8fafc;
        line-height: 1.25;
    }

    .tier-sub-text {
        font-size: 0.72rem;
        color: #94a3b8;
        margin-top: 2px;
        line-height: 1.3;
    }

    .tier-pill-btn {
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #ffffff;
        border-radius: 24px;
        padding: 0.45rem 0.85rem;
        font-size: 0.72rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: background 0.15s ease;
        flex-shrink: 0;
    }

    .tier-pill-btn:hover {
        background: rgba(255, 255, 255, 0.25);
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
                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
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

    {{-- 3. Dark Modern Tier Banner --}}
    <div class="tier-modern-banner">
        <div class="tier-banner-left">
            <div class="tier-sparkle-box">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8z" />
                </svg>
            </div>
            <div>
                <div class="tier-title-text">Tier: {{ $tierInfo['badge'] ?? 'Perintis' }}</div>
                <div class="tier-sub-text">
                    Total GMV: Rp {{ number_format($gmv ?? 0, 0, ',', '.') }} ·
                    {{ $cp?->is_verified ? 'Verified Store' : 'Status Aktif' }}
                </div>
            </div>
        </div>
        <a href="{{ route('creator.pos.reports') }}" class="tier-pill-btn">
            Detail Laporan
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </a>
    </div>

    {{-- 4. Group 1: Kasir Digital & Pengaturan Utama (Pusat Pengaturan POS) --}}
    <div class="menu-group-card">
        {{-- Pengaturan POS Baru --}}
        <a href="{{ route('creator.pos.settings') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon highlight">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
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
                        <rect x="2" y="3" width="20" height="14" rx="2" /><line x1="8" y1="21" x2="16" y2="21" /><line x1="12" y1="17" x2="12" y2="21" />
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
                        <line x1="18" y1="20" x2="18" y2="10" /><line x1="12" y1="20" x2="12" y2="4" /><line x1="6" y1="20" x2="6" y2="14" />
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
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
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
                        <rect x="3" y="3" width="18" height="18" rx="2" /><path d="M3 9h18M9 21V9" />
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
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" />
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
                        <circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" />
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
                        <line x1="19" y1="5" x2="5" y2="19" /><circle cx="6.5" cy="6.5" r="2.5" /><circle cx="17.5" cy="17.5" r="2.5" />
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
                        <rect x="3" y="3" width="7" height="7" /><rect x="14" y="3" width="7" height="7" /><rect x="14" y="14" width="7" height="7" /><rect x="3" y="14" width="7" height="7" />
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

    {{-- 6. Group 3: Akun, Penarikan & Logout --}}
    <div class="menu-group-card">
        {{-- Tarik Saldo Toko --}}
        <a href="{{ route('creator.payout.settings') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="4" width="20" height="16" rx="2" /><line x1="2" y1="10" x2="22" y2="10" />
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

        {{-- Pengaturan Akun Umum --}}
        <a href="{{ route('creator.profile.edit') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
                <span class="menu-item-title">Keamanan & Password</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- Logout --}}
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

{{-- ── 4-BUTTON BOTTOM NAVIGATION BAR (Button 4: Profil Aktif) ── --}}
<div class="pos-bottom-nav-wrap" id="posBottomNavWrap">
    <div class="pos-bottom-nav-bar">
        <!-- 1. Home / Kasir -->
        <a href="{{ route('creator.pos.index') }}" class="pos-nav-item" title="Kasir Home">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span class="pos-nav-label">Home</span>
        </a>

        <!-- 2. Transactions -->
        <a href="{{ route('creator.pos.index', ['screen' => 'transactions']) }}" class="pos-nav-item" title="Transaksi">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <rect x="2" y="4" width="20" height="16" rx="2"/>
                <circle cx="12" cy="12" r="3"/>
                <line x1="2" y1="9" x2="6" y2="9"/>
                <line x1="18" y1="15" x2="22" y2="15"/>
            </svg>
            <span class="pos-nav-label">Transaksi</span>
        </a>

        <!-- 3. Laporan -->
        <a href="{{ route('creator.pos.reports') }}" class="pos-nav-item" title="Laporan POS">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <line x1="18" y1="20" x2="18" y2="10"/>
                <line x1="12" y1="20" x2="12" y2="4"/>
                <line x1="6" y1="20" x2="6" y2="14"/>
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
(function(){
    var navItems = document.querySelectorAll('.pos-nav-item');
    var activeIdx = Array.from(navItems).findIndex(function(el){ return el.classList.contains('active'); });
    navItems.forEach(function(item, idx){
        item.addEventListener('click', function(){
            if(this.classList.contains('active')) return;
            sessionStorage.setItem('posNavDir', idx > activeIdx ? 'right' : 'left');
        });
    });
    var dir = sessionStorage.getItem('posNavDir');
    sessionStorage.removeItem('posNavDir');
    var wrap = document.querySelector('.menu-hub-container');
    if(wrap && dir){
        var from = dir === 'right' ? '28px' : '-28px';
        wrap.style.opacity = '0';
        wrap.style.transform = 'translateX('+from+')';
        wrap.style.transition = 'none';
        requestAnimationFrame(function(){
            requestAnimationFrame(function(){
                wrap.style.opacity = '1';
                wrap.style.transform = 'translateX(0)';
                wrap.style.transition = 'opacity 0.28s cubic-bezier(0.16,1,0.3,1),transform 0.28s cubic-bezier(0.16,1,0.3,1)';
            });
        });
    }
})();
</script>
@endsection

