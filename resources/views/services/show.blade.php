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
}
.pd-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: 0.2s; }
.pd-breadcrumb a:hover { color: var(--primary); }
.pd-breadcrumb span.cur { color: var(--text-main); font-weight: 500; }

/* ─── CONTAINERS ─── */
.pd-container {
    max-width: 1200px; margin: 0 auto 1.5rem;
    background: #fff; border-radius: 0 0 20px 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}

/* ─── PRODUCT BLOCK ─── */
.pd-product-block {
    display: flex; padding: 1.5rem; gap: 2rem;
}
@media (max-width: 768px) {
    .pd-product-block { flex-direction: column; padding: 1rem; gap: 1rem; }
}

/* ── GALLERY (Left) ── */
.pd-gallery { width: 450px; flex-shrink: 0; }
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
.pd-price-main { font-size: 1.8rem; font-weight: 600; background: linear-gradient(135deg, #1eb349, #a5cf37); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.pd-discount { background: linear-gradient(135deg, #1eb349, #a5cf37); color: #fff; font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.03em; }

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
    .pd-seller-block { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
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
.pd-seller-actions { display: flex; gap: 0.5rem; }
.pd-seller-btn {
    padding: 0.35rem 0.75rem; border-radius: 2px; font-size: 0.85rem; font-weight: 500;
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
.pd-seller-stat { display: flex; align-items: center; gap: 0.5rem; }
.pd-seller-stat span { color: var(--primary); font-weight: 500; }


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

/* Description Content */
.pd-desc-content { font-size: 0.95rem; color: var(--text-main); line-height: 1.8; white-space: pre-wrap; }


/* ─── MOBILE STICKY BAR ─── */
.pd-sticky-bar { display: none; }
@media (max-width: 768px) {
    .pd-actions { display: none; }
    .pd-sticky-bar {
        display: flex; align-items: center; gap: 0.5rem;
        position: fixed; bottom: 0; left: 0; right: 0;
        background: #fff; padding: 0.75rem 1rem; border-top: 1px solid var(--border);
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05); z-index: 50;
    }
    .pd-sticky-bar .pd-btn { flex: 1; height: 42px; font-size: 0.9rem; }
}

/* ─── RELATED ─── */
.pd-related-title { font-size: 1rem; font-weight: 500; color: var(--text-muted); text-transform: uppercase; margin-bottom: 1rem; }
.pd-related-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.75rem; }
@media (max-width: 992px) { .pd-related-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px) { .pd-related-grid { grid-template-columns: repeat(2, 1fr); } }
.pd-related-card {
    background: #fff; text-decoration: none; color: inherit;
    border-radius: 0 0 16px 16px; overflow: hidden; border: 1px solid var(--border); transition: 0.2s;
}
.pd-related-card:hover { border-color: var(--primary); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(30,179,73,0.1); }
.pd-related-img { width: 100%; aspect-ratio: 1/1; background: var(--bg-light); }
.pd-related-img img { width: 100%; height: 100%; object-fit: cover; }
.pd-related-body { padding: 0.75rem; }
.pd-related-name { font-size: 0.85rem; font-weight: 400; color: var(--text-main); margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3; }
.pd-related-price { font-size: 1rem; font-weight: 600; background: linear-gradient(135deg, #1eb349, #a5cf37); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

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

    {{-- 1. PRODUCT BLOCK --}}
    <div class="pd-container pd-product-block">

        {{-- LEFT COLUMN: Gallery --}}
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
                    <div class="swiper-slide pd-tiktok-slide">
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

        {{-- RIGHT COLUMN: Info --}}
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
                    <div class="pd-discount">{{ round((($service->price - $service->sale_price)/$service->price)*100) }}% OFF</div>
                @else
                    <div class="pd-price-main">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                @endif
            </div>

            {{-- Pengiriman / Garansi Placeholder --}}
            <div class="pd-attr-row">
                <div class="pd-attr-label">Pengiriman</div>
                <div class="pd-attr-content">
                    <div>
                        <div style="display:flex; align-items:center; gap:4px; color:var(--text-main);">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 10h14l-1.5 10h-11z"/><path d="M9 10V5a3 3 0 0 1 6 0v5"/></svg>
                            <b>Garansi Tepat Waktu</b>
                        </div>
                        <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Dapatkan voucher s/d Rp10.000 jika pesanan terlambat.</div>
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

                <div class="pd-actions">
                    <button type="submit" name="action" value="cart" class="pd-btn pd-btn-outline"
                        @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                        Masukkan Keranjang
                    </button>
                    <button type="submit" name="action" value="buy" class="pd-btn pd-btn-primary"
                        @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                        Beli Sekarang
                    </button>
                </div>
            </form>
            @else
            <div class="pd-price-box">
                <div class="pd-price-main">Konsultasi Penawaran</div>
            </div>
            <a href="javascript:void(0)" onclick="openOrderModal('Produk: {{ addslashes($service->name) }}')" class="pd-btn pd-btn-primary" style="margin-top:2rem;">
                Tanya via WhatsApp
            </a>
            @endif

        </div>
    </div>


    {{-- 2. SELLER BLOCK --}}
    @if(isset($service->seller) && $service->seller)
    @php $seller = $service->seller; $cp = $seller->creatorProfile; @endphp
    <div class="pd-container pd-seller-block">
        <div class="pd-seller-left">
            @if($seller->avatar)
                <img src="{{ asset('storage/'.$seller->avatar) }}" class="pd-seller-ava" alt="{{ $seller->name }}">
            @else
                <div class="pd-seller-ava">{{ strtoupper(substr($seller->name,0,2)) }}</div>
            @endif
            <div class="pd-seller-info">
                <div class="pd-seller-name">{{ optional($cp)->store_name ?: $seller->name }}</div>
                <div class="pd-seller-sub">Toko Aktif</div>
                <div class="pd-seller-actions">
                    <a href="javascript:void(0)" class="pd-seller-btn pd-seller-btn-outline">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10z"></path></svg>
                        Chat Sekarang
                    </a>
                    <a href="{{ route('store.show', optional($cp)->store_slug ?? '#') }}" class="pd-seller-btn pd-seller-btn-gray">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        Kunjungi Toko
                    </a>
                </div>
            </div>
        </div>
        <div class="pd-seller-right">
            <div class="pd-seller-stat">Penilaian <span>10,4RB</span></div>
            <div class="pd-seller-stat">Persentase Chat Dibalas <span>81%</span></div>
            <div class="pd-seller-stat">Bergabung <span>6 tahun lalu</span></div>
            <div class="pd-seller-stat">Produk <span>56</span></div>
            <div class="pd-seller-stat">Waktu Chat Dibalas <span>hitungan jam</span></div>
            <div class="pd-seller-stat">Pengikut <span>1,6RB</span></div>
        </div>
    </div>
    @endif


    {{-- 3. DETAILS BLOCK --}}
    <div class="pd-container pd-details-block">
        
        @if(is_array($service->specifications) && count($service->specifications) > 0)
        <div class="pd-section-title">SPESIFIKASI PRODUK</div>
        <table class="pd-specs">
            @foreach($service->specifications as $spec)
            <tr>
                <td>{{ $spec['key'] }}</td>
                <td>{{ $spec['value'] }}</td>
            </tr>
            @endforeach
        </table>
        @endif

        <div class="pd-section-title">DESKRIPSI PRODUK</div>
        <div class="pd-desc-content">
            @if($service->short_desc)
            <p><b>{{ $service->short_desc }}</b></p>
            @endif
            {!! $service->description ?? 'Belum ada deskripsi mendetail.' !!}
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
    <div style="max-width:1200px; margin: 0 auto 4rem;">
        <div class="pd-related-title">Produk Lain Dari Toko Ini</div>
        <div class="pd-related-grid">
            @foreach($related as $r)
            <a href="{{ route_locale('products.show', $r->slug) }}" class="pd-related-card">
                <div class="pd-related-img">
                    <img src="{{ $r->image_url }}" alt="{{ $r->name }}" loading="lazy">
                </div>
                <div class="pd-related-body">
                    <div class="pd-related-name">{{ $r->name }}</div>
                    <div class="pd-related-price">Rp{{ number_format($r->sale_price ?? $r->price, 0, ',', '.') }}</div>
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
