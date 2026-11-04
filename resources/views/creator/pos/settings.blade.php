@extends('creator.layout')

@section('title', 'Pengaturan Kasir POS – Creator Studio')

@section('styles')
<style>
    :root {
        --pg: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        --pos-green: #1eb349;
        --pos-green-dark: #16933b;
        --pos-gradient: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
    }

    /* ── Global Reset & Centering (Anti-Mepet, Super App Mobile First) ── */
    body {
        background: #f8fafc !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .cr-main-wrapper {
        margin-left: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        overflow-x: hidden !important;
        background: #f8fafc !important;
    }

    .cr-main-canvas {
        margin: 0 auto !important;
        padding: 0.85rem 1rem 120px 1rem !important;
        width: 100% !important;
        max-width: 480px !important;
        box-sizing: border-box !important;
        background: transparent !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    .cr-topbar-header-row {
        display: none !important;
    }

    .ps-wrap {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto !important;
        padding: 0 !important;
        box-sizing: border-box !important;
    }

    /* ── Header ── */
    .ps-hdr {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-bottom: 0.85rem;
    }
    .ps-back {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #334155;
        text-decoration: none;
        flex-shrink: 0;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
        transition: transform .15s;
    }
    .ps-back:hover {
        transform: scale(1.05);
    }
    .ps-hdr-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
    }
    .ps-hdr-sub {
        font-size: 0.68rem;
        color: #64748b;
        margin: 2px 0 0;
    }

    /* ── 2×3 Tab Grid Navigation (Compact & Identical) ── */
    .ps-tab-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.45rem;
        margin-bottom: 1rem;
    }
    .ps-tab-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 0.55rem 0.25rem;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        color: #475569;
        font-size: 0.7rem;
        font-weight: 700;
        cursor: pointer;
        transition: all .15s;
        text-align: center;
    }
    .ps-tab-btn:hover {
        border-color: #86efac;
        color: #166534;
    }
    .ps-tab-btn.active {
        background: var(--pg) !important;
        border-color: transparent !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(30,179,73,.25) !important;
    }

    /* ── Content Card (Consistent on all tabs) ── */
    .ps-content-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.15rem 1.15rem;
        box-shadow: 0 2px 10px rgba(0,0,0,.02);
        box-sizing: border-box;
    }

    /* ── Inputs & UI elements from POS ── */
    .form-input {
        width: 100%;
        box-sizing: border-box;
        height: 42px;
        padding: 0 0.85rem;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 0.82rem;
        color: #0f172a;
        outline: none;
        transition: border-color .15s;
        background: #ffffff;
    }
    .form-input:focus {
        border-color: #1eb349;
    }
    textarea.form-input {
        height: auto;
        padding: 0.6rem 0.85rem;
    }

    .btn-submit-green {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: var(--pg);
        color: #fff;
        border: none;
        border-radius: 999px;
        padding: 0.62rem 1.25rem;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(30,179,73,.35);
        transition: all .15s;
        width: 100%;
    }
    .btn-submit-green:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(30,179,73,.45);
    }

    .btn-outline-bio {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: #ffffff;
        color: #166534;
        border: 1.5px solid #bbf7d0;
        border-radius: 999px;
        padding: 0.58rem 1.1rem;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-outline-bio:hover {
        background: #f0fdf4;
    }

    /* ── Standarized Bottom Navigation Bar ── */
    /* POS Standard Bottom Navigation Bar (100% Identical Across All Screens) */
    .pos-bottom-nav-wrap {
        display: flex !important;
        position: fixed !important;
        bottom: 16px !important;
        left: 14px !important;
        right: 14px !important;
        max-width: 440px !important;
        margin: 0 auto !important;
        z-index: 10000 !important;
        justify-content: center !important;
        pointer-events: none !important;
    }
    .pos-bottom-nav-bar {
        pointer-events: auto !important;
        width: 100% !important;
        height: 62px !important;
        background: #ffffff !important;
        border-radius: 999px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0.35rem 0.65rem !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.04) !important;
        border: 1.5px solid #f1f5f9 !important;
        box-sizing: border-box !important;
    }
    .pos-nav-item {
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: transparent;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        color: #64748b;
        padding: 0;
        outline: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        text-decoration: none;
        -webkit-tap-highlight-color: transparent;
    }
    .pos-nav-item.active {
        background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
        color: #ffffff !important;
        padding: 0.5rem 1.1rem !important;
        border-radius: 999px !important;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35) !important;
        gap: 6px !important;
        width: auto !important;
    }
    .pos-nav-label {
        display: none;
    }
    .pos-nav-item.active .pos-nav-label {
        display: inline-block;
        font-size: 0.8rem;
        font-weight: 700;
        color: #ffffff !important;
        letter-spacing: -0.01em;
    }
</style>
@endsection

@section('content')
<div class="ps-wrap">

    {{-- Header --}}
    <div class="ps-hdr">
        <a href="{{ route('creator.pos.profile') }}" class="ps-back" title="Kembali ke Profil">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="ps-hdr-title">Pengaturan POS</h1>
            <p class="ps-hdr-sub">Struk, Biaya, Diskon, Meja, Staf & Pembayaran</p>
        </div>
    </div>

    {{-- 2×3 Grid Tab Navigation --}}
    <div class="ps-tab-grid">
        <button type="button" class="ps-tab-btn active" id="tabBtnSettingStruk" onclick="switchSettingTab('struk')">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <span>Struk & Logo</span>
        </button>
        <button type="button" class="ps-tab-btn" id="tabBtnSettingFees" onclick="switchSettingTab('fees')">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>Pajak & Biaya (<span id="settingFeesBadgeCount">{{ count($posFees ?? []) }}</span>)</span>
        </button>
        <button type="button" class="ps-tab-btn" id="tabBtnSettingDiscounts" onclick="switchSettingTab('discounts')">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
            <span>Diskon (<span id="settingDiscountsBadgeCount">{{ count($posDiscounts ?? []) }}</span>)</span>
        </button>
        <button type="button" class="ps-tab-btn" id="tabBtnSettingTables" onclick="switchSettingTab('tables')">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            <span>Kelola Meja (<span id="settingTablesBadgeCount">{{ count($posTables ?? []) }}</span>)</span>
        </button>
        <button type="button" class="ps-tab-btn" id="tabBtnSettingStaff" onclick="switchSettingTab('staff')">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Staf Kasir (<span id="settingStaffBadgeCount">{{ count($cashiers) }}</span>)</span>
        </button>
        <button type="button" class="ps-tab-btn" id="tabBtnSettingPayment" onclick="switchSettingTab('payment')">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            <span>Metode Bayar</span>
        </button>
    </div>

    {{-- Main Content Card containing all 6 panes --}}
    <div class="ps-content-card">
                            <div id="paneSettingStruk" style="display: block;">
                        <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem;">

                            <!-- Upload Logo Struk -->
                            <div
                                style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.1rem;">
                                <div
                                    style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                                    <div>
                                        <div style="font-size:0.85rem; font-weight:800; color:#0f172a;">Logo Struk Transaksi
                                        </div>
                                        <div style="font-size:0.72rem; color:#64748b;">Logo toko akan dicetak di bagian
                                            paling atas struk thermal</div>
                                    </div>
                                    <div id="posLogoDeleteWrap" style="{{ empty($posLogoUrl) ? 'display:none;' : '' }}">
                                        <button type="button" onclick="handlePosLogoDelete()"
                                            style="background:#fee2e2; border:1px solid #fecaca; color:#dc2626; font-size:0.72rem; font-weight:700; padding:4px 9px; border-radius:6px; cursor:pointer;">
                                            Hapus Logo
                                        </button>
                                    </div>
                                </div>

                                <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                                    <div id="posLogoPreviewContainer"
                                        style="width: 130px; height: 65px; border: 2px dashed #cbd5e1; border-radius: 10px; display: flex; align-items: center; justify-content: center; background: #ffffff; overflow: hidden; padding: 4px; flex-shrink:0;">
                                        @if(!empty($posLogoUrl))
                                            <img id="posLogoPreviewImg" src="{{ $posLogoUrl }}" alt="Logo POS"
                                                style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        @else
                                            <span id="posLogoPreviewEmpty"
                                                style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-align:center;">Belum
                                                ada logo</span>
                                        @endif
                                    </div>

                                    <div style="flex:1; min-width: 180px;">
                                        <form id="formUploadPosLogo" onsubmit="handlePosLogoUpload(event)">
                                            <input type="file" id="posLogoFileInput" accept="image/*" style="display:none;"
                                                onchange="previewPosLogoSelected(this)">
                                            <div style="display:flex; gap:8px; align-items:center;">
                                                <button type="button" class="btn-outline-bio"
                                                    onclick="document.getElementById('posLogoFileInput').click()"
                                                    style="padding:0.45rem 0.9rem; font-size:0.75rem; border-radius:8px;">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg>
                                                    Pilih Logo Baru
                                                </button>
                                                <button type="submit" class="btn-submit-green" id="btnSavePosLogo"
                                                    style="width:auto; padding:0.45rem 1rem; font-size:0.75rem; border-radius:8px; display:none;">
                                                    Upload &amp; Pasang
                                                </button>
                                            </div>
                                        </form>
                                        <div style="font-size:0.69rem; color:#94a3b8; margin-top:6px; line-height:1.35;">
                                            Format JPG, PNG, WEBP atau SVG (Maks. 3MB). Saran: format horizontal /
                                            transparan.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Toko di Struk -->
                            <div
                                style="background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.1rem;">
                                <div style="font-size:0.85rem; font-weight:800; color:#0f172a; margin-bottom:0.25rem;">
                                    Identitas Toko di Cetakan Struk</div>
                                <div style="font-size:0.72rem; color:#64748b; margin-bottom:1rem;">Informasi nama, alamat
                                    dan kontak yang tercetak di kertas thermal struk</div>

                                <div style="display:flex; flex-direction:column; gap:12px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.75rem; font-weight:700; color:#334155; margin-bottom:4px;">Nama
                                            Toko Struk</label>
                                        <input type="text" id="setPosStoreName" class="form-input"
                                            style="height:38px; font-size:0.82rem;" value="{{ $storeName }}"
                                            placeholder="Contoh: Toko Kopi Senja" oninput="liveUpdateReceiptPreview()">
                                    </div>

                                    <div>
                                        <label
                                            style="display:block; font-size:0.75rem; font-weight:700; color:#334155; margin-bottom:4px;">Alamat
                                            Toko</label>
                                        <textarea id="setPosStoreAddress" class="form-input" rows="2"
                                            style="font-size:0.82rem; padding:8px 10px; resize:none;"
                                            placeholder="Contoh: Jl. Sudirman No. 12, Jakarta Pusat"
                                            oninput="liveUpdateReceiptPreview()">{{ $posStoreAddress ?: ($profile?->address ?? '') }}</textarea>
                                    </div>

                                    <div>
                                        <label
                                            style="display:block; font-size:0.75rem; font-weight:700; color:#334155; margin-bottom:4px;">No.
                                            Telepon / WhatsApp Toko</label>
                                        <input type="text" id="setPosStorePhone" class="form-input"
                                            style="height:38px; font-size:0.82rem;"
                                            value="{{ $posStorePhone ?: ($profile?->phone ?? '') }}"
                                            placeholder="Contoh: 081234567890" oninput="liveUpdateReceiptPreview()">
                                    </div>
                                </div>
                            </div>

                            <!-- Mini Live Preview Box -->
                            <div
                                style="border:1.5px dashed #cbd5e1; border-radius:12px; padding:12px 16px; background:#ffffff; text-align:center;">
                                <div
                                    style="font-size:0.68rem; font-weight:800; text-transform:uppercase; letter-spacing:1px; color:#64748b; margin-bottom:8px;">
                                    — Live Preview Header Struk —
                                </div>
                                <div id="prevReceiptHeader"
                                    style="font-family:'Montserrat',sans-serif; color:#000; line-height:1.35; max-width:280px; margin:0 auto;">
                                    <div id="prevHeaderLogo" style="margin-bottom:4px;">
                                        @if(!empty($posLogoUrl))
                                            <img src="{{ $posLogoUrl }}" alt="Logo"
                                                style="max-height:36px; max-width:120px; object-fit:contain; margin:0 auto; display:block;">
                                        @endif
                                    </div>
                                    <div id="prevHeaderName" style="font-weight:800; font-size:13.5px;">{{ $storeName }}
                                    </div>
                                    <div id="prevHeaderAddress" style="font-size:10px; color:#333; margin-top:2px;">
                                        {{ $posStoreAddress ?: ($profile?->address ?? 'Alamat toko belum diatur') }}
                                    </div>
                                    <div id="prevHeaderPhone" style="font-size:10px; color:#333; margin-top:1px;">
                                        {{ $posStorePhone ? ('No. Telp ' . $posStorePhone) : 'No. Telp: -' }}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Footer Tab 1: Simpan Pengaturan -->
                        <div style="margin-top:1.25rem; display:flex; justify-content:flex-end; gap:8px;">
                            <button type="button" class="btn-submit-green" id="btnSaveStoreSettings"
                                onclick="savePosStoreSettings()"
                                style="width:auto; padding:0.6rem 1.6rem; font-size:0.82rem; border-radius:999px;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                    <polyline points="17 21 17 13 7 13 7 21" />
                                    <polyline points="7 3 7 8 15 8" />
                                </svg>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- TAB 2: PAJAK & BIAYA TAMBAHAN                       -->
                    <!-- ═══════════════════════════════════════════════════ -->
                    <div id="paneSettingFees" style="display: none;">
                        <!-- Guidance Alert Box -->
                        <div
                            style="background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#166534" stroke-width="2.2" viewBox="0 0 24 24"
                                style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <div style="font-size:0.75rem; color:#166534; line-height:1.45;">
                                <strong>Pajak &amp; Biaya Tambahan Fleksibel:</strong> Anda dapat menambahkan beberapa jenis
                                biaya atau pajak (misal <strong>PPN 11%</strong>, <strong>Service Charge Resto 5%</strong>,
                                <strong>PB1</strong>, atau <strong>Biaya Kemasan/Takeaway Rp 2.000</strong>). Biaya yang
                                berstatus aktif akan otomatis dihitung dan dirinci di struk thermal kasir.
                            </div>
                        </div>

                        <!-- Top Action Bar -->
                        <div
                            style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:8px;">
                            <div>
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a;">Daftar Biaya &amp; Pajak POS
                                </div>
                                <div style="font-size:0.72rem; color:#64748b;">Atur persentase (%) atau nominal rupiah (Rp)
                                    untuk tiap komponen</div>
                            </div>
                            <button type="button" class="btn-submit-green" onclick="addFeeRow()"
                                style="width:auto; padding:0.45rem 1rem; font-size:0.76rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                + Tambah Biaya / Pajak
                            </button>
                        </div>

                        <!-- Container Daftar Biaya -->
                        <div id="feeRowsListContainer" style="display:flex; flex-direction:column; gap:10px;">
                            <!-- Rendered dynamically by renderFeeSettingsList() -->
                        </div>

                        <!-- Live Simulation Box -->
                        <div
                            style="margin-top:1.25rem; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px; padding:12px 14px;">
                            <div
                                style="font-size:0.74rem; font-weight:700; color:#475569; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                Simulasi Perhitungan Transaksi (Contoh Subtotal Rp 100.000):
                            </div>
                            <div id="feeLiveSimulationText"
                                style="font-size:0.78rem; color:#0f172a; font-weight:600; line-height:1.5;">
                                Memuat simulasi...
                            </div>
                        </div>

                        <!-- Footer Tab 2: Simpan Pengaturan Biaya -->
                        <div style="margin-top:1.25rem; display:flex; justify-content:flex-end; gap:8px;">
                            <button type="button" class="btn-submit-green" id="btnSaveFeesSettings"
                                onclick="savePosFeesSettings()"
                                style="width:auto; padding:0.6rem 1.6rem; font-size:0.82rem; border-radius:999px;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                                    <polyline points="17 21 17 13 7 13 7 21" />
                                    <polyline points="7 3 7 8 15 8" />
                                </svg>
                                Simpan Pengaturan Biaya &amp; Pajak
                            </button>
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- TAB 3: DISKON & PROMO PRODUK                        -->
                    <!-- ═══════════════════════════════════════════════════ -->
                    <div id="paneSettingDiscounts" style="display: none;">
                        <!-- Guidance Alert Box -->
                        <div
                            style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24"
                                style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <div style="font-size:0.75rem; color:#1e40af; line-height:1.45;">
                                <strong>Promo Fleksibel Berbasis Produk:</strong> Anda dapat membuat promo potongan harga
                                (%) atau nominal (Rp). Diskon dapat diterapkan untuk <strong>Semua Produk</strong> atau
                                <strong>Produk Tertentu (bisa pilih lebih dari 1 produk)</strong>. Saat transaksi di kasir
                                POS, sistem akan otomatis menghitung potongan hanya untuk produk yang valid!
                            </div>
                        </div>

                        <!-- Top Action Bar -->
                        <div
                            style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:8px;">
                            <div>
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a;">Katalog Diskon &amp; Promo
                                    POS</div>
                                <div style="font-size:0.72rem; color:#64748b;">Kelola promo aktif, potongan harga, dan
                                    target produk</div>
                            </div>
                            <button type="button" class="btn-submit-green" onclick="openAddDiscountDrawer()"
                                style="width:auto; padding:0.45rem 1rem; font-size:0.76rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                + Buat Diskon Baru
                            </button>
                        </div>

                        <!-- Collapsible Drawer / Form Buat & Edit Diskon -->
                        <div id="discountDrawerForm"
                            style="display:none; background:#f8fafc; border:1.5px solid #cbd5e1; border-radius:14px; padding:1.1rem; margin-bottom:1.25rem;">
                            <div
                                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.85rem; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">
                                <div style="font-size:0.85rem; font-weight:800; color:#0f172a;" id="discountDrawerTitle">
                                    Tambah Diskon / Promo Baru
                                </div>
                                <button type="button" onclick="closeDiscountDrawer()"
                                    style="background:none; border:none; font-size:1.1rem; color:#64748b; cursor:pointer; line-height:1;">&times;</button>
                            </div>

                            <form id="formDiscountData" onsubmit="handleSaveDiscount(event)">
                                <input type="hidden" id="discountEditId" value="">

                                <div style="display:grid; grid-template-columns:1fr; gap:10px; margin-bottom:10px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Nama
                                            Promo / Diskon *</label>
                                        <input type="text" id="discountInputName" class="form-input"
                                            style="height:36px; font-size:0.8rem;"
                                            placeholder="Contoh: Diskon Kopi 15% / Promo Gajian Rp 10.000" required>
                                    </div>
                                </div>

                                <div class="cashier-form-grid"
                                    style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Tipe
                                            Diskon *</label>
                                        <select id="discountInputType" class="form-input"
                                            style="height:36px; font-size:0.8rem;" onchange="onDiscountTypeChanged()">
                                            <option value="percent">Persentase (%)</option>
                                            <option value="nominal">Nominal Rupiah (Rp)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Besaran
                                            Potongan *</label>
                                        <div style="position:relative;">
                                            <input type="number" id="discountInputValue" class="form-input"
                                                style="height:36px; font-size:0.8rem; padding-right:32px;"
                                                placeholder="Contoh: 15" min="0" step="any" required>
                                            <span id="discountValueSuffix"
                                                style="position:absolute; right:10px; top:50%; transform:translateY(-50%); font-size:0.75rem; font-weight:700; color:#64748b;">%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Scope Pilihan Produk -->
                                <div
                                    style="margin-bottom:12px; background:#ffffff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 12px;">
                                    <label
                                        style="display:block; font-size:0.73rem; font-weight:800; color:#1e293b; margin-bottom:6px;">Berlaku
                                        Untuk Produk:</label>
                                    <div style="display:flex; gap:16px; flex-wrap:wrap;">
                                        <label
                                            style="display:flex; align-items:center; gap:6px; font-size:0.78rem; font-weight:600; color:#334155; cursor:pointer;">
                                            <input type="radio" name="discountScopeRadio" value="all" checked
                                                onchange="toggleDiscountScopeUI('all')"
                                                style="accent-color:#1eb349; cursor:pointer;">
                                            <span>Semua Produk (Global)</span>
                                        </label>
                                        <label
                                            style="display:flex; align-items:center; gap:6px; font-size:0.78rem; font-weight:600; color:#334155; cursor:pointer;">
                                            <input type="radio" name="discountScopeRadio" value="specific"
                                                onchange="toggleDiscountScopeUI('specific')"
                                                style="accent-color:#1eb349; cursor:pointer;">
                                            <span>Pilih Produk Tertentu (Multi-select)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Multi-product Picker Checklist (Visible only when scope === 'specific') -->
                                <div id="discountProductPickerWrap"
                                    style="display:none; margin-bottom:12px; background:#ffffff; border:1.5px solid #cbd5e1; border-radius:10px; padding:10px;">
                                    <div
                                        style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; gap:8px; flex-wrap:wrap;">
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <span style="font-size:0.75rem; font-weight:700; color:#0f172a;">Pilih
                                                Produk:</span>
                                            <span id="discountSelectedCountBadge"
                                                style="font-size:0.68rem; background:#dcfce7; color:#15803d; font-weight:700; padding:1px 7px; border-radius:999px;">0
                                                produk dipilih</span>
                                        </div>
                                        <div style="display:flex; gap:6px;">
                                            <button type="button" onclick="selectAllDiscountProducts()"
                                                style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; font-size:0.68rem; font-weight:700; padding:3px 8px; border-radius:6px; cursor:pointer;">
                                                Pilih Semua
                                            </button>
                                            <button type="button" onclick="deselectAllDiscountProducts()"
                                                style="background:#fee2e2; border:1px solid #fecaca; color:#dc2626; font-size:0.68rem; font-weight:700; padding:3px 8px; border-radius:6px; cursor:pointer;">
                                                Hapus Pilihan
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Search input within picker -->
                                    <div style="margin-bottom:8px;">
                                        <input type="text" id="discountProductPickerSearch" class="form-input"
                                            style="height:32px; font-size:0.74rem;" placeholder="Cari nama produk..."
                                            oninput="filterDiscountProductPicker()">
                                    </div>

                                    <!-- Scrollable Checklist -->
                                    <div id="discountProductPickerList"
                                        style="max-height:220px; overflow-y:auto; display:flex; flex-direction:column; gap:4px; padding-right:4px;">
                                        <!-- Rendered dynamically from window.posAllProducts -->
                                    </div>
                                </div>

                                <!-- Status Toggle -->
                                <div style="margin-bottom:12px;">
                                    <label
                                        style="display:flex; align-items:center; gap:8px; font-size:0.8rem; font-weight:600; color:#1e293b; cursor:pointer;">
                                        <input type="checkbox" id="discountInputActive" checked
                                            style="width:16px; height:16px; accent-color:#1eb349; cursor:pointer;">
                                        <span>Promo Aktif &amp; Siap Digunakan di Kasir</span>
                                    </label>
                                </div>

                                <div
                                    style="display:flex; justify-content:flex-end; gap:8px; border-top:1px solid #e2e8f0; padding-top:10px;">
                                    <button type="button" class="btn-outline-bio" onclick="closeDiscountDrawer()"
                                        style="padding:0.45rem 1rem; font-size:0.76rem; border-radius:8px;">Batal</button>
                                    <button type="submit" class="btn-submit-green" id="btnSaveDiscountSubmit"
                                        style="width:auto; padding:0.45rem 1.25rem; font-size:0.76rem; border-radius:8px;">
                                        Simpan Promo Diskon
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Discounts List Cards -->
                        <div id="discountListContainer" style="display:flex; flex-direction:column; gap:8px;">
                            <!-- Rendered dynamically by renderDiscountSettingsList() -->
                        </div>
                    </div>

                    <!-- ═══════════════════════════════════════════════════ -->
                    <!-- TAB 4: STAF KASIR & SHIFT                          -->
                    <!-- ═══════════════════════════════════════════════════ -->
                    <div id="paneSettingStaff" style="display: none;">

                        <!-- Guidance Alert Box -->
                        <div
                            style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24"
                                style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <div style="font-size:0.75rem; color:#1e40af; line-height:1.45;">
                                <strong>Akses Terbatas Staf Kasir:</strong> Akun kasir hanya dapat membuka halaman
                                <strong>Kasir POS</strong>. Seluruh menu lain (Overview, Produk, Saldo, Laporan) otomatis
                                terkunci demi keamanan data Anda. Setiap transaksi dan closing shift akan tercatat atas nama
                                staf yang sedang bertugas.
                            </div>
                        </div>

                        <!-- Top Action Bar -->
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <div>
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a;">Daftar Akun Kasir</div>
                                <div style="font-size:0.72rem; color:#64748b;">Kelola akun login (email), jam kerja / shift,
                                    dan status aktif staf</div>
                            </div>
                            <button type="button" class="btn-submit-green" onclick="openAddCashierDrawer()"
                                style="width:auto; padding:0.45rem 1rem; font-size:0.76rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                + Tambah Staf Kasir
                            </button>
                        </div>

                        <!-- Collapsible Drawer / Form Tambah & Edit Staf -->
                        <div id="cashierDrawerForm"
                            style="display:none; background:#f8fafc; border:1.5px solid #cbd5e1; border-radius:14px; padding:1.1rem; margin-bottom:1.25rem;">
                            <div
                                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.85rem; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">
                                <div style="font-size:0.85rem; font-weight:800; color:#0f172a;" id="cashierDrawerTitle">
                                    Tambah Staf Kasir Baru
                                </div>
                                <button type="button" onclick="closeCashierDrawer()"
                                    style="background:none; border:none; font-size:1.1rem; color:#64748b; cursor:pointer; line-height:1;">&times;</button>
                            </div>

                            <form id="formCashierData" onsubmit="handleSaveCashier(event)">
                                <input type="hidden" id="cashierEditId" value="">

                                <div class="cashier-form-grid"
                                    style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Nama
                                            Lengkap Staf *</label>
                                        <input type="text" id="cashierInputName" class="form-input"
                                            style="height:36px; font-size:0.8rem;" placeholder="Contoh: Siti Rahmawati"
                                            required>
                                    </div>
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Jabatan
                                            / Role Title</label>
                                        <input type="text" id="cashierInputRole" class="form-input"
                                            style="height:36px; font-size:0.8rem;"
                                            placeholder="Contoh: Kasir Pagi / Kasir Utama" value="Kasir">
                                    </div>
                                </div>

                                <div class="cashier-form-grid"
                                    style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:10px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Email
                                            Login *</label>
                                        <input type="email" id="cashierInputEmail" class="form-input"
                                            style="height:36px; font-size:0.8rem;" placeholder="Contoh: kasir@tokosaya.com"
                                            required autocomplete="off">
                                        <div style="font-size:0.67rem; color:#94a3b8; margin-top:2px;">Email untuk login
                                            staf kasir ke sistem POS</div>
                                    </div>
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Password
                                            Login <span id="cashierPasswordNotice"
                                                style="font-weight:normal; color:#94a3b8;">*</span></label>
                                        <input type="password" id="cashierInputPassword" class="form-input"
                                            style="height:36px; font-size:0.8rem;" placeholder="Minimal 6 karakter"
                                            autocomplete="new-password">
                                        <div style="font-size:0.67rem; color:#94a3b8; margin-top:2px;"
                                            id="cashierPassHelper">Wajib diisi untuk akun baru</div>
                                    </div>
                                </div>

                                <div class="cashier-form-grid"
                                    style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                                    <div>
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:4px;">Jam
                                            Kerja / Shift Kasir</label>
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <input type="time" id="cashierShiftStart" class="form-input"
                                                style="height:36px; font-size:0.82rem; padding:0 8px; flex:1;" value="08:00"
                                                onchange="syncCashierShiftTime()">
                                            <span style="font-size:0.73rem; font-weight:700; color:#64748b;">s/d</span>
                                            <input type="time" id="cashierShiftEnd" class="form-input"
                                                style="height:36px; font-size:0.82rem; padding:0 8px; flex:1;" value="16:00"
                                                onchange="syncCashierShiftTime()">
                                        </div>
                                        <input type="hidden" id="cashierInputShift" value="08:00 - 16:00">
                                        <div style="display:flex; gap:4px; flex-wrap:wrap; margin-top:6px;">
                                            <button type="button" onclick="setCashierShiftPreset('08:00', '16:00')"
                                                style="font-size:0.65rem; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:999px; padding:2px 7px; color:#475569; cursor:pointer; font-weight:600;">Pagi
                                                (08:00 - 16:00)</button>
                                            <button type="button" onclick="setCashierShiftPreset('12:00', '20:00')"
                                                style="font-size:0.65rem; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:999px; padding:2px 7px; color:#475569; cursor:pointer; font-weight:600;">Siang
                                                (12:00 - 20:00)</button>
                                            <button type="button" onclick="setCashierShiftPreset('16:00', '00:00')"
                                                style="font-size:0.65rem; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:999px; padding:2px 7px; color:#475569; cursor:pointer; font-weight:600;">Malam
                                                (16:00 - 00:00)</button>
                                        </div>
                                    </div>
                                    <div style="display:flex; flex-direction:column; justify-content:center;">
                                        <label
                                            style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:4px;">Status
                                            Akun</label>
                                        <label
                                            style="display:flex; align-items:center; gap:8px; font-size:0.8rem; font-weight:600; color:#1e293b; cursor:pointer; padding:8px 0;">
                                            <input type="checkbox" id="cashierInputActive" checked
                                                style="width:16px; height:16px; accent-color:#1eb349; cursor:pointer;">
                                            <span>Aktif &amp; Boleh Login</span>
                                        </label>
                                    </div>
                                </div>

                                <div
                                    style="display:flex; justify-content:flex-end; gap:8px; border-top:1px solid #e2e8f0; padding-top:10px;">
                                    <button type="button" class="btn-outline-bio" onclick="closeCashierDrawer()"
                                        style="padding:0.45rem 1rem; font-size:0.76rem; border-radius:8px;">Batal</button>
                                    <button type="submit" class="btn-submit-green" id="btnSaveCashierSubmit"
                                        style="width:auto; padding:0.45rem 1.25rem; font-size:0.76rem; border-radius:8px;">
                                        Simpan Akun Staf
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Cashiers List Cards / Grid -->
                        <div id="cashierListWrap" style="display:flex; flex-direction:column; gap:8px;">
                            @forelse($cashiers as $c)
                                <div class="cashier-card-item" id="cashierRow-{{ $c->id }}"
                                    style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:12px 14px; display:flex; align-items:center; justify-content:space-between; gap:12px; transition:all 0.2s ease;">
                                    <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                                        <div
                                            style="width:38px; height:38px; border-radius:50%; background:#f1f5f9; border:1.5px solid #cbd5e1; display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:800; color:#334155; flex-shrink:0;">
                                            {{ strtoupper(substr($c->name, 0, 2)) }}
                                        </div>
                                        <div style="min-width:0;">
                                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                                <span
                                                    style="font-size:0.88rem; font-weight:800; color:#0f172a;">{{ $c->name }}</span>
                                                <span
                                                    style="font-size:0.7rem; color:#0369a1; background:#f0f9ff; border:1px solid #bae6fd; padding:1px 6px; border-radius:4px; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                                                    <svg width="11" height="11" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path
                                                            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                                        <polyline points="22,6 12,13 2,6" />
                                                    </svg>
                                                    {{ $c->email }}
                                                </span>
                                                @if($c->is_active)
                                                    <span class="badge-status-active" id="badgeActive-{{ $c->id }}"
                                                        style="font-size:0.68rem; background:#dcfce7; color:#15803d; font-weight:700; padding:2px 7px; border-radius:999px;">Aktif</span>
                                                @else
                                                    <span class="badge-status-inactive" id="badgeActive-{{ $c->id }}"
                                                        style="font-size:0.68rem; background:#f1f5f9; color:#64748b; font-weight:700; padding:2px 7px; border-radius:999px;">Nonaktif</span>
                                                @endif
                                            </div>
                                            <div
                                                style="display:flex; align-items:center; gap:12px; font-size:0.72rem; color:#64748b; margin-top:3px; flex-wrap:wrap;">
                                                <span
                                                    style="display:inline-flex; align-items:center; gap:4px; color:#475569; font-weight:600;">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                        <circle cx="12" cy="7" r="4" />
                                                    </svg>
                                                    {{ $c->cashier_role_title ?: 'Kasir' }}
                                                </span>
                                                @if(!empty($c->shift_hours))
                                                    <span
                                                        style="display:inline-flex; align-items:center; gap:4px; color:#0369a1; background:#f0f9ff; padding:1px 6px; border-radius:4px; font-weight:600;">
                                                        <svg width="11" height="11" fill="none" stroke="currentColor"
                                                            stroke-width="2" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="12" r="10" />
                                                            <polyline points="12 6 12 12 16 14" />
                                                        </svg>
                                                        {{ $c->shift_hours }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                        <button type="button" class="btn-outline-bio"
                                            style="padding:4px 9px; font-size:0.72rem; border-radius:6px;"
                                            onclick='editCashierData(@json($c))' title="Edit Kasir">
                                            Edit
                                        </button>
                                        <button type="button"
                                            style="padding:4px 9px; font-size:0.72rem; border-radius:6px; border:1px solid #e2e8f0; background:#f8fafc; color:#334155; cursor:pointer; font-weight:600;"
                                            onclick="toggleCashierActive({{ $c->id }})" title="Aktifkan / Nonaktifkan">
                                            {{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                        <button type="button"
                                            style="padding:4px 8px; font-size:0.72rem; border-radius:6px; border:1px solid #fee2e2; background:#fff1f2; color:#e11d48; cursor:pointer; font-weight:700;"
                                            onclick="deleteCashier({{ $c->id }}, '{{ addslashes($c->name) }}')"
                                            title="Hapus Staf">
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div id="cashierEmptyNotice"
                                    style="text-align:center; padding:2rem 1rem; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px;">
                                    <div
                                        style="width:40px; height:40px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 8px auto; color:#94a3b8;">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="9" cy="7" r="4" />
                                        </svg>
                                    </div>
                                    <div style="font-size:0.85rem; font-weight:700; color:#334155;">Belum Ada Akun Staf Kasir
                                    </div>
                                    <div style="font-size:0.72rem; color:#64748b; margin-top:2px;">Klik tombol "+ Tambah Staf
                                        Kasir" di atas untuk menambahkan staf yang bertugas.</div>
                                </div>
                            @endforelse
                        </div>

                    </div>

                    <!-- TAB 5: KELOLA MEJA & BARCODE QR DINE-IN -->
                    <div id="paneSettingTables" style="display:none;">

                        <!-- Guidance Alert Box (Identik dengan Staf Kasir) -->
                        <div
                            style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" stroke-width="2.2" viewBox="0 0 24 24"
                                style="flex-shrink:0; margin-top:2px;">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                            <div style="font-size:0.75rem; color:#1e40af; line-height:1.45;">
                                <strong>Dine-In Barcode QR Meja:</strong> Setiap meja memiliki Barcode QR khusus yang
                                terhubung ke Link in Bio (Tema 1-4). Pelanggan tinggal scan untuk memesan, dan nomor meja
                                langsung terisi otomatis saat checkout!
                            </div>
                        </div>

                        <!-- Top Action Bar (Identik dengan Staf Kasir) -->
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                            <div>
                                <div style="font-size:0.88rem; font-weight:800; color:#0f172a;">Daftar Meja &amp; Barcode QR
                                </div>
                                <div style="font-size:0.72rem; color:#64748b;">Kelola nomor meja, kapasitas kursi, dan
                                    generate barcode QR untuk dine-in</div>
                            </div>
                            <button type="button" class="btn-submit-green" onclick="toggleAddTableForm()"
                                style="width:auto; padding:0.45rem 1rem; font-size:0.76rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3"
                                    viewBox="0 0 24 24">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                + Tambah Meja
                            </button>
                        </div>

                        <!-- Collapsible Form Tambah Meja (Identik dengan Drawer Staf Kasir) -->
                        <div id="formAddTableBox"
                            style="display:none; background:#f8fafc; border:1.5px solid #cbd5e1; border-radius:14px; padding:1.1rem; margin-bottom:1.25rem;">
                            <div
                                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.85rem; border-bottom:1px solid #e2e8f0; padding-bottom:8px;">
                                <div style="font-size:0.85rem; font-weight:800; color:#0f172a;">
                                    Tambah Meja Baru
                                </div>
                                <button type="button" onclick="toggleAddTableForm(false)"
                                    style="background:none; border:none; font-size:1.1rem; color:#64748b; cursor:pointer; line-height:1;">&times;</button>
                            </div>

                            <div style="display:grid; grid-template-columns: 2fr 1fr 2fr; gap:10px; margin-bottom:12px;">
                                <div>
                                    <label
                                        style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Nama
                                        Meja / Nomor <span style="color:#ef4444;">*</span></label>
                                    <input type="text" id="newTableName" class="form-input"
                                        style="height:36px; font-size:0.8rem;" placeholder="Contoh: Meja 01, VIP 1">
                                </div>
                                <div>
                                    <label
                                        style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Kapasitas
                                        Kursi</label>
                                    <input type="number" id="newTableSeats" class="form-input"
                                        style="height:36px; font-size:0.8rem;" placeholder="4" min="1" value="4">
                                </div>
                                <div>
                                    <label
                                        style="display:block; font-size:0.73rem; font-weight:700; color:#334155; margin-bottom:3px;">Area
                                        / Catatan</label>
                                    <input type="text" id="newTableArea" class="form-input"
                                        style="height:36px; font-size:0.8rem;" placeholder="Contoh: Indoor Lt 1 / Outdoor">
                                </div>
                            </div>

                            <div
                                style="display:flex; justify-content:flex-end; gap:8px; border-top:1px solid #e2e8f0; padding-top:10px;">
                                <button type="button" class="btn-outline-bio" onclick="toggleAddTableForm(false)"
                                    style="padding:0.45rem 1rem; font-size:0.76rem; border-radius:8px;">Batal</button>
                                <button type="button" class="btn-submit-green" onclick="confirmAddTable()"
                                    style="width:auto; padding:0.45rem 1.25rem; font-size:0.76rem; border-radius:8px;">
                                    Simpan Meja
                                </button>
                            </div>
                        </div>

                        <!-- Table List Container -->
                        <div id="tableRowsListContainer" style="display:flex; flex-direction:column; gap:8px;">
                            <!-- Rendered by renderTablesSettingsList() -->
                        </div>

                        <!-- Bottom Save Notice & Button (Identik tombol hijau POS) -->
                        <div
                            style="display:flex; justify-content:space-between; align-items:center; margin-top:1.2rem; padding-top:1rem; border-top:1.5px solid #f1f5f9;">
                            <div style="font-size:0.72rem; color:#64748b;">
                                Perubahan meja otomatis tersinkronisasi dengan kasir POS dan Link in Bio.
                            </div>
                            <button type="button" class="btn-submit-green" id="btnSaveTablesSettings"
                                onclick="saveTablesSettings()"
                                style="width:auto; padding:0.48rem 1.4rem; font-size:0.78rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Simpan Perubahan Meja
                            </button>
                        </div>
                    </div>

                    <!-- ══ TAB 6: PEMBAYARAN CASHLESS & BEBAN FEE POS ══ -->
                    <div id="paneSettingPayment" style="display:none;">
                        <!-- Info Alert -->
                        <div
                            style="background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:12px 14px; margin-bottom:1.1rem; display:flex; gap:10px; align-items:flex-start;">
                            <svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.2" viewBox="0 0 24 24"
                                style="flex-shrink:0; margin-top:2px;">
                                <rect x="2" y="5" width="20" height="14" rx="2" />
                                <line x1="2" y1="10" x2="22" y2="10" />
                            </svg>
                            <div style="font-size:0.77rem; color:#166534; line-height:1.45;">
                                <strong>Konfigurasi Cashless & Penanggung Fee Platform/Admin</strong><br>
                                Atur metode cashless (Midtrans Snap dinamis otomatis atau QRIS Statis upload toko sendiri)
                                dan tentukan beban biaya fee ditanggung penjual (potong saldo deposit) atau pembeli
                                (ditambahkan ke total tagihan).
                            </div>
                        </div>

                        <!-- Opsi 1: Mode Cashless (QRIS) -->
                        <div
                            style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.2rem; margin-bottom:1.2rem;">
                            <div style="font-weight:800; font-size:0.86rem; color:#0f172a; margin-bottom:0.25rem;">
                                1. Metode Cashless POS (QRIS)
                            </div>
                            <div style="font-size:0.74rem; color:#64748b; margin-bottom:1rem;">
                                Pilih alur penerimaan non-tunai saat kasir memilih pembayaran QRIS / Cashless.
                            </div>

                            <div
                                style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:12px; margin-bottom:1rem;">
                                <!-- Midtrans Snap -->
                                <label
                                    style="border:1.5px solid {{ $posCashlessMode === 'snap_midtrans' ? '#16a34a' : '#e2e8f0' }}; background: {{ $posCashlessMode === 'snap_midtrans' ? '#f0fdf4' : '#ffffff' }}; border-radius:12px; padding:1rem; cursor:pointer; display:flex; gap:10px; align-items:flex-start; transition:all 0.15s ease;"
                                    class="cashless-mode-card" id="cardModeSnap">
                                    <input type="radio" name="set_pos_cashless_mode" value="snap_midtrans" {{ $posCashlessMode === 'snap_midtrans' ? 'checked' : '' }}
                                        onchange="onCashlessModeChange('snap_midtrans')"
                                        style="accent-color:#16a34a; margin-top:3px;">
                                    <div>
                                        <div
                                            style="font-weight:700; font-size:0.82rem; color:#0f172a; display:flex; align-items:center; gap:6px;">
                                            Buyle
                                            QRIS
                                            <span
                                                style="font-size:0.65rem; background:#dcfce7; color:#15803d; padding:2px 6px; border-radius:999px; font-weight:700;">Dinamis</span>
                                        </div>
                                        <div style="font-size:0.72rem; color:#64748b; margin-top:4px; line-height:1.4;">
                                            QRIS terbit otomatis &amp; nominal unik per transaksi. Pembayaran terverifikasi
                                            otomatis via sistem gateway.
                                        </div>
                                    </div>
                                </label>

                                <!-- QRIS Statis Toko -->
                                <label
                                    style="border:1.5px solid {{ $posCashlessMode === 'qris_static' ? '#16a34a' : '#e2e8f0' }}; background: {{ $posCashlessMode === 'qris_static' ? '#f0fdf4' : '#ffffff' }}; border-radius:12px; padding:1rem; cursor:pointer; display:flex; gap:10px; align-items:flex-start; transition:all 0.15s ease;"
                                    class="cashless-mode-card" id="cardModeStatic">
                                    <input type="radio" name="set_pos_cashless_mode" value="qris_static" {{ $posCashlessMode === 'qris_static' ? 'checked' : '' }}
                                        onchange="onCashlessModeChange('qris_static')"
                                        style="accent-color:#16a34a; margin-top:3px;">
                                    <div>
                                        <div
                                            style="font-weight:700; font-size:0.82rem; color:#0f172a; display:flex; align-items:center; gap:6px;">
                                            QRIS Statis Toko
                                            <span
                                                style="font-size:0.65rem; background:#fef3c7; color:#b45309; padding:2px 6px; border-radius:999px; font-weight:700;">Upload
                                                Gambar</span>
                                        </div>
                                        <div style="font-size:0.72rem; color:#64748b; margin-top:4px; line-height:1.4;">
                                            Menampilkan gambar QRIS toko Anda langsung di layar kasir/pelanggan. Uang
                                            langsung masuk rekening Anda.
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Panel Upload QRIS Statis -->
                            <div id="staticQrisUploadPanel"
                                style="display: {{ $posCashlessMode === 'qris_static' ? 'block' : 'none' }}; border-top:1px dashed #e2e8f0; padding-top:1rem; margin-top:0.5rem;">
                                <div style="font-weight:700; font-size:0.78rem; color:#334155; margin-bottom:0.6rem;">
                                    File Gambar QRIS Statis Toko (Auto WebP)
                                </div>

                                <div style="display:flex; flex-wrap:wrap; gap:16px; align-items:center;">
                                    <!-- Preview Box -->
                                    <div id="qrisPreviewContainer"
                                        style="width:130px; height:130px; border:2px dashed #cbd5e1; border-radius:12px; display:flex; align-items:center; justify-content:center; background:#f8fafc; overflow:hidden; flex-shrink:0;">
                                        @if($posQrisUrl)
                                            <img src="{{ $posQrisUrl }}" id="qrisPreviewImg"
                                                style="width:100%; height:100%; object-fit:contain; background:#ffffff;">
                                        @else
                                            <div id="qrisPreviewPlaceholder"
                                                style="text-align:center; padding:8px; color:#94a3b8; font-size:0.68rem;">
                                                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8"
                                                    viewBox="0 0 24 24" style="margin:0 auto 4px auto; display:block;">
                                                    <rect x="3" y="3" width="7" height="7"></rect>
                                                    <rect x="14" y="3" width="7" height="7"></rect>
                                                    <rect x="14" y="14" width="7" height="7"></rect>
                                                    <rect x="3" y="14" width="7" height="7"></rect>
                                                </svg>
                                                Belum ada QRIS
                                            </div>
                                        @endif
                                    </div>

                                    <div style="flex:1; min-width:200px;">
                                        <input type="file" id="posQrisFileInput" accept="image/*" style="display:none;"
                                            onchange="handleQrisFileSelect(event)">
                                        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:8px;">
                                            <button type="button" class="btn-submit-green"
                                                onclick="document.getElementById('posQrisFileInput').click()"
                                                style="width:auto; padding:0.45rem 1rem; font-size:0.75rem; border-radius:8px;">
                                                <svg width="14" height="14" fill="none" stroke="currentColor"
                                                    stroke-width="2.2" viewBox="0 0 24 24">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="17 8 12 3 7 8" />
                                                    <line x1="12" y1="3" x2="12" y2="15" />
                                                </svg>
                                                Unggah Gambar QRIS
                                            </button>
                                            <button type="button" class="btn-outline-bio" id="btnRemoveQris"
                                                onclick="removeQrisImage()"
                                                style="padding:0.45rem 0.9rem; font-size:0.75rem; border-radius:8px; color:#ef4444; border-color:#fca5a5; display: {{ $posQrisUrl ? 'inline-flex' : 'none' }};">
                                                Hapus QRIS
                                            </button>
                                        </div>
                                        <div style="font-size:0.7rem; color:#64748b; line-height:1.45;">
                                            Format didukung: JPG, PNG, WEBP (Maks 5MB). File yang diunggah <strong>otomatis
                                                dikonversi ke WebP</strong> dan dikompresi agar ringan, jernih, dan cepat
                                            tampil saat scan.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Opsi 2: Penanggung Biaya Admin & Platform Fee -->
                        <div
                            style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:14px; padding:1.2rem; margin-bottom:1.2rem;">
                            <div style="font-weight:800; font-size:0.86rem; color:#0f172a; margin-bottom:0.25rem;">
                                2. Penanggung Biaya Platform &amp; Admin POS
                            </div>
                            <div style="font-size:0.74rem; color:#64748b; margin-bottom:1rem;">
                                Tarif saat ini: Platform Fee {{ $platformFeeRate }}% &amp; Admin Fee {{ $adminFeeRate }}%.
                                Tentukan pihak yang membayar biaya transaksi ini.
                            </div>

                            <div
                                style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:12px; margin-bottom:0.5rem;">
                                <!-- Ditanggung Penjual -->
                                <label
                                    style="border:1.5px solid {{ $posFeePayer === 'seller' ? '#16a34a' : '#e2e8f0' }}; background: {{ $posFeePayer === 'seller' ? '#f0fdf4' : '#ffffff' }}; border-radius:12px; padding:1rem; cursor:pointer; display:flex; gap:10px; align-items:flex-start; transition:all 0.15s ease;"
                                    class="fee-payer-card" id="cardFeePayerSeller">
                                    <input type="radio" name="set_pos_fee_payer" value="seller" {{ $posFeePayer === 'seller' ? 'checked' : '' }} onchange="onFeePayerChange('seller')"
                                        style="accent-color:#16a34a; margin-top:3px;">
                                    <div>
                                        <div
                                            style="font-weight:700; font-size:0.82rem; color:#0f172a; display:flex; align-items:center; gap:6px;">
                                            Ditanggung Penjual
                                            <span
                                                style="font-size:0.65rem; background:#e0f2fe; color:#0369a1; padding:2px 6px; border-radius:999px; font-weight:700;">Potong
                                                Saldo Deposit</span>
                                        </div>
                                        <div style="font-size:0.72rem; color:#64748b; margin-top:4px; line-height:1.4;">
                                            Pembeli hanya membayar murni total belanja (+ pajak/layanan toko). Fee platform
                                            dipotong otomatis dari <strong>Saldo Deposit POS</strong> penjual.
                                        </div>
                                        <div
                                            style="font-size:0.68rem; color:#b45309; margin-top:6px; background:#fffbeb; padding:4px 8px; border-radius:6px; border:1px solid #fef3c7; line-height:1.35;">
                                            ⚠️ Jika deposit tidak mencukupi, transaksi POS tidak dapat diproses.
                                        </div>
                                    </div>
                                </label>

                                <!-- Ditanggung Pembeli -->
                                <label
                                    style="border:1.5px solid {{ $posFeePayer === 'buyer' ? '#16a34a' : '#e2e8f0' }}; background: {{ $posFeePayer === 'buyer' ? '#f0fdf4' : '#ffffff' }}; border-radius:12px; padding:1rem; cursor:pointer; display:flex; gap:10px; align-items:flex-start; transition:all 0.15s ease;"
                                    class="fee-payer-card" id="cardFeePayerBuyer">
                                    <input type="radio" name="set_pos_fee_payer" value="buyer" {{ $posFeePayer === 'buyer' ? 'checked' : '' }} onchange="onFeePayerChange('buyer')"
                                        style="accent-color:#16a34a; margin-top:3px;">
                                    <div>
                                        <div
                                            style="font-weight:700; font-size:0.82rem; color:#0f172a; display:flex; align-items:center; gap:6px;">
                                            Ditanggung Pembeli
                                            <span
                                                style="font-size:0.65rem; background:#dcfce7; color:#15803d; padding:2px 6px; border-radius:999px; font-weight:700;">Tambah
                                                ke Tagihan</span>
                                        </div>
                                        <div style="font-size:0.72rem; color:#64748b; margin-top:4px; line-height:1.4;">
                                            Sistem kasir secara otomatis menambahkan nominal fee platform &amp; admin ke
                                            total tagihan yang dibayarkan oleh pembeli. Saldo deposit penjual tidak
                                            dipotong.
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Bottom Save Action -->
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-top:0.5rem;">
                            <div style="font-size:0.72rem; color:#64748b;">
                                Pengaturan akan langsung aktif di layar kasir POS.
                            </div>
                            <button type="button" class="btn-submit-green" id="btnSavePaymentSettings"
                                onclick="savePaymentSettings()"
                                style="width:auto; padding:0.48rem 1.4rem; font-size:0.78rem; border-radius:999px;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Simpan Pengaturan Pembayaran
                            </button>
                        </div>
                    </div>

                </div> <!-- end posSettingsRealContent -->

            </div>

            <!-- Footer: Penutup Modal -->
            <div class="pos-modal-foot"
                style="display:flex; justify-content:space-between; align-items:center; padding:0.8rem 1.4rem; background:#f8fafc; border-top:1.5px solid #f1f5f9;">
                <div style="font-size:0.7rem; color:#94a3b8;">
                    Kustomisasi tersimpan &amp; terhubung langsung ke modul POS.

    </div>

</div>{{-- end .ps-wrap --}}

{{-- Bottom Nav (4 Items, identical to profile, reports, index) --}}
<div class="pos-bottom-nav-wrap">
    <div class="pos-bottom-nav-bar">
        <a href="{{ route('creator.pos.index') }}" class="pos-nav-item" title="Home">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span class="pos-nav-label">Home</span>
        </a>
        <a href="{{ route('creator.pos.index', ['screen'=>'transactions']) }}" class="pos-nav-item" title="Transaksi">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><circle cx="12" cy="12" r="3"/><line x1="2" y1="9" x2="6" y2="9"/><line x1="18" y1="15" x2="22" y2="15"/></svg>
            <span class="pos-nav-label">Transaksi</span>
        </a>
        <a href="{{ route('creator.pos.reports') }}" class="pos-nav-item" title="Laporan">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            <span class="pos-nav-label">Laporan</span>
        </a>
        <a href="{{ route('creator.pos.profile') }}" class="pos-nav-item active" title="Profil">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span class="pos-nav-label">Profil</span>
        </a>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';

// State Data from Server
let platformFeeRate = {{ $platformFeeRate }};
let adminFeeRate = {{ $adminFeeRate }};
let currentPosBalance = {{ (float) $posBalance }};

let posFeesData = {!! json_encode($profile ? $profile->getPosFees() : []) !!};
let posDiscountsData = {!! json_encode($profile ? $profile->getPosDiscounts() : []) !!};
let posTablesData = {!! json_encode($profile ? $profile->getPosTables() : []) !!};
const posBioBaseUrl = {!! json_encode(!empty($profile->custom_domain) ? ('https://' . rtrim($profile->custom_domain, '/')) : url('/')) !!};
const posStoreDisplayName = {!! json_encode($profile->store_name ?? $storeName ?? 'Toko') !!};

let posCashlessMode = {!! json_encode($posCashlessMode ?? 'snap_midtrans') !!};
let posQrisUrl = {!! json_encode($posQrisUrl ?? null) !!};
let posFeePayer = {!! json_encode($posFeePayer ?? 'seller') !!};
let posBalance = {{ (float) ($posBalance ?? 0) }};

let storePhoneText = {!! json_encode($posStorePhone ?: ($profile->phone ?? '')) !!};

window.posCatalog = {
@foreach($products as $prod)
    @php
        $pPrice = (float) ($prod->sale_price ?: $prod->price);
        $pImg = $prod->image ? (str_starts_with($prod->image, 'http') ? $prod->image : asset('storage/' . $prod->image)) : '';
    @endphp
    '{{ $prod->id }}': {
        id: '{{ $prod->id }}',
        name: {!! json_encode($prod->name) !!},
        price: {{ $pPrice }},
        image: {!! json_encode($pImg) !!}
    },
@endforeach
};

function showPosToast(msg, type = 'success') {
    let toast = document.getElementById('posToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'posToast';
        toast.style.cssText = 'position:fixed; top:20px; left:50%; transform:translateX(-50%); background:#0f172a; color:#fff; padding:0.65rem 1.25rem; border-radius:999px; font-size:0.78rem; font-weight:700; z-index:99999; box-shadow:0 10px 25px rgba(0,0,0,.2); display:flex; align-items:center; gap:8px; transition:opacity .2s, transform .2s; pointer-events:none;';
        document.body.appendChild(toast);
    }
    toast.innerHTML = `<span style="color:#22c55e;">✔</span> ${msg}`;
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(-10px)';
    }, 2800);
}

function showPosAlert(msg, title = 'Perhatian', type = 'info') {
    alert((title ? (title + ': ') : '') + msg);
}

function calculateTotals() {
    // No cart on settings page
}

function closeModal(modalId) {
    const el = document.getElementById(modalId);
    if (el) el.classList.remove('show');
}

            function switchSettingTab(tabId) {
                const tabs = ['struk', 'fees', 'discounts', 'staff', 'tables', 'payment'];
                const btnMap = {
                    struk: 'tabBtnSettingStruk',
                    fees: 'tabBtnSettingFees',
                    discounts: 'tabBtnSettingDiscounts',
                    staff: 'tabBtnSettingStaff',
                    tables: 'tabBtnSettingTables',
                    payment: 'tabBtnSettingPayment',
                };
                const paneMap = {
                    struk: 'paneSettingStruk',
                    fees: 'paneSettingFees',
                    discounts: 'paneSettingDiscounts',
                    staff: 'paneSettingStaff',
                    tables: 'paneSettingTables',
                    payment: 'paneSettingPayment',
                };
                tabs.forEach(t => {
                    const btn = document.getElementById(btnMap[t]);
                    const pane = document.getElementById(paneMap[t]);
                    if (btn) btn.classList.toggle('active', t === tabId);
                    if (pane) pane.style.display = t === tabId ? 'block' : 'none';
                });

                // Render the appropriate pane when switching
                if (tabId === 'fees') renderFeeSettingsList();
                if (tabId === 'discounts') {
                    renderDiscountSettingsList();
                    renderDiscountProductPicker();
                }
                if (tabId === 'tables') {
                    renderTablesSettingsList();
                }
            }

            // ═══════════════════════════════════════════════
            // FEES MANAGEMENT (Tab Pajak & Biaya)
            // ═══════════════════════════════════════════════

            function renderFeeSettingsList() {
                const container = document.getElementById('feeRowsListContainer');
                if (!container) return;

                if (!posFeesData || posFeesData.length === 0) {
                    container.innerHTML = `
                                            <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.82rem;">
                                                Belum ada biaya / pajak ditambahkan.<br>Klik <strong>+ Tambah Biaya / Pajak</strong> untuk menambah.
                                            </div>`;
                    renderFeeSimulation();
                    updateFeesBadgeCount();
                    return;
                }

                container.innerHTML = posFeesData.map((fee, idx) => `
                                        <div style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:10px 14px; display:flex; align-items:center; justify-content:space-between; gap:10px; transition:all 0.2s ease;">
                                            <div style="display:flex; align-items:center; gap:10px; flex:1; min-width:0;">
                                                <label style="display:flex; align-items:center; gap:6px; cursor:pointer; margin:0;">
                                                    <input type="checkbox" ${fee.is_active ? 'checked' : ''}
                                                        onchange="toggleFeeActive(${idx}, this.checked)"
                                                        style="width:16px; height:16px; accent-color:#1eb349; cursor:pointer; flex-shrink:0;">
                                                </label>
                                                <div style="min-width:0;">
                                                    <div style="font-size:0.82rem; font-weight:800; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                        ${escapeHtml(fee.name)}
                                                    </div>
                                                    <div style="font-size:0.7rem; color:#64748b; margin-top:1px;">
                                                        ${fee.type === 'percent' ? fee.value + '%' : 'Rp ' + formatRupiah(fee.value)} (${fee.type === 'percent' ? 'Persentase' : 'Nominal Tetap'})
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                                <input type="number" value="${fee.value}" min="0" step="any"
                                                    style="width:70px; height:30px; border:1px solid #e2e8f0; border-radius:6px; padding:0 6px; font-size:0.78rem; font-weight:600; text-align:right;"
                                                    onchange="updateFeeValue(${idx}, this.value)">
                                                <select onchange="updateFeeType(${idx}, this.value)"
                                                    style="height:30px; border:1px solid #e2e8f0; border-radius:6px; padding:0 4px; font-size:0.72rem; font-weight:600; color:#334155;">
                                                    <option value="percent" ${fee.type === 'percent' ? 'selected' : ''}>%</option>
                                                    <option value="nominal" ${fee.type === 'nominal' ? 'selected' : ''}>Rp</option>
                                                </select>
                                                <button onclick="removeFeeRow(${idx})"
                                                    style="background:#fee2e2; border:1px solid #fecaca; color:#dc2626; border-radius:6px; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; font-size:0.9rem;">×</button>
                                            </div>
                                        </div>
                                    `).join('');

                renderFeeSimulation();
                updateFeesBadgeCount();
            }

            function addFeeRow() {
                posFeesData.push({ name: 'Biaya Baru', type: 'percent', value: 0, is_active: true });
                renderFeeSettingsList();
                const container = document.getElementById('feeRowsListContainer');
                if (container) {
                    const cards = container.querySelectorAll('[style*="border-radius:12px"]');
                    if (cards.length > 0) {
                        const lastCard = cards[cards.length - 1];
                        lastCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                        // Focus name input via inline edit on click
                        const nameEl = lastCard.querySelector('div[style*="font-weight:800"]');
                        if (nameEl) {
                            nameEl.contentEditable = 'true';
                            nameEl.focus();
                            nameEl.onblur = function () {
                                const newName = this.innerText.trim() || 'Biaya Baru';
                                posFeesData[posFeesData.length - 1].name = newName;
                                this.contentEditable = 'false';
                                renderFeeSettingsList();
                            };
                            // Select all text
                            const range = document.createRange();
                            range.selectNodeContents(nameEl);
                            window.getSelection().removeAllRanges();
                            window.getSelection().addRange(range);
                        }
                    }
                }
            }

            function toggleFeeActive(idx, active) {
                if (posFeesData[idx]) {
                    posFeesData[idx].is_active = active;
                    renderFeeSimulation();
                    updateFeesBadgeCount();
                    renderCartFees();
                    calculateTotals();
                }
            }

            function updateFeeValue(idx, val) {
                if (posFeesData[idx]) {
                    posFeesData[idx].value = parseFloat(val) || 0;
                    renderFeeSimulation();
                    renderCartFees();
                    calculateTotals();
                }
            }

            function updateFeeType(idx, type) {
                if (posFeesData[idx]) {
                    posFeesData[idx].type = type;
                    renderFeeSimulation();
                    renderCartFees();
                    calculateTotals();
                }
            }

            function removeFeeRow(idx) {
                posFeesData.splice(idx, 1);
                renderFeeSettingsList();
                renderCartFees();
                calculateTotals();
            }

            function renderFeeSimulation() {
                const simEl = document.getElementById('feeLiveSimulationText');
                if (!simEl) return;
                const exampleSubtotal = 100000;
                let lines = [];
                let totalCustomFee = 0;
                (posFeesData || []).forEach(fee => {
                    if (!fee.is_active) return;
                    let amount = fee.type === 'percent'
                        ? (exampleSubtotal * (parseFloat(fee.value) || 0)) / 100
                        : (parseFloat(fee.value) || 0);
                    totalCustomFee += amount;
                    lines.push(`${escapeHtml(fee.name)}: +Rp ${formatRupiah(amount)}`);
                });
                const systemFee = (exampleSubtotal * (platformFeeRate + adminFeeRate)) / 100;
                const grandTotal = exampleSubtotal + totalCustomFee + systemFee;
                if (lines.length === 0) {
                    simEl.innerHTML = `<span style="color:#94a3b8;">Tidak ada biaya tambahan aktif.</span>`;
                } else {
                    simEl.innerHTML = `
                                            <div>Subtotal: Rp ${formatRupiah(exampleSubtotal)}</div>
                                            ${lines.map(l => `<div style="color:#0369a1;">+ ${l}</div>`).join('')}
                                            <div style="color:#64748b; font-size:0.72rem;">+ Platform/Admin Fee: Rp ${formatRupiah(systemFee)}</div>
                                            <div style="margin-top:4px; padding-top:4px; border-top:1px dashed #cbd5e1; font-weight:900; color:#1eb349;">
                                                Grand Total: Rp ${formatRupiah(grandTotal)}
                                            </div>`;
                }
            }

            function updateFeesBadgeCount() {
                const active = (posFeesData || []).filter(f => f.is_active).length;
                const badge = document.getElementById('settingFeesBadgeCount');
                if (badge) badge.innerText = active;
            }

            function savePosFeesSettings() {
                const btn = document.getElementById('btnSaveFeesSettings');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" style="animation:spin 0.8s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Menyimpan...`;
                }
                fetch('{{ route("creator.pos.update-settings") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ pos_fees: posFeesData })
                })
                    .then(r => r.json())
                    .then(data => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Simpan Pengaturan Biaya & Pajak`;
                        }
                        if (data.success) {
                            showPosToast('Pengaturan biaya & pajak berhasil disimpan!', 'success');
                            renderCartFees();
                            calculateTotals();
                        } else {
                            showPosAlert(data.message || 'Gagal menyimpan.', 'Error', 'error');
                        }
                    })
                    .catch(() => {
                        if (btn) { btn.disabled = false; btn.innerText = 'Simpan Pengaturan Biaya & Pajak'; }
                        showPosAlert('Terjadi kesalahan jaringan.', 'Error', 'error');
                    });
            }

            // Render dynamic fee rows inside cart summary box
            function renderCartFees() {
                const container = document.getElementById('cartFeesContainer');
                if (!container) return;
                const activeFees = (posFeesData || []).filter(f => f.is_active);
                if (activeFees.length === 0) {
                    container.style.display = 'none';
                    container.innerHTML = '';
                    return;
                }
                container.style.display = 'block';
                const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
                container.innerHTML = activeFees.map(fee => {
                    const amount = fee.type === 'percent'
                        ? (subtotal * (parseFloat(fee.value) || 0)) / 100
                        : (parseFloat(fee.value) || 0);
                    return `<div class="summary-row" style="font-size:0.78rem; color:#475569;">
                                            <span>${escapeHtml(fee.name)}${fee.type === 'percent' ? ' (' + fee.value + '%)' : ''}</span>
                                            <span style="font-weight:600;">+Rp ${formatRupiah(amount)}</span>
                                        </div>`;
                }).join('');
            }

            // ═══════════════════════════════════════════════
            // DISCOUNTS MANAGEMENT (Tab Diskon & Promo)
            // ═══════════════════════════════════════════════

            function renderDiscountSettingsList() {
                const container = document.getElementById('discountListContainer');
                if (!container) return;

                if (!posDiscountsData || posDiscountsData.length === 0) {
                    container.innerHTML = `
                                            <div style="text-align:center; padding:2rem; color:#94a3b8; font-size:0.82rem;">
                                                Belum ada promo / diskon dibuat.<br>Klik <strong>+ Buat Diskon Baru</strong> untuk membuat promo pertama.
                                            </div>`;
                    updateDiscountsBadgeCount();
                    return;
                }

                container.innerHTML = posDiscountsData.map((disc, idx) => {
                    const scopeLabel = disc.scope === 'all'
                        ? 'Semua Produk'
                        : `${(disc.product_ids || []).length} Produk Dipilih`;
                    const valueLabel = disc.type === 'percent'
                        ? disc.value + '%'
                        : 'Rp ' + formatRupiah(disc.value);
                    return `
                                        <div id="discountCard-${idx}" style="background:#ffffff; border:1.5px solid ${disc.is_active ? '#bbf7d0' : '#e2e8f0'}; border-radius:12px; padding:10px 14px; display:flex; align-items:center; justify-content:space-between; gap:10px; transition:all 0.2s ease;">
                                            <div style="display:flex; align-items:center; gap:10px; flex:1; min-width:0;">
                                                <label style="display:flex; align-items:center; gap:6px; cursor:pointer; margin:0;">
                                                    <input type="checkbox" ${disc.is_active ? 'checked' : ''}
                                                        onchange="toggleDiscountActive(${idx}, this.checked)"
                                                        style="width:16px; height:16px; accent-color:#1eb349; cursor:pointer; flex-shrink:0;">
                                                </label>
                                                <div style="min-width:0;">
                                                    <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                                        <span style="font-size:0.82rem; font-weight:800; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${escapeHtml(disc.name)}</span>
                                                        <span style="font-size:0.68rem; background:${disc.is_active ? '#dcfce7' : '#f1f5f9'}; color:${disc.is_active ? '#15803d' : '#64748b'}; font-weight:700; padding:1px 7px; border-radius:999px; flex-shrink:0;">${disc.is_active ? 'Aktif' : 'Nonaktif'}</span>
                                                    </div>
                                                    <div style="font-size:0.7rem; color:#64748b; margin-top:2px;">
                                                        Potongan <strong>${valueLabel}</strong> &nbsp;·&nbsp; ${scopeLabel}
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                                <button onclick="openEditDiscountDrawer(${idx})"
                                                    style="background:#f0f9ff; border:1px solid #bae6fd; color:#0369a1; border-radius:6px; padding:4px 9px; font-size:0.7rem; font-weight:700; cursor:pointer;">Edit</button>
                                                <button onclick="removeDiscount(${idx})"
                                                    style="background:#fee2e2; border:1px solid #fecaca; color:#dc2626; border-radius:6px; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; font-size:0.9rem;">×</button>
                                            </div>
                                        </div>`;
                }).join('');
                updateDiscountsBadgeCount();
                // Also update the cart preset dropdown
                renderDiscountPresetDropdown();
            }

            function updateDiscountsBadgeCount() {
                const active = (posDiscountsData || []).filter(d => d.is_active).length;
                const badge = document.getElementById('settingDiscountsBadgeCount');
                if (badge) badge.innerText = active;
            }

            function toggleDiscountActive(idx, active) {
                if (posDiscountsData[idx]) {
                    posDiscountsData[idx].is_active = active;
                    renderDiscountSettingsList();
                    renderDiscountPresetDropdown();
                    calculateTotals();
                }
            }

            function removeDiscount(idx) {
                posDiscountsData.splice(idx, 1);
                renderDiscountSettingsList();
                renderDiscountPresetDropdown();
                calculateTotals();
            }

            // ── Discount Drawer ──
            let discountEditIndex = -1;

            function openAddDiscountDrawer() {
                discountEditIndex = -1;
                const title = document.getElementById('discountDrawerTitle');
                if (title) title.innerText = 'Tambah Diskon / Promo Baru';
                document.getElementById('discountEditId').value = '';
                document.getElementById('discountInputName').value = '';
                document.getElementById('discountInputType').value = 'percent';
                document.getElementById('discountInputValue').value = '';
                document.getElementById('discountValueSuffix').innerText = '%';
                document.getElementById('discountInputActive').checked = true;
                document.querySelector('input[name="discountScopeRadio"][value="all"]').checked = true;
                toggleDiscountScopeUI('all');
                deselectAllDiscountProducts();
                const drawer = document.getElementById('discountDrawerForm');
                if (drawer) { drawer.style.display = 'block'; drawer.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
            }

            function openEditDiscountDrawer(idx) {
                const disc = posDiscountsData[idx];
                if (!disc) return;
                discountEditIndex = idx;
                const title = document.getElementById('discountDrawerTitle');
                if (title) title.innerText = 'Edit Diskon: ' + disc.name;
                document.getElementById('discountEditId').value = idx;
                document.getElementById('discountInputName').value = disc.name;
                document.getElementById('discountInputType').value = disc.type || 'percent';
                document.getElementById('discountInputValue').value = disc.value;
                document.getElementById('discountValueSuffix').innerText = disc.type === 'percent' ? '%' : 'Rp';
                document.getElementById('discountInputActive').checked = !!disc.is_active;
                const scope = disc.scope || 'all';
                document.querySelector(`input[name="discountScopeRadio"][value="${scope}"]`).checked = true;
                toggleDiscountScopeUI(scope);
                // Re-check selected products
                const selectedIds = new Set((disc.product_ids || []).map(Number));
                document.querySelectorAll('#discountProductPickerList input[type="checkbox"]').forEach(cb => {
                    cb.checked = selectedIds.has(Number(cb.value));
                });
                updateDiscountSelectedCount();
                const drawer = document.getElementById('discountDrawerForm');
                if (drawer) { drawer.style.display = 'block'; drawer.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); }
            }

            function closeDiscountDrawer() {
                const drawer = document.getElementById('discountDrawerForm');
                if (drawer) drawer.style.display = 'none';
                discountEditIndex = -1;
            }

            function onDiscountTypeChanged() {
                const type = document.getElementById('discountInputType').value;
                const suffix = document.getElementById('discountValueSuffix');
                if (suffix) suffix.innerText = type === 'percent' ? '%' : 'Rp';
            }

            function toggleDiscountScopeUI(scope) {
                const wrap = document.getElementById('discountProductPickerWrap');
                if (wrap) wrap.style.display = scope === 'specific' ? 'block' : 'none';
            }

            function handleSaveDiscount(e) {
                e.preventDefault();
                const name = document.getElementById('discountInputName').value.trim();
                const type = document.getElementById('discountInputType').value;
                const value = parseFloat(document.getElementById('discountInputValue').value) || 0;
                const isActive = document.getElementById('discountInputActive').checked;
                const scope = document.querySelector('input[name="discountScopeRadio"]:checked')?.value || 'all';
                const productIds = scope === 'specific'
                    ? Array.from(document.querySelectorAll('#discountProductPickerList input[type="checkbox"]:checked')).map(cb => Number(cb.value))
                    : [];

                if (!name) { showPosAlert('Nama promo tidak boleh kosong.', 'Nama Wajib', 'warning'); return; }
                if (type === 'percent' && value > 100) { showPosAlert('Diskon persen tidak boleh melebihi 100%.', 'Nilai Tidak Valid', 'warning'); return; }
                if (scope === 'specific' && productIds.length === 0) { showPosAlert('Pilih minimal 1 produk untuk diskon spesifik.', 'Produk Wajib Dipilih', 'warning'); return; }

                const discObj = { name, type, value, scope, product_ids: productIds, is_active: isActive };

                if (discountEditIndex >= 0) {
                    posDiscountsData[discountEditIndex] = discObj;
                } else {
                    posDiscountsData.push(discObj);
                }
                // Save to server
                fetch('{{ route("creator.pos.update-settings") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ pos_discounts: posDiscountsData })
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showPosToast('Promo diskon berhasil disimpan!', 'success');
                            closeDiscountDrawer();
                            renderDiscountSettingsList();
                            renderDiscountPresetDropdown();
                            calculateTotals();
                        } else {
                            showPosAlert(data.message || 'Gagal menyimpan.', 'Error', 'error');
                        }
                    })
                    .catch(() => showPosAlert('Terjadi kesalahan jaringan.', 'Error', 'error'));
            }

            // ── Product Picker for Discount ──
            function renderDiscountProductPicker() {
                const list = document.getElementById('discountProductPickerList');
                if (!list) return;
                const products = window.posAllProducts || [];
                if (products.length === 0) {
                    list.innerHTML = `<div style="font-size:0.78rem; color:#94a3b8; text-align:center; padding:1rem;">Tidak ada produk tersedia.</div>`;
                    return;
                }
                list.innerHTML = products.map(p => `
                                        <label style="display:flex; align-items:center; gap:8px; font-size:0.78rem; font-weight:600; color:#1e293b; cursor:pointer; padding:5px 4px; border-radius:6px; transition:background 0.15s;">
                                            <input type="checkbox" value="${p.id}"
                                                style="width:15px; height:15px; accent-color:#1eb349; cursor:pointer; flex-shrink:0;"
                                                onchange="updateDiscountSelectedCount()">
                                            <span>${escapeHtml(p.name)}</span>
                                        </label>
                                    `).join('');
            }

            function filterDiscountProductPicker() {
                const q = (document.getElementById('discountProductPickerSearch')?.value || '').toLowerCase().trim();
                document.querySelectorAll('#discountProductPickerList label').forEach(label => {
                    const name = label.querySelector('span')?.innerText?.toLowerCase() || '';
                    label.style.display = !q || name.includes(q) ? '' : 'none';
                });
            }

            function selectAllDiscountProducts() {
                document.querySelectorAll('#discountProductPickerList input[type="checkbox"]').forEach(cb => { cb.checked = true; });
                updateDiscountSelectedCount();
            }

            function deselectAllDiscountProducts() {
                document.querySelectorAll('#discountProductPickerList input[type="checkbox"]').forEach(cb => { cb.checked = false; });
                updateDiscountSelectedCount();
            }

            function updateDiscountSelectedCount() {
                const count = document.querySelectorAll('#discountProductPickerList input[type="checkbox"]:checked').length;
                const badge = document.getElementById('discountSelectedCountBadge');
                if (badge) badge.innerText = count + ' produk dipilih';
            }

            // ── Cart Preset Dropdown ──
            function renderDiscountPresetDropdown() {
                const sel = document.getElementById('posDiscountPresetSelect');
                const wrap = document.getElementById('cartDiscountPresetWrap');
                if (!sel || !wrap) return;
                const activeDiscounts = (posDiscountsData || []).filter(d => d.is_active);
                if (activeDiscounts.length === 0) {
                    wrap.style.display = 'none';
                    sel.innerHTML = '<option value="">Tanpa Diskon</option>';
                    return;
                }
                wrap.style.display = '';
                sel.innerHTML = '<option value="">Tanpa Diskon</option>' +
                    activeDiscounts.map((d, i) => {
                        const label = `${d.name} (${d.type === 'percent' ? d.value + '%' : 'Rp ' + formatRupiah(d.value)})`;
                        return `<option value="${i}">${label}</option>`;
                    }).join('');
            }

            function onDiscountPresetChanged() {
                const sel = document.getElementById('posDiscountPresetSelect');
                const idx = sel ? parseInt(sel.value) : NaN;
                const activeDiscounts = (posDiscountsData || []).filter(d => d.is_active);
                const discInput = document.getElementById('posDiscountInput');
                if (!isNaN(idx) && activeDiscounts[idx]) {
                    const disc = activeDiscounts[idx];
                    setDiscountType(disc.type === 'percent' ? 'percent' : 'nominal');
                    if (discInput) discInput.value = disc.value;
                    window._selectedDiscountPreset = disc;
                } else {
                    window._selectedDiscountPreset = null;
                    if (discInput) discInput.value = 0;
                }
                calculateTotals();
            }

            // Live Preview Struk di Tab 1
            function liveUpdateReceiptPreview() {
                const name = (document.getElementById('setPosStoreName')?.value || '').trim() || storeNameText;
                const addr = (document.getElementById('setPosStoreAddress')?.value || '').trim();
                const phone = (document.getElementById('setPosStorePhone')?.value || '').trim();

                const prevName = document.getElementById('prevHeaderName');
                const prevAddr = document.getElementById('prevHeaderAddress');
                const prevPhone = document.getElementById('prevHeaderPhone');

                if (prevName) prevName.innerText = name;
                if (prevAddr) prevAddr.innerText = addr || 'Alamat toko belum diatur';
                if (prevPhone) prevPhone.innerText = phone ? ('No. Telp ' + phone) : 'No. Telp: -';
            }

            // Simpan Pengaturan Struk Toko (Nama, Alamat, No Telp)
            function savePosStoreSettings() {
                const name = (document.getElementById('setPosStoreName')?.value || '').trim();
                const address = (document.getElementById('setPosStoreAddress')?.value || '').trim();
                const phone = (document.getElementById('setPosStorePhone')?.value || '').trim();

                if (!name) {
                    showPosAlert('Nama toko struk tidak boleh kosong.', 'Nama Toko Wajib', 'warning');
                    return;
                }

                const btn = document.getElementById('btnSaveStoreSettings');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24" style="animation:spin 0.8s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Menyimpan...`;
                }

                fetch('{{ route("creator.pos.update-settings") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        store_name: name,
                        pos_store_address: address,
                        pos_store_phone: phone
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Simpan Pengaturan`;
                        }
                        if (data.success) {
                            storeNameText = name;
                            storeAddressText = address;
                            storePhoneText = phone;

                            // Update main thermal receipt display
                            const recName = document.getElementById('recHeaderStoreName');
                            const recAddr = document.getElementById('recHeaderStoreAddress');
                            const recPhone = document.getElementById('recHeaderStorePhone');
                            if (recName) recName.innerText = name;
                            if (recAddr) recAddr.innerText = address;
                            if (recPhone) recPhone.innerText = phone ? ('No. Telp ' + phone) : '';

                            showPosToast('Pengaturan struk & profil toko berhasil disimpan!', 'success');
                            closeModal('posSettingsModal'); // Menutup pop-up saat tombol Simpan ditekan
                        } else {
                            showPosAlert(data.message || 'Gagal menyimpan pengaturan.', 'Gagal Simpan', 'error');
                        }
                    })
                    .catch(err => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = `<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Simpan Pengaturan`;
                        }
                        showPosAlert('Terjadi kesalahan jaringan: ' + err.message, 'Gagal Simpan', 'error');
                    });
            }

            // Preview Logo yang dipilih
            function previewPosLogoSelected(input) {
                if (input.files && input.files[0]) {
                    const file = input.files[0];
                    if (file.size > 3 * 1024 * 1024) {
                        showPosAlert('Ukuran file maksimal 3MB.', 'File Terlalu Besar', 'warning');
                        input.value = '';
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const container = document.getElementById('posLogoPreviewContainer');
                        if (container) {
                            container.innerHTML = `<img id="posLogoPreviewImg" src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
                        }
                        const prevLogo = document.getElementById('prevHeaderLogo');
                        if (prevLogo) {
                            prevLogo.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-height:36px; max-width:120px; object-fit:contain; margin:0 auto; display:block;">`;
                        }
                        const btnSave = document.getElementById('btnSavePosLogo');
                        if (btnSave) btnSave.style.display = 'inline-flex';
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Handle Upload Logo Struk
            function handlePosLogoUpload(event) {
                event.preventDefault();
                const fileInput = document.getElementById('posLogoFileInput');
                if (!fileInput.files || !fileInput.files[0]) {
                    showPosAlert('Silakan pilih file logo terlebih dahulu.', 'Pilih File', 'warning');
                    return;
                }

                const btnSave = document.getElementById('btnSavePosLogo');
                if (btnSave) {
                    btnSave.disabled = true;
                    btnSave.innerText = 'Mengupload...';
                }

                const formData = new FormData();
                formData.append('logo', fileInput.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route("creator.pos.upload-logo") }}', {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (btnSave) {
                            btnSave.disabled = false;
                            btnSave.innerText = 'Upload & Pasang';
                            btnSave.style.display = 'none';
                        }
                        if (data.success && data.logo_url) {
                            currentPosLogoUrl = data.logo_url;
                            updateReceiptHeaderLogo(data.logo_url);
                            const delWrap = document.getElementById('posLogoDeleteWrap');
                            if (delWrap) delWrap.style.display = 'block';
                            showPosToast('Logo struk kasir berhasil dipasang!', 'success');
                        } else {
                            showPosAlert(data.message || 'Gagal mengupload logo struk.', 'Gagal Upload', 'error');
                        }
                    })
                    .catch(err => {
                        if (btnSave) {
                            btnSave.disabled = false;
                            btnSave.innerText = 'Upload & Pasang';
                        }
                        showPosAlert('Terjadi kesalahan: ' + err.message, 'Gagal Upload', 'error');
                    });
            }

            // Handle Hapus Logo Struk
            function handlePosLogoDelete() {
                if (!confirm('Yakin ingin menghapus logo struk dan kembali menggunakan icon standar?')) return;

                fetch('{{ route("creator.pos.remove-logo") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            currentPosLogoUrl = null;
                            updateReceiptHeaderLogo(null);
                            const container = document.getElementById('posLogoPreviewContainer');
                            if (container) {
                                container.innerHTML = `<span id="posLogoPreviewEmpty" style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-align:center;">Belum ada logo</span>`;
                            }
                            const prevLogo = document.getElementById('prevHeaderLogo');
                            if (prevLogo) prevLogo.innerHTML = '';
                            const delWrap = document.getElementById('posLogoDeleteWrap');
                            if (delWrap) delWrap.style.display = 'none';
                            const fileInput = document.getElementById('posLogoFileInput');
                            if (fileInput) fileInput.value = '';
                            const btnSave = document.getElementById('btnSavePosLogo');
                            if (btnSave) btnSave.style.display = 'none';
                            showPosToast(data.message || 'Logo struk berhasil dihapus.', 'success');
                        } else {
                            showPosAlert(data.message || 'Gagal menghapus logo.', 'Gagal', 'error');
                        }
                    })
                    .catch(err => {
                        showPosAlert('Terjadi kesalahan: ' + err.message, 'Gagal', 'error');
                    });
            }

            function updateReceiptHeaderLogo(logoUrl) {
                const wrap = document.getElementById('recHeaderLogoWrap');
                if (!wrap) return;
                if (logoUrl) {
                    wrap.innerHTML = `<img src="${logoUrl}" alt="Logo Toko" id="recHeaderLogoImg" style="max-height: 48px; max-width: 140px; object-fit: contain; margin: 0 auto 3px auto; display: block;">`;
                } else {
                    wrap.innerHTML = `<svg id="recHeaderLogoFallbackSvg" width="38" height="38" viewBox="0 0 24 24" fill="currentColor" style="display:inline-block; margin:0 auto 2px auto; color:#000000;"><path d="M4 4h16a1 1 0 0 1 1 1v2a3 3 0 0 1-3 3 3 3 0 0 1-3-3 3 3 0 0 1-3 3 3 3 0 0 1-3-3 3 3 0 0 1-3-3V5a1 1 0 0 1 1-1zm0 8v9a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-9h-2v7H6v-7H4zm5 1h6v6H9v-6z"/></svg>`;
                }
            }

            // ═══════════════════════════════════════════════
            // KELOLA STAF KASIR & SHIFT (AJAX CRUD)
            // ═══════════════════════════════════════════════

            function syncCashierShiftTime() {
                const start = document.getElementById('cashierShiftStart')?.value || '08:00';
                const end = document.getElementById('cashierShiftEnd')?.value || '16:00';
                const hidden = document.getElementById('cashierInputShift');
                if (hidden) hidden.value = start + ' - ' + end;
            }

            function setCashierShiftPreset(start, end) {
                const sInput = document.getElementById('cashierShiftStart');
                const eInput = document.getElementById('cashierShiftEnd');
                if (sInput) sInput.value = start;
                if (eInput) eInput.value = end;
                syncCashierShiftTime();
            }

            function openAddCashierDrawer() {
                document.getElementById('cashierDrawerTitle').innerText = 'Tambah Staf Kasir Baru';
                document.getElementById('cashierEditId').value = '';
                document.getElementById('cashierInputName').value = '';
                document.getElementById('cashierInputRole').value = 'Kasir';
                document.getElementById('cashierInputEmail').value = '';
                document.getElementById('cashierInputPassword').value = '';
                document.getElementById('cashierShiftStart').value = '08:00';
                document.getElementById('cashierShiftEnd').value = '16:00';
                document.getElementById('cashierInputShift').value = '08:00 - 16:00';
                document.getElementById('cashierInputActive').checked = true;

                const notice = document.getElementById('cashierPasswordNotice');
                if (notice) notice.innerText = '*';
                const helper = document.getElementById('cashierPassHelper');
                if (helper) helper.innerText = 'Wajib diisi minimal 6 karakter';

                const drawer = document.getElementById('cashierDrawerForm');
                if (drawer) {
                    drawer.style.display = 'block';
                    drawer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }

            function closeCashierDrawer() {
                const drawer = document.getElementById('cashierDrawerForm');
                if (drawer) drawer.style.display = 'none';
            }

            function editCashierData(c) {
                document.getElementById('cashierDrawerTitle').innerText = 'Edit Staf Kasir: ' + c.name;
                document.getElementById('cashierEditId').value = c.id;
                document.getElementById('cashierInputName').value = c.name || '';
                document.getElementById('cashierInputRole').value = c.cashier_role_title || 'Kasir';
                document.getElementById('cashierInputEmail').value = c.email || c.username || '';
                document.getElementById('cashierInputPassword').value = '';
                const rawShift = c.shift_hours || '08:00 - 16:00';
                document.getElementById('cashierInputShift').value = rawShift;
                if (rawShift.includes('-')) {
                    const parts = rawShift.split('-');
                    document.getElementById('cashierShiftStart').value = parts[0].trim().substring(0, 5) || '08:00';
                    document.getElementById('cashierShiftEnd').value = parts[1].trim().substring(0, 5) || '16:00';
                } else {
                    document.getElementById('cashierShiftStart').value = '08:00';
                    document.getElementById('cashierShiftEnd').value = '16:00';
                }
                document.getElementById('cashierInputActive').checked = !!c.is_active;

                const notice = document.getElementById('cashierPasswordNotice');
                if (notice) notice.innerText = '(opsional)';
                const helper = document.getElementById('cashierPassHelper');
                if (helper) helper.innerText = 'Kosongkan jika password tidak diubah';

                const drawer = document.getElementById('cashierDrawerForm');
                if (drawer) {
                    drawer.style.display = 'block';
                    drawer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }

            function handleSaveCashier(event) {
                event.preventDefault();
                const id = document.getElementById('cashierEditId').value;
                const name = (document.getElementById('cashierInputName').value || '').trim();
                const role = (document.getElementById('cashierInputRole').value || '').trim();
                const email = (document.getElementById('cashierInputEmail').value || '').trim();
                const password = document.getElementById('cashierInputPassword').value;
                const shift = (document.getElementById('cashierInputShift').value || '').trim();
                const isActive = document.getElementById('cashierInputActive').checked ? 1 : 0;

                if (!name || !email) {
                    showPosAlert('Nama lengkap dan email login kasir wajib diisi!', 'Data Belum Lengkap', 'warning');
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showPosAlert('Format email tidak valid. Masukkan format email yang benar (contoh: kasir@tokosaya.com).', 'Format Email Salah', 'warning');
                    return;
                }

                if (!id && (!password || password.length < 6)) {
                    showPosAlert('Password wajib diisi minimal 6 karakter untuk akun kasir baru.', 'Password Terlalu Pendek', 'warning');
                    return;
                }

                const btn = document.getElementById('btnSaveCashierSubmit');
                if (btn) {
                    btn.disabled = true;
                    btn.innerText = 'Menyimpan...';
                }

                fetch('{{ route("creator.pos.cashiers.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: id || null,
                        name: name,
                        email: email,
                        password: password,
                        shift_hours: shift,
                        cashier_role_title: role,
                        is_active: isActive
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerText = 'Simpan Akun Staf';
                        }
                        if (data.success) {
                            closeCashierDrawer();
                            refreshCashierListUI(data.cashiers || []);
                            showPosToast(data.message || 'Akun staf kasir berhasil disimpan!', 'success');
                        } else {
                            showPosAlert(data.message || 'Gagal menyimpan akun kasir.', 'Gagal Simpan', 'error');
                        }
                    })
                    .catch(err => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerText = 'Simpan Akun Staf';
                        }
                        showPosAlert('Terjadi kesalahan: ' + err.message, 'Gagal Simpan', 'error');
                    });
            }

            function toggleCashierActive(cashierId) {
                const url = '{{ route("creator.pos.cashiers.toggle", ":id") }}'.replace(':id', cashierId);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showPosToast(data.message || 'Status kasir diperbarui.', 'success');
                            if (data.cashiers) refreshCashierListUI(data.cashiers);
                        } else {
                            showPosAlert(data.message || 'Gagal mengubah status kasir.', 'Gagal', 'error');
                        }
                    })
                    .catch(err => showPosAlert('Terjadi kesalahan: ' + err.message, 'Gagal', 'error'));
            }

            function deleteCashier(cashierId, cashierName) {
                if (!confirm(`Yakin ingin menghapus staf kasir "${cashierName}"? Akun ini tidak akan dapat login lagi ke modul POS.`)) return;

                const url = '{{ route("creator.pos.cashiers.delete", ":id") }}'.replace(':id', cashierId);
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showPosToast(data.message || 'Akun staf kasir berhasil dihapus.', 'success');
                            if (data.cashiers) refreshCashierListUI(data.cashiers);
                        } else {
                            showPosAlert(data.message || 'Gagal menghapus akun kasir.', 'Gagal', 'error');
                        }
                    })
                    .catch(err => showPosAlert('Terjadi kesalahan: ' + err.message, 'Gagal', 'error'));
            }

            // Render Ulang Daftar Kasir Secara Dinamis
            function refreshCashierListUI(cashiers) {
                const countBadge = document.getElementById('settingStaffBadgeCount');
                if (countBadge) countBadge.innerText = cashiers.length;

                const wrap = document.getElementById('cashierListWrap');
                if (!wrap) return;

                if (cashiers.length === 0) {
                    wrap.innerHTML = `
                                            <div id="cashierEmptyNotice" style="text-align:center; padding:2rem 1rem; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px;">
                                                <div style="width:40px; height:40px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 8px auto; color:#94a3b8;">
                                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                                </div>
                                                <div style="font-size:0.85rem; font-weight:700; color:#334155;">Belum Ada Akun Staf Kasir</div>
                                                <div style="font-size:0.72rem; color:#64748b; margin-top:2px;">Klik tombol "+ Tambah Staf Kasir" di atas untuk menambahkan staf yang bertugas.</div>
                                            </div>
                                        `;
                    return;
                }

                let html = '';
                cashiers.forEach(c => {
                    const initials = (c.name || 'KS').substring(0, 2).toUpperCase();
                    const isActive = !!c.is_active;
                    const statusBadge = isActive
                        ? `<span style="font-size:0.68rem; background:#dcfce7; color:#15803d; font-weight:700; padding:2px 7px; border-radius:999px;">Aktif</span>`
                        : `<span style="font-size:0.68rem; background:#f1f5f9; color:#64748b; font-weight:700; padding:2px 7px; border-radius:999px;">Nonaktif</span>`;
                    const toggleLabel = isActive ? 'Nonaktifkan' : 'Aktifkan';
                    const roleTitle = c.cashier_role_title || 'Kasir';
                    const shiftHtml = c.shift_hours
                        ? `<span style="display:inline-flex; align-items:center; gap:4px; color:#0369a1; background:#f0f9ff; padding:1px 6px; border-radius:4px; font-weight:600;"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>${escapeHtml(c.shift_hours)}</span>`
                        : '';

                    const cJson = JSON.stringify(c).replace(/"/g, '&quot;');

                    html += `
                                            <div class="cashier-card-item" id="cashierRow-${c.id}" style="background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:12px 14px; display:flex; align-items:center; justify-content:space-between; gap:12px; transition:all 0.2s ease;">
                                                <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                                                    <div style="width:38px; height:38px; border-radius:50%; background:#f1f5f9; border:1.5px solid #cbd5e1; display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:800; color:#334155; flex-shrink:0;">
                                                        ${initials}
                                                    </div>
                                                    <div style="min-width:0;">
                                                        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                                            <span style="font-size:0.88rem; font-weight:800; color:#0f172a;">${escapeHtml(c.name)}</span>
                                                            <span style="font-size:0.7rem; color:#0369a1; background:#f0f9ff; border:1px solid #bae6fd; padding:1px 6px; border-radius:4px; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                                                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                                                ${escapeHtml(c.email || c.username || '')}
                                                            </span>
                                                            ${statusBadge}
                                                        </div>
                                                        <div style="display:flex; align-items:center; gap:12px; font-size:0.72rem; color:#64748b; margin-top:3px; flex-wrap:wrap;">
                                                            <span style="display:inline-flex; align-items:center; gap:4px; color:#475569; font-weight:600;">
                                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                                ${escapeHtml(roleTitle)}
                                                            </span>
                                                            ${shiftHtml}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div style="display:flex; align-items:center; gap:6px; flex-shrink:0;">
                                                    <button type="button" class="btn-outline-bio" style="padding:4px 9px; font-size:0.72rem; border-radius:6px;" onclick="editCashierData(${cJson})" title="Edit Kasir">
                                                        Edit
                                                    </button>
                                                    <button type="button" style="padding:4px 9px; font-size:0.72rem; border-radius:6px; border:1px solid #e2e8f0; background:#f8fafc; color:#334155; cursor:pointer; font-weight:600;" onclick="toggleCashierActive(${c.id})" title="Aktifkan / Nonaktifkan">
                                                        ${toggleLabel}
                                                    </button>
                                                    <button type="button" style="padding:4px 8px; font-size:0.72rem; border-radius:6px; border:1px solid #fee2e2; background:#fff1f2; color:#e11d48; cursor:pointer; font-weight:700;" onclick="deleteCashier(${c.id}, '${escapeHtml(c.name)}')" title="Hapus Staf">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        `;
                });
                wrap.innerHTML = html;
            }

            function escapeHtml(str) {
                return (str || '').replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
            }

            // ═══════════════════════════════════════════════
            // TABLE MANAGEMENT (Kelola Meja & Barcode QR)
            // ═══════════════════════════════════════════════

            function toggleAddTableForm(show) {
                const box = document.getElementById('formAddTableBox');
                if (!box) return;
                if (show === undefined) {
                    box.style.display = box.style.display === 'none' ? 'block' : 'none';
                } else {
                    box.style.display = show ? 'block' : 'none';
                }
                if (box.style.display === 'block') {
                    document.getElementById('newTableName')?.focus();
                }
            }

            function confirmAddTable() {
                const nameInput = document.getElementById('newTableName');
                const seatsInput = document.getElementById('newTableSeats');
                const areaInput = document.getElementById('newTableArea');

                const name = (nameInput?.value || '').trim();
                if (!name) {
                    showPosAlert('Silakan isi Nama atau Nomor Meja.', 'Nama Meja Kosong', 'warning');
                    nameInput?.focus();
                    return;
                }

                const seats = parseInt(seatsInput?.value) || 4;
                const area = (areaInput?.value || '').trim();

                if (!Array.isArray(posTablesData)) posTablesData = [];

                // Cek duplikasi nama meja
                const exists = posTablesData.some(t => (t.name || '').toLowerCase() === name.toLowerCase());
                if (exists) {
                    showPosAlert('Nama meja "' + name + '" sudah ada. Gunakan nama yang berbeda.', 'Meja Sudah Ada', 'warning');
                    return;
                }

                posTablesData.push({
                    id: 'table_' + Date.now(),
                    name: name,
                    seats: seats,
                    area: area,
                    created_at: new Date().toISOString()
                });

                // Reset form
                if (nameInput) nameInput.value = '';
                if (seatsInput) seatsInput.value = '4';
                if (areaInput) areaInput.value = '';
                toggleAddTableForm(false);

                renderTablesSettingsList();
                renderTablesQuickSelect();
                updateTablesBadgeCount();
                saveTablesSettings(false);
                showPosToast('Meja "' + name + '" berhasil ditambahkan!', 'success');
            }

            function deleteTable(idx) {
                if (!posTablesData || !posTablesData[idx]) return;
                const table = posTablesData[idx];
                if (confirm('Hapus ' + (table.name || 'meja ini') + '? Barcode QR meja ini tidak akan bisa digunakan lagi.')) {
                    posTablesData.splice(idx, 1);
                    renderTablesSettingsList();
                    renderTablesQuickSelect();
                    updateTablesBadgeCount();
                    saveTablesSettings(false);
                    showPosToast('Meja berhasil dihapus.', 'info');
                }
            }

            function updateTablesBadgeCount() {
                const count = (posTablesData || []).length;
                const badge = document.getElementById('settingTablesBadgeCount');
                if (badge) badge.innerText = count;
            }

            function renderTablesSettingsList() {
                const container = document.getElementById('tableRowsListContainer');
                if (!container) return;

                if (!posTablesData || posTablesData.length === 0) {
                    container.innerHTML = `
                                            <div style="text-align:center; padding:2.5rem 1rem; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px;">
                                                <div style="width:44px; height:44px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin:0 auto 10px auto; color:#94a3b8;">
                                                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                                        <path d="M3 9h18M9 21V9" />
                                                    </svg>
                                                </div>
                                                <div style="font-size:0.88rem; font-weight:700; color:#334155;">Belum Ada Meja Ditambahkan</div>
                                                <div style="font-size:0.75rem; color:#64748b; margin-top:3px; max-width:380px; margin-left:auto; margin-right:auto;">
                                                    Klik tombol <strong>+ Tambah Meja</strong> di atas untuk membuat meja dan men-download barcode QR tiap meja.
                                                </div>
                                            </div>
                                        `;
                    updateTablesBadgeCount();
                    return;
                }


// Initial tab activation on load
document.addEventListener('DOMContentLoaded', function() {
    const initialTab = {!! json_encode($activeTab ?? 'struk') !!};
    switchSettingTab(initialTab);
});
</script>

<script>
/* ── POS Smooth Slide Transition ── */
(function(){
    var navItems = document.querySelectorAll('.pos-nav-item');
    var activeIdx = Array.from(navItems).findIndex(function(el){ return el.classList.contains('active'); });
    navItems.forEach(function(item, idx){
        item.addEventListener('click', function(){
            if(this.classList.contains('active')) return;
            sessionStorage.setItem('posNavDir', idx > activeIdx ? 'right' : 'left');
        });
    });
    var dir = sessionStorage.getItem('posNavDir');
    sessionStorage.removeItem('posNavDir');
    var wrap = document.querySelector('.ps-wrap');
    if(wrap && dir){
        var from = dir === 'right' ? '28px' : '-28px';
        wrap.style.opacity = '0';
        wrap.style.transform = 'translateX('+from+')';
        wrap.style.transition = 'none';
        requestAnimationFrame(function(){
            requestAnimationFrame(function(){
                wrap.style.opacity = '1';
                wrap.style.transform = 'translateX(0)';
                wrap.style.transition = 'opacity 0.28s cubic-bezier(0.16,1,0.3,1),transform 0.28s cubic-bezier(0.16,1,0.3,1)';
            });
        });
    }
})();
</script>
@endsection