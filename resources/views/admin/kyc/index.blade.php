@extends('layouts.admin')

@section('page-title', 'Verifikasi e-KYC Creator')

@section('content')
<div class="kyc-container">
    <div class="kyc-header">
        <div>
            <h1 class="kyc-title">Verifikasi Identitas & Biometrik Creator</h1>
            <p class="kyc-subtitle">Tinjau kelengkapan identitas KTP dan foto biometrik creator sebelum penerbitan event atau penarikan dana.</p>
        </div>
        <div class="kyc-header-stats">
            <span class="stat-badge pending">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Menunggu: {{ $counts['pending'] }}
            </span>
            <span class="stat-badge verified">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Disetujui: {{ $counts['verified'] }}
            </span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="kyc-toolbar">
        <div class="kyc-tabs">
            <a href="{{ route('admin.kyc.index', ['status' => 'pending']) }}" class="kyc-tab {{ $status === 'pending' ? 'active' : '' }}">
                Menunggu Review <span class="tab-count">{{ $counts['pending'] }}</span>
            </a>
            <a href="{{ route('admin.kyc.index', ['status' => 'verified']) }}" class="kyc-tab {{ $status === 'verified' ? 'active' : '' }}">
                Disetujui <span class="tab-count">{{ $counts['verified'] }}</span>
            </a>
            <a href="{{ route('admin.kyc.index', ['status' => 'rejected']) }}" class="kyc-tab {{ $status === 'rejected' ? 'active' : '' }}">
                Ditolak <span class="tab-count">{{ $counts['rejected'] }}</span>
            </a>
            <a href="{{ route('admin.kyc.index', ['status' => 'all']) }}" class="kyc-tab {{ $status === 'all' ? 'active' : '' }}">
                Semua Data <span class="tab-count">{{ $counts['all'] }}</span>
            </a>
        </div>

        <form method="GET" action="{{ route('admin.kyc.index') }}" class="kyc-search-form">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="kyc-search-box">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, NIK, email...">
                @if($search)
                    <a href="{{ route('admin.kyc.index', ['status' => $status]) }}" class="clear-search" title="Hapus pencarian">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </a>
                @endif
            </div>
            <button type="submit" class="kyc-btn-search">Cari</button>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="kyc-card">
        @if($kycs->isEmpty())
            <div class="kyc-empty">
                <svg width="48" height="48" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg>
                <p class="empty-title">Tidak ada permohonan verifikasi</p>
                <p class="empty-desc">Belum ada creator dengan status verifikasi "{{ $status === 'all' ? 'semua' : $status }}" saat ini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="kyc-table">
                    <thead>
                        <tr>
                            <th>Creator</th>
                            <th>NIK & Nama KTP</th>
                            <th>Wilayah & Tgl Lahir</th>
                            <th>Tanggal Diajukan</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kycs as $item)
                        <tr>
                            <td>
                                <div class="creator-cell">
                                    <div class="creator-avatar">
                                        {{ strtoupper(substr($item->user->name ?? 'C', 0, 1)) }}
                                    </div>
                                    <div class="creator-info">
                                        <div class="creator-name">{{ $item->user->name ?? 'Pengguna' }}</div>
                                        <div class="creator-email">{{ $item->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="nik-cell">
                                    <div class="nik-number">{{ $item->nik ?? '-' }}</div>
                                    <div class="nik-name">{{ $item->full_name ?? '-' }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="meta-cell">
                                    <div class="meta-primary">{{ $item->province ?? 'Wilayah belum terdeteksi' }}</div>
                                    <div class="meta-secondary">
                                        {{ $item->dob ? $item->dob->format('d/m/Y') : '-' }} 
                                        ({{ $item->gender === 'L' ? 'Laki-laki' : ($item->gender === 'P' ? 'Perempuan' : '-') }})
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="meta-cell">
                                    <div class="meta-primary">{{ $item->submitted_at ? $item->submitted_at->format('d M Y') : '-' }}</div>
                                    <div class="meta-secondary">{{ $item->submitted_at ? $item->submitted_at->format('H:i') . ' WIB' : '-' }}</div>
                                </div>
                            </td>
                            <td>
                                @if($item->status === 'verified')
                                    <span class="status-pill status-verified">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        Terverifikasi
                                    </span>
                                @elseif($item->status === 'pending')
                                    <span class="status-pill status-pending">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        Menunggu Review
                                    </span>
                                @elseif($item->status === 'rejected')
                                    <span class="status-pill status-rejected">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        Ditolak
                                    </span>
                                @else
                                    <span class="status-pill status-none">Belum Diajukan</span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.kyc.show', $item) }}" class="btn-review">
                                    <span>Tinjau</span>
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($kycs->hasPages())
                <div class="kyc-pagination">
                    {{ $kycs->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<style>
.kyc-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.5rem 1rem;
    font-family: inherit;
    color: #1E293B;
}

.kyc-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.kyc-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 0.35rem 0;
}

.kyc-subtitle {
    font-size: 0.875rem;
    color: #64748B;
    margin: 0;
}

.kyc-header-stats {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.stat-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 0.85rem;
    border-radius: 9999px;
    font-size: 0.8125rem;
    font-weight: 600;
}

.stat-badge.pending {
    background: #FEF3C7;
    color: #92400E;
}

.stat-badge.verified {
    background: #DCFCE7;
    color: #166534;
}

.kyc-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.25rem;
}

.kyc-tabs {
    display: flex;
    gap: 0.5rem;
    overflow-x: auto;
    padding-bottom: 4px;
}

.kyc-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.9rem;
    border-radius: 8px;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #64748B;
    text-decoration: none;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.kyc-tab:hover {
    color: #0F172A;
    border-color: #CBD5E1;
}

.kyc-tab.active {
    background: #0F172A;
    color: #FFFFFF;
    border-color: #0F172A;
}

.tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.1rem 0.45rem;
    border-radius: 9999px;
    font-size: 0.6875rem;
    font-weight: 700;
    background: rgba(148, 163, 184, 0.2);
}

.kyc-tab.active .tab-count {
    background: rgba(255, 255, 255, 0.25);
    color: #FFFFFF;
}

.kyc-search-form {
    display: flex;
    gap: 0.5rem;
}

.kyc-search-box {
    position: relative;
    display: flex;
    align-items: center;
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 0.4rem 0.75rem;
    width: 260px;
}

.kyc-search-box svg {
    color: #94A3B8;
    margin-right: 0.5rem;
    flex-shrink: 0;
}

.kyc-search-box input {
    border: none;
    outline: none;
    width: 100%;
    font-size: 0.8125rem;
    color: #1E293B;
}

.clear-search {
    color: #94A3B8;
    display: flex;
    align-items: center;
    text-decoration: none;
}

.kyc-btn-search {
    background: #1eb349;
    color: #FFFFFF;
    border: none;
    border-radius: 8px;
    padding: 0 1rem;
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
}

.kyc-btn-search:hover {
    background: #179b3e;
}

.kyc-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.table-responsive {
    overflow-x: auto;
}

.kyc-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 0.8125rem;
}

.kyc-table th {
    background: #F8FAFC;
    color: #64748B;
    font-weight: 600;
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid #E2E8F0;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.05em;
}

.kyc-table td {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle;
}

.kyc-table tr:last-child td {
    border-bottom: none;
}

.creator-cell {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.creator-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #E0E7FF;
    color: #4338CA;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.creator-name {
    font-weight: 600;
    color: #0F172A;
}

.creator-email {
    font-size: 0.75rem;
    color: #64748B;
}

.nik-number {
    font-family: monospace;
    font-weight: 600;
    color: #0F172A;
    font-size: 0.875rem;
}

.nik-name {
    font-size: 0.75rem;
    color: #64748B;
}

.meta-primary {
    font-weight: 500;
    color: #1E293B;
}

.meta-secondary {
    font-size: 0.75rem;
    color: #64748B;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.65rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-verified {
    background: #DCFCE7;
    color: #166534;
}

.status-pending {
    background: #FEF3C7;
    color: #92400E;
}

.status-rejected {
    background: #FEE2E2;
    color: #991B1B;
}

.status-none {
    background: #F1F5F9;
    color: #64748B;
}

.btn-review {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.45rem 0.85rem;
    background: #F8FAFC;
    color: #0F172A;
    border: 1px solid #CBD5E1;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.75rem;
    transition: all 0.2s ease;
}

.btn-review:hover {
    background: #0F172A;
    color: #FFFFFF;
    border-color: #0F172A;
}

.kyc-empty {
    padding: 3.5rem 1.5rem;
    text-align: center;
}

.empty-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1E293B;
    margin: 1rem 0 0.25rem 0;
}

.empty-desc {
    font-size: 0.8125rem;
    color: #64748B;
    margin: 0;
}

.kyc-pagination {
    padding: 1rem 1.25rem;
    border-top: 1px solid #E2E8F0;
}

@media (max-width: 768px) {
    .kyc-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    .kyc-search-box {
        width: 100%;
    }
}
</style>
@endsection
