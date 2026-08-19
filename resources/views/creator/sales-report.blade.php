@extends('creator.layout')
@section('title', 'Laporan Penjualan')
@section('page_title', 'Laporan Penjualan')
@section('breadcrumb', 'Keuangan › Laporan Penjualan')

@section('styles')
<style>
    .report-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
    .stat-c { background:#fff; border-radius:14px; border:1px solid #e7f0e7; padding:1.25rem; box-shadow:0 2px 8px rgba(0,0,0,0.03); }
    .stat-c .label { font-size:0.72rem; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.4rem; }
    .stat-c .val { font-size:1.5rem; font-weight:800; color:#0f1f0f; letter-spacing:-0.03em; }
    .section-card { background:#fff; border-radius:16px; border:1px solid #e7f0e7; box-shadow:0 2px 8px rgba(0,0,0,0.04); overflow:hidden; }
    .section-header { padding:1rem 1.5rem; border-bottom:1px solid #f3f7f3; }
    .section-header h2 { font-size:0.9rem; font-weight:800; color:#0f1f0f; }
    .cr-table { width:100%; border-collapse:collapse; }
    .cr-table th { text-align:left; font-size:0.68rem; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:0.07em; padding:0.75rem 1.25rem; background:#f9fefb; border-bottom:1.5px solid #e7f0e7; }
    .cr-table td { padding:0.875rem 1.25rem; font-size:0.82rem; color:#374151; border-bottom:1px solid #f3f7f3; }
    .cr-table tr:last-child td { border-bottom:none; }
    .cr-table tbody tr:hover td { background:#fafff8; }
    .empty-state { text-align:center; padding:3rem 1rem; color:#94A3B8; }
    @media(max-width:640px) { .report-stats { grid-template-columns:1fr; } }
</style>
@endsection

@section('content')
<div class="report-stats">
    <div class="stat-c">
        <div class="label">Total Pendapatan</div>
        <div class="val">Rp {{ number_format($gmv, 0, ',', '.') }}</div>
    </div>
    <div class="stat-c">
        <div class="label">Total Transaksi</div>
        <div class="val">{{ $totalSales }}</div>
    </div>
    <div class="stat-c">
        <div class="label">Saldo Bersih</div>
        <div class="val" style="color:#1eb349;">Rp {{ number_format($netBalance, 0, ',', '.') }}</div>
    </div>
</div>

<div class="section-card">
    <div class="section-header">
        <h2>📊 Semua Penjualan</h2>
    </div>
    @if($sales->count())
    <div style="overflow-x:auto;">
        <table class="cr-table">
            <thead><tr>
                <th>No. Order</th>
                <th>Produk</th>
                <th>Pembeli</th>
                <th>Pendapatan</th>
                <th>Tanggal</th>
            </tr></thead>
            <tbody>
                @foreach($sales as $order)
                    @foreach($order->items as $item)
                    <tr>
                        <td style="font-weight:700;color:#0f1f0f;">#{{ $order->order_number ?? $order->id }}</td>
                        <td>{{ Str::limit($item->product->name ?? '—', 40) }}</td>
                        <td style="color:#64748B;">{{ $order->user->name ?? '—' }}</td>
                        <td style="font-weight:700;color:#1eb349;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        <td style="color:#94A3B8;">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:1rem 1.25rem; border-top:1px solid #f3f7f3;">
        {{ $sales->links() }}
    </div>
    @else
    <div class="empty-state">
        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem; opacity:0.4;"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        <h3 style="font-size:0.95rem; font-weight:700; color:#374151; margin-bottom:0.5rem;">Belum Ada Data Penjualan</h3>
        <p style="font-size:0.8rem;">Data penjualan akan muncul di sini setelah ada transaksi sukses.</p>
    </div>
    @endif
</div>
@endsection
