@extends('layouts.admin')
@section('title', 'Detail Resource Creator - ' . $user->name)
@section('page-title', 'Detail Audit Resource Creator')

@section('content')
<style>
.res-detail-page { font-family: 'Montserrat', sans-serif; }

/* Top Header Card */
.res-header-card {
    background: #ffffff;
    border: 1.5px solid #E2E8F0;
    border-radius: 20px;
    padding: 1.5rem 1.75rem;
    margin-bottom: 1.75rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.res-creator-profile {
    display: flex;
    align-items: center;
    gap: 1.25rem;
}

.res-detail-avatar {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    object-fit: cover;
    background: #1eb349;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.res-creator-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.3;
}

.res-creator-sub {
    font-size: 0.85rem;
    color: #64748B;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.res-header-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-impersonate {
    background: #0F172A;
    color: #ffffff;
    border: none;
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-impersonate:hover {
    background: #1E293B;
    color: #ffffff;
}

.btn-compress-all {
    background: #F0FDF4;
    color: #1eb349;
    border: 1.5px solid #DCFCE7;
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
}

.btn-compress-all:hover {
    background: #1eb349;
    color: #ffffff;
}

.btn-back {
    background: #F8FAFC;
    color: #475569;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 0.75rem 1.25rem;
    font-size: 0.85rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-back:hover {
    background: #E2E8F0;
    color: #0F172A;
}

/* Metric Cards */
.res-metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.75rem;
}

.res-metric-card {
    background: #ffffff;
    border: 1.5px solid #F1F5F9;
    border-radius: 18px;
    padding: 1.25rem 1.4rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
}

.res-metric-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #64748B;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.res-metric-value {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.2;
}

.res-metric-desc {
    font-size: 0.78rem;
    color: #64748B;
    margin-top: 0.35rem;
}

/* Analysis Box (ROI & Hog Detector) */
.res-analysis-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.75rem;
}

@media(max-width: 992px) {
    .res-analysis-grid { grid-template-columns: 1fr; }
}

.res-analysis-card {
    background: #ffffff;
    border: 1.5px solid #E2E8F0;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.02);
}

.res-analysis-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0F172A;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #F1F5F9;
}

.res-analysis-box {
    background: #F8FAFC;
    border-radius: 14px;
    padding: 1rem;
    border: 1px solid #E2E8F0;
}

/* Assets Table Card */
.res-table-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1.5px solid #F1F5F9;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    overflow: hidden;
}

.res-table-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1.5px solid #F1F5F9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    background: #ffffff;
}

.res-table-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0F172A;
}

.res-table-sub {
    font-size: 0.78rem;
    color: #64748B;
    margin-top: 0.15rem;
}

.res-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}

.res-table th {
    background: #F8FAFC;
    padding: 0.9rem 1.25rem;
    text-align: left;
    font-weight: 700;
    color: #475569;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1.5px solid #E2E8F0;
}

.res-table td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #F1F5F9;
    color: #1E293B;
    vertical-align: middle;
}

.res-table tr:hover td {
    background: #F8FAFC;
}

/* File Thumb */
.asset-thumb-wrap {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    overflow: hidden;
    background: #F1F5F9;
    border: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.asset-thumb-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.asset-file-icon {
    color: #64748B;
}

/* Dependency Badges */
.dep-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.78rem;
    font-weight: 600;
    color: #0F172A;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    padding: 0.35rem 0.65rem;
    border-radius: 8px;
    margin-bottom: 0.35rem;
}

.dep-item:last-child { margin-bottom: 0; }

.dep-sub {
    font-size: 0.7rem;
    color: #64748B;
    margin-left: auto;
}

/* Traffic Badge */
.traffic-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.3rem 0.65rem;
    border-radius: 100px;
    font-size: 0.75rem;
    font-weight: 700;
    background: #F8FAFC;
    color: #334155;
    border: 1px solid #E2E8F0;
}

.btn-open-link {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.75rem;
    border-radius: 8px;
    background: #F1F5F9;
    color: #334155;
    font-weight: 700;
    font-size: 0.75rem;
    text-decoration: none;
    transition: all 0.2s;
    border: 1px solid #CBD5E1;
}

.btn-open-link:hover {
    background: #0F172A;
    color: #ffffff;
}

.btn-compress-item {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.4rem 0.75rem;
    border-radius: 8px;
    background: #F0FDF4;
    color: #1eb349;
    font-weight: 700;
    font-size: 0.75rem;
    border: 1px solid #DCFCE7;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-compress-item:hover {
    background: #1eb349;
    color: #ffffff;
}
</style>

<div class="res-detail-page">
    @if(session('success'))
        <div style="background:#F0FDF4;color:#15803D;padding:.875rem 1.25rem;border-radius:14px;margin-bottom:1.5rem;border:1px solid #BBF7D0;font-size:.825rem;font-weight:600;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#FEF2F2;color:#991B1B;padding:.875rem 1.25rem;border-radius:14px;margin-bottom:1.5rem;border:1px solid #FECACA;font-size:.825rem;font-weight:600;">
            ✕ {{ session('error') }}
        </div>
    @endif

    {{-- Top Header --}}
    <div class="res-header-card">
        <div class="res-creator-profile">
            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="res-detail-avatar" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <div class="res-detail-avatar" style="display:none;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @else
                <div class="res-detail-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif
            <div>
                <div class="res-creator-title">{{ $user->name }}</div>
                <div class="res-creator-sub">
                    <span>{{ $creatorProfile ? ($creatorProfile->store_name ?: '@' . $user->username) : '@' . ($user->username ?: 'user') }}</span>
                    <span>&bull;</span>
                    <span>{{ $user->email }}</span>
                    <span>&bull;</span>
                    <span style="background:#F1F5F9; padding:0.15rem 0.5rem; border-radius:6px; font-weight:700; color:#334155; font-size:0.75rem;">
                        {{ strtoupper($user->role) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="res-header-actions">
            <a href="{{ route('admin.creator-resources.index') }}" class="btn-back">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>

            <form action="{{ route('admin.creator-resources.compress-all', $user->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-compress-all" onclick="return confirm('Kompresi maksimal seluruh berkas milik {{ addslashes($user->name) }}?')">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 14h6v6M20 10h-6V4M14 10l7-7M4 20l7-7"/></svg>
                    Compress Semua Aset
                </button>
            </form>

            <form action="{{ route('admin.creator-resources.impersonate', $user->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-impersonate" onclick="return confirm('Anda akan masuk ke Dashboard Creator sebagai {{ addslashes($user->name) }}. Lanjutkan?')">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Masuk ke Dashboard Creator
                </button>
            </form>
        </div>
    </div>

    {{-- Metric Cards --}}
    <div class="res-metrics-grid">
        <div class="res-metric-card">
            <div class="res-metric-label">
                <span>Total Disk Storage</span>
                <svg width="18" height="18" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            </div>
            <div class="res-metric-value">{{ $totalSizeMb }} MB</div>
            <div class="res-metric-desc">Total {{ $totalAssets }} berkas media tersimpan</div>
        </div>

        <div class="res-metric-card">
            <div class="res-metric-label">
                <span>Est. Biaya Server</span>
                <svg width="18" height="18" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            </div>
            <div class="res-metric-value">Rp {{ number_format($estMonthlyCost, 0, ',', '.') }}<span style="font-size:0.8rem; font-weight:600; color:#64748B;">/bln</span></div>
            <div class="res-metric-desc">Kapasitas media di server cloud</div>
        </div>

        <div class="res-metric-card">
            <div class="res-metric-label">
                <span>Omset Penjualan</span>
                <svg width="18" height="18" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="res-metric-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="res-metric-desc">Total akumulasi transaksi berhasil</div>
        </div>

        <div class="res-metric-card">
            <div class="res-metric-label">
                <span>Jumlah Konten</span>
                <svg width="18" height="18" fill="none" stroke="#475569" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
            </div>
            <div class="res-metric-value">{{ $productCount }} <span style="font-size:0.85rem; font-weight:600; color:#64748B;">Produk</span> &bull; {{ $bioBlocksCount }} <span style="font-size:0.85rem; font-weight:600; color:#64748B;">Blocks</span></div>
            <div class="res-metric-desc">Aktif di toko & bio link</div>
        </div>
    </div>

    {{-- Analysis Grid: Storage vs Revenue & Bandwidth Hog --}}
    <div class="res-analysis-grid">
        {{-- Storage Cost vs Revenue Impact --}}
        <div class="res-analysis-card">
            <div class="res-analysis-title">
                <svg width="20" height="20" fill="none" stroke="#0F172A" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                Storage Cost vs Revenue Impact (ROI Meter)
            </div>
            <div class="res-analysis-box">
                @php
                    $ratio = $estMonthlyCost > 0 ? round($totalRevenue / $estMonthlyCost, 1) : 0;
                @endphp
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                    <span style="font-size:0.825rem; font-weight:700; color:#475569;">LTV-to-Storage Cost Ratio:</span>
                    <strong style="font-size:1.1rem; color:#0F172A;">
                        {{ $ratio }}x
                    </strong>
                </div>
                <div style="font-size:0.8rem; color:#475569; line-height:1.5;">
                    @if($totalRevenue > 0)
                        Kreator ini memakan storage <strong>{{ $totalSizeMb }} MB</strong> (Biaya server est. <strong>Rp {{ number_format($estMonthlyCost, 0, ',', '.') }}/bulan</strong>), dan menghasilkan omset <strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong>. Performa akun ini <strong>Sangat Menguntungkan (High ROI)</strong>.
                    @elseif($totalSizeMb > 50)
                        Kreator ini memakan storage cukup besar <strong>{{ $totalSizeMb }} MB</strong> (Biaya server est. <strong>Rp {{ number_format($estMonthlyCost, 0, ',', '.') }}/bulan</strong>), namun belum menghasilkan transaksi (<strong>Rp 0</strong>). Direkomendasikan untuk meninjau penggunaan aset.
                    @else
                        Kreator ini menggunakan storage <strong>{{ $totalSizeMb }} MB</strong> (Biaya server est. <strong>Rp {{ number_format($estMonthlyCost, 0, ',', '.') }}/bulan</strong>) dengan omset <strong>Rp 0</strong>. Penggunaan storage masih dalam batas wajar.
                    @endif
                </div>
            </div>
        </div>

        {{-- Bandwidth Hog Detector --}}
        <div class="res-analysis-card">
            <div class="res-analysis-title">
                <svg width="20" height="20" fill="none" stroke="#0F172A" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                Bandwidth Hog & Traffic Detector
            </div>
            <div class="res-analysis-box">
                @php
                    $totalHits = collect($detailedAssets)->sum('hits_count');
                    $topAsset = collect($detailedAssets)->sortByDesc('hits_count')->first();
                @endphp
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                    <span style="font-size:0.825rem; font-weight:700; color:#475569;">Total Traffic Hits / Views:</span>
                    <strong style="font-size:1.1rem; color:#0F172A;">
                        {{ number_format($totalHits) }} hits
                    </strong>
                </div>
                <div style="font-size:0.8rem; color:#475569; line-height:1.5;">
                    @if($topAsset && $topAsset['hits_count'] > 0)
                        File paling tinggi diakses: <strong>{{ $topAsset['type'] }}</strong> dengan total <strong>{{ number_format($topAsset['hits_count']) }} hits</strong>. Tidak terdeteksi lonjakan bandwidth abnormal yang mengancam beban server CDN.
                    @else
                        Belum ada lonjakan traffic atau akses file yang terdeteksi pada media aset creator ini.
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- ══ Location Intelligence Card ══ --}}
    <div style="background:#ffffff; border-radius:20px; border:1.5px solid #E2E8F0; box-shadow:0 4px 20px rgba(0,0,0,0.03); margin-bottom:1.75rem; overflow:hidden;">
        {{-- Header --}}
        <div style="padding:1.1rem 1.5rem; border-bottom:1.5px solid #F1F5F9; display:flex; align-items:center; gap:0.75rem; justify-content:space-between; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:0.6rem;">
                <svg width="20" height="20" fill="none" stroke="#0F172A" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span style="font-size:1rem; font-weight:800; color:#0F172A;">Location Intelligence</span>
            </div>
            <span style="background:#FEF9C3; color:#854D0E; font-size:0.72rem; font-weight:700; padding:0.25rem 0.65rem; border-radius:100px; border:1px solid #FDE047;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline-block;vertical-align:middle;margin-right:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Data hanya terlihat oleh Admin
            </span>
        </div>

        {{-- Content Grid --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:0; border-bottom:1px solid #F1F5F9;">

            {{-- IP Address --}}
            <div style="padding:1.1rem 1.5rem; border-right:1px solid #F1F5F9;">
                <div style="font-size:0.7rem; font-weight:700; color:#94A3B8; letter-spacing:0.07em; text-transform:uppercase; margin-bottom:0.4rem;">Detected IP Address</div>
                @if($detected_ip)
                    <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                        <span style="font-size:0.95rem; font-weight:800; color:#0F172A; font-family:monospace;">{{ $detected_ip }}</span>
                        <a href="https://ipinfo.io/{{ $detected_ip }}" target="_blank"
                           style="display:inline-flex; align-items:center; gap:0.3rem; font-size:0.7rem; font-weight:700; padding:0.2rem 0.5rem; background:#F1F5F9; color:#475569; border-radius:6px; text-decoration:none; border:1px solid #CBD5E1;">
                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            Lookup
                        </a>
                    </div>
                @else
                    <span style="font-size:0.85rem; color:#94A3B8; font-style:italic;">Belum terdeteksi</span>
                @endif
            </div>

            {{-- Koordinat GPS --}}
            <div style="padding:1.1rem 1.5rem; border-right:1px solid #F1F5F9;">
                <div style="font-size:0.7rem; font-weight:700; color:#94A3B8; letter-spacing:0.07em; text-transform:uppercase; margin-bottom:0.4rem;">Koordinat GPS</div>
                @if($latitude && $longitude)
                    <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                        <span style="font-size:0.85rem; font-weight:700; color:#0F172A; font-family:monospace;">{{ $latitude }}, {{ $longitude }}</span>
                    </div>
                @else
                    <span style="font-size:0.85rem; color:#94A3B8; font-style:italic;">Belum terdeteksi</span>
                @endif
            </div>

            {{-- Google Maps Link --}}
            <div style="padding:1.1rem 1.5rem; border-right:1px solid #F1F5F9;">
                <div style="font-size:0.7rem; font-weight:700; color:#94A3B8; letter-spacing:0.07em; text-transform:uppercase; margin-bottom:0.4rem;">Lihat di Maps</div>
                @if($gmaps_link)
                    <a href="{{ $gmaps_link }}" target="_blank"
                       style="display:inline-flex; align-items:center; gap:0.45rem; background:#0F172A; color:#ffffff; font-size:0.82rem; font-weight:700; padding:0.5rem 0.9rem; border-radius:10px; text-decoration:none; transition:background 0.2s;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Buka Google Maps
                    </a>
                @else
                    <span style="font-size:0.85rem; color:#94A3B8; font-style:italic;">Koordinat belum tersedia</span>
                @endif
            </div>

            {{-- Lokasi Alamat --}}
            <div style="padding:1.1rem 1.5rem;">
                <div style="font-size:0.7rem; font-weight:700; color:#94A3B8; letter-spacing:0.07em; text-transform:uppercase; margin-bottom:0.4rem;">Lokasi (Isi Creator)</div>
                @if($province_name || $city_name || $full_address)
                    <div style="font-size:0.85rem; font-weight:600; color:#0F172A; line-height:1.5;">
                        @if($city_name)<div>{{ $city_name }}@if($province_name), {{ $province_name }}@endif</div>@endif
                        @if($full_address)<div style="font-size:0.78rem; color:#64748B; margin-top:0.2rem;">{{ Str::limit($full_address, 60) }}</div>@endif
                    </div>
                @else
                    <span style="font-size:0.85rem; color:#94A3B8; font-style:italic;">Belum diisi creator</span>
                @endif
            </div>
        </div>

        {{-- Mini Map Preview if coordinates available --}}
        @if($latitude && $longitude)
        <div style="padding:0.75rem 1.5rem; background:#F8FAFC; display:flex; align-items:center; gap:0.5rem; font-size:0.78rem; color:#475569;">
            <svg width="14" height="14" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <span>Koordinat akurat terdeteksi via GPS Browser / IP Geolocation saat creator mengisi profil.</span>
            <span style="margin-left:auto; font-family:monospace; font-weight:700; color:#0F172A;">{{ number_format((float)$latitude, 6) }}&deg;N &nbsp; {{ number_format((float)$longitude, 6) }}&deg;E</span>
        </div>
        @endif
    </div>

    {{-- Asset Dependency Graph Table --}}
    <div class="res-table-card">

        <div class="res-table-header">
            <div>
                <div class="res-table-title">Asset Dependency Graph & Daftar Berkas Media</div>
                <div class="res-table-sub">Pemetaan rinci lokasi penggunaan setiap berkas gambar/dokumen pada platform</div>
            </div>
        </div>

        <table class="res-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Preview</th>
                    <th>Nama Berkas & Tipe Aset</th>
                    <th>Ukuran & Path Storage</th>
                    <th>Asset Dependency (Lokasi Penggunaan)</th>
                    <th>Traffic / Hits</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detailedAssets as $asset)
                    @php
                        $isImg = preg_match('/\.(jpg|jpeg|png|webp|gif|svg)$/i', $asset['raw_path']) || \Illuminate\Support\Str::startsWith($asset['raw_path'], ['http://', 'https://']);
                    @endphp
                    <tr>
                        <td>
                            <div class="asset-thumb-wrap">
                                @if($isImg && $asset['url'])
                                    <img src="{{ $asset['url'] }}" alt="Asset Preview" class="asset-thumb-img" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                    <div class="asset-file-icon" style="display:none;">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    </div>
                                @else
                                    <div class="asset-file-icon">
                                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <strong style="font-size:0.875rem; color:#0F172A;">{{ $asset['type'] }}</strong>
                            <div style="font-size:0.75rem; color:#64748B; margin-top:0.15rem; word-break:break-all;">
                                {{ basename($asset['raw_path']) }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size:0.85rem; font-weight:700; color:#0F172A;">{{ $asset['size_formatted'] }}</div>
                            <div style="font-size:0.72rem; color:#64748B; margin-top:0.1rem; word-break:break-all;">
                                {{ $asset['raw_path'] }}
                            </div>
                            @if(!$asset['exists'])
                                <span style="display:inline-block; font-size:0.68rem; font-weight:700; color:#DC2626; background:#FEF2F2; padding:0.1rem 0.4rem; border-radius:4px; margin-top:0.2rem;">
                                    File Tidak Ditemukan di Storage
                                </span>
                            @endif
                        </td>
                        <td>
                            @foreach($asset['used_in'] as $use)
                                <div class="dep-item">
                                    <svg width="14" height="14" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span>{{ $use['label'] }}</span>
                                    <span class="dep-sub">{{ $use['sub'] }}</span>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <span class="traffic-badge">{{ number_format($asset['hits_count']) }} hits</span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display:inline-flex; gap:0.4rem; align-items:center;">
                                @if($isImg && $asset['exists'])
                                    <form action="{{ route('admin.creator-resources.compress-asset', $user->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <input type="hidden" name="raw_path" value="{{ $asset['raw_path'] }}">
                                        <button type="submit" class="btn-compress-item" title="Kompresi Maksimal Berkas Ini">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 14h6v6M20 10h-6V4M14 10l7-7M4 20l7-7"/></svg>
                                            Compress
                                        </button>
                                    </form>
                                @endif

                                @if($asset['url'])
                                    <a href="{{ $asset['url'] }}" target="_blank" class="btn-open-link">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                        Buka File
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:2.5rem; color:#64748B;">
                            Tidak ada berkas media tersimpan untuk creator ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
