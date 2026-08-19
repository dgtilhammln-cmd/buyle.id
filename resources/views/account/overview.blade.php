@extends('account.layout')
@section('title', 'Overview – Akun')

@section('acc_page')

{{-- STAT CARDS --}}
<div class="acc-stat-grid">
    <div class="acc-stat-card">
        <div class="acc-stat-label">Total Pesanan</div>
        <div class="acc-stat-value">{{ $totalOrders }}</div>
    </div>
    <div class="acc-stat-card">
        <div class="acc-stat-label">Pesanan Aktif</div>
        <div class="acc-stat-value blue">{{ $activeOrders }}</div>
    </div>
    <div class="acc-stat-card">
        <div class="acc-stat-label">Total Belanja</div>
        <div class="acc-stat-value">Rp {{ number_format($totalSpent, 0, ',', '.') }}</div>
    </div>
    <div class="acc-stat-card">
        <div class="acc-stat-label">Alamat Tersimpan</div>
        <div class="acc-stat-value">{{ $totalAddresses }}</div>
    </div>
</div>

{{-- RECENT ORDERS --}}
<div class="acc-card">
    <div class="acc-card-header">
        <h2>Pesanan Terbaru</h2>
        <p>5 transaksi terakhir Anda</p>
    </div>
    <div class="acc-card-body" style="padding:0;">
        @if($recentOrders->count())
            <div style="overflow-x:auto;">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td style="font-weight:600; color:#0f172a;">#{{ $order->order_number ?? $order->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
                            <td style="font-weight:700;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            <td>
                                @php $s = $order->status->value ?? $order->status; @endphp
                                <span class="order-badge badge-{{ $s }}">
                                    {{ match($s) {
                                        'pending'    => 'Menunggu',
                                        'processing' => 'Diproses',
                                        'shipped'    => 'Dikirim',
                                        'completed'  => 'Selesai',
                                        'cancelled'  => 'Dibatalkan',
                                        default      => ucfirst($s),
                                    } }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <h3>Belum ada pesanan</h3>
                <p>Temukan produk terbaik untuk kebutuhan rumah Anda.</p>
                <a href="{{ route('products') }}" style="display:inline-flex;align-items:center;gap:0.5rem;margin-top:1rem;background:#0EA5E9;color:#fff;padding:0.6rem 1.25rem;border-radius:10px;font-size:0.85rem;font-weight:700;text-decoration:none;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/></svg>
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
