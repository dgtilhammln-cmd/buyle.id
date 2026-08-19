@extends('layouts.app')

@section('title', 'Keranjang Belanja')
@section('meta_description', 'Lihat dan kelola keranjang belanja Anda di buyle.id.')

@section('content')
<style>
:root {
    --c-accent: #0EA5E9;
    --c-accent-dark: #0369A1;
    --c-text: #0F172A;
    --c-muted: #64748B;
    --c-border: #E2E8F0;
    --c-bg: #F1F5F9;
    --c-card: #ffffff;
    --font: 'Montserrat', sans-serif;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--c-bg); font-family: var(--font); }

.cart-page { max-width: 480px; margin: 0 auto; min-height: 100vh; padding-bottom: 240px; }
.cart-topbar { background: #fff; padding: 1rem 1.25rem 0.85rem; display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid var(--c-border); z-index: 50; }
.cart-topbar h1 { font-size: 1.05rem; font-weight: 800; color: var(--c-text); flex: 1; margin: 0; }
.cart-count-badge { background: var(--c-accent); color: #fff; font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 99px; }
.cart-items-wrap { padding: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem; }
.cart-item { background: var(--c-card); border-radius: 14px; padding: 0.85rem; display: flex; align-items: flex-start; gap: 0.85rem; border: 1px solid var(--c-border); }
.cart-item-img { width: 72px; height: 72px; border-radius: 10px; overflow: hidden; background: #F1F5F9; flex-shrink: 0; border: 1px solid #E2E8F0; }
.cart-item-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
.cart-item-body { flex: 1; min-width: 0; }
.cart-item-name { font-size: 0.85rem; font-weight: 700; color: var(--c-text); line-height: 1.35; margin-bottom: 0.2rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.cart-item-variant { font-size: 0.72rem; color: var(--c-muted); margin-bottom: 0.45rem; background: #F1F5F9; display: inline-block; padding: 0.15rem 0.5rem; border-radius: 4px; }
.cart-item-price { font-size: 0.9rem; font-weight: 800; color: var(--c-accent); }
.cart-item-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 0.65rem; padding-top: 0.65rem; border-top: 1px solid #F1F5F9; gap: 0.5rem; }
.qty-ctrl { display: flex; align-items: center; border: 1.5px solid var(--c-border); border-radius: 8px; overflow: hidden; height: 32px; background: #F8FAFC; flex-shrink: 0; }
.qty-ctrl button { width: 32px; height: 100%; background: none; border: none; font-size: 1rem; font-weight: 700; color: var(--c-text); cursor: pointer; display: flex; align-items: center; justify-content: center; }
.qty-ctrl button:hover { background: #E2E8F0; }
.qty-ctrl input { width: 36px; height: 100%; border: none; border-left: 1px solid #E2E8F0; border-right: 1px solid #E2E8F0; background: #fff; text-align: center; font-weight: 700; font-family: var(--font); font-size: 0.85rem; color: var(--c-text); }
.qty-ctrl input:focus { outline: none; }
.item-subtotal { font-size: 0.8rem; font-weight: 700; color: var(--c-text); text-align: right; }
.item-subtotal span { font-size: 0.68rem; color: var(--c-muted); font-weight: 500; display: block; }
.btn-del { background: none; border: none; color: #CBD5E1; cursor: pointer; padding: 0.25rem; display: flex; align-items: center; justify-content: center; border-radius: 6px; transition: color 0.2s, background 0.2s; flex-shrink: 0; }
.btn-del:hover { color: #EF4444; background: #FEE2E2; }

.cart-sticky-footer { position: fixed; bottom: 85px; left: 50%; transform: translateX(-50%); width: 100%; max-width: 480px; background: #fff; border-top: 1px solid var(--c-border); padding: 0.85rem 1.25rem; z-index: 100; box-shadow: 0 -4px 20px rgba(0,0,0,0.07); }
.footer-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
.footer-row-label { font-size: 0.78rem; color: var(--c-muted); font-weight: 500; }
.footer-row-val { font-size: 0.78rem; color: var(--c-muted); font-weight: 600; }
.footer-total-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; }
.footer-total-label { font-size: 0.95rem; font-weight: 700; color: var(--c-text); }
.footer-total-val { font-size: 1.15rem; font-weight: 900; color: var(--c-accent); }
.btn-checkout { display: flex; width: 100%; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.9rem; background: linear-gradient(135deg, var(--c-accent), var(--c-accent-dark)); color: #fff; border: none; border-radius: 12px; font-family: var(--font); font-weight: 800; font-size: 0.95rem; cursor: pointer; text-decoration: none; transition: opacity 0.2s; }
.btn-checkout:hover { opacity: 0.9; color: #fff; }

.empty-state { text-align: center; padding: 4rem 2rem; }
.empty-icon-wrap { width: 100px; height: 100px; background: #F0F9FF; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
.empty-state h2 { font-size: 1.15rem; font-weight: 800; color: var(--c-text); margin-bottom: 0.5rem; }
.empty-state p { font-size: 0.85rem; color: var(--c-muted); line-height: 1.6; margin-bottom: 1.5rem; }
.btn-shop { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.8rem 1.75rem; background: linear-gradient(135deg, var(--c-accent), var(--c-accent-dark)); color: #fff; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 0.9rem; }

@media (min-width: 768px) {
    .cart-page { max-width: 1100px; display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start; margin: 120px auto 4rem; padding: 0 1.5rem; min-height: auto; }
    .cart-topbar { position: relative; top: auto; background: transparent; border-bottom: none; padding: 0 0 0.5rem; grid-column: 1 / -1; margin-bottom: 0; }
    .cart-topbar h1 { font-size: 1.4rem; }
    .cart-items-wrap { padding: 0; grid-column: 1; }
    .cart-sticky-footer { position: sticky; bottom: auto; top: 100px; transform: none; left: auto; max-width: none; background: var(--c-card); border: 1px solid var(--c-border); border-radius: 16px; padding: 1.25rem; box-shadow: none; grid-column: 2; }
}
@media (max-width: 768px) {
    /* Hide floating chat widget on cart page to avoid overlapping checkout button */
    .fc-widget { display: none !important; }
}
</style>

<div class="cart-page">
    <div class="cart-topbar">
        <h1>Keranjang Belanja</h1>
        @if(!$summary['items']->isEmpty())
            <span class="cart-count-badge">{{ $summary['count'] }} item</span>
        @endif
    </div>

    @if(session('success') || session('error'))
    <div style="padding:0.5rem 0.75rem;">
        @if(session('success'))
            <div style="background:#F0FDF4;color:#166534;padding:0.75rem 1rem;border-radius:10px;font-size:0.85rem;border:1px solid #BBF7D0;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="background:#FEF2F2;color:#991B1B;padding:0.75rem 1rem;border-radius:10px;font-size:0.85rem;border:1px solid #FECACA;">{{ session('error') }}</div>
        @endif
    </div>
    @endif

    @if($summary['items']->isEmpty())
        <div class="empty-state" style="grid-column:1/-1;">
            <div class="empty-icon-wrap">
                <svg width="48" height="48" fill="none" stroke="#0EA5E9" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <h2>Keranjang Masih Kosong</h2>
            <p>Yuk cari produk perlengkapan rumah impianmu dan tambahkan ke keranjang!</p>
            <a href="{{ route('products') }}" class="btn-shop">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="cart-items-wrap">
            @foreach($summary['items'] as $item)
            <div class="cart-item">
                <div class="cart-item-img">
                    @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/'.$item->product->image) }}" alt="{{ $item->product->name }}" loading="lazy">
                    @else
                        <div style="width:100%;height:100%;background:#E2E8F0;display:flex;align-items:center;justify-content:center;">
                            <svg width="24" height="24" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                    @endif
                </div>
                <div class="cart-item-body">
                    <div class="cart-item-name">{{ $item->product->name ?? 'Produk Telah Dihapus' }}</div>
                    @if($item->variantValue)
                        <div class="cart-item-variant">Varian: {{ $item->variantValue->value }}</div>
                    @endif
                    <div class="cart-item-price">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                    <div class="cart-item-footer">
                        <form action="{{ route('cart.update') }}" method="POST" style="display:contents;">
                            @csrf
                            <input type="hidden" name="cart_id" value="{{ $item->id }}">
                            <div class="qty-ctrl">
                                <button type="button" onclick="this.nextElementSibling.stepDown();this.closest('form').submit();">−</button>
                                <input type="number" name="qty" value="{{ $item->qty }}" min="1" onchange="this.closest('form').submit()">
                                <button type="button" onclick="this.previousElementSibling.stepUp();this.closest('form').submit();">+</button>
                            </div>
                        </form>
                        <div class="item-subtotal">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            <span>Subtotal</span>
                        </div>
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cart_id" value="{{ $item->id }}">
                            <button type="submit" class="btn-del" title="Hapus">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="cart-sticky-footer">
            <div class="footer-row">
                <span class="footer-row-label">{{ $summary['count'] }} produk dipilih</span>
                <span class="footer-row-val">Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}</span>
            </div>
            <div class="footer-row">
                <span class="footer-row-label">Total Berat</span>
                <span class="footer-row-val">{{ $summary['total_weight'] }} gr</span>
            </div>
            <div class="footer-total-row">
                <span class="footer-total-label">Total Harga</span>
                <span class="footer-total-val">Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('checkout.index') }}" class="btn-checkout">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                Lanjut Checkout
            </a>
        </div>
    @endif
</div>
@endsection
