@extends('layouts.admin')
@section('title', 'Riwayat Transaksi Custom Domain')
@section('page-title', 'Riwayat & Transaksi Custom Domain')
@section('content')

<style>
.dom-page { font-family: 'Montserrat', sans-serif; }
.dom-head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.dom-title { font-size: 1.5rem; font-weight: 700; color: #0F172A; margin: 0 0 .2rem; }
.dom-sub { font-size: .85rem; color: #64748B; margin: 0; }

.dom-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
.dom-stat-card { background: #fff; border-radius: 16px; border: 1.5px solid #E2E8F0; padding: 1.15rem 1.25rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
.dom-stat-label { font-size: .75rem; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: .35rem; }
.dom-stat-val { font-size: 1.45rem; font-weight: 700; color: #0F172A; }
.dom-stat-sub { font-size: .75rem; color: #16A34A; margin-top: .2rem; font-weight: 500; }

.dom-tabs { display: flex; gap: .5rem; margin-bottom: 1.25rem; flex-wrap: wrap; }
.dom-tab-btn {
    padding: .55rem 1.1rem; border-radius: 12px; font-size: .82rem; font-weight: 600;
    text-decoration: none; border: 1.5px solid #E2E8F0; color: #64748B; background: #fff;
    transition: all .2s; display: inline-flex; align-items: center; gap: .4rem;
}
.dom-tab-btn:hover { border-color: #CBD5E1; color: #1E293B; }
.dom-tab-btn.active { background: #0F172A; color: #fff; border-color: #0F172A; }

.dom-table-card { background: #fff; border-radius: 18px; border: 1.5px solid #E2E8F0; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
.dom-table { width: 100%; border-collapse: collapse; text-align: left; font-size: .83rem; }
.dom-table th { background: #F8FAFC; color: #475569; font-weight: 600; padding: .85rem 1.2rem; border-bottom: 1.5px solid #E2E8F0; text-transform: uppercase; font-size: .72rem; letter-spacing: 0.05em; }
.dom-table td { padding: 1rem 1.2rem; border-bottom: 1px solid #F1F5F9; color: #334155; vertical-align: middle; }
.dom-table tr:hover td { background: #F8FAFC; }

.badge-status { padding: .3rem .75rem; border-radius: 20px; font-size: .73rem; font-weight: 600; display: inline-flex; align-items: center; gap: .35rem; }
.badge-pending { background: #FEF3C7; color: #D97706; }
.badge-paid { background: #DCFCE7; color: #16A34A; }
.badge-cancelled { background: #FEE2E2; color: #DC2626; }

.select-status { padding: .35rem .6rem; border-radius: 8px; border: 1px solid #CBD5E1; font-size: .78rem; font-weight: 600; background: #fff; cursor: pointer; outline: none; }
</style>

<div class="dom-page">
    <div class="dom-head">
        <div>
            <h1 class="dom-title">Riwayat Transaksi Custom Domain</h1>
            <p class="dom-sub">Daftar permintaan &pembelian nama domain dari creator buyle.id.</p>
        </div>
        <form method="GET" action="{{ route('admin.domain-orders.index') }}" style="display:flex; gap:.5rem;">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama domain / creator..." style="padding:.5rem .9rem; border-radius:10px; border:1.5px solid #CBD5E1; font-size:.82rem; outline:none;">
            <button type="submit" style="background:#0F172A; color:#fff; border:none; padding:.5rem 1rem; border-radius:10px; font-weight:600; font-size:.82rem; cursor:pointer;">Cari</button>
        </form>
    </div>

    <!-- Stats summary -->
    <div class="dom-stats">
        <div class="dom-stat-card">
            <div class="dom-stat-label">Total Permintaan</div>
            <div class="dom-stat-val">{{ number_format($stats['total']) }}</div>
            <div class="dom-stat-sub">Semua Transaksi Domain</div>
        </div>
        <div class="dom-stat-card">
            <div class="dom-stat-label">Lunas (Paid)</div>
            <div class="dom-stat-val" style="color:#16A34A;">{{ number_format($stats['paid']) }}</div>
            <div class="dom-stat-sub">Siap Didaftarkan Registrar</div>
        </div>
        <div class="dom-stat-card">
            <div class="dom-stat-label">Pending</div>
            <div class="dom-stat-val" style="color:#D97706;">{{ number_format($stats['pending']) }}</div>
            <div class="dom-stat-sub">Menunggu Pembayaran Midtrans</div>
        </div>
        <div class="dom-stat-card">
            <div class="dom-stat-label">Total Omset Domain</div>
            <div class="dom-stat-val" style="color:#2563EB;">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
            <div class="dom-stat-sub">Pendapatan Bersih Lunas</div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="dom-tabs">
        <a href="{{ route('admin.domain-orders.index', ['status' => 'all', 'q' => $q]) }}" class="dom-tab-btn {{ $status === 'all' ? 'active' : '' }}">
            Semua ({{ $stats['total'] }})
        </a>
        <a href="{{ route('admin.domain-orders.index', ['status' => 'paid', 'q' => $q]) }}" class="dom-tab-btn {{ $status === 'paid' ? 'active' : '' }}">
            Lunas ({{ $stats['paid'] }})
        </a>
        <a href="{{ route('admin.domain-orders.index', ['status' => 'pending', 'q' => $q]) }}" class="dom-tab-btn {{ $status === 'pending' ? 'active' : '' }}">
            Pending ({{ $stats['pending'] }})
        </a>
        <a href="{{ route('admin.domain-orders.index', ['status' => 'cancelled', 'q' => $q]) }}" class="dom-tab-btn {{ $status === 'cancelled' ? 'active' : '' }}">
            Batal ({{ $stats['cancelled'] }})
        </a>
    </div>

    <!-- Table -->
    <div class="dom-table-card">
        <table class="dom-table">
            <thead>
                <tr>
                    <th>ID & Tanggal</th>
                    <th>User / Creator</th>
                    <th>Nama Domain</th>
                    <th>Harga</th>
                    <th>Status Pembayaran</th>
                    <th style="text-align:right;">Ubah Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <strong style="color:#0F172A; display:block;">#DOM-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong>
                        <span style="font-size:.74rem; color:#94A3B8;">{{ $order->created_at ? $order->created_at->format('d M Y H:i') : '-' }}</span>
                    </td>
                    <td>
                        <div style="font-weight:600; color:#0F172A;">{{ $order->user->name ?? 'User Hapus' }}</div>
                        <div style="font-size:.75rem; color:#64748B;">{{ $order->user->email ?? '-' }}</div>
                        @if($order->user)
                            <a href="{{ route('admin.creator-resources.show', $order->user->id) }}" style="font-size:.72rem; color:#1eb349; font-weight:600; text-decoration:none;" target="_blank">Lihat Resource Creator &rarr;</a>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:700; color:#166534; font-size:.9rem; display:flex; align-items:center; gap:0.3rem;">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            {{ $order->domain_name }}
                        </div>
                        <span style="font-size:.73rem; color:#94A3B8;">Ekstensi: .{{ $order->extension }}</span>
                    </td>
                    <td>
                        <strong style="color:#0F172A; font-size:.9rem; display:block;">Total: Rp {{ number_format($order->amount, 0, ',', '.') }}</strong>
                        @if($order->base_amount > 0)
                            <div style="font-size:.72rem; color:#64748B; margin-top:.2rem;">
                                Base: Rp {{ number_format($order->base_amount, 0, ',', '.') }}<br>
                                Platform Fee: Rp {{ number_format($order->platform_fee, 0, ',', '.') }}<br>
                                Admin Fee: Rp {{ number_format($order->admin_fee, 0, ',', '.') }}<br>
                                PPN 11%: Rp {{ number_format($order->tax_amount, 0, ',', '.') }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($order->status === 'paid')
                            <span class="badge-status badge-paid">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                Lunas (Paid)
                            </span>
                        @elseif($order->status === 'pending')
                            <span class="badge-status badge-pending">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Menunggu Pembayaran
                            </span>
                        @else
                            <span class="badge-status badge-cancelled">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                Dibatalkan
                            </span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <form method="POST" action="{{ route('admin.domain-orders.update-status', $order->id) }}" style="display:inline-flex; align-items:center; gap:.4rem;">
                            @csrf
                            <select name="status" class="select-status" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:3rem 1rem; color:#94A3B8;">
                        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:.5rem; opacity:0.6;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <div>Belum ada transaksi domain yang sesuai filter.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($orders->hasPages())
        <div style="padding: 1rem 1.25rem; border-top: 1px solid #E2E8F0;">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
