@extends('creator.layout')

@section('title', 'Menu & Profil – Creator Studio')
@section('page_title', 'Menu & Profil')
@section('page_subtitle', 'Pusat pengaturan akun, integrasi toko, dan aplikasi bisnis.')

@section('styles')
<style>
    .menu-hub-container {
        max-width: 540px;
        margin: 0 auto;
        padding-bottom: 2.5rem;
    }

    /* Top Navigation Bar Header */
    .menu-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }

    .menu-header-title {
        font-size: 1.2rem;
        font-weight: 600;
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
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .menu-icon-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
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
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        margin-bottom: 1rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .profile-touch-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
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
        box-shadow: 0 2px 8px rgba(30,179,73,0.25);
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

    /* ── CARD 2: Dark Modern Tier Banner (Upgrade to Pro style) ── */
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
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.25);
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
        background: rgba(255,255,255,0.1);
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
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.22);
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
        background: rgba(255,255,255,0.25);
    }

    /* ── iOS Grouped Cards List ── */
    .menu-group-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
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
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        flex-shrink: 0;
        transition: all 0.15s;
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

    /* Toggle Switch Component */
    .menu-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }

    .menu-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .menu-switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: 0.25s;
        border-radius: 34px;
    }

    .menu-switch-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.25s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }

    .menu-switch input:checked + .menu-switch-slider {
        background-color: #1eb349;
    }

    .menu-switch input:checked + .menu-switch-slider:before {
        transform: translateX(20px);
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

    /* ── PIN Setup Modal ── */
    .pin-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(5px);
        z-index: 99999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
    }

    .pin-modal-card {
        background: #ffffff;
        border-radius: 24px;
        max-width: 400px;
        width: 100%;
        padding: 1.75rem 1.5rem;
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        animation: pinModalPop 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        text-align: center;
    }

    @keyframes pinModalPop {
        0% { transform: scale(0.92); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .pin-input-boxes {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        margin: 1.25rem 0;
    }

    .pin-digit-box {
        width: 42px;
        height: 48px;
        font-size: 1.4rem;
        font-weight: 700;
        text-align: center;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        outline: none;
        transition: all 0.15s;
    }

    .pin-digit-box:focus {
        border-color: #1eb349;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(30,179,73,0.15);
    }
</style>
@endsection

@section('content')
<div class="menu-hub-container">

    {{-- 1. Top Bar Header --}}
    <div class="menu-header-bar">
        <a href="{{ route('creator.dashboard') }}" class="menu-icon-btn" title="Kembali ke Overview">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>
        <h2 class="menu-header-title">Menu & Profil</h2>
        <a href="{{ route('creator.dashboard') }}" class="menu-icon-btn" title="Dashboard">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1V9.5z" />
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

    {{-- 2. Profile Touch Card (Direct Link to https://buyle.id/creator/profile) --}}
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

    {{-- 3. Dark Modern Tier Banner (Upgrade to Pro style) --}}
    <div class="tier-modern-banner">
        <div class="tier-banner-left">
            <div class="tier-sparkle-box">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8z"/>
                </svg>
            </div>
            <div>
                <div class="tier-title-text">Tier: {{ $tierInfo['badge'] ?? 'Perintis' }}</div>
                <div class="tier-sub-text">
                    Total GMV: Rp {{ number_format($gmv ?? 0, 0, ',', '.') }} · {{ $cp?->is_verified ? 'Verified Creator' : 'Status Aktif' }}
                </div>
            </div>
        </div>
        <a href="{{ route('creator.sales.report') }}" class="tier-pill-btn">
            Detail Tier
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </a>
    </div>

    {{-- 4. Group 1: Toko, Finansial & Keamanan PIN/Biometrik --}}
    <div class="menu-group-card">
        {{-- Settings / Pengaturan Profil --}}
        <a href="{{ route('creator.profile.edit') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                </div>
                <span class="menu-item-title">Pengaturan Profil & Toko</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- Kelola Produk Saya --}}
        <a href="{{ route('creator.products.index') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2" />
                        <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                    </svg>
                </div>
                <span class="menu-item-title">Kelola Produk Saya</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- Kelompok Produk --}}
        <a href="{{ route('creator.groups.index') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <span class="menu-item-title">Kelompok Produk</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- Saldo & Pencairan Dana --}}
        <a href="{{ route('creator.payout.settings') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <circle cx="16" cy="12" r="2" />
                        <path d="M6 12h.01" />
                    </svg>
                </div>
                <span class="menu-item-title">Saldo & Rekening Pencairan</span>
            </div>
            <div class="menu-item-right">
                <span class="menu-item-badge" style="color:#16a34a; background:#dcfce7;">
                    Rp {{ number_format($availableBalance ?? 0, 0, ',', '.') }}
                </span>
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- PIN Keamanan Layar PWA --}}
        <div class="menu-group-item" onclick="openPinModal()" style="cursor: pointer;">
            <div class="menu-item-left">
                <div class="menu-item-icon" style="color: #6366f1; background: #e0e7ff;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <div>
                    <span class="menu-item-title" style="display:block;">PIN Keamanan PWA</span>
                    <span style="font-size: 0.7rem; color: #94a3b8;">Kunci aplikasi tanpa logout</span>
                </div>
            </div>
            <div class="menu-item-right">
                <span class="menu-item-badge" id="pinBadgeText" style="{{ $hasPin ? 'color:#16a34a; background:#dcfce7;' : 'color:#64748b; background:#f1f5f9;' }}">
                    {{ $hasPin ? 'PIN Aktif' : 'Atur PIN' }}
                </span>
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </div>

        {{-- Enable Fingerprint / Face ID (Persis Sesuai Gambar Referensi 1) --}}
        <div class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon" style="color: #059669; background: #d1fae5;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004 11m0 0a8 8 0 00.99 7.132"></path>
                    </svg>
                </div>
                <div>
                    <span class="menu-item-title" style="display:block;">Enable finger Print/Face ID</span>
                    <span style="font-size: 0.7rem; color: #94a3b8;">Sensor sidik jari / Face Unlock HP</span>
                </div>
            </div>
            <div class="menu-item-right">
                <label class="menu-switch">
                    <input type="checkbox" id="biometricToggle" {{ $biometricEnabled ? 'checked' : '' }} onchange="handleBiometricToggle(this)">
                    <span class="menu-switch-slider"></span>
                </label>
            </div>
        </div>

        {{-- Interactive Push Notification (PWA) Toggle --}}
        <div class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon" style="color: #1eb349; background: #ecfdf5;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
                <div>
                    <span class="menu-item-title" style="display:block;">Notifikasi Push PWA (HP)</span>
                    <span style="font-size: 0.7rem; color: #94a3b8;">Notif instan saat order masuk</span>
                </div>
            </div>
            <div class="menu-item-right">
                <label class="menu-switch">
                    <input type="checkbox" id="pushNotificationToggle" {{ $hasPushSub ? 'checked' : '' }} onchange="handlePushToggle(this)">
                    <span class="menu-switch-slider"></span>
                </label>
            </div>
        </div>
    </div>

    {{-- 5. Group 2: Aplikasi Creator Studio --}}
    <div class="menu-group-card">
        {{-- Kasir Digital POS --}}
        <a href="{{ route('creator.pos.index') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon" style="color: #0284c7; background: #e0f2fe;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="4" width="20" height="16" rx="2" />
                        <line x1="2" y1="10" x2="22" y2="10" />
                    </svg>
                </div>
                <span class="menu-item-title">Kasir POS Digital</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- Web Builder / Bio Link --}}
        <a href="{{ route('creator.bio.index') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon" style="color: #10b981; background: #d1fae5;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="2" y1="12" x2="22" y2="12" />
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                    </svg>
                </div>
                <span class="menu-item-title">Web Builder (Bio Link)</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- Tiket Scanner --}}
        <a href="{{ route('creator.scanner.index') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon" style="color: #8b5cf6; background: #ede9fe;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 7V5a2 2 0 0 1 2-2h2" />
                        <path d="M17 3h2a2 2 0 0 1 2 2v2" />
                        <path d="M21 17v2a2 2 0 0 1-2 2h-2" />
                        <path d="M7 21H5a2 2 0 0 1-2-2v-2" />
                        <line x1="7" y1="12" x2="17" y2="12" />
                    </svg>
                </div>
                <span class="menu-item-title">Tiket Scanner Event</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- Auto Reply DM Instagram --}}
        <a href="{{ route('creator.automation-reply.index') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon" style="color: #ec4899; background: #fce7f3;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                    </svg>
                </div>
                <span class="menu-item-title">Auto Reply DM Instagram</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- Undangan Digital Wedding --}}
        <a href="{{ route('creator.wedding.index') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon" style="color: #f43f5e; background: #ffe4e6;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                </div>
                <span class="menu-item-title">Undangan Digital (Wedding)</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
    </div>

    {{-- 6. Group 3: Toko Publik & Bantuan --}}
    <div class="menu-group-card">
        {{-- Kunjungi Web Toko Saya --}}
        <a href="{{ $storeSlug ? route('store.show', $storeSlug) : url('/') }}" target="_blank" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                        <polyline points="15 3 21 3 21 9" />
                        <line x1="10" y1="14" x2="21" y2="3" />
                    </svg>
                </div>
                <span class="menu-item-title">Kunjungi Web Toko Saya</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- Laporan Penjualan & Order --}}
        <a href="{{ route('creator.sales.report') }}" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                    </svg>
                </div>
                <span class="menu-item-title">Statistik & Data Order</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- Bantuan WhatsApp CS --}}
        <a href="https://wa.me/6281222223803" target="_blank" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon" style="color: #15803d; background: #dcfce7;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                    </svg>
                </div>
                <span class="menu-item-title">Bantuan CS Buyle (WhatsApp)</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>

        {{-- Kebijakan Privasi --}}
        <a href="{{ route('legal.privacy') }}" target="_blank" class="menu-group-item">
            <div class="menu-item-left">
                <div class="menu-item-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <span class="menu-item-title">Kebijakan Privasi</span>
            </div>
            <div class="menu-item-right">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
        </a>
    </div>

    {{-- 7. Group 4: Logout --}}
    <div class="menu-group-card">
        <form id="logoutFormMenu" action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="menu-logout-btn">
                <div class="menu-item-left">
                    <div class="menu-logout-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </div>
                    <span style="font-size: 0.85rem; font-weight: 600;">Keluar dari Akun (Logout)</span>
                </div>
                <div style="color: #f87171;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </button>
        </form>
    </div>

</div>

{{-- ── PIN Setup Modal ── --}}
<div class="pin-modal-backdrop" id="pinSetupModal">
    <div class="pin-modal-card">
        <div style="width:48px; height:48px; border-radius:50%; background:#ecfdf5; color:#1eb349; display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>
        <h3 style="font-size:1.15rem; font-weight:600; color:#0f172a; margin:0 0 0.35rem;">Atur PIN Keamanan PWA</h3>
        <p style="font-size:0.78rem; color:#64748b; margin:0 0 1.25rem; line-height:1.4;">
            Masukkan 6 angka PIN rahasia untuk membuka aplikasi PWA tanpa perlu login email ulang.
        </p>

        <form id="pinSetupForm" onsubmit="submitPinSetup(event)">
            <div class="pin-input-boxes">
                <input type="password" maxlength="1" inputmode="numeric" class="pin-digit-box" autofocus>
                <input type="password" maxlength="1" inputmode="numeric" class="pin-digit-box">
                <input type="password" maxlength="1" inputmode="numeric" class="pin-digit-box">
                <input type="password" maxlength="1" inputmode="numeric" class="pin-digit-box">
                <input type="password" maxlength="1" inputmode="numeric" class="pin-digit-box">
                <input type="password" maxlength="1" inputmode="numeric" class="pin-digit-box">
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:0.75rem 1rem; margin-bottom:1.25rem;">
                <div style="text-align:left;">
                    <div style="font-size:0.8rem; font-weight:600; color:#0f172a;">Gunakan Sensor Biometrik</div>
                    <div style="font-size:0.7rem; color:#94a3b8;">Buka dengan Sidik Jari / Face ID</div>
                </div>
                <label class="menu-switch">
                    <input type="checkbox" id="modalBiometricCheckbox" {{ $biometricEnabled ? 'checked' : '' }}>
                    <span class="menu-switch-slider"></span>
                </label>
            </div>

            <div style="display:flex; gap:0.6rem;">
                <button type="button" onclick="closePinModal()" style="flex:1; padding:0.75rem; border:1px solid #e2e8f0; border-radius:12px; background:#fff; color:#475569; font-weight:600; font-size:0.82rem; cursor:pointer;">
                    Batal
                </button>
                <button type="submit" style="flex:1.2; padding:0.75rem; border:none; border-radius:12px; background:linear-gradient(135deg, #1eb349 0%, #16a34a 100%); color:#fff; font-weight:600; font-size:0.82rem; cursor:pointer; box-shadow:0 4px 14px rgba(30,179,73,0.35);">
                    Simpan PIN
                </button>
            </div>

            @if($hasPin)
                <div style="margin-top:0.85rem;">
                    <button type="button" onclick="removePinSecurity()" style="background:none; border:none; color:#ef4444; font-size:0.74rem; font-weight:600; cursor:pointer;">
                        Hapus PIN (Nonaktifkan Kunci)
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ── PIN Modal Logic ──
    const pinModal = document.getElementById('pinSetupModal');
    const digitInputs = document.querySelectorAll('.pin-digit-box');

    function openPinModal() {
        if (!pinModal) return;
        pinModal.style.display = 'flex';
        digitInputs.forEach(i => i.value = '');
        if (digitInputs[0]) digitInputs[0].focus();
    }

    function closePinModal() {
        if (!pinModal) return;
        pinModal.style.display = 'none';
    }

    // Auto-advance PIN inputs
    digitInputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if (input.value.length === 1 && index < digitInputs.length - 1) {
                digitInputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && input.value === '' && index > 0) {
                digitInputs[index - 1].focus();
            }
        });
    });

    async function submitPinSetup(e) {
        e.preventDefault();
        let pin = '';
        digitInputs.forEach(i => pin += i.value);

        if (pin.length !== 6) {
            alert('PIN harus terdiri dari 6 digit angka.');
            return;
        }

        const bioChecked = document.getElementById('modalBiometricCheckbox').checked;

        try {
            const res = await fetch('{{ route("creator.pin.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    pin: pin,
                    biometric_enabled: bioChecked ? 1 : 0
                })
            });

            const data = await res.json();
            if (data.success) {
                alert(data.message || 'PIN keamanan berhasil disimpan!');
                sessionStorage.setItem('pwa_unlocked', '1');
                location.reload();
            } else {
                alert(data.message || 'Gagal menyimpan PIN.');
            }
        } catch (err) {
            alert('Terjadi kesalahan koneksi.');
        }
    }

    async function removePinSecurity() {
        if (!confirm('Yakin ingin menonaktifkan PIN keamanan dan sensor biometrik?')) return;

        try {
            const res = await fetch('{{ route("creator.pin.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const data = await res.json();
            if (data.success) {
                alert(data.message);
                sessionStorage.removeItem('pwa_unlocked');
                location.reload();
            }
        } catch (err) {
            alert('Gagal menonaktifkan PIN.');
        }
    }

    // ── Biometric Sensor Toggle ──
    async function handleBiometricToggle(checkbox) {
        @if(!$hasPin)
            alert('Silakan atur 6-digit PIN keamanan terlebih dahulu sebelum mengaktifkan sensor biometrik.');
            checkbox.checked = false;
            openPinModal();
            return;
        @endif

        const enabled = checkbox.checked;

        // Cek dukungan sensor WebAuthn
        if (enabled && window.PublicKeyCredential) {
            try {
                const available = await PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable();
                if (!available) {
                    alert('Sensor biometrik (Fingerprint/Face ID) tidak terdeteksi di perangkat ini.');
                }
            } catch (e) {}
        }

        try {
            const res = await fetch('{{ route("creator.pin.toggle-biometric") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ enabled: enabled ? 1 : 0 })
            });
            const data = await res.json();
            if (!data.success) {
                checkbox.checked = !enabled;
                alert(data.message || 'Gagal mengubah pengaturan biometrik.');
            }
        } catch (err) {
            checkbox.checked = !enabled;
            alert('Kesalahan jaringan.');
        }
    }

    // ── Handle Push Notification Web Push Toggle ──
    async function handlePushToggle(checkbox) {
        if (!('Notification' in window)) {
            alert('Browser ini belum mendukung fitur Web Push Notifications.');
            checkbox.checked = false;
            return;
        }

        if (!checkbox.checked) return;

        try {
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                alert('Izin notifikasi tidak diberikan. Harap aktifkan izin notifikasi di pengaturan HP.');
                checkbox.checked = false;
                return;
            }

            if ('serviceWorker' in navigator) {
                const registration = await navigator.serviceWorker.ready;
                let subscription = await registration.pushManager.getSubscription();
                
                if (!subscription) {
                    subscription = await registration.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlB64ToUint8Array('BEl62iUYgUivxIkv69yViEuiBIa-Ib9-SkvMeAtA3LFgDzkrxZJjSgSnfckjBJuNu3NevSTCxZsioqvKh8Mr-nc')
                    });
                }

                const subJson = subscription.toJSON();
                await fetch('{{ route("creator.push.subscribe") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        endpoint: subJson.endpoint,
                        keys: subJson.keys || {}
                    })
                });

                new Notification('Buyle Creator Studio', {
                    body: 'Notifikasi berhasil diaktifkan! Anda akan menerima update instan saat order masuk.',
                    icon: '/buyle-logo.png'
                });
            }
        } catch (err) {
            console.error('Push notification error:', err);
        }
    }

    function urlB64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }
</script>
@endsection
