@extends('account.layout')
@section('title', 'Riwayat Pesanan – Akun')

@section('acc_page')
    <style>
        /* ─── ORDER FILTER TABS (Super App Compact Capsules - Persis Tab di Bio) ─── */
        .order-tabs {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 1rem;
            padding: 0.15rem 0.1rem 0.4rem;
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
            gap: 0.35rem;
            padding: 0.38rem 0.75rem;
            font-size: 0.74rem;
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
            width: 12px;
            height: 12px;
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
            padding: 0.55rem 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 1rem;
        }

        .order-search-box svg {
            color: #94A3B8;
            flex-shrink: 0;
            width: 16px;
            height: 16px;
        }

        .order-search-box input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 0.8rem;
            color: #334155;
        }

        .order-card {
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            border-radius: 16px;
            margin-bottom: 0.85rem;
            overflow: hidden;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            display: block;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        }

        .order-card:hover {
            box-shadow: 0 6px 20px rgba(30, 179, 73, 0.09);
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .order-card:active {
            transform: scale(0.99);
        }

        .order-card-header {
            padding: 0.65rem 0.95rem;
            border-bottom: 1px solid #F1F5F9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            background: #FAFAFA;
        }

        .order-card-meta {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.73rem;
            color: #64748B;
            min-width: 0;
        }

        .order-meta-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #1eb349;
            flex-shrink: 0;
        }

        .order-meta-date {
            font-weight: 500;
            color: #64748B;
            white-space: nowrap;
        }

        .order-meta-dot {
            color: #cbd5e1;
        }

        .order-card-id {
            font-family: monospace;
            font-weight: 700;
            color: #0F172A;
            font-size: 0.72rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .order-card-status {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            white-space: nowrap;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        .order-card-status.pending {
            background: #FEF9C3;
            color: #854D0E;
            border: 1px solid #FEF08A;
        }

        .order-card-status.confirmed,
        .order-card-status.processing,
        .order-card-status.shipped,
        .order-card-status.delivered {
            background: #DCFCE7;
            color: #15803D;
            border: 1px solid #BBF7D0;
        }

        .order-card-status.cancelled,
        .order-card-status.refunded {
            background: #FEE2E2;
            color: #DC2626;
            border: 1px solid #FECACA;
        }

        .order-card-body {
            padding: 0.75rem 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .order-item-img-wrap {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            overflow: hidden;
            border: 1.5px solid #F1F5F9;
            background: #F8FAFC;
            flex-shrink: 0;
        }

        .order-item-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .order-item-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .order-item-title {
            font-weight: 700;
            color: #0F172A;
            font-size: 0.83rem;
            margin: 0;
            line-height: 1.35;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .order-item-meta-row {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 1px;
        }

        .order-item-qty {
            font-size: 0.72rem;
            color: #64748B;
            font-weight: 500;
        }

        .order-item-variant-badge {
            font-size: 0.68rem;
            color: #0369a1;
            background: #e0f2fe;
            border: 1px solid #bae6fd;
            padding: 0 5px;
            border-radius: 4px;
            font-weight: 600;
            white-space: nowrap;
        }

        .order-item-more {
            font-size: 0.68rem;
            font-weight: 700;
            color: #16a34a;
            background: #dcfce7;
            padding: 0 5px;
            border-radius: 4px;
            white-space: nowrap;
        }

        .order-card-footer {
            padding: 0.55rem 0.95rem;
            border-top: 1px solid #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            background: #ffffff;
        }

        .order-card-price-block {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }

        .order-price-label {
            font-size: 0.72rem;
            color: #94A3B8;
            font-weight: 500;
        }

        .order-price-total {
            font-weight: 800;
            color: #0F172A;
            font-size: 0.92rem;
            line-height: 1;
        }

        .btn-order-view {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            color: #0F172A;
            font-size: 0.72rem;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.15s ease;
        }

        .order-card:hover .btn-order-view {
            background: #1eb349;
            color: #ffffff;
            border-color: #1eb349;
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
            <input type="text" id="searchInput" placeholder="Cari No. Pesanan atau Nama Produk"
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
                        {{-- Card Header: Meta & Status Badge --}}
                        <div class="order-card-header">
                            <div class="order-card-meta">
                                <span class="order-meta-icon">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                        <line x1="3" y1="6" x2="21" y2="6" />
                                        <path d="M16 10a4 4 0 01-8 0" />
                                    </svg>
                                </span>
                                <span class="order-meta-date">{{ $order->created_at->format('d M Y') }}</span>
                                <span class="order-meta-dot">•</span>
                                <span class="order-card-id">{{ $orderNo }}</span>
                            </div>
                            <div class="order-card-status {{ $statusEnum }}">
                                @if(in_array($statusEnum, ['confirmed', 'delivered']))
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                @endif
                                {{ $order->status->label() }}
                            </div>
                        </div>

                        {{-- Card Body: Item Thumbnail & Title --}}
                        <div class="order-card-body">
                            <div class="order-item-img-wrap">
                                <img src="{{ $productImg }}" alt="Product" class="order-item-img"
                                    onerror="this.src='https://placehold.co/150x150/f1f5f9/94a3b8?text=No+Image'">
                            </div>
                            <div class="order-item-info">
                                <h4 class="order-item-title">{{ $productName }}</h4>
                                <div class="order-item-meta-row">
                                    @if($firstItem && ($firstItem->variant_name || ($firstItem->variantValue)))
                                        <span class="order-item-variant-badge">
                                            {{ $firstItem->variant_name ?: ($firstItem->variantValue ? ($firstItem->variantValue->variantOption->name ?? 'Varian') . ': ' . $firstItem->variantValue->value : '') }}
                                        </span>
                                    @endif
                                    <span class="order-item-qty">{{ $qty }} barang</span>
                                    @if($otherCount > 0)
                                        <span class="order-item-more">+{{ $otherCount }} lainnya</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer: Compact Price & Action Button --}}
                        <div class="order-card-footer">
                            <div class="order-card-price-block">
                                <span class="order-price-label">Total Pesanan:</span>
                                <span class="order-price-total">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="btn-order-view">
                                <span>Lihat Detail</span>
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
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