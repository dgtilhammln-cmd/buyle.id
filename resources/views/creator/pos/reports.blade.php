@extends('creator.layout')

@section('title', 'Laporan Kasir POS – Creator Studio')

@section('styles')
<style>
    :root { --pg: linear-gradient(135deg,#1eb349 0%,#a5cf37 100%); }
    body { background:#f8fafc !important; }

    .rp-wrap {
        max-width: 520px;
        margin: 0 auto;
        padding: 0.65rem 0.75rem 6rem;
    }

    /* ── Header ── */
    .rp-hdr {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    .rp-hdr-left { display: flex; align-items: center; gap: 0.6rem; }
    .rp-back {
        width: 36px; height: 36px; border-radius: 50%;
        background: #fff; border: 1px solid #e2e8f0;
        display: flex; align-items: center; justify-content: center;
        color: #334155; text-decoration: none; flex-shrink: 0;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .rp-hdr-title { font-size: 1.05rem; font-weight: 700; color: #0f172a; margin: 0; line-height: 1.2; }
    .rp-hdr-sub { font-size: 0.67rem; color: #64748b; margin: 2px 0 0; }
    .rp-export {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 0.38rem 0.75rem;
        border-radius: 999px; background: #fff;
        border: 1px solid #e2e8f0; color: #166534;
        font-size: 0.72rem; font-weight: 600; text-decoration: none;
        box-shadow: 0 1px 3px rgba(0,0,0,.02);
    }

    /* ── Period Filter: 3-col grid ── */
    .rp-period-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.35rem;
        margin-bottom: 0.75rem;
    }
    .rp-period-pill {
        display: flex; align-items: center; justify-content: center;
        padding: 0.4rem 0.25rem;
        border-radius: 10px;
        background: #fff; border: 1.5px solid #e2e8f0;
        color: #475569; font-size: 0.72rem; font-weight: 600;
        text-decoration: none; text-align: center;
        transition: all .15s;
    }
    .rp-period-pill.active {
        background: var(--pg); border-color: transparent;
        color: #fff; box-shadow: 0 3px 10px rgba(30,179,73,.28);
    }

    /* ── KPI Cards 2-col ── */
    .rp-kpi-grid {
        display: grid; grid-template-columns: repeat(2, 1fr);
        gap: 0.55rem; margin-bottom: 0.75rem;
    }
    .rp-kpi {
        background: #fff; border-radius: 16px;
        border: 1px solid #e2e8f0; padding: 0.75rem 0.85rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.02);
    }
    .rp-kpi.dark {
        background: linear-gradient(135deg,#0f172a 0%,#1e293b 100%);
        border-color: #334155; box-shadow: 0 4px 14px rgba(15,23,42,.15);
    }
    .rp-kpi-label {
        font-size: 0.67rem; font-weight: 600; color: #64748b;
        display: flex; align-items: center; gap: 4px;
        margin-bottom: 0.3rem;
    }
    .rp-kpi.dark .rp-kpi-label { color: #94a3b8; }
    .rp-kpi-val { font-size: 1.05rem; font-weight: 800; color: #0f172a; letter-spacing: -.02em; }
    .rp-kpi.dark .rp-kpi-val { color: #fff; }
    .rp-kpi-sub { font-size: 0.64rem; color: #94a3b8; margin-top: 2px; }

    /* ── Method Filter Row (small pills, single row) ── */
    .rp-method-row {
        display: flex; align-items: center; gap: 0.3rem;
        overflow-x: auto; padding-bottom: 0.4rem;
        margin-bottom: 0.75rem; scrollbar-width: none;
    }
    .rp-method-row::-webkit-scrollbar { display: none; }
    .rp-method-pill {
        padding: 0.3rem 0.65rem; border-radius: 999px;
        font-size: 0.68rem; font-weight: 600;
        background: #fff; border: 1px solid #e2e8f0;
        color: #64748b; white-space: nowrap; text-decoration: none;
        transition: all .15s;
    }
    .rp-method-pill.active { background: #0f172a; color: #fff; border-color: #0f172a; }

    /* ── Section Tabs ── */
    .rp-sec-tabs {
        display: grid; grid-template-columns: repeat(3,1fr);
        background: #f1f5f9; border-radius: 12px;
        padding: 3px; gap: 3px; margin-bottom: 0.75rem;
    }
    .rp-sec-btn {
        text-align: center; padding: 0.4rem 0.15rem;
        border-radius: 9px; font-size: 0.7rem; font-weight: 600;
        color: #64748b; border: none; background: transparent;
        cursor: pointer; transition: all .15s;
    }
    .rp-sec-btn.active {
        background: #fff; color: #0f172a; font-weight: 700;
        box-shadow: 0 2px 6px rgba(0,0,0,.05);
    }

    /* ── Search ── */
    .rp-search {
        position: relative; margin-bottom: 0.65rem;
    }
    .rp-search input {
        width: 100%; box-sizing: border-box;
        background: #fff; border: 1.5px solid #e2e8f0;
        border-radius: 999px; padding: 0.5rem 0.9rem 0.5rem 2.1rem;
        font-size: 0.78rem; color: #0f172a; outline: none;
    }
    .rp-search input:focus { border-color: #1eb349; }
    .rp-search svg { position: absolute; left: 0.7rem; top: 50%; transform: translateY(-50%); color: #94a3b8; }

    /* ── Order Card ── */
    .rp-order-card {
        background: #fff; border-radius: 14px;
        border: 1px solid #e2e8f0; padding: 0.7rem 0.85rem;
        margin-bottom: 0.55rem;
        box-shadow: 0 1px 4px rgba(0,0,0,.02);
    }
    .rp-order-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.25rem; }
    .rp-order-no { font-size: 0.78rem; font-weight: 700; color: #0f172a; }
    .rp-order-time { font-size: 0.65rem; color: #94a3b8; }
    .rp-order-mid { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.3rem; }
    .rp-order-customer { font-size: 0.75rem; color: #334155; font-weight: 500; }
    .rp-order-amt { font-size: 0.92rem; font-weight: 800; color: #166534; }
    .rp-order-foot {
        display: flex; align-items: center; justify-content: space-between;
        border-top: 1px dashed #f1f5f9; padding-top: 0.35rem;
        margin-top: 0.25rem; font-size: 0.66rem; color: #64748b;
    }
    .badge-cash { background:#ecfdf5; color:#059669; padding:2px 8px; border-radius:999px; font-weight:600; font-size:0.65rem; border:1px solid #a7f3d0; }
    .badge-qris { background:#eff6ff; color:#2563eb; padding:2px 8px; border-radius:999px; font-weight:600; font-size:0.65rem; border:1px solid #bfdbfe; }
    .badge-tf   { background:#f5f3ff; color:#7c3aed; padding:2px 8px; border-radius:999px; font-weight:600; font-size:0.65rem; border:1px solid #ddd6fe; }

    /* ── Top Menu Rank ── */
    .rp-rank {
        background: #fff; border-radius: 14px; border: 1px solid #e2e8f0;
        padding: 0.65rem 0.85rem; margin-bottom: 0.45rem;
        display: flex; align-items: center; justify-content: space-between;
    }
    .rp-rank-left { display: flex; align-items: center; gap: 0.55rem; }
    .rp-rank-badge {
        width: 26px; height: 26px; border-radius: 7px;
        background: #f1f5f9; display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem; font-weight: 700; color: #334155;
    }
    .rp-rank-badge.r1 { background:#fef3c7; color:#b45309; }
    .rp-rank-badge.r2 { background:#e2e8f0; color:#475569; }
    .rp-rank-badge.r3 { background:#ffedd5; color:#c2410c; }
    .rp-rank-name { font-size: 0.8rem; font-weight: 700; color: #0f172a; }
    .rp-rank-qty  { font-size: 0.66rem; color: #64748b; margin-top: 1px; }
    .rp-rank-rev  { font-size: 0.82rem; font-weight: 700; color: #0f172a; }

    /* ── Mutasi Card ── */
    .rp-mut-card {
        background: #fff; border-radius: 14px; border: 1px solid #e2e8f0;
        padding: 0.65rem 0.85rem; margin-bottom: 0.45rem;
        display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
    }
    .rp-mut-type { font-size: 0.75rem; font-weight: 700; }
    .rp-mut-desc { font-size: 0.65rem; color: #64748b; margin-top: 1px; }
    .rp-mut-date { font-size: 0.63rem; color: #94a3b8; margin-top: 2px; }
    .rp-mut-amt { font-size: 0.88rem; font-weight: 800; flex-shrink: 0; }

    /* ── Empty state ── */
    .rp-empty {
        text-align: center; padding: 2rem 1rem;
        background: #fff; border-radius: 14px; border: 1px dashed #cbd5e1;
    }
    .rp-empty p { margin: 0.35rem 0 0; font-size: 0.75rem; color: #64748b; }
    .rp-empty small { font-size: 0.67rem; color: #94a3b8; }

    /* ── POS Bottom Nav ── */
    .pos-bottom-nav-wrap {
        display: flex !important; position: fixed !important;
        bottom: 16px !important; left: 14px !important; right: 14px !important;
        max-width: 440px !important; margin: 0 auto !important;
        z-index: 10000 !important; justify-content: center !important;
    }
    .pos-bottom-nav-bar {
        width: 100% !important; height: 60px !important; background: #fff !important;
        border-radius: 999px !important; display: flex !important;
        align-items: center !important; justify-content: space-between !important;
        padding: 0.3rem 0.6rem !important;
        box-shadow: 0 8px 28px rgba(0,0,0,.1), 0 2px 6px rgba(0,0,0,.04) !important;
        border: 1.5px solid #f1f5f9 !important;
    }
    .pos-nav-item {
        display: flex; align-items: center; justify-content: center;
        border: none; background: transparent; cursor: pointer;
        transition: all .22s; color: #64748b; padding: 0;
        text-decoration: none; width: 42px; height: 42px;
        border-radius: 50%; outline: none;
    }
    .pos-nav-item.active {
        background: var(--pg) !important; color: #fff !important;
        padding: 0.45rem 1rem !important; border-radius: 999px !important;
        box-shadow: 0 4px 14px rgba(30,179,73,.35) !important;
        gap: 5px !important; width: auto !important;
    }
    .pos-nav-label { display: none; }
    .pos-nav-item.active .pos-nav-label { display: inline-block; font-size: 0.75rem; font-weight: 700; color: #fff; }
</style>
@endsection

@section('content')
<div class="rp-wrap">

    {{-- Header --}}
    <div class="rp-hdr">
        <div class="rp-hdr-left">
            <a href="{{ route('creator.pos.index') }}" class="rp-back" title="Kembali">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="rp-hdr-title">Laporan POS</h1>
                <p class="rp-hdr-sub">Rekap penjualan kasir digital</p>
            </div>
        </div>
        <a href="{{ route('creator.sales.report.export_pos', array_merge(request()->query(), ['pos_method'=>$posPaymentMethod])) }}" class="rp-export" title="Ekspor CSV">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Ekspor
        </a>
    </div>

    {{-- Period Filter 3×2 Grid --}}
    <div class="rp-period-grid">
        @php $periods = [
            'today'=>'Hari Ini','yesterday'=>'Kemarin','7'=>'7 Hari',
            '30'=>'30 Hari','this_month'=>'Bulan Ini','all'=>'Semua'
        ]; @endphp
        @foreach($periods as $key => $label)
            <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['filter'=>$key])) }}"
               class="rp-period-pill {{ $filter === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    {{-- KPI Cards --}}
    <div class="rp-kpi-grid">
        <div class="rp-kpi dark">
            <div class="rp-kpi-label">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Total Omset POS
            </div>
            <div class="rp-kpi-val">Rp {{ number_format($posTotalSales, 0, ',', '.') }}</div>
            <div class="rp-kpi-sub">{{ number_format($posTotalTransactions) }} struk selesai</div>
        </div>

        <div class="rp-kpi">
            <div class="rp-kpi-label">
                <svg width="12" height="12" fill="none" stroke="#059669" stroke-width="2.2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                Tunai (Cash)
            </div>
            <div class="rp-kpi-val" style="color:#059669;">Rp {{ number_format($posCashTotal, 0, ',', '.') }}</div>
            <div class="rp-kpi-sub">Fisik di laci kasir</div>
        </div>

        <div class="rp-kpi">
            <div class="rp-kpi-label">
                <svg width="12" height="12" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                QRIS / Digital
            </div>
            <div class="rp-kpi-val" style="color:#2563eb;">Rp {{ number_format($posDigitalTotal, 0, ',', '.') }}</div>
            <div class="rp-kpi-sub">Non-tunai / cashless</div>
        </div>

        <div class="rp-kpi">
            <div class="rp-kpi-label">
                <svg width="12" height="12" fill="none" stroke="#d97706" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4"/><path d="M4 6v12a2 2 0 0 0 2 2h14v-4"/><circle cx="16" cy="14" r="1"/></svg>
                Saldo Deposit
            </div>
            <div class="rp-kpi-val" style="color:#d97706;">Rp {{ number_format($posBalance, 0, ',', '.') }}</div>
            <div class="rp-kpi-sub">Saldo biaya transaksi</div>
        </div>
    </div>

    {{-- Method Filter (small, scrollable) --}}
    <div class="rp-method-row">
        @foreach(['all'=>'Semua ('. count($posOrders) .')','cash'=>'Tunai (Cash)','qris'=>'QRIS / Digital','transfer'=>'Transfer Bank'] as $k=>$v)
            <a href="{{ route('creator.pos.reports', array_merge(request()->query(), ['pos_method'=>$k])) }}"
               class="rp-method-pill {{ ($posPaymentMethod??'all') === $k ? 'active' : '' }}">{{ $v }}</a>
        @endforeach
    </div>

    {{-- Section Tabs --}}
    <div class="rp-sec-tabs">
        <button type="button" class="rp-sec-btn active" onclick="switchSec('struk',this)">Struk ({{ count($posOrders) }})</button>
        <button type="button" class="rp-sec-btn" onclick="switchSec('menu',this)">Top Menu</button>
        <button type="button" class="rp-sec-btn" onclick="switchSec('mutasi',this)">Mutasi Deposit</button>
    </div>

    {{-- SECTION 1: STRUK --}}
    <div id="sec-struk">
        <div class="rp-search">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="orderSearch" placeholder="Cari no. struk atau nama pelanggan…" oninput="filterOrders()">
        </div>

        <div id="ordersContainer">
            @forelse($posOrders as $order)
                @php
                    $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address??'',true)??[]);
                    $cName   = $addr['name'] ?? $order->user?->name ?? 'Walk-in';
                    $method  = strtolower($addr['payment_method'] ?? ($order->payment->method ?? 'cash'));
                    $tableNo = $addr['table_number'] ?? ($addr['table'] ?? null);
                @endphp
                <div class="rp-order-card" data-search="{{ strtolower($order->order_number.' '.$cName) }}">
                    <div class="rp-order-top">
                        <span class="rp-order-no">#{{ $order->order_number }}</span>
                        <span class="rp-order-time">{{ $order->created_at->format('d M y H:i') }}</span>
                    </div>
                    <div class="rp-order-mid">
                        <span class="rp-order-customer">{{ $cName }}{{ $tableNo ? ' · Meja '.$tableNo : '' }}</span>
                        <span class="rp-order-amt">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="rp-order-foot">
                        @if($method==='cash')
                            <span class="badge-cash">Tunai</span>
                        @elseif($method==='qris')
                            <span class="badge-qris">QRIS</span>
                        @else
                            <span class="badge-tf">{{ strtoupper($method) }}</span>
                        @endif
                        <span>{{ $order->items->sum('qty') }} item</span>
                    </div>
                </div>
            @empty
                <div class="rp-empty">
                    <svg width="36" height="36" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto;display:block;"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    <p>Belum ada transaksi POS</p>
                    <small>Pada rentang waktu yang dipilih.</small>
                </div>
            @endforelse
        </div>
    </div>

    {{-- SECTION 2: TOP MENU --}}
    <div id="sec-menu" style="display:none;">
        @forelse($topProducts as $i => $p)
            <div class="rp-rank">
                <div class="rp-rank-left">
                    <div class="rp-rank-badge {{ $i===0?'r1':($i===1?'r2':($i===2?'r3':'')) }}">#{{ $i+1 }}</div>
                    <div>
                        <div class="rp-rank-name">{{ $p->product_name ?? 'Menu POS' }}</div>
                        <div class="rp-rank-qty">Terjual <strong style="color:#166534;">{{ $p->total_qty }}</strong> porsi</div>
                    </div>
                </div>
                <div class="rp-rank-rev">Rp {{ number_format($p->total_revenue, 0, ',', '.') }}</div>
            </div>
        @empty
            <div class="rp-empty">
                <p>Belum ada data menu terlaris</p>
                <small>Data muncul otomatis setelah ada transaksi.</small>
            </div>
        @endforelse
    </div>

    {{-- SECTION 3: MUTASI --}}
    <div id="sec-mutasi" style="display:none;">
        @forelse($posMutations as $m)
            <div class="rp-mut-card">
                <div style="min-width:0;">
                    <div class="rp-mut-type" style="color: {{ $m->type==='topup' ? '#16a34a' : '#ef4444' }};">
                        {{ $m->type==='topup' ? '+ Top Up Deposit' : '- Potongan Biaya' }}
                    </div>
                    <div class="rp-mut-desc">{{ $m->description ?? '-' }}</div>
                    <div class="rp-mut-date">{{ $m->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="rp-mut-amt" style="color: {{ $m->type==='topup' ? '#16a34a' : '#ef4444' }};">
                    {{ $m->type==='topup' ? '+' : '-' }} Rp {{ number_format(abs($m->amount), 0, ',', '.') }}
                </div>
            </div>
        @empty
            <div class="rp-empty">
                <p>Belum ada riwayat mutasi deposit</p>
                <small>Pada rentang waktu yang dipilih.</small>
            </div>
        @endforelse
    </div>

</div>{{-- end .rp-wrap --}}

{{-- Bottom Nav --}}
<div class="pos-bottom-nav-wrap">
    <div class="pos-bottom-nav-bar">
        <a href="{{ route('creator.pos.index') }}" class="pos-nav-item" title="Home">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span class="pos-nav-label">Home</span>
        </a>
        <a href="{{ route('creator.pos.index', ['screen'=>'transactions']) }}" class="pos-nav-item" title="Transaksi">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><circle cx="12" cy="12" r="3"/><line x1="2" y1="9" x2="6" y2="9"/><line x1="18" y1="15" x2="22" y2="15"/></svg>
            <span class="pos-nav-label">Transaksi</span>
        </a>
        <a href="{{ route('creator.pos.reports') }}" class="pos-nav-item active" title="Laporan">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            <span class="pos-nav-label">Laporan</span>
        </a>
        <a href="{{ route('creator.pos.profile') }}" class="pos-nav-item" title="Profil">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span class="pos-nav-label">Profil</span>
        </a>
    </div>
</div>

<script>
function switchSec(sec, btn) {
    document.querySelectorAll('.rp-sec-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('sec-struk').style.display = sec==='struk' ? '' : 'none';
    document.getElementById('sec-menu').style.display  = sec==='menu'  ? '' : 'none';
    document.getElementById('sec-mutasi').style.display= sec==='mutasi'? '' : 'none';
}
function filterOrders() {
    const q = (document.getElementById('orderSearch')?.value||'').toLowerCase().trim();
    document.querySelectorAll('#ordersContainer .rp-order-card').forEach(c => {
        c.style.display = (c.dataset.search||'').includes(q) ? '' : 'none';
    });
}
</script>
@endsection
