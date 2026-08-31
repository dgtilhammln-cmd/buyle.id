@extends('creator.layout')

@section('title', 'Overview – Creator Studio')

@section('styles')
<style>
    /* ── DASHBOARD GRID LAYOUT ── */
    .cr-dash-header {
        margin-bottom: 2rem;
    }
    .cr-dash-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #0b120c;
        letter-spacing: -0.01em;
        margin: 0;
        line-height: 1.2;
    }
    .cr-dash-sub {
        font-size: 0.875rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 0.35rem;
    }

    /* Row 1 Grid: 3 Columns */
    .cr-grid-top {
        display: grid;
        grid-template-columns: 1.8fr 1.15fr 0.85fr;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    /* Card 1: Black Welcome Banner */
    .card-banner-black {
        background: #0b120c;
        border-radius: 28px;
        padding: 2.25rem 2.25rem;
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 190px;
        position: relative;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .badge-member {
        background: #a3e635;
        color: #0b120c;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        padding: 0.3rem 0.85rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-transform: uppercase;
        align-self: flex-start;
        margin-bottom: 1.25rem;
    }
    .banner-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.01em;
        line-height: 1.2;
    }
    .banner-sub {
        font-size: 0.82rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 0.45rem;
    }

    /* Card 2: Budget Card (Modern White with Chart) */
    .card-budget-modern {
        background: #ffffff;
        border-radius: 28px;
        padding: 1.5rem 0 0 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 190px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
    }
    .budget-content {
        padding: 0 1.75rem;
        position: relative;
        z-index: 10;
    }
    .budget-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 0.35rem;
    }
    .budget-val {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        margin-bottom: 0.5rem;
    }
    .budget-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #f8fafc;
        color: #0f172a;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 0.35rem 0.75rem;
        border-radius: 100px;
        border: 1.5px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s;
    }
    .budget-badge:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .budget-badge svg {
        color: #1eb349;
    }
    .budget-decor {
        position: absolute;
        top: -15px;
        right: -10px;
        width: 140px;
        height: auto;
        opacity: 0.9;
        z-index: 5;
        pointer-events: none;
        filter: drop-shadow(0 10px 15px rgba(0,0,0,0.05));
    }
    .budget-chart-container {
        margin-top: 0.5rem;
        position: relative;
        z-index: 5;
        width: 100%;
        margin-bottom: -5px;
    }
    .budget-chart-svg {
        width: 100%;
        height: 80px;
        display: block;
        overflow: visible;
    }
    
    /* Interactive Slow-mo loading animations (5 seconds) */
    .draw-line-slow {
        stroke-dasharray: 1200;
        stroke-dashoffset: 1200;
        animation: dashSlow 5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    @keyframes dashSlow {
        to { stroke-dashoffset: 0; }
    }
    .fade-fill-slow {
        opacity: 0;
        animation: fadeFillSlow 5s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    @keyframes fadeFillSlow {
        0% { opacity: 0; }
        60% { opacity: 0; }
        100% { opacity: 1; }
    }
    .pop-dot-slow {
        opacity: 0;
        transform: scale(0);
        transform-origin: center;
        animation: popSlow 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    @keyframes popSlow {
        to { opacity: 1; transform: scale(1); }
    }
    
    .budget-days {
        display: flex;
        justify-content: space-between;
        padding: 0.25rem 1.75rem 1.25rem 1.75rem;
        font-size: 0.65rem;
        font-weight: 600;
        color: #94a3b8;
    }

    /* Card 3: Date & Digital Live Clock */
    .card-calendar-white {
        background: #fafcfa;
        border: 1.5px solid #f1f5f9;
        border-radius: 28px;
        padding: 2rem 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        min-height: 190px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }
    .calendar-month {
        color: #1eb349;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }
    .calendar-day {
        font-size: 2.75rem;
        font-weight: 700;
        color: #0b120c;
        line-height: 1.1;
        margin: 0.2rem 0;
        letter-spacing: -0.02em;
    }
    .calendar-clock {
        font-size: 0.78rem;
        font-weight: 700;
        color: #94a3b8;
        font-variant-numeric: tabular-nums;
    }

    /* Row 2 Grid: 3 Columns (Wave Chart + Views + Clicks) */
    .cr-grid-bottom {
        display: grid;
        grid-template-columns: 1.8fr 1fr 1fr;
        gap: 1.25rem;
    }

    /* Card 4: Traffic Wave */
    .card-wave {
        background: #ffffff;
        border: 1.5px solid #f1f5f9;
        border-radius: 28px;
        padding: 1.75rem 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 230px;
    }
    .wave-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .wave-title {
        font-size: 0.75rem;
        font-weight: 800;
        color: #475569;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .wave-live-badge {
        color: #1eb349;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        background: #f0fdf4;
        border: 1px solid #dcfce7;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .wave-live-dot {
        width: 6px;
        height: 6px;
        background: #1eb349;
        border-radius: 50%;
        animation: livePulse 1.8s infinite;
    }
    @keyframes livePulse {
        0% { transform: scale(0.9); opacity: 0.8; }
        50% { transform: scale(1.4); opacity: 1; box-shadow: 0 0 8px #1eb349; }
        100% { transform: scale(0.9); opacity: 0.8; }
    }
    .wave-chart-svg {
        width: 100%;
        height: 90px;
        overflow: visible;
    }
    .wave-days {
        display: flex;
        justify-content: space-between;
        font-size: 0.72rem;
        font-weight: 600;
        color: #94a3b8;
        padding: 0 0.5rem;
        margin-top: 0.5rem;
    }

    /* Card 5 & 6: Stat Counter Cards */
    .card-counter {
        background: #ffffff;
        border: 1.5px solid #f1f5f9;
        border-radius: 28px;
        padding: 2rem 1.5rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 230px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
    }
    .counter-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0b120c;
        margin-bottom: 1rem;
    }
    .counter-label {
        font-size: 0.72rem;
        font-weight: 800;
        color: #94a3b8;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.35rem;
    }
    .counter-value {
        font-size: 2rem;
        font-weight: 700;
        color: #0b120c;
        line-height: 1;
        letter-spacing: -0.02em;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .cr-grid-top { grid-template-columns: 1fr 1fr; }
        .card-calendar-white { grid-column: span 2; }
        .cr-grid-bottom { grid-template-columns: 1fr 1fr; }
        .card-wave { grid-column: span 2; }
    }
    @media (max-width: 768px) {
        .cr-grid-top, .cr-grid-bottom { grid-template-columns: 1fr; }
        .card-calendar-white, .card-wave { grid-column: span 1; }
        .card-banner-black, .card-balance-neon, .card-calendar-white, .card-wave, .card-counter { min-height: auto; padding: 1.5rem; }
        .cr-dash-title { font-size: 1.6rem; }
    }
</style>
@endsection

@section('content')

@php
    $cp = $seller->creatorProfile;
    $storeName = $cp?->store_name ?: ($seller->name ?: 'Surabaya');
    $location = $cp?->city_name ?: 'Surabaya';
@endphp

{{-- ── OVERVIEW HEADER ── --}}
<div class="cr-dash-header">
    <h1 class="cr-dash-title">Overview</h1>
    <p class="cr-dash-sub">Manage your premium branding assets & digital products.</p>
</div>

{{-- ── ROW 1: TOP 3 CARDS ── --}}
<div class="cr-grid-top">
    {{-- 1. Black Welcome Card --}}
    <div class="card-banner-black">
        <div>
            <div class="badge-member">
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                PREMIUM MEMBER
            </div>
            <div class="banner-title">Halo, {{ $storeName }}!</div>
            <div class="banner-sub">Kelola aset premium dan kembangkan tokomu.</div>
        </div>
    </div>

    {{-- 2. Budget Card (Modern with Chart) --}}
    <div class="card-budget-modern">
        <!-- Floating Decor SVG -->
        <svg class="budget-decor" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g style="transform: rotate(20deg) translate(40px, -20px);">
                <rect x="50" y="30" width="110" height="65" rx="12" fill="#f8fafc" stroke="#e2e8f0" stroke-width="2"/>
                <circle cx="75" cy="62" r="12" fill="#e2e8f0"/>
                <circle cx="130" cy="62" r="22" fill="#e2e8f0"/>
            </g>
            <g style="transform: rotate(5deg) translate(0px, 15px);">
                <rect x="30" y="60" width="120" height="70" rx="12" fill="#ffffff" stroke="#cbd5e1" stroke-width="2"/>
                <circle cx="55" cy="95" r="14" fill="#cbd5e1"/>
                <circle cx="115" cy="95" r="26" fill="#cbd5e1"/>
            </g>
        </svg>

        <div class="budget-content">
            <div class="budget-label">{{ $storeName }}</div>
            <div class="budget-val" id="rt-balance">
                Rp {{ number_format($availableBalance ?? $gmv ?? 0, 0, ',', '.') }}
            </div>
            <a href="{{ route('creator.payout.settings') }}" class="budget-badge">
                + Tarik Saldo
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="budget-chart-container">
            <!-- SVG Chart with 5s slow-mo animation -->
            <svg class="budget-chart-svg" viewBox="0 0 500 120" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="budgetGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#1eb349" stop-opacity="0.4"/>
                        <stop offset="100%" stop-color="#a5cf37" stop-opacity="0.0"/>
                    </linearGradient>
                    <linearGradient id="budgetLineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#1eb349"/>
                        <stop offset="100%" stop-color="#a5cf37"/>
                    </linearGradient>
                    
                    <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                        <feGaussianBlur stdDeviation="4" result="blur" />
                        <feComposite in="SourceGraphic" in2="blur" operator="over" />
                    </filter>
                </defs>
                <!-- Filled area -->
                <path class="fade-fill-slow" d="M 0,110 L 50,110 L 120,70 L 200,90 L 280,30 L 360,60 L 460,20 L 500,10 L 500,120 L 0,120 Z" fill="url(#budgetGrad)"/>
                
                <!-- Vertical grid lines (optional, to mimic image) -->
                <path d="M 120,70 L 120,120 M 200,90 L 200,120 M 280,30 L 280,120 M 360,60 L 360,120 M 460,20 L 460,120" stroke="#f1f5f9" stroke-width="1" class="fade-fill-slow"/>

                <!-- Stroke Line -->
                <path class="draw-line-slow" d="M 0,110 L 50,110 L 120,70 L 200,90 L 280,30 L 360,60 L 460,20 L 500,10" fill="none" stroke="url(#budgetLineGrad)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                
                <!-- Dot marker -->
                <g class="pop-dot-slow" style="animation-delay: 3.5s;">
                    <!-- Pulse glow -->
                    <circle cx="280" cy="30" r="12" fill="#1eb349" opacity="0.2" filter="url(#glow)"/>
                    <circle cx="280" cy="30" r="6" fill="#ffffff" stroke="#1eb349" stroke-width="3"/>
                </g>
                
                <!-- Floating badge on dot -->
                <g class="pop-dot-slow" style="animation-delay: 4.2s;">
                    <rect x="250" y="-5" width="60" height="24" rx="12" fill="#1eb349" filter="url(#glow)"/>
                    <polygon points="280,25 275,18 285,18" fill="#1eb349"/>
                    <text x="280" y="11" fill="#fff" font-family="Montserrat" font-size="11" font-weight="bold" text-anchor="middle">Aktif</text>
                </g>
            </svg>
            <div class="budget-days">
                <span>Sun</span>
                <span>Mon</span>
                <span>Tue</span>
                <span>Wed</span>
                <span>Thu</span>
                <span>Fri</span>
                <span>Sat</span>
            </div>
        </div>
    </div>

    {{-- 3. Date & Digital Clock Card --}}
    <div class="card-calendar-white">
        <div class="calendar-month">{{ strtoupper(now()->translatedFormat('F')) }}</div>
        <div class="calendar-day">{{ now()->format('d') }}</div>
        <div class="calendar-clock" id="liveClockDisplay">{{ now()->format('H.i.s') }}</div>
    </div>
</div>

{{-- ── ROW 2: BOTTOM 3 CARDS ── --}}
<div class="cr-grid-bottom">
    {{-- 4. Traffic Wave Card --}}
    <div class="card-wave">
        <div class="wave-header">
            <span class="wave-title">TRAFFIC WAVE</span>
            <span class="wave-live-badge">
                <span class="wave-live-dot"></span>
                LIVE
            </span>
        </div>

        {{-- Smooth Wave SVG Visualization --}}
        <svg class="wave-chart-svg" viewBox="0 0 500 100" preserveAspectRatio="none">
            <defs>
                <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#1eb349" stop-opacity="0.35"/>
                    <stop offset="100%" stop-color="#1eb349" stop-opacity="0.0"/>
                </linearGradient>
                <linearGradient id="waveLineGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#1eb349"/>
                    <stop offset="50%" stop-color="#a3e635"/>
                    <stop offset="100%" stop-color="#1eb349"/>
                </linearGradient>
            </defs>
            <path d="M 0,80 Q 60,10 120,45 T 240,65 T 360,15 T 500,60 L 500,100 L 0,100 Z" fill="url(#waveGradient)"/>
            <path d="M 0,80 Q 60,10 120,45 T 240,65 T 360,15 T 500,60" fill="none" stroke="url(#waveLineGradient)" stroke-width="3.5" stroke-linecap="round"/>
        </svg>

        <div class="wave-days">
            <span>Mon</span>
            <span>Tue</span>
            <span>Wed</span>
            <span>Thu</span>
            <span>Fri</span>
            <span>Sat</span>
            <span>Sun</span>
        </div>
    </div>

    {{-- 5. Total Views Counter Card --}}
    <div class="card-counter">
        <div class="counter-icon-box">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        </div>
        <div class="counter-label">TOTAL VIEWS</div>
        <div class="counter-value" id="rt-views">{{ number_format(($totalProducts * 12) + 24, 0, ',', '.') }}</div>
    </div>

    {{-- 6. Total Clicks / Transaksi Counter Card --}}
    <div class="card-counter">
        <div class="counter-icon-box">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
            </svg>
        </div>
        <div class="counter-label">TOTAL TRANSAKSI</div>
        <div class="counter-value" id="rt-clicks">{{ number_format($totalTransactions ?? 0, 0, ',', '.') }}</div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ── Realtime Digital Clock ──
    function updateClock() {
        const now = new Date();
        const hrs  = String(now.getHours()).padStart(2, '0');
        const mins = String(now.getMinutes()).padStart(2, '0');
        const secs = String(now.getSeconds()).padStart(2, '0');
        const el = document.getElementById('liveClockDisplay');
        if (el) el.textContent = `${hrs}.${mins}.${secs}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // ── Realtime Stats Polling (every 30s) ──
    const statsUrl = '{{ route("creator.stats.realtime") }}';

    function formatIDR(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }
    function formatNum(num) {
        return Number(num).toLocaleString('id-ID');
    }

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

            if (balEl) {
                balEl.innerHTML = formatIDR(data.available_balance);
            }
            if (viewEl)  viewEl.textContent  = formatNum(data.total_views);
            if (clickEl) clickEl.textContent = formatNum(data.total_transactions);

            // Flash animation to signal update
            [balEl, viewEl, clickEl].forEach(el => {
                if (!el) return;
                el.style.transition = 'color 0.3s';
                el.style.color = '#1eb349';
                setTimeout(() => { el.style.color = ''; }, 600);
            });
        } catch(e) {}
    }

    // Poll every 30 seconds
    setInterval(fetchRealtimeStats, 30000);
</script>
@endsection
