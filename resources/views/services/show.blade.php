@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

<style>
/* ══════════════════════════════════════
   PRODUCT SHOW — buyle.id
   Ultra Minimalist & Clean Layout
   Font: Montserrat 300-600
══════════════════════════════════════ */

*, *::before, *::after { box-sizing: border-box; }

.pd-page {
    font-family: 'Montserrat', sans-serif;
    background: #ffffff;
    min-height: 100vh;
    padding-top: 80px;
    color: #1E293B;
}

/* ── Accent Vars ── */
.pd-page {
    --primary: #1eb349;
    --primary-hover: #16a34a;
    --text-main: #0F172A;
    --text-muted: #64748B;
    --border: #F1F5F9;
    --bg-light: #F8FAFC;
}

/* ─── BREADCRUMB ─── */
.pd-breadcrumb {
    max-width: 1200px; margin: 0 auto;
    padding: 2rem 1.5rem 0;
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.8rem; font-weight: 400; color: var(--text-muted);
}
.pd-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: 0.2s; }
.pd-breadcrumb a:hover { color: var(--primary); }
.pd-breadcrumb span.cur { color: var(--text-main); font-weight: 500; }

/* ─── MAIN GRID ─── */
.pd-main {
    max-width: 1200px; margin: 1.5rem auto 0;
    display: grid;
    grid-template-columns: 460px 1fr;
    gap: 4rem;
    padding: 0 1.5rem 4rem;
    align-items: start;
}

/* ─── GALLERY ─── */
.pd-gallery {}
.pd-gallery-main {
    width: 100%; aspect-ratio: 1/1;
    border-radius: 12px; overflow: hidden;
    background: var(--bg-light);
    margin-bottom: 1rem; position: relative;
}
.pd-gallery-main img { width: 100%; height: 100%; object-fit: cover; }

.pd-gallery-main .swiper-button-next,
.pd-gallery-main .swiper-button-prev {
    width: 40px; height: 40px; background: rgba(255,255,255,0.9);
    border-radius: 50%; color: var(--text-main);
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.pd-gallery-main .swiper-button-next::after,
.pd-gallery-main .swiper-button-prev::after { font-size: 14px; font-weight: 300; }

.pd-thumbs { display: flex; gap: 0.75rem; }
.pd-thumb-item {
    width: 72px; height: 72px; border-radius: 8px;
    overflow: hidden; border: 1px solid transparent;
    cursor: pointer; opacity: 0.6; transition: 0.2s; flex-shrink: 0;
    background: var(--bg-light);
}
.pd-thumb-item.swiper-slide-thumb-active { opacity: 1; border-color: var(--primary); }
.pd-thumb-item img { width: 100%; height: 100%; object-fit: cover; }

/* ─── PRODUCT INFO ─── */
.pd-info {}

.pd-title {
    font-size: 2rem; font-weight: 500; color: var(--text-main);
    line-height: 1.3; margin: 0 0 1rem; letter-spacing: -0.02em;
}

.pd-stats {
    display: flex; align-items: center; gap: 1rem;
    font-size: 0.85rem; color: var(--text-muted); margin-bottom: 2rem;
    padding-bottom: 1rem; border-bottom: 1px solid var(--border);
}
.pd-stars { display: flex; align-items: center; gap: 4px; color: #F59E0B; font-weight: 500; }
.pd-stat-sep { width: 1px; height: 14px; background: var(--border); }

/* SELLER INFO - REQUIRED & PROMINENT */
.pd-seller-block {
    display: flex; align-items: center; gap: 1rem;
    padding: 1.5rem; background: var(--bg-light);
    border-radius: 12px; margin-bottom: 2rem;
    text-decoration: none; color: inherit; transition: 0.2s;
    border: 1px solid transparent;
}
.pd-seller-block:hover { border-color: var(--primary); background: #ffffff; box-shadow: 0 4px 20px rgba(30,179,73,0.05); }
.pd-seller-ava {
    width: 56px; height: 56px; border-radius: 50%; object-fit: cover;
    background: #fff; border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; font-weight: 500; color: var(--primary); flex-shrink: 0;
}
.pd-seller-text { flex: 1; }
.pd-seller-name { font-size: 1.05rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem; }
.pd-seller-sub { font-size: 0.85rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem; }
.pd-seller-badge {
    background: rgba(30,179,73,0.1); color: var(--primary);
    padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
}

/* Price */
.pd-price-wrap { margin-bottom: 2rem; }
.pd-price-main { font-size: 2.25rem; font-weight: 600; color: var(--primary); line-height: 1; letter-spacing: -0.02em; }
.pd-price-old-wrap { display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem; }
.pd-price-old { font-size: 1rem; color: var(--text-muted); text-decoration: line-through; }
.pd-discount { background: #EF4444; color: #fff; font-size: 0.75rem; font-weight: 500; padding: 0.25rem 0.5rem; border-radius: 4px; }

/* Qty */
.pd-qty-wrap {
    display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem;
}
.pd-qty-label { font-size: 0.9rem; font-weight: 500; color: var(--text-muted); }
.pd-qty-ctrl {
    display: flex; align-items: center; border: 1px solid var(--border);
    border-radius: 8px; overflow: hidden;
}
.pd-qty-btn {
    width: 44px; height: 44px; border: none; background: #fff;
    font-size: 1.25rem; color: var(--text-muted); cursor: pointer; transition: 0.2s;
    display: flex; align-items: center; justify-content: center;
}
.pd-qty-btn:hover { background: var(--bg-light); color: var(--text-main); }
.pd-qty-input {
    width: 50px; height: 44px; border: none; border-left: 1px solid var(--border); border-right: 1px solid var(--border);
    text-align: center; font-size: 1rem; font-weight: 500; font-family: inherit; color: var(--text-main);
}
.pd-stock { font-size: 0.85rem; color: var(--text-muted); }

/* Actions */
.pd-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 3rem; }
.pd-btn {
    padding: 1rem; border-radius: 8px; font-size: 0.95rem; font-weight: 500; font-family: inherit;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    transition: 0.2s; border: none; text-decoration: none;
}
.pd-btn-outline { background: #fff; color: var(--primary); border: 1px solid var(--primary); }
.pd-btn-outline:hover { background: rgba(30,179,73,0.05); }
.pd-btn-primary { background: var(--primary); color: #fff; }
.pd-btn-primary:hover { background: var(--primary-hover); }
.pd-btn:disabled { opacity: 0.5; cursor: not-allowed; }

/* Description */
.pd-desc-title { font-size: 1.15rem; font-weight: 500; color: var(--text-main); margin-bottom: 1.25rem; }
.pd-desc-content { font-size: 0.95rem; color: var(--text-muted); line-height: 1.8; font-weight: 300; }
.pd-desc-content p { margin-bottom: 1rem; }
.pd-desc-content ul { padding-left: 1.5rem; margin-bottom: 1rem; }
.pd-desc-content h2, .pd-desc-content h3 { font-size: 1.1rem; font-weight: 500; color: var(--text-main); margin: 1.5rem 0 0.75rem; }

/* Specs */
.pd-specs { width: 100%; border-collapse: collapse; font-size: 0.9rem; margin-bottom: 3rem; }
.pd-specs td { padding: 1rem 0; border-bottom: 1px solid var(--border); }
.pd-specs td:first-child { width: 35%; color: var(--text-muted); font-weight: 300; }
.pd-specs td:last-child { color: var(--text-main); font-weight: 500; }

/* FAQ */
.pd-faq { margin-top: 3rem; }
.pd-faq-item { margin-bottom: 1.5rem; }
.pd-faq-q { font-size: 0.95rem; font-weight: 500; color: var(--text-main); margin-bottom: 0.5rem; }
.pd-faq-a { font-size: 0.9rem; color: var(--text-muted); line-height: 1.7; font-weight: 300; }

/* ─── RELATED ─── */
.pd-related { max-width: 1200px; margin: 0 auto; padding: 4rem 1.5rem; border-top: 1px solid var(--border); }
.pd-related-title { font-size: 1.25rem; font-weight: 500; color: var(--text-main); margin-bottom: 2rem; }
.pd-related-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
.pd-related-card { display: block; text-decoration: none; color: inherit; transition: 0.2s; }
.pd-related-card:hover { transform: translateY(-4px); }
.pd-related-img { width: 100%; aspect-ratio: 1/1; border-radius: 8px; overflow: hidden; background: var(--bg-light); margin-bottom: 1rem; }
.pd-related-img img { width: 100%; height: 100%; object-fit: cover; }
.pd-related-name { font-size: 0.9rem; font-weight: 400; color: var(--text-main); margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.pd-related-price { font-size: 1.05rem; font-weight: 500; color: var(--primary); }

/* ─── MOBILE STICKY BAR ─── */
.pd-sticky-bar { display: none; }

/* ─── RESPONSIVE ─── */
@media (max-width: 992px) {
    .pd-main { grid-template-columns: 1fr; gap: 3rem; }
    .pd-related-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
}

@media (max-width: 768px) {
    .pd-page { padding-top: 60px; }
    .pd-main { padding: 0 1.25rem 2rem; gap: 2rem; }
    .pd-related { padding: 3rem 1.25rem 6rem; }
    .pd-title { font-size: 1.5rem; }
    .pd-price-main { font-size: 1.75rem; }
    
    .pd-actions { display: none; }
    
    .pd-sticky-bar {
        display: flex; align-items: center; gap: 0.75rem;
        position: fixed; bottom: 0; left: 0; right: 0;
        background: #fff; padding: 1rem; border-top: 1px solid var(--border);
        box-shadow: 0 -4px 12px rgba(0,0,0,0.03); z-index: 50;
    }
    .pd-sticky-bar .pd-btn { flex: 1; padding: 0.875rem; font-size: 0.9rem; }
}
</style>

<div class="pd-page">

    {{-- Breadcrumb --}}
    <div class="pd-breadcrumb">
        <a href="{{ route_locale('home') }}">Beranda</a>
        <span>/</span>
        <a href="{{ route_locale('products') }}">Produk</a>
        <span>/</span>
        <span class="cur">{{ Str::limit($service->name, 40) }}</span>
    </div>

    {{-- Main Container --}}
    <div class="pd-main">

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
        </div>

        {{-- RIGHT COLUMN: Info --}}
        <div class="pd-info">

            <h1 class="pd-title">{{ $service->name }}</h1>

            <div class="pd-stats">
                @if($service->rating > 0)
                <div class="pd-stars">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    {{ number_format($service->rating, 1) }}
                </div>
                @endif
                @if($service->rating > 0 && $service->sold_count > 0)<div class="pd-stat-sep"></div>@endif
                @if($service->sold_count > 0)
                <div>Terjual <span style="color:var(--text-main); font-weight:500;">{{ $service->sold_count >= 1000 ? number_format($service->sold_count/1000, 1, ',', '').'RB' : $service->sold_count }}</span></div>
                @endif
            </div>

            {{-- SELLER INFO BLOCK --}}
            @if(isset($service->creator) && $service->creator)
            @php $creator = $service->creator; $cp = $creator->creatorProfile; @endphp
            <a href="{{ route('store.show', optional($cp)->store_slug ?? '#') }}" class="pd-seller-block">
                @if($creator->avatar)
                    <img src="{{ asset('storage/'.$creator->avatar) }}" class="pd-seller-ava" alt="{{ $creator->name }}">
                @else
                    <div class="pd-seller-ava">{{ strtoupper(substr($creator->name,0,2)) }}</div>
                @endif
                <div class="pd-seller-text">
                    <div class="pd-seller-name">{{ optional($cp)->store_name ?: $creator->name }}</div>
                    <div class="pd-seller-sub">
                        <span class="pd-seller-badge">Seller</span>
                        {{ optional($cp)->city_id ? 'Toko Terverifikasi' : 'Mitra buyle.id' }}
                    </div>
                </div>
                <div>
                    <svg width="20" height="20" fill="none" stroke="var(--text-muted)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                </div>
            </a>
            @endif

            {{-- PRICE --}}
            @if($service->price > 0)
            <div class="pd-price-wrap">
                @if($service->sale_price > 0 && $service->sale_price < $service->price)
                    <div class="pd-price-main">Rp{{ number_format($service->sale_price, 0, ',', '.') }}</div>
                    <div class="pd-price-old-wrap">
                        <span class="pd-discount">{{ round((($service->price - $service->sale_price)/$service->price)*100) }}% OFF</span>
                        <span class="pd-price-old">Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                    </div>
                @else
                    <div class="pd-price-main">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                @endif
            </div>

            {{-- CART FORM --}}
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $service->id }}">

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
                    <div class="pd-stock">
                        @if($service->type === 'service')
                            Jasa / Layanan
                        @elseif($service->stock > 0)
                            Sisa {{ $service->stock }}
                        @else
                            <span style="color:#EF4444;">Habis</span>
                        @endif
                    </div>
                </div>

                <div class="pd-actions">
                    <button type="submit" name="action" value="cart" class="pd-btn pd-btn-outline"
                        @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                        Tambah ke Keranjang
                    </button>
                    <button type="submit" name="action" value="buy" class="pd-btn pd-btn-primary"
                        @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                        Beli Langsung
                    </button>
                </div>
            </form>
            @else
            <div class="pd-price-wrap">
                <div class="pd-price-main" style="font-size:1.75rem;">Konsultasi Penawaran</div>
                <div class="pd-price-old-wrap" style="color:var(--text-muted); font-size:0.9rem;">
                    Harga menyesuaikan dengan kebutuhan spesifik Anda.
                </div>
            </div>
            <a href="javascript:void(0)" onclick="openOrderModal('Produk: {{ addslashes($service->name) }}')" class="pd-btn pd-btn-primary" style="margin-bottom:3rem; max-width:300px;">
                Tanya via WhatsApp
            </a>
            @endif


            {{-- SPECS --}}
            @if(is_array($service->specifications) && count($service->specifications) > 0)
            <div class="pd-desc-title">Spesifikasi</div>
            <table class="pd-specs">
                @foreach($service->specifications as $spec)
                <tr>
                    <td>{{ $spec['key'] }}</td>
                    <td>{{ $spec['value'] }}</td>
                </tr>
                @endforeach
            </table>
            @endif

            {{-- DESC --}}
            <div class="pd-desc-title">Deskripsi Produk</div>
            <div class="pd-desc-content">
                {!! $service->description ?? '<p>Belum ada deskripsi mendetail.</p>' !!}
            </div>

            {{-- FAQ --}}
            @php $faqs = is_array($service->faqs) && !empty($service->faqs) ? $service->faqs : []; @endphp
            @if(count($faqs) > 0)
            <div class="pd-faq">
                <div class="pd-desc-title">FAQ (Pertanyaan Umum)</div>
                @foreach($faqs as $f)
                <div class="pd-faq-item">
                    <div class="pd-faq-q">{{ $f['q'] ?? '' }}</div>
                    <div class="pd-faq-a">{{ $f['a'] ?? '' }}</div>
                </div>
                @endforeach
            </div>
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

    {{-- RELATED --}}
    @if($related->count() > 0)
    <div class="pd-related">
        <div class="pd-related-title">Produk Serupa</div>
        <div class="pd-related-grid">
            @foreach($related as $r)
            <a href="{{ route_locale('products.show', $r->slug) }}" class="pd-related-card">
                <div class="pd-related-img">
                    <img src="{{ $r->image_url }}" alt="{{ $r->name }}" loading="lazy">
                </div>
                <div class="pd-related-name">{{ $r->name }}</div>
                <div class="pd-related-price">Rp{{ number_format($r->sale_price ?? $r->price, 0, ',', '.') }}</div>
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
            spaceBetween: 12, slidesPerView: 'auto',
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
