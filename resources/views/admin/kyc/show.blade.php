@extends('layouts.admin')

@section('page-title', 'Detail Verifikasi e-KYC: ' . ($kyc->user->name ?? 'Creator'))

@section('content')
<div class="kyc-show-container">
    <div class="kyc-show-topbar">
        <a href="{{ route('admin.kyc.index') }}" class="back-link">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            <span>Kembali ke Daftar Verifikasi</span>
        </a>
        <div class="status-wrap">
            @if($kyc->status === 'verified')
                <span class="status-badge verified">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Terverifikasi
                </span>
            @elseif($kyc->status === 'pending')
                <span class="status-badge pending">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Menunggu Peninjauan Admin
                </span>
            @elseif($kyc->status === 'rejected')
                <span class="status-badge rejected">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Verifikasi Ditolak
                </span>
            @endif
        </div>
    </div>

    @if($kyc->status === 'rejected' && $kyc->rejection_reason)
    <div class="alert-box alert-rejected">
        <div class="alert-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div>
            <div class="alert-title">Alasan Penolakan:</div>
            <div class="alert-message">{{ $kyc->rejection_reason }}</div>
            <div class="alert-meta">Ditolak pada {{ $kyc->reviewed_at ? $kyc->reviewed_at->format('d M Y H:i') : '-' }} WIB</div>
        </div>
    </div>
    @endif

    <div class="kyc-grid">
        <!-- Left Column: Photos Comparison -->
        <div class="photos-column">
            <div class="section-card">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        <span>Bukti Visual KTP & Biometrik Wajah</span>
                    </div>
                    <span class="badge-subtle">Private Cloud</span>
                </div>
                <div class="card-body">
                    <div class="photos-comparison-grid">
                        <!-- KTP Photo -->
                        <div class="photo-box">
                            <div class="photo-label">
                                <span class="label-badge">Foto KTP Asli</span>
                            </div>
                            <div class="photo-preview-wrap" onclick="openPhotoModal('{{ $ktpUrl }}', 'Foto KTP: {{ $kyc->full_name }}')">
                                @if($ktpUrl)
                                    <img src="{{ $ktpUrl }}" alt="Foto KTP" class="doc-photo">
                                    <div class="photo-overlay">
                                        <svg width="24" height="24" fill="none" stroke="#FFFFFF" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                        <span>Perbesar Gambar</span>
                                    </div>
                                @else
                                    <div class="no-photo">Foto KTP tidak tersedia</div>
                                @endif
                            </div>
                            <div class="photo-caption">Format WebP terenkripsi, resolusi terkalibrasi</div>
                        </div>

                        <!-- Selfie Liveness Photo -->
                        <div class="photo-box">
                            <div class="photo-label">
                                <span class="label-badge biometrics">Biometrik Wajah Liveness</span>
                            </div>
                            <div class="photo-preview-wrap" onclick="openPhotoModal('{{ $selfieUrl }}', 'Foto Biometrik: {{ $kyc->full_name }}')">
                                @if($selfieUrl)
                                    <img src="{{ $selfieUrl }}" alt="Foto Biometrik Wajah" class="doc-photo">
                                    <div class="photo-overlay">
                                        <svg width="24" height="24" fill="none" stroke="#FFFFFF" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                        <span>Perbesar Gambar</span>
                                    </div>
                                @else
                                    <div class="no-photo">Foto biometrik tidak tersedia</div>
                                @endif
                            </div>
                            <div class="photo-caption">Liveness token: <code>{{ substr($kyc->liveness_token ?? 'N/A', 0, 16) }}...</code></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Actions Card -->
            <div class="section-card actions-card">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                        <span>Keputusan Verifikasi</span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="actions-hint">
                        Pastikan keaslian foto KTP dan kesesuaian wajah pada foto biometrik liveness sebelum menyetujui. Setelah disetujui, akun creator akan mendapatkan hak penerbitan tiket dan pencairan saldo.
                    </p>
                    <div class="action-buttons-wrap">
                        <button type="button" class="btn-approve" onclick="confirmApprove()">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Setujui Verifikasi</span>
                        </button>
                        <button type="button" class="btn-reject" onclick="openRejectModal()">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            <span>Tolak Permohonan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Extracted NIK Data & User Info -->
        <div class="info-column">
            <!-- Creator Account Summary -->
            <div class="section-card">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Informasi Akun Creator</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-row">
                            <span class="info-key">Nama Akun</span>
                            <span class="info-val font-semibold">{{ $kyc->user->name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Email</span>
                            <span class="info-val">{{ $kyc->user->email ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">No. WhatsApp / HP</span>
                            <span class="info-val">{{ $kyc->user->phone ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Terdaftar Sejak</span>
                            <span class="info-val">{{ $kyc->user->created_at ? $kyc->user->created_at->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NIK Intelligence & Breakdown -->
            <div class="section-card">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="8" x2="17" y2="8"/><line x1="7" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="12" y2="16"/></svg>
                        <span>Data Hasil Validasi NIK KTP</span>
                    </div>
                    <span class="badge-subtle">Dekode Algoritmik</span>
                </div>
                <div class="card-body">
                    <div class="nik-banner">
                        <div class="nik-banner-label">Nomor Induk Kependudukan (NIK)</div>
                        <div class="nik-banner-number">{{ $kyc->nik }}</div>
                    </div>

                    @php
                        $parsed = $kyc->nik_parsed_data ?? [];
                    @endphp

                    <div class="info-list">
                        <div class="info-row">
                            <span class="info-key">Nama Lengkap (KTP)</span>
                            <span class="info-val font-semibold">{{ $kyc->full_name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Provinsi Terdaftar</span>
                            <span class="info-val">{{ $kyc->province ?? ($parsed['province'] ?? '-') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Tanggal Lahir</span>
                            <span class="info-val">{{ $kyc->dob ? $kyc->dob->format('d F Y') : ($parsed['dob'] ?? '-') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Jenis Kelamin</span>
                            <span class="info-val">
                                @if(($kyc->gender ?? ($parsed['gender'] ?? '')) === 'L')
                                    Laki-laki
                                @elseif(($kyc->gender ?? ($parsed['gender'] ?? '')) === 'P')
                                    Perempuan
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Waktu Pengajuan</span>
                            <span class="info-val">{{ $kyc->submitted_at ? $kyc->submitted_at->format('d M Y H:i:s') : '-' }} WIB</span>
                        </div>
                        @if($kyc->reviewed_at)
                        <div class="info-row">
                            <span class="info-key">Waktu Peninjauan</span>
                            <span class="info-val">{{ $kyc->reviewed_at->format('d M Y H:i:s') }} WIB</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Alamat e-KTP -->
            <div class="section-card" style="margin-top: 1.25rem;">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Alamat Tertera Pada e-KTP</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-row">
                            <span class="info-key">Alamat / Jalan</span>
                            <span class="info-val font-semibold">{{ $kyc->ktp_address ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">RT / RW</span>
                            <span class="info-val">{{ $kyc->ktp_rt_rw ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Kelurahan / Desa</span>
                            <span class="info-val">{{ $kyc->ktp_subdistrict ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Kecamatan</span>
                            <span class="info-val">{{ $kyc->ktp_district ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Kota / Kabupaten</span>
                            <span class="info-val">{{ $kyc->city ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Provinsi</span>
                            <span class="info-val">{{ $kyc->province ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alamat Domisili -->
            <div class="section-card" style="margin-top: 1.25rem;">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <span>Alamat Domisili (Tempat Tinggal)</span>
                    </div>
                    @if($kyc->is_domicile_same)
                        <span class="badge-subtle" style="background:#dcfce7; color:#166534; font-weight:700;">Sesuai KTP</span>
                    @else
                        <span class="badge-subtle" style="background:#fef3c7; color:#92400e; font-weight:700;">Domisili Berbeda</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($kyc->is_domicile_same)
                        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.85rem 1rem; color:#166534; font-size:0.8125rem;">
                            <strong>Alamat domisili sama persis dengan e-KTP.</strong>
                        </div>
                    @else
                        <div class="info-list">
                            <div class="info-row">
                                <span class="info-key">Alamat Lengkap</span>
                                <span class="info-val font-semibold">{{ $kyc->domicile_address ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">RT / RW</span>
                                <span class="info-val">{{ $kyc->domicile_rt_rw ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Kelurahan / Desa</span>
                                <span class="info-val">{{ $kyc->domicile_subdistrict ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Kecamatan</span>
                                <span class="info-val">{{ $kyc->domicile_district ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Kabupaten / Kota</span>
                                <span class="info-val">{{ $kyc->domicile_city ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Provinsi</span>
                                <span class="info-val">{{ $kyc->domicile_province ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Kode Pos</span>
                                <span class="info-val">{{ $kyc->domicile_postal_code ?? '-' }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Zoom Photo -->
<div id="photoModal" class="kyc-modal" style="display:none;" onclick="closePhotoModal(event)">
    <div class="kyc-modal-dialog" onclick="event.stopPropagation()">
        <div class="kyc-modal-header">
            <h3 id="modalPhotoTitle" class="modal-heading">Pratinjau Dokumen</h3>
            <button type="button" class="btn-close-modal" onclick="closePhotoModal()">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="kyc-modal-body">
            <img id="modalPhotoImage" src="" alt="Pratinjau" class="modal-full-img">
        </div>
    </div>
</div>

<!-- Modal Reject Reason -->
<div id="rejectModal" class="kyc-modal" style="display:none;" onclick="closeRejectModal(event)">
    <div class="kyc-modal-dialog small" onclick="event.stopPropagation()">
        <div class="kyc-modal-header">
            <h3 class="modal-heading">Tolak Permohonan Verifikasi</h3>
            <button type="button" class="btn-close-modal" onclick="closeRejectModal()">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="kyc-modal-body">
            <p class="modal-desc">
                Berikan alasan penolakan yang jelas agar creator dapat memperbaiki foto KTP atau pengambilan foto biometrik dengan benar.
            </p>
            <div class="form-group">
                <label for="rejectReason" class="form-label">Alasan Penolakan:</label>
                <textarea id="rejectReason" class="form-textarea" rows="4" placeholder="Contoh: Foto KTP buram dan nomor NIK tidak terbaca dengan jelas. Harap ulangi foto dengan pencahayaan yang cukup."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeRejectModal()">Batal</button>
                <button type="button" class="btn-modal-submit-reject" id="btnSubmitReject" onclick="submitReject()">Kirim Penolakan</button>
            </div>
        </div>
    </div>
</div>

<style>
.kyc-show-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.5rem 1rem;
    font-family: inherit;
    color: #1E293B;
}

.kyc-show-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #475569;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    transition: color 0.2s ease;
}

.back-link:hover {
    color: #0F172A;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 0.9rem;
    border-radius: 9999px;
    font-size: 0.8125rem;
    font-weight: 600;
}

.status-badge.verified {
    background: #DCFCE7;
    color: #166534;
}

.status-badge.pending {
    background: #FEF3C7;
    color: #92400E;
}

.status-badge.rejected {
    background: #FEE2E2;
    color: #991B1B;
}

.alert-box {
    display: flex;
    gap: 0.85rem;
    padding: 1rem 1.25rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.alert-rejected {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #991B1B;
}

.alert-icon {
    flex-shrink: 0;
    margin-top: 2px;
}

.alert-title {
    font-weight: 700;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.alert-message {
    font-size: 0.8125rem;
    line-height: 1.45;
}

.alert-meta {
    font-size: 0.75rem;
    color: #7F1D1D;
    margin-top: 0.4rem;
}

.kyc-grid {
    display: grid;
    grid-template-columns: 1.35fr 1fr;
    gap: 1.5rem;
}

@media (max-width: 900px) {
    .kyc-grid {
        grid-template-columns: 1fr;
    }
}

.section-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #E2E8F0;
    background: #F8FAFC;
}

.card-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 700;
    color: #0F172A;
}

.badge-subtle {
    display: inline-block;
    padding: 0.2rem 0.55rem;
    background: #F1F5F9;
    color: #475569;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
}

.card-body {
    padding: 1.25rem;
}

.photos-comparison-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 600px) {
    .photos-comparison-grid {
        grid-template-columns: 1fr;
    }
}

.photo-box {
    display: flex;
    flex-direction: column;
}

.photo-label {
    margin-bottom: 0.5rem;
}

.label-badge {
    display: inline-block;
    padding: 0.25rem 0.6rem;
    background: #EFF6FF;
    color: #1D4ED8;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}

.label-badge.biometrics {
    background: #FAF5FF;
    color: #7E22CE;
}

.photo-preview-wrap {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    background: #0F172A;
    aspect-ratio: 4 / 3;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 1px solid #E2E8F0;
}

.doc-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.25s ease;
}

.photo-preview-wrap:hover .doc-photo {
    transform: scale(1.03);
}

.photo-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    opacity: 0;
    transition: opacity 0.2s ease;
    color: #FFFFFF;
    font-size: 0.75rem;
    font-weight: 600;
}

.photo-preview-wrap:hover .photo-overlay {
    opacity: 1;
}

.no-photo {
    color: #94A3B8;
    font-size: 0.8125rem;
    text-align: center;
    padding: 1rem;
}

.photo-caption {
    margin-top: 0.5rem;
    font-size: 0.7rem;
    color: #64748B;
}

.photo-caption code {
    background: #F1F5F9;
    padding: 0.15rem 0.35rem;
    border-radius: 4px;
    color: #0F172A;
}

.actions-hint {
    font-size: 0.8125rem;
    color: #64748B;
    line-height: 1.5;
    margin: 0 0 1.25rem 0;
}

.action-buttons-wrap {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-approve {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: #1eb349;
    color: #FFFFFF;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn-approve:hover {
    background: #179b3e;
}

.btn-reject {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: #FFFFFF;
    color: #DC2626;
    border: 1px solid #FCA5A5;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-reject:hover {
    background: #FEF2F2;
    border-color: #EF4444;
}

.nik-banner {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    padding: 0.85rem 1rem;
    margin-bottom: 1.25rem;
}

.nik-banner-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748B;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.nik-banner-number {
    font-family: monospace;
    font-size: 1.25rem;
    font-weight: 700;
    color: #0F172A;
    letter-spacing: 0.05em;
}

.info-list {
    display: flex;
    flex-direction: column;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.7rem 0;
    border-bottom: 1px solid #F1F5F9;
    font-size: 0.8125rem;
}

.info-row:last-child {
    border-bottom: none;
}

.info-key {
    color: #64748B;
}

.info-val {
    color: #0F172A;
    text-align: right;
}

.font-semibold {
    font-weight: 600;
}

/* Modals */
.kyc-modal {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.75);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.kyc-modal-dialog {
    background: #FFFFFF;
    border-radius: 12px;
    max-width: 800px;
    width: 100%;
    overflow: hidden;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
}

.kyc-modal-dialog.small {
    max-width: 520px;
}

.kyc-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #E2E8F0;
}

.modal-heading {
    font-size: 1rem;
    font-weight: 700;
    color: #0F172A;
    margin: 0;
}

.btn-close-modal {
    background: transparent;
    border: none;
    color: #94A3B8;
    cursor: pointer;
    display: flex;
    align-items: center;
    padding: 0.25rem;
    transition: color 0.2s ease;
}

.btn-close-modal:hover {
    color: #0F172A;
}

.kyc-modal-body {
    padding: 1.25rem;
}

.modal-full-img {
    width: 100%;
    max-height: 75vh;
    object-fit: contain;
    border-radius: 8px;
    display: block;
}

.modal-desc {
    font-size: 0.8125rem;
    color: #64748B;
    line-height: 1.5;
    margin: 0 0 1rem 0;
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-label {
    display: block;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #1E293B;
    margin-bottom: 0.4rem;
}

.form-textarea {
    width: 100%;
    border: 1px solid #CBD5E1;
    border-radius: 8px;
    padding: 0.75rem;
    font-size: 0.8125rem;
    font-family: inherit;
    color: #1E293B;
    outline: none;
    resize: vertical;
    box-sizing: border-box;
}

.form-textarea:focus {
    border-color: #1eb349;
    box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15);
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
}

.btn-modal-cancel {
    background: #F1F5F9;
    color: #475569;
    border: none;
    border-radius: 8px;
    padding: 0.6rem 1rem;
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
}

.btn-modal-submit-reject {
    background: #DC2626;
    color: #FFFFFF;
    border: none;
    border-radius: 8px;
    padding: 0.6rem 1.25rem;
    font-size: 0.8125rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s ease;
}

.btn-modal-submit-reject:hover {
    background: #B91C1C;
}
</style>

<script>
function openPhotoModal(imgUrl, title) {
    if (!imgUrl) return;
    document.getElementById('modalPhotoImage').src = imgUrl;
    document.getElementById('modalPhotoTitle').textContent = title;
    document.getElementById('photoModal').style.display = 'flex';
}

function closePhotoModal(e) {
    if (!e || e.target === document.getElementById('photoModal') || e.target.closest('.btn-close-modal')) {
        document.getElementById('photoModal').style.display = 'none';
        document.getElementById('modalPhotoImage').src = '';
    }
}

function openRejectModal() {
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeRejectModal(e) {
    if (!e || e.target === document.getElementById('rejectModal') || e.target.closest('.btn-close-modal') || e.target.closest('.btn-modal-cancel')) {
        document.getElementById('rejectModal').style.display = 'none';
    }
}

function confirmApprove() {
    if (!confirm('Apakah Anda yakin ingin menyetujui verifikasi identitas creator ini?')) {
        return;
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('{{ route("admin.kyc.approve", $kyc) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Gagal menyetujui verifikasi.');
        }
    })
    .catch(err => {
        alert('Terjadi kesalahan koneksi.');
    });
}

function submitReject() {
    const reason = document.getElementById('rejectReason').value.trim();
    if (!reason || reason.length < 10) {
        alert('Harap masukkan alasan penolakan minimal 10 karakter.');
        return;
    }

    const btn = document.getElementById('btnSubmitReject');
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('{{ route("admin.kyc.reject", $kyc) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Gagal menolak verifikasi.');
            btn.disabled = false;
            btn.textContent = 'Kirim Penolakan';
        }
    })
    .catch(err => {
        alert('Terjadi kesalahan koneksi.');
        btn.disabled = false;
        btn.textContent = 'Kirim Penolakan';
    });
}
</script>
@endsection
