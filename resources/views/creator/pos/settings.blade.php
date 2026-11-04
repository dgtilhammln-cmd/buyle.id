@extends('creator.layout')

@section('title', 'Pengaturan Kasir POS – Creator Studio')
@section('page_title', 'Pengaturan POS')

@section('styles')
<style>
    :root { --pg: linear-gradient(135deg,#1eb349 0%,#a5cf37 100%); }
    body { background:#f8fafc !important; }

        /* Reset Global Layout Wrapper (Full Centered, Anti-Mepet, No Overflow) */
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
        gap: 0.6rem;
        margin-bottom: 0.85rem;
    }
    .ps-back {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #e2e8f0;
        display: flex; align-items: center; justify-content: center;
        color: #334155;
        text-decoration: none;
        flex-shrink: 0;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .ps-hdr-text h1 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }
    .ps-hdr-text p {
        margin: 2px 0 0;
        font-size: 0.68rem;
        color: #64748b;
    }

    /* ── Tab Grid 2×3 ── */
    .ps-tab-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.4rem;
        margin-bottom: 0.85rem;
    }
    .ps-tab-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        padding: 0.45rem 0.2rem;
        border-radius: 12px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        color: #475569;
        font-size: 0.65rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        line-height: 1.2;
        text-align: center;
    }
    .ps-tab-btn svg { flex-shrink: 0; }
    .ps-tab-btn.active {
        background: var(--pg);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 3px 10px rgba(30,179,73,.28);
    }

    /* ── Card ── */
    .ps-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.02);
        margin-bottom: 0.75rem;
    }
    .ps-card-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 0.15rem;
    }
    .ps-card-sub {
        font-size: 0.68rem;
        color: #64748b;
        margin: 0 0 0.85rem;
    }

    /* ── Form Controls ── */
    .ps-field { margin-bottom: 0.7rem; }
    .ps-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }
    .ps-input, .ps-textarea, .ps-select {
        width: 100%;
        box-sizing: border-box;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
        color: #0f172a;
        outline: none;
        font-family: inherit;
        transition: border-color .15s;
    }
    .ps-input:focus, .ps-textarea:focus, .ps-select:focus {
        border-color: #1eb349;
        background: #fff;
    }
    .ps-textarea { resize: vertical; min-height: 56px; }

    /* ── Logo Box ── */
    .ps-logo-box {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        border-radius: 14px;
        padding: 0.7rem;
        margin-bottom: 0.7rem;
    }
    .ps-logo-thumb {
        width: 52px; height: 52px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .ps-logo-thumb img { width: 100%; height: 100%; object-fit: contain; }

    /* ── Save Button ── */
    .ps-save {
        width: 100%;
        background: var(--pg);
        color: #fff;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.7rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        box-shadow: 0 4px 12px rgba(30,179,73,.3);
        margin-top: 0.85rem;
        transition: opacity .15s;
    }
    .ps-save:disabled { opacity: .65; cursor: not-allowed; }

    /* ── Row Item (fees/discounts/tables) ── */
    .ps-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.55rem 0.7rem;
        margin-bottom: 0.45rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }
    .ps-row-inner { flex: 1; }
    .ps-row-inner .ps-input { margin-bottom: 4px; }
    .ps-row-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; padding-top: 2px; }

    /* ── Section Add Button ── */
    .ps-add-btn {
        padding: 0.3rem 0.7rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        border: 1.5px solid;
        cursor: pointer;
        background: none;
    }

    /* ── Cashier Row ── */
    .ps-cashier-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.55rem 0.7rem;
        margin-bottom: 0.4rem;
    }
    .ps-cashier-avatar {
        width: 34px; height: 34px;
        border-radius: 9px;
        background: #ede9fe;
        color: #6d28d9;
        font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    .ps-cashier-info { flex: 1; min-width: 0; }
    .ps-cashier-name { font-size: 0.8rem; font-weight: 700; color: #0f172a; }
    .ps-cashier-sub { font-size: 0.67rem; color: #64748b; }

    /* ── Payment Mode label ── */
    .ps-mode-note { font-size: 0.68rem; color: #64748b; margin-top: 3px; }

    /* ── POS Bottom Nav ── */
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
        height: 60px !important;
        background: #fff !important;
        border-radius: 999px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0.3rem 0.6rem !important;
        box-shadow: 0 8px 28px rgba(0,0,0,.1), 0 2px 6px rgba(0,0,0,.04) !important;
        border: 1.5px solid #f1f5f9 !important;
    }
    .pos-nav-item {
        display: flex; align-items: center; justify-content: center;
        border: none; background: transparent; cursor: pointer;
        transition: all .22s cubic-bezier(.16,1,.3,1);
        color: #64748b; padding: 0;
        text-decoration: none;
        width: 42px; height: 42px;
        border-radius: 50%; outline: none;
    }
    .pos-nav-item.active {
        background: var(--pg) !important;
        color: #fff !important;
        padding: 0.45rem 1rem !important;
        border-radius: 999px !important;
        box-shadow: 0 4px 14px rgba(30,179,73,.35) !important;
        gap: 5px !important;
        width: auto !important;
    }
    .pos-nav-label { display: none; }
    .pos-nav-item.active .pos-nav-label {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="ps-wrap">

    {{-- Header --}}
    <div class="ps-hdr">
        <a href="{{ route('creator.pos.index') }}" class="ps-back" title="Kembali ke Kasir">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="ps-hdr-text">
            <h1>Pengaturan POS</h1>
            <p>Struk, Biaya, Diskon, Staf & Metode Bayar</p>
        </div>
    </div>

    {{-- Tab Grid 2×3 --}}
    @php $activeTab = request('tab', 'struk'); @endphp
    <div class="ps-tab-grid">
        <button type="button" class="ps-tab-btn {{ $activeTab === 'struk' ? 'active' : '' }}" onclick="switchTab('struk',this)">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Struk & Logo
        </button>
        <button type="button" class="ps-tab-btn {{ $activeTab === 'fees' ? 'active' : '' }}" onclick="switchTab('fees',this)">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Pajak & Biaya
        </button>
        <button type="button" class="ps-tab-btn {{ $activeTab === 'discounts' ? 'active' : '' }}" onclick="switchTab('discounts',this)">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
            Diskon
        </button>
        <button type="button" class="ps-tab-btn {{ $activeTab === 'tables' ? 'active' : '' }}" onclick="switchTab('tables',this)">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            Kelola Meja
        </button>
        <button type="button" class="ps-tab-btn {{ $activeTab === 'staff' ? 'active' : '' }}" onclick="switchTab('staff',this)">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Staf Kasir
        </button>
        <button type="button" class="ps-tab-btn {{ $activeTab === 'payment' ? 'active' : '' }}" onclick="switchTab('payment',this)">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Metode Bayar
        </button>
    </div>

    {{-- ── TAB 1: STRUK & LOGO ── --}}
    <div id="pane-struk" class="ps-pane" style="{{ $activeTab === 'struk' ? '' : 'display:none;' }}">
        <div class="ps-card">
            <p class="ps-card-title">Struk & Profil Toko</p>
            <p class="ps-card-sub">Logo, nama, alamat & pesan footer struk kasir</p>

            {{-- Logo --}}
            <div class="ps-field">
                <label class="ps-label">Logo Struk (Tampil di Struk, Email & WA)</label>
                <div class="ps-logo-box">
                    <div class="ps-logo-thumb">
                        @if($posLogoUrl)
                            <img src="{{ $posLogoUrl }}" alt="Logo">
                        @else
                            <svg width="22" height="22" fill="none" stroke="#94a3b8" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                            </svg>
                        @endif
                    </div>
                    <div style="flex:1;">
                        <input type="file" id="posLogoInput" accept="image/*" style="display:none;" onchange="uploadPosLogo(this)">
                        <button type="button" onclick="document.getElementById('posLogoInput').click()" class="ps-input" style="cursor:pointer; text-align:center; font-weight:600; padding:0.38rem 0.6rem;">
                            Ganti Logo
                        </button>
                        @if($posLogoUrl)
                            <div style="margin-top:4px;">
                                <button type="button" onclick="removePosLogo()" style="border:none; background:none; color:#ef4444; font-size:0.7rem; font-weight:600; cursor:pointer; padding:0;">Hapus Logo</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="ps-field">
                <label class="ps-label">Nama Brand / Toko</label>
                <input type="text" id="setStoreName" class="ps-input" value="{{ $profile->store_name ?? $seller->name }}" placeholder="Nama Toko">
            </div>
            <div class="ps-field">
                <label class="ps-label">Alamat Toko</label>
                <textarea id="setStoreAddress" class="ps-textarea" placeholder="Jl. Contoh No. 1...">{{ $posStoreAddress }}</textarea>
            </div>
            <div class="ps-field">
                <label class="ps-label">No. Telepon / WhatsApp</label>
                <input type="text" id="setStorePhone" class="ps-input" value="{{ $posStorePhone }}" placeholder="08xxxxxxxxxx">
            </div>
            <div class="ps-field">
                <label class="ps-label">Pesan Footer Struk</label>
                <input type="text" id="setReceiptFooter" class="ps-input" value="{{ $profile->pos_receipt_footer ?? 'Terima Kasih Atas Kunjungan Anda!' }}" placeholder="Terima kasih!">
            </div>
            <button type="button" class="ps-save" id="btnSaveStore" onclick="saveStoreSettings()">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Pengaturan Struk
            </button>
        </div>
    </div>

    {{-- ── TAB 2: PAJAK & BIAYA ── --}}
    <div id="pane-fees" class="ps-pane" style="{{ $activeTab === 'fees' ? '' : 'display:none;' }}">
        <div class="ps-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.65rem;">
                <div>
                    <p class="ps-card-title">Pajak & Biaya Tambahan</p>
                    <p class="ps-card-sub" style="margin-bottom:0;">PB1 Restoran, Service Charge, dsb.</p>
                </div>
                <button type="button" class="ps-add-btn" style="border-color:#86efac; color:#166534;" onclick="addFeeRow()">+ Tambah</button>
            </div>
            <div id="feesContainer">
                @forelse($posFees as $f)
                    <div class="ps-row fee-row">
                        <div class="ps-row-inner">
                            <input type="text" class="ps-input fee-name" value="{{ $f['name'] ?? '' }}" placeholder="Nama Biaya" style="margin-bottom:4px;">
                            <div style="display:flex; gap:5px;">
                                <select class="ps-select fee-type" style="width:100px;">
                                    <option value="percent" {{ ($f['type']??'') === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                                    <option value="fixed" {{ ($f['type']??'') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                                </select>
                                <input type="number" class="ps-input fee-amount" value="{{ $f['amount'] ?? 0 }}" placeholder="0">
                            </div>
                        </div>
                        <div class="ps-row-actions">
                            <label style="font-size:0.67rem; color:#475569; display:flex; align-items:center; gap:3px; cursor:pointer; white-space:nowrap;">
                                <input type="checkbox" class="fee-enabled" {{ !empty($f['is_active']) ? 'checked' : '' }}> Aktif
                            </label>
                            <button type="button" onclick="this.closest('.fee-row').remove()" style="border:none; background:none; color:#ef4444; font-size:0.67rem; cursor:pointer; padding:0;">Hapus</button>
                        </div>
                    </div>
                @empty
                    <div id="feesEmpty" style="text-align:center; padding:1.25rem; color:#94a3b8; font-size:0.73rem;">Belum ada biaya tambahan. Klik "+ Tambah".</div>
                @endforelse
            </div>
            <button type="button" class="ps-save" id="btnSaveFees" onclick="saveFeesSettings()">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                Simpan Pajak & Biaya
            </button>
        </div>
    </div>

    {{-- ── TAB 3: DISKON ── --}}
    <div id="pane-discounts" class="ps-pane" style="{{ $activeTab === 'discounts' ? '' : 'display:none;' }}">
        <div class="ps-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.65rem;">
                <div>
                    <p class="ps-card-title">Diskon & Promo</p>
                    <p class="ps-card-sub" style="margin-bottom:0;">Potongan harga di struk kasir</p>
                </div>
                <button type="button" class="ps-add-btn" style="border-color:#fef08a; color:#854d0e;" onclick="addDiscountRow()">+ Tambah</button>
            </div>
            <div id="discountsContainer">
                @forelse($posDiscounts as $d)
                    <div class="ps-row disc-row">
                        <div class="ps-row-inner">
                            <input type="text" class="ps-input disc-name" value="{{ $d['name'] ?? '' }}" placeholder="Nama Diskon" style="margin-bottom:4px;">
                            <div style="display:flex; gap:5px;">
                                <select class="ps-select disc-type" style="width:100px;">
                                    <option value="percent" {{ ($d['type']??'') === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                                    <option value="fixed" {{ ($d['type']??'') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                                </select>
                                <input type="number" class="ps-input disc-amount" value="{{ $d['amount'] ?? 0 }}" placeholder="0">
                            </div>
                        </div>
                        <div class="ps-row-actions">
                            <label style="font-size:0.67rem; color:#475569; display:flex; align-items:center; gap:3px; cursor:pointer; white-space:nowrap;">
                                <input type="checkbox" class="disc-enabled" {{ !empty($d['is_active']) ? 'checked' : '' }}> Aktif
                            </label>
                            <button type="button" onclick="this.closest('.disc-row').remove()" style="border:none; background:none; color:#ef4444; font-size:0.67rem; cursor:pointer; padding:0;">Hapus</button>
                        </div>
                    </div>
                @empty
                    <div id="discEmpty" style="text-align:center; padding:1.25rem; color:#94a3b8; font-size:0.73rem;">Belum ada diskon. Klik "+ Tambah".</div>
                @endforelse
            </div>
            <button type="button" class="ps-save" id="btnSaveDisc" onclick="saveDiscountsSettings()">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                Simpan Diskon & Promo
            </button>
        </div>
    </div>

    {{-- ── TAB 4: MEJA ── --}}
    <div id="pane-tables" class="ps-pane" style="{{ $activeTab === 'tables' ? '' : 'display:none;' }}">
        <div class="ps-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.65rem;">
                <div>
                    <p class="ps-card-title">Kelola Meja Dine-in</p>
                    <p class="ps-card-sub" style="margin-bottom:0;">Nomor meja makan di tempat</p>
                </div>
                <button type="button" class="ps-add-btn" style="border-color:#fed7aa; color:#c2410c;" onclick="addTableRow()">+ Meja</button>
            </div>
            <div id="tablesContainer">
                @forelse($posTables as $t)
                    <div class="ps-row table-row" style="align-items:center;">
                        <div style="width:30px; height:30px; border-radius:8px; background:#ffedd5; color:#c2410c; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                        </div>
                        <input type="text" class="ps-input table-name" value="{{ $t['name'] ?? '' }}" placeholder="Meja 01" style="flex:1; margin:0 6px;">
                        <button type="button" onclick="this.closest('.table-row').remove()" style="border:none; background:none; color:#ef4444; font-size:0.67rem; cursor:pointer; padding:0;">Hapus</button>
                    </div>
                @empty
                    <div id="tableEmpty" style="text-align:center; padding:1.25rem; color:#94a3b8; font-size:0.73rem;">Belum ada meja. Klik "+ Meja".</div>
                @endforelse
            </div>
            <button type="button" class="ps-save" id="btnSaveTables" onclick="saveTablesSettings()">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                Simpan Data Meja
            </button>
        </div>
    </div>

    {{-- ── TAB 5: STAF KASIR ── --}}
    <div id="pane-staff" class="ps-pane" style="{{ $activeTab === 'staff' ? '' : 'display:none;' }}">
        <div class="ps-card">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.65rem;">
                <div>
                    <p class="ps-card-title">Staf Kasir</p>
                    <p class="ps-card-sub" style="margin-bottom:0;">Kelola akun staf yang bisa login kasir</p>
                </div>
                <button type="button" class="ps-add-btn" style="border-color:#ddd6fe; color:#6d28d9;" onclick="document.getElementById('addCashierBox').style.display='block'">+ Tambah</button>
            </div>

            {{-- Form Tambah Kasir --}}
            <div id="addCashierBox" style="display:none; background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:0.7rem; margin-bottom:0.65rem;">
                <div style="font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">Akun Staf Kasir Baru</div>
                <div class="ps-field"><input type="text" id="newCashierName" class="ps-input" placeholder="Nama Kasir"></div>
                <div class="ps-field"><input type="text" id="newCashierUsername" class="ps-input" placeholder="Username Login"></div>
                <div class="ps-field"><input type="password" id="newCashierPassword" class="ps-input" placeholder="PIN / Password (min 6 karakter)"></div>
                <div style="display:flex; gap:5px; margin-top:4px;">
                    <button type="button" onclick="saveNewCashier()" class="ps-save" style="flex:1; margin-top:0; padding:0.5rem;">Simpan Kasir</button>
                    <button type="button" onclick="document.getElementById('addCashierBox').style.display='none'" class="ps-input" style="width:auto; padding:0.5rem 0.75rem; cursor:pointer; border-radius:10px;">Batal</button>
                </div>
            </div>

            <div id="cashiersContainer">
                @forelse($cashiers as $c)
                    <div class="ps-cashier-row">
                        <div class="ps-cashier-avatar">{{ strtoupper(substr($c->name, 0, 1)) }}</div>
                        <div class="ps-cashier-info">
                            <div class="ps-cashier-name">{{ $c->name }}</div>
                            <div class="ps-cashier-sub">{{ $c->username ?? $c->email }}</div>
                        </div>
                        <button type="button" onclick="deleteCashier({{ $c->id }})" style="border:none; background:none; color:#ef4444; font-size:0.7rem; font-weight:600; cursor:pointer; flex-shrink:0;">Hapus</button>
                    </div>
                @empty
                    <div style="text-align:center; padding:1.25rem; color:#94a3b8; font-size:0.73rem;">Belum ada staf kasir terdaftar.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── TAB 6: METODE BAYAR ── --}}
    <div id="pane-payment" class="ps-pane" style="{{ $activeTab === 'payment' ? '' : 'display:none;' }}">
        <div class="ps-card">
            <p class="ps-card-title">Metode Pembayaran & QRIS</p>
            <p class="ps-card-sub">Mode cashless, upload QRIS, dan beban biaya</p>

            <div class="ps-field">
                <label class="ps-label">Mode Pembayaran Cashless</label>
                <select id="setCashlessMode" class="ps-select" onchange="toggleQrisSection(this.value)">
                    <option value="snap_midtrans" {{ $posCashlessMode === 'snap_midtrans' ? 'selected' : '' }}>Midtrans — QRIS Dinamis (Cek Otomatis)</option>
                    <option value="static_qris" {{ $posCashlessMode === 'static_qris' ? 'selected' : '' }}>QRIS Statis Toko (Upload Gambar QR)</option>
                </select>
            </div>

            <div id="qrisSection" style="{{ $posCashlessMode === 'static_qris' ? '' : 'display:none;' }}">
                <div class="ps-field">
                    <label class="ps-label">Gambar QRIS Toko</label>
                    <div class="ps-logo-box">
                        <div class="ps-logo-thumb">
                            @if($posQrisUrl)
                                <img src="{{ $posQrisUrl }}" alt="QRIS">
                            @else
                                <svg width="20" height="20" fill="none" stroke="#94a3b8" stroke-width="1.8" viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                                </svg>
                            @endif
                        </div>
                        <div style="flex:1;">
                            <input type="file" id="posQrisInput" accept="image/*" style="display:none;" onchange="uploadPosQris(this)">
                            <button type="button" onclick="document.getElementById('posQrisInput').click()" class="ps-input" style="cursor:pointer; text-align:center; font-weight:600; padding:0.38rem 0.6rem;">Upload QRIS</button>
                            @if($posQrisUrl)
                                <div style="margin-top:4px;">
                                    <button type="button" onclick="removePosQris()" style="border:none; background:none; color:#ef4444; font-size:0.7rem; font-weight:600; cursor:pointer; padding:0;">Hapus QRIS</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="ps-field">
                <label class="ps-label">Beban Biaya Platform Transaksi</label>
                <select id="setFeePayer" class="ps-select">
                    <option value="seller" {{ $posFeePayer === 'seller' ? 'selected' : '' }}>Ditanggung Penjual / Toko</option>
                    <option value="buyer" {{ $posFeePayer === 'buyer' ? 'selected' : '' }}>Dibebankan ke Pelanggan</option>
                </select>
                <div class="ps-mode-note">
                    Biaya platform: {{ $platformFeeRate }}% + Rp {{ number_format($adminFeeRate, 0, ',', '.') }} per transaksi digital.
                </div>
            </div>

            <button type="button" class="ps-save" id="btnSavePayment" onclick="savePaymentSettings()">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/></svg>
                Simpan Pengaturan Pembayaran
            </button>
        </div>
    </div>

</div>{{-- end .ps-wrap --}}

{{-- Bottom Nav --}}
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
        <a href="{{ route('creator.pos.profile') }}" class="pos-nav-item active" title="Settings">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.6 9 1.65 1.65 0 003.09 7.5H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9"/></svg>
            <span class="pos-nav-label">Pengaturan</span>
        </a>
    </div>
</div>

<script>
const CSRF = '{{ csrf_token() }}';

function switchTab(tab, btn) {
    document.querySelectorAll('.ps-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.ps-pane').forEach(p => p.style.display = 'none');
    const pane = document.getElementById('pane-' + tab);
    if (pane) pane.style.display = '';
    const url = new URL(window.location.href);
    url.searchParams.set('tab', tab);
    window.history.replaceState({}, '', url);
}

function toggleQrisSection(val) {
    const s = document.getElementById('qrisSection');
    if (s) s.style.display = val === 'static_qris' ? '' : 'none';
}

// Helper POST JSON
function postJSON(url, data, btn, label) {
    if (btn) { btn.disabled = true; btn.innerText = 'Menyimpan…'; }
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: JSON.stringify(data)
    }).then(r => r.json()).then(d => {
        if (btn) { btn.disabled = false; btn.innerText = label; }
        if (d.success) {
            showToast(d.message || 'Tersimpan!');
        } else {
            alert(d.message || 'Gagal menyimpan.');
        }
        return d;
    }).catch(e => {
        if (btn) { btn.disabled = false; btn.innerText = label; }
        alert('Kesalahan jaringan: ' + e.message);
    });
}

function showToast(msg) {
    const t = document.createElement('div');
    t.innerText = msg;
    t.style.cssText = 'position:fixed;bottom:90px;left:50%;transform:translateX(-50%);background:#0f172a;color:#fff;padding:0.55rem 1rem;border-radius:999px;font-size:0.78rem;font-weight:600;z-index:999999;box-shadow:0 4px 14px rgba(0,0,0,.2);';
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2500);
}

// 1. Struk & Toko
function saveStoreSettings() {
    postJSON(
        '{{ route("creator.pos.update-settings") }}',
        { store_name: document.getElementById('setStoreName')?.value?.trim(),
          pos_store_address: document.getElementById('setStoreAddress')?.value?.trim(),
          pos_store_phone: document.getElementById('setStorePhone')?.value?.trim(),
          pos_receipt_footer: document.getElementById('setReceiptFooter')?.value?.trim() },
        document.getElementById('btnSaveStore'),
        'Simpan Pengaturan Struk'
    );
}

// 2. Logo Upload / Remove
function uploadPosLogo(input) {
    if (!input.files?.[0]) return;
    const fd = new FormData(); fd.append('logo', input.files[0]);
    fetch('{{ route("creator.pos.upload-logo") }}', { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}, body:fd })
        .then(r => r.json()).then(d => { if (d.success) { showToast('Logo berhasil diunggah!'); setTimeout(()=>location.reload(), 800); } else alert(d.message||'Gagal.'); });
}
function removePosLogo() {
    if (!confirm('Hapus logo?')) return;
    fetch('{{ route("creator.pos.remove-logo") }}', { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'} })
        .then(r=>r.json()).then(d=>{ if(d.success){showToast('Logo dihapus.'); setTimeout(()=>location.reload(),800);} });
}

// 3. Biaya
function addFeeRow() {
    document.getElementById('feesEmpty')?.remove();
    const d = document.createElement('div'); d.className='ps-row fee-row';
    d.innerHTML=`<div class="ps-row-inner">
        <input type="text" class="ps-input fee-name" placeholder="Nama Biaya" style="margin-bottom:4px;">
        <div style="display:flex;gap:5px;">
            <select class="ps-select fee-type" style="width:100px;"><option value="percent">Persen (%)</option><option value="fixed">Nominal (Rp)</option></select>
            <input type="number" class="ps-input fee-amount" placeholder="0" value="0">
        </div></div>
        <div class="ps-row-actions">
            <label style="font-size:.67rem;color:#475569;display:flex;align-items:center;gap:3px;cursor:pointer;white-space:nowrap;"><input type="checkbox" class="fee-enabled" checked> Aktif</label>
            <button type="button" onclick="this.closest('.fee-row').remove()" style="border:none;background:none;color:#ef4444;font-size:.67rem;cursor:pointer;padding:0;">Hapus</button>
        </div>`;
    document.getElementById('feesContainer').appendChild(d);
}
function saveFeesSettings() {
    const fees = [...document.querySelectorAll('.fee-row')].map(r => ({
        name:(r.querySelector('.fee-name')?.value||'').trim(),
        type:r.querySelector('.fee-type')?.value||'percent',
        amount:parseFloat(r.querySelector('.fee-amount')?.value||0),
        is_active:r.querySelector('.fee-enabled')?.checked||false
    })).filter(f=>f.name);
    postJSON('{{ route("creator.pos.update-settings") }}', {pos_fees:fees}, document.getElementById('btnSaveFees'), 'Simpan Pajak & Biaya');
}

// 4. Diskon
function addDiscountRow() {
    document.getElementById('discEmpty')?.remove();
    const d = document.createElement('div'); d.className='ps-row disc-row';
    d.innerHTML=`<div class="ps-row-inner">
        <input type="text" class="ps-input disc-name" placeholder="Nama Diskon" style="margin-bottom:4px;">
        <div style="display:flex;gap:5px;">
            <select class="ps-select disc-type" style="width:100px;"><option value="percent">Persen (%)</option><option value="fixed">Nominal (Rp)</option></select>
            <input type="number" class="ps-input disc-amount" placeholder="0" value="0">
        </div></div>
        <div class="ps-row-actions">
            <label style="font-size:.67rem;color:#475569;display:flex;align-items:center;gap:3px;cursor:pointer;white-space:nowrap;"><input type="checkbox" class="disc-enabled" checked> Aktif</label>
            <button type="button" onclick="this.closest('.disc-row').remove()" style="border:none;background:none;color:#ef4444;font-size:.67rem;cursor:pointer;padding:0;">Hapus</button>
        </div>`;
    document.getElementById('discountsContainer').appendChild(d);
}
function saveDiscountsSettings() {
    const discounts = [...document.querySelectorAll('.disc-row')].map(r => ({
        name:(r.querySelector('.disc-name')?.value||'').trim(),
        type:r.querySelector('.disc-type')?.value||'percent',
        amount:parseFloat(r.querySelector('.disc-amount')?.value||0),
        is_active:r.querySelector('.disc-enabled')?.checked||false
    })).filter(d=>d.name);
    postJSON('{{ route("creator.pos.update-settings") }}', {pos_discounts:discounts}, document.getElementById('btnSaveDisc'), 'Simpan Diskon & Promo');
}

// 5. Meja
function addTableRow() {
    document.getElementById('tableEmpty')?.remove();
    const d = document.createElement('div'); d.className='ps-row table-row'; d.style.alignItems='center';
    d.innerHTML=`<div style="width:30px;height:30px;border-radius:8px;background:#ffedd5;color:#c2410c;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div>
        <input type="text" class="ps-input table-name" placeholder="Meja 01" style="flex:1;margin:0 6px;">
        <button type="button" onclick="this.closest('.table-row').remove()" style="border:none;background:none;color:#ef4444;font-size:.67rem;cursor:pointer;padding:0;">Hapus</button>`;
    document.getElementById('tablesContainer').appendChild(d);
}
function saveTablesSettings() {
    const tables = [...document.querySelectorAll('.table-row')].map(r => ({ name:(r.querySelector('.table-name')?.value||'').trim() })).filter(t=>t.name);
    postJSON('{{ route("creator.pos.update-settings") }}', {pos_tables:tables}, document.getElementById('btnSaveTables'), 'Simpan Data Meja');
}

// 6. Kasir
function saveNewCashier() {
    const name=(document.getElementById('newCashierName')?.value||'').trim();
    const username=(document.getElementById('newCashierUsername')?.value||'').trim();
    const password=(document.getElementById('newCashierPassword')?.value||'').trim();
    if (!name||!username||!password) { alert('Isi Nama, Username, dan PIN/Password.'); return; }
    fetch('{{ route("creator.pos.cashiers.save") }}', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        body:JSON.stringify({name,username,password})
    }).then(r=>r.json()).then(d=>{ if(d.success){ showToast('Kasir ditambahkan!'); setTimeout(()=>location.reload(),800); } else alert(d.message||'Gagal.'); });
}
function deleteCashier(id) {
    if (!confirm('Hapus staf kasir ini?')) return;
    fetch('{{ url("creator/pos/cashiers") }}/'+id, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'} })
        .then(r=>r.json()).then(d=>{ if(d.success){ showToast('Kasir dihapus.'); setTimeout(()=>location.reload(),800); } });
}

// 7. Metode Bayar & QRIS
function savePaymentSettings() {
    postJSON('{{ route("creator.pos.update-settings") }}',
        { pos_cashless_mode: document.getElementById('setCashlessMode')?.value, pos_fee_payer: document.getElementById('setFeePayer')?.value },
        document.getElementById('btnSavePayment'), 'Simpan Pengaturan Pembayaran');
}
function uploadPosQris(input) {
    if (!input.files?.[0]) return;
    const fd = new FormData(); fd.append('qris_image', input.files[0]);
    fetch('{{ route("creator.pos.upload-qris") }}', { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}, body:fd })
        .then(r=>r.json()).then(d=>{ if(d.success){ showToast('QRIS berhasil diunggah!'); setTimeout(()=>location.reload(),800); } else alert(d.message||'Gagal.'); });
}
function removePosQris() {
    if (!confirm('Hapus QRIS toko?')) return;
    fetch('{{ route("creator.pos.remove-qris") }}', { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'} })
        .then(r=>r.json()).then(d=>{ if(d.success){ showToast('QRIS dihapus.'); setTimeout(()=>location.reload(),800); } });
}
</script>
@endsection
