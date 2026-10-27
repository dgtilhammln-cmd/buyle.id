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
        $pType = strtolower($product->product_type ?? $product->type ?? '');

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
            $category = 'Produk';
        }

        $stock = isset($block->data_json['stock']) && $block->data_json['stock'] !== '' && $block->data_json['stock'] !== null ? (int) $block->data_json['stock'] : ($product ? $product->stock : null);
        $isOutOfStock = ($stock !== null && $stock <= 0);
        $firstImage = !empty($images[0]) ? (\Illuminate\Support\Str::startsWith($images[0], 'http') ? $images[0] : asset('storage/' . $images[0])) : asset('images/buyle-og.png');
        $bioName = $config['name'] ?? $profile->store_name ?? $username;
        $pageTitle = $prodTitle . ' - ' . $bioName . ' | buyle.id';
        $rawDesc = !empty($block->data_json['description']) ? $block->data_json['description'] : ($product ? ($product->description ?: $product->short_desc) : '');
        $shortDesc = !empty($rawDesc) ? \Illuminate\Support\Str::limit(strip_tags($rawDesc), 160) : '';
        $pageDesc = !empty($shortDesc) ? $shortDesc : 'Beli ' . $prodTitle . ' berkualitas dengan harga terbaik dari ' . $bioName . ' di buyle.id.';
        $hasDiscount = !empty($origPrice) && $origPrice > $price;
        $discountPct = $hasDiscount ? round((($origPrice - $price) / $origPrice) * 100) : 0;
        $ratingVal = $product ? $product->getEffectiveRating() : null;
        $blocks = $blocks ?? $profile->bioBlocks;
        $products = $products ?? \App\Models\CreatorBioBlock::where('creator_id', $profile->id)->where('is_active', true)->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])->get();
        $formattedImages = array_map(function ($img) {
            return (\Illuminate\Support\Str::startsWith($img, 'http://') || \Illuminate\Support\Str::startsWith($img, 'https://')) ? $img : asset('storage/' . $img);
        }, $images);

        $isSellerVerified = false;
        if (isset($profile) && $profile) {
            $isSellerVerified = $profile->isVerified();
        } elseif ($product && $product->seller && $product->seller->creatorProfile) {
            $isSellerVerified = $product->seller->creatorProfile->isVerified();
        }
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Google Search Console Meta Verification Tag --}}
    @php
        $rawGsc = $profile->site_verification_code ?? $config['site_verification_code'] ?? $config['google_search_console'] ?? null;
        $gscToken = null;
        if (!empty($rawGsc)) {
            if (preg_match('/content=["\']([^"\']+)["\']/i', $rawGsc, $m)) {
                $gscToken = trim($m[1]);
            } elseif (\Illuminate\Support\Str::contains($rawGsc, 'google-site-verification=')) {
                $gscToken = trim(str_replace('google-site-verification=', '', $rawGsc));
                $gscToken = preg_replace('/["\'>\/].*$/', '', $gscToken);
            } else {
                $gscToken = trim(strip_tags($rawGsc));
            }
        }
    @endphp
    @if(!empty($gscToken))
        <meta name="google-site-verification" content="{{ $gscToken }}" />
    @endif

    <meta property="og:title" content="{{ $prodTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:image" content="{{ $firstImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="product">
    <meta property="product:price:amount" content="{{ $price }}">
    <meta property="product:price:currency" content="IDR">
    <meta name="thumbnail" content="{{ $firstImage }}">
    @if(!empty($config['theme5_favicon']))
        <link rel="icon" href="{{ asset('storage/' . $config['theme5_favicon']) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=4">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=4">
    @endif
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">


    @php
        $canonProductUrl = url()->current();
        $prodImage = $firstImage;

        $pRawRating = (float) ($ratingVal ?? 0);
        $prodRatingVal = $pRawRating >= 1.0 ? number_format($pRawRating, 1) : null;
        $realReviewCnt = $product ? $product->getEffectiveReviewsCount() : 0;

        $pNameLower = strtolower($prodTitle);
        $pTypeLower = strtolower($pType);

        $isFoodProduct = in_array($pTypeLower, ['makanan', 'food', 'kuliner', 'fnb', 'resto', 'minuman']) ||
            preg_match('/\b(bakso|mie|nasi|soto|ayam|bebek|es|kopi|makanan|kuliner|resto)\b/i', $pNameLower);

        $isServiceProduct = in_array($pTypeLower, ['jasa', 'service', 'layanan', 'agency', 'software', 'aplikasi']) ||
            preg_match('/\b(testgo|website|web|jasa|service|layanan|software|app|design|desain|agency|konsultasi)\b/i', $pNameLower);

        $mainProductSchema = [
            '@type' => 'Product',
            '@id' => $canonProductUrl . '#product',
            'name' => $prodTitle,
            'image' => $prodImage,
            'description' => strip_tags($pageDesc),
            'brand' => [
                '@type' => 'Brand',
                'name' => $bioName,
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => $canonProductUrl,
                'priceCurrency' => 'IDR',
                'price' => (string) $price,
                'availability' => $isOutOfStock ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock',
            ]
        ];

        if ($prodRatingVal !== null && $realReviewCnt > 0) {
            $mainProductSchema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $prodRatingVal,
                'reviewCount' => (string) $realReviewCnt,
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }

        $productSchemaGraph = [
            $mainProductSchema,
            [
                '@type' => 'BreadcrumbList',
                '@id' => $canonProductUrl . '#breadcrumb',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Beranda',
                        'item' => url('/' . $username),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => $category,
                        'item' => url('/' . $username . '/produk'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $prodTitle,
                        'item' => $canonProductUrl,
                    ]
                ]
            ]
        ];

        if ($isServiceProduct) {
            $productSchemaGraph[] = [
                '@type' => 'HowTo',
                '@id' => $canonProductUrl . '#howto',
                'name' => 'Cara Menggunakan & Memesan ' . $prodTitle,
                'description' => 'Panduan langkah demi langkah penggunaan dan pemesanan layanan ' . $prodTitle . ' dari ' . $bioName,
                'step' => [
                    [
                        '@type' => 'HowToStep',
                        'position' => 1,
                        'name' => 'Pilih Paket ' . $prodTitle,
                        'text' => 'Buka rincian produk ' . $prodTitle . ' di buyle.id dan periksa spesifikasi layanan.'
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 2,
                        'name' => 'Lakukan Pembayaran / Pesan Sekarang',
                        'text' => 'Klik tombol Beli / Pesan atau selesaikan instruksi pembayaran secara langsung.'
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 3,
                        'name' => 'Serah Terima & Konsultasi Layanan',
                        'text' => 'Tim ' . $bioName . ' akan langsung memproses serta menyerahkan akses produk ' . $prodTitle . '.'
                    ]
                ]
            ];
        }

        if ($isFoodProduct) {
            $productSchemaGraph[] = [
                '@type' => 'HowTo',
                '@id' => $canonProductUrl . '#howto',
                'name' => 'Cara Pesan & Nikmati ' . $prodTitle,
                'description' => 'Panduan cara memesan dan menikmati hidangan ' . $prodTitle . ' segar hangat dari ' . $bioName,
                'step' => [
                    [
                        '@type' => 'HowToStep',
                        'position' => 1,
                        'name' => 'Pilih Menu & Porsi ' . $prodTitle,
                        'text' => 'Buka rincian menu ' . $prodTitle . ' dan tentukan porsi serta catatan rasa.'
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 2,
                        'name' => 'Pemesanan & Delivery Instant',
                        'text' => 'Klik tombol Beli / Pesan WhatsApp untuk pengiriman kilat kurir instan atau dine-in.'
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 3,
                        'name' => 'Nikmati Hidangan Fresh',
                        'text' => 'Hidangan ' . $prodTitle . ' siap disajikan lezat.'
                    ]
                ]
            ];

            $productSchemaGraph[] = [
                '@type' => 'MenuItem',
                '@id' => $canonProductUrl . '#menuitem',
                'name' => $prodTitle,
                'description' => strip_tags($pageDesc),
                'image' => $prodImage,
                'offers' => [
                    '@type' => 'Offer',
                    'price' => (string) $price,
                    'priceCurrency' => 'IDR',
                    'availability' => $isOutOfStock ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock',
                ]
            ];
        }

        $productSchemaJson = [
            '@context' => 'https://schema.org',
            '@graph' => $productSchemaGraph,
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($productSchemaJson, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --em: #1eb349;
            --em2: #15803d;
            --eml: #f0fdf4;
            --emb: #bbf7d0;
            --s9: #0f172a;
            --s8: #1e293b;
            --s7: #334155;
            --s6: #475569;
            --s5: #64748b;
            --s4: #94a3b8;
            --s2: #e2e8f0;
            --bg: #f1f5f9;
            --wh: #ffffff;
            --am: #f59e0b;
            --rd: #ef4444;
            --r6: 6px;
            --r10: 10px;
            --r16: 16px;
            --shd: 0 4px 24px rgba(15, 23, 42, 0.07);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--s8);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── BREADCRUMB ── */
        .bc-bar {
            background: var(--wh);
            border-bottom: 1px solid var(--s2);
        }

        .bc-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0.65rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.75rem;
            color: var(--s5);
            flex-wrap: wrap;
        }

        .bc-inner a {
            color: var(--s6);
            text-decoration: none;
            font-weight: 500;
        }

        .bc-inner a:hover {
            color: var(--em);
        }

        .bc-sep {
            color: var(--s4);
        }

        .bc-cur {
            color: var(--s9);
            font-weight: 600;
            max-width: 260px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── PAGE LAYOUT ── */
        .ps-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1.5rem 1.25rem 3rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

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
        .ps-gallery {
            background: #fafafa;
            border-right: 1px solid var(--s2);
            display: flex;
            flex-direction: column;
        }

        .ps-slider-wrap {
            position: relative;
            overflow: hidden;
            aspect-ratio: 1/1;
            width: 100%;
            background: #f8f8f8;
        }

        .ps-slider-track {
            display: flex;
            height: 100%;
            transition: transform 0.38s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .ps-slide {
            min-width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .ps-slide img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .ps-no-img {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            gap: 0.75rem;
            color: var(--s4);
        }

        .ps-btn-prev,
        .ps-btn-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(15, 23, 42, 0.55);
            color: #fff;
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
            backdrop-filter: blur(4px);
            transition: background 0.2s;
        }

        .ps-btn-prev:hover,
        .ps-btn-next:hover {
            background: rgba(30, 179, 73, 0.9);
        }

        .ps-btn-prev {
            left: 10px;
        }

        .ps-btn-next {
            right: 10px;
        }

        .ps-disc-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 4;
            background: var(--rd);
            color: #fff;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.2rem 0.5rem;
            border-radius: 5px;
            letter-spacing: 0.03em;
        }

        .ps-thumbs {
            display: flex;
            gap: 0.45rem;
            padding: 0.65rem 0.75rem;
            overflow-x: auto;
            border-top: 1px solid var(--s2);
        }

        .ps-thumb {
            width: 54px;
            height: 54px;
            border-radius: var(--r6);
            overflow: hidden;
            border: 2px solid var(--s2);
            cursor: pointer;
            flex-shrink: 0;
            transition: border-color 0.2s;
            background: #fff;
        }

        .ps-thumb.active,
        .ps-thumb:hover {
            border-color: var(--em);
        }

        .ps-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* ── DETAILS (RIGHT) ── */
        .ps-info {
            padding: 1.75rem 1.75rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .ps-meta-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .ps-badge-cat {
            font-size: 0.67rem;
            font-weight: 700;
            background: var(--eml);
            color: var(--em2);
            border: 1px solid var(--emb);
            padding: 0.2rem 0.55rem;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .ps-badge-stock {
            font-size: 0.72rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
        }

        .stock-in {
            color: #16a34a;
        }

        .stock-out {
            color: var(--rd);
        }

        .stock-unlimited {
            color: var(--s5);
        }

        .ps-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--s9);
            line-height: 1.3;
            letter-spacing: -0.025em;
        }

        .ps-rating-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: var(--s5);
        }

        .ps-stars {
            display: flex;
            align-items: center;
            gap: 0.2rem;
            color: var(--am);
            font-weight: 600;
            font-size: 0.78rem;
        }

        .ps-verified {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            color: var(--em2);
            font-weight: 500;
        }

        .ps-price-box {
            background: var(--bg);
            border: 1px solid var(--s2);
            border-radius: var(--r10);
            padding: 0.85rem 1.1rem;
            display: flex;
            align-items: baseline;
            gap: 0.65rem;
            flex-wrap: wrap;
        }

        .ps-price {
            font-size: 1.55rem;
            font-weight: 700;
            color: var(--em2);
            letter-spacing: -0.03em;
        }

        .ps-orig {
            font-size: 0.85rem;
            color: var(--s4);
            text-decoration: line-through;
        }

        .ps-save {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--rd);
            background: #fef2f2;
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
        }

        /* short desc */
        .ps-short-desc {
            font-size: 0.83rem;
            color: var(--s6);
            line-height: 1.65;
        }

        /* Seller mini card */
        .ps-seller {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid var(--s2);
            border-radius: var(--r10);
            padding: 0.6rem 0.9rem;
            gap: 0.6rem;
        }

        .ps-seller-l {
            display: flex;
            align-items: center;
            gap: 0.55rem;
        }

        .ps-av-img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--em);
        }

        .ps-av-fb {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--em), var(--em2));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.88rem;
        }

        .ps-seller-name {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--s9);
        }

        .ps-seller-sub {
            font-size: 0.67rem;
            color: var(--s5);
        }

        .ps-seller-link {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--em2);
            text-decoration: none;
            white-space: nowrap;
        }

        .ps-seller-link:hover {
            text-decoration: underline;
        }

        /* CTA Buttons */
        .ps-cta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .ps-btn-buy {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.85rem 1.2rem;
            border-radius: var(--r10);
            background: linear-gradient(135deg, var(--em) 0%, var(--em2) 100%);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.22s ease;
            box-shadow: 0 4px 16px rgba(30, 179, 73, 0.28);
            letter-spacing: 0.01em;
        }

        .ps-btn-buy:hover {
            background: linear-gradient(135deg, #16a34a, #14532d);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(30, 179, 73, 0.38);
            color: #fff;
        }

        .ps-btn-buy:active {
            transform: translateY(0);
        }

        .ps-btn-buy:disabled,
        .ps-btn-buy.disabled {
            background: var(--s2);
            color: var(--s4);
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        /* ── 2-BUTTON ROW ── */
        .ps-cta-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.55rem;
        }

        .ps-btn-cart {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            width: 100%;
            padding: 0.82rem 1rem;
            border-radius: var(--r10);
            background: var(--wh);
            color: var(--em2);
            border: 2px solid var(--em);
            font-family: 'Montserrat', sans-serif;
            font-size: 0.87rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            letter-spacing: 0.01em;
        }

        .ps-btn-cart:hover {
            background: var(--eml);
            border-color: var(--em2);
        }

        .ps-btn-cart-badge {
            background: #ef4444;
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 800;
            min-width: 19px;
            height: 19px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            line-height: 1;
            margin-left: 0.15rem;
            box-shadow: 0 2px 5px rgba(239, 68, 68, 0.35);
        }

        .ps-btn-checkout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            width: 100%;
            padding: 0.82rem 1rem;
            border-radius: var(--r10);
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #fff;
            border: 2px solid transparent;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.87rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.22s ease;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
            letter-spacing: 0.01em;
        }

        .ps-btn-checkout:hover {
            background: linear-gradient(135deg, #179b3e 0%, #94bc2e 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45);
            color: #fff;
        }

        .ps-btn-checkout:active,
        .ps-btn-cart:active {
            transform: translateY(0);
        }

        .ps-btn-disabled {
            background: var(--s2);
            color: var(--s4);
            cursor: not-allowed;
            border-color: var(--s2);
            box-shadow: none;
        }

        @media (max-width: 380px) {
            .ps-cta-row {
                grid-template-columns: 1fr;
            }
        }

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
            border-bottom: 1px dashed rgba(255, 255, 255, 0.15);
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
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ef4444;
            box-shadow: 0 0 8px #ef4444;
            animation: pulse-dot 1.2s infinite ease-in-out;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.4);
                opacity: 0.6;
            }
        }

        .ticket-war-title {
            font-size: 0.76rem;
            font-weight: 600;
            color: #94a3b8;
        }

        .ticket-war-timer-grid {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            margin: 0.5rem 0 0.4rem;
        }

        .war-timer-box {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
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

        /* ── DESCRIPTION CARD (BELOW) ── */
        .ps-desc-card {
            background: var(--wh);
            border-radius: var(--r16);
            border: 1px solid var(--s2);
            box-shadow: var(--shd);
            overflow: hidden;
        }

        .ps-desc-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--s2);
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--s9);
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .ps-desc-body {
            padding: 1.25rem 1.5rem;
            font-size: 0.85rem;
            color: var(--s7);
            line-height: 1.75;
        }

        .ps-desc-body p {
            margin-bottom: 0.65rem;
        }

        .ps-desc-body ul,
        .ps-desc-body ol {
            padding-left: 1.25rem;
            margin-bottom: 0.65rem;
        }

        .ps-desc-body h1,
        .ps-desc-body h2,
        .ps-desc-body h3 {
            color: var(--s9);
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .ps-desc-body img {
            max-width: 100%;
            border-radius: var(--r6);
        }

        .ps-desc-body table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.65rem;
        }

        .ps-desc-body table td,
        .ps-desc-body table th {
            border: 1px solid var(--s2);
            padding: 0.4rem 0.6rem;
            font-size: 0.82rem;
        }

        /* ── FLOATING WA ── */
        .t5-floating-wa {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-decoration: none;
        }

        .t5-floating-wa:hover {
            transform: scale(1.1) translateY(-2px);
            box-shadow: 0 12px 32px rgba(37, 211, 102, 0.55);
            color: #fff;
        }

        .t5-wa-pulse {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid #25D366;
            animation: waPulse 2s infinite;
            pointer-events: none;
        }

        @keyframes waPulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .ps-wrap {
                padding: 0;
                gap: 0;
            }

            .bc-bar {
                display: none;
            }

            .ps-top {
                grid-template-columns: 1fr;
                border-radius: 0;
                border-left: none;
                border-right: none;
                border-top: none;
            }

            .ps-gallery {
                border-right: none;
                border-bottom: 1px solid var(--s2);
            }

            .ps-slider-wrap {
                aspect-ratio: 4/3;
            }

            .ps-info {
                padding: 1.15rem 1rem 1.25rem;
                gap: 0.85rem;
            }

            .ps-title {
                font-size: 1.15rem;
            }

            .ps-price {
                font-size: 1.3rem;
            }

            .ps-desc-card {
                border-radius: 0;
                border-left: none;
                border-right: none;
                margin-top: 0.5rem;
            }

            .ps-desc-header {
                padding: 0.85rem 1rem;
            }

            .ps-desc-body {
                padding: 1rem;
            }

            .t5-floating-wa {
                bottom: 16px;
                right: 16px;
                width: 50px;
                height: 50px;
            }
        }

        @media (max-width: 480px) {
            .ps-info {
                padding: 1rem 0.9rem;
            }

            .ps-price {
                font-size: 1.2rem;
            }

            .ps-title {
                font-size: 1.05rem;
            }
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    @include('bio.theme5.header', ['products' => $products, 'blocks' => $blocks, 'config' => $config, 'profile' => $profile, 'username' => $username])

    @php
        $homeUrl = !empty($profile->custom_domain) ? 'https://' . rtrim($profile->custom_domain, '/') : url('/' . $username);
        $productsUrl = !empty($profile->custom_domain) ? 'https://' . rtrim($profile->custom_domain, '/') . '/produk' : url('/' . $username . '/produk');
    @endphp

    {{-- BREADCRUMB (Desktop only) --}}
    <div class="bc-bar">
        <div class="bc-inner">
            <a href="{{ $homeUrl }}">Beranda</a>
            <span class="bc-sep">›</span>
            <a href="{{ $productsUrl }}">Produk</a>
            <span class="bc-sep">›</span>
            <span class="bc-cur">{{ $prodTitle }}</span>
        </div>
    </div>

    <div class="ps-wrap">

        @if(session('error'))
            <div
                style="background:#fef2f2;color:#dc2626;border:1px solid #fca5a5;padding:0.75rem 1rem;border-radius:10px;font-size:0.82rem;font-weight:600;margin:0 1.25rem;">
                {{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div
                style="background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;padding:0.75rem 1rem;border-radius:10px;font-size:0.82rem;font-weight:600;margin:0 1.25rem;">
                {{ session('success') }}</div>
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
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </button>
                        <button class="ps-btn-next" onclick="psMove(1)" aria-label="Next">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </button>
                    @endif

                    @if(count($images) > 0)
                        <div class="ps-slider-track" id="psTrack">
                            @foreach($images as $img)
                                @php $imgUrl = (\Illuminate\Support\Str::startsWith($img, 'http://') || \Illuminate\Support\Str::startsWith($img, 'https://')) ? $img : asset('storage/' . $img); @endphp
                                <div class="ps-slide">
                                    <img src="{{ $imgUrl }}" alt="{{ $prodTitle }}" loading="lazy"
                                        onclick="openImageLightbox({{ $loop->index }})" style="cursor:zoom-in;"
                                        title="Klik untuk memperbesar / zoom" draggable="false">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="ps-no-img">
                            <svg width="52" height="52" fill="none" stroke="currentColor" stroke-width="1.2"
                                viewBox="0 0 24 24" style="opacity:.25">
                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                            <span style="font-size:.75rem;color:var(--s4);">Foto tidak tersedia</span>
                        </div>
                    @endif
                </div>

                @if(count($images) > 1)
                    <div class="ps-thumbs">
                        @foreach($images as $i => $img)
                            @php $tUrl = (\Illuminate\Support\Str::startsWith($img, 'http://') || \Illuminate\Support\Str::startsWith($img, 'https://')) ? $img : asset('storage/' . $img); @endphp
                            <div class="ps-thumb {{ $i === 0 ? 'active' : '' }}" onclick="psTo({{ $i }})">
                                <img src="{{ $tUrl }}" alt="Foto {{ $i + 1 }}" draggable="false">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- RIGHT: PRODUCT INFO --}}
            <div class="ps-info">

                {{-- Stock only (no category badge) --}}
                <div class="ps-meta-row">
                    @if($stock === null || (int) $stock < 0)
                        <span class="ps-badge-stock stock-unlimited">• Unlimited</span>
                    @elseif((int) $stock > 0)
                        <span class="ps-badge-stock stock-in">• Sisa {{ $stock }} unit</span>
                    @else
                        <span class="ps-badge-stock stock-out">• Stok Habis</span>
                    @endif
                </div>

                {{-- Title --}}
                <h1 class="ps-title">{{ $prodTitle }}</h1>

                {{-- Rating & verified --}}
                @if($ratingVal !== null && $ratingVal > 0)
                <div class="ps-rating-row">
                    <span class="ps-stars">
                        <svg width="13" height="13" fill="#f59e0b" viewBox="0 0 24 24">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                        </svg>
                        {{ number_format($ratingVal, 1) }}
                    </span>
                    @if($isSellerVerified)
                        <span>•</span>
                        <span class="ps-verified">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                            Terverifikasi
                        </span>
                    @endif
                </div>
                @elseif($isSellerVerified)
                <div class="ps-rating-row">
                    <span class="ps-verified">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        Terverifikasi
                    </span>
                </div>
                @endif

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

                @if($pType === 'ticket')
                    @php
                        $tEventDate = $product ? $product->event_date : (!empty($block->data_json['event_date']) ? \Carbon\Carbon::parse($block->data_json['event_date']) : null);
                        $tEventTime = $product ? $product->event_time : ($block->data_json['event_time'] ?? null);
                        $tEventType = $product ? ($product->event_type ?? 'offline') : ($block->data_json['event_type'] ?? 'offline');
                        $tEventLoc = $product ? $product->event_location : ($block->data_json['event_location'] ?? null);
                        $tEventPlatform = $product ? $product->event_platform : ($block->data_json['event_platform'] ?? null);
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

                {{-- Seller mini card --}}
                <div class="ps-seller">
                    <div class="ps-seller-l">
                        @if(!empty($config['avatar']))
                            <img src="{{ asset('storage/' . $config['avatar']) }}" alt="{{ $bioName }}" class="ps-av-img"
                                draggable="false">
                        @else
                            <div class="ps-av-fb">{{ strtoupper(substr($bioName, 0, 1)) }}</div>
                        @endif
                        <div>
                            <div class="ps-seller-name" style="display:flex; align-items:center; gap:0.35rem;">
                                <span>{{ $bioName }}</span>
                                @if($isSellerVerified)
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#0D9488" title="Verified Creator">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('/' . $username) }}" class="ps-seller-link">Profil →</a>
                </div>

                {{-- CTA BUTTONS: 2-column Keranjang + Checkout atau Mode WA --}}
                @php
                    $prodId = $product ? $product->id : ($block->data_json['product_id'] ?? null);
                    $cleanNum = preg_replace('/[^0-9]/', '', $waNumber);

                    $isBuyleCheckout = true;
                    if ($product && isset($product->is_buyle_checkout)) {
                        $isBuyleCheckout = (bool) $product->is_buyle_checkout;
                    } elseif (isset($block->data_json['is_buyle_checkout'])) {
                        $isBuyleCheckout = (bool) $block->data_json['is_buyle_checkout'];
                    } elseif ($paymentMethod === 'wa') {
                        $isBuyleCheckout = false;
                    }

                    $waLeadMessage = 'Halo, saya tertarik dengan produk *' . $prodTitle . '* (Rp ' . number_format($price, 0, ',', '.') . '). Mohon informasi lebih lanjut.';
                @endphp

                @if($isOutOfStock)
                    <div class="ps-cta-row">
                        <button class="ps-btn-cart ps-btn-disabled" disabled>
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                            </svg>
                            Stok Habis
                        </button>
                        <button class="ps-btn-checkout ps-btn-disabled" disabled>
                            Stok Habis
                        </button>
                    </div>
                @elseif(!$isBuyleCheckout)
                    {{-- Pembayaran Dibatasi / Mode WA -> Button Keranjang Hilang, Button Checkout Berubah jadi "Hubungi
                    Kami" --}}
                    <div class="ps-cta-row" style="grid-template-columns: 1fr;">
                        <button type="button" class="ps-btn-checkout"
                            onclick='openT5LeadModal({{ json_encode($waLeadMessage) }})'
                            style="background:linear-gradient(135deg, #25D366 0%, #128C7E 100%); font-size:0.92rem; padding:0.9rem; gap:0.6rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                            </svg>
                            Hubungi Kami
                        </button>
                    </div>
                @elseif($prodId)
                    @php
                        $isWarActive = $product && ($product->product_type ?? '') === 'ticket' && !empty($product->ticket_war_start) && now()->lt($product->ticket_war_start);
                        $warTimestamp = $isWarActive ? $product->ticket_war_start->timestamp * 1000 : 0;
                    @endphp

                    {{-- TICKET WAR REAL-TIME COUNTDOWN CARD --}}
                    @if($isWarActive)
                        <div id="t5TicketWarCard" class="ticket-war-card">
                            <div class="ticket-war-header">
                                <span class="ticket-war-badge">
                                    <span class="live-dot"></span> LIVE DROP
                                </span>
                                <span class="ticket-war-title">Penjualan Tiket Dibuka Dalam:</span>
                            </div>
                            <div class="ticket-war-timer-grid">
                                <div class="war-timer-box">
                                    <span id="t5WarDays" class="war-timer-val">00</span>
                                    <span class="war-timer-lbl">Hari</span>
                                </div>
                                <span class="war-timer-sep">:</span>
                                <div class="war-timer-box">
                                    <span id="t5WarHours" class="war-timer-val">00</span>
                                    <span class="war-timer-lbl">Jam</span>
                                </div>
                                <span class="war-timer-sep">:</span>
                                <div class="war-timer-box">
                                    <span id="t5WarMins" class="war-timer-val">00</span>
                                    <span class="war-timer-lbl">Menit</span>
                                </div>
                                <span class="war-timer-sep">:</span>
                                <div class="war-timer-box">
                                    <span id="t5WarSecs" class="war-timer-val">00</span>
                                    <span class="war-timer-lbl">Detik</span>
                                </div>
                            </div>
                            <div class="ticket-war-date">
                                Jadwal: {{ $product->ticket_war_start->isoFormat('dddd, D MMMM Y — HH:mm') }} WIB
                            </div>
                        </div>
                    @endif

                    {{-- VARIANT SELECTOR THEME 5 --}}
                    @if($product && $product->variantOptions && $product->variantOptions->count() > 0)
                        <div id="t5-variant-section" style="margin-bottom:1rem;">
                            <style>
                                .t5-var-group { margin-bottom:0.65rem; }
                                .t5-var-label { font-size:0.75rem; font-weight:700; color:#475569; margin-bottom:0.35rem; text-transform:uppercase; letter-spacing:0.03em; }
                                .t5-var-pills { display:flex; flex-wrap:wrap; gap:0.4rem; }
                                .t5-var-pill {
                                    display:inline-flex; align-items:center; gap:0.4rem;
                                    padding:0.35rem 0.8rem; border:1.5px solid #E2E8F0; border-radius:99px;
                                    background:#fff; color:#0F172A; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all 0.15s;
                                }
                                .t5-var-pill:hover:not(:disabled) { border-color:#1eb349; color:#1eb349; }
                                .t5-var-pill.selected { border-color:#1eb349; background:rgba(30,179,73,0.1); color:#1eb349; font-weight:700; }
                                .t5-var-pill:disabled { opacity:0.4; cursor:not-allowed; }
                                .t5-var-pill-img { width:20px; height:20px; border-radius:4px; object-fit:cover; }
                            </style>
                            @foreach($product->variantOptions as $opt)
                                <div class="t5-var-group">
                                    <div class="t5-var-label">{{ $opt->name }}</div>
                                    <div class="t5-var-pills">
                                        @foreach($opt->values as $val)
                                            @php
                                                $valImg = $val->image ? (\Illuminate\Support\Str::startsWith($val->image, 'http') ? $val->image : asset('storage/' . $val->image)) : null;
                                                $valFinalPrice = $val->finalPrice();
                                            @endphp
                                            <button type="button" class="t5-var-pill"
                                                data-variant-id="{{ $val->id }}"
                                                data-price="{{ $valFinalPrice }}"
                                                data-price-formatted="Rp {{ number_format($valFinalPrice, 0, ',', '.') }}"
                                                data-stock="{{ $val->stock }}"
                                                data-label="{{ $opt->name }}: {{ $val->value }}"
                                                data-img="{{ $valImg ?: $firstImage }}"
                                                data-has-custom-img="{{ $valImg ? '1' : '0' }}"
                                                @if($val->stock !== null && $val->stock <= 0) disabled @endif
                                                onclick="selectT5Variant(this)">
                                                @if($valImg)
                                                    <img src="{{ $valImg }}" alt="{{ $val->value }}" class="t5-var-pill-img">
                                                @endif
                                                <span>{{ $val->value }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- T5 INTERACTIVE VARIANT POPUP MODAL --}}
                        <div id="t5-variant-modal" class="buyle-variant-modal" style="display:none;" aria-hidden="true">
                            <div class="bvm-backdrop" onclick="closeT5VariantModal()"></div>
                            <div class="bvm-sheet">
                                <div class="bvm-handle-bar"><span class="bvm-handle"></span></div>
                                <div class="bvm-header">
                                    <div class="bvm-thumb-wrap">
                                        <img id="t5-bvm-thumb-img" src="{{ $firstImage }}" alt="{{ $prodTitle }}" class="bvm-thumb">
                                        <span id="t5-bvm-thumb-badge" class="bvm-thumb-badge" style="display:none;">Foto Variasi</span>
                                    </div>
                                    <div class="bvm-header-info">
                                        <div class="bvm-price" id="t5-bvm-price-display">Rp {{ number_format($price, 0, ',', '.') }}</div>
                                        <div class="bvm-stock" id="t5-bvm-stock-display">
                                            @if($stock === null)
                                                <span style="color:#10b981;">• Stok Tersedia</span>
                                            @elseif((int)$stock > 0)
                                                <span>• Sisa {{ $stock }} unit</span>
                                            @else
                                                <span style="color:#ef4444;">• Stok Habis</span>
                                            @endif
                                        </div>
                                        <div class="bvm-selected-label" id="t5-bvm-selected-label">
                                            Silakan pilih opsi variasi
                                        </div>
                                    </div>
                                    <button type="button" class="bvm-close-btn" onclick="closeT5VariantModal()" aria-label="Tutup">
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
                                                    <button type="button" class="bvm-pill t5-bvm-pill"
                                                        data-variant-id="{{ $val->id }}"
                                                        data-price="{{ $valFinalPrice }}"
                                                        data-price-formatted="Rp {{ number_format($valFinalPrice, 0, ',', '.') }}"
                                                        data-stock="{{ $val->stock }}"
                                                        data-label="{{ $opt->name }}: {{ $val->value }}"
                                                        data-img="{{ $valImg ?: $firstImage }}"
                                                        data-has-custom-img="{{ $valImg ? '1' : '0' }}"
                                                        @if($isValOutOfStock) disabled @endif
                                                        onclick="selectT5BvmVariant(this)">
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
                                        $t5MaxLimit = null;
                                        if (($product->product_type ?? $product->type) === 'ticket') {
                                            $t5MaxLimit = (int) ($product->max_tickets_per_order ?: 4);
                                        } elseif (!empty($product->max_order)) {
                                            $t5MaxLimit = (int) $product->max_order;
                                        }
                                    @endphp
                                    <div class="bvm-qty-row">
                                        <div>
                                            <div class="bvm-qty-label">Kuantitas</div>
                                            <div class="bvm-qty-sub">
                                                Jumlah pesanan
                                                @if($t5MaxLimit)
                                                    <span style="color:#16a34a; font-weight:600;">(Maks. {{ $t5MaxLimit }} per order)</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="bvm-qty-ctrl">
                                            <button type="button" class="bvm-qty-btn" onclick="stepT5BvmQty(-1)">−</button>
                                            <input type="number" id="t5-bvm-qty-input" class="bvm-qty-input" value="1" min="1" data-max-limit="{{ $t5MaxLimit ?? '' }}" readonly>
                                            <button type="button" class="bvm-qty-btn" onclick="stepT5BvmQty(1)">+</button>
                                        </div>
                                    </div>
                                    <div id="t5-bvm-error" class="bvm-error" style="display:none;">
                                        ⚠ Silakan pilih salah satu variasi terlebih dahulu.
                                    </div>
                                </div>
                                <div class="bvm-footer">
                                    <button type="button" id="t5-bvm-submit-btn" class="bvm-btn-primary" onclick="confirmT5BvmSubmit()">
                                        <span id="t5-bvm-submit-label">Beli Sekarang</span>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <style>
                            .buyle-variant-modal { position: fixed; inset: 0; z-index: 999999; display: flex; align-items: flex-end; justify-content: center; visibility: hidden; opacity: 0; transition: opacity 0.24s ease, visibility 0.24s ease; }
                            .buyle-variant-modal.active { visibility: visible; opacity: 1; }
                            .bvm-backdrop { position: absolute; inset: 0; background: rgba(15, 23, 42, 0.62); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
                            .bvm-sheet { position: relative; width: 100%; max-width: 480px; background: #ffffff; border-radius: 24px 24px 0 0; box-shadow: 0 -12px 48px rgba(0, 0, 0, 0.22); display: flex; flex-direction: column; max-height: 86vh; transform: translateY(100%); transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1); z-index: 1; overflow: hidden; }
                            .buyle-variant-modal.active .bvm-sheet { transform: translateY(0); }
                            @media (min-width: 640px) { .buyle-variant-modal { align-items: center; padding: 1.5rem; } .bvm-sheet { border-radius: 20px; transform: scale(0.95); box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25); } .buyle-variant-modal.active .bvm-sheet { transform: scale(1); } .bvm-handle-bar { display: none !important; } }
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

                    <div class="ps-cta-row">
                        <form action="{{ route('cart.add') }}" method="POST" style="display:contents;"
                            class="t5-add-cart-form">
                            @csrf
                            <input type="hidden" name="action" id="t5_action_input" value="cart">
                            <input type="hidden" name="product_id" value="{{ $prodId }}">
                            <input type="hidden" name="qty" id="t5_form_qty" value="1">
                            <input type="hidden" name="variant_value_id" id="t5_selected_variant_id" value="">
                            @if(!empty($affiliateUserId))
                                <input type="hidden" name="affiliate_id" value="{{ $affiliateUserId }}">
                            @endif
                            <button type="submit" class="ps-btn-cart" id="t5BtnCart" {{ $isWarActive ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2"
                                    viewBox="0 0 24 24">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                                    <line x1="3" y1="6" x2="21" y2="6" />
                                    <path d="M16 10a4 4 0 0 1-8 0" />
                                </svg>
                                Keranjang
                            </button>
                        </form>

                        @if($isWarActive)
                            <button type="button" id="t5BtnWarLocked" class="ps-btn-checkout ps-btn-war-locked" disabled>
                                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                <span id="t5WarBtnLabel">Penjualan Belum Dibuka</span>
                            </button>
                            <button type="button" onclick="t5DirectCheckout()" id="t5BtnWarReady" class="ps-btn-checkout"
                                style="display: none;">
                                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2"
                                    viewBox="0 0 24 24">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                </svg>
                                Checkout Sekarang!
                            </button>
                        @else
                            <button type="button" class="ps-btn-checkout" onclick="t5DirectCheckout()">
                                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.2"
                                    viewBox="0 0 24 24">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                                Checkout
                            </button>
                        @endif
                    </div>

                    @if($isWarActive)
                        <script>
                            (function () {
                                const targetTime = {{ $warTimestamp }};
                                function updateT5WarCountdown() {
                                    const now = Date.now();
                                    const diff = targetTime - now;

                                    if (diff <= 0) {
                                        const warCard = document.getElementById('t5TicketWarCard');
                                        if (warCard) {
                                            warCard.style.transition = 'all 0.4s ease';
                                            warCard.style.opacity = '0';
                                            setTimeout(() => warCard.style.display = 'none', 400);
                                        }

                                        const lockedBtn = document.getElementById('t5BtnWarLocked');
                                        const readyBtn = document.getElementById('t5BtnWarReady');
                                        const cartBtn = document.getElementById('t5BtnCart');

                                        if (lockedBtn) lockedBtn.style.display = 'none';
                                        if (readyBtn) readyBtn.style.display = 'flex';
                                        if (cartBtn) {
                                            cartBtn.removeAttribute('disabled');
                                            cartBtn.style.opacity = '1';
                                            cartBtn.style.cursor = 'pointer';
                                        }

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
                                        } catch (e) { }

                                        return;
                                    }

                                    const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                                    const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                    const s = Math.floor((diff % (1000 * 60)) / 1000);

                                    const dEl = document.getElementById('t5WarDays');
                                    const hEl = document.getElementById('t5WarHours');
                                    const mEl = document.getElementById('t5WarMins');
                                    const sEl = document.getElementById('t5WarSecs');

                                    if (dEl) dEl.textContent = String(d).padStart(2, '0');
                                    if (hEl) hEl.textContent = String(h).padStart(2, '0');
                                    if (mEl) mEl.textContent = String(m).padStart(2, '0');
                                    if (sEl) sEl.textContent = String(s).padStart(2, '0');

                                    setTimeout(updateT5WarCountdown, 1000);
                                }
                                updateT5WarCountdown();
                            })();
                        </script>
                    @endif
                @else
                    <div class="ps-cta-row" style="grid-template-columns: 1fr;">
                        <button type="button" class="ps-btn-checkout"
                            onclick='openT5LeadModal({{ json_encode($waLeadMessage) }})'
                            style="background:linear-gradient(135deg, #25D366 0%, #128C7E 100%); font-size:0.92rem; padding:0.9rem; gap:0.6rem;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                            </svg>
                            Hubungi Kami
                        </button>
                    </div>
                @endif

            </div>{{-- end .ps-info --}}
        </div>{{-- end .ps-top --}}

        {{-- DESCRIPTION CARD (BELOW) --}}
        @if(!empty($rawDesc))
            <div class="ps-desc-card">
                <div class="ps-desc-header">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                    Deskripsi Produk
                </div>
                <div class="ps-desc-body">
                    {{-- Video Produk (Minimalist YouTube / TikTok Embed) --}}
                    @php
                        $prodVideoUrl = !empty($product->tiktok_video_url) ? $product->tiktok_video_url : (!empty($product->youtube_video_url) ? $product->youtube_video_url : (!empty($block->data_json['tiktok_video_url']) ? $block->data_json['tiktok_video_url'] : null));
                    @endphp
                    @if(!empty($prodVideoUrl))
                        @include('partials.product_video', ['videoUrl' => $prodVideoUrl])
                    @endif

                    {!! $rawDesc !!}

                    @php
                        $posterImage = !empty($product->og_image) ? asset('storage/' . $product->og_image) : (!empty($block->data_json['og_image']) ? asset('storage/' . $block->data_json['og_image']) : null);
                    @endphp
                    @if(!empty($posterImage))
                        <div
                            style="margin-top: 1.5rem; width: 100%; aspect-ratio: 16/9; border-radius: 14px; overflow: hidden; background: #F1F5F9; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                            <img src="{{ $posterImage }}" alt="{{ $prodTitle }}"
                                style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>{{-- end .ps-wrap --}}

    {{-- FLOATING WA (Trigger Lead Popup Modal) --}}
    @if(!empty($config['wa']))
        @php $waFN = preg_replace('/[^0-9]/', '', $config['wa']); @endphp
        @if($waFN)
            <a href="javascript:void(0)" onclick="openT5LeadModal('Halo, saya ingin berkonsultasi via WhatsApp.')"
                class="t5-floating-wa" title="Chat via WhatsApp">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                    <path
                        d="M12 0C5.373 0 0 5.373 0 12c0 2.124.556 4.117 1.528 5.849L0 24l6.335-1.508A11.948 11.948 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.885 0-3.65-.52-5.154-1.422l-.37-.218-3.764.896.924-3.667-.243-.381A9.953 9.953 0 0 1 2 12c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10z" />
                </svg>
                <span class="t5-wa-pulse"></span>
            </a>
        @endif
    @endif

    {{-- SECTION: Lainnya dari [namacreator] - Swipe Card Style (Matching Homepage) --}}
    @php
        // Ambil produk lain dari creator yang sama (selain produk ini)
        $creatorDisplayName = $config['name'] ?? $profile->store_name ?? $username;
        $otherProducts = \App\Models\CreatorBioBlock::where('creator_id', $profile->id)
            ->where('is_active', true)
            ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
            ->get();
    @endphp
    @if($otherProducts && $otherProducts->count() > 0)
        <style>
            /* Swipe Cards Container - Same as Homepage */
            .t5-swipe-container {
                display: flex;
                gap: 1rem;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                padding-bottom: 0.5rem;
            }

            .t5-swipe-container::-webkit-scrollbar {
                display: none;
            }

            .t5-swipe-card-item {
                flex: 0 0 calc(20% - 0.8rem);
                min-width: 170px;
                scroll-snap-align: start;
                text-decoration: none;
                color: inherit;
                display: flex;
                flex-direction: column;
            }

            @media (max-width: 900px) {
                .t5-swipe-card-item {
                    flex: 0 0 calc(33.333% - 0.75rem);
                    min-width: 150px;
                }
            }

            @media (max-width: 600px) {
                .t5-swipe-card-item {
                    flex: 0 0 calc(44% - 0.5rem);
                    min-width: 135px;
                }
            }

            .t5-card-box {
                background: #ffffff;
                border: 1.5px solid #E2E8F0;
                border-radius: 16px;
                padding: 0.75rem;
                display: flex;
                flex-direction: column;
                height: 100%;
                transition: all 0.25s ease;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
            }

            .t5-card-box:hover {
                border-color: #1eb349;
                box-shadow: 0 8px 25px rgba(30, 179, 73, 0.13);
                transform: translateY(-3px);
            }

            .t5-card-box:hover .t5-card-img {
                transform: scale(1.04);
            }

            .t5-card-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 0.3s ease;
            }
        </style>
        <div style="max-width: 1100px; margin: 1.5rem auto 3rem; padding: 0 1.25rem;">
            {{-- Headline --}}
            <div
                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
                <h2
                    style="font-family: 'Montserrat', sans-serif; font-size: 1.15rem; font-weight: 900; color: #0F172A; margin: 0;">
                    ✨ Lainnya dari {{ $creatorDisplayName }}
                </h2>
                <a href="{{ url('/' . $username) }}"
                    style="font-size: 0.85rem; font-weight: 700; color: #1eb349; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                    Lihat Semua <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path d="M5 12h14m-7-7l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- 5-card Swipe Container --}}
            <div class="t5-swipe-container">
                @foreach($otherProducts as $op)
                    @php
                        $opBlock = $op;
                        $opProd = null;
                        if (in_array($op->type, ['buyle_product', 'buyle_affiliate']) && !empty($op->data_json['product_id'])) {
                            $opProd = \App\Models\Product::find($op->data_json['product_id']);
                        }
                        $opTitle = !empty($op->title) ? $op->title : ($opProd->name ?? 'Produk');
                        $opSlug = $opProd->slug ?? null;
                        $opPrice = $op->data_json['price'] ?? $op->data_json['custom_price'] ?? ($opProd ? ($opProd->is_on_sale ? $opProd->sale_price : $opProd->effective_price) : 0);
                        $opOrig = $op->data_json['original_price'] ?? ($opProd && $opProd->is_on_sale ? $opProd->price : null);
                        $opHasDisc = !empty($opOrig) && $opOrig > $opPrice;
                        $opDiscPct = $opHasDisc ? round((($opOrig - $opPrice) / $opOrig) * 100) : 0;
                        // Image
                        $opImgs = $op->data_json['images'] ?? [];
                        if (empty($opImgs) && !empty($op->data_json['image']))
                            $opImgs = [$op->data_json['image']];
                        if (empty($opImgs) && $opProd && $opProd->image)
                            $opImgs = [$opProd->image];
                        $opImg = !empty($opImgs[0]) ? (\Illuminate\Support\Str::startsWith($opImgs[0], 'http') ? $opImgs[0] : asset('storage/' . $opImgs[0])) : asset('images/buyle-og.png');
                        // Rating (Dynamic realtime dari DB)
                        $opRatingVal = $opProd ? $opProd->getEffectiveRating() : null;
                        // Sales
                        $opSold = $opProd ? ($opProd->sold_count ?? $opProd->sales_count ?? 0) : 0;
                        // URL
                        $opUrl = $opSlug ? url('/' . $username . '/p/' . $opSlug) : url('/' . $username);
                    @endphp
                    <a href="{{ $opUrl }}" class="t5-swipe-card-item">
                        <div class="t5-card-box">
                            {{-- Image 1:1 --}}
                            <div
                                style="position: relative; width: 100%; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; background: #F1F5F9;">
                                <img src="{{ $opImg }}" alt="{{ $opTitle }}" class="t5-card-img" loading="lazy">
                                @if($opHasDisc)
                                    <div
                                        style="position: absolute; top: 8px; left: 8px; background: #EF4444; color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 8px; z-index: 2; box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);">
                                        -{{ $opDiscPct }}%
                                    </div>
                                @endif
                            </div>

                            {{-- Card Body --}}
                            <div
                                style="padding: 0.85rem 0.25rem 0.25rem; display: flex; flex-direction: column; flex: 1; justify-content: space-between;">
                                <div>
                                    {{-- Rating & Verified --}}
                                    <div
                                        style="display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; font-weight: 700; color: #1E293B; margin-bottom: 0.35rem;">
                                        @if($opRatingVal !== null && $opRatingVal > 0)
                                            <div style="display: flex; align-items: center; gap: 0.3rem;">
                                                <span style="color: #F59E0B; font-size: 0.95rem;">★</span>
                                                <span style="font-family: 'Montserrat', sans-serif;">{{ number_format($opRatingVal, 1) }}</span>
                                            </div>
                                        @else
                                            <div style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #94A3B8; font-weight: 600;">
                                                <span style="color: #CBD5E1; font-size: 0.85rem;">★</span>
                                                <span>0.0</span>
                                            </div>
                                        @endif
                                        @if($isSellerVerified)
                                            <div
                                                style="display: inline-flex; align-items: center; gap: 0.25rem; color: #0D9488; font-size: 0.78rem; font-weight: 700;">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                    viewBox="0 0 24 24">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                    <polyline points="22 4 12 14.01 9 11.01" />
                                                </svg>
                                                Verified
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Title --}}
                                    <h3
                                        style="font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 800; color: #0F172A; margin: 0 0 0.4rem; line-height: 1.35; height: 2.7em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        {{ $opTitle }}
                                    </h3>
                                </div>

                                {{-- Price & CTA --}}
                                <div>
                                    <div style="border-top: 1px solid #F1F5F9; margin: 0.65rem 0 0.75rem;"></div>
                                    <div
                                        style="display: flex; align-items: flex-end; justify-content: space-between; gap: 0.5rem;">
                                        <div>
                                            @if($opHasDisc)
                                                <span
                                                    style="font-size: 0.72rem; color: #94A3B8; text-decoration: line-through; display: block; font-weight: 500;">
                                                    Rp {{ number_format($opOrig, 0, ',', '.') }}
                                                </span>
                                            @endif
                                            <span
                                                style="font-family: 'Montserrat', sans-serif; font-size: 1.05rem; font-weight: 900; color: #16a34a;">
                                                @if($opPrice > 0)
                                                    Rp {{ number_format($opPrice, 0, ',', '.') }}
                                                @else
                                                    <span style="color: #1eb349;">GRATIS</span>
                                                @endif
                                            </span>
                                        </div>
                                        <span
                                            style="background: linear-gradient(135deg, #1eb349, #7db928); color: #ffffff; padding: 0.45rem 0.9rem; border-radius: 99px; font-size: 0.78rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.2rem; box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3); white-space: nowrap;">
                                            Lihat <svg width="10" height="10" fill="none" stroke="currentColor"
                                                stroke-width="2.5" viewBox="0 0 24 24">
                                                <path d="M5 12h14m-7-7l7 7-7 7" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- FOOTER --}}
    @include('bio.theme5.footer', ['products' => $products, 'config' => $config, 'profile' => $profile, 'username' => $username])

    {{-- LIGHTBOX IMAGE ZOOM MODAL --}}
    <style>
        .ps-lightbox-modal {
            position: fixed;
            inset: 0;
            z-index: 999999;
            background: rgba(15, 23, 42, 0.93);
            display: none;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(8px);
            padding: 1.5rem;
        }

        .ps-lightbox-modal.active {
            display: flex;
            animation: lbFadeIn 0.25s ease;
        }

        @keyframes lbFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .ps-lightbox-content {
            max-width: 90vw;
            max-height: 88vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ps-lightbox-content img {
            max-width: 100%;
            max-height: 88vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5);
            cursor: zoom-out;
        }

        .ps-lightbox-close {
            position: absolute;
            top: 20px;
            right: 24px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            z-index: 10;
            line-height: 1;
        }

        .ps-lightbox-close:hover {
            background: rgba(239, 68, 68, 0.9);
        }

        .ps-lightbox-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border: none;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            font-size: 28px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            z-index: 10;
            line-height: 1;
        }

        .ps-lightbox-nav:hover {
            background: rgba(30, 179, 73, 0.9);
        }

        .ps-lb-prev {
            left: 24px;
        }

        .ps-lb-next {
            right: 24px;
        }
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
        function toggleT5Drawer() {
            var d = document.getElementById('t5MobileDrawer');
            if (d) d.classList.toggle('active');
        }

        const psImagesList = {!! json_encode($formattedImages) !!};

        let psCur = 0;
        const psTotal = {{ count($images) }};

        function psTo(i) {
            if (psTotal === 0) return;
            psCur = Math.max(0, Math.min(i, psTotal - 1));
            var t = document.getElementById('psTrack');
            if (t) t.style.transform = 'translateX(-' + (psCur * 100) + '%)';
            document.querySelectorAll('.ps-thumb').forEach(function (el, idx) {
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

        // Keyboard navigation for Lightbox
        document.addEventListener('keydown', function (e) {
            const lbModal = document.getElementById('psLightboxModal');
            if (lbModal && lbModal.classList.contains('active')) {
                if (e.key === 'Escape') closeImageLightbox();
                if (e.key === 'ArrowLeft') moveLightbox(-1);
                if (e.key === 'ArrowRight') moveLightbox(1);
            }
        });

        // Touch swipe for gallery
        var txStart = 0;
        var pw = document.getElementById('psWrap');
        if (pw) {
            pw.addEventListener('touchstart', function (e) { txStart = e.changedTouches[0].screenX; }, { passive: true });
            pw.addEventListener('touchend', function (e) {
                var diff = txStart - e.changedTouches[0].screenX;
                if (Math.abs(diff) > 40) psMove(diff > 0 ? 1 : -1);
            });
        }

        // AJAX Add to Cart
        document.addEventListener('submit', function (e) {
            if (e.target && e.target.classList.contains('t5-add-cart-form')) {
                var form = e.target;
                var act = form.querySelector('input[name="action"]');
                // If action is 'buy', allow native submission to redirect directly to cart.index
                if (act && act.value === 'buy') {
                    return;
                }

                if (t5HasVariants && !document.getElementById('t5_selected_variant_id').value) {
                    e.preventDefault();
                    openT5VariantModal('cart');
                    return false;
                }

                e.preventDefault();
                var btn = form.querySelector('button[type="submit"]');
                if (btn) btn.disabled = true;

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (btn) btn.disabled = false;
                        if (data.success) {
                            if (window.updateHeaderCartBadge) window.updateHeaderCartBadge(data.total_items);
                            if (window.showCartToast) window.showCartToast(data.message);
                        } else {
                            alert(data.message || 'Gagal menambahkan ke keranjang');
                        }
                    })
                    .catch(function (err) {
                        if (btn) btn.disabled = false;
                        form.submit();
                    });
            }
        });

        // ── THEME 5 VARIANT MODAL & CONTROLS ──
        var t5HasVariants = document.getElementById('t5-variant-section') !== null;
        window._t5CurrentAction = 'buy';

        window.openT5VariantModal = function(action) {
            var modal = document.getElementById('t5-variant-modal');
            if (!modal) return;
            window._t5CurrentAction = action || 'buy';

            var submitLabel = document.getElementById('t5-bvm-submit-label');
            if (submitLabel) {
                submitLabel.textContent = (window._t5CurrentAction === 'cart') ? 'Tambah ke Keranjang' : 'Beli Sekarang';
            }

            // Sync quantity honoring maxLimit
            var modalQty = document.getElementById('t5-bvm-qty-input');
            var formQty = document.getElementById('t5_form_qty');
            if (modalQty && formQty) {
                var qVal = parseInt(formQty.value) || 1;
                var maxLimit = parseInt(modalQty.dataset.maxLimit || '') || null;
                if (maxLimit && qVal > maxLimit) qVal = maxLimit;
                modalQty.value = qVal;
                formQty.value = qVal;
            }

            // Auto-select active or first available variant
            var currentVid = document.getElementById('t5_selected_variant_id').value;
            var activePill = currentVid ? modal.querySelector('.t5-bvm-pill[data-variant-id="' + currentVid + '"]') : null;
            if (!activePill) {
                activePill = modal.querySelector('.t5-bvm-pill:not(:disabled)');
            }
            if (activePill) {
                selectT5BvmVariant(activePill, false);
            }

            modal.style.display = 'flex';
            requestAnimationFrame(function() {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        };

        window.closeT5VariantModal = function() {
            var modal = document.getElementById('t5-variant-modal');
            if (!modal) return;
            modal.classList.remove('active');
            document.body.style.overflow = '';
            setTimeout(function() {
                if (!modal.classList.contains('active')) {
                    modal.style.display = 'none';
                }
            }, 280);
        };

        window.selectT5BvmVariant = function(btn, syncToPage) {
            if (typeof syncToPage === 'undefined') syncToPage = true;
            if (btn.disabled) return;
            var group = btn.closest('.bvm-pills');
            if (group) {
                group.querySelectorAll('.t5-bvm-pill').forEach(function(p) { p.classList.remove('selected'); });
            }
            btn.classList.add('selected');

            var vid = btn.dataset.variantId;
            var price = parseFloat(btn.dataset.price) || 0;
            var priceFormatted = btn.dataset.priceFormatted;
            var stock = btn.dataset.stock;
            var label = btn.dataset.label;
            var imgUrl = btn.dataset.img;
            var hasCustomImg = btn.dataset.hasCustomImg === '1';

            var thumbImg = document.getElementById('t5-bvm-thumb-img');
            var thumbBadge = document.getElementById('t5-bvm-thumb-badge');
            if (thumbImg && imgUrl) {
                thumbImg.src = imgUrl;
            }
            if (thumbBadge) {
                thumbBadge.style.display = hasCustomImg ? 'block' : 'none';
            }

            var priceDisplay = document.getElementById('t5-bvm-price-display');
            if (priceDisplay && priceFormatted) {
                priceDisplay.textContent = priceFormatted;
            }

            var stockDisplay = document.getElementById('t5-bvm-stock-display');
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

            var labelDisplay = document.getElementById('t5-bvm-selected-label');
            if (labelDisplay) {
                labelDisplay.innerHTML = '<span style="color:#15803d;font-weight:700;">✓ Dipilih:</span> ' + label;
            }

            var err = document.getElementById('t5-bvm-error');
            if (err) err.style.display = 'none';

            document.getElementById('t5_selected_variant_id').value = vid;

            if (syncToPage) {
                var pagePills = document.querySelectorAll('.t5-var-pill[data-variant-id="' + vid + '"]');
                pagePills.forEach(function(pill) {
                    var parent = pill.closest('.t5-var-pills');
                    if (parent) {
                        parent.querySelectorAll('.t5-var-pill').forEach(function(p) { p.classList.remove('selected'); });
                    }
                    pill.classList.add('selected');
                });

                var pagePriceEl = document.querySelector('.ps-price');
                if (pagePriceEl && price > 0) {
                    pagePriceEl.textContent = 'Rp ' + price.toLocaleString('id-ID');
                }
            }
        };

        window.stepT5BvmQty = function(delta) {
            var input = document.getElementById('t5-bvm-qty-input');
            if (!input) return;
            var current = parseInt(input.value) || 1;
            var next = current + delta;
            if (next < 1) next = 1;

            var maxLimit = parseInt(input.dataset.maxLimit || '') || null;
            if (maxLimit && next > maxLimit) {
                next = maxLimit;
            }

            var selectedPill = document.querySelector('.t5-bvm-pill.selected');
            if (selectedPill && selectedPill.dataset.stock) {
                var maxStock = parseInt(selectedPill.dataset.stock);
                if (!isNaN(maxStock) && maxStock > 0 && next > maxStock) {
                    next = maxStock;
                }
            }

            input.value = next;
            var formQty = document.getElementById('t5_form_qty');
            if (formQty) formQty.value = next;
        };

        window.confirmT5BvmSubmit = function() {
            var vidEl = document.getElementById('t5_selected_variant_id');
            var vid = vidEl ? vidEl.value : '';
            if (!vid) {
                var firstPill = document.querySelector('.t5-bvm-pill:not(:disabled)');
                if (firstPill) {
                    selectT5BvmVariant(firstPill, true);
                    vid = firstPill.dataset.variantId;
                }
            }
            if (!vid) {
                var err = document.getElementById('t5-bvm-error');
                if (err) {
                    err.style.display = 'block';
                    err.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
                return false;
            }

            var modalQty = document.getElementById('t5-bvm-qty-input');
            var formQty = document.getElementById('t5_form_qty');
            if (modalQty && formQty) {
                formQty.value = modalQty.value;
            }

            closeT5VariantModal();

            var form = document.querySelector('.t5-add-cart-form');
            if (!form) return;
            var act = document.getElementById('t5_action_input') || form.querySelector('input[name="action"]');

            if (window._t5CurrentAction === 'cart') {
                if (act) act.value = 'cart';
                var btn = form.querySelector('button[type="submit"]');
                if (btn) btn.click();
                else form.submit();
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

        window.selectT5Variant = function(btn) {
            var vid = btn.dataset.variantId;
            var modalPill = document.querySelector('.t5-bvm-pill[data-variant-id="' + vid + '"]');
            if (modalPill) {
                selectT5BvmVariant(modalPill, true);
            } else {
                var pills = btn.closest('.t5-var-pills').querySelectorAll('.t5-var-pill');
                pills.forEach(function(p){ p.classList.remove('selected'); });
                btn.classList.add('selected');
                document.getElementById('t5_selected_variant_id').value = vid;
            }
        };

        // Direct Checkout: Add to Cart with Affiliate Tracking then Redirect
        window.t5DirectCheckout = function () {
            if (t5HasVariants) {
                openT5VariantModal('buy');
                return false;
            }
            doT5DirectCheckout();
        };

        window.doT5DirectCheckout = function () {
            var form = document.querySelector('.t5-add-cart-form');
            if (!form) {
                window.location.href = "{{ route('cart.index') }}";
                return;
            }
            var act = document.getElementById('t5_action_input') || form.querySelector('input[name="action"]');
            if (act) act.value = 'buy';
            var btn = document.querySelector('.ps-btn-checkout');
            if (btn) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
            }
            form.submit();
        };

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeT5VariantModal();
        });
        // Lock image dragging
        document.addEventListener('dragstart', function (e) {
            if (e.target && e.target.tagName === 'IMG') {
                e.preventDefault();
                return false;
            }
        }, true);

        // Retargeting Tracking System
        try {
            var prodId = {{ $product->id ?? 0 }};
            var catId = {{ $product->category_id ?? 0 }};
            if (prodId > 0) {
                var history = JSON.parse(localStorage.getItem('buyle_viewed_history') || '[]');
                history = history.filter(function (item) { return String(item.id) !== String(prodId); });
                history.unshift({ id: prodId, category_id: catId, time: Date.now() });
                if (history.length > 20) history.pop();
                localStorage.setItem('buyle_viewed_history', JSON.stringify(history));
            }
        } catch (e) { }
    </script>

    @include('partials.adsense_modal')
    @include('partials.report_modal', ['reportType' => 'product', 'targetName' => $prodTitle])
</body>

</html>