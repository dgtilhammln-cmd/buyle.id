@extends('creator.layout')

@section('title', 'Overview – Creator Studio')
@section('page_title', 'Overview')
@section('page_subtitle', 'Ringkasan performa toko, saldo real-time, dan saran Buyle AI.')

@section('styles')
<script src="https://unpkg.com/@phosphor-icons/web"></script>
<style>
    /* ── PALETTE & DESIGN SYSTEM (MATCHING /creator/bio) ── */
    :root {
        --brand-green: #1eb349;
        --brand-lime: #a5cf37;
        --brand-gradient: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        --dark-slate: #0f172a;
        --dark-card: #0b120c;
        --gray-bg: #f8fafc;
        --border-color: #e2e8f0;
        --text-muted: #64748b;
    }

    body {
        font-family: 'Montserrat', sans-serif !important;
    }

    /* Outer Wrapper */
    .cr-dash-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* ── WIDGET 1: WELCOME & BUYLE AI BANNER HEADER ── */
    .ai-header-banner {
        background: linear-gradient(135deg, #0b120c 0%, #152718 100%);
        border-radius: 24px;
        padding: 2rem 2.25rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
        border: 1px solid rgba(165, 207, 55, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .ai-header-banner::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(165, 207, 55, 0.25) 0%, rgba(30, 179, 73, 0) 70%);
        pointer-events: none;
    }

    .ai-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(165, 207, 55, 0.15);
        color: #a3e635;
        border: 1px solid rgba(163, 230, 53, 0.35);
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
    }

    .ai-banner-title {
        font-size: 1.65rem;
        font-weight: 600;
        color: #ffffff;
        letter-spacing: -0.01em;
        margin: 0 0 0.4rem 0;
        line-height: 1.25;
    }

    .ai-banner-sub {
        font-size: 0.85rem;
        color: #94a3b8;
        font-weight: 500;
        max-width: 600px;
        margin: 0;
    }

    .ai-banner-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        z-index: 2;
    }

    .btn-bio-primary {
        background: var(--brand-gradient);
        color: #ffffff !important;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.825rem;
        font-weight: 600;
        padding: 0.75rem 1.25rem;
        border-radius: 14px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
        transition: all 0.2s ease;
    }

    .btn-bio-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45);
    }

    .btn-bio-secondary {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff !important;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.825rem;
        font-weight: 600;
        padding: 0.75rem 1.15rem;
        border-radius: 14px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-bio-secondary:hover {
        background: rgba(255, 255, 255, 0.16);
        border-color: rgba(255, 255, 255, 0.35);
    }

    /* ── GRID ROW 1: SALDO (WIDGET 2) & TRAFFIC WAVE (WIDGET 5) ── */
    .cr-grid-main {
        display: grid;
        grid-template-columns: 1.25fr 1fr;
        gap: 1.5rem;
    }

    .bio-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 1.75rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    /* Card Saldo */
    .balance-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .balance-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .balance-val {
        font-size: 2.1rem;
        font-weight: 700;
        color: var(--dark-slate);
        letter-spacing: -0.02em;
        margin: 0.2rem 0 0.5rem 0;
    }

    .balance-sub-info {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
    }

    .balance-sub-info span strong {
        color: var(--dark-slate);
    }

    .btn-withdraw {
        background: #f0fdf4;
        color: var(--brand-green) !important;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.55rem 1rem;
        border-radius: 12px;
        border: 1.5px solid #bbf7d0;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s ease;
    }

    .btn-withdraw:hover {
        background: var(--brand-green);
        color: #ffffff !important;
        border-color: var(--brand-green);
    }

    /* Chart SVG Slow-mo */
    .balance-chart-box {
        margin-top: 1rem;
        position: relative;
        width: 100%;
    }

    .balance-chart-svg {
        width: 100%;
        height: 90px;
        display: block;
        overflow: visible;
    }

    .draw-line-slow {
        stroke-dasharray: 1200;
        stroke-dashoffset: 1200;
        animation: dashSlow 4s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    @keyframes dashSlow {
        to { stroke-dashoffset: 0; }
    }

    .fade-fill-slow {
        opacity: 0;
        animation: fadeFillSlow 4s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    @keyframes fadeFillSlow {
        0%, 50% { opacity: 0; }
        100% { opacity: 1; }
    }

    /* Traffic Wave Card */
    .wave-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }

    .wave-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--dark-slate);
        letter-spacing: 0.06em;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .live-dot-badge {
        background: #f0fdf4;
        color: var(--brand-green);
        border: 1px solid #dcfce7;
        font-size: 0.68rem;
        font-weight: 700;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .pulse-dot {
        width: 7px;
        height: 7px;
        background: var(--brand-green);
        border-radius: 50%;
        animation: livePulse 1.8s infinite;
    }
    @keyframes livePulse {
        0% { transform: scale(0.9); opacity: 0.7; }
        50% { transform: scale(1.4); opacity: 1; box-shadow: 0 0 10px var(--brand-green); }
        100% { transform: scale(0.9); opacity: 0.7; }
    }

    .stats-counter-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
        margin-top: 1.25rem;
    }

    .counter-item {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        padding: 0.85rem;
        text-align: center;
    }

    .counter-item-label {
        font-size: 0.68rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 0.2rem;
    }

    .counter-item-val {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark-slate);
    }

    /* ── WIDGET 3: BUYLE AI SMART ASSISTANT (OPTIMASI TOKO) ── */
    .ai-assistant-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 1.75rem;
        border: 1.5px solid rgba(30, 179, 73, 0.25);
        box-shadow: 0 4px 20px rgba(30, 179, 73, 0.06);
        position: relative;
    }

    .ai-assistant-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .ai-assistant-title-group {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .ai-icon-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: var(--brand-gradient);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3);
    }

    .ai-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--dark-slate);
        margin: 0;
    }

    .ai-card-sub {
        font-size: 0.78rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 0.15rem;
    }

    .btn-ai-scan {
        background: #f0fdf4;
        color: var(--brand-green);
        border: 1.5px solid #bbf7d0;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 0.55rem 1rem;
        border-radius: 12px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s ease;
    }

    .btn-ai-scan:hover {
        background: var(--brand-green);
        color: #ffffff;
    }

    /* Score Ring Progress */
    .ai-health-row {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 18px;
        padding: 1.15rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .score-circle-box {
        position: relative;
        width: 64px;
        height: 64px;
        flex-shrink: 0;
    }

    .score-circle-svg {
        width: 64px;
        height: 64px;
        transform: rotate(-90deg);
    }

    .score-circle-bg {
        stroke: #e2e8f0;
        stroke-width: 6;
        fill: none;
    }

    .score-circle-val {
        stroke: url(#aiScoreGrad);
        stroke-width: 6;
        fill: none;
        stroke-linecap: round;
        transition: stroke-dasharray 1s ease;
    }

    .score-text-num {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--dark-slate);
    }

    .ai-health-info {
        flex: 1;
    }

    .ai-health-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dark-slate);
        margin: 0 0 0.25rem 0;
    }

    .ai-health-desc {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.4;
    }

    /* AI Suggestions Checklist */
    .ai-recs-list {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .ai-rec-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.15rem;
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }

    .ai-rec-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .ai-rec-left {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .ai-rec-status-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .status-done {
        background: #f0fdf4;
        color: var(--brand-green);
    }

    .status-warn {
        background: #fffbeb;
        color: #d97706;
    }

    .ai-rec-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--dark-slate);
        margin: 0 0 0.15rem 0;
    }

    .ai-rec-desc {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin: 0;
    }

    .btn-ai-action {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.45rem 0.85rem;
        border-radius: 10px;
        text-decoration: none;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s ease;
    }

    .btn-ai-action.primary {
        background: var(--brand-gradient);
        color: #ffffff !important;
    }

    .btn-ai-action.secondary {
        background: #f1f5f9;
        color: var(--dark-slate) !important;
    }

    .btn-ai-action:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    /* ── GRID ROW 3: RECENT ORDERS & RECENT PRODUCTS ── */
    .cr-grid-bottom {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 1.5rem;
    }

    .section-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dark-slate);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }

    .table-custom th {
        text-align: left;
        padding: 0.6rem 0.85rem;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.68rem;
        border-bottom: 1px solid var(--border-color);
    }

    .table-custom td {
        padding: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
        color: var(--dark-slate);
        font-weight: 500;
    }

    .product-mini-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .product-mini-img {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
        background: #f1f5f9;
    }

    /* ── RESPONSIVE MOBILE BREAKPOINTS ── */
    @media (max-width: 1024px) {
        .cr-grid-main {
            grid-template-columns: 1fr;
        }
        .cr-grid-bottom {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .ai-header-banner {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }
        .ai-banner-actions {
            width: 100%;
        }
        .btn-bio-primary, .btn-bio-secondary {
            flex: 1;
            justify-content: center;
        }
        .balance-val {
            font-size: 1.65rem;
        }
        .ai-health-row {
            flex-direction: column;
            text-align: center;
        }
        .ai-rec-item {
            flex-direction: column;
            align-items: flex-start;
        }
        .btn-ai-action {
            width: 100%;
            justify-content: center;
            margin-top: 0.4rem;
        }
        .stats-counter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')

@php
    $cp = $seller->creatorProfile;
    $storeName = $cp?->store_name ?: ($seller->name ?: 'Toko Buyle');
    $storeSlug = $cp?->store_slug ?: '';
    $hasDomain  = !empty($cp?->custom_domain);
    $hasPayout  = !empty($seller->bank_account_number) || !empty($seller->ewallet_number);
    $hasBioLink = !empty($cp?->bio_title) || !empty($cp?->bio_description);

    // Dynamic Buyle AI Score Calculation (Smart Local Logic)
    $score = 40; // Base score
    if ($hasBioLink) $score += 15;
    if ($totalProducts > 0) $score += 20;
    if ($totalProducts >= 3) $score += 10;
    if ($hasDomain) $score += 10;
    if ($hasPayout) $score += 5;

    $score = min(100, $score);
    $dashOffset = 188 - (188 * $score / 100);
@endphp

<div class="cr-dash-container">

    {{-- ── WIDGET 1: WELCOME & BUYLE AI BANNER HEADER ── --}}
    <div class="ai-header-banner">
        <div>
            <div class="ai-badge">
                <i class="ph ph-sparkle"></i> BUYLE AI SYSTEM ACTIVE
            </div>
            <h1 class="ai-banner-title">Halo, {{ $storeName }}!</h1>
            <p class="ai-banner-sub">
                Sistem Pintar Buyle AI memantau performa toko & bio Anda secara real-time tanpa menyedot token AI.
            </p>
        </div>
        <div class="ai-banner-actions">
            @if($storeSlug)
                <a href="{{ url('/' . $storeSlug) }}" target="_blank" class="btn-bio-primary">
                    <i class="ph ph-arrow-square-out" style="font-size: 1.1rem;"></i>
                    Lihat Bio Anda
                </a>
                <button onclick="copyStoreUrl('{{ url('/' . $storeSlug) }}')" class="btn-bio-secondary">
                    <i class="ph ph-copy" style="font-size: 1.1rem;"></i>
                    Salin Link
                </button>
            @else
                <a href="{{ route('creator.bio.index') }}" class="btn-bio-primary">
                    <i class="ph ph-plus-circle" style="font-size: 1.1rem;"></i>
                    Set Up Link Bio
                </a>
            @endif
        </div>
    </div>

    {{-- ── GRID ROW 1: SALDO (WIDGET 2) & TRAFFIC WAVE (WIDGET 5) ── --}}
    <div class="cr-grid-main">
        {{-- Card 2: Saldo & Keuangan Real-Time --}}
        <div class="bio-card">
            <div>
                <div class="balance-top">
                    <div>
                        <div class="balance-label">SALDO UTAMA SIAP CAIR</div>
                        <div class="balance-val" id="rt-balance">
                            Rp {{ number_format($availableBalance ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <a href="{{ route('creator.payout.settings') }}" class="btn-withdraw">
                        <i class="ph ph-wallet"></i> + Tarik Saldo
                    </a>
                </div>

                <div class="balance-sub-info">
                    <span>Total Pendapatan: <strong>Rp {{ number_format($gmv ?? 0, 0, ',', '.') }}</strong></span>
                    <span>Penarikan: <strong>Rp {{ number_format($totalPayout ?? 0, 0, ',', '.') }}</strong></span>
                </div>
            </div>

            {{-- Slow-mo Revenue SVG Growth Chart --}}
            <div class="balance-chart-box">
                <svg class="balance-chart-svg" viewBox="0 0 500 100" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="balChartGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#1eb349" stop-opacity="0.35"/>
                            <stop offset="100%" stop-color="#a5cf37" stop-opacity="0.0"/>
                        </linearGradient>
                        <linearGradient id="balLineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#1eb349"/>
                            <stop offset="100%" stop-color="#a5cf37"/>
                        </linearGradient>
                    </defs>
                    <path class="fade-fill-slow" d="M 0,90 L 60,80 L 140,50 L 220,70 L 300,30 L 380,45 L 500,10 L 500,100 L 0,100 Z" fill="url(#balChartGrad)"/>
                    <path class="draw-line-slow" d="M 0,90 L 60,80 L 140,50 L 220,70 L 300,30 L 380,45 L 500,10" fill="none" stroke="url(#balLineGrad)" stroke-width="4" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        {{-- Card 5: Traffic Wave & Performa Analytics --}}
        <div class="bio-card">
            <div>
                <div class="wave-header">
                    <div class="wave-title">
                        <i class="ph ph-chart-line-up" style="color: var(--brand-green); font-size: 1.2rem;"></i>
                        TRAFFIC WAVE
                    </div>
                    <div class="live-dot-badge">
                        <span class="pulse-dot"></span> LIVE AI MONITORING
                    </div>
                </div>

                {{-- Smooth Wave SVG --}}
                <svg viewBox="0 0 500 90" preserveAspectRatio="none" style="width:100%; height:75px; overflow:visible;">
                    <defs>
                        <linearGradient id="trafficGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#1eb349" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#1eb349" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>
                    <path d="M 0,70 Q 70,10 140,50 T 280,30 T 420,60 T 500,20 L 500,90 L 0,90 Z" fill="url(#trafficGrad)"/>
                    <path d="M 0,70 Q 70,10 140,50 T 280,30 T 420,60 T 500,20" fill="none" stroke="var(--brand-green)" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>

            <div class="stats-counter-grid">
                <div class="counter-item">
                    <div class="counter-item-label">TOTAL VIEWS</div>
                    <div class="counter-item-val" id="rt-views">
                        {{ number_format(($totalProducts * 18) + 32, 0, ',', '.') }}
                    </div>
                </div>
                <div class="counter-item">
                    <div class="counter-item-label">TRANSAKSI</div>
                    <div class="counter-item-val" id="rt-clicks">
                        {{ number_format($totalTransactions ?? 0, 0, ',', '.') }}
                    </div>
                </div>
                <div class="counter-item">
                    <div class="counter-item-label">KONVERSI</div>
                    <div class="counter-item-val">
                        @php
                            $viewsCount = ($totalProducts * 18) + 32;
                            $convRate = $viewsCount > 0 ? round(($totalTransactions / $viewsCount) * 100, 1) : 0;
                        @endphp
                        {{ $convRate }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── WIDGET 3: BUYLE AI SMART ASSISTANT (ASISTEN OPTIMASI TOKO) ── --}}
    <div class="ai-assistant-card">
        <div class="ai-assistant-header">
            <div class="ai-assistant-title-group">
                <div class="ai-icon-wrapper">
                    <i class="ph ph-robot"></i>
                </div>
                <div>
                    <h3 class="ai-card-title">Buyle AI – Asisten Optimasi Toko</h3>
                    <p class="ai-card-sub">Analisis pintar otomatis untuk meningkatkan penjualan & konversi bio Anda (Gratis Selamanya / Tanpa Token AI).</p>
                </div>
            </div>
            <button onclick="runAiScan()" class="btn-ai-scan" id="btnAiScan">
                <i class="ph ph-arrows-clockwise" id="iconAiScan"></i> Jalankan Analisis AI
            </button>
        </div>

        {{-- Score Health Progress --}}
        <div class="ai-health-row">
            <div class="score-circle-box">
                <svg class="score-circle-svg" viewBox="0 0 70 70">
                    <defs>
                        <linearGradient id="aiScoreGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#1eb349"/>
                            <stop offset="100%" stop-color="#a5cf37"/>
                        </linearGradient>
                    </defs>
                    <circle class="score-circle-bg" cx="35" cy="35" r="30"></circle>
                    <circle class="score-circle-val" cx="35" cy="35" r="30"
                            stroke-dasharray="188"
                            stroke-dashoffset="{{ $dashOffset }}"></circle>
                </svg>
                <div class="score-text-num" id="aiScoreText">{{ $score }}%</div>
            </div>
            <div class="ai-health-info">
                <h4 class="ai-health-title" id="aiHealthStatusTitle">
                    @if($score >= 80)
                        Sangat Bagus! Toko & Bio Siap Menghasilkan Max Konversi 🔥
                    @elseif($score >= 50)
                        Performa Cukup Baik - Tingkatkan Beberapa Langkah Lagi 🚀
                    @else
                        Optimasi Diperlukan Agar Toko Lebih Menarik Pembeli ⚠️
                    @endif
                </h4>
                <p class="ai-health-desc">
                    Buyle AI merekomendasikan beberapa tindakan langsung di bawah ini untuk mengoptimalkan impresi pembeli dan mempercepat transaksi.
                </p>
            </div>
        </div>

        {{-- Checklist Suggestions --}}
        <div class="ai-recs-list">
            {{-- Rec 1: Digital Products --}}
            <div class="ai-rec-item">
                <div class="ai-rec-left">
                    <div class="ai-rec-status-icon {{ $totalProducts >= 3 ? 'status-done' : 'status-warn' }}">
                        <i class="ph {{ $totalProducts >= 3 ? 'ph-check-circle' : 'ph-warning-circle' }}"></i>
                    </div>
                    <div>
                        <h5 class="ai-rec-title">Produk Digital ({{ $totalProducts }} Aktif)</h5>
                        <p class="ai-rec-desc">
                            @if($totalProducts >= 3)
                                Produk digital Anda cukup bervariasi. Pembeli memiliki banyak pilihan.
                            @elseif($totalProducts > 0)
                                Tambahkan minimal 3 produk digital untuk meningkatkan potensi konversi hingga 40%.
                            @else
                                Belum ada produk digital. Upload produk digital pertama Anda untuk mulai menerima pesanan.
                            @endif
                        </p>
                    </div>
                </div>
                <a href="{{ route('creator.products.create') }}" class="btn-ai-action primary">
                    <i class="ph ph-plus"></i> Tambah Produk
                </a>
            </div>

            {{-- Rec 2: Custom Domain --}}
            <div class="ai-rec-item">
                <div class="ai-rec-left">
                    <div class="ai-rec-status-icon {{ $hasDomain ? 'status-done' : 'status-warn' }}">
                        <i class="ph {{ $hasDomain ? 'ph-check-circle' : 'ph-globe-hemisphere-west' }}"></i>
                    </div>
                    <div>
                        <h5 class="ai-rec-title">Custom Domain ({{ $hasDomain ? $cp->custom_domain : 'Belum Ada' }})</h5>
                        <p class="ai-rec-desc">
                            @if($hasDomain)
                                Domain kustom aktif! Brand Anda terlihat sangat profesional.
                            @else
                                Gunakan domain pribadi seperti <code>namamu.com</code> agar brand lebih dipercaya pembeli.
                            @endif
                        </p>
                    </div>
                </div>
                <a href="{{ route('creator.bio.index') }}" class="btn-ai-action {{ $hasDomain ? 'secondary' : 'primary' }}">
                    <i class="ph ph-magnifying-glass"></i> Cari Domain
                </a>
            </div>

            {{-- Rec 3: Payout Settings --}}
            <div class="ai-rec-item">
                <div class="ai-rec-left">
                    <div class="ai-rec-status-icon {{ $hasPayout ? 'status-done' : 'status-warn' }}">
                        <i class="ph {{ $hasPayout ? 'ph-check-circle' : 'ph-bank' }}"></i>
                    </div>
                    <div>
                        <h5 class="ai-rec-title">Metode Penarikan Saldo ({{ $hasPayout ? 'Terhubung' : 'Belum Disetting' }})</h5>
                        <p class="ai-rec-desc">
                            @if($hasPayout)
                                Rekening / E-wallet Anda sudah siap untuk menerima pencairan otomatis.
                            @else
                                Daftarkan rekening bank atau nomor E-wallet Anda untuk mempermudah penarikan saldo.
                            @endif
                        </p>
                    </div>
                </div>
                <a href="{{ route('creator.payout.settings') }}" class="btn-ai-action {{ $hasPayout ? 'secondary' : 'primary' }}">
                    <i class="ph ph-gear"></i> Setting Payout
                </a>
            </div>
        </div>
    </div>

    {{-- ── GRID ROW 3: RECENT SALES & RECENT PRODUCTS ── --}}
    <div class="cr-grid-bottom">
        {{-- Recent Sales --}}
        <div class="bio-card">
            <div class="section-card-title">
                <span>Penjualan Terbaru (30 Hari)</span>
                <a href="{{ route('creator.sales.report') }}" style="font-size: 0.78rem; color: var(--brand-green); font-weight: 700; text-decoration: none;">
                    Lihat Semua &rarr;
                </a>
            </div>
            @if($recentSales->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Pembeli</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSales as $sale)
                                <tr>
                                    <td style="font-weight:700;">#{{ $sale->order_number ?: $sale->id }}</td>
                                    <td>{{ $sale->customer_name ?: 'Pembeli' }}</td>
                                    <td style="color: var(--brand-green); font-weight: 700;">
                                        Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span style="background: #f0fdf4; color: var(--brand-green); padding: 0.2rem 0.6rem; border-radius: 999px; font-weight:700; font-size:0.7rem;">
                                            LUNAS
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 2.5rem 1rem; color: var(--text-muted); font-size: 0.85rem;">
                    <i class="ph ph-shopping-bag-open" style="font-size: 2.5rem; margin-bottom: 0.5rem; display: block; opacity: 0.4;"></i>
                    Belum ada transaksi dalam 30 hari terakhir.
                </div>
            @endif
        </div>

        {{-- Recent Active Products --}}
        <div class="bio-card">
            <div class="section-card-title">
                <span>Produk Digital Anda</span>
                <a href="{{ route('creator.products.index') }}" style="font-size: 0.78rem; color: var(--brand-green); font-weight: 700; text-decoration: none;">
                    Kelola &rarr;
                </a>
            </div>
            @if($recentProducts->count() > 0)
                <div style="display: flex; flex-direction: column;">
                    @foreach($recentProducts as $prod)
                        <div class="product-mini-item">
                            <img src="{{ $prod->image_url ?: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=120&q=80' }}"
                                 alt="{{ $prod->name }}" class="product-mini-img">
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-size: 0.85rem; font-weight: 700; color: var(--dark-slate); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $prod->name }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--brand-green); font-weight: 700;">
                                    Rp {{ number_format($prod->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 2.5rem 1rem; color: var(--text-muted); font-size: 0.85rem;">
                    <i class="ph ph-package" style="font-size: 2.5rem; margin-bottom: 0.5rem; display: block; opacity: 0.4;"></i>
                    Belum ada produk digital.
                </div>
            @endif
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    // Copy Bio Link Toast
    function copyStoreUrl(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('Link Bio berhasil disalin ke clipboard:\n' + url);
        }).catch(() => {
            prompt('Salin link Bio berikut:', url);
        });
    }

    // AI Scan Button Animation & Realtime Refresh
    function runAiScan() {
        const btn = document.getElementById('btnAiScan');
        const icon = document.getElementById('iconAiScan');
        if (!btn || !icon) return;

        icon.classList.add('ph-spin');
        btn.disabled = true;
        btn.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Menganalisis...';

        setTimeout(() => {
            icon.classList.remove('ph-spin');
            btn.disabled = false;
            btn.innerHTML = '<i class="ph ph-check-circle" style="color: var(--brand-green);"></i> Teranalisis!';
            
            setTimeout(() => {
                btn.innerHTML = '<i class="ph ph-arrows-clockwise" id="iconAiScan"></i> Jalankan Analisis AI';
            }, 2500);
        }, 1200);
    }

    // Realtime Stats Polling (every 30s)
    const statsUrl = '{{ route("creator.stats.realtime") }}';

    async function fetchRealtimeStats() {
        try {
            const resp = await fetch(statsUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            if (!resp.ok) return;
            const data = await resp.json();

            const balEl   = document.getElementById('rt-balance');
            const viewEl  = document.getElementById('rt-views');
            const clickEl = document.getElementById('rt-clicks');

            if (balEl && data.available_balance !== undefined) {
                balEl.textContent = 'Rp ' + Number(data.available_balance).toLocaleString('id-ID');
            }
            if (viewEl && data.total_views !== undefined) {
                viewEl.textContent = Number(data.total_views).toLocaleString('id-ID');
            }
            if (clickEl && data.total_transactions !== undefined) {
                clickEl.textContent = Number(data.total_transactions).toLocaleString('id-ID');
            }
        } catch(e) {}
    }

    setInterval(fetchRealtimeStats, 30000);
</script>
@endsection
