@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

<style>
/* ══════════════════════════════════════
   PRODUCT SHOW — buyle.id
   Digital Product • Modern Super App Layout
   Font: Montserrat thin / 400-600
══════════════════════════════════════ */

*, *::before, *::after { box-sizing: border-box; }

.pd-page {
    font-family: 'Montserrat', sans-serif;
    background: #F8FAFC;
    min-height: 100vh;
    padding-top: 72px;
}

/* ── Accent Vars ── */
.pd-page {
    --g1: #1eb349;
    --g2: #a5cf37;
    --grad: linear-gradient(135deg, #1eb349, #a5cf37);
    --grad-light: linear-gradient(135deg, #f0fdf4, #f7fee7);
    --text: #0F172A;
    --muted: #64748B;
    --border: #E2E8F0;
    --surface: #ffffff;
}

/* ─── BREADCRUMB ─── */
.pd-breadcrumb {
    max-width: 1200px; margin: 0 auto;
    padding: 1.5rem 1.5rem 0;
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.78rem; color: var(--muted);
}
.pd-breadcrumb a { color: var(--muted); text-decoration: none; }
.pd-breadcrumb a:hover { color: var(--g1); }
.pd-breadcrumb span.cur { color: var(--text); font-weight: 500; }

/* ─── MAIN GRID ─── */
.pd-main {
    max-width: 1200px; margin: 1rem auto 0;
    display: grid;
    grid-template-columns: minmax(0, 500px) 1fr;
    gap: 2rem;
    padding: 0 1.5rem 3rem;
    align-items: start;
}

/* ─── GALLERY ─── */
.pd-gallery {}
.pd-gallery-main {
    width: 100%; aspect-ratio: 1/1;
    border-radius: 20px; overflow: hidden;
    background: #fff; border: 1px solid var(--border);
    position: relative; margin-bottom: 0.75rem;
}
.pd-gallery-main img { width: 100%; height: 100%; object-fit: cover; }

/* Swiper nav overrides */
.pd-gallery-main .swiper-button-next,
.pd-gallery-main .swiper-button-prev {
    width: 36px; height: 36px;
    background: rgba(255,255,255,0.95);
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    color: var(--text);
}
.pd-gallery-main .swiper-button-next::after,
.pd-gallery-main .swiper-button-prev::after { font-size: 13px; font-weight: 700; }

.pd-thumbs {
    display: flex; gap: 0.5rem;
}
.pd-thumb-item {
    width: 64px; height: 64px; border-radius: 12px;
    overflow: hidden; border: 2px solid transparent;
    cursor: pointer; opacity: 0.55; transition: 0.2s;
    flex-shrink: 0;
}
.pd-thumb-item.swiper-slide-thumb-active { opacity: 1; border-color: var(--g1); }
.pd-thumb-item img { width: 100%; height: 100%; object-fit: cover; }

/* TikTok embed */
.pd-tiktok-wrap {
    margin-top: 1.25rem;
    border-radius: 16px; overflow: hidden;
    border: 1px solid var(--border); background: #fff;
}
.pd-tiktok-label {
    font-size: 0.8rem; font-weight: 600; color: var(--muted);
    padding: 0.75rem 1rem 0; display: block;
}

/* ─── PRODUCT INFO ─── */
.pd-info {}

/* Creator strip */
.pd-creator-strip {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: var(--grad-light); border: 1px solid rgba(30,179,73,0.15);
    border-radius: 99px; padding: 0.4rem 0.875rem;
    text-decoration: none; margin-bottom: 1rem;
}
.pd-creator-ava {
    width: 24px; height: 24px; border-radius: 50%;
    object-fit: cover; border: 1.5px solid var(--g1);
}
.pd-creator-name { font-size: 0.8rem; font-weight: 500; color: var(--g1); }

.pd-title {
    font-size: 1.75rem; font-weight: 600; color: var(--text);
    line-height: 1.3; margin: 0 0 1rem;
}

/* Stats row */
.pd-stats {
    display: flex; align-items: center; gap: 1rem;
    font-size: 0.82rem; color: var(--muted); margin-bottom: 1rem;
}
.pd-stars { color: #F59E0B; display: flex; align-items: center; gap: 3px; font-weight: 600; }
.pd-stat-sep { width: 1px; height: 14px; background: var(--border); }

/* Short desc */
.pd-short-desc {
    font-size: 0.92rem; color: var(--muted); line-height: 1.7;
    margin-bottom: 1.5rem; border-left: 3px solid var(--g1);
    padding-left: 1rem;
}

/* Price card */
.pd-price-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 20px; padding: 1.5rem; margin-bottom: 1.25rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}
.pd-price-row { display: flex; align-items: flex-end; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.5rem; }
.pd-price-main { font-size: 2rem; font-weight: 700; color: var(--g1); line-height: 1; }
.pd-price-old { font-size: 1rem; color: var(--muted); text-decoration: line-through; padding-bottom: 2px; }
.pd-discount-badge {
    background: var(--grad); color: #fff;
    font-size: 0.72rem; font-weight: 600;
    padding: 0.25rem 0.6rem; border-radius: 8px;
}
.pd-price-note { font-size: 0.78rem; color: var(--muted); }

/* Vouchers */
.pd-vouchers {
    display: flex; gap: 0.5rem; overflow-x: auto;
    scrollbar-width: none; padding-bottom: 0.25rem;
    margin-bottom: 1.25rem;
}
.pd-vouchers::-webkit-scrollbar { display: none; }
.pd-voucher {
    flex-shrink: 0; border: 1px dashed rgba(30,179,73,0.4);
    background: var(--grad-light); border-radius: 10px;
    padding: 0.5rem 0.75rem; display: flex; align-items: center; gap: 0.5rem;
    cursor: pointer; transition: 0.2s;
}
.pd-voucher:hover { border-color: var(--g1); box-shadow: 0 2px 8px rgba(30,179,73,0.1); }
.pd-voucher-icon {
    width: 28px; height: 28px; background: var(--grad);
    color: #fff; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; font-weight: 700; flex-shrink: 0;
}
.pd-voucher-text { line-height: 1.3; }
.pd-voucher-title { font-size: 0.78rem; font-weight: 600; color: var(--text); }
.pd-voucher-sub { font-size: 0.7rem; color: var(--muted); }

/* Qty */
.pd-qty-wrap {
    display: flex; align-items: center; gap: 1rem;
    background: #F8FAFC; border: 1px solid var(--border);
    border-radius: 14px; padding: 0.875rem 1rem;
    margin-bottom: 1.25rem;
}
.pd-qty-label { font-size: 0.85rem; font-weight: 500; color: var(--text); flex: 1; }
.pd-qty-ctrl {
    display: flex; align-items: center;
    border: 1px solid var(--border); border-radius: 10px;
    overflow: hidden; background: #fff;
}
.pd-qty-btn {
    width: 38px; height: 38px; border: none; background: transparent;
    font-size: 1.2rem; color: var(--muted); cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: 0.15s;
}
.pd-qty-btn:hover { background: #F1F5F9; color: var(--text); }
.pd-qty-input {
    width: 48px; height: 38px; border: none;
    border-left: 1px solid var(--border); border-right: 1px solid var(--border);
    text-align: center; font-size: 1rem; font-weight: 600;
    font-family: 'Montserrat', sans-serif; background: #fff; color: var(--text);
}
.pd-stock-label { font-size: 0.78rem; color: var(--muted); }
.pd-stock-out { color: #EF4444; font-weight: 600; }

/* Action Buttons */
.pd-actions {
    display: grid; grid-template-columns: 1fr 2fr; gap: 0.75rem;
    margin-bottom: 1.5rem;
}
.pd-btn {
    padding: 0.9rem 1.25rem; border-radius: 14px;
    font-size: 0.9rem; font-weight: 600;
    font-family: 'Montserrat', sans-serif;
    cursor: pointer; border: none;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    transition: all 0.2s; text-decoration: none;
}
.pd-btn-outline {
    background: #fff; color: var(--g1);
    border: 1.5px solid rgba(30,179,73,0.35);
}
.pd-btn-outline:hover { background: var(--grad-light); border-color: var(--g1); }
.pd-btn-primary {
    background: var(--grad); color: #fff;
    box-shadow: 0 6px 18px rgba(30,179,73,0.25);
}
.pd-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(30,179,73,0.35); }
.pd-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none !important; box-shadow: none !important; }

/* Guarantee badges */
.pd-guarantee-row {
    display: grid; grid-template-columns: 1fr 1fr 1fr;
    gap: 0.5rem; border-top: 1px solid var(--border); padding-top: 1.25rem;
}
.pd-guarantee-item {
    display: flex; flex-direction: column; align-items: center; text-align: center;
    gap: 0.35rem;
}
.pd-guarantee-item svg { color: var(--g1); }
.pd-guarantee-item span { font-size: 0.7rem; color: var(--muted); line-height: 1.3; }

/* ─── BOTTOM SECTION ─── */
.pd-bottom {
    max-width: 1200px; margin: 0 auto;
    padding: 0 1.5rem 4rem;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 2rem;
}

.pd-card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 20px; padding: 1.75rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.pd-section-title {
    font-size: 1.05rem; font-weight: 600; color: var(--text);
    margin-bottom: 1.25rem;
    display: flex; align-items: center; gap: 0.6rem;
}
.pd-section-title::before {
    content: ''; display: block;
    width: 4px; height: 18px;
    background: var(--grad); border-radius: 4px;
}

/* Specs */
.pd-specs { width: 100%; border-collapse: collapse; font-size: 0.9rem; margin-bottom: 2rem; }
.pd-specs td { padding: 0.875rem 0; border-bottom: 1px solid var(--border); vertical-align: top; }
.pd-specs td:first-child { width: 45%; color: var(--muted); }
.pd-specs td:last-child { font-weight: 500; color: var(--text); }

/* Description */
.pd-content {
    font-size: 0.92rem; color: var(--muted); line-height: 1.8;
}
.pd-content h2, .pd-content h3 {
    font-weight: 600; color: var(--text); margin: 1.5rem 0 0.75rem;
}
.pd-content ul, .pd-content ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.pd-content p { margin-bottom: 1rem; }
.pd-content a { color: var(--g1); }

/* FAQ */
.pd-faq-item {
    background: #F8FAFC; border: 1px solid var(--border);
    border-radius: 14px; padding: 1.25rem 1.5rem;
    margin-bottom: 0.75rem;
}
.pd-faq-q { font-size: 0.92rem; font-weight: 600; color: var(--text); margin-bottom: 0.5rem; }
.pd-faq-a { font-size: 0.87rem; color: var(--muted); line-height: 1.7; }

/* Sidebar guarantee box */
.pd-guarantee-box {}
.pd-guarantee-list { display: flex; flex-direction: column; gap: 1rem; }
.pd-gl-item { display: flex; align-items: flex-start; gap: 0.875rem; }
.pd-gl-icon {
    width: 40px; height: 40px; background: var(--grad-light);
    border: 1px solid rgba(30,179,73,0.15);
    border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    color: var(--g1);
}
.pd-gl-title { font-size: 0.88rem; font-weight: 600; color: var(--text); margin-bottom: 0.2rem; }
.pd-gl-desc { font-size: 0.8rem; color: var(--muted); line-height: 1.5; }

/* Seller Card */
.pd-seller-card {
    margin-top: 1.25rem;
    background: var(--grad-light);
    border: 1px solid rgba(30,179,73,0.15);
    border-radius: 16px; padding: 1.25rem;
}
.pd-seller-inner { display: flex; align-items: center; gap: 0.875rem; }
.pd-seller-ava {
    width: 48px; height: 48px; border-radius: 50%;
    object-fit: cover; border: 2px solid var(--g1); flex-shrink: 0;
    background: #fff; display: flex; align-items: center; justify-content: center;
    font-weight: 600; color: var(--g1); font-size: 1.1rem;
}
.pd-seller-name { font-size: 0.9rem; font-weight: 600; color: var(--text); }
.pd-seller-sub { font-size: 0.78rem; color: var(--muted); }

/* Related Products */
.pd-related {
    background: #F8FAFC;
    border-top: 1px solid var(--border);
    padding: 3.5rem 1.5rem 4rem;
}
.pd-related-inner { max-width: 1200px; margin: 0 auto; }
.pd-related-title {
    font-size: 1.25rem; font-weight: 600; color: var(--text);
    margin-bottom: 1.5rem;
}
.pd-related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.25rem;
}
.pd-related-card {
    background: #fff; border: 1px solid var(--border);
    border-radius: 16px; overflow: hidden; text-decoration: none;
    color: inherit; display: block;
    transition: transform 0.2s, box-shadow 0.2s;
}
.pd-related-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(30,179,73,0.12); border-color: var(--g1); }
.pd-related-card img { width: 100%; aspect-ratio: 1/1; object-fit: cover; }
.pd-related-body { padding: 0.875rem; }
.pd-related-name { font-size: 0.88rem; font-weight: 500; color: var(--text); margin-bottom: 0.35rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.pd-related-price { font-size: 0.95rem; font-weight: 600; color: var(--g1); }

/* ─── MOBILE STICKY BAR ─── */
.pd-sticky-bar { display: none; }

/* ─── RESPONSIVE ─── */
@media (max-width: 1024px) {
    .pd-main { grid-template-columns: 1fr; }
    .pd-bottom { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
    .pd-page { padding-top: 64px; }
    .pd-main { padding: 0.75rem 0.75rem 2rem; gap: 1.25rem; }
    .pd-bottom { padding: 0 0.75rem 7rem; }
    .pd-title { font-size: 1.35rem; }
    .pd-price-main { font-size: 1.75rem; }
    .pd-card { padding: 1.25rem; }
    .pd-actions { display: none; }

    .pd-sticky-bar {
        display: flex; align-items: center; gap: 0.75rem;
        position: fixed; bottom: 0; left: 0; right: 0;
        background: rgba(255,255,255,0.97);
        backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
        padding: 0.875rem 1rem;
        border-top: 1px solid var(--border);
        box-shadow: 0 -4px 20px rgba(0,0,0,0.06);
        z-index: 100;
    }
    .pd-sticky-bar .pd-btn { flex: 1; padding: 0.8rem; font-size: 0.88rem; border-radius: 12px; }
    .pd-related-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
}

@media (max-width: 480px) {
    .pd-guarantee-row { grid-template-columns: 1fr 1fr; }
}
</style>

{{-- Breadcrumb --}}
<div class="pd-page">
<div class="pd-breadcrumb">
    <a href="{{ route_locale('home') }}">Beranda</a>
    <span>›</span>
    <a href="{{ route_locale('products') }}">Produk</a>
    <span>›</span>
    <span class="cur">{{ Str::limit($service->name, 40) }}</span>
</div>

{{-- Main Grid --}}
<div class="pd-main">

    {{-- LEFT: Gallery --}}
    <div class="pd-gallery">
        @php
            $imgs = [];
            if ($service->image) $imgs[] = asset('storage/'.$service->image);
            else $imgs[] = asset('images/service-default.jpg');
            if (is_array($service->gallery)) {
                foreach ($service->gallery as $g) $imgs[] = asset('storage/'.$g);
            }
        @endphp

        <div class="pd-gallery-main swiper" id="pd-swiper-main">
            <div class="swiper-wrapper">
                @foreach($imgs as $img)
                <div class="swiper-slide">
                    <a href="{{ $img }}" class="glightbox" data-gallery="product-gallery">
                        <img src="{{ $img }}" alt="{{ $service->name }}" loading="lazy">
                    </a>
                </div>
                @endforeach
            </div>
            @if(count($imgs) > 1)
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            @endif
        </div>

        @if(count($imgs) > 1)
        <div class="swiper pd-thumbs" id="pd-swiper-thumbs">
            <div class="swiper-wrapper">
                @foreach($imgs as $img)
                <div class="swiper-slide pd-thumb-item">
                    <img src="{{ $img }}" alt="thumb" loading="lazy">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($service->tiktok_video_url)
        <div class="pd-tiktok-wrap">
            <span class="pd-tiktok-label">📹 Video Produk</span>
            <blockquote class="tiktok-embed"
                cite="{{ $service->tiktok_video_url }}"
                data-video-id="{{ Str::afterLast(rtrim($service->tiktok_video_url, '/'), '/') }}"
                style="max-width:100%; margin:0; border:none;">
                <section>
                    <a target="_blank" href="{{ $service->tiktok_video_url }}">Tonton di TikTok</a>
                </section>
            </blockquote>
            <script async src="https://www.tiktok.com/embed.js"></script>
        </div>
        @endif
    </div>

    {{-- RIGHT: Product Info --}}
    <div class="pd-info">

        {{-- Creator Strip --}}
        @if(isset($service->creator) && $service->creator)
        @php $creator = $service->creator; $cp = $creator->creatorProfile; @endphp
        <a href="{{ route('store.show', optional($cp)->store_slug ?? '#') }}" class="pd-creator-strip">
            @if($creator->avatar)
                <img src="{{ asset('storage/'.$creator->avatar) }}" class="pd-creator-ava" alt="{{ $creator->name }}">
            @else
                <div class="pd-creator-ava" style="background:var(--grad-light); display:flex; align-items:center; justify-content:center; font-weight:600; font-size:0.7rem; color:var(--g1);">
                    {{ strtoupper(substr($creator->name,0,2)) }}
                </div>
            @endif
            <span class="pd-creator-name">{{ optional($cp)->store_name ?: $creator->name }}</span>
        </a>
        @endif

        <h1 class="pd-title">{{ $service->name }}</h1>

        @if($service->rating > 0 || $service->sold_count > 0)
        <div class="pd-stats">
            @if($service->rating > 0)
            <div class="pd-stars">
                ★ {{ number_format($service->rating, 1) }}
            </div>
            @endif
            @if($service->rating > 0 && $service->sold_count > 0)<div class="pd-stat-sep"></div>@endif
            @if($service->sold_count > 0)
            <div>Terjual <b style="color:var(--text);">{{ $service->sold_count >= 1000 ? number_format($service->sold_count/1000, 1, ',', '').'RB' : $service->sold_count }}</b></div>
            @endif
        </div>
        @endif

        @if($service->short_desc)
        <p class="pd-short-desc">{{ $service->short_desc }}</p>
        @endif

        {{-- Price Card --}}
        @if($service->price > 0)
        <div class="pd-price-card">
            <div class="pd-price-row">
                @if($service->sale_price > 0 && $service->sale_price < $service->price)
                    <div class="pd-price-main">Rp{{ number_format($service->sale_price, 0, ',', '.') }}</div>
                    <div class="pd-price-old">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                    <div class="pd-discount-badge">Hemat {{ round((($service->price - $service->sale_price)/$service->price)*100) }}%</div>
                @else
                    <div class="pd-price-main">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                @endif
            </div>
            <div class="pd-price-note">Harga termasuk pajak dan biaya admin</div>
        </div>

        {{-- Vouchers --}}
        <div class="pd-vouchers">
            <div class="pd-voucher">
                <div class="pd-voucher-icon">%</div>
                <div class="pd-voucher-text">
                    <div class="pd-voucher-title">Diskon 50RB</div>
                    <div class="pd-voucher-sub">Min. 300RB</div>
                </div>
            </div>
            <div class="pd-voucher">
                <div class="pd-voucher-icon">%</div>
                <div class="pd-voucher-text">
                    <div class="pd-voucher-title">Diskon 10%</div>
                    <div class="pd-voucher-sub">S/d 100RB</div>
                </div>
            </div>
            <div class="pd-voucher">
                <div class="pd-voucher-icon" style="font-size:0.6rem;">FREE</div>
                <div class="pd-voucher-text">
                    <div class="pd-voucher-title">Gratis Ongkir</div>
                    <div class="pd-voucher-sub">S/d 20RB</div>
                </div>
            </div>
        </div>

        <form action="{{ route('cart.add') }}" method="POST" id="form-add-to-cart">
            @csrf
            <input type="hidden" name="product_id" value="{{ $service->id }}">

            {{-- Qty --}}
            <div class="pd-qty-wrap">
                <div class="pd-qty-label">Kuantitas</div>
                <div class="pd-qty-ctrl">
                    <button type="button" class="pd-qty-btn" onclick="document.getElementById('qty_input').stepDown()">−</button>
                    <input type="number" id="qty_input" class="pd-qty-input" name="qty"
                        value="{{ $service->min_order ?? 1 }}" min="{{ $service->min_order ?? 1 }}"
                        @if($service->type !== 'service' && $service->stock > 0) max="{{ $service->stock }}" @endif
                        @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                    <button type="button" class="pd-qty-btn" onclick="document.getElementById('qty_input').stepUp()">+</button>
                </div>
                @if($service->type === 'service')
                    <span class="pd-stock-label">Jasa / Layanan</span>
                @elseif($service->stock > 0)
                    <span class="pd-stock-label">Stok: <b style="color:var(--text);">{{ $service->stock }}</b></span>
                @else
                    <span class="pd-stock-out">Stok Habis</span>
                @endif
            </div>

            {{-- Desktop Actions --}}
            <div class="pd-actions">
                <button type="submit" name="action" value="cart" class="pd-btn pd-btn-outline"
                    @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 20a1 1 0 100-2 1 1 0 000 2zM20 20a1 1 0 100-2 1 1 0 000 2z"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
                    </svg>
                    Keranjang
                </button>
                <button type="submit" name="action" value="buy" class="pd-btn pd-btn-primary"
                    @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                    ⚡ Beli Sekarang
                </button>
            </div>
        </form>

        @else
        {{-- No price: CTA chat --}}
        <div class="pd-price-card" style="background: var(--grad-light); border-color: rgba(30,179,73,0.2);">
            <div class="pd-price-main" style="color: var(--g1);">Konsultasi Gratis</div>
            <div style="font-size:0.9rem; color:var(--muted); margin-top:0.5rem;">Tim ahli kami siap memberikan penawaran terbaik untuk Anda.</div>
        </div>
        <a href="javascript:void(0)" onclick="openOrderModal('Produk: {{ addslashes($service->name) }}')"
            class="pd-btn pd-btn-primary" style="width:100%; justify-content:center;">
            💬 Tanya via WhatsApp
        </a>
        @endif

        {{-- Guarantee Row --}}
        <div class="pd-guarantee-row">
            <div class="pd-guarantee-item">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>100% Original & Terpercaya</span>
            </div>
            <div class="pd-guarantee-item">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                <span>Refund & Garansi Aman</span>
            </div>
            <div class="pd-guarantee-item">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Akses Instan Digital</span>
            </div>
        </div>

    </div>
</div>

{{-- BOTTOM SECTION --}}
<div class="pd-bottom">

    {{-- LEFT: Description & Specs --}}
    <div class="pd-card">
        @if(is_array($service->specifications) && count($service->specifications) > 0)
        <div class="pd-section-title">Spesifikasi Produk</div>
        <table class="pd-specs">
            @foreach($service->specifications as $spec)
            <tr>
                <td>{{ $spec['key'] }}</td>
                <td>{{ $spec['value'] }}</td>
            </tr>
            @endforeach
        </table>
        @endif

        <div class="pd-section-title">Deskripsi Produk</div>
        <div class="pd-content">
            {!! $service->description ?? '<p>Belum ada deskripsi untuk produk ini.</p>' !!}
        </div>

        @php $faqs = is_array($service->faqs) && !empty($service->faqs) ? $service->faqs : []; @endphp
        @if(count($faqs) > 0)
        <div class="pd-section-title" style="margin-top: 2.5rem;">FAQ — {{ $service->name }}</div>
        @foreach($faqs as $f)
        <div class="pd-faq-item">
            <div class="pd-faq-q">{{ $f['q'] ?? '' }}</div>
            <div class="pd-faq-a">{{ $f['a'] ?? '' }}</div>
        </div>
        @endforeach
        <script type="application/ld+json">
        {
          "@@context": "https://schema.org",
          "@@type": "FAQPage",
          "mainEntity": [
            @foreach($faqs as $idx => $f)
            {
              "@@type": "Question",
              "name": "{{ addslashes(strip_tags($f['q'] ?? '')) }}",
              "acceptedAnswer": {
                "@@type": "Answer",
                "text": "{{ addslashes(strip_tags($f['a'] ?? '')) }}"
              }
            }{{ $idx < count($faqs) - 1 ? ',' : '' }}
            @endforeach
          ]
        }
        </script>
        @endif
    </div>

    {{-- RIGHT: Sidebar --}}
    <div>
        {{-- Guarantee box sticky --}}
        <div class="pd-card pd-guarantee-box" style="position:sticky; top:90px;">
            <div class="pd-section-title">Jaminan Berbelanja</div>
            <div class="pd-guarantee-list">
                <div class="pd-gl-item">
                    <div class="pd-gl-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        <div class="pd-gl-title">100% Produk Original</div>
                        <div class="pd-gl-desc">Semua produk asli dan bergaransi dari seller terpercaya.</div>
                    </div>
                </div>
                <div class="pd-gl-item">
                    <div class="pd-gl-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div>
                        <div class="pd-gl-title">Akses Produk Digital Instan</div>
                        <div class="pd-gl-desc">Link dikirim otomatis setelah pembayaran berhasil.</div>
                    </div>
                </div>
                <div class="pd-gl-item">
                    <div class="pd-gl-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <div>
                        <div class="pd-gl-title">CS Responsif via WhatsApp</div>
                        <div class="pd-gl-desc">Tim kami siap membantu setiap hari dari jam 08.00–22.00.</div>
                    </div>
                </div>
                <div class="pd-gl-item">
                    <div class="pd-gl-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <div class="pd-gl-title">Refund 2x24 Jam</div>
                        <div class="pd-gl-desc">Jika produk bermasalah, dana dikembalikan penuh.</div>
                    </div>
                </div>
            </div>

            @if($service->brochure)
            <a href="{{ asset('storage/'.$service->brochure) }}" target="_blank"
                class="pd-btn pd-btn-outline" style="margin-top:1.5rem; width:100%; justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh Brosur / Datasheet
            </a>
            @endif

            {{-- Seller info --}}
            @if(isset($service->creator) && $service->creator)
            @php $creatorSb = $service->creator; $cpSb = $creatorSb->creatorProfile; @endphp
            <div class="pd-seller-card">
                <div style="font-size:0.75rem; font-weight:600; color:var(--muted); margin-bottom:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">Penjual</div>
                <div class="pd-seller-inner">
                    @if($creatorSb->avatar)
                        <img src="{{ asset('storage/'.$creatorSb->avatar) }}" class="pd-seller-ava" alt="{{ $creatorSb->name }}">
                    @else
                        <div class="pd-seller-ava">{{ strtoupper(substr($creatorSb->name,0,2)) }}</div>
                    @endif
                    <div>
                        <div class="pd-seller-name">{{ optional($cpSb)->store_name ?: $creatorSb->name }}</div>
                        <div class="pd-seller-sub">{{ optional($cpSb)->store_description ? Str::limit($cpSb->store_description,50) : 'Seller Digital buyle.id' }}</div>
                    </div>
                </div>
                @if(optional($cpSb)->store_slug)
                <a href="{{ route('store.show', $cpSb->store_slug) }}"
                    class="pd-btn pd-btn-outline" style="margin-top:0.875rem; width:100%; justify-content:center; font-size:0.82rem;">
                    Kunjungi Toko →
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

{{-- MOBILE STICKY BAR --}}
@if($service->price > 0)
<div class="pd-sticky-bar">
    <button type="button" class="pd-btn pd-btn-outline"
        onclick="document.querySelector('button[value=\'cart\']').click()"
        @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 20a1 1 0 100-2 1 1 0 000 2zM20 20a1 1 0 100-2 1 1 0 000 2z"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
        </svg>
        Keranjang
    </button>
    <button type="button" class="pd-btn pd-btn-primary"
        onclick="document.querySelector('button[value=\'buy\']').click()"
        @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
        ⚡ Beli Sekarang
    </button>
</div>
@endif

{{-- RELATED --}}
@if($related->count() > 0)
<div class="pd-related">
    <div class="pd-related-inner">
        <div class="pd-related-title">Produk Lainnya dari Marketplace</div>
        <div class="pd-related-grid">
            @foreach($related as $r)
            <a href="{{ route_locale('products.show', $r->slug) }}" class="pd-related-card">
                <img src="{{ $r->image_url }}" alt="{{ $r->name }}" loading="lazy">
                <div class="pd-related-body">
                    <div class="pd-related-name">{{ $r->name }}</div>
                    <div class="pd-related-price">Rp{{ number_format($r->sale_price ?? $r->price, 0, ',', '.') }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif

</div>{{-- .pd-page --}}

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });

    var thumbsEl = document.getElementById('pd-swiper-thumbs');
    if (thumbsEl) {
        var swiperThumbs = new Swiper('#pd-swiper-thumbs', {
            spaceBetween: 8, slidesPerView: 'auto',
            freeMode: true, watchSlidesProgress: true,
        });
        new Swiper('#pd-swiper-main', {
            spaceBetween: 0,
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            thumbs: { swiper: swiperThumbs },
        });
    } else {
        var mainEl = document.getElementById('pd-swiper-main');
        if (mainEl) new Swiper('#pd-swiper-main', { spaceBetween: 0 });
    }
});
</script>

@endsection
