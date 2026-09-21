@if($products->count() > 0)
<section class="t5-products-section" id="products-section">
    <div class="t5-section-header">
        <div class="t5-section-title-wrap">
            <span class="t5-section-badge">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                KATALOG DIGITAL
            </span>
            <h2 class="t5-section-title">Produk & Layanan Unggulan</h2>
            <p class="t5-section-sub">Aset digital, e-book, lisensi, & jasa kreatif pilihan berkualitas tinggi.</p>
        </div>
    </div>

    <div class="t5-products-grid">
        @foreach($products as $product)
            @php
                $img = $product->image_url ?: asset('images/buyle-placeholder.svg');
                $hasDiscount = $product->sale_price && $product->sale_price < $product->price;
                $effectivePrice = $hasDiscount ? $product->sale_price : $product->price;
                $discountPercent = $hasDiscount ? round((($product->price - $product->sale_price) / $product->price) * 100) : 0;
                $prodIdentifier = !empty($product->slug) ? $product->slug : $product->id;
                if (!empty($profile->custom_domain)) {
                    $productUrl = 'https://' . rtrim($profile->custom_domain, '/') . '/p/' . $prodIdentifier;
                } else {
                    $productUrl = route('bio.product.show', ['username' => $username, 'identifier' => $prodIdentifier]);
                }
                $ratingVal = !empty($product->rating) && $product->rating > 0 ? number_format($product->rating, 1) : '5.0';
            @endphp

            <div class="t5-product-card">
                <a href="{{ $productUrl }}" class="t5-product-img-wrap">
                    <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy" class="t5-product-img">
                    
                    @if($hasDiscount)
                        <span class="t5-discount-tag">-{{ $discountPercent }}%</span>
                    @endif

                    @if(!empty($product->product_type))
                        <span class="t5-type-tag">{{ strtoupper($product->product_type) }}</span>
                    @endif
                </a>

                <div class="t5-product-body">
                    <div class="t5-product-meta">
                        <span class="t5-rating-badge">
                            <svg width="13" height="13" fill="#f59e0b" stroke="#f59e0b" stroke-width="1" viewBox="0 0 24 24">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            {{ $ratingVal }}
                        </span>
                        <span class="t5-verified-tag">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
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

                        <a href="{{ $productUrl }}" class="t5-buy-btn" title="Beli / Detail Produk">
                            <span>Beli</span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
