@extends('creator.layout')

@section('title', 'Kasir Digital (POS)')
@section('page_title', 'Kasir Digital (POS)')
@section('page_subtitle', 'Kasir digital cepat & praktis. Auto-sync menu Makanan dari Link in Bio toko Anda.')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    * { font-family: 'Montserrat', sans-serif; box-sizing: border-box; }

    /* ── Card Styling (Sama dengan Profil & Link in Bio) ──────────────── */
    .prof-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .prof-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        border-bottom: 1.5px solid #f1f5f9;
        background: #fafbfa;
        font-size: 0.78rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .prof-card-head svg { color: #1eb349; }
    .prof-card-body { padding: 1.5rem; }

    /* ── Form Inputs & Controls ───────────────────────────────────────── */
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem; }
    .form-group { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 0.85rem; }
    .form-group.full { grid-column: 1 / -1; }

    .form-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .form-input {
        height: 44px;
        padding: 0 1rem;
        border: 1.5px solid #cbd5e1;
        border-radius: 12px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.84rem;
        font-weight: 500;
        color: #0f172a;
        background: #f8fafc;
        outline: none;
        transition: all 0.2s ease;
        width: 100%;
    }
    .form-input:focus {
        border-color: #1eb349;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(30, 179, 73, 0.12);
    }

    /* ── Link in Bio Signature Green Buttons ──────────────────────────── */
    .btn-submit-green {
        background: linear-gradient(135deg, #1eb349, #16963c);
        border-radius: 12px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 800;
        color: #ffffff;
        font-size: 0.9rem;
        padding: 0.8rem 1.5rem;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(30, 179, 73, 0.3);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        text-decoration: none;
    }
    .btn-submit-green:hover {
        background: linear-gradient(135deg, #16963c, #0f762e);
        box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45);
        transform: translateY(-2px);
        color: #ffffff;
    }
    .btn-submit-green:active {
        transform: scale(0.98);
    }
    .btn-submit-green:disabled {
        background: #cbd5e1 !important;
        box-shadow: none !important;
        cursor: not-allowed !important;
        transform: none !important;
    }

    .btn-outline-bio {
        background: #ffffff;
        border: 1.5px solid #1eb349;
        color: #1eb349;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .btn-outline-bio:hover {
        background: #f0fdf4;
        border-color: #16963c;
        color: #16963c;
        transform: translateY(-1px);
    }

    /* ── POS Layout Grid ──────────────────────────────────────────────── */
    .pos-wrapper {
        display: grid;
        grid-template-columns: 1fr 390px;
        gap: 1.25rem;
        align-items: start;
    }
    @media (max-width: 991px) {
        .pos-wrapper {
            grid-template-columns: 1fr;
        }
    }

    /* ── Search Input ─────────────────────────────────────────────────── */
    .search-input-wrap {
        position: relative;
        margin-bottom: 1rem;
    }
    .search-input-wrap svg {
        position: absolute;
        left: 14px;
        top: 14px;
        color: #94a3b8;
    }
    .search-input-wrap input {
        padding-left: 42px;
    }

    /* ── Product Grid ────────────────────────────────────────────────── */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(155px, 1fr));
        gap: 1rem;
    }
    @media (max-width: 576px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
    }

    .product-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 0.85rem;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        display: flex;
        flex-direction: column;
        user-select: none;
    }
    .product-card:hover {
        border-color: #1eb349;
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(30, 179, 73, 0.15);
    }
    .product-card:active {
        transform: scale(0.96);
    }

    .product-img-wrapper {
        width: 100%;
        height: 120px;
        border-radius: 12px;
        overflow: hidden;
        background: #f8fafc;
        margin-bottom: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-name {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
        margin-bottom: 0.35rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-price {
        font-size: 0.88rem;
        font-weight: 800;
        color: #1eb349;
        margin-top: auto;
    }
    .product-qty-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 4px 11px;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(30, 179, 73, 0.35);
        animation: popBadge 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes popBadge {
        0% { transform: scale(0.5); }
        100% { transform: scale(1); }
    }

    /* ── Cart Items List ─────────────────────────────────────────────── */
    .cart-items-container {
        max-height: 260px;
        overflow-y: auto;
        padding-right: 4px;
        margin-bottom: 1rem;
    }
    .cart-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .cart-item-info {
        flex: 1;
        padding-right: 0.5rem;
    }
    .cart-item-title {
        font-weight: 700;
        font-size: 0.84rem;
        color: #0f172a;
        line-height: 1.25;
    }
    .cart-item-unit-price {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 2px;
    }
    .cart-qty-ctrl {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .btn-qty {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1.5px solid #cbd5e1;
        background: #ffffff;
        color: #0f172a;
        font-weight: 800;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-qty:hover {
        background: #1eb349;
        color: #ffffff;
        border-color: #1eb349;
    }
    .qty-num {
        font-weight: 800;
        font-size: 0.88rem;
        min-width: 22px;
        text-align: center;
    }

    /* ── Summary & Calculator Box ────────────────────────────────────── */
    .summary-box {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        font-size: 0.83rem;
        color: #475569;
    }
    .summary-total {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        border-top: 2px dashed #cbd5e1;
        padding-top: 0.75rem;
        margin-top: 0.75rem;
    }

    /* ── Discount Type Toggle ────────────────────────────────────────── */
    .disc-toggle {
        display: flex;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }
    .disc-btn {
        padding: 3px 10px;
        font-size: 0.72rem;
        font-weight: 800;
        border: none;
        background: #transparent;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }
    .disc-btn.active {
        background: #1eb349;
        color: #fff;
    }

    /* ── CUSTOM MODAL OVERLAY SYSTEM (Fix Unhidden Modals Bug) ───────── */
    .pos-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(6px);
        z-index: 99999;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        transition: opacity 0.25s ease;
    }
    .pos-modal-overlay.show {
        display: flex;
        opacity: 1;
    }
    .pos-modal-card {
        background: #ffffff;
        border-radius: 24px;
        width: 100%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transform: scale(0.95);
        transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .pos-modal-overlay.show .pos-modal-card {
        transform: scale(1);
    }
    .pos-modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-bottom: 1.5px solid #f1f5f9;
        background: #fafbfa;
    }
    .pos-modal-head h5 {
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .pos-modal-close {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        font-weight: 700;
        transition: all 0.2s;
    }
    .pos-modal-close:hover {
        background: #fee2e2;
        color: #dc2626;
    }
    .pos-modal-body {
        padding: 1.5rem;
    }
    .pos-modal-foot {
        padding: 1rem 1.5rem 1.5rem;
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    /* ── Payment Options Cards ───────────────────────────────────────── */
    .pay-option-card {
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        transition: all 0.2s ease;
        margin-bottom: 0.75rem;
        background: #ffffff;
    }
    .pay-option-card:hover {
        border-color: #1eb349;
        background: #fafbfa;
    }
    .pay-option-card.selected {
        border-color: #1eb349;
        background: #f0fdf4;
        box-shadow: 0 0 0 2px rgba(30, 179, 73, 0.2);
    }

    /* ── Quick Money Pill Buttons ────────────────────────────────────── */
    .btn-quick-cash {
        background: #ffffff;
        border: 1.5px solid #86efac;
        color: #166534;
        font-weight: 700;
        font-size: 0.78rem;
        padding: 6px 14px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-quick-cash:hover {
        background: #1eb349;
        color: #ffffff;
        border-color: #1eb349;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(30, 179, 73, 0.25);
    }

    /* ── Mobile Fixed Bottom Drawer Bar ──────────────────────────────── */
    .mobile-cart-bar {
        display: none;
        position: fixed;
        bottom: 60px;
        left: 0;
        right: 0;
        background: #0f172a;
        color: #ffffff;
        padding: 0.9rem 1.25rem;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.25);
        z-index: 999;
        align-items: center;
        justify-content: space-between;
        border-radius: 16px 16px 0 0;
    }
    @media (max-width: 991px) {
        .mobile-cart-bar {
            display: flex;
        }
    }

    /* ── Thermal Receipt Styles ──────────────────────────────────────── */
    @media print {
        body * { visibility: hidden !important; }
        #printablePosReceiptArea, #printablePosReceiptArea * { visibility: visible !important; }
        #printablePosReceiptArea {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            max-width: 80mm !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .no-print { display: none !important; }
    }
</style>
@endsection

@section('topbar_actions')
<button type="button" class="btn-outline-bio" onclick="openModal('posHistoryModal')">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    Riwayat Transaksi Hari Ini
</button>
@endsection

@section('content')
@php
    $midtransClientKey = \App\Models\Setting::get('midtrans_client_key', config('services.midtrans.client_key'));
    $midtransIsProd   = (bool) \App\Models\Setting::get('midtrans_is_production', config('services.midtrans.is_production', false));
    $snapUrl           = $midtransIsProd ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
    $siteLogo          = \App\Models\Setting::get('logo');
    $siteLogoUrl       = $siteLogo ? url('storage/' . $siteLogo) : null;
    $storeName         = $profile->store_name ?? auth()->user()->name ?? 'Toko Saya';
@endphp

{{-- Midtrans Snap JS --}}
@if($midtransClientKey)
    <script src="{{ $snapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
@endif

<!-- POS Main Grid Wrapper -->
<div class="pos-wrapper">
    <!-- LEFT COLUMN: Product Search & Food Menu Grid -->
    <div>
        <div class="prof-card">
            <div class="prof-card-head">
                <div class="d-flex align-items-center gap-2">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                        <line x1="8" y1="7" x2="16" y2="7"/>
                        <line x1="8" y1="11" x2="10" y2="11"/>
                    </svg>
                    PILIH MENU MAKANAN
                </div>
                <span class="badge" style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-weight:700; padding:5px 12px; border-radius:20px; font-size:0.75rem;">
                    {{ count($products) }} Menu Tersedia
                </span>
            </div>

            <div class="prof-card-body">
                <!-- Search Bar Input -->
                <div class="search-input-wrap">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" id="posProductSearch" class="form-input" placeholder="Cari nama produk / menu makanan..." oninput="filterProducts()">
                </div>

                <!-- Products Grid -->
                <div class="product-grid" id="productGridContainer">
                    @forelse($products as $prod)
                        @php
                            $prodPrice = (float) ($prod->sale_price ?: $prod->price);
                        @endphp
                        <div class="product-card" data-id="{{ $prod->id }}" data-name="{{ strtolower($prod->name) }}" data-price="{{ $prodPrice }}" onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->name) }}', {{ $prodPrice }})">
                            <div class="product-qty-badge d-none" id="badge-qty-{{ $prod->id }}">0</div>
                            <div class="product-img-wrapper">
                                @if(!empty($prod->image))
                                    <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}" class="product-img">
                                @else
                                    <svg width="32" height="32" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="product-name">{{ $prod->name }}</div>
                            <div class="product-price">Rp {{ number_format($prodPrice, 0, ',', '.') }}</div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted" style="font-size:0.85rem;">
                            Belum ada produk kategori makanan yang aktif di Link in Bio Anda.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Order Cart & Calculator -->
    <div id="cartPanel">
        <div class="prof-card">
            <div class="prof-card-head">
                <div class="d-flex align-items-center gap-2">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    KERANJANG TRANSAKSI
                </div>
                <button type="button" onclick="clearCart()" style="font-size:0.75rem; font-weight:700; color:#dc2626; border:none; background:none; cursor:pointer;">
                    Kosongkan
                </button>
            </div>

            <div class="prof-card-body">
                <!-- Form Atas Nama Pelanggan (Mandatory) -->
                <div class="form-group">
                    <label class="form-label">
                        Atas Nama Pelanggan <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="posCustomerName" class="form-input" placeholder="Masukkan nama pelanggan" required>
                </div>

                <!-- Form Grid: Meja & Phone -->
                <div class="form-grid">
                    <div class="form-group mb-0">
                        <label class="form-label">No. Meja / Antrean</label>
                        <input type="text" id="posTableNumber" class="form-input" placeholder="Meja 04 (Opsional)">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">No. HP / WA Pelanggan</label>
                        <input type="text" id="posCustomerPhone" class="form-input" placeholder="08xxx (Opsional)">
                    </div>
                </div>

                <!-- Form Email Pelanggan (Wajib jika ingin kirim E-Receipt via email) -->
                <div class="form-group mt-2 mb-0">
                    <label class="form-label" style="display:flex; justify-content:space-between; align-items:center;">
                        <span>Email Pelanggan</span>
                        <span style="font-size:0.68rem; font-weight:500; color:#166534; background:#f0fdf4; padding:1px 6px; border-radius:4px; border:1px solid #bbf7d0;">Wajib jika ingin E-Receipt Email</span>
                    </label>
                    <input type="email" id="posCartCustomerEmail" class="form-input" placeholder="contoh@gmail.com (Opsional)" oninput="syncCustomerEmail(this.value)">
                </div>

                <hr style="border-top:1.5px dashed #e2e8f0; margin: 1.25rem 0;">

                <!-- Cart Items Container -->
                <div class="cart-items-container" id="cartItemsContainer">
                    <div class="text-center py-4 text-muted" id="cartEmptyNotice" style="font-size: 0.84rem;">
                        Keranjang masih kosong.<br>Tap menu makanan di sebelah kiri untuk menambah.
                    </div>
                </div>

                <!-- Summary Box & Fees Calculator -->
                <div class="summary-box">
                    <!-- Subtotal Row -->
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong style="color:#0f172a;" id="displaySubtotal">Rp 0</strong>
                    </div>

                    <!-- Diskon Field -->
                    <div class="summary-row">
                        <div class="d-flex align-items-center gap-1">
                            <span>Diskon</span>
                            <div class="disc-toggle">
                                <button type="button" class="disc-btn active" id="btnDiscNominal" onclick="setDiscountType('nominal')">Rp</button>
                                <button type="button" class="disc-btn" id="btnDiscPercent" onclick="setDiscountType('percent')">%</button>
                            </div>
                        </div>
                        <div style="width: 105px;">
                            <input type="number" id="posDiscountInput" class="form-input text-end" style="height:34px; padding:0 10px; font-size:0.8rem;" placeholder="0" min="0" oninput="calculateTotals()">
                        </div>
                    </div>

                    <!-- Service Fee Field -->
                    <div class="summary-row">
                        <span>Biaya Layanan Toko</span>
                        <div style="width: 105px;">
                            <input type="number" id="posServiceFeeInput" class="form-input text-end" style="height:34px; padding:0 10px; font-size:0.8rem;" placeholder="0" min="0" oninput="calculateTotals()">
                        </div>
                    </div>

                    <!-- Platform & Admin Fees Breakdown -->
                    <div class="summary-row" style="font-size:0.75rem; color:#94a3b8;">
                        <span>Biaya Platform ({{ $platformFeeRate }}%) & Admin ({{ $adminFeeRate }}%)</span>
                        <span id="displaySystemFees">Rp 0</span>
                    </div>

                    <!-- Grand Total -->
                    <div class="summary-total summary-row">
                        <span>Total Bayar</span>
                        <span style="color:#1eb349;" id="displayGrandTotal">Rp 0</span>
                    </div>
                </div>

                <!-- Checkout Button -->
                <button type="button" class="btn-submit-green" id="btnCheckout" onclick="openPaymentModal()" disabled>
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                    Proses Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Sticky Bottom Bar -->
<div class="mobile-cart-bar" id="mobileCartBar">
    <div>
        <div style="font-size:0.75rem; color:#94a3b8;" id="mobileCartCount">0 Items</div>
        <div style="font-size:1.1rem; font-weight:800; color:#ffffff;" id="mobileCartTotal">Rp 0</div>
    </div>
    <button type="button" class="btn-submit-green" style="width:auto; padding:0.6rem 1.2rem; font-size:0.82rem;" onclick="scrollToCartPanel()">
        Lihat Keranjang
    </button>
</div>

<!-- CUSTOM OVERLAY MODAL 1: Payment Method Selection -->
<div class="pos-modal-overlay" id="posPaymentModal">
    <div class="pos-modal-card">
        <div class="pos-modal-head">
            <h5>Pilih Metode Pembayaran</h5>
            <button type="button" class="pos-modal-close" onclick="closeModal('posPaymentModal')">&times;</button>
        </div>
        <div class="pos-modal-body">
            <div class="text-center mb-4 p-3" style="background: #f0fdf4; border-radius: 16px; border: 1.5px solid #bbf7d0;">
                <div style="font-size: 0.78rem; color: #166534; font-weight: 600;">Total Tagihan</div>
                <div style="font-size: 1.7rem; font-weight: 800; color: #1eb349;" id="modalPayTotal">Rp 0</div>
            </div>

            <label class="form-label mb-2">Pilih Metode Pembayaran</label>

            <!-- Tunai / Cash -->
            <div class="pay-option-card selected" id="optCash" onclick="selectPaymentMethod('cash')">
                <input type="radio" name="pay_method" value="cash" checked style="accent-color:#1eb349;">
                <svg width="22" height="22" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                <div>
                    <strong style="font-size:0.9rem; color:#0f172a;">Tunai (Cash)</strong>
                    <div style="font-size:0.75rem; color:#64748b;">Bayar langsung dengan uang tunai</div>
                </div>
            </div>

            <!-- Cash Calculator Panel -->
            <div id="cashCalcPanel" class="p-3 mb-3" style="background:#fafbfa; border-radius:14px; border:1.5px solid #e2e8f0;">
                <label class="form-label mb-1" style="color:#166534;">Nominal Uang Diterima (Rp)</label>
                <input type="number" id="cashPaidInput" class="form-input mb-2" placeholder="Masukkan jumlah uang" oninput="calculateCashChange()">
                
                <!-- Quick Cash Buttons -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button type="button" class="btn-quick-cash" onclick="setQuickCash('exact')">Uang Pas</button>
                    <button type="button" class="btn-quick-cash" onclick="setQuickCash(20000)">20.000</button>
                    <button type="button" class="btn-quick-cash" onclick="setQuickCash(50000)">50.000</button>
                    <button type="button" class="btn-quick-cash" onclick="setQuickCash(100000)">100.000</button>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top border-slate-200">
                    <span style="font-size:0.85rem; font-weight:700; color:#0f172a;">Kembalian:</span>
                    <strong style="font-size:1.15rem; color:#1eb349;" id="cashChangeDisplay">Rp 0</strong>
                </div>
            </div>

            <!-- Transfer Direct -->
            <div class="pay-option-card" id="optTransfer" onclick="selectPaymentMethod('transfer')">
                <input type="radio" name="pay_method" value="transfer" style="accent-color:#1eb349;">
                <svg width="22" height="22" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><rect x="9" y="11" width="12" height="8" rx="2"/></svg>
                <div>
                    <strong style="font-size:0.9rem; color:#0f172a;">Transfer Bank Direct</strong>
                    <div style="font-size:0.75rem; color:#64748b;">Transfer ke rekening bank toko</div>
                </div>
            </div>

            <!-- QRIS Midtrans -->
            <div class="pay-option-card" id="optQris" onclick="selectPaymentMethod('qris')">
                <input type="radio" name="pay_method" value="qris" style="accent-color:#1eb349;">
                <svg width="22" height="22" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                <div>
                    <strong style="font-size:0.9rem; color:#0f172a;">QRIS Dynamic (Midtrans)</strong>
                    <div style="font-size:0.75rem; color:#64748b;">Scan QRIS otomatis terverifikasi via Midtrans</div>
                </div>
            </div>

            <!-- Optional E-Receipt Email -->
            <div class="form-group mt-3 mb-0">
                <label class="form-label">Email Pembeli (Kirim E-Receipt Struk)</label>
                <input type="email" id="posCustomerEmail" class="form-input" placeholder="contoh@gmail.com (Opsional)">
            </div>
        </div>

        <div class="pos-modal-foot">
            <button type="button" class="btn-outline-bio" style="border-color:#cbd5e1; color:#64748b;" onclick="closeModal('posPaymentModal')">Batal</button>
            <button type="button" class="btn-submit-green" id="btnSubmitOrder" onclick="processOrderCheckout()" style="width:auto;">
                Konfirmasi & Bayar
            </button>
        </div>
    </div>
</div>

<!-- CUSTOM OVERLAY MODAL 2: Receipt Preview & Thermal Print -->
<div class="pos-modal-overlay" id="posReceiptModal">
    <div class="pos-modal-card" style="max-width: 440px;">
        <div class="pos-modal-head no-print">
            <h5>Struk Transaksi</h5>
            <button type="button" class="pos-modal-close" onclick="closeModal('posReceiptModal'); resetPOS();">&times;</button>
        </div>

        <div class="pos-modal-body" id="posReceiptModalContent">
            <!-- Thermal Struk Print Template -->
            <div style="font-family: 'Courier New', Courier, monospace; font-size: 12px; color: #000000; line-height: 1.3;" id="printablePosReceiptArea">
                <div class="text-center mb-3">
                    @if($siteLogoUrl)
                        <img src="{{ $siteLogoUrl }}" alt="buyle.id" style="height: 28px; width: auto; margin-bottom: 4px;" class="no-print">
                    @endif
                    <div style="font-weight: 900; font-size: 15px; text-transform: uppercase;">{{ $storeName }}</div>
                    <div style="font-size: 11px;">buyle.id POS</div>
                    <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>
                </div>

                <table style="width: 100%; font-size: 11px; margin-bottom: 8px;">
                    <tr>
                        <td>No Order:</td>
                        <td class="text-end" style="font-weight:700;" id="recOrderNum">-</td>
                    </tr>
                    <tr>
                        <td>Tanggal:</td>
                        <td class="text-end" id="recDate">-</td>
                    </tr>
                    <tr>
                        <td>Pelanggan:</td>
                        <td class="text-end" style="font-weight:700;" id="recCustomerName">-</td>
                    </tr>
                    <tr id="recNotesRow" style="display:none;">
                        <td>Catatan:</td>
                        <td class="text-end" id="recNotes">-</td>
                    </tr>
                    <tr>
                        <td>Metode:</td>
                        <td class="text-end" style="font-weight:700;" id="recPayMethod">-</td>
                    </tr>
                </table>

                <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>

                <table style="width: 100%; font-size: 11px; margin-bottom: 8px;" id="recItemsTable">
                    <thead>
                        <tr style="border-bottom: 1px solid #000;">
                            <th class="text-start">Item</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>

                <table style="width: 100%; font-size: 11px;">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-end" id="recSubtotal">Rp 0</td>
                    </tr>
                    <tr id="recDiscountRow" style="display:none;">
                        <td>Diskon:</td>
                        <td class="text-end" id="recDiscount">-Rp 0</td>
                    </tr>
                    <tr id="recServiceFeeRow" style="display:none;">
                        <td>Biaya Layanan:</td>
                        <td class="text-end" id="recServiceFee">Rp 0</td>
                    </tr>
                    <tr id="recPlatformFeeRow">
                        <td>Biaya Platform:</td>
                        <td class="text-end" id="recPlatformFee">Rp 0</td>
                    </tr>
                    <tr id="recAdminFeeRow">
                        <td>Biaya Admin:</td>
                        <td class="text-end" id="recAdminFee">Rp 0</td>
                    </tr>
                    <tr style="font-weight: 900; font-size: 13px;">
                        <td style="padding-top:4px;">TOTAL:</td>
                        <td class="text-end" style="padding-top:4px;" id="recTotal">Rp 0</td>
                    </tr>
                    <tr id="recCashPaidRow" style="display:none;">
                        <td>Tunai Diterima:</td>
                        <td class="text-end" id="recCashPaid">Rp 0</td>
                    </tr>
                    <tr id="recCashChangeRow" style="display:none;">
                        <td>Kembalian:</td>
                        <td class="text-end" id="recCashChange">Rp 0</td>
                    </tr>
                </table>

                <div style="border-bottom: 1px dashed #000; margin: 12px 0 8px 0;"></div>
                <div class="text-center" style="font-size: 10px; color: #444444;">
                    Terima kasih atas kunjungan Anda!<br>
                    Powered by buyle.id
                </div>
            </div>

            <!-- E-Receipt Email (no-print) -->
            <div class="no-print mt-4 pt-3 border-top">
                <label class="form-label mb-1">Kirim E-Receipt ke Email</label>
                <div class="d-flex gap-2">
                    <input type="email" id="recSendEmailInput" class="form-input" placeholder="email@pembeli.com">
                    <button class="btn-submit-green" style="width:auto; padding:0 1.25rem; font-size:0.82rem;" onclick="sendEReceiptEmail()">Kirim</button>
                </div>
            </div>
        </div>

        <div class="pos-modal-foot no-print">
            <button type="button" class="btn-outline-bio" style="border-color:#cbd5e1; color:#64748b;" onclick="closeModal('posReceiptModal'); resetPOS();">
                Transaksi Baru
            </button>
            <button type="button" class="btn-submit-green" style="width:auto;" onclick="window.print()">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Print Struk Thermal
            </button>
        </div>
    </div>
</div>

<!-- CUSTOM OVERLAY MODAL 3: History Modal -->
<div class="pos-modal-overlay" id="posHistoryModal">
    <div class="pos-modal-card" style="max-width: 720px;">
        <div class="pos-modal-head">
            <h5>Riwayat Transaksi POS Hari Ini</h5>
            <button type="button" class="pos-modal-close" onclick="closeModal('posHistoryModal')">&times;</button>
        </div>
        <div class="pos-modal-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size:0.83rem; width:100%;">
                    <thead style="background:#fafbfa; color:#64748b; font-size:0.75rem; text-transform:uppercase;">
                        <tr>
                            <th class="ps-4">No. Order / Waktu</th>
                            <th>Pelanggan</th>
                            <th>Metode Bayar</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayOrders as $tOrd)
                            @php
                                $tAddr = is_array($tOrd->shipping_address) ? $tOrd->shipping_address : (json_decode($tOrd->shipping_address ?? '', true) ?? []);
                                $tPayMethod = $tAddr['payment_method'] ?? ($tOrd->payment?->payment_method ?? 'cash');
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <strong style="color:#0f172a;">#{{ $tOrd->order_number }}</strong>
                                    <div style="font-size:0.73rem; color:#64748b;">{{ $tOrd->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    <div style="font-weight:700; color:#0f172a;">{{ $tAddr['name'] ?? 'Pelanggan' }}</div>
                                    @if(!empty($tOrd->notes))
                                        <div style="font-size:0.73rem; color:#64748b;">{{ $tOrd->notes }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; text-transform:uppercase; font-weight:700;">{{ $tPayMethod }}</span>
                                </td>
                                <td class="font-weight-bold" style="color:#1eb349;">
                                    Rp {{ number_format($tOrd->total, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge" style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-weight:700;">Berhasil</span>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn-outline-bio py-1 px-2" style="font-size:0.75rem;" onclick="reprintPastReceipt({{ json_encode($tOrd) }})">
                                        Cetak Struk
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Belum ada transaksi POS hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // State POS
    let cart = [];
    let discountType = 'nominal';
    let platformFeeRate = {{ $platformFeeRate }};
    let adminFeeRate = {{ $adminFeeRate }};
    let currentCompletedOrder = null;

    // Custom Modal Helpers (Vanilla JS - Independent from Bootstrap)
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
        }
    }

    // Close Modal when clicking backdrop overlay
    document.querySelectorAll('.pos-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    });

    // Filter produk live
    function filterProducts() {
        const query = document.getElementById('posProductSearch').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.product-card');

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            if (name.includes(query)) {
                card.classList.remove('d-none');
            } else {
                card.classList.add('d-none');
            }
        });
    }

    // Add product to cart
    function addToCart(id, name, price) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({ id, name, price, qty: 1 });
        }
        renderCart();
    }

    // Update Item Qty
    function updateQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (!item) return;

        item.qty += delta;
        if (item.qty <= 0) {
            cart = cart.filter(i => i.id !== id);
        }
        renderCart();
    }

    // Remove Item
    function removeItem(id) {
        cart = cart.filter(i => i.id !== id);
        renderCart();
    }

    // Clear Cart
    function clearCart() {
        cart = [];
        renderCart();
    }

    // Render Cart HTML & Badges
    function renderCart() {
        const container = document.getElementById('cartItemsContainer');
        
        document.querySelectorAll('.product-qty-badge').forEach(b => {
            b.classList.add('d-none');
            b.innerText = '0';
        });

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4 text-muted" id="cartEmptyNotice" style="font-size: 0.84rem;">
                    Keranjang masih kosong.<br>Tap menu makanan di sebelah kiri untuk menambah.
                </div>
            `;
            document.getElementById('btnCheckout').disabled = true;
            calculateTotals();
            return;
        }

        document.getElementById('btnCheckout').disabled = false;
        let html = '';

        cart.forEach(item => {
            const itemSub = item.price * item.qty;
            
            const badge = document.getElementById(`badge-qty-${item.id}`);
            if (badge) {
                badge.innerText = `x${item.qty}`;
                badge.classList.remove('d-none');
            }

            html += `
                <div class="cart-item-row">
                    <div class="cart-item-info">
                        <div class="cart-item-title">${escapeHtml(item.name)}</div>
                        <div class="cart-item-unit-price">@ Rp ${formatRupiah(item.price)} &bull; Sub: Rp ${formatRupiah(itemSub)}</div>
                    </div>
                    <div class="cart-qty-ctrl">
                        <button type="button" class="btn-qty" onclick="updateQty(${item.id}, -1)">-</button>
                        <span class="qty-num">${item.qty}</span>
                        <button type="button" class="btn-qty" onclick="updateQty(${item.id}, 1)">+</button>
                        <button type="button" class="btn text-danger ms-1 p-0" onclick="removeItem(${item.id})" style="border:none; background:none;">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        calculateTotals();
    }

    // Toggle Discount Type (nominal / percent)
    function setDiscountType(type) {
        discountType = type;
        document.getElementById('btnDiscNominal').classList.toggle('active', type === 'nominal');
        document.getElementById('btnDiscPercent').classList.toggle('active', type === 'percent');
        calculateTotals();
    }

    // Calculate Subtotal, Discounts, Fees & Total
    function calculateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        
        let discountVal = parseFloat(document.getElementById('posDiscountInput').value) || 0;
        let discountAmount = 0;

        if (discountType === 'percent') {
            discountVal = Math.min(100, Math.max(0, discountVal));
            discountAmount = (subtotal * discountVal) / 100;
        } else {
            discountAmount = Math.min(subtotal, Math.max(0, discountVal));
        }

        const serviceFee = Math.max(0, parseFloat(document.getElementById('posServiceFeeInput').value) || 0);

        const platformFee = (subtotal * platformFeeRate) / 100;
        const adminFee = (subtotal * adminFeeRate) / 100;
        const systemFees = platformFee + adminFee;

        const grandTotal = Math.max(0, subtotal - discountAmount + serviceFee + systemFees);

        document.getElementById('displaySubtotal').innerText = 'Rp ' + formatRupiah(subtotal);
        document.getElementById('displaySystemFees').innerText = 'Rp ' + formatRupiah(systemFees);
        document.getElementById('displayGrandTotal').innerText = 'Rp ' + formatRupiah(grandTotal);

        const totalItemsCount = cart.reduce((sum, item) => sum + item.qty, 0);
        document.getElementById('mobileCartCount').innerText = `${totalItemsCount} Items`;
        document.getElementById('mobileCartTotal').innerText = 'Rp ' + formatRupiah(grandTotal);
    }

    function syncCustomerEmail(val) {
        const target = document.getElementById('posCustomerEmail');
        if (target) target.value = val;
    }

    // Open Payment Modal
    function openPaymentModal() {
        const custName = document.getElementById('posCustomerName').value.trim();
        if (!custName) {
            alert('Silakan isi Form Atas Nama Pelanggan terlebih dahulu!');
            document.getElementById('posCustomerName').focus();
            return;
        }

        if (cart.length === 0) {
            alert('Keranjang POS masih kosong!');
            return;
        }

        // Sync email from cart input if set
        const cartEmail = (document.getElementById('posCartCustomerEmail')?.value || '').trim();
        if (cartEmail) {
            document.getElementById('posCustomerEmail').value = cartEmail;
        }

        const grandTotalText = document.getElementById('displayGrandTotal').innerText;
        document.getElementById('modalPayTotal').innerText = grandTotalText;
        
        const grandTotal = getGrandTotalVal();
        document.getElementById('cashPaidInput').value = grandTotal;
        calculateCashChange();

        openModal('posPaymentModal');
    }

    function getGrandTotalVal() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        let discountVal = parseFloat(document.getElementById('posDiscountInput').value) || 0;
        let discountAmount = discountType === 'percent' ? (subtotal * Math.min(100, discountVal)) / 100 : Math.min(subtotal, discountVal);
        const serviceFee = Math.max(0, parseFloat(document.getElementById('posServiceFeeInput').value) || 0);
        const systemFees = (subtotal * (platformFeeRate + adminFeeRate)) / 100;
        return Math.max(0, subtotal - discountAmount + serviceFee + systemFees);
    }

    // Select Payment Method
    function selectPaymentMethod(method) {
        document.querySelectorAll('.pay-option-card').forEach(c => c.classList.remove('selected'));
        const rad = document.querySelector(`input[name="pay_method"][value="${method}"]`);
        if (rad) rad.checked = true;

        const card = document.getElementById('opt' + method.charAt(0).toUpperCase() + method.slice(1));
        if (card) card.classList.add('selected');

        const cashPanel = document.getElementById('cashCalcPanel');
        if (method === 'cash') {
            cashPanel.style.display = 'block';
        } else {
            cashPanel.style.display = 'none';
        }
    }

    // Quick Cash Buttons
    function setQuickCash(val) {
        if (val === 'exact') {
            document.getElementById('cashPaidInput').value = getGrandTotalVal();
        } else {
            document.getElementById('cashPaidInput').value = val;
        }
        calculateCashChange();
    }

    // Calculate Cash Change
    function calculateCashChange() {
        const grandTotal = getGrandTotalVal();
        const cashPaid = parseFloat(document.getElementById('cashPaidInput').value) || 0;
        const change = Math.max(0, cashPaid - grandTotal);
        document.getElementById('cashChangeDisplay').innerText = 'Rp ' + formatRupiah(change);
    }

    // Submit Order Checkout
    function processOrderCheckout() {
        const custName = document.getElementById('posCustomerName').value.trim();
        const tableNum = document.getElementById('posTableNumber').value.trim();
        const custPhone = document.getElementById('posCustomerPhone').value.trim();
        const custEmail = (document.getElementById('posCartCustomerEmail')?.value || document.getElementById('posCustomerEmail')?.value || '').trim();

        const payMethod = document.querySelector('input[name="pay_method"]:checked').value;
        const cashPaid = parseFloat(document.getElementById('cashPaidInput').value) || 0;
        const grandTotal = getGrandTotalVal();

        if (payMethod === 'cash' && cashPaid < grandTotal) {
            alert('Jumlah uang tunai yang diterima kurang dari total tagihan!');
            return;
        }

        const btnSubmit = document.getElementById('btnSubmitOrder');
        btnSubmit.disabled = true;
        btnSubmit.innerText = 'Memproses...';

        const payload = {
            _token: '{{ csrf_token() }}',
            customer_name: custName,
            table_number: tableNum,
            customer_phone: custPhone,
            customer_email: custEmail,
            items: cart.map(i => ({ id: i.id, qty: i.qty })),
            discount_type: discountType,
            discount_val: parseFloat(document.getElementById('posDiscountInput').value) || 0,
            service_fee: parseFloat(document.getElementById('posServiceFeeInput').value) || 0,
            payment_method: payMethod,
            cash_paid: cashPaid,
            cash_change: Math.max(0, cashPaid - grandTotal)
        };

        fetch('{{ route("creator.pos.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btnSubmit.disabled = false;
            btnSubmit.innerText = 'Konfirmasi & Bayar';

            if (data.success) {
                closeModal('posPaymentModal');

                if (payMethod === 'qris' && data.snap_token && typeof window.snap !== 'undefined') {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            showReceiptModal(data.order);
                        },
                        onPending: function(result) {
                            showReceiptModal(data.order);
                        },
                        onError: function(result) {
                            alert('Pembayaran QRIS Gagal atau dibatalkan.');
                        },
                        onClose: function() {
                            showReceiptModal(data.order);
                        }
                    });
                } else {
                    showReceiptModal(data.order);
                }
            } else {
                alert(data.message || 'Gagal memproses transaksi.');
            }
        })
        .catch(err => {
            btnSubmit.disabled = false;
            btnSubmit.innerText = 'Konfirmasi & Bayar';
            alert('Terjadi kesalahan jaringan/server: ' + err.message);
        });
    }

    // Show Receipt Modal
    function showReceiptModal(order) {
        currentCompletedOrder = order;
        const addr = order.shipping_address || {};

        document.getElementById('recOrderNum').innerText = '#' + (order.order_number || ('ORD-' + order.id));
        document.getElementById('recDate').innerText = formatDate(order.created_at || new Date().toISOString());
        document.getElementById('recCustomerName').innerText = addr.name || 'Pelanggan';
        
        if (order.notes) {
            document.getElementById('recNotes').innerText = order.notes;
            document.getElementById('recNotesRow').style.display = 'table-row';
        } else {
            document.getElementById('recNotesRow').style.display = 'none';
        }

        const payMethodLabel = {
            'cash': 'Tunai',
            'transfer': 'Transfer Direct',
            'qris': 'QRIS Midtrans'
        }[addr.payment_method || 'cash'] || 'Tunai';

        document.getElementById('recPayMethod').innerText = payMethodLabel;

        let itemsHtml = '';
        (order.items || []).forEach(item => {
            itemsHtml += `
                <tr>
                    <td class="text-start" style="font-weight:700;">
                        ${escapeHtml(item.product_name)}
                        <div style="font-size:10px; font-weight:normal; color:#444;">@ Rp ${formatRupiah(item.price)}</div>
                    </td>
                    <td class="text-center" style="vertical-align:top;">${item.quantity}</td>
                    <td class="text-end" style="font-weight:700; vertical-align:top;">Rp ${formatRupiah(item.subtotal)}</td>
                </tr>
            `;
        });
        document.querySelector('#recItemsTable tbody').innerHTML = itemsHtml;

        document.getElementById('recSubtotal').innerText = 'Rp ' + formatRupiah(order.subtotal);

        if (order.discount > 0) {
            document.getElementById('recDiscount').innerText = '-Rp ' + formatRupiah(order.discount);
            document.getElementById('recDiscountRow').style.display = 'table-row';
        } else {
            document.getElementById('recDiscountRow').style.display = 'none';
        }

        if (addr.service_fee > 0) {
            document.getElementById('recServiceFee').innerText = 'Rp ' + formatRupiah(addr.service_fee);
            document.getElementById('recServiceFeeRow').style.display = 'table-row';
        } else {
            document.getElementById('recServiceFeeRow').style.display = 'none';
        }

        document.getElementById('recPlatformFee').innerText = 'Rp ' + formatRupiah(order.platform_fee || 0);
        document.getElementById('recAdminFee').innerText = 'Rp ' + formatRupiah(order.admin_fee || 0);
        document.getElementById('recTotal').innerText = 'Rp ' + formatRupiah(order.total);

        if (addr.payment_method === 'cash' && addr.cash_paid > 0) {
            document.getElementById('recCashPaid').innerText = 'Rp ' + formatRupiah(addr.cash_paid);
            document.getElementById('recCashChange').innerText = 'Rp ' + formatRupiah(addr.cash_change || 0);
            document.getElementById('recCashPaidRow').style.display = 'table-row';
            document.getElementById('recCashChangeRow').style.display = 'table-row';
        } else {
            document.getElementById('recCashPaidRow').style.display = 'none';
            document.getElementById('recCashChangeRow').style.display = 'none';
        }

        if (addr.email) {
            document.getElementById('recSendEmailInput').value = addr.email;
        }

        openModal('posReceiptModal');
    }

    // Send E-Receipt Email
    function sendEReceiptEmail() {
        if (!currentCompletedOrder) return;
        const email = document.getElementById('recSendEmailInput').value.trim();
        if (!email) {
            alert('Silakan masukkan alamat email tujuan!');
            return;
        }

        fetch('{{ route("creator.pos.send-receipt") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                order_id: currentCompletedOrder.id,
                email: email
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
        })
        .catch(err => {
            alert('Gagal mengirim E-Receipt: ' + err.message);
        });
    }

    // Reprint Past Order Receipt
    function reprintPastReceipt(order) {
        closeModal('posHistoryModal');
        showReceiptModal(order);
    }

    // Reset POS State
    function resetPOS() {
        cart = [];
        document.getElementById('posCustomerName').value = '';
        document.getElementById('posTableNumber').value = '';
        document.getElementById('posCustomerPhone').value = '';
        document.getElementById('posCustomerEmail').value = '';
        document.getElementById('posDiscountInput').value = '';
        document.getElementById('posServiceFeeInput').value = '';
        renderCart();
    }

    function scrollToCartPanel() {
        document.getElementById('cartPanel').scrollIntoView({ behavior: 'smooth' });
    }

    function formatRupiah(num) {
        return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function formatDate(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function escapeHtml(str) {
        return (str || '').replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
    }
</script>
@endsection
