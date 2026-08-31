@extends('creator.layout')

@section('title', 'Laporan Penjualan – Creator Studio')

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

/* ── Metric Cards ────────────────────────────────────────── */
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
}
.metric-card.dark {
    background: linear-gradient(135deg, #0b120c, #1a2744);
    border: none;
}
.metric-label { font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
.metric-card.dark .metric-label { color: #94a3b8; }
.metric-value { font-size: 1.6rem; font-weight: 800; color: #0f172a; line-height: 1.1; }
.metric-card.dark .metric-value { color: #fff; }

/* ── Traffic Wave Chart Card ─────────────────────────────── */
.wave-card {
    background: #fff;
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    margin-bottom: 1.5rem;
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
.chart-wrap { position: relative; height: 140px; }

/* ── Content Grid ────────────────────────────────────────── */
.main-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.5rem;
    align-items: start;
}

/* ── Panel Card ──────────────────────────────────────────── */
.panel-card {
    background: #fff;
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
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
}
@media (max-width: 600px) {
    .metrics-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .rp-title { font-size: 1.3rem; }
    .filter-bar { gap: 0.25rem; }
    .data-table { display: block; overflow-x: auto; }
    .wave-card { padding: 1rem; }
}
</style>
@endsection

@section('content')

{{-- ── Header + Filters ──────────────────────────────────────────── --}}
<div class="rp-header">
    <div>
        <h1 class="rp-title">Laporan Penjualan</h1>
        <p class="rp-sub">Pantau trafik visitor, sumber klik, dan data pembelian produkmu.</p>
    </div>

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
</div>

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
    <div class="metric-card dark">
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
                            <td style="white-space:nowrap; color:#64748b; font-size:0.78rem;">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="td-user">
                                <div class="name">{{ $order->user?->name ?? 'Guest' }}</div>
                                <div class="email">{{ $order->user?->email ?? '' }}</div>
                                <div class="phone">{{ $order->user?->phone ?? '' }}</div>
                            </td>
                            <td>
                                @foreach($order->items as $item)
                                    <span class="badge">{{ Str::limit($item->product_name, 28) }}</span>
                                @endforeach
                            </td>
                            <td style="font-weight:700; white-space:nowrap;">Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</td>
                            <td>
                                @if(!empty($order->utm_source))
                                    <span class="badge green">{{ $order->utm_source }}</span>
                                @else
                                    <span style="color:#cbd5e1; font-size:0.75rem;">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 2.5rem; color:#94a3b8; font-size:0.85rem;">
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

        {{-- UTM Sources --}}
        <div class="panel-card">
            <h3 class="panel-title" style="margin-bottom:1rem;">Sumber Trafik</h3>
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

btnOpen.addEventListener('click', () => modal.classList.add('show'));
btnClose.addEventListener('click', () => modal.classList.remove('show'));
modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('show'); });
</script>
@endsection
