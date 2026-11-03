@extends('creator.layout')

@section('title', 'Pengaturan Kasir POS – Creator Studio')
@section('page_title', 'Pengaturan POS')
@section('page_subtitle', 'Kelola profil struk, logo toko, pajak, diskon, staf kasir, dan pembayaran.')

@section('styles')
<style>
    :root {
        --pos-green: #1eb349;
        --pos-green-dark: #16933b;
        --pos-gradient: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
    }

    body {
        background-color: #f8fafc !important;
    }

    .pos-set-container {
        max-width: 580px;
        margin: 0 auto;
        padding: 0.75rem 0.85rem 6.5rem 0.85rem;
        min-height: 100vh;
    }

    /* Top Navigation Header */
    .pos-set-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.85rem;
        gap: 0.5rem;
    }

    .pos-set-header-left {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .pos-set-back-btn {
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
    }

    .pos-set-back-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .pos-set-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
    }

    .pos-set-subtitle {
        font-size: 0.72rem;
        color: #64748b;
        margin: 2px 0 0 0;
    }

    /* Horizontal Pill Tabs */
    .pos-set-nav {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        overflow-x: auto;
        padding-bottom: 0.45rem;
        margin-bottom: 0.85rem;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .pos-set-nav::-webkit-scrollbar {
        display: none;
    }

    .pos-set-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .pos-set-tab-btn.active {
        background: var(--pos-gradient);
        border-color: transparent;
        color: #ffffff !important;
        box-shadow: 0 3px 10px rgba(30, 179, 73, 0.3);
    }

    /* Content Card */
    .pos-set-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 1.15rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        margin-bottom: 1rem;
    }

    .pos-form-group {
        margin-bottom: 0.9rem;
    }

    .pos-form-group:last-child {
        margin-bottom: 0;
    }

    .pos-form-label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.35rem;
    }

    .pos-form-input, .pos-form-textarea, .pos-form-select {
        width: 100%;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.55rem 0.85rem;
        font-size: 0.82rem;
        color: #0f172a;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.15s ease, background 0.15s ease;
        font-family: inherit;
    }

    .pos-form-input:focus, .pos-form-textarea:focus, .pos-form-select:focus {
        border-color: var(--pos-green);
        background: #ffffff;
    }

    .pos-btn-save {
        width: 100%;
        background: var(--pos-gradient);
        color: #ffffff;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 0.75rem 1rem;
        border-radius: 14px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
        transition: transform 0.1s ease, box-shadow 0.1s ease;
    }

    .pos-btn-save:active {
        transform: scale(0.98);
    }

    .pos-btn-save:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Logo Uploader */
    .pos-logo-box {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        border-radius: 16px;
        padding: 0.85rem;
        margin-bottom: 0.9rem;
    }

    .pos-logo-preview {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .pos-logo-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* Switch toggle */
    .pos-switch-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 0.85rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 0.5rem;
        cursor: pointer;
    }

    /* Cashier & Table list card */
    .pos-row-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 0.85rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        margin-bottom: 0.5rem;
    }

    /* POS Bottom Navigation Bar */
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
    }

    .pos-bottom-nav-bar {
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
        text-decoration: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        outline: none;
    }

    .pos-nav-item.active {
        background: var(--pos-gradient) !important;
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
        color: #ffffff;
    }
</style>
@endsection

@section('content')
<div class="pos-set-container">
    {{-- Header Top Bar --}}
    <div class="pos-set-header">
        <div class="pos-set-header-left">
            <a href="{{ route('creator.pos.index') }}" class="pos-set-back-btn" title="Kembali ke Kasir">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="pos-set-title">Pengaturan POS</h1>
                <p class="pos-set-subtitle">Profil Struk, Pajak, Diskon & Staf Kasir</p>
            </div>
        </div>

        <a href="{{ route('creator.pos.profile') }}" class="pos-set-back-btn" title="Menu & Profil">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
        </a>
    </div>

    {{-- Horizontal Capsule Tabs --}}
    @php
        $activeTab = request('tab', 'struk');
    @endphp
    <div class="pos-set-nav">
        <button type="button" class="pos-set-tab-btn {{ $activeTab === 'struk' ? 'active' : '' }}" onclick="switchPosTab('struk', this)">
            🧾 Struk & Toko
        </button>
        <button type="button" class="pos-set-tab-btn {{ $activeTab === 'fees' ? 'active' : '' }}" onclick="switchPosTab('fees', this)">
            💰 Pajak & Biaya
        </button>
        <button type="button" class="pos-set-tab-btn {{ $activeTab === 'discounts' ? 'active' : '' }}" onclick="switchPosTab('discounts', this)">
            🏷️ Diskon & Promo
        </button>
        <button type="button" class="pos-set-tab-btn {{ $activeTab === 'tables' ? 'active' : '' }}" onclick="switchPosTab('tables', this)">
            🪑 Kelola Meja
        </button>
        <button type="button" class="pos-set-tab-btn {{ $activeTab === 'staff' ? 'active' : '' }}" onclick="switchPosTab('staff', this)">
            👥 Staf Kasir
        </button>
        <button type="button" class="pos-set-tab-btn {{ $activeTab === 'payment' ? 'active' : '' }}" onclick="switchPosTab('payment', this)">
            💳 Metode Bayar
        </button>
    </div>

    {{-- ── TAB 1: STRUK & TOKO ── --}}
    <div id="tabStruk" class="pos-set-tab-pane" style="{{ $activeTab === 'struk' ? 'display:block;' : 'display:none;' }}">
        <div class="pos-set-card">
            {{-- Logo Struk --}}
            <div class="pos-form-group">
                <label class="pos-form-label">Logo Struk POS (Tampil di Gambar & Cetak)</label>
                <div class="pos-logo-box">
                    <div class="pos-logo-preview" id="logoPreviewWrap">
                        @if($posLogoUrl)
                            <img src="{{ $posLogoUrl }}" id="posLogoImg" alt="Logo Toko">
                        @else
                            <svg width="24" height="24" fill="none" stroke="#94a3b8" stroke-width="1.8" viewBox="0 0 24 24" id="posLogoPlaceholder">
                                <rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><polyline points="21 15 16 10 5 21" />
                            </svg>
                        @endif
                    </div>
                    <div style="flex:1;">
                        <input type="file" id="posLogoFileInput" accept="image/png,image/jpeg,image/webp,image/svg+xml" style="display:none;" onchange="uploadPosLogo(this)">
                        <button type="button" onclick="document.getElementById('posLogoFileInput').click()" class="pos-form-input" style="cursor:pointer; text-align:center; font-weight:600; padding:0.4rem 0.75rem; margin-bottom:5px; background:#ffffff;">
                            Ganti Logo Toko
                        </button>
                        @if($posLogoUrl)
                            <button type="button" onclick="removePosLogo()" style="border:none; background:none; color:#ef4444; font-size:0.72rem; font-weight:600; cursor:pointer;">
                                Hapus Logo
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Nama Toko --}}
            <div class="pos-form-group">
                <label class="pos-form-label">Nama Brand / Toko</label>
                <input type="text" id="setStoreName" class="pos-form-input" value="{{ $profile->store_name ?? $seller->name }}" placeholder="Nama Toko Anda">
            </div>

            {{-- Alamat Toko --}}
            <div class="pos-form-group">
                <label class="pos-form-label">Alamat Toko (Dicetak pada Struk)</label>
                <textarea id="setStoreAddress" class="pos-form-textarea" rows="2" placeholder="Jl. Raya Utama No. 123, Kota...">{{ $posStoreAddress }}</textarea>
            </div>

            {{-- Telepon Toko --}}
            <div class="pos-form-group">
                <label class="pos-form-label">No. Telepon / WhatsApp Toko</label>
                <input type="text" id="setStorePhone" class="pos-form-input" value="{{ $posStorePhone }}" placeholder="08xxxxxxxxxx">
            </div>

            {{-- Catatan Footer Struk --}}
            <div class="pos-form-group">
                <label class="pos-form-label">Pesan / Footer Struk</label>
                <input type="text" id="setReceiptFooter" class="pos-form-input" value="{{ $profile->pos_receipt_footer ?? 'Terima Kasih Atas Kunjungan Anda!' }}" placeholder="Terima Kasih!">
            </div>

            <div style="margin-top: 1.25rem;">
                <button type="button" class="pos-btn-save" id="btnSaveStore" onclick="saveStoreSettings()">
                    Simpan Pengaturan Struk
                </button>
            </div>
        </div>
    </div>

    {{-- ── TAB 2: PAJAK & BIAYA ── --}}
    <div id="tabFees" class="pos-set-tab-pane" style="{{ $activeTab === 'fees' ? 'display:block;' : 'display:none;' }}">
        <div class="pos-set-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.85rem;">
                <div>
                    <h5 style="margin:0; font-size:0.9rem; font-weight:700; color:#0f172a;">Pajak & Biaya Tambahan</h5>
                    <div style="font-size:0.7rem; color:#64748b;">PB1 Restoran, Service Charge, atau Biaya Kemasan</div>
                </div>
                <button type="button" onclick="addNewFeeRow()" style="padding:0.35rem 0.75rem; border-radius:999px; background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; font-size:0.75rem; font-weight:700; cursor:pointer;">
                    + Tambah
                </button>
            </div>

            <div id="feesListContainer">
                @forelse($posFees as $idx => $f)
                    <div class="pos-row-item fee-item-row" data-idx="{{ $idx }}">
                        <div style="flex:1; margin-right:8px;">
                            <input type="text" class="pos-form-input fee-name" value="{{ $f['name'] ?? '' }}" placeholder="Nama Biaya (misal PB1)" style="margin-bottom:4px; font-weight:600;">
                            <div style="display:flex; gap:6px;">
                                <select class="pos-form-select fee-type" style="width:110px;">
                                    <option value="percent" {{ ($f['type'] ?? '') === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                                    <option value="fixed" {{ ($f['type'] ?? '') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                                </select>
                                <input type="number" class="pos-form-input fee-amount" value="{{ $f['amount'] ?? 0 }}" placeholder="Nilai">
                            </div>
                        </div>
                        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                            <label style="font-size:0.7rem; color:#475569; display:flex; align-items:center; gap:4px; cursor:pointer;">
                                <input type="checkbox" class="fee-enabled" {{ !empty($f['is_active']) ? 'checked' : '' }}> Aktif
                            </label>
                            <button type="button" onclick="this.closest('.fee-item-row').remove()" style="border:none; background:none; color:#ef4444; font-size:0.72rem; cursor:pointer;">Hapus</button>
                        </div>
                    </div>
                @empty
                    <div id="emptyFeePlaceholder" style="text-align:center; padding:1.5rem; color:#94a3b8; font-size:0.75rem;">
                        Belum ada pengaturan biaya tambahan. Klik tombol "+ Tambah" di atas.
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 1.25rem;">
                <button type="button" class="pos-btn-save" id="btnSaveFees" onclick="saveFeesSettings()">
                    Simpan Biaya & Pajak
                </button>
            </div>
        </div>
    </div>

    {{-- ── TAB 3: DISKON & PROMO ── --}}
    <div id="tabDiscounts" class="pos-set-tab-pane" style="{{ $activeTab === 'discounts' ? 'display:block;' : 'display:none;' }}">
        <div class="pos-set-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.85rem;">
                <div>
                    <h5 style="margin:0; font-size:0.9rem; font-weight:700; color:#0f172a;">Diskon & Promo Struk</h5>
                    <div style="font-size:0.7rem; color:#64748b;">Potongan harga pesanan otomatis / manual di kasir</div>
                </div>
                <button type="button" onclick="addNewDiscountRow()" style="padding:0.35rem 0.75rem; border-radius:999px; background:#fefce8; border:1px solid #fef08a; color:#854d0e; font-size:0.75rem; font-weight:700; cursor:pointer;">
                    + Tambah
                </button>
            </div>

            <div id="discountsListContainer">
                @forelse($posDiscounts as $idx => $d)
                    <div class="pos-row-item discount-item-row" data-idx="{{ $idx }}">
                        <div style="flex:1; margin-right:8px;">
                            <input type="text" class="pos-form-input disc-name" value="{{ $d['name'] ?? '' }}" placeholder="Nama Diskon (misal Member 10%)" style="margin-bottom:4px; font-weight:600;">
                            <div style="display:flex; gap:6px;">
                                <select class="pos-form-select disc-type" style="width:110px;">
                                    <option value="percent" {{ ($d['type'] ?? '') === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                                    <option value="fixed" {{ ($d['type'] ?? '') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                                </select>
                                <input type="number" class="pos-form-input disc-amount" value="{{ $d['amount'] ?? 0 }}" placeholder="Nilai">
                            </div>
                        </div>
                        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                            <label style="font-size:0.7rem; color:#475569; display:flex; align-items:center; gap:4px; cursor:pointer;">
                                <input type="checkbox" class="disc-enabled" {{ !empty($d['is_active']) ? 'checked' : '' }}> Aktif
                            </label>
                            <button type="button" onclick="this.closest('.discount-item-row').remove()" style="border:none; background:none; color:#ef4444; font-size:0.72rem; cursor:pointer;">Hapus</button>
                        </div>
                    </div>
                @empty
                    <div id="emptyDiscPlaceholder" style="text-align:center; padding:1.5rem; color:#94a3b8; font-size:0.75rem;">
                        Belum ada diskon terdaftar. Klik "+ Tambah" untuk membuat diskon baru.
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 1.25rem;">
                <button type="button" class="pos-btn-save" id="btnSaveDiscounts" onclick="saveDiscountsSettings()">
                    Simpan Diskon & Promo
                </button>
            </div>
        </div>
    </div>

    {{-- ── TAB 4: KELOLA MEJA ── --}}
    <div id="tabTables" class="pos-set-tab-pane" style="{{ $activeTab === 'tables' ? 'display:block;' : 'display:none;' }}">
        <div class="pos-set-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.85rem;">
                <div>
                    <h5 style="margin:0; font-size:0.9rem; font-weight:700; color:#0f172a;">Kelola Meja Dine-in</h5>
                    <div style="font-size:0.7rem; color:#64748b;">Nomor meja untuk pesanan makan di tempat</div>
                </div>
                <button type="button" onclick="addNewTableRow()" style="padding:0.35rem 0.75rem; border-radius:999px; background:#fff7ed; border:1px solid #fed7aa; color:#c2410c; font-size:0.75rem; font-weight:700; cursor:pointer;">
                    + Tambah Meja
                </button>
            </div>

            <div id="tablesListContainer">
                @forelse($posTables as $idx => $t)
                    <div class="pos-row-item table-item-row" data-idx="{{ $idx }}">
                        <div style="display:flex; align-items:center; gap:8px; flex:1;">
                            <div style="width:34px; height:34px; border-radius:8px; background:#ffedd5; color:#c2410c; font-weight:700; display:flex; align-items:center; justify-content:center; font-size:0.8rem;">
                                🪑
                            </div>
                            <div style="flex:1;">
                                <input type="text" class="pos-form-input table-name" value="{{ $t['name'] ?? '' }}" placeholder="Nomor Meja (misal Meja 01)" style="font-weight:600;">
                            </div>
                        </div>
                        <button type="button" onclick="this.closest('.table-item-row').remove()" style="border:none; background:none; color:#ef4444; font-size:0.72rem; cursor:pointer; margin-left:8px;">Hapus</button>
                    </div>
                @empty
                    <div id="emptyTablePlaceholder" style="text-align:center; padding:1.5rem; color:#94a3b8; font-size:0.75rem;">
                        Belum ada meja dikonfigurasi. Klik "+ Tambah Meja".
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 1.25rem;">
                <button type="button" class="pos-btn-save" id="btnSaveTables" onclick="saveTablesSettings()">
                    Simpan Tata Letak Meja
                </button>
            </div>
        </div>
    </div>

    {{-- ── TAB 5: STAF KASIR ── --}}
    <div id="tabStaff" class="pos-set-tab-pane" style="{{ $activeTab === 'staff' ? 'display:block;' : 'display:none;' }}">
        <div class="pos-set-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.85rem;">
                <div>
                    <h5 style="margin:0; font-size:0.9rem; font-weight:700; color:#0f172a;">Staf Kasir Terdaftar</h5>
                    <div style="font-size:0.7rem; color:#64748b;">Kelola akun kasir untuk melayani transaksi</div>
                </div>
                <button type="button" onclick="document.getElementById('addCashierCard').style.display='block'" style="padding:0.35rem 0.75rem; border-radius:999px; background:#f5f3ff; border:1px solid #ddd6fe; color:#6d28d9; font-size:0.75rem; font-weight:700; cursor:pointer;">
                    + Tambah Kasir
                </button>
            </div>

            {{-- Form Tambah Kasir (Inline collapsible) --}}
            <div id="addCashierCard" style="display:none; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:14px; padding:0.85rem; margin-bottom:0.85rem;">
                <div style="font-weight:700; font-size:0.8rem; color:#0f172a; margin-bottom:6px;">Tambah Akun Staf Kasir</div>
                <div class="pos-form-group">
                    <input type="text" id="newCashierName" class="pos-form-input" placeholder="Nama Kasir (misal: Budi Santoso)">
                </div>
                <div class="pos-form-group">
                    <input type="text" id="newCashierUsername" class="pos-form-input" placeholder="Username Kasir (untuk login)">
                </div>
                <div class="pos-form-group">
                    <input type="password" id="newCashierPassword" class="pos-form-input" placeholder="PIN / Password (min 6 karakter)">
                </div>
                <div style="display:flex; gap:6px; margin-top:8px;">
                    <button type="button" onclick="saveNewCashier()" class="pos-btn-save" style="flex:1; padding:0.5rem;">Simpan Kasir</button>
                    <button type="button" onclick="document.getElementById('addCashierCard').style.display='none'" class="pos-form-input" style="width:auto; padding:0.5rem 0.85rem; cursor:pointer;">Batal</button>
                </div>
            </div>

            <div id="cashiersListContainer">
                @forelse($cashiers as $c)
                    <div class="pos-row-item">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div style="width:36px; height:36px; border-radius:10px; background:#ede9fe; color:#6d28d9; font-weight:700; display:flex; align-items:center; justify-content:center; font-size:0.85rem;">
                                {{ strtoupper(substr($c->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:0.82rem; font-weight:700; color:#0f172a;">{{ $c->name }}</div>
                                <div style="font-size:0.7rem; color:#64748b;">Username: {{ $c->username ?? $c->email }}</div>
                            </div>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <button type="button" onclick="deleteCashier({{ $c->id }})" style="border:none; background:none; color:#ef4444; font-size:0.72rem; cursor:pointer;">
                                Hapus
                            </button>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center; padding:1.5rem; color:#94a3b8; font-size:0.75rem;">
                        Belum ada staf kasir terdaftar.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── TAB 6: METODE BAYAR & FEE ── --}}
    <div id="tabPayment" class="pos-set-tab-pane" style="{{ $activeTab === 'payment' ? 'display:block;' : 'display:none;' }}">
        <div class="pos-set-card">
            {{-- Mode Cashless --}}
            <div class="pos-form-group">
                <label class="pos-form-label">Mode Pembayaran Cashless / QRIS</label>
                <select id="setCashlessMode" class="pos-form-select" onchange="toggleQrisUploadVisibility(this.value)">
                    <option value="snap_midtrans" {{ $posCashlessMode === 'snap_midtrans' ? 'selected' : '' }}>
                        Midtrans Gateway (QRIS Dinamis Otomatis Cek Pembayaran)
                    </option>
                    <option value="static_qris" {{ $posCashlessMode === 'static_qris' ? 'selected' : '' }}>
                        QRIS Statis Toko Pribadi (Upload Gambar QR Toko)
                    </option>
                </select>
            </div>

            {{-- Upload QRIS Statis --}}
            <div class="pos-form-group" id="qrisUploadSection" style="{{ $posCashlessMode === 'static_qris' ? 'display:block;' : 'display:none;' }}">
                <label class="pos-form-label">Gambar QRIS Toko</label>
                <div class="pos-logo-box">
                    <div class="pos-logo-preview" id="qrisPreviewWrap">
                        @if($posQrisUrl)
                            <img src="{{ $posQrisUrl }}" id="posQrisImg" alt="QRIS Toko">
                        @else
                            <svg width="24" height="24" fill="none" stroke="#94a3b8" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" /><rect x="14" y="3" width="7" height="7" /><rect x="14" y="14" width="7" height="7" />
                            </svg>
                        @endif
                    </div>
                    <div style="flex:1;">
                        <input type="file" id="posQrisFileInput" accept="image/*" style="display:none;" onchange="uploadPosQris(this)">
                        <button type="button" onclick="document.getElementById('posQrisFileInput').click()" class="pos-form-input" style="cursor:pointer; text-align:center; font-weight:600; padding:0.4rem 0.75rem; margin-bottom:5px; background:#ffffff;">
                            Upload Gambar QRIS
                        </button>
                        @if($posQrisUrl)
                            <button type="button" onclick="removePosQris()" style="border:none; background:none; color:#ef4444; font-size:0.72rem; font-weight:600; cursor:pointer;">
                                Hapus QRIS
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Fee Payer --}}
            <div class="pos-form-group">
                <label class="pos-form-label">Beban Biaya Transaksi Digital / Platform</label>
                <select id="setFeePayer" class="pos-form-select">
                    <option value="seller" {{ $posFeePayer === 'seller' ? 'selected' : '' }}>
                        Ditanggung Penjual / Toko
                    </option>
                    <option value="buyer" {{ $posFeePayer === 'buyer' ? 'selected' : '' }}>
                        Dibebankan ke Pelanggan (Ditambahkan ke Struk)
                    </option>
                </select>
                <div style="font-size:0.7rem; color:#64748b; margin-top:4px;">
                    Biaya platform kasir: {{ $platformFeeRate }}% + Rp {{ number_format($adminFeeRate, 0, ',', '.') }} per transaksi digital.
                </div>
            </div>

            <div style="margin-top: 1.25rem;">
                <button type="button" class="pos-btn-save" id="btnSavePayment" onclick="savePaymentSettings()">
                    Simpan Pengaturan Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── 4-BUTTON BOTTOM NAVIGATION BAR (Button 4: Profil/Pengaturan Aktif) ── --}}
<div class="pos-bottom-nav-wrap" id="posBottomNavWrap">
    <div class="pos-bottom-nav-bar">
        <!-- 1. Home / Kasir -->
        <a href="{{ route('creator.pos.index') }}" class="pos-nav-item" title="Kasir Home">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span class="pos-nav-label">Home</span>
        </a>

        <!-- 2. Transactions -->
        <a href="{{ route('creator.pos.index', ['screen' => 'transactions']) }}" class="pos-nav-item" title="Transaksi">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <rect x="2" y="4" width="20" height="16" rx="2"/>
                <circle cx="12" cy="12" r="3"/>
                <line x1="2" y1="9" x2="6" y2="9"/>
                <line x1="18" y1="15" x2="22" y2="15"/>
            </svg>
            <span class="pos-nav-label">Transaksi</span>
        </a>

        <!-- 3. Laporan -->
        <a href="{{ route('creator.pos.reports') }}" class="pos-nav-item" title="Laporan POS">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <line x1="18" y1="20" x2="18" y2="10"/>
                <line x1="12" y1="20" x2="12" y2="4"/>
                <line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
            <span class="pos-nav-label">Laporan</span>
        </a>

        <!-- 4. Profile / Settings -->
        <a href="{{ route('creator.pos.profile') }}" class="pos-nav-item active" title="Profil Kasir & Toko">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3" />
                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" />
            </svg>
            <span class="pos-nav-label">Settings</span>
        </a>
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    function switchPosTab(tab, btn) {
        document.querySelectorAll('.pos-set-tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.pos-set-tab-pane').forEach(p => p.style.display = 'none');
        const activePane = document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1));
        if (activePane) activePane.style.display = 'block';

        const url = new URL(window.location.href);
        url.searchParams.set('tab', tab);
        window.history.replaceState({}, '', url);
    }

    function toggleQrisUploadVisibility(val) {
        const sec = document.getElementById('qrisUploadSection');
        if (sec) sec.style.display = val === 'static_qris' ? 'block' : 'none';
    }

    // 1. Simpan Profil & Struk
    function saveStoreSettings() {
        const btn = document.getElementById('btnSaveStore');
        const name = (document.getElementById('setStoreName')?.value || '').trim();
        const address = (document.getElementById('setStoreAddress')?.value || '').trim();
        const phone = (document.getElementById('setStorePhone')?.value || '').trim();
        const footer = (document.getElementById('setReceiptFooter')?.value || '').trim();

        if (btn) { btn.disabled = true; btn.innerText = 'Menyimpan...'; }

        fetch('{{ route("creator.pos.update-settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                store_name: name,
                pos_store_address: address,
                pos_store_phone: phone,
                pos_receipt_footer: footer
            })
        })
        .then(res => res.json())
        .then(data => {
            if (btn) { btn.disabled = false; btn.innerText = 'Simpan Pengaturan Struk'; }
            if (data.success) {
                alert('Pengaturan struk & profil toko berhasil disimpan!');
            } else {
                alert(data.message || 'Gagal menyimpan pengaturan.');
            }
        })
        .catch(err => {
            if (btn) { btn.disabled = false; btn.innerText = 'Simpan Pengaturan Struk'; }
            alert('Kesalahan jaringan: ' + err.message);
        });
    }

    // 2. Upload Logo
    function uploadPosLogo(input) {
        if (!input.files || !input.files[0]) return;
        const formData = new FormData();
        formData.append('logo', input.files[0]);

        fetch('{{ route("creator.pos.upload-logo") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Logo struk berhasil diunggah!');
                window.location.reload();
            } else {
                alert(data.message || 'Gagal mengunggah logo.');
            }
        })
        .catch(err => alert('Gagal upload logo: ' + err.message));
    }

    function removePosLogo() {
        if (!confirm('Hapus logo struk toko?')) return;
        fetch('{{ route("creator.pos.remove-logo") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Logo berhasil dihapus.');
                window.location.reload();
            }
        });
    }

    // 3. Simpan Biaya & Pajak
    function addNewFeeRow() {
        const ph = document.getElementById('emptyFeePlaceholder');
        if (ph) ph.style.display = 'none';

        const container = document.getElementById('feesListContainer');
        const div = document.createElement('div');
        div.className = 'pos-row-item fee-item-row';
        div.innerHTML = `
            <div style="flex:1; margin-right:8px;">
                <input type="text" class="pos-form-input fee-name" placeholder="Nama Biaya (misal PB1)" style="margin-bottom:4px; font-weight:600;">
                <div style="display:flex; gap:6px;">
                    <select class="pos-form-select fee-type" style="width:110px;">
                        <option value="percent">Persen (%)</option>
                        <option value="fixed">Nominal (Rp)</option>
                    </select>
                    <input type="number" class="pos-form-input fee-amount" placeholder="Nilai" value="0">
                </div>
            </div>
            <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                <label style="font-size:0.7rem; color:#475569; display:flex; align-items:center; gap:4px; cursor:pointer;">
                    <input type="checkbox" class="fee-enabled" checked> Aktif
                </label>
                <button type="button" onclick="this.closest('.fee-item-row').remove()" style="border:none; background:none; color:#ef4444; font-size:0.72rem; cursor:pointer;">Hapus</button>
            </div>
        `;
        container.appendChild(div);
    }

    function saveFeesSettings() {
        const btn = document.getElementById('btnSaveFees');
        const fees = [];
        document.querySelectorAll('.fee-item-row').forEach(row => {
            const name = (row.querySelector('.fee-name')?.value || '').trim();
            const type = row.querySelector('.fee-type')?.value || 'percent';
            const amount = parseFloat(row.querySelector('.fee-amount')?.value || 0);
            const is_active = row.querySelector('.fee-enabled')?.checked || false;
            if (name) fees.push({ name, type, amount, is_active });
        });

        if (btn) { btn.disabled = true; btn.innerText = 'Menyimpan...'; }

        fetch('{{ route("creator.pos.update-settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ pos_fees: fees })
        })
        .then(res => res.json())
        .then(data => {
            if (btn) { btn.disabled = false; btn.innerText = 'Simpan Biaya & Pajak'; }
            if (data.success) {
                alert('Pengaturan biaya & pajak berhasil disimpan!');
            } else {
                alert(data.message || 'Gagal menyimpan.');
            }
        });
    }

    // 4. Simpan Diskon
    function addNewDiscountRow() {
        const ph = document.getElementById('emptyDiscPlaceholder');
        if (ph) ph.style.display = 'none';

        const container = document.getElementById('discountsListContainer');
        const div = document.createElement('div');
        div.className = 'pos-row-item discount-item-row';
        div.innerHTML = `
            <div style="flex:1; margin-right:8px;">
                <input type="text" class="pos-form-input disc-name" placeholder="Nama Diskon" style="margin-bottom:4px; font-weight:600;">
                <div style="display:flex; gap:6px;">
                    <select class="pos-form-select disc-type" style="width:110px;">
                        <option value="percent">Persen (%)</option>
                        <option value="fixed">Nominal (Rp)</option>
                    </select>
                    <input type="number" class="pos-form-input disc-amount" placeholder="Nilai" value="0">
                </div>
            </div>
            <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                <label style="font-size:0.7rem; color:#475569; display:flex; align-items:center; gap:4px; cursor:pointer;">
                    <input type="checkbox" class="disc-enabled" checked> Aktif
                </label>
                <button type="button" onclick="this.closest('.discount-item-row').remove()" style="border:none; background:none; color:#ef4444; font-size:0.72rem; cursor:pointer;">Hapus</button>
            </div>
        `;
        container.appendChild(div);
    }

    function saveDiscountsSettings() {
        const btn = document.getElementById('btnSaveDiscounts');
        const discounts = [];
        document.querySelectorAll('.discount-item-row').forEach(row => {
            const name = (row.querySelector('.disc-name')?.value || '').trim();
            const type = row.querySelector('.disc-type')?.value || 'percent';
            const amount = parseFloat(row.querySelector('.disc-amount')?.value || 0);
            const is_active = row.querySelector('.disc-enabled')?.checked || false;
            if (name) discounts.push({ name, type, amount, is_active });
        });

        if (btn) { btn.disabled = true; btn.innerText = 'Menyimpan...'; }

        fetch('{{ route("creator.pos.update-settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ pos_discounts: discounts })
        })
        .then(res => res.json())
        .then(data => {
            if (btn) { btn.disabled = false; btn.innerText = 'Simpan Diskon & Promo'; }
            if (data.success) {
                alert('Diskon & promo berhasil disimpan!');
            } else {
                alert(data.message || 'Gagal menyimpan.');
            }
        });
    }

    // 5. Simpan Meja
    function addNewTableRow() {
        const ph = document.getElementById('emptyTablePlaceholder');
        if (ph) ph.style.display = 'none';

        const container = document.getElementById('tablesListContainer');
        const div = document.createElement('div');
        div.className = 'pos-row-item table-item-row';
        div.innerHTML = `
            <div style="display:flex; align-items:center; gap:8px; flex:1;">
                <div style="width:34px; height:34px; border-radius:8px; background:#ffedd5; color:#c2410c; font-weight:700; display:flex; align-items:center; justify-content:center; font-size:0.8rem;">
                    🪑
                </div>
                <div style="flex:1;">
                    <input type="text" class="pos-form-input table-name" placeholder="Nomor Meja (misal Meja 01)" style="font-weight:600;">
                </div>
            </div>
            <button type="button" onclick="this.closest('.table-item-row').remove()" style="border:none; background:none; color:#ef4444; font-size:0.72rem; cursor:pointer; margin-left:8px;">Hapus</button>
        `;
        container.appendChild(div);
    }

    function saveTablesSettings() {
        const btn = document.getElementById('btnSaveTables');
        const tables = [];
        document.querySelectorAll('.table-item-row').forEach(row => {
            const name = (row.querySelector('.table-name')?.value || '').trim();
            if (name) tables.push({ name });
        });

        if (btn) { btn.disabled = true; btn.innerText = 'Menyimpan...'; }

        fetch('{{ route("creator.pos.update-settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ pos_tables: tables })
        })
        .then(res => res.json())
        .then(data => {
            if (btn) { btn.disabled = false; btn.innerText = 'Simpan Tata Letak Meja'; }
            if (data.success) {
                alert('Pengaturan meja dine-in berhasil disimpan!');
            } else {
                alert(data.message || 'Gagal menyimpan.');
            }
        });
    }

    // 6. Staf Kasir
    function saveNewCashier() {
        const name = (document.getElementById('newCashierName')?.value || '').trim();
        const username = (document.getElementById('newCashierUsername')?.value || '').trim();
        const password = (document.getElementById('newCashierPassword')?.value || '').trim();

        if (!name || !username || !password) {
            alert('Harap isi Nama, Username, dan PIN/Password kasir.');
            return;
        }

        fetch('{{ route("creator.pos.cashiers.save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name, username, password })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Akun staf kasir berhasil ditambahkan!');
                window.location.reload();
            } else {
                alert(data.message || 'Gagal menambahkan kasir.');
            }
        })
        .catch(err => alert('Gagal: ' + err.message));
    }

    function deleteCashier(id) {
        if (!confirm('Hapus akun staf kasir ini?')) return;
        fetch('{{ url("creator/pos/cashiers") }}/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Staf kasir berhasil dihapus.');
                window.location.reload();
            }
        });
    }

    // 7. Simpan Pengaturan Pembayaran
    function savePaymentSettings() {
        const btn = document.getElementById('btnSavePayment');
        const mode = document.getElementById('setCashlessMode')?.value || 'snap_midtrans';
        const feePayer = document.getElementById('setFeePayer')?.value || 'seller';

        if (btn) { btn.disabled = true; btn.innerText = 'Menyimpan...'; }

        fetch('{{ route("creator.pos.update-settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                pos_cashless_mode: mode,
                pos_fee_payer: feePayer
            })
        })
        .then(res => res.json())
        .then(data => {
            if (btn) { btn.disabled = false; btn.innerText = 'Simpan Pengaturan Pembayaran'; }
            if (data.success) {
                alert('Pengaturan pembayaran berhasil disimpan!');
            } else {
                alert(data.message || 'Gagal menyimpan.');
            }
        });
    }

    function uploadPosQris(input) {
        if (!input.files || !input.files[0]) return;
        const formData = new FormData();
        formData.append('qris_image', input.files[0]);

        fetch('{{ route("creator.pos.upload-qris") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('QRIS toko berhasil diunggah!');
                window.location.reload();
            } else {
                alert(data.message || 'Gagal upload QRIS.');
            }
        });
    }

    function removePosQris() {
        if (!confirm('Hapus gambar QRIS toko?')) return;
        fetch('{{ route("creator.pos.remove-qris") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Gambar QRIS berhasil dihapus.');
                window.location.reload();
            }
        });
    }
</script>
@endsection

