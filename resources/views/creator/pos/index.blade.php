@extends('creator.layout')

@section('title', 'Kasir Digital (POS)')

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

<style>
    /* POS Container Layout */
    .pos-wrapper {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 991px) {
        .pos-wrapper {
            grid-template-columns: 1fr;
        }
    }

    /* Card Panels */
    .pos-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        padding: 20px;
        margin-bottom: 20px;
    }

    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 14px;
        margin-top: 16px;
    }
    @media (max-width: 576px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    }

    .product-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        user-select: none;
    }
    .product-card:hover {
        border-color: #1eb349;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(30, 179, 73, 0.12);
    }
    .product-card:active {
        transform: scale(0.98);
    }
    .product-img-wrapper {
        width: 100%;
        height: 110px;
        border-radius: 8px;
        overflow: hidden;
        background: #f8fafc;
        margin-bottom: 8px;
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
        font-size: 0.88rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.25;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-price {
        font-size: 0.85rem;
        font-weight: 800;
        color: #1eb349;
        margin-top: auto;
    }
    .product-qty-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        background: #1eb349;
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 20px;
        box-shadow: 0 2px 6px rgba(30, 179, 73, 0.3);
    }

    /* Cart Right Sidebar */
    .cart-panel {
        position: sticky;
        top: 80px;
    }
    .cart-items-container {
        max-height: 280px;
        overflow-y: auto;
        padding-right: 4px;
        margin-bottom: 16px;
    }
    .cart-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .cart-item-info {
        flex: 1;
        padding-right: 8px;
    }
    .cart-item-title {
        font-weight: 700;
        font-size: 0.85rem;
        color: #0f172a;
        line-height: 1.2;
    }
    .cart-item-unit-price {
        font-size: 0.75rem;
        color: #64748b;
    }
    .cart-qty-ctrl {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-qty {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #0f172a;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-qty:hover {
        background: #1eb349;
        color: #ffffff;
        border-color: #1eb349;
    }
    .qty-num {
        font-weight: 700;
        font-size: 0.85rem;
        min-width: 20px;
        text-align: center;
    }

    /* Summary Breakdown */
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 0.85rem;
        color: #475569;
    }
    .summary-total {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        border-top: 2px solid #e2e8f0;
        padding-top: 10px;
        margin-top: 10px;
    }

    /* Custom Form Inputs */
    .pos-input {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.85rem;
        outline: none;
        transition: border-color 0.2s;
    }
    .pos-input:focus {
        border-color: #1eb349;
        box-shadow: 0 0 0 3px rgba(30, 179, 73, 0.12);
    }

    /* Buttons */
    .btn-pos-primary {
        width: 100%;
        padding: 13px;
        background: #1eb349;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.95rem;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-pos-primary:hover {
        background: #16963c;
    }
    .btn-pos-primary:disabled {
        background: #94a3b8;
        cursor: not-allowed;
    }

    /* Radio Payment Selector */
    .pay-option-card {
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        padding: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s;
        margin-bottom: 8px;
    }
    .pay-option-card.selected {
        border-color: #1eb349;
        background: #f0fdf4;
    }

    /* Mobile Sticky Bar */
    .mobile-cart-bar {
        display: none;
        position: fixed;
        bottom: 60px;
        left: 0;
        right: 0;
        background: #0f172a;
        color: #ffffff;
        padding: 12px 16px;
        box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.15);
        z-index: 99;
        align-items: center;
        justify-content: space-between;
    }
    @media (max-width: 991px) {
        .mobile-cart-bar {
            display: flex;
        }
    }

    /* Thermal Receipt Print Styles */
    @media print {
        body * {
            visibility: hidden !important;
        }
        #posReceiptModalContent, #posReceiptModalContent * {
            visibility: visible !important;
        }
        #posReceiptModalContent {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            max-width: 80mm !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="container-fluid px-0">
    <!-- Header Title Banner -->
    <div class="pos-card mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <svg width="22" height="22" fill="none" stroke="#1eb349" stroke-width="2.5" viewBox="0 0 24 24">
                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                        <line x1="8" y1="7" x2="16" y2="7"/>
                        <line x1="8" y1="11" x2="10" y2="11"/>
                        <line x1="14" y1="11" x2="16" y2="11"/>
                        <line x1="8" y1="15" x2="10" y2="15"/>
                        <line x1="14" y1="15" x2="16" y2="15"/>
                    </svg>
                    <h4 class="m-0 font-weight-bold" style="letter-spacing: -0.02em; color: #ffffff;">
                        Kasir Digital (POS)
                    </h4>
                </div>
                <div class="text-muted" style="font-size: 0.82rem; color: #94a3b8 !important;">
                    Toko: <strong>{{ $storeName }}</strong> &bull; Auto-sync menu Makanan dari Link in Bio
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-light d-flex align-items-center gap-1" onclick="openHistoryModal()">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Riwayat Transaksi Hari Ini
                </button>
            </div>
        </div>
    </div>

    <!-- POS Main Grid Wrapper -->
    <div class="pos-wrapper">
        <!-- LEFT COLUMN: Product Search + Menu Grid -->
        <div>
            <!-- Search Bar & Filters -->
            <div class="pos-card">
                <div class="row g-2 align-items-center">
                    <div class="col-md-7">
                        <div class="position-relative">
                            <svg width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24" style="position:absolute;left:12px;top:11px;">
                                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            <input type="text" id="posProductSearch" class="pos-input" style="padding-left: 36px;" placeholder="Cari nama produk / menu makanan..." oninput="filterProducts()">
                        </div>
                    </div>
                    <div class="col-md-5 text-end">
                        <span class="badge" style="background:#ecfdf5; color:#059669; font-weight:700; padding:6px 12px; border-radius:6px; font-size:0.78rem;">
                            {{ count($products) }} Menu Makanan Tersedia
                        </span>
                    </div>
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
                        <div class="col-12 text-center py-5 text-muted">
                            Belum ada produk kategori makanan yang aktif di Link in Bio Anda.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Order Cart & Calculator -->
        <div class="cart-panel" id="cartPanel">
            <div class="pos-card">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <h5 class="m-0 font-weight-bold" style="color: #0f172a;">Keranjang POS</h5>
                    <button class="btn btn-sm text-danger p-0 font-weight-bold" onclick="clearCart()" style="font-size:0.8rem;">
                        Kosongkan
                    </button>
                </div>

                <!-- Form Atas Nama Pelanggan (Mandatory) -->
                <div class="mb-3">
                    <label class="form-label font-weight-bold mb-1" style="font-size: 0.8rem; color: #334155;">
                        Atas Nama Pelanggan <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="posCustomerName" class="pos-input" placeholder="Masukkan nama pelanggan" required>
                </div>

                <!-- Optional Meja & Contacts -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label mb-1" style="font-size: 0.75rem; color: #64748b;">No. Meja / Antrean</label>
                        <input type="text" id="posTableNumber" class="pos-input" placeholder="Contoh: Meja 04">
                    </div>
                    <div class="col-6">
                        <label class="form-label mb-1" style="font-size: 0.75rem; color: #64748b;">No. HP Pelanggan</label>
                        <input type="text" id="posCustomerPhone" class="pos-input" placeholder="08xxx (Opsional)">
                    </div>
                </div>

                <!-- Cart Items List -->
                <div class="cart-items-container" id="cartItemsContainer">
                    <div class="text-center py-4 text-muted" id="cartEmptyNotice" style="font-size: 0.85rem;">
                        Keranjang masih kosong.<br>Tap menu makanan di sebelah kiri untuk menambah.
                    </div>
                </div>

                <!-- Calculator & Discount Controls -->
                <div class="border-top pt-3">
                    <!-- Subtotal Row -->
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong style="color:#0f172a;" id="displaySubtotal">Rp 0</strong>
                    </div>

                    <!-- Diskon Field -->
                    <div class="summary-row">
                        <div class="d-flex align-items-center gap-1">
                            <span>Diskon</span>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary py-0 px-1 btn-discount-type active" id="btnDiscNominal" onclick="setDiscountType('nominal')" style="font-size:0.7rem;">Rp</button>
                                <button type="button" class="btn btn-outline-secondary py-0 px-1 btn-discount-type" id="btnDiscPercent" onclick="setDiscountType('percent')" style="font-size:0.7rem;">%</button>
                            </div>
                        </div>
                        <div style="width: 110px;">
                            <input type="number" id="posDiscountInput" class="pos-input text-end py-1" placeholder="0" min="0" oninput="calculateTotals()">
                        </div>
                    </div>

                    <!-- Service Fee Field -->
                    <div class="summary-row">
                        <span>Biaya Layanan Toko</span>
                        <div style="width: 110px;">
                            <input type="number" id="posServiceFeeInput" class="pos-input text-end py-1" placeholder="0" min="0" oninput="calculateTotals()">
                        </div>
                    </div>

                    <!-- Platform & Admin Fee Transparent Breakdown -->
                    <div class="summary-row" style="font-size:0.75rem; color:#94a3b8;">
                        <span>Biaya Platform ({{ $platformFeeRate }}%) & Admin ({{ $adminFeeRate }}%)</span>
                        <span id="displaySystemFees">Rp 0</span>
                    </div>

                    <!-- Total Amount -->
                    <div class="summary-total summary-row">
                        <span>Total Bayar</span>
                        <span style="color:#1eb349;" id="displayGrandTotal">Rp 0</span>
                    </div>

                    <!-- Checkout Button -->
                    <button class="btn-pos-primary mt-3" id="btnCheckout" onclick="openPaymentModal()" disabled>
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                        Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Fixed Bottom Drawer Bar -->
<div class="mobile-cart-bar" id="mobileCartBar">
    <div>
        <div style="font-size:0.75rem; color:#94a3b8;" id="mobileCartCount">0 Items</div>
        <div style="font-size:1.05rem; font-weight:800; color:#ffffff;" id="mobileCartTotal">Rp 0</div>
    </div>
    <button class="btn btn-sm btn-success font-weight-bold px-3 py-2" onclick="scrollToCartPanel()">
        Lihat Keranjang
    </button>
</div>

<!-- MODAL 1: Payment Method & Calculator -->
<div class="modal fade" id="posPaymentModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden;">
            <div class="modal-header border-0 pb-0" style="background:#0f172a; color:#ffffff;">
                <h5 class="modal-title font-weight-bold" style="color:#ffffff;">Pilih Metode Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4 p-3" style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 0.8rem; color: #64748b;">Total Tagihan</div>
                    <div style="font-size: 1.6rem; font-weight: 800; color: #1eb349;" id="modalPayTotal">Rp 0</div>
                </div>

                <!-- Payment Options Radio -->
                <label class="form-label font-weight-bold mb-2" style="font-size: 0.85rem;">Metode Pembayaran</label>
                
                <!-- Tunai / Cash -->
                <div class="pay-option-card selected" id="optCash" onclick="selectPaymentMethod('cash')">
                    <input type="radio" name="pay_method" value="cash" checked>
                    <svg width="20" height="20" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg>
                    <div>
                        <strong style="font-size:0.9rem; color:#0f172a;">Tunai (Cash)</strong>
                        <div style="font-size:0.75rem; color:#64748b;">Bayar langsung dengan uang tunai</div>
                    </div>
                </div>

                <!-- Cash Nominal & Change Calculator Panel -->
                <div id="cashCalcPanel" class="p-3 mb-3" style="background:#f0fdf4; border-radius:10px; border:1px solid #bbf7d0;">
                    <label class="form-label font-weight-bold mb-1" style="font-size:0.78rem; color:#166534;">Nominal Uang Diterima (Rp)</label>
                    <input type="number" id="cashPaidInput" class="pos-input mb-2" placeholder="Masukkan jumlah uang" oninput="calculateCashChange()">
                    
                    <!-- Quick Nominal Buttons -->
                    <div class="d-flex flex-wrap gap-1 mb-2">
                        <button type="button" class="btn btn-xs btn-outline-success font-weight-bold" onclick="setQuickCash('exact')">Uang Pas</button>
                        <button type="button" class="btn btn-xs btn-outline-success font-weight-bold" onclick="setQuickCash(20000)">20.000</button>
                        <button type="button" class="btn btn-xs btn-outline-success font-weight-bold" onclick="setQuickCash(50000)">50.000</button>
                        <button type="button" class="btn btn-xs btn-outline-success font-weight-bold" onclick="setQuickCash(100000)">100.000</button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-emerald-200">
                        <span style="font-size:0.85rem; font-weight:700; color:#166534;">Kembalian:</span>
                        <strong style="font-size:1.1rem; color:#15803d;" id="cashChangeDisplay">Rp 0</strong>
                    </div>
                </div>

                <!-- Transfer Bank -->
                <div class="pay-option-card" id="optTransfer" onclick="selectPaymentMethod('transfer')">
                    <input type="radio" name="pay_method" value="transfer">
                    <svg width="20" height="20" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><rect x="9" y="11" width="12" height="8" rx="2"/></svg>
                    <div>
                        <strong style="font-size:0.9rem; color:#0f172a;">Transfer Bank Direct</strong>
                        <div style="font-size:0.75rem; color:#64748b;">Transfer ke rekening bank toko</div>
                    </div>
                </div>

                <!-- QRIS (Midtrans) -->
                <div class="pay-option-card" id="optQris" onclick="selectPaymentMethod('qris')">
                    <input type="radio" name="pay_method" value="qris">
                    <svg width="20" height="20" fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <div>
                        <strong style="font-size:0.9rem; color:#0f172a;">QRIS Dynamic (Midtrans)</strong>
                        <div style="font-size:0.75rem; color:#64748b;">Scan QRIS otomatis terverifikasi via Midtrans</div>
                    </div>
                </div>

                <!-- E-Receipt Email (Optional) -->
                <div class="mt-3">
                    <label class="form-label font-weight-bold mb-1" style="font-size: 0.78rem; color: #475569;">Email Pembeli (Kirim E-Receipt Struk)</label>
                    <input type="email" id="posCustomerEmail" class="pos-input" placeholder="contoh@gmail.com (Opsional)">
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light font-weight-bold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-pos-primary" id="btnSubmitOrder" onclick="processOrderCheckout()" style="width:auto; padding:10px 24px;">
                    Konfirmasi & Bayar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 2: Receipt Preview & Thermal Print -->
<div class="modal fade" id="posReceiptModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden;">
            <div class="modal-header border-0 pb-0 no-print">
                <h5 class="modal-title font-weight-bold" style="color:#0f172a;">Struk Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetPOS()"></button>
            </div>

            <div class="modal-body p-4" id="posReceiptModalContent">
                <!-- Printable Receipt Template (58mm / 80mm friendly) -->
                <div style="font-family: 'Courier New', Courier, monospace; font-size: 12px; color: #000000; line-height: 1.3;" id="printablePosReceiptArea">
                    <div class="text-center mb-3">
                        @if($siteLogoUrl)
                            <img src="{{ $siteLogoUrl }}" alt="buyle.id" style="height: 28px; width: auto; margin-bottom: 4px;" class="no-print">
                        @endif
                        <div style="font-weight: 900; font-size: 16px; text-transform: uppercase;">{{ $storeName }}</div>
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

                    <!-- Receipt Items -->
                    <table style="width: 100%; font-size: 11px; margin-bottom: 8px;" id="recItemsTable">
                        <thead>
                            <tr style="border-bottom: 1px solid #000;">
                                <th class="text-start">Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Item Rows Injected via JS -->
                        </tbody>
                    </table>

                    <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>

                    <!-- Totals Breakdown -->
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

                <!-- E-Receipt Email Input Section (no-print) -->
                <div class="no-print mt-4 pt-3 border-top">
                    <label class="form-label font-weight-bold mb-1" style="font-size:0.78rem;">Kirim E-Receipt ke Email</label>
                    <div class="input-group">
                        <input type="email" id="recSendEmailInput" class="pos-input" placeholder="email@pembeli.com">
                        <button class="btn btn-success font-weight-bold px-3" onclick="sendEReceiptEmail()">Kirim</button>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0 no-print">
                <button type="button" class="btn btn-outline-secondary font-weight-bold" onclick="resetPOS()" data-bs-dismiss="modal">
                    Transaksi Baru
                </button>
                <button type="button" class="btn btn-primary font-weight-bold d-flex align-items-center gap-1" onclick="window.print()">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Print Struk Thermal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 3: Today's Transactions History -->
<div class="modal fade" id="posHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden;">
            <div class="modal-header" style="background:#0f172a; color:#ffffff;">
                <h5 class="modal-title font-weight-bold" style="color:#ffffff;">Riwayat Transaksi POS Hari Ini</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size:0.85rem;">
                        <thead class="bg-light">
                            <tr>
                                <th>No. Order / Waktu</th>
                                <th>Pelanggan</th>
                                <th>Metode Bayar</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayOrders as $tOrd)
                                @php
                                    $tAddr = is_array($tOrd->shipping_address) ? $tOrd->shipping_address : (json_decode($tOrd->shipping_address ?? '', true) ?? []);
                                    $tPayMethod = $tAddr['payment_method'] ?? ($tOrd->payment?->payment_method ?? 'cash');
                                @endphp
                                <tr>
                                    <td>
                                        <strong style="color:#0f172a;">#{{ $tOrd->order_number }}</strong>
                                        <div style="font-size:0.75rem; color:#64748b;">{{ $tOrd->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td>
                                        <div style="font-weight:700;">{{ $tAddr['name'] ?? 'Pelanggan' }}</div>
                                        @if(!empty($tOrd->notes))
                                            <div style="font-size:0.75rem; color:#64748b;">{{ $tOrd->notes }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary" style="text-transform:uppercase;">{{ $tPayMethod }}</span>
                                    </td>
                                    <td class="font-weight-bold" style="color:#1eb349;">
                                        Rp {{ number_format($tOrd->total, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Berhasil</span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-xs btn-outline-primary font-weight-bold" onclick="reprintPastReceipt({{ json_encode($tOrd) }})">
                                            Cetak Struk
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
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
</div>

<script>
    // State POS
    let cart = [];
    let discountType = 'nominal';
    let platformFeeRate = {{ $platformFeeRate }};
    let adminFeeRate = {{ $adminFeeRate }};
    let currentCompletedOrder = null;

    // Toast Notification helper
    function showToast(msg, isError = false) {
        alert(msg);
    }

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
        
        // Reset all product badges
        document.querySelectorAll('.product-qty-badge').forEach(b => {
            b.classList.add('d-none');
            b.innerText = '0';
        });

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4 text-muted" id="cartEmptyNotice" style="font-size: 0.85rem;">
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
            
            // Update badge on product card
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
                        <button type="button" class="btn text-danger ms-1 p-0" onclick="removeItem(${item.id})">
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

        // Update displays
        document.getElementById('displaySubtotal').innerText = 'Rp ' + formatRupiah(subtotal);
        document.getElementById('displaySystemFees').innerText = 'Rp ' + formatRupiah(systemFees);
        document.getElementById('displayGrandTotal').innerText = 'Rp ' + formatRupiah(grandTotal);

        // Mobile Bar Update
        const totalItemsCount = cart.reduce((sum, item) => sum + item.qty, 0);
        document.getElementById('mobileCartCount').innerText = `${totalItemsCount} Items`;
        document.getElementById('mobileCartTotal').innerText = 'Rp ' + formatRupiah(grandTotal);
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

        const grandTotalText = document.getElementById('displayGrandTotal').innerText;
        document.getElementById('modalPayTotal').innerText = grandTotalText;
        
        // Auto fill cash paid input with exact amount by default
        const grandTotal = getGrandTotalVal();
        document.getElementById('cashPaidInput').value = grandTotal;
        calculateCashChange();

        const modal = new bootstrap.Modal(document.getElementById('posPaymentModal'));
        modal.show();
    }

    function getGrandTotalVal() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        let discountVal = parseFloat(document.getElementById('posDiscountInput').value) || 0;
        let discountAmount = discountType === 'percent' ? (subtotal * Math.min(100, discountVal)) / 100 : Math.min(subtotal, discountVal);
        const serviceFee = Math.max(0, parseFloat(document.getElementById('posServiceFeeInput').value) || 0);
        const systemFees = (subtotal * (platformFeeRate + adminFeeRate)) / 100;
        return Math.max(0, subtotal - discountAmount + serviceFee + systemFees);
    }

    // Payment Option Switch
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

    // Quick Cash Nominals
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

    // Submit Order Checkout via AJAX
    function processOrderCheckout() {
        const custName = document.getElementById('posCustomerName').value.trim();
        const tableNum = document.getElementById('posTableNumber').value.trim();
        const custPhone = document.getElementById('posCustomerPhone').value.trim();
        const custEmail = document.getElementById('posCustomerEmail').value.trim();

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
                // Close payment modal
                const payModalEl = document.getElementById('posPaymentModal');
                const payModal = bootstrap.Modal.getInstance(payModalEl);
                if (payModal) payModal.hide();

                // If QRIS & Midtrans Snap Token available
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

    // Render & Open Receipt Modal
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

        // Render Items Table
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

        // Breakdown Totals
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

        const modal = new bootstrap.Modal(document.getElementById('posReceiptModal'));
        modal.show();
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
        const histModalEl = document.getElementById('posHistoryModal');
        const histModal = bootstrap.Modal.getInstance(histModalEl);
        if (histModal) histModal.hide();

        showReceiptModal(order);
    }

    // Open History Modal
    function openHistoryModal() {
        const modal = new bootstrap.Modal(document.getElementById('posHistoryModal'));
        modal.show();
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

    // Helper Utils
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
