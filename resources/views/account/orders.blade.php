@extends('account.layout')
@section('title', 'Riwayat Pesanan – Akun')

@section('acc_page')
    <style>
        /* ─── ORDER FILTER TABS (Super App Compact Capsules - Persis Tab di Bio) ─── */
        .order-tabs {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            padding: 0.2rem 0.1rem 0.5rem;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            border-bottom: none;
        }

        .order-tabs::-webkit-scrollbar {
            display: none;
        }

        .ord-tab {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1.05rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            border-radius: 999px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
            user-select: none;
        }

        .ord-tab:hover {
            color: #0f172a;
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .ord-tab.ord-active {
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%) !important;
            color: #ffffff !important;
            font-weight: 700;
            border-color: transparent !important;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.3);
            transform: translateY(-1px);
        }

        .ord-tab svg {
            flex-shrink: 0;
            transition: stroke 0.2s ease, opacity 0.2s ease;
            stroke: currentColor;
            opacity: 0.75;
        }

        .ord-tab.ord-active svg {
            stroke: #ffffff !important;
            opacity: 1;
        }

        .order-search-box {
            background: #F1F5F9;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .order-search-box svg {
            color: #94A3B8;
            flex-shrink: 0;
        }

        .order-search-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 0.9rem;
            color: #334155;
        }

        .order-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            margin-bottom: 1.25rem;
            overflow: hidden;
            transition: box-shadow 0.2s;
            cursor: pointer;
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .order-card:hover {
            box-shadow: 0 8px 24px rgba(30, 179, 73, 0.08);
            border-color: #bbf7d0;
        }

        .order-card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #F1F5F9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .order-card-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.85rem;
            color: #64748B;
        }

        .order-card-id {
            font-weight: 700;
            color: #0F172A;
        }

        .order-card-status {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            white-space: nowrap;
            flex-shrink: 0;
            display: inline-block;
        }

        @media (max-width: 640px) {
            .order-card-status {
                font-size: 0.7rem;
                padding: 0.25rem 0.55rem;
            }
        }

        .order-card-status.pending {
            background: #DCFCE7;
            color: #15803D;
        }

        .order-card-status.confirmed {
            background: #DCFCE7;
            color: #15803D;
        }

        .order-card-status.processing {
            background: #DCFCE7;
            color: #15803D;
        }

        .order-card-status.shipped {
            background: #DCFCE7;
            color: #15803D;
        }

        .order-card-status.delivered {
            background: #DCFCE7;
            color: #15803D;
        }

        .order-card-status.cancelled,
        .order-card-status.refunded {
            background: #FEE2E2;
            color: #DC2626;
        }

        .order-card-body {
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.25rem;
            flex-wrap: wrap;
        }

        .order-item-main {
            display: flex;
            gap: 1rem;
            flex: 1;
            min-width: 260px;
        }

        .order-item-img {
            width: 72px;
            height: 72px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #F1F5F9;
            flex-shrink: 0;
        }

        .order-item-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .order-item-title {
            font-weight: 600;
            color: #0F172A;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .order-item-variant {
            font-size: 0.8rem;
            color: #64748B;
            margin-bottom: 0.4rem;
        }

        .order-item-more {
            font-size: 0.75rem;
            font-weight: 600;
            color: #1eb349;
            background: #dcfce7;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            display: inline-block;
            align-self: flex-start;
        }

        .order-card-price {
            text-align: right;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 120px;
        }

        .order-price-label {
            font-size: 0.75rem;
            color: #64748B;
            margin-bottom: 0.25rem;
        }

        .order-price-total {
            font-weight: 800;
            color: #1eb349;
            font-size: 1.1rem;
        }

        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
            color: #94A3B8;
        }

        .empty-state>svg {
            width: 56px;
            height: 56px;
            margin-bottom: 1rem;
            opacity: 0.4;
        }

        .btn-empty-shop {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.25rem;
            padding: 0.6rem 1.35rem;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.25);
        }

        .btn-empty-shop:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(30, 179, 73, 0.35);
        }

        .btn-empty-shop svg {
            width: 15px !important;
            height: 15px !important;
            margin: 0 !important;
            opacity: 1 !important;
        }
    </style>

    <div class="acc-card" style="background:transparent; border:none; box-shadow:none; padding:0;">
        <!-- TABS (Super App Capsule Pills) -->
        <div class="order-tabs" id="statusTabs">
            <div class="ord-tab ord-active" data-filter="all">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" />
                    <rect x="14" y="3" width="7" height="7" rx="1.5" />
                    <rect x="14" y="14" width="7" height="7" rx="1.5" />
                    <rect x="3" y="14" width="7" height="7" rx="1.5" />
                </svg>
                Semua
            </div>
            <div class="ord-tab" data-filter="pending">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                Belum Bayar
            </div>
            <div class="ord-tab" data-filter="processing">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path
                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                    <line x1="12" y1="22.08" x2="12" y2="12" />
                </svg>
                Sedang Dikemas
            </div>
            <div class="ord-tab" data-filter="shipped">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="1" y="3" width="15" height="13" rx="2" />
                    <polygon points="16 8 20 8 23 11 23 16 16 16 8" />
                    <circle cx="5.5" cy="18.5" r="2.5" />
                    <circle cx="18.5" cy="18.5" r="2.5" />
                </svg>
                Dikirim
            </div>
            <div class="ord-tab" data-filter="delivered">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                Selesai
            </div>
            <div class="ord-tab" data-filter="cancelled">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" y1="9" x2="9" y2="15" />
                    <line x1="9" y1="9" x2="15" y2="15" />
                </svg>
                Dibatalkan
            </div>
        </div>

        <!-- SEARCH -->
        <div class="order-search-box">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8" />
                <path d="M21 21l-4.35-4.35" />
            </svg>
            <input type="text" id="searchInput" placeholder="Kamu bisa cari berdasarkan No. Pesanan atau Nama Produk"
                autocomplete="off">
        </div>

        <!-- ORDER LIST -->
        <div id="orderList">
            @if($orders->count())
                @foreach($orders as $order)
                    @php
                        $statusEnum = $order->status->value;
                        // Group confirmed and delivered into 'delivered' (Selesai) for the tabs
                        $tabStatus = in_array($statusEnum, ['confirmed', 'delivered']) ? 'delivered' : $statusEnum;

                        $firstItem = $order->items->first();
                        $productName = $firstItem && $firstItem->product ? $firstItem->product->name : 'Produk Dihapus';
                        $productImg = $firstItem && $firstItem->product
                            ? $firstItem->product->image_url
                            : 'https://placehold.co/150x150/f1f5f9/94a3b8?text=No+Image';
                        $qty = $firstItem ? $firstItem->quantity : 0;
                        $otherCount = $order->items->count() - 1;
                        $rawOrderNo = $order->order_number ?: ('BYL-' . $order->id);
                        $orderNo = str_starts_with($rawOrderNo, '#') ? $rawOrderNo : '#' . $rawOrderNo;
                        $searchString = strtolower($orderNo . ' ' . $productName);
                    @endphp
                    <a href="{{ route('account.orders.show', $order->id) }}" class="order-card" data-status="{{ $tabStatus }}"
                        data-search="{{ $searchString }}">
                        <div class="order-card-header">
                            <div class="order-card-meta">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                    <line x1="3" y1="6" x2="21" y2="6" />
                                    <path d="M16 10a4 4 0 01-8 0" />
                                </svg>
                                <span>{{ $order->created_at->format('d M Y') }}</span>
                                <span style="width:4px;height:4px;border-radius:50%;background:#CBD5E1;"></span>
                                <span class="order-card-id">{{ $orderNo }}</span>
                            </div>
                            <div class="order-card-status {{ $statusEnum }}">
                                {{ $order->status->label() }}
                            </div>
                        </div>
                        <div class="order-card-body">
                            <div class="order-item-main">
                                <img src="{{ $productImg }}" alt="Product" class="order-item-img"
                                    onerror="this.src='https://placehold.co/150x150/f1f5f9/94a3b8?text=No+Image'">
                                <div class="order-item-info">
                                    <div class="order-item-title">{{ $productName }}</div>
                                    @if($firstItem && ($firstItem->variant_name || ($firstItem->variantValue)))
                                        <div style="font-size:0.75rem; color:#1eb349; font-weight:600; margin-bottom:0.1rem;">
                                            🏷
                                            {{ $firstItem->variant_name ?: ($firstItem->variantValue ? ($firstItem->variantValue->variantOption->name ?? 'Varian') . ': ' . $firstItem->variantValue->value : '') }}
                                        </div>
                                    @endif
                                    <div class="order-item-variant">x {{ $qty }}</div>
                                    @if($otherCount > 0)
                                        <div class="order-item-more">+ {{ $otherCount }} produk lainnya</div>
                                    @endif
                                </div>
                            </div>
                            <div class="order-card-price">
                                <div class="order-price-label">Total Pesanan</div>
                                <div class="order-price-total">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach

                <div id="noResults" class="empty-state" style="display:none;">
                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="M21 21l-4.35-4.35" />
                    </svg>
                    <h3 style="color:#334155; margin:0 0 0.5rem; font-size:1.1rem;">Pesanan tidak ditemukan</h3>
                    <p style="margin:0; font-size:0.9rem;">Coba cari dengan kata kunci lain atau cek tab status yang berbeda.
                    </p>
                </div>
            @else
                <div class="empty-state">
                    <svg width="56" height="56" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    <h3 style="color:#0F172A; margin:0 0 0.5rem; font-size:1.15rem; font-weight:700;">Belum ada pesanan</h3>
                    <p style="margin:0; font-size:0.875rem; color:#64748B;">Temukan berbagai produk digital dan kreatif terbaik
                        untuk Anda.</p>
                    <a href="{{ route('products') }}" class="btn-empty-shop">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 01-8 0"></path>
                        </svg>
                        Mulai Belanja
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.ord-tab');
            const searchInput = document.getElementById('searchInput');
            const cards = document.querySelectorAll('.order-card');
            const noResults = document.getElementById('noResults');

            let currentFilter = 'all';

            function filterOrders() {
                const query = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                cards.forEach(card => {
                    const status = card.getAttribute('data-status');
                    const searchData = card.getAttribute('data-search');

                    const matchStatus = (currentFilter === 'all' || status === currentFilter);
                    const matchSearch = (query === '' || searchData.includes(query));

                    if (matchStatus && matchSearch) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (cards.length > 0) {
                    noResults.style.display = visibleCount === 0 ? 'block' : 'none';
                }
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    tabs.forEach(t => t.classList.remove('ord-active'));
                    this.classList.add('ord-active');
                    currentFilter = this.getAttribute('data-filter');
                    filterOrders();
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', filterOrders);
            }
        });
    </script>
@endsection