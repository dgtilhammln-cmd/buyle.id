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
        $ratingVal   = $product ? $product->getEffectiveRating() : null;
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

        /* ── TICKET WAR COUNTDOWN (REAL-TIME LIVE DROP) ── */
        .ticket-war-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border: 1.5px solid #334155;
            border-radius: 14px;
            padding: 1rem;
            margin-bottom: 1rem;
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.25);
            text-align: center;
        }
        .ticket-war-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding-bottom: 0.6rem;
            border-bottom: 1px dashed rgba(255,255,255,0.15);
        }
        .ticket-war-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7rem;
            font-weight: 800;
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.4);
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            letter-spacing: 0.05em;
        }
        .live-dot {
            width: 7px; height: 7px; border-radius: 50%; background: #ef4444;
            box-shadow: 0 0 8px #ef4444;
            animation: pulse-dot 1.2s infinite ease-in-out;
        }
        @keyframes pulse-dot { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.4); opacity: 0.6; } }
        .ticket-war-title { font-size: 0.76rem; font-weight: 600; color: #94a3b8; }
        .ticket-war-timer-grid {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            margin: 0.5rem 0 0.4rem;
        }
        .war-timer-box {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 0.5rem 0.65rem;
            min-width: 52px;
            text-align: center;
        }
        .war-timer-val {
            display: block;
            font-family: monospace;
            font-size: 1.25rem;
            font-weight: 800;
            color: #38bdf8;
            line-height: 1;
        }
        .war-timer-lbl {
            font-size: 0.62rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-top: 3px;
            display: block;
        }
        .war-timer-sep {
            font-size: 1.1rem;
            font-weight: 800;
            color: #475569;
            margin-bottom: 8px;
        }
        .ticket-war-date {
            font-size: 0.72rem;
            color: #cbd5e1;
            margin-top: 0.45rem;
            font-weight: 500;
        }
        .ps-btn-war-locked {
            background: #334155 !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            opacity: 0.85;
            box-shadow: none !important;
        }

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
        <a href="{{ url('/keranjang') }}" class="ps-topbar-brand" title="Keranjang Belanja" style="background:none; border:none; padding:0.35rem; display:inline-flex; align-items:center; justify-content:center; position:relative;">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
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
                <div class="ps-slider-wrap" id="psWrap" style="{{ ($psIsExpired ?? false) ? 'filter:grayscale(100%); opacity:0.75;' : '' }}">
                    @if($psIsExpired ?? false)
                        {{-- Expired full overlay on product image --}}
                        <div style="position:absolute;inset:0;background:rgba(15,23,42,0.42);display:flex;align-items:center;justify-content:center;z-index:10;border-radius:inherit;">
                            <div style="text-align:center;">
                                <div style="font-size:2rem;margin-bottom:0.5rem;">&#127937;</div>
                                <div style="background:rgba(20,20,30,0.88);color:#94A3B8;font-size:0.82rem;font-weight:800;padding:0.4rem 1.25rem;border-radius:99px;font-family:'Montserrat',sans-serif;letter-spacing:0.08em;border:1px solid rgba(148,163,184,0.3);">EVENT TELAH BERAKHIR</div>
                            </div>
                        </div>
                    @elseif($hasDiscount)
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

                @if($product && ($product->product_type ?? '') === 'ticket')
                    @php
                        $tEventDate = $product->event_date ?: (!empty($block->data_json['event_date']) ? \Carbon\Carbon::parse($block->data_json['event_date']) : null);
                        $tEventTime = $product->event_time ?: ($block->data_json['event_time'] ?? null);
                        $tEventType = $product->event_type ?: ($block->data_json['event_type'] ?? 'offline');
                        $tEventLoc = $product->event_location ?: ($block->data_json['event_location'] ?? null);
                        $tEventPlatform = $product->event_platform ?: ($block->data_json['event_platform'] ?? null);
                    @endphp

                    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:14px; padding:1rem; margin:1rem 0; display:flex; flex-direction:column; gap:0.75rem; box-shadow:0 2px 8px rgba(0,0,0,0.03);">
                        @if($tEventDate)
                            <div style="display:flex; align-items:flex-start; gap:0.65rem;">
                                <div style="width:34px; height:34px; border-radius:10px; background:#ecfdf5; color:#059669; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                </div>
                                <div style="flex:1;">
                                    <div style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; color:#64748b; font-weight:700;">Waktu Pelaksanaan</div>
                                    <div style="font-size:0.9rem; font-weight:700; color:#0f172a; margin-top:2px;">
                                        {{ \Carbon\Carbon::parse($tEventDate)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </div>
                                    @if($tEventTime)
                                        <div style="display:inline-block; margin-top:3px; padding:1px 8px; background:#f0fdf4; color:#166534; border-radius:999px; font-size:0.75rem; font-weight:600; border:1px solid #bbf7d0;">
                                            {{ $tEventTime }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div style="display:flex; align-items:flex-start; gap:0.65rem; padding-top:0.65rem; border-top:1px dashed #e2e8f0;">
                            @if($tEventType === 'online')
                                <div style="width:34px; height:34px; border-radius:10px; background:#eff6ff; color:#2563eb; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                        <line x1="8" y1="21" x2="16" y2="21"/>
                                        <line x1="12" y1="17" x2="12" y2="21"/>
                                    </svg>
                                </div>
                                <div style="flex:1;">
                                    <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                        <span style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; color:#64748b; font-weight:700;">Platform Online</span>
                                        <span style="font-size:0.7rem; background:#eff6ff; color:#1d4ed8; padding:1px 6px; border-radius:999px; font-weight:600; border:1px solid #bfdbfe;">Virtual</span>
                                    </div>
                                    <div style="font-size:0.88rem; font-weight:700; color:#0f172a; margin-top:2px;">
                                        {{ $tEventPlatform ?: 'Zoom / Google Meet' }}
                                    </div>
                                    <div style="font-size:0.75rem; color:#64748b; margin-top:2px; display:flex; align-items:center; gap:4px;">
                                        <svg width="12" height="12" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                        <span>Link meeting diberikan otomatis setelah e-tiket resmi diterbitkan.</span>
                                    </div>
                                </div>
                            @else
                                <div style="width:34px; height:34px; border-radius:10px; background:#fef2f2; color:#dc2626; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                        <circle cx="12" cy="10" r="3" />
                                    </svg>
                                </div>
                                <div style="flex:1;">
                                    <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                        <span style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; color:#64748b; font-weight:700;">Lokasi / Venue</span>
                                        <span style="font-size:0.7rem; background:#fef2f2; color:#b91c1c; padding:1px 6px; border-radius:999px; font-weight:600; border:1px solid #fecaca;">Offline</span>
                                    </div>
                                    <div style="font-size:0.88rem; font-weight:700; color:#0f172a; margin-top:2px;">
                                        {{ $tEventLoc ?: 'Venue diumumkan oleh penyelenggara' }}
                                    </div>
                                    <div style="font-size:0.75rem; color:#64748b; margin-top:2px;">
                                        Check-in dengan scan QR code pada e-tiket di gerbang masuk venue.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

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
                        {{-- VARIANT SELECTOR BIO --}}
                        @if($product && $product->variantOptions && $product->variantOptions->count() > 0)
                        @php
                            $bioHasVariants = true;
                            $bioProdId = $prodId;
                        @endphp
                        <div id="bio-variant-section" style="margin-bottom:1rem;">
                            <style>
                            .bio-variant-group { margin-bottom:0.65rem; }
                            .bio-variant-label { font-size:0.78rem; font-weight:700; color:#475569; margin-bottom:0.4rem; text-transform:uppercase; letter-spacing:0.03em; }
                            .bio-variant-pills { display:flex; flex-wrap:wrap; gap:0.4rem; }
                            .bio-variant-pill {
                                display:inline-flex; align-items:center; gap:0.4rem;
                                padding:0.35rem 0.8rem; border:1.5px solid #E2E8F0; border-radius:99px;
                                background:#fff; color:#0F172A; font-size:0.8rem; font-weight:500; font-family:inherit; cursor:pointer; transition:all 0.15s;
                            }
                            .bio-variant-pill:hover:not(:disabled) { border-color:#1eb349; color:#1eb349; }
                            .bio-variant-pill.selected { border-color:#1eb349; background:rgba(30,179,73,0.1); color:#1eb349; font-weight:700; }
                            .bio-variant-pill:disabled { opacity:0.4; cursor:not-allowed; }
                            .bio-variant-pill-img { width:20px; height:20px; border-radius:4px; object-fit:cover; }
                            .bio-variant-err { display:none; color:#EF4444; font-size:0.78rem; background:#FEF2F2; border:1px solid #FCA5A5; border-radius:8px; padding:0.35rem 0.7rem; margin-bottom:0.5rem; }
                            </style>
                            @foreach($product->variantOptions as $opt)
                            <div class="bio-variant-group">
                                <div class="bio-variant-label">{{ $opt->name }}</div>
                                <div class="bio-variant-pills">
                                    @foreach($opt->values as $val)
                                    @php
                                        $valImg = $val->image ? (\Illuminate\Support\Str::startsWith($val->image, 'http') ? $val->image : asset('storage/' . $val->image)) : null;
                                        $valFinalPrice = $val->finalPrice();
                                    @endphp
                                    <button type="button" class="bio-variant-pill"
                                        data-variant-id="{{ $val->id }}"
                                        data-price="{{ $valFinalPrice }}"
                                        data-price-formatted="Rp {{ number_format($valFinalPrice, 0, ',', '.') }}"
                                        data-stock="{{ $val->stock }}"
                                        data-label="{{ $opt->name }}: {{ $val->value }}"
                                        data-img="{{ $valImg ?: $firstImage }}"
                                        data-has-custom-img="{{ $valImg ? '1' : '0' }}"
                                        @if($val->stock !== null && $val->stock <= 0) disabled @endif
                                        onclick="selectBioVariant(this)">
                                        @if($valImg)
                                            <img src="{{ $valImg }}" alt="{{ $val->value }}" class="bio-variant-pill-img">
                                        @endif
                                        <span>{{ $val->value }}</span>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                            <div id="bio-variant-err" class="bio-variant-err">⚠ Pilih varian dulu sebelum lanjut.</div>
                        </div>

                        {{-- BIO INTERACTIVE VARIANT POPUP MODAL --}}
                        <div id="bio-variant-modal" class="buyle-variant-modal" style="display:none;" aria-hidden="true">
                            <div class="bvm-backdrop" onclick="closeBioVariantModal()"></div>
                            <div class="bvm-sheet">
                                <div class="bvm-handle-bar">
                                    <span class="bvm-handle"></span>
                                </div>
                                <div class="bvm-header">
                                    <div class="bvm-thumb-wrap">
                                        <img id="bio-bvm-thumb-img" src="{{ $firstImage }}" alt="{{ $prodTitle }}" class="bvm-thumb">
                                        <span id="bio-bvm-thumb-badge" class="bvm-thumb-badge" style="display:none;">Foto Variasi</span>
                                    </div>
                                    <div class="bvm-header-info">
                                        <div class="bvm-price" id="bio-bvm-price-display">Rp {{ number_format($price, 0, ',', '.') }}</div>
                                        <div class="bvm-stock" id="bio-bvm-stock-display">
                                            @if($stock === null)
                                                <span style="color:#10b981;">• Stok Tersedia</span>
                                            @elseif((int)$stock > 0)
                                                <span>• Sisa {{ $stock }} unit</span>
                                            @else
                                                <span style="color:#ef4444;">• Stok Habis</span>
                                            @endif
                                        </div>
                                        <div class="bvm-selected-label" id="bio-bvm-selected-label">
                                            Silakan pilih opsi variasi
                                        </div>
                                    </div>
                                    <button type="button" class="bvm-close-btn" onclick="closeBioVariantModal()" aria-label="Tutup">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                        </svg>
                                    </button>
                                </div>

                                <div class="bvm-body">
                                    @foreach($product->variantOptions as $opt)
                                        <div class="bvm-group">
                                            <div class="bvm-group-title">{{ $opt->name }}</div>
                                            <div class="bvm-pills">
                                                @foreach($opt->values as $val)
                                                    @php
                                                        $valImg = $val->image ? (\Illuminate\Support\Str::startsWith($val->image, 'http') ? $val->image : asset('storage/' . $val->image)) : null;
                                                        $valFinalPrice = $val->finalPrice();
                                                        $isValOutOfStock = ($val->stock !== null && $val->stock <= 0);
                                                    @endphp
                                                    <button type="button" 
                                                        class="bvm-pill bio-bvm-pill" 
                                                        data-variant-id="{{ $val->id }}"
                                                        data-price="{{ $valFinalPrice }}"
                                                        data-price-formatted="Rp {{ number_format($valFinalPrice, 0, ',', '.') }}"
                                                        data-stock="{{ $val->stock }}"
                                                        data-label="{{ $opt->name }}: {{ $val->value }}"
                                                        data-img="{{ $valImg ?: $firstImage }}"
                                                        data-has-custom-img="{{ $valImg ? '1' : '0' }}"
                                                        @if($isValOutOfStock) disabled @endif
                                                        onclick="selectBioBvmVariant(this)">
                                                        @if($valImg)
                                                            <img src="{{ $valImg }}" alt="{{ $val->value }}" class="bvm-pill-img">
                                                        @endif
                                                        <span class="bvm-pill-text">{{ $val->value }}</span>
                                                        @if($isValOutOfStock)
                                                            <span class="bvm-pill-diff" style="color:#ef4444;">(Habis)</span>
                                                        @endif
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    @php
                                        $bioMaxLimit = null;
                                        if (($product->product_type ?? $product->type) === 'ticket') {
                                            $bioMaxLimit = (int) ($product->max_tickets_per_order ?: 4);
                                        } elseif (!empty($product->max_order)) {
                                            $bioMaxLimit = (int) $product->max_order;
                                        }
                                    @endphp
                                    <div class="bvm-qty-row">
                                        <div>
                                            <div class="bvm-qty-label">Kuantitas</div>
                                            <div class="bvm-qty-sub">
                                                Jumlah pesanan
                                                @if($bioMaxLimit)
                                                    <span style="color:#16a34a; font-weight:600;">(Maks. {{ $bioMaxLimit }} per order)</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="bvm-qty-ctrl">
                                            <button type="button" class="bvm-qty-btn" onclick="stepBioBvmQty(-1)">−</button>
                                            <input type="number" id="bio-bvm-qty-input" class="bvm-qty-input" value="1" min="1" data-max-limit="{{ $bioMaxLimit ?? '' }}" readonly>
                                            <button type="button" class="bvm-qty-btn" onclick="stepBioBvmQty(1)">+</button>
                                        </div>
                                    </div>

                                    <div id="bio-bvm-error" class="bvm-error" style="display:none;">
                                        ⚠ Silakan pilih salah satu variasi terlebih dahulu.
                                    </div>
                                </div>

                                <div class="bvm-footer">
                                    <button type="button" id="bio-bvm-submit-btn" class="bvm-btn-primary" onclick="confirmBioBvmSubmit()">
                                        <span id="bio-bvm-submit-label">Beli Sekarang</span>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <style>
                            .buyle-variant-modal {
                                position: fixed; inset: 0; z-index: 999999;
                                display: flex; align-items: flex-end; justify-content: center;
                                visibility: hidden; opacity: 0;
                                transition: opacity 0.24s ease, visibility 0.24s ease;
                            }
                            .buyle-variant-modal.active { visibility: visible; opacity: 1; }
                            .bvm-backdrop { position: absolute; inset: 0; background: rgba(15, 23, 42, 0.62); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
                            .bvm-sheet {
                                position: relative; width: 100%; max-width: 480px;
                                background: #ffffff; border-radius: 24px 24px 0 0;
                                box-shadow: 0 -12px 48px rgba(0, 0, 0, 0.22);
                                display: flex; flex-direction: column; max-height: 86vh;
                                transform: translateY(100%); transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
                                z-index: 1; overflow: hidden;
                            }
                            .buyle-variant-modal.active .bvm-sheet { transform: translateY(0); }
                            @media (min-width: 640px) {
                                .buyle-variant-modal { align-items: center; padding: 1.5rem; }
                                .bvm-sheet { border-radius: 20px; transform: scale(0.95); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25); }
                                .buyle-variant-modal.active .bvm-sheet { transform: scale(1); }
                                .bvm-handle-bar { display: none !important; }
                            }
                            .bvm-handle-bar { display: flex; justify-content: center; padding-top: 10px; padding-bottom: 4px; }
                            .bvm-handle { width: 38px; height: 4px; background: #cbd5e1; border-radius: 99px; }
                            .bvm-header { display: flex; align-items: center; gap: 0.85rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid #f1f5f9; position: relative; }
                            .bvm-thumb-wrap { width: 70px; height: 70px; border-radius: 12px; overflow: hidden; background: #f8fafc; border: 1.5px solid #e2e8f0; flex-shrink: 0; position: relative; }
                            .bvm-thumb { width: 100%; height: 100%; object-fit: cover; transition: opacity 0.2s ease; }
                            .bvm-thumb-badge { position: absolute; bottom: 2px; right: 2px; background: rgba(15, 23, 42, 0.8); color: #fff; font-size: 0.58rem; font-weight: 700; padding: 1px 4px; border-radius: 4px; }
                            .bvm-header-info { flex: 1; min-width: 0; }
                            .bvm-price { font-family: 'Montserrat', sans-serif; font-size: 1.25rem; font-weight: 800; color: #1eb349; line-height: 1.2; }
                            .bvm-stock { font-size: 0.78rem; font-weight: 600; color: #64748b; margin-top: 3px; }
                            .bvm-selected-label { font-size: 0.78rem; color: #0f172a; font-weight: 600; margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
                            .bvm-close-btn { width: 32px; height: 32px; border-radius: 50%; border: none; background: #f1f5f9; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.15s; flex-shrink: 0; }
                            .bvm-close-btn:hover { background: #e2e8f0; color: #0f172a; }
                            .bvm-body { padding: 1rem 1.25rem; overflow-y: auto; -webkit-overflow-scrolling: touch; max-height: calc(86vh - 180px); }
                            .bvm-group { margin-bottom: 1.1rem; }
                            .bvm-group-title { font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 0.55rem; text-transform: uppercase; letter-spacing: 0.04em; }
                            .bvm-pills { display: flex; flex-wrap: wrap; gap: 0.5rem; }
                            .bvm-pill { display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.45rem 0.85rem; border: 1.5px solid #e2e8f0; border-radius: 12px; background: #ffffff; color: #0f172a; font-size: 0.82rem; font-weight: 600; cursor: pointer; transition: all 0.16s ease; user-select: none; }
                            .bvm-pill:hover:not(:disabled) { border-color: #1eb349; color: #1eb349; background: rgba(30, 179, 73, 0.04); }
                            .bvm-pill.selected { border-color: #1eb349; background: linear-gradient(135deg, rgba(30, 179, 73, 0.12), rgba(165, 207, 55, 0.08)); color: #15803d; box-shadow: 0 2px 8px rgba(30, 179, 73, 0.2); }
                            .bvm-pill:disabled { opacity: 0.4; cursor: not-allowed; background: #f8fafc; border-style: dashed; }
                            .bvm-pill-img { width: 22px; height: 22px; border-radius: 6px; object-fit: cover; border: 1px solid #e2e8f0; flex-shrink: 0; }
                            .bvm-qty-row { display: flex; align-items: center; justify-content: space-between; padding-top: 0.85rem; margin-top: 0.5rem; border-top: 1px solid #f1f5f9; }
                            .bvm-qty-label { font-size: 0.82rem; font-weight: 700; color: #0f172a; }
                            .bvm-qty-sub { font-size: 0.72rem; color: #94a3b8; }
                            .bvm-qty-ctrl { display: inline-flex; align-items: center; border: 1.5px solid #e2e8f0; border-radius: 10px; overflow: hidden; background: #fff; }
                            .bvm-qty-btn { width: 32px; height: 32px; border: none; background: #f8fafc; color: #0f172a; font-size: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; }
                            .bvm-qty-btn:hover { background: #e2e8f0; }
                            .bvm-qty-input { width: 44px; height: 32px; border: none; text-align: center; font-size: 0.88rem; font-weight: 700; color: #0f172a; background: #fff; pointer-events: none; }
                            .bvm-error { color: #ef4444; font-size: 0.78rem; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 0.45rem 0.75rem; margin-top: 0.75rem; }
                            .bvm-footer { padding: 0.85rem 1.25rem 1.15rem; border-top: 1px solid #f1f5f9; background: #ffffff; }
                            .bvm-btn-primary { width: 100%; padding: 0.8rem 1.5rem; border-radius: 999px; border: none; background: linear-gradient(135deg, #1eb349, #a5cf37); color: #ffffff; font-family: 'Montserrat', sans-serif; font-size: 0.92rem; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35); transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; letter-spacing: 0.02em; }
                            .bvm-btn-primary:hover { box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45); transform: translateY(-1px); }
                        </style>
                        @endif

                        @php
                            $isWarActive = $product && ($product->product_type ?? '') === 'ticket' && !empty($product->ticket_war_start) && now()->lt($product->ticket_war_start);
                            $warTimestamp = $isWarActive ? $product->ticket_war_start->timestamp * 1000 : 0;
                            // Deteksi event expired — khusus tipe ticket
                            $psEventRaw = isset($product) ? ($product->event_date ?: (!empty($product->data_json['event_date']) ? $product->data_json['event_date'] : null)) : null;
                            $psIsExpired = isset($product) && ($product->product_type ?? '') === 'ticket' && $psEventRaw && \Carbon\Carbon::parse($psEventRaw)->endOfDay()->isPast();
                        @endphp

                        {{-- TICKET WAR REAL-TIME COUNTDOWN CARD --}}
                        @if($isWarActive)
                        <div id="ticketWarCard" class="ticket-war-card">
                            <div class="ticket-war-header">
                                <span class="ticket-war-badge">
                                    <span class="live-dot"></span> LIVE DROP
                                </span>
                                <span class="ticket-war-title">Penjualan Tiket Dibuka Dalam:</span>
                            </div>
                            <div class="ticket-war-timer-grid">
                                <div class="war-timer-box">
                                    <span id="warDays" class="war-timer-val">00</span>
                                    <span class="war-timer-lbl">Hari</span>
                                </div>
                                <span class="war-timer-sep">:</span>
                                <div class="war-timer-box">
                                    <span id="warHours" class="war-timer-val">00</span>
                                    <span class="war-timer-lbl">Jam</span>
                                </div>
                                <span class="war-timer-sep">:</span>
                                <div class="war-timer-box">
                                    <span id="warMins" class="war-timer-val">00</span>
                                    <span class="war-timer-lbl">Menit</span>
                                </div>
                                <span class="war-timer-sep">:</span>
                                <div class="war-timer-box">
                                    <span id="warSecs" class="war-timer-val">00</span>
                                    <span class="war-timer-lbl">Detik</span>
                                </div>
                            </div>
                            <div class="ticket-war-date">
                                Jadwal: {{ $product->ticket_war_start->isoFormat('dddd, D MMMM Y — HH:mm') }} WIB
                            </div>
                        </div>
                        @endif

                        {{-- Expired Banner: tampil di atas CTA jika event sudah berlalu --}}
                        @if($psIsExpired ?? false)
                        <div style="background:linear-gradient(135deg,#F1F5F9,#E2E8F0);border:1.5px solid #CBD5E1;border-radius:14px;padding:1rem 1.25rem;margin-bottom:1rem;display:flex;align-items:center;gap:0.75rem;">
                            <span style="font-size:1.4rem;">&#127937;</span>
                            <div>
                                <div style="font-family:'Montserrat',sans-serif;font-size:0.88rem;font-weight:800;color:#64748B;margin-bottom:0.15rem;">Event Telah Berakhir</div>
                                <div style="font-size:0.75rem;color:#94A3B8;font-weight:500;">Penjualan tiket untuk event ini sudah ditutup.</div>
                            </div>
                        </div>
                        @endif
                        <div class="ps-cta-row" style="{{ ($psIsExpired ?? false) ? 'pointer-events:none;opacity:0.45;' : '' }}">
                            <form action="{{ route('cart.add') }}" method="POST" id="bio-cart-form" style="display:contents;" class="ps-add-cart-form">
                                @csrf
                                <input type="hidden" name="action" id="bio_action_input" value="cart">
                                <input type="hidden" name="product_id" value="{{ $prodId }}">
                                <input type="hidden" name="qty" value="1">
                                <input type="hidden" name="variant_value_id" id="bio_selected_variant_id" value="">
                                @if(!empty($affiliateUserId))
                                    <input type="hidden" name="affiliate_id" value="{{ $affiliateUserId }}">
                                @endif
                                <button type="button" class="ps-btn-cart" id="btnBioCart" onclick="submitBioCart()" {{ $isWarActive ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                    Keranjang
                                </button>
                            </form>

                            @if($isWarActive)
                                <button type="button" id="btnTicketWarLocked" class="ps-btn-checkout ps-btn-war-locked" disabled>
                                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span id="warBtnLabel">Penjualan Belum Dibuka</span>
                                </button>
                                <button type="button" onclick="bioDirectCheckout()" id="btnTicketWarReady" class="ps-btn-checkout" style="display: none;">
                                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                                    Checkout Sekarang!
                                </button>
                            @else
                                <button type="button" class="ps-btn-checkout" onclick="bioDirectCheckout()">
                                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                    Checkout
                                </button>
                            @endif
                        </div>

                        @if($isWarActive)
                        <script>
                            (function() {
                                const targetTime = {{ $warTimestamp }};
                                function updateWarCountdown() {
                                    const now = Date.now();
                                    const diff = targetTime - now;

                                    if (diff <= 0) {
                                        // ══════════════════════════════════════════════════
                                        // 0ms INSTANT AUTO-UNLOCK (NO PAGE RELOAD NEEDED!)
                                        // ══════════════════════════════════════════════════
                                        const warCard = document.getElementById('ticketWarCard');
                                        if (warCard) {
                                            warCard.style.transition = 'all 0.4s ease';
                                            warCard.style.opacity = '0';
                                            setTimeout(() => warCard.style.display = 'none', 400);
                                        }

                                        const lockedBtn = document.getElementById('btnTicketWarLocked');
                                        const readyBtn = document.getElementById('btnTicketWarReady');
                                        const cartBtn = document.getElementById('btnBioCart');

                                        if (lockedBtn) lockedBtn.style.display = 'none';
                                        if (readyBtn) readyBtn.style.display = 'flex';
                                        if (cartBtn) {
                                            cartBtn.removeAttribute('disabled');
                                            cartBtn.style.opacity = '1';
                                            cartBtn.style.cursor = 'pointer';
                                        }

                                        // Web Audio Instant Chime Notice
                                        try {
                                            const AudioCtx = window.AudioContext || window.webkitAudioContext;
                                            if (AudioCtx) {
                                                const ctx = new AudioCtx();
                                                const osc = ctx.createOscillator();
                                                const gain = ctx.createGain();
                                                osc.type = 'sine';
                                                osc.frequency.setValueAtTime(880, ctx.currentTime);
                                                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                                                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
                                                osc.connect(gain); gain.connect(ctx.destination);
                                                osc.start(); osc.stop(ctx.currentTime + 0.4);
                                            }
                                        } catch(e) {}

                                        return;
                                    }

                                    const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                                    const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                    const s = Math.floor((diff % (1000 * 60)) / 1000);

                                    const dEl = document.getElementById('warDays');
                                    const hEl = document.getElementById('warHours');
                                    const mEl = document.getElementById('warMins');
                                    const sEl = document.getElementById('warSecs');

                                    if (dEl) dEl.textContent = String(d).padStart(2, '0');
                                    if (hEl) hEl.textContent = String(h).padStart(2, '0');
                                    if (mEl) mEl.textContent = String(m).padStart(2, '0');
                                    if (sEl) sEl.textContent = String(s).padStart(2, '0');

                                    setTimeout(updateWarCountdown, 1000);
                                }
                                updateWarCountdown();
                            })();
                        </script>
                        @endif
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
            <div class="ps-desc-body">
                @php
                    $bioVideoUrl = !empty($product->tiktok_video_url) ? $product->tiktok_video_url : (!empty($product->youtube_video_url) ? $product->youtube_video_url : (!empty($block->data_json['tiktok_video_url']) ? $block->data_json['tiktok_video_url'] : null));
                @endphp
                @if(!empty($bioVideoUrl))
                    @include('partials.product_video', ['videoUrl' => $bioVideoUrl])
                @endif
                {!! $rawDesc !!}
            </div>
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

        // ── BIO VARIANT SELECTOR & MODAL ─────────────────────────────
        var bioHasVariants = document.getElementById('bio-variant-section') !== null;
        window._bioCurrentAction = 'buy';

        window.openBioVariantModal = function(action) {
            var modal = document.getElementById('bio-variant-modal');
            if (!modal) return;
            window._bioCurrentAction = action || 'buy';

            var submitLabel = document.getElementById('bio-bvm-submit-label');
            if (submitLabel) {
                submitLabel.textContent = (window._bioCurrentAction === 'cart') ? 'Tambah ke Keranjang' : 'Beli Sekarang';
            }

            // Sync quantity honoring maxLimit
            var modalQty = document.getElementById('bio-bvm-qty-input');
            var formQty = document.querySelector('#bio-cart-form input[name="qty"]');
            if (modalQty && formQty) {
                var qVal = parseInt(formQty.value) || 1;
                var maxLimit = parseInt(modalQty.dataset.maxLimit || '') || null;
                if (maxLimit && qVal > maxLimit) qVal = maxLimit;
                modalQty.value = qVal;
                formQty.value = qVal;
            }

            // Auto-select active or first available variant
            var currentVid = document.getElementById('bio_selected_variant_id').value;
            var activePill = currentVid ? modal.querySelector('.bio-bvm-pill[data-variant-id="' + currentVid + '"]') : null;
            if (!activePill) {
                activePill = modal.querySelector('.bio-bvm-pill:not(:disabled)');
            }
            if (activePill) {
                selectBioBvmVariant(activePill, false);
            }

            modal.style.display = 'flex';
            requestAnimationFrame(function() {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        };

        window.closeBioVariantModal = function() {
            var modal = document.getElementById('bio-variant-modal');
            if (!modal) return;
            modal.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(function() {
                if (!modal.classList.contains('active')) {
                    modal.style.display = 'none';
                }
            }, 280);
        };

        window.selectBioBvmVariant = function(btn, syncToPage) {
            if (typeof syncToPage === 'undefined') syncToPage = true;
            if (btn.disabled) return;
            var group = btn.closest('.bvm-pills');
            if (group) {
                group.querySelectorAll('.bio-bvm-pill').forEach(function(p) { p.classList.remove('selected'); });
            }
            btn.classList.add('selected');

            var vid = btn.dataset.variantId;
            var price = parseFloat(btn.dataset.price) || 0;
            var priceFormatted = btn.dataset.priceFormatted;
            var stock = btn.dataset.stock;
            var label = btn.dataset.label;
            var imgUrl = btn.dataset.img;
            var hasCustomImg = btn.dataset.hasCustomImg === '1';

            // Update modal image preview
            var thumbImg = document.getElementById('bio-bvm-thumb-img');
            var thumbBadge = document.getElementById('bio-bvm-thumb-badge');
            if (thumbImg && imgUrl) {
                thumbImg.src = imgUrl;
            }
            if (thumbBadge) {
                thumbBadge.style.display = hasCustomImg ? 'block' : 'none';
            }

            // Update modal price
            var priceDisplay = document.getElementById('bio-bvm-price-display');
            if (priceDisplay && priceFormatted) {
                priceDisplay.textContent = priceFormatted;
            }

            // Update modal stock info
            var stockDisplay = document.getElementById('bio-bvm-stock-display');
            if (stockDisplay) {
                if (stock === '' || stock === null) {
                    stockDisplay.innerHTML = '<span style="color:#10b981;">• Stok Tersedia</span>';
                } else {
                    var sNum = parseInt(stock);
                    if (sNum > 0) {
                        stockDisplay.innerHTML = '<span style="color:#10b981;">• Sisa ' + sNum + ' unit</span>';
                    } else {
                        stockDisplay.innerHTML = '<span style="color:#ef4444;">• Stok Habis</span>';
                    }
                }
            }

            // Update label info
            var labelDisplay = document.getElementById('bio-bvm-selected-label');
            if (labelDisplay) {
                labelDisplay.innerHTML = '<span style="color:#15803d;font-weight:700;">✓ Dipilih:</span> ' + label;
            }

            // Hide error in modal
            var err = document.getElementById('bio-bvm-error');
            if (err) err.style.display = 'none';

            // Sync to form input
            document.getElementById('bio_selected_variant_id').value = vid;

            // Sync to page
            if (syncToPage) {
                var pagePills = document.querySelectorAll('.bio-variant-pill[data-variant-id="' + vid + '"]');
                pagePills.forEach(function(pill) {
                    var parent = pill.closest('.bio-variant-pills');
                    if (parent) {
                        parent.querySelectorAll('.bio-variant-pill').forEach(function(p) { p.classList.remove('selected'); });
                    }
                    pill.classList.add('selected');
                });

                var pagePriceEl = document.querySelector('.ps-price');
                if (pagePriceEl && price > 0) {
                    pagePriceEl.textContent = 'Rp ' + price.toLocaleString('id-ID');
                }

                var errEl = document.getElementById('bio-variant-err');
                if (errEl) errEl.style.display = 'none';
            }
        };

        window.stepBioBvmQty = function(delta) {
            var input = document.getElementById('bio-bvm-qty-input');
            if (!input) return;
            var current = parseInt(input.value) || 1;
            var next = current + delta;
            if (next < 1) next = 1;

            var maxLimit = parseInt(input.dataset.maxLimit || '') || null;
            if (maxLimit && next > maxLimit) {
                next = maxLimit;
            }

            var selectedPill = document.querySelector('.bio-bvm-pill.selected');
            if (selectedPill && selectedPill.dataset.stock) {
                var maxStock = parseInt(selectedPill.dataset.stock);
                if (!isNaN(maxStock) && maxStock > 0 && next > maxStock) {
                    next = maxStock;
                }
            }

            input.value = next;
            var formQty = document.querySelector('#bio-cart-form input[name="qty"]');
            if (formQty) formQty.value = next;
        };

        window.confirmBioBvmSubmit = function() {
            var vidEl = document.getElementById('bio_selected_variant_id');
            var vid = vidEl ? vidEl.value : '';
            if (!vid) {
                var firstPill = document.querySelector('.bio-bvm-pill:not(:disabled)');
                if (firstPill) {
                    selectBioBvmVariant(firstPill, true);
                    vid = firstPill.dataset.variantId;
                }
            }
            if (!vid) {
                var err = document.getElementById('bio-bvm-error');
                if (err) {
                    err.style.display = 'block';
                    err.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
                return false;
            }

            var modalQty = document.getElementById('bio-bvm-qty-input');
            var formQty = document.querySelector('#bio-cart-form input[name="qty"]');
            if (modalQty && formQty) {
                formQty.value = modalQty.value;
            }

            closeBioVariantModal();

            var form = document.getElementById('bio-cart-form');
            if (!form) return;
            var act = document.getElementById('bio_action_input');

            if (window._bioCurrentAction === 'cart') {
                if (act) act.value = 'cart';
                form.submit();
            } else {
                if (act) act.value = 'buy';
                var btn = document.querySelector('.ps-btn-checkout');
                if (btn) {
                    btn.disabled = true;
                    btn.style.opacity = '0.7';
                }
                form.submit();
            }
        };

        window.selectBioVariant = function(btn) {
            var vid = btn.dataset.variantId;
            var modalPill = document.querySelector('.bio-bvm-pill[data-variant-id="' + vid + '"]');
            if (modalPill) {
                selectBioBvmVariant(modalPill, true);
            } else {
                var pills = btn.closest('.bio-variant-pills').querySelectorAll('.bio-variant-pill');
                pills.forEach(function(p){ p.classList.remove('selected'); });
                btn.classList.add('selected');
                document.getElementById('bio_selected_variant_id').value = vid;
            }
        };

        window.submitBioCart = function() {
            if (bioHasVariants) {
                var vid = document.getElementById('bio_selected_variant_id').value;
                if (!vid) {
                    openBioVariantModal('cart');
                    return false;
                }
            }
            var form = document.getElementById('bio-cart-form');
            var act = document.getElementById('bio_action_input');
            if (act) act.value = 'cart';
            if (form) form.submit();
        };

        window.bioDirectCheckout = function() {
            if (bioHasVariants) {
                // Selalu buka interactive pop up variasi saat klik Beli Sekarang / Checkout
                openBioVariantModal('buy');
                return false;
            }
            doBioDirectCheckout();
        };

        window.doBioDirectCheckout = function() {
            var form = document.getElementById('bio-cart-form');
            if (!form) {
                window.location.href = "{{ route('cart.index') }}";
                return;
            }
            var act = document.getElementById('bio_action_input');
            if (act) act.value = 'buy';
            var btn = document.querySelector('.ps-btn-checkout');
            if (btn) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
            }
            form.submit();
        };

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeBioVariantModal();
        });

        // Simpan & Teruskan Nomor Meja QR Code
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            const meja = urlParams.get('meja') || sessionStorage.getItem('buyle_table_number') || localStorage.getItem('buyle_table_number');
            if (meja) {
                const decodedMeja = decodeURIComponent(meja).trim();
                sessionStorage.setItem('buyle_table_number', decodedMeja);
                localStorage.setItem('buyle_table_number', decodedMeja);

                const form = document.getElementById('bio-cart-form');
                if (form) {
                    let hidden = form.querySelector('input[name="table_number"]');
                    if (!hidden) {
                        hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = 'table_number';
                        form.appendChild(hidden);
                    }
                    hidden.value = decodedMeja;
                }
            }
        })();
    </script>

    {{-- SMART RETARGETING TRACKER --}}
    <script>
        (function() {
            try {
                const prodData = {
                    id: {{ (int)$product->id }},
                    name: {!! json_encode($prodTitle) !!},
                    slug: {!! json_encode($product->slug) !!},
                    category_id: {{ (int)($product->product_category_id ?? 0) }},
                    seller_id: {{ (int)($product->seller_id ?? 0) }},
                    timestamp: Date.now()
                };

                let history = JSON.parse(localStorage.getItem('buyle_viewed_history') || '[]');
                history = history.filter(item => item && item.id !== prodData.id);
                history.unshift(prodData);
                if (history.length > 20) history = history.slice(0, 20);
                localStorage.setItem('buyle_viewed_history', JSON.stringify(history));

                let catMap = JSON.parse(localStorage.getItem('buyle_cat_interests') || '{}');
                if (prodData.category_id) {
                    catMap[prodData.category_id] = (catMap[prodData.category_id] || 0) + 1;
                    localStorage.setItem('buyle_cat_interests', JSON.stringify(catMap));
                }

                let sellerMap = JSON.parse(localStorage.getItem('buyle_seller_interests') || '{}');
                if (prodData.seller_id) {
                    sellerMap[prodData.seller_id] = (sellerMap[prodData.seller_id] || 0) + 1;
                    localStorage.setItem('buyle_seller_interests', JSON.stringify(sellerMap));
                }
            } catch(e) {}
        })();
    </script>

    @include('partials.adsense_modal')
    @include('partials.report_modal', ['reportType' => 'product', 'targetName' => $prodTitle])
</body>
</html>