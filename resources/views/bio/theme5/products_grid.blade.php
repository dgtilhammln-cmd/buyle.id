@php
    $webType = $config['web_type'] ?? 'company_profile';
    $isEO = ($webType === 'event_organizer');

    $catalogProducts = $products->reject(function ($p) use ($isEO) {
        // Sembunyikan produk yang tidak aktif / diarsipkan
        if (isset($p->is_active) && !$p->is_active) return true;

        $pType = strtolower($p->product_type ?? '');
        $pName = strtolower($p->name ?? $p->title ?? '');
        $bData = is_array($p->data_json ?? null) ? $p->data_json : (json_decode($p->data_json ?? '[]', true) ?: []);
        if (empty($pType)) {
            $pType = strtolower($bData['product_type'] ?? $bData['category'] ?? '');
        }
        $catName = strtolower($p->category->name ?? '');

        // Mode EO: hanya tampilkan produk tiket / event
        if ($isEO) {
            $isTicket = in_array($pType, ['ticket', 'tiket', 'tiket event', 'event', 'ticket_event']) || !empty($p->event_date);
            return !$isTicket;
        }

        // Mode Company Profile: Sembunyikan Jasa, Makanan, Barang Fisik jika ada section terpisah
        if (in_array($pType, ['service', 'jasa', 'layanan', 'jasa / layanan / service', 'services'])
            || in_array($catName, ['service', 'jasa', 'layanan', 'services'])
            || str_contains($pName, 'jasa')
            || str_contains($pName, 'layanan')
            || str_contains($pName, 'service')) return true;

        if (in_array($pType, ['makanan', 'fnb', 'kuliner', 'food', 'dapur', 'resto'])
            || in_array($catName, ['makanan', 'fnb', 'kuliner', 'food'])) return true;

        if (in_array($pType, ['physical', 'barang', 'fisik', 'umkm'])
            || in_array($catName, ['physical', 'barang', 'fisik', 'umkm'])) return true;

        return false;
    });

    $isProfileVerified = isset($profile) && $profile ? $profile->isVerified() : false;
    $productsEnabled = !empty($config['products_section_enabled'] ?? 1);

    $defaultHeadline = $isEO ? 'Tiket Event' : 'Produk Unggulan';
    $defaultSubheadline = $isEO ? 'Daftar tiket event resmi dan terpercaya.' : 'Katalog produk pilihan terbaik.';
    $headline = !empty($config['products_headline']) ? $config['products_headline'] : $defaultHeadline;
    $subheadline = !empty($config['products_subheadline']) ? $config['products_subheadline'] : $defaultSubheadline;
    $seeAllEnabled = !empty($config['products_see_all_enabled'] ?? 1);
    $seeAllText = !empty($config['products_see_all_text']) ? $config['products_see_all_text'] : ($isEO ? 'Lihat Semua Tiket' : 'Lihat Semua');
@endphp

@if($productsEnabled && $catalogProducts->count() > 0)
    <style>
        .t5-products-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            text-align: left;
            margin-bottom: 2rem;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .t5-products-header .t5-section-title-wrap {
            text-align: left;
        }
        .t5-products-header .t5-section-title {
            text-align: left;
            margin: 0;
        }
        .t5-products-header .t5-section-sub {
            text-align: left;
            margin-top: 0.35rem;
        }
        .t5-see-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.35rem;
            background: #0f172a;
            color: #ffffff;
            border-radius: 999px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.15);
            transition: all 0.25s ease;
            white-space: nowrap;
        }
        .t5-see-all-btn:hover {
            background: #1eb349;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 179, 73, 0.25);
        }
        /* Rasio foto produk presisi 1:1 */
        .t5-product-img-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1 !important;
            background: #f1f5f9;
            overflow: hidden;
            display: block;
        }
        .t5-product-img {
            width: 100%;
            height: 100%;
            aspect-ratio: 1 / 1 !important;
            object-fit: cover !important;
            transition: transform 0.4s ease;
        }
    </style>
    <section class="t5-products-section" id="products-section">
        <div class="t5-products-header">
            <div class="t5-section-title-wrap">
                <h2 class="t5-section-title">{{ $headline }}</h2>
                @if(!empty($subheadline))
                    <p class="t5-section-sub">{{ $subheadline }}</p>
                @endif
            </div>
            @if($seeAllEnabled)
                @php
                    $storeUsername = $profile->store_slug ?? ($username ?? 'creator');
                    if (!empty($config['products_see_all_link'])) {
                        $allProductsUrl = $config['products_see_all_link'];
                    } elseif (!empty($profile->custom_domain)) {
                        $allProductsUrl = 'https://' . rtrim($profile->custom_domain, '/') . '/produk';
                    } else {
                        $allProductsUrl = url($storeUsername . '/produk');
                    }
                @endphp
                <a href="{{ $allProductsUrl }}" class="t5-see-all-btn">
                    <span>{{ $seeAllText }}</span>
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </a>
            @endif
        </div>

        <div class="t5-products-grid">
            @foreach($catalogProducts as $product)
                @php
                    $img = $product->image_url ?: asset('images/buyle-placeholder.svg');
                    $hasDiscount = $product->sale_price && $product->sale_price < $product->price;
                    $effectivePrice = $hasDiscount ? $product->sale_price : $product->price;
                    $discountPercent = $hasDiscount ? round((($product->price - $product->sale_price) / $product->price) * 100) : 0;
                    $bData = is_array($product->data_json) ? $product->data_json : (json_decode($product->data_json ?? '[]', true) ?: []);
                    $linkedProd = !empty($bData['product_id']) ? \App\Models\Product::find($bData['product_id']) : null;
                    // Priority: linked product slug > data_json slug > block->slug > title slug > block ID (numeric, always resolves)
                    $prodIdentifier = !empty($linkedProd->slug)
                        ? $linkedProd->slug
                        : (!empty($bData['slug'])
                            ? $bData['slug']
                            : (!empty($product->slug)
                                ? $product->slug
                                : (\Illuminate\Support\Str::slug($product->title ?? ($product->name ?? '')) ?: $product->id)));

                    $isTix = ($linkedProd && method_exists($linkedProd, 'isTicketProduct') && $linkedProd->isTicketProduct())
                        || ($product && method_exists($product, 'isTicketProduct') && $product->isTicketProduct())
                        || (($linkedProd->product_type ?? ($product->product_type ?? '')) === 'ticket');

                    $targetSlug = !empty($linkedProd->slug) ? $linkedProd->slug : (!empty($product->slug) ? $product->slug : $prodIdentifier);
                    if ($isTix) {
                        $productUrl = route('tix.show', ['slug' => $targetSlug]);
                    } else {
                        $productUrl = route('products.show', ['slug' => $targetSlug]);
                    }
                    $ratingVal = $product ? $product->getEffectiveRating() : null;
                @endphp

                <div class="t5-product-card">
                    <a href="{{ $productUrl }}" class="t5-product-img-wrap" aria-label="{{ $product->name }}">
                        <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy" class="t5-product-img" draggable="false">

                        @if($hasDiscount)
                            <span class="t5-discount-tag">-{{ $discountPercent }}%</span>
                        @endif
                    </a>

                    <div class="t5-product-body">
                        <div class="t5-product-meta">
                            @if($ratingVal !== null && $ratingVal > 0)
                                <span class="t5-rating-badge">
                                    <svg width="13" height="13" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"
                                        viewBox="0 0 24 24">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                    {{ number_format($ratingVal, 1) }}
                                </span>
                            @endif
                            @if(!empty($product->event_date))
                                @php
                                    try {
                                        $pEvDate = $product->event_date instanceof \Carbon\Carbon
                                            ? $product->event_date
                                            : \Carbon\Carbon::parse($product->event_date);
                                        $pEvDateStr = $pEvDate->translatedFormat('d M Y');
                                    } catch (\Exception $e) { $pEvDateStr = ''; }
                                @endphp
                                @if($pEvDateStr)
                                    <span class="t5-rating-badge" style="background:#ecfdf5; color:#047857; border:1px solid #a7f3d0; font-weight:700;">
                                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        {{ $pEvDateStr }}
                                    </span>
                                @endif
                            @endif
                            @if($isProfileVerified)
                                <span class="t5-verified-tag">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                        viewBox="0 0 24 24">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                        <polyline points="22 4 12 14.01 9 11.01" />
                                    </svg>
                                    Verified
                                </span>
                            @endif
                        </div>

                        <h3 class="t5-product-name">
                            <a href="{{ $productUrl }}">{{ $product->name }}</a>
                        </h3>

                        <div class="t5-product-footer">
                            <div class="t5-price-wrap">
                                @if($hasDiscount)
                                    <span class="t5-price-old">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                @endif
                                <span class="t5-price-main">Rp{{ number_format($effectivePrice, 0, ',', '.') }}</span>
                            </div>

                            <a href="{{ $productUrl }}" class="t5-buy-btn" title="Detail Produk" aria-label="Lihat detail {{ $product->name }}">
                                <span>Lihat</span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                    <polyline points="12 5 19 12 12 19" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif