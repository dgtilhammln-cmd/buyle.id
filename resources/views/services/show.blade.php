@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

<style>
/* ══════════════════════════════════════
   PRODUCT SHOW — buyle.id (Shopee Style)
   Font: Montserrat
══════════════════════════════════════ */

*, *::before, *::after { box-sizing: border-box; }

.pd-page {
    font-family: 'Montserrat', sans-serif;
    background: #F5F5F5; /* Abu-abu shopee */
    min-height: 100vh;
    padding-top: 80px;
    color: #1E293B;
}
@media (max-width: 768px) {
    footer.cv-footer-v2 { display: none !important; }
    .pd-actions { display: none !important; } /* Hide inline buttons on mobile since sticky bottom bar handles checkout */
}

/* ── Accent Vars ── */
.pd-page {
    --primary: #1eb349;
    --primary-hover: #16a34a;
    --primary-light: rgba(30, 179, 73, 0.1);
    --text-main: #0F172A;
    --text-muted: #64748B;
    --border: #E2E8F0;
    --bg-light: #F8FAFC;
}

/* ─── BREADCRUMB ─── */
.pd-breadcrumb {
    max-width: 1200px; margin: 0 auto;
    padding: 1.5rem 1.5rem 1rem;
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.8rem; font-weight: 400; color: var(--text-muted);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.pd-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: 0.2s; white-space: nowrap; }
.pd-breadcrumb a:hover { color: var(--primary); }
.pd-breadcrumb span.cur { color: var(--text-main); font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ─── CONTAINERS & GRID ─── */
.pd-layout-grid {
    max-width: 1200px;
    margin: 0 auto 2rem;
    padding: 0 1rem;
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 1.5rem;
    align-items: start;
}
@media (max-width: 992px) {
    .pd-layout-grid {
        grid-template-columns: 1fr;
    }
}

.pd-main-col {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    min-width: 0;
}

.pd-sidebar-col {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    position: sticky;
    top: 90px;
}
@media (max-width: 992px) {
    .pd-sidebar-col {
        position: static;
    }
}

.pd-container {
    width: 100%;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}

/* ─── PRODUCT BLOCK ─── */
.pd-product-block {
    display: flex;
    padding: 1.5rem;
    gap: 1.5rem;
}
@media (max-width: 768px) {
    .pd-product-block { flex-direction: column; padding: 1rem; gap: 1rem; }
}

/* ── GALLERY (Left) ── */
.pd-gallery { width: 360px; flex-shrink: 0; }
@media (max-width: 768px) { .pd-gallery { width: 100%; } }

.pd-gallery-main {
    width: 100%; aspect-ratio: 1/1;
    border-radius: 4px; overflow: hidden;
    background: var(--bg-light); margin-bottom: 0.75rem;
    position: relative;
}
.pd-gallery-main img { width: 100%; height: 100%; object-fit: cover; }
.pd-tiktok-slide { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #000; }
.pd-tiktok-slide iframe { width: 100%; height: 100%; border: none; }

.pd-gallery-main .swiper-button-next,
.pd-gallery-main .swiper-button-prev {
    width: 32px; height: 32px; background: rgba(0,0,0,0.3); color: #fff; border-radius: 50%;
}
.pd-gallery-main .swiper-button-next::after,
.pd-gallery-main .swiper-button-prev::after { font-size: 12px; }

.pd-thumbs { display: flex; gap: 0.5rem; }
.pd-thumb-item {
    width: 82px; height: 82px; border-radius: 4px;
    overflow: hidden; border: 2px solid transparent;
    cursor: pointer; opacity: 1; transition: 0.2s; flex-shrink: 0;
    background: var(--bg-light);
}
.pd-thumb-item:hover { border-color: var(--primary); }
.pd-thumb-item.swiper-slide-thumb-active { border-color: var(--primary); }
.pd-thumb-item img { width: 100%; height: 100%; object-fit: cover; }

/* ── PRODUCT INFO (Right) ── */
.pd-info { flex: 1; min-width: 0; }

.pd-title {
    font-size: 1.25rem; font-weight: 500; color: var(--text-main);
    line-height: 1.4; margin: 0 0 0.5rem; word-wrap: break-word;
}

.pd-stats {
    display: flex; align-items: center; gap: 1rem;
    font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;
}
.pd-stars { display: flex; align-items: center; gap: 4px; color: var(--primary); font-weight: 500; border-bottom: 1px solid var(--primary); cursor: pointer; }
.pd-stat-sep { width: 1px; height: 14px; background: var(--border); }
.pd-stat-val { color: var(--text-main); font-weight: 500; border-bottom: 1px solid var(--text-main); cursor: pointer; }

/* Price Box */
.pd-price-box {
    background: linear-gradient(135deg, rgba(30,179,73,0.04), rgba(165,207,55,0.04));
    border-left: 3px solid var(--primary);
    padding: 1rem 1.25rem;
    display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;
    border-radius: 0 8px 8px 0;
    flex-wrap: wrap;
}
.pd-price-old { font-size: 1rem; color: var(--text-muted); text-decoration: line-through; white-space: nowrap; }
.pd-price-main { font-size: 1.6rem; font-weight: 700; background: linear-gradient(135deg, #1eb349, #a5cf37); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; white-space: nowrap; }
.pd-discount { background: linear-gradient(135deg, #1eb349, #a5cf37); color: #fff; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap; flex-shrink: 0; }

@media (max-width: 640px) {
    .pd-price-box { padding: 1rem; }
    .pd-price-main { font-size: 1.35rem; }
    .pd-price-old { font-size: 0.9rem; }
}
/* Shipping & Attributes Row */
.pd-attr-row { display: flex; align-items: flex-start; margin-bottom: 1.5rem; font-size: 0.9rem; }
.pd-attr-label { width: 110px; color: var(--text-muted); flex-shrink: 0; padding-top: 6px; }
.pd-attr-content { flex: 1; display: flex; flex-wrap: wrap; gap: 0.5rem; color: var(--text-main); }

/* Qty */
.pd-qty-ctrl {
    display: inline-flex; align-items: center; border: 1px solid var(--border);
    border-radius: 2px; overflow: hidden; background: #fff;
}
.pd-qty-btn {
    width: 32px; height: 32px; border: none; background: #fff;
    font-size: 1.2rem; color: var(--text-muted); cursor: pointer;
    display: flex; align-items: center; justify-content: center;
}
.pd-qty-btn:hover { background: var(--bg-light); }
.pd-qty-input {
    width: 50px; height: 32px; border: none; border-left: 1px solid var(--border); border-right: 1px solid var(--border);
    text-align: center; font-size: 0.95rem; font-weight: 400; font-family: inherit; color: var(--text-main);
}
.pd-stock { font-size: 0.85rem; color: var(--text-muted); margin-left: 1rem; align-self: center; }

/* Actions */
.pd-actions { display: flex; gap: 1rem; margin-top: 2rem; }
.pd-btn {
    padding: 0 1.5rem; height: 48px; border-radius: 99px; font-size: 0.95rem; font-weight: 500; font-family: inherit;
    cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
    transition: 0.2s; border: none; text-decoration: none;
}
.pd-btn-outline { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary); }
.pd-btn-outline:hover { background: rgba(30,179,73,0.15); }
.pd-btn-primary { 
    background: linear-gradient(135deg, #1eb349, #a5cf37); 
    color: #fff; border: 1px solid transparent; 
}
.pd-btn-primary:hover { opacity: 0.9; box-shadow: 0 4px 12px rgba(30,179,73,0.2); }
.pd-btn:disabled { opacity: 0.5; cursor: not-allowed; }


/* ─── SELLER BLOCK ─── */
.pd-seller-block {
    display: flex; align-items: center; padding: 1.5rem;
}
@media (max-width: 768px) {
    .pd-seller-block { flex-direction: column; align-items: flex-start; gap: 1rem; }
}
.pd-seller-left {
    display: flex; align-items: center; gap: 1rem;
    padding-right: 2rem; border-right: 1px solid var(--border);
    min-width: 350px;
}
@media (max-width: 768px) {
    .pd-seller-left { border-right: none; padding-right: 0; min-width: 100%; border-bottom: 1px solid var(--border); padding-bottom: 1rem; }
}
.pd-seller-ava {
    width: 78px; height: 78px; border-radius: 50%; object-fit: cover;
    background: #fff; border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; font-weight: 500; color: var(--primary); flex-shrink: 0;
}
.pd-seller-info { flex: 1; }
.pd-seller-name { font-size: 1rem; font-weight: 500; color: var(--text-main); margin-bottom: 0.25rem; }
.pd-seller-sub { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.75rem; }
.pd-seller-actions { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.pd-seller-btn {
    padding: 0.35rem 0.75rem; border-radius: 99px; font-size: 0.85rem; font-weight: 500;
    cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;
    text-decoration: none; transition: 0.2s;
}
.pd-seller-btn-outline { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary); }
.pd-seller-btn-outline:hover { background: rgba(30,179,73,0.15); }
.pd-seller-btn-gray { background: #fff; color: var(--text-muted); border: 1px solid var(--border); }
.pd-seller-btn-gray:hover { background: var(--bg-light); color: var(--text-main); }

.pd-seller-right {
    flex: 1; padding-left: 2rem;
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;
    font-size: 0.9rem; color: var(--text-muted);
}
@media (max-width: 768px) {
    .pd-seller-right { padding-left: 0; grid-template-columns: repeat(2, 1fr); width: 100%; }
}
.pd-seller-stat { display: flex; flex-direction: column; gap: 0.25rem; }
.pd-seller-stat span { color: var(--primary); font-weight: 600; font-size: 0.95rem; }


/* ─── DETAILS BLOCK ─── */
.pd-details-block { padding: 2rem; }
@media (max-width: 768px) { .pd-details-block { padding: 1.5rem 1rem; } }
.pd-section-title {
    background: linear-gradient(135deg, rgba(30,179,73,0.06), rgba(165,207,55,0.04));
    border-left: 3px solid var(--primary);
    padding: 0.75rem 1rem; font-size: 0.85rem;
    font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem;
    text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0 8px 8px 0;
}

/* Specs Table */
.pd-specs { width: 100%; font-size: 0.9rem; margin-bottom: 2rem; }
.pd-specs td { padding: 0.5rem 0; }
.pd-specs td:first-child { width: 150px; color: var(--text-muted); }
.pd-specs td:last-child { color: var(--text-main); }

/* Description Content — Prose Typography */
.pd-desc-content {
    font-size: 0.95rem;
    color: var(--text-main);
    line-height: 1.9;
}

/* Headings inside description */
.pd-desc-content h1,
.pd-desc-content h2,
.pd-desc-content h3,
.pd-desc-content h4 {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-main);
    margin: 1.75rem 0 0.6rem;
    line-height: 1.4;
    letter-spacing: -0.01em;
}
.pd-desc-content h1 { font-size: 1.2rem; }
.pd-desc-content h2 { font-size: 1.1rem; }
.pd-desc-content h3 { font-size: 1rem; }

/* Paragraphs */
.pd-desc-content p {
    margin: 0 0 1rem;
    color: #334155;
    line-height: 1.85;
}

/* Lists */
.pd-desc-content ul,
.pd-desc-content ol {
    margin: 0.5rem 0 1.25rem 1.5rem;
    padding: 0;
}
.pd-desc-content li {
    margin-bottom: 0.4rem;
    line-height: 1.7;
    color: #334155;
}

/* Strong */
.pd-desc-content strong { color: var(--text-main); font-weight: 700; }

/* Links inside description */
.pd-desc-content a {
    color: var(--primary);
    text-decoration: underline;
    text-decoration-color: rgba(30,179,73,0.3);
}

/* First heading no top margin */
.pd-desc-content h1:first-child,
.pd-desc-content h2:first-child,
.pd-desc-content h3:first-child { margin-top: 0; }


/* ─── MOBILE STICKY BAR ─── */
.pd-sticky-bar { display: none; }
@media (max-width: 768px) {
    .pd-page { padding-top: 0; }
    .pd-actions { display: none; }
    .pd-sticky-bar {
        display: flex; align-items: center; gap: 0.5rem;
        position: fixed; bottom: 0; left: 0; right: 0;
        background: #fff; padding: 0.75rem 1rem env(safe-area-inset-bottom, 0px);
        padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px));
        border-top: 1px solid var(--border);
        box-shadow: 0 -4px 15px rgba(0,0,0,0.08); z-index: 200;
    }
    .pd-sticky-bar .pd-btn { flex: 1; height: 46px; font-size: 0.9rem; border-radius: 12px; }
    .pd-breadcrumb { padding: 1rem 1rem 0.5rem; }
    /* push content up so sticky bar doesn't overlap */
    .pd-page { padding-bottom: 80px; }
    /* move report button above sticky bar */
    .buyle-report-btn { bottom: 80px !important; left: 12px !important; }
}


/* ─── RELATED (MATCHING HOMEPAGE 100%) ─── */
.pd-related-title { font-size: 1.25rem; font-weight: 800; color: #0F172A; margin-bottom: 1rem; font-family: 'Montserrat', sans-serif; }
.swipe-cards-container {
    display: flex;
    gap: 1.1rem;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    padding: 0.25rem 0.25rem 0.85rem;
    scrollbar-width: thin;
}
.swipe-cards-container::-webkit-scrollbar { height: 4px; }
.swipe-cards-container::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 99px; }
.article-card-swipe-item {
    flex: 0 0 240px;
    width: 240px;
    scroll-snap-align: start;
}
@media (min-width: 1024px) {
    .article-card-swipe-item {
        flex: 0 0 calc(20% - 0.88rem);
        width: calc(20% - 0.88rem);
    }
}
.article-card-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08) !important;
}
.article-card-box:hover .article-banner-img {
    transform: scale(1.05);
}

</style>

<div class="pd-page">

    {{-- Breadcrumb --}}
    <div class="pd-breadcrumb">
        <a href="{{ route_locale('home') }}">Beranda</a>
        <span>></span>
        <a href="{{ route_locale('products') }}">Produk</a>
        <span>></span>
        <span class="cur">{{ Str::limit($service->name, 40) }}</span>
    </div>

    {{-- LAYOUT 3 KOLOM --}}
    <div class="pd-layout-grid">
        
        {{-- MAIN COLUMN (Kiri & Tengah) --}}
        <div class="pd-main-col">
            <div class="pd-container pd-product-block">
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
                            @if($service->tiktok_video_url)
                            <div class="swiper-slide pd-tiktok-slide swiper-no-swiping">
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
                        @if(count($imgs) > 1 || $service->tiktok_video_url)
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        @endif
                    </div>

                    @if(count($imgs) > 1 || $service->tiktok_video_url)
                    <div class="swiper pd-thumbs" id="pd-swiper-thumbs">
                        <div class="swiper-wrapper">
                            @foreach($imgs as $img)
                            <div class="swiper-slide pd-thumb-item">
                                <img src="{{ $img }}" alt="thumb" loading="lazy">
                            </div>
                            @endforeach
                            @if($service->tiktok_video_url)
                            <div class="swiper-slide pd-thumb-item" style="background:#000; display:flex; align-items:center; justify-content:center; color:#fff;">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                {{-- CENTER: Info --}}
                <div class="pd-info">
                    <h1 class="pd-title">{{ $service->name }}</h1>

                    <div class="pd-stats" style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                        @php
                            $hasRealRating = ($service->rating && $service->rating > 0) || ($service->reviews_avg_rating && $service->reviews_avg_rating > 0);
                            $ratingVal     = $hasRealRating ? number_format($service->rating ?: $service->reviews_avg_rating, 1) : null;
                            $reviewCnt     = (int) ($service->reviews_count ?? ($service->review_count ?? 0));
                            $soldCnt       = (int) ($service->sold_count ?? 0);
                        @endphp
                        {{-- Rating (Realtime dari DB jika ada ulasan/rating) --}}
                        @if($hasRealRating || $reviewCnt > 0)
                            <div style="display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.85rem;">
                                <span style="color: #F59E0B; font-size: 1rem;">★</span>
                                <span style="color: #0F172A; font-weight: 700;">{{ $ratingVal ?: '5.0' }}</span>
                                @if($reviewCnt > 0)
                                    <span style="font-size: 0.78rem; color: #64748B;">({{ $reviewCnt }} ulasan)</span>
                                @endif
                            </div>
                            <span style="color: #CBD5E1;">|</span>
                        @endif
                        {{-- Terjual --}}
                        <div style="font-size: 0.85rem; color: #64748B;">
                            Terjual <span style="color: var(--text-main); font-weight: 700;">
                                @if($soldCnt >= 1000)
                                    {{ number_format($soldCnt/1000, 1, ',', '') }}RB
                                @else
                                    {{ $soldCnt }}
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- PRICE --}}
                    @if($service->price > 0)
                    <div class="pd-price-box">
                        @if($service->sale_price > 0 && $service->sale_price < $service->price)
                            <div class="pd-price-old">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                            <div class="pd-price-main">Rp{{ number_format($service->sale_price, 0, ',', '.') }}</div>
                            <span class="pd-discount">{{ round((($service->price - $service->sale_price)/$service->price)*100) }}% OFF</span>
                        @else
                            <div class="pd-price-main">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                        @endif
                    </div>

                    {{-- Pengiriman Digital --}}
                    <div class="pd-attr-row">
                        <div class="pd-attr-label">Pengiriman</div>
                        <div class="pd-attr-content">
                            <div>
                                <div style="display:flex; align-items:center; gap:4px; color:var(--text-main);">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    <b>Akses Instan & Otomatis</b>
                                </div>
                                <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Produk ini dapat langsung diakses/diunduh setelah pembayaran berhasil.</div>
                            </div>
                        </div>
                    </div>

                    {{-- CART FORM --}}
                    <form id="pd-cart-form" action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $service->id }}">

                        <div class="pd-attr-row" style="align-items:center;">
                            <div class="pd-attr-label">Kuantitas</div>
                            <div class="pd-attr-content">
                                <div class="pd-qty-ctrl">
                                    <button type="button" class="pd-qty-btn" onclick="document.getElementById('qty_input').stepDown()">−</button>
                                    <input type="number" id="qty_input" class="pd-qty-input" name="qty"
                                        value="{{ $service->min_order ?? 1 }}" min="{{ $service->min_order ?? 1 }}"
                                        @if($service->type !== 'service' && !is_null($service->stock) && $service->stock > 0) max="{{ $service->stock }}" @endif
                                        @if($service->type !== 'service' && !is_null($service->stock) && $service->stock <= 0) disabled @endif>
                                    <button type="button" class="pd-qty-btn" onclick="document.getElementById('qty_input').stepUp()">+</button>
                                </div>
                                <div class="pd-stock">
                                    @if($service->type === 'service')
                                        Jasa / Layanan
                                    @elseif(is_null($service->stock))
                                        <span style="color:#10B981;">Stok Tersedia</span>
                                    @elseif($service->stock > 0)
                                        Sisa {{ $service->stock }} buah
                                    @else
                                        <span style="color:#EE4D2D;">Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="pd-actions" style="margin-top:1.5rem; display:flex; gap:0.75rem;">
                            <button type="submit" name="action" value="cart" class="pd-btn pd-btn-outline" style="flex:1;"
                                @if($service->type !== 'service' && !is_null($service->stock) && $service->stock <= 0) disabled @endif>
                                Tambah Keranjang
                            </button>
                            <button type="submit" name="action" value="buy" class="pd-btn pd-btn-primary" style="flex:1;"
                                @if($service->type !== 'service' && !is_null($service->stock) && $service->stock <= 0) disabled @endif>
                                Beli Sekarang
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="pd-price-box">
                        <div class="pd-price-main">Konsultasi Penawaran</div>
                    </div>
                    <a href="javascript:void(0)" onclick="openOrderModal('Produk: {{ addslashes($service->name) }}')" class="pd-btn pd-btn-primary" style="margin-top:2rem; width:100%;">
                        Tanya via WhatsApp
                    </a>
                    @endif
                </div>
            </div>

            {{-- TABS --}}
            <div class="pd-container pd-tabs-block" style="padding:0;">
                <div class="pd-tabs-header" style="display:flex; border-bottom:1px solid var(--border); background:#f8fafc;">
                    <button id="tabBtn-desc" class="pd-tab-btn active" onclick="switchTab('desc')" style="flex:1; padding:1.2rem; background:transparent; border:none; font-weight:700; color:var(--text-main); border-bottom:3px solid var(--primary); cursor:pointer;">DESKRIPSI PRODUK</button>
                    <button id="tabBtn-creator" class="pd-tab-btn" onclick="switchTab('creator')" style="flex:1; padding:1.2rem; background:transparent; border:none; font-weight:700; color:var(--text-muted); border-bottom:3px solid transparent; cursor:pointer;">PROFIL CREATOR</button>
                </div>
                <div style="padding: 2rem;">
                    <div id="tab-desc" class="pd-tab-content" style="display:block;">
                        @if(is_array($service->specifications) && count($service->specifications) > 0)
                        <table class="pd-specs">
                            @foreach($service->specifications as $spec)
                            <tr>
                                <td>{{ $spec['key'] }}</td>
                                <td>{{ $spec['value'] }}</td>
                            </tr>
                            @endforeach
                        </table>
                        @endif

                        <div class="pd-desc-content">
                            {{-- SEO Prefix Paragraph --}}
                             @php
                                $catName = $service->subCategory?->name ?? $service->category?->name ?? $service->type_label ?? 'Produk Digital';
                            @endphp
                            <p style="font-size:0.9rem; color:#334155; line-height:1.8; margin-bottom:1.25rem; padding:1rem 1.25rem; background:linear-gradient(135deg,#f0fdf4,#e8f5e9); border-left:4px solid #1eb349; border-radius:0 12px 12px 0;">
                                Cari berbagai macam dari pilihan terlengkap <strong>{{ $catName }}</strong>. Temukan {{ $catName }} terbaik, termurah, dan berkualitas tinggi hanya di <strong>BUYLE.ID</strong>, marketplace produk dan jasa digital terpercaya untuk para Konten Kreator dan Freelancer Indonesia.
                            </p>

                            @if($service->short_desc)
                            <p><b>{{ $service->short_desc }}</b></p>
                            @endif
                            {!! $service->description ?? 'Belum ada deskripsi mendetail.' !!}

                            {{-- Poster Landscape Banner (og_image) --}}
                            @if(!empty($service->og_image))
                                <div style="margin-top: 1.5rem; margin-bottom: 1.5rem; width: 100%; aspect-ratio: 16/9; border-radius: 14px; overflow: hidden; background: #F1F5F9; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                                    <img src="{{ asset('storage/' . $service->og_image) }}" alt="{{ $service->name }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                </div>
                            @endif

                            {{-- Security Notice Paragraph --}}
                            <p style="font-size:0.825rem; color:#475569; line-height:1.6; margin-top:1.75rem; padding:0.875rem 1.25rem; background:#FFFBEB; border:1px solid #FDE68A; border-radius:10px; display:flex; align-items:flex-start; gap:0.65rem;">
                                <svg width="18" height="18" fill="none" stroke="#D97706" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                                <span>
                                    <strong style="color:#B45309;">Jaminan Transaksi Aman:</strong> Untuk transaksi yang aman dan terlindungi, selalu lakukan pembayaran melalui platform resmi <strong>buyle.id</strong>. Kerugian akibat transaksi atau pembayaran di luar platform menjadi tanggung jawab pribadi.
                                </span>
                            </p>
                        </div>
                    </div>
                    <div id="tab-creator" class="pd-tab-content" style="display:none;">
                        @if(isset($service->seller) && $service->seller)
                            @php $seller = $service->seller; $cp = $seller->creatorProfile; @endphp
                            <div style="display:flex; gap:1.5rem; align-items:flex-start;">
                                @if($seller->avatar)
                                    <img src="{{ asset('storage/'.$seller->avatar) }}" alt="{{ $seller->name }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
                                @else
                                    <div style="width:80px;height:80px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#64748b;font-weight:600;">{{ strtoupper(substr($seller->name,0,2)) }}</div>
                                @endif
                                <div>
                                    <h3 style="margin:0 0 0.5rem; font-size:1.2rem;">{{ optional($cp)->store_name ?: $seller->name }}</h3>
                                    <p style="margin:0 0 1rem; color:var(--text-muted); font-size:0.95rem; line-height:1.6;">
                                        {{ optional($cp)->store_description ?: 'Creator ini belum menuliskan deskripsi profilnya.' }}
                                    </p>
                                    <a href="{{ route('store.show', optional($cp)->store_slug ?? '#') }}" class="pd-btn pd-btn-outline" style="height:38px;font-size:0.85rem;">Kunjungi Profil Lengkap</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: Creator Card --}}
        <div class="pd-sidebar-col">
            @php 
                $seller = $service->seller; 
                $cp = $seller?->creatorProfile; 
                $displaySellerName = optional($cp)->store_name ?: ($seller?->name ?? ($settings['site_name'] ?? 'buyle.id Official'));
                $displayPhone = $seller?->phone ?? ($wa?->phone_number ?? '');
                $bannerUrl = optional($cp)->store_banner_1 ? asset('storage/'.$cp->store_banner_1) : null;
            @endphp
            <div class="pd-creator-card" style="background:#fff; border-radius:24px; overflow:hidden; border: 1.5px solid var(--border); box-shadow:0 8px 30px rgba(0,0,0,0.06);">
                @php
                    $isVerified = $cp?->is_verified ?? false;
                    $tierData = $cp ? $cp->getTierInfo() : ['badge' => 'Perintis', 'header_bg' => 'linear-gradient(135deg, #1eb349 0%, #a5cf37 100%)', 'icon_svg' => ''];
                    $ratingStats = $cp ? $cp->getRatingStats() : ['rating' => '5.0', 'count' => 0];
                @endphp
                <!-- Banner Top Header matching Image 2 reference design -->
                <div style="width:100%; height:44px; background:{{ $tierData['header_bg'] ?? 'linear-gradient(135deg, #1eb349 0%, #a5cf37 100%)' }}; padding: 0 1rem; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 5px; color: #ffffff; font-weight: 700; font-size: 0.75rem; white-space: nowrap;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#facc15" stroke="#facc15" stroke-width="1"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-5.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                        <span>Verified Creator</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px; color: #ffffff; font-weight: 700; font-size: 0.75rem; white-space: nowrap;">
                        {!! $tierData['icon_svg'] ?? '<svg width="14" height="14" viewBox="0 0 24 24" fill="#facc15"><path d="M12 2l2.4 5.3 5.8.5-4.4 3.9 1.3 5.6-5.1-3-5.1 3 1.3-5.6-4.4-3.9 5.8-.5z"/></svg>' !!}
                        <span>{{ $tierData['badge'] }}</span>
                    </div>
                </div>

                <!-- Avatar & Rating Pill: overlap HANYA SEDIKIT di bawah green bar -->
                <div style="margin-top:-18px; display:flex; justify-content:center; position:relative; z-index:2;">
                    <div style="position:relative; display:inline-block;">
                        @if($seller?->avatar)
                            <img src="{{ asset('storage/'.$seller->avatar) }}" style="width:72px; height:72px; border-radius:50%; border:3px solid #fff; object-fit:cover; background:#fff; box-shadow:0 4px 16px rgba(0,0,0,0.12); display:block;">
                        @else
                            <div style="width:72px; height:72px; border-radius:50%; border:3px solid #fff; display:flex; align-items:center; justify-content:center; font-size:1.4rem; font-weight:800; color:#1eb349; background:#e7f0e7; box-shadow:0 4px 16px rgba(0,0,0,0.12);">
                                {{ strtoupper(substr($displaySellerName, 0, 2)) }}
                            </div>
                        @endif
                        {{-- Rating pill badge top-right of avatar --}}
                        <div style="position:absolute; top:-4px; right:-20px; background:linear-gradient(135deg, #1eb349, #a5cf37); color:#fff; font-size:0.73rem; font-weight:800; padding:0.2rem 0.55rem; border-radius:999px; display:flex; align-items:center; gap:3px; box-shadow:0 2px 8px rgba(0,0,0,0.18); white-space:nowrap;">
                            <span>{{ $ratingStats['rating'] }}</span>
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="#facc15" stroke="#facc15"><path d="M12 2l2.4 5.3 5.8.5-4.4 3.9 1.3 5.6-5.1-3-5.1 3 1.3-5.6-4.4-3.9 5.8-.5z"/></svg>
                        </div>
                    </div>
                </div>
                
                <div style="text-align:center; padding: 0.85rem 1.25rem 1.25rem;">
                    <div style="font-size:1.25rem; font-weight:800; color:var(--text-main); margin-bottom:0.25rem; font-family: var(--font, 'Montserrat', sans-serif);">
                        {{ $displaySellerName }}
                    </div>

                    {{-- Bio / Deskripsi Creator --}}
                    <div style="font-size:0.85rem; color:var(--text-muted); line-height:1.4; margin-bottom:0.75rem; text-align:center;">
                        {{ optional($cp)->store_description ?: 'Digital Agency Pengusaha Indonesia' }}
                    </div>

                    {{-- Status Keaktifan Realtime --}}
                    <div style="font-size:0.8rem; margin-top:0.75rem; margin-bottom:1.25rem;">
                        @if($seller && $seller->last_seen_at && $seller->last_seen_at->gt(now()->subMinutes(10)))
                            <span style="color: #1eb349; font-weight: 600; display:inline-flex; align-items:center; gap:5px;">
                                <span style="width:8px; height:8px; background:#1eb349; border-radius:50%; display:inline-block;"></span> Online
                            </span>
                        @else
                            <span style="color: var(--text-muted);">
                                Aktif {{ ($seller && $seller->last_seen_at) ? $seller->last_seen_at->diffForHumans() : 'baru saja' }}
                            </span>
                        @endif
                    </div>
                    
                    <a href="{{ $cp?->store_slug ? route('store.show', $cp->store_slug) : (isset($sellerUrl) ? $sellerUrl : '#') }}" class="pd-btn pd-btn-primary" style="width:100%; height:44px; border-radius:99px; font-size:0.85rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(30,179,73,0.25);">
                        Lihat Profil Creator
                    </a>
                </div>
            </div>

            {{-- BANNER IKLAN SIDEBAR (Mendukung Rasio 4:3, 3:4 & Gambar Utuh) --}}
            @php
                $ad1Img = $settings['ad_product_sidebar_1_image'] ?? null;
                $ad1Url = $settings['ad_product_sidebar_1_url'] ?? null;
                $ad2Img = $settings['ad_product_sidebar_2_image'] ?? null;
                $ad2Url = $settings['ad_product_sidebar_2_url'] ?? null;
                $waContactUrl = isset($wa) && $wa->phone_number ? 'https://wa.me/'.$wa->phone_number.'?text='.urlencode('Halo Admin buyle.id, saya tertarik untuk pasang iklan banner di halaman produk.') : route('contact');
            @endphp

            {{-- Banner 1 (Atas) --}}
            @if(!empty($ad1Img))
                <div style="border-radius:16px; overflow:hidden; border:1.5px solid var(--border); background:#fff; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                    <a href="{{ $ad1Url ?: 'javascript:void(0)' }}" {{ $ad1Url ? 'target="_blank" rel="noopener noreferrer"' : '' }} style="display:block; width:100%; overflow:hidden;">
                        <img src="{{ asset('storage/'.$ad1Img) }}" alt="Iklan Banner 1" style="width:100%; height:auto; display:block; object-fit:contain; transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    </a>
                </div>
            @else
                <a href="{{ $waContactUrl }}" target="_blank" style="display:flex; flex-direction:column; align-items:center; justify-content:center; width:100%; min-height:180px; border-radius:16px; border:2px dashed #CBD5E1; background:#F8FAFC; text-decoration:none; color:#64748B; padding:1.25rem 1rem; text-align:center; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--primary)';this.style.background='#F0FDF4';this.style.color='var(--primary)'" onmouseout="this.style.borderColor='#CBD5E1';this.style.background='#F8FAFC';this.style.color='#64748B'">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="margin-bottom:0.4rem;"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    <span style="font-size:0.8rem; font-weight:700;">Space Iklan Tersedia</span>
                    <span style="font-size:0.7rem; color:#94A3B8; margin-top:2px;">Pasang Banner Iklan Disini &rsaquo;</span>
                </a>
            @endif

            {{-- Banner 2 (Bawah) --}}
            @if(!empty($ad2Img))
                <div style="border-radius:16px; overflow:hidden; border:1.5px solid var(--border); background:#fff; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                    <a href="{{ $ad2Url ?: 'javascript:void(0)' }}" {{ $ad2Url ? 'target="_blank" rel="noopener noreferrer"' : '' }} style="display:block; width:100%; overflow:hidden;">
                        <img src="{{ asset('storage/'.$ad2Img) }}" alt="Iklan Banner 2" style="width:100%; height:auto; display:block; object-fit:contain; transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                    </a>
                </div>
            @else
                <a href="{{ $waContactUrl }}" target="_blank" style="display:flex; flex-direction:column; align-items:center; justify-content:center; width:100%; min-height:180px; border-radius:16px; border:2px dashed #CBD5E1; background:#F8FAFC; text-decoration:none; color:#64748B; padding:1.25rem 1rem; text-align:center; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--primary)';this.style.background='#F0FDF4';this.style.color='var(--primary)'" onmouseout="this.style.borderColor='#CBD5E1';this.style.background='#F8FAFC';this.style.color='#64748B'">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" style="margin-bottom:0.4rem;"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                    <span style="font-size:0.8rem; font-weight:700;">Space Iklan Tersedia</span>
                    <span style="font-size:0.7rem; color:#94A3B8; margin-top:2px;">Pasang Banner Iklan Disini &rsaquo;</span>
                </a>
            @endif
        </div>

    </div>

    {{-- MOBILE STICKY BAR --}}
    @if($service->price > 0)
    <div class="pd-sticky-bar">
        <button type="submit" form="pd-cart-form" name="action" value="cart" class="pd-btn pd-btn-outline"
            @if($service->type !== 'service' && !is_null($service->stock) && $service->stock <= 0) disabled @endif>
            Keranjang
        </button>
        <button type="submit" form="pd-cart-form" name="action" value="buy" class="pd-btn pd-btn-primary"
            @if($service->type !== 'service' && !is_null($service->stock) && $service->stock <= 0) disabled @endif>
            Beli Langsung
        </button>
    </div>
    @endif

    {{-- 4. RELATED (Lainnya dari [namacreator]) --}}
    @if(isset($related) && $related->count() > 0)
    <div style="max-width:1200px; margin: 0 auto 4rem; padding: 0 1rem;">
        @php
            $creatorName = optional($service->seller->creatorProfile)->store_name ?: ($service->seller->name ?? ($sellerName ?? 'Creator'));
        @endphp
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
            <h2 class="pd-related-title" style="margin:0; font-size: 1.1rem; font-weight: 900; color: #0F172A;">
                Lainnya dari {{ $creatorName }}
            </h2>
            <a href="{{ $cp?->store_slug ? route('store.show', $cp->store_slug) : (isset($sellerUrl) ? $sellerUrl : '#') }}" style="font-size: 0.85rem; font-weight: 700; color: #1eb349; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                Lihat Semua <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14m-7-7l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="swipe-cards-container">
            @foreach($related as $r)
                @php
                    $rPrice = $r->sale_price > 0 && $r->sale_price < $r->price ? $r->sale_price : $r->effective_price;
                    $rOrigPrice = $r->sale_price > 0 && $r->sale_price < $r->price ? $r->price : null;
                    $rHasDiscount = !empty($rOrigPrice) && $rOrigPrice > $rPrice;
                    $rDiscountPct = $rHasDiscount ? round((($rOrigPrice - $rPrice) / $rOrigPrice) * 100) : 0;
                    $rImage = $r->image ? asset('storage/' . $r->image) : asset('images/buyle-og.png');
                    $rRating = ($r->rating && $r->rating > 0) ? number_format($r->rating, 1) : (($r->reviews_avg_rating && $r->reviews_avg_rating > 0) ? number_format($r->reviews_avg_rating, 1) : '5.0');
                @endphp
                <a href="{{ route('products.show', $r->slug) }}"
                    style="text-decoration: none; color: inherit; display: flex; flex-direction: column;"
                    class="article-card-swipe-item">
                    <div style="background: #ffffff; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 0.75rem; display: flex; flex-direction: column; height: 100%; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.02);"
                        class="article-card-box">
                        
                        {{-- Image 1:1 Aspect Ratio --}}
                        <div style="position: relative; width: 100%; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; background: #F1F5F9;">
                            <img src="{{ $rImage }}" alt="{{ $r->name }}"
                                style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s ease;"
                                class="article-banner-img">
                            
                            {{-- Discount Badge Top-Left --}}
                            @if($rHasDiscount)
                                <div style="position: absolute; top: 8px; left: 8px; background: #EF4444; color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 8px; font-family: 'Montserrat', sans-serif; z-index: 2; box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);">
                                    -{{ $rDiscountPct }}%
                                </div>
                            @endif
                        </div>

                        {{-- Card Body --}}
                        <div style="padding: 0.85rem 0.25rem 0.25rem; display: flex; flex-direction: column; flex: 1; justify-content: space-between;">
                            <div>
                                {{-- Rating & Verified Badge --}}
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; font-weight: 700; color: #1E293B; margin-bottom: 0.35rem;">
                                    <div style="display: flex; align-items: center; gap: 0.3rem;">
                                        <span style="color: #F59E0B; font-size: 0.95rem;">★</span>
                                        <span style="font-family: 'Montserrat', sans-serif;">{{ $rRating }}</span>
                                    </div>
                                    <div style="display: inline-flex; align-items: center; gap: 0.25rem; color: #0D9488; font-size: 0.78rem; font-weight: 700;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg>
                                        Verified
                                    </div>
                                </div>

                                {{-- Title --}}
                                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 800; color: #0F172A; margin: 0 0 0.4rem; line-height: 1.35; height: 2.7em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    {{ $r->name }}
                                </h3>
                            </div>

                            {{-- Footer Separator & Price CTA --}}
                            <div>
                                <div style="border-top: 1px solid #F1F5F9; margin: 0.65rem 0 0.75rem;"></div>
                                <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 0.35rem;">
                                    <div style="white-space: nowrap; min-width: 0; flex: 1;">
                                        @if($rHasDiscount)
                                            <span style="font-size: 0.68rem; color: #94A3B8; text-decoration: line-through; display: block; font-weight: 500; white-space: nowrap;">
                                                Rp{{ number_format($rOrigPrice, 0, ',', '.') }}
                                            </span>
                                        @endif
                                        <span style="font-family: 'Montserrat', sans-serif; font-size: 0.92rem; font-weight: 800; color: #16a34a; white-space: nowrap; display: block;">
                                            @if($rPrice > 0)
                                                Rp{{ number_format($rPrice, 0, ',', '.') }}
                                            @else
                                                <span style="color: #1eb349;">GRATIS</span>
                                            @endif
                                        </span>
                                    </div>
                                    <span style="background: linear-gradient(135deg, #1eb349, #7db928); color: #ffffff; padding: 0.35rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2rem; box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3); flex-shrink: 0; white-space: nowrap;" class="retarget-btn-cta">
                                        Lihat <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14m-7-7l7 7-7 7" /></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 5. OTHER RELATED PRODUCTS (Produk Terkait Lainnya dari Creator Lain) --}}
    @if(isset($otherRelated) && $otherRelated->count() > 0)
    <div style="max-width:1200px; margin: 0 auto 4rem; padding: 0 1rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
            <h2 class="pd-related-title" style="margin:0; font-size: 1.1rem; font-weight: 900; color: #0F172A;">
                Produk Terkait Lainnya
            </h2>
            <a href="{{ route('products') }}" style="font-size: 0.85rem; font-weight: 700; color: #1eb349; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                Lihat Semua <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14m-7-7l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="swipe-cards-container">
            @foreach($otherRelated as $r)
                @php
                    $rPrice = $r->sale_price > 0 && $r->sale_price < $r->price ? $r->sale_price : $r->effective_price;
                    $rOrigPrice = $r->sale_price > 0 && $r->sale_price < $r->price ? $r->price : null;
                    $rHasDiscount = !empty($rOrigPrice) && $rOrigPrice > $rPrice;
                    $rDiscountPct = $rHasDiscount ? round((($rOrigPrice - $rPrice) / $rOrigPrice) * 100) : 0;
                    $rImage = $r->image ? asset('storage/' . $r->image) : asset('images/buyle-og.png');
                    $rRating = ($r->rating && $r->rating > 0) ? number_format($r->rating, 1) : (($r->reviews_avg_rating && $r->reviews_avg_rating > 0) ? number_format($r->reviews_avg_rating, 1) : '5.0');
                @endphp
                <a href="{{ route('products.show', $r->slug) }}"
                    style="text-decoration: none; color: inherit; display: flex; flex-direction: column;"
                    class="article-card-swipe-item">
                    <div style="background: #ffffff; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 0.75rem; display: flex; flex-direction: column; height: 100%; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.02);"
                        class="article-card-box">
                        
                        {{-- Image 1:1 Aspect Ratio --}}
                        <div style="position: relative; width: 100%; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; background: #F1F5F9;">
                            <img src="{{ $rImage }}" alt="{{ $r->name }}"
                                style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s ease;"
                                class="article-banner-img">
                            
                            {{-- Discount Badge Top-Left --}}
                            @if($rHasDiscount)
                                <div style="position: absolute; top: 8px; left: 8px; background: #EF4444; color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 8px; font-family: 'Montserrat', sans-serif; z-index: 2; box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);">
                                    -{{ $rDiscountPct }}%
                                </div>
                            @endif
                        </div>

                        {{-- Card Body --}}
                        <div style="padding: 0.85rem 0.25rem 0.25rem; display: flex; flex-direction: column; flex: 1; justify-content: space-between;">
                            <div>
                                {{-- Rating & Verified Badge --}}
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; font-weight: 700; color: #1E293B; margin-bottom: 0.35rem;">
                                    <div style="display: flex; align-items: center; gap: 0.3rem;">
                                        <span style="color: #F59E0B; font-size: 0.95rem;">★</span>
                                        <span style="font-family: 'Montserrat', sans-serif;">{{ $rRating }}</span>
                                    </div>
                                    <div style="display: inline-flex; align-items: center; gap: 0.25rem; color: #0D9488; font-size: 0.78rem; font-weight: 700;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg>
                                        Verified
                                    </div>
                                </div>

                                {{-- Title --}}
                                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 800; color: #0F172A; margin: 0 0 0.4rem; line-height: 1.35; height: 2.7em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    {{ $r->name }}
                                </h3>
                            </div>

                            {{-- Footer Separator & Price CTA --}}
                            <div>
                                <div style="border-top: 1px solid #F1F5F9; margin: 0.65rem 0 0.75rem;"></div>
                                <div style="display: flex; align-items: flex-end; justify-content: space-between; gap: 0.35rem;">
                                    <div style="white-space: nowrap; min-width: 0; flex: 1;">
                                        @if($rHasDiscount)
                                            <span style="font-size: 0.68rem; color: #94A3B8; text-decoration: line-through; display: block; font-weight: 500; white-space: nowrap;">
                                                Rp{{ number_format($rOrigPrice, 0, ',', '.') }}
                                            </span>
                                        @endif
                                        <span style="font-family: 'Montserrat', sans-serif; font-size: 0.92rem; font-weight: 800; color: #16a34a; white-space: nowrap; display: block;">
                                            @if($rPrice > 0)
                                                Rp{{ number_format($rPrice, 0, ',', '.') }}
                                            @else
                                                <span style="color: #1eb349;">GRATIS</span>
                                            @endif
                                        </span>
                                    </div>
                                    <span style="background: linear-gradient(135deg, #1eb349, #7db928); color: #ffffff; padding: 0.35rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2rem; box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3); flex-shrink: 0; white-space: nowrap;" class="retarget-btn-cta">
                                        Lihat <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14m-7-7l7 7-7 7" /></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
function switchTab(tab) {
    const descTab = document.getElementById('tab-desc');
    const creatorTab = document.getElementById('tab-creator');
    const btnDesc = document.getElementById('tabBtn-desc');
    const btnCreator = document.getElementById('tabBtn-creator');

    if (tab === 'desc') {
        if (descTab) descTab.style.display = 'block';
        if (creatorTab) creatorTab.style.display = 'none';
        if (btnDesc) {
            btnDesc.style.color = 'var(--text-main)';
            btnDesc.style.borderBottom = '3px solid var(--primary)';
        }
        if (btnCreator) {
            btnCreator.style.color = 'var(--text-muted)';
            btnCreator.style.borderBottom = '3px solid transparent';
        }
    } else {
        if (descTab) descTab.style.display = 'none';
        if (creatorTab) creatorTab.style.display = 'block';
        if (btnDesc) {
            btnDesc.style.color = 'var(--text-muted)';
            btnDesc.style.borderBottom = '3px solid transparent';
        }
        if (btnCreator) {
            btnCreator.style.color = 'var(--text-main)';
            btnCreator.style.borderBottom = '3px solid var(--primary)';
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });

    // Auto-resolve city/province name from ID if name is not yet saved in DB
    const locBox = document.getElementById('creator-location-box');
    if (locBox) {
        const provId = locBox.getAttribute('data-prov-id');
        const cityId = locBox.getAttribute('data-city-id');
        const provName = locBox.getAttribute('data-prov-name');
        const cityName = locBox.getAttribute('data-city-name');
        const locText = document.getElementById('creator-location-text');

        if ((!provName || !cityName) && (provId || cityId)) {
            const apiBase = "https://www.emsifa.com/api-wilayah-indonesia/api";
            let cName = cityName || '', pName = provName || '';
            
            const promises = [];
            if (provId && !pName) {
                promises.push(fetch(`${apiBase}/provinces.json`).then(r => r.json()).then(provinces => {
                    const found = provinces.find(p => p.id == provId);
                    if (found) pName = found.name;
                }).catch(() => {}));
            }
            if (provId && cityId && !cName) {
                promises.push(fetch(`${apiBase}/regencies/${provId}.json`).then(r => r.json()).then(cities => {
                    const found = cities.find(c => c.id == cityId);
                    if (found) cName = found.name;
                }).catch(() => {}));
            }
            
            Promise.all(promises).then(() => {
                if (cName && pName) locText.textContent = `${cName}, ${pName}`;
                else if (cName) locText.textContent = cName;
                else if (pName) locText.textContent = pName;
            });
        }
    }

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

@include('partials.report_modal', ['reportType' => 'product', 'targetName' => $service->name])
@endsection
