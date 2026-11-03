@extends('creator.layout')

@section('title', 'Laporan Kasir POS – Creator Studio')
@section('page_title', 'Laporan Kasir POS')
@section('page_subtitle', 'Analisis penjualan kasir digital, omset harian, dan mutasi deposit.')

@section('styles')
<style>
    /* ── SUPER APP POS REPORT STYLING ── */
    :root {
        --pos-green: #1eb349;
        --pos-green-dark: #16933b;
        --pos-gradient: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
    }

    body {
        background-color: #f8fafc !important;
    }

    .pos-rep-container {
        max-width: 580px;
        margin: 0 auto;
        padding: 0.75rem 0.85rem 6.5rem 0.85rem;
        min-height: 100vh;
    }

    /* Top Navigation Header */
    .pos-rep-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.85rem;
        gap: 0.5rem;
    }

    .pos-rep-header-left {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .pos-rep-back-btn {
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
        flex-shrink: 0;
    }

    .pos-rep-back-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .pos-rep-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
    }

    .pos-rep-subtitle {
        font-size: 0.72rem;
        color: #64748b;
        margin: 2px 0 0 0;
    }

    .pos-rep-export-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0.45rem 0.85rem;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #166534;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        transition: all 0.15s ease;
    }

    .pos-rep-export-btn:hover {
        background: #f0fdf4;
        border-color: #86efac;
    }

    /* Period Filter Capsules */
    .pos-rep-filter-row {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        overflow-x: auto;
        padding-bottom: 0.45rem;
        margin-bottom: 0.75rem;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .pos-rep-filter-row::-webkit-scrollbar {
        display: none;
    }

    .pos-rep-pill {
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        padding: 0.4rem 0.85rem;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .pos-rep-pill.active {
        background: var(--pos-gradient);
        border-color: transparent;
        color: #ffffff !important;
        box-shadow: 0 3px 10px rgba(30, 179, 73, 0.3);
    }

    /* KPI Summary Cards Grid */
    .pos-kpi-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.65rem;
        margin-bottom: 0.85rem;
    }

    .pos-kpi-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        padding: 0.9rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .pos-kpi-card.highlight {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-color: #334155;
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.15);
    }

    .pos-kpi-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .pos-kpi-card.highlight .pos-kpi-label {
        color: #94a3b8;
    }

    .pos-kpi-val {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .pos-kpi-card.highlight .pos-kpi-val {
        color: #ffffff;
    }

    .pos-kpi-sub {
        font-size: 0.68rem;
        color: #94a3b8;
        margin-top: 3px;
    }

    /* Method Filter Row */
    .pos-method-row {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        overflow-x: auto;
        padding-bottom: 0.45rem;
        margin-bottom: 0.85rem;
        scrollbar-width: none;
    }

    .pos-method-row::-webkit-scrollbar {
        display: none;
    }

    .pos-method-pill {
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
        text-decoration: none;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #64748b;
        white-space: nowrap;
        transition: all 0.15s ease;
    }

    .pos-method-pill.active {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }

    /* Section Navigation Tabs */
    .pos-section-tabs {
        display: flex;
        background: #f1f5f9;
        border-radius: 14px;
        padding: 3px;
        margin-bottom: 0.85rem;
        gap: 3px;
    }

    .pos-sec-tab-btn {
        flex: 1;
        text-align: center;
        padding: 0.45rem 0.25rem;
        border-radius: 11px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        border: none;
        background: transparent;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .pos-sec-tab-btn.active {
        background: #ffffff;
        color: #0f172a;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    /* List Card Items */
    .pos-order-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 0.85rem 1rem;
        margin-bottom: 0.65rem;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
        transition: all 0.15s ease;
    }

    .pos-order-card:active {
        background: #f8fafc;
    }

    .pos-order-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.35rem;
    }

    .pos-order-no {
        font-size: 0.82rem;
        font-weight: 700;
        color: #0f172a;
    }

    .pos-order-time {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    .pos-order-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.35rem;
    }

    .pos-order-customer {
        font-size: 0.78rem;
        color: #334155;
        font-weight: 500;
    }

    .pos-order-amount {
        font-size: 0.95rem;
        font-weight: 700;
        color: #166534;
    }

    .pos-order-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px dashed #f1f5f9;
        padding-top: 0.45rem;
        margin-top: 0.35rem;
        font-size: 0.7rem;
        color: #64748b;
    }

    .pos-badge-cash {
        background: #ecfdf5;
        color: #059669;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 999px;
        border: 1px solid #a7f3d0;
    }

    .pos-badge-qris {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 999px;
        border: 1px solid #bfdbfe;
    }

    .pos-badge-transfer {
        background: #f5f3ff;
        color: #7c3aed;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 999px;
        border: 1px solid #ddd6fe;
    }

    /* Top Product Ranking */
    .pos-top-rank-item {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 0.8rem 1rem;
        margin-bottom: 0.55rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pos-rank-left {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .pos-rank-badge {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: #334155;
    }

    .pos-rank-badge.top-1 { background: #fef3c7; color: #b45309; }
    .pos-rank-badge.top-2 { background: #e2e8f0; color: #475569; }
    .pos-rank-badge.top-3 { background: #ffedd5; color: #c2410c; }

    /* Search Box */
    .pos-rep-search {
        position: relative;
        margin-bottom: 0.75rem;
    }

    .pos-rep-search input {
        width: 100%;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 999px;
        padding: 0.55rem 1rem 0.55rem 2.4rem;
        font-size: 0.8rem;
        color: #0f172a;
        outline: none;
        transition: border-color 0.15s ease;
    }

    .pos-rep-search input:focus {
        border-color: var(--pos-green);
    }

    .pos-rep-search svg {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    /* POS Bottom Navigation Bar */
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
    }

    .pos-bottom-nav-bar {
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
        text-decoration: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        outline: none;
    }

    .pos-nav-item.active {
        background: var(--pos-gradient) !important;
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
        color: #ffffff;
    }
</style>
@endsection

@section('content')
<div class="pos-rep-container">
    {{-- Header Top Bar --}}
    <div class="pos-rep-header">
        <div class="pos-rep-header-left">
            <a href="{{ route('creator.pos.index') }}" class="pos-rep-back-btn" title="Kembali ke Kasir">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="pos-rep-title">Laporan POS</h1>
                <p class="pos-rep-subtitle">Kasir & Rekap Shift Transaksi</p>
            </div>
        </div>

        <a href="{{ route('creator.sales.report.export_pos', array_merge(request()->query(), ['pos_method' => $posPaymentMethod])) }}"
           class="pos-rep-export-btn" title="Unduh CSV Excel">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" y1="15" x2="12" y2="3" />
            </svg>
            <span>Export CSV</span>
        </a>
    </div>

    {{-- Filter Periode Capsules --}}
    <div class="pos-rep-filter-row">
        <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['filter' => 'today'])) }}"
           class="pos-rep-pill {{ $filter === 'today' ? 'active' : '' }}">Hari Ini</a>
        <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['filter' => 'yesterday'])) }}"
           class="pos-rep-pill {{ $filter === 'yesterday' ? 'active' : '' }}">Kemarin</a>
        <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['filter' => '7'])) }}"
           class="pos-rep-pill {{ $filter === '7' ? 'active' : '' }}">7 Hari</a>
        <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['filter' => '30'])) }}"
           class="pos-rep-pill {{ $filter === '30' ? 'active' : '' }}">30 Hari</a>
        <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['filter' => 'this_month'])) }}"
           class="pos-rep-pill {{ $filter === 'this_month' ? 'active' : '' }}">Bulan Ini</a>
        <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['filter' => 'all'])) }}"
           class="pos-rep-pill {{ $filter === 'all' ? 'active' : '' }}">Semua</a>
    </div>

    {{-- KPI Cards Grid --}}
    <div class="pos-kpi-grid">
        <div class="pos-kpi-card highlight">
            <span class="pos-kpi-label">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="12" y1="1" x2="12" y2="23" /><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
                Total Omset POS
            </span>
            <div class="pos-kpi-val">Rp {{ number_format($posTotalSales, 0, ',', '.') }}</div>
            <div class="pos-kpi-sub">{{ number_format($posTotalTransactions) }} Struk Selesai</div>
        </div>

        <div class="pos-kpi-card">
            <span class="pos-kpi-label">
                <svg width="13" height="13" fill="none" stroke="#10b981" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="2" y="6" width="20" height="12" rx="2" /><circle cx="12" cy="12" r="2" />
                </svg>
                Tunai di Laci (Cash)
            </span>
            <div class="pos-kpi-val" style="color: #059669;">Rp {{ number_format($posCashTotal, 0, ',', '.') }}</div>
            <div class="pos-kpi-sub">Fisik Uang Kasir</div>
        </div>

        <div class="pos-kpi-card">
            <span class="pos-kpi-label">
                <svg width="13" height="13" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" /><rect x="14" y="3" width="7" height="7" /><rect x="14" y="14" width="7" height="7" /><rect x="3" y="14" width="7" height="7" />
                </svg>
                Digital / QRIS
            </span>
            <div class="pos-kpi-val" style="color: #2563eb;">Rp {{ number_format($posDigitalTotal, 0, ',', '.') }}</div>
            <div class="pos-kpi-sub">Masuk Saldo / QRIS</div>
        </div>

        <div class="pos-kpi-card">
            <span class="pos-kpi-label">
                <svg width="13" height="13" fill="none" stroke="#d97706" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4" /><path d="M4 6v12a2 2 0 0 0 2 2h14v-4" /><circle cx="16" cy="14" r="1" />
                </svg>
                Saldo Deposit Kasir
            </span>
            <div class="pos-kpi-val" style="color: #d97706;">Rp {{ number_format($posBalance, 0, ',', '.') }}</div>
            <div class="pos-kpi-sub">Deposit Biaya Transaksi</div>
        </div>
    </div>

    {{-- Filter Metode Bayar --}}
    <div class="pos-method-row">
        <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['pos_method' => 'all'])) }}"
           class="pos-method-pill {{ ($posPaymentMethod ?? 'all') === 'all' ? 'active' : '' }}">Semua ({{ count($posOrders) }})</a>
        <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['pos_method' => 'cash'])) }}"
           class="pos-method-pill {{ ($posPaymentMethod ?? '') === 'cash' ? 'active' : '' }}">💵 Tunai (Cash)</a>
        <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['pos_method' => 'qris'])) }}"
           class="pos-method-pill {{ ($posPaymentMethod ?? '') === 'qris' ? 'active' : '' }}">📱 QRIS / Digital</a>
        <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['pos_method' => 'transfer'])) }}"
           class="pos-method-pill {{ ($posPaymentMethod ?? '') === 'transfer' ? 'active' : '' }}">🏦 Transfer Bank</a>
    </div>

    {{-- Section Tabs --}}
    <div class="pos-section-tabs">
        <button type="button" class="pos-sec-tab-btn active" onclick="switchReportSection('struk', this)">Daftar Struk ({{ count($posOrders) }})</button>
        <button type="button" class="pos-sec-tab-btn" onclick="switchReportSection('top_menu', this)">Menu Terlaris</button>
        <button type="button" class="pos-sec-tab-btn" onclick="switchReportSection('mutasi', this)">Mutasi Deposit</button>
    </div>

    {{-- SECTION 1: DAFTAR STRUK POS --}}
    <div id="secStruk">
        <div class="pos-rep-search">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" id="posReportSearchInput" placeholder="Cari No. Struk atau Nama Pelanggan..." onkeyup="filterReportOrders()">
        </div>

        <div id="posReportOrdersContainer">
            @forelse($posOrders as $order)
                @php
                    $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
                    $cName = $addr['name'] ?? $order->user?->name ?? 'Pelanggan Umum (Walk-in)';
                    $cPhone = $addr['phone'] ?? $order->user?->phone ?? '-';
                    $method = strtolower($addr['payment_method'] ?? ($order->payment->method ?? 'cash'));
                    $tableNo = $addr['table_number'] ?? ($addr['table'] ?? null);
                    $cashierName = $order->cashier_name ?? ($order->user?->name ?? '-');
                @endphp
                <div class="pos-order-card" data-search="{{ strtolower($order->order_number . ' ' . $cName . ' ' . $cPhone) }}">
                    <div class="pos-order-header">
                        <span class="pos-order-no">#{{ $order->order_number }}</span>
                        <span class="pos-order-time">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="pos-order-body">
                        <div>
                            <div class="pos-order-customer">{{ $cName }}</div>
                            <div style="font-size:0.68rem; color:#94a3b8; margin-top:2px;">
                                {{ $order->items->sum('quantity') }} item
                                @if($tableNo) • Meja {{ $tableNo }} @endif
                            </div>
                        </div>
                        <div class="pos-order-amount">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                    </div>
                    <div class="pos-order-footer">
                        <div>
                            @if($method === 'cash')
                                <span class="pos-badge-cash">Tunai (Cash)</span>
                            @elseif($method === 'qris')
                                <span class="pos-badge-qris">QRIS</span>
                            @else
                                <span class="pos-badge-transfer">{{ strtoupper($method) }}</span>
                            @endif
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span>Kasir: {{ $cashierName }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 2.5rem 1rem; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
                    <svg width="40" height="40" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 0.5rem auto; display:block;">
                        <circle cx="12" cy="12" r="10" /><line x1="8" y1="12" x2="16" y2="12" />
                    </svg>
                    <div style="font-weight: 600; color: #475569; font-size: 0.9rem;">Belum Ada Transaksi POS</div>
                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Tidak ada transaksi kasir pada periode ini.</div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- SECTION 2: TOP MENU TERLARIS --}}
    <div id="secTopMenu" style="display: none;">
        @forelse($topProducts as $idx => $prod)
            <div class="pos-top-rank-item">
                <div class="pos-rank-left">
                    <div class="pos-rank-badge {{ $idx === 0 ? 'top-1' : ($idx === 1 ? 'top-2' : ($idx === 2 ? 'top-3' : '')) }}">
                        #{{ $idx + 1 }}
                    </div>
                    <div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: #0f172a;">{{ $prod->product_name ?? 'Menu POS' }}</div>
                        <div style="font-size: 0.7rem; color: #64748b; margin-top: 2px;">
                            Terjual: <strong style="color: #166534;">{{ $prod->total_qty }} porsi</strong>
                        </div>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.88rem; font-weight: 700; color: #0f172a;">
                        Rp {{ number_format($prod->total_revenue, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 2.5rem 1rem; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <div style="font-weight: 600; color: #475569; font-size: 0.9rem;">Belum Ada Data Penjualan Menu</div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Data menu terlaris akan muncul otomatis saat ada transaksi.</div>
            </div>
        @endforelse
    </div>

    {{-- SECTION 3: MUTASI SALDO DEPOSIT --}}
    <div id="secMutasi" style="display: none;">
        @forelse($posMutations as $mut)
            <div class="pos-order-card">
                <div class="pos-order-header">
                    <span class="pos-order-no">
                        @if($mut->type === 'topup')
                            <span style="color:#16a34a;">+ Top Up Saldo Deposit</span>
                        @elseif($mut->type === 'fee_deduction')
                            <span style="color:#ef4444;">- Potongan Biaya Transaksi</span>
                        @else
                            {{ ucfirst($mut->type) }}
                        @endif
                    </span>
                    <span class="pos-order-time">{{ $mut->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="pos-order-body">
                    <div style="font-size: 0.75rem; color: #475569;">
                        {{ $mut->description ?? '-' }}
                    </div>
                    <div style="font-size: 0.92rem; font-weight: 800; color: {{ $mut->type === 'topup' ? '#16a34a' : '#ef4444' }};">
                        {{ $mut->type === 'topup' ? '+' : '-' }} Rp {{ number_format(abs($mut->amount), 0, ',', '.') }}
                    </div>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 2.5rem 1rem; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <div style="font-weight: 600; color: #475569; font-size: 0.9rem;">Belum Ada Riwayat Mutasi</div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Tidak ada mutasi deposit kasir pada rentang waktu ini.</div>
            </div>
        @endforelse
    </div>
</div>

{{-- ── 4-BUTTON BOTTOM NAVIGATION BAR (Button 3: Laporan Aktif) ── --}}
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

        <!-- 3. Laporan (ACTIVE) -->
        <a href="{{ route('creator.pos.reports') }}" class="pos-nav-item active" title="Laporan POS">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <line x1="18" y1="20" x2="18" y2="10"/>
                <line x1="12" y1="20" x2="12" y2="4"/>
                <line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
            <span class="pos-nav-label">Laporan</span>
        </a>

        <!-- 4. Profile -->
        <a href="{{ route('creator.pos.profile') }}" class="pos-nav-item" title="Profil Kasir & Toko">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            <span class="pos-nav-label">Profil</span>
        </a>
    </div>
</div>

<script>
    function switchReportSection(sec, btn) {
        document.querySelectorAll('.pos-sec-tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.getElementById('secStruk').style.display = sec === 'struk' ? 'block' : 'none';
        document.getElementById('secTopMenu').style.display = sec === 'top_menu' ? 'block' : 'none';
        document.getElementById('secMutasi').style.display = sec === 'mutasi' ? 'block' : 'none';
    }

    function filterReportOrders() {
        const q = (document.getElementById('posReportSearchInput')?.value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('#posReportOrdersContainer .pos-order-card');
        cards.forEach(card => {
            const searchKey = card.getAttribute('data-search') || '';
            card.style.display = searchKey.includes(q) ? 'block' : 'none';
        });
    }
</script>
@endsection

