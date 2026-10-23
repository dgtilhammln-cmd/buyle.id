@extends('creator.layout')

@section('title', 'Aplikasi · Pusat Ekosistem Buyle')
@section('page_title', 'Aplikasi')
@section('page_subtitle', 'Pusat integrasi aplikasi Buyle untuk mengelola seluruh aspek bisnis digital Anda.')

@section('styles')
<style>
    .app-hub-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        width: 100%;
        font-family: 'Montserrat', sans-serif;
    }

    /* Header bar */
    .app-hub-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
    }

    .app-hub-header-info h2 {
        font-size: 0.98rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 0.18rem;
        letter-spacing: -0.01em;
    }

    .app-hub-header-info p {
        font-size: 0.78rem;
        color: #94a3b8;
        margin: 0;
    }

    .app-hub-total-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        white-space: nowrap;
    }

    /* Grid - 4 cols desktop */
    .apps-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    @media (max-width: 900px) {
        .apps-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 540px) {
        .apps-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    /* Card */
    .app-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 1.1rem 1rem 1rem;
        display: flex;
        flex-direction: column;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .app-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }

    .app-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.85rem;
    }

    /* Icon box */
    .app-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #64748b;
    }

    .app-icon-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 6px;
        border-radius: 10px;
    }

    .app-status-dot {
        width: 7px;
        height: 7px;
        background: #94a3b8;
        border-radius: 50%;
    }

    /* Card body */
    .app-card-title {
        font-size: 0.88rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 0.3rem;
        letter-spacing: -0.01em;
        line-height: 1.25;
    }

    .app-card-badge {
        font-size: 0.65rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
        display: block;
    }

    .app-card-desc {
        font-size: 0.76rem;
        color: #94a3b8;
        line-height: 1.55;
        margin: 0 0 1rem;
        flex: 1;
    }

    /* Launch button */
    .btn-launch-app {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        width: 100%;
        padding: 0.58rem 0.75rem;
        background: #f1f5f9;
        color: #334155;
        font-size: 0.78rem;
        font-weight: 700;
        border-radius: 8px;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        box-sizing: border-box;
        font-family: 'Montserrat', sans-serif;
        margin-top: auto;
    }

    .btn-launch-app:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .btn-launch-app svg {
        transition: transform 0.15s ease;
        flex-shrink: 0;
        color: #64748b;
    }

    .btn-launch-app:hover svg {
        transform: translateX(3px);
    }

    /* Footer note */
    .app-footer-note {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.85rem 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .app-footer-note-icon {
        color: #94a3b8;
        flex-shrink: 0;
    }

    .app-footer-note-text {
        font-size: 0.76rem;
        color: #94a3b8;
        margin: 0;
        line-height: 1.5;
    }

    .app-footer-note-text strong {
        color: #64748b;
    }
</style>
@endsection

@section('content')
<div class="app-hub-wrapper">

    {{-- Header Bar --}}
    <div class="app-hub-header">
        <div class="app-hub-header-info">
            <h2>Pusat Aplikasi</h2>
            <p>Kelola seluruh modul bisnis dari satu tempat</p>
        </div>
        <span class="app-hub-total-badge">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7" rx="1.5" />
                <rect x="14" y="3" width="7" height="7" rx="1.5" />
                <rect x="14" y="14" width="7" height="7" rx="1.5" />
                <rect x="3" y="14" width="7" height="7" rx="1.5" />
            </svg>
            {{ count($apps) }} Aplikasi Aktif
        </span>
    </div>

    {{-- App Cards Grid --}}
    <div class="apps-grid">
        @foreach($apps as $app)
            <div class="app-card">
                {{-- Card head: icon + status dot --}}
                <div class="app-card-head">
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
                    <div class="app-icon-box">
                        @if(!empty($logoUrl))
                            <img src="{{ $logoUrl }}" alt="{{ $app['name'] }}" class="app-icon-img">
                        @elseif($app['icon_type'] === 'globe')
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="2" y1="12" x2="22" y2="12" />
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                            </svg>
                        @elseif($app['icon_type'] === 'pos')
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="4" y="3" width="16" height="18" rx="2" />
                                <line x1="8" y1="7" x2="16" y2="7" />
                                <line x1="8" y1="11" x2="10" y2="11" />
                                <line x1="14" y1="11" x2="16" y2="11" />
                                <line x1="8" y1="15" x2="10" y2="15" />
                                <line x1="14" y1="15" x2="16" y2="15" />
                            </svg>
                        @elseif($app['icon_type'] === 'instagram')
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                            </svg>
                        @else
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" />
                                <line x1="13" y1="5" x2="13" y2="19" stroke-dasharray="2 2" />
                            </svg>
                        @endif
                    </div>
                    <span class="app-status-dot" title="Aktif"></span>
                </div>

                {{-- App Info --}}
                <span class="app-card-badge">{{ $app['badge'] }}</span>
                <h3 class="app-card-title">{{ $app['name'] }}</h3>
                <p class="app-card-desc">{{ $app['description'] }}</p>

                {{-- Launch Button --}}
                <a href="{{ $app['route'] }}" class="btn-launch-app">
                    <span>Buka</span>
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Footer Note --}}
    <div class="app-footer-note">
        <div class="app-footer-note-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="16" x2="12" y2="12" />
                <line x1="12" y1="8" x2="12.01" y2="8" />
            </svg>
        </div>
        <p class="app-footer-note-text">
            <strong>Navigasi Cepat:</strong> Setiap aplikasi dilengkapi tombol <em>Kembali ke Aplikasi</em> di bilah atas untuk berpindah modul kapan saja.
        </p>
    </div>

</div>
@endsection
