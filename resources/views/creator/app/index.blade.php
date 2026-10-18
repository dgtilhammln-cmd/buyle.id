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

    /* ── Banner Pengantar ────────────────────────────────────── */
    .app-intro-card {
        background: linear-gradient(135deg, #0b120c 0%, #152217 60%, #1b3120 100%);
        border-radius: 20px;
        padding: 1.75rem 2rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .app-intro-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(30, 179, 73, 0.28) 0%, rgba(30, 179, 73, 0) 70%);
        pointer-events: none;
    }

    .app-intro-content {
        position: relative;
        z-index: 2;
        max-width: 720px;
    }

    .app-intro-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(30, 179, 73, 0.18);
        border: 1px solid rgba(30, 179, 73, 0.45);
        color: #86efac;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 4px 12px;
        border-radius: 999px;
        margin-bottom: 0.85rem;
    }

    .app-intro-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 0.5rem;
        line-height: 1.3;
    }

    .app-intro-desc {
        font-size: 0.88rem;
        color: #cbd5e1;
        line-height: 1.6;
        margin: 0;
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

    /* ── Action Button ────────────────────────────────────────── */
    .btn-launch-app {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.85rem 1.25rem;
        background: #0f172a;
        color: #ffffff;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 12px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .btn-launch-app:hover {
        background: linear-gradient(135deg, #1eb349 0%, #15803d 100%);
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(30, 179, 73, 0.35);
        transform: translateY(-1px);
    }

    .btn-launch-app svg {
        transition: transform 0.2s ease;
    }

    .btn-launch-app:hover svg {
        transform: translateX(3px);
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
                    <div class="app-logo-box" style="border-color: {{ $app['border_color'] }}; background: {{ $app['bg_gradient'] }};">
                        @if(!empty($app['logo']))
                            <img src="{{ asset('storage/' . $app['logo']) }}" alt="{{ $app['name'] }}" class="app-logo-img">
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
