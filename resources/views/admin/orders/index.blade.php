@extends('layouts.admin')
@section('title', 'Manajemen Transaksi')
@section('page-title', 'Manajemen Transaksi')
@section('content')

<style>
    .trx-page {
        font-family: 'Montserrat', sans-serif;
        color: #0F172A;
    }

    /* Page Header */
    .trx-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }
    .trx-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0F172A;
        margin: 0 0 0.2rem;
    }
    .trx-subtitle {
        font-size: 0.8125rem;
        color: #64748B;
        margin: 0;
    }

    /* Filter Toolbar */
    .trx-period-bar {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }
    .trx-btn-filter {
        padding: 0.38rem 0.85rem;
        font-size: 0.775rem;
        font-weight: 600;
        border-radius: 50px;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        color: #64748B;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.15s ease;
    }
    .trx-btn-filter:hover {
        border-color: #CBD5E1;
        color: #0F172A;
    }
    .trx-btn-filter.active {
        background: #0F172A;
        border-color: #0F172A;
        color: #FFFFFF;
    }
    .trx-btn-download {
        background: #1eb349;
        border-color: #1eb349;
        color: #FFFFFF;
        cursor: pointer;
    }
    .trx-btn-download:hover {
        background: #16933c;
        border-color: #16933c;
        color: #FFFFFF;
    }

    /* 3 Top KPI Cards */
    .trx-kpi-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 900px) {
        .trx-kpi-grid {
            grid-template-columns: 1fr;
        }
    }
    .trx-kpi-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 1.15rem 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .trx-kpi-card.profit-card {
        background: #F0FDF4;
        border-color: #BBF7D0;
    }
    .trx-kpi-label {
        font-size: 0.725rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 0.25rem;
    }
    .trx-kpi-val {
        font-size: 1.45rem;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.2;
    }
    .trx-kpi-val.green {
        color: #15803D;
    }
    .trx-kpi-sub {
        font-size: 0.725rem;
        color: #64748B;
        margin-top: 0.35rem;
    }

    /* 5 Main Pillars Tabs */
    .trx-tabs-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        overflow-x: auto;
        padding-bottom: 4px;
        -webkit-overflow-scrolling: touch;
    }
    .trx-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1rem;
        border-radius: 10px;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        color: #475569;
        white-space: nowrap;
        transition: all 0.15s ease;
    }
    .trx-tab-btn:hover {
        border-color: #CBD5E1;
        background: #F8FAFC;
        color: #0F172A;
    }
    .trx-tab-btn.active {
        background: #0F172A;
        border-color: #0F172A;
        color: #FFFFFF;
    }
    .trx-tab-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 50px;
        background: #F1F5F9;
        color: #475569;
    }
    .trx-tab-btn.active .trx-tab-badge {
        background: #1eb349;
        color: #FFFFFF;
    }

    /* Substatus Pills for Marketplace */
    .trx-substatus-bar {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 1rem;
        overflow-x: auto;
        padding-bottom: 2px;
    }
    .trx-substatus-pill {
        padding: 0.3rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        color: #64748B;
        white-space: nowrap;
    }
    .trx-substatus-pill:hover {
        color: #0F172A;
    }
    .trx-substatus-pill.active {
        background: #1eb349;
        border-color: #1eb349;
        color: #FFFFFF;
    }

    /* Financial Bar for Finance Tab */
    .trx-finance-recap {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    @media (max-width: 768px) {
        .trx-finance-recap {
            grid-template-columns: 1fr;
        }
    }
    .trx-frecap-box {
        border-left: 3px solid #E2E8F0;
        padding-left: 0.85rem;
    }
    .trx-frecap-box.green-border {
        border-left-color: #1eb349;
    }
    .trx-frecap-box.amber-border {
        border-left-color: #D97706;
    }
    .trx-frecap-title {
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        margin-bottom: 0.15rem;
    }
    .trx-frecap-val {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0F172A;
    }
    .trx-frecap-sub {
        font-size: 0.7rem;
        color: #64748B;
        margin-top: 0.2rem;
    }

    /* Search Bar */
    .trx-search-wrap {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 0.6rem 0.85rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .trx-search-input {
        border: none;
        outline: none;
        font-size: 0.8125rem;
        color: #0F172A;
        width: 100%;
        background: transparent;
        font-family: inherit;
    }
    .trx-search-btn {
        padding: 0.35rem 0.85rem;
        border-radius: 8px;
        background: #0F172A;
        color: #FFFFFF;
        border: none;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
    }
    .trx-reset-btn {
        font-size: 0.75rem;
        color: #64748B;
        text-decoration: underline;
        white-space: nowrap;
    }

    /* Table & Card Wrapper */
    .trx-table-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .trx-table-scroll {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .trx-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8125rem;
    }
    .trx-table th {
        background: #F8FAFC;
        padding: 0.75rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-bottom: 1px solid #E2E8F0;
        white-space: nowrap;
    }
    .trx-table td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
        white-space: nowrap;
    }
    .trx-table tr:hover td {
        background: #FAFBFA;
    }

    /* Pillar Code Tag */
    .trx-tag {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 6px;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .trx-tag-mkt { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
    .trx-tag-tix { background: #FAF5FF; color: #7E22CE; border: 1px solid #E9D5FF; }
    .trx-tag-pos { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
    .trx-tag-wed { background: #FDF2F8; color: #BE185D; border: 1px solid #FBCFE8; }
    .trx-tag-amp { background: #FFF1F2; color: #BE123C; border: 1px solid #FECDD3; }
    .trx-tag-wd  { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }

    /* Action Button */
    .trx-action-link {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.3rem 0.65rem;
        border-radius: 6px;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        color: #0F172A;
        font-size: 0.725rem;
        font-weight: 600;
        text-decoration: none;
    }
    .trx-action-link:hover {
        background: #0F172A;
        color: #FFFFFF;
        border-color: #0F172A;
    }

    /* Empty State */
    .trx-empty {
        padding: 3rem 1.5rem;
        text-align: center;
        color: #64748B;
    }
    .trx-empty-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 0.25rem;
    }

    /* Modal Styling */
    .trx-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .trx-modal-box {
        background: #FFFFFF;
        border-radius: 16px;
        width: 100%;
        max-width: 440px;
        padding: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        margin: 1rem;
    }
    .trx-modal-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #F1F5F9;
    }
</style>

<div class="trx-page">

    {{-- Header & Quick Period Filters --}}
    <div class="trx-header">
        <div>
            <h1 class="trx-title">Manajemen Transaksi</h1>
            <p class="trx-subtitle">Ringkasan transaksi multi-layanan Buyle.id &bull; {{ number_format($kpi['total_items']) }} data ditemukan</p>
        </div>

        <div class="trx-period-bar">
            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'status' => $subStatus, 'period' => '7d', 'q' => $q]) }}"
               class="trx-btn-filter {{ $period === '7d' ? 'active' : '' }}">7 Hari</a>
            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'status' => $subStatus, 'period' => '30d', 'q' => $q]) }}"
               class="trx-btn-filter {{ $period === '30d' ? 'active' : '' }}">30 Hari</a>
            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'status' => $subStatus, 'period' => '1y', 'q' => $q]) }}"
               class="trx-btn-filter {{ $period === '1y' ? 'active' : '' }}">1 Tahun</a>

            <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex;align-items:center;gap:0.35rem;">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="hidden" name="status" value="{{ $subStatus }}">
                <input type="date" name="start_date" value="{{ $start_date }}" style="padding:0.35rem 0.5rem;border:1px solid #E2E8F0;border-radius:6px;font-size:0.75rem;font-family:inherit;">
                <span style="font-size:0.75rem;color:#94A3B8;">s/d</span>
                <input type="date" name="end_date" value="{{ $end_date }}" style="padding:0.35rem 0.5rem;border:1px solid #E2E8F0;border-radius:6px;font-size:0.75rem;font-family:inherit;">
                <button type="submit" class="trx-btn-filter active">Filter</button>
            </form>

            <button type="button" onclick="openExportModal()" class="trx-btn-filter trx-btn-download">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Unduh Data
            </button>
        </div>
    </div>

    {{-- Top 3 Clean KPI Cards --}}
    <div class="trx-kpi-grid">
        {{-- Card 1: Omset Kotor (Gross) --}}
        <div class="trx-kpi-card">
            <div class="trx-kpi-label">Total Omset (Gross)</div>
            <div class="trx-kpi-val">Rp {{ number_format($kpi['gross'], 0, ',', '.') }}</div>
            <div class="trx-kpi-sub">Total akumulasi pembayaran yang masuk ke payment gateway</div>
        </div>

        {{-- Card 2: Hak Mitra / Penjual --}}
        <div class="trx-kpi-card">
            <div class="trx-kpi-label">Jatah Mitra / Penjual</div>
            <div class="trx-kpi-val">Rp {{ number_format($kpi['partner_net'], 0, ',', '.') }}</div>
            <div class="trx-kpi-sub">Hak bersih milik penjual, kreator, pengantin, atau merchant</div>
        </div>

        {{-- Card 3: Profit Bersih Platform (Buyle.id) --}}
        <div class="trx-kpi-card profit-card">
            <div class="trx-kpi-label" style="color:#166534;">Profit Bersih Buyle.id</div>
            <div class="trx-kpi-val green">+ Rp {{ number_format($kpi['profit'], 0, ',', '.') }}</div>
            <div class="trx-kpi-sub" style="color:#166534;">Total Platform Fee dan Admin Fee murni milik platform</div>
        </div>
    </div>

    {{-- 5 Main Pillars Tabs --}}
    <div class="trx-tabs-container">
        @foreach($tabs as $key => $t)
            <a href="{{ route('admin.orders.index', ['tab' => $key, 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date, 'q' => $q]) }}"
               class="trx-tab-btn {{ $tab === $key ? 'active' : '' }}">
                <span class="trx-tab-badge">{{ $t['code'] }}</span>
                <span>{{ $t['label'] }}</span>
                <span style="font-size:0.75rem;opacity:0.75;">({{ number_format($counts[$key] ?? 0) }})</span>
            </a>
        @endforeach
    </div>

    {{-- Substatus Pills for Marketplace --}}
    @if($tab === 'marketplace')
        <div class="trx-substatus-bar">
            <span style="font-size:0.75rem;font-weight:700;color:#64748B;margin-right:0.25rem;">Status:</span>
            <a href="{{ route('admin.orders.index', ['tab' => 'marketplace', 'status' => 'all', 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date, 'q' => $q]) }}"
               class="trx-substatus-pill {{ $subStatus === 'all' ? 'active' : '' }}">
                Semua ({{ number_format($subStatusCounts['all']) }})
            </a>
            <a href="{{ route('admin.orders.index', ['tab' => 'marketplace', 'status' => 'processing', 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date, 'q' => $q]) }}"
               class="trx-substatus-pill {{ $subStatus === 'processing' ? 'active' : '' }}">
                Perlu Dikirim ({{ number_format($subStatusCounts['processing']) }})
            </a>
            <a href="{{ route('admin.orders.index', ['tab' => 'marketplace', 'status' => 'shipped', 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date, 'q' => $q]) }}"
               class="trx-substatus-pill {{ $subStatus === 'shipped' ? 'active' : '' }}">
                Sedang Dikirim ({{ number_format($subStatusCounts['shipped']) }})
            </a>
            <a href="{{ route('admin.orders.index', ['tab' => 'marketplace', 'status' => 'completed', 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date, 'q' => $q]) }}"
               class="trx-substatus-pill {{ $subStatus === 'completed' ? 'active' : '' }}">
                Selesai ({{ number_format($subStatusCounts['completed']) }})
            </a>
        </div>
    @endif

    {{-- Special Financial Clearance Bar for Finance Tab --}}
    @if($tab === 'finance')
        <div class="trx-finance-recap">
            <div class="trx-frecap-box green-border">
                <div class="trx-frecap-title">Dana Sudah Ditransfer (Withdrawal)</div>
                <div class="trx-frecap-val" style="color:#15803D;">Rp {{ number_format($financeStats['payout_processed'] ?? 0, 0, ',', '.') }}</div>
                <div class="trx-frecap-sub">Penarikan yang telah berhasil dikirim ke rekening bank mitra</div>
            </div>
            <div class="trx-frecap-box amber-border">
                <div class="trx-frecap-title">Perlu Ditransfer (Antrean Payout)</div>
                <div class="trx-frecap-val" style="color:#B45309;">Rp {{ number_format($financeStats['payout_pending'] ?? 0, 0, ',', '.') }}</div>
                <div class="trx-frecap-sub">
                    @if(($financeStats['payout_pending_count'] ?? 0) > 0)
                        <a href="{{ route('admin.payouts.index', ['status' => 'pending']) }}" style="color:#B45309;font-weight:700;text-decoration:underline;">
                            {{ $financeStats['payout_pending_count'] }} permohonan perlu diproses &rarr;
                        </a>
                    @else
                        Tidak ada antrean pencairan dana
                    @endif
                </div>
            </div>
            <div class="trx-frecap-box">
                <div class="trx-frecap-title">Saldo Mengendap (Belum Ditarik)</div>
                <div class="trx-frecap-val">Rp {{ number_format($financeStats['outstanding'] ?? 0, 0, ',', '.') }}</div>
                <div class="trx-frecap-sub">Hak mitra yang tersimpan di saldo dompet dan belum ditarik</div>
            </div>
        </div>
    @endif

    {{-- Search Bar --}}
    <form class="trx-search-wrap" method="GET" action="{{ route('admin.orders.index') }}">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <input type="hidden" name="status" value="{{ $subStatus }}">
        <input type="hidden" name="period" value="{{ $period }}">
        <input type="hidden" name="start_date" value="{{ $start_date }}">
        <input type="hidden" name="end_date" value="{{ $end_date }}">

        <svg width="16" height="16" fill="none" stroke="#94A3B8" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" name="q" class="trx-search-input" placeholder="Cari kode Order ID (MKT, TIX, POS, AMP), nama pelanggan, atau nama toko..." value="{{ $q }}">

        <button type="submit" class="trx-search-btn">Cari</button>
        @if($q)
            <a href="{{ route('admin.orders.index', ['tab' => $tab, 'status' => $subStatus, 'period' => $period, 'start_date' => $start_date, 'end_date' => $end_date]) }}" class="trx-reset-btn">Reset</a>
        @endif
    </form>

    {{-- Main Responsive Table --}}
    <div class="trx-table-card">
        <div class="trx-table-scroll">
            <table class="trx-table">
                <thead>
                    <tr>
                        <th>Order ID / Ref</th>
                        <th>Waktu</th>
                        <th>Pelanggan</th>
                        <th>Toko / Mitra</th>
                        <th>Metode</th>
                        <th style="text-align:right;">Total Gross</th>
                        <th style="text-align:right;">Jatah Mitra</th>
                        <th style="text-align:right;">Profit Buyle</th>
                        <th>Status</th>
                        <th style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $row)
                        @php
                            $isStandardOrder = isset($row->order_number) && is_object($row) && !isset($row->pillar);
                            
                            if ($isStandardOrder) {
                                $oid = $row->order_number;
                                $tagType = 'MKT';
                                if ($tab === 'ticketing' || str_starts_with($oid, 'TIX-')) {
                                    $tagType = 'TIX';
                                } elseif ($tab === 'pos' || str_starts_with($oid, 'POS-')) {
                                    $tagType = 'POS';
                                }

                                $custName = $row->user->name ?? ($row->shipping_address['receiver_name'] ?? ($row->shipping_address['customer_name'] ?? 'Pelanggan'));
                                $custPhone = $row->user_phone ?? ($row->shipping_address['phone'] ?? ($row->shipping_address['customer_phone'] ?? '-'));
                                $partnerName = $row->items->first()?->product?->seller?->name ?? ($tagType === 'POS' ? 'Kasir POS' : 'Creator');
                                $method = $row->payment?->method ? strtoupper($row->payment->method) : ($row->shipping_address['payment_method'] ?? 'MIDTRANS');
                                $grossAmt = (float)$row->total;
                                $platFee = (float)$row->platform_fee;
                                $admFee = (float)$row->admin_fee;
                                $profitAmt = $platFee + $admFee;
                                $partnerNet = max(0, (float)$row->subtotal - $platFee);
                                $stValue = is_object($row->status) ? $row->status->value : (string)$row->status;

                                $stColor = match($stValue) {
                                    'confirmed', 'completed', 'delivered' => '#15803D',
                                    'processing', 'shipped'               => '#0284C7',
                                    'cancelled', 'refunded'               => '#DC2626',
                                    default                               => '#D97706',
                                };
                                $stBg = match($stValue) {
                                    'confirmed', 'completed', 'delivered' => '#DCFCE7',
                                    'processing', 'shipped'               => '#E0F2FE',
                                    'cancelled', 'refunded'               => '#FEE2E2',
                                    default                               => '#FEF3C7',
                                };
                                $stLabel = match($stValue) {
                                    'confirmed'  => 'Lunas',
                                    'processing' => 'Diproses',
                                    'shipped'    => 'Dikirim',
                                    'completed'  => 'Selesai',
                                    'delivered'  => 'Terkirim',
                                    'cancelled'  => 'Batal',
                                    default      => ucfirst($stValue),
                                };
                                $detailUrl = route('admin.orders.show', $row);
                            } else {
                                // Unified row (Wedding Amplop, Finance Payout, dll)
                                $oid = $row->order_number;
                                $tagType = $row->pillar ?? 'AMP';
                                $custName = $row->customer_name;
                                $custPhone = $row->customer_info ?? '-';
                                $partnerName = $row->partner_name;
                                $method = $row->channel;
                                $grossAmt = (float)$row->gross;
                                $partnerNet = (float)$row->partner_net;
                                $profitAmt = (float)$row->profit;
                                $stLabel = $row->status;
                                $stColor = $row->status_color ?? '#15803D';
                                $stBg = $row->status_bg ?? '#DCFCE7';
                                $detailUrl = '#';
                            }
                        @endphp
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.45rem;">
                                    <span class="trx-tag trx-tag-{{ strtolower($tagType) }}">{{ $tagType }}</span>
                                    <strong style="color:#0F172A;font-weight:700;">{{ $oid }}</strong>
                                </div>
                            </td>
                            <td style="color:#64748B;">
                                {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d M Y, H:i') : '-' }}
                            </td>
                            <td>
                                <div style="font-weight:600;color:#0F172A;">{{ $custName }}</div>
                                <div style="font-size:0.72rem;color:#64748B;">{{ $custPhone }}</div>
                            </td>
                            <td style="color:#334155;font-weight:600;">
                                {{ $partnerName }}
                            </td>
                            <td>
                                <span style="font-size:0.75rem;font-weight:600;color:#475569;">{{ strtoupper($method) }}</span>
                            </td>
                            <td style="text-align:right;font-weight:700;color:{{ $grossAmt < 0 ? '#DC2626' : '#0F172A' }};">
                                Rp {{ number_format($grossAmt, 0, ',', '.') }}
                            </td>
                            <td style="text-align:right;font-weight:600;color:#475569;">
                                Rp {{ number_format($partnerNet, 0, ',', '.') }}
                            </td>
                            <td style="text-align:right;font-weight:700;color:#15803D;">
                                @if($profitAmt > 0)
                                    + Rp {{ number_format($profitAmt, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span style="display:inline-block;padding:0.25rem 0.6rem;border-radius:50px;font-size:0.7rem;font-weight:700;background:{{ $stBg }};color:{{ $stColor }};">
                                    {{ $stLabel }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                @if($isStandardOrder)
                                    <a href="{{ $detailUrl }}" class="trx-action-link">Detail</a>
                                @elseif($tagType === 'WD')
                                    <a href="{{ route('admin.payouts.index') }}" class="trx-action-link">Cek Payout</a>
                                @else
                                    <span style="font-size:0.75rem;color:#94A3B8;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="trx-empty">
                                <div class="trx-empty-title">Belum Ada Transaksi</div>
                                <div>Tidak ada riwayat transaksi yang sesuai dengan filter periode atau pencarian ini.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div style="padding:1rem 1.25rem;border-top:1px solid #F1F5F9;display:flex;justify-content:flex-end;">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Modal Export Laporan --}}
<div class="trx-modal-overlay" id="exportModal">
    <div class="trx-modal-box">
        <div class="trx-modal-head">
            <h3 style="margin:0;font-size:1.05rem;font-weight:700;color:#0F172A;">Unduh Laporan Transaksi</h3>
            <button type="button" onclick="closeExportModal()" style="background:none;border:none;cursor:pointer;color:#64748B;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <form method="GET" action="{{ route('admin.orders.export') }}" target="_blank">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input type="hidden" name="status" value="{{ $subStatus }}">
            <input type="hidden" name="start_date" value="{{ $start_date }}">
            <input type="hidden" name="end_date" value="{{ $end_date }}">

            <div style="margin-bottom:1rem;font-size:0.8125rem;color:#475569;">
                Pilih format data yang ingin diunduh untuk tab <strong>{{ $tabs[$tab]['label'] ?? 'Transaksi' }}</strong>:
            </div>

            <div style="display:flex;gap:0.75rem;margin-bottom:1.5rem;">
                <label style="flex:1;display:flex;align-items:center;gap:0.5rem;padding:0.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;cursor:pointer;">
                    <input type="radio" name="format" value="xlsx" checked>
                    <div>
                        <strong style="font-size:0.8125rem;color:#0F172A;">Excel (.xlsx)</strong>
                        <div style="font-size:0.7rem;color:#64748B;">Format tabel lengkap</div>
                    </div>
                </label>
                <label style="flex:1;display:flex;align-items:center;gap:0.5rem;padding:0.75rem 1rem;border:1.5px solid #E2E8F0;border-radius:10px;cursor:pointer;">
                    <input type="radio" name="format" value="csv">
                    <div>
                        <strong style="font-size:0.8125rem;color:#0F172A;">CSV (.csv)</strong>
                        <div style="font-size:0.7rem;color:#64748B;">Format teks data</div>
                    </div>
                </label>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:0.5rem;">
                <button type="button" onclick="closeExportModal()" class="trx-btn-filter" style="cursor:pointer;">Batal</button>
                <button type="submit" class="trx-btn-filter trx-btn-download" style="cursor:pointer;" onclick="setTimeout(closeExportModal, 500)">Unduh Sekarang</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openExportModal() {
    document.getElementById('exportModal').style.display = 'flex';
}
function closeExportModal() {
    document.getElementById('exportModal').style.display = 'none';
}
document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) closeExportModal();
});
</script>
@endpush

@endsection
