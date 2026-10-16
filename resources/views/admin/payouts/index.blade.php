@extends('layouts.admin')
@section('title', 'Keuangan & Pencairan')
@section('page-title', 'Keuangan & Pencairan')
@section('content')

<style>
.pw {
    font-family: 'Montserrat', sans-serif;
}

/* ── Stat Cards ── */
.pw-cards {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.875rem;
    margin-bottom: 1.5rem;
}
@media (max-width: 1200px) {
    .pw-cards { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 768px) {
    .pw-cards { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .pw-cards { grid-template-columns: 1fr; }
}

.pw-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #E2E8F0;
    padding: 1.1rem 1.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.pw-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.4rem;
    flex-shrink: 0;
}
.pw-card-label {
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    line-height: 1.3;
}
.pw-card-value {
    font-size: 1.1rem;
    font-weight: 800;
    color: #0F172A;
    letter-spacing: -0.02em;
    line-height: 1.2;
}
.pw-card-sub {
    font-size: 0.7rem;
    color: #94A3B8;
    font-weight: 500;
    margin-top: 0.1rem;
}

/* ── Table Section ── */
.pw-section {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    overflow: hidden;
}
.pw-section-head {
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid #F1F5F9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}
.pw-section-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0F172A;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* ── Filter Tabs ── */
.pw-tabs {
    display: flex;
    gap: 0.35rem;
    background: #F8FAFC;
    border-radius: 10px;
    padding: 3px;
}
.pw-tab {
    padding: 0.4rem 0.875rem;
    border-radius: 7px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748B;
    text-decoration: none;
    transition: all 0.15s;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}
.pw-tab:hover {
    background: #fff;
    color: #1eb349;
}
.pw-tab.active {
    background: #fff;
    color: #1eb349;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}
.pw-tab-badge {
    background: #ef4444;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 800;
    border-radius: 99px;
    padding: 0.1rem 0.4rem;
    min-width: 18px;
    text-align: center;
}

/* ── Table ── */
.pw-table-wrap {
    overflow-x: auto;
}
.pw-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.82rem;
}
.pw-table th {
    padding: 0.75rem 1rem;
    text-align: left;
    font-weight: 700;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #64748B;
    background: #F8FAFC;
    border-bottom: 1px solid #F1F5F9;
    white-space: nowrap;
}
.pw-table td {
    padding: 1rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #F8FAFC;
    color: #1E293B;
}
.pw-table tr:last-child td {
    border-bottom: none;
}
.pw-table tr:hover td {
    background: #FAFBFC;
}

/* ── Creator Cell ── */
.pw-creator {
    display: flex;
    align-items: center;
    gap: 0.65rem;
}
.pw-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    font-size: 0.8rem;
    flex-shrink: 0;
    overflow: hidden;
}
.pw-creator-name {
    font-weight: 700;
    color: #0F172A;
    font-size: 0.82rem;
    line-height: 1.3;
}
.pw-creator-sub {
    font-size: 0.7rem;
    color: #94A3B8;
    font-weight: 500;
}

/* ── Bank Cell ── */
.pw-bank {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}
.pw-bank-name {
    font-weight: 700;
    color: #334155;
    font-size: 0.82rem;
}
.pw-bank-num {
    font-size: 0.78rem;
    color: #64748B;
    font-weight: 600;
    letter-spacing: 0.04em;
    font-family: 'Courier New', monospace;
}
.pw-bank-holder {
    font-size: 0.7rem;
    color: #94A3B8;
}
.btn-copy {
    border: none;
    background: #F1F5F9;
    color: #475569;
    border-radius: 6px;
    padding: 0.25rem 0.5rem;
    font-size: 0.65rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.15s;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    margin-top: 0.25rem;
    font-family: 'Montserrat', sans-serif;
}
.btn-copy:hover {
    background: #E2E8F0;
    color: #1eb349;
}
.btn-copy.copied {
    background: #dcfce7;
    color: #166534;
}

/* ── Amount Cell ── */
.pw-amount-gross {
    font-size: 0.82rem;
    color: #475569;
    font-weight: 600;
}
.pw-amount-fee {
    font-size: 0.72rem;
    color: #ef4444;
    font-weight: 600;
}
.pw-amount-net {
    font-size: 1rem;
    font-weight: 800;
    color: #1eb349;
    letter-spacing: -0.01em;
}

/* ── Status Badge ── */
.pw-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.3rem 0.65rem;
    border-radius: 99px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
}
.pw-badge.pending  { background: #fef9c3; color: #854d0e; }
.pw-badge.approved { background: #dcfce7; color: #166534; }
.pw-badge.processed { background: #d1fae5; color: #065f46; }
.pw-badge.rejected  { background: #fee2e2; color: #991b1b; }

/* ── Action Buttons ── */
.pw-actions {
    display: flex;
    gap: 0.4rem;
    align-items: center;
}
.btn-acc {
    height: 34px;
    padding: 0 0.875rem;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition: all 0.15s;
    font-family: 'Montserrat', sans-serif;
    white-space: nowrap;
}
.btn-acc:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(30,179,73,0.35);
}
.btn-reject {
    height: 34px;
    padding: 0 0.75rem;
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FCA5A5;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition: all 0.15s;
    font-family: 'Montserrat', sans-serif;
    white-space: nowrap;
}
.btn-reject:hover {
    background: #FEE2E2;
    border-color: #F87171;
}

/* ── Empty State ── */
.pw-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #94A3B8;
}
.pw-empty svg {
    opacity: 0.25;
    margin-bottom: 0.875rem;
}
.pw-empty-title {
    font-weight: 700;
    font-size: 0.9rem;
    color: #475569;
    margin-bottom: 0.3rem;
}
.pw-empty-sub {
    font-size: 0.8rem;
}

/* ── Modal ── */
.pw-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.pw-modal-overlay.open {
    display: flex;
}
.pw-modal {
    background: #fff;
    border-radius: 18px;
    width: 100%;
    max-width: 480px;
    padding: 1.75rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    animation: modalIn 0.2s ease-out;
}
@keyframes modalIn {
    from { opacity: 0; transform: translateY(12px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.pw-modal-title {
    font-size: 1rem;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.pw-modal-sub {
    font-size: 0.78rem;
    color: #64748B;
    margin-bottom: 1.25rem;
    line-height: 1.5;
}
.pw-modal-group {
    margin-bottom: 1rem;
}
.pw-modal-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #334155;
    display: block;
    margin-bottom: 0.4rem;
}
.pw-modal-input {
    width: 100%;
    height: 42px;
    padding: 0 0.875rem;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.82rem;
    color: #0F172A;
    background: #F8FAFC;
    outline: none;
    transition: border-color 0.15s;
    box-sizing: border-box;
}
.pw-modal-input:focus {
    border-color: #1eb349;
    background: #fff;
}
.pw-modal-textarea {
    width: 100%;
    padding: 0.75rem 0.875rem;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.82rem;
    color: #0F172A;
    background: #F8FAFC;
    outline: none;
    resize: vertical;
    min-height: 90px;
    transition: border-color 0.15s;
    box-sizing: border-box;
}
.pw-modal-textarea:focus {
    border-color: #DC2626;
    background: #fff;
}
.pw-modal-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1.25rem;
}
.btn-modal-confirm {
    flex: 1;
    height: 42px;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    transition: all 0.15s;
}
.btn-modal-confirm:hover {
    box-shadow: 0 4px 14px rgba(30,179,73,0.35);
}
.btn-modal-confirm.danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}
.btn-modal-confirm.danger:hover {
    box-shadow: 0 4px 14px rgba(239,68,68,0.35);
}
.btn-modal-cancel {
    height: 42px;
    padding: 0 1rem;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 700;
    color: #64748B;
    cursor: pointer;
    font-family: 'Montserrat', sans-serif;
    transition: all 0.15s;
}
.btn-modal-cancel:hover {
    background: #F1F5F9;
}

/* ── Info block inside modal ── */
.pw-modal-info {
    background: #F8FAFC;
    border-radius: 10px;
    padding: 0.875rem 1rem;
    margin-bottom: 1rem;
    display: grid;
    gap: 0.4rem;
}
.pw-modal-info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.78rem;
}
.pw-modal-info-key {
    color: #64748B;
    font-weight: 600;
}
.pw-modal-info-val {
    font-weight: 700;
    color: #0F172A;
    text-align: right;
    max-width: 55%;
}
.pw-modal-info-val.green { color: #1eb349; }
.pw-modal-info-val.red   { color: #ef4444; }

/* ── Alert Banner ── */
.pw-alert {
    border-radius: 12px;
    padding: 0.875rem 1.1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
    font-size: 0.82rem;
    margin-bottom: 1.25rem;
}
.pw-alert.success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
}
.pw-alert.error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
}

/* ── Pagination ── */
.pw-pagination {
    padding: 1rem 1.5rem;
    border-top: 1px solid #F1F5F9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.78rem;
    color: #64748B;
    gap: 0.5rem;
    flex-wrap: wrap;
}
</style>

<div class="pw">

    {{-- Alert Flash Messages --}}
    @if(session('success'))
    <div class="pw-alert success">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="pw-alert error">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Financial Overview Cards --}}
    <div class="pw-cards">
        <div class="pw-card">
            <div class="pw-card-icon" style="background:#EFF6FF;">
                <svg width="18" height="18" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="pw-card-label">Total GMV</div>
            <div class="pw-card-value">Rp {{ number_format($totalGmv, 0, ',', '.') }}</div>
            <div class="pw-card-sub">Perputaran uang seluruh transaksi</div>
        </div>
        <div class="pw-card">
            <div class="pw-card-icon" style="background:#F0FDF4;">
                <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
            </div>
            <div class="pw-card-label">Platform Fee (5%)</div>
            <div class="pw-card-value" style="color:#1eb349">Rp {{ number_format($platformFee, 0, ',', '.') }}</div>
            <div class="pw-card-sub">Laba dari layanan transaksi</div>
        </div>
        <div class="pw-card">
            <div class="pw-card-icon" style="background:#FFF7ED;">
                <svg width="18" height="18" fill="none" stroke="#F97316" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="16" cy="12" r="2"/><path d="M6 12h.01"/></svg>
            </div>
            <div class="pw-card-label">Biaya Pencairan</div>
            <div class="pw-card-value" style="color:#16A34A">Rp 0 (Gratis)</div>
            <div class="pw-card-sub">100% dana utuh untuk creator</div>
        </div>
        <div class="pw-card" style="border-color:#bbf7d0; background: linear-gradient(135deg,#f0fdf4,#dcfce7);">
            <div class="pw-card-icon" style="background:#fff;">
                <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
            </div>
            <div class="pw-card-label">Laba Bersih Buyle.id</div>
            <div class="pw-card-value" style="color:#15803d">Rp {{ number_format($platformFee, 0, ',', '.') }}</div>
            <div class="pw-card-sub">Platform Fee 5% transaksi</div>
        </div>
        <div class="pw-card" style="border-color:#fed7aa;">
            <div class="pw-card-icon" style="background:#FFF7ED;">
                <svg width="18" height="18" fill="none" stroke="#F97316" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="pw-card-label">Kewajiban Saldo Creator</div>
            <div class="pw-card-value" style="color:#EA580C">Rp {{ number_format($creatorLiability, 0, ',', '.') }}</div>
            <div class="pw-card-sub">Dana titipan belum dicairkan</div>
        </div>
    </div>

    {{-- Payout Requests Table --}}
    <div class="pw-section">
        <div class="pw-section-head">
            <div class="pw-section-title">
                <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="16" cy="12" r="2"/><path d="M6 12h.01"/></svg>
                Permintaan Pencairan Dana
            </div>
            <nav class="pw-tabs">
                <a href="?status=all" class="pw-tab {{ $status === 'all' ? 'active' : '' }}">Semua</a>
                <a href="?status=pending" class="pw-tab {{ $status === 'pending' ? 'active' : '' }}">
                    Menunggu
                    @if($pendingCount > 0)<span class="pw-tab-badge">{{ $pendingCount }}</span>@endif
                </a>
                <a href="?status=processed" class="pw-tab {{ $status === 'processed' ? 'active' : '' }}">Selesai</a>
                <a href="?status=rejected" class="pw-tab {{ $status === 'rejected' ? 'active' : '' }}">Ditolak</a>
            </nav>
        </div>

        @if($requests->count() > 0)
        <div class="pw-table-wrap">
            <table class="pw-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Creator</th>
                        <th>Rekening Tujuan</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $req)
                    <tr>
                        <td style="white-space:nowrap; color:#64748B; font-size:0.78rem;">
                            {{ $req->created_at->format('d M Y') }}<br>
                            <span style="color:#94A3B8">{{ $req->created_at->format('H:i') }}</span>
                        </td>
                        <td>
                            <div class="pw-creator">
                                <div class="pw-avatar">
                                    @if($req->seller?->avatar)
                                        <img src="{{ asset('storage/'.$req->seller->avatar) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                                    @else
                                        {{ strtoupper(substr($req->seller?->name ?? 'U', 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="pw-creator-name">{{ $req->seller?->name ?? 'Unknown' }}</div>
                                    <div class="pw-creator-sub">{{ $req->seller?->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="pw-bank">
                                <div class="pw-bank-name">{{ $req->bank_name }}</div>
                                <div class="pw-bank-num">{{ $req->bank_account_number }}</div>
                                <div class="pw-bank-holder">a/n {{ $req->bank_account_name }}</div>
                                @if($req->status === 'pending')
                                <button class="btn-copy" onclick="copyAccountNum(this, '{{ $req->bank_account_number }}')">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Salin Nomor
                                </button>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="pw-amount-net">Rp {{ number_format($req->amount, 0, ',', '.') }}</div>
                            <div style="font-size:0.68rem;color:#16a34a;font-weight:700;">100% Tanpa Potongan</div>
                            @if($req->reference_number)
                            <div style="font-size:0.65rem;color:#94A3B8;margin-top:0.2rem;">Ref: {{ $req->reference_number }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="pw-badge {{ $req->status }}">
                                @if($req->status === 'pending')
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    Menunggu
                                @elseif($req->status === 'processed' || $req->status === 'approved')
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    Selesai
                                @elseif($req->status === 'rejected')
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    Ditolak
                                @endif
                            </span>
                            @if($req->admin_notes)
                            <div style="font-size:0.67rem;color:#94A3B8;margin-top:0.3rem;max-width:150px;line-height:1.3;">{{ $req->admin_notes }}</div>
                            @endif
                        </td>
                        <td style="text-align:right">
                            @if($req->status === 'pending')
                            <div class="pw-actions" style="justify-content:flex-end">
                                <button class="btn-acc" onclick="openAccModal({{ $req->id }}, 'Rp {{ number_format($req->amount, 0, ',', '.') }}', '{{ $req->bank_name }} - {{ $req->bank_account_number }}', '{{ $req->bank_account_name }}')">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    ACC
                                </button>
                                <button class="btn-reject" onclick="openRejectModal({{ $req->id }}, 'Rp {{ number_format($req->amount, 0, ',', '.') }}')">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    Tolak
                                </button>
                            </div>
                            @else
                            <span style="font-size:0.72rem;color:#94A3B8;">{{ $req->processed_at?->format('d M Y') ?? '' }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pw-pagination">
            <span>{{ $requests->firstItem() }}-{{ $requests->lastItem() }} dari {{ $requests->total() }} pengajuan</span>
            <div>{{ $requests->links() }}</div>
        </div>
        @else
        <div class="pw-empty">
            <svg width="52" height="52" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="16" cy="12" r="2"/><path d="M6 12h.01"/></svg>
            <div class="pw-empty-title">Belum ada permintaan pencairan</div>
            <div class="pw-empty-sub">Permintaan dari creator akan muncul di sini</div>
        </div>
        @endif
    </div>

</div>

{{-- Modal ACC / Konfirmasi Transfer --}}
<div class="pw-modal-overlay" id="modalAcc">
    <div class="pw-modal">
        <div class="pw-modal-title">
            <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Konfirmasi Pencairan Dana
        </div>
        <div class="pw-modal-sub">Pastikan kamu sudah transfer ke rekening di bawah sebelum klik Selesai.</div>
        <div class="pw-modal-info" id="accInfo"></div>
        <form id="formAcc" method="POST">
            @csrf
            <div class="pw-modal-group">
                <label class="pw-modal-label">No. Referensi Transfer (Opsional)</label>
                <input type="text" name="reference_number" class="pw-modal-input" placeholder="Contoh: 20241016123456">
            </div>
            <div class="pw-modal-group">
                <label class="pw-modal-label">Catatan Admin (Opsional)</label>
                <input type="text" name="admin_notes" class="pw-modal-input" placeholder="Misal: Transfer berhasil via BCA Mobile">
            </div>
            <div class="pw-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalAcc')">Batal</button>
                <button type="submit" class="btn-modal-confirm">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Tandai Selesai
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tolak / Reject --}}
<div class="pw-modal-overlay" id="modalReject">
    <div class="pw-modal">
        <div class="pw-modal-title" style="color:#DC2626">
            <svg width="20" height="20" fill="none" stroke="#DC2626" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Tolak Permintaan Pencairan
        </div>
        <div class="pw-modal-sub">Saldo creator akan <strong>otomatis dikembalikan</strong>. Isi alasan penolakan agar creator memahami situasinya.</div>
        <div class="pw-modal-info" id="rejectInfo"></div>
        <form id="formReject" method="POST">
            @csrf
            <div class="pw-modal-group">
                <label class="pw-modal-label">Alasan Penolakan <span style="color:#ef4444">*</span></label>
                <textarea name="admin_notes" class="pw-modal-textarea" placeholder="Contoh: Nomor rekening tidak aktif atau tidak terdaftar di BCA." required></textarea>
            </div>
            <div class="pw-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modalReject')">Batal</button>
                <button type="submit" class="btn-modal-confirm danger">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    Tolak & Kembalikan Saldo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAccModal(id, netAmount, bank, holder) {
    document.getElementById('formAcc').action = '/admin/payouts/' + id + '/approve';
    document.getElementById('accInfo').innerHTML = `
        <div class="pw-modal-info-row">
            <span class="pw-modal-info-key">Rekening Tujuan</span>
            <span class="pw-modal-info-val">${bank}</span>
        </div>
        <div class="pw-modal-info-row">
            <span class="pw-modal-info-key">Atas Nama</span>
            <span class="pw-modal-info-val">${holder}</span>
        </div>
        <div class="pw-modal-info-row" style="border-top:1px dashed #E2E8F0; padding-top:0.5rem; margin-top:0.3rem;">
            <span class="pw-modal-info-key" style="font-weight:800;color:#0F172A;">Total Transfer (100% Utuh)</span>
            <span class="pw-modal-info-val green" style="font-size:1.05rem;">${netAmount}</span>
        </div>
    `;
    document.getElementById('modalAcc').classList.add('open');
}

function openRejectModal(id, amount) {
    document.getElementById('formReject').action = '/admin/payouts/' + id + '/reject';
    document.getElementById('rejectInfo').innerHTML = `
        <div class="pw-modal-info-row">
            <span class="pw-modal-info-key">Jumlah Dikembalikan</span>
            <span class="pw-modal-info-val green">${amount}</span>
        </div>
    `;
    document.getElementById('formReject').querySelector('textarea').value = '';
    document.getElementById('modalReject').classList.add('open');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

function copyAccountNum(btn, num) {
    navigator.clipboard.writeText(num).then(() => {
        btn.classList.add('copied');
        btn.innerHTML = `<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Tersalin!`;
        setTimeout(() => {
            btn.classList.remove('copied');
            btn.innerHTML = `<svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Salin Nomor`;
        }, 2500);
    });
}

// Close modal on overlay click
document.querySelectorAll('.pw-modal-overlay').forEach(el => {
    el.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
});

// Close on Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.pw-modal-overlay.open').forEach(el => el.classList.remove('open'));
    }
});
</script>

@endsection
