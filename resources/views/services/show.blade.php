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
    display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;
    border-radius: 0 8px 8px 0;
}
.pd-price-old { font-size: 1rem; color: var(--text-muted); text-decoration: line-through; }
.pd-price-main { font-size: 1.6rem; font-weight: 700; background: linear-gradient(135deg, #1eb349, #a5cf37); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; white-space: nowrap; }
.pd-discount { background: linear-gradient(135deg, #1eb349, #a5cf37); color: #fff; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.03em; white-space: nowrap; flex-shrink: 0; }

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
        position: fixed; bottom: 85px; left: 0; right: 0;
        background: #fff; padding: 0.75rem 1rem; border-top: 1px solid var(--border);
        box-shadow: 0 -4px 15px rgba(0,0,0,0.05); z-index: 40;
    }
    .pd-sticky-bar .pd-btn { flex: 1; height: 42px; font-size: 0.9rem; }
    .pd-breadcrumb { padding: 1rem 1rem 0.5rem; }
}

/* ─── RELATED ─── */
.pd-related-title { font-size: 1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1rem; font-family: 'Montserrat', sans-serif; }
.pd-related-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; }
@media (max-width: 768px) { 
    .pd-related-grid { 
        display: flex; overflow-x: auto; scroll-snap-type: x mandatory; 
        padding-bottom: 1rem; -webkit-overflow-scrolling: touch; gap: 0.75rem;
    }
    .pd-related-grid > * { flex: 0 0 160px; scroll-snap-align: start; }
}

/* Card Design Matching Landing Page */
.cv-promo-card {
    display: flex; flex-direction: column; background: #fff;
    border: 1.5px solid #F1F5F9; border-radius: 14px; overflow: hidden; height: 100%;
    text-decoration: none; transition: border-color .25s, box-shadow .25s, transform .25s;
}
.cv-promo-card:hover { border-color: var(--primary); box-shadow: 0 8px 24px rgba(30,179,73,.1); transform: translateY(-3px); }
.cv-promo-card-img { width: 100%; aspect-ratio: 1/1; object-fit: cover; }
.cv-promo-card-body { padding: 1rem; display: flex; flex-direction: column; flex: 1; }
.cv-promo-card-badge { align-self: flex-start; font-size: 0.65rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 6px; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; background: #DBEAFE; color: #2563EB; }
.cv-promo-card-badge.service { background: #FEF3C7; color: #D97706; }
.cv-promo-card-name { font-size: 0.85rem; font-weight: 600; color: #1E293B; line-height: 1.4; margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.cv-promo-card-price { font-size: 0.95rem; font-weight: 700; color: #1E293B; margin-top: auto; }
.cv-promo-card-price-old { font-size: 0.7rem; color: #94A3B8; text-decoration: line-through; font-weight: 400; }
.cv-promo-card-discount { display: inline-block; font-size: 0.65rem; font-weight: 700; color: #DC2626; background: #FEE2E2; padding: 0.1rem 0.35rem; border-radius: 4px; }
.cv-promo-card-meta { display: flex; align-items: center; gap: 0.3rem; font-size: 0.75rem; color: #94A3B8; margin-top: 0.25rem; }
.cv-promo-card-star { color: #FBBF24; }

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

                    <div class="pd-stats">
                        @if($service->rating > 0)
                        <div class="pd-stars">
                            <span class="pd-stat-val" style="color:var(--primary); border-color:var(--primary);">{{ number_format($service->rating, 1) }}</span>
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                        <div class="pd-stat-sep"></div>
                        @endif
                        
                        @if($service->rating > 0)
                        <div><span class="pd-stat-val">156</span> Penilaian</div>
                        <div class="pd-stat-sep"></div>
                        @endif

                        @if($service->sold_count > 0)
                        <div>Terjual <span style="color:var(--text-main); font-weight:500;">{{ $service->sold_count >= 1000 ? number_format($service->sold_count/1000, 1, ',', '').'RB' : $service->sold_count }}</span></div>
                        @endif
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
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $service->id }}">

                        <div class="pd-attr-row" style="align-items:center;">
                            <div class="pd-attr-label">Kuantitas</div>
                            <div class="pd-attr-content">
                                <div class="pd-qty-ctrl">
                                    <button type="button" class="pd-qty-btn" onclick="document.getElementById('qty_input').stepDown()">−</button>
                                    <input type="number" id="qty_input" class="pd-qty-input" name="qty"
                                        value="{{ $service->min_order ?? 1 }}" min="{{ $service->min_order ?? 1 }}"
                                        @if($service->type !== 'service' && $service->stock > 0) max="{{ $service->stock }}" @endif
                                        @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                                    <button type="button" class="pd-qty-btn" onclick="document.getElementById('qty_input').stepUp()">+</button>
                                </div>
                                <div class="pd-stock">
                                    @if($service->type === 'service')
                                        Jasa / Layanan
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
                                @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                                Tambah Keranjang
                            </button>
                            <button type="submit" name="action" value="buy" class="pd-btn pd-btn-primary" style="flex:1;"
                                @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
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
                                $catName = $service->category?->name ?? $service->type_label ?? 'Produk Digital';
                            @endphp
                            <p style="font-size:0.9rem; color:#334155; line-height:1.8; margin-bottom:1.25rem; padding:1rem 1.25rem; background:linear-gradient(135deg,#f0fdf4,#e8f5e9); border-left:4px solid #1eb349; border-radius:0 12px 12px 0;">
                                Cari berbagai macam dari pilihan terlengkap <strong>{{ $catName }}</strong>. Temukan {{ $catName }} terbaik, termurah, dan berkualitas tinggi hanya di <strong>BUYLE.ID</strong>, marketplace produk dan jasa digital terpercaya untuk para Konten Kreator dan Freelancer Indonesia.
                            </p>

                            @if($service->short_desc)
                            <p><b>{{ $service->short_desc }}</b></p>
                            @endif
                            {!! $service->description ?? 'Belum ada deskripsi mendetail.' !!}

                            {{-- SEO Closing Paragraph --}}
                            <p style="font-size:0.85rem; color:#64748B; line-height:1.75; margin-top:1.75rem; padding:0.875rem 1.25rem; background:#f8fafc; border:1px dashed #CBD5E1; border-radius:10px;">
                                🛡️ <strong>Belanja aman di BUYLE.ID</strong> — Semua produk digital diverifikasi, transaksi terlindungi, dan layanan pelanggan siap membantu. Dapatkan <strong>{{ $service->name }}</strong> asli dan berlisensi langsung dari kreatornya. Tersedia pilihan {{ $catName }} terbaik, termurah, dan terlengkap hanya di <strong>buyle.id</strong>.
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
            <div class="pd-creator-card" style="background:#fff; border-radius:20px; overflow:hidden; border: 1.5px solid var(--border); box-shadow:0 4px 20px rgba(0,0,0,0.04);">
                <!-- Banner -->
                @if($bannerUrl)
                    <div style="width:100%; aspect-ratio:16/7; overflow:hidden; background:#f8fafc;">
                        <img src="{{ $bannerUrl }}" alt="{{ $displaySellerName }}" style="width:100%; height:100%; object-fit:cover; display:block;">
                    </div>
                @else
                    <div style="width:100%; height:95px; background:linear-gradient(135deg, var(--primary), #a5cf37);"></div>
                @endif

                <!-- Avatar -->
                <div style="margin-top:-38px; display:flex; justify-content:center; position:relative; z-index:2;">
                    @if($seller?->avatar)
                        <img src="{{ asset('storage/'.$seller->avatar) }}" style="width:76px; height:76px; border-radius:50%; border:3.5px solid #fff; object-fit:cover; background:#fff; box-shadow:0 4px 14px rgba(0,0,0,0.12);">
                    @else
                        <div style="width:76px; height:76px; border-radius:50%; border:3.5px solid #fff; display:flex; align-items:center; justify-content:center; font-size:1.6rem; font-weight:700; color:var(--primary); background:#e7f0e7; box-shadow:0 4px 14px rgba(0,0,0,0.12);">
                            {{ strtoupper(substr($displaySellerName, 0, 2)) }}
                        </div>
                    @endif
                </div>
                
                <div style="text-align:center; padding: 1rem 1.25rem 1.5rem;">
                    <div style="font-size:1.15rem; font-weight:700; color:var(--text-main); margin-bottom:0.4rem;">
                        {{ $displaySellerName }}
                    </div>

                    @if(optional($cp)->creator_type)
                        <div style="margin-bottom:0.6rem;">
                            <span style="display:inline-flex;align-items:center;gap:0.3rem;background:#F0FDF4;color:#15803D;font-size:0.75rem;font-weight:600;padding:0.2rem 0.6rem;border-radius:999px;border:1px solid #BBF7D0;">
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                {{ $cp->creator_type }}
                            </span>
                        </div>
                    @endif
                    
                    {{-- Lokasi Creator --}}
                    <div id="creator-location-box" style="font-size:0.85rem; color:var(--text-muted); margin-bottom:0.75rem; line-height:1.4; display:flex; align-items:center; justify-content:center; gap:4px;"
                         data-prov-id="{{ optional($cp)->province_id }}"
                         data-city-id="{{ optional($cp)->city_id }}"
                         data-prov-name="{{ optional($cp)->province_name }}"
                         data-city-name="{{ optional($cp)->city_name }}">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span id="creator-location-text">
                            @if(optional($cp)->city_name && optional($cp)->province_name)
                                {{ $cp->city_name }}, {{ $cp->province_name }}
                            @elseif(optional($cp)->city_name)
                                {{ $cp->city_name }}
                            @elseif(optional($cp)->province_name)
                                {{ $cp->province_name }}
                            @else
                                Indonesia
                            @endif
                        </span>
                    </div>

                    {{-- Bio / Profil Singkat --}}
                    @if(optional($cp)->store_description)
                    <div style="font-size:0.82rem; color:var(--text-muted); line-height:1.5; margin-bottom:0.75rem; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;">
                        {{ $cp->store_description }}
                    </div>
                    @endif
                    
                    {{-- Status Keaktifan Realtime --}}
                    <div style="font-size:0.82rem; margin-bottom:1.25rem;">
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
        <button type="button" class="pd-btn pd-btn-outline"
            onclick="document.querySelector('button[value=\'cart\']').click()"
            @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
            Keranjang
        </button>
        <button type="button" class="pd-btn pd-btn-primary"
            onclick="document.querySelector('button[value=\'buy\']').click()"
            @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
            Beli Langsung
        </button>
    </div>
    @endif

    {{-- 4. RELATED --}}
    @if($related->count() > 0)
    <div style="max-width:1200px; margin: 0 auto 4rem; padding: 0 1rem;">
        <div class="pd-related-title">Produk Lain Dari Creator Ini</div>
        <div class="pd-related-grid">
            @foreach($related as $r)
            @php
                $effPrice = ($r->sale_price > 0 && $r->sale_price < $r->price) ? $r->sale_price : $r->price;
                $discount = ($r->sale_price > 0 && $r->sale_price < $r->price)
                    ? round((($r->price - $r->sale_price)/$r->price)*100) : 0;
            @endphp
            <div>
                <a href="{{ route('products.show', $r->slug) }}" class="cv-promo-card">
                    @if($r->image)
                        <img src="{{ asset('storage/'.$r->image) }}" alt="{{ $r->name }}" class="cv-promo-card-img" loading="lazy">
                    @else
                        <div style="width:100%; aspect-ratio:1/1; display:flex; align-items:center; justify-content:center; background:#F1F5F9;">
                            <svg width="32" height="32" fill="none" stroke="#CBD5E1" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                    @endif
                    <div class="cv-promo-card-body">
                        <span class="cv-promo-card-badge {{ $r->type === 'service' ? 'service' : '' }}">
                            {{ $r->type === 'service' ? 'Jasa' : 'Produk' }}
                        </span>
                        <div class="cv-promo-card-name">{{ $r->name }}</div>
                        @if($r->price > 0)
                        <div>
                            @if($discount > 0)
                            <div class="cv-promo-card-price-old">Rp{{ number_format($r->price,0,',','.') }}</div>
                            @endif
                            <div style="display:flex;align-items:center;gap:.3rem;flex-wrap:wrap;">
                                <span class="cv-promo-card-price">Rp{{ number_format($effPrice,0,',','.') }}</span>
                                @if($discount > 0)
                                    <span class="cv-promo-card-discount">{{ $discount }}%</span>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($r->rating > 0 || $r->sold_count > 0)
                        <div class="cv-promo-card-meta">
                            @if($r->rating > 0)
                                <span class="cv-promo-card-star">★</span>
                                <span>{{ number_format($r->rating,1) }}</span>
                            @endif
                            @if($r->sold_count > 0)
                                <span>· {{ $r->sold_count >= 1000 ? number_format($r->sold_count/1000,1).'rb' : $r->sold_count }} {{ $r->type === 'service' ? 'dipesan' : 'terjual' }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </a>
            </div>
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

@endsection
