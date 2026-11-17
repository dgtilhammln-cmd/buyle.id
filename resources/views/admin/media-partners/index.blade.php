@extends('layouts.admin')

@section('title', 'Media Partner & Komisi Buyletix')
@section('page-title', 'Media Partner & Komisi Buyletix')

@section('content')
<style>
    /* ── Montserrat Theme Consistency with Admin Users ── */
    .mp-page {
        font-family: 'Montserrat', sans-serif;
    }

    /* Head Row */
    .mp-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .mp-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0F172A;
        letter-spacing: -.03em;
        margin: 0 0 .2rem;
    }
    .mp-sub {
        font-size: .8rem;
        color: #94A3B8;
        margin: 0;
        font-weight: 500;
    }
    .mp-sub strong {
        color: #1eb349;
    }

    /* Primary Action Button (Identical to admin/users) */
    .mp-btn-main {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .58rem 1.1rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #1eb349, #15803D);
        color: #fff;
        font-size: .8rem;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(30,179,73,0.25);
        transition: all .2s;
        font-family: 'Montserrat', sans-serif;
    }
    .mp-btn-main:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(30,179,73,0.35);
    }

    /* Stat Cards */
    .mp-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .mp-stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.15rem;
        border: 1.5px solid #F1F5F9;
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    }
    .mp-stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.35rem;
    }
    .mp-stat-val {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0F172A;
    }
    .mp-stat-val.green {
        color: #16A34A;
    }
    .mp-stat-val.amber {
        color: #D97706;
    }

    /* Filter Card (Identical to admin/users ufilter-card) */
    .mp-filter-card {
        background: #fff;
        border-radius: 16px;
        border: 1.5px solid #F1F5F9;
        padding: .9rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    }
    .mp-filter-form {
        display: flex;
        gap: .5rem;
        align-items: center;
        flex-wrap: wrap;
        flex: 1;
    }
    .mp-search-input {
        padding: .55rem .95rem;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        font-size: .8rem;
        outline: none;
        width: 260px;
        font-family: 'Montserrat', sans-serif;
        background: #F8FAFC;
        transition: all .2s;
        color: #0F172A;
        box-sizing: border-box;
    }
    .mp-search-input:focus {
        border-color: #bbf7d0;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30,179,73,0.08);
    }
    .mp-search-btn {
        background: #0F172A;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: .55rem 1rem;
        font-size: .8rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
        transition: background .2s;
    }
    .mp-search-btn:hover {
        background: #1E293B;
    }

    /* Table & Card */
    .mp-table-card {
        background: #ffffff;
        border: 1.5px solid #F1F5F9;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    }
    .mp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.78rem;
        text-align: left;
    }
    .mp-table th {
        background: #F8FAFC;
        padding: 0.85rem 1rem;
        font-weight: 700;
        color: #475569;
        border-bottom: 1.5px solid #E2E8F0;
        text-transform: uppercase;
        font-size: 0.68rem;
        letter-spacing: 0.04em;
    }
    .mp-table td {
        padding: 0.95rem 1rem;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
        color: #1E293B;
    }

    /* Avatars */
    .mp-av {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #F1F5F9;
        flex-shrink: 0;
    }
    .mp-av-init {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #fff;
        font-weight: 800;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 2px solid #F1F5F9;
    }

    /* Badges & Pills */
    .mp-badge {
        display: inline-flex;
        align-items: center;
        padding: .2rem .6rem;
        border-radius: 50px;
        font-size: .67rem;
        font-weight: 700;
    }
    .mp-badge.active {
        background: #DCFCE7;
        color: #15803D;
    }
    .mp-badge.inactive {
        background: #FEE2E2;
        color: #DC2626;
    }
    .mp-rate-pill {
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #DBEAFE;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 99px;
        font-size: 0.72rem;
    }
    .mp-ref-box {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        padding: 2px 6px;
        font-family: monospace;
        font-size: 0.72rem;
    }

    /* Action Buttons (Consistent with admin/users u-btn) */
    .mp-action-btn {
        padding: .4rem .85rem;
        border-radius: 10px;
        font-size: .75rem;
        font-weight: 700;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        border: none;
        display: inline-block;
        font-family: 'Montserrat', sans-serif;
        transition: all .18s;
    }
    .mp-action-btn.secondary {
        background: #F8FAFC;
        color: #475569;
        border: 1px solid #E2E8F0;
    }
    .mp-action-btn.secondary:hover {
        background: #F1F5F9;
        color: #0F172A;
    }
    .mp-action-btn.success {
        background: #F0FDF4;
        color: #16A34A;
        border: 1px solid #BBF7D0;
    }
    .mp-action-btn.success:hover {
        background: #DCFCE7;
    }

    /* ── Modal (Anti Keluar Jalur / Box Sizing Safe) ── */
    .mp-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        box-sizing: border-box;
    }
    .mp-modal-box {
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 620px;
        box-shadow: 0 20px 45px rgba(0,0,0,0.2);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: 90vh;
        box-sizing: border-box;
        border: 1.5px solid #F1F5F9;
    }
    .mp-modal-header {
        padding: 1.15rem 1.4rem;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }
    .mp-modal-title {
        font-weight: 800;
        font-size: 1.05rem;
        color: #0F172A;
        letter-spacing: -.02em;
    }
    .mp-modal-close {
        border: none;
        background: transparent;
        font-size: 1.3rem;
        cursor: pointer;
        color: #64748B;
        padding: 0;
        line-height: 1;
        transition: color .2s;
    }
    .mp-modal-close:hover {
        color: #0F172A;
    }
    .mp-modal-body {
        padding: 1.4rem;
        overflow-y: auto;
        box-sizing: border-box;
    }
    .mp-modal-footer {
        padding: 0.9rem 1.4rem;
        background: #F8FAFC;
        border-top: 1px solid #F1F5F9;
        display: flex;
        justify-content: flex-end;
        gap: 0.6rem;
        flex-shrink: 0;
    }

    /* Modal Form Elements */
    .mp-form-group {
        margin-bottom: 0.95rem;
        box-sizing: border-box;
        width: 100%;
    }
    .mp-form-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.35rem;
    }
    .mp-input, .mp-select, .mp-textarea {
        width: 100% !important;
        box-sizing: border-box !important;
        min-width: 0 !important;
        max-width: 100% !important;
        padding: .55rem .95rem;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        font-size: .8rem;
        outline: none;
        font-family: 'Montserrat', sans-serif;
        background: #F8FAFC;
        transition: all .2s;
        color: #0F172A;
    }
    .mp-input:focus, .mp-select:focus, .mp-textarea:focus {
        border-color: #bbf7d0;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30,179,73,0.08);
    }
    .mp-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        box-sizing: border-box;
        width: 100%;
    }
    @media (max-width: 580px) {
        .mp-grid-2 {
            grid-template-columns: 1fr !important;
        }
    }

    /* Modal Buttons */
    .mp-btn-cancel {
        background: #F1F5F9;
        color: #475569;
        border: none;
        border-radius: 12px;
        padding: .6rem 1.2rem;
        font-weight: 700;
        font-size: .8rem;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
        transition: all .18s;
    }
    .mp-btn-cancel:hover {
        background: #E2E8F0;
        color: #0F172A;
    }
    .mp-btn-submit {
        background: linear-gradient(135deg, #1eb349, #15803D);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: .6rem 1.4rem;
        font-weight: 700;
        font-size: .8rem;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(30,179,73,0.25);
        font-family: 'Montserrat', sans-serif;
        transition: all .18s;
    }
    .mp-btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(30,179,73,0.35);
    }

    /* Searchable Creator Picker Dropdown Item */
    .creator-item-row {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.55rem 0.75rem;
        border-radius: 8px;
        cursor: pointer;
        transition: background .15s;
    }
    .creator-item-row:hover {
        background: #F0FDF4;
    }
</style>

<div class="mp-page">

    {{-- Head Section --}}
    <div class="mp-head">
        <div>
            <h1 class="mp-title">Media Partner &amp; Komisi Buyletix</h1>
            <p class="mp-sub">
                Atur promotor digital, persentase komisi fleksibel, tracking referal EO seumur hidup, dan integrasi profil creator. Ditemukan <strong>{{ $totalPartnersCount }}</strong> mitra.
            </p>
        </div>
        <div>
            <button type="button" onclick="openCreateModal()" class="mp-btn-main">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Media Partner
            </button>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#F0FDF4; color:#15803D; padding:.875rem 1.25rem; border-radius:12px; margin-bottom:1.5rem; border:1px solid #BBF7D0; font-size:.8rem; font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#FEF2F2; color:#DC2626; padding:.875rem 1.25rem; border-radius:12px; margin-bottom:1.5rem; border:1px solid #FECACA; font-size:.8rem; font-weight:600;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats Grid --}}
    <div class="mp-stats-grid">
        <div class="mp-stat-card">
            <div class="mp-stat-label">Total Media Partner</div>
            <div class="mp-stat-val">{{ $totalPartnersCount }}</div>
        </div>
        <div class="mp-stat-card">
            <div class="mp-stat-label">Total EO Terbawa</div>
            <div class="mp-stat-val">{{ $totalReferredCreators }} EO</div>
        </div>
        <div class="mp-stat-card">
            <div class="mp-stat-label">Total Komisi Terakumulasi</div>
            <div class="mp-stat-val green">Rp {{ number_format($totalEarnedOverall, 0, ',', '.') }}</div>
        </div>
        <div class="mp-stat-card">
            <div class="mp-stat-label">Saldo Belum Dicairkan</div>
            <div class="mp-stat-val amber">Rp {{ number_format($totalBalanceOverall, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="mp-filter-card">
        <form method="GET" action="{{ route('admin.media-partners.index') }}" class="mp-filter-form">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari media partner, PIC, referral..." class="mp-search-input">
            <button type="submit" class="mp-search-btn">Cari</button>
            @if($search !== '')
                <a href="{{ route('admin.media-partners.index') }}" style="font-size:0.75rem; color:#64748B; text-decoration:none; font-weight:600; padding:0.4rem 0.6rem;">Reset</a>
            @endif
        </form>
        <div style="font-size:0.75rem; color:#64748B; font-weight:600;">
            Menampilkan {{ $mediaPartners->count() }} dari {{ $mediaPartners->total() }} mitra
        </div>
    </div>

    {{-- Table Card --}}
    <div class="mp-table-card">
        <div style="overflow-x:auto;">
            <table class="mp-table">
                <thead>
                    <tr>
                        <th>Media Partner</th>
                        <th>Kode &amp; Link Referral</th>
                        <th>PIC &amp; Kontak</th>
                        <th>% Komisi</th>
                        <th>EO Terbawa</th>
                        <th>Rekening Pencairan</th>
                        <th>Total Komisi</th>
                        <th>Saldo Pending</th>
                        <th>Status</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mediaPartners as $mp)
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:0.65rem;">
                                    @if($mp->avatar_url)
                                        <img src="{{ $mp->avatar_url }}" alt="{{ $mp->name }}" class="mp-av">
                                    @else
                                        <div class="mp-av-init">{{ strtoupper(substr($mp->name, 0, 1)) }}</div>
                                    @endif
                                    <div>
                                        <div style="font-weight:800; color:#0F172A; font-size:0.85rem;">{{ $mp->name }}</div>
                                        @if($mp->user)
                                            <a href="{{ route('admin.creator-resources.index', ['search' => $mp->user->username ?: $mp->user->name]) }}" style="font-size:0.68rem; font-weight:700; color:#15803D; text-decoration:none; background:#DCFCE7; padding:1px 6px; border-radius:4px; display:inline-block; margin-top:2px;">
                                                Creator {{ '@' . ($mp->user->username ?: 'user') }}
                                            </a>
                                        @endif
                                        @if($mp->notes)
                                            <div style="font-size:0.68rem; color:#94A3B8; margin-top:2px;">{{ Str::limit($mp->notes, 30) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mp-ref-box">
                                    <span>{{ $mp->code }}</span>
                                    <button type="button" onclick="copyRefLink('{{ $mp->referral_url }}')" style="border:none; background:transparent; color:#64748B; cursor:pointer; padding:2px;" title="Salin Link Pendaftaran EO">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:600; color:#334155;">{{ $mp->pic_name ?: '-' }}</div>
                                @if($mp->pic_phone)
                                    <div style="font-size:0.68rem; color:#64748B;">{{ $mp->pic_phone }}</div>
                                @endif
                                @if($mp->pic_email)
                                    <div style="font-size:0.68rem; color:#94A3B8;">{{ $mp->pic_email }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="mp-rate-pill">{{ $mp->commission_rate }}%</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.media-partners.show', $mp) }}" style="font-weight:800; color:#1eb349; text-decoration:none;" title="Lihat Daftar EO">
                                    {{ $mp->creators_count }} EO &rsaquo;
                                </a>
                            </td>
                            <td>
                                @if($mp->bank_name && $mp->bank_account_number)
                                    <div style="font-weight:700; color:#0F172A; font-size:0.75rem;">{{ $mp->bank_name }}</div>
                                    <div style="font-family:monospace; font-size:0.72rem; color:#475569;">{{ $mp->bank_account_number }}</div>
                                    <div style="font-size:0.65rem; color:#94A3B8;">a/n {{ $mp->bank_account_name }}</div>
                                @else
                                    <span style="color:#CBD5E1; font-size:0.7rem;">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:700; color:#15803D;">Rp {{ number_format($mp->total_commission_earned, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                <div style="font-weight:800; color:#D97706; font-size:0.85rem;">
                                    Rp {{ number_format($mp->balance, 0, ',', '.') }}
                                </div>
                            </td>
                            <td>
                                <span class="mp-badge {{ $mp->is_active ? 'active' : 'inactive' }}">
                                    {{ $mp->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex; gap:0.35rem; align-items:center;">
                                    <a href="{{ route('admin.media-partners.show', $mp) }}" class="mp-action-btn secondary" title="Lihat Detail Transaksi">
                                        Detail
                                    </a>
                                    <button type="button" onclick="openEditModal({{ json_encode($mp) }})" class="mp-action-btn secondary" title="Edit Data &amp; Komisi">
                                        Edit
                                    </button>
                                    @if($mp->balance > 0)
                                        <button type="button" onclick="openPayoutModal({{ $mp->id }}, '{{ addslashes($mp->name) }}', {{ $mp->balance }}, '{{ addslashes($mp->bank_name . ' ' . $mp->bank_account_number . ' a/n ' . $mp->bank_account_name) }}')" class="mp-action-btn success" title="Catat Pencairan Komisi">
                                            Cairkan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center; padding:3rem; color:#94A3B8;">
                                Belum ada Media Partner terdaftar. Klik tombol <strong>Tambah Media Partner</strong> di kanan atas untuk mendaftarkan mitra pertama.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mediaPartners->hasPages())
            <div style="padding:1rem; border-top:1px solid #F1F5F9; display:flex; justify-content:flex-end;">
                {{ $mediaPartners->links() }}
            </div>
        @endif
    </div>

</div>

{{-- MODAL TAMBAH MEDIA PARTNER (DENGAN SEARCHABLE CREATOR PICKER) --}}
<div class="mp-modal-overlay" id="modalCreate" style="display:none;">
    <div class="mp-modal-box">
        <div class="mp-modal-header">
            <div class="mp-modal-title">Daftarkan Media Partner Baru</div>
            <button type="button" onclick="closeModal('modalCreate')" class="mp-modal-close">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.media-partners.store') }}">
            @csrf
            <div class="mp-modal-body">

                {{-- Searchable Creator Picker --}}
                <div style="background:#F0FDF4; border:1.5px solid #BBF7D0; border-radius:14px; padding:0.85rem 1rem; margin-bottom:1rem; box-sizing:border-box;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.35rem;">
                        <label style="font-size:0.75rem; font-weight:800; color:#15803D;">
                            Tarik Data dari Creator (All Creator)
                        </label>
                        <span style="font-size:0.65rem; background:#DCFCE7; color:#15803D; font-weight:700; padding:1px 6px; border-radius:4px;">
                            Auto-Sync Profil &amp; Rekening
                        </span>
                    </div>
                    <p style="font-size:0.7rem; color:#166534; margin:0 0 0.5rem 0; line-height:1.4;">
                        Ketik nama, username, atau email creator terdaftar untuk mengisi profil &amp; rekening secara instan.
                    </p>

                    <div style="position:relative; width:100%;">
                        <div style="position:relative;">
                            <input type="text" id="createCreatorSearch" placeholder="Ketik nama, @username, atau email creator..." 
                                class="mp-input" autocomplete="off" 
                                oninput="handleCreatorSearch(this.value, 'create')" 
                                onfocus="handleCreatorSearch(this.value, 'create')">
                            <svg width="15" height="15" fill="none" stroke="#64748B" stroke-width="2" viewBox="0 0 24 24" 
                                style="position:absolute; right:12px; top:50%; transform:translateY(-50%); pointer-events:none;">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </div>

                        {{-- Search Results Dropdown List --}}
                        <div id="createCreatorDropdown" style="display:none; position:absolute; left:0; right:0; top:calc(100% + 4px); max-height:220px; overflow-y:auto; background:#ffffff; border:1.5px solid #BBF7D0; border-radius:12px; box-shadow:0 12px 28px rgba(0,0,0,0.12); z-index:99999; padding:4px; box-sizing:border-box;">
                        </div>
                    </div>

                    <input type="hidden" name="user_id" id="createUserId" value="">

                    {{-- Live Preview Box saat Creator Terpilih --}}
                    <div id="createCreatorPreview" style="display:none; margin-top:0.65rem; padding:0.6rem 0.75rem; background:#ffffff; border-radius:10px; border:1px solid #BBF7D0; align-items:center; gap:0.65rem;">
                        <img id="createCreatorAvatar" src="" style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:1.5px solid #BBF7D0; display:none;">
                        <div id="createCreatorAvatarInit" style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; font-weight:800; display:flex; align-items:center; justify-content:center; font-size:0.85rem; flex-shrink:0;"></div>
                        <div style="flex:1; min-width:0;">
                            <div id="createCreatorName" style="font-size:0.78rem; font-weight:800; color:#0F172A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"></div>
                            <div id="createCreatorMeta" style="font-size:0.68rem; color:#64748B;"></div>
                        </div>
                        <button type="button" onclick="clearCreatorPull('create')" style="border:none; background:#FEE2E2; color:#DC2626; border-radius:6px; padding:0.3rem 0.6rem; font-size:0.68rem; font-weight:700; cursor:pointer;">
                            Ganti / Putus
                        </button>
                    </div>
                </div>

                <div class="mp-form-group">
                    <label class="mp-form-label">Nama Media Partner / Komunitas <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" id="createName" required placeholder="Contoh: Info Konser Jawa Timur" class="mp-input">
                </div>

                <div class="mp-grid-2">
                    <div class="mp-form-group">
                        <label class="mp-form-label">Kode Unik / Slug Referral</label>
                        <input type="text" name="code" id="createCode" placeholder="infokonser (opsional)" class="mp-input">
                    </div>
                    <div class="mp-form-group">
                        <label class="mp-form-label">Persentase Komisi (%) <span style="color:#ef4444">*</span></label>
                        <input type="number" step="0.01" name="commission_rate" value="2.00" required class="mp-input">
                    </div>
                </div>

                <div class="mp-grid-2">
                    <div class="mp-form-group">
                        <label class="mp-form-label">Nama PIC / Penanggung Jawab</label>
                        <input type="text" name="pic_name" id="createPicName" placeholder="Nama kontak PIC" class="mp-input">
                    </div>
                    <div class="mp-form-group">
                        <label class="mp-form-label">No. WhatsApp PIC</label>
                        <input type="text" name="pic_phone" id="createPicPhone" placeholder="08xxxxxxxx" class="mp-input">
                    </div>
                </div>

                <div class="mp-form-group">
                    <label class="mp-form-label">Email PIC</label>
                    <input type="email" name="pic_email" id="createPicEmail" placeholder="partner@domain.com" class="mp-input">
                </div>

                {{-- Rekening Section --}}
                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:0.85rem; margin-bottom:1rem; box-sizing:border-box;">
                    <div style="font-size:0.7rem; font-weight:800; color:#334155; margin-bottom:0.5rem; text-transform:uppercase;">
                        Rekening Pencairan Komisi (Opsional)
                    </div>
                    <div class="mp-grid-2" style="margin-bottom:0.5rem;">
                        <input type="text" name="bank_name" id="createBankName" placeholder="Bank / E-Wallet (BCA, DANA)" class="mp-input">
                        <input type="text" name="bank_account_number" id="createBankNumber" placeholder="Nomor Rekening" class="mp-input">
                    </div>
                    <input type="text" name="bank_account_name" id="createBankHolder" placeholder="Atas Nama Pemilik Rekening" class="mp-input">
                </div>

                <div class="mp-form-group" style="margin-bottom:0;">
                    <label class="mp-form-label">Catatan Tambahan</label>
                    <textarea name="notes" rows="2" placeholder="Kesepakatan khusus atau MOU..." class="mp-textarea"></textarea>
                </div>
            </div>
            <div class="mp-modal-footer">
                <button type="button" onclick="closeModal('modalCreate')" class="mp-btn-cancel">Batal</button>
                <button type="submit" class="mp-btn-submit">Daftarkan Mitra</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT MEDIA PARTNER (DENGAN SEARCHABLE CREATOR PICKER) --}}
<div class="mp-modal-overlay" id="modalEdit" style="display:none;">
    <div class="mp-modal-box">
        <div class="mp-modal-header">
            <div class="mp-modal-title">Edit Media Partner</div>
            <button type="button" onclick="closeModal('modalEdit')" class="mp-modal-close">&times;</button>
        </div>
        <form id="formEdit" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="mp-modal-body">

                {{-- Sinkronisasi Creator di Edit --}}
                <div style="background:#F0FDF4; border:1.5px solid #BBF7D0; border-radius:14px; padding:0.85rem 1rem; margin-bottom:1rem; box-sizing:border-box;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.35rem;">
                        <label style="font-size:0.75rem; font-weight:800; color:#15803D;">
                            Hubungkan / Sinkronkan Akun Creator
                        </label>
                        <span style="font-size:0.65rem; background:#DCFCE7; color:#15803D; font-weight:700; padding:1px 6px; border-radius:4px;">
                            All Creator
                        </span>
                    </div>

                    <div style="position:relative; width:100%;">
                        <div style="position:relative;">
                            <input type="text" id="editCreatorSearch" placeholder="Cari nama, @username, atau email creator..." 
                                class="mp-input" autocomplete="off" 
                                oninput="handleCreatorSearch(this.value, 'edit')" 
                                onfocus="handleCreatorSearch(this.value, 'edit')">
                            <svg width="15" height="15" fill="none" stroke="#64748B" stroke-width="2" viewBox="0 0 24 24" 
                                style="position:absolute; right:12px; top:50%; transform:translateY(-50%); pointer-events:none;">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </div>

                        {{-- Dropdown Search Edit --}}
                        <div id="editCreatorDropdown" style="display:none; position:absolute; left:0; right:0; top:calc(100% + 4px); max-height:220px; overflow-y:auto; background:#ffffff; border:1.5px solid #BBF7D0; border-radius:12px; box-shadow:0 12px 28px rgba(0,0,0,0.12); z-index:99999; padding:4px; box-sizing:border-box;">
                        </div>
                    </div>

                    <input type="hidden" name="user_id" id="editUserId" value="">

                    {{-- Live Preview Box Edit --}}
                    <div id="editCreatorPreview" style="display:none; margin-top:0.65rem; padding:0.6rem 0.75rem; background:#ffffff; border-radius:10px; border:1px solid #BBF7D0; align-items:center; gap:0.65rem;">
                        <img id="editCreatorAvatar" src="" style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:1.5px solid #BBF7D0; display:none;">
                        <div id="editCreatorAvatarInit" style="width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; font-weight:800; display:flex; align-items:center; justify-content:center; font-size:0.85rem; flex-shrink:0;"></div>
                        <div style="flex:1; min-width:0;">
                            <div id="editCreatorName" style="font-size:0.78rem; font-weight:800; color:#0F172A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"></div>
                            <div id="editCreatorMeta" style="font-size:0.68rem; color:#64748B;"></div>
                        </div>
                        <button type="button" onclick="clearCreatorPull('edit')" style="border:none; background:#FEE2E2; color:#DC2626; border-radius:6px; padding:0.3rem 0.6rem; font-size:0.68rem; font-weight:700; cursor:pointer;">
                            Lepas
                        </button>
                    </div>
                </div>

                <div class="mp-form-group">
                    <label class="mp-form-label">Nama Media Partner <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" id="editName" required class="mp-input">
                </div>

                <div class="mp-grid-2">
                    <div class="mp-form-group">
                        <label class="mp-form-label">Kode Unik / Slug Referral <span style="color:#ef4444">*</span></label>
                        <input type="text" name="code" id="editCode" required class="mp-input">
                    </div>
                    <div class="mp-form-group">
                        <label class="mp-form-label">Persentase Komisi (%) <span style="color:#ef4444">*</span></label>
                        <input type="number" step="0.01" name="commission_rate" id="editRate" required class="mp-input">
                    </div>
                </div>

                <div class="mp-grid-2">
                    <div class="mp-form-group">
                        <label class="mp-form-label">Nama PIC</label>
                        <input type="text" name="pic_name" id="editPicName" class="mp-input">
                    </div>
                    <div class="mp-form-group">
                        <label class="mp-form-label">No. WhatsApp PIC</label>
                        <input type="text" name="pic_phone" id="editPicPhone" class="mp-input">
                    </div>
                </div>

                <div class="mp-form-group">
                    <label class="mp-form-label">Email PIC</label>
                    <input type="email" name="pic_email" id="editPicEmail" class="mp-input">
                </div>

                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:0.85rem; margin-bottom:1rem; box-sizing:border-box;">
                    <div style="font-size:0.7rem; font-weight:800; color:#334155; margin-bottom:0.5rem; text-transform:uppercase;">
                        Rekening Pencairan Komisi
                    </div>
                    <div class="mp-grid-2" style="margin-bottom:0.5rem;">
                        <input type="text" name="bank_name" id="editBankName" placeholder="Bank / E-Wallet" class="mp-input">
                        <input type="text" name="bank_account_number" id="editBankNumber" placeholder="Nomor Rekening" class="mp-input">
                    </div>
                    <input type="text" name="bank_account_name" id="editBankHolder" placeholder="Atas Nama Pemilik Rekening" class="mp-input">
                </div>

                <div class="mp-form-group" style="margin-bottom:0;">
                    <label class="mp-form-label">Catatan</label>
                    <textarea name="notes" id="editNotes" rows="2" class="mp-textarea"></textarea>
                </div>
            </div>
            <div class="mp-modal-footer">
                <button type="button" onclick="closeModal('modalEdit')" class="mp-btn-cancel">Batal</button>
                <button type="submit" class="mp-btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PENCAIRAN KOMISI (PAYOUT) --}}
<div class="mp-modal-overlay" id="modalPayout" style="display:none;">
    <div class="mp-modal-box">
        <div class="mp-modal-header">
            <div class="mp-modal-title">Pencairan Komisi Media Partner</div>
            <button type="button" onclick="closeModal('modalPayout')" class="mp-modal-close">&times;</button>
        </div>
        <form id="formPayout" method="POST" action="">
            @csrf
            <div class="mp-modal-body">
                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:0.85rem; margin-bottom:1rem; box-sizing:border-box;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.35rem;">
                        <span style="font-size:0.75rem; color:#64748B;">Mitra:</span>
                        <strong id="payoutPartnerName" style="color:#0F172A;"></strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.35rem;">
                        <span style="font-size:0.75rem; color:#64748B;">Saldo Tersedia:</span>
                        <strong id="payoutMaxText" style="color:#16A34A; font-size:0.95rem;"></strong>
                    </div>
                    <div style="font-size:0.72rem; color:#475569; border-top:1px dashed #CBD5E1; padding-top:0.35rem; margin-top:0.35rem;">
                        Rekening Tujuan: <span id="payoutBankText" style="font-weight:700;"></span>
                    </div>
                </div>

                <div class="mp-form-group">
                    <label class="mp-form-label">Nominal Pencairan (Rp) <span style="color:#ef4444">*</span></label>
                    <input type="number" name="amount" id="payoutAmountInput" required min="1000" class="mp-input" style="font-size:1rem; font-weight:800; color:#0F172A;">
                </div>

                <div class="mp-form-group">
                    <label class="mp-form-label">No. Referensi Transfer Bank (Opsional)</label>
                    <input type="text" name="reference_number" placeholder="Contoh: REF893719BCA" class="mp-input">
                </div>

                <div class="mp-form-group" style="margin-bottom:0;">
                    <label class="mp-form-label">Catatan Admin</label>
                    <textarea name="notes" rows="2" placeholder="Catatan internal..." class="mp-textarea"></textarea>
                </div>
            </div>
            <div class="mp-modal-footer">
                <button type="button" onclick="closeModal('modalPayout')" class="mp-btn-cancel">Batal</button>
                <button type="submit" class="mp-btn-submit">Konfirmasi Pencairan</button>
            </div>
        </form>
    </div>
</div>

<script>
window.creatorsList = @json($creatorsList);

/**
 * Filter & render dropdown hasil pencarian creator
 */
function handleCreatorSearch(keyword, mode) {
    const dropdown = document.getElementById(mode + 'CreatorDropdown');
    const term = (keyword || '').toLowerCase().trim();

    let matches = window.creatorsList;
    if (term !== '') {
        matches = window.creatorsList.filter(c => {
            const name = (c.name || '').toLowerCase();
            const username = (c.username || '').toLowerCase();
            const email = (c.email || '').toLowerCase();
            const phone = (c.phone || '').toLowerCase();
            return name.includes(term) || username.includes(term) || email.includes(term) || phone.includes(term);
        });
    }

    if (matches.length === 0) {
        dropdown.innerHTML = '<div style="padding:0.75rem; text-align:center; color:#94A3B8; font-size:0.72rem;">Tidak ada creator yang cocok.</div>';
        dropdown.style.display = 'block';
        return;
    }

    let html = '';
    matches.slice(0, 30).forEach(c => {
        const usernameText = c.username ? '@' + c.username : 'user';
        const avHtml = c.avatar_url 
            ? `<img src="${c.avatar_url}" style="width:32px; height:32px; border-radius:50%; object-fit:cover; border:1px solid #BBF7D0; flex-shrink:0;">`
            : `<div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; font-weight:800; display:flex; align-items:center; justify-content:center; font-size:0.75rem; flex-shrink:0;">${(c.name || 'C').charAt(0).toUpperCase()}</div>`;
        
        const bankInfo = c.bank_name ? `<span style="font-size:0.62rem; background:#EFF6FF; color:#1D4ED8; padding:1px 5px; border-radius:4px; font-weight:700;">${c.bank_name}</span>` : '';

        html += `
            <div class="creator-item-row" onclick="selectCreator(${c.id}, '${mode}')">
                ${avHtml}
                <div style="flex:1; min-width:0;">
                    <div style="display:flex; align-items:center; gap:0.4rem;">
                        <span style="font-weight:700; font-size:0.78rem; color:#0F172A; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${escapeHtml(c.name)}</span>
                        <span style="font-size:0.65rem; color:#16A34A; font-weight:700;">${escapeHtml(usernameText)}</span>
                        ${bankInfo}
                    </div>
                    <div style="font-size:0.68rem; color:#64748B; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        ${escapeHtml(c.email)}${c.phone ? ' &bull; ' + escapeHtml(c.phone) : ''}
                    </div>
                </div>
            </div>
        `;
    });

    dropdown.innerHTML = html;
    dropdown.style.display = 'block';
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

/**
 * Pilih creator dari hasil pencarian
 */
function selectCreator(userId, mode) {
    const creator = window.creatorsList.find(c => String(c.id) === String(userId));
    if (!creator) return;

    // Sembunyikan dropdown
    document.getElementById(mode + 'CreatorDropdown').style.display = 'none';

    if (mode === 'create') {
        document.getElementById('createCreatorSearch').value = creator.name + (creator.username ? ' (@' + creator.username + ')' : '');
        document.getElementById('createUserId').value = creator.id;
        document.getElementById('createName').value = creator.name || '';
        document.getElementById('createCode').value = creator.username || (creator.name ? creator.name.toLowerCase().replace(/[^a-z0-9]/g, '') : '');
        document.getElementById('createPicName').value = creator.name || '';
        document.getElementById('createPicPhone').value = creator.phone || '';
        document.getElementById('createPicEmail').value = creator.email || '';
        document.getElementById('createBankName').value = creator.bank_name || '';
        document.getElementById('createBankNumber').value = creator.bank_account_number || '';
        document.getElementById('createBankHolder').value = creator.bank_account_name || creator.name || '';

        // Tampilkan Preview Box
        const preview = document.getElementById('createCreatorPreview');
        const avImg = document.getElementById('createCreatorAvatar');
        const avInit = document.getElementById('createCreatorAvatarInit');
        document.getElementById('createCreatorName').innerText = creator.name + (creator.username ? ' (@' + creator.username + ')' : '');
        document.getElementById('createCreatorMeta').innerText = creator.email + (creator.phone ? ' | ' + creator.phone : '') + (creator.bank_name ? ' | ' + creator.bank_name : '');

        if (creator.avatar_url) {
            avImg.src = creator.avatar_url;
            avImg.style.display = 'block';
            avInit.style.display = 'none';
        } else {
            avImg.style.display = 'none';
            avInit.innerText = (creator.name || 'C').charAt(0).toUpperCase();
            avInit.style.display = 'flex';
        }
        preview.style.display = 'flex';
    } else if (mode === 'edit') {
        document.getElementById('editCreatorSearch').value = creator.name + (creator.username ? ' (@' + creator.username + ')' : '');
        document.getElementById('editUserId').value = creator.id;
        if (!document.getElementById('editName').value) document.getElementById('editName').value = creator.name || '';
        if (!document.getElementById('editPicName').value) document.getElementById('editPicName').value = creator.name || '';
        if (!document.getElementById('editPicPhone').value) document.getElementById('editPicPhone').value = creator.phone || '';
        if (!document.getElementById('editPicEmail').value) document.getElementById('editPicEmail').value = creator.email || '';
        if (!document.getElementById('editBankName').value) document.getElementById('editBankName').value = creator.bank_name || '';
        if (!document.getElementById('editBankNumber').value) document.getElementById('editBankNumber').value = creator.bank_account_number || '';
        if (!document.getElementById('editBankHolder').value) document.getElementById('editBankHolder').value = creator.bank_account_name || creator.name || '';

        // Preview Box Edit
        const preview = document.getElementById('editCreatorPreview');
        const avImg = document.getElementById('editCreatorAvatar');
        const avInit = document.getElementById('editCreatorAvatarInit');
        document.getElementById('editCreatorName').innerText = creator.name + (creator.username ? ' (@' + creator.username + ')' : '');
        document.getElementById('editCreatorMeta').innerText = creator.email + (creator.phone ? ' | ' + creator.phone : '');

        if (creator.avatar_url) {
            avImg.src = creator.avatar_url;
            avImg.style.display = 'block';
            avInit.style.display = 'none';
        } else {
            avImg.style.display = 'none';
            avInit.innerText = (creator.name || 'C').charAt(0).toUpperCase();
            avInit.style.display = 'flex';
        }
        preview.style.display = 'flex';
    }
}

function clearCreatorPull(mode) {
    if (mode === 'create') {
        document.getElementById('createCreatorSearch').value = '';
        document.getElementById('createUserId').value = '';
        document.getElementById('createCreatorPreview').style.display = 'none';
        document.getElementById('createCreatorDropdown').style.display = 'none';
    } else if (mode === 'edit') {
        document.getElementById('editCreatorSearch').value = '';
        document.getElementById('editUserId').value = '';
        document.getElementById('editCreatorPreview').style.display = 'none';
        document.getElementById('editCreatorDropdown').style.display = 'none';
    }
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', function(e) {
    const createInput = document.getElementById('createCreatorSearch');
    const createDropdown = document.getElementById('createCreatorDropdown');
    if (createInput && createDropdown && !createInput.contains(e.target) && !createDropdown.contains(e.target)) {
        createDropdown.style.display = 'none';
    }

    const editInput = document.getElementById('editCreatorSearch');
    const editDropdown = document.getElementById('editCreatorDropdown');
    if (editInput && editDropdown && !editInput.contains(e.target) && !editDropdown.contains(e.target)) {
        editDropdown.style.display = 'none';
    }
});

function openCreateModal(selectedUserId = null) {
    document.getElementById('modalCreate').style.display = 'flex';
    if (selectedUserId) {
        selectCreator(selectedUserId, 'create');
    }
}

function openEditModal(partner) {
    document.getElementById('formEdit').action = '/admin/media-partners/' + partner.id;
    document.getElementById('editUserId').value = partner.user_id || '';
    document.getElementById('editName').value = partner.name || '';
    document.getElementById('editCode').value = partner.code || '';
    document.getElementById('editRate').value = partner.commission_rate || '2.00';
    document.getElementById('editPicName').value = partner.pic_name || '';
    document.getElementById('editPicPhone').value = partner.pic_phone || '';
    document.getElementById('editPicEmail').value = partner.pic_email || '';
    document.getElementById('editBankName').value = partner.bank_name || '';
    document.getElementById('editBankNumber').value = partner.bank_account_number || '';
    document.getElementById('editBankHolder').value = partner.bank_account_name || '';
    document.getElementById('editNotes').value = partner.notes || '';

    if (partner.user_id) {
        selectCreator(partner.user_id, 'edit');
    } else {
        clearCreatorPull('edit');
    }

    document.getElementById('modalEdit').style.display = 'flex';
}

function openPayoutModal(id, name, maxBalance, bankInfo) {
    document.getElementById('formPayout').action = '/admin/media-partners/' + id + '/payout';
    document.getElementById('payoutPartnerName').innerText = name;
    document.getElementById('payoutMaxText').innerText = 'Rp ' + Number(maxBalance).toLocaleString('id-ID');
    document.getElementById('payoutBankText').innerText = bankInfo || 'Belum diisi';
    const amountInput = document.getElementById('payoutAmountInput');
    amountInput.max = maxBalance;
    amountInput.value = maxBalance;
    document.getElementById('modalPayout').style.display = 'flex';
}

function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function copyRefLink(url) {
    navigator.clipboard.writeText(url);
    alert('Link referral disalin:\n' + url);
}

// Auto open jika diarahkan dari halaman All Creator dengan param sync_user_id
document.addEventListener('DOMContentLoaded', function() {
    const syncUserId = "{{ $syncUserId ?? '' }}";
    if (syncUserId) {
        openCreateModal(syncUserId);
    }
});
</script>
@endsection
