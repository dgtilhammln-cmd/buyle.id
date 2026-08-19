@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

<style>
/* ── TOKENS & RESET ── */
:root {
    --bg-base: #ffffff;
    --bg-surface: #F8FAFC;
    --border-1: #E2E8F0;
    --text-main: #0F172A;
    --text-muted: #64748B;
    --accent: #1eb349;
    --accent-dark: #16a34a;
}

.pd-layout {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.5rem 1.5rem 3rem;
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 3rem;
    align-items: start;
}

.pd-card {
    background: var(--bg-base);
    border: 1px solid var(--border-1);
    border-radius: 20px;
    padding: 1.75rem;
    box-shadow: 0 16px 40px rgba(30,179,73,0.03);
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
}

.pd-card-hover:hover {
    border-color: var(--accent);
    transform: translateY(-4px);
    box-shadow: 0 16px 40px rgba(30,179,73,0.1);
}

/* ── BREADCRUMB ── */
.pd-breadcrumb {
    padding: 6rem 1.5rem 0; /* Ditambah agar tidak tertutup header */
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--text-muted);
}
.pd-breadcrumb a { color: var(--text-muted); text-decoration: none; transition: 0.2s; }
.pd-breadcrumb a:hover { color: var(--accent); }
.pd-breadcrumb span { color: var(--text-main); font-weight: 600; }

/* ── GALLERY ── */
.pd-gallery-main {
    width: 100%;
    aspect-ratio: 1/1;
    border-radius: 20px;
    overflow: hidden;
    background: var(--bg-surface);
    border: 1px solid var(--border-1);
    margin-bottom: 1rem;
    position: relative;
}
.pd-gallery-main img {
    width: 100%; height: 100%; object-fit: cover;
}
.pd-gallery-main .swiper-button-next,
.pd-gallery-main .swiper-button-prev {
    color: var(--text-main);
    background: rgba(255,255,255,0.95);
    width: 36px; height: 36px;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.pd-gallery-main .swiper-button-next::after,
.pd-gallery-main .swiper-button-prev::after { font-size: 14px; font-weight: 800; }

.pd-thumbs {
    display: flex; gap: 0.5rem;
}
.pd-thumb-item {
    width: 65px; height: 65px;
    border-radius: 12px; overflow: hidden;
    border: 2px solid transparent;
    cursor: pointer; opacity: 0.5; transition: 0.2s;
}
.pd-thumb-item.swiper-slide-thumb-active {
    opacity: 1; border-color: var(--accent);
}
.pd-thumb-item img {
    width: 100%; height: 100%; object-fit: cover;
}

/* ── INFO ── */
.pd-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-main);
    line-height: 1.25;
    margin-bottom: 0.75rem;
    letter-spacing: -0.02em;
}
.pd-desc-short {
    font-size: 1rem;
    color: var(--text-muted);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

/* Rating / Stats */
.pd-stats {
    display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;
    font-size: 0.9rem;
}
.pd-stars { color: #F59E0B; display: flex; align-items: center; gap: 4px; font-weight: 700; }
.pd-stat-divider { width: 1px; height: 16px; background: var(--border-1); }

/* Price Box */
.pd-price-box {
    background: linear-gradient(135deg, #F0F9FF, #ffffff);
    border: 1px solid rgba(30,179,73,0.15);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;
}
.pd-price-current {
    font-size: 1.85rem; font-weight: 900; color: var(--accent-dark); line-height: 1;
    letter-spacing: -0.02em;
}
.pd-price-old {
    font-size: 1.15rem; text-decoration: line-through; color: var(--text-muted);
}
.pd-badge-discount {
    background: #EF4444; color: #fff; font-size: 0.75rem; font-weight: 800;
    padding: 0.35rem 0.65rem; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.05em;
}

/* Vouchers */
.pd-voucher-scroll {
    display: flex;
    gap: 0.75rem;
    overflow-x: auto;
    padding-bottom: 0.75rem;
    margin-bottom: 1.5rem;
    scrollbar-width: none; /* Firefox */
}
.pd-voucher-scroll::-webkit-scrollbar { display: none; }
.pd-voucher-card {
    background: #ffffff;
    border: 1px solid var(--border-1);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: max-content;
    cursor: pointer;
    transition: 0.2s;
}
.pd-voucher-card:hover {
    border-color: var(--accent);
    background: #F0F9FF;
}
.pd-v-icon {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    color: #fff;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.9rem;
}
.pd-v-title {
    font-size: 0.85rem; font-weight: 800; color: var(--text-main); line-height: 1.2;
}
.pd-v-desc {
    font-size: 0.75rem; color: var(--text-muted);
}

/* Options / QTY */
.pd-qty-wrap {
    display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;
    background: var(--bg-surface); padding: 1rem; border-radius: 16px; border: 1px solid var(--border-1);
}
.pd-qty-ctrl {
    display: flex; align-items: center;
    border: 1px solid var(--border-1);
    border-radius: 10px; overflow: hidden;
    background: var(--bg-base); box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}
.pd-qty-btn {
    width: 40px; height: 40px;
    border: none; background: transparent; cursor: pointer;
    font-size: 1.25rem; color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    transition: 0.2s;
}
.pd-qty-btn:hover { background: #F1F5F9; color: var(--text-main); }
.pd-qty-input {
    width: 50px; height: 40px; border: none; text-align: center;
    font-size: 1.05rem; font-weight: 700; color: var(--text-main);
    border-left: 1px solid var(--border-1); border-right: 1px solid var(--border-1);
}

/* Action Buttons */
.pd-actions {
    display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;
}
.pd-btn {
    padding: 1rem 1.5rem; border-radius: 14px; font-weight: 700; font-size: 1rem;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1); text-decoration: none; border: none;
}
.pd-btn-outline {
    background: #F0F9FF; color: var(--accent-dark); border: 1.5px solid rgba(30,179,73,0.3);
}
.pd-btn-outline:hover {
    background: #dcfce7; border-color: var(--accent); transform: translateY(-2px);
}
.pd-btn-primary {
    background: linear-gradient(135deg, #1eb349, #a5cf37);
    color: #fff;
    box-shadow: 0 8px 24px rgba(30,179,73,0.25);
}
.pd-btn-primary:hover {
    transform: translateY(-2px); box-shadow: 0 12px 32px rgba(30,179,73,0.35);
}
.pd-btn:disabled {
    opacity: 0.6; cursor: not-allowed; transform: none !important; box-shadow: none !important;
}

/* Bottom Content */
.pd-bottom-layout {
    max-width: 1200px; margin: 0 auto; padding: 0 1.5rem 4rem;
    display: grid; grid-template-columns: 1fr 320px; gap: 3rem;
}

.pd-section-title {
    font-size: 1.25rem; font-weight: 800; color: var(--text-main);
    margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;
}
.pd-section-title::before {
    content:''; display:block; width:5px; height:20px; background:var(--accent); border-radius:4px;
}

.pd-specs-table { width: 100%; border-collapse: collapse; font-size: 0.95rem; margin-bottom: 2.5rem; }
.pd-specs-table td { padding: 1rem 0; border-bottom: 1px solid var(--border-1); }
.pd-specs-table td:first-child { width: 160px; color: var(--text-muted); }
.pd-specs-table td:last-child { color: var(--text-main); font-weight: 600; }

.pd-content {
    font-size: 1rem; color: var(--text-muted); line-height: 1.8;
}
.pd-content h2, .pd-content h3 { color: var(--text-main); font-weight: 800; margin-top: 2rem; margin-bottom: 1rem; }
.pd-content ul { padding-left: 1.5rem; margin-bottom: 1.25rem; }
.pd-content p { margin-bottom: 1.25rem; }

/* Mobile Sticky Action Bar */
.pd-mobile-actions { display: none; }

@media (max-width: 1024px) {
    .pd-layout { grid-template-columns: 1fr; gap: 2rem; }
    .pd-bottom-layout { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
    .pd-layout { padding: 1rem 1rem 2rem; gap: 1.5rem; }
    .pd-bottom-layout { padding: 0 1rem 6rem; }
    .pd-title { font-size: 1.5rem; }
    .pd-price-current { font-size: 1.75rem; }
    .pd-card { padding: 1.25rem; }
    .pd-price-box { padding: 1.25rem; }
    
    /* Hide desktop actions */
    .pd-desktop-actions { display: none; }
    
    .pd-mobile-actions {
        display: flex; align-items: center; gap: 0.75rem;
        position: fixed; bottom: 0; left: 0; right: 0;
        background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
        padding: 1rem; border-top: 1px solid var(--border-1);
        box-shadow: 0 -4px 24px rgba(0,0,0,0.06); z-index: 50;
    }
    .pd-mobile-actions .pd-btn { flex: 1; padding: 0.875rem; font-size: 0.95rem; border-radius: 12px; }
}
</style>

{{-- BREADCRUMB --}}
<div class="pd-breadcrumb">
    <a href="{{ route_locale('home') }}">Beranda</a>
    <span>›</span>
    <a href="{{ route_locale('products') }}">Produk &amp; Layanan</a>
    <span>›</span>
    <span>{{ $service->name }}</span>
</div>

{{-- TOP SECTION --}}
<section class="pd-layout">
    {{-- Left: Gallery --}}
    <div>
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

    {{-- Right: Product Info --}}
    <div>
        <h1 class="pd-title">{{ $service->name }}</h1>
        
        @if($service->rating > 0 || $service->sold_count > 0)
        <div class="pd-stats">
            @if($service->rating > 0)
            <div class="pd-stars">
                <span>{{ number_format($service->rating, 1) }}</span>
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            @endif
            @if($service->rating > 0 && $service->sold_count > 0)
                <div class="pd-stat-divider"></div>
            @endif
            @if($service->sold_count > 0)
            <div style="color:var(--text-muted);">
                Terjual <span style="font-weight:700; color:var(--text-main);">{{ $service->sold_count >= 1000 ? number_format($service->sold_count/1000, 1, ',', '').'RB' : $service->sold_count }}</span>
            </div>
            @endif
        </div>
        @endif

        @if($service->short_desc)
        <p class="pd-desc-short">{{ $service->short_desc }}</p>
        @endif

        @if($service->price > 0)
            <div class="pd-price-box">
                @if($service->sale_price > 0 && $service->sale_price < $service->price)
                    <div class="pd-price-current">Rp{{ number_format($service->sale_price, 0, ',', '.') }}</div>
                    <div class="pd-price-old">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                    <div class="pd-badge-discount">Hemat {{ round((($service->price - $service->sale_price)/$service->price)*100) }}%</div>
                @else
                    <div class="pd-price-current">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                @endif
            </div>

            {{-- Vouchers --}}
            <div class="pd-voucher-scroll">
                <div class="pd-voucher-card">
                    <div class="pd-v-icon">%</div>
                    <div>
                        <div class="pd-v-title">Diskon 50RB</div>
                        <div class="pd-v-desc">Min. belanja 300RB</div>
                    </div>
                </div>
                <div class="pd-voucher-card">
                    <div class="pd-v-icon">%</div>
                    <div>
                        <div class="pd-v-title">Diskon 10%</div>
                        <div class="pd-v-desc">S/d 100RB</div>
                    </div>
                </div>
                <div class="pd-voucher-card">
                    <div class="pd-v-icon">Rp</div>
                    <div>
                        <div class="pd-v-title">Gratis Ongkir</div>
                        <div class="pd-v-desc">S/d 20RB</div>
                    </div>
                </div>
            </div>

            <form action="{{ route('cart.add') }}" method="POST" id="form-add-to-cart">
                @csrf
                <input type="hidden" name="product_id" value="{{ $service->id }}">
                
                <div class="pd-qty-wrap">
                    <span style="font-size:0.95rem; font-weight:700; color:var(--text-main);">Kuantitas:</span>
                    <div class="pd-qty-ctrl">
                        <button type="button" class="pd-qty-btn" onclick="document.getElementById('qty_input').stepDown()">−</button>
                        <input type="number" id="qty_input" class="pd-qty-input" name="qty" 
                               value="{{ $service->min_order ?? 1 }}" min="{{ $service->min_order ?? 1 }}"
                               @if($service->type !== 'service' && $service->stock > 0) max="{{ $service->stock }}" @endif
                               @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                        <button type="button" class="pd-qty-btn" onclick="document.getElementById('qty_input').stepUp()">+</button>
                    </div>
                    <span style="font-size:0.9rem; color:var(--text-muted);">
                        @if($service->type === 'service')
                            Jasa / Layanan
                        @elseif($service->stock > 0)
                            Tersisa <b style="color:var(--text-main);">{{ $service->stock }}</b> buah
                        @else
                            <b style="color:#EF4444;">Stok Habis</b>
                        @endif
                    </span>
                </div>

                <div class="pd-actions pd-desktop-actions">
                    <button type="submit" name="action" value="cart" class="pd-btn pd-btn-outline" 
                            @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 20a1 1 0 100-2 1 1 0 000 2zM20 20a1 1 0 100-2 1 1 0 000 2z"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
                        Keranjang
                    </button>
                    <button type="submit" name="action" value="buy" class="pd-btn pd-btn-primary"
                            @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
                        Beli Sekarang
                    </button>
                </div>
            </form>
        @else
            {{-- Non-ecommerce / Custom Service --}}
            <div class="pd-price-box" style="background: linear-gradient(135deg, #f0fdf4, #ffffff); border-color: #BFDBFE;">
                <div class="pd-price-current" style="color: #2563EB; font-size:2rem;">Konsultasi Gratis</div>
                <div style="font-size:1rem; color:#1E3A8A; width:100%;">Tim ahli kami siap memberikan penawaran terbaik untuk Anda.</div>
            </div>
            
            <a href="javascript:void(0)" onclick="openOrderModal('Produk: {{ addslashes($service->name) }}')" class="pd-btn pd-btn-primary" style="width:100%;">
                Tanya via WhatsApp
            </a>
        @endif
    </div>
</section>

{{-- BOTTOM CONTENT --}}
<section class="pd-bottom-layout">
    <div class="pd-card">
        @if(is_array($service->specifications) && count($service->specifications) > 0)
            <div class="pd-section-title">Spesifikasi Produk</div>
            <table class="pd-specs-table">
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

        @php
            $faqs = is_array($service->faqs) && !empty($service->faqs) ? $service->faqs : [];
        @endphp
        @if(count($faqs) > 0)
            <div class="pd-section-title" style="margin-top: 3.5rem;">FAQ {{ $service->name }}</div>
            <div style="display:flex; flex-direction:column; gap:1.25rem;">
                @foreach($faqs as $f)
                <div style="background:var(--bg-surface); padding:1.5rem; border-radius:16px; border:1px solid var(--border-1);">
                    <div style="font-weight:800; color:var(--text-main); margin-bottom:0.75rem; font-size:1.05rem;">{{ $f['q'] ?? '' }}</div>
                    <div style="font-size:0.95rem; color:var(--text-muted); line-height:1.7;">{{ $f['a'] ?? '' }}</div>
                </div>
                @endforeach
            </div>
            
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

    {{-- SIDEBAR --}}
    <div>
        <div class="pd-card" style="position: sticky; top: 100px;">
            <div class="pd-section-title" style="margin-bottom:1.5rem;">Jaminan Belanja</div>
            
            <div style="display:flex; align-items:flex-start; gap:14px; margin-bottom:1.25rem;">
                <div style="width:42px; height:42px; background:#F0F9FF; color:var(--accent); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <div style="font-weight:800; font-size:0.95rem; color:var(--text-main); margin-bottom:0.25rem;">100% Original</div>
                    <div style="font-size:0.85rem; color:var(--text-muted); line-height:1.5;">Produk asli dan bergaransi resmi.</div>
                </div>
            </div>
            
            <div style="display:flex; align-items:flex-start; gap:14px; margin-bottom:1.25rem;">
                <div style="width:42px; height:42px; background:#F0F9FF; color:var(--accent); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                </div>
                <div>
                    <div style="font-weight:800; font-size:0.95rem; color:var(--text-main); margin-bottom:0.25rem;">Pengiriman Aman</div>
                    <div style="font-size:0.85rem; color:var(--text-muted); line-height:1.5;">Tepat waktu dengan asuransi pengiriman.</div>
                </div>
            </div>
            
            @if($service->brochure)
            <a href="{{ asset('storage/'.$service->brochure) }}" target="_blank" class="pd-btn pd-btn-outline" style="margin-top:2rem; width:100%;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh Brosur/Datasheet
            </a>
            @endif
        </div>
    </div>
</section>

{{-- MOBILE STICKY ACTION BAR --}}
@if($service->price > 0)
<div class="pd-mobile-actions">
    <button type="button" onclick="document.querySelector('button[value=\'cart\']').click()" class="pd-btn pd-btn-outline" 
            @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 20a1 1 0 100-2 1 1 0 000 2zM20 20a1 1 0 100-2 1 1 0 000 2z"/><path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/></svg>
        Keranjang
    </button>
    <button type="button" onclick="document.querySelector('button[value=\'buy\']').click()" class="pd-btn pd-btn-primary"
            @if($service->type !== 'service' && $service->stock <= 0) disabled @endif>
        Beli Sekarang
    </button>
</div>
@endif

{{-- ═══ MENGAPA buyle.id ═══ --}}
<section style="background:var(--bg-base); padding:5rem 1.5rem; border-top:1px solid var(--border-1);">
    <div style="max-width:1200px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:3.5rem;">
            <div style="font-size:0.75rem; font-weight:800; letter-spacing:0.15em; color:var(--accent); text-transform:uppercase; margin-bottom:0.75rem;">Mengapa Memilih Kami</div>
            <h2 style="font-size:2.25rem; font-weight:900; color:var(--text-main); line-height:1.2; letter-spacing:-0.03em;">Toko buyle.id Tangga<br>Terpercaya di Surabaya</h2>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.5rem;">
            <div class="pd-card" style="text-align:center; padding:2rem 1.5rem;">
                <div style="width:56px; height:56px; background:#F0F9FF; color:var(--accent); border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" /></svg>
                </div>
                <div style="font-weight:800; margin-bottom:0.75rem; color:var(--text-main); font-size:1.1rem;">Produk Berkualitas</div>
                <div style="font-size:0.9rem; color:var(--text-muted); line-height:1.6;">Semua produk tersertifikasi dan bergaransi resmi dari produsen.</div>
            </div>
            <div class="pd-card" style="text-align:center; padding:2rem 1.5rem;">
                <div style="width:56px; height:56px; background:#F0F9FF; color:var(--accent); border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7" /></svg>
                </div>
                <div style="font-weight:800; margin-bottom:0.75rem; color:var(--text-main); font-size:1.1rem;">Pengiriman Cepat</div>
                <div style="font-size:0.9rem; color:var(--text-muted); line-height:1.6;">Dikirim ke seluruh Indonesia dengan aman dan tepat waktu.</div>
            </div>
            <div class="pd-card" style="text-align:center; padding:2rem 1.5rem;">
                <div style="width:56px; height:56px; background:#F0F9FF; color:var(--accent); border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
                </div>
                <div style="font-weight:800; margin-bottom:0.75rem; color:var(--text-main); font-size:1.1rem;">Harga Terbaik</div>
                <div style="font-size:0.9rem; color:var(--text-muted); line-height:1.6;">Harga kompetitif langsung dari distributor resmi. Diskon setiap hari.</div>
            </div>
            <div class="pd-card" style="text-align:center; padding:2rem 1.5rem;">
                <div style="width:56px; height:56px; background:#F0F9FF; color:var(--accent); border-radius:14px; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" /></svg>
                </div>
                <div style="font-weight:800; margin-bottom:0.75rem; color:var(--text-main); font-size:1.1rem;">CS Responsif</div>
                <div style="font-size:0.9rem; color:var(--text-muted); line-height:1.6;">Tim customer service kami siap membantu via WhatsApp setiap saat.</div>
            </div>
        </div>
    </div>
</section>

{{-- TESTIMONIALS --}}
@include('components.testimonials')

{{-- RELATED PRODUCTS --}}
@if($related->count() > 0)
<section style="background:var(--bg-surface); padding:5rem 1.5rem; border-top:1px solid var(--border-1);">
    <div style="max-width:1200px; margin:0 auto;">
        <div style="font-size:1.75rem; font-weight:900; color:var(--text-main); margin-bottom:2.5rem; letter-spacing:-0.02em;">Produk &amp; Layanan Lainnya</div>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:1.5rem;">
            @foreach($related as $r)
                <a href="{{ route_locale('products.show', $r->slug) }}" style="text-decoration:none; display:block;" class="pd-card pd-card-hover">
                    <img src="{{ $r->image_url }}" alt="{{ $r->name }}" style="width:100%; aspect-ratio:1/1; object-fit:cover; border-radius:12px; margin-bottom:1.25rem;" loading="lazy">
                    <div style="font-weight:800; color:var(--text-main); font-size:1.1rem; margin-bottom:0.5rem; line-height:1.3;">{{ $r->name }}</div>
                    <div style="font-size:0.9rem; color:var(--text-muted); display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; line-height:1.6;">{{ $r->short_desc }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
    });

    var thumbsEl = document.getElementById('pd-swiper-thumbs');
    if (thumbsEl) {
        var swiperThumbs = new Swiper('#pd-swiper-thumbs', {
            spaceBetween: 8,
            slidesPerView: 'auto',
            freeMode: true,
            watchSlidesProgress: true,
        });
        new Swiper('#pd-swiper-main', {
            spaceBetween: 0,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            thumbs: { swiper: swiperThumbs },
        });
    } else {
        var mainEl = document.getElementById('pd-swiper-main');
        if (mainEl) new Swiper('#pd-swiper-main', { spaceBetween: 0 });
    }
});
</script>

@endsection
