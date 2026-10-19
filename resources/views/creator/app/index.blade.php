@extends('creator.layout')

@section('title', 'Aplikasi · Pusat Ekosistem Buyle')
@section('page_title', 'Aplikasi')
@section('page_subtitle', 'Pusat integrasi aplikasi resmi Buyle untuk mengelola seluruh aspek bisnis digital Anda.')

@section('styles')
<style>
    .app-hub-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
        width: 100%;
        font-family: 'Montserrat', sans-serif;
    }

    /* ── Banner Pengantar (Signature Buyle Green Theme) ───────── */
    .app-intro-card {
        background: linear-gradient(135deg, #1eb349 0%, #179e3f 50%, #15803d 100%);
        border-radius: 20px;
        padding: 1.85rem 2.25rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 10px 30px rgba(30, 179, 73, 0.22);
    }

    .app-intro-card::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -80px;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(165, 207, 55, 0.45) 0%, rgba(165, 207, 55, 0) 70%);
        pointer-events: none;
    }

    .app-intro-content {
        position: relative;
        z-index: 2;
        max-width: 760px;
    }

    .app-intro-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        border: 1.5px solid rgba(255, 255, 255, 0.35);
        color: #ffffff;
        font-size: 0.74rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 5px 14px;
        border-radius: 999px;
        margin-bottom: 0.95rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .app-intro-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 0.6rem;
        line-height: 1.3;
        letter-spacing: -0.01em;
    }

    .app-intro-desc {
        font-size: 0.92rem;
        color: #f0fdf4;
        line-height: 1.6;
        margin: 0;
        font-weight: 500;
    }

    /* ── App Cards Grid ───────────────────────────────────────── */
    .apps-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    @media (max-width: 1024px) {
        .apps-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .apps-grid {
            grid-template-columns: 1fr;
        }
    }

    .app-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .app-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.07);
        border-color: #cbd5e1;
    }

    .app-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        gap: 0.5rem;
    }

    .app-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
    }

    .app-status-dot {
        width: 7px;
        height: 7px;
        background: #1eb349;
        border-radius: 50%;
        box-shadow: 0 0 0 2px rgba(30, 179, 73, 0.25);
    }

    .app-category-badge {
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 170px;
    }

    /* ── Logo & Icon Box ──────────────────────────────────────── */
    .app-logo-area {
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
    }

    .app-logo-box {
        width: 72px;
        height: 72px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        flex-shrink: 0;
        transition: transform 0.2s ease;
    }

    .app-card:hover .app-logo-box {
        transform: scale(1.04);
    }

    .app-logo-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
    }

    .app-logo-fallback {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ── Card Content ─────────────────────────────────────────── */
    .app-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 0.4rem;
        letter-spacing: -0.01em;
    }

    .app-desc {
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.6;
        margin: 0 0 1.5rem;
        flex: 1;
    }

    /* ── Action Button (Matching Buyle Signature Pill Button) ──── */
    .btn-launch-app {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        width: 100%;
        padding: 0.85rem 1.4rem;
        background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        color: #ffffff !important;
        font-size: 0.88rem;
        font-weight: 800;
        border-radius: 999px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-sizing: border-box;
        box-shadow: 0 4px 16px rgba(30, 179, 73, 0.35);
        font-family: 'Montserrat', sans-serif;
    }

    .btn-launch-app:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(30, 179, 73, 0.45);
        filter: brightness(1.04);
        color: #ffffff !important;
    }

    .btn-launch-app svg {
        transition: transform 0.2s ease;
    }

    .btn-launch-app:hover svg {
        transform: translateX(4px);
    }

    /* ── Footer Info Box ──────────────────────────────────────── */
    .app-footer-note {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .app-footer-note-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .app-footer-note-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .app-footer-note-text {
        font-size: 0.82rem;
        color: #475569;
        margin: 0;
        line-height: 1.5;
    }

    .app-footer-note-text strong {
        color: #0f172a;
    }
    /* ── Feature Chips Strip ─────────────────────────────────── */
    .app-features {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin: 0 0 1.4rem;
    }

    .app-feature-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        border: 1.5px solid;
        white-space: nowrap;
        letter-spacing: 0.01em;
        transition: transform 0.15s ease;
    }

    .app-feature-chip:hover {
        transform: translateY(-1px);
    }

    .app-feature-chip svg {
        flex-shrink: 0;
    }

</style>
@endsection

@section('content')
<div class="app-hub-wrapper">

    {{-- Intro Banner --}}
    <div class="app-intro-card">
        <div class="app-intro-content">
            <div class="app-intro-tag">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" />
                    <rect x="14" y="3" width="7" height="7" rx="1.5" />
                    <rect x="14" y="14" width="7" height="7" rx="1.5" />
                    <rect x="3" y="14" width="7" height="7" rx="1.5" />
                </svg>
                Pusat Aplikasi Terintegrasi
            </div>
            <h2 class="app-intro-title">Kelola Aplikasi Bisnis Anda dalam Satu Ekosistem</h2>
            <p class="app-intro-desc">
                Pilih aplikasi di bawah ini untuk memulai operasional kasir fisik, mendesain landing page etalase toko, atau memvalidasi tiket masuk event Anda.
            </p>
        </div>
    </div>

    {{-- App Cards Grid --}}
    <div class="apps-grid">
        @foreach($apps as $app)
            <div class="app-card">
                <div class="app-card-top">
                    <div class="app-status-badge">
                        <span class="app-status-dot"></span>
                        Aktif
                    </div>
                    <div class="app-category-badge" title="{{ $app['badge'] }}">
                        {{ $app['badge'] }}
                    </div>
                </div>

                {{-- Logo / Placeholder Area --}}
                <div class="app-logo-area">
                    @php
                        $rawLogo = $app['logo'] ?? null;
                        $logoUrl = null;
                        if (!empty($rawLogo)) {
                            if (\Illuminate\Support\Str::startsWith($rawLogo, ['http://', 'https://'])) {
                                $logoUrl = $rawLogo;
                            } elseif (\Illuminate\Support\Str::startsWith($rawLogo, 'storage/')) {
                                $logoUrl = asset($rawLogo);
                            } else {
                                $logoUrl = asset('storage/' . $rawLogo);
                            }
                        }
                    @endphp
                    <div class="app-logo-box" style="border-color: {{ $app['border_color'] }}; background: {{ !empty($logoUrl) ? '#ffffff' : $app['bg_gradient'] }};">
                        @if(!empty($logoUrl))
                            <img src="{{ $logoUrl }}" alt="{{ $app['name'] }}" class="app-logo-img">
                        @else
                            {{-- High End SVG Placeholder Icon --}}
                            <div class="app-logo-fallback" style="color: {{ $app['theme_color'] }};">
                                @if($app['icon_type'] === 'globe')
                                    {{-- Web Builder Icon --}}
                                    <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="2" y1="12" x2="22" y2="12" />
                                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                    </svg>
                                @elseif($app['icon_type'] === 'pos')
                                    {{-- POS Icon --}}
                                    <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="4" y="3" width="16" height="18" rx="2" />
                                        <line x1="8" y1="7" x2="16" y2="7" />
                                        <line x1="8" y1="11" x2="10" y2="11" />
                                        <line x1="14" y1="11" x2="16" y2="11" />
                                        <line x1="8" y1="15" x2="10" y2="15" />
                                        <line x1="14" y1="15" x2="16" y2="15" />
                                    </svg>
                                @elseif($app['icon_type'] === 'instagram')
                                    {{-- Instagram / Automation Reply Icon --}}
                                    <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                                    </svg>
                                @else
                                    {{-- Ticket Icon --}}
                                    <svg width="34" height="34" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" />
                                        <line x1="13" y1="5" x2="13" y2="19" stroke-dasharray="2 2" />
                                    </svg>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- App Info --}}
                <h3 class="app-title">{{ $app['name'] }}</h3>
                <p class="app-desc">{{ $app['description'] }}</p>

                {{-- Feature Chips --}}
                @if(!empty($app['features']))
                <div class="app-features">
                    @foreach($app['features'] as $feat)
                    <span class="app-feature-chip" style="color:{{ $app['theme_color'] }}; border-color:{{ $app['border_color'] }}; background:{{ $app['bg_gradient'] }};">
                        @php $ic = $feat['icon'] ?? ''; @endphp
                        @if($ic === 'link')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        @elseif($ic === 'globe')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                        @elseif($ic === 'shop')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        @elseif($ic === 'percent')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                        @elseif($ic === 'tag')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                        @elseif($ic === 'zap')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        @elseif($ic === 'calc')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="12" x2="10" y2="12"/><line x1="14" y1="12" x2="16" y2="12"/><line x1="8" y1="16" x2="10" y2="16"/><line x1="14" y1="16" x2="16" y2="16"/></svg>
                        @elseif($ic === 'print')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        @elseif($ic === 'qris')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h.01M14 17h3M17 14v7M20 14h.01"/></svg>
                        @elseif($ic === 'mail')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        @elseif($ic === 'report')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        @elseif($ic === 'wallet')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"/><path d="M4 6v12a2 2 0 0 0 2 2h14v-4"/><path d="M18 12a2 2 0 0 0 0 4h4v-4z"/></svg>
                        @elseif($ic === 'scan')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><line x1="3" y1="12" x2="21" y2="12"/></svg>
                        @elseif($ic === 'gate')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        @elseif($ic === 'users')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        @elseif($ic === 'chart')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        @elseif($ic === 'pin')
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/><circle cx="12" cy="10" r="3"/></svg>
                        @else
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="2"/></svg>
                        @endif
                        {{ $feat['label'] }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- Launch Action --}}
                <a href="{{ $app['route'] }}" class="btn-launch-app">
                    <span>Buka {{ $app['name'] }}</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Note / Info Footer --}}
    <div class="app-footer-note">
        <div class="app-footer-note-left">
            <div class="app-footer-note-icon">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="16" x2="12" y2="12" />
                    <line x1="12" y1="8" x2="12.01" y2="8" />
                </svg>
            </div>
            <p class="app-footer-note-text">
                <strong>Navigasi Cepat:</strong> Setiap aplikasi yang dibuka telah dilengkapi tombol <em>Kembali ke Aplikasi</em> di bilah atas untuk memudahkan Anda berpindah modul kapan saja.
            </p>
        </div>
    </div>

</div>
@endsection
