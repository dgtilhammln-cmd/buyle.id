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
                    @if($kyc->status === 'verified')
                        <span class="badge-subtle" style="background:#dcfce7; color:#166534; font-weight:700;">Status: Disetujui</span>
                    @elseif($kyc->status === 'rejected')
                        <span class="badge-subtle" style="background:#fee2e2; color:#991b1b; font-weight:700;">Status: Ditolak</span>
                    @else
                        <span class="badge-subtle" style="background:#fef3c7; color:#92400e; font-weight:700;">Status: Menunggu Review</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($kyc->status === 'verified')
                        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:1rem; margin-bottom:1rem;">
                            <div style="display:flex; align-items:center; gap:0.5rem; color:#166534; font-weight:700; font-size:0.9rem;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>e-KYC Creator Ini Telah Disetujui Resmi</span>
                            </div>
                            <div style="font-size:0.8rem; color:#15803d; margin-top:0.35rem; line-height:1.45;">
                                Diverifikasi pada {{ $kyc->reviewed_at ? $kyc->reviewed_at->format('d M Y H:i') : '-' }} WIB
                                @if($kyc->reviewer) oleh {{ $kyc->reviewer->name }} @endif.
                                Seluruh hak akses fitur Gatekeeper Studio & pencairan saldo telah aktif secara penuh.
                            </div>
                        </div>
                        <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                            <button type="button" class="btn-reject" onclick="openRejectModal()" style="font-size:0.8rem; padding:0.55rem 1rem;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                <span>Batalkan & Tolak Verifikasi</span>
                            </button>
                        </div>
                    @elseif($kyc->status === 'rejected')
                        <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:10px; padding:1rem; margin-bottom:1rem;">
                            <div style="display:flex; align-items:center; gap:0.5rem; color:#991b1b; font-weight:700; font-size:0.9rem;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                <span>Pengajuan Ini Sedang Dalam Status Ditolak</span>
                            </div>
                            <div style="font-size:0.8rem; color:#7f1d1d; margin-top:0.35rem; line-height:1.45;">
                                <strong>Alasan Penolakan:</strong> {{ $kyc->rejection_reason }}
                            </div>
                        </div>
                        <div class="action-buttons-wrap">
                            <button type="button" class="btn-approve" onclick="openApproveModal()">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Setujui Ulang Verifikasi</span>
                            </button>
                            <button type="button" class="btn-reject" onclick="openRejectModal()">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                <span>Perbarui Alasan Penolakan</span>
                            </button>
                        </div>
                    @else
                        <p class="actions-hint">
                            Pastikan keaslian foto KTP dan kesesuaian wajah pada foto biometrik liveness sebelum menyetujui. Setelah disetujui, akun creator akan mendapatkan hak penerbitan tiket dan pencairan saldo.
                        </p>
                        <div class="action-buttons-wrap">
                            <button type="button" class="btn-approve" onclick="openApproveModal()">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Setujui Verifikasi</span>
                            </button>
                            <button type="button" class="btn-reject" onclick="openRejectModal()">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                <span>Tolak Permohonan</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Extracted NIK Data & User Info -->
        <div class="info-column">
            <!-- 1. Profil Creator Buyle -->
            <div class="section-card">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Profil Creator Buyle</span>
                    </div>
                    @if($kyc->user && $kyc->user->creatorProfile && $kyc->user->creatorProfile->is_verified)
                        <span class="badge-subtle" style="background:#dcfce7; color:#166534; font-weight:700;">Toko Terverifikasi</span>
                    @else
                        <span class="badge-subtle" style="background:#f1f5f9; color:#475569; font-weight:700;">Creator Buyle</span>
                    @endif
                </div>
                <div class="card-body">
                    @php
                        $user = $kyc->user;
                        $profile = $user?->creatorProfile;
                        $storeName = $profile->store_name ?? ($user->name ?? 'Creator Buyle');
                        $storeSlug = $profile->store_slug ?? ($user->username ?? '');
                        $avatarUrl = $user?->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar)) : null;
                    @endphp

                    <div style="display:flex; align-items:center; gap:0.85rem; padding-bottom:1rem; border-bottom:1px solid #f1f5f9; margin-bottom:1rem;">
                        <div style="width:50px; height:50px; border-radius:50%; background:#e0e7ff; color:#4338ca; display:flex; align-items:center; justify-content:center; font-size:1.25rem; font-weight:800; overflow:hidden; flex-shrink:0; border:2px solid #e2e8f0;">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $storeName }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                {{ strtoupper(substr($storeName, 0, 1)) }}
                            @endif
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:1rem; font-weight:800; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $storeName }}
                            </div>
                            <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">
                                @if($user && $user->username)
                                    <span>{{ '@' . $user->username }}</span> &bull; 
                                @endif
                                <span>{{ $user->email ?? '-' }}</span>
                            </div>
                            @if($storeSlug)
                                <a href="{{ url('@' . $storeSlug) }}" target="_blank" style="font-size:0.75rem; color:#1eb349; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.25rem; margin-top:4px;">
                                    <span>Kunjungi Toko Creator &rsaquo;</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="info-list">
                        <div class="info-row">
                            <span class="info-key">Nama Akun</span>
                            <span class="info-val font-semibold">{{ $user->name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">No. WhatsApp</span>
                            <span class="info-val">
                                @if($user && $user->phone)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $user->phone);
                                        if (str_starts_with($cleanPhone, '0')) $cleanPhone = '62' . substr($cleanPhone, 1);
                                    @endphp
                                    <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" style="color:#16a34a; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:0.25rem;">
                                        {{ $user->phone }} &rarr;
                                    </a>
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Total Produk Aktif</span>
                            <span class="info-val font-semibold">{{ $user?->products?->count() ?? 0 }} Produk</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Terdaftar Sejak</span>
                            <span class="info-val">{{ $user?->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                        </div>
                    </div>

                    @if($user)
                    <div style="margin-top:1rem; pt:0.5rem;">
                        <a href="{{ route('admin.creator-resources.show', $user->id) }}" target="_blank" style="display:block; text-align:center; background:#f8fafc; border:1px solid #e2e8f0; color:#334155; font-size:0.8125rem; font-weight:700; padding:0.6rem; border-radius:10px; text-decoration:none; transition:all 0.2s ease;">
                            Buka Detail Lengkap di Creator Buyle &rarr;
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- 2. GABUNGAN 1 CARD: Identitas & Alamat e-KTP (Ringkas & Terpadu) -->
            <div class="section-card" style="margin-top: 1.25rem;">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="8" x2="17" y2="8"/><line x1="7" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="12" y2="16"/></svg>
                        <span>Data Identitas & Alamat e-KTP</span>
                    </div>
                    <span class="badge-subtle">Dekode Algoritmik & AI</span>
                </div>
                <div class="card-body">
                    <div class="nik-banner">
                        <div class="nik-banner-label">Nomor Induk Kependudukan (NIK)</div>
                        <div class="nik-banner-number">{{ $kyc->nik }}</div>
                    </div>

                    @php
                        $parsed = $kyc->nik_parsed_data ?? [];
                        $genderText = (($kyc->gender ?? ($parsed['gender'] ?? '')) === 'L') ? 'Laki-laki' : ((($kyc->gender ?? ($parsed['gender'] ?? '')) === 'P') ? 'Perempuan' : '-');
                    @endphp

                    <div class="info-list">
                        <div class="info-row">
                            <span class="info-key">Nama Lengkap (KTP)</span>
                            <span class="info-val font-semibold">{{ $kyc->full_name ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Tgl Lahir / Kelamin</span>
                            <span class="info-val">
                                {{ $kyc->dob ? $kyc->dob->format('d F Y') : ($parsed['dob'] ?? '-') }} ({{ $genderText }})
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Alamat / Jalan</span>
                            <span class="info-val font-semibold">{{ $kyc->ktp_address ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">RT / RW & Kelurahan</span>
                            <span class="info-val">
                                RT/RW: {{ $kyc->ktp_rt_rw ?? '-' }} &bull; Kel: {{ $kyc->ktp_subdistrict ?? '-' }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Kecamatan & Kota</span>
                            <span class="info-val">
                                Kec. {{ $kyc->ktp_district ?? '-' }}, {{ $kyc->city ?? '-' }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Provinsi</span>
                            <span class="info-val">{{ $kyc->province ?? ($parsed['province'] ?? '-') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Waktu Pengajuan</span>
                            <span class="info-val">{{ $kyc->submitted_at ? $kyc->submitted_at->format('d M Y H:i:s') : '-' }} WIB</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Alamat Domisili (Tempat Tinggal) -->
            <div class="section-card" style="margin-top: 1.25rem;">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <span>Alamat Domisili (Tempat Tinggal)</span>
                    </div>
                    @if($kyc->is_domicile_same)
                        <span class="badge-subtle" style="background:#dcfce7; color:#166534; font-weight:700;">Sesuai e-KTP</span>
                    @else
                        <span class="badge-subtle" style="background:#fef3c7; color:#92400e; font-weight:700;">Domisili Berbeda</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($kyc->is_domicile_same)
                        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.85rem 1rem; color:#166534; font-size:0.8125rem; display:flex; align-items:center; gap:0.5rem;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Alamat domisili tinggal saat ini <strong>sama persis</strong> dengan data e-KTP.</span>
                        </div>
                    @else
                        <div class="info-list">
                            <div class="info-row">
                                <span class="info-key">Alamat Domisili</span>
                                <span class="info-val font-semibold">{{ $kyc->domicile_address ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">RT/RW & Kelurahan</span>
                                <span class="info-val">RT/RW: {{ $kyc->domicile_rt_rw ?? '-' }} &bull; Kel: {{ $kyc->domicile_subdistrict ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Kecamatan & Kota</span>
                                <span class="info-val">Kec. {{ $kyc->domicile_district ?? '-' }}, {{ $kyc->domicile_city ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-key">Provinsi & Kode Pos</span>
                                <span class="info-val">{{ $kyc->domicile_province ?? '-' }} ({{ $kyc->domicile_postal_code ?? '-' }})</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 4. Rekening Bank & Verifikasi Pembayaran -->
            <div class="section-card" style="margin-top: 1.25rem;">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                        <span>Rekening Bank Pencairan Dana</span>
                    </div>
                    @if($bankHolder && $kyc->full_name && strcasecmp(trim($bankHolder), trim($kyc->full_name)) === 0)
                        <span class="badge-subtle" style="background:#dcfce7; color:#166534; font-weight:700;">Nama Sesuai KTP</span>
                    @elseif($bankHolder)
                        <span class="badge-subtle" style="background:#fef3c7; color:#92400e; font-weight:700;">Nama Rekening Terdaftar</span>
                    @else
                        <span class="badge-subtle" style="background:#f1f5f9; color:#64748b;">Belum Diisi</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-row">
                            <span class="info-key">Nama Bank</span>
                            <span class="info-val font-semibold">{{ $bankName ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Nomor Rekening</span>
                            <span class="info-val font-mono" style="font-size:0.95rem; font-weight:700; color:#0f172a;">
                                {{ $bankAccount ?? '-' }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-key">Atas Nama Rekening</span>
                            <span class="info-val font-semibold">{{ $bankHolder ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Riwayat Transaksi & Pencairan Dana -->
            <div class="section-card" style="margin-top: 1.25rem;">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <span>Riwayat Finansial & Pencairan</span>
                    </div>
                </div>
                <div class="card-body">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:0.75rem;">
                            <div style="font-size:0.7rem; color:#166534; font-weight:700;">TOTAL OMSET PENJUALAN</div>
                            <div style="font-size:1.05rem; font-weight:800; color:#15803d; margin-top:2px;">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </div>
                            <div style="font-size:0.7rem; color:#166534; margin-top:2px;">{{ $totalOrdersCount }} transaksi selesai</div>
                        </div>

                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:0.75rem;">
                            <div style="font-size:0.7rem; color:#475569; font-weight:700;">SALDO DICAIRKAN</div>
                            <div style="font-size:1.05rem; font-weight:800; color:#0f172a; margin-top:2px;">
                                Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}
                            </div>
                            <div style="font-size:0.7rem; color:#d97706; margin-top:2px;">
                                @if($pendingPayout > 0)
                                    Rp {{ number_format($pendingPayout, 0, ',', '.') }} pending
                                @else
                                    0 pending
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
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

<!-- Modal Approve Confirmation -->
<div id="approveModal" class="kyc-modal" style="display:none;" onclick="closeApproveModal(event)">
    <div class="kyc-modal-dialog small" onclick="event.stopPropagation()">
        <div class="kyc-modal-header">
            <h3 class="modal-heading">Konfirmasi Persetujuan e-KYC</h3>
            <button type="button" class="btn-close-modal" onclick="closeApproveModal()">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="kyc-modal-body">
            <div style="display:flex; gap:0.85rem; align-items:flex-start; margin-bottom:1.25rem;">
                <div style="width:42px; height:42px; border-radius:50%; background:#dcfce7; display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid #bbf7d0;">
                    <svg width="22" height="22" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <h4 style="margin:0 0 0.35rem 0; font-size:0.95rem; font-weight:800; color:#0f172a;">Setujui Verifikasi Identitas Creator?</h4>
                    <p class="modal-desc" style="margin:0; line-height:1.5;">
                        Setelah disetujui, akun creator <strong>{{ $kyc->full_name ?? ($kyc->user->name ?? 'Creator') }}</strong> (NIK: <code>{{ $kyc->nik }}</code>) akan langsung berstatus <strong>Terverifikasi Resmi</strong>. Fitur Scanner, Manajemen Event, dan Pencairan Dana akan segera aktif.
                    </p>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeApproveModal()">Batal</button>
                <button type="button" class="btn-modal-submit-approve" id="btnSubmitApprove" onclick="submitApprove()" style="background:#16a34a; color:#fff; border:none; border-radius:8px; padding:0.6rem 1.25rem; font-size:0.8125rem; font-weight:700; cursor:pointer; transition:background 0.2s ease;">Ya, Setujui Sekarang</button>
            </div>
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

function openApproveModal() {
    document.getElementById('approveModal').style.display = 'flex';
}

function closeApproveModal(e) {
    if (!e || e.target === document.getElementById('approveModal') || e.target.closest('.btn-close-modal') || e.target.closest('.btn-modal-cancel')) {
        document.getElementById('approveModal').style.display = 'none';
    }
}

function submitApprove() {
    const btn = document.getElementById('btnSubmitApprove');
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('{{ route("admin.kyc.approve", $kyc) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ _token: csrf })
    })
    .then(async res => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            throw new Error(data.message || ('Server error (' + res.status + ')'));
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            btn.textContent = 'Berhasil Disetujui!';
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            alert(data.message || 'Gagal menyetujui verifikasi.');
            btn.disabled = false;
            btn.textContent = 'Ya, Setujui Sekarang';
        }
    })
    .catch(err => {
        console.error('Approve error:', err);
        alert('Terjadi kendala saat menyetujui verifikasi: ' + err.message);
        btn.disabled = false;
        btn.textContent = 'Ya, Setujui Sekarang';
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
        body: JSON.stringify({ reason: reason, _token: csrf })
    })
    .then(async res => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            throw new Error(data.message || ('Server error (' + res.status + ')'));
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            btn.textContent = 'Berhasil Ditolak!';
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            alert(data.message || 'Gagal menolak verifikasi.');
            btn.disabled = false;
            btn.textContent = 'Kirim Penolakan';
        }
    })
    .catch(err => {
        console.error('Reject error:', err);
        alert('Terjadi kendala saat menolak verifikasi: ' + err.message);
        btn.disabled = false;
        btn.textContent = 'Kirim Penolakan';
    });
}
</script>
@endsection
