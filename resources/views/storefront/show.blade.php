@extends('layouts.app')

@section('title', $profile->meta_title ?: $profile->store_name . ' - Storefront')
@section('meta_description', $profile->meta_desc ?: $profile->store_description)
@section('meta_keywords', $profile->meta_keywords)

@section('content')
<style>
    /* Creator Storefront Styles */
    .store-hero {
        background: linear-gradient(135deg, rgba(30,179,73,0.05), rgba(165,207,55,0.1));
        padding: 3rem 1rem;
        text-align: center;
        border-bottom: 1px solid rgba(30,179,73,0.1);
        position: relative;
        overflow: hidden;
    }
    .store-hero::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(165,207,55,0.08) 0%, rgba(255,255,255,0) 60%);
        pointer-events: none;
    }
    .store-avatar {
        width: 100px; height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 8px 24px rgba(30,179,73,0.15);
        margin: 0 auto 1.5rem;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 800; color: #94A3B8;
        position: relative; z-index: 1;
    }
    .store-title {
        font-size: 1.75rem; font-weight: 800; color: #1E293B;
        margin-bottom: 0.5rem; font-family: 'Outfit', sans-serif;
        position: relative; z-index: 1;
    }
    .store-desc {
        font-size: 0.95rem; color: #64748B; max-width: 600px; margin: 0 auto;
        line-height: 1.6; position: relative; z-index: 1;
    }
    
    .store-nav {
        display: flex; gap: 0.5rem; overflow-x: auto; padding: 1.5rem 1rem;
        max-width: 1200px; margin: 0 auto;
        scrollbar-width: none; /* Firefox */
    }
    .store-nav::-webkit-scrollbar { display: none; }
    
    .nav-pill {
        padding: 0.6rem 1.25rem; border-radius: 99px;
        background: #F1F5F9; color: #64748B; font-size: 0.875rem; font-weight: 600;
        text-decoration: none; white-space: nowrap; transition: all 0.2s;
        border: 1px solid transparent;
    }
    .nav-pill:hover { background: #E2E8F0; color: #1E293B; }
    .nav-pill.active {
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        color: #fff; box-shadow: 0 4px 12px rgba(30,179,73,0.3);
    }
    
    .products-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem; max-width: 1200px; margin: 0 auto 3rem; padding: 0 1rem;
    }
    
    .product-card {
        background: #fff; border-radius: 16px; border: 1px solid #F1F5F9;
        overflow: hidden; transition: all 0.3s;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.02);
        display: flex; flex-direction: column;
        text-decoration: none; color: inherit;
    }
    .product-card:hover {
        transform: translateY(-4px); box-shadow: 0 12px 24px rgba(30,179,73,0.1);
        border-color: rgba(30,179,73,0.2);
    }
    .product-img-wrap {
        width: 100%; aspect-ratio: 16/9; overflow: hidden; background: #F8FAFC;
    }
    .product-img {
        width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;
    }
    .product-card:hover .product-img { transform: scale(1.05); }
    .product-info { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .product-name {
        font-size: 1.05rem; font-weight: 700; color: #1E293B;
        margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .product-price {
        font-size: 1.15rem; font-weight: 800; color: #1eb349;
        margin-top: auto; display: flex; align-items: center; justify-content: space-between;
    }
    
    .empty-state {
        text-align: center; padding: 4rem 1rem; color: #94A3B8; grid-column: 1/-1;
    }

    @media (max-width: 640px) {
        .store-hero { padding: 2rem 1rem; }
        .store-avatar { width: 80px; height: 80px; margin-bottom: 1rem; }
        .store-title { font-size: 1.5rem; }
        .store-desc { font-size: 0.875rem; }
        .products-grid { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; }
    }
</style>

<div class="storefront-wrapper">
    <!-- Hero Section -->
    <div class="store-hero">
        @if($seller->avatar)
            <img src="{{ asset('storage/' . $seller->avatar) }}" alt="{{ $profile->store_name }}" class="store-avatar">
        @else
            <div class="store-avatar">{{ strtoupper(substr($profile->store_name ?: $seller->name, 0, 2)) }}</div>
        @endif
        
        <h1 class="store-title">{{ $profile->store_name ?: $seller->name }}</h1>
        @if($profile->store_description)
            <p class="store-desc">{{ $profile->store_description }}</p>
        @endif
    </div>

    <!-- Navigation / Categories -->
    <div class="store-nav">
        <a href="{{ route('store.show', $profile->store_slug) }}" class="nav-pill {{ !request()->has('group') ? 'active' : '' }}">Semua Produk</a>
        @foreach($groups as $group)
            <a href="{{ route('store.show', ['slug' => $profile->store_slug, 'group' => $group->slug]) }}" class="nav-pill {{ request('group') === $group->slug ? 'active' : '' }}">
                {{ $group->name }}
            </a>
        @endforeach
    </div>

    <!-- Products Grid -->
    <div class="products-grid">
        @forelse($products as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="product-card">
                <div class="product-img-wrap">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-img">
                </div>
                <div class="product-info">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <div class="product-price">
                        <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 1rem; opacity:0.5;">
                    <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"></path><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
                <p>Belum ada produk di kategori ini.</p>
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
    <div style="max-width:1200px; margin:0 auto 3rem; padding:0 1rem; display:flex; justify-content:center;">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
