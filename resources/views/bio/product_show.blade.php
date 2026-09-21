<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @php
        $product = $product ?? null;
        $images = $block->data_json['images'] ?? [];
        if (empty($images) && !empty($block->data_json['image'])) {
            $images = [$block->data_json['image']];
        }
        if ($product) {
            $pGallery = $product->gallery;
            if (is_string($pGallery)) {
                $pGallery = json_decode($pGallery, true) ?: [];
            }
            if (is_array($pGallery) && !empty($pGallery)) {
                $mainImg = $product->image ? [$product->image] : [];
                $merged = array_unique(array_merge($mainImg, $pGallery));
                if (!empty($merged)) {
                    $images = array_values($merged);
                }
            } elseif (empty($images) && !empty($product->image)) {
                $images = [$product->image];
            }
        }
        $prodTitle = !empty($block->title) ? $block->title : ($product->name ?? 'Produk');
        $price = $block->data_json['price'] ?? $block->data_json['custom_price'] ?? ($product ? ($product->is_on_sale ? $product->sale_price : $product->effective_price) : 0);
        $origPrice = $block->data_json['original_price'] ?? ($product && $product->is_on_sale ? $product->price : null);
        $paymentMethod = $block->data_json['payment_method'] ?? (in_array($block->type, ['buyle_product', 'buyle_affiliate']) ? 'web' : 'wa');
        $waText = $block->data_json['wa_text'] ?? '';
        $waNumber = $config['wa'] ?? '';
        $waMessage = 'Halo, saya mendapatkan nomor dari buyle.id. ' . ($waText ?: 'Saya tertarik dengan produk *' . $prodTitle . '* (Rp ' . number_format($price, 0, ',', '.') . '). Apakah masih tersedia?');
        $rawCat = $block->data_json['category'] ?? ($product && $product->category ? $product->category->name : null);
        $pType  = strtolower($product->product_type ?? $product->type ?? '');

        // Category Resolution
        if (in_array($pType, ['external_link', 'file_upload', 'digital'])) {
            $category = 'Produk Digital';
        } elseif ($pType === 'ticket') {
            $category = 'Tiket Event';
        } elseif (in_array($pType, ['service', 'jasa'])) {
            $category = 'Jasa / Layanan';
        } elseif (in_array($pType, ['makanan', 'fnb', 'food'])) {
            $category = 'Makanan';
        } elseif (in_array($pType, ['physical', 'barang', 'product', 'fisik'])) {
            $category = 'Barang / Fisik';
        } elseif (!empty($rawCat)) {
            $category = ucfirst($rawCat);
        } else {
            $category = 'Produk Digital';
        }

        $stock = isset($block->data_json['stock']) && $block->data_json['stock'] !== '' && $block->data_json['stock'] !== null 
            ? (int)$block->data_json['stock'] 
            : ($product ? $product->stock : null);
        $isOutOfStock = ($stock !== null && $stock <= 0);
        $firstImage = !empty($images[0]) ? (\Illuminate\Support\Str::startsWith($images[0], 'http') ? $images[0] : asset('storage/' . $images[0])) : asset('images/buyle-og.png');
        $bioName   = $config['name'] ?? $profile->store_name ?? $username;
        $pageTitle = $prodTitle . ' - ' . $bioName . ' | buyle.id';
        $rawDesc   = !empty($block->data_json['description']) ? $block->data_json['description'] : ($product ? ($product->description ?: $product->short_desc) : '');
        $pageDesc  = !empty($rawDesc) ? \Illuminate\Support\Str::limit(strip_tags($rawDesc), 160) : 'Beli ' . $prodTitle . ' berkualitas dengan harga terbaik dari ' . $bioName . ' di buyle.id.';

        $hasDiscount = !empty($origPrice) && $origPrice > $price;
        $discountPct = $hasDiscount ? round((($origPrice - $price) / $origPrice) * 100) : 0;
        $ratingVal   = ($product && !empty($product->rating) && $product->rating > 0) ? number_format($product->rating, 1) : '5.0';

        $blocks   = $blocks ?? $profile->bioBlocks;
        $products = $products ?? \App\Models\CreatorBioBlock::where('creator_id', $profile->id)->where('is_active', true)->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])->get();
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=4">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=4">

    <style>
        :root {
            --t5-emerald: #1eb349;
            --t5-emerald-dark: #15803d;
            --t5-emerald-light: #f0fdf4;
            --t5-emerald-border: #bbf7d0;
            --t5-slate-900: #0f172a;
            --t5-slate-800: #1e293b;
            --t5-slate-700: #334155;
            --t5-slate-600: #475569;
            --t5-slate-500: #64748b;
            --t5-slate-400: #94a3b8;
            --t5-slate-200: #e2e8f0;
            --t5-bg-main: #f8fafc;
            --t5-white: #ffffff;
            --t5-amber: #f59e0b;
            --t5-radius-sm: 6px;
            --t5-radius-md: 10px;
            --t5-radius-lg: 16px;
            --t5-shadow-lg: 0 10px 28px rgba(15,23,42,0.06);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; font-size: 16px; }
        body {
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--t5-bg-main);
            color: var(--t5-slate-800);
            line-height: 1.6;
            overflow-x: hidden;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
        }

        /* BREADCRUMB BAR */
        .t5-breadcrumb-wrap {
            max-width: 1100px; margin: 0 auto; padding: 1rem 1.25rem 0.25rem;
        }
        .t5-breadcrumb {
            display: flex; align-items: center; gap: 0.4rem; font-size: 0.78rem; color: var(--t5-slate-500); flex-wrap: wrap;
        }
        .t5-breadcrumb a { color: var(--t5-slate-600); text-decoration: none; font-weight: 400; transition: color 0.2s; }
        .t5-breadcrumb a:hover { color: var(--t5-emerald); }
        .t5-breadcrumb-sep { color: var(--t5-slate-400); display: flex; align-items: center; }
        .t5-breadcrumb-current { color: var(--t5-slate-900); font-weight: 600; max-width: 280px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* MAIN PRODUCT SHOW CONTAINER (COMPACT & PROPORTIONAL) */
        .ps-container {
            max-width: 1100px; margin: 0 auto; padding: 0.5rem 1.25rem 2.5rem;
        }
        .ps-card-main {
            background: var(--t5-white); border-radius: var(--t5-radius-lg);
            border: 1px solid var(--t5-slate-200); box-shadow: var(--t5-shadow-lg);
            padding: 1.35rem; display: grid; grid-template-columns: 380px 1fr; gap: 1.75rem;
            align-items: start;
        }
        @media (max-width: 860px) {
            .ps-card-main { grid-template-columns: 1fr; gap: 1.25rem; padding: 1rem; }
            .ps-container { padding: 0.5rem 0.85rem 2rem; }
            .t5-breadcrumb-wrap { padding: 0.85rem 0.85rem 0.25rem; }
        }

        /* GALLERY SLIDER */
        .ps-gallery { display: flex; flex-direction: column; gap: 0.6rem; width: 100%; }
        .ps-slider-container {
            position: relative; overflow: hidden; border-radius: var(--t5-radius-md);
            background: #ffffff; border: 1px solid var(--t5-slate-200); aspect-ratio: 1/1; max-height: 380px; width: 100%;
        }
        .ps-slider-track { display: flex; transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1); width: 100%; height: 100%; }
        .ps-slide { min-width: 100%; height: 100%; position: relative; background: #fff; }
        .ps-slide img { width: 100%; height: 100%; object-fit: contain; display: block; }
        .ps-slider-prev, .ps-slider-next {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(15,23,42,0.6); color: #fff; border: none;
            width: 32px; height: 32px; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center; z-index: 10;
            backdrop-filter: blur(4px); transition: background 0.2s;
        }
        .ps-slider-prev:hover, .ps-slider-next:hover { background: rgba(30,179,73,0.85); }
        .ps-slider-prev { left: 8px; }
        .ps-slider-next { right: 8px; }
        .ps-badge-disc {
            position: absolute; top: 0.65rem; left: 0.65rem; z-index: 5;
            background: #ef4444; color: #fff; font-size: 0.7rem; font-weight: 600;
            padding: 0.18rem 0.45rem; border-radius: 4px; letter-spacing: 0.02em;
        }

        .ps-thumbnails { display: flex; gap: 0.4rem; overflow-x: auto; padding-bottom: 0.2rem; }
        .ps-thumb {
            width: 50px; height: 50px; border-radius: 6px; overflow: hidden;
            border: 2px solid var(--t5-slate-200); cursor: pointer; flex-shrink: 0;
            transition: border-color 0.2s;
        }
        .ps-thumb.active, .ps-thumb:hover { border-color: var(--t5-emerald); }
        .ps-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

        /* DETAILS */
        .ps-details { display: flex; flex-direction: column; gap: 0.85rem; width: 100%; }
        .ps-header-meta { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
        .ps-cat-badge {
            font-size: 0.68rem; font-weight: 600; background: var(--t5-emerald-light);
            color: var(--t5-emerald-dark); border: 1px solid var(--t5-emerald-border);
            padding: 0.18rem 0.5rem; border-radius: 5px; text-transform: uppercase; letter-spacing: 0.05em;
        }
        .ps-stock-badge {
            font-size: 0.72rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem;
        }
        .ps-stock-in { color: #16a34a; }
        .ps-stock-out { color: #ef4444; }

        .ps-title {
            font-size: 1.25rem; font-weight: 600; color: var(--t5-slate-900);
            line-height: 1.35; letter-spacing: -0.02em;
        }
        .ps-rating-row { display: flex; align-items: center; gap: 0.6rem; font-size: 0.78rem; }
        .ps-rating-stars { display: flex; align-items: center; gap: 0.2rem; color: var(--t5-amber); font-weight: 600; }
        .ps-verified-badge { display: flex; align-items: center; gap: 0.2rem; color: var(--t5-emerald-dark); font-weight: 500; }

        .ps-price-box {
            background: var(--t5-bg-main); border: 1px solid var(--t5-slate-200);
            padding: 0.75rem 1rem; border-radius: var(--t5-radius-md);
            display: flex; align-items: baseline; gap: 0.6rem; flex-wrap: wrap;
        }
        .ps-price-main { font-size: 1.4rem; font-weight: 700; color: var(--t5-emerald-dark); }
        .ps-price-orig { font-size: 0.85rem; color: var(--t5-slate-400); text-decoration: line-through; }
        .ps-save-badge { font-size: 0.7rem; font-weight: 600; color: #ef4444; background: #fef2f2; padding: 0.15rem 0.4rem; border-radius: 4px; }

        .ps-desc-box {
            font-size: 0.84rem; color: var(--t5-slate-700); line-height: 1.6;
            padding-top: 0.6rem; border-top: 1px solid var(--t5-slate-200);
        }
        .ps-desc-box p { margin-bottom: 0.5rem; }
        .ps-desc-box ul, .ps-desc-box ol { padding-left: 1rem; margin-bottom: 0.5rem; }

        /* SELLER CARD */
        .ps-seller-card {
            display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;
            background: #fff; border: 1px solid var(--t5-slate-200); border-radius: var(--t5-radius-md);
            padding: 0.6rem 0.8rem;
        }
        .ps-seller-left { display: flex; align-items: center; gap: 0.6rem; }
        .ps-seller-avatar { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 2px solid var(--t5-emerald); }
        .ps-seller-fallback { width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, var(--t5-emerald), #15803d); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.85rem; }
        .ps-seller-name { font-size: 0.8rem; font-weight: 600; color: var(--t5-slate-900); }
        .ps-seller-sub { font-size: 0.68rem; color: var(--t5-slate-500); }
        .ps-seller-btn { font-size: 0.72rem; font-weight: 600; color: var(--t5-emerald-dark); text-decoration: none; }
        .ps-seller-btn:hover { text-decoration: underline; }

        /* CTA ACTIONS */
        .ps-actions { display: flex; flex-direction: column; gap: 0.5rem; }
        .ps-btn-buy {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            width: 100%; padding: 0.8rem 1.1rem; border-radius: var(--t5-radius-md);
            background: linear-gradient(135deg, var(--t5-emerald) 0%, var(--t5-emerald-dark) 100%);
            color: #ffffff; font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 600;
            text-decoration: none; border: none; cursor: pointer; transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(30,179,73,0.25);
        }
        .ps-btn-buy:hover {
            background: linear-gradient(135deg, #16a34a 0%, #14532d 100%);
            transform: translateY(-1px); box-shadow: 0 6px 18px rgba(30,179,73,0.35); color: #fff;
        }

        /* FLOATING WHATSAPP BUTTON */
        .t5-floating-wa {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: #ffffff; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-decoration: none;
        }
        .t5-floating-wa:hover {
            transform: scale(1.1) translateY(-2px);
            box-shadow: 0 12px 30px rgba(37, 211, 102, 0.55); color: #ffffff;
        }
        .t5-wa-pulse {
            position: absolute; inset: 0; border-radius: 50%;
            border: 2px solid #25D366; animation: t5WaPulse 2s infinite; pointer-events: none;
        }
        @keyframes t5WaPulse {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.4); opacity: 0; }
        }

        @media (max-width: 640px) {
            .ps-title { font-size: 1.15rem; }
            .ps-price-main { font-size: 1.25rem; }
            .t5-floating-wa { bottom: 18px; right: 18px; width: 50px; height: 50px; }
            .ps-card-main { gap: 1rem; }
        }
    </style>
</head>
<body>

    {{-- HEADER TEMA 5 --}}
    @include('bio.theme5.header', ['products' => $products, 'blocks' => $blocks, 'config' => $config, 'profile' => $profile, 'username' => $username])

    {{-- BREADCRUMB --}}
    <div class="t5-breadcrumb-wrap">
        <div class="t5-breadcrumb">
            <a href="{{ url('/' . $username) }}">Beranda</a>
            <span class="t5-breadcrumb-sep">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
            <a href="{{ url('/' . $username . '/produk') }}">Produk</a>
            <span class="t5-breadcrumb-sep">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
            <span class="t5-breadcrumb-current">{{ $prodTitle }}</span>
        </div>
    </div>

    {{-- MAIN PRODUCT CONTENT --}}
    <div class="ps-container">
        @if(session('error'))
            <div style="background:#fef2f2; color:#dc2626; border:1px solid #fca5a5; padding:0.75rem 1rem; border-radius:10px; font-size:0.82rem; margin-bottom:1rem; font-weight:600;">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; padding:0.75rem 1rem; border-radius:10px; font-size:0.82rem; margin-bottom:1rem; font-weight:600;">
                {{ session('success') }}
            </div>
        @endif

        <div class="ps-card-main">
            {{-- LEFT COLUMN: GALLERY --}}
            <div class="ps-gallery">
                <div class="ps-slider-container" id="psSliderContainer">
                    @if($hasDiscount)
                        <span class="ps-badge-disc">-{{ $discountPct }}%</span>
                    @endif
                    @if(count($images) > 0)
                        @if(count($images) > 1)
                            <button class="ps-slider-prev" onclick="psSlideMove(-1)">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
                            </button>
                            <button class="ps-slider-next" onclick="psSlideMove(1)">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                            </button>
                        @endif
                        <div class="ps-slider-track" id="psSliderTrack">
                            @foreach($images as $img)
                                @php $imgUrl = (\Illuminate\Support\Str::startsWith($img, 'http://') || \Illuminate\Support\Str::startsWith($img, 'https://')) ? $img : asset('storage/' . $img); @endphp
                                <div class="ps-slide">
                                    <img src="{{ $imgUrl }}" alt="{{ $prodTitle }}">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="ps-slide" style="display:flex; align-items:center; justify-content:center; background:#ffffff;">
                            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:0.3;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                    @endif
                </div>

                @if(count($images) > 1)
                    <div class="ps-thumbnails">
                        @foreach($images as $i => $img)
                            @php $tUrl = (\Illuminate\Support\Str::startsWith($img, 'http://') || \Illuminate\Support\Str::startsWith($img, 'https://')) ? $img : asset('storage/' . $img); @endphp
                            <div class="ps-thumb {{ $i === 0 ? 'active' : '' }}" onclick="psSlideTo({{ $i }})">
                                <img src="{{ $tUrl }}" alt="Thumbnail {{ $i+1 }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- RIGHT COLUMN: DETAILS --}}
            <div class="ps-details">
                <div class="ps-header-meta">
                    <span class="ps-cat-badge">{{ $category }}</span>
                    @if($stock === null || (int)$stock < 0)
                        <span class="ps-stock-badge ps-stock-in">&bull; Stok Unlimited</span>
                    @elseif((int)$stock > 0)
                        <span class="ps-stock-badge ps-stock-in">&bull; Sisa {{ $stock }} unit</span>
                    @else
                        <span class="ps-stock-badge ps-stock-out">&bull; Stok Habis</span>
                    @endif
                </div>

                <h1 class="ps-title">{{ $prodTitle }}</h1>

                <div class="ps-rating-row">
                    <span class="ps-rating-stars">
                        <svg width="14" height="14" fill="#f59e0b" stroke="#f59e0b" stroke-width="1" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        {{ $ratingVal }}
                    </span>
                    <span style="color:var(--t5-slate-400);">&bull;</span>
                    <span class="ps-verified-badge">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Terverifikasi buyle.id
                    </span>
                </div>

                <div class="ps-price-box">
                    <span class="ps-price-main">Rp {{ number_format($price, 0, ',', '.') }}</span>
                    @if($hasDiscount)
                        <span class="ps-price-orig">Rp {{ number_format($origPrice, 0, ',', '.') }}</span>
                        <span class="ps-save-badge">Hemat {{ $discountPct }}%</span>
                    @endif
                </div>

                {{-- ACTION CTA BUTTONS --}}
                <div class="ps-actions">
                    @if($isOutOfStock)
                        <button type="button" class="ps-btn-buy" disabled style="background:#cbd5e1; color:#64748b; cursor:not-allowed; box-shadow:none;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                            Stok Habis
                        </button>
                    @elseif($paymentMethod === 'wa' && $waNumber)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}?text={{ urlencode($waMessage) }}" target="_blank" class="ps-btn-buy">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                            Beli via WhatsApp
                        </a>
                    @elseif($product || !empty($block->data_json['product_id']))
                        <form action="{{ route('cart.add') }}" method="POST" style="width:100%;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product ? $product->id : ($block->data_json['product_id'] ?? '') }}">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" class="ps-btn-buy">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                Beli Sekarang (Checkout via Buyle)
                            </button>
                        </form>
                    @elseif($block->url)
                        <a href="{{ $block->url }}" target="_blank" class="ps-btn-buy">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            Beli Sekarang (Checkout)
                        </a>
                    @else
                        @php
                            $cleanNumber = preg_replace('/[^0-9]/', '', $waNumber);
                            $checkoutMsg = 'Halo, saya ingin checkout / pesan produk *' . $prodTitle . '* (Rp ' . number_format($price, 0, ',', '.') . ') dari buyle.id Anda. Apakah masih tersedia?';
                            $waUrl = !empty($cleanNumber) 
                                ? 'https://wa.me/' . $cleanNumber . '?text=' . urlencode($checkoutMsg)
                                : 'https://wa.me/?text=' . urlencode($checkoutMsg);
                        @endphp
                        <a href="{{ $waUrl }}" target="_blank" class="ps-btn-buy">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                            Beli Sekarang (Checkout)
                        </a>
                    @endif
                </div>

                {{-- CREATOR CARD --}}
                <div class="ps-seller-card">
                    <div class="ps-seller-left">
                        @if(!empty($config['avatar']))
                            <img src="{{ asset('storage/' . $config['avatar']) }}" alt="{{ $bioName }}" class="ps-seller-avatar">
                        @else
                            <div class="ps-seller-fallback">{{ strtoupper(substr($bioName, 0, 1)) }}</div>
                        @endif
                        <div>
                            <div class="ps-seller-name">{{ $bioName }}</div>
                            <div class="ps-seller-sub">Official Creator buyle.id</div>
                        </div>
                    </div>
                    <a href="{{ url('/' . $username) }}" class="ps-seller-btn">Profil &rarr;</a>
                </div>

                @if(!empty($rawDesc))
                    <div class="ps-desc-box">
                        {!! $rawDesc !!}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- FLOATING WHATSAPP BUTTON --}}
    @if(!empty($config['wa']))
        @php $waFloatNum = preg_replace('/[^0-9]/', '', $config['wa']); @endphp
        @if($waFloatNum)
            <a href="https://wa.me/{{ \Illuminate\Support\Str::startsWith($waFloatNum, '62') ? $waFloatNum : '62' . ltrim($waFloatNum, '0') }}"
               target="_blank" rel="noopener noreferrer" class="t5-floating-wa" title="Chat via WhatsApp">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.556 4.117 1.528 5.849L0 24l6.335-1.508A11.948 11.948 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.65-.52-5.154-1.422l-.37-.218-3.764.896.924-3.667-.243-.381A9.953 9.953 0 0 1 2 12c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10z"/>
                </svg>
                <span class="t5-wa-pulse"></span>
            </a>
        @endif
    @endif

    {{-- FOOTER TEMA 5 --}}
    @include('bio.theme5.footer', ['products' => $products, 'config' => $config, 'profile' => $profile, 'username' => $username])

    <script>
        function toggleT5Drawer() {
            var drawer = document.getElementById('t5MobileDrawer');
            if (drawer) drawer.classList.toggle('active');
        }
        let psCurrentSlide = 0;
        const psTotalSlides = {{ count($images) }};
        function psSlideTo(index) {
            psCurrentSlide = Math.max(0, Math.min(index, psTotalSlides - 1));
            const track = document.getElementById('psSliderTrack');
            if (track) track.style.transform = `translateX(-${psCurrentSlide * 100}%)`;
            document.querySelectorAll('.ps-thumb').forEach((t, i) => t.classList.toggle('active', i === psCurrentSlide));
        }
        function psSlideMove(dir) { psSlideTo(psCurrentSlide + dir); }
        let touchStartX = 0;
        const psContainer = document.getElementById('psSliderContainer');
        if (psContainer) {
            psContainer.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; }, {passive:true});
            psContainer.addEventListener('touchend', e => {
                const diff = touchStartX - e.changedTouches[0].screenX;
                if (Math.abs(diff) > 40) psSlideMove(diff > 0 ? 1 : -1);
            });
        }
    </script>
    @include('partials.adsense_modal')
    @include('partials.report_modal', ['reportType' => 'product', 'targetName' => $prodTitle])
</body>
</html>
