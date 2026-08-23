@extends('layouts.app')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('meta_keywords', $seo['keywords'])
@section('og_type', 'profile')

@push('head')
@php
    $appUrl     = rtrim(config('app.url'), '/');
    $storeUrl   = route('store.show', $profile->store_slug);
    $avatarUrl  = $seller->avatar ? asset('storage/' . $seller->avatar) : null;
    $totalProducts = $products->total();

    // ── ProfilePage Schema ──────────────────────────────────────────
    $profilePageSchema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'ProfilePage',
        '@id'         => $storeUrl . '#profilepage',
        'url'         => $storeUrl,
        'name'        => $profile->store_name . ' — buyle.id Creator',
        'description' => $profile->store_description ?: ('Toko digital ' . $profile->store_name . ' di buyle.id'),
        'mainEntity'  => array_filter([
            '@type'       => 'Organization',
            '@id'         => $storeUrl . '#seller',
            'name'        => $profile->store_name,
            'url'         => $storeUrl,
            'description' => $profile->store_description,
            'image'       => $avatarUrl ?: null,
            'sameAs'      => [$storeUrl],
        ]),
    ];
    $profilePageJson = json_encode($profilePageSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

    // ── ItemList Schema — first page of products ──────────────────
    $itemListElements = [];
    foreach ($products as $idx => $prod) {
        $effPrice = ($prod->sale_price > 0 && $prod->sale_price < $prod->price) ? $prod->sale_price : $prod->price;
        $itemListElements[] = [
            '@type'    => 'ListItem',
            'position' => $idx + 1,
            'item'     => array_filter([
                '@type'       => 'Product',
                '@id'         => route('products.show', $prod->slug) . '#product',
                'name'        => $prod->name,
                'url'         => route('products.show', $prod->slug),
                'description' => $prod->short_desc ?: null,
                'image'       => $prod->image ? asset('storage/' . $prod->image) : null,
                'offers'      => $effPrice > 0 ? [
                    '@type'         => 'Offer',
                    'price'         => number_format($effPrice, 0, '', ''),
                    'priceCurrency' => 'IDR',
                    'availability'  => 'https://schema.org/InStock',
                    'url'           => route('products.show', $prod->slug),
                    'seller'        => ['@type' => 'Organization', 'name' => $profile->store_name],
                ] : null,
            ]),
        ];
    }
    $itemListSchema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => 'Produk ' . $profile->store_name,
        'description'     => 'Daftar produk dari ' . $profile->store_name . ' di buyle.id',
        'url'             => $storeUrl,
        'numberOfItems'   => $totalProducts,
        'itemListElement' => $itemListElements,
    ];
    $itemListJson = json_encode($itemListSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $profilePageJson !!}</script>
<script type="application/ld+json">{!! $itemListJson !!}</script>
@endpush

@section('content')
<style>
/* ═══════════════════════════════════════════
   CREATOR STOREFRONT — buyle.id
   Modern Desktop Layout & Mobile First
═══════════════════════════════════════════ */

/* ── Global font: Montserrat tipis ── */
.sf-page, .sf-page * {
    font-family: 'Montserrat', sans-serif !important;
}
.sf-store-name, .sf-card-price { font-weight: 600 !important; }
.sf-card-name, .sf-desc, .sf-rating, .sf-tab, .sf-tab-sidebar,
.sf-sort-btn, .sf-search, .sf-card-price-strike { font-weight: 400 !important; }

/* ── Wrapper ── */
.sf-page {
    background: #f8fafc;
    min-height: 100vh;
    padding-top: 80px; /* offset for fixed header */
}
.sf-page * { box-sizing: border-box; }

.sf-layout {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    padding: 0;
}

/* ── Sidebar (Profile + Desktop Tabs) ── */
.sf-sidebar {
    width: 100%;
}
.sf-profile {
    background: #fff;
    padding: 1.5rem 1rem;
    border-radius: 0 0 20px 20px;
    color: #1E293B;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    border-bottom: 1px solid #e2e8f0;
}
.sf-profile-inner {
    display: flex; align-items: center; gap: 1rem; max-width: 1200px; margin: 0 auto;
}
.sf-avatar {
    width: 64px; height: 64px; border-radius: 50%;
    border: 3px solid #f8fafc;
    object-fit: cover; flex-shrink: 0;
    background: #f0fdf4; color: #1eb349;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; font-weight: 600;
}
.sf-profile-text { flex: 1; min-width: 0; }
.sf-store-name {
    font-size: 1.25rem; margin: 0 0 0.25rem; line-height: 1.2;
}
.sf-desc {
    font-size: 0.85rem; color: #475569;
    line-height: 1.5; margin: 0;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}

.sf-desktop-tabs { display: none; } /* Hidden on mobile */
.sf-mobile-tabs {
    padding: 1rem 1rem 0; overflow-x: auto; scrollbar-width: none;
    -webkit-overflow-scrolling: touch; display: flex; gap: 0.5rem;
}
.sf-mobile-tabs::-webkit-scrollbar { display: none; }

/* ── Main Content ── */
.sf-main {
    flex: 1; min-width: 0;
}

/* ── Banner ── */
.sf-banner-slider {
    display: flex; overflow-x: auto; scroll-snap-type: x mandatory;
    gap: 0.5rem; padding: 1rem 1rem 0; scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
}
.sf-banner-slider::-webkit-scrollbar { display: none; }
.sf-banner {
    flex: 0 0 100%; scroll-snap-align: center;
    border-radius: 16px; overflow: hidden;
    aspect-ratio: 2/1; background: #e2e8f0;
}
.sf-banner img { width: 100%; height: 100%; object-fit: cover; display: block; }

/* ── Tabs & Buttons ── */
.sf-tab, .sf-tab-sidebar {
    padding: 0.6rem 1.2rem; border-radius: 99px;
    font-size: 0.85rem; white-space: nowrap;
    text-decoration: none; transition: all 0.2s;
    background: #fff; color: #475569; border: 1px solid #e2e8f0;
}
.sf-tab:hover, .sf-tab-sidebar:hover { border-color: #1eb349; color: #1eb349; }
.sf-tab.active, .sf-tab-sidebar.active {
    background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
    border-color: transparent; color: #fff; box-shadow: 0 4px 10px rgba(30,179,73,0.2);
}

/* ── Toolbar ── */
.sf-toolbar {
    display: flex; align-items: center; gap: 0.5rem;
    padding: 1rem 1rem 0; flex-wrap: wrap;
}
.sf-sort-btn {
    padding: 0.6rem 1.2rem; border-radius: 99px; font-size: 0.85rem;
    background: #fff; color: #475569; border: 1px solid #e2e8f0;
    cursor: pointer; transition: all 0.2s; text-decoration: none;
}
.sf-sort-btn.active {
    background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); border-color: transparent; color: #fff;
}
.sf-sort-btn:hover:not(.active) { border-color: #1eb349; color: #1eb349; }

.sf-search-wrap { flex: 1; min-width: 200px; position: relative; }
.sf-search {
    width: 100%; height: 42px; border-radius: 99px;
    border: 1px solid #e2e8f0; padding: 0 2.5rem 0 1rem;
    font-size: 0.85rem; background: #fff; outline: none;
    transition: all 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}
.sf-search:focus { border-color: #1eb349; box-shadow: 0 0 0 3px rgba(30,179,73,0.1); }
.sf-search-icon {
    position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);
    color: #94A3B8; pointer-events: none;
}

/* ── Products Grid ── */
.sf-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 0.75rem; padding: 1rem 1rem 3rem;
}

.sf-card {
    background: #fff; border-radius: 16px; border: 1px solid #e2e8f0;
    overflow: hidden; text-decoration: none; color: inherit;
    display: flex; flex-direction: column;
    transition: transform 0.2s, box-shadow 0.2s;
}
.sf-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(30,179,73,0.15); border-color: #1eb349; }
.sf-card-img {
    width: 100%; aspect-ratio: 1/1; overflow: hidden; background: #f1f5f9; position: relative;
}
.sf-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
.sf-card:hover .sf-card-img img { transform: scale(1.05); }
.sf-discount-badge {
    position: absolute; top: 0.5rem; left: 0.5rem;
    background: #EF4444; color: #fff; font-size: 0.65rem; font-weight: 700;
    padding: 0.2rem 0.4rem; border-radius: 4px; z-index: 2;
}
.sf-card-body { padding: 0.6rem; flex: 1; display: flex; flex-direction: column; }
.sf-card-name {
    font-size: 0.8rem; color: #1E293B; margin: 0 0 0.4rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.35;
    font-weight: 500;
}
.sf-card-price { margin-top: auto; display: flex; flex-direction: column; }
.sf-card-price-strike { font-size: 0.7rem; color: #94A3B8; text-decoration: line-through; margin-bottom: 0.1rem; }
.sf-card-price-main { font-size: 0.95rem; color: #1eb349; font-weight: 600; }
/* ── Desktop Layout Overrides ── */
@media (min-width: 768px) {
    .sf-layout { padding: 2rem 1rem; flex-direction: row; align-items: flex-start; }
    
    .sf-sidebar {
        width: 300px; flex-shrink: 0; position: sticky; top: 100px;
        background: #fff; border-radius: 20px; padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;
    }
    .sf-profile {
        background: transparent; padding: 0; border-radius: 0;
        box-shadow: none; text-align: center; margin-bottom: 2rem; border: none;
    }
    .sf-profile-inner { flex-direction: column; gap: 1rem; align-items: center; }
    .sf-avatar {
        width: 100px; height: 100px; border-width: 4px;
        box-shadow: 0 8px 16px rgba(30,179,73,0.15); font-size: 2rem;
    }
    .sf-desc { color: #64748B; font-size: 0.9rem; -webkit-line-clamp: 3; }
    
    .sf-mobile-tabs { display: none; }
    .sf-desktop-tabs {
        display: flex; flex-direction: column; gap: 0.5rem;
    }
    .sf-tab-sidebar {
        border: none; background: #f8fafc; text-align: left; padding: 0.75rem 1rem;
        border-radius: 12px;
    }
    .sf-tab-sidebar:hover { background: #f0fdf4; }
    
    .sf-banner-slider { padding: 0; gap: 1rem; }
    .sf-banner { aspect-ratio: 21/6; border-radius: 20px; }
    
    .sf-toolbar { padding: 1.5rem 0 0; }
    .sf-grid { grid-template-columns: repeat(3, 1fr); padding: 1.5rem 0 3rem; gap: 1.25rem; }
}
@media (min-width: 1024px) {
    .sf-grid { grid-template-columns: repeat(4, 1fr); }
}

/* ── Empty State ── */
.sf-empty { grid-column: 1/-1; text-align: center; padding: 4rem 1rem; color: #94A3B8; }
.sf-empty svg { margin: 0 auto 1rem; opacity: 0.5; }

/* ── Smart Search Notice ── */
.sf-search-notice {
    margin: 0.75rem 1rem 0;
    padding: 0.6rem 1rem;
    border-radius: 10px;
    background: rgba(30,179,73,0.08);
    border-left: 3px solid #1eb349;
    font-size: 0.82rem;
    color: #0F172A;
    display: flex; align-items: center; gap: 0.5rem;
    animation: sf-fade-in 0.25s ease;
}
.sf-search-notice a {
    color: #1eb349; font-weight: 600;
    text-decoration: none; border-bottom: 1px solid rgba(30,179,73,0.4);
}
.sf-search-notice a:hover { border-bottom-color: #1eb349; }
@keyframes sf-fade-in { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:none; } }
</style>

<div class="sf-page">
    <div class="sf-layout">

        {{-- ── Sidebar ── --}}
        <aside class="sf-sidebar">
            <div class="sf-profile">
                <div class="sf-profile-inner">
                    @if($seller->avatar)
                        <img src="{{ asset('storage/' . $seller->avatar) }}" alt="{{ $profile->store_name }}" class="sf-avatar">
                    @else
                        <div class="sf-avatar">{{ strtoupper(substr($profile->store_name ?: $seller->name, 0, 2)) }}</div>
                    @endif
                    <div class="sf-profile-text">
                        <h1 class="sf-store-name">{{ $profile->store_name ?: $seller->name }}</h1>
                        @if($profile->store_description)
                            <p class="sf-desc">{{ $profile->store_description }}</p>
                        @endif
                    </div>
                </div>
            </div>

            @if($groups->count() > 0)
            <div class="sf-desktop-tabs">
                <div style="font-size: 0.9rem; font-weight: 600; color: #1E293B; margin-bottom: 0.5rem; padding-left: 0.5rem;">Kategori Produk</div>
                <a href="{{ route('store.show', $profile->store_slug) }}{{ request()->has('sort') ? '?sort='.request('sort') : '' }}"
                   class="sf-tab-sidebar {{ !request()->has('group') ? 'active' : '' }}">Semua Produk</a>
                @foreach($groups as $group)
                    <a href="{{ route('store.show', $profile->store_slug) }}?group={{ $group->slug }}{{ request()->has('sort') ? '&sort='.request('sort') : '' }}"
                       class="sf-tab-sidebar {{ request('group') === $group->slug ? 'active' : '' }}">
                        {{ $group->name }}
                    </a>
                @endforeach
            </div>
            @endif
        </aside>

        {{-- ── Main Content ── --}}
        <main class="sf-main">
            {{-- ── Banners ── --}}
            @if($profile->store_banner_1 || $profile->store_banner_2)
                <div class="sf-banner-slider">
                    @if($profile->store_banner_1)
                    <div class="sf-banner">
                        <img src="{{ asset('storage/' . $profile->store_banner_1) }}" alt="Banner 1">
                    </div>
                    @endif
                    @if($profile->store_banner_2)
                    <div class="sf-banner">
                        <img src="{{ asset('storage/' . $profile->store_banner_2) }}" alt="Banner 2">
                    </div>
                    @endif
                </div>
            @else
                @php
                    $bannerImg = isset($profile->store_banner) && $profile->store_banner
                        ? asset('storage/' . $profile->store_banner)
                        : ($products->count() > 0 && $products->first()->image ? $products->first()->image_url : null);
                @endphp
                @if($bannerImg)
                <div class="sf-banner-slider">
                    <div class="sf-banner">
                        <img src="{{ $bannerImg }}" alt="Banner {{ $profile->store_name }}">
                    </div>
                </div>
                @endif
            @endif

            {{-- ── Mobile Tabs ── --}}
            @if($groups->count() > 0)
            <div class="sf-mobile-tabs">
                <a href="{{ route('store.show', $profile->store_slug) }}{{ request()->has('sort') ? '?sort='.request('sort') : '' }}"
                   class="sf-tab {{ !request()->has('group') ? 'active' : '' }}">Semua</a>
                @foreach($groups as $group)
                    <a href="{{ route('store.show', $profile->store_slug) }}?group={{ $group->slug }}{{ request()->has('sort') ? '&sort='.request('sort') : '' }}"
                       class="sf-tab {{ request('group') === $group->slug ? 'active' : '' }}">
                        {{ $group->name }}
                    </a>
                @endforeach
            </div>
            @endif

            {{-- ── Toolbar (Search & Sort) ── --}}
            <div class="sf-toolbar">
                @php
                    $baseParams = array_filter(['group' => request('group'), 'q' => request('q')]);
                @endphp
                <a href="{{ route('store.show', $profile->store_slug) }}?{{ http_build_query(array_merge($baseParams, ['sort' => 'terbaru'])) }}"
                   class="sf-sort-btn {{ ($sort ?? 'terbaru') === 'terbaru' ? 'active' : '' }}">Terbaru</a>
                <a href="{{ route('store.show', $profile->store_slug) }}?{{ http_build_query(array_merge($baseParams, ['sort' => 'terlaris'])) }}"
                   class="sf-sort-btn {{ ($sort ?? '') === 'terlaris' ? 'active' : '' }}">Terlaris</a>
                
                <div class="sf-search-wrap">
                    <form method="GET" action="{{ route('store.show', $profile->store_slug) }}" id="sfSearchForm">
                        @if(request('group'))<input type="hidden" name="group" value="{{ request('group') }}">@endif
                        @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                        <input type="text" name="q" value="{{ request('q') }}" class="sf-search"
                            placeholder="Cari Produk {{ $profile->store_name }}…"
                            oninput="clearTimeout(window._st);window._st=setTimeout(()=>this.form.submit(),600)">
                        <svg class="sf-search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </form>
                </div>
            </div>

            {{-- ── Smart Search Notice (1 baris compact) ── --}}
            @if(request('q') && isset($suggestion) && $suggestion)
                <div class="sf-search-notice">
                    {{-- SVG icon: search / sparkle --}}
                    @if($suggestionApplied ?? false)
                        <svg width="14" height="14" fill="none" stroke="#1eb349" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="flex-shrink:0;opacity:.8;">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        Tidak ada hasil untuk <b style="margin:0 3px;">&ldquo;{{ request('q') }}&rdquo;</b> &mdash; Menampilkan hasil untuk:
                        <a href="{{ route('store.show', $profile->store_slug) }}?q={{ urlencode($suggestion) }}{{ request('group') ? '&group='.request('group') : '' }}{{ request('sort') ? '&sort='.request('sort') : '' }}">&ldquo;{{ $suggestion }}&rdquo;</a>
                    @else
                        <svg width="14" height="14" fill="none" stroke="#1eb349" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="flex-shrink:0;opacity:.8;">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Mungkin Maksud Anda:
                        <a href="{{ route('store.show', $profile->store_slug) }}?q={{ urlencode($suggestion) }}{{ request('group') ? '&group='.request('group') : '' }}{{ request('sort') ? '&sort='.request('sort') : '' }}">{{ $suggestion }}</a>?
                    @endif
                </div>
            @endif

            {{-- ── Products Grid ── --}}
            <div class="sf-grid">
                @forelse($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}" class="sf-card">
                        <div class="sf-card-img">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                @php $discount = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                                <div class="sf-discount-badge">{{ $discount }}%</div>
                            @endif
                        </div>
                        <div class="sf-card-body">
                            <p class="sf-card-name">{{ $product->name }}</p>
                            <div class="sf-card-price">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <div class="sf-card-price-strike">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                                @endif
                                <div class="sf-card-price-main">Rp{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="sf-empty">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        @if(request('q'))
                            <p style="margin-top: 1rem; font-size:0.95rem;">Tidak ada produk yang cocok untuk <b>"{{ request('q') }}"</b>.</p>
                            @if(isset($suggestion) && $suggestion)
                            <p style="margin-top:0.5rem; font-size:0.88rem; color:#64748B;">
                                Coba cari:
                                <a href="{{ route('store.show', $profile->store_slug) }}?q={{ urlencode($suggestion) }}{{ request('group') ? '&group='.request('group') : '' }}{{ request('sort') ? '&sort='.request('sort') : '' }}"
                                   style="color:#1eb349; font-weight:600; text-decoration:underline;">
                                    {{ $suggestion }}
                                </a>
                            </p>
                            @endif
                            <a href="{{ route('store.show', $profile->store_slug) }}"
                               style="display:inline-block; margin-top:1rem; padding:0.5rem 1.25rem; border-radius:99px; background:linear-gradient(135deg,#1eb349,#a5cf37); color:#fff; font-size:0.82rem; font-weight:600; text-decoration:none;">
                               Lihat Semua Produk
                            </a>
                        @else
                            <p style="margin-top: 1rem;">Belum ada produk di sini.</p>
                        @endif
                    </div>
                @endforelse
            </div>

            {{-- ── Pagination ── --}}
            @if($products->hasPages())
            <div style="padding: 0 1rem 3rem; display:flex; justify-content:center;">
                {{ $products->links() }}
            </div>
            @endif

        </main>
    </div>
</div>
@endsection
