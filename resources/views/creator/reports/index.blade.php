@extends('creator.layout')

@section('title', 'Laporan Penjualan – Creator Studio')

@section('styles')
<style>
    .cr-dash-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .cr-dash-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #0b120c;
        margin: 0 0 0.25rem 0;
    }
    .cr-dash-sub {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
    }

    .report-filters {
        display: flex;
        gap: 0.75rem;
        background: #fff;
        padding: 0.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
    }
    .report-filter-btn {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        background: transparent;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .report-filter-btn:hover {
        background: #f8fafc;
        color: #0f172a;
    }
    .report-filter-btn.active {
        background: #1eb349;
        color: #fff;
    }

    /* Metric Cards */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .metric-card {
        background: #fff;
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .metric-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    .metric-val {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .panel-card {
        background: #fff;
        border-radius: 24px;
        padding: 1.5rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 8px 25px rgba(0,0,0,0.03);
    }
    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .panel-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #fff;
        background: #0f172a;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-export:hover {
        background: #1eb349;
    }

    /* Tables */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }
    .data-table th {
        text-align: left;
        padding: 0.75rem;
        color: #64748b;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
    }
    .data-table td {
        padding: 0.75rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 500;
    }
    .data-table tr:last-child td { border-bottom: none; }
    
    .table-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        background: #f1f5f9;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
    }
    .table-badge.success { background: #dcfce7; color: #166534; }

    /* List items for Sidebar Panel */
    .list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .list-item:last-child { border-bottom: none; }
    .list-title { font-weight: 600; font-size: 0.85rem; color: #1e293b; }
    .list-stat { font-weight: 700; font-size: 0.85rem; color: #1eb349; }

    @media (max-width: 1024px) {
        .metrics-grid { grid-template-columns: repeat(2, 1fr); }
        .content-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .metrics-grid { grid-template-columns: 1fr; }
        .report-filters { overflow-x: auto; white-space: nowrap; width: 100%; }
        .data-table { display: block; overflow-x: auto; white-space: nowrap; }
    }
</style>
@endsection

@section('content')

<div class="cr-dash-header">
    <div>
        <h1 class="cr-dash-title">Laporan Penjualan</h1>
        <p class="cr-dash-sub">Pantau trafik visitor, sumber klik, dan data pembelian produkmu.</p>
    </div>
    <div class="report-filters">
        <a href="{{ route('creator.sales.report', ['filter' => '7']) }}" class="report-filter-btn {{ $filter == '7' ? 'active' : '' }}">7 Hari</a>
        <a href="{{ route('creator.sales.report', ['filter' => '30']) }}" class="report-filter-btn {{ $filter == '30' ? 'active' : '' }}">30 Hari</a>
        <a href="{{ route('creator.sales.report', ['filter' => '90']) }}" class="report-filter-btn {{ $filter == '90' ? 'active' : '' }}">90 Hari</a>
        <!-- Custom date can be added via JS modal in future -->
    </div>
</div>

<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-title">Total Visitor</div>
        <div class="metric-val">{{ number_format($totalVisitors) }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-title">Unique Visitor</div>
        <div class="metric-val">{{ number_format($uniqueVisitors) }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-title">Total Transaksi</div>
        <div class="metric-val">{{ number_format($totalOrders) }}</div>
    </div>
    <div class="metric-card" style="background: linear-gradient(135deg, #0b120c, #1e293b); border:none;">
        <div class="metric-title" style="color: #94a3b8;">Total Penjualan</div>
        <div class="metric-val" style="color: #fff;">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
    </div>
</div>

<div class="content-grid">
    {{-- Left: Data Pembeli --}}
    <div class="panel-card">
        <div class="panel-header">
            <h3 class="panel-title">Data Pembeli</h3>
            <div style="display:flex; gap:0.5rem;">
                <a href="{{ route('creator.sales.report.export', ['filter' => $filter, 'format' => 'xls']) }}" class="btn-export">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    XLS
                </a>
                <a href="{{ route('creator.sales.report.export', ['filter' => $filter, 'format' => 'pdf']) }}" class="btn-export" style="background:#dc2626;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    PDF
                </a>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pembeli</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>UTM Source</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buyers as $order)
                        <tr>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <div>{{ $order->user->name ?? 'Guest' }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">{{ $order->user->email ?? '' }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">{{ $order->user->phone ?? '' }}</div>
                            </td>
                            <td>
                                @foreach($order->items as $item)
                                    <div class="table-badge">{{ $item->product_name }}</div>
                                @endforeach
                            </td>
                            <td style="font-weight:700;">Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</td>
                            <td>
                                @if($order->utm_source)
                                    <span class="table-badge success">{{ $order->utm_source }}</span>
                                @else
                                    <span style="color:#94a3b8;font-size:0.8rem;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 2rem;">Belum ada data pembeli di rentang waktu ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right: Top Products & UTM --}}
    <div>
        <div class="panel-card" style="margin-bottom:1.5rem;">
            <h3 class="panel-title" style="margin-bottom:1rem;">Produk Terpopuler</h3>
            @forelse($topProducts as $prod)
                <div class="list-item">
                    <div class="list-title">{{ $prod->name }}
                        <div style="font-size:0.7rem; color:#64748b; font-weight:500;">Rp {{ number_format($prod->sold_amount, 0,',','.') }}</div>
                    </div>
                    <div class="list-stat">{{ number_format($prod->visits_count) }} Kunjungan</div>
                </div>
            @empty
                <div style="font-size:0.85rem; color:#64748b;">Belum ada data klik produk.</div>
            @endforelse
        </div>

        <div class="panel-card">
            <h3 class="panel-title" style="margin-bottom:1rem;">Sumber Trafik (UTM)</h3>
            @forelse($utmSources as $utm)
                <div class="list-item">
                    <div class="list-title">{{ $utm->utm_source }}</div>
                    <div class="list-stat">{{ $utm->count }} Transaksi</div>
                </div>
            @empty
                <div style="font-size:0.85rem; color:#64748b;">Belum ada data UTM.</div>
            @endforelse
        </div>
    </div>
</div>

@endsection
