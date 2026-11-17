@extends('layouts.admin')

@section('title', 'Media Partner & Komisi Buyletix - Admin')

@section('content')
<style>
    .mp-container {
        padding: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
        font-family: 'Montserrat', sans-serif;
    }
    .mp-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .mp-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0F172A;
        margin: 0;
    }
    .mp-sub {
        font-size: 0.8rem;
        color: #64748B;
        margin-top: 0.25rem;
    }
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
        border: 1px solid #E2E8F0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
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
    .mp-filter-box {
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .mp-input {
        padding: 0.55rem 0.85rem;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        font-size: 0.78rem;
        font-family: inherit;
        outline: none;
        transition: all 0.2s;
        min-width: 260px;
    }
    .mp-input:focus {
        border-color: #1eb349;
        box-shadow: 0 0 0 3px rgba(30,179,73,0.1);
    }
    .mp-btn-primary {
        background: #1eb349;
        color: #ffffff;
        border: none;
        padding: 0.6rem 1.1rem;
        border-radius: 10px;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-family: inherit;
        transition: all 0.2s;
        text-decoration: none;
    }
    .mp-btn-primary:hover {
        background: #17933c;
    }
    .mp-table-card {
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
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
    .mp-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 2px 7px;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 800;
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
    .btn-icon {
        border: none;
        background: transparent;
        color: #64748B;
        cursor: pointer;
        padding: 3px;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .btn-icon:hover {
        color: #1eb349;
        background: #F0FDF4;
    }
    /* Modal */
    .mp-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .mp-modal-box {
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .mp-modal-header {
        padding: 1.15rem 1.35rem;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .mp-modal-body {
        padding: 1.35rem;
        max-height: 75vh;
        overflow-y: auto;
    }
    .mp-form-group {
        margin-bottom: 1rem;
    }
    .mp-form-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.35rem;
    }
    .mp-modal-footer {
        padding: 0.9rem 1.35rem;
        background: #F8FAFC;
        border-top: 1px solid #F1F5F9;
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }
</style>

<div class="mp-container">

    {{-- Header --}}
    <div class="mp-header">
        <div>
            <h1 class="mp-title">Media Partner & Komisi Buyletix</h1>
            <p class="mp-sub">Atur mitra promotor/media partner digital, persentase komisi fleksibel, dan tracking EO seumur hidup.</p>
        </div>
        <div style="display:flex; gap:0.5rem;">
            <button type="button" onclick="openCreateModal()" class="mp-btn-primary">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Media Partner
            </button>
        </div>
    </div>

    {{-- Feedback Alerts --}}
    @if(session('success'))
        <div style="background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; padding:0.85rem 1.25rem; border-radius:12px; margin-bottom:1.25rem; font-size:0.8rem; font-weight:700;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#FEF2F2; border:1px solid #FECACA; color:#DC2626; padding:0.85rem 1.25rem; border-radius:12px; margin-bottom:1.25rem; font-size:0.8rem; font-weight:700;">
            ✕ {{ session('error') }}
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="mp-stats-grid">
        <div class="mp-stat-card">
            <div class="mp-stat-label">Total Media Partner</div>
            <div class="mp-stat-val">{{ number_format($totalPartnersCount) }} Mitra</div>
        </div>
        <div class="mp-stat-card">
            <div class="mp-stat-label">EO Terbawa (Lifetime)</div>
            <div class="mp-stat-val">{{ number_format($totalReferredCreators) }} Creator</div>
        </div>
        <div class="mp-stat-card">
            <div class="mp-stat-label">Total Akumulasi Komisi</div>
            <div class="mp-stat-val green">Rp {{ number_format($totalEarnedOverall, 0, ',', '.') }}</div>
        </div>
        <div class="mp-stat-card">
            <div class="mp-stat-label">Total Komisi Dicairkan</div>
            <div class="mp-stat-val">Rp {{ number_format($totalPaidOverall, 0, ',', '.') }}</div>
        </div>
        <div class="mp-stat-card">
            <div class="mp-stat-label">Saldo Pending Payout</div>
            <div class="mp-stat-val amber">Rp {{ number_format($totalBalanceOverall, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="mp-filter-box">
        <form method="GET" action="{{ route('admin.media-partners.index') }}" style="display:flex; gap:0.5rem; align-items:center;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama partner, kode, PIC, HP..." class="mp-input">
            <button type="submit" class="mp-btn-primary" style="padding:0.55rem 0.85rem;">Cari</button>
            @if($search)
                <a href="{{ route('admin.media-partners.index') }}" style="font-size:0.75rem; color:#64748B; text-decoration:none; margin-left:0.25rem;">Reset</a>
            @endif
        </form>

        <div style="font-size:0.75rem; color:#64748B; font-weight:600;">
            Menampilkan {{ $mediaPartners->count() }} dari {{ $mediaPartners->total() }} mitra terdaftar
        </div>
    </div>

    {{-- Media Partners Table --}}
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
                                <div style="font-weight:800; color:#0F172A; font-size:0.85rem;">{{ $mp->name }}</div>
                                @if($mp->notes)
                                    <div style="font-size:0.68rem; color:#94A3B8; margin-top:2px;">{{ Str::limit($mp->notes, 35) }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="mp-ref-box">
                                    <span>{{ $mp->code }}</span>
                                    <button type="button" class="btn-icon" onclick="copyRefLink('{{ $mp->referral_url }}')" title="Salin Link Pendaftaran">
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
                                    {{-- Detail --}}
                                    <a href="{{ route('admin.media-partners.show', $mp) }}" class="btn-icon" style="padding:4px 8px; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:6px; font-weight:700; font-size:0.7rem; text-decoration:none; color:#475569;" title="Lihat Detail Transaksi">
                                        Detail
                                    </a>

                                    {{-- Edit --}}
                                    <button type="button" onclick="openEditModal({{ json_encode($mp) }})" class="btn-icon" style="padding:4px 8px; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:6px; font-weight:700; font-size:0.7rem; color:#334155;" title="Edit Data & Komisi">
                                        Edit
                                    </button>

                                    {{-- Payout --}}
                                    @if($mp->balance > 0)
                                        <button type="button" onclick="openPayoutModal({{ $mp->id }}, '{{ addslashes($mp->name) }}', {{ $mp->balance }}, '{{ addslashes($mp->bank_name . ' ' . $mp->bank_account_number . ' a/n ' . $mp->bank_account_name) }}')" style="padding:4px 8px; background:#f0fdf4; border:1px solid #bbf7d0; color:#16a34a; border-radius:6px; font-weight:700; font-size:0.7rem; cursor:pointer;" title="Catat Pencairan Komisi">
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

{{-- MODAL TAMBAH MEDIA PARTNER --}}
<div class="mp-modal-overlay" id="modalCreate" style="display:none;">
    <div class="mp-modal-box">
        <div class="mp-modal-header">
            <div style="font-weight:800; font-size:0.95rem; color:#0F172A;">Daftarkan Media Partner Baru</div>
            <button type="button" onclick="closeModal('modalCreate')" style="border:none; background:transparent; font-size:1.3rem; cursor:pointer; color:#64748B;">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.media-partners.store') }}">
            @csrf
            <div class="mp-modal-body">
                <div class="mp-form-group">
                    <label class="mp-form-label">Nama Media Partner / Komunitas <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Info Konser Jawa Timur" class="mp-input" style="width:100%;">
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                    <div class="mp-form-group">
                        <label class="mp-form-label">Kode Unik / Slug Referral</label>
                        <input type="text" name="code" placeholder="infokonser (opsional)" class="mp-input" style="width:100%;">
                    </div>
                    <div class="mp-form-group">
                        <label class="mp-form-label">Persentase Komisi (%) <span style="color:#ef4444">*</span></label>
                        <input type="number" step="0.01" name="commission_rate" value="2.00" required class="mp-input" style="width:100%;">
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                    <div class="mp-form-group">
                        <label class="mp-form-label">Nama PIC / Penanggung Jawab</label>
                        <input type="text" name="pic_name" placeholder="Nama kontak PIC" class="mp-input" style="width:100%;">
                    </div>
                    <div class="mp-form-group">
                        <label class="mp-form-label">No. WhatsApp PIC</label>
                        <input type="text" name="pic_phone" placeholder="08xxxxxxxx" class="mp-input" style="width:100%;">
                    </div>
                </div>
                <div class="mp-form-group">
                    <label class="mp-form-label">Email PIC</label>
                    <input type="email" name="pic_email" placeholder="partner@domain.com" class="mp-input" style="width:100%;">
                </div>
                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:0.85rem; margin-bottom:1rem;">
                    <div style="font-size:0.7rem; font-weight:800; color:#334155; margin-bottom:0.5rem;">REKENING PENCAIRAN KOMISI (OPSIONAL)</div>
                    <div style="display:grid; grid-template-columns:1fr 1.5fr; gap:0.5rem; margin-bottom:0.5rem;">
                        <input type="text" name="bank_name" placeholder="Bank / E-Wallet (BCA, DANA)" class="mp-input" style="width:100%;">
                        <input type="text" name="bank_account_number" placeholder="Nomor Rekening" class="mp-input" style="width:100%;">
                    </div>
                    <input type="text" name="bank_account_name" placeholder="Atas Nama Pemilik Rekening" class="mp-input" style="width:100%;">
                </div>
                <div class="mp-form-group">
                    <label class="mp-form-label">Catatan Tambahan</label>
                    <textarea name="notes" rows="2" placeholder="Kesepakatan khusus atau MOU..." class="mp-input" style="width:100%; height:auto;"></textarea>
                </div>
            </div>
            <div class="mp-modal-footer">
                <button type="button" onclick="closeModal('modalCreate')" class="mp-input" style="background:#fff; cursor:pointer;">Batal</button>
                <button type="submit" class="mp-btn-primary">Daftarkan Mitra</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT MEDIA PARTNER --}}
<div class="mp-modal-overlay" id="modalEdit" style="display:none;">
    <div class="mp-modal-box">
        <div class="mp-modal-header">
            <div style="font-weight:800; font-size:0.95rem; color:#0F172A;">Edit Media Partner</div>
            <button type="button" onclick="closeModal('modalEdit')" style="border:none; background:transparent; font-size:1.3rem; cursor:pointer; color:#64748B;">&times;</button>
        </div>
        <form id="formEdit" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="mp-modal-body">
                <div class="mp-form-group">
                    <label class="mp-form-label">Nama Media Partner <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" id="editName" required class="mp-input" style="width:100%;">
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                    <div class="mp-form-group">
                        <label class="mp-form-label">Kode Unik / Slug Referral <span style="color:#ef4444">*</span></label>
                        <input type="text" name="code" id="editCode" required class="mp-input" style="width:100%;">
                    </div>
                    <div class="mp-form-group">
                        <label class="mp-form-label">Persentase Komisi (%) <span style="color:#ef4444">*</span></label>
                        <input type="number" step="0.01" name="commission_rate" id="editRate" required class="mp-input" style="width:100%;">
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                    <div class="mp-form-group">
                        <label class="mp-form-label">Nama PIC</label>
                        <input type="text" name="pic_name" id="editPicName" class="mp-input" style="width:100%;">
                    </div>
                    <div class="mp-form-group">
                        <label class="mp-form-label">No. WhatsApp PIC</label>
                        <input type="text" name="pic_phone" id="editPicPhone" class="mp-input" style="width:100%;">
                    </div>
                </div>
                <div class="mp-form-group">
                    <label class="mp-form-label">Email PIC</label>
                    <input type="email" name="pic_email" id="editPicEmail" class="mp-input" style="width:100%;">
                </div>
                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:0.85rem; margin-bottom:1rem;">
                    <div style="font-size:0.7rem; font-weight:800; color:#334155; margin-bottom:0.5rem;">REKENING PENCAIRAN KOMISI</div>
                    <div style="display:grid; grid-template-columns:1fr 1.5fr; gap:0.5rem; margin-bottom:0.5rem;">
                        <input type="text" name="bank_name" id="editBankName" placeholder="Bank / E-Wallet" class="mp-input" style="width:100%;">
                        <input type="text" name="bank_account_number" id="editBankNumber" placeholder="Nomor Rekening" class="mp-input" style="width:100%;">
                    </div>
                    <input type="text" name="bank_account_name" id="editBankHolder" placeholder="Atas Nama Pemilik Rekening" class="mp-input" style="width:100%;">
                </div>
                <div class="mp-form-group">
                    <label class="mp-form-label">Catatan</label>
                    <textarea name="notes" id="editNotes" rows="2" class="mp-input" style="width:100%; height:auto;"></textarea>
                </div>
            </div>
            <div class="mp-modal-footer">
                <button type="button" onclick="closeModal('modalEdit')" class="mp-input" style="background:#fff; cursor:pointer;">Batal</button>
                <button type="submit" class="mp-btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PENCAIRAN KOMISI (PAYOUT) --}}
<div class="mp-modal-overlay" id="modalPayout" style="display:none;">
    <div class="mp-modal-box">
        <div class="mp-modal-header">
            <div style="font-weight:800; font-size:0.95rem; color:#0F172A;">Pencairan Komisi Media Partner</div>
            <button type="button" onclick="closeModal('modalPayout')" style="border:none; background:transparent; font-size:1.3rem; cursor:pointer; color:#64748B;">&times;</button>
        </div>
        <form id="formPayout" method="POST" action="">
            @csrf
            <div class="mp-modal-body">
                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:0.85rem; margin-bottom:1rem;">
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
                    <input type="number" name="amount" id="payoutAmountInput" required min="1000" class="mp-input" style="width:100%; font-size:1rem; font-weight:800; color:#0F172A;">
                </div>

                <div class="mp-form-group">
                    <label class="mp-form-label">No. Referensi Transfer Bank (Opsional)</label>
                    <input type="text" name="reference_number" placeholder="Contoh: REF893719BCA" class="mp-input" style="width:100%;">
                </div>

                <div class="mp-form-group">
                    <label class="mp-form-label">Catatan Admin</label>
                    <textarea name="notes" rows="2" placeholder="Catatan internal..." class="mp-input" style="width:100%; height:auto;"></textarea>
                </div>
            </div>
            <div class="mp-modal-footer">
                <button type="button" onclick="closeModal('modalPayout')" class="mp-input" style="background:#fff; cursor:pointer;">Batal</button>
                <button type="submit" class="mp-btn-primary" style="background:#16A34A;">Konfirmasi Pencairan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalCreate').style.display = 'flex';
}
function openEditModal(partner) {
    document.getElementById('formEdit').action = '/admin/media-partners/' + partner.id;
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
</script>
@endsection
