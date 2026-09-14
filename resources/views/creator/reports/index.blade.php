@extends('creator.layout')

@section('title', 'Laporan Penjualan – Creator Studio')
@section('page_title', 'Laporan Penjualan')
@section('page_subtitle', 'Pantau trafik visitor, sumber klik, dan data pembelian produkmu.')

@section('topbar_actions')
    <div class="filter-bar">
        <a href="{{ route('creator.sales.report', ['filter' => '7']) }}"  class="filter-btn {{ $filter === '7'  ? 'active' : '' }}">7 Hari</a>
        <a href="{{ route('creator.sales.report', ['filter' => '30']) }}" class="filter-btn {{ $filter === '30' ? 'active' : '' }}">30 Hari</a>
        <a href="{{ route('creator.sales.report', ['filter' => '90']) }}" class="filter-btn {{ $filter === '90' ? 'active' : '' }}">90 Hari</a>
        <div class="filter-divider"></div>
        <button type="button" class="filter-custom-btn {{ $filter === 'custom' ? 'active' : '' }}" id="btnOpenCustomDate">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            @if($filter === 'custom')
                {{ \Carbon\Carbon::parse($startDate)->format('d M') }} – {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            @else
                Custom
            @endif
        </button>
    </div>
@endsection

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
* { font-family: 'Montserrat', sans-serif; }

/* ── Header ─────────────────────────────────────────────── */
.rp-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.75rem;
    flex-wrap: wrap;
    gap: 1rem;
}
.rp-title { font-size: 1.6rem; font-weight: 700; color: #0f172a; margin: 0 0 0.2rem; }
.rp-sub   { font-size: 0.82rem; color: #64748b; margin: 0; font-weight: 400; }

/* ── Filter Bar ──────────────────────────────────────────── */
.filter-bar {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #fff;
    padding: 0.4rem;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    flex-wrap: wrap;
}
.filter-btn {
    padding: 0.45rem 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    background: transparent;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.filter-btn:hover  { background: #f8fafc; color: #0f172a; }
.filter-btn.active { background: linear-gradient(135deg, #1eb349, #a5cf37); color: #fff; }
.filter-divider    { width: 1px; height: 20px; background: #e2e8f0; }
.filter-custom-btn {
    padding: 0.45rem 0.9rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #475569;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    transition: 0.2s;
}
.filter-custom-btn:hover { border-color: #1eb349; color: #1eb349; }
.filter-custom-btn.active { background: linear-gradient(135deg, #1eb349, #a5cf37); color: #fff; border-color: transparent; }

/* ── Custom Date Modal ───────────────────────────────────── */
.date-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.35);
    z-index: 999;
    align-items: center;
    justify-content: center;
}
.date-modal-overlay.show { display: flex; }
.date-modal {
    background: #fff;
    border-radius: 20px;
    padding: 1.75rem;
    width: 360px;
    max-width: 95vw;
    box-shadow: 0 20px 60px rgba(0,0,0,0.18);
}
.date-modal h4 { font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0 0 1.25rem; }
.date-input-group { margin-bottom: 1rem; }
.date-input-group label { display: block; font-size: 0.78rem; font-weight: 600; color: #64748b; margin-bottom: 0.35rem; }
.date-input-group input {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.85rem;
    font-family: 'Montserrat', sans-serif;
    outline: none;
    transition: 0.2s;
    box-sizing: border-box;
}
.date-input-group input:focus { border-color: #1eb349; box-shadow: 0 0 0 3px rgba(30,179,73,0.12); }
.date-modal-actions { display: flex; gap: 0.75rem; margin-top: 1.25rem; }
.btn-apply {
    flex: 1;
    padding: 0.65rem;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
}
.btn-cancel {
    padding: 0.65rem 1rem;
    background: #f1f5f9;
    color: #475569;
    border: none;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
}

/* ── Metric Cards ──────────────────────────────────── */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.metric-card {
    background: #fff;
    border-radius: 18px;
    padding: 1.25rem 1.5rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,0.03);
    min-width: 0;
    overflow: hidden;
}
.metric-card.dark {
    background: linear-gradient(135deg, #0b120c, #1a2744);
    border: none;
}
.metric-label { font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
.metric-card.dark .metric-label { color: #94a3b8; }
.metric-value { font-size: 1.4rem; font-weight: 800; color: #0f172a; line-height: 1.1; word-break: break-word; }
.metric-card.dark .metric-value { color: #fff; }

/* ── Traffic Wave Chart Card ─────────────────────────────── */
.wave-card {
    background: #fff;
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    margin-bottom: 1.5rem;
    min-width: 0;
    overflow: hidden;
}
.wave-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
}
.wave-title { font-size: 0.95rem; font-weight: 700; color: #0f172a; }
.wave-live-badge {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #1eb349;
    background: #dcfce7;
    padding: 0.25rem 0.65rem;
    border-radius: 20px;
}
.wave-live-dot {
    width: 7px;
    height: 7px;
    background: #1eb349;
    border-radius: 50%;
    animation: pulse-dot 1.5s infinite;
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.5; transform: scale(1.3); }
}
.chart-wrap { position: relative; height: 140px; min-width: 0; width: 100%; }

/* ── Content Grid ────────────────────────────────────────── */
.main-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.5rem;
    align-items: start;
    min-width: 0;
}

/* ── Panel Card ──────────────────────────────────────────── */
.panel-card {
    background: #fff;
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    min-width: 0;
    overflow: hidden;
}
.panel-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.panel-title { font-size: 0.95rem; font-weight: 700; color: #0f172a; margin: 0; }
.export-group { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.btn-export {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #fff;
    background: #0f172a;
    padding: 0.4rem 0.85rem;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.2s;
    white-space: nowrap;
}
.btn-export:hover { background: #1eb349; }
.btn-export.red   { background: #dc2626; }
.btn-export.red:hover { background: #b91c1c; }

/* ── Data Table ──────────────────────────────────────────── */
.data-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
.data-table th {
    text-align: left;
    padding: 0.65rem 0.75rem;
    color: #64748b;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}
.data-table td {
    padding: 0.75rem;
    color: #334155;
    border-bottom: 1px solid #f8fafc;
    font-weight: 500;
    vertical-align: middle;
}
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: #fafafa; }

.td-user .name  { font-weight: 600; color: #1e293b; font-size: 0.82rem; }
.td-user .email { font-size: 0.72rem; color: #94a3b8; margin-top: 1px; }
.td-user .phone { font-size: 0.72rem; color: #94a3b8; }

.badge {
    display: inline-block;
    padding: 0.2rem 0.55rem;
    border-radius: 5px;
    font-size: 0.72rem;
    font-weight: 600;
    background: #f1f5f9;
    color: #475569;
    margin: 1px;
}
.badge.green { background: #dcfce7; color: #166534; }

/* ── Sidebar Lists ───────────────────────────────────────── */
.side-panel { margin-bottom: 1.25rem; }
.list-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 0.7rem 0;
    border-bottom: 1px dashed #e2e8f0;
    gap: 1rem;
}
.list-row:last-child { border-bottom: none; }
.list-name { font-weight: 600; font-size: 0.82rem; color: #1e293b; flex: 1; min-width: 0; }
.list-name small { display: block; font-weight: 400; color: #94a3b8; font-size: 0.7rem; margin-top: 1px; }
.list-stat { font-weight: 700; font-size: 0.8rem; color: #1eb349; white-space: nowrap; flex-shrink: 0; }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 1100px) {
    .main-grid { grid-template-columns: 1fr; }
    .metrics-grid { grid-template-columns: repeat(2, 1fr); }
    .metrics-grid .dark[style*="span 4"] { grid-column: span 2 !important; }
}
@media (max-width: 600px) {
    .metrics-grid { grid-template-columns: repeat(2, 1fr); gap: 0.5rem; }
    .metrics-grid .dark[style*="span 4"] { grid-column: span 2 !important; }
    .metric-card { padding: 0.85rem 1rem; }
    .metric-value { font-size: 1.05rem; }
    .rp-title { font-size: 1.3rem; }
    .filter-bar { gap: 0.25rem; }
    .data-table { width: 100%; }
    .wave-card { padding: 1rem; }
}
@media (max-width: 420px) {
    .metrics-grid { grid-template-columns: 1fr; }
    .metrics-grid .dark[style*="span 4"] { grid-column: span 1 !important; }
}
</style>
@endsection

@section('content')

{{-- ── Custom Date Modal ──────────────────────────────────────────── --}}
<div class="date-modal-overlay" id="customDateModal">
    <div class="date-modal">
        <h4>Pilih Periode Custom</h4>
        <form method="GET" action="{{ route('creator.sales.report') }}" id="customDateForm">
            <input type="hidden" name="filter" value="custom">
            <div class="date-input-group">
                <label>Dari Tanggal</label>
                <input type="date" name="start_date" id="inputStartDate" value="{{ $filter === 'custom' ? \Carbon\Carbon::parse($startDate)->format('Y-m-d') : \Carbon\Carbon::now()->subDays(30)->format('Y-m-d') }}" required>
            </div>
            <div class="date-input-group">
                <label>Sampai Tanggal</label>
                <input type="date" name="end_date" id="inputEndDate" value="{{ $filter === 'custom' ? \Carbon\Carbon::parse($endDate)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') }}" required>
            </div>
            <div class="date-modal-actions">
                <button type="button" class="btn-cancel" id="btnCloseModal">Batal</button>
                <button type="submit" class="btn-apply">Terapkan Filter</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Metric Cards ────────────────────────────────────────────────── --}}
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-label">Total Visitor</div>
        <div class="metric-value">{{ number_format($totalVisitors) }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Unique Visitor</div>
        <div class="metric-value">{{ number_format($uniqueVisitors) }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Total Transaksi</div>
        <div class="metric-value">{{ number_format($totalOrders) }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Link Klik Bio</div>
        <div class="metric-value">{{ number_format($totalBioClicks) }}</div>
    </div>
    <div class="metric-card dark" style="grid-column: span 4;">
        <div class="metric-label">Total Penjualan</div>
        <div class="metric-value">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
    </div>
</div>

{{-- ── Traffic Wave Chart ──────────────────────────────────────────── --}}
<div class="wave-card">
    <div class="wave-header">
        <span class="wave-title">TRAFFIC WAVE</span>
        <span class="wave-live-badge"><span class="wave-live-dot"></span> LIVE</span>
    </div>
    <div class="chart-wrap">
        <canvas id="trafficChart"></canvas>
    </div>
</div>

{{-- ── Main Grid: Buyers + Sidebar ────────────────────────────────── --}}
<div class="main-grid">

    {{-- Buyers Table --}}
    <div class="panel-card">
        <div class="panel-head">
            <h3 class="panel-title">Data Pembeli</h3>
            <div class="export-group">
                <a href="{{ route('creator.sales.report.export', array_merge(request()->query(), ['format' => 'xls'])) }}" class="btn-export">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export XLS
                </a>
                <a href="{{ route('creator.sales.report.export', array_merge(request()->query(), ['format' => 'pdf'])) }}" class="btn-export red">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export PDF
                </a>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal & ID</th>
                        <th>Pembeli</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Status Pesanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buyers as $order)
                        @php
                            $statusVal = is_object($order->status) ? $order->status->value : (string)$order->status;
                            $statusLabel = is_object($order->status) && method_exists($order->status, 'label') ? $order->status->label() : ucfirst($statusVal);
                            $statusBadgeStyle = match($statusVal) {
                                'completed' => 'background:#dcfce7;color:#166534;border:1px solid #bbf7d0;',
                                'shipped'   => 'background:#e0e7ff;color:#3730a3;border:1px solid #c7d2fe;',
                                'processing' => 'background:#fef3c7;color:#92400e;border:1px solid #fde68a;',
                                'cancelled'  => 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                                default      => 'background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;',
                            };
                            $shipment = $order->shipment;
                            $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
                        @endphp
                        <tr>
                            <td style="white-space:nowrap; color:#64748b; font-size:0.75rem;">
                                <div style="font-weight:700;color:#0f172a;">#{{ $order->order_number ?? ('ORD-' . $order->id) }}</div>
                                <div>{{ $order->created_at->format('d M Y H:i') }}</div>
                            </td>
                            <td class="td-user">
                                <div class="name" style="font-weight:700; color:#0f172a;">{{ $addr['name'] ?? $order->user?->name ?? 'Pembeli' }}</div>
                                @if(!empty($addr['phone']) || !empty($order->user?->phone))
                                    <div class="phone" style="font-size:0.75rem;color:#1eb349;font-weight:600;">{{ $addr['phone'] ?? $order->user?->phone }}</div>
                                @endif
                                <div class="email" style="font-size:0.72rem;color:#94a3b8;">{{ $order->user?->email ?? '' }}</div>
                            </td>
                            <td>
                                @foreach($order->items as $item)
                                    <div style="display:flex;align-items:center;gap:4px;margin-bottom:2px;">
                                        <span class="badge" style="font-weight:600;">{{ Str::limit($item->product_name, 28) }}</span>
                                        <span style="font-size:0.68rem;color:#64748b;">(x{{ $item->quantity }})</span>
                                    </div>
                                @endforeach
                            </td>
                            <td style="font-weight:800; white-space:nowrap; color:#0f172a;">Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</td>
                            <td>
                                <span id="badge-status-{{ $order->id }}" class="badge" style="{{ $statusBadgeStyle }} font-weight:700; padding:3px 8px; border-radius:6px; font-size:0.72rem;">
                                    {{ $statusLabel }}
                                </span>
                                @if(!empty($shipment?->tracking_number))
                                    <div id="resi-text-{{ $order->id }}" style="font-size:0.7rem; color:#2563eb; font-weight:700; margin-top:3px;">
                                        <i class="fas fa-truck"></i> {{ $shipment->tracking_number }}
                                    </div>
                                @else
                                    <div id="resi-text-{{ $order->id }}" style="font-size:0.68rem; color:#94a3b8; margin-top:2px;">Belum ada resi</div>
                                @endif
                            </td>
                            <td>
                                <button type="button" onclick="openOrderModal({{ json_encode([
                                    'id' => $order->id,
                                    'order_number' => $order->order_number ?? ('ORD-' . $order->id),
                                    'date' => $order->created_at->format('d M Y H:i'),
                                    'status' => $statusVal,
                                    'status_label' => $statusLabel,
                                    'user_name' => $addr['name'] ?? $order->user?->name ?? 'Pembeli',
                                    'phone' => $addr['phone'] ?? $order->user?->phone ?? '',
                                    'email' => $order->user?->email ?? '',
                                    'shipping_address' => $addr,
                                    'items' => $order->items->map(fn($i) => [
                                        'name' => $i->product_name,
                                        'quantity' => $i->quantity,
                                        'price' => $i->price,
                                        'subtotal' => $i->subtotal,
                                    ])->values(),
                                    'total' => $order->items->sum('subtotal'),
                                    'courier_name' => $shipment?->courier_name ?? '',
                                    'tracking_number' => $shipment?->tracking_number ?? '',
                                    'update_url' => route('creator.sales.report.update_order', $order->id),
                                ]) }})" class="btn-export" style="background:#0f172a; color:#fff; border:none; padding:0.4rem 0.75rem; font-size:0.75rem; border-radius:8px; cursor:pointer; font-weight:700; white-space:nowrap;">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:3px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Detail & Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding: 2.5rem; color:#94a3b8; font-size:0.85rem;">
                                Belum ada data pembeli di rentang waktu ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Sidebar --}}
    <div>
        {{-- Top Products --}}
        <div class="panel-card side-panel">
            <h3 class="panel-title" style="margin-bottom:1rem;">Produk Terpopuler</h3>
            @forelse($topProducts as $prod)
                <div class="list-row">
                    <div class="list-name">
                        {{ Str::limit($prod->name, 32) }}
                        <small>Rp {{ number_format($prod->sold_amount ?? 0, 0, ',', '.') }}</small>
                    </div>
                    <div class="list-stat">{{ number_format($prod->visits_count) }} klik</div>
                </div>
            @empty
                <p style="font-size:0.82rem; color:#94a3b8; margin:0;">Belum ada data kunjungan produk.</p>
            @endforelse
        </div>

        {{-- Top Bio Link Clicks --}}
        <div class="panel-card side-panel">
            <h3 class="panel-title" style="margin-bottom:1rem;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:4px;"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                Klik Link Bio
            </h3>
            @forelse($topBioLinks as $link)
                <div class="list-row">
                    <div class="list-name">{{ Str::limit($link->page_title ?? 'Block #'.$link->bio_block_id, 32) }}</div>
                    <div class="list-stat">{{ number_format($link->click_count) }} klik</div>
                </div>
            @empty
                <p style="font-size:0.82rem; color:#94a3b8; margin:0;">Belum ada klik link bio tercatat.</p>
            @endforelse
        </div>

        {{-- Bio UTM Sources --}}
        @if($bioUtmSources->isNotEmpty())
        <div class="panel-card side-panel">
            <h3 class="panel-title" style="margin-bottom:1rem;">Sumber Trafik Bio</h3>
            @foreach($bioUtmSources as $utm)
                <div class="list-row">
                    <div class="list-name">{{ $utm->utm_source }}</div>
                    <div class="list-stat">{{ number_format($utm->count) }} klik</div>
                </div>
            @endforeach
        </div>
        @endif

        {{-- UTM Sources (orders) --}}
        <div class="panel-card">
            <h3 class="panel-title" style="margin-bottom:1rem;">Sumber Trafik Order</h3>
            @forelse($utmSources as $utm)
                <div class="list-row">
                    <div class="list-name">{{ $utm->utm_source }}</div>
                    <div class="list-stat">{{ number_format($utm->count) }} transaksi</div>
                </div>
            @empty
                <p style="font-size:0.82rem; color:#94a3b8; margin:0;">Belum ada data UTM tercatat.</p>
            @endforelse
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
// ── Traffic Wave Chart ────────────────────────────────────────────────
(function() {
    const filter = @json($filter);
    const days   = filter === '7' ? 7 : (filter === '90' ? 90 : 30);

    // Generate labels (day names or dates)
    const labels = [];
    const now    = new Date();
    for (let i = days - 1; i >= 0; i--) {
        const d = new Date(now);
        d.setDate(d.getDate() - i);
        labels.push(days <= 7
            ? d.toLocaleDateString('id-ID', { weekday: 'short' })
            : d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' })
        );
    }

    // Visitor data from PHP — group by date
    const rawVisitors = @json($visitorsByDate ?? []);
    const data = labels.map((_, idx) => {
        const d = new Date(now);
        d.setDate(d.getDate() - (days - 1 - idx));
        const key = d.toISOString().split('T')[0];
        return rawVisitors[key] ?? 0;
    });

    const ctx = document.getElementById('trafficChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data,
                borderColor: '#1eb349',
                backgroundColor: (context) => {
                    const g = context.chart.ctx.createLinearGradient(0, 0, 0, 130);
                    g.addColorStop(0, 'rgba(30,179,73,0.25)');
                    g.addColorStop(1, 'rgba(30,179,73,0)');
                    return g;
                },
                borderWidth: 2.5,
                tension: 0.45,
                fill: true,
                pointRadius: days <= 7 ? 5 : 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#1eb349',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: {
                callbacks: {
                    label: ctx => ` ${ctx.parsed.y} kunjungan`
                }
            }},
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'Montserrat', size: 11 }, color: '#94a3b8' } },
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Montserrat', size: 11 }, color: '#94a3b8', precision: 0 } }
            }
        }
    });
})();

// ── Custom Date Modal ─────────────────────────────────────────────────
const modal    = document.getElementById('customDateModal');
const btnOpen  = document.getElementById('btnOpenCustomDate');
const btnClose = document.getElementById('btnCloseModal');

if (btnOpen && modal) {
    btnOpen.addEventListener('click', () => modal.classList.add('show'));
    btnClose.addEventListener('click', () => modal.classList.remove('show'));
    modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('show'); });
}

// ── Order Detail & Edit Management Modal ────────────────────────────────
let currentOrderData = null;

function openOrderModal(data) {
    currentOrderData = data;
    document.getElementById('od_title').innerText = 'Detail Pesanan #' + data.order_number;
    document.getElementById('od_date').innerText = 'Tanggal Transaksi: ' + data.date;
    document.getElementById('od_user_name').innerText = data.user_name;
    
    let contactInfo = [];
    if (data.phone) contactInfo.push('📱 ' + data.phone);
    if (data.email) contactInfo.push('✉️ ' + data.email);
    document.getElementById('od_user_contact').innerText = contactInfo.join('  •  ');

    // WA Button link
    const waBtn = document.getElementById('od_wa_btn');
    if (data.phone) {
        let cleanPhone = data.phone.replace(/[^0-9]/g, '');
        if (cleanPhone.startsWith('0')) cleanPhone = '62' + cleanPhone.substring(1);
        const waText = encodeURIComponent(`Halo ${data.user_name}, mengenai pesanan #${data.order_number} di buyle.id...`);
        waBtn.href = `https://wa.me/${cleanPhone}?text=${waText}`;
        waBtn.style.display = 'inline-flex';
    } else {
        waBtn.style.display = 'none';
    }

    // Shipping Address Box
    const addrBox = document.getElementById('od_address_box');
    const addrContent = document.getElementById('od_address_content');
    const sa = data.shipping_address || {};
    if (sa.address || sa.city || sa.province || sa.district) {
        let lines = [];
        if (sa.name) lines.push(`<strong>Penerima:</strong> ${sa.name} (${sa.phone || ''})`);
        if (sa.address) lines.push(sa.address);
        let locParts = [sa.district, sa.city, sa.province, sa.postal_code].filter(Boolean);
        if (locParts.length) lines.push(locParts.join(', '));
        if (sa.notes) lines.push(`<em>Catatan: ${sa.notes}</em>`);

        addrContent.innerHTML = lines.join('<br>');
        addrBox.style.display = 'block';
    } else {
        addrBox.style.display = 'none';
    }

    // Render Ordered Items
    const itemsList = document.getElementById('od_items_list');
    let itemsHtml = '';
    (data.items || []).forEach(item => {
        itemsHtml += `
            <div style="display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0.85rem; border-bottom:1px solid #f1f5f9; font-size:0.82rem;">
                <div>
                    <strong style="color:#0f172a;">${item.name}</strong>
                    <div style="font-size:0.72rem; color:#64748b;">Rp ${Number(item.price).toLocaleString('id-ID')} x ${item.quantity}</div>
                </div>
                <div style="font-weight:700; color:#0f172a;">Rp ${Number(item.subtotal).toLocaleString('id-ID')}</div>
            </div>
        `;
    });
    itemsList.innerHTML = itemsHtml;
    document.getElementById('od_total_price').innerText = 'Rp ' + Number(data.total).toLocaleString('id-ID');

    // Set Form values
    document.getElementById('od_order_id').value = data.id;
    document.getElementById('od_update_url').value = data.update_url;
    document.getElementById('od_input_status').value = data.status || 'processing';
    document.getElementById('od_input_courier').value = data.courier_name || '';
    document.getElementById('od_input_resi').value = data.tracking_number || '';

    document.getElementById('orderDetailModal').classList.add('show');
}

function closeOrderModal() {
    document.getElementById('orderDetailModal').classList.remove('show');
}

function saveOrderData(e) {
    e.preventDefault();
    const btn = document.getElementById('od_btn_save');
    const orderId = document.getElementById('od_order_id').value;
    const url = document.getElementById('od_update_url').value;
    const statusVal = document.getElementById('od_input_status').value;
    const courierVal = document.getElementById('od_input_courier').value;
    const resiVal = document.getElementById('od_input_resi').value;

    btn.disabled = true;
    btn.innerText = '⏳ Menyimpan...';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            order_status: statusVal,
            courier_name: courierVal,
            tracking_number: resiVal
        })
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerText = '💾 Simpan Perubahan Status & Resi';
        if (res.success) {
            // Update table row badges live
            const badge = document.getElementById('badge-status-' + orderId);
            const resiText = document.getElementById('resi-text-' + orderId);

            if (badge) {
                badge.innerText = res.status_label;
                const bgStyles = {
                    'completed': 'background:#dcfce7;color:#166534;border:1px solid #bbf7d0;',
                    'shipped': 'background:#e0e7ff;color:#3730a3;border:1px solid #c7d2fe;',
                    'processing': 'background:#fef3c7;color:#92400e;border:1px solid #fde68a;',
                    'cancelled': 'background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;',
                };
                badge.style.cssText = (bgStyles[res.status] || 'background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;') + ' font-weight:700; padding:3px 8px; border-radius:6px; font-size:0.72rem;';
            }

            if (resiText) {
                if (res.tracking_number) {
                    resiText.innerHTML = `<i class="fas fa-truck"></i> ${res.tracking_number}`;
                    resiText.style.color = '#2563eb';
                    resiText.style.fontWeight = '700';
                } else {
                    resiText.innerText = 'Belum ada resi';
                    resiText.style.color = '#94a3b8';
                }
            }

            closeOrderModal();
            alert('🎉 ' + res.message);
        } else {
            alert('❌ Gagal memperbarui: ' + (res.message || 'Terjadi kesalahan'));
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerText = '💾 Simpan Perubahan Status & Resi';
        alert('❌ Terjadi kesalahan jaringan!');
    });
}
</script>

<!-- Modal Order Detail & Manajemen Pesanan -->
<div class="date-modal-overlay" id="orderDetailModal">
    <div class="date-modal" style="width:580px; max-width:95vw; border-radius:24px; padding:1.75rem;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; border-bottom:1px solid #f1f5f9; padding-bottom:0.85rem;">
            <div>
                <h4 style="font-size:1.1rem; font-weight:800; color:#0f172a; margin:0;" id="od_title">Detail Pesanan</h4>
                <div style="font-size:0.75rem; color:#64748b; margin-top:2px;" id="od_date"></div>
            </div>
            <button type="button" class="btn-cancel" onclick="closeOrderModal()" style="padding:0.35rem 0.75rem; border-radius:8px;">✕</button>
        </div>

        <div style="max-height:75vh; overflow-y:auto; padding-right:4px;">
            <!-- Customer Info & WA Chat Button -->
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:1rem; margin-bottom:1rem;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div style="font-size:0.72rem; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">Informasi Pembeli</div>
                        <div style="font-size:0.95rem; font-weight:800; color:#0f172a; margin-top:2px;" id="od_user_name"></div>
                        <div style="font-size:0.8rem; color:#475569; margin-top:1px;" id="od_user_contact"></div>
                    </div>
                    <a id="od_wa_btn" href="#" target="_blank" style="display:inline-flex; align-items:center; gap:6px; background:#25d366; color:#fff; padding:0.45rem 0.85rem; border-radius:10px; text-decoration:none; font-size:0.78rem; font-weight:700; box-shadow:0 2px 6px rgba(37,211,102,0.3);">
                        <i class="fab fa-whatsapp" style="font-size:14px;"></i> Hubungi WA
                    </a>
                </div>
            </div>

            <!-- Shipping Address (if available) -->
            <div id="od_address_box" style="display:none; background:#fff; border:1px dashed #cbd5e1; border-radius:14px; padding:1rem; margin-bottom:1rem;">
                <div style="font-size:0.72rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.4rem;">
                    📍 Alamat Pengiriman
                </div>
                <div id="od_address_content" style="font-size:0.82rem; color:#1e293b; line-height:1.5;"></div>
            </div>

            <!-- Products List -->
            <div style="margin-bottom:1.25rem;">
                <div style="font-size:0.72rem; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:0.5rem;">Produk Dipesan</div>
                <div id="od_items_list" style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;"></div>
                <div style="display:flex; justify-content:space-between; align-items:center; padding:0.75rem 1rem; background:#f8fafc; border-top:1px solid #e2e8f0; font-weight:800; font-size:0.9rem; color:#0f172a;">
                    <span>Total Pembayaran</span>
                    <span id="od_total_price" style="color:#1eb349;"></span>
                </div>
            </div>

            <!-- Form Edit Status & Resi -->
            <form id="od_form" onsubmit="saveOrderData(event)">
                <input type="hidden" id="od_order_id">
                <input type="hidden" id="od_update_url">

                <div style="background:#fff; border:1.5px solid #e2e8f0; border-radius:16px; padding:1.1rem; box-shadow:0 4px 14px rgba(0,0,0,0.03);">
                    <div style="font-size:0.85rem; font-weight:800; color:#0f172a; margin-bottom:0.85rem; display:flex; align-items:center; gap:6px;">
                        ✏️ Manajemen Status & Pengiriman
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.85rem; margin-bottom:0.85rem;">
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:0.3rem;">Status Pesanan</label>
                            <select id="od_input_status" style="width:100%; padding:0.55rem 0.75rem; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; font-weight:600; outline:none; font-family:'Montserrat',sans-serif;">
                                <option value="pending">Menunggu Pembayaran</option>
                                <option value="processing">Diproses (Menyiapkan Barang)</option>
                                <option value="shipped">Dikirim (Dalam Pengiriman)</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:0.3rem;">Ekspedisi / Kurir</label>
                            <input type="text" id="od_input_courier" placeholder="Misal: J&T, JNE, SiCepat, Express" style="width:100%; padding:0.55rem 0.75rem; border:1px solid #cbd5e1; border-radius:10px; font-size:0.82rem; outline:none; font-family:'Montserrat',sans-serif;">
                        </div>
                    </div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:0.3rem;">Nomor Resi Pengiriman (AWB)</label>
                        <input type="text" id="od_input_resi" placeholder="Masukkan No Resi... (contoh: JY1241520391)" style="width:100%; padding:0.6rem 0.8rem; border:1.5px solid #94a3b8; border-radius:10px; font-size:0.85rem; font-weight:700; color:#0f172a; outline:none; font-family:monospace;">
                    </div>

                    <button type="submit" id="od_btn_save" style="width:100%; padding:0.75rem; background:linear-gradient(135deg,#0f172a,#1e293b); color:#fff; border:none; border-radius:12px; font-size:0.85rem; font-weight:800; cursor:pointer; font-family:'Montserrat',sans-serif; transition:all 0.2s; box-shadow:0 4px 12px rgba(15,23,42,0.2);">
                        💾 Simpan Perubahan Status & Resi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
