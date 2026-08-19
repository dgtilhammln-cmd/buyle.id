@extends('layouts.admin')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('content')
@php
$totalLeads    = \App\Models\Lead::count();
$todayLeads    = \App\Models\Lead::whereDate('created_at',today())->count();
$monthLeads    = \App\Models\Lead::whereMonth('created_at',now()->month)->count();
$newLeadsCount = \App\Models\Lead::where('status','new')->count();

$hour = now()->timezone('Asia/Jakarta')->format('H');
if ($hour < 11) { $greeting = 'pagi'; }
elseif ($hour < 15) { $greeting = 'siang'; }
elseif ($hour < 18) { $greeting = 'sore'; }
else { $greeting = 'malam'; }

$quotes = [
    "Pertumbuhan berawal dari tekad untuk terus melangkah, satu pencapaian setiap harinya.",
    "Setiap tantangan adalah anak tangga menuju kesuksesan perusahaan yang lebih besar.",
    "Inovasi hari ini adalah pondasi kokoh untuk kejayaan esok hari.",
    "Kolaborasi dan dedikasi adalah kunci utama menuju pertumbuhan tanpa batas.",
    "Tidak ada hasil gemilang tanpa kerja keras dan sinergi bersama.",
    "Terus bergerak maju, karena potensi perusahaan kita tidak memiliki batas akhir.",
    "Keberhasilan besar dimulai dari langkah-langkah kecil yang konsisten.",
    "Jadilah pelopor perubahan, ciptakan standar baru dalam industri kita.",
    "Fokus pada kualitas akan selalu membawa kita pada kuantitas kesuksesan.",
    "Visi yang jelas dan kerja keras akan mewujudkan masa depan gemilang.",
    "Jadikan setiap masalah sebagai peluang untuk berkembang lebih pesat.",
    "Ketekunan hari ini adalah jaminan kemakmuran perusahaan di masa depan.",
    "Kita membangun lebih dari sekadar bisnis; kita membangun mahakarya.",
    "Pertumbuhan sejati terjadi ketika kita melampaui batas zona nyaman.",
    "Energi positif dan kerja cerdas adalah katalisator pertumbuhan kita.",
    "Perubahan adalah satu-satunya konstanta; beradaptasi adalah kunci untuk menang.",
    "Tetap fokus pada tujuan, dan biarkan hasil kerja keras kita yang berbicara.",
    "Visi besar membutuhkan eksekusi yang konsisten dan semangat tak pantang menyerah.",
    "Jangan pernah berhenti berinovasi, karena dunia terus bergerak maju.",
    "Kesuksesan adalah perjalanan, bukan tujuan akhir; mari terus bertumbuh.",
    "Setiap pelanggan yang puas adalah fondasi dari kerajaan bisnis kita.",
    "Keunggulan bukanlah tindakan sesekali, melainkan kebiasaan kita sehari-hari.",
    "Bersama-sama kita kuat, bersama-sama kita menembus batas ketidakmungkinan.",
    "Pemimpin sejati menciptakan peluang pertumbuhan di setiap kondisi.",
    "Percaya pada proses, kerja keras kita akan berbuah manis pada waktunya.",
    "Jangan takut mencoba hal baru; di sanalah tersembunyi inovasi terbesar.",
    "Kunci keberhasilan adalah fokus pada solusi, bukan pada masalah.",
    "Mari tingkatkan standar kita dan tunjukkan pada dunia apa yang kita bisa.",
    "Kemajuan kecil setiap hari akan menghasilkan pencapaian masif di akhir tahun.",
    "Teruslah melangkah, sejarah kejayaan perusahaan ini sedang kita tulis bersama."
];
$dayOfYear = now()->timezone('Asia/Jakarta')->dayOfYear;
$dailyQuote = $quotes[$dayOfYear % count($quotes)];
$adminName = str_ireplace('Cyclevent', 'buyle.id', session('admin_name', 'Admin buyle.id'));
@endphp

{{-- Welcome & Company Header --}}
<div style="background: #ffffff; border: 1px solid #E2E8F0; border-radius: 20px; padding: 1.5rem 2rem; margin-bottom: 2rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
  
  {{-- Left: Logo and Info --}}
  <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
    {{-- Logo Container --}}
    <div style="height: 58px; width: auto; min-width: 80px; max-width: 220px; background: #ffffff; border: 1.5px solid #E2E8F0; border-radius: 14px; display: flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
      @php $adminLogo = \App\Models\Setting::get('logo'); @endphp
      @if($adminLogo)
        <img src="{{ asset('storage/'.$adminLogo) }}" alt="Logo" style="height: 38px; width: auto; max-width: 180px; object-fit: contain; display: block;">
      @else
        <svg width="36" height="36" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
      @endif
    </div>

    {{-- Text Info --}}
    <div>
      <h2 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; margin: 0 0 0.25rem; letter-spacing: -0.02em;">
        buyle.id
      </h2>
      <div style="font-size: 0.85rem; color: #94A3B8; margin-bottom: 0.75rem; font-weight: 500;">
        Dashboard Admin — {{ now()->timezone('Asia/Jakarta')->translatedFormat('l, d F Y') }}
      </div>
      <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
        <span style="display: inline-flex; align-items: center; gap: 0.375rem; background: #EFF6FF; border: 1px solid #BFDBFE; color: #2563EB; font-size: 0.75rem; font-weight: 700; padding: 0.375rem 0.875rem; border-radius: 100px;">
          <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
          HVM Digital
        </span>
        <span style="display: inline-flex; align-items: center; gap: 0.4rem; background: #ECFDF5; border: 1px solid #A7F3D0; color: #059669; font-size: 0.75rem; font-weight: 700; padding: 0.375rem 0.875rem; border-radius: 100px;">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          @php
            $licExpiry = \App\Models\Setting::where('key','site_license_expiry')->value('value');
            $licStatus = \App\Models\Setting::where('key','site_license_status')->value('value') ?? 'active';
          @endphp
          @if($licExpiry)
            Lisensi {{ $licStatus === 'active' ? 'Aktif' : 'Dibekukan' }} Sampai Dengan {{ \Carbon\Carbon::parse($licExpiry)->locale('id')->isoFormat('D MMM YYYY') }}
          @else
            Lisensi Aktif Sampai Dengan 8 Jul 2027
          @endif
        </span>
      </div>
    </div>
  </div>

  {{-- Right: Welcome or Live Clock --}}
  <div style="text-align: right; padding-left: 1.5rem; border-left: 1px dashed #E2E8F0;">
    <div style="font-size: 0.85rem; color: #64748B; font-weight: 600; margin-bottom: 0.25rem;">Selamat {{ ucfirst($greeting) }},</div>
    <div style="font-size: 1.125rem; font-weight: 800; color: #0F172A; letter-spacing: -0.01em;">{{ $adminName }}</div>
    <div id="realtime-time" style="font-family: 'Montserrat', monospace; font-size: 1.5rem; font-weight: 800; color: #3B82F6; margin-top: 0.5rem; font-variant-numeric: tabular-nums;">00:00:00</div>
    <div id="realtime-date" style="display:none;"></div>
  </div>
</div>

{{-- Page Header --}}
<div style="margin-bottom:1.5rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
  <div>
    <h1 style="font-size:1.375rem;font-weight:700;color:var(--text1);margin:0 0 .25rem;letter-spacing:-.02em;">Overview</h1>
    <p style="font-size:.8125rem;color:var(--text3);margin:0;">Ringkasan data website buyle.id</p>
  </div>
  <form method="GET" action="{{ route('admin.dashboard') }}" style="display: flex; gap: 0.5rem; align-items: center;">
    <input type="date" id="dash-start" name="start_date" value="{{ $start_date ?? '' }}" style="padding: 0.5rem 0.75rem; font-size: 0.8rem; background: #fff; border: 1px solid var(--border); border-radius: 50px; color: var(--text1); font-family: inherit; font-weight: 500;">
    <span style="color: var(--text3); font-weight: 500; font-size: 0.8rem;">s/d</span>
    <input type="date" id="dash-end" name="end_date" value="{{ $end_date ?? '' }}" style="padding: 0.5rem 0.75rem; font-size: 0.8rem; background: #fff; border: 1px solid var(--border); border-radius: 50px; color: var(--text1); font-family: inherit; font-weight: 500;">
    <button type="submit" class="btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.8rem; border-radius: 50px; background: #3B82F6; color: #fff; border: none; cursor: pointer; font-weight: 600;">Filter</button>
    <div style="border-left: 1px solid var(--border); margin: 0 0.25rem; height: 30px;"></div>
    <button type="button" onclick="downloadReport('xls')" class="btn-outline" style="padding: 0.5rem 1rem; font-size: 0.8rem; border: 1px solid var(--border); border-radius: 50px; color: var(--text2); background: #fff; cursor: pointer; font-weight: 600; display:flex; gap:0.3rem; align-items:center;" onmouseover="this.style.borderColor='#3B82F6'; this.style.color='#3B82F6';" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text2)';">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg> XLS
    </button>
    <button type="button" onclick="downloadReport('pdf')" class="btn-outline" style="padding: 0.5rem 1rem; font-size: 0.8rem; border: 1px solid var(--border); border-radius: 50px; color: var(--text2); background: #fff; cursor: pointer; font-weight: 600; display:flex; gap:0.3rem; align-items:center;" onmouseover="this.style.borderColor='#EF4444'; this.style.color='#EF4444';" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text2)';">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg> PDF
    </button>
  </form>
</div>
<div style="font-size:0.75rem; color:var(--text3,#7A7A8A); margin-top:-1rem; margin-bottom:1.5rem; text-align:right;">
  *Silakan filter periode tanggal terlebih dahulu sebelum men-download laporan.
</div>

<script>
function downloadReport(format) {
    const start = document.getElementById('dash-start').value;
    const end = document.getElementById('dash-end').value;
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

{{-- STAT CARDS ROW --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-bottom:2rem;">
  @foreach([
    ['Visitor / Page Views','visitor',$stats['visitor'],'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z','#3B82F6', 'rgba(59, 130, 246, 0.1)'],
    ['Klik WA','wa_click',$stats['wa_click'],'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z','#10B981', 'rgba(16, 185, 129, 0.1)'],
    ['Total Leads','leads',$stats['leads'],'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','#F59E0B', 'rgba(245, 158, 11, 0.1)'],
    ['Total Buyer','total_buyers',$stats['total_buyers'],'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M23 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75','#8B5CF6', 'rgba(139, 92, 246, 0.1)'],
  ] as $sc)
  <div style="background:#FFFFFF;border-radius:20px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.03);display:flex;flex-direction:column;gap:1rem;transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.03)';">
    <div style="display:flex;align-items:center;gap:1rem;">
      <div style="background:{{ $sc[5] }};border-radius:12px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="24" height="24" fill="none" stroke="{{ $sc[4] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $sc[3] }}"/></svg>
      </div>
      <div>
        <div style="font-size:0.875rem;color:var(--text3);font-weight:500;margin-bottom:0.2rem;">{{ $sc[0] }}</div>
        <div style="font-size:1.75rem;font-weight:800;color:var(--text1);line-height:1;">{{ $sc[2] }}</div>
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- MIDDLE ROW: Chart + Content Stats --}}
<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:1.5rem;margin-bottom:2rem;">

  {{-- Leads Chart --}}
  <div style="background:#FFFFFF;border-radius:24px;padding:1.75rem 1.75rem 1.5rem;box-shadow:0 2px 20px rgba(0,0,0,0.04);">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.75rem;">
      <div>
        <div style="font-size:.8rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.35rem;">Traffic &amp; Leads</div>
        <div style="font-size:1.85rem;font-weight:800;color:#1E293B;line-height:1;">{{ $monthLeads }} <span style="font-size:.875rem;font-weight:500;color:#94A3B8;">leads bulan ini</span></div>
      </div>
      <div style="display:flex;gap:.625rem;align-items:center;padding:.5rem .875rem;background:#F8FAFC;border-radius:100px;">
        <div style="display:flex;align-items:center;gap:.35rem;font-size:.72rem;font-weight:700;color:#64748B;"><span style="display:inline-block;width:10px;height:3px;background:#A3AED0;border-radius:2px;"></span>Visitor</div>
        <div style="display:flex;align-items:center;gap:.35rem;font-size:.72rem;font-weight:700;color:#64748B;"><span style="display:inline-block;width:10px;height:3px;background:#10B981;border-radius:2px;"></span>WA</div>
        <div style="display:flex;align-items:center;gap:.35rem;font-size:.72rem;font-weight:700;color:#64748B;"><span style="display:inline-block;width:10px;height:3px;background:#3B82F6;border-radius:2px;"></span>Leads</div>
        <div style="display:flex;align-items:center;gap:.35rem;font-size:.72rem;font-weight:700;color:#64748B;"><span style="display:inline-block;width:10px;height:3px;background:#8B5CF6;border-radius:2px;"></span>Buyer Baru</div>
      </div>
    </div>
    <canvas id="leads-chart" height="105"></canvas>
  </div>

  {{-- Content Overview --}}
  <div style="background:#FFFFFF;border-radius:20px;padding:1.5rem;box-shadow:0 4px 15px rgba(0,0,0,0.03);">
    <div style="font-size:.75rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text3);margin-bottom:1.25rem;">Konten Website</div>
    <div style="display:flex; flex-direction:column; gap:0.5rem;">
    @foreach([
      ['Layanan',route('admin.services.index'),$counts['services'],'#F59E0B','M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z', 'rgba(245, 158, 11, 0.1)'],
      ['Galeri',route('admin.gallery.index'),$counts['gallery'],'#3B82F6','M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'rgba(59, 130, 246, 0.1)'],
      ['Artikel',route('admin.articles.index'),$counts['articles'],'#8B5CF6','M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'rgba(139, 92, 246, 0.1)'],
      ['Klien',route('admin.clients.index'),$counts['clients'],'#10B981','M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'rgba(16, 185, 129, 0.1)'],
    ] as $cc)
    <a href="{{ $cc[1] }}" style="display:flex;align-items:center;justify-content:space-between;padding:.75rem 1rem;border-radius:12px;text-decoration:none;transition:background .2s;" onmouseover="this.style.background='var(--bg3)'" onmouseout="this.style.background='transparent'">
      <div style="display:flex;align-items:center;gap:.75rem;">
        <div style="width:36px;height:36px;background:{{ $cc[5] }};border-radius:10px;display:flex;align-items:center;justify-content:center;">
          <svg width="18" height="18" fill="none" stroke="{{ $cc[3] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $cc[4] }}"/></svg>
        </div>
        <span style="font-size:.875rem;color:var(--text1);font-weight:600;">{{ $cc[0] }}</span>
      </div>
      <div style="display:flex;align-items:center;gap:.5rem;">
        <span style="font-size:1.125rem;font-weight:800;color:{{ $cc[3] }};">{{ $cc[2] }}</span>
        <svg width="14" height="14" fill="none" stroke="var(--text3)" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
      </div>
    </a>
    @endforeach
    </div>
  </div>
</div>

{{-- RECENT LEADS TABLE --}}
<div style="background:#FFFFFF;border-radius:20px;box-shadow:0 4px 15px rgba(0,0,0,0.03);overflow:hidden;">
  <div style="display:flex;align-items:center;justify-content:space-between;padding:1.5rem 2rem;border-bottom:1px solid var(--border);">
    <div>
      <div style="font-size:1.125rem;font-weight:700;color:var(--text1);">Checkup progress / Leads</div>
      <div style="font-size:.8rem;color:var(--text3);margin-top:.25rem;">Data Request Order terbaru</div>
    </div>
    <a href="{{ route('admin.leads.index') }}" style="display:inline-flex;align-items:center;gap:.375rem;font-size:.875rem;font-weight:600;color:#3B82F6;text-decoration:none;">
      Lihat Semua
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
  </div>
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;">
      <thead>
        <tr>
          @foreach(['Nama','Produk','Telepon','Waktu','Status'] as $h)
          <th style="padding:1rem 2rem;text-align:left;font-size:.75rem;font-weight:700;color:var(--text3);background:var(--bg3);white-space:nowrap; border-bottom:1px solid var(--border);">{{ $h }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @forelse($recentLeads as $lead)
        <tr style="border-bottom:1px solid var(--border);transition:background .2s;" onmouseover="this.style.background='var(--bg3)'" onmouseout="this.style.background='transparent'">
          <td style="padding:1rem 2rem;">
            <div style="font-size:.875rem;font-weight:700;color:var(--text1);">{{ $lead->name }}</div>
            @if($lead->company)<div style="font-size:.75rem;color:var(--text3);margin-top:0.15rem;">{{ $lead->company }}</div>@endif
          </td>
          <td style="padding:1rem 2rem;font-size:.875rem;color:var(--text2);max-width:180px;font-weight:500;">
            <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:block;">{{ $lead->product ?? '-' }}</span>
          </td>
          <td style="padding:1rem 2rem;font-size:.875rem;color:var(--text2);white-space:nowrap;font-weight:500;">{{ $lead->phone }}</td>
          <td style="padding:1rem 2rem;font-size:.875rem;color:var(--text3);white-space:nowrap;font-weight:500;">{{ $lead->created_at->diffForHumans() }}</td>
          <td style="padding:1rem 2rem;">
            @php
              $sc = match($lead->status){
                'new'=>['#F59E0B','rgba(245, 158, 11, 0.1)','Baru'],
                'contacted'=>['#3B82F6','rgba(59, 130, 246, 0.1)','Dihubungi'],
                'closed'=>['#10B981','rgba(16, 185, 129, 0.1)','Selesai'],
                default=>['#64748B','rgba(100, 116, 139, 0.1)','Lainnya']};
            @endphp
            <span style="background:{{ $sc[1] }};color:{{ $sc[0] }};font-size:.7rem;font-weight:700;padding:.375rem .75rem;border-radius:100px;white-space:nowrap;">{{ $sc[2] }}</span>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" style="padding:3rem;text-align:center;color:var(--text3);font-size:1rem;font-weight:500;">Belum ada leads masuk</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Quick Actions --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem;margin-top:2rem;">
  @foreach([
    ['Tambah Layanan',route('admin.services.create'),'#F59E0B','rgba(245, 158, 11, 0.1)','M12 4v16m8-8H4'],
    ['Upload Foto',route('admin.gallery.create'),'#3B82F6','rgba(59, 130, 246, 0.1)','M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01'],
    ['Tulis Artikel',route('admin.articles.create'),'#8B5CF6','rgba(139, 92, 246, 0.1)','M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
    ['Pengaturan WA',route('admin.wa.index'),'#10B981','rgba(16, 185, 129, 0.1)','M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15'],
  ] as $qa)
  <a href="{{ $qa[1] }}" style="background:#FFFFFF;border-radius:16px;padding:1.25rem;display:flex;align-items:center;gap:1rem;text-decoration:none;box-shadow:0 4px 15px rgba(0,0,0,0.03);transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.03)';">
    <div style="width:42px;height:42px;background:{{ $qa[3] }};border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <svg width="20" height="20" fill="none" stroke="{{ $qa[2] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $qa[4] }}"/></svg>
    </div>
    <span style="font-size:.9rem;font-weight:700;color:var(--text1);">{{ $qa[0] }}</span>
  </a>
  @endforeach
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Realtime Clock Widget
function updateClock() {
    const now = new Date();
    
    // Time
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('realtime-time').textContent = `${h}:${m}:${s}`;
    
    // Date
    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    const day = days[now.getDay()];
    const date = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();
    document.getElementById('realtime-date').textContent = `${day}, ${date} ${month} ${year}`;
}
setInterval(updateClock, 1000);
updateClock();

// ─── PREMIUM CHART CONFIG ───
Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";

// Crosshair plugin
const crosshairPlugin = {
  id: 'crosshair',
  afterDraw(chart) {
    if (chart.tooltip._active && chart.tooltip._active.length) {
      const ctx = chart.ctx;
      const x = chart.tooltip._active[0].element.x;
      ctx.save();
      ctx.beginPath();
      ctx.moveTo(x, chart.scales.y.top);
      ctx.lineTo(x, chart.scales.y.bottom);
      ctx.lineWidth = 1;
      ctx.strokeStyle = 'rgba(59,130,246,0.2)';
      ctx.setLineDash([5, 4]);
      ctx.stroke();
      ctx.restore();
    }
  }
};
Chart.register(crosshairPlugin);

// Build gradients
const dashCtx = document.getElementById('leads-chart').getContext('2d');
const gVisitor = dashCtx.createLinearGradient(0, 0, 0, 280);
gVisitor.addColorStop(0, 'rgba(163,174,208,0.20)');
gVisitor.addColorStop(1, 'rgba(163,174,208,0.00)');

const gWa = dashCtx.createLinearGradient(0, 0, 0, 280);
gWa.addColorStop(0, 'rgba(16,185,129,0.15)');
gWa.addColorStop(1, 'rgba(16,185,129,0.00)');

const gLeads = dashCtx.createLinearGradient(0, 0, 0, 280);
gLeads.addColorStop(0, 'rgba(59,130,246,0.25)');
gLeads.addColorStop(1, 'rgba(59,130,246,0.00)');

const gBuyer = dashCtx.createLinearGradient(0, 0, 0, 280);
gBuyer.addColorStop(0, 'rgba(139,92,246,0.20)');
gBuyer.addColorStop(1, 'rgba(139,92,246,0.00)');

new Chart(dashCtx, {
  type: 'line',
  data: {
    labels: {!! json_encode($labels) !!},
    datasets: [
      {
        label: 'Visitor',
        data: {!! json_encode($visitorValues) !!},
        borderColor: '#A3AED0',
        backgroundColor: gVisitor,
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
        label: 'WA Click',
        data: {!! json_encode($waValues) !!},
        borderColor: '#10B981',
        backgroundColor: gWa,
        borderWidth: 2.5,
        tension: 0.45,
        fill: true,
        pointRadius: 0,
        pointHoverRadius: 5,
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: '#10B981',
        pointHoverBorderWidth: 2.5,
      },
      {
        label: 'Leads',
        data: {!! json_encode($values) !!},
        borderColor: '#3B82F6',
        backgroundColor: gLeads,
        borderWidth: 2.5,
        tension: 0.45,
        fill: true,
        pointRadius: 0,
        pointHoverRadius: 6,
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: '#3B82F6',
        pointHoverBorderWidth: 2.5,
      },
      {
        label: 'Buyer Baru',
        data: {!! json_encode($buyerValues) !!},
        borderColor: '#8B5CF6',
        backgroundColor: gBuyer,
        borderWidth: 2.5,
        tension: 0.45,
        fill: true,
        pointRadius: 0,
        pointHoverRadius: 6,
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: '#8B5CF6',
        pointHoverBorderWidth: 2.5,
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true,
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
            const dots = { 'Visitor': '⬤ ', 'WA Click': '⬤ ', 'Leads': '⬤ ' };
            return `  ${ctx.parsed.y}  ${ctx.dataset.label}`;
          }
        }
      }
    },
    scales: {
      x: {
        grid: { display: false },
        border: { display: false },
        ticks: { color: '#94A3B8', font: { size: 11 }, maxRotation: 0, maxTicksLimit: 8 }
      },
      y: {
        grid: { color: '#F1F5F9', lineWidth: 1, borderDash: [5, 4] },
        border: { display: false },
        ticks: { color: '#94A3B8', font: { size: 11 }, stepSize: 1, precision: 0 }
      }
    }
  }
});
</script>
@endpush
@endsection
