@extends('layouts.admin')
@section('title','Analytics')
@section('page-title','Analytics & Statistik')
@section('content')

<style>
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.4; transform: scale(0.8); }
}
</style>

<div style="margin-bottom:2rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-size:1.5rem;font-weight:800;color:var(--text1);margin:0 0 .25rem;letter-spacing:-.02em;">Analytics & Statistik</h1>
    <p style="font-size:.875rem;color:var(--text3);margin:0;">Laporan performa website, penjualan, dan estimasi profit bisnis</p>
  </div>
  <div style="display: flex; gap: 0.5rem; align-items: center; background:#FFFFFF; padding:0.5rem; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
    <input type="date" id="date-from" style="padding: 0.375rem 0.5rem; font-size: 0.8rem; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text1); font-family: inherit; font-weight:600; outline:none;">
    <span style="color: var(--text3); font-weight:600; font-size:0.8rem;">s/d</span>
    <input type="date" id="date-to" style="padding: 0.375rem 0.5rem; font-size: 0.8rem; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text1); font-family: inherit; font-weight:600; outline:none;">
    <button onclick="loadCustomData()" style="padding: 0.4rem 1rem; font-size: 0.8rem; background:#3B82F6; color:#fff; border:none; border-radius:6px; font-weight:700; cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">Filter</button>
    
    <div style="border-left: 1px solid var(--border); margin: 0 0.5rem; height: 30px;"></div>
    
    <button onclick="downloadReport('xls')" style="padding: 0.4rem 1rem; font-size: 0.8rem; background:rgba(16, 185, 129, 0.1); color: #10B981; border:none; border-radius:6px; font-weight:700; cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">↓ XLS</button>
    <button onclick="downloadReport('pdf')" style="padding: 0.4rem 1rem; font-size: 0.8rem; background:rgba(239, 68, 68, 0.1); color: #EF4444; border:none; border-radius:6px; font-weight:700; cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">↓ PDF</button>
  </div>
</div>
<div style="font-size:0.75rem; color:var(--text3); margin-top:-1.5rem; margin-bottom:2rem; text-align:right;">
  *Silakan filter periode tanggal terlebih dahulu sebelum men-download laporan.
</div>

<div style="display:flex;gap:0.75rem;margin-bottom:2rem;flex-wrap:wrap;align-items:center;">
    <span style="font-size:0.875rem;color:var(--text3);font-weight:700;">Periode Cepat:</span>
    @foreach(['7'=>'7 Hari','30'=>'30 Hari','365'=>'1 Tahun'] as $p=>$l)
    <button onclick="loadData('{{ $p }}')" id="btn-{{ $p }}" style="padding:0.4rem 1rem;font-size:0.8rem;font-weight:700;border:none;background:{{ $p==='30'?'rgba(59, 130, 246, 0.1)':'#FFFFFF' }};color:{{ $p==='30'?'#3B82F6':'var(--text3)' }};border-radius:100px;cursor:pointer;transition:all 0.2s;box-shadow:0 2px 10px rgba(0,0,0,0.02);">{{ $l }}</button>
    @endforeach
</div>

{{-- Summary Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-bottom:1rem;">
    {{-- Visitor --}}
    <div style="background:#FFFFFF;border-radius:20px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.03);display:flex;align-items:center;gap:1.25rem;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
      <div style="width:50px;height:50px;background:rgba(59,130,246,0.1);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="24" height="24" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      </div>
      <div>
        <div style="font-size:.75rem;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.25rem;">Visitor</div>
        <div id="stat-pageview" style="font-size:1.5rem;font-weight:800;color:var(--text1);line-height:1;">—</div>
      </div>
    </div>

    {{-- Akun Terdaftar --}}
    <div style="background:#FFFFFF;border-radius:20px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.03);display:flex;align-items:center;gap:1.25rem;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
      <div style="width:50px;height:50px;background:rgba(16,185,129,0.1);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="24" height="24" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M16 11l2 2 4-4"/></svg>
      </div>
      <div>
        <div style="font-size:.75rem;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.25rem;">Akun Terdaftar</div>
        <div id="stat-users" style="font-size:1.5rem;font-weight:800;color:var(--text1);line-height:1;">—</div>
      </div>
    </div>

    {{-- Produk Terjual --}}
    <div style="background:#FFFFFF;border-radius:20px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.03);display:flex;align-items:center;gap:1.25rem;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
      <div style="width:50px;height:50px;background:rgba(245,158,11,0.1);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="24" height="24" fill="none" stroke="#F59E0B" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
      </div>
      <div>
        <div style="font-size:.75rem;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.25rem;">Produk Terjual</div>
        <div id="stat-sold" style="font-size:1.5rem;font-weight:800;color:var(--text1);line-height:1;">—</div>
      </div>
    </div>

    {{-- Estimasi Profit Bersih --}}
    <div style="background:#FFFFFF;border-radius:20px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.03);display:flex;align-items:center;gap:1.25rem;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
      <div style="width:50px;height:50px;background:rgba(139,92,246,0.1);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="24" height="24" fill="none" stroke="#8B5CF6" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
      </div>
      <div>
        <div style="font-size:.75rem;font-weight:700;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.15rem;">Est. Profit Bersih</div>
        <div id="stat-net" style="font-size:1.25rem;font-weight:800;color:var(--text1);line-height:1;">—</div>
        <div id="stat-gross" style="font-size:.7rem;font-weight:600;color:#94A3B8;margin-top:.2rem;">Kotor: —</div>
      </div>
    </div>
</div>

{{-- Revenue Info Note --}}
<div style="background:#F0FDF4;border:1.5px solid #D1FAE5;border-radius:14px;padding:.75rem 1.25rem;margin-bottom:2rem;display:flex;align-items:center;gap:.75rem;">
    <svg width="16" height="16" fill="none" stroke="#10B981" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    <p style="margin:0;font-size:.75rem;color:#047857;font-weight:600;">Est. Profit Bersih = Pemasukan Kotor &minus; Estimasi Biaya Payment Gateway (Midtrans: 2.9% + Rp2.000/transaksi). Angka ini adalah <em>estimasi</em>, bukan angka final.</p>
</div>

{{-- No CTR alert needed anymore --}}

{{-- Chart + Top Pages --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:2rem;">
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
        <div style="background:#FFFFFF;border-radius:24px;padding:1.75rem 1.75rem 1.5rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.75rem;">
              <div>
                <div style="font-size:.8rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.35rem;">Grafik Performa</div>
                <div id="chart-period-label" style="font-size:1.35rem;font-weight:800;color:#1E293B;line-height:1;">30 Hari Terakhir</div>
              </div>
              <div style="display:flex;gap:.625rem;align-items:center;padding:.5rem .875rem;background:#F8FAFC;border-radius:100px;">
              <div style="display:flex;align-items:center;gap:.35rem;font-size:.72rem;font-weight:700;color:#64748B;"><span style="display:inline-block;width:10px;height:3px;background:#A3AED0;border-radius:2px;"></span>Visitor</div>
                <div style="display:flex;align-items:center;gap:.35rem;font-size:.72rem;font-weight:700;color:#64748B;"><span style="display:inline-block;width:10px;height:3px;background:#10B981;border-radius:2px;"></span>Akun Baru</div>
                <div style="display:flex;align-items:center;gap:.35rem;font-size:.72rem;font-weight:700;color:#64748B;"><span style="display:inline-block;width:10px;height:3px;background:#8B5CF6;border-radius:2px;"></span>Revenue (Rp)</div>
              </div>
            </div>
            <canvas id="visits-chart" height="100"></canvas>
        </div>
        
        <div style="background:#FFFFFF;border-radius:20px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
            <h3 style="font-size:.875rem;font-weight:800;color:var(--text1);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1.25rem;">Tipe Perangkat</h3>
            <div id="device-breakdown" style="display:flex;flex-direction:column;gap:1rem;">
                <div style="text-align:center;padding:1rem;color:var(--text3);font-weight:500;">Memuat...</div>
            </div>
        </div>
    </div>
    
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
        <div style="background:#FFFFFF;border-radius:20px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.03);flex:1;">
            <h3 style="font-size:.875rem;font-weight:800;color:var(--text1);text-transform:uppercase;letter-spacing:.05em;margin:0 0 1.25rem;">Top Halaman</h3>
            <div id="top-pages" style="display:flex;flex-direction:column;gap:0.75rem;">
                <div style="text-align:center;padding:2rem;color:var(--text3);font-weight:500;">Memuat...</div>
            </div>
        </div>
    </div>
</div>

{{-- Location Card --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;">
    {{-- Top Locations --}}
    <div style="background:#FFFFFF;border-radius:20px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <div>
                <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .2rem;">Lokasi Pengunjung</h3>
                <p style="font-size:.75rem;color:#64748B;margin:0;">Deteksi kota berdasarkan IP</p>
            </div>
            <div id="loc-realtime-badge" style="display:flex;align-items:center;gap:0.4rem;background:rgba(16,185,129,0.1);padding:.35rem .75rem;border-radius:100px;">
                <span style="width:7px;height:7px;background:#10B981;border-radius:50%;animation:pulse-dot 1.5s infinite;"></span>
                <span style="font-size:.7rem;font-weight:700;color:#10B981;">REALTIME</span>
            </div>
        </div>
        <div id="location-list" style="display:flex;flex-direction:column;gap:0.75rem;">
            <div style="text-align:center;padding:2rem;color:#94A3B8;font-weight:500;">Memuat data lokasi...</div>
        </div>
    </div>

    {{-- Realtime Active Users --}}
    <div style="background:#FFFFFF;border-radius:20px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <div>
                <h3 style="font-size:.875rem;font-weight:800;color:#1E293B;text-transform:uppercase;letter-spacing:.05em;margin:0 0 .2rem;">Pengunjung Aktif (1 Jam Terakhir)</h3>
                <p style="font-size:.75rem;color:#64748B;margin:0;">Berdasarkan kunjungan terbaru</p>
            </div>
            <div style="display:flex;align-items:center;gap:0.4rem;background:rgba(59,130,246,0.1);padding:.35rem .75rem;border-radius:100px;">
                <span style="width:7px;height:7px;background:#3B82F6;border-radius:50%;animation:pulse-dot 1.5s infinite .5s;"></span>
                <span style="font-size:.7rem;font-weight:700;color:#3B82F6;">LIVE</span>
            </div>
        </div>
        <div style="display:flex;align-items:center;justify-content:center;flex-direction:column;gap:0.5rem;padding:1.5rem 0;">
            <div id="active-count" style="font-size:4rem;font-weight:900;color:#1E293B;line-height:1;">—</div>
            <div style="font-size:.875rem;color:#64748B;font-weight:600;">Sesi aktif ditemukan</div>
        </div>
        <div id="recent-locations" style="display:flex;flex-direction:column;gap:0.5rem;max-height:200px;overflow-y:auto;">
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
let chart = null;
let currentPeriod = '30';

function setStatsLoading() {
    ['stat-pageview','stat-users','stat-sold','stat-net'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { el.innerHTML = '<span style="display:inline-block;width:48px;height:18px;background:#E2E8F0;border-radius:6px;animation:pulse-dot 1.2s infinite;"></span>'; }
    });
    const g = document.getElementById('stat-gross');
    if (g) g.textContent = 'Kotor: memuat...';
}

function setStatsError() {
    ['stat-pageview','stat-users','stat-sold'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = '0';
    });
    const n = document.getElementById('stat-net');
    if (n) n.textContent = 'Rp 0';
    const g = document.getElementById('stat-gross');
    if (g) g.textContent = 'Kotor: Rp 0';
}

async function loadCustomData() {
    const from = document.getElementById('date-from').value;
    const to = document.getElementById('date-to').value;
    if (!from || !to) return alert('Pilih tanggal mulai dan selesai');
    
    currentPeriod = 'custom';
    ['7','30','365'].forEach(p => {
        const btn = document.getElementById('btn-' + p);
        btn.style.background = '#FFFFFF';
        btn.style.color = 'var(--text3)';
    });

    setStatsLoading();
    try {
        const res  = await fetch('/admin/analytics/data?period=custom&from=' + from + '&to=' + to);
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        renderData(data);
    } catch(e) {
        console.error('Analytics fetch error:', e);
        setStatsError();
    }
}

async function loadData(period) {
    currentPeriod = period;
    // Update button styles
    ['7','30','365'].forEach(p => {
        const btn = document.getElementById('btn-' + p);
        btn.style.background = p === period ? 'rgba(59, 130, 246, 0.1)' : '#FFFFFF';
        btn.style.color = p === period ? '#3B82F6' : 'var(--text3)';
    });

    setStatsLoading();
    try {
        const res  = await fetch('/admin/analytics/data?period=' + period);
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        renderData(data);
    } catch(e) {
        console.error('Analytics fetch error:', e);
        setStatsError();
    }
}

function renderData(data) {
    const s = data.summary || {};
    // Stats
    document.getElementById('stat-pageview').textContent = (s.pageview || 0).toLocaleString();
    document.getElementById('stat-users').textContent    = (s.registered_users || 0).toLocaleString();
    document.getElementById('stat-sold').textContent     = (s.products_sold || 0).toLocaleString();

    const gross = s.gross_revenue || 0;
    const net   = s.net_revenue   || 0;
    document.getElementById('stat-net').textContent   = 'Rp ' + net.toLocaleString('id-ID');
    document.getElementById('stat-gross').textContent = 'Kotor: Rp ' + gross.toLocaleString('id-ID');

    // Chart — Premium GA Style
    if (chart) chart.destroy();
    const canvasEl = document.getElementById('visits-chart');
    const chartCtx = canvasEl.getContext('2d');

    // Build gradients fresh each time
    const gV = chartCtx.createLinearGradient(0, 0, 0, 300);
    gV.addColorStop(0, 'rgba(163,174,208,0.20)');
    gV.addColorStop(1, 'rgba(163,174,208,0.00)');

    const gW = chartCtx.createLinearGradient(0, 0, 0, 300);
    gW.addColorStop(0, 'rgba(16,185,129,0.15)');
    gW.addColorStop(1, 'rgba(16,185,129,0.00)');

    const gL = chartCtx.createLinearGradient(0, 0, 0, 300);
    gL.addColorStop(0, 'rgba(59,130,246,0.25)');
    gL.addColorStop(1, 'rgba(59,130,246,0.00)');

    // Update period label
    const periods = {'7':'7 Hari Terakhir','30':'30 Hari Terakhir','365':'1 Tahun Terakhir','custom':'Rentang Kustom'};
    const lbl = document.getElementById('chart-period-label');
    if (lbl) lbl.textContent = periods[currentPeriod] || '30 Hari Terakhir';

    chart = new Chart(chartCtx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Visitor',
                    data: data.visitorValues,
                    borderColor: '#A3AED0',
                    backgroundColor: gV,
                    borderWidth: 2.5,
                    tension: 0.45,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#A3AED0',
                    pointHoverBorderWidth: 2.5,
                },
                {
                    label: 'Akun Baru',
                    data: data.userValues,
                    borderColor: '#10B981',
                    backgroundColor: gW,
                    borderWidth: 2.5,
                    tension: 0.45,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#10B981',
                    pointHoverBorderWidth: 2.5,
                    yAxisID: 'y',
                },
                {
                    label: 'Revenue (Rp)',
                    data: data.revenueValues,
                    borderColor: '#8B5CF6',
                    backgroundColor: gL,
                    borderWidth: 2.5,
                    tension: 0.45,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#8B5CF6',
                    pointHoverBorderWidth: 2.5,
                    yAxisID: 'y2',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: true,
                    backgroundColor: '#FFFFFF',
                    titleColor: '#1E293B',
                    bodyColor: '#64748B',
                    borderColor: '#E4E7F0',
                    borderWidth: 1,
                    padding: { x: 14, y: 10 },
                    cornerRadius: 12,
                    titleFont: { size: 12, weight: '700' },
                    bodyFont: { size: 12, weight: '600' },
                    callbacks: {
                        label: function(ctx) {
                            return `  ${ctx.parsed.y}  ${ctx.dataset.label}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { color: '#94A3B8', font: { size: 11 }, maxRotation: 0, maxTicksLimit: 10 }
                },
                y: {
                    grid: { color: '#F1F5F9', lineWidth: 1, borderDash: [5, 4] },
                    border: { display: false },
                    ticks: { color: '#94A3B8', font: { size: 11 }, stepSize: 1, precision: 0 },
                    position: 'left',
                },
                y2: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        color: '#8B5CF6',
                        font: { size: 11 },
                        callback: v => 'Rp' + (v >= 1000000 ? (v/1000000).toFixed(1)+'jt' : (v >= 1000 ? (v/1000).toFixed(0)+'k' : v))
                    },
                    position: 'right',
                }
            }
        }
    });

    // Top Pages
    const topPagesEl = document.getElementById('top-pages');
    if (data.top_pages && data.top_pages.length) {
        topPagesEl.innerHTML = data.top_pages.map(p => {
            const path = p.page_url ? (new URL(p.page_url, window.location.origin)).pathname : '/';
            return `<div style="display:flex;justify-content:space-between;align-items:center;padding:0.625rem 0;border-bottom:1px solid var(--border);font-size:0.875rem;">
                <span style="color:var(--text2);font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:75%;">${path}</span>
                <span style="font-weight:800;color:#3B82F6;flex-shrink:0;">${parseInt(p.views).toLocaleString()}</span>
            </div>`;
        }).join('');
    } else {
        topPagesEl.innerHTML = '<p style="color:var(--text3);text-align:center;padding:1rem;">Belum ada data.</p>';
    }

    // Device Breakdown
    const deviceEl = document.getElementById('device-breakdown');
    if (data.devices && Object.keys(data.devices).length) {
        const total = Object.values(data.devices).reduce((a, b) => a + b, 0);
        deviceEl.innerHTML = Object.entries(data.devices).map(([dev, count]) => {
            const pct = Math.round((count / total) * 100);
            const icon = dev.toLowerCase() === 'desktop'
                ? '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>'
                : '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>';
            return `
            <div style="margin-bottom:1rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.375rem;font-size:0.875rem;">
                    <div style="display:flex;align-items:center;gap:0.375rem;color:var(--text1);font-weight:600;">
                        ${icon} <span style="text-transform:capitalize;">${dev}</span>
                    </div>
                    <div style="font-weight:700;color:var(--text2);">${pct}% <span style="color:var(--text3);font-size:0.75rem;font-weight:500;">(${count})</span></div>
                </div>
                <div style="width:100%;background:var(--bg3);border-radius:100px;height:6px;overflow:hidden;">
                    <div style="height:100%;background:#3B82F6;border-radius:100px;width:${pct}%;"></div>
                </div>
            </div>`;
        }).join('');
    } else {
        deviceEl.innerHTML = '<p style="color:var(--text3);text-align:center;padding:1rem;">Belum ada data.</p>';
    }

    // Location Breakdown

    const locEl = document.getElementById('location-list');
    if (data.locations && Object.keys(data.locations).length) {
        const totalLoc = Object.values(data.locations).reduce((a, b) => a + b, 0);
        const colors = ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#06B6D4','#F97316','#6366F1','#14B8A6'];
        locEl.innerHTML = Object.entries(data.locations).map(([loc, count], i) => {
            const pct = Math.round((count / totalLoc) * 100);
            const color = colors[i % colors.length];
            const parts = loc.split(', ');
            const city = parts[0];
            const countryCode = parts[1] || '';
            return `
            <div style="margin-bottom:.25rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.35rem;">
                    <div style="display:flex;align-items:center;gap:.625rem;">
                        <span style="width:10px;height:10px;background:${color};border-radius:50%;flex-shrink:0;"></span>
                        <div>
                            <span style="font-size:.875rem;font-weight:700;color:#1E293B;">${city}</span>
                            ${countryCode ? `<span style="font-size:.7rem;color:#94A3B8;margin-left:.25rem;">${countryCode}</span>` : ''}
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0;">
                        <span style="font-size:.8rem;font-weight:700;color:${color};">${count.toLocaleString()}</span>
                        <span style="font-size:.7rem;color:#94A3B8;">(${pct}%)</span>
                    </div>
                </div>
                <div style="width:100%;background:#F1F5F9;border-radius:100px;height:5px;overflow:hidden;">
                    <div style="height:100%;background:${color};border-radius:100px;width:${pct}%;transition:width .6s ease;"></div>
                </div>
            </div>`;
        }).join('');
    } else {
        locEl.innerHTML = '<p style="color:#94A3B8;text-align:center;padding:1rem;font-size:.875rem;">Belum ada data lokasi.<br><small>Data lokasi akan muncul pada kunjungan berikutnya.</small></p>';
    }

    // (no CTR alert)
}

// No CTR check needed

// Load on page init
loadData('30');

// Auto-refresh realtime every 60s
setInterval(fetchRealtime, 60000);
fetchRealtime();

async function fetchRealtime() {
    try {
        const res = await fetch('/admin/analytics/data?period=7');
        const data = await res.json();

        // Active count (last 1h visits)
        const recent = await fetch('/admin/analytics/realtime');
        if (recent.ok) {
            const rt = await recent.json();
            document.getElementById('active-count').textContent = rt.active ?? '—';
            const rlEl = document.getElementById('recent-locations');
            if (rt.locations && rt.locations.length) {
                rlEl.innerHTML = rt.locations.map(l => `
                    <div style="display:flex;align-items:center;gap:.625rem;padding:.5rem .75rem;background:#F8FAFC;border-radius:10px;">
                        <span style="width:8px;height:8px;background:#10B981;border-radius:50%;flex-shrink:0;"></span>
                        <span style="font-size:.8rem;font-weight:600;color:#334155;">${l}</span>
                    </div>`).join('');
            }
        }
    } catch(e) {}
}

function downloadReport(format) {
    const start = document.getElementById('date-from').value;
    const end = document.getElementById('date-to').value;
    if(!start || !end) {
        alert('Mohon isi rentang tanggal (s/d) lalu klik Filter terlebih dahulu!');
        return;
    }
    const url = `/admin/analytics/export/${format}?start_date=${start}&end_date=${end}`;
    if(format === 'pdf') {
        window.open(url, '_blank');
    } else {
        window.location.href = url;
    }
}
</script>
@endpush
@endsection
