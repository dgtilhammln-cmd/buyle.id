@extends('layouts.admin')
@section('title', 'Audit Resource Creator')
@section('page-title', 'Audit Resource & Storage Creator')

@section('content')
<style>
.res-page { font-family: 'Montserrat', sans-serif; }

/* Stat Cards */
.res-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.75rem;
}

.res-stat-card {
    background: #ffffff;
    border: 1.5px solid #F1F5F9;
    border-radius: 20px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.res-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
}

.res-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.res-stat-icon.green { background: rgba(30, 179, 73, 0.12); color: #1eb349; }
.res-stat-icon.blue  { background: rgba(59, 130, 246, 0.12); color: #3b82f6; }
.res-stat-icon.purple{ background: rgba(139, 92, 246, 0.12); color: #8b5cf6; }
.res-stat-icon.amber { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }

.res-stat-val { font-size: 1.35rem; font-weight: 800; color: #0F172A; line-height: 1.2; }
.res-stat-lbl { font-size: 0.75rem; font-weight: 600; color: #64748B; margin-top: 0.15rem; }

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

.res-table tr:last-child td {
    border-bottom: none;
}

.res-table tr:hover td {
    background: #F8FAFC;
}

.res-user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.res-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 0.85rem;
    flex-shrink: 0;
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

.res-badge.normal { background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7; }
.res-badge.warning{ background: #FFFBEB; color: #D97706; border: 1px solid #FEF3C7; }
.res-badge.danger { background: #FEF2F2; color: #DC2626; border: 1px solid #FEE2E2; }

@media(max-width: 768px) {
    .res-table-card { overflow-x: auto; }
    .res-table { min-width: 650px; }
}
</style>

<div class="res-page">
    {{-- Header stats --}}
    <div class="res-stats-grid">
        <div class="res-stat-card">
            <div class="res-stat-icon green">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            </div>
            <div>
                <div class="res-stat-val">{{ $totalStorageMbOverall }} MB</div>
                <div class="res-stat-lbl">Total Disk Storage</div>
            </div>
        </div>

        <div class="res-stat-card">
            <div class="res-stat-icon blue">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <div>
                <div class="res-stat-val">{{ number_format($totalProductsOverall) }}</div>
                <div class="res-stat-lbl">Total Produk</div>
            </div>
        </div>

        <div class="res-stat-card">
            <div class="res-stat-icon purple">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
            </div>
            <div>
                <div class="res-stat-val">{{ number_format($totalBlocksOverall) }}</div>
                <div class="res-stat-lbl">Total Bio Blocks</div>
            </div>
        </div>

        <div class="res-stat-card">
            <div class="res-stat-icon amber">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div>
                <div class="res-stat-val">{{ number_format($totalAssetsOverall) }}</div>
                <div class="res-stat-lbl">Total Media Assets</div>
            </div>
        </div>
    </div>

    {{-- Filter / Search --}}
    <form method="GET" action="{{ route('admin.creator-resources.index') }}" class="res-filter-box">
        <div class="res-search-group">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, username, atau nama toko..." class="res-input">
            <button type="submit" class="res-btn">Cari</button>
        </div>

        <div style="display:flex; align-items:center; gap:0.5rem;">
            <label style="font-size:0.75rem; font-weight:700; color:#64748B;">URUTKAN:</label>
            <select name="sort_by" onchange="this.form.submit()" class="res-select">
                <option value="storage_desc"  {{ $sortBy === 'storage_desc'  ? 'selected' : '' }}>Storage Terbesar (Boros)</option>
                <option value="storage_asc"   {{ $sortBy === 'storage_asc'   ? 'selected' : '' }}>Storage Terkecil</option>
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
                    <th>Total Storage</th>
                    <th>Jumlah File</th>
                    <th>Produk</th>
                    <th>Bio Blocks</th>
                    <th>Status Penggunaan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($creatorResources as $item)
                    @php
                        $user = $item['user'];
                        $profile = $item['creator_profile'];
                        $mb = $item['total_size_mb'];
                    @endphp
                    <tr>
                        <td>
                            <div class="res-user-info">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="res-avatar">
                                @else
                                    <div class="res-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                @endif
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
                            <strong style="font-size:0.95rem; color:#0F172A;">{{ $mb }} MB</strong>
                        </td>
                        <td>
                            <span>{{ number_format($item['asset_file_count']) }} berkas</span>
                        </td>
                        <td>
                            <span>{{ number_format($item['product_count']) }} produk</span>
                        </td>
                        <td>
                            <span>{{ number_format($item['bio_blocks_count']) }} block</span>
                        </td>
                        <td>
                            @if($mb > 100)
                                <span class="res-badge danger">Kapasitas Tinggi (&gt;100MB)</span>
                            @elseif($mb > 30)
                                <span class="res-badge warning">Penggunaan Sedang (&gt;30MB)</span>
                            @else
                                <span class="res-badge normal">Penggunaan Normal</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            @if($profile && $profile->store_slug)
                                <a href="{{ url('/' . $profile->store_slug) }}" target="_blank" style="color:#1eb349; font-weight:700; text-decoration:none; font-size:0.8rem;">
                                    Lihat Bio
                                </a>
                            @else
                                <span style="color:#94A3B8; font-size:0.8rem;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:2rem; color:#64748B;">
                            Tidak ada data creator yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
