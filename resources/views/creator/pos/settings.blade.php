@extends('creator.layout')

@section('title', 'Pengaturan Kasir POS – Creator Studio')
@section('page_title', 'Pengaturan POS')
@section('page_subtitle', 'Konfigurasi toko, struk, metode bayar, pajak, diskon, dan staf kasir.')

@section('styles')
    <style>
        :root {
            --pos-green: #1eb349;
            --pos-green-dark: #16933b;
            --pos-gradient: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
        }

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
            max-width: 460px !important;
            box-sizing: border-box !important;
            background: transparent !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        .cr-topbar-header-row {
            display: none !important;
        }

        .settings-hub-container {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }

        /* Top Header Bar */
        .menu-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.1rem;
        }

        .menu-header-left {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            min-width: 0;
        }

        .menu-header-title {
            font-size: 1.12rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.2;
        }

        .menu-header-sub {
            font-size: 0.72rem;
            color: #64748b;
            margin-top: 2px;
        }

        .menu-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            text-decoration: none;
            transition: all 0.18s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            flex-shrink: 0;
            cursor: pointer;
        }

        .menu-icon-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        /* ── Horizontal Pill Tabs (Compact, Clean & Scrollable) ── */
        .pos-settings-tabs-scroll {
            display: flex;
            gap: 0.45rem;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-bottom: 0.85rem;
            margin-bottom: 0.35rem;
        }

        .pos-settings-tabs-scroll::-webkit-scrollbar {
            display: none;
        }

        .tab-pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.76rem;
            font-weight: 600;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            flex-shrink: 0;
        }

        .tab-pill-btn:hover {
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .tab-pill-btn.active {
            background: #1eb349 !important;
            color: #ffffff !important;
            border-color: #1eb349 !important;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.25) !important;
        }

        /* ── Standard Group Card (100% Identical to Profile) ── */
        .settings-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            margin-bottom: 1rem;
            padding: 1.15rem;
            box-sizing: border-box;
        }

        .card-sec-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.95rem;
        }

        .card-sec-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .card-sec-badge {
            font-size: 0.68rem;
            font-weight: 600;
            padding: 0.15rem 0.55rem;
            border-radius: 999px;
            background: #ecfdf5;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        /* Input Controls (Sleek, Clean, Modern) */
        .form-group-clean {
            margin-bottom: 0.85rem;
        }

        .form-label-clean {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.35rem;
        }

        .form-control-clean {
            width: 100%;
            height: 38px;
            padding: 0 0.85rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #0f172a;
            font-size: 0.82rem;
            outline: none;
            box-sizing: border-box;
            transition: all 0.15s ease;
            font-family: inherit;
        }

        .form-control-clean:focus {
            border-color: #1eb349;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
        }

        textarea.form-control-clean {
            height: auto;
            padding: 0.55rem 0.85rem;
            resize: vertical;
        }

        .btn-green-pill {
            width: 100%;
            height: 40px;
            border-radius: 999px;
            background: linear-gradient(135deg, #1eb349 0%, #16933b 100%);
            color: #ffffff;
            border: none;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.25);
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-green-pill:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(30, 179, 73, 0.35);
        }

        .btn-white-pill {
            height: 34px;
            padding: 0 0.85rem;
            border-radius: 999px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 0.74rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-white-pill:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        /* Item Row in List (Fee, Discount, Table, Cashier) */
        .list-row-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0.85rem;
            background: #f8fafc;
            border-radius: 14px;
            border: 1px solid #f1f5f9;
            margin-bottom: 0.55rem;
            gap: 0.5rem;
        }

        .list-row-left {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            min-width: 0;
            flex: 1;
        }

        .list-row-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .list-row-sub {
            font-size: 0.68rem;
            color: #64748b;
            margin-top: 1px;
        }

        /* Toggle Switch (Sleek iOS style) */
        .switch-clean {
            position: relative;
            display: inline-block;
            width: 36px;
            height: 20px;
            flex-shrink: 0;
        }

        .switch-clean input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider-clean {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #cbd5e1;
            transition: .2s;
            border-radius: 20px;
        }

        .slider-clean:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .2s;
            border-radius: 50%;
        }

        input:checked + .slider-clean {
            background-color: #1eb349;
        }

        input:checked + .slider-clean:before {
            transform: translateX(16px);
        }

        /* Bottom Nav (Identical to profile.blade.php) */
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
            height: 48px;
            width: 48px;
            border-radius: 50%;
            text-decoration: none;
        }

        .pos-nav-item.active {
            color: #1eb349;
            background: #f0fdf4;
        }

        .pos-nav-label {
            display: none !important;
        }

        /* Notification Toast */
        #settingsToast {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s ease;
            z-index: 999999;
            background: #0f172a;
            color: #ffffff;
            padding: 0.65rem 1.15rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            pointer-events: none;
            white-space: nowrap;
        }

        #settingsToast.active {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
    </style>
@endsection

@section('content')
    <div class="settings-hub-container">
        {{-- Header Bar --}}
        <div class="menu-header-bar">
            <div class="menu-header-left">
                <a href="{{ route('creator.pos.profile') }}" class="menu-icon-btn" title="Kembali ke Menu Profil">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </a>
                <div>
                    <h1 class="menu-header-title">Pengaturan Kasir</h1>
                    <div class="menu-header-sub">Struk, pembayaran, pajak, dan staf</div>
                </div>
            </div>
            <a href="{{ route('creator.pos.index') }}" class="menu-icon-btn" title="Masuk ke Kasir POS">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                    <line x1="2" y1="10" x2="22" y2="10"></line>
                </svg>
            </a>
        </div>

        {{-- Horizontal Pill Navigation --}}
        <div class="pos-settings-tabs-scroll">
            <button type="button" class="tab-pill-btn active" onclick="switchSettingTab('struk', this)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Struk
            </button>
            <button type="button" class="tab-pill-btn" onclick="switchSettingTab('bayar', this)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Pembayaran
            </button>
            <button type="button" class="tab-pill-btn" onclick="switchSettingTab('pajak', this)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                Pajak &amp; Biaya
            </button>
            <button type="button" class="tab-pill-btn" onclick="switchSettingTab('diskon', this)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                Diskon
            </button>
            <button type="button" class="tab-pill-btn" onclick="switchSettingTab('meja', this)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                Meja
            </button>
            <button type="button" class="tab-pill-btn" onclick="switchSettingTab('staf', this)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Staf Kasir
            </button>
        </div>

        {{-- ── TAB 1: STRUK & IDENTITAS TOKO ── --}}
        <div id="tab-pane-struk" class="setting-pane">
            <div class="settings-card">
                <div class="card-sec-head">
                    <h3 class="card-sec-title">Logo Toko di Struk</h3>
                </div>

                <div style="display: flex; align-items: center; gap: 0.85rem; margin-bottom: 0.85rem;">
                    <div id="logoPreviewWrap" style="width: 56px; height: 56px; border-radius: 14px; border: 1.5px dashed #cbd5e1; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                        @if($posLogoUrl)
                            <img src="{{ $posLogoUrl }}" alt="Logo" id="logoPreviewImg" style="width: 100%; height: 100%; object-fit: contain;">
                        @else
                            <svg id="logoPreviewSvg" width="22" height="22" fill="none" stroke="#94a3b8" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        @endif
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <input type="file" id="posLogoInput" accept="image/*" style="display: none;" onchange="uploadLogoAjax(this)">
                        <div style="display: flex; gap: 0.45rem; flex-wrap: wrap;">
                            <button type="button" class="btn-white-pill" onclick="document.getElementById('posLogoInput').click()">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Ganti Logo
                            </button>
                            <button type="button" class="btn-white-pill" id="btnDeleteLogo" style="color: #dc2626; border-color: #fecaca; {{ empty($posLogoUrl) ? 'display:none;' : '' }}" onclick="deleteLogoAjax()">
                                Hapus
                            </button>
                        </div>
                        <div style="font-size: 0.65rem; color: #94a3b8; margin-top: 3px;">JPG/PNG transparan maks 2MB</div>
                    </div>
                </div>

                <div class="form-group-clean">
                    <label class="form-label-clean">Nama Toko di Struk</label>
                    <input type="text" id="settingStoreName" class="form-control-clean" value="{{ $storeName }}" placeholder="Nama Brand / Usaha">
                </div>

                <div class="form-group-clean">
                    <label class="form-label-clean">Alamat Toko</label>
                    <textarea id="settingStoreAddress" class="form-control-clean" rows="2" placeholder="Alamat cabang atau outlet">{{ $posStoreAddress }}</textarea>
                </div>

                <div class="form-group-clean">
                    <label class="form-label-clean">No. Telepon / WhatsApp</label>
                    <input type="text" id="settingStorePhone" class="form-control-clean" value="{{ $posStorePhone }}" placeholder="Contoh: 08123456789">
                </div>

                <button type="button" class="btn-green-pill" onclick="saveStrukInfoSettings()">
                    Simpan Perubahan Struk
                </button>
            </div>
        </div>

        {{-- ── TAB 2: METODE PEMBAYARAN ── --}}
        <div id="tab-pane-bayar" class="setting-pane" style="display: none;">
            <div class="settings-card">
                <div class="card-sec-head">
                    <h3 class="card-sec-title">Mode Pembayaran Non-Tunai</h3>
                </div>

                <div class="form-group-clean">
                    <label class="form-label-clean">Sistem QRIS / Non-Tunai</label>
                    <select id="settingCashlessMode" class="form-control-clean" onchange="toggleQrisUploadVisibility(this.value)">
                        <option value="snap_midtrans" {{ $posCashlessMode === 'snap_midtrans' ? 'selected' : '' }}>Otomatis (QRIS Dinamis Midtrans)</option>
                        <option value="qris_static" {{ $posCashlessMode === 'qris_static' ? 'selected' : '' }}>Statis (Upload Gambar QRIS Sendiri)</option>
                    </select>
                </div>

                {{-- QRIS Statis Upload --}}
                <div id="qrisStaticWrap" style="margin-bottom: 0.85rem; {{ $posCashlessMode === 'qris_static' ? '' : 'display:none;' }}">
                    <label class="form-label-clean">Gambar QRIS Toko</label>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div id="qrisPreviewWrap" style="width: 52px; height: 52px; border-radius: 12px; border: 1px solid #e2e8f0; background: #ffffff; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                            @if($posQrisUrl)
                                <img src="{{ $posQrisUrl }}" alt="QRIS" id="qrisPreviewImg" style="width: 100%; height: 100%; object-fit: contain;">
                            @else
                                <svg width="22" height="22" fill="none" stroke="#94a3b8" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            @endif
                        </div>
                        <div>
                            <input type="file" id="posQrisInput" accept="image/*" style="display: none;" onchange="uploadQrisAjax(this)">
                            <button type="button" class="btn-white-pill" onclick="document.getElementById('posQrisInput').click()">
                                Upload Gambar QRIS
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group-clean">
                    <label class="form-label-clean">Penanggung Biaya Sistem (Rp 500 + 0.5%)</label>
                    <select id="settingFeePayer" class="form-control-clean">
                        <option value="seller" {{ ($posFeePayer ?? 'seller') === 'seller' ? 'selected' : '' }}>Penjual (Potong dari Deposit POS)</option>
                        <option value="buyer" {{ ($posFeePayer ?? 'seller') === 'buyer' ? 'selected' : '' }}>Pembeli (Ditambahkan ke Tagihan)</option>
                    </select>
                </div>

                <button type="button" class="btn-green-pill" onclick="savePaymentSettings()">
                    Simpan Pengaturan Pembayaran
                </button>
            </div>
        </div>

        {{-- ── TAB 3: PAJAK & BIAYA TAMBAHAN ── --}}
        <div id="tab-pane-pajak" class="setting-pane" style="display: none;">
            <div class="settings-card">
                <div class="card-sec-head">
                    <h3 class="card-sec-title">Daftar Pajak &amp; Biaya</h3>
                    <span class="card-sec-badge" id="feesBadgeCount">{{ count($posFees) }} Item</span>
                </div>

                <div id="posFeesListContainer">
                    {{-- Render via JS --}}
                </div>

                <div style="margin-top: 0.85rem; padding-top: 0.85rem; border-top: 1px dashed #e2e8f0;">
                    <div style="font-size: 0.76rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Tambah Pajak / Biaya Baru</div>
                    <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 0.35rem; margin-bottom: 0.5rem;">
                        <input type="text" id="newFeeName" class="form-control-clean" placeholder="Nama (e.g. PPN)">
                        <select id="newFeeType" class="form-control-clean">
                            <option value="percent">Persen (%)</option>
                            <option value="nominal">Nominal (Rp)</option>
                        </select>
                        <input type="number" id="newFeeValue" class="form-control-clean" placeholder="Nilai (e.g. 11)">
                    </div>
                    <button type="button" class="btn-white-pill" style="width: 100%;" onclick="addNewFeeItem()">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambahkan ke Daftar
                    </button>
                </div>

                <div style="margin-top: 1rem;">
                    <button type="button" class="btn-green-pill" onclick="saveFeesAjax()">
                        Simpan Pajak &amp; Biaya
                    </button>
                </div>
            </div>
        </div>

        {{-- ── TAB 4: DISKON ── --}}
        <div id="tab-pane-diskon" class="setting-pane" style="display: none;">
            <div class="settings-card">
                <div class="card-sec-head">
                    <h3 class="card-sec-title">Preset Diskon POS</h3>
                    <span class="card-sec-badge" id="discountsBadgeCount">{{ count($posDiscounts) }} Preset</span>
                </div>

                <div id="posDiscountsListContainer">
                    {{-- Render via JS --}}
                </div>

                <div style="margin-top: 0.85rem; padding-top: 0.85rem; border-top: 1px dashed #e2e8f0;">
                    <div style="font-size: 0.76rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Tambah Preset Diskon Baru</div>
                    <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 0.35rem; margin-bottom: 0.5rem;">
                        <input type="text" id="newDiscName" class="form-control-clean" placeholder="Nama Diskon">
                        <select id="newDiscType" class="form-control-clean">
                            <option value="percent">Persen (%)</option>
                            <option value="nominal">Nominal (Rp)</option>
                        </select>
                        <input type="number" id="newDiscValue" class="form-control-clean" placeholder="Nilai">
                    </div>
                    <button type="button" class="btn-white-pill" style="width: 100%;" onclick="addNewDiscountItem()">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambahkan Preset Diskon
                    </button>
                </div>

                <div style="margin-top: 1rem;">
                    <button type="button" class="btn-green-pill" onclick="saveDiscountsAjax()">
                        Simpan Preset Diskon
                    </button>
                </div>
            </div>
        </div>

        {{-- ── TAB 5: MEJA ── --}}
        <div id="tab-pane-meja" class="setting-pane" style="display: none;">
            <div class="settings-card">
                <div class="card-sec-head">
                    <h3 class="card-sec-title">Daftar Meja Kasir</h3>
                    <span class="card-sec-badge" id="tablesBadgeCount">{{ count($posTables) }} Meja</span>
                </div>

                <div id="posTablesListContainer">
                    {{-- Render via JS --}}
                </div>

                <div style="margin-top: 0.85rem; padding-top: 0.85rem; border-top: 1px dashed #e2e8f0;">
                    <div style="display: flex; gap: 0.45rem;">
                        <input type="text" id="newTableName" class="form-control-clean" placeholder="Nomor / Nama Meja (e.g. Meja 01)">
                        <button type="button" class="btn-white-pill" style="flex-shrink: 0;" onclick="addNewTableItem()">
                            Tambah
                        </button>
                    </div>
                </div>

                <div style="margin-top: 1rem;">
                    <button type="button" class="btn-green-pill" onclick="saveTablesAjax()">
                        Simpan Daftar Meja
                    </button>
                </div>
            </div>
        </div>

        {{-- ── TAB 6: STAF KASIR ── --}}
        <div id="tab-pane-staf" class="setting-pane" style="display: none;">
            <div class="settings-card">
                <div class="card-sec-head">
                    <h3 class="card-sec-title">Staf &amp; Kasir Aktif</h3>
                    <span class="card-sec-badge">{{ count($cashiers) }} Kasir</span>
                </div>

                <div id="cashiersListWrap">
                    @forelse($cashiers as $c)
                        <div class="list-row-item">
                            <div class="list-row-left">
                                <div style="width: 32px; height: 32px; border-radius: 50%; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700; flex-shrink: 0;">
                                    {{ strtoupper(substr($c->name, 0, 1)) }}
                                </div>
                                <div style="min-width: 0;">
                                    <div class="list-row-name">{{ $c->name }}</div>
                                    <div class="list-row-sub">{{ $c->shift_hours ?? 'Shift Standar' }} &middot; {{ $c->email }}</div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.4rem;">
                                <label class="switch-clean">
                                    <input type="checkbox" {{ $c->is_active ? 'checked' : '' }} onchange="toggleCashierStatus({{ $c->id }})">
                                    <span class="slider-clean"></span>
                                </label>
                                <button type="button" class="btn-white-pill" style="color: #dc2626; border-color: #fecaca; padding: 0 0.5rem; height: 28px;" onclick="deleteCashierAjax({{ $c->id }}, '{{ addslashes($c->name) }}')">
                                    &times;
                                </button>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: #94a3b8; font-size: 0.75rem; padding: 1rem 0;">
                            Belum ada staf kasir tambahan.
                        </div>
                    @endforelse
                </div>

                {{-- Form Tambah Kasir --}}
                <div style="margin-top: 0.85rem; padding-top: 0.85rem; border-top: 1px dashed #e2e8f0;">
                    <div style="font-size: 0.76rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">Tambah Staf Kasir Baru</div>
                    <div class="form-group-clean">
                        <label class="form-label-clean">Nama Lengkap</label>
                        <input type="text" id="addStaffName" class="form-control-clean" placeholder="Nama Kasir">
                    </div>
                    <div class="form-group-clean">
                        <label class="form-label-clean">Email Login</label>
                        <input type="email" id="addStaffEmail" class="form-control-clean" placeholder="email@toko.com">
                    </div>
                    <div class="form-group-clean">
                        <label class="form-label-clean">Kata Sandi (Min 6 Karakter)</label>
                        <input type="password" id="addStaffPassword" class="form-control-clean" placeholder="Password akun kasir">
                    </div>
                    <div class="form-group-clean">
                        <label class="form-label-clean">Jam Shift</label>
                        <input type="text" id="addStaffShift" class="form-control-clean" value="08:00 - 16:00" placeholder="08:00 - 16:00">
                    </div>
                    <button type="button" class="btn-green-pill" onclick="saveNewCashierAjax()">
                        Simpan Akun Kasir Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Navigation (100% Sesuai Profile) --}}
    <div class="pos-bottom-nav-wrap">
        <div class="pos-bottom-nav-bar">
            <a href="{{ route('creator.pos.index') }}" class="pos-nav-item" title="Home">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
            </a>
            <a href="{{ route('creator.pos.index', ['screen' => 'transactions']) }}" class="pos-nav-item" title="Transaksi">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="2" y="4" width="20" height="16" rx="2" />
                    <circle cx="12" cy="12" r="3" />
                    <line x1="2" y1="9" x2="6" y2="9" />
                    <line x1="18" y1="15" x2="22" y2="15" />
                </svg>
            </a>
            <a href="{{ route('creator.pos.reports') }}" class="pos-nav-item" title="Laporan">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="18" y1="20" x2="18" y2="10" />
                    <line x1="12" y1="20" x2="12" y2="4" />
                    <line x1="6" y1="20" x2="6" y2="14" />
                </svg>
            </a>
            <a href="{{ route('creator.pos.profile') }}" class="pos-nav-item active" title="Profil">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </a>
        </div>
    </div>

    {{-- Notification Toast --}}
    <div id="settingsToast">Pengaturan berhasil disimpan</div>
@endsection

@section('scripts')
    <script>
        const csrfToken = '{{ csrf_token() }}';
        let posFeesData = {!! json_encode($posFees ?? []) !!};
        let posDiscountsData = {!! json_encode($posDiscounts ?? []) !!};
        let posTablesData = {!! json_encode($posTables ?? []) !!};

        function showToast(msg) {
            const t = document.getElementById('settingsToast');
            if (!t) return;
            t.innerText = msg;
            t.classList.add('active');
            setTimeout(() => t.classList.remove('active'), 2500);
        }

        // Tab Switching
        function switchSettingTab(tab, btn) {
            document.querySelectorAll('.tab-pill-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');
            document.querySelectorAll('.setting-pane').forEach(p => p.style.display = 'none');
            const target = document.getElementById('tab-pane-' + tab);
            if (target) target.style.display = 'block';
        }

        function toggleQrisUploadVisibility(mode) {
            const wrap = document.getElementById('qrisStaticWrap');
            if (wrap) wrap.style.display = (mode === 'qris_static') ? 'block' : 'none';
        }

        // ── 1. Struk & Logo ──
        function saveStrukInfoSettings() {
            const storeName = document.getElementById('settingStoreName').value.trim();
            const storeAddress = document.getElementById('settingStoreAddress').value.trim();
            const storePhone = document.getElementById('settingStorePhone').value.trim();

            fetch('{{ route("creator.pos.update-settings") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    store_name: storeName,
                    pos_store_address: storeAddress,
                    pos_store_phone: storePhone
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('Pengaturan struk berhasil disimpan!');
                } else {
                    alert(data.message || 'Gagal menyimpan.');
                }
            })
            .catch(() => alert('Terjadi kesalahan jaringan.'));
        }

        function uploadLogoAjax(input) {
            if (!input.files || !input.files[0]) return;
            const fd = new FormData();
            fd.append('logo', input.files[0]);
            fd.append('_token', csrfToken);

            fetch('{{ route("creator.pos.upload-logo") }}', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.logo_url) {
                    const wrap = document.getElementById('logoPreviewWrap');
                    wrap.innerHTML = `<img src="${data.logo_url}" alt="Logo" style="width:100%;height:100%;object-fit:contain;">`;
                    document.getElementById('btnDeleteLogo').style.display = 'inline-flex';
                    showToast('Logo struk berhasil dipasang!');
                } else {
                    alert(data.message || 'Gagal upload logo.');
                }
            })
            .catch(() => alert('Gagal upload logo.'));
        }

        function deleteLogoAjax() {
            if (!confirm('Hapus logo struk?')) return;
            fetch('{{ route("creator.pos.remove-logo") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const wrap = document.getElementById('logoPreviewWrap');
                    wrap.innerHTML = `<svg width="22" height="22" fill="none" stroke="#94a3b8" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>`;
                    document.getElementById('btnDeleteLogo').style.display = 'none';
                    showToast('Logo struk berhasil dihapus.');
                }
            });
        }

        // ── 2. Pembayaran ──
        function savePaymentSettings() {
            const cashlessMode = document.getElementById('settingCashlessMode').value;
            const feePayer = document.getElementById('settingFeePayer').value;

            fetch('{{ route("creator.pos.update-settings") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    pos_cashless_mode: cashlessMode,
                    pos_fee_payer: feePayer
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('Pengaturan pembayaran berhasil disimpan!');
                }
            })
            .catch(() => alert('Terjadi kesalahan jaringan.'));
        }

        function uploadQrisAjax(input) {
            if (!input.files || !input.files[0]) return;
            const fd = new FormData();
            fd.append('qris_image', input.files[0]);
            fd.append('_token', csrfToken);

            fetch('{{ route("creator.pos.upload-qris") }}', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.qris_url) {
                    const wrap = document.getElementById('qrisPreviewWrap');
                    wrap.innerHTML = `<img src="${data.qris_url}" alt="QRIS" style="width:100%;height:100%;object-fit:contain;">`;
                    showToast('QRIS toko berhasil dipasang!');
                }
            });
        }

        // ── 3. Pajak & Biaya ──
        function renderFees() {
            const c = document.getElementById('posFeesListContainer');
            if (!c) return;
            if (!posFeesData || posFeesData.length === 0) {
                c.innerHTML = `<div style="text-align:center; color:#94a3b8; font-size:0.75rem; padding:0.75rem 0;">Belum ada pajak atau biaya tambahan.</div>`;
                return;
            }
            c.innerHTML = posFeesData.map((f, i) => `
                <div class="list-row-item">
                    <div class="list-row-left">
                        <div>
                            <div class="list-row-name">${escapeHtml(f.name)}</div>
                            <div class="list-row-sub">${f.type === 'percent' ? f.value + '%' : 'Rp ' + Number(f.value).toLocaleString('id-ID')}</div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:0.4rem;">
                        <label class="switch-clean">
                            <input type="checkbox" ${f.is_active ? 'checked' : ''} onchange="toggleFeeItem(${i}, this.checked)">
                            <span class="slider-clean"></span>
                        </label>
                        <button type="button" class="btn-white-pill" style="color:#dc2626; border-color:#fecaca; padding:0 0.5rem; height:28px;" onclick="removeFeeItem(${i})">&times;</button>
                    </div>
                </div>
            `).join('');
            document.getElementById('feesBadgeCount').innerText = posFeesData.length + ' Item';
        }

        function toggleFeeItem(i, checked) {
            if (posFeesData[i]) posFeesData[i].is_active = checked;
        }

        function removeFeeItem(i) {
            posFeesData.splice(i, 1);
            renderFees();
        }

        function addNewFeeItem() {
            const name = document.getElementById('newFeeName').value.trim();
            const type = document.getElementById('newFeeType').value;
            const val = parseFloat(document.getElementById('newFeeValue').value) || 0;
            if (!name) return alert('Masukkan nama pajak atau biaya.');
            posFeesData.push({ name: name, type: type, value: val, is_active: true });
            document.getElementById('newFeeName').value = '';
            document.getElementById('newFeeValue').value = '';
            renderFees();
        }

        function saveFeesAjax() {
            fetch('{{ route("creator.pos.update-settings") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ pos_fees: posFeesData })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) showToast('Pajak & biaya berhasil disimpan!');
            });
        }

        // ── 4. Diskon ──
        function renderDiscounts() {
            const c = document.getElementById('posDiscountsListContainer');
            if (!c) return;
            if (!posDiscountsData || posDiscountsData.length === 0) {
                c.innerHTML = `<div style="text-align:center; color:#94a3b8; font-size:0.75rem; padding:0.75rem 0;">Belum ada preset diskon.</div>`;
                return;
            }
            c.innerHTML = posDiscountsData.map((d, i) => `
                <div class="list-row-item">
                    <div class="list-row-left">
                        <div>
                            <div class="list-row-name">${escapeHtml(d.name)}</div>
                            <div class="list-row-sub">${d.type === 'percent' ? d.value + '%' : 'Rp ' + Number(d.value).toLocaleString('id-ID')} &middot; ${d.scope === 'specific' ? 'Produk Tertentu' : 'Semua Produk'}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-white-pill" style="color:#dc2626; border-color:#fecaca; padding:0 0.5rem; height:28px;" onclick="removeDiscountItem(${i})">&times;</button>
                </div>
            `).join('');
            document.getElementById('discountsBadgeCount').innerText = posDiscountsData.length + ' Preset';
        }

        function removeDiscountItem(i) {
            posDiscountsData.splice(i, 1);
            renderDiscounts();
        }

        function addNewDiscountItem() {
            const name = document.getElementById('newDiscName').value.trim();
            const type = document.getElementById('newDiscType').value;
            const val = parseFloat(document.getElementById('newDiscValue').value) || 0;
            if (!name) return alert('Masukkan nama diskon.');
            posDiscountsData.push({ name: name, type: type, value: val, scope: 'all', product_ids: [] });
            document.getElementById('newDiscName').value = '';
            document.getElementById('newDiscValue').value = '';
            renderDiscounts();
        }

        function saveDiscountsAjax() {
            fetch('{{ route("creator.pos.update-settings") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ pos_discounts: posDiscountsData })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) showToast('Preset diskon berhasil disimpan!');
            });
        }

        // ── 5. Meja ──
        function renderTables() {
            const c = document.getElementById('posTablesListContainer');
            if (!c) return;
            if (!posTablesData || posTablesData.length === 0) {
                c.innerHTML = `<div style="text-align:center; color:#94a3b8; font-size:0.75rem; padding:0.75rem 0;">Belum ada meja.</div>`;
                return;
            }
            c.innerHTML = posTablesData.map((t, i) => `
                <div class="list-row-item">
                    <div class="list-row-name">${escapeHtml(t)}</div>
                    <button type="button" class="btn-white-pill" style="color:#dc2626; border-color:#fecaca; padding:0 0.5rem; height:28px;" onclick="removeTableItem(${i})">&times;</button>
                </div>
            `).join('');
            document.getElementById('tablesBadgeCount').innerText = posTablesData.length + ' Meja';
        }

        function removeTableItem(i) {
            posTablesData.splice(i, 1);
            renderTables();
        }

        function addNewTableItem() {
            const val = document.getElementById('newTableName').value.trim();
            if (!val) return alert('Masukkan nomor atau nama meja.');
            posTablesData.push(val);
            document.getElementById('newTableName').value = '';
            renderTables();
        }

        function saveTablesAjax() {
            fetch('{{ route("creator.pos.update-settings") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ pos_tables: posTablesData })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) showToast('Daftar meja berhasil disimpan!');
            });
        }

        // ── 6. Staf Kasir ──
        function toggleCashierStatus(id) {
            fetch('{{ url("creator/pos/cashiers") }}/' + id + '/toggle', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) showToast('Status kasir berhasil diubah.');
            });
        }

        function deleteCashierAjax(id, name) {
            if (!confirm('Hapus staf ' + name + '?')) return;
            fetch('{{ url("creator/pos/cashiers") }}/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('Staf kasir berhasil dihapus.');
                    location.reload();
                }
            });
        }

        function saveNewCashierAjax() {
            const name = document.getElementById('addStaffName').value.trim();
            const email = document.getElementById('addStaffEmail').value.trim();
            const password = document.getElementById('addStaffPassword').value.trim();
            const shift = document.getElementById('addStaffShift').value.trim();

            if (!name || !email || !password) return alert('Nama, email, dan kata sandi wajib diisi.');

            fetch('{{ route("creator.pos.cashiers.save") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    password: password,
                    shift_hours: shift,
                    cashier_role_title: 'Kasir',
                    is_active: 1
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('Staf kasir baru berhasil ditambahkan!');
                    location.reload();
                } else {
                    alert(data.message || 'Gagal menambahkan staf.');
                }
            });
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
        }

        document.addEventListener('DOMContentLoaded', function () {
            renderFees();
            renderDiscounts();
            renderTables();

            // Support query tab parameter
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab');
            if (activeTab) {
                const btn = Array.from(document.querySelectorAll('.tab-pill-btn')).find(b => b.innerText.toLowerCase().includes(activeTab.toLowerCase()));
                if (btn) switchSettingTab(activeTab, btn);
            }
        });
    </script>
@endsection
