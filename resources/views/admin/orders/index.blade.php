@extends('layouts.admin')
@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')
@section('content')

<style>
    /* ── Minimalist Orders Page System ── */
    .opage {
        font-family: 'Montserrat', sans-serif;
    }

    /* Page Header */
    .opage-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .opage-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0F172A;
        letter-spacing: -.02em;
        margin: 0 0 .2rem;
    }

    .opage-sub {
        font-size: .8125rem;
        color: #64748B;
        margin: 0;
        font-weight: 500;
    }

    /* Period Filter Pills & Inputs */
    .filter-btn {
        padding: 0.35rem 0.85rem;
        font-size: 0.775rem;
        font-weight: 600;
        border-radius: 50px;
        border: 1px solid #E2E8F0;
        background: #fff;
        color: #64748B;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .filter-btn:hover, .filter-btn.active {
        background: #f0fdf4;
        border-color: #10B981;
        color: #15803D;
    }

    /* 5 Stat Cards in 1 Row */
    .stat-5-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.85rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 1200px) {
        .stat-5-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .stat-5-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 480px) {
        .stat-5-grid { grid-template-columns: 1fr; }
    }

    .dash-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .dash-card {
        background: #FFFFFF;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        padding: 1rem;
        transition: all 0.15s ease;
        height: 100%;
    }
    .dash-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-color: #CBD5E1;
        transform: translateY(-1px);
    }
    .dash-card.card-active {
        border-color: #10B981;
        background: #F0FDF4;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.15);
    }

    /* Tabs Header */
    .o-tabs-wrap {
        display: flex;
        gap: .4rem;
        overflow-x: auto;
        margin-bottom: 1rem;
        padding-bottom: .25rem;
    }

    .o-tab {
        padding: .4rem 0.9rem;
        font-size: .775rem;
        font-weight: 600;
        color: #64748B;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 99px;
        text-decoration: none;
        white-space: nowrap;
        transition: all .15s;
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .o-tab:hover {
        background: #F8FAFC;
        border-color: #CBD5E1;
        color: #0F172A;
    }

    .o-tab.active {
        background: #F0FDF4;
        border-color: #BBF7D0;
        color: #15803D;
    }

    .o-tab-count {
        background: #F1F5F9;
        color: #64748B;
        padding: 2px 7px;
        border-radius: 20px;
        font-size: .7rem;
        transition: all .15s;
    }

    .o-tab.active .o-tab-count {
        background: #10B981;
        color: #fff;
    }

    /* Search & Filter Bar */
    .o-filter-bar {
        display: flex;
        gap: .65rem;
        align-items: center;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        background: #fff;
        padding: 0.65rem 0.85rem;
        border-radius: 10px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .o-search-input {
        flex: 1;
        min-width: 220px;
        padding: .45rem .75rem;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        font-size: .8125rem;
        outline: none;
        font-family: inherit;
        background: #F8FAFC;
        transition: all .15s;
        color: #0F172A;
    }

    .o-search-input:focus {
        border-color: #10B981;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
    }

    .o-btn-submit {
        background: #0F172A;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: .45rem 0.95rem;
        font-size: .775rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .o-btn-submit:hover {
        background: #1E293B;
    }

    .o-btn-reset {
        background: #F1F5F9;
        color: #475569;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: .45rem 0.85rem;
        font-size: .775rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .15s;
    }
    .o-btn-reset:hover {
        background: #E2E8F0;
    }

    /* View Toggle */
    .view-toggle-wrap {
        display: flex;
        gap: 4px;
        background: #F1F5F9;
        padding: 3px;
        border-radius: 8px;
    }
    .view-toggle-btn {
        background: none;
        border: none;
        padding: 5px 8px;
        border-radius: 6px;
        color: #64748B;
        cursor: pointer;
        transition: all 0.15s;
    }
    .view-toggle-btn.active {
        background: #fff;
        color: #0F172A;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Minimalist Order Cards */
    .o-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        margin-bottom: 0.85rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: all .15s;
    }

    .o-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.04);
    }

    .o-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #F1F5F9;
        background: #FAFBFA;
        font-size: 0.8125rem;
    }

    .o-card-user {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-weight: 600;
        color: #0F172A;
    }

    .o-card-ordernum {
        font-size: .725rem;
        font-weight: 700;
        padding: .2rem .55rem;
        background: #F1F5F9;
        color: #475569;
        border-radius: 6px;
    }

    .o-card-body {
        padding: 0.85rem 1rem;
        display: grid;
        grid-template-columns: 2.2fr 1.4fr 1.2fr auto;
        gap: 1rem;
        align-items: center;
    }
    @media (max-width: 992px) {
        .o-card-body {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
    }

    .o-item-col {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .o-item-img {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #E2E8F0;
        flex-shrink: 0;
    }

    .o-item-title {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #0F172A;
        line-height: 1.3;
    }

    .o-col-title {
        font-size: 0.7rem;
        color: #64748B;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        margin-bottom: 0.2rem;
    }

    .o-total-price {
        font-size: 0.875rem;
        font-weight: 700;
        color: #0F172A;
    }

    .o-btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 0.85rem;
        font-size: 0.775rem;
        font-weight: 600;
        color: #0F172A;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.15s;
    }
    .o-btn-action:hover {
        background: #0F172A;
        color: #fff;
        border-color: #0F172A;
    }

    /* Grid Layout for Orders */
    .o-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 0.85rem;
    }
    @media (max-width: 1400px) { .o-grid { grid-template-columns: repeat(4, 1fr); } }
    @media (max-width: 1024px) { .o-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px)  { .o-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px)  { .o-grid { grid-template-columns: 1fr; } }

    .o-gcard {
        background: #fff;
        border-radius: 10px;
        border: 1px solid #E2E8F0;
        padding: 0.85rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.15s;
    }
    .o-gcard:hover {
        border-color: #CBD5E1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }
    .o-gcard-img {
        width: 100%;
        height: 90px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #F1F5F9;
        margin-bottom: 0.65rem;
    }
    .o-gcard-body {
        flex: 1;
        margin-bottom: 0.65rem;
    }
    .o-gcard-order {
        font-size: 0.7rem;
        color: #64748B;
        font-weight: 600;
    }
    .o-gcard-name {
        font-size: 0.8125rem;
        font-weight: 600;
        color: #0F172A;
        line-height: 1.3;
        margin: 0.15rem 0;
    }
    .o-gcard-email {
        font-size: 0.725rem;
        color: #64748B;
    }
    .o-gcard-price {
        font-size: 0.8375rem;
        font-weight: 700;
        color: #0F172A;
        margin-top: 0.35rem;
    }
    .o-gcard-foot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 0.5rem;
        border-top: 1px solid #F1F5F9;
    }

    /* Omset / Financial Table View */
    .omset-summary-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 992px) { .omset-summary-grid { grid-template-columns: repeat(2, 1fr); } }

    .omset-sum-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 0.75rem 0.9rem;
    }
    .omset-sum-lbl {
        font-size: 0.7rem;
        color: #64748B;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .omset-sum-val {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0F172A;
        margin-top: 0.15rem;
    }

    .omset-table-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        overflow-x: auto;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .omset-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8125rem;
    }

    .omset-table th {
        background: #FAFBFA;
        padding: 0.65rem 0.85rem;
        border-bottom: 1px solid #E2E8F0;
        color: #64748B;
        font-weight: 600;
        font-size: 0.725rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        white-space: nowrap;
    }

    .omset-table td {
        padding: 0.75rem 0.85rem;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
        white-space: nowrap;
    }

    .omset-table tr:hover td {
        background: #F8FAFC;
    }

    .badge-pill {
        display: inline-block;
        padding: 0.2rem 0.55rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    /* Modal Styling */
    .rm-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .rm-modal {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 440px;
        padding: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .rm-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #F1F5F9;
    }
</style>

<div class="opage">

    {{-- Header --}}
    <div class="opage-head">
        <div>
            <h1 class="opage-title">Manajemen Pesanan</h1>
            <p class="opage-sub">Ringkasan transaksi konsumen buyle.id &bull; {{ number_format($orders->total()) }} pesanan ditemukan</p>
        </div>

        {{-- Filter Periode Quick Pills --}}
        <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'period' => '7d', 'q' => $q]) }}" class="filter-btn {{ $period === '7d' ? 'active' : '' }}">7 Hari</a>
            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'period' => '30d', 'q' => $q]) }}" class="filter-btn {{ $period === '30d' ? 'active' : '' }}">30 Hari</a>
            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'period' => '1y', 'q' => $q]) }}" class="filter-btn {{ $period === '1y' ? 'active' : '' }}">1 Tahun</a>

            <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex;align-items:center;gap:0.35rem;">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="date" name="start_date" value="{{ $start_date }}" style="padding:0.35rem 0.6rem;border:1px solid #E2E8F0;border-radius:6px;font-size:0.775rem;">
                <span style="font-size:0.75rem;color:#94A3B8;">s/d</span>
                <input type="date" name="end_date" value="{{ $end_date }}" style="padding:0.35rem 0.6rem;border:1px solid #E2E8F0;border-radius:6px;font-size:0.775rem;">
                <button type="submit" class="filter-btn active">Filter</button>
            </form>

            <button type="button" onclick="openReportModal()" class="filter-btn" style="background:#0F172A;color:#fff;border-color:#0F172A;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Laporan
            </button>
        </div>
    </div>

    {{-- 5 STAT CARDS IN 1 ROW (CLICKABLE SHORTCUTS) --}}
    <div class="stat-5-grid">
        {{-- Card 1: Total Order --}}
        <a href="{{ route('admin.orders.index', ['tab' => 'all', 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date, 'q' => $q]) }}" class="dash-card-link">
            <div class="dash-card {{ $tab === 'all' ? 'card-active' : '' }}">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="background:rgba(30, 179, 73, 0.1);border-radius:10px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="#16A34A" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div>
                        <div style="font-size:0.725rem;color:#64748B;font-weight:600;margin-bottom:0.1rem;">Total Transaksi</div>
                        <div style="font-size:1.2rem;font-weight:700;color:#0F172A;line-height:1.1;">{{ number_format($stats['total']) }}</div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card 2: Perlu Dikirim --}}
        <a href="{{ route('admin.orders.index', ['tab' => 'processing', 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date, 'q' => $q]) }}" class="dash-card-link">
            <div class="dash-card {{ $tab === 'processing' ? 'card-active' : '' }}">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="background:rgba(217, 119, 6, 0.1);border-radius:10px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="#D97706" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <div style="font-size:0.725rem;color:#64748B;font-weight:600;margin-bottom:0.1rem;">Perlu Dikirim</div>
                        <div style="font-size:1.2rem;font-weight:700;color:#0F172A;line-height:1.1;">{{ number_format($stats['processing']) }}</div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card 3: Sedang Dikirim --}}
        <a href="{{ route('admin.orders.index', ['tab' => 'shipped', 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date, 'q' => $q]) }}" class="dash-card-link">
            <div class="dash-card {{ $tab === 'shipped' ? 'card-active' : '' }}">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="background:rgba(37, 99, 235, 0.1);border-radius:10px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="#2563EB" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div>
                        <div style="font-size:0.725rem;color:#64748B;font-weight:600;margin-bottom:0.1rem;">Sedang Dikirim</div>
                        <div style="font-size:1.2rem;font-weight:700;color:#0F172A;line-height:1.1;">{{ number_format($stats['shipped']) }}</div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card 4: Order Selesai --}}
        <a href="{{ route('admin.orders.index', ['tab' => 'completed', 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date, 'q' => $q]) }}" class="dash-card-link">
            <div class="dash-card {{ $tab === 'completed' ? 'card-active' : '' }}">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="background:rgba(22, 101, 52, 0.1);border-radius:10px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="#166534" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div>
                        <div style="font-size:0.725rem;color:#64748B;font-weight:600;margin-bottom:0.1rem;">Order Selesai</div>
                        <div style="font-size:1.2rem;font-weight:700;color:#0F172A;line-height:1.1;">{{ number_format($stats['completed']) }}</div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card 5: Total Omset (Clickable) --}}
        <a href="{{ route('admin.orders.index', ['tab' => 'omset', 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date, 'q' => $q]) }}" class="dash-card-link">
            <div class="dash-card {{ $tab === 'omset' ? 'card-active' : '' }}">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <div style="background:rgba(16, 185, 129, 0.12);border-radius:10px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="20" height="20" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <div style="font-size:0.725rem;color:#64748B;font-weight:600;margin-bottom:0.1rem;">Total Omset</div>
                        <div style="font-size:1.1rem;font-weight:700;color:#166534;line-height:1.1;">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Tabs Filter Header --}}
    <div class="o-tabs-wrap">
        @foreach($tabs as $k => $t)
            <a href="{{ route('admin.orders.index', ['tab' => $k, 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date, 'q' => $q]) }}" class="o-tab {{ $tab == $k ? 'active' : '' }}">
                {{ $t['label'] }} <span class="o-tab-count">{{ $counts[$k] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    {{-- Search Form --}}
    <form class="o-filter-bar" method="GET" action="{{ route('admin.orders.index') }}">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <input type="hidden" name="period" value="{{ $period }}">
        <input type="hidden" name="start_date" value="{{ $start_date }}">
        <input type="hidden" name="end_date" value="{{ $end_date }}">

        <svg width="16" height="16" fill="none" stroke="#94A3B8" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" name="q" class="o-search-input" placeholder="Cari no. pesanan, nama/email pembeli, toko creator, atau produk..." value="{{ $q }}">

        <button type="submit" class="o-btn-submit">
            Cari
        </button>
        @if($q)
            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date]) }}" class="o-btn-reset">Reset</a>
        @endif

        @if($tab !== 'omset')
            {{-- View Toggle --}}
            <div class="view-toggle-wrap" style="margin-left:auto;">
                <button type="button" class="view-toggle-btn active" id="btn-list-view" onclick="setView('list')" title="List View">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                </button>
                <button type="button" class="view-toggle-btn" id="btn-grid-view" onclick="setView('grid')" title="Grid View">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                </button>
            </div>
        @endif
    </form>

    {{-- CONDITIONAL TAB DISPLAY --}}
    @if($tab === 'omset')

        {{-- Omset Summary Header Cards --}}
        <div class="omset-summary-grid">
            <div class="omset-sum-box">
                <div class="omset-sum-lbl">Total Omset (Gross)</div>
                <div class="omset-sum-val">Rp {{ number_format($revenueStats['gross'], 0, ',', '.') }}</div>
            </div>
            <div class="omset-sum-box">
                <div class="omset-sum-lbl">Subtotal Produk</div>
                <div class="omset-sum-val">Rp {{ number_format($revenueStats['subtotal'], 0, ',', '.') }}</div>
            </div>
            <div class="omset-sum-box">
                <div class="omset-sum-lbl">Total Admin Fee</div>
                <div class="omset-sum-val" style="color:#D97706;">+ Rp {{ number_format($revenueStats['admin_fee'], 0, ',', '.') }}</div>
            </div>
            <div class="omset-sum-box">
                <div class="omset-sum-lbl">Total Platform Fee</div>
                <div class="omset-sum-val" style="color:#D97706;">+ Rp {{ number_format($revenueStats['platform_fee'], 0, ',', '.') }}</div>
            </div>
            <div class="omset-sum-box" style="background:#F0FDF4;border-color:#BBF7D0;">
                <div class="omset-sum-lbl" style="color:#166534;">Profit Platform</div>
                <div class="omset-sum-val" style="color:#15803D;">+ Rp {{ number_format($revenueStats['profit'], 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Financial Transaction History Table --}}
        <div class="omset-table-card">
            <table class="omset-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Order ID</th>
                        <th>Customer Email</th>
                        <th>Transaction Type</th>
                        <th>Channel</th>
                        <th style="text-align:right;">Harga Produk</th>
                        <th style="text-align:right;">Admin Fee</th>
                        <th style="text-align:right;">Platform Fee</th>
                        <th style="text-align:right;">Amount / Total</th>
                        <th style="text-align:right;">Profit</th>
                        <th style="text-align:center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                        @php
                            $types = $o->items->map(function($i) {
                                $pt = $i->product?->product_type ?? $i->product?->type ?? '';
                                if ($pt === 'ticket') return 'Tiket';
                                if (in_array($pt, ['external_link', 'digital', 'file'])) return 'Digital';
                                return 'Fisik';
                            })->unique()->implode(', ');
                            $txType = $types ?: 'Digital';
                            $channel = $o->payment?->method ? strtoupper($o->payment->method) : ($o->payment?->gateway ? strtoupper($o->payment->gateway) : 'MIDTRANS');
                            $profit = ($o->admin_fee ?? 0) + ($o->platform_fee ?? 0);
                        @endphp
                        <tr>
                            <td>
                                <div style="font-weight:500;color:#0F172A;">{{ $o->created_at->format('d M Y, H:i') }} WIB</div>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $o) }}" style="font-weight:700;color:#10B981;text-decoration:none;">#{{ $o->order_number }}</a>
                            </td>
                            <td>
                                <div style="font-weight:600;color:#0F172A;">{{ $o->user->name ?? 'Guest' }}</div>
                                <div style="font-size:0.75rem;color:#64748B;">{{ $o->user->email ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge-pill" style="background:#F1F5F9;color:#475569;">{{ $txType }}</span>
                            </td>
                            <td>
                                <span class="badge-pill" style="background:#EFF6FF;color:#1D4ED8;">{{ $channel }}</span>
                            </td>
                            <td style="text-align:right;font-weight:500;">Rp {{ number_format($o->subtotal, 0, ',', '.') }}</td>
                            <td style="text-align:right;color:#D97706;font-weight:500;">+ Rp {{ number_format($o->admin_fee ?? 0, 0, ',', '.') }}</td>
                            <td style="text-align:right;color:#D97706;font-weight:500;">+ Rp {{ number_format($o->platform_fee ?? 0, 0, ',', '.') }}</td>
                            <td style="text-align:right;font-weight:700;color:#0F172A;">Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                            <td style="text-align:right;font-weight:700;color:#15803D;">+ Rp {{ number_format($profit, 0, ',', '.') }}</td>
                            <td style="text-align:center;">
                                @php
                                    $statusVal = is_object($o->status) ? $o->status->value : (string)$o->status;
                                    $stBadge = match($statusVal) {
                                        'confirmed', 'processing' => ['#059669', '#E6F4EA', 'Perlu Dikirim'],
                                        'shipped'                 => ['#047857', '#D1E7DD', 'Dikirim'],
                                        'completed', 'delivered'  => ['#166534', '#DCFCE7', 'Selesai'],
                                        default                   => ['#475569', '#F1F5F9', ucfirst($statusVal)]
                                    };
                                @endphp
                                <span class="badge-pill" style="color:{{ $stBadge[0] }};background:{{ $stBadge[1] }};">
                                    {{ $stBadge[2] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" style="text-align:center;padding:2.5rem 1rem;color:#94A3B8;">
                                Belum ada riwayat transaksi omset pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.25rem;">
            {{ $orders->links() }}
        </div>

    @else

        {{-- Order List --}}
        <div id="view-list">
            @forelse($orders as $o)
                <div class="o-card">
                    {{-- Card Header --}}
                    <div class="o-card-head">
                        <div class="o-card-user">
                            @php
                                $userAvatar = $o->user->avatar ?? null;
                                $userName = $o->user->name ?? $o->receiver_name ?? 'Konsumen';
                            @endphp
                            @if($userAvatar)
                                <img src="{{ asset('storage/' . $userAvatar) }}" loading="lazy" style="width:26px;height:26px;border-radius:50%;object-fit:cover;border:1px solid #E2E8F0;flex-shrink:0;">
                            @else
                                <div style="width:26px;height:26px;border-radius:50%;background:#F1F5F9;color:#64748B;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.725rem;flex-shrink:0;border:1px solid #E2E8F0;">
                                    {{ strtoupper(substr($userName, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <span>{{ $userName }}</span>
                                @if($o->user && $o->user->email)
                                    <span style="font-size:0.75rem;color:#94A3B8;font-weight:400;margin-left:0.25rem;">({{ $o->user->email }})</span>
                                @endif
                            </div>
                        </div>
                        <div class="o-card-ordernum">#{{ $o->order_number }}</div>
                    </div>

                    {{-- Card Body --}}
                    <div class="o-card-body">

                        {{-- Products Column --}}
                        <div>
                            @foreach($o->items->take(2) as $item)
                                <div class="o-item-col" style="{{ !$loop->last ? 'margin-bottom:0.5rem;' : '' }}">
                                    <img src="{{ $item->product && $item->product->image ? asset('storage/' . $item->product->image) : asset('img/no-image.jpg') }}" loading="lazy" class="o-item-img" onerror="this.src='https://via.placeholder.com/60?text=No+Img'">
                                    <div>
                                        <div class="o-item-title">{{ Str::limit($item->product_name ?? ($item->product->name ?? 'Produk'), 50) }}</div>
                                        @if($item->product && $item->product->seller)
                                            <div style="font-size:0.725rem;color:#059669;font-weight:600;margin-bottom:0.15rem;">Toko: {{ $item->product->seller->store_name ?? $item->product->seller->name }}</div>
                                        @endif
                                        <div style="font-size:0.725rem;color:#64748B;font-weight:500;">Qty: {{ $item->qty }} &bull; Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            @endforeach
                            @if($o->items->count() > 2)
                                <div style="font-size:0.725rem;color:#64748B;margin-top:0.35rem;font-weight:500;background:#F8FAFC;padding:.15rem .45rem;border-radius:4px;display:inline-block;">
                                    + {{ $o->items->count() - 2 }} produk lainnya
                                </div>
                            @endif
                        </div>

                        {{-- Status & Courier --}}
                        <div>
                            <div class="o-col-title">Status Pesanan</div>
                            @php
                                $statusVal = is_object($o->status) ? $o->status->value : (string)$o->status;
                                $stBadge = match($statusVal) {
                                    'confirmed', 'processing' => ['#059669', '#E6F4EA', 'Perlu Dikirim'],
                                    'shipped'                 => ['#047857', '#D1E7DD', 'Dikirim'],
                                    'completed', 'delivered'  => ['#166534', '#DCFCE7', 'Selesai'],
                                    default                   => ['#475569', '#F1F5F9', ucfirst($statusVal)]
                                };
                            @endphp
                            <span class="badge-pill" style="color:{{ $stBadge[0] }};background:{{ $stBadge[1] }};">
                                {{ $stBadge[2] }}
                            </span>

                            <div class="o-col-title" style="margin-top:0.65rem;">Ekspedisi / Resi</div>
                            <div style="font-size:0.775rem;font-weight:600;color:#1E293B;">{{ $o->shipment->courier_name ?? 'Pengiriman Digital / Reguler' }}</div>
                            <div style="font-size:0.725rem;color:#64748B;margin-top:0.1rem;">Resi: {{ $o->shipment->tracking_number ?? 'Belum ada resi' }}</div>
                        </div>

                        {{-- Total Amount --}}
                        <div>
                            <div class="o-col-title">Total Pembayaran</div>
                            <div class="o-total-price">Rp {{ number_format($o->total, 0, ',', '.') }}</div>
                            <div style="font-size:0.725rem;color:#64748B;margin-top:0.2rem;font-weight:500;">
                                {{ $o->payment->method ?? 'Payment Gateway' }}
                            </div>
                        </div>

                        {{-- Action Minimalist Button --}}
                        <div style="text-align:right;">
                            <a href="{{ route('admin.orders.show', $o) }}" class="o-btn-action">
                                Rincian
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>

                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:3rem 1rem;background:#fff;border-radius:12px;border:1px dashed #CBD5E1;">
                    <svg width="48" height="48" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:0.75rem;opacity:0.5;">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                    <div style="font-size:0.95rem;font-weight:600;color:#334155;margin-bottom:0.2rem;">Tidak Ada Pesanan</div>
                    <div style="font-size:0.775rem;color:#94A3B8;">Belum ada pesanan pada filter periode atau tab ini.</div>
                </div>
            @endforelse

            {{-- Pagination --}}
            <div style="margin-top:1.25rem;">
                {{ $orders->links() }}
            </div>
        </div>

        {{-- Grid View --}}
        <div id="view-grid" style="display:none;">
            <div class="o-grid">
            @forelse($orders as $o)
                @php
                    $firstItem = $o->items->first();
                    $firstImg  = $firstItem && $firstItem->product && $firstItem->product->image
                        ? asset('storage/' . $firstItem->product->image)
                        : asset('img/no-image.jpg');
                    $userName = $o->user->name ?? $o->receiver_name ?? 'Konsumen';
                    $userEmail = $o->user->email ?? '—';
                    $statusVal = is_object($o->status) ? $o->status->value : (string)$o->status;
                    $stBadge = match($statusVal) {
                        'confirmed','processing' => ['#059669', '#E6F4EA', 'Proses'],
                        'shipped'                => ['#047857', '#D1E7DD', 'Kirim'],
                        'completed','delivered'  => ['#166534', '#DCFCE7', 'Selesai'],
                        default                  => ['#475569', '#F1F5F9', ucfirst($statusVal)]
                    };
                @endphp
                <div class="o-gcard">
                    <img src="{{ $firstImg }}" class="o-gcard-img" loading="lazy" onerror="this.src='https://via.placeholder.com/200?text=Produk'">
                    <div class="o-gcard-body">
                        <div class="o-gcard-order">#{{ $o->order_number }}</div>
                        <div class="o-gcard-name">{{ Str::limit($userName, 22) }}</div>
                        <div class="o-gcard-email">{{ Str::limit($userEmail, 24) }}</div>
                        <div class="o-gcard-price">Rp {{ number_format($o->total, 0, ',', '.') }}</div>
                    </div>
                    <div class="o-gcard-foot">
                        <span class="badge-pill" style="color:{{ $stBadge[0] }};background:{{ $stBadge[1] }};">
                            {{ $stBadge[2] }}
                        </span>
                        <a href="{{ route('admin.orders.show', $o) }}" style="display:flex;align-items:center;gap:.2rem;font-size:.725rem;font-weight:600;color:#10B981;text-decoration:none;">
                            Detail
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:3rem 1rem;background:#fff;border-radius:12px;border:1px dashed #CBD5E1;">
                    <div style="font-size:0.95rem;font-weight:600;color:#334155;">Tidak Ada Pesanan</div>
                    <div style="font-size:0.775rem;color:#94A3B8;">Belum ada pesanan pada filter ini.</div>
                </div>
            @endforelse
            </div>
            <div style="margin-top:1.25rem;">{{ $orders->links() }}</div>
        </div>

    @endif

</div>

{{-- MODAL DOWNLOAD LAPORAN --}}
<div id="reportModal" class="rm-overlay">
    <div class="rm-modal">
        <div class="rm-head">
            <div style="font-weight:600;font-size:0.95rem;color:#0F172A;">Unduh Laporan Pesanan</div>
            <button type="button" onclick="closeReportModal()" style="background:none;border:none;color:#94A3B8;cursor:pointer;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.orders.export') }}" method="GET" class="rm-body">
            <div style="margin-bottom:1rem;">
                <label style="font-size:0.775rem;font-weight:600;color:#475569;display:block;margin-bottom:0.35rem;">Format File</label>
                <select name="format" style="width:100%;padding:0.5rem 0.75rem;border:1px solid #CBD5E1;border-radius:8px;font-family:inherit;font-size:0.8125rem;outline:none;">
                    <option value="xlsx">Excel (.xlsx)</option>
                    <option value="csv">CSV (.csv)</option>
                    <option value="pdf">PDF Document (.pdf)</option>
                </select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:1.25rem;">
                <div>
                    <label style="font-size:0.775rem;font-weight:600;color:#475569;display:block;margin-bottom:0.35rem;">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ now()->subDays(30)->format('Y-m-d') }}" style="width:100%;padding:0.5rem 0.75rem;border:1px solid #CBD5E1;border-radius:8px;font-family:inherit;font-size:0.8125rem;outline:none;">
                </div>
                <div>
                    <label style="font-size:0.775rem;font-weight:600;color:#475569;display:block;margin-bottom:0.35rem;">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" style="width:100%;padding:0.5rem 0.75rem;border:1px solid #CBD5E1;border-radius:8px;font-family:inherit;font-size:0.8125rem;outline:none;">
                </div>
            </div>
            <button type="submit" style="width:100%;padding:0.6rem;background:#0F172A;color:#fff;border:none;border-radius:8px;font-weight:600;font-size:0.8125rem;cursor:pointer;font-family:inherit;">
                Download File Laporan
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openReportModal() {
    document.getElementById('reportModal').style.display = 'flex';
}
function closeReportModal() {
    document.getElementById('reportModal').style.display = 'none';
}

function setView(type) {
    const listEl = document.getElementById('view-list');
    const gridEl = document.getElementById('view-grid');
    const btnList = document.getElementById('btn-list-view');
    const btnGrid = document.getElementById('btn-grid-view');

    if (!listEl || !gridEl) return;

    if (type === 'grid') {
        listEl.style.display = 'none';
        gridEl.style.display = 'block';
        if (btnList) btnList.classList.remove('active');
        if (btnGrid) btnGrid.classList.add('active');
    } else {
        listEl.style.display = 'block';
        gridEl.style.display = 'none';
        if (btnList) btnList.classList.add('active');
        if (btnGrid) btnGrid.classList.remove('active');
    }
    localStorage.setItem('admin_orders_view', type);
}

// Restore saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const saved = localStorage.getItem('admin_orders_view') || 'list';
    setView(saved);
});
</script>
@endpush

@endsection