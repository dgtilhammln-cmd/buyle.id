@extends('layouts.app')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('meta_keywords', $seo['keywords'])

@section('content')
<style>
/* ═══════════════════════════════════════════
   CREATOR STOREFRONT — buyle.id
   Mobile-first, 2-col mobile / 3-col desktop
═══════════════════════════════════════════ */

/* ── Global font: Montserrat tipis ── */
.sf-page, .sf-page * {
    font-family: 'Montserrat', sans-serif !important;
}
.sf-store-name, .sf-card-price { font-weight: 600 !important; }
.sf-card-name, .sf-desc, .sf-rating, .sf-tab,
.sf-sort-btn, .sf-search, .sf-card-price-strike { font-weight: 400 !important; }

/* ── Wrapper ── */
.sf-page { max-width: 760px; margin: 0 auto; background: #fff; min-height: 100vh; overflow-x: hidden; box-sizing: border-box; }
.sf-page * { box-sizing: border-box; }

/* ── Profile Card ── */
.sf-profile {
    background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
    padding: 1.25rem 1rem 1rem;
    border-radius: 0 0 20px 20px;
}
.sf-profile-inner { display: flex; align-items: flex-start; gap: 0.875rem; }
.sf-avatar {
    width: 60px; height: 60px; border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.8);
    object-fit: cover; flex-shrink: 0;
    background: rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; font-weight: 600; color: #fff;
}
.sf-profile-text { flex: 1; min-width: 0; }
.sf-store-name {
    font-size: 1.1rem; font-weight: 600; color: #fff;
    margin: 0 0 0.2rem; line-height: 1.2;
    display: flex; align-items: center; gap: 0.4rem;
    flex-wrap: wrap;
}
.sf-badge {
    width: 20px; height: 20px; display: inline-flex;
    align-items: center; justify-content: center;
    background: rgba(255,255,255,0.25); border-radius: 50%;
    font-size: 0.7rem;
}
.sf-rating {
    display: flex; align-items: center; gap: 0.35rem;
    color: rgba(255,255,255,0.9); font-size: 0.8rem; font-weight: 500;
    margin-bottom: 0.5rem;
}
.sf-star { color: #FFD700; font-size: 0.85rem; }
.sf-desc {
    font-size: 0.8rem; color: rgba(255,255,255,0.85);
    line-height: 1.5; margin: 0;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}

/* ── Banner ── */
.sf-banner-slider {
    display: flex; overflow-x: auto; scroll-snap-type: x mandatory;
    gap: 0.5rem; padding: 0.75rem 0.75rem 0; scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
}
.sf-banner-slider::-webkit-scrollbar { display: none; }
.sf-banner {
    flex: 0 0 100%; scroll-snap-align: center;
    border-radius: 14px; overflow: hidden;
    aspect-ratio: 16/6; background: #f1f5f9;
}
.sf-banner img { width: 100%; height: 100%; object-fit: cover; display: block; }
.sf-banner-placeholder {
    width: 100%; height: 100%;
    background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
    display: flex; align-items: center; justify-content: center;
    color: #94A3B8; font-size: 0.8rem;
}

/* ── Group Tabs ── */
.sf-tabs-wrap {
    padding: 0.875rem 0.75rem 0;
    overflow-x: auto; scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
}
.sf-tabs-wrap::-webkit-scrollbar { display: none; }
.sf-tabs {
    display: flex; gap: 0.5rem; width: max-content;
}
.sf-tab {
    padding: 0.5rem 1.1rem; border-radius: 99px;
    font-size: 0.8rem; font-weight: 600; white-space: nowrap;
    text-decoration: none; transition: all 0.2s;
    border: 1.5px solid #e2e8f0; background: #fff; color: #475569;
}
.sf-tab:hover { border-color: #1eb349; color: #1eb349; }
.sf-tab.active {
    background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); border-color: transparent; color: #fff;
}

/* ── Sort + Search Bar ── */
.sf-toolbar {
    display: flex; align-items: center; gap: 0.5rem;
    padding: 0.75rem 0.75rem 0;
}
.sf-sort-btn {
    padding: 0.55rem 1rem; border-radius: 99px;
    font-size: 0.78rem; font-weight: 600; white-space: nowrap;
    border: 1.5px solid #e2e8f0; background: #fff; color: #475569;
    cursor: pointer; transition: all 0.2s; text-decoration: none;
}
.sf-sort-btn.active {
    background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); border-color: transparent; color: #fff;
}
.sf-sort-btn:hover:not(.active) { border-color: #1eb349; color: #1eb349; }
.sf-search-wrap {
    flex: 1; position: relative; min-width: 0;
}
.sf-search {
    width: 100%; height: 38px; border-radius: 99px;
    border: 1.5px solid #e2e8f0; padding: 0 2.2rem 0 0.875rem;
    font-size: 0.8rem; font-family: 'Montserrat', sans-serif;
    background: #f8fafc; outline: none; color: #0F172A;
    transition: border-color 0.2s;
}
.sf-search:focus { border-color: #1eb349; background: #fff; }
.sf-search::placeholder { color: #94A3B8; }
.sf-search-icon {
    position: absolute; right: 0.7rem; top: 50%; transform: translateY(-50%);
    color: #94A3B8; pointer-events: none;
}

/* ── Products Grid ── */
.sf-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    padding: 0.875rem 0.75rem 2rem;
}
@media (min-width: 640px) {
    .sf-page { border-radius: 0; }
    .sf-grid { grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .sf-avatar { width: 72px; height: 72px; }
    .sf-store-name { font-size: 1.25rem; }
}
@media (min-width: 1024px) {
    .sf-page { max-width: 900px; }
    .sf-grid { grid-template-columns: repeat(4, 1fr); }
}

/* ── Product Card ── */
.sf-card {
    background: #fff; border-radius: 14px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    overflow: hidden; text-decoration: none; color: inherit;
    display: flex; flex-direction: column;
    transition: transform 0.2s, box-shadow 0.2s;
}
.sf-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(30,179,73,0.1); }
.sf-card-img {
    width: 100%; aspect-ratio: 1/1; overflow: hidden; background: #f8fafc;
    flex-shrink: 0;
}
.sf-card-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s; }
.sf-card:hover .sf-card-img img { transform: scale(1.04); }
.sf-card-body { padding: 0.6rem 0.7rem 0.75rem; flex: 1; display: flex; flex-direction: column; }
.sf-card-name {
    font-size: 0.78rem; font-weight: 500; color: #0F172A; margin: 0 0 0.35rem;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    line-height: 1.35;
}
.sf-card-price {
    margin-top: auto; font-size: 0.9rem; font-weight: 700; color: #1eb349; line-height: 1.1;
}
.sf-card-price-strike {
    font-size: 0.7rem; font-weight: 400; color: #94A3B8;
    text-decoration: line-through; margin-top: 1px;
}

/* ── Empty State ── */
.sf-empty {
    grid-column: 1/-1; text-align: center;
    padding: 3rem 1rem; color: #94A3B8;
}
.sf-empty svg { margin: 0 auto 1rem; opacity: 0.5; }
.sf-empty p { font-size: 0.875rem; }
</style>

<div class="sf-page">

    {{-- ── Profile Card ── --}}
    <div class="sf-profile">
        <div class="sf-profile-inner">
            @if($seller->avatar)
                <img src="{{ asset('storage/' . $seller->avatar) }}" alt="{{ $profile->store_name }}" class="sf-avatar">
            @else
                <div class="sf-avatar">{{ strtoupper(substr($profile->store_name ?: $seller->name, 0, 2)) }}</div>
            @endif
            <div class="sf-profile-text">
                <h1 class="sf-store-name">
                    {{ $profile->store_name ?: $seller->name }}
                </h1>
                @if($profile->store_description)
                    <p class="sf-desc">{{ $profile->store_description }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Banner ── --}}
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
            // Fallback for old 'store_banner' or first product image
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

    {{-- ── Group Tabs ── --}}
    @if($groups->count() > 0)
    <div class="sf-tabs-wrap">
        <div class="sf-tabs">
            <a href="{{ route('store.show', $profile->store_slug) }}{{ request()->has('sort') ? '?sort='.request('sort') : '' }}"
               class="sf-tab {{ !request()->has('group') ? 'active' : '' }}">Semua Produk</a>
            @foreach($groups as $group)
                <a href="{{ route('store.show', $profile->store_slug) }}?group={{ $group->slug }}{{ request()->has('sort') ? '&sort='.request('sort') : '' }}"
                   class="sf-tab {{ request('group') === $group->slug ? 'active' : '' }}">
                    {{ $group->name }}
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Sort + Search ── --}}
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
                <svg class="sf-search-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </form>
        </div>
    </div>

    {{-- ── Products Grid ── --}}
    <div class="sf-grid">
        @forelse($products as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="sf-card">
                <div class="sf-card-img">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                </div>
                <div class="sf-card-body">
                    <p class="sf-card-name">{{ $product->name }}</p>
                    <div class="sf-card-price">
                        Rp{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="sf-card-price-strike">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="sf-empty">
                <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
                <p>Belum ada produk{{ request('q') ? ' untuk pencarian ini' : ' di sini' }}.</p>
            </div>
        @endforelse
    </div>

    {{-- ── Pagination ── --}}
    @if($products->hasPages())
    <div style="padding: 0 0.75rem 2rem; display:flex; justify-content:center;">
        {{ $products->links() }}
    </div>
    @endif

</div>
@endsection
