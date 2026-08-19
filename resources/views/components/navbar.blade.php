@php
    $logo   = \App\Models\Setting::get('logo');
    $siteName = \App\Models\Setting::get('site_name', 'buyle.id');
    $siteTagline = \App\Models\Setting::get('site_tagline', 'Toko Serba Ada');
    $waNav  = \App\Models\WaSetting::primary();

    $navLinks = [
        ['url' => url('/'),          'label' => 'Beranda'],
        ['url' => route('about'),    'label' => 'Tentang Kami'],
        ['url' => route('products'), 'label' => 'Produk'],
        ['url' => route('gallery'),  'label' => 'Galeri'],
        ['url' => route('articles'), 'label' => 'Artikel'],
        ['url' => route('contact'),  'label' => 'Kontak'],
    ];

    $currentUrl = url()->current();

    // CTA labels
    $ctaLabel = 'Konsultasi';
@endphp

<style>
    /* ═══════════════════════════════════
       NAVBAR ENTRY ANIMATIONS (Premium)
    ═══════════════════════════════════ */
    @keyframes navDropIn {
        0%   { opacity: 0; transform: translateY(-24px) scale(0.97); filter: blur(4px); }
        100% { opacity: 1; transform: translateY(0)     scale(1);    filter: blur(0); }
    }
    @keyframes navFadeIn {
        0%   { opacity: 0; transform: translateY(-18px) scale(0.98); }
        100% { opacity: 1; transform: translateY(0)     scale(1); }
    }

    /* ═══════════════════════════════════
       NAVBAR LAYOUT
    ═══════════════════════════════════ */
    .pill-navbar-wrapper {
        position: fixed;
        top: 1rem;
        left: 0;
        width: 100%;
        z-index: 999;
        pointer-events: none;
    }
    .pill-navbar-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        pointer-events: auto;
    }

    /* Shared pill container */
    .nav-pill-box {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 999px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07), 0 1px 3px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    /* Scrolled State */
    @media (min-width: 992px) {
        .pill-navbar-inner.navbar-scrolled {
            background: #ffffff;
            border-radius: 999px;
            padding: 0.25rem 0.75rem;
            box-shadow: 0 10px 40px rgba(14, 165, 233, 0.12), 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid rgba(14, 165, 233, 0.1);
            gap: 0.5rem;
        }
        .pill-navbar-inner.navbar-scrolled .nav-pill-box {
            box-shadow: none;
            border: none;
            background: transparent;
        }
    }

    /* ── Logo Pill ── */
    .nav-pill-logo {
        padding: 0.35rem 1.35rem;
        height: 50px;
        width: auto;
        text-decoration: none;
        gap: 0.75rem;
        animation: navDropIn 0.9s cubic-bezier(0.22, 1, 0.36, 1) both;
        animation-delay: 0.05s;
        flex-shrink: 0;
    }
    .nav-logo-img-wrap {
        height: 38px;
        width: auto;
        max-width: none;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .nav-logo-img-wrap img {
        width: auto;
        height: 100%;
        max-width: none;
        object-fit: contain;
        display: block;
    }
    
    /* ── Menu Links Pill ── */
    .nav-pill-menu {
        padding: 0.3rem; gap: 0.1rem;
        animation: navFadeIn 0.95s cubic-bezier(0.22, 1, 0.36, 1) both;
        animation-delay: 0.2s;
    }
    .pill-link {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.75rem; font-weight: 500; color: #475569;
        text-decoration: none; padding: 0.45rem 0.75rem;
        border-radius: 999px; transition: color 0.2s, background 0.2s;
        white-space: nowrap;
    }
    .pill-link:hover { color: #0EA5E9; background: rgba(14,165,233,0.07); }
    .pill-link.active { background: #0369a1; color: #fff; font-weight: 600; }

    .cart-dropdown-wrap:hover .cart-dropdown-content {
        opacity: 1 !important;
        visibility: visible !important;
        transform: translateY(0) !important;
    }

    /* ── Desktop Search Styling ── */
    .desktop-search-form {
        flex: 1;
        max-width: 600px;
        padding: 0.25rem;
        animation: navFadeIn 0.95s cubic-bezier(0.22, 1, 0.36, 1) both;
        animation-delay: 0.15s;
    }
        /* ── Search Pill ── */
    .nav-pill-search {
        flex: 1;
        max-width: 600px;
        padding: 0.25rem;
        animation: navFadeIn 0.95s cubic-bezier(0.22, 1, 0.36, 1) both;
        animation-delay: 0.15s;
        position: relative;
    }
    .search-form {
        display: flex;
        align-items: center;
        width: 100%;
        background: #F8FAFC;
        border-radius: 999px;
        padding: 0.4rem 0.75rem 0.4rem 1rem;
        border: 1px solid #E2E8F0;
        transition: all 0.2s;
    }
    .search-form:focus-within {
        background: #fff;
        border-color: #0EA5E9;
        box-shadow: 0 0 0 3px rgba(14,165,233,0.1);
        border-radius: 999px;
    }
    .search-input {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.8rem;
        color: #1e293b;
        padding: 0.3rem 0;
    }
    .search-btn {
        background: #F1F5F9;
        border: none;
        color: #64748b;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }
    .search-btn:hover { background: #0EA5E9; color: #fff; }
    
    /* Search Dropdown */
    .search-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        left: 0.25rem;
        right: 0.25rem;
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease;
        z-index: 100;
        padding: 1rem;
    }
    .nav-pill-search:focus-within .search-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .search-dropdown-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #94A3B8;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .search-hist-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        border-radius: 6px;
        color: #475569;
        font-size: 0.8rem;
        text-decoration: none;
        transition: background 0.2s;
    }
    .search-hist-item:hover { background: #F1F5F9; color: #0EA5E9; }

    /* ── Action Buttons Pill ── */
    .nav-pill-actions {
        padding: 0.3rem; gap: 0.75rem;
        animation: navFadeIn 1s cubic-bezier(0.22, 1, 0.36, 1) both;
        animation-delay: 0.35s;
        align-items: center;
    }
    .pill-icon-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        background: transparent;
        transition: all 0.2s;
        text-decoration: none;
    }
    .pill-icon-btn:hover {
        color: #0EA5E9;
    }
    .auth-text-btn {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        padding: 0.4rem 0.5rem;
        transition: color 0.2s;
    }
    .auth-text-btn:hover { color: #0EA5E9; }
    
    .auth-solid-btn {
        font-family: 'Montserrat', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(135deg, #0EA5E9, #0369A1);
        text-decoration: none;
        padding: 0.5rem 1.25rem;
        border-radius: 999px;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 4px 14px rgba(14,165,233,0.35);
    }
    .auth-solid-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(14,165,233,0.45); }

    .auth-divider {
        width: 1px;
        height: 24px;
        background: #E2E8F0;
        margin: 0 0.25rem;
    }

    /* ── Mobile ── */
    .mobile-menu-btn {
        display: none;
        background: #fff; border: none; border-radius: 999px;
        width: 46px; height: 46px;
        align-items: center; justify-content: center;
        cursor: pointer; box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        color: #0EA5E9; pointer-events: auto;
        animation: navDropIn 0.9s cubic-bezier(0.22, 1, 0.36, 1) both;
        animation-delay: 0.05s;
    }
    #mobile-drawer.open { transform: translateX(0) !important; }

    @media (max-width: 991px) {
        .pill-navbar-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            background: rgba(248, 250, 252, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.03);
            display: flex;
            justify-content: center;
            z-index: 999;
            padding: 0.75rem 1rem;
            pointer-events: none;
        }
        .pill-navbar-inner {
            display: grid !important;
            grid-template-columns: 1fr auto;
            grid-template-rows: auto auto;
            grid-template-areas: 
                "logo actions"
                "search search";
            gap: 0.85rem;
            width: 100%;
            max-width: 100%;
            align-items: center;
            background: transparent !important;
            border-radius: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            box-sizing: border-box;
            pointer-events: auto;
        }
        
        .nav-pill-box {
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
            display: flex;
            align-items: center;
            min-width: 0;
        }

        /* Show logo pill on mobile */
        .nav-pill-logo { grid-area: logo; padding: 0 0.85rem !important; margin-right: 0 !important; height: 38px !important; width: auto !important; max-width: none !important; }
        .nav-logo-img-wrap { height: 32px; width: auto; max-width: none; display: flex; align-items: center; }
        .nav-logo-img-wrap img { height: 100%; width: auto; max-width: none; object-fit: contain; }
        
        /* Actions pill */
        .nav-pill-actions { grid-area: actions; padding: 0 !important; height: 32px !important; justify-content: flex-end; }
        .auth-solid-btn { padding: 0 0.85rem; font-size: 0.75rem; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 999px; margin: 0; line-height: 1; flex-shrink: 0; background: #fff; color: #0EA5E9; border: 1.5px solid #0EA5E9; box-shadow: none; font-weight: 700; }
        .auth-solid-btn:hover { background: #0EA5E9; color: #fff; box-shadow: 0 4px 12px rgba(14,165,233,0.2); }
        
        /* Search pill — Huge and modern */
        .nav-pill-search { grid-area: search; max-width: none; width: 100%; padding: 0 !important; min-width: 0; height: auto !important; }
        .search-form { 
            padding: 0 0.5rem 0 1rem; 
            border: 1px solid rgba(0,0,0,0.05); 
            border-radius: 16px; 
            height: 48px; 
            width: 100%; 
            background: #ffffff; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.04); 
            display: flex; 
            align-items: center; 
            min-width: 0; 
        }
        .search-input { font-size: 0.85rem; padding: 0; height: 100%; margin: 0; border: none; background: transparent; outline: none; flex: 1; min-width: 0; width: 100%; font-weight: 500; color: #334155; }
        .search-input::placeholder { color: #94A3B8; }
        .search-btn { width: 36px; height: 36px; border-radius: 12px; background: #FFF0F0; color: #F97316; display: flex; align-items: center; justify-content: center; margin-left: 0.5rem; flex-shrink: 0; border: none; }
        .search-btn svg { width: 18px; height: 18px; stroke-width: 2.5; }
        
        /* Make search icon blue to match theme */
        .search-btn { background: #F0F9FF; color: #0EA5E9; }

        /* Hide all icons except Daftar */
        .pill-icon-btn { display: flex !important; width: 32px; height: 32px; background: #fff; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.05); align-items: center; justify-content: center; }
        .pill-icon-btn svg { width: 16px; height: 16px; color: #64748B; }
        .cart-dropdown-wrap, .auth-text-btn, .auth-divider { display: none !important; } /* Hide cart and login text on mobile header since they go to bottom nav */
        
        body { padding-top: 115px !important; background-color: #F8FAFC !important; }
    }
    /* RTL support */
    [dir="rtl"] .pill-navbar-inner { flex-direction: row-reverse; }
</style>

<div class="pill-navbar-wrapper" id="mainNavbar">
    <div class="pill-navbar-inner">

        {{-- ── Logo Pill ── --}}
        <a href="/" class="nav-pill-box nav-pill-logo">
            <div class="nav-logo-img-wrap">
                @if($logo)
                    <img src="{{ asset('storage/'.$logo) }}" alt="Logo">
                @else
                    <span style="font-weight:900;color:#0EA5E9;font-size:1rem;font-family:'Montserrat',sans-serif;">buyle.id</span>
                @endif
            </div>
        </a>

                {{-- ── Search Pill ── --}}
        <div class="nav-pill-box nav-pill-search">
            <form action="{{ route('products') }}" method="GET" class="search-form">
                <input type="text" name="q" placeholder="Cari di buyle.id..." class="search-input" value="{{ request('q') }}" autocomplete="off">
                <button type="submit" class="search-btn" aria-label="Cari">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><path d="M21 21l-4.35-4.35"></path></svg>
                </button>
            </form>
            {{-- Search Dropdown (History & Dynamic Recommendations based on views/popularity) --}}
            @php
                $popularProducts = \Illuminate\Support\Facades\Cache::remember('popular_search_products', 600, function() {
                    return \App\Models\Product::where('is_active', true)
                        ->take(5)
                        ->get(['id', 'name', 'slug']);
                });
            @endphp
            <div class="search-dropdown">
                <div class="search-dropdown-title" style="display:flex; justify-content:space-between; align-items:center;">
                    <span>Pencarian Populer</span>
                    <span style="font-size:0.65rem; color:#94A3B8; font-weight:500;">Paling Banyak Dilihat</span>
                </div>
                <div style="display:flex; flex-direction:column; gap:0.25rem;">
                    @forelse($popularProducts as $pop)
                        <a href="{{ route('products', ['q' => $pop->name]) }}" class="search-hist-item">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                            <span style="flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $pop->name }}</span>
                            @if($pop->views_count > 0)
                                <span style="font-size:0.65rem; color:#94A3B8; font-weight:600; margin-left:auto; display:flex; align-items:center; gap:3px;">
                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    {{ number_format($pop->views_count, 0, ',', '.') }}
                                </span>
                            @endif
                        </a>
                    @empty
                        <a href="{{ route('products', ['q' => 'Peralatan Rumah']) }}" class="search-hist-item">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><path d="M21 21l-4.35-4.35"></path></svg>
                            <span>Semua Produk buyle.id</span>
                        </a>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── CTA Actions & Auth ── --}}
        <div class="nav-pill-box nav-pill-actions">
            
            {{-- Lacak Resi Icon (Box with search) --}}
            <a href="{{ url('/cek-resi') }}" class="pill-icon-btn" title="Lacak Pesanan">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                </svg>
            </a>
            
            {{-- Cart Dropdown --}}
            <div style="position:relative; display:inline-block;" class="cart-dropdown-wrap">
                <a href="{{ route('cart.index') }}" class="pill-icon-btn" title="Keranjang">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    @php
                        try {
                            $cartService = app(\App\Services\CartService::class);
                            $cartSummary = $cartService->getSummary();
                            $cartCount = $cartSummary['count'];
                            $cartItems = $cartSummary['items'];
                        } catch(\Exception $e) {
                            $cartCount = 0;
                            $cartItems = [];
                        }
                    @endphp
                    @if($cartCount > 0)
                        <span style="position:absolute; top:-4px; right:-4px; background:#EF4444; color:#fff; font-size:0.65rem; font-weight:800; width:18px; height:18px; display:flex; align-items:center; justify-content:center; border-radius:50%; box-shadow:0 2px 4px rgba(239,68,68,0.4);">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>
                
                {{-- Cart Dropdown Content --}}
                <div class="cart-dropdown-content" style="position:absolute; top:calc(100% + 15px); right:0; background:#fff; border:1px solid #E2E8F0; border-radius:16px; box-shadow:0 20px 40px rgba(0,0,0,0.1); width:320px; z-index:999; opacity:0; visibility:hidden; transform:translateY(10px); transition:all 0.3s; padding:1.25rem; font-family:'Montserrat',sans-serif;">
                    {{-- Caret Up Arrow --}}
                    <div style="position:absolute; top:-6px; right:16px; width:12px; height:12px; background:#fff; border-left:1px solid #E2E8F0; border-top:1px solid #E2E8F0; transform:rotate(45deg);"></div>
                    
                    @if(empty($cartItems))
                        <div style="text-align:center; padding:1rem 0;">
                            <img src="{{ asset('storage/cart-kosong.jpg') }}" alt="Keranjang Kosong" style="width:140px; height:auto; margin:0 auto 1rem;">
                            <h4 style="font-size:1rem; font-weight:700; color:#0f172a; margin-bottom:0.25rem;">Keranjang Masih Kosong</h4>
                            <p style="font-size:0.8rem; color:#64748B;">Yuk, cari perlengkapan rumah impianmu sekarang!</p>
                        </div>
                    @else
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; padding-bottom:0.75rem; border-bottom:1px solid #F1F5F9;">
                            <span style="font-size:0.875rem; font-weight:700; color:#0f172a;">Keranjang ({{ $cartCount }})</span>
                            <a href="{{ route('cart.index') }}" style="font-size:0.8rem; color:#0EA5E9; text-decoration:none; font-weight:600;">Lihat Semua</a>
                        </div>
                        <div style="max-height:240px; overflow-y:auto; margin-bottom:1rem; display:flex; flex-direction:column; gap:0.75rem;">
                            @php $dropdownTotal = 0; @endphp
                            @foreach($cartItems->take(3) as $cartItem)
                                @php $dropdownTotal += $cartItem->subtotal; @endphp
                                <div style="display:flex; gap:0.75rem; align-items:center;">
                                    <div style="width:50px; height:50px; border-radius:8px; background:#F1F5F9; overflow:hidden; flex-shrink:0;">
                                        @if($cartItem->product && !empty($cartItem->product->image))
                                            <img src="{{ asset('storage/'.$cartItem->product->image) }}" alt="{{ $cartItem->product->name }}" style="width:100%; height:100%; object-fit:cover;">
                                        @else
                                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#94A3B8;">
                                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div style="flex:1; min-width:0;">
                                        <div style="font-size:0.825rem; font-weight:600; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $cartItem->product->name ?? 'Produk Dihapus' }}</div>
                                        <div style="font-size:0.75rem; color:#64748B; margin-top:0.2rem;">{{ $cartItem->qty }} x Rp {{ number_format($cartItem->unit_price, 0, ',', '.') }}</div>
                                    </div>
                                    <div style="font-size:0.825rem; font-weight:700; color:#0EA5E9;">
                                        Rp {{ number_format($cartItem->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('cart.index') }}" style="display:block; width:100%; padding:0.55rem; text-align:center; background:#0F172A; color:#fff; font-size:0.78rem; font-weight:600; font-family:'Montserrat',sans-serif; text-decoration:none; border-radius:8px; transition:background 0.2s; letter-spacing:0.01em;" onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0F172A'">
                            Lanjut ke Pembayaran
                        </a>
                    @endif
                </div>
            </div>

            {{-- Share Dropdown --}}
            <div style="position:relative; display:inline-block;" class="share-dropdown-wrap">
                <button class="pill-icon-btn share-trigger" title="Bagikan" style="background:none;border:none;cursor:pointer;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                    </svg>
                </button>
                {{-- Share Dropdown Content --}}
                <div class="share-dropdown-content" style="position:absolute; top:calc(100% + 15px); right:-10px; background:#fff; border:1px solid #E2E8F0; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.08); padding:0.75rem; z-index:999; opacity:0; visibility:hidden; transform:translateY(10px); transition:all 0.3s; display:flex; gap:0.5rem;">
                    {{-- Caret --}}
                    <div style="position:absolute; top:-6px; right:20px; width:12px; height:12px; background:#fff; border-left:1px solid #E2E8F0; border-top:1px solid #E2E8F0; transform:rotate(45deg);"></div>
                    <a href="#" onclick="shareWa()" style="width:32px;height:32px;border-radius:50%;background:#22C55E;display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="WhatsApp">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    </a>
                    <a href="#" onclick="shareIg()" style="width:32px;height:32px;border-radius:50%;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="Instagram">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/></svg>
                    </a>
                    <a href="#" onclick="shareFb()" style="width:32px;height:32px;border-radius:50%;background:#1877F2;display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="Facebook">
                        <svg width="10" height="18" viewBox="0 0 10 18" fill="currentColor"><path d="M6.5 18V10h2.7l.4-3H6.5V5.1c0-.9.2-1.5 1.5-1.5H9.7V.1C9.4.1 8.4 0 7.3 0 4.7 0 3 1.5 3 4.4V7H.3v3H3v8h3.5z"/></svg>
                    </a>
                    <a href="#" onclick="shareX()" style="width:32px;height:32px;border-radius:50%;background:#000;display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="X (Twitter)">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.261 5.632zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <button onclick="copyLink()" style="width:32px;height:32px;border-radius:50%;background:#F1F5F9;border:none;display:flex;align-items:center;justify-content:center;color:#475569;cursor:pointer;transition:transform 0.2s, background 0.2s;" onmouseover="this.style.transform='scale(1.1)'; this.style.background='#E2E8F0';" onmouseout="this.style.transform='scale(1)'; this.style.background='#F1F5F9';" title="Salin Tautan">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    </button>
                </div>
            </div>
            <script>
            (function(){
                const wrap = document.querySelector('.share-dropdown-wrap');
                const dd = wrap ? wrap.querySelector('.share-dropdown-content') : null;
                if(wrap && dd) {
                    wrap.addEventListener('mouseenter', ()=>{ dd.style.opacity='1'; dd.style.visibility='visible'; dd.style.transform='translateY(0)'; });
                    wrap.addEventListener('mouseleave', ()=>{ dd.style.opacity='0'; dd.style.visibility='hidden'; dd.style.transform='translateY(10px)'; });
                }
            })();
            function shareWa(){ window.open('https://wa.me/?text='+encodeURIComponent(document.title+' '+location.href),'_blank'); }
            function shareIg(){ navigator.clipboard.writeText(location.href).then(()=>alert('Link disalin! Paste di Instagram Story atau Bio.')); }
            function shareFb(){ window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),'_blank','width=600,height=400'); }
            function shareX(){ window.open('https://x.com/intent/tweet?url='+encodeURIComponent(location.href)+'&text='+encodeURIComponent(document.title),'_blank','width=600,height=400'); }
            function copyLink(){ navigator.clipboard.writeText(location.href).then(()=>{ const t=document.getElementById('copyLinkText'); if(t){ t.textContent='Tersalin!'; setTimeout(()=>t.textContent='Salin Tautan',2000); } }); }
            </script>

            <div class="auth-divider"></div>
            
            @auth
                {{-- User is logged in - show account dropdown --}}
                <div style="position:relative;" id="user-menu-wrap">
                    <button onclick="document.getElementById('userDropdown').classList.toggle('open')"
                        style="display:flex;align-items:center;gap:0.5rem;background:#F1F5F9;border:1.5px solid #E2E8F0;border-radius:999px;padding:0.4rem 0.9rem 0.4rem 0.4rem;cursor:pointer;font-family:'Montserrat',sans-serif;font-size:0.825rem;font-weight:600;color:#374151;">
                        @if(Auth::user()->avatar && str_starts_with(Auth::user()->avatar, 'http'))
                            <img src="{{ Auth::user()->avatar }}" alt="" style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                        @elseif(Auth::user()->avatar)
                            <img src="{{ asset('storage/'.Auth::user()->avatar) }}" alt="" style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                        @else
                            <span style="width:28px;height:28px;border-radius:50%;background:#0EA5E9;color:#fff;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800;">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</span>
                        @endif
                        {{ explode(' ', Auth::user()->name)[0] }}
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div id="userDropdown" style="position:absolute;top:calc(100% + 8px);right:0;background:#fff;border:1px solid #E2E8F0;border-radius:14px;box-shadow:0 16px 40px rgba(0,0,0,0.1);min-width:200px;padding:0.5rem;display:none;z-index:999;">
                        <a href="{{ route('account.overview') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.65rem 0.875rem;border-radius:10px;text-decoration:none;color:#374151;font-size:0.85rem;font-weight:600;transition:background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Akun Saya
                        </a>
                        <a href="{{ route('account.orders') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.65rem 0.875rem;border-radius:10px;text-decoration:none;color:#374151;font-size:0.85rem;font-weight:600;transition:background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/></svg>
                            Pesanan Saya
                        </a>
                        <a href="{{ route('account.wishlist') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.65rem 0.875rem;border-radius:10px;text-decoration:none;color:#374151;font-size:0.85rem;font-weight:600;transition:background 0.15s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            Wishlist
                        </a>
                        <a href="{{ route('account.cart') }}" style="display:flex;align-items:center;gap:0.6rem;padding:0.65rem 0.875rem;border-radius:10px;text-decoration:none;color:#374151;font-size:0.85rem;font-weight:600;transition:background 0.15s;" onmouseover="this.style.background='#F0F9FF'" onmouseout="this.style.background='transparent'">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            Keranjang
                        </a>
                        <hr style="border:none;border-top:1px solid #F1F5F9;margin:0.4rem 0;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" style="display:flex;align-items:center;gap:0.6rem;width:100%;padding:0.65rem 0.875rem;border-radius:10px;text-decoration:none;color:#EF4444;font-size:0.85rem;font-weight:600;background:none;border:none;cursor:pointer;font-family:'Montserrat',sans-serif;" onmouseover="this.style.background='#FEF2F2'" onmouseout="this.style.background='transparent'">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                <script>
                document.addEventListener('click', function(e) {
                    if (!document.getElementById('user-menu-wrap')?.contains(e.target)) {
                        document.getElementById('userDropdown')?.classList.remove('open');
                    }
                });
                document.getElementById('userDropdown')?.addEventListener('transitionend', function(){});
                const ud = document.getElementById('userDropdown');
                if(ud) { const obs = new MutationObserver(()=>{ ud.style.display = ud.classList.contains('open') ? 'block' : 'none'; }); obs.observe(ud, {attributes:true, attributeFilter:['class']}); }
                </script>
            @else
                {{-- Text Button Masuk --}}
                <a href="{{ route('login') }}" class="auth-text-btn">Masuk</a>
                
                {{-- Solid Button Daftar --}}
                <a href="{{ route('register') }}" class="auth-solid-btn">Daftar</a>
            @endauth
        </div>

        {{-- ── Mobile Toggle ── --}}
        <button class="mobile-menu-btn" onclick="document.getElementById('mobile-drawer').classList.add('open')" aria-label="Menu">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
</div>

{{-- ── Mobile Drawer ── --}}
<div id="mobile-drawer" style="position:fixed;inset:0;background:rgba(255,255,255,0.98);backdrop-filter:blur(16px);z-index:9999;display:flex;flex-direction:column;padding:2rem;transform:translateX(100%);transition:transform 0.4s cubic-bezier(0.22,1,0.36,1);">
    <button aria-label="Close Menu" onclick="document.getElementById('mobile-drawer').classList.remove('open')" style="position:absolute;top:1.25rem;right:1.25rem;background:transparent;border:none;color:#475569;cursor:pointer;">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:2.5rem;">
        @if($logo)
            <img src="{{ asset('storage/'.$logo) }}" alt="Logo" style="height:38px; width:38px; object-fit:cover; border-radius:50%;">
        @else
            <span style="font-weight:900;color:#0EA5E9;font-size:1.4rem;">{{ $siteName }}</span>
        @endif
    </div>
    <nav style="display:flex;flex-direction:column;gap:1.25rem;">
        {{-- Mobile Search Form (Inside Drawer) --}}
        <div style="margin-bottom: 1.5rem;">
            <form action="{{ route('products') }}" method="GET" style="display:flex; background:#F1F5F9; border-radius:999px; padding:0.4rem 0.8rem; align-items:center;">
                <input type="text" name="q" placeholder="Cari produk..." value="{{ request('q') }}" autocomplete="off" style="flex:1; border:none; background:transparent; outline:none; font-family:'Montserrat',sans-serif; font-size:0.9rem; padding:0.25rem;">
                <button type="submit" aria-label="Cari produk" style="background:none; border:none; color:#94A3B8; display:flex; align-items:center;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><path d="M21 21l-4.35-4.35"></path></svg>
                </button>
            </form>
        </div>

        @foreach($navLinks as $link)
            <a href="{{ $link['url'] }}" style="font-family:'Montserrat',sans-serif;font-size:1.1rem;font-weight:600;color:{{ $currentUrl == $link['url'] ? '#0EA5E9' : '#1e293b' }};text-decoration:none;transition:color .2s;">
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>



    <div style="margin-top:auto; display:flex; flex-direction:column; gap:1rem;">
        {{-- Mobile Auth --}}
        <div style="display:flex; gap:0.75rem;">
            @auth
                <a href="{{ route('account.overview') }}" style="flex:1; text-align:center; padding:0.875rem; border-radius:999px; font-family:'Montserrat',sans-serif; font-weight:600; text-decoration:none; color:#0EA5E9; background:#F0F9FF; border:1px solid #BAE6FD;">
                    Akun Saya
                </a>
                <form method="POST" action="{{ route('logout') }}" style="flex:1;">
                    @csrf
                    <button type="submit" style="width:100%; padding:0.875rem; border-radius:999px; font-family:'Montserrat',sans-serif; font-weight:600; color:#fff; background:#EF4444; border:none; cursor:pointer;">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" style="flex:1; text-align:center; padding:0.875rem; border-radius:999px; font-family:'Montserrat',sans-serif; font-weight:600; text-decoration:none; color:#0EA5E9; background:#F0F9FF; border:1px solid #BAE6FD;">
                    Masuk
                </a>
                <a href="{{ route('register') }}" style="flex:1; text-align:center; padding:0.875rem; border-radius:999px; font-family:'Montserrat',sans-serif; font-weight:600; text-decoration:none; color:#fff; background:#0EA5E9; box-shadow:0 4px 14px rgba(14,165,233,.3);">
                    Daftar
                </a>
            @endauth
        </div>

        @if($waNav)
            @php
                $waNumberMobile = preg_replace('/[^0-9]/', '', $waNav->nomor_wa ?? '');
                if(str_starts_with($waNumberMobile, '0')) {
                    $waNumberMobile = '62' . substr($waNumberMobile, 1);
                }
            @endphp
            <a href="https://wa.me/{{ $waNumberMobile }}" target="_blank" style="display:block;background:#10B981;color:#fff;text-align:center;padding:.875rem;border-radius:999px;font-family:'Montserrat',sans-serif;font-weight:600;text-decoration:none;box-shadow:0 4px 14px rgba(16,185,129,.3);">
                Hubungi via WhatsApp
            </a>
        @endif
    </div>
</div>

<script>


document.addEventListener('DOMContentLoaded', function() {
    // Scroll effect
    const navbar = document.querySelector('.pill-navbar-inner');
    if (navbar) {
        window.addEventListener('scroll', function() {
            navbar.classList.toggle('navbar-scrolled', window.scrollY > 50);
        });
    }
});
</script>
