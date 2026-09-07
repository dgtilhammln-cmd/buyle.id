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
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .opage-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0F172A;
        letter-spacing: -.03em;
        margin: 0 0 .25rem;
    }

    .opage-sub {
        font-size: .8125rem;
        color: #64748B;
        margin: 0;
        font-weight: 500;
    }

    /* Period Filter Pills & Inputs */
    .filter-btn {
        padding: 0.45rem 0.95rem;
        font-size: 0.8rem;
        font-weight: 700;
        border-radius: 50px;
        border: 1.5px solid #E2E8F0;
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
        border-color: #1eb349;
        color: #1eb349;
    }

    /* 5 Stat Cards in 1 Row */
    .stat-5-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
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

    .dash-card {
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        padding: 1.15rem 1.25rem;
        transition: all 0.2s ease;
    }
    .dash-card:hover {
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        border-color: #CBD5E1;
    }

    /* Tabs Header */
    .o-tabs-wrap {
        display: flex;
        gap: .5rem;
        overflow-x: auto;
        margin-bottom: 1.25rem;
        padding-bottom: .25rem;
    }

    .o-tab {
        padding: .5rem 1.1rem;
        font-size: .8rem;
        font-weight: 700;
        color: #64748B;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 99px;
        text-decoration: none;
        white-space: nowrap;
        transition: all .2s;
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
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }

    .o-tab-count {
        background: #F1F5F9;
        color: #64748B;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: .7rem;
        transition: all .2s;
    }

    .o-tab.active .o-tab-count {
        background: #1eb349;
        color: #fff;
    }

    /* Search & Filter Bar */
    .o-filter-bar {
        display: flex;
        gap: .75rem;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        background: #fff;
        padding: 0.85rem 1rem;
        border-radius: 14px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }

    .o-search-input {
        flex: 1;
        min-width: 220px;
        padding: .55rem .9rem;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        font-size: .82rem;
        outline: none;
        font-family: 'Montserrat', sans-serif;
        background: #F8FAFC;
        transition: all .2s;
        color: #0F172A;
    }

    .o-search-input:focus {
        border-color: #1eb349;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.1);
    }

    .o-btn-submit {
        background: #1eb349;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: .55rem 1.15rem;
        font-size: .8rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
        transition: all .2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .o-btn-submit:hover {
        background: #166534;
    }

    .o-btn-reset {
        background: #F1F5F9;
        color: #475569;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: .55rem 1rem;
        font-size: .8rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
    }
    .o-btn-reset:hover {
        background: #E2E8F0;
    }

    /* Minimalist Order Cards */
    .o-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: all .2s;
    }

    .o-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }

    .o-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid #F1F5F9;
        background: #FAFBFA;
        font-size: 0.82rem;
    }

    .o-card-user {
        display: flex;
        align-items: center;
        gap: .65rem;
        font-weight: 700;
        color: #0F172A;
    }

    .o-card-ordernum {
        font-size: .75rem;
        font-weight: 800;
        padding: .25rem .65rem;
        background: #F1F5F9;
        color: #475569;
        border-radius: 6px;
        font-family: monospace;
    }

    .o-card-body {
        display: grid;
        grid-template-columns: 2.2fr 1fr 1fr 0.8fr;
        padding: 1.15rem 1.25rem;
        gap: 1.25rem;
        align-items: center;
    }

    @media(max-width: 992px) {
        .o-card-body {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }

    .o-item-col {
        display: flex;
        gap: 0.85rem;
        align-items: center;
    }

    .o-item-img {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #E2E8F0;
        flex-shrink: 0;
        background: #F8FAFC;
    }

    .o-item-title {
        font-weight: 700;
        font-size: .85rem;
        color: #1E293B;
        line-height: 1.35;
        margin-bottom: .2rem;
    }

    .o-col-title {
        font-size: .68rem;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        margin-bottom: .3rem;
        letter-spacing: .04em;
    }

    .o-total-price {
        font-weight: 800;
        color: #1eb349;
        font-size: 1.05rem;
    }

    /* Action Minimalist Buttons */
    .o-btn-action {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .78rem;
        font-weight: 700;
        color: #1eb349;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        text-decoration: none;
        padding: .45rem .85rem;
        transition: all .2s;
        border-radius: 8px;
    }

    .o-btn-action:hover {
        background: #1eb349;
        color: #fff;
        border-color: #1eb349;
    }

    /* Modal Download Laporan */
    .rm-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.45); backdrop-filter: blur(4px);
        display: none; align-items: center; justify-content: center;
        z-index: 99999; padding: 1rem;
    }

    .rm-modal {
        background: #fff; border-radius: 20px; width: 100%; max-width: 480px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2); overflow: hidden;
        animation: rmPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes rmPop { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

    .rm-head {
        padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9;
        display: flex; align-items: center; justify-content: space-between;
    }

    .rm-body { padding: 1.5rem; }
</style>

<div class="opage">

    {{-- Page Header --}}
    <div class="opage-head">
        <div>
            <h1 class="opage-title">Manajemen Pesanan</h1>
            <p class="opage-sub">Ringkasan transaksi konsumen buyle.id &bull; <strong>{{ $orders->total() }}</strong> pesanan ditemukan</p>
        </div>

        {{-- Filter Periode Header Form --}}
        <form method="GET" action="{{ route('admin.orders.index') }}" style="display: flex; gap: 0.4rem; align-items: center; flex-wrap: wrap;">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input type="hidden" name="q" value="{{ $q }}">

            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'q' => $q, 'period' => '7d']) }}" class="filter-btn {{ $period === '7d' ? 'active' : '' }}">7 Hari</a>
            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'q' => $q, 'period' => '30d']) }}" class="filter-btn {{ $period === '30d' ? 'active' : '' }}">30 Hari</a>
            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'q' => $q, 'period' => '1y']) }}" class="filter-btn {{ $period === '1y' ? 'active' : '' }}">1 Tahun</a>

            <div style="width: 1px; height: 20px; background: #CBD5E1; margin: 0 0.2rem;"></div>

            <input type="date" name="start_date" value="{{ $start_date }}" style="padding: 0.4rem 0.65rem; font-size: 0.78rem; background: #fff; border: 1.5px solid #E2E8F0; border-radius: 50px; color: #1E293B; outline: none; font-family: inherit; font-weight: 600;">
            <span style="color: #94A3B8; font-weight: 600; font-size: 0.78rem;">s/d</span>
            <input type="date" name="end_date" value="{{ $end_date }}" style="padding: 0.4rem 0.65rem; font-size: 0.78rem; background: #fff; border: 1.5px solid #E2E8F0; border-radius: 50px; color: #1E293B; outline: none; font-family: inherit; font-weight: 600;">

            <button type="submit" class="filter-btn active" style="background: #1eb349; color: #fff; border-color: #1eb349;">
                Filter
            </button>

            <button type="button" class="filter-btn" onclick="openReportModal()" style="margin-left: 0.25rem;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Laporan
            </button>
        </form>
    </div>

    {{-- 5 STAT CARDS IN 1 ROW --}}
    <div class="stat-5-grid">
        {{-- Card 1: Total Order --}}
        <div class="dash-card">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="background:rgba(30, 179, 73, 0.1);border-radius:12px;width:44px;height:44px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#64748B;font-weight:600;margin-bottom:0.15rem;">Total Transaksi</div>
                    <div style="font-size:1.35rem;font-weight:800;color:#0F172A;line-height:1.1;">{{ number_format($stats['total']) }}</div>
                </div>
            </div>
        </div>

        {{-- Card 2: Perlu Dikirim --}}
        <div class="dash-card">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="background:rgba(5, 150, 105, 0.1);border-radius:12px;width:44px;height:44px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="#059669" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#64748B;font-weight:600;margin-bottom:0.15rem;">Perlu Dikirim</div>
                    <div style="font-size:1.35rem;font-weight:800;color:#0F172A;line-height:1.1;">{{ number_format($stats['processing']) }}</div>
                </div>
            </div>
        </div>

        {{-- Card 3: Sedang Dikirim --}}
        <div class="dash-card">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="background:rgba(16, 185, 129, 0.1);border-radius:12px;width:44px;height:44px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="#10B981" stroke-width="2.2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#64748B;font-weight:600;margin-bottom:0.15rem;">Sedang Dikirim</div>
                    <div style="font-size:1.35rem;font-weight:800;color:#0F172A;line-height:1.1;">{{ number_format($stats['shipped']) }}</div>
                </div>
            </div>
        </div>

        {{-- Card 4: Order Selesai --}}
        <div class="dash-card">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="background:rgba(22, 101, 52, 0.1);border-radius:12px;width:44px;height:44px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="#166534" stroke-width="2.2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#64748B;font-weight:600;margin-bottom:0.15rem;">Order Selesai</div>
                    <div style="font-size:1.35rem;font-weight:800;color:#0F172A;line-height:1.1;">{{ number_format($stats['completed']) }}</div>
                </div>
            </div>
        </div>

        {{-- Card 5: Total Omset --}}
        <div class="dash-card">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div style="background:rgba(142, 189, 40, 0.12);border-radius:12px;width:44px;height:44px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="#8ebd28" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div>
                    <div style="font-size:0.75rem;color:#64748B;font-weight:600;margin-bottom:0.15rem;">Total Omset</div>
                    <div style="font-size:1.15rem;font-weight:800;color:#166534;line-height:1.1;">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
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

        <svg width="18" height="18" fill="none" stroke="#94A3B8" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" name="q" class="o-search-input" placeholder="Cari no. pesanan, nama/email pembeli, toko creator, atau produk..." value="{{ $q }}">

        <button type="submit" class="o-btn-submit">
            Cari
        </button>
        @if($q)
            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date]) }}" class="o-btn-reset">Reset</a>
        @endif
    </form>

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
                            <img src="{{ asset('storage/' . $userAvatar) }}" loading="lazy" style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:1px solid #E2E8F0;flex-shrink:0;">
                        @else
                            <div style="width:28px;height:28px;border-radius:50%;background:#F1F5F9;color:#64748B;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.75rem;flex-shrink:0;border:1px solid #E2E8F0;">
                                {{ strtoupper(substr($userName, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <span>{{ $userName }}</span>
                            @if($o->user && $o->user->email)
                                <span style="font-size:0.75rem;color:#94A3B8;font-weight:500;margin-left:0.25rem;">({{ $o->user->email }})</span>
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
                            <div class="o-item-col" style="{{ !$loop->last ? 'margin-bottom:0.75rem;' : '' }}">
                                <img src="{{ $item->product && $item->product->image ? asset('storage/' . $item->product->image) : asset('img/no-image.jpg') }}" loading="lazy" class="o-item-img" onerror="this.src='https://via.placeholder.com/60?text=No+Img'">
                                <div>
                                    <div class="o-item-title">{{ Str::limit($item->product_name ?? ($item->product->name ?? 'Produk'), 50) }}</div>
                                    @if($item->product && $item->product->user)
                                        <div style="font-size:0.72rem;color:#059669;font-weight:600;margin-bottom:0.2rem;">Toko: {{ $item->product->user->store_name ?? $item->product->user->name }}</div>
                                    @endif
                                    <div style="font-size:0.72rem;color:#64748B;font-weight:600;">Qty: {{ $item->qty }} &bull; Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                        @if($o->items->count() > 2)
                            <div style="font-size:0.72rem;color:#64748B;margin-top:0.4rem;font-weight:600;background:#F8FAFC;padding:.2rem .5rem;border-radius:4px;display:inline-block;">
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
                        <span style="font-size:0.72rem;font-weight:700;color:{{ $stBadge[0] }};background:{{ $stBadge[1] }};padding:0.25rem 0.6rem;border-radius:50px;display:inline-block;">
                            {{ $stBadge[2] }}
                        </span>

                        <div class="o-col-title" style="margin-top:0.85rem;">Ekspedisi / Resi</div>
                        <div style="font-size:0.8rem;font-weight:700;color:#1E293B;">{{ $o->shipment->courier_name ?? 'Pengiriman Digital / Reguler' }}</div>
                        <div style="font-size:0.72rem;color:#64748B;margin-top:0.15rem;">Resi: {{ $o->shipment->tracking_number ?? 'Belum ada resi' }}</div>
                    </div>

                    {{-- Total Amount --}}
                    <div>
                        <div class="o-col-title">Total Pembayaran</div>
                        <div class="o-total-price">Rp {{ number_format($o->total, 0, ',', '.') }}</div>
                        <div style="font-size:0.72rem;color:#64748B;margin-top:0.25rem;font-weight:600;">
                            {{ $o->payment->method ?? 'Payment Gateway' }}
                        </div>
                    </div>

                    {{-- Action Minimalist Button --}}
                    <div style="text-align:right;">
                        <a href="{{ route('admin.orders.show', $o) }}" class="o-btn-action">
                            Rincian
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                </div>
            </div>
        @empty
            <div style="text-align:center;padding:4rem 1rem;background:#fff;border-radius:16px;border:1.5px dashed #CBD5E1;">
                <svg width="60" height="60" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem;opacity:0.5;">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
                <div style="font-size:1rem;font-weight:700;color:#334155;margin-bottom:0.25rem;">Tidak Ada Pesanan</div>
                <div style="font-size:0.8rem;color:#94A3B8;">Belum ada pesanan pada filter periode atau tab ini.</div>
            </div>
        @endforelse

        {{-- Pagination --}}
        <div style="margin-top:1.5rem;">
            {{ $orders->links() }}
        </div>
    </div>

</div>

{{-- MODAL DOWNLOAD LAPORAN --}}
<div id="reportModal" class="rm-overlay">
    <div class="rm-modal">
        <div class="rm-head">
            <div style="font-weight:800;font-size:1.05rem;color:#0F172A;">Unduh Laporan Pesanan</div>
            <button type="button" onclick="closeReportModal()" style="background:none;border:none;color:#94A3B8;cursor:pointer;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.orders.export') }}" method="GET" class="rm-body">
            <div style="margin-bottom:1.25rem;">
                <label style="font-size:0.8rem;font-weight:700;color:#475569;display:block;margin-bottom:0.4rem;">Format File</label>
                <select name="format" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #E2E8F0;border-radius:10px;font-family:inherit;font-size:0.82rem;outline:none;">
                    <option value="xlsx">Excel (.xlsx)</option>
                    <option value="csv">CSV (.csv)</option>
                    <option value="pdf">PDF Document (.pdf)</option>
                </select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;margin-bottom:1.5rem;">
                <div>
                    <label style="font-size:0.8rem;font-weight:700;color:#475569;display:block;margin-bottom:0.4rem;">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ now()->subDays(30)->format('Y-m-d') }}" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #E2E8F0;border-radius:10px;font-family:inherit;font-size:0.82rem;outline:none;">
                </div>
                <div>
                    <label style="font-size:0.8rem;font-weight:700;color:#475569;display:block;margin-bottom:0.4rem;">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #E2E8F0;border-radius:10px;font-family:inherit;font-size:0.82rem;outline:none;">
                </div>
            </div>
            <button type="submit" style="width:100%;padding:0.75rem;background:#1eb349;color:#fff;border:none;border-radius:12px;font-weight:700;font-size:0.85rem;cursor:pointer;font-family:inherit;">
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
</script>
@endpush

@endsection