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

    /* Card 2: Affiliate Balance (Neon Green Gradient) */
    .card-balance-neon {
        background: linear-gradient(135deg, #1eb349, #4ade80);
        border-radius: 28px;
        padding: 2rem;
        color: #032d16;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 190px;
        box-shadow: 0 12px 30px rgba(30,179,73,0.25);
    }
    .balance-label-sm {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        color: #032d16;
        opacity: 0.75;
        text-transform: uppercase;
    }
    .balance-val {
        font-size: 1.6rem;
        font-weight: 700;
        color: #032d16;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        line-height: 1;
        margin: 0.6rem 0;
    }
    .btn-withdraw-pill {
        background: #0b120c;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        padding: 0.55rem 1.35rem;
        border-radius: 999px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        align-self: flex-start;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-withdraw-pill:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.35);
        color: #fff;
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

    {{-- 2. Neon Lime Affiliate Balance Card --}}
    <div class="card-balance-neon">
        <div class="balance-label-sm">AFFILIATE BALANCE</div>
        <div class="balance-val" id="rt-balance">
            Rp {{ number_format($availableBalance ?? $gmv ?? 0, 0, ',', '.') }}
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="opacity:0.7;" title="Saldo Siap Ditarik"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <a href="{{ route('creator.payout.settings') }}" class="btn-withdraw-pill">
            WITHDRAW &rarr;
        </a>
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
                balEl.innerHTML = formatIDR(data.available_balance)
                    + ` <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="opacity:0.7;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
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
