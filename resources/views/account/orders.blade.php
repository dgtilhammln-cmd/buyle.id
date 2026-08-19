@extends('account.layout')
@section('title', 'Riwayat Pesanan – Akun')

@section('acc_page')
<style>
.order-tabs { display:flex; gap:1.5rem; border-bottom:1px solid #E2E8F0; margin-bottom:1.25rem; overflow-x:auto; }
.ord-tab { padding:0.75rem 0; font-weight:600; color:#64748B; cursor:pointer; border-bottom:2px solid transparent; white-space:nowrap; transition:all 0.2s; font-size:0.9rem; }
.ord-tab:hover { color:#0EA5E9; }
.ord-tab.ord-active { color:#0EA5E9; border-bottom-color:#0EA5E9; }

.order-search-box { background:#F1F5F9; border-radius:10px; padding:0.75rem 1rem; display:flex; align-items:center; gap:0.5rem; margin-bottom:1.5rem; }
.order-search-box svg { color:#94A3B8; flex-shrink:0; }
.order-search-box input { border:none; background:transparent; outline:none; width:100%; font-size:0.9rem; color:#334155; }

.order-card { background:#fff; border:1px solid #E2E8F0; border-radius:12px; margin-bottom:1.25rem; overflow:hidden; transition:box-shadow 0.2s; cursor:pointer; display:block; text-decoration:none; color:inherit; }
.order-card:hover { box-shadow:0 8px 24px rgba(14,165,233,0.08); border-color:#BAE6FD; }
.order-card-header { padding:1rem 1.25rem; border-bottom:1px solid #F1F5F9; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem; }
.order-card-meta { display:flex; align-items:center; gap:0.75rem; font-size:0.85rem; color:#64748B; }
.order-card-id { font-weight:700; color:#0F172A; }
.order-card-status { font-size:0.75rem; font-weight:700; padding:0.35rem 0.75rem; border-radius:6px; text-transform:uppercase; letter-spacing:0.02em; }
.order-card-status.pending { background:#FEF3C7; color:#D97706; }
.order-card-status.confirmed { background:#DBEAFE; color:#2563EB; }
.order-card-status.processing { background:#E0E7FF; color:#4F46E5; }
.order-card-status.shipped { background:#F3E8FF; color:#9333EA; }
.order-card-status.delivered { background:#DCFCE7; color:#059669; }
.order-card-status.cancelled, .order-card-status.refunded { background:#FEE2E2; color:#DC2626; }

.order-card-body { padding:1.25rem; display:flex; align-items:flex-start; justify-content:space-between; gap:1.25rem; flex-wrap:wrap; }
.order-item-main { display:flex; gap:1rem; flex:1; min-width:260px; }
.order-item-img { width:72px; height:72px; border-radius:8px; object-fit:cover; border:1px solid #F1F5F9; flex-shrink:0; }
.order-item-info { display:flex; flex-direction:column; justify-content:center; }
.order-item-title { font-weight:600; color:#0F172A; font-size:0.95rem; margin-bottom:0.25rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.order-item-variant { font-size:0.8rem; color:#64748B; margin-bottom:0.4rem; }
.order-item-more { font-size:0.75rem; font-weight:600; color:#0EA5E9; background:#E0F2FE; padding:0.2rem 0.6rem; border-radius:4px; display:inline-block; align-self:flex-start; }

.order-card-price { text-align:right; display:flex; flex-direction:column; justify-content:center; min-width:120px; }
.order-price-label { font-size:0.75rem; color:#64748B; margin-bottom:0.25rem; }
.order-price-total { font-weight:800; color:#0EA5E9; font-size:1.1rem; }

.empty-state { text-align:center; padding:4rem 1rem; color:#94A3B8; }
.empty-state svg { width:64px; height:64px; margin-bottom:1rem; opacity:0.5; }
</style>

<div class="acc-card" style="background:transparent; border:none; box-shadow:none; padding:0;">
    <div style="margin-bottom:1.5rem;">
        <h2 style="font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 0.25rem;">Riwayat Pesanan</h2>
        <p style="color:#64748B; font-size:0.9rem; margin:0;">Semua transaksi pembelian Anda</p>
    </div>

    <!-- TABS -->
    <div class="order-tabs" id="statusTabs">
        <div class="ord-tab ord-active" data-filter="all">Semua</div>
        <div class="ord-tab" data-filter="pending">Belum Bayar</div>
        <div class="ord-tab" data-filter="processing">Sedang Dikemas</div>
        <div class="ord-tab" data-filter="shipped">Dikirim</div>
        <div class="ord-tab" data-filter="delivered">Selesai</div>
        <div class="ord-tab" data-filter="cancelled">Dibatalkan</div>
    </div>

    <!-- SEARCH -->
    <div class="order-search-box">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        <input type="text" id="searchInput" placeholder="Kamu bisa cari berdasarkan No. Pesanan atau Nama Produk" autocomplete="off">
    </div>

    <!-- ORDER LIST -->
    <div id="orderList">
        @if($orders->count())
            @foreach($orders as $order)
                @php
                    $statusEnum = $order->status->value;
                    // Group confirmed and processing into 'processing' for the tabs
                    $tabStatus = in_array($statusEnum, ['confirmed', 'processing']) ? 'processing' : $statusEnum;
                    
                    $firstItem = $order->items->first();
                    $productName = $firstItem && $firstItem->product ? $firstItem->product->name : 'Produk Dihapus';
                    $productImg = $firstItem && $firstItem->product 
                        ? $firstItem->product->image_url 
                        : 'https://placehold.co/150x150/f1f5f9/94a3b8?text=No+Image';
                    $qty = $firstItem ? $firstItem->quantity : 0;
                    $otherCount = $order->items->count() - 1;
                    $orderNo = $order->order_number ?? $order->id;
                    $searchString = strtolower($orderNo . ' ' . $productName);
                @endphp
                <a href="{{ route('account.orders.show', $order->id) }}" class="order-card" data-status="{{ $tabStatus }}" data-search="{{ $searchString }}">
                    <div class="order-card-header">
                        <div class="order-card-meta">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                            <span>{{ $order->created_at->format('d M Y') }}</span>
                            <span style="width:4px;height:4px;border-radius:50%;background:#CBD5E1;"></span>
                            <span class="order-card-id">#{{ $orderNo }}</span>
                        </div>
                        <div class="order-card-status {{ $statusEnum }}">
                            {{ $order->status->label() }}
                        </div>
                    </div>
                    <div class="order-card-body">
                        <div class="order-item-main">
                            <img src="{{ $productImg }}" alt="Product" class="order-item-img" onerror="this.src='https://placehold.co/150x150/f1f5f9/94a3b8?text=No+Image'">
                            <div class="order-item-info">
                                <div class="order-item-title">{{ $productName }}</div>
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
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <h3 style="color:#334155; margin:0 0 0.5rem; font-size:1.1rem;">Pesanan tidak ditemukan</h3>
                <p style="margin:0; font-size:0.9rem;">Coba cari dengan kata kunci lain atau cek tab status yang berbeda.</p>
            </div>
        @else
            <div class="empty-state">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <h3 style="color:#334155; margin:0 0 0.5rem; font-size:1.2rem;">Belum ada pesanan</h3>
                <p style="margin:0; font-size:0.95rem;">Temukan produk terbaik untuk kebutuhan rumah Anda.</p>
                <a href="{{ route('products') }}" style="display:inline-flex;align-items:center;gap:0.5rem;margin-top:1.25rem;background:#0EA5E9;color:#fff;padding:0.75rem 1.5rem;border-radius:10px;font-size:0.9rem;font-weight:700;text-decoration:none;">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('ord-active'));
            this.classList.add('ord-active');
            currentFilter = this.getAttribute('data-filter');
            filterOrders();
        });
    });
    
    if(searchInput) {
        searchInput.addEventListener('input', filterOrders);
    }
});
</script>
@endsection
