@php
    $catalogProducts = $products->reject(function ($p) {
        $pType = strtolower($p->product_type ?? '');
        $pName = strtolower($p->name ?? $p->title ?? '');
        $bData = is_array($p->data_json ?? null) ? $p->data_json : (json_decode($p->data_json ?? '[]', true) ?: []);
        if (empty($pType)) {
            $pType = strtolower($bData['product_type'] ?? $bData['category'] ?? '');
        }
        $catName = strtolower($p->category->name ?? '');

        // Sembunyikan: Jasa / Service
        if (in_array($pType, ['service', 'jasa', 'layanan', 'jasa / layanan / service', 'services'])
            || in_array($catName, ['service', 'jasa', 'layanan', 'services'])
            || str_contains($pName, 'jasa')
            || str_contains($pName, 'layanan')
            || str_contains($pName, 'service')) return true;

        // Sembunyikan: Makanan / Kuliner / FnB
        if (in_array($pType, ['makanan', 'fnb', 'kuliner', 'food', 'dapur', 'resto'])
            || in_array($catName, ['makanan', 'fnb', 'kuliner', 'food'])) return true;

        // Sembunyikan: Barang Fisik / UMKM
        if (in_array($pType, ['physical', 'barang', 'fisik', 'umkm'])
            || in_array($catName, ['physical', 'barang', 'fisik', 'umkm'])) return true;

        return false;
    });
@endphp

@if($catalogProducts->count() > 0)
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
    </style>
    <section class="t5-products-section" id="products-section">
        <div class="t5-products-header">
            <div class="t5-section-title-wrap">
                <h2 class="t5-section-title">Produk Unggulan</h2>
                <p class="t5-section-sub">Katalog produk pilihan terbaik.</p>
            </div>
            @php
                $storeUsername = $profile->store_slug ?? ($username ?? 'creator');
                if (!empty($profile->custom_domain)) {
                    $allProductsUrl = 'https://' . rtrim($profile->custom_domain, '/') . '/produk';
                } else {
                    $allProductsUrl = url($storeUsername . '/produk');
                }
            @endphp
            <a href="{{ $allProductsUrl }}" class="t5-see-all-btn">
                <span>Lihat Semua</span>
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <polyline points="12 5 19 12 12 19" />
                </svg>
            </a>
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

                    if (!empty($profile->custom_domain)) {
                        $productUrl = 'https://' . rtrim($profile->custom_domain, '/') . '/produk/' . $prodIdentifier;
                    } else {
                        $productUrl = route('bio.product.show', ['username' => $username, 'identifier' => $prodIdentifier]);
                    }
                    $ratingVal = !empty($product->rating) && $product->rating > 0 ? number_format($product->rating, 1) : '5.0';
                @endphp

                <div class="t5-product-card">
                    <a href="{{ $productUrl }}" class="t5-product-img-wrap">
                        <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy" class="t5-product-img" draggable="false">

                        @if($hasDiscount)
                            <span class="t5-discount-tag">-{{ $discountPercent }}%</span>
                        @endif
                    </a>

                    <div class="t5-product-body">
                        <div class="t5-product-meta">
                            <span class="t5-rating-badge">
                                <svg width="13" height="13" fill="#f59e0b" stroke="#f59e0b" stroke-width="1"
                                    viewBox="0 0 24 24">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                                {{ $ratingVal }}
                            </span>
                            <span class="t5-verified-tag">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" />
                                </svg>
                                Verified
                            </span>
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

                            <a href="{{ $productUrl }}" class="t5-buy-btn" title="Detail Produk">
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