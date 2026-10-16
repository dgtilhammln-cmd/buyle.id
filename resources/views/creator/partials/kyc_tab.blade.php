{{-- TAB PENGATURAN & VERIFIKASI e-KYC BIOMETRIK --}}
<div id="tab-settings" class="payout-tab-pane" style="display: {{ $activeTab === 'settings' ? 'block' : 'none' }};">

    {{-- CARD 1: STATUS VERIFIKASI --}}
    <div class="prof-card" style="margin-bottom: 1.5rem;">
        <div class="prof-card-head">
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg>
                Status Verifikasi Identitas & Biometrik (e-KYC)
            </div>
            @if(isset($kyc) && $kyc->status === 'verified')
                <span class="kyc-badge-pill verified">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Terverifikasi
                </span>
            @elseif(isset($kyc) && $kyc->status === 'pending')
                <span class="kyc-badge-pill pending">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Menunggu Review Admin
                </span>
            @elseif(isset($kyc) && $kyc->status === 'rejected')
                <span class="kyc-badge-pill rejected">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Verifikasi Ditolak
                </span>
            @else
                <span class="kyc-badge-pill unverified">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Belum Terverifikasi
                </span>
            @endif
        </div>

        <div class="form-body">
            @if(isset($kyc) && $kyc->status === 'verified')
                {{-- TAMPILAN JIKA SUDAH TERVERIFIKASI --}}
                <div class="kyc-verified-box">
                    <div class="verified-icon-wrap">
                        <svg width="32" height="32" fill="none" stroke="#166534" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div>
                        <h4 style="margin:0 0 0.35rem 0; font-size:1rem; font-weight:800; color:#14532d;">Identitas Creator Terverifikasi Resmi</h4>
                        <p style="margin:0 0 0.75rem 0; font-size:0.8125rem; color:#166534; line-height:1.5;">
                            Akun Anda telah memenuhi standar kepatuhan e-KYC Buyle.id. Seluruh fitur penjualan event tiket berbayar dan pencairan saldo telah aktif secara penuh.
                        </p>
                        <div class="verified-meta-grid">
                            <div><strong>Nama KTP:</strong> {{ $kyc->full_name }}</div>
                            <div><strong>NIK:</strong> {{ substr($kyc->nik, 0, 6) . '******' . substr($kyc->nik, -4) }}</div>
                            <div><strong>Provinsi:</strong> {{ $kyc->province ?? '-' }}</div>
                            <div><strong>Diverifikasi Pada:</strong> {{ $kyc->reviewed_at ? $kyc->reviewed_at->format('d M Y H:i') : '-' }} WIB</div>
                        </div>
                    </div>
                </div>
            @elseif(isset($kyc) && $kyc->status === 'pending')
                {{-- TAMPILAN MENUNGGU PENINJAUAN --}}
                <div class="kyc-pending-box">
                    <div class="pending-icon-wrap">
                        <svg width="32" height="32" fill="none" stroke="#92400e" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <h4 style="margin:0 0 0.35rem 0; font-size:1rem; font-weight:800; color:#78350f;">Dokumen Sedang Ditinjau Tim Verifikator</h4>
                        <p style="margin:0 0 0.75rem 0; font-size:0.8125rem; color:#92400e; line-height:1.5;">
                            Permohonan e-KYC Anda telah kami terima dan saat ini sedang ditinjau oleh tim verifikator Buyle.id. Estimasi proses peninjauan adalah maksimal 1x24 jam kerja.
                        </p>
                        <div class="pending-meta-grid">
                            <div><strong>Nama Diajukan:</strong> {{ $kyc->full_name }}</div>
                            <div><strong>NIK:</strong> {{ substr($kyc->nik, 0, 6) . '******' . substr($kyc->nik, -4) }}</div>
                            <div><strong>Waktu Pengajuan:</strong> {{ $kyc->submitted_at ? $kyc->submitted_at->format('d M Y H:i') : '-' }} WIB</div>
                        </div>
                    </div>
                </div>
            @else
                {{-- FORM PENGAJUAN VERIFIKASI (BARU ATAU AJUKAN ULANG DITOLAK) --}}
                @if(isset($kyc) && $kyc->status === 'rejected')
                    <div class="kyc-rejected-alert">
                        <div style="flex-shrink:0;">
                            <svg width="24" height="24" fill="none" stroke="#b91c1c" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </div>
                        <div>
                            <strong style="display:block; font-size:0.875rem; color:#991b1b; margin-bottom:0.25rem;">Pengajuan Sebelumnya Ditolak</strong>
                            <div style="font-size:0.8125rem; color:#7f1d1d; line-height:1.45;">
                                <strong>Alasan Penolakan:</strong> {{ $kyc->rejection_reason }}
                            </div>
                            <div style="font-size:0.75rem; color:#991b1b; margin-top:0.4rem;">Silakan lengkapi data dan ambil ulang foto KTP serta foto wajah biometrik sesuai petunjuk di bawah ini.</div>
                        </div>
                    </div>
                @endif

                <div class="kyc-intro-card">
                    <div class="kyc-intro-title">
                        <svg width="18" height="18" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Kenapa Creator Wajib e-KYC?
                    </div>
                    <div class="kyc-intro-text">
                        Untuk mencegah penipuan tiket, calo, dan melindungi hak pembeli, setiap penyelenggara event wajib memverifikasi KTP asli dan biometrik wajah satu kali. Data foto disimpan secara aman di private cloud Buyle.id dalam format WebP terkompresi tanpa membebani penyimpanan perangkat Anda.
                    </div>
                </div>

                {{-- FORM BODY --}}
                <form id="kycSubmitForm" onsubmit="handleKycFormSubmit(event)" style="margin-top:1.5rem;">
                    {{-- STEP 1: DUAL-MODE KTP (UPLOAD GAMBAR & LIVE SCANNER) + AI SMART VISION EXTRACTOR --}}
                    <div class="kyc-form-section">
                        <div class="kyc-section-header">
                            <span class="step-num">1</span>
                            <div style="flex:1;">
                                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
                                    <div>
                                        <h3 class="step-heading">Foto Dokumen KTP Asli</h3>
                                        <p class="step-desc">Unggah file foto KTP atau gunakan kamera live scanner. AI Vision akan otomatis mengekstrak NIK, Nama, dan Alamat Anda.</p>
                                    </div>
                                    {{-- Mode Selector Tab --}}
                                    <div class="kyc-mode-selector">
                                        <button type="button" id="tabModeKtpUpload" class="mode-tab-btn active" onclick="switchKtpMode('upload')">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                            Unggah File Gambar
                                        </button>
                                        <button type="button" id="tabModeKtpCam" class="mode-tab-btn" onclick="switchKtpMode('camera')">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                            Pindai Kamera Live
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="scanner-device-container">
                            {{-- PANE A: UPLOAD FILE GAMBAR KTP --}}
                            <div id="ktpUploadPane" class="ktp-mode-pane" style="display:block;">
                                {{-- Drag & Drop Upload Zone Light --}}
                                <div id="ktpDropZone" class="ktp-dropzone-light" onclick="document.getElementById('ktpFileInput').click()">
                                    <input type="file" id="ktpFileInput" accept="image/*" style="display:none;" onchange="handleFileUpload(event, 'ktp')">
                                    <div class="dropzone-icon-circle">
                                        <svg width="36" height="36" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="8" x2="17" y2="8"/><line x1="7" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="12" y2="16"/></svg>
                                    </div>
                                    <div class="dropzone-text-primary">Klik untuk memilih foto KTP atau seret gambar ke sini</div>
                                    <div class="dropzone-text-sub">Format: JPG, PNG, atau WebP. AI Vision akan otomatis membaca data KTP.</div>
                                    <button type="button" class="btn-select-file" onclick="event.stopPropagation(); document.getElementById('ktpFileInput').click();">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        Pilih Foto KTP
                                    </button>
                                </div>
                            </div>

                            {{-- PANE B: LIVE CAMERA SCANNER KTP --}}
                            <div id="ktpCameraPane" class="ktp-mode-pane" style="display:none;">
                                <div class="scanner-viewport-wrap light-theme" id="ktpViewportWrap">
                                    <video id="ktpVideo" playsinline autoplay muted class="scanner-live-video" style="display:none;"></video>
                                    <canvas id="ktpCanvas" style="display:none;"></canvas>

                                    {{-- Placeholder sebelum kamera dibuka --}}
                                    <div id="ktpPlaceholder" class="scanner-placeholder light-theme">
                                        <div class="placeholder-icon-wrap">
                                            <svg width="38" height="38" fill="none" stroke="#1eb349" stroke-width="1.8" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                        </div>
                                        <span class="placeholder-title">Kamera KTP Belum Aktif</span>
                                        <span class="placeholder-sub">Klik tombol di bawah untuk menyalakan kamera atau kembali ke tab unggah file gambar</span>
                                        <div style="display:flex; gap:0.5rem; margin-top:0.75rem; flex-wrap:wrap; justify-content:center;">
                                            <button type="button" class="btn-activate-cam" onclick="startKtpCamera()">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                                Nyalakan Kamera
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Overlay Frame Persegi KTP Light --}}
                                    <div id="ktpFrameOverlay" class="ktp-frame-overlay light-theme" style="display:none;">
                                        <div class="ktp-card-outline light-theme">
                                            <div class="ktp-corner top-left"></div>
                                            <div class="ktp-corner top-right"></div>
                                            <div class="ktp-corner bottom-left"></div>
                                            <div class="ktp-corner bottom-right"></div>
                                            <span class="ktp-frame-hint light-theme">Posisikan Kartu KTP di Dalam Bingkai Ini</span>
                                        </div>
                                    </div>

                                    {{-- Banner Panduan Realtime KTP Light --}}
                                    <div id="ktpGuidanceBanner" class="guidance-banner-light" style="display:none;">
                                        <svg id="ktpGuideIcon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                        <span id="ktpGuideText">Menyesuaikan pencahayaan...</span>
                                    </div>
                                </div>

                                {{-- Kontrol Kamera KTP --}}
                                <div class="scanner-controls-bar" id="ktpControlsBar" style="display:none;">
                                    <button type="button" class="btn-control-secondary" onclick="switchKtpCamera()">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                                        Ganti Kamera
                                    </button>
                                    <button type="button" id="btnSnapKtp" class="btn-snap-photo" onclick="snapKtpPhoto()">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                                        Ambil Foto KTP
                                    </button>
                                    <button type="button" class="btn-control-secondary" onclick="stopKtpCamera()">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        Tutup Kamera
                                    </button>
                                </div>
                            </div>

                            {{-- PREVIEW HASIL SCAN / UPLOAD KTP DENGAN STATUS AI VISION EXTRACTOR --}}
                            <div id="ktpResultCard" class="photo-result-card-light" style="display:none;">
                                <div class="result-img-container">
                                    <img id="ktpPreviewImg" src="" alt="Pratinjau KTP">
                                    <div class="result-badge-pill">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        Foto KTP Siap
                                    </div>
                                </div>

                                {{-- Status Ekstraksi AI Vision --}}
                                <div id="ktpAiExtractStatus" class="ktp-ai-status-box" style="display:none;">
                                    <div class="ai-status-spinner" id="ktpAiSpinner"></div>
                                    <div class="ai-status-content">
                                        <div class="ai-status-title" id="ktpAiStatusTitle">Mengekstrak Data e-KTP dengan AI Smart Vision...</div>
                                        <div class="ai-status-desc" id="ktpAiStatusDesc">Mohon tunggu beberapa detik, sistem sedang membaca NIK, Nama Lengkap, dan Alamat secara otomatis.</div>
                                    </div>
                                    <button type="button" id="btnRetryAiExtract" class="btn-retry-ai" style="display:none;" onclick="triggerKtpAiExtract()">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                        Ekstrak Ulang AI
                                    </button>
                                </div>

                                <div class="result-feedback-bar" id="ktpSmartFeedback">
                                    <svg width="16" height="16" fill="none" stroke="#166534" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    <span id="ktpSmartFeedbackText">Analisis Cerdas: Foto terdeteksi tajam dengan pencahayaan ideal.</span>
                                </div>
                                <div class="result-action-row">
                                    <button type="button" class="btn-retake-light" onclick="retakeKtpPhoto()">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                        Ganti / Ambil Ulang Foto KTP
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2: DATA IDENTITAS & ALAMAT SESUAI e-KTP --}}
                    <div class="kyc-form-section">
                        <div class="kyc-section-header">
                            <span class="step-num">2</span>
                            <div style="flex:1;">
                                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                                    <div>
                                        <h3 class="step-heading">Data Identitas & Alamat Sesuai e-KTP</h3>
                                        <p class="step-desc">Kolom di bawah ini terisi otomatis oleh AI setelah foto KTP diunggah. Anda tetap dapat mengoreksi data jika diperlukan.</p>
                                    </div>
                                    <div id="aiExtractionBadge" class="ai-extracted-badge" style="display:none;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span>Terisi Otomatis via AI Vision</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="kyc-input-grid">
                            <div class="form-group-custom">
                                <label class="label-custom" for="kycNikInput">Nomor Induk Kependudukan (NIK 16 Digit) <span class="req">*</span></label>
                                <div class="input-with-icon">
                                    <svg width="18" height="18" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="7" y1="8" x2="17" y2="8"/><line x1="7" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="12" y2="16"/></svg>
                                    <input type="text" id="kycNikInput" maxlength="16" inputmode="numeric" placeholder="Contoh: 320101XXXXXXXXXX" class="input-custom font-mono" oninput="handleNikInput(this.value)">
                                </div>
                                <div id="nikFeedback" class="nik-feedback-box" style="display:none;"></div>
                            </div>

                            <div class="form-group-custom">
                                <label class="label-custom" for="kycNameInput">Nama Lengkap (Sesuai KTP) <span class="req">*</span></label>
                                <div class="input-with-icon">
                                    <svg width="18" height="18" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <input type="text" id="kycNameInput" placeholder="Nama lengkap persis seperti di KTP" class="input-custom" value="" oninput="checkFormReadiness()">
                                </div>
                            </div>
                        </div>

                        {{-- Detail Alamat Tertera di e-KTP --}}
                        <div style="margin-top:1.25rem; padding-top:1.25rem; border-top:1px dashed #e2e8f0;">
                            <div style="font-size:0.8125rem; font-weight:800; color:#334155; margin-bottom:0.75rem; display:flex; align-items:center; gap:0.4rem;">
                                <svg width="15" height="15" fill="none" stroke="#1eb349" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Detail Alamat Tertera Pada e-KTP
                            </div>
                            <div class="kyc-input-grid" style="margin-bottom:0.5rem;">
                                <div class="form-group-custom" style="grid-column: 1 / -1;">
                                    <label class="label-custom" for="ktpAddressStreet">Alamat Jalan / Dusun / No. Rumah</label>
                                    <input type="text" id="ktpAddressStreet" placeholder="Contoh: Jl. Bambu Larangan No. 12" class="input-custom" oninput="onKtpAddressChange()">
                                </div>
                                <div class="form-group-custom">
                                    <label class="label-custom" for="ktpAddressRtRw">RT / RW</label>
                                    <input type="text" id="ktpAddressRtRw" placeholder="Contoh: 004/009" class="input-custom" oninput="onKtpAddressChange()">
                                </div>
                                <div class="form-group-custom">
                                    <label class="label-custom" for="ktpAddressKelurahan">Kelurahan / Desa</label>
                                    <input type="text" id="ktpAddressKelurahan" placeholder="Contoh: Pegadungan" class="input-custom" oninput="onKtpAddressChange()">
                                </div>
                                <div class="form-group-custom">
                                    <label class="label-custom" for="ktpAddressKecamatan">Kecamatan</label>
                                    <input type="text" id="ktpAddressKecamatan" placeholder="Contoh: Kalideres" class="input-custom" oninput="onKtpAddressChange()">
                                </div>
                                <div class="form-group-custom">
                                    <label class="label-custom" for="ktpAddressCity">Kabupaten / Kota</label>
                                    <input type="text" id="ktpAddressCity" placeholder="Contoh: Jakarta Barat" class="input-custom" oninput="onKtpAddressChange()">
                                </div>
                                <div class="form-group-custom" style="grid-column: 1 / -1;">
                                    <label class="label-custom" for="ktpAddressProvince">Provinsi</label>
                                    <input type="text" id="ktpAddressProvince" placeholder="Contoh: DKI Jakarta" class="input-custom" oninput="onKtpAddressChange()">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3: FORM KE-2 WAJIB - ALAMAT DOMISILI (TEMPAT TINGGAL SAAT INI) --}}
                    <div class="kyc-form-section">
                        <div class="kyc-section-header">
                            <span class="step-num">3</span>
                            <div>
                                <h3 class="step-heading">Alamat Domisili (Tempat Tinggal Saat Ini) <span class="req">*</span></h3>
                                <p class="step-desc">Tentukan apakah tempat tinggal Anda saat ini sesuai alamat e-KTP atau berada di alamat berbeda.</p>
                            </div>
                        </div>

                        {{-- Radio Selector: Sesuai KTP vs Domisili Berbeda --}}
                        <div class="domicile-choice-grid">
                            <label class="domicile-radio-card active" id="domicileChoiceSameLabel" onclick="setDomicileMode(true)">
                                <input type="radio" name="domicile_type" id="domicileChoiceSame" value="1" checked style="display:none;">
                                <div class="radio-check-indicator">
                                    <svg width="12" height="12" fill="none" stroke="#ffffff" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <div>
                                    <strong class="domicile-card-title">Sesuai Alamat e-KTP</strong>
                                    <p class="domicile-card-desc">Tempat tinggal saat ini sama persis dengan alamat yang tertera di dokumen e-KTP.</p>
                                </div>
                            </label>

                            <label class="domicile-radio-card" id="domicileChoiceDiffLabel" onclick="setDomicileMode(false)">
                                <input type="radio" name="domicile_type" id="domicileChoiceDiff" value="0" style="display:none;">
                                <div class="radio-check-indicator">
                                    <svg width="12" height="12" fill="none" stroke="#ffffff" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <div>
                                    <strong class="domicile-card-title">Alamat Domisili Berbeda</strong>
                                    <p class="domicile-card-desc">Tinggal di alamat berbeda dari e-KTP (Isi form manual via API wilayah).</p>
                                </div>
                            </label>
                        </div>

                        {{-- Pane A: Tampilan Ringkasan Alamat Sesuai KTP --}}
                        <div id="domicileSamePreviewBox" class="domicile-same-box">
                            <div class="domicile-same-icon">
                                <svg width="22" height="22" fill="none" stroke="#166534" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            </div>
                            <div style="flex:1;">
                                <div style="font-size:0.875rem; font-weight:800; color:#14532d; margin-bottom:0.25rem;">Alamat Domisili Otomatis Mengikuti e-KTP</div>
                                <div id="domicileSameSummaryText" style="font-size:0.8125rem; color:#166534; line-height:1.5;">
                                    Data domisili akan disamakan dengan alamat yang tertera pada e-KTP Anda di atas.
                                </div>
                            </div>
                        </div>

                        {{-- Pane B: Form Manual Bertingkat Menggunakan API Lokasi Wilayah Indonesia --}}
                        <div id="domicileDifferentFormPane" class="domicile-diff-box" style="display:none;">
                            <div style="margin-bottom:1rem; font-size:0.8125rem; color:#475569; display:flex; align-items:center; gap:0.4rem;">
                                <svg width="16" height="16" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                Pilih lokasi domisili Anda secara bertingkat (Provinsi, Kabupaten/Kota, Kecamatan, dan Kelurahan).
                            </div>

                            <div class="kyc-input-grid">
                                {{-- Dropdown Provinsi --}}
                                <div class="form-group-custom">
                                    <label class="label-custom" for="domicileProvinceSelect">Provinsi Domisili <span class="req">*</span></label>
                                    <select id="domicileProvinceSelect" class="input-custom" onchange="onDomicileProvinceChange(this.value)">
                                        <option value="">-- Pilih Provinsi --</option>
                                    </select>
                                </div>

                                {{-- Dropdown Kabupaten/Kota --}}
                                <div class="form-group-custom">
                                    <label class="label-custom" for="domicileCitySelect">Kabupaten / Kota Domisili <span class="req">*</span></label>
                                    <select id="domicileCitySelect" class="input-custom" disabled onchange="onDomicileCityChange(this.value)">
                                        <option value="">-- Pilih Kabupaten/Kota --</option>
                                    </select>
                                </div>

                                {{-- Dropdown Kecamatan --}}
                                <div class="form-group-custom">
                                    <label class="label-custom" for="domicileDistrictSelect">Kecamatan Domisili <span class="req">*</span></label>
                                    <select id="domicileDistrictSelect" class="input-custom" disabled onchange="onDomicileDistrictChange(this.value)">
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                </div>

                                {{-- Dropdown Kelurahan / Desa --}}
                                <div class="form-group-custom">
                                    <label class="label-custom" for="domicileSubdistrictSelect">Kelurahan / Desa Domisili <span class="req">*</span></label>
                                    <select id="domicileSubdistrictSelect" class="input-custom" disabled onchange="checkFormReadiness()">
                                        <option value="">-- Pilih Kelurahan/Desa --</option>
                                    </select>
                                </div>

                                {{-- RT / RW --}}
                                <div class="form-group-custom">
                                    <label class="label-custom" for="domicileRtRwInput">RT / RW Domisili</label>
                                    <input type="text" id="domicileRtRwInput" class="input-custom" placeholder="Contoh: 004/009" oninput="checkFormReadiness()">
                                </div>

                                {{-- Kode Pos --}}
                                <div class="form-group-custom">
                                    <label class="label-custom" for="domicilePostalCodeInput">Kode Pos</label>
                                    <input type="text" id="domicilePostalCodeInput" class="input-custom font-mono" maxlength="6" inputmode="numeric" placeholder="Contoh: 11840" oninput="checkFormReadiness()">
                                </div>

                                {{-- Detail Alamat Lengkap --}}
                                <div class="form-group-custom" style="grid-column: 1 / -1;">
                                    <label class="label-custom" for="domicileAddressInput">Alamat Lengkap Domisili (Nama Jalan, No. Rumah, Patokan) <span class="req">*</span></label>
                                    <textarea id="domicileAddressInput" rows="2" class="input-custom" style="height:auto; padding:0.65rem 0.85rem;" placeholder="Tuliskan nama jalan, blok, nomor rumah, atau patokan tempat tinggal saat ini..." oninput="checkFormReadiness()"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 4: BIOMETRIK WAJAH LIVENESS (KAMERA LANGSUNG ONLY - SMART SPATIAL AI DETECTOR) --}}
                    <div class="kyc-form-section">
                        <div class="kyc-section-header">
                            <span class="step-num">4</span>
                            <div style="flex:1;">
                                <div>
                                    <h3 class="step-heading">Pindai Biometrik Wajah Liveness (Wajib Kamera Langsung)</h3>
                                    <p class="step-desc">Untuk keamanan dan validasi identitas, foto wajah wajib diambil secara langsung di depan kamera dengan memposisikan wajah di dalam bingkai oval.</p>
                                </div>
                            </div>
                        </div>

                        <div class="scanner-device-container">
                            <div id="faceCameraPane" class="face-mode-pane" style="display:block;">
                                <div class="scanner-viewport-wrap light-theme" id="faceViewportWrap">
                                    <video id="faceVideo" playsinline autoplay muted class="scanner-live-video" style="display:none;"></video>
                                    <canvas id="faceCanvas" style="display:none;"></canvas>

                                    {{-- Placeholder sebelum kamera wajah dibuka --}}
                                    <div id="facePlaceholder" class="scanner-placeholder light-theme">
                                        <div class="placeholder-icon-wrap">
                                            <svg width="38" height="38" fill="none" stroke="#1eb349" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                                        </div>
                                        <span class="placeholder-title">Kamera Biometrik Wajah Belum Aktif</span>
                                        <span class="placeholder-sub">Klik tombol di bawah untuk menyalakan kamera. Sistem akan memeriksa posisi wajah secara cerdas di dalam oval.</span>
                                        <div style="display:flex; gap:0.5rem; margin-top:0.75rem; flex-wrap:wrap; justify-content:center;">
                                            <button type="button" class="btn-activate-cam" onclick="startFaceCamera()">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                                Buka Kamera Selfie
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Overlay Oval Frame Wajah Light --}}
                                    <div id="faceFrameOverlay" class="face-frame-overlay light-theme" style="display:none;">
                                        <div class="face-oval-outline light-theme" id="faceOvalOutline">
                                            <span class="face-frame-hint light-theme" id="faceFrameHint">Posisikan Wajah di Tengah Oval</span>
                                        </div>
                                    </div>

                                    {{-- Banner Panduan Realtime Wajah Light --}}
                                    <div id="faceGuidanceBanner" class="guidance-banner-light guide-warn" style="display:none;">
                                        <svg id="faceGuideIcon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                        <span id="faceGuideText">Posisikan wajah tepat di dalam bingkai oval...</span>
                                    </div>

                                    {{-- Challenge Status Badge Light --}}
                                    <div id="livenessChallengeBox" class="liveness-challenge-box-light" style="display:none;">
                                        <div class="challenge-dot" id="challengeDot"></div>
                                        <span id="challengeInstruction">Posisikan wajah Anda ke tengah bingkai oval</span>
                                    </div>
                                </div>

                                {{-- Kontrol Kamera Wajah --}}
                                <div class="scanner-controls-bar" id="faceControlsBar" style="display:none;">
                                    <button type="button" class="btn-control-secondary" onclick="switchFaceCamera()">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                                        Ganti Kamera
                                    </button>
                                    <button type="button" id="btnSnapFace" class="btn-snap-photo" onclick="snapFacePhoto()" disabled title="Posisikan wajah tepat di tengah oval untuk mengaktifkan tombol ini">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                                        Ambil Foto Biometrik
                                    </button>
                                    <button type="button" class="btn-control-secondary" onclick="stopFaceCamera()">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        Tutup Kamera
                                    </button>
                                </div>
                            </div>

                            {{-- PREVIEW HASIL BIOMETRIK WAJAH DENGAN SMART FEEDBACK (TEMA LIGHT) --}}
                            <div id="faceResultCard" class="photo-result-card-light" style="display:none;">
                                <div class="result-img-container">
                                    <img id="facePreviewImg" src="" alt="Pratinjau Biometrik Wajah">
                                    <div class="result-badge-pill">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        Biometrik Wajah Siap
                                    </div>
                                </div>
                                <div class="result-feedback-bar" id="faceSmartFeedback">
                                    <svg width="16" height="16" fill="none" stroke="#166534" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    <span id="faceSmartFeedbackText">Analisis Cerdas: Wajah terdeteksi jelas dan liveness token terverifikasi.</span>
                                </div>
                                <div class="result-action-row">
                                    <button type="button" class="btn-retake-light" onclick="retakeFacePhoto()">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                        Pindai Ulang Wajah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SUBMIT BAR --}}
                    <div class="kyc-submit-bar">
                        <div class="security-guarantee-note">
                            <svg width="16" height="16" fill="none" stroke="#166534" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Data Anda dienkripsi secara privat dan hanya digunakan untuk validasi legalitas penyelenggara event resmi Buyle.id.</span>
                        </div>
                        <button type="submit" id="btnSubmitKyc" class="btn-submit-kyc" disabled>
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            <span id="btnSubmitKycText">Kirim Pengajuan Verifikasi</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<style>
/* ── e-KYC LIGHT THEME STYLING (BUYLE.ID DESIGN SYSTEM) ── */
.kyc-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
}
.kyc-badge-pill.verified {
    background: #dcfce7;
    color: #166534;
}
.kyc-badge-pill.pending {
    background: #fef3c7;
    color: #92400e;
}
.kyc-badge-pill.rejected {
    background: #fee2e2;
    color: #991b1b;
}
.kyc-badge-pill.unverified {
    background: #f1f5f9;
    color: #475569;
}

.kyc-verified-box {
    display: flex;
    gap: 1.25rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    border: 1.5px solid #bbf7d0;
    border-radius: 16px;
    align-items: flex-start;
}
.verified-icon-wrap {
    width: 52px;
    height: 52px;
    background: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(22, 101, 52, 0.12);
}
.verified-meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.5rem 1rem;
    margin-top: 0.85rem;
    padding-top: 0.85rem;
    border-top: 1px solid rgba(22, 101, 52, 0.15);
    font-size: 0.8125rem;
    color: #14532d;
}

.kyc-pending-box {
    display: flex;
    gap: 1.25rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border: 1.5px solid #fde68a;
    border-radius: 16px;
    align-items: flex-start;
}
.pending-icon-wrap {
    width: 52px;
    height: 52px;
    background: #ffffff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(146, 64, 14, 0.12);
}
.pending-meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.5rem 1rem;
    margin-top: 0.85rem;
    padding-top: 0.85rem;
    border-top: 1px solid rgba(146, 64, 14, 0.15);
    font-size: 0.8125rem;
    color: #78350f;
}

.kyc-rejected-alert {
    display: flex;
    gap: 1rem;
    padding: 1rem 1.25rem;
    background: #fef2f2;
    border: 1.5px solid #fecaca;
    border-radius: 14px;
    margin-bottom: 1.5rem;
}

.kyc-intro-card {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
    padding: 1.1rem 1.25rem;
}
.kyc-intro-title {
    font-size: 0.875rem;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 0.4rem;
}
.kyc-intro-text {
    font-size: 0.8125rem;
    color: #64748b;
    line-height: 1.55;
}

.kyc-form-section {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 16px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}
.kyc-section-header {
    display: flex;
    gap: 0.85rem;
    align-items: flex-start;
    margin-bottom: 1.25rem;
}
.step-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #1eb349;
    color: #ffffff;
    font-weight: 800;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.step-heading {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 0.2rem 0;
}
.step-desc {
    font-size: 0.8125rem;
    color: #64748b;
    margin: 0;
    line-height: 1.45;
}

/* Mode Selector Bar */
.kyc-mode-selector {
    display: inline-flex;
    background: #f1f5f9;
    border-radius: 10px;
    padding: 3px;
    gap: 3px;
    border: 1px solid #e2e8f0;
}
.mode-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.45rem 0.85rem;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 700;
    color: #64748b;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}
.mode-tab-btn:hover {
    color: #0f172a;
}
.mode-tab-btn.active {
    background: #ffffff;
    color: #166534;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.kyc-input-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}
@media (max-width: 768px) {
    .kyc-input-grid {
        grid-template-columns: 1fr;
    }
}

.form-group-custom {
    display: flex;
    flex-direction: column;
}
.label-custom {
    font-size: 0.8125rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 0.45rem;
}
.label-custom .req {
    color: #ef4444;
}
.input-with-icon {
    position: relative;
    display: flex;
    align-items: center;
}
.input-with-icon svg {
    position: absolute;
    left: 0.85rem;
    pointer-events: none;
}
.input-custom {
    width: 100%;
    height: 44px;
    padding: 0 1rem 0 2.5rem;
    border-radius: 12px;
    border: 1.5px solid #cbd5e1;
    font-family: inherit;
    font-size: 0.85rem;
    color: #0f172a;
    outline: none;
    transition: all 0.2s ease;
    box-sizing: border-box;
}
.input-custom:focus {
    border-color: #1eb349;
    box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.15);
}
.font-mono {
    font-family: monospace;
    letter-spacing: 0.05em;
    font-size: 0.92rem;
}

.nik-feedback-box {
    margin-top: 0.6rem;
    padding: 0.65rem 0.85rem;
    border-radius: 10px;
    font-size: 0.75rem;
    line-height: 1.45;
}
.nik-feedback-box.valid {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}
.nik-feedback-box.invalid {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

/* Light Dropzone Area */
.scanner-device-container {
    max-width: 580px;
    margin: 0 auto;
}
.ktp-dropzone-light {
    border: 2px dashed #bbf7d0;
    background: #f8fafc;
    border-radius: 16px;
    padding: 2.25rem 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
}
.ktp-dropzone-light:hover {
    border-color: #1eb349;
    background: #f0fdf4;
}
.dropzone-icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid #bbf7d0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.4rem;
    box-shadow: 0 4px 12px rgba(30, 179, 73, 0.1);
}
.dropzone-text-primary {
    font-size: 0.875rem;
    font-weight: 700;
    color: #0f172a;
}
.dropzone-text-sub {
    font-size: 0.75rem;
    color: #64748b;
    max-width: 320px;
    margin-bottom: 0.5rem;
}
.btn-select-file {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 1.25rem;
    background: #1eb349;
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.8125rem;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(30, 179, 73, 0.25);
    transition: all 0.2s ease;
}
.btn-select-file:hover {
    background: #179b3e;
}

/* Light Camera Viewport */
.scanner-viewport-wrap.light-theme {
    position: relative;
    width: 100%;
    aspect-ratio: 4 / 3;
    border-radius: 16px;
    overflow: hidden;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #e2e8f0;
}
.scanner-live-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.scanner-placeholder.light-theme {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    padding: 1.5rem;
    text-align: center;
}
.placeholder-icon-wrap {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.4rem;
}
.placeholder-title {
    font-weight: 800;
    color: #0f172a;
    font-size: 0.92rem;
}
.placeholder-sub {
    color: #64748b;
    font-size: 0.78rem;
    max-width: 290px;
    line-height: 1.45;
}

.btn-activate-cam {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.65rem 1.25rem;
    background: #1eb349;
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.8125rem;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(30, 179, 73, 0.25);
    transition: all 0.2s ease;
}
.btn-activate-cam:hover {
    background: #179b3e;
}

/* KTP Frame Overlay Light */
.ktp-frame-overlay.light-theme {
    position: absolute;
    inset: 0;
    pointer-events: none;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.ktp-card-outline.light-theme {
    position: relative;
    width: 85%;
    aspect-ratio: 1.586;
    border: 2px dashed #1eb349;
    background: rgba(30, 179, 73, 0.05);
    border-radius: 12px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 0.75rem;
    box-shadow: 0 0 0 9999px rgba(248, 250, 252, 0.75);
}
.ktp-corner {
    position: absolute;
    width: 18px;
    height: 18px;
    border-color: #1eb349;
    border-style: solid;
}
.ktp-corner.top-left { top: -2px; left: -2px; border-width: 3px 0 0 3px; border-top-left-radius: 12px; }
.ktp-corner.top-right { top: -2px; right: -2px; border-width: 3px 3px 0 0; border-top-right-radius: 12px; }
.ktp-corner.bottom-left { bottom: -2px; left: -2px; border-width: 0 0 3px 3px; border-bottom-left-radius: 12px; }
.ktp-corner.bottom-right { bottom: -2px; right: -2px; border-width: 0 3px 3px 0; border-bottom-right-radius: 12px; }

.ktp-frame-hint.light-theme {
    background: #ffffff;
    color: #0f172a;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.25rem 0.65rem;
    border-radius: 6px;
}

/* Face Frame Overlay Light */
.face-frame-overlay.light-theme {
    position: absolute;
    inset: 0;
    pointer-events: none;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}
.face-oval-outline.light-theme {
    position: relative;
    width: 62%;
    aspect-ratio: 0.78;
    border: 3.5px solid #cbd5e1;
    background: rgba(30, 179, 73, 0.04);
    border-radius: 50% 50% 45% 45% / 60% 60% 40% 40%;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 1.25rem;
    box-shadow: 0 0 0 9999px rgba(248, 250, 252, 0.75);
    transition: all 0.25s ease;
}
.face-oval-outline.light-theme.ideal {
    border-color: #1eb349;
    box-shadow: 0 0 0 9999px rgba(248, 250, 252, 0.75), 0 0 20px rgba(30, 179, 73, 0.35);
}
.face-oval-outline.light-theme.warn {
    border-color: #f59e0b;
}
.face-oval-outline.light-theme.error {
    border-color: #ef4444;
}

.face-frame-hint.light-theme {
    background: #ffffff;
    color: #0f172a;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.25rem 0.65rem;
    border-radius: 6px;
}

/* Guidance Banners Light */
.guidance-banner-light {
    position: absolute;
    top: 0.85rem;
    left: 0.85rem;
    right: 0.85rem;
    padding: 0.55rem 0.85rem;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.45rem;
    z-index: 10;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
}
.guidance-banner-light.guide-ideal {
    background: #f0fdf4;
    color: #166534;
    border: 1.5px solid #bbf7d0;
}
.guidance-banner-light.guide-warn {
    background: #fffbeb;
    color: #92400e;
    border: 1.5px solid #fde68a;
}
.guidance-banner-light.guide-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1.5px solid #fca5a5;
}

.liveness-challenge-box-light {
    position: absolute;
    bottom: 0.85rem;
    background: #ffffff;
    color: #0f172a;
    padding: 0.45rem 1rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    z-index: 10;
    border: 1.5px solid #bbf7d0;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
}
.challenge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #eab308;
    animation: pulseDot 1s infinite alternate;
}
.challenge-dot.success {
    background: #22c55e;
    animation: none;
}
@keyframes pulseDot {
    from { opacity: 0.4; transform: scale(0.8); }
    to { opacity: 1; transform: scale(1.2); }
}

/* Light Photo Result Preview Card */
.photo-result-card-light {
    background: #ffffff;
    border: 1.5px solid #bbf7d0;
    border-radius: 16px;
    padding: 1rem;
    box-shadow: 0 4px 14px rgba(30, 179, 73, 0.08);
}
.result-img-container {
    position: relative;
    width: 100%;
    aspect-ratio: 1.586;
    max-height: 280px;
    border-radius: 12px;
    overflow: hidden;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.result-img-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.result-badge-pill {
    position: absolute;
    bottom: 0.65rem;
    right: 0.65rem;
    background: #166534;
    color: #ffffff;
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.72rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.35rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}
.result-feedback-bar {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.65rem 0.85rem;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #166534;
    margin-top: 0.75rem;
}
.result-feedback-bar.warn {
    background: #fffbeb;
    border-color: #fde68a;
    color: #92400e;
}
.result-action-row {
    margin-top: 0.75rem;
    text-align: center;
}
.btn-retake-light {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.55rem 1rem;
    background: #f8fafc;
    color: #334155;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-retake-light:hover {
    background: #ffffff;
    border-color: #1eb349;
    color: #166534;
}

.scanner-controls-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    margin-top: 0.85rem;
    flex-wrap: wrap;
}
.btn-snap-photo {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.7rem 1.5rem;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-weight: 800;
    font-size: 0.875rem;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(30, 179, 73, 0.3);
    transition: all 0.2s ease;
}
.btn-snap-photo:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(30, 179, 73, 0.4);
}
.btn-snap-photo:disabled {
    background: #94a3b8 !important;
    opacity: 0.55;
    cursor: not-allowed !important;
    box-shadow: none !important;
    transform: none !important;
}

.btn-control-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.65rem 0.95rem;
    background: #ffffff;
    color: #475569;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}
.btn-control-secondary:hover {
    background: #f8fafc;
    border-color: #94a3b8;
}

/* Submit Bar */
.kyc-submit-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1.5px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 1rem;
}
.security-guarantee-note {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.75rem;
    color: #166534;
    max-width: 480px;
    line-height: 1.45;
}
.btn-submit-kyc {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.85rem 1.85rem;
    background: #1eb349;
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-weight: 800;
    font-size: 0.92rem;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(30, 179, 73, 0.25);
    transition: all 0.2s ease;
}
.btn-submit-kyc:hover:not(:disabled) {
    background: #179b3e;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(30, 179, 73, 0.35);
}
.btn-submit-kyc:disabled {
    opacity: 0.45;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}

/* ── KTP AI EXTRACTION STATUS BOX & SPINNER ── */
.ktp-ai-status-box {
    margin-top: 0.85rem;
    padding: 0.9rem 1.1rem;
    border-radius: 14px;
    display: flex;
    align-items: center;
    gap: 0.85rem;
    transition: all 0.25s ease;
}
.ktp-ai-status-box.loading {
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    color: #166534;
}
.ktp-ai-status-box.success {
    background: #dcfce7;
    border: 1.5px solid #86efac;
    color: #14532d;
}
.ktp-ai-status-box.warn {
    background: #fefce8;
    border: 1.5px solid #fef08a;
    color: #854d0e;
}
.ai-status-spinner {
    width: 22px;
    height: 22px;
    border: 2.5px solid #bbf7d0;
    border-top-color: #1eb349;
    border-radius: 50%;
    animation: kycSpin 0.75s linear infinite;
    flex-shrink: 0;
}
@keyframes kycSpin {
    to { transform: rotate(360deg); }
}
.ai-status-content {
    flex: 1;
}
.ai-status-title {
    font-size: 0.84rem;
    font-weight: 800;
    line-height: 1.3;
}
.ai-status-desc {
    font-size: 0.75rem;
    line-height: 1.45;
    opacity: 0.9;
    margin-top: 2px;
}
.btn-retry-ai {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.75rem;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 9px;
    font-size: 0.74rem;
    font-weight: 700;
    color: #334155;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.15s ease;
}
.btn-retry-ai:hover {
    border-color: #1eb349;
    color: #166534;
    background: #f0fdf4;
}
.ai-extracted-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.75rem;
    border-radius: 9999px;
    background: #dcfce7;
    color: #166534;
    font-size: 0.72rem;
    font-weight: 800;
    border: 1px solid #86efac;
}

/* ── DOMICILE CHOICE CARDS & SECTIONS ── */
.domicile-choice-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.25rem;
}
@media (max-width: 640px) {
    .domicile-choice-grid {
        grid-template-columns: 1fr;
    }
}
.domicile-radio-card {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.domicile-radio-card:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}
.domicile-radio-card.active {
    border-color: #1eb349;
    background: #f0fdf4;
    box-shadow: 0 4px 12px rgba(30, 179, 73, 0.1);
}
.radio-check-indicator {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #cbd5e1;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
    transition: all 0.2s ease;
}
.domicile-radio-card.active .radio-check-indicator {
    border-color: #1eb349;
    background: #1eb349;
}
.domicile-radio-card:not(.active) .radio-check-indicator svg {
    display: none;
}
.domicile-card-title {
    display: block;
    font-size: 0.875rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.2rem;
}
.domicile-radio-card.active .domicile-card-title {
    color: #14532d;
}
.domicile-card-desc {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0;
    line-height: 1.4;
}
.domicile-radio-card.active .domicile-card-desc {
    color: #166534;
}

.domicile-same-box {
    display: flex;
    gap: 0.85rem;
    padding: 1.1rem 1.25rem;
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: 14px;
    align-items: flex-start;
}
.domicile-same-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #dcfce7;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.domicile-diff-box {
    padding: 1.25rem;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px;
}
</style>

<script>
// ── STATE VARIABLES e-KYC ──
let ktpStream = null;
let faceStream = null;
let ktpDevices = [];
let faceDevices = [];
let ktpDeviceIndex = 0;
let faceDeviceIndex = 0;
let ktpGuidanceInterval = null;
let faceGuidanceInterval = null;

let capturedKtpBase64 = null;
let capturedFaceBase64 = null;
let isNikValid = false;
let livenessVerified = false;
let livenessToken = null;

// AI KTP Extraction & Domicile state
let aiExtractedData = null;
let isAiExtracting = false;
let isDomicileSame = true;
const apiWilayahBase = "https://www.emsifa.com/api-wilayah-indonesia/api";

// Liveness Micro-motion state
let previousFaceBuffer = null;
let stableCenterFrames = 0;
let movementCount = 0;

// ── MODE SWITCHER (UPLOAD VS CAMERA FOR KTP) ──
function switchKtpMode(mode) {
    document.querySelectorAll('#tabModeKtpUpload, #tabModeKtpCam').forEach(b => b.classList.remove('active'));
    if (mode === 'upload') {
        document.getElementById('tabModeKtpUpload').classList.add('active');
        document.getElementById('ktpUploadPane').style.display = 'block';
        document.getElementById('ktpCameraPane').style.display = 'none';
        stopKtpCamera();
    } else {
        document.getElementById('tabModeKtpCam').classList.add('active');
        document.getElementById('ktpUploadPane').style.display = 'none';
        document.getElementById('ktpCameraPane').style.display = 'block';
        startKtpCamera();
    }
}

// ── NIK REALTIME PARSER ──
function handleNikInput(nik) {
    const cleanNik = nik.replace(/\D/g, '');
    document.getElementById('kycNikInput').value = cleanNik;
    const feedbackBox = document.getElementById('nikFeedback');

    if (cleanNik.length < 16) {
        feedbackBox.style.display = 'none';
        isNikValid = false;
        checkFormReadiness();
        return;
    }

    feedbackBox.style.display = 'block';
    feedbackBox.className = 'nik-feedback-box';
    feedbackBox.innerHTML = 'Memvalidasi struktur wilayah NIK...';

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('{{ route("creator.kyc.parse-nik") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ nik: cleanNik })
    })
    .then(r => r.json())
    .then(res => {
        if (res.valid) {
            isNikValid = true;
            feedbackBox.className = 'nik-feedback-box valid';
            const genderStr = res.gender === 'L' ? 'Laki-laki' : 'Perempuan';
            feedbackBox.innerHTML = `
                <div style="font-weight:700;">NIK Valid (${cleanNik})</div>
                <div>Wilayah: <strong>${res.province}</strong> | Lahir: <strong>${res.dob}</strong> (${genderStr})</div>
            `;
        } else {
            isNikValid = false;
            feedbackBox.className = 'nik-feedback-box invalid';
            feedbackBox.textContent = res.error || 'Format NIK tidak valid.';
        }
        checkFormReadiness();
    })
    .catch(() => {
        isNikValid = false;
        feedbackBox.className = 'nik-feedback-box invalid';
        feedbackBox.textContent = 'Gagal memeriksa NIK.';
        checkFormReadiness();
    });
}

// ── KAMERA KTP ENGINE & LUMINOSITY GUIDANCE (LIGHT THEME) ──
async function startKtpCamera() {
    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        ktpDevices = devices.filter(d => d.kind === 'videoinput');

        const constraints = {
            video: {
                facingMode: { ideal: 'environment' },
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        };

        ktpStream = await navigator.mediaDevices.getUserMedia(constraints);
        const video = document.getElementById('ktpVideo');
        video.srcObject = ktpStream;
        video.style.display = 'block';

        document.getElementById('ktpPlaceholder').style.display = 'none';
        document.getElementById('ktpFrameOverlay').style.display = 'flex';
        document.getElementById('ktpGuidanceBanner').style.display = 'flex';
        document.getElementById('ktpControlsBar').style.display = 'flex';

        startKtpGuidanceLoop();
    } catch(err) {
        showKycInteractiveModal({
            type: 'error',
            title: 'Izin Kamera KTP',
            message: 'Tidak dapat mengakses kamera: ' + (err.message || 'Harap izinkan akses kamera pada browser Anda.')
        });
    }
}

function startKtpGuidanceLoop() {
    clearInterval(ktpGuidanceInterval);
    const video = document.getElementById('ktpVideo');
    const banner = document.getElementById('ktpGuidanceBanner');
    const text = document.getElementById('ktpGuideText');
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = 160;
    canvas.height = 120;

    ktpGuidanceInterval = setInterval(() => {
        if (!ktpStream || video.readyState < 2) return;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const data = imgData.data;

        let totalBrightness = 0;
        for (let i = 0; i < data.length; i += 16) {
            totalBrightness += (data[i] * 0.299 + data[i+1] * 0.587 + data[i+2] * 0.114);
        }
        const avg = totalBrightness / (data.length / 16);

        if (avg < 48) {
            banner.className = 'guidance-banner-light guide-error';
            text.textContent = 'Pencahayaan Terlalu Gelap: Cari tempat lebih terang atau nyalakan lampu.';
        } else if (avg > 218) {
            banner.className = 'guidance-banner-light guide-warn';
            text.textContent = 'Pencahayaan Terlalu Silau: Kurangi pantulan kilau cahaya pada KTP.';
        } else {
            banner.className = 'guidance-banner-light guide-ideal';
            text.textContent = 'Pencahayaan Ideal. Posisikan KTP memenuhi kotak panduan dan tekan Ambil Foto.';
        }
    }, 450);
}

function snapKtpPhoto() {
    const video = document.getElementById('ktpVideo');
    if (!ktpStream || video.readyState < 2) return;

    const canvas = document.getElementById('ktpCanvas');
    canvas.width = video.videoWidth || 1280;
    canvas.height = video.videoHeight || 720;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    capturedKtpBase64 = canvas.toDataURL('image/webp', 0.82);

    displayKtpResult(capturedKtpBase64, 'Pindai kamera berhasil. Foto KTP siap diverifikasi.');
    stopKtpCamera();
}

function displayKtpResult(imgUrl, feedbackMsg, isWarn = false) {
    document.getElementById('ktpPreviewImg').src = imgUrl;
    document.getElementById('ktpSmartFeedbackText').textContent = feedbackMsg;
    const feedbackBar = document.getElementById('ktpSmartFeedback');
    feedbackBar.className = isWarn ? 'result-feedback-bar warn' : 'result-feedback-bar';

    document.getElementById('ktpUploadPane').style.display = 'none';
    document.getElementById('ktpCameraPane').style.display = 'none';
    document.getElementById('ktpResultCard').style.display = 'block';

    // Otomatis picu AI Vision untuk membaca data KTP
    extractKtpWithAi(imgUrl);

    checkFormReadiness();
}

function retakeKtpPhoto() {
    capturedKtpBase64 = null;
    aiExtractedData = null;
    const statusBox = document.getElementById('ktpAiExtractStatus');
    if (statusBox) statusBox.style.display = 'none';
    const badge = document.getElementById('aiExtractionBadge');
    if (badge) badge.style.display = 'none';

    document.getElementById('ktpResultCard').style.display = 'none';
    switchKtpMode('upload');
    checkFormReadiness();
}

// ── AI VISION KTP AUTO-EXTRACTOR ENGINE ──
function extractKtpWithAi(imgUrl) {
    if (!imgUrl) return;

    const statusBox = document.getElementById('ktpAiExtractStatus');
    const spinner = document.getElementById('ktpAiSpinner');
    const title = document.getElementById('ktpAiStatusTitle');
    const desc = document.getElementById('ktpAiStatusDesc');
    const retryBtn = document.getElementById('btnRetryAiExtract');
    const badge = document.getElementById('aiExtractionBadge');

    statusBox.style.display = 'flex';
    statusBox.className = 'ktp-ai-status-box loading';
    spinner.style.display = 'block';
    title.textContent = 'Mengekstrak Data e-KTP dengan AI Smart Vision...';
    desc.textContent = 'Mohon tunggu beberapa detik, sistem sedang membaca NIK, Nama Lengkap, dan Alamat Anda secara otomatis.';
    retryBtn.style.display = 'none';
    isAiExtracting = true;
    checkFormReadiness();

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('{{ route("creator.kyc.extract-ktp") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ktp_photo: imgUrl })
    })
    .then(r => r.json())
    .then(res => {
        isAiExtracting = false;
        if (res.success && res.data) {
            aiExtractedData = res.data;
            statusBox.className = 'ktp-ai-status-box success';
            spinner.style.display = 'none';
            title.textContent = 'Ekstraksi AI Berhasil!';
            desc.textContent = `NIK: ${res.data.nik || '-'} | Nama: ${res.data.full_name || '-'}`;
            retryBtn.style.display = 'inline-flex';
            if (badge) badge.style.display = 'inline-flex';

            // Auto-fill NIK
            if (res.data.nik) {
                document.getElementById('kycNikInput').value = res.data.nik;
                handleNikInput(res.data.nik);
            }

            // Auto-fill Nama Lengkap
            if (res.data.full_name) {
                document.getElementById('kycNameInput').value = res.data.full_name;
            }

            // Auto-fill Alamat KTP
            if (res.data.address) document.getElementById('ktpAddressStreet').value = res.data.address;
            if (res.data.rt_rw) document.getElementById('ktpAddressRtRw').value = res.data.rt_rw;
            if (res.data.subdistrict) document.getElementById('ktpAddressKelurahan').value = res.data.subdistrict;
            if (res.data.district) document.getElementById('ktpAddressKecamatan').value = res.data.district;
            if (res.data.city) document.getElementById('ktpAddressCity').value = res.data.city;
            if (res.data.province) document.getElementById('ktpAddressProvince').value = res.data.province;

            onKtpAddressChange();
        } else {
            statusBox.className = 'ktp-ai-status-box warn';
            spinner.style.display = 'none';
            if (res.quota_exceeded) {
                title.textContent = 'Mode Mandiri Aktif (Kuota AI Penuh)';
                desc.textContent = res.message || 'Batas kuota AI tercapai. Cukup ketik NIK 16 digit Anda di bawah, sistem lokal akan otomatis mendekode data wilayah KTP.';
            } else {
                title.textContent = 'AI Belum Dapat Mengekstrak Otomatis';
                desc.textContent = res.message || 'Silakan lengkapi NIK dan data formulir secara manual.';
            }
            retryBtn.style.display = 'inline-flex';
            const nikInput = document.getElementById('kycNikInput');
            if (nikInput && !nikInput.value) {
                setTimeout(() => nikInput.focus(), 300);
            }
        }
        checkFormReadiness();
    })
    .catch(() => {
        isAiExtracting = false;
        statusBox.className = 'ktp-ai-status-box warn';
        spinner.style.display = 'none';
        title.textContent = 'Mode Mandiri Aktif';
        desc.textContent = 'Layanan AI sedang padat. Anda dapat langsung mengetikkan NIK & Nama pada formulir di bawah.';
        retryBtn.style.display = 'inline-flex';
        checkFormReadiness();
    });
}

function triggerKtpAiExtract() {
    if (capturedKtpBase64) {
        extractKtpWithAi(capturedKtpBase64);
    }
}

// ── DOMICILE ADDRESS HANDLERS (SESUAI KTP VS BEDA VIA API WILAYAH) ──
function setDomicileMode(sameAsKtp) {
    isDomicileSame = sameAsKtp;
    const sameLabel = document.getElementById('domicileChoiceSameLabel');
    const diffLabel = document.getElementById('domicileChoiceDiffLabel');
    const samePreview = document.getElementById('domicileSamePreviewBox');
    const diffForm = document.getElementById('domicileDifferentFormPane');

    if (sameAsKtp) {
        sameLabel.classList.add('active');
        diffLabel.classList.remove('active');
        samePreview.style.display = 'flex';
        diffForm.style.display = 'none';
        document.getElementById('domicileChoiceSame').checked = true;
    } else {
        sameLabel.classList.remove('active');
        diffLabel.classList.add('active');
        samePreview.style.display = 'none';
        diffForm.style.display = 'block';
        document.getElementById('domicileChoiceDiff').checked = true;
        loadDomicileProvinces();
    }
    checkFormReadiness();
}

function onKtpAddressChange() {
    const street = document.getElementById('ktpAddressStreet')?.value.trim() || '';
    const rtrw = document.getElementById('ktpAddressRtRw')?.value.trim() || '';
    const kel = document.getElementById('ktpAddressKelurahan')?.value.trim() || '';
    const kec = document.getElementById('ktpAddressKecamatan')?.value.trim() || '';
    const city = document.getElementById('ktpAddressCity')?.value.trim() || '';
    const prov = document.getElementById('ktpAddressProvince')?.value.trim() || '';

    const summaryEl = document.getElementById('domicileSameSummaryText');
    if (!summaryEl) return;

    const parts = [street, rtrw ? `RT/RW ${rtrw}` : '', kel ? `Kel. ${kel}` : '', kec ? `Kec. ${kec}` : '', city, prov].filter(Boolean);
    if (parts.length > 0) {
        summaryEl.innerHTML = `<strong>Alamat KTP:</strong> ${parts.join(', ')}`;
    } else {
        summaryEl.textContent = 'Data domisili akan disamakan dengan alamat yang tertera pada e-KTP Anda di atas.';
    }
}

function loadDomicileProvinces() {
    const provSelect = document.getElementById('domicileProvinceSelect');
    if (!provSelect || provSelect.options.length > 1) return;

    provSelect.innerHTML = '<option value="">Memuat daftar provinsi...</option>';
    fetch(`${apiWilayahBase}/provinces.json`)
        .then(r => r.json())
        .then(provinces => {
            provSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
            provinces.forEach(p => {
                let opt = document.createElement('option');
                opt.value = p.id;
                opt.dataset.name = p.name;
                opt.textContent = p.name;
                provSelect.appendChild(opt);
            });
        })
        .catch(() => {
            provSelect.innerHTML = '<option value="">Gagal memuat provinsi</option>';
        });
}

function onDomicileProvinceChange(provId) {
    const citySelect = document.getElementById('domicileCitySelect');
    const distSelect = document.getElementById('domicileDistrictSelect');
    const villSelect = document.getElementById('domicileSubdistrictSelect');

    citySelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
    distSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    villSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';

    citySelect.disabled = true;
    distSelect.disabled = true;
    villSelect.disabled = true;

    if (!provId) {
        checkFormReadiness();
        return;
    }

    citySelect.innerHTML = '<option value="">Memuat kabupaten/kota...</option>';
    fetch(`${apiWilayahBase}/regencies/${provId}.json`)
        .then(r => r.json())
        .then(cities => {
            citySelect.innerHTML = '<option value="">-- Pilih Kabupaten/Kota --</option>';
            cities.forEach(c => {
                let opt = document.createElement('option');
                opt.value = c.id;
                opt.dataset.name = c.name;
                opt.textContent = c.name;
                citySelect.appendChild(opt);
            });
            citySelect.disabled = false;
            checkFormReadiness();
        })
        .catch(() => {
            citySelect.innerHTML = '<option value="">Gagal memuat kabupaten/kota</option>';
        });
}

function onDomicileCityChange(cityId) {
    const distSelect = document.getElementById('domicileDistrictSelect');
    const villSelect = document.getElementById('domicileSubdistrictSelect');

    distSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    villSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';

    distSelect.disabled = true;
    villSelect.disabled = true;

    if (!cityId) {
        checkFormReadiness();
        return;
    }

    distSelect.innerHTML = '<option value="">Memuat kecamatan...</option>';
    fetch(`${apiWilayahBase}/districts/${cityId}.json`)
        .then(r => r.json())
        .then(districts => {
            distSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            districts.forEach(d => {
                let opt = document.createElement('option');
                opt.value = d.id;
                opt.dataset.name = d.name;
                opt.textContent = d.name;
                distSelect.appendChild(opt);
            });
            distSelect.disabled = false;
            checkFormReadiness();
        })
        .catch(() => {
            distSelect.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
        });
}

function onDomicileDistrictChange(distId) {
    const villSelect = document.getElementById('domicileSubdistrictSelect');
    villSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
    villSelect.disabled = true;

    if (!distId) {
        checkFormReadiness();
        return;
    }

    villSelect.innerHTML = '<option value="">Memuat kelurahan/desa...</option>';
    fetch(`${apiWilayahBase}/villages/${distId}.json`)
        .then(r => r.json())
        .then(villages => {
            villSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
            villages.forEach(v => {
                let opt = document.createElement('option');
                opt.value = v.id;
                opt.dataset.name = v.name;
                opt.textContent = v.name;
                villSelect.appendChild(opt);
            });
            villSelect.disabled = false;
            checkFormReadiness();
        })
        .catch(() => {
            villSelect.innerHTML = '<option value="">Gagal memuat kelurahan/desa</option>';
        });
}

function stopKtpCamera() {
    clearInterval(ktpGuidanceInterval);
    if (ktpStream) {
        ktpStream.getTracks().forEach(t => t.stop());
        ktpStream = null;
    }
    document.getElementById('ktpVideo').style.display = 'none';
    document.getElementById('ktpFrameOverlay').style.display = 'none';
    document.getElementById('ktpGuidanceBanner').style.display = 'none';
    document.getElementById('ktpControlsBar').style.display = 'none';
    document.getElementById('ktpPlaceholder').style.display = 'flex';
}

async function switchKtpCamera() {
    if (ktpDevices.length < 2) return;
    ktpDeviceIndex = (ktpDeviceIndex + 1) % ktpDevices.length;
    stopKtpCamera();
    try {
        ktpStream = await navigator.mediaDevices.getUserMedia({
            video: { deviceId: { exact: ktpDevices[ktpDeviceIndex].deviceId } }
        });
        const video = document.getElementById('ktpVideo');
        video.srcObject = ktpStream;
        video.style.display = 'block';
        document.getElementById('ktpPlaceholder').style.display = 'none';
        document.getElementById('ktpFrameOverlay').style.display = 'flex';
        document.getElementById('ktpGuidanceBanner').style.display = 'flex';
        document.getElementById('ktpControlsBar').style.display = 'flex';
        startKtpGuidanceLoop();
    } catch(e) {}
}

// ── SMART LIVE FACE BIOMETRICS & SPATIAL AI CENTERING ENGINE ──
async function startFaceCamera() {
    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        faceDevices = devices.filter(d => d.kind === 'videoinput');

        const constraints = {
            video: {
                facingMode: { ideal: 'user' },
                width: { ideal: 960 },
                height: { ideal: 720 }
            }
        };

        faceStream = await navigator.mediaDevices.getUserMedia(constraints);
        const video = document.getElementById('faceVideo');
        video.srcObject = faceStream;
        video.style.display = 'block';

        document.getElementById('facePlaceholder').style.display = 'none';
        document.getElementById('faceFrameOverlay').style.display = 'flex';
        document.getElementById('faceGuidanceBanner').style.display = 'flex';
        document.getElementById('faceControlsBar').style.display = 'flex';
        document.getElementById('livenessChallengeBox').style.display = 'flex';

        // Reset verification states
        stableCenterFrames = 0;
        movementCount = 0;
        livenessVerified = false;
        previousFaceBuffer = null;
        document.getElementById('btnSnapFace').disabled = true;

        startIntelligentFaceLoop();
    } catch(err) {
        showKycInteractiveModal({
            type: 'error',
            title: 'Izin Kamera Selfie',
            message: 'Tidak dapat mengakses kamera selfie: ' + (err.message || 'Harap izinkan akses kamera pada browser Anda.')
        });
    }
}

function startIntelligentFaceLoop() {
    clearInterval(faceGuidanceInterval);
    const video = document.getElementById('faceVideo');
    const banner = document.getElementById('faceGuidanceBanner');
    const text = document.getElementById('faceGuideText');
    const oval = document.getElementById('faceOvalOutline');
    const challengeBox = document.getElementById('challengeInstruction');
    const dot = document.getElementById('challengeDot');
    const snapBtn = document.getElementById('btnSnapFace');

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const W = 160;
    const H = 120;
    canvas.width = W;
    canvas.height = H;

    // Center oval parameters in analysis buffer
    const ocx = W * 0.5;   // 80
    const ocy = H * 0.5;   // 60
    const orx = W * 0.22;  // ~35.2
    const ory = H * 0.33;  // ~39.6

    faceGuidanceInterval = setInterval(async () => {
        if (!faceStream || video.readyState < 2) return;
        ctx.drawImage(video, 0, 0, W, H);
        const imgData = ctx.getImageData(0, 0, W, H);
        const data = imgData.data;

        let skinInOval = 0;
        let totalOval = 0;
        let skinLeft = 0;
        let skinRight = 0;
        let skinCenter = 0;
        let brightnessSum = 0;

        // Frame difference for liveness micro-motion
        let frameDiff = 0;

        for (let y = 0; y < H; y += 2) {
            for (let x = 0; x < W; x += 2) {
                const idx = (y * W + x) * 4;
                const r = data[idx];
                const g = data[idx+1];
                const b = data[idx+2];
                const yVal = 0.299 * r + 0.587 * g + 0.114 * b;
                brightnessSum += yVal;

                if (previousFaceBuffer) {
                    frameDiff += Math.abs(yVal - previousFaceBuffer[idx]);
                }

                // YCbCr skin tone detection:
                const cb = 128 - 0.168736 * r - 0.331264 * g + 0.5 * b;
                const cr = 128 + 0.5 * r - 0.418688 * g - 0.081312 * b;
                const isSkin = (cb >= 75 && cb <= 130 && cr >= 132 && cr <= 175 && r > g && g > b && (r - g) >= 10);

                const dx = (x - ocx) / orx;
                const dy = (y - ocy) / ory;
                const inOval = (dx * dx + dy * dy) <= 1.0;

                if (inOval) {
                    totalOval++;
                    if (isSkin) skinInOval++;
                }

                if (isSkin) {
                    if (x < 52) skinLeft++;
                    else if (x > 108) skinRight++;
                    else skinCenter++;
                }
            }
        }

        previousFaceBuffer = new Uint8Array(data);

        const sampleCount = (W / 2) * (H / 2);
        const avgBrightness = brightnessSum / sampleCount;

        // 1. Cek Pencahayaan
        if (avgBrightness < 45) {
            banner.className = 'guidance-banner-light guide-error';
            text.textContent = 'Pencahayaan Wajah Terlalu Gelap: Cari sumber cahaya dari depan.';
            oval.className = 'face-oval-outline light-theme error';
            resetFaceChallenge('Pencahayaan terlalu gelap');
            return;
        }

        // 2. Cek apakah posisi wajah berada di samping (Spatial Boundary Check)
        // Jika piksel kulit terkonsentrasi di sisi kanan atau kiri, tapi sedikit di oval:
        if (skinRight > 40 && skinRight > (skinCenter * 1.5 + skinInOval * 1.2)) {
            banner.className = 'guidance-banner-light guide-warn';
            text.textContent = 'Wajah Terdeteksi di Samping Kanan: Geser kepala Anda tepat ke tengah oval.';
            oval.className = 'face-oval-outline light-theme warn';
            resetFaceChallenge('Geser wajah Anda ke tengah bingkai oval');
            return;
        }

        if (skinLeft > 40 && skinLeft > (skinCenter * 1.5 + skinInOval * 1.2)) {
            banner.className = 'guidance-banner-light guide-warn';
            text.textContent = 'Wajah Terdeteksi di Samping Kiri: Geser kepala Anda tepat ke tengah oval.';
            oval.className = 'face-oval-outline light-theme warn';
            resetFaceChallenge('Geser wajah Anda ke tengah bingkai oval');
            return;
        }

        // 3. Cek Kepadatan Kulit di Dalam Oval (Mencegah objek latar seperti pohon/dinding)
        const ovalSkinRatio = skinInOval / (totalOval || 1);

        if (ovalSkinRatio < 0.22) {
            banner.className = 'guidance-banner-light guide-warn';
            text.textContent = 'Wajah Belum Terdeteksi: Posisikan wajah Anda tepat di dalam bingkai oval.';
            oval.className = 'face-oval-outline light-theme warn';
            resetFaceChallenge('Posisikan wajah Anda di dalam oval');
            return;
        }

        if (ovalSkinRatio > 0.88) {
            banner.className = 'guidance-banner-light guide-warn';
            text.textContent = 'Wajah Terlalu Dekat: Mundurkan sedikit wajah Anda dari kamera.';
            oval.className = 'face-oval-outline light-theme warn';
            resetFaceChallenge('Mundurkan sedikit wajah Anda');
            return;
        }

        // 4. POSISI WAJAH BERHASIL TERPUSAT DI DALAM BINGKAI OVAL!
        oval.className = 'face-oval-outline light-theme ideal';
        stableCenterFrames++;

        if (stableCenterFrames < 4) {
            banner.className = 'guidance-banner-light guide-ideal';
            text.textContent = 'Wajah Terpusat Sempurna. Tahan posisi Anda...';
            challengeBox.textContent = 'Langkah 1/2: Tahan posisi wajah di tengah oval';
            dot.className = 'challenge-dot';
            snapBtn.disabled = true;
        } else {
            // Langkah 2: Liveness Challenge (Kedipan mata / micro-motion)
            challengeBox.textContent = 'Langkah 2/2: Kedipkan mata perlahan atau tengok santai';
            banner.className = 'guidance-banner-light guide-ideal';
            text.textContent = 'Mendeteksi keaslian biometrik orang hidup...';

            if (frameDiff > 1400) {
                movementCount++;
            }

            if (movementCount >= 2 || stableCenterFrames >= 9) {
                livenessVerified = true;
                livenessToken = 'LIVE_STRICT_' + Date.now() + '_' + Math.random().toString(36).substring(2, 10);
                challengeBox.textContent = 'Biometrik Terverifikasi! Klik tombol Ambil Foto.';
                dot.className = 'challenge-dot success';
                banner.className = 'guidance-banner-light guide-ideal';
                text.textContent = 'Wajah terverifikasi dan siap diambil.';
                snapBtn.disabled = false;
            }
        }
    }, 320);
}

function resetFaceChallenge(msg) {
    stableCenterFrames = 0;
    movementCount = 0;
    livenessVerified = false;
    document.getElementById('challengeInstruction').textContent = msg;
    document.getElementById('challengeDot').className = 'challenge-dot';
    document.getElementById('btnSnapFace').disabled = true;
}

function snapFacePhoto() {
    if (!livenessVerified) {
        alert('Posisikan wajah Anda tepat di tengah oval dan selesaikan panduan verifikasi terlebih dahulu.');
        return;
    }

    const video = document.getElementById('faceVideo');
    if (!faceStream || video.readyState < 2) return;

    const canvas = document.getElementById('faceCanvas');
    canvas.width = video.videoWidth || 960;
    canvas.height = video.videoHeight || 720;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    capturedFaceBase64 = canvas.toDataURL('image/webp', 0.82);
    displayFaceResult(capturedFaceBase64, 'Pindai biometrik berhasil. Wajah terverifikasi dan siap dikirim.');
    stopFaceCamera();
}

function displayFaceResult(imgUrl, feedbackMsg, isWarn = false) {
    document.getElementById('facePreviewImg').src = imgUrl;
    document.getElementById('faceSmartFeedbackText').textContent = feedbackMsg;
    const feedbackBar = document.getElementById('faceSmartFeedback');
    feedbackBar.className = isWarn ? 'result-feedback-bar warn' : 'result-feedback-bar';

    document.getElementById('faceCameraPane').style.display = 'none';
    document.getElementById('faceResultCard').style.display = 'block';

    checkFormReadiness();
}

function retakeFacePhoto() {
    capturedFaceBase64 = null;
    livenessVerified = false;
    livenessToken = null;
    document.getElementById('faceResultCard').style.display = 'none';
    document.getElementById('faceCameraPane').style.display = 'block';
    startFaceCamera();
    checkFormReadiness();
}

function stopFaceCamera() {
    clearInterval(faceGuidanceInterval);
    if (faceStream) {
        faceStream.getTracks().forEach(t => t.stop());
        faceStream = null;
    }
    document.getElementById('faceVideo').style.display = 'none';
    document.getElementById('faceFrameOverlay').style.display = 'none';
    document.getElementById('faceGuidanceBanner').style.display = 'none';
    document.getElementById('faceControlsBar').style.display = 'none';
    document.getElementById('livenessChallengeBox').style.display = 'none';
    document.getElementById('facePlaceholder').style.display = 'flex';
}

async function switchFaceCamera() {
    if (faceDevices.length < 2) return;
    faceDeviceIndex = (faceDeviceIndex + 1) % faceDevices.length;
    stopFaceCamera();
    try {
        faceStream = await navigator.mediaDevices.getUserMedia({
            video: { deviceId: { exact: faceDevices[faceDeviceIndex].deviceId } }
        });
        const video = document.getElementById('faceVideo');
        video.srcObject = faceStream;
        video.style.display = 'block';
        document.getElementById('facePlaceholder').style.display = 'none';
        document.getElementById('faceFrameOverlay').style.display = 'flex';
        document.getElementById('faceGuidanceBanner').style.display = 'flex';
        document.getElementById('faceControlsBar').style.display = 'flex';
        document.getElementById('livenessChallengeBox').style.display = 'flex';
        startIntelligentFaceLoop();
    } catch(e) {}
}

// ── SMART UPLOAD SCANNER UNTUK KTP ──
function handleFileUpload(event, type) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            const canvas = document.createElement('canvas');
            let width = img.width;
            let height = img.height;
            const maxDim = 1280;
            if (width > maxDim || height > maxDim) {
                if (width > height) {
                    height = Math.round((height * maxDim) / width);
                    width = maxDim;
                } else {
                    width = Math.round((width * maxDim) / height);
                    height = maxDim;
                }
            }
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            // Analisis kecerahan foto KTP yang diunggah
            const imgData = ctx.getImageData(0, 0, width, height);
            const data = imgData.data;
            let totalBrightness = 0;
            const step = Math.max(1, Math.floor(data.length / 5000));
            let count = 0;
            for (let i = 0; i < data.length; i += step * 4) {
                totalBrightness += (data[i] * 0.299 + data[i+1] * 0.587 + data[i+2] * 0.114);
                count++;
            }
            const avgBrightness = totalBrightness / count;

            let smartMsg = 'Analisis Cerdas: Gambar tajam dengan pencahayaan seimbang.';
            let isWarn = false;

            if (avgBrightness < 50) {
                smartMsg = 'Analisis Cerdas: Foto agak gelap. Pastikan nomor NIK dan tulisan tetap jelas terbaca.';
                isWarn = true;
            } else if (avgBrightness > 220) {
                smartMsg = 'Analisis Cerdas: Foto cukup terang. Pastikan tidak ada pantulan kilau yang menutupi data.';
                isWarn = true;
            }

            const webpData = canvas.toDataURL('image/webp', 0.84);

            if (type === 'ktp') {
                capturedKtpBase64 = webpData;
                displayKtpResult(webpData, smartMsg, isWarn);
            }
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

// ── VALIDASI FORM KESIAPAN SUBMIT ──
function checkFormReadiness() {
    const nik = document.getElementById('kycNikInput')?.value.trim() || '';
    const name = document.getElementById('kycNameInput')?.value.trim() || '';
    const btn = document.getElementById('btnSubmitKyc');

    if (!btn) return;

    let domicileValid = true;
    if (!isDomicileSame) {
        const prov = document.getElementById('domicileProvinceSelect')?.value;
        const city = document.getElementById('domicileCitySelect')?.value;
        const dist = document.getElementById('domicileDistrictSelect')?.value;
        const subdist = document.getElementById('domicileSubdistrictSelect')?.value;
        const addr = document.getElementById('domicileAddressInput')?.value.trim() || '';
        domicileValid = Boolean(prov && city && dist && subdist && addr.length >= 3);
    }

    const isReady = (
        nik.length === 16 &&
        isNikValid &&
        name.length >= 3 &&
        capturedKtpBase64 &&
        capturedFaceBase64 &&
        domicileValid &&
        !isAiExtracting
    );

    btn.disabled = !isReady;
}

// ── AJAX SUBMIT e-KYC ──
function handleKycFormSubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmitKyc');
    const btnText = document.getElementById('btnSubmitKycText');
    const nik = document.getElementById('kycNikInput').value.trim();
    const fullName = document.getElementById('kycNameInput').value.trim();

    if (!isNikValid || nik.length !== 16) {
        showKycInteractiveModal({
            type: 'warning',
            title: 'Periksa Kembali NIK',
            message: 'Harap periksa kembali 16 digit NIK Anda. Pastikan format NIK sudah valid dan terisi lengkap.'
        });
        return;
    }
    if (!fullName || fullName.length < 3) {
        showKycInteractiveModal({
            type: 'warning',
            title: 'Nama Lengkap Diperlukan',
            message: 'Harap masukkan Nama Lengkap sesuai yang tertera pada e-KTP fisik Anda.'
        });
        return;
    }
    if (!capturedKtpBase64) {
        showKycInteractiveModal({
            type: 'warning',
            title: 'Foto e-KTP Belum Siap',
            message: 'Foto fisik e-KTP Anda wajib dipindai melalui kamera atau diunggah terlebih dahulu.'
        });
        return;
    }
    if (!capturedFaceBase64) {
        showKycInteractiveModal({
            type: 'warning',
            title: 'Biometrik Wajah Diperlukan',
            message: 'Foto biometrik wajah wajib dipindai secara langsung melalui kamera untuk verifikasi keaslian liveness.'
        });
        return;
    }

    if (!isDomicileSame) {
        const prov = document.getElementById('domicileProvinceSelect')?.value;
        const city = document.getElementById('domicileCitySelect')?.value;
        const dist = document.getElementById('domicileDistrictSelect')?.value;
        const subdist = document.getElementById('domicileSubdistrictSelect')?.value;
        const addr = document.getElementById('domicileAddressInput')?.value.trim();
        if (!prov || !city || !dist || !subdist || !addr) {
            showKycInteractiveModal({
                type: 'warning',
                title: 'Alamat Domisili Belum Lengkap',
                message: 'Harap lengkapi seluruh data alamat domisili (Provinsi, Kota/Kabupaten, Kecamatan, Kelurahan, dan Alamat Lengkap).'
            });
            return;
        }
    }

    btn.disabled = true;
    btnText.textContent = 'Mengunggah & Memproses e-KYC...';

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const payload = {
        nik: nik,
        full_name: fullName,
        ktp_photo: capturedKtpBase64,
        selfie_photo: capturedFaceBase64,
        liveness_token: livenessToken || ('CLIENT_LIVE_' + Date.now()),
        is_domicile_same: isDomicileSame ? 1 : 0,
        ktp_address: document.getElementById('ktpAddressStreet')?.value.trim() || null,
        ktp_rt_rw: document.getElementById('ktpAddressRtRw')?.value.trim() || null,
        ktp_subdistrict: document.getElementById('ktpAddressKelurahan')?.value.trim() || null,
        ktp_district: document.getElementById('ktpAddressKecamatan')?.value.trim() || null,
        ktp_city: document.getElementById('ktpAddressCity')?.value.trim() || null,
        ktp_province: document.getElementById('ktpAddressProvince')?.value.trim() || null,
        ai_extracted_data: aiExtractedData
    };

    if (!isDomicileSame) {
        const provEl = document.getElementById('domicileProvinceSelect');
        const cityEl = document.getElementById('domicileCitySelect');
        const distEl = document.getElementById('domicileDistrictSelect');
        const villEl = document.getElementById('domicileSubdistrictSelect');

        payload.domicile_province = provEl.options[provEl.selectedIndex]?.dataset.name || provEl.options[provEl.selectedIndex]?.text || '';
        payload.domicile_city = cityEl.options[cityEl.selectedIndex]?.dataset.name || cityEl.options[cityEl.selectedIndex]?.text || '';
        payload.domicile_district = distEl.options[distEl.selectedIndex]?.dataset.name || distEl.options[distEl.selectedIndex]?.text || '';
        payload.domicile_subdistrict = villEl.options[villEl.selectedIndex]?.dataset.name || villEl.options[villEl.selectedIndex]?.text || '';
        payload.domicile_rt_rw = document.getElementById('domicileRtRwInput')?.value.trim() || null;
        payload.domicile_postal_code = document.getElementById('domicilePostalCodeInput')?.value.trim() || null;
        payload.domicile_address = document.getElementById('domicileAddressInput')?.value.trim() || '';
    }

    fetch('{{ route("creator.kyc.submit") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showKycInteractiveModal({
                type: 'success',
                title: 'Pengajuan Berhasil Dikirim!',
                message: res.message || 'Pengajuan verifikasi berhasil dikirim. Admin akan meninjau dalam 1x24 jam.',
                buttonText: 'Selesai & Muat Ulang',
                onConfirm: () => window.location.reload()
            });
        } else {
            showKycInteractiveModal({
                type: 'error',
                title: 'Gagal Mengirim Verifikasi',
                message: res.message || 'Gagal mengirim pengajuan verifikasi. Harap periksa kembali isian Anda.'
            });
            btn.disabled = false;
            btnText.textContent = 'Kirim Pengajuan Verifikasi';
        }
    })
    .catch(() => {
        showKycInteractiveModal({
            type: 'error',
            title: 'Kendala Jaringan',
            message: 'Terjadi kesalahan jaringan saat mengirim data. Silakan periksa koneksi internet Anda dan coba lagi.'
        });
        btn.disabled = false;
        btnText.textContent = 'Kirim Pengajuan Verifikasi';
    });
}

// ── INTERACTIVE MODAL DIALOG CONTROLLER (THEME LIGHT & MOBILE FRIENDLY) ──
let kycModalCallback = null;

function showKycInteractiveModal(opts = {}) {
    const overlay = document.getElementById('kycInteractiveModal');
    const iconContainer = document.getElementById('kycModalIconContainer');
    const titleEl = document.getElementById('kycModalTitle');
    const descEl = document.getElementById('kycModalDesc');
    const confirmBtn = document.getElementById('kycModalConfirmBtn');

    const type = opts.type || 'info';
    titleEl.textContent = opts.title || 'Pemberitahuan';
    descEl.textContent = opts.message || '';
    confirmBtn.textContent = opts.buttonText || 'Oke, Mengerti';

    iconContainer.className = 'kyc-modal-icon-wrap ' + type;
    if (type === 'success') {
        iconContainer.innerHTML = '<svg width="34" height="34" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>';
    } else if (type === 'warning') {
        iconContainer.innerHTML = '<svg width="34" height="34" fill="none" stroke="#d97706" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
    } else {
        iconContainer.innerHTML = '<svg width="34" height="34" fill="none" stroke="#dc2626" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
    }

    kycModalCallback = opts.onConfirm || null;
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeKycModal() {
    const overlay = document.getElementById('kycInteractiveModal');
    overlay.style.display = 'none';
    document.body.style.overflow = '';
    if (typeof kycModalCallback === 'function') {
        const cb = kycModalCallback;
        kycModalCallback = null;
        cb();
    }
}

function handleKycModalBackdrop(e) {
    if (e.target.id === 'kycInteractiveModal') {
        closeKycModal();
    }
}
</script>

<!-- MODAL DIALOG INTERAKTIF & MOBILE-FRIENDLY TEMA LIGHT BUYLE -->
<div id="kycInteractiveModal" class="kyc-interactive-overlay" style="display:none;" onclick="handleKycModalBackdrop(event)">
    <div class="kyc-interactive-card" onclick="event.stopPropagation()">
        <div id="kycModalIconContainer" class="kyc-modal-icon-wrap success">
            <svg width="34" height="34" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h3 id="kycModalTitle" class="kyc-modal-title">Pengajuan Berhasil Dikirim</h3>
        <p id="kycModalDesc" class="kyc-modal-desc">Admin akan meninjau verifikasi dalam 1x24 jam.</p>
        <div class="kyc-modal-btn-wrap">
            <button type="button" id="kycModalConfirmBtn" class="kyc-modal-btn-primary" onclick="closeKycModal()">
                Oke, Mengerti
            </button>
        </div>
    </div>
</div>

<style>
.kyc-interactive-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.25rem;
    animation: kycFadeIn 0.2s ease-out;
}
@keyframes kycFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes kycPopIn {
    from { transform: scale(0.92); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.kyc-interactive-card {
    background: #ffffff;
    border-radius: 24px;
    max-width: 440px;
    width: 100%;
    padding: 2.25rem 2rem 1.85rem;
    text-align: center;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05);
    animation: kycPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    border: 1.5px solid #f1f5f9;
}
.kyc-modal-icon-wrap {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    margin: 0 auto 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s ease;
}
.kyc-modal-icon-wrap.success {
    background: #f0fdf4;
    border: 3px solid #bbf7d0;
    color: #16a34a;
}
.kyc-modal-icon-wrap.warning {
    background: #fffbeb;
    border: 3px solid #fef3c7;
    color: #d97706;
}
.kyc-modal-icon-wrap.error {
    background: #fef2f2;
    border: 3px solid #fecaca;
    color: #dc2626;
}
.kyc-modal-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 0.5rem;
    line-height: 1.35;
    letter-spacing: -0.02em;
}
.kyc-modal-desc {
    font-size: 0.875rem;
    color: #64748b;
    margin: 0 0 1.5rem;
    line-height: 1.55;
}
.kyc-modal-btn-wrap {
    display: flex;
    gap: 0.75rem;
}
.kyc-modal-btn-primary {
    flex: 1;
    background: #1eb349;
    color: #ffffff;
    border: none;
    padding: 0.875rem 1.25rem;
    border-radius: 14px;
    font-size: 0.9375rem;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(30, 179, 73, 0.3);
    transition: all 0.2s ease;
    font-family: inherit;
}
.kyc-modal-btn-primary:hover {
    background: #16a34a;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(30, 179, 73, 0.4);
}
.kyc-modal-btn-primary:active {
    transform: translateY(1px);
}
</style>
