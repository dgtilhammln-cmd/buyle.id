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
        $blocks      = $blocks ?? $profile->bioBlocks;
        $products    = $products ?? \App\Models\CreatorBioBlock::where('creator_id', $profile->id)->where('is_active', true)->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])->get();
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
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">

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

    <style>
        :root {
            --em:  #1eb349;
            --em2: #15803d;
            --eml: #f0fdf4;
            --emb: #bbf7d0;
            --s9:  #0f172a;
            --s8:  #1e293b;
            --s7:  #334155;
            --s6:  #475569;
            --s5:  #64748b;
            --s4:  #94a3b8;
            --s2:  #e2e8f0;
            --bg:  #f1f5f9;
            --wh:  #ffffff;
            --am:  #f59e0b;
            --rd:  #ef4444;
            --r6:  6px;
            --r10: 10px;
            --r16: 16px;
            --shd: 0 4px 24px rgba(15,23,42,0.07);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--s8);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── BREADCRUMB ── */
        .bc-bar { background: var(--wh); border-bottom: 1px solid var(--s2); }
        .bc-inner { max-width: 1100px; margin: 0 auto; padding: 0.65rem 1.25rem; display: flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; color: var(--s5); flex-wrap: wrap; }
        .bc-inner a { color: var(--s6); text-decoration: none; font-weight: 500; }
        .bc-inner a:hover { color: var(--em); }
        .bc-sep { color: var(--s4); }
        .bc-cur { color: var(--s9); font-weight: 600; max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* ── PAGE LAYOUT ── */
        .ps-wrap { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1.25rem 3rem; display: flex; flex-direction: column; gap: 1.25rem; }

        /* ── TOP CARD: LEFT (GALLERY) + RIGHT (INFO) ── */
        .ps-top {
            background: var(--wh);
            border-radius: var(--r16);
            border: 1px solid var(--s2);
            box-shadow: var(--shd);
            display: grid;
            grid-template-columns: minmax(0, 420px) 1fr;
            gap: 0;
            overflow: hidden;
        }

        /* ── GALLERY (LEFT) ── */
        .ps-gallery { background: #fafafa; border-right: 1px solid var(--s2); display: flex; flex-direction: column; }
        .ps-slider-wrap {
            position: relative; overflow: hidden; aspect-ratio: 1/1; width: 100%; background: #f8f8f8;
        }
        .ps-slider-track { display: flex; height: 100%; transition: transform 0.38s cubic-bezier(0.22, 1, 0.36, 1); }
        .ps-slide { min-width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #fff; }
        .ps-slide img { width: 100%; height: 100%; object-fit: contain; display: block; }
        .ps-no-img { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; gap: 0.75rem; color: var(--s4); }
        .ps-btn-prev, .ps-btn-next {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(15,23,42,0.55); color: #fff; border: none;
            width: 34px; height: 34px; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center; z-index: 5;
            backdrop-filter: blur(4px); transition: background 0.2s;
        }
        .ps-btn-prev:hover, .ps-btn-next:hover { background: rgba(30,179,73,0.9); }
        .ps-btn-prev { left: 10px; }
        .ps-btn-next { right: 10px; }
        .ps-disc-badge {
            position: absolute; top: 10px; left: 10px; z-index: 4;
            background: var(--rd); color: #fff; font-size: 0.68rem; font-weight: 700;
            padding: 0.2rem 0.5rem; border-radius: 5px; letter-spacing: 0.03em;
        }
        .ps-thumbs { display: flex; gap: 0.45rem; padding: 0.65rem 0.75rem; overflow-x: auto; border-top: 1px solid var(--s2); }
        .ps-thumb {
            width: 54px; height: 54px; border-radius: var(--r6); overflow: hidden;
            border: 2px solid var(--s2); cursor: pointer; flex-shrink: 0;
            transition: border-color 0.2s; background: #fff;
        }
        .ps-thumb.active, .ps-thumb:hover { border-color: var(--em); }
        .ps-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

        /* ── DETAILS (RIGHT) ── */
        .ps-info { padding: 1.75rem 1.75rem 1.5rem; display: flex; flex-direction: column; gap: 1rem; }

        .ps-meta-row { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
        .ps-badge-cat {
            font-size: 0.67rem; font-weight: 700; background: var(--eml);
            color: var(--em2); border: 1px solid var(--emb);
            padding: 0.2rem 0.55rem; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.06em;
        }
        .ps-badge-stock {
            font-size: 0.72rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.2rem;
        }
        .stock-in { color: #16a34a; }
        .stock-out { color: var(--rd); }
        .stock-unlimited { color: var(--s5); }

        .ps-title { font-size: 1.35rem; font-weight: 700; color: var(--s9); line-height: 1.3; letter-spacing: -0.025em; }

        .ps-rating-row { display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--s5); }
        .ps-stars { display: flex; align-items: center; gap: 0.2rem; color: var(--am); font-weight: 600; font-size: 0.78rem; }
        .ps-verified { display: inline-flex; align-items: center; gap: 0.2rem; color: var(--em2); font-weight: 500; }

        .ps-price-box {
            background: var(--bg); border: 1px solid var(--s2); border-radius: var(--r10);
            padding: 0.85rem 1.1rem; display: flex; align-items: baseline; gap: 0.65rem; flex-wrap: wrap;
        }
        .ps-price { font-size: 1.55rem; font-weight: 700; color: var(--em2); letter-spacing: -0.03em; }
        .ps-orig { font-size: 0.85rem; color: var(--s4); text-decoration: line-through; }
        .ps-save { font-size: 0.68rem; font-weight: 700; color: var(--rd); background: #fef2f2; padding: 0.15rem 0.45rem; border-radius: 4px; }

        /* short desc */
        .ps-short-desc { font-size: 0.83rem; color: var(--s6); line-height: 1.65; }

        /* Seller mini card */
        .ps-seller {
            display: flex; align-items: center; justify-content: space-between;
            border: 1px solid var(--s2); border-radius: var(--r10); padding: 0.6rem 0.9rem; gap: 0.6rem;
        }
        .ps-seller-l { display: flex; align-items: center; gap: 0.55rem; }
        .ps-av-img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--em); }
        .ps-av-fb { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--em), var(--em2)); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.88rem; }
        .ps-seller-name { font-size: 0.8rem; font-weight: 700; color: var(--s9); }
        .ps-seller-sub  { font-size: 0.67rem; color: var(--s5); }
        .ps-seller-link { font-size: 0.72rem; font-weight: 600; color: var(--em2); text-decoration: none; white-space: nowrap; }
        .ps-seller-link:hover { text-decoration: underline; }

        /* CTA Buttons */
        .ps-cta { display: flex; flex-direction: column; gap: 0.5rem; }
        .ps-btn-buy {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            width: 100%; padding: 0.85rem 1.2rem; border-radius: var(--r10);
            background: linear-gradient(135deg, var(--em) 0%, var(--em2) 100%);
            color: #fff; font-family: 'Montserrat', sans-serif; font-size: 0.9rem; font-weight: 700;
            text-decoration: none; border: none; cursor: pointer;
            transition: all 0.22s ease;
            box-shadow: 0 4px 16px rgba(30,179,73,0.28);
            letter-spacing: 0.01em;
        }
        .ps-btn-buy:hover { background: linear-gradient(135deg, #16a34a, #14532d); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(30,179,73,0.38); color: #fff; }
        .ps-btn-buy:active { transform: translateY(0); }
        .ps-btn-buy:disabled, .ps-btn-buy.disabled { background: var(--s2); color: var(--s4); cursor: not-allowed; box-shadow: none; transform: none; }

        /* ── 2-BUTTON ROW ── */
        .ps-cta-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.55rem; }
        .ps-btn-cart {
            display: flex; align-items: center; justify-content: center; gap: 0.45rem;
            width: 100%; padding: 0.82rem 1rem; border-radius: var(--r10);
            background: var(--wh); color: var(--em2);
            border: 2px solid var(--em);
            font-family: 'Montserrat', sans-serif; font-size: 0.87rem; font-weight: 700;
            text-decoration: none; cursor: pointer;
            transition: all 0.2s ease;
            letter-spacing: 0.01em;
        }
        .ps-btn-cart:hover { background: var(--eml); border-color: var(--em2); }
        .ps-btn-cart-badge {
            background: #ef4444; color: #ffffff;
            font-size: 0.68rem; font-weight: 800;
            min-width: 19px; height: 19px;
            border-radius: 999px;
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0 4px; line-height: 1; margin-left: 0.15rem;
            box-shadow: 0 2px 5px rgba(239, 68, 68, 0.35);
        }
        .ps-btn-checkout {
            display: flex; align-items: center; justify-content: center; gap: 0.45rem;
            width: 100%; padding: 0.82rem 1rem; border-radius: var(--r10);
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #fff; border: 2px solid transparent;
            font-family: 'Montserrat', sans-serif; font-size: 0.87rem; font-weight: 700;
            text-decoration: none; cursor: pointer;
            transition: all 0.22s ease;
            box-shadow: 0 4px 14px rgba(30,179,73,0.35);
            letter-spacing: 0.01em;
        }
        .ps-btn-checkout:hover { background: linear-gradient(135deg, #179b3e 0%, #94bc2e 100%); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(30,179,73,0.45); color: #fff; }
        .ps-btn-checkout:active, .ps-btn-cart:active { transform: translateY(0); }
        .ps-btn-disabled { background: var(--s2); color: var(--s4); cursor: not-allowed; border-color: var(--s2); box-shadow: none; }
        @media (max-width: 380px) { .ps-cta-row { grid-template-columns: 1fr; } }

        /* ── DESCRIPTION CARD (BELOW) ── */
        .ps-desc-card {
            background: var(--wh); border-radius: var(--r16); border: 1px solid var(--s2); box-shadow: var(--shd);
            overflow: hidden;
        }
        .ps-desc-header {
            padding: 1rem 1.5rem; border-bottom: 1px solid var(--s2);
            font-size: 0.88rem; font-weight: 700; color: var(--s9); letter-spacing: -0.01em;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .ps-desc-body { padding: 1.25rem 1.5rem; font-size: 0.85rem; color: var(--s7); line-height: 1.75; }
        .ps-desc-body p { margin-bottom: 0.65rem; }
        .ps-desc-body ul, .ps-desc-body ol { padding-left: 1.25rem; margin-bottom: 0.65rem; }
        .ps-desc-body h1, .ps-desc-body h2, .ps-desc-body h3 { color: var(--s9); font-weight: 700; margin-bottom: 0.4rem; }
        .ps-desc-body img { max-width: 100%; border-radius: var(--r6); }
        .ps-desc-body table { width: 100%; border-collapse: collapse; margin-bottom: 0.65rem; }
        .ps-desc-body table td, .ps-desc-body table th { border: 1px solid var(--s2); padding: 0.4rem 0.6rem; font-size: 0.82rem; }

        /* ── FLOATING WA ── */
        .t5-floating-wa {
            position: fixed; bottom: 24px; right: 24px; z-index: 9999;
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: #fff; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 24px rgba(37,211,102,0.4);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-decoration: none;
        }
        .t5-floating-wa:hover { transform: scale(1.1) translateY(-2px); box-shadow: 0 12px 32px rgba(37,211,102,0.55); color: #fff; }
        .t5-wa-pulse { position: absolute; inset: 0; border-radius: 50%; border: 2px solid #25D366; animation: waPulse 2s infinite; pointer-events: none; }
        @keyframes waPulse { 0% { transform: scale(1); opacity: 0.8; } 100% { transform: scale(1.5); opacity: 0; } }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .ps-wrap { padding: 0; gap: 0; }
            .bc-bar { display: none; }
            .ps-top { grid-template-columns: 1fr; border-radius: 0; border-left: none; border-right: none; border-top: none; }
            .ps-gallery { border-right: none; border-bottom: 1px solid var(--s2); }
            .ps-slider-wrap { aspect-ratio: 4/3; }
            .ps-info { padding: 1.15rem 1rem 1.25rem; gap: 0.85rem; }
            .ps-title { font-size: 1.15rem; }
            .ps-price { font-size: 1.3rem; }
            .ps-desc-card { border-radius: 0; border-left: none; border-right: none; margin-top: 0.5rem; }
            .ps-desc-header { padding: 0.85rem 1rem; }
            .ps-desc-body { padding: 1rem; }
            .t5-floating-wa { bottom: 16px; right: 16px; width: 50px; height: 50px; }
        }
        @media (max-width: 480px) {
            .ps-info { padding: 1rem 0.9rem; }
            .ps-price { font-size: 1.2rem; }
            .ps-title { font-size: 1.05rem; }
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    @include('bio.theme5.header', ['products' => $products, 'blocks' => $blocks, 'config' => $config, 'profile' => $profile, 'username' => $username])

    {{-- BREADCRUMB (Desktop only) --}}
    <div class="bc-bar">
        <div class="bc-inner">
            <a href="{{ url('/' . $username) }}">Beranda</a>
            <span class="bc-sep">›</span>
            <a href="{{ url('/' . $username . '/produk') }}">Produk</a>
            <span class="bc-sep">›</span>
            <span class="bc-cur">{{ $prodTitle }}</span>
        </div>
    </div>

    <div class="ps-wrap">

        @if(session('error'))
            <div style="background:#fef2f2;color:#dc2626;border:1px solid #fca5a5;padding:0.75rem 1rem;border-radius:10px;font-size:0.82rem;font-weight:600;margin:0 1.25rem;">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:0.75rem 1rem;border-radius:10px;font-size:0.82rem;font-weight:600;margin:0 1.25rem;">{{ session('success') }}</div>
        @endif

        {{-- TOP CARD: GALLERY + INFO --}}
        <div class="ps-top">

            {{-- LEFT: GALLERY --}}
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
                                    <img src="{{ $imgUrl }}" alt="{{ $prodTitle }}" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="ps-no-img">
                            <svg width="52" height="52" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24" style="opacity:.25"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <span style="font-size:.75rem;color:var(--s4);">Foto tidak tersedia</span>
                        </div>
                    @endif
                </div>

                @if(count($images) > 1)
                    <div class="ps-thumbs">
                        @foreach($images as $i => $img)
                            @php $tUrl = (\Illuminate\Support\Str::startsWith($img, 'http://') || \Illuminate\Support\Str::startsWith($img, 'https://')) ? $img : asset('storage/' . $img); @endphp
                            <div class="ps-thumb {{ $i === 0 ? 'active' : '' }}" onclick="psTo({{ $i }})">
                                <img src="{{ $tUrl }}" alt="Foto {{ $i+1 }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- RIGHT: PRODUCT INFO --}}
            <div class="ps-info">

                {{-- Stock only (no category badge) --}}
                <div class="ps-meta-row">
                    @if($stock === null || (int)$stock < 0)
                        <span class="ps-badge-stock stock-unlimited">• Unlimited</span>
                    @elseif((int)$stock > 0)
                        <span class="ps-badge-stock stock-in">• Sisa {{ $stock }} unit</span>
                    @else
                        <span class="ps-badge-stock stock-out">• Stok Habis</span>
                    @endif
                </div>

                {{-- Title --}}
                <h1 class="ps-title">{{ $prodTitle }}</h1>

                {{-- Rating & verified --}}
                <div class="ps-rating-row">
                    <span class="ps-stars">
                        <svg width="13" height="13" fill="#f59e0b" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        {{ $ratingVal }}
                    </span>
                    <span>•</span>
                    <span class="ps-verified">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Terverifikasi
                    </span>
                </div>

                {{-- Price --}}
                <div class="ps-price-box">
                    <span class="ps-price">Rp {{ number_format($price, 0, ',', '.') }}</span>
                    @if($hasDiscount)
                        <span class="ps-orig">Rp {{ number_format($origPrice, 0, ',', '.') }}</span>
                        <span class="ps-save">Hemat {{ $discountPct }}%</span>
                    @endif
                </div>

                {{-- Short description --}}
                @if(!empty($shortDesc))
                    <p class="ps-short-desc">{{ $shortDesc }}</p>
                @endif

                {{-- Seller mini card --}}
                <div class="ps-seller">
                    <div class="ps-seller-l">
                        @if(!empty($config['avatar']))
                            <img src="{{ asset('storage/' . $config['avatar']) }}" alt="{{ $bioName }}" class="ps-av-img">
                        @else
                            <div class="ps-av-fb">{{ strtoupper(substr($bioName, 0, 1)) }}</div>
                        @endif
                        <div>
                            <div class="ps-seller-name">{{ $bioName }}</div>
                        </div>
                    </div>
                    <a href="{{ url('/' . $username) }}" class="ps-seller-link">Profil →</a>
                </div>

                {{-- CTA BUTTONS: 2-column Keranjang + Checkout --}}
                @php
                    $prodId = $product ? $product->id : ($block->data_json['product_id'] ?? null);
                    $cleanNum = preg_replace('/[^0-9]/', '', $waNumber);
                    $ctaMsg = 'Halo, saya ingin memesan *' . $prodTitle . '* (Rp ' . number_format($price, 0, ',', '.') . '). Apakah masih tersedia?';
                    $waOrderUrl = !empty($cleanNum) ? 'https://wa.me/' . $cleanNum . '?text=' . urlencode($ctaMsg) : '#';
                @endphp

                @if($isOutOfStock)
                    <div class="ps-cta-row">
                        <button class="ps-btn-cart ps-btn-disabled" disabled>
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                            Stok Habis
                        </button>
                        <button class="ps-btn-checkout ps-btn-disabled" disabled>
                            Stok Habis
                        </button>
                    </div>
                @elseif($paymentMethod === 'wa' && $waNumber)
                    <div class="ps-cta-row">
                        <a href="https://buyle.id/keranjang" class="ps-btn-cart">
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            Keranjang
                        </a>
                        <a href="{{ $waOrderUrl }}" target="_blank" class="ps-btn-checkout">
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            Checkout
                        </a>
                    </div>
                @elseif($prodId)
                    <div class="ps-cta-row">
                        <form action="{{ route('cart.add') }}" method="POST" style="display:contents;">
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
                    <div class="ps-cta-row">
                        <a href="https://buyle.id/keranjang" class="ps-btn-cart">
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            Keranjang
                        </a>
                        <a href="{{ $waOrderUrl }}" target="_blank" class="ps-btn-checkout">
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            Checkout
                        </a>
                    </div>
                @endif

            </div>{{-- end .ps-info --}}
        </div>{{-- end .ps-top --}}

        {{-- DESCRIPTION CARD (BELOW) --}}
        @if(!empty($rawDesc))
        <div class="ps-desc-card">
            <div class="ps-desc-header">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Deskripsi Produk
            </div>
            <div class="ps-desc-body">{!! $rawDesc !!}</div>
        </div>
        @endif

    </div>{{-- end .ps-wrap --}}

    {{-- FLOATING WA --}}
    @if(!empty($config['wa']))
        @php $waFN = preg_replace('/[^0-9]/', '', $config['wa']); @endphp
        @if($waFN)
            <a href="https://wa.me/{{ \Illuminate\Support\Str::startsWith($waFN, '62') ? $waFN : '62' . ltrim($waFN, '0') }}"
               target="_blank" rel="noopener noreferrer" class="t5-floating-wa" title="Chat via WhatsApp">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.556 4.117 1.528 5.849L0 24l6.335-1.508A11.948 11.948 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.65-.52-5.154-1.422l-.37-.218-3.764.896.924-3.667-.243-.381A9.953 9.953 0 0 1 2 12c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10z"/>
                </svg>
                <span class="t5-wa-pulse"></span>
            </a>
        @endif
    @endif

    {{-- FOOTER --}}
    @include('bio.theme5.footer', ['products' => $products, 'config' => $config, 'profile' => $profile, 'username' => $username])

    <script>
        function toggleT5Drawer() {
            var d = document.getElementById('t5MobileDrawer');
            if (d) d.classList.toggle('active');
        }
        let psCur = 0;
        const psTotal = {{ count($images) }};
        function psTo(i) {
            psCur = Math.max(0, Math.min(i, psTotal - 1));
            var t = document.getElementById('psTrack');
            if (t) t.style.transform = 'translateX(-' + (psCur * 100) + '%)';
            document.querySelectorAll('.ps-thumb').forEach(function(el, idx) {
                el.classList.toggle('active', idx === psCur);
            });
        }
        function psMove(d) { psTo(psCur + d); }

        // Touch swipe
        var txStart = 0;
        var pw = document.getElementById('psWrap');
        if (pw) {
            pw.addEventListener('touchstart', function(e) { txStart = e.changedTouches[0].screenX; }, {passive:true});
            pw.addEventListener('touchend', function(e) {
                var diff = txStart - e.changedTouches[0].screenX;
                if (Math.abs(diff) > 40) psMove(diff > 0 ? 1 : -1);
            });
        }
    </script>

    @include('partials.adsense_modal')
    @include('partials.report_modal', ['reportType' => 'product', 'targetName' => $prodTitle])
</body>
</html>
