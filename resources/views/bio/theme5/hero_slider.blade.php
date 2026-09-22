@php
    $bioName = $config['name'] ?? $profile->store_name ?? $username;
    $bioText = $config['bio'] ?? $profile->store_description ?? 'Temukan koleksi produk & layanan terbaik di sini.';
    $roleTitle = $profile->bio_role ? ucfirst(str_replace('_', ' ', $profile->bio_role)) : 'Official Website';

    // Collect up to 5 banner images
    $rawBanners = [];

    // 1. Check Primary Cover ($config['cover'] or $profile->store_banner_1)
    $primaryCover = $config['cover'] ?? ($profile->store_banner_1 ?? null);
    if (!empty($primaryCover)) {
        $rawBanners[] = [
            'image' => Str::startsWith($primaryCover, ['http://', 'https://']) ? $primaryCover : asset('storage/' . $primaryCover),
            'link' => '#products-section'
        ];
    }

    // 2. Check store_banner_2
    if (!empty($profile->store_banner_2)) {
        $img = asset('storage/' . $profile->store_banner_2);
        if (!in_array($img, array_column($rawBanners, 'image'))) {
            $rawBanners[] = [
                'image' => $img,
                'link' => '#products-section'
            ];
        }
    }

    // 3. Check $config['banners'] array (up to 5 total)
    if (!empty($config['banners']) && is_array($config['banners'])) {
        foreach ($config['banners'] as $b) {
            if (count($rawBanners) >= 5)
                break;
            $imgUrl = is_array($b) ? ($b['image'] ?? null) : $b;
            if ($imgUrl) {
                $fullImg = Str::startsWith($imgUrl, ['http://', 'https://']) ? $imgUrl : asset('storage/' . $imgUrl);
                if (!in_array($fullImg, array_column($rawBanners, 'image'))) {
                    $rawBanners[] = [
                        'image' => $fullImg,
                        'link' => is_array($b) ? ($b['link'] ?? '#products-section') : '#products-section'
                    ];
                }
            }
        }
    }

    // Build slides array (Max 5 slides)
    // Slide 1 has hero overlay text. Slides 2..5 are CLEAN banners only.
    $slides = [];
    foreach ($rawBanners as $idx => $bItem) {
        if ($idx === 0) {
            $slides[] = [
                'image' => $bItem['image'],
                'has_overlay' => true,
                'title' => 'Selamat Datang di ' . $bioName,
                'desc' => $bioText,
                'cta' => 'Jelajahi Katalog',
                'link' => $bItem['link'] ?? '#products-section'
            ];
        } else {
            $slides[] = [
                'image' => $bItem['image'],
                'has_overlay' => false,
                'link' => $bItem['link'] ?? '#products-section'
            ];
        }
    }

    // Fallback if no banner uploaded at all
    if (empty($slides)) {
        $slides = [
            [
                'image' => null,
                'bg_gradient' => 'linear-gradient(135deg, #080a0c 0%, #161b20 50%, #064e3b 100%)',
                'has_overlay' => true,
                'title' => 'Selamat Datang di ' . $bioName,
                'desc' => $bioText,
                'cta' => 'Jelajahi Katalog',
                'link' => '#products-section'
            ]
        ];
    }
@endphp

<section class="t5-hero-slider-section" id="hero-section">
    <div class="t5-hero-slider-container">
        <div class="t5-slider-track" id="t5SliderTrack">
            @foreach($slides as $index => $slide)
                <div class="t5-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                    @if(!empty($slide['image']))
                        @if(!empty($slide['has_overlay']))
                            <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] ?? 'Banner' }}" class="t5-slide-bg-img"
                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                            <div class="t5-slide-overlay"></div>
                        @else
                            @if(!empty($slide['link']) && $slide['link'] !== '#')
                                <a href="{{ $slide['link'] }}" class="t5-clean-banner-link"
                                    style="display:block; width:100%; height:100%;">
                                    <img src="{{ $slide['image'] }}" alt="Banner {{ $index + 1 }}" class="t5-slide-bg-img"
                                        loading="lazy" style="object-fit:cover;">
                                </a>
                            @else
                                <img src="{{ $slide['image'] }}" alt="Banner {{ $index + 1 }}" class="t5-slide-bg-img" loading="lazy"
                                    style="object-fit:cover;">
                            @endif
                        @endif
                    @else
                        <div class="t5-slide-bg-gradient" style="background: {{ $slide['bg_gradient'] }};"></div>
                    @endif

                    @if(!empty($slide['has_overlay']))
                        <div class="t5-slide-content">
                            <h1 class="t5-slide-title">{{ $slide['title'] }}</h1>
                            <p class="t5-slide-desc">{{ $slide['desc'] }}</p>

                            <div class="t5-slide-actions">
                                <a href="{{ $slide['link'] }}" class="t5-btn-primary">
                                    <span>{{ $slide['cta'] }}</span>
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                                        viewBox="0 0 24 24">
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                        <polyline points="12 5 19 12 12 19" />
                                    </svg>
                                </a>
                                @if(!empty($config['wa']))
                                    <a href="javascript:void(0)"
                                        onclick="openT5LeadModal('Halo, saya ingin bertanya via WhatsApp.')"
                                        class="t5-btn-secondary">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                        </svg>
                                        <span>Hubungi Kami!</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Prev / Next Navigation Arrows --}}
        @if(count($slides) > 1)
            <button type="button" class="t5-slider-arrow t5-arrow-prev" onclick="prevT5Slide()"
                aria-label="Slide Sebelumnya">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
            </button>
            <button type="button" class="t5-slider-arrow t5-arrow-next" onclick="nextT5Slide()"
                aria-label="Slide Selanjutnya">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
            </button>

            {{-- Slider Indicators --}}
            <div class="t5-slider-dots">
                @foreach($slides as $index => $slide)
                    <button type="button" class="t5-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToT5Slide({{ $index }})"
                        aria-label="Ke slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif
    </div>
</section>