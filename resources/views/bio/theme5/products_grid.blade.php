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

    $hasHeadlineKey = array_key_exists('products_headline', $config);
    $headline = $hasHeadlineKey ? ($config['products_headline'] ?? '') : ($isEO ? 'Tiket Event' : 'Produk Unggulan');

    $hasSubKey = array_key_exists('products_subheadline', $config);
    $subheadline = $hasSubKey ? ($config['products_subheadline'] ?? '') : ($isEO ? 'Daftar tiket event resmi dan terpercaya.' : 'Katalog produk pilihan terbaik.');

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
        /* Rasio foto produk landscape 16:9 & Center Alignment */
        .t5-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 460px));
            justify-content: center;
            gap: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .t5-product-img-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9 !important;
            background: #0f172a;
            overflow: hidden;
            display: block;
        }
        .t5-product-img {
            width: 100%;
            height: 100%;
            aspect-ratio: 16 / 9 !important;
            object-fit: cover !important;
            transition: transform 0.4s ease;
        }
        @media (max-width: 640px) {
            .t5-products-grid {
                grid-template-columns: 1fr !important;
                gap: 1.25rem !important;
            }
            .t5-product-card {
                width: 100% !important;
            }
        }
    </style>
    <section class="t5-products-section" id="products-section">
        @if(!empty(trim($headline)) || !empty(trim($subheadline)) || $seeAllEnabled)
            <div class="t5-products-header" style="{{ (empty(trim($headline)) && empty(trim($subheadline))) ? 'justify-content:flex-end; margin-bottom:1rem;' : '' }}">
                @if(!empty(trim($headline)) || !empty(trim($subheadline)))
                    <div class="t5-section-title-wrap">
                        @if(!empty(trim($headline)))
                            <h2 class="t5-section-title">{{ $headline }}</h2>
                        @endif
                        @if(!empty(trim($subheadline)))
                            <p class="t5-section-sub">{{ $subheadline }}</p>
                        @endif
                    </div>
                @endif
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
        @endif

        <div class="t5-products-grid">
            @foreach($catalogProducts as $product)
                @php
                    $bData = is_array($product->data_json) ? $product->data_json : (json_decode($product->data_json ?? '[]', true) ?: []);
                    $linkedProd = !empty($bData['product_id']) ? \App\Models\Product::find($bData['product_id']) : null;

                    // Poster Event (Landscape 16:9), fallback ke gambar utama
                    $landscapeImg = null;
                    if (!empty($product->og_image)) {
                        $landscapeImg = \Illuminate\Support\Str::startsWith($product->og_image, 'http')
                            ? $product->og_image
                            : asset('storage/' . $product->og_image);
                    } elseif (!empty($linkedProd->og_image)) {
                        $landscapeImg = \Illuminate\Support\Str::startsWith($linkedProd->og_image, 'http')
                            ? $linkedProd->og_image
                            : asset('storage/' . $linkedProd->og_image);
                    } elseif (!empty($product->image)) {
                        $landscapeImg = \Illuminate\Support\Str::startsWith($product->image, 'http')
                            ? $product->image
                            : asset('storage/' . $product->image);
                    } elseif (!empty($product->image_url)) {
                        $landscapeImg = $product->image_url;
                    } else {
                        $landscapeImg = asset('images/buyle-placeholder.svg');
                    }

                    $hasDiscount = $product->sale_price && $product->sale_price < $product->price;
                    $effectivePrice = $hasDiscount ? $product->sale_price : $product->price;
                    $discountPercent = $hasDiscount ? round((($product->price - $product->sale_price) / $product->price) * 100) : 0;
                    
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
                        <img src="{{ $landscapeImg }}" alt="{{ $product->name }}" loading="lazy" class="t5-product-img" draggable="false"
                            onerror="this.onerror=null;this.src='{{ asset('images/buyle-placeholder.svg') }}';">

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