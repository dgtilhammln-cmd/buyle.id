<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $storeName }} – Menu Self-Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <script src="{{ app(\App\Services\MidtransService::class)->isProduction() ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ app(\App\Services\MidtransService::class)->getClientKey() }}"></script>
    <style>
        :root {
            --store-accent: {{ $posAccentColor }};
            --safe-area-top: env(safe-area-inset-top, 0px);
            --safe-area-bottom: env(safe-area-inset-bottom, 0px);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            justify-content: center;
        }

        .store-app-canvas {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 0 35px rgba(0, 0, 0, 0.05);
            padding-bottom: calc(90px + var(--safe-area-bottom));
        }

        /* Banner 19:6 */
        .store-banner-wrap {
            width: 100%;
            aspect-ratio: 19 / 6;
            background: var(--store-accent);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .store-banner-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Header Info Card */
        .store-info-box {
            padding: 1rem 1rem 0.75rem 1rem;
            position: relative;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
        }

        .store-brand-row {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-top: -30px;
            margin-bottom: 0.65rem;
        }

        .store-logo-img {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            object-fit: cover;
            background: #ffffff;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            flex-shrink: 0;
        }

        .store-logo-fallback {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: var(--store-accent);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 800;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            flex-shrink: 0;
        }

        .store-name-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.25;
            padding-top: 26px;
        }

        .store-meta-pills {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.5rem;
        }

        .pill-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.25rem 0.55rem;
            border-radius: 999px;
            text-decoration: none;
        }

        .pill-open {
            background: #dcfce7;
            color: #15803d;
        }

        .pill-closed {
            background: #fee2e2;
            color: #b91c1c;
        }

        .pill-table {
            background: #f1f5f9;
            color: #334155;
            font-weight: 700;
        }

        .pill-maps {
            background: #eff6ff;
            color: #2563eb;
        }

        .store-addr-text {
            font-size: 0.75rem;
            color: #64748b;
            line-height: 1.4;
        }

        /* Category Filter Scroll */
        .category-scroll-bar {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.75rem 1rem;
            overflow-x: auto;
            white-space: nowrap;
            background: #ffffff;
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 1px solid #f1f5f9;
            scrollbar-width: none;
        }

        .category-scroll-bar::-webkit-scrollbar {
            display: none;
        }

        .cat-pill-btn {
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease;
            font-family: inherit;
        }

        .cat-pill-btn.active {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
        }

        /* Products List */
        .products-grid {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .product-card-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.75rem;
            border-radius: 14px;
            background: #ffffff;
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
            transition: transform 0.15s ease;
        }

        .product-info-col {
            flex: 1;
            min-width: 0;
        }

        .prod-cat-sub {
            font-size: 0.68rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 2px;
        }

        .prod-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .prod-price {
            font-size: 0.85rem;
            font-weight: 800;
            color: #16a34a;
        }

        .product-img-wrap {
            width: 72px;
            height: 72px;
            border-radius: 12px;
            overflow: hidden;
            background: #f8fafc;
            position: relative;
            flex-shrink: 0;
        }

        .product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-add-cart {
            margin-top: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.15s;
        }

        .btn-add-cart:active {
            transform: scale(0.96);
        }

        /* Bottom Floating Bar */
        .bottom-cart-bar {
            position: fixed;
            bottom: calc(12px + var(--safe-area-bottom));
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 24px);
            max-width: 456px;
            background: #0f172a;
            color: #ffffff;
            border-radius: 999px;
            padding: 0.65rem 1rem;
            display: none;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            z-index: 100;
            cursor: pointer;
        }

        .bottom-cart-left {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .cart-count-pill {
            background: #16a34a;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 800;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-total-text {
            font-size: 0.88rem;
            font-weight: 800;
        }

        .cart-action-text {
            font-size: 0.78rem;
            font-weight: 700;
            color: #86efac;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Checkout Drawer Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 999;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }

        .modal-drawer {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 24px 24px 0 0;
            padding: 1.25rem 1.25rem calc(20px + var(--safe-area-bottom)) 1.25rem;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.25s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        .modal-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .modal-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
        }

        .btn-close-modal {
            background: #f1f5f9;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
            color: #475569;
        }

        .drawer-sec-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.45rem;
        }

        .cart-item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.55rem 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .qty-stepper {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 2px 8px;
        }

        .qty-btn {
            background: none;
            border: none;
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            cursor: pointer;
            width: 20px;
            text-align: center;
        }

        /* Payment Selector Cards */
        .pay-option-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 0.85rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            cursor: pointer;
            transition: all 0.15s;
        }

        .pay-option-card.selected {
            border-color: #16a34a;
            background: #f0fdf4;
        }

        .btn-confirm-order {
            width: 100%;
            padding: 0.85rem;
            border-radius: 999px;
            background: #16a34a;
            color: #ffffff;
            font-size: 0.92rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(22, 163, 74, 0.3);
            margin-top: 1rem;
            font-family: inherit;
        }

        .form-control-input {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 0.825rem;
            font-family: inherit;
            outline: none;
            margin-bottom: 0.5rem;
        }

        .form-control-input:focus {
            border-color: #16a34a;
        }
    </style>
</head>
<body>
    <div class="store-app-canvas">
        {{-- Banner 19:6 --}}
        <div class="store-banner-wrap">
            @if($posBannerUrl)
                <img src="{{ $posBannerUrl }}" alt="Banner Toko" class="store-banner-img">
            @endif
        </div>

        {{-- Store Info Card --}}
        <div class="store-info-box">
            <div class="store-brand-row">
                @if($posLogoUrl)
                    <img src="{{ $posLogoUrl }}" alt="Logo" class="store-logo-img">
                @else
                    <div class="store-logo-fallback">{{ strtoupper(substr($storeName, 0, 1)) }}</div>
                @endif
                <div class="store-info-col">
                    <h1 class="store-name-title">{{ $storeName }}</h1>
                </div>
            </div>

            <div class="store-meta-pills">
                <span class="pill-badge {{ $isOpen ? 'pill-open' : 'pill-closed' }}">
                    <span style="width:6px;height:6px;border-radius:50%;background:currentColor;display:inline-block;"></span>
                    {{ $isOpen ? 'Buka' : 'Tutup' }} ({{ $storeOpen }} - {{ $storeClose }})
                </span>

                @if($selectedTable)
                    <span class="pill-badge pill-table">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                        {{ $selectedTable }}
                    </span>
                @endif

                @if($posMapsLink)
                    <a href="{{ $posMapsLink }}" target="_blank" class="pill-badge pill-maps">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Lokasi Maps
                    </a>
                @endif
            </div>

            @if($posStoreAddress)
                <p class="store-addr-text">{{ $posStoreAddress }}</p>
            @endif
        </div>

        {{-- Category Bar --}}
        <div class="category-scroll-bar">
            <button type="button" class="cat-pill-btn active" onclick="filterCategory('all', this)">Semua Menu</button>
            @foreach($categories as $cat)
                <button type="button" class="cat-pill-btn" onclick="filterCategory('{{ $cat->id }}', this)">{{ $cat->name }}</button>
            @endforeach
        </div>

        {{-- Products List --}}
        <div class="products-grid">
            @forelse($products as $prod)
                <div class="product-card-row" data-category="{{ $prod->category_id ?? 'all' }}">
                    <div class="product-info-col">
                        <div class="prod-cat-sub">{{ $prod->category->name ?? 'Menu' }}</div>
                        <div class="prod-title">{{ $prod->name }}</div>
                        <div class="prod-price">Rp {{ number_format($prod->sale_price ?: $prod->price, 0, ',', '.') }}</div>
                        <button type="button" class="btn-add-cart" onclick="addToCart({{ $prod->id }}, '{{ addslashes($prod->name) }}', {{ $prod->sale_price ?: $prod->price }})">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            <span>Tambah</span>
                        </button>
                    </div>
                    <div class="product-img-wrap">
                        @if($prod->thumbnail && str_starts_with($prod->thumbnail, 'http'))
                            <img src="{{ $prod->thumbnail }}" alt="{{ $prod->name }}" loading="lazy">
                        @elseif($prod->thumbnail)
                            <img src="{{ asset('storage/' . $prod->thumbnail) }}" alt="{{ $prod->name }}" loading="lazy">
                        @else
                            <div style="width:100%;height:100%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#cbd5e1;">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:3rem 1rem; color:#94a3b8; font-size:0.85rem;">
                    Belum ada produk aktif yang tersedia.
                </div>
            @endforelse
        </div>

        {{-- Floating Bottom Cart Bar --}}
        <div id="bottomCartBar" class="bottom-cart-bar" onclick="openCheckoutDrawer()">
            <div class="bottom-cart-left">
                <div class="cart-count-pill" id="cartCountBadge">0</div>
                <div class="cart-total-text" id="cartTotalFmt">Rp 0</div>
            </div>
            <div class="cart-action-text">
                <span>Lihat Pesanan</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
        </div>

        {{-- Checkout Drawer Modal --}}
        <div id="checkoutModalOverlay" class="modal-overlay">
            <div class="modal-drawer">
                <div class="modal-header-row">
                    <h2 class="modal-title">Rincian Pesanan</h2>
                    <button type="button" class="btn-close-modal" onclick="closeCheckoutDrawer()">&times;</button>
                </div>

                {{-- Table & Customer Details --}}
                <div style="margin-bottom:1rem;">
                    <label style="font-size:0.75rem; font-weight:700; color:#334155; display:block; margin-bottom:4px;">Nomor / Nama Meja</label>
                    @if(!empty($posTables) && count($posTables) > 0)
                        <select id="checkoutTableSelect" class="form-control-input">
                            <option value="">Pilih Nomor Meja...</option>
                            @foreach($posTables as $t)
                                @php $tName = is_array($t) ? ($t['name'] ?? $t['table_no'] ?? 'Meja') : $t; @endphp
                                <option value="{{ $tName }}" {{ strtolower($selectedTable) === strtolower($tName) ? 'selected' : '' }}>
                                    {{ $tName }}
                                </option>
                            @endforeach
                            @if($selectedTable && !in_array($selectedTable, array_map(fn($t) => is_array($t) ? ($t['name'] ?? '') : $t, $posTables)))
                                <option value="{{ $selectedTable }}" selected>{{ $selectedTable }}</option>
                            @endif
                        </select>
                    @else
                        <input type="text" id="checkoutTableInput" class="form-control-input" value="{{ $selectedTable }}" placeholder="Masukkan Nomor / Nama Meja (e.g. Meja 01)">
                    @endif

                    <input type="text" id="checkoutCustName" class="form-control-input" placeholder="Nama Pemesan (Opsional)">
                    <input type="tel" id="checkoutCustPhone" class="form-control-input" placeholder="No. WhatsApp / HP (Opsional)">
                </div>

                {{-- Cart Items List --}}
                <div class="drawer-sec-title">Daftar Menu</div>
                <div id="drawerCartItemsWrap">
                    {{-- Render via JS --}}
                </div>

                {{-- Price Breakdown --}}
                <div style="margin-top:1rem; padding-top:0.75rem; border-top:1px solid #f1f5f9; font-size:0.8rem;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:4px; color:#64748b;">
                        <span>Subtotal</span>
                        <span id="drawerSubtotalFmt">Rp 0</span>
                    </div>
                    <div id="drawerDiscountRow" style="display:none; justify-content:space-between; margin-bottom:4px; color:#16a34a;">
                        <span id="drawerDiscountLabel">Diskon</span>
                        <span id="drawerDiscountFmt">-Rp 0</span>
                    </div>
                    <div id="drawerFeesWrap"></div>
                    <div style="display:flex; justify-content:space-between; margin-top:6px; padding-top:6px; border-top:1.5px solid #f1f5f9; font-size:0.95rem; font-weight:800; color:#0f172a;">
                        <span>Total Pembayaran</span>
                        <span id="drawerTotalFmt">Rp 0</span>
                    </div>
                </div>

                {{-- Payment Method Selection --}}
                <div style="margin-top:1rem;">
                    <div class="drawer-sec-title">Metode Pembayaran</div>

                    {{-- Option 1: Bayar di Kasir --}}
                    <div class="pay-option-card selected" id="payOptCash" onclick="selectPaymentMethod('cash')">
                        <input type="radio" name="payMethod" value="cash" checked style="margin-top:3px;">
                        <div>
                            <div style="font-size:0.82rem; font-weight:700; color:#0f172a;">Bayar di Kasir (Cash)</div>
                            <div style="font-size:0.72rem; color:#64748b;">Pesan sekarang dari meja, bayar tunai langsung di kasir toko.</div>
                        </div>
                    </div>

                    {{-- Option 2: Pembayaran Digital --}}
                    <div class="pay-option-card" id="payOptMidtrans" onclick="selectPaymentMethod('midtrans')">
                        <input type="radio" name="payMethod" value="midtrans" style="margin-top:3px;">
                        <div>
                            <div style="font-size:0.82rem; font-weight:700; color:#0f172a;">Pembayaran Digital (Snap Midtrans)</div>
                            <div style="font-size:0.72rem; color:#64748b;">QRIS, GoPay, OVO, ShopeePay, Virtual Account online instan.</div>
                        </div>
                    </div>
                </div>

                <button type="button" id="btnSubmitOrder" class="btn-confirm-order" onclick="submitStorefrontOrder()">
                    Konfirmasi &amp; Buat Pesanan
                </button>
            </div>
        </div>
    </div>

    <script>
        const storeSlug = '{{ $slug }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const posFeesData = {!! json_encode($posFees ?? []) !!};
        const posDiscountsData = {!! json_encode($posDiscounts ?? []) !!};

        let cartItems = {};
        let selectedPayment = 'cash';

        function filterCategory(catId, btn) {
            document.querySelectorAll('.cat-pill-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');

            document.querySelectorAll('.product-card-row').forEach(card => {
                if (catId === 'all' || card.getAttribute('data-category') === catId) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function addToCart(id, name, price) {
            if (!cartItems[id]) {
                cartItems[id] = { id, name, price, qty: 0 };
            }
            cartItems[id].qty++;
            updateCartUI();
        }

        function changeQty(id, delta) {
            if (!cartItems[id]) return;
            cartItems[id].qty += delta;
            if (cartItems[id].qty <= 0) {
                delete cartItems[id];
            }
            updateCartUI();
            renderDrawerItems();
        }

        function calculateCart() {
            let subtotal = 0;
            let totalQty = 0;
            Object.values(cartItems).forEach(it => {
                subtotal += it.price * it.qty;
                totalQty += it.qty;
            });

            // Discount
            let discountAmt = 0;
            let discLabel = 'Diskon';
            (posDiscountsData || []).forEach(d => {
                if (d.is_active === false) return;
                const minQty = d.min_qty || 1;
                if (totalQty >= minQty) {
                    const amt = (d.type === 'percent') ? (subtotal * (parseFloat(d.value) || 0) / 100) : Math.min(subtotal, (parseFloat(d.value) || 0));
                    if (amt > discountAmt) {
                        discountAmt = amt;
                        discLabel = d.name || 'Diskon';
                    }
                }
            });

            // Fees
            let feesAmt = 0;
            const feeRows = [];
            (posFeesData || []).forEach(f => {
                if (f.is_active === false) return;
                const val = parseFloat(f.value) || 0;
                const fAmt = (f.type === 'percent') ? (subtotal * val / 100) : val;
                feesAmt += fAmt;
                feeRows.push({ name: f.name, amount: fAmt });
            });

            const total = Math.max(0, subtotal - discountAmt + feesAmt);

            return { subtotal, totalQty, discountAmt, discLabel, feesAmt, feeRows, total };
        }

        function updateCartUI() {
            const { subtotal, totalQty, total } = calculateCart();
            const bar = document.getElementById('bottomCartBar');
            if (totalQty > 0) {
                bar.style.display = 'flex';
                document.getElementById('cartCountBadge').innerText = totalQty;
                document.getElementById('cartTotalFmt').innerText = 'Rp ' + total.toLocaleString('id-ID');
            } else {
                bar.style.display = 'none';
                closeCheckoutDrawer();
            }
        }

        function openCheckoutDrawer() {
            renderDrawerItems();
            document.getElementById('checkoutModalOverlay').style.display = 'flex';
        }

        function closeCheckoutDrawer() {
            document.getElementById('checkoutModalOverlay').style.display = 'none';
        }

        function selectPaymentMethod(m) {
            selectedPayment = m;
            document.getElementById('payOptCash').classList.toggle('selected', m === 'cash');
            document.getElementById('payOptMidtrans').classList.toggle('selected', m === 'midtrans');
            document.querySelector(`input[name="payMethod"][value="${m}"]`).checked = true;
        }

        function renderDrawerItems() {
            const { subtotal, discountAmt, discLabel, feeRows, total } = calculateCart();
            const c = document.getElementById('drawerCartItemsWrap');
            c.innerHTML = Object.values(cartItems).map(it => `
                <div class="cart-item-row">
                    <div style="flex:1; min-width:0; padding-right:0.5rem;">
                        <div style="font-weight:700; font-size:0.82rem; color:#0f172a;">${it.name}</div>
                        <div style="font-size:0.72rem; color:#64748b;">Rp ${it.price.toLocaleString('id-ID')} &times; ${it.qty}</div>
                    </div>
                    <div class="qty-stepper">
                        <button type="button" class="qty-btn" onclick="changeQty(${it.id}, -1)">&minus;</button>
                        <span style="font-size:0.8rem; font-weight:700; min-width:16px; text-align:center;">${it.qty}</span>
                        <button type="button" class="qty-btn" onclick="changeQty(${it.id}, 1)">&plus;</button>
                    </div>
                </div>
            `).join('');

            document.getElementById('drawerSubtotalFmt').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');

            const discRow = document.getElementById('drawerDiscountRow');
            if (discountAmt > 0) {
                discRow.style.display = 'flex';
                document.getElementById('drawerDiscountLabel').innerText = discLabel;
                document.getElementById('drawerDiscountFmt').innerText = '-Rp ' + discountAmt.toLocaleString('id-ID');
            } else {
                discRow.style.display = 'none';
            }

            const feesWrap = document.getElementById('drawerFeesWrap');
            feesWrap.innerHTML = feeRows.map(f => `
                <div style="display:flex; justify-content:space-between; margin-bottom:4px; color:#64748b;">
                    <span>${f.name}</span>
                    <span>Rp ${f.amount.toLocaleString('id-ID')}</span>
                </div>
            `).join('');

            document.getElementById('drawerTotalFmt').innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        function submitStorefrontOrder() {
            const items = Object.values(cartItems);
            if (items.length === 0) return alert('Keranjang belanja kosong.');

            const tableSelect = document.getElementById('checkoutTableSelect');
            const tableInput = document.getElementById('checkoutTableInput');
            const tableNo = (tableSelect ? tableSelect.value : (tableInput ? tableInput.value : '')).trim();

            if (!tableNo) {
                return alert('Silakan pilih atau masukkan nomor meja Anda.');
            }

            const custName = document.getElementById('checkoutCustName')?.value.trim() || '';
            const custPhone = document.getElementById('checkoutCustPhone')?.value.trim() || '';

            const btn = document.getElementById('btnSubmitOrder');
            btn.disabled = true;
            btn.innerText = 'Memproses Pesanan...';

            fetch('{{ route("storefront.order", ["slug" => $slug]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    items: items.map(it => ({ id: it.id, qty: it.qty })),
                    table_number: tableNo,
                    customer_name: custName,
                    customer_phone: custPhone,
                    payment_method: selectedPayment
                })
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.innerText = 'Konfirmasi & Buat Pesanan';

                if (!data.success) {
                    alert(data.message || 'Gagal membuat pesanan.');
                    return;
                }

                // If digital payment via Snap Midtrans
                if (selectedPayment === 'midtrans' && data.snap_token && typeof window.snap !== 'undefined') {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            window.location.href = data.status_url;
                        },
                        onPending: function (result) {
                            window.location.href = data.status_url;
                        },
                        onError: function (result) {
                            alert('Pembayaran gagal atau dibatalkan.');
                            window.location.href = data.status_url;
                        },
                        onClose: function () {
                            window.location.href = data.status_url;
                        }
                    });
                } else {
                    // Cash payment at cashier
                    window.location.href = data.status_url;
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerText = 'Konfirmasi & Buat Pesanan';
                alert('Terjadi kesalahan jaringan.');
            });
        }
    </script>
</body>
</html>
