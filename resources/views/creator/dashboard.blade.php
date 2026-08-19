@extends('creator.layout')

@section('title', 'Beranda Creator')
@section('page_title', 'Beranda')
@section('breadcrumb', 'Overview')

@section('topbar_actions')
    <a href="{{ route('creator.products.create') }}" class="btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Produk
    </a>
@endsection

@section('styles')
<style>
    /* ── STAT GRID ── */
    .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
    .stat-card {
        background: #fff; border-radius: 16px; padding: 1.25rem 1.5rem;
        border: 1px solid #e7f0e7;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        transition: box-shadow 0.2s;
        display: flex; flex-direction: column; gap: 0.5rem;
    }
    .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.07); }
    .stat-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 0.25rem;
    }
    .stat-icon.green { background: #dcfce7; color: #15803d; }
    .stat-icon.lime { background: #f7fee7; color: #65a30d; }
    .stat-icon.emerald { background: #ecfdf5; color: #059669; }
    .stat-icon.amber { background: #fffbeb; color: #d97706; }
    .stat-label { font-size: 0.72rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 1.75rem; font-weight: 800; color: #0f1f0f; letter-spacing: -0.03em; line-height: 1.1; }
    .stat-sub { font-size: 0.72rem; color: #64748B; }

    /* ── SECTION CARD ── */
    .section-card {
        background: #fff; border-radius: 16px; border: 1px solid #e7f0e7;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem;
    }
    .section-header {
        padding: 1rem 1.5rem; border-bottom: 1px solid #f1f5f1;
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }
    .section-header h2 { font-size: 0.9rem; font-weight: 800; color: #0f1f0f; }
    .section-header p { font-size: 0.75rem; color: #64748B; margin-top: 0.1rem; }
    .section-body { padding: 1.25rem 1.5rem; }

    /* ── TABLE ── */
    .cr-table { width: 100%; border-collapse: collapse; }
    .cr-table th {
        text-align: left; font-size: 0.68rem; font-weight: 700; color: #94A3B8;
        text-transform: uppercase; letter-spacing: 0.07em;
        padding: 0.6rem 1rem; border-bottom: 2px solid #f1f5f1;
    }
    .cr-table td { padding: 0.875rem 1rem; font-size: 0.82rem; color: #374151; border-bottom: 1px solid #f8f9fa; }
    .cr-table tr:last-child td { border-bottom: none; }
    .cr-table tbody tr:hover td { background: #f9fefb; }

    /* ── BADGES ── */
    .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.68rem; font-weight: 700; }
    .badge-green { background: #dcfce7; color: #15803d; }
    .badge-red { background: #fee2e2; color: #b91c1c; }
    .badge-gray { background: #f1f5f9; color: #64748B; }
    .badge-amber { background: #fef3c7; color: #92400e; }

    /* ── PRODUCT MINI CARD ── */
    .product-mini { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f1; }
    .product-mini:last-child { border-bottom: none; padding-bottom: 0; }
    .product-mini-thumb {
        width: 44px; height: 44px; border-radius: 10px; object-fit: cover;
        background: #f1f5f1; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; color: #94A3B8;
        overflow: hidden;
    }
    .product-mini-name { font-size: 0.82rem; font-weight: 700; color: #0f1f0f; }
    .product-mini-cat { font-size: 0.7rem; color: #94A3B8; margin-top: 0.1rem; }
    .product-mini-price { font-size: 0.82rem; font-weight: 800; color: #1eb349; margin-left: auto; white-space: nowrap; }

    /* ── BALANCE CARD ── */
    .balance-card {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        border-radius: 16px; padding: 1.5rem;
        color: #fff; margin-bottom: 1.5rem;
        position: relative; overflow: hidden;
    }
    .balance-card::before {
        content: ''; position: absolute; right: -30px; top: -40px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }
    .balance-card::after {
        content: ''; position: absolute; right: 40px; bottom: -50px;
        width: 120px; height: 120px; border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }
    .balance-label { font-size: 0.72rem; font-weight: 700; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.08em; }
    .balance-amount { font-size: 2rem; font-weight: 800; letter-spacing: -0.04em; margin: 0.25rem 0 0.75rem; line-height: 1; }
    .balance-meta { font-size: 0.75rem; opacity: 0.8; }
    .btn-withdraw {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);
        color: #fff; border-radius: 8px; padding: 0.5rem 1rem;
        font-family: 'Montserrat', sans-serif; font-size: 0.8rem; font-weight: 700;
        cursor: pointer; text-decoration: none; margin-top: 1rem;
        transition: all 0.2s; backdrop-filter: blur(4px);
    }
    .btn-withdraw:hover { background: rgba(255,255,255,0.3); color: #fff; }

    /* ── EMPTY STATE ── */
    .empty-state { text-align: center; padding: 2.5rem 1rem; color: #94A3B8; }
    .empty-state svg { margin-bottom: 1rem; opacity: 0.4; }
    .empty-state h3 { font-size: 0.95rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem; }
    .empty-state p { font-size: 0.8rem; }

    @media (max-width: 991px) {
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .stat-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
        .stat-value { font-size: 1.375rem; }
    }
</style>
@endsection

@section('content')

{{-- ── STATUS BANNER ── --}}
@if(!$seller->email_verified_at)
<div style="background:#fef3c7; border:1px solid #fde68a; border-radius:12px; padding:0.875rem 1.25rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:0.75rem; font-size:0.82rem; color:#92400e;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    Email belum terverifikasi. Verifikasi email untuk mengaktifkan fitur penjualan penuh.
</div>
@endif

{{-- ── STAT GRID ── --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon green">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div class="stat-label">Total GMV</div>
        <div class="stat-value">Rp {{ number_format($gmv, 0, ',', '.') }}</div>
        <div class="stat-sub">Semua penjualan sukses</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon lime">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        </div>
        <div class="stat-label">Transaksi</div>
        <div class="stat-value">{{ $totalTransactions }}</div>
        <div class="stat-sub">Order sukses terbayar</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon emerald">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        </div>
        <div class="stat-label">Produk Aktif</div>
        <div class="stat-value">{{ $activeProducts }}<span style="font-size:1rem; color:#94A3B8;">/{{ $totalProducts }}</span></div>
        <div class="stat-sub">Dari total {{ $totalProducts }} produk</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div class="stat-label">Platform Fee</div>
        <div class="stat-value">{{ $platformFeeRate }}%</div>
        <div class="stat-sub">Rp {{ number_format($platformFee, 0, ',', '.') }}</div>
    </div>
</div>

{{-- ── ROW: BALANCE + RECENT PRODUCTS ── --}}
<div style="display:grid; grid-template-columns:1fr 1.5fr; gap:1.25rem; margin-bottom:1.5rem; align-items:start;">

    {{-- Balance Card --}}
    <div>
        <div class="balance-card">
            <div class="balance-label">💰 Saldo Tersedia</div>
            <div class="balance-amount">Rp {{ number_format($availableBalance, 0, ',', '.') }}</div>
            <div class="balance-meta">Sudah dicairkan: Rp {{ number_format($totalPayout, 0, ',', '.') }}</div>
            <a href="{{ route('creator.payout.settings') }}" class="btn-withdraw">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg>
                Ajukan Pencairan
            </a>
        </div>

        {{-- Status Akun --}}
        <div class="section-card">
            <div class="section-header">
                <div>
                    <h2>Status Akun</h2>
                    <p>Informasi seller Anda</p>
                </div>
            </div>
            <div class="section-body" style="display:flex; flex-direction:column; gap:0.75rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; font-size:0.82rem;">
                    <span style="color:#64748B;">Status</span>
                    <span class="badge badge-green">✅ Aktif</span>
                </div>
                <div style="display:flex; align-items:center; justify-content:space-between; font-size:0.82rem;">
                    <span style="color:#64748B;">Bergabung</span>
                    <span style="font-weight:600; color:#374151;">{{ $seller->created_at->format('d M Y') }}</span>
                </div>
                <div style="display:flex; align-items:center; justify-content:space-between; font-size:0.82rem;">
                    <span style="color:#64748B;">Email</span>
                    <span style="font-weight:600; color:#374151; font-size:0.75rem;">{{ $seller->email }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Products --}}
    <div class="section-card">
        <div class="section-header">
            <div>
                <h2>Produk Terbaru</h2>
                <p>5 produk terakhir yang Anda tambahkan</p>
            </div>
            <a href="{{ route('creator.products.index') }}" style="font-size:0.75rem; font-weight:700; color:#1eb349; text-decoration:none;">Lihat Semua →</a>
        </div>
        <div class="section-body" style="padding-top:0.25rem; padding-bottom:0.25rem;">
            @forelse($recentProducts as $p)
                <div class="product-mini">
                    <div class="product-mini-thumb">
                        @if($p->image)
                            <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                        @endif
                    </div>
                    <div>
                        <div class="product-mini-name">{{ Str::limit($p->name, 30) }}</div>
                        <div class="product-mini-cat">{{ $p->category->name ?? 'Tanpa Kategori' }} • {{ $p->is_active ? '✅ Aktif' : '⏸ Non-aktif' }}</div>
                    </div>
                    <div class="product-mini-price">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                </div>
            @empty
                <div class="empty-state">
                    <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    <h3>Belum ada produk</h3>
                    <p>Mulai tambahkan produk digital Anda</p>
                    <a href="{{ route('creator.products.create') }}" class="btn-primary" style="margin-top:1rem; display:inline-flex;">+ Tambah Produk</a>
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- ── PENJUALAN TERBARU ── --}}
<div class="section-card">
    <div class="section-header">
        <div>
            <h2>Penjualan Terbaru</h2>
            <p>Order sukses 30 hari terakhir</p>
        </div>
        <a href="{{ route('creator.sales.report') }}" style="font-size:0.75rem; font-weight:700; color:#1eb349; text-decoration:none;">Laporan Lengkap →</a>
    </div>
    @if($recentSales->count())
    <div style="overflow-x:auto;">
        <table class="cr-table">
            <thead>
                <tr>
                    <th>No. Order</th>
                    <th>Produk</th>
                    <th>Pembeli</th>
                    <th>Total</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentSales as $order)
                    @foreach($order->items as $item)
                    <tr>
                        <td style="font-weight:700; color:#0f1f0f;">#{{ $order->order_number ?? $order->id }}</td>
                        <td>{{ Str::limit($item->product->name ?? 'Produk', 35) }}</td>
                        <td style="color:#64748B;">{{ $order->user->name ?? '-' }}</td>
                        <td style="font-weight:700; color:#1eb349;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        <td style="color:#94A3B8;">{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state">
        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        <h3>Belum ada penjualan</h3>
        <p>Penjualan sukses akan muncul di sini setelah pembeli melakukan pembayaran.</p>
    </div>
    @endif
</div>

@endsection
