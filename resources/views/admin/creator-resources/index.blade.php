@extends('layouts.admin')
@section('title', 'Audit Resource & Revenue Creators')
@section('page-title', 'Audit Resource, Revenue & Aktivitas Creators')

@section('content')
<style>
.res-page { font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif; }

/* Stat Cards Grid - Exactly 1 Baris 10 Card */
.res-stats-grid-10 {
    display: grid;
    grid-template-columns: repeat(10, minmax(120px, 1fr));
    gap: 0.65rem;
    margin-bottom: 1.5rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
    scrollbar-width: thin;
}

.res-stat-card {
    background: #ffffff;
    border: 1.5px solid #F1F5F9;
    border-radius: 16px;
    padding: 0.85rem 0.75rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 96px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.res-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.06);
}

.res-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.35rem;
}

.res-stat-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(30, 179, 73, 0.08);
    color: #1eb349;
    border: 1px solid rgba(30, 179, 73, 0.15);
    flex-shrink: 0;
}

.res-stat-val { font-size: 1.05rem; font-weight: 800; color: #0F172A; line-height: 1.2; word-break: break-word; }
.res-stat-lbl { font-size: 0.65rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.03em; }

/* Filter & Search Bar */
.res-filter-box {
    background: #ffffff;
    border: 1.5px solid #E2E8F0;
    border-radius: 18px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.res-search-group {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    flex: 1;
    min-width: 260px;
}

.res-input {
    padding: 0.6rem 1rem;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    font-size: 0.8125rem;
    outline: none;
    width: 100%;
    font-family: inherit;
    background: #F8FAFC;
    color: #0F172A;
    transition: all 0.2s;
}

.res-input:focus {
    border-color: #1eb349;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(30,179,73,0.1);
}

.res-select {
    padding: 0.6rem 1rem;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    font-size: 0.8125rem;
    outline: none;
    font-family: inherit;
    background: #F8FAFC;
    color: #0F172A;
    cursor: pointer;
}

.res-btn {
    background: #0F172A;
    color: #ffffff;
    border: none;
    border-radius: 12px;
    padding: 0.6rem 1.25rem;
    font-size: 0.8125rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    transition: background 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.res-btn:hover { background: #1E293B; }

/* View Switcher Controls */
.view-switch-btn {
    padding: 0.55rem 0.85rem;
    border-radius: 10px;
    border: 1.5px solid #E2E8F0;
    background: #F8FAFC;
    color: #64748B;
    font-weight: 700;
    font-size: 0.78rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition: all 0.2s;
}

.view-switch-btn.active {
    background: #0F172A;
    color: #ffffff;
    border-color: #0F172A;
}

/* Creator Grid Layout */
.res-creators-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.25rem;
    margin-top: 1rem;
}

.creator-card-box {
    background: #ffffff;
    border: 1.5px solid #F1F5F9;
    border-radius: 20px;
    padding: 1.25rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.creator-card-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.07);
    border-color: #CBD5E1;
}

/* Table Container */
.res-table-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1.5px solid #F1F5F9;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    overflow: hidden;
}

.res-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
}

.res-table th {
    background: #F8FAFC;
    padding: 0.9rem 1.1rem;
    text-align: left;
    font-weight: 700;
    color: #475569;
    font-size: 0.725rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1.5px solid #E2E8F0;
    white-space: nowrap;
}

.res-table td {
    padding: 0.95rem 1.1rem;
    border-bottom: 1px solid #F1F5F9;
    color: #1E293B;
    vertical-align: middle;
}

.res-table tr:hover td {
    background: #F8FAFC;
}

.res-user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.res-avatar-wrap {
    position: relative;
    width: 42px;
    height: 42px;
    flex-shrink: 0;
}

.res-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    background: #1eb349;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.85rem;
}

/* Online Indicator Glowing Animation */
.res-online-pulse {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    background: #10B981;
    border: 2px solid #ffffff;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.4);
    animation: pulse-ring 2s infinite;
}

@keyframes pulse-ring {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6); }
    70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.res-user-name {
    font-weight: 700;
    color: #0F172A;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.res-user-sub {
    font-size: 0.75rem;
    color: #64748B;
    margin-top: 0.1rem;
}

/* STRICT NO WRAPPING FOR BADGES */
.online-badge-now, .online-badge-today, .online-badge-week, .online-badge-inactive, .res-badge {
    white-space: nowrap !important;
    word-break: keep-all !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.35rem !important;
    padding: 0.3rem 0.65rem !important;
    border-radius: 100px !important;
    font-size: 0.72rem !important;
    font-weight: 700 !important;
    line-height: 1 !important;
}

.online-badge-now { color: #10B981; background: #ECFDF5; border: 1px solid #A7F3D0; }
.online-badge-today { color: #D97706; background: #FFFBEB; border: 1px solid #FDE68A; }
.online-badge-week { color: #2563EB; background: #EFF6FF; border: 1px solid #BFDBFE; }
.online-badge-inactive { color: #64748B; background: #F8FAFC; border: 1px solid #E2E8F0; }

.res-badge.high-roi { background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7; }
.res-badge.normal   { background: #F8FAFC; color: #475569; border: 1px solid #E2E8F0; }
.res-badge.warning  { background: #FFFBEB; color: #D97706; border: 1px solid #FEF3C7; }
.res-badge.danger   { background: #FEF2F2; color: #DC2626; border: 1px solid #FEE2E2; }

.btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.4rem 0.75rem;
    border-radius: 10px;
    background: #F8FAFC;
    color: #0F172A;
    font-weight: 700;
    font-size: 0.75rem;
    text-decoration: none;
    transition: all 0.2s;
    border: 1.5px solid #E2E8F0;
    white-space: nowrap;
}

.btn-detail:hover { background: #0F172A; color: #ffffff; }

.btn-compress-sm {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.4rem 0.65rem;
    border-radius: 10px;
    background: #F0FDF4;
    color: #1eb349;
    font-weight: 700;
    font-size: 0.75rem;
    border: 1px solid #DCFCE7;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.btn-compress-sm:hover { background: #1eb349; color: #ffffff; }

.btn-wa-nudge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.4rem 0.65rem;
    border-radius: 10px;
    background: #25D366;
    color: #ffffff;
    font-weight: 700;
    font-size: 0.75rem;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}

.btn-wa-nudge:hover { background: #128C7E; color: #ffffff; }

.btn-clean-orphan {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FEE2E2;
    border-radius: 8px;
    padding: 0.25rem 0.5rem;
    font-size: 0.65rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 0.25rem;
    transition: all 0.2s;
    width: 100%;
}

.btn-clean-orphan:hover { background: #DC2626; color: #ffffff; }

/* Modals Overlay */
.res-modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.res-modal-card {
    background: #ffffff;
    border-radius: 20px;
    max-width: 750px;
    width: 100%;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    overflow: hidden;
}

.res-modal-header {
    padding: 1.25rem 1.5rem;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.res-modal-body {
    padding: 1.25rem 1.5rem;
    overflow-y: auto;
}

@media(max-width: 1200px) {
    .res-stats-grid-10 { grid-template-columns: repeat(5, minmax(120px, 1fr)); }
}
@media(max-width: 768px) {
    .res-stats-grid-10 { grid-template-columns: repeat(2, minmax(120px, 1fr)); }
    .res-table-card { overflow-x: auto; }
    .res-table { min-width: 1050px; }
}
</style>

<div class="res-page">
    @if(session('success'))
        <div style="background:#F0FDF4;color:#15803D;padding:.875rem 1.25rem;border-radius:14px;margin-bottom:1.5rem;border:1px solid #BBF7D0;font-size:.825rem;font-weight:600;display:flex;align-items:center;gap:.5rem;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#FEF2F2;color:#991B1B;padding:.875rem 1.25rem;border-radius:14px;margin-bottom:1.5rem;border:1px solid #FECACA;font-size:.825rem;font-weight:600;display:flex;align-items:center;gap:.5rem;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Grid View 1 Baris 10 Card --}}
    <div class="res-stats-grid-10">
        {{-- Card 1: Total Creators --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Creators</div>
                <div class="res-stat-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
            </div>
            <div class="res-stat-val">{{ number_format($totalCreatorsCount) }}</div>
        </div>

        {{-- Card 2: Online Hari Ini --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Online</div>
                <div class="res-stat-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
            </div>
            <div class="res-stat-val" style="color:#10B981;">{{ number_format($onlineCreatorsCount) }}</div>
        </div>

        {{-- Card 3: Disk Storage --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Storage</div>
                <div class="res-stat-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
            </div>
            <div class="res-stat-val">{{ $totalStorageMbOverall }} MB</div>
        </div>

        {{-- Card 4: Est Server Cost --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Biaya Server</div>
                <div class="res-stat-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
            </div>
            <div class="res-stat-val">Rp {{ number_format($estServerCostOverall, 0, ',', '.') }}</div>
        </div>

        {{-- Card 5: Total Omset --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Total Omset</div>
                <div class="res-stat-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="res-stat-val" style="color:#16A34A;">Rp {{ number_format($totalRevenueOverall, 0, ',', '.') }}</div>
        </div>

        {{-- Card 6: Abandoned Cart Potential (Omset Tertunda) --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Cart Pending</div>
                <div class="res-stat-icon" style="background:rgba(217, 119, 6, 0.08); color:#D97706; border-color:rgba(217, 119, 6, 0.15);">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </div>
            </div>
            <div class="res-stat-val" style="color:#D97706;">Rp {{ number_format($totalAbandonedOverall, 0, ',', '.') }}</div>
        </div>

        {{-- Card 7: Efficiency (LTV/MB) --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">LTV / MB</div>
                <div class="res-stat-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
            </div>
            <div class="res-stat-val">Rp {{ number_format($avgLtvPerMb, 0, ',', '.') }}</div>
        </div>

        {{-- Card 8: Total Produk --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Produk</div>
                <div class="res-stat-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
                </div>
            </div>
            <div class="res-stat-val">{{ number_format($totalProductsOverall) }}</div>
        </div>

        {{-- Card 9: Total Assets --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">File Media</div>
                <div class="res-stat-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
            </div>
            <div class="res-stat-val">{{ number_format($totalAssetsOverall) }}</div>
        </div>

        {{-- Card 10: Berkas Sampah Terbuang (Orphan Cleaner) --}}
        <div class="res-stat-card" style="border-color:{{ $orphanData['count'] > 0 ? '#FECACA' : '#F1F5F9' }};">
            <div class="res-stat-top">
                <div class="res-stat-lbl" style="color:{{ $orphanData['count'] > 0 ? '#DC2626' : '#64748B' }};">Ghost Files</div>
                <div class="res-stat-icon" style="background:rgba(220, 38, 38, 0.08); color:#DC2626; border-color:rgba(220, 38, 38, 0.15);">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </div>
            </div>
            <div class="res-stat-val" style="color:{{ $orphanData['count'] > 0 ? '#DC2626' : '#0F172A' }}; cursor:pointer;" onclick="openGhostModal()">
                {{ $orphanData['count'] }} <span style="font-size:0.68rem; font-weight:600; color:#64748B;">({{ $orphanData['total_mb'] }} MB)</span>
            </div>
            @if($orphanData['count'] > 0)
                <button type="button" class="btn-clean-orphan" onclick="openGhostModal()">
                    Bersihkan Sampah
                </button>
            @else
                <div style="font-size:0.65rem; color:#10B981; font-weight:700; display:flex; align-items:center; gap:0.2rem;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Bersih (0 Ghost)
                </div>
            @endif
        </div>
    </div>

    {{-- Filter / Search Bar & View Switcher --}}
    <form method="GET" action="{{ route('admin.creator-resources.index') }}" class="res-filter-box">
        <div class="res-search-group">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, username, telepon, atau toko creator..." class="res-input">
            <button type="submit" class="res-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Cari
            </button>
        </div>

        <div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">
            {{-- Filter Status Online --}}
            <div style="display:flex; align-items:center; gap:0.3rem;">
                <label style="font-size:0.75rem; font-weight:700; color:#64748B;">STATUS ONLINE:</label>
                <select name="online_status" onchange="this.form.submit()" class="res-select">
                    <option value="all"           {{ $onlineFilter === 'all'           ? 'selected' : '' }}>Semua Creator</option>
                    <option value="online_now"    {{ $onlineFilter === 'online_now'    ? 'selected' : '' }}>Online Sekarang</option>
                    <option value="active_today"  {{ $onlineFilter === 'active_today'  ? 'selected' : '' }}>Aktif Hari Ini</option>
                    <option value="active_week"   {{ $onlineFilter === 'active_week'   ? 'selected' : '' }}>Aktif Minggu Ini</option>
                    <option value="inactive"      {{ $onlineFilter === 'inactive'      ? 'selected' : '' }}>Inaktif (> 7 Hari)</option>
                </select>
            </div>

            {{-- Filter Urutan --}}
            <div style="display:flex; align-items:center; gap:0.3rem;">
                <label style="font-size:0.75rem; font-weight:700; color:#64748B;">URUTKAN:</label>
                <select name="sort_by" onchange="this.form.submit()" class="res-select">
                    <option value="storage_desc"    {{ $sortBy === 'storage_desc'    ? 'selected' : '' }}>Storage Terbesar (Boros)</option>
                    <option value="storage_asc"     {{ $sortBy === 'storage_asc'     ? 'selected' : '' }}>Storage Terkecil</option>
                    <option value="revenue_desc"    {{ $sortBy === 'revenue_desc'    ? 'selected' : '' }}>Omset Penjualan Terbanyak</option>
                    <option value="abandoned_desc"  {{ $sortBy === 'abandoned_desc'  ? 'selected' : '' }}>Potensi Cart Tertunda</option>
                    <option value="online_recent"   {{ $sortBy === 'online_recent'   ? 'selected' : '' }}>Aktivitas Online Terbaru</option>
                    <option value="products_desc"   {{ $sortBy === 'products_desc'   ? 'selected' : '' }}>Jumlah Produk Terbanyak</option>
                    <option value="blocks_desc"     {{ $sortBy === 'blocks_desc'     ? 'selected' : '' }}>Jumlah Bio Block Terbanyak</option>
                </select>
            </div>

            {{-- View Mode Switcher (Table vs Grid) --}}
            <div style="display:flex; gap:0.25rem; background:#F1F5F9; padding:0.2rem; border-radius:12px;">
                <button type="button" id="btnViewTable" onclick="switchViewMode('table')" class="view-switch-btn active" title="Tampilan Tabel Data">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    Tabel
                </button>
                <button type="button" id="btnViewGrid" onclick="switchViewMode('grid')" class="view-switch-btn" title="Tampilan Grid Card Creator">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Grid Cards
                </button>
            </div>
        </div>
    </form>

    {{-- VIEW MODE 1: Table View --}}
    <div id="viewContainerTable" class="res-table-card">
        <table class="res-table">
            <thead>
                <tr>
                    <th>Creator / Toko</th>
                    <th>Riwayat Online</th>
                    <th>Storage Size</th>
                    <th>Est. Biaya Server</th>
                    <th>Total Omset</th>
                    <th>Cart Tertunda</th>
                    <th>Katalog & Block</th>
                    <th>Status ROI & Health</th>
                    <th style="text-align: right;">Aksi Canggih</th>
                </tr>
            </thead>
            <tbody>
                @forelse($creatorResources as $item)
                    @php
                        $user = $item['user'];
                        $profile = $item['creator_profile'];
                        $mb = $item['total_size_mb'];
                        $rev = $item['total_revenue'];
                        $cost = $item['est_monthly_cost'];
                        $abandoned = $item['abandoned_cart_value'];
                    @endphp
                    <tr>
                        <td>
                            <div class="res-user-info">
                                <div class="res-avatar-wrap">
                                    @if($item['avatar_url'])
                                        <img src="{{ $item['avatar_url'] }}" alt="{{ $user->name }}" class="res-avatar" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                        <div class="res-avatar" style="display:none;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    @else
                                        <div class="res-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                    @endif

                                    @if($item['is_online_now'])
                                        <div class="res-online-pulse" title="Online Sekarang (Sedang Aktif)"></div>
                                    @endif
                                </div>
                                <div>
                                    <div class="res-user-name">
                                        {{ $user->name }}
                                    </div>
                                    <div class="res-user-sub">
                                        {{ $profile ? ($profile->store_name ?: '@' . $user->username) : '@' . ($user->username ?: 'user') }}
                                        &bull; {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div title="Terakhir aktif: {{ $item['last_seen_full'] }}">
                                <span class="{{ $item['online_badge_class'] }}">
                                    <svg width="8" height="8" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                                    @if($item['is_online_now'])
                                        Online Now
                                    @else
                                        {{ $item['online_status_label'] }}
                                    @endif
                                </span>
                                <div style="font-size:0.7rem; color:#64748B; margin-top:0.25rem;">
                                    {{ $item['last_seen_text'] }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong style="font-size:0.92rem; color:#0F172A;">{{ $mb }} MB</strong>
                            <div style="font-size:0.72rem; color:#64748B; margin-top:0.1rem;">{{ number_format($item['asset_file_count']) }} berkas</div>
                        </td>
                        <td>
                            <span style="font-size:0.825rem; font-weight:700; color:#475569;">
                                Rp {{ number_format($cost, 0, ',', '.') }}/bln
                            </span>
                        </td>
                        <td>
                            <strong style="font-size:0.92rem; color:#16A34A;">
                                Rp {{ number_format($rev, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>
                            @if($item['abandoned_cart_count'] > 0)
                                <div style="cursor:pointer;" onclick="openCartModal({{ json_encode($item['abandoned_cart_items']) }}, '{{ addslashes($user->name) }}')">
                                    <span style="font-size:0.825rem; font-weight:700; color:#D97706; text-decoration:underline;" title="Klik untuk rincian pembeli yang menunda keranjang">
                                        Rp {{ number_format($abandoned, 0, ',', '.') }}
                                    </span>
                                    <div style="font-size:0.7rem; color:#D97706; font-weight:600;">{{ $item['abandoned_cart_count'] }} item tertunda &rsaquo;</div>
                                </div>
                            @else
                                <span style="font-size:0.78rem; color:#94A3B8;">Rp 0</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-size:0.78rem; font-weight:600; color:#1E293B;">
                                {{ number_format($item['product_count']) }} produk &bull; {{ number_format($item['bio_blocks_count']) }} block
                            </span>
                        </td>
                        <td>
                            @if($rev > 0)
                                <span class="res-badge high-roi">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    High Revenue
                                </span>
                            @elseif($mb > 50)
                                <span class="res-badge danger">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    High Storage Zero Revenue
                                </span>
                            @elseif($abandoned > 0)
                                <span class="res-badge warning">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                                    High Potential Cart
                                </span>
                            @else
                                <span class="res-badge normal">Penggunaan Normal</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display:inline-flex; gap:0.35rem; align-items:center; flex-wrap:nowrap;">
                                {{-- Direct WA Nudge Button --}}
                                @if($item['wa_link'])
                                    <a href="{{ $item['wa_link'] }}" target="_blank" class="btn-wa-nudge" title="Kirim Pesan WhatsApp Sapaan & Support Revenue ke Creator Ini">
                                        <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.385 0 0 5.385 0 12.031c0 2.124.553 4.197 1.604 6.014L0 24l6.138-1.611a11.97 11.97 0 005.893 1.541h.005c6.645 0 12.03-5.386 12.03-12.032C24.066 5.385 18.676 0 12.031 0zm0 22.033h-.004a9.98 9.98 0 01-5.09-1.396l-.365-.217-3.784.993 1.01-3.69-.238-.379a9.95 9.95 0 01-1.528-5.312c0-5.513 4.486-10 10-10 2.671 0 5.182 1.04 7.07 2.93 1.888 1.888 2.927 4.4 2.927 7.07 0 5.514-4.486 10-10 10z"/></svg>
                                        Nudge WA
                                    </a>
                                @endif

                                {{-- Compress Media --}}
                                <form action="{{ route('admin.creator-resources.compress-all', $user->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn-compress-sm" title="Kompresi Maksimal Berkas Media Creator Ini" onclick="return confirm('Kompresi semua gambar milik {{ addslashes($user->name) }}?')">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 14h6v6M20 10h-6V4M14 10l7-7M4 20l7-7"/></svg>
                                        Compress
                                    </button>
                                </form>

                                {{-- Lihat Detail --}}
                                <a href="{{ route('admin.creator-resources.show', $user->id) }}" class="btn-detail">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center; padding:2.5rem; color:#64748B;">
                            Tidak ada data creator yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- VIEW MODE 2: Grid Cards View --}}
    <div id="viewContainerGrid" class="res-creators-grid" style="display:none;">
        @forelse($creatorResources as $item)
            @php
                $user = $item['user'];
                $profile = $item['creator_profile'];
                $mb = $item['total_size_mb'];
                $rev = $item['total_revenue'];
                $cost = $item['est_monthly_cost'];
                $abandoned = $item['abandoned_cart_value'];
            @endphp
            <div class="creator-card-box">
                <div>
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
                        <div class="res-user-info">
                            <div class="res-avatar-wrap">
                                @if($item['avatar_url'])
                                    <img src="{{ $item['avatar_url'] }}" alt="{{ $user->name }}" class="res-avatar">
                                @else
                                    <div class="res-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                @endif
                                @if($item['is_online_now'])
                                    <div class="res-online-pulse" title="Online Sekarang"></div>
                                @endif
                            </div>
                            <div>
                                <div class="res-user-name">{{ $user->name }}</div>
                                <div class="res-user-sub">{{ $profile ? ($profile->store_name ?: '@' . $user->username) : '@' . ($user->username ?: 'user') }}</div>
                            </div>
                        </div>
                        <span class="{{ $item['online_badge_class'] }}">
                            {{ $item['online_status_label'] }}
                        </span>
                    </div>

                    <div style="background:#F8FAFC; border-radius:14px; padding:0.85rem; margin-bottom:1rem; display:grid; grid-template-columns: 1fr 1fr; gap:0.6rem; font-size:0.78rem;">
                        <div>
                            <div style="color:#64748B; font-size:0.7rem; font-weight:700;">TOTAL OMSET</div>
                            <div style="font-weight:800; color:#16A34A; font-size:0.9rem;">Rp {{ number_format($rev, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div style="color:#64748B; font-size:0.7rem; font-weight:700;">CART PENDING</div>
                            @if($item['abandoned_cart_count'] > 0)
                                <div style="font-weight:800; color:#D97706; font-size:0.9rem; cursor:pointer;" onclick="openCartModal({{ json_encode($item['abandoned_cart_items']) }}, '{{ addslashes($user->name) }}')">
                                    Rp {{ number_format($abandoned, 0, ',', '.') }} &rsaquo;
                                </div>
                            @else
                                <div style="font-weight:700; color:#94A3B8; font-size:0.85rem;">Rp 0</div>
                            @endif
                        </div>
                        <div>
                            <div style="color:#64748B; font-size:0.7rem; font-weight:700;">STORAGE DISK</div>
                            <div style="font-weight:800; color:#0F172A;">{{ $mb }} MB</div>
                        </div>
                        <div>
                            <div style="color:#64748B; font-size:0.7rem; font-weight:700;">BIAYA SERVER</div>
                            <div style="font-weight:700; color:#475569;">Rp {{ number_format($cost, 0, ',', '.') }}/bln</div>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; pt-2; border-top:1px solid #F1F5F9;">
                    <div style="font-size:0.72rem; color:#64748B; font-weight:600;">
                        {{ $item['product_count'] }} produk &bull; {{ $item['bio_blocks_count'] }} block
                    </div>
                    <div style="display:flex; gap:0.3rem;">
                        @if($item['wa_link'])
                            <a href="{{ $item['wa_link'] }}" target="_blank" class="btn-wa-nudge" title="Nudge WA">WA</a>
                        @endif
                        <a href="{{ route('admin.creator-resources.show', $user->id) }}" class="btn-detail">Detail</a>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align:center; padding:3rem; color:#64748B; background:#ffffff; border-radius:20px;">
                Tidak ada data creator yang ditemukan.
            </div>
        @endforelse
    </div>
</div>

{{-- MODAL 1: Ghost Files Details & Risk Explanation Modal --}}
<div id="ghostModal" class="res-modal-overlay">
    <div class="res-modal-card">
        <div class="res-modal-header">
            <div>
                <h3 style="font-size:1.05rem; font-weight:800; color:#0F172A; margin:0; display:flex; align-items:center; gap:0.4rem;">
                    <svg width="20" height="20" fill="none" stroke="#DC2626" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    Pembersih Berkas Sampah Terbuang (Ghost Files)
                </h3>
                <p style="font-size:0.75rem; color:#64748B; margin:0.15rem 0 0 0;">Preview seluruh berkas fisik di server storage yang tidak terdaftar di database.</p>
            </div>
            <button onclick="closeGhostModal()" style="border:none; background:none; font-size:1.25rem; cursor:pointer; color:#64748B;">&times;</button>
        </div>
        <div class="res-modal-body">
            {{-- Explanation Box for Impact --}}
            <div style="background:#EFF6FF; border:1px solid #BFDBFE; padding:0.9rem 1.1rem; border-radius:14px; margin-bottom:1rem; font-size:0.78rem; color:#1E40AF; line-height:1.5;">
                <strong>💡 Apakah Aman Dihapus? (Dampak Penghapusan)</strong><br>
                Sistem telah memindai seluruh referensi di database produk, bio block, banner, dan avatar user. Seluruh berkas yang terdaftar di bawah ini <strong>tidak lagi digunakan/terhubung ke toko manapun</strong>. Menghapusnya <strong>100% AMAN</strong> dan akan langsung membebaskan <strong>{{ $orphanData['total_mb'] }} MB</strong> kapasitas ruang disk server Hostinger/VPS.
            </div>

            <div style="background:#FEF2F2; border:1px solid #FEE2E2; padding:0.85rem 1rem; border-radius:12px; margin-bottom:1rem; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="font-size:0.85rem; font-weight:700; color:#991B1B;">Ditemukan {{ $orphanData['count'] }} Ghost Files</div>
                    <div style="font-size:0.75rem; color:#7F1D1D;">Ukuran total sampah terbuang: <strong>{{ $orphanData['total_mb'] }} MB</strong></div>
                </div>
                @if($orphanData['count'] > 0)
                    <form action="{{ route('admin.creator-resources.clean-orphans') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="res-btn" style="background:#DC2626; color:#ffffff; font-size:0.75rem; padding:0.55rem 1.1rem;" onclick="return confirm('Hapus {{ $orphanData['count'] }} berkas sampah terbuang ({{ $orphanData['total_mb'] }} MB) secara permanen?')">
                            Hapus Semua Sampah
                        </button>
                    </form>
                @endif
            </div>

            @if($orphanData['count'] > 0)
                <div style="max-height:340px; overflow-y:auto; border:1px solid #E2E8F0; border-radius:12px;">
                    <table style="width:100%; border-collapse:collapse; font-size:0.78rem;">
                        <thead style="background:#F8FAFC; border-bottom:1px solid #E2E8F0; position:sticky; top:0;">
                            <tr>
                                <th style="padding:0.6rem 0.85rem; text-align:left; color:#475569;">Preview</th>
                                <th style="padding:0.6rem 0.85rem; text-align:left; color:#475569;">Nama & Lokasi Storage</th>
                                <th style="padding:0.6rem 0.85rem; text-align:center; color:#475569;">Ekstensi</th>
                                <th style="padding:0.6rem 0.85rem; text-align:right; color:#475569;">Ukuran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(array_slice($orphanData['files'], 0, 50) as $f)
                                <tr style="border-bottom:1px solid #F1F5F9;">
                                    <td style="padding:0.5rem 0.85rem; width:50px;">
                                        @if($f['is_image'])
                                            <a href="{{ $f['full_url'] }}" target="_blank">
                                                <img src="{{ $f['full_url'] }}" alt="Ghost file" style="width:36px; height:36px; object-fit:cover; border-radius:6px; border:1px solid #E2E8F0;">
                                            </a>
                                        @else
                                            <div style="width:36px; height:36px; border-radius:6px; background:#F1F5F9; display:flex; align-items:center; justify-content:center; color:#64748B; font-weight:700; font-size:0.65rem;">
                                                {{ $f['extension'] }}
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding:0.5rem 0.85rem;">
                                        <div style="font-weight:700; color:#0F172A; word-break:break-all;">{{ $f['filename'] }}</div>
                                        <div style="font-size:0.7rem; color:#64748B; font-family:monospace; word-break:break-all;">{{ $f['directory'] }}</div>
                                    </td>
                                    <td style="padding:0.5rem 0.85rem; text-align:center;">
                                        <span style="background:#F8FAFC; border:1px solid #E2E8F0; padding:0.15rem 0.4rem; border-radius:6px; font-weight:700; font-size:0.68rem; color:#475569;">
                                            {{ $f['extension'] }}
                                        </span>
                                    </td>
                                    <td style="padding:0.5rem 0.85rem; text-align:right; font-weight:700; color:#0F172A;">
                                        {{ $f['size_formatted'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($orphanData['count'] > 50)
                    <div style="font-size:0.72rem; color:#64748B; margin-top:0.5rem; text-align:center;">
                        Menampilkan 50 dari {{ $orphanData['count'] }} berkas sampah.
                    </div>
                @endif
            @else
                <div style="text-align:center; padding:2rem; color:#10B981; font-weight:700; font-size:0.9rem;">
                    ✓ Server bersih! Tidak ada berkas sampah terbuang yang terdeteksi.
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL 2: Abandoned Cart Breakdown & Email Blast Modal --}}
<div id="cartModal" class="res-modal-overlay">
    <div class="res-modal-card">
        <div class="res-modal-header">
            <div>
                <h3 style="font-size:1.05rem; font-weight:800; color:#0F172A; margin:0; display:flex; align-items:center; gap:0.4rem;">
                    <svg width="20" height="20" fill="none" stroke="#D97706" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Detail Keranjang Tertunda (Abandoned Cart) - <span id="cartModalCreatorName">Creator</span>
                </h3>
                <p style="font-size:0.75rem; color:#64748B; margin:0.15rem 0 0 0;">Daftar pembeli yang menyimpan produk di keranjang belanja tapi belum menyelesaikan pembayaran.</p>
            </div>
            <button onclick="closeCartModal()" style="border:none; background:none; font-size:1.25rem; cursor:pointer; color:#64748B;">&times;</button>
        </div>
        <div class="res-modal-body">
            <div id="cartModalListContainer">
                {{-- Dynamic via JavaScript --}}
            </div>
        </div>
    </div>
</div>

<script>
function switchViewMode(mode) {
    if (mode === 'grid') {
        document.getElementById('viewContainerTable').style.display = 'none';
        document.getElementById('viewContainerGrid').style.display = 'grid';
        document.getElementById('btnViewGrid').classList.add('active');
        document.getElementById('btnViewTable').classList.remove('active');
    } else {
        document.getElementById('viewContainerGrid').style.display = 'none';
        document.getElementById('viewContainerTable').style.display = 'block';
        document.getElementById('btnViewTable').classList.add('active');
        document.getElementById('btnViewGrid').classList.remove('active');
    }
}

function openGhostModal() {
    document.getElementById('ghostModal').style.display = 'flex';
}
function closeGhostModal() {
    document.getElementById('ghostModal').style.display = 'none';
}

function openCartModal(items, creatorName) {
    document.getElementById('cartModalCreatorName').innerText = creatorName;
    const container = document.getElementById('cartModalListContainer');
    
    if (!items || items.length === 0) {
        container.innerHTML = `<div style="text-align:center; padding:2rem; color:#64748B;">Tidak ada item keranjang belanja tertunda untuk creator ini.</div>`;
    } else {
        let html = `
            <div style="max-height:350px; overflow-y:auto; border:1px solid #E2E8F0; border-radius:12px; margin-bottom:1rem;">
                <table style="width:100%; border-collapse:collapse; font-size:0.78rem;">
                    <thead style="background:#F8FAFC; border-bottom:1px solid #E2E8F0;">
                        <tr>
                            <th style="padding:0.6rem 0.85rem; text-align:left; color:#475569;">Pembeli</th>
                            <th style="padding:0.6rem 0.85rem; text-align:left; color:#475569;">Produk Tertunda</th>
                            <th style="padding:0.6rem 0.85rem; text-align:center; color:#475569;">Qty</th>
                            <th style="padding:0.6rem 0.85rem; text-align:right; color:#475569;">Subtotal</th>
                            <th style="padding:0.6rem 0.85rem; text-align:right; color:#475569;">Aksi Reminder</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        items.forEach(item => {
            const subtotalFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(item.subtotal);
            
            let waBtn = '';
            if (item.buyer_wa_link) {
                waBtn = `<a href="${item.buyer_wa_link}" target="_blank" class="btn-wa-nudge" style="padding:0.3rem 0.6rem; font-size:0.7rem;">WA Nudge</a>`;
            }

            let emailBtn = '';
            if (item.buyer_email) {
                emailBtn = `
                    <form action="{{ route('admin.creator-resources.send-cart-email') }}" method="POST" style="display:inline; margin:0;">
                        @csrf
                        <input type="hidden" name="buyer_email" value="${item.buyer_email}">
                        <input type="hidden" name="buyer_name" value="${item.buyer_name}">
                        <input type="hidden" name="product_name" value="${item.product_name}">
                        <input type="hidden" name="creator_name" value="${creatorName}">
                        <button type="submit" class="btn-compress-sm" style="padding:0.3rem 0.6rem; font-size:0.7rem;" title="Kirim Email Recovery ke ${item.buyer_email}">
                            Blast Email
                        </button>
                    </form>
                `;
            }

            html += `
                <tr style="border-bottom:1px solid #F1F5F9;">
                    <td style="padding:0.6rem 0.85rem;">
                        <div style="font-weight:700; color:#0F172A;">${item.buyer_name}</div>
                        <div style="font-size:0.7rem; color:#64748B;">${item.buyer_email || 'Tidak ada email'} &bull; ${item.buyer_phone || '-'}</div>
                    </td>
                    <td style="padding:0.6rem 0.85rem;">
                        <div style="font-weight:700; color:#1E293B;">${item.product_name}</div>
                        <div style="font-size:0.7rem; color:#94A3B8;">Ditambahkan ${item.time_ago}</div>
                    </td>
                    <td style="padding:0.6rem 0.85rem; text-align:center; font-weight:700; color:#0F172A;">
                        ${item.qty}
                    </td>
                    <td style="padding:0.6rem 0.85rem; text-align:right; font-weight:800; color:#D97706;">
                        ${subtotalFormatted}
                    </td>
                    <td style="padding:0.6rem 0.85rem; text-align:right;">
                        <div style="display:flex; gap:0.25rem; justify-content:flex-end;">
                            ${waBtn}
                            ${emailBtn}
                        </div>
                    </td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;
        container.innerHTML = html;
    }

    document.getElementById('cartModal').style.display = 'flex';
}

function closeCartModal() {
    document.getElementById('cartModal').style.display = 'none';
}
</script>

@endsection