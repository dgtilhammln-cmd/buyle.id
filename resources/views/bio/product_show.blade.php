<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $product = $product ?? null;
        $images = $block->data_json['images'] ?? [];
        if (empty($images) && !empty($block->data_json['image'])) {
            $images = [$block->data_json['image']];
        }
        if ($product) {
            $pGallery = $product->gallery;
            if (is_string($pGallery)) { $pGallery = json_decode($pGallery, true) ?: []; }
            if (is_array($pGallery) && !empty($pGallery)) {
                $mainImg = $product->image ? [$product->image] : [];
                $merged = array_unique(array_merge($mainImg, $pGallery));
                if (!empty($merged)) { $images = array_values($merged); }
            } elseif (empty($images) && !empty($product->image)) {
                $images = [$product->image];
            }
        }
        $prodTitle   = !empty($block->title) ? $block->title : ($product->name ?? 'Produk');
        $price       = $block->data_json['price'] ?? $block->data_json['custom_price'] ?? ($product ? ($product->is_on_sale ? $product->sale_price : $product->effective_price) : 0);
        $origPrice   = $block->data_json['original_price'] ?? ($product && $product->is_on_sale ? $product->price : null);
        $paymentMethod = $block->data_json['payment_method'] ?? (in_array($block->type, ['buyle_product', 'buyle_affiliate']) ? 'web' : 'wa');
        $waText      = $block->data_json['wa_text'] ?? '';
        $waNumber    = $config['wa'] ?? '';
        $waMessage   = 'Halo, saya mendapatkan nomor dari buyle.id. ' . ($waText ?: 'Saya tertarik dengan produk *' . $prodTitle . '* (Rp ' . number_format($price, 0, ',', '.') . '). Apakah masih tersedia?');
        $rawCat      = $block->data_json['category'] ?? ($product && $product->category ? $product->category->name : null);
        $pType       = strtolower($product->product_type ?? $product->type ?? '');

        if (in_array($pType, ['external_link', 'file_upload', 'digital'])) { $category = 'Produk Digital'; }
        elseif ($pType === 'ticket') { $category = 'Tiket Event'; }
        elseif (in_array($pType, ['service', 'jasa'])) { $category = 'Jasa / Layanan'; }
        elseif (in_array($pType, ['makanan', 'fnb', 'food'])) { $category = 'Makanan'; }
        elseif (in_array($pType, ['physical', 'barang', 'product', 'fisik'])) { $category = 'Barang / Fisik'; }
        elseif (!empty($rawCat)) { $category = ucfirst($rawCat); }
        else { $category = 'Produk'; }

        $stock       = isset($block->data_json['stock']) && $block->data_json['stock'] !== '' && $block->data_json['stock'] !== null ? (int)$block->data_json['stock'] : ($product ? $product->stock : null);
        $isOutOfStock = ($stock !== null && $stock <= 0);
        $firstImage  = !empty($images[0]) ? (\Illuminate\Support\Str::startsWith($images[0], 'http') ? $images[0] : asset('storage/' . $images[0])) : asset('images/buyle-og.png');
        $bioName     = $config['name'] ?? $profile->store_name ?? $username;
        $pageTitle   = $prodTitle . ' - ' . $bioName . ' | buyle.id';
        $rawDesc     = !empty($block->data_json['description']) ? $block->data_json['description'] : ($product ? ($product->description ?: $product->short_desc) : '');
        $shortDesc   = !empty($rawDesc) ? \Illuminate\Support\Str::limit(strip_tags($rawDesc), 160) : '';
        $pageDesc    = !empty($shortDesc) ? $shortDesc : 'Beli ' . $prodTitle . ' berkualitas dengan harga terbaik dari ' . $bioName . ' di buyle.id.';
        $hasDiscount = !empty($origPrice) && $origPrice > $price;
        $discountPct = $hasDiscount ? round((($origPrice - $price) / $origPrice) * 100) : 0;
        $ratingVal   = ($product && !empty($product->rating) && $product->rating > 0) ? number_format($product->rating, 1) : '5.0';
        $formattedImages = array_map(function($img) {
            return (\Illuminate\Support\Str::startsWith($img, 'http://') || \Illuminate\Support\Str::startsWith($img, 'https://')) ? $img : asset('storage/' . $img);
        }, $images);

        $currentTheme = $theme ?? ($profile->bio_theme ?? 'theme1');
        $homeUrl = !empty($profile->custom_domain) ? 'https://' . rtrim($profile->custom_domain, '/') : url('/' . $username);
        $productsUrl = !empty($profile->custom_domain) ? 'https://' . rtrim($profile->custom_domain, '/') . '/produk' : url('/' . $username . '/produk');
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="{{ $prodTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:image" content="{{ $firstImage }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="product">
    <meta property="product:price:amount" content="{{ $price }}">
    <meta property="product:price:currency" content="IDR">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=4">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=4">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "{{ e($prodTitle) }}",
        "image": "{{ $firstImage }}",
        "description": "{{ e(strip_tags($pageDesc)) }}",
        "offers": {
            "@type": "Offer",
            "url": "{{ url()->current() }}",
            "priceCurrency": "IDR",
            "price": "{{ $price }}",
            "availability": "{{ $isOutOfStock ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock' }}"
        }
    }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* ── THEME PALETTES (TEMA 1 - 4) ── */
        /* Theme 1: Dark Mode */
        body.theme-theme1 {
            background: #0f172a;
            color: #f8fafc;
            --card-bg: #1e293b;
            --card-border: #334155;
            --text-main: #f8fafc;
            --text-sub: #94a3b8;
            --price-color: #34d399;
            --accent: #10b981;
            --topbar-bg: rgba(15, 23, 42, 0.95);
            --topbar-border: rgba(255, 255, 255, 0.1);
            --topbar-text: #ffffff;
            --img-bg: #0f172a;
        }

        /* Theme 2: Slate Light */
        body.theme-theme2 {
            background: #f8fafc;
            color: #0f172a;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --text-main: #0f172a;
            --text-sub: #64748b;
            --price-color: #2563eb;
            --accent: #2563eb;
            --topbar-bg: rgba(255, 255, 255, 0.95);
            --topbar-border: #e2e8f0;
            --topbar-text: #0f172a;
            --img-bg: #f1f5f9;
        }

        /* Theme 3: Emerald Gradient */
        body.theme-theme3 {
            background: linear-gradient(135deg, #a5cf37, #1eb349);
            background-attachment: fixed;
            color: #0f172a;
            --card-bg: rgba(255, 255, 255, 0.95);
            --card-border: rgba(255, 255, 255, 0.3);
            --text-main: #0f172a;
            --text-sub: #475569;
            --price-color: #16a34a;
            --accent: #1eb349;
            --topbar-bg: rgba(30, 179, 73, 0.95);
            --topbar-border: rgba(255, 255, 255, 0.2);
            --topbar-text: #ffffff;
            --img-bg: #f8fafc;
        }

        /* Theme 4: Clean Modern Light */
        body.theme-theme4 {
            background: #f1f5f9;
            color: #1e293b;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --text-main: #1e293b;
            --text-sub: #64748b;
            --price-color: #16a34a;
            --accent: #1eb349;
            --topbar-bg: rgba(255, 255, 255, 0.95);
            --topbar-border: #e2e8f0;
            --topbar-text: #1e293b;
            --img-bg: #f8fafc;
        }

        /* ── TOPBAR NAV ── */
        .ps-topbar {
            position: sticky; top: 0; z-index: 100;
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--topbar-border);
            backdrop-filter: blur(12px);
            padding: 0.75rem 1.25rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .ps-topbar-back {
            display: inline-flex; align-items: center; gap: 0.4rem;
            color: var(--topbar-text); text-decoration: none;
            font-size: 0.85rem; font-weight: 600; transition: opacity 0.2s;
        }
        .ps-topbar-back:hover { opacity: 0.8; }
        .ps-topbar-brand {
            font-size: 0.82rem; font-weight: 700; color: var(--topbar-text);
            text-decoration: none; opacity: 0.9; display: flex; align-items: center; gap: 0.35rem;
        }
        .ps-topbar-brand img { width: 20px; height: 20px; border-radius: 4px; }

        /* ── PAGE LAYOUT ── */
        .ps-wrap {
            max-width: 900px;
            margin: 1.5rem auto 3rem;
            padding: 0 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* ── MAIN PRODUCT CARD ── */
        .ps-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            overflow: hidden;
            display: grid;
            grid-template-columns: minmax(0, 400px) 1fr;
        }

        /* ── GALLERY ── */
        .ps-gallery {
            background: var(--img-bg);
            border-right: 1px solid var(--card-border);
            display: flex; flex-direction: column;
            position: relative;
        }
        .ps-slider-wrap {
            position: relative; overflow: hidden; aspect-ratio: 1/1; width: 100%;
        }
        .ps-slider-track { display: flex; height: 100%; transition: transform 0.35s ease; }
        .ps-slide { min-width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
        .ps-slide img { width: 100%; height: 100%; object-fit: contain; display: block; }
        .ps-no-img {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            height: 100%; gap: 0.6rem; color: var(--text-sub); opacity: 0.6;
        }
        .ps-btn-prev, .ps-btn-next {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(0,0,0,0.5); color: #fff; border: none;
            width: 32px; height: 32px; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center; z-index: 5;
            transition: background 0.2s;
        }
        .ps-btn-prev { left: 8px; }
        .ps-btn-next { right: 8px; }
        .ps-disc-badge {
            position: absolute; top: 10px; left: 10px; z-index: 4;
            background: #ef4444; color: #fff; font-size: 0.68rem; font-weight: 700;
            padding: 0.2rem 0.5rem; border-radius: 6px;
        }
        .ps-thumbs {
            display: flex; gap: 0.4rem; padding: 0.6rem; overflow-x: auto;
            border-top: 1px solid var(--card-border);
        }
        .ps-thumb {
            width: 50px; height: 50px; border-radius: 6px; overflow: hidden;
            border: 2px solid var(--card-border); cursor: pointer; flex-shrink: 0;
            transition: border-color 0.2s;
        }
        .ps-thumb.active { border-color: var(--accent); }
        .ps-thumb img { width: 100%; height: 100%; object-fit: cover; }

        /* ── PRODUCT INFO ── */
        .ps-info {
            padding: 1.5rem;
            display: flex; flex-direction: column; gap: 1rem;
        }
        .ps-stock-badge {
            font-size: 0.72rem; font-weight: 600;
        }
        .stock-in { color: #10b981; }
        .stock-out { color: #ef4444; }
        .stock-unlimited { color: var(--text-sub); }

        .ps-title {
            font-size: 1.3rem; font-weight: 700; color: var(--text-main); line-height: 1.3;
        }

        .ps-price-box {
            display: flex; align-items: baseline; gap: 0.6rem; flex-wrap: wrap;
        }
        .ps-price { font-size: 1.5rem; font-weight: 800; color: var(--price-color); }
        .ps-orig { font-size: 0.85rem; color: var(--text-sub); text-decoration: line-through; }
        .ps-save { font-size: 0.68rem; font-weight: 700; color: #ef4444; background: rgba(239,68,68,0.1); padding: 0.15rem 0.45rem; border-radius: 4px; }

        /* Seller card */
        .ps-seller {
            display: flex; align-items: center; justify-content: space-between;
            border: 1px solid var(--card-border); border-radius: 10px; padding: 0.6rem 0.9rem;
            background: rgba(0,0,0,0.02);
        }
        .ps-seller-l { display: flex; align-items: center; gap: 0.55rem; }
        .ps-av-img { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent); }
        .ps-av-fb { width: 34px; height: 34px; border-radius: 50%; background: var(--accent); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; }
        .ps-seller-name { font-size: 0.8rem; font-weight: 700; color: var(--text-main); }
        .ps-seller-sub { font-size: 0.67rem; color: var(--text-sub); }
        .ps-seller-link { font-size: 0.75rem; font-weight: 700; color: var(--accent); text-decoration: none; }

        /* CTA Buttons */
        .ps-cta { display: flex; flex-direction: column; gap: 0.5rem; margin-top: auto; }
        .ps-btn-buy {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            width: 100%; padding: 0.85rem 1.2rem; border-radius: 10px;
            background: #25D366; color: #fff;
            font-family: 'Montserrat', sans-serif; font-size: 0.9rem; font-weight: 700;
            text-decoration: none; border: none; cursor: pointer;
            transition: transform 0.2s, opacity 0.2s;
            box-shadow: 0 4px 14px rgba(37,211,102,0.3);
        }
        .ps-btn-buy:hover { transform: translateY(-2px); opacity: 0.95; }
        .ps-btn-buy:active { transform: translateY(0); }

        .ps-cta-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.55rem; }
        .ps-btn-cart {
            display: flex; align-items: center; justify-content: center; gap: 0.45rem;
            width: 100%; padding: 0.82rem 1rem; border-radius: 10px;
            background: transparent; color: var(--text-main);
            border: 2px solid var(--card-border);
            font-family: 'Montserrat', sans-serif; font-size: 0.87rem; font-weight: 700;
            text-decoration: none; cursor: pointer; transition: all 0.2s ease;
        }
        .ps-btn-cart:hover { border-color: var(--accent); color: var(--accent); }
        .ps-btn-checkout {
            display: flex; align-items: center; justify-content: center; gap: 0.45rem;
            width: 100%; padding: 0.82rem 1rem; border-radius: 10px;
            background: var(--accent); color: #fff; border: 2px solid transparent;
            font-family: 'Montserrat', sans-serif; font-size: 0.87rem; font-weight: 700;
            text-decoration: none; cursor: pointer; transition: transform 0.2s ease;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
        }
        .ps-btn-checkout:hover { transform: translateY(-2px); color: #fff; }

        /* ── DESCRIPTION CARD ── */
        .ps-desc-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .ps-desc-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--card-border);
            font-size: 0.88rem; font-weight: 700; color: var(--text-main);
            display: flex; align-items: center; gap: 0.4rem;
        }
        .ps-desc-body {
            padding: 1.25rem;
            font-size: 0.88rem; color: var(--text-main);
            line-height: 1.75; word-break: break-word;
        }
        .ps-desc-body span, .ps-desc-body p, .ps-desc-body div { max-width: 100%; }
        .ps-desc-body p { margin-bottom: 0.65rem; }
        .ps-desc-body ul, .ps-desc-body ol { padding-left: 1.25rem; margin-bottom: 0.65rem; }
        .ps-desc-body h1, .ps-desc-body h2, .ps-desc-body h3 { color: var(--text-main); font-weight: 700; margin-bottom: 0.4rem; }
        .ps-desc-body img { max-width: 100%; height: auto; border-radius: 8px; margin: 0.5rem 0; }
        .ps-desc-body table { width: 100%; border-collapse: collapse; margin-bottom: 0.65rem; }
        .ps-desc-body table td, .ps-desc-body table th { border: 1px solid var(--card-border); padding: 0.4rem 0.6rem; font-size: 0.82rem; }

        @media (max-width: 768px) {
            .ps-wrap { margin: 1rem auto 2rem; padding: 0 0.75rem; }
            .ps-card { grid-template-columns: 1fr; border-radius: 14px; }
            .ps-gallery { border-right: none; border-bottom: 1px solid var(--card-border); }
            .ps-slider-wrap { aspect-ratio: 4/3; }
            .ps-info { padding: 1.15rem; gap: 0.85rem; }
            .ps-title { font-size: 1.15rem; }
            .ps-price { font-size: 1.3rem; }
        }
    </style>
</head>
<body class="theme-{{ $currentTheme }}">

    {{-- TOPBAR --}}
    <nav class="ps-topbar">
        <a href="{{ url('/' . $username) }}" class="ps-topbar-back">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali ke Profil
        </a>
        <a href="{{ $homeUrl }}" class="ps-topbar-brand">
            <img src="{{ asset('images/buyle-logo.png') }}" alt="buyle.id" onerror="this.style.display='none'">
            buyle.id
        </a>
    </nav>

    <div class="ps-wrap">

        @if(session('error'))
            <div style="background:#fef2f2;color:#dc2626;border:1px solid #fca5a5;padding:0.75rem 1rem;border-radius:10px;font-size:0.82rem;font-weight:600;">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:0.75rem 1rem;border-radius:10px;font-size:0.82rem;font-weight:600;">{{ session('success') }}</div>
        @endif

        {{-- MAIN CARD: GALLERY + INFO --}}
        <div class="ps-card">

            {{-- GALLERY --}}
            <div class="ps-gallery">
                <div class="ps-slider-wrap" id="psWrap">
                    @if($hasDiscount)
                        <span class="ps-disc-badge">-{{ $discountPct }}%</span>
                    @endif

                    @if(count($images) > 1)
                        <button class="ps-btn-prev" onclick="psMove(-1)" aria-label="Prev">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <button class="ps-btn-next" onclick="psMove(1)" aria-label="Next">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    @endif

                    @if(count($images) > 0)
                        <div class="ps-slider-track" id="psTrack">
                            @foreach($images as $img)
                                @php $imgUrl = (\Illuminate\Support\Str::startsWith($img, 'http://') || \Illuminate\Support\Str::startsWith($img, 'https://')) ? $img : asset('storage/' . $img); @endphp
                                <div class="ps-slide">
                                    <img src="{{ $imgUrl }}" alt="{{ $prodTitle }}" loading="lazy" onclick="openImageLightbox({{ $loop->index }})" style="cursor:zoom-in;" title="Klik untuk memperbesar / zoom" draggable="false">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="ps-no-img">
                            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <span style="font-size:0.75rem;">Foto produk tidak tersedia</span>
                        </div>
                    @endif
                </div>

                @if(count($images) > 1)
                    <div class="ps-thumbs">
                        @foreach($images as $i => $img)
                            @php $tUrl = (\Illuminate\Support\Str::startsWith($img, 'http://') || \Illuminate\Support\Str::startsWith($img, 'https://')) ? $img : asset('storage/' . $img); @endphp
                            <div class="ps-thumb {{ $i === 0 ? 'active' : '' }}" onclick="psTo({{ $i }})">
                                <img src="{{ $tUrl }}" alt="Foto {{ $i+1 }}" draggable="false">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- PRODUCT INFO --}}
            <div class="ps-info">

                <div class="ps-stock-badge">
                    @if($stock === null || (int)$stock < 0)
                        <span class="stock-unlimited">• Unlimited</span>
                    @elseif((int)$stock > 0)
                        <span class="stock-in">• Sisa {{ $stock }} unit</span>
                    @else
                        <span class="stock-out">• Stok Habis</span>
                    @endif
                </div>

                <h1 class="ps-title">{{ $prodTitle }}</h1>

                <div class="ps-price-box">
                    <span class="ps-price">Rp {{ number_format($price, 0, ',', '.') }}</span>
                    @if($hasDiscount)
                        <span class="ps-orig">Rp {{ number_format($origPrice, 0, ',', '.') }}</span>
                        <span class="ps-save">Hemat {{ $discountPct }}%</span>
                    @endif
                </div>

                <div class="ps-seller">
                    <div class="ps-seller-l">
                        @if(!empty($config['avatar']))
                            <img src="{{ asset('storage/' . $config['avatar']) }}" alt="{{ $bioName }}" class="ps-av-img" draggable="false">
                        @else
                            <div class="ps-av-fb">{{ strtoupper(substr($bioName, 0, 1)) }}</div>
                        @endif
                        <div>
                            <div class="ps-seller-name">{{ $bioName }}</div>
                            <div class="ps-seller-sub">Creator buyle.id</div>
                        </div>
                    </div>
                    <a href="{{ url('/' . $username) }}" class="ps-seller-link">Lihat Profil →</a>
                </div>

                <div class="ps-cta">
                    @php
                        $prodId = $product ? $product->id : ($block->data_json['product_id'] ?? null);
                        $isBuyleCheckout = true;
                        if ($product && isset($product->is_buyle_checkout)) {
                            $isBuyleCheckout = (bool)$product->is_buyle_checkout;
                        } elseif (isset($block->data_json['is_buyle_checkout'])) {
                            $isBuyleCheckout = (bool)$block->data_json['is_buyle_checkout'];
                        } elseif ($paymentMethod === 'wa') {
                            $isBuyleCheckout = false;
                        }
                    @endphp

                    @if($isOutOfStock)
                        <button class="ps-btn-buy" style="background:#cbd5e1; color:#64748b; cursor:not-allowed;" disabled>
                            Stok Habis
                        </button>
                    @elseif(!$isBuyleCheckout || $paymentMethod === 'wa')
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}?text={{ urlencode($waMessage) }}" target="_blank" class="ps-btn-buy" draggable="false">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            </svg>
                            Beli via WhatsApp
                        </a>
                    @elseif($prodId)
                        <div class="ps-cta-row">
                            <form action="{{ route('cart.add') }}" method="POST" style="display:contents;" class="ps-add-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $prodId }}">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" class="ps-btn-cart">
                                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    Keranjang
                                </button>
                            </form>
                            <a href="https://buyle.id/keranjang" class="ps-btn-checkout">
                                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                Checkout
                            </a>
                        </div>
                    @else
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}?text={{ urlencode($waMessage) }}" target="_blank" class="ps-btn-buy" draggable="false">
                            Beli Sekarang
                        </a>
                    @endif
                </div>

            </div>
        </div>

        {{-- DESCRIPTION CARD --}}
        @if(!empty($rawDesc))
        <div class="ps-desc-card">
            <div class="ps-desc-header">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Deskripsi Produk
            </div>
            <div class="ps-desc-body">{!! $rawDesc !!}</div>
        </div>
        @endif

    </div>

    {{-- LIGHTBOX IMAGE ZOOM MODAL --}}
    <style>
        .ps-lightbox-modal {
            position: fixed; inset: 0; z-index: 999999;
            background: rgba(15, 23, 42, 0.93);
            display: none; align-items: center; justify-content: center;
            backdrop-filter: blur(8px); padding: 1.5rem;
        }
        .ps-lightbox-modal.active { display: flex; animation: lbFadeIn 0.25s ease; }
        @keyframes lbFadeIn { from { opacity: 0; } to { opacity: 1; } }
        .ps-lightbox-content { max-width: 90vw; max-height: 88vh; position: relative; display: flex; align-items: center; justify-content: center; }
        .ps-lightbox-content img { max-width: 100%; max-height: 88vh; object-fit: contain; border-radius: 12px; box-shadow: 0 12px 40px rgba(0,0,0,0.5); cursor: zoom-out; }
        .ps-lightbox-close {
            position: absolute; top: 20px; right: 24px;
            background: rgba(255,255,255,0.2); color: #fff; border: none;
            width: 42px; height: 42px; border-radius: 50%; font-size: 24px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background 0.2s; z-index: 10; line-height: 1;
        }
        .ps-lightbox-close:hover { background: rgba(239,68,68,0.9); }
        .ps-lightbox-nav {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(255,255,255,0.2); color: #fff; border: none;
            width: 48px; height: 48px; border-radius: 50%; font-size: 28px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background 0.2s; z-index: 10; line-height: 1;
        }
        .ps-lightbox-nav:hover { background: rgba(30,179,73,0.9); }
        .ps-lb-prev { left: 24px; }
        .ps-lb-next { right: 24px; }
    </style>

    <div id="psLightboxModal" class="ps-lightbox-modal" onclick="closeImageLightbox(event)">
        <button type="button" class="ps-lightbox-close" onclick="closeImageLightbox()">&times;</button>
        @if(count($images) > 1)
            <button type="button" class="ps-lightbox-nav ps-lb-prev" onclick="moveLightbox(-1, event)">&lsaquo;</button>
            <button type="button" class="ps-lightbox-nav ps-lb-next" onclick="moveLightbox(1, event)">&rsaquo;</button>
        @endif
        <div class="ps-lightbox-content">
            <img id="psLightboxImg" src="" alt="Zoom Image">
        </div>
    </div>

    <script>
        const psImagesList = {!! json_encode($formattedImages) !!};
        let psCur = 0;
        const psTotal = {{ count($images) }};

        function psTo(i) {
            if (psTotal === 0) return;
            psCur = Math.max(0, Math.min(i, psTotal - 1));
            var t = document.getElementById('psTrack');
            if (t) t.style.transform = 'translateX(-' + (psCur * 100) + '%)';
            document.querySelectorAll('.ps-thumb').forEach(function(el, idx) {
                el.classList.toggle('active', idx === psCur);
            });
        }
        function psMove(d) { psTo((psCur + d + psTotal) % psTotal); }

        let lbCur = 0;
        function openImageLightbox(idx) {
            lbCur = idx;
            const lbImg = document.getElementById('psLightboxImg');
            const lbModal = document.getElementById('psLightboxModal');
            if (lbImg && lbModal && psImagesList.length > 0) {
                lbImg.src = psImagesList[lbCur];
                lbModal.classList.add('active');
            }
        }
        function closeImageLightbox(e) {
            if (!e || e.target.id === 'psLightboxModal' || e.target.classList.contains('ps-lightbox-close')) {
                const lbModal = document.getElementById('psLightboxModal');
                if (lbModal) lbModal.classList.remove('active');
            }
        }
        function moveLightbox(dir, e) {
            if (e) e.stopPropagation();
            if (psImagesList.length === 0) return;
            lbCur = (lbCur + dir + psImagesList.length) % psImagesList.length;
            const lbImg = document.getElementById('psLightboxImg');
            if (lbImg) lbImg.src = psImagesList[lbCur];
            psTo(lbCur);
        }

        document.addEventListener('keydown', function(e) {
            const lbModal = document.getElementById('psLightboxModal');
            if (lbModal && lbModal.classList.contains('active')) {
                if (e.key === 'Escape') closeImageLightbox();
                if (e.key === 'ArrowLeft') moveLightbox(-1);
                if (e.key === 'ArrowRight') moveLightbox(1);
            }
        });

        var txStart = 0;
        var pw = document.getElementById('psWrap');
        if (pw) {
            pw.addEventListener('touchstart', function(e) { txStart = e.changedTouches[0].screenX; }, {passive:true});
            pw.addEventListener('touchend', function(e) {
                var diff = txStart - e.changedTouches[0].screenX;
                if (Math.abs(diff) > 40) psMove(diff > 0 ? 1 : -1);
            });
        }

        document.addEventListener('dragstart', function(e) {
            if (e.target && e.target.tagName === 'IMG') {
                e.preventDefault();
                return false;
            }
        }, true);
    </script>

    @include('partials.adsense_modal')
    @include('partials.report_modal', ['reportType' => 'product', 'targetName' => $prodTitle])
</body>
</html>