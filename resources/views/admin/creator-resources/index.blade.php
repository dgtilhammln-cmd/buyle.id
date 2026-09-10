@extends('layouts.admin')
@section('title', 'Audit Resource & Revenue Creators')
@section('page-title', 'Audit Resource, Revenue & Aktivitas Creators')

@section('content')
<style>
.res-page { font-family: 'Montserrat', sans-serif; }

/* Stat Cards Grid - 1 Baris 10 Card */
.res-stats-grid-10 {
    display: grid;
    grid-template-columns: repeat(10, minmax(130px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1.75rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
    scrollbar-width: thin;
}

.res-stat-card {
    background: #ffffff;
    border: 1.5px solid #F1F5F9;
    border-radius: 16px;
    padding: 1rem 0.85rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 100px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.res-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
}

.res-stat-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.res-stat-icon {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(30, 179, 73, 0.08);
    color: #1eb349;
    border: 1px solid rgba(30, 179, 73, 0.15);
}

.res-stat-val { font-size: 1.1rem; font-weight: 800; color: #0F172A; line-height: 1.2; }
.res-stat-lbl { font-size: 0.68rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.03em; margin-top: 0.1rem; }

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
    font-family: 'Montserrat', sans-serif;
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
    font-family: 'Montserrat', sans-serif;
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
    font-family: 'Montserrat', sans-serif;
    transition: background 0.2s;
}

.res-btn:hover { background: #1E293B; }

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

.res-online-dot {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #10B981;
    border: 2px solid #ffffff;
}

.res-user-name {
    font-weight: 700;
    color: #0F172A;
    font-size: 0.875rem;
}

.res-user-sub {
    font-size: 0.75rem;
    color: #64748B;
    margin-top: 0.1rem;
}

.res-badge {
    padding: 0.25rem 0.65rem;
    border-radius: 100px;
    font-size: 0.7rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}

.res-badge.high-roi { background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7; }
.res-badge.normal   { background: #F8FAFC; color: #475569; border: 1px solid #E2E8F0; }
.res-badge.warning  { background: #FFFBEB; color: #D97706; border: 1px solid #FEF3C7; }
.res-badge.danger   { background: #FEF2F2; color: #DC2626; border: 1px solid #FEE2E2; }

.btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.45rem 0.85rem;
    border-radius: 10px;
    background: #F8FAFC;
    color: #0F172A;
    font-weight: 700;
    font-size: 0.78rem;
    text-decoration: none;
    transition: all 0.2s;
    border: 1.5px solid #E2E8F0;
}

.btn-detail:hover {
    background: #0F172A;
    color: #ffffff;
}

.btn-compress-sm {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.45rem 0.75rem;
    border-radius: 10px;
    background: #F0FDF4;
    color: #1eb349;
    font-weight: 700;
    font-size: 0.78rem;
    border: 1px solid #DCFCE7;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-compress-sm:hover {
    background: #1eb349;
    color: #ffffff;
}

.btn-clean-orphan {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FEE2E2;
    border-radius: 8px;
    padding: 0.2rem 0.5rem;
    font-size: 0.65rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 0.25rem;
    transition: all 0.2s;
}

.btn-clean-orphan:hover {
    background: #DC2626;
    color: #ffffff;
}

@media(max-width: 1200px) {
    .res-stats-grid-10 { grid-template-columns: repeat(5, minmax(130px, 1fr)); }
}
@media(max-width: 768px) {
    .res-stats-grid-10 { grid-template-columns: repeat(2, minmax(130px, 1fr)); }
    .res-table-card { overflow-x: auto; }
    .res-table { min-width: 900px; }
}
</style>

<div class="res-page">
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

    {{-- Grid View 1 Baris 10 Card --}}
    <div class="res-stats-grid-10">
        {{-- Card 1: Total Creators --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Creators</div>
                <div class="res-stat-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
            </div>
            <div class="res-stat-val">{{ number_format($totalCreatorsCount) }}</div>
        </div>

        {{-- Card 2: Online Hari Ini --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Online</div>
                <div class="res-stat-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
            </div>
            <div class="res-stat-val">{{ number_format($onlineCreatorsCount) }}</div>
        </div>

        {{-- Card 3: Disk Storage --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Storage</div>
                <div class="res-stat-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
            </div>
            <div class="res-stat-val">{{ $totalStorageMbOverall }} MB</div>
        </div>

        {{-- Card 4: Est Server Cost --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Biaya Server</div>
                <div class="res-stat-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
            </div>
            <div class="res-stat-val">Rp {{ number_format($estServerCostOverall, 0, ',', '.') }}</div>
        </div>

        {{-- Card 5: Total Omset --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Omset</div>
                <div class="res-stat-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <div class="res-stat-val">Rp {{ number_format($totalRevenueOverall, 0, ',', '.') }}</div>
        </div>

        {{-- Card 6: Efficiency (LTV/MB) --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">LTV / MB</div>
                <div class="res-stat-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                </div>
            </div>
            <div class="res-stat-val">Rp {{ number_format($avgLtvPerMb, 0, ',', '.') }}</div>
        </div>

        {{-- Card 7: Total Produk --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Produk</div>
                <div class="res-stat-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
                </div>
            </div>
            <div class="res-stat-val">{{ number_format($totalProductsOverall) }}</div>
        </div>

        {{-- Card 8: Total Blocks --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Bio Blocks</div>
                <div class="res-stat-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                </div>
            </div>
            <div class="res-stat-val">{{ number_format($totalBlocksOverall) }}</div>
        </div>

        {{-- Card 9: Total Assets --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Assets</div>
                <div class="res-stat-icon">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
            </div>
            <div class="res-stat-val">{{ number_format($totalAssetsOverall) }}</div>
        </div>

        {{-- Card 10: Berkas Sampah (Orphan Cleaner) --}}
        <div class="res-stat-card">
            <div class="res-stat-top">
                <div class="res-stat-lbl">Ghost Files</div>
                <div class="res-stat-icon" style="background:rgba(220, 38, 38, 0.08); color:#DC2626; border-color:rgba(220, 38, 38, 0.15);">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </div>
            </div>
            <div class="res-stat-val" style="color:{{ $orphanData['count'] > 0 ? '#DC2626' : '#0F172A' }};">
                {{ $orphanData['count'] }} <span style="font-size:0.7rem; font-weight:600; color:#64748B;">({{ $orphanData['total_mb'] }} MB)</span>
            </div>
            @if($orphanData['count'] > 0)
                <form action="{{ route('admin.creator-resources.clean-orphans') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-clean-orphan" onclick="return confirm('Hapus {{ $orphanData['count'] }} berkas sampah terbuang ({{ $orphanData['total_mb'] }} MB) secara permanen?')">
                        Bersihkan Sampah
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Filter / Search --}}
    <form method="GET" action="{{ route('admin.creator-resources.index') }}" class="res-filter-box">
        <div class="res-search-group">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, username, atau nama toko creator..." class="res-input">
            <button type="submit" class="res-btn">Cari</button>
        </div>

        <div style="display:flex; align-items:center; gap:0.5rem;">
            <label style="font-size:0.75rem; font-weight:700; color:#64748B;">URUTKAN:</label>
            <select name="sort_by" onchange="this.form.submit()" class="res-select">
                <option value="storage_desc"  {{ $sortBy === 'storage_desc'  ? 'selected' : '' }}>Storage Terbesar (Boros)</option>
                <option value="storage_asc"   {{ $sortBy === 'storage_asc'   ? 'selected' : '' }}>Storage Terkecil</option>
                <option value="revenue_desc"  {{ $sortBy === 'revenue_desc'  ? 'selected' : '' }}>Omset Penjualan Terbanyak</option>
                <option value="products_desc" {{ $sortBy === 'products_desc' ? 'selected' : '' }}>Jumlah Produk Terbanyak</option>
                <option value="blocks_desc"   {{ $sortBy === 'blocks_desc'   ? 'selected' : '' }}>Jumlah Block Bio Terbanyak</option>
                <option value="assets_desc"   {{ $sortBy === 'assets_desc'   ? 'selected' : '' }}>Jumlah File Media Terbanyak</option>
            </select>
        </div>
    </form>

    {{-- Resource Table --}}
    <div class="res-table-card">
        <table class="res-table">
            <thead>
                <tr>
                    <th>Creator / Toko</th>
                    <th>Status Aktivitas</th>
                    <th>Storage Size</th>
                    <th>Est. Biaya Server</th>
                    <th>Omset (Revenue)</th>
                    <th>Produk / Block</th>
                    <th>Status ROI & Storage</th>
                    <th style="text-align: right;">Aksi</th>
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
                                        <div class="res-online-dot" title="Online Sekarang"></div>
                                    @endif
                                </div>
                                <div>
                                    <div class="res-user-name">{{ $user->name }}</div>
                                    <div class="res-user-sub">
                                        {{ $profile ? ($profile->store_name ?: '@' . $user->username) : '@' . ($user->username ?: 'user') }}
                                        &bull; {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($item['is_online_now'])
                                <span style="font-size:0.75rem; font-weight:700; color:#10B981; background:#ECFDF5; padding:0.2rem 0.55rem; border-radius:100px; border:1px solid #A7F3D0;">
                                    Online Sekarang
                                </span>
                            @else
                                <span style="font-size:0.75rem; font-weight:600; color:#64748B;">
                                    {{ $item['last_seen_text'] }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <strong style="font-size:0.95rem; color:#0F172A;">{{ $mb }} MB</strong>
                            <div style="font-size:0.72rem; color:#64748B; margin-top:0.1rem;">{{ number_format($item['asset_file_count']) }} berkas</div>
                        </td>
                        <td>
                            <span style="font-size:0.85rem; font-weight:700; color:#475569;">
                                Rp {{ number_format($cost, 0, ',', '.') }}/bln
                            </span>
                        </td>
                        <td>
                            <strong style="font-size:0.92rem; color:#0F172A;">
                                Rp {{ number_format($rev, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>
                            <span style="font-size:0.8rem; font-weight:600; color:#1E293B;">
                                {{ number_format($item['product_count']) }} produk &bull; {{ number_format($item['bio_blocks_count']) }} block
                            </span>
                        </td>
                        <td>
                            @if($rev > 0)
                                <span class="res-badge high-roi">High ROI (Omset Aktif)</span>
                            @elseif($mb > 50)
                                <span class="res-badge danger">Zero Revenue - High Storage</span>
                            @elseif($mb > 20)
                                <span class="res-badge warning">Penggunaan Sedang</span>
                            @else
                                <span class="res-badge normal">Penggunaan Normal</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display:inline-flex; gap:0.4rem; align-items:center;">
                                <form action="{{ route('admin.creator-resources.compress-all', $user->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn-compress-sm" title="Kompresi Maksimal Semua Berkas Creator Ini" onclick="return confirm('Kompresi semua gambar milik {{ addslashes($user->name) }}?')">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 14h6v6M20 10h-6V4M14 10l7-7M4 20l7-7"/></svg>
                                        Compress
                                    </button>
                                </form>
                                <a href="{{ route('admin.creator-resources.show', $user->id) }}" class="btn-detail">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Lihat Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:2.5rem; color:#64748B;">
                            Tidak ada data creator yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
