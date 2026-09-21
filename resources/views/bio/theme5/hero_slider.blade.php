@php
    $bioName     = $config['name'] ?? $profile->store_name ?? $username;
    $bioText     = $config['bio'] ?? $profile->store_description ?? 'Temukan koleksi produk digital & layanan terbaik di sini.';
    $roleTitle   = $profile->bio_role ? ucfirst(str_replace('_', ' ', $profile->bio_role)) : 'Digital Creator';
    
    // Collect all banner images from Profile Banners, Config Banners, and Banner Blocks
    $slides = [];

    if (!empty($profile->store_banner_1)) {
        $slides[] = [
            'image' => asset('storage/' . $profile->store_banner_1),
            'tag'   => 'OFFICIAL PROMO',
            'title' => 'Selamat Datang di ' . $bioName,
            'desc'  => $bioText,
            'cta'   => 'Jelajahi Produk',
            'link'  => '#products-section'
        ];
    }
    if (!empty($profile->store_banner_2)) {
        $slides[] = [
            'image' => asset('storage/' . $profile->store_banner_2),
            'tag'   => 'REKOMENDASI TERBAIK',
            'title' => 'Aset & Layanan Digital Berlisensi',
            'desc'  => 'Dapatkan akses instan, produk original & terverifikasi langsung dari ' . $bioName . '.',
            'cta'   => 'Lihat Katalog',
            'link'  => '#products-section'
        ];
    }
    if (!empty($config['banners']) && is_array($config['banners'])) {
        foreach ($config['banners'] as $idx => $b) {
            $imgUrl = is_array($b) ? ($b['image'] ?? null) : $b;
            if ($imgUrl) {
                $slides[] = [
                    'image' => Str::startsWith($imgUrl, ['http://', 'https://']) ? $imgUrl : asset('storage/' . $imgUrl),
                    'tag'   => 'FEATURED',
                    'title' => is_array($b) ? ($b['title'] ?? 'Promo Spesial #' . ($idx+1)) : 'Promo Spesial',
                    'desc'  => is_array($b) ? ($b['desc'] ?? $bioText) : $bioText,
                    'cta'   => 'Selengkapnya',
                    'link'  => is_array($b) ? ($b['link'] ?? '#products-section') : '#products-section'
                ];
            }
        }
    }

    // Fallback slides if no custom banners uploaded yet
    if (empty($slides)) {
        $slides = [
            [
                'image' => null,
                'bg_gradient' => 'linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #064e3b 100%)',
                'tag'   => 'OFFICIAL CREATOR',
                'title' => 'Solusi Digital Terlengkap dari ' . $bioName,
                'desc'  => $bioText,
                'cta'   => 'Lihat Katalog Produk',
                'link'  => '#products-section'
            ],
            [
                'image' => null,
                'bg_gradient' => 'linear-gradient(135deg, #064e3b 0%, #0f172a 60%, #1eb349 100%)',
                'tag'   => 'TRANSAKSI INSTAN & AMAN',
                'title' => 'Produk Digital & Layanan Profesional',
                'desc'  => 'Akses cepat, lisensi original, dan pembayaran aman terverifikasi via buyle.id.',
                'cta'   => 'Beli Sekarang',
                'link'  => '#products-section'
            ],
            [
                'image' => null,
                'bg_gradient' => 'linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #0f172a 100%)',
                'tag'   => 'KONSULTASI & LAYANAN',
                'title' => 'Terhubung Langsung dengan ' . $bioName,
                'desc'  => 'Dapatkan rekomendasi & layanan khusus yang disesuaikan dengan kebutuhan Anda.',
                'cta'   => 'Hubungi Kami',
                'link'  => '#links-section'
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
                        <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" class="t5-slide-bg-img" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                        <div class="t5-slide-overlay"></div>
                    @else
                        <div class="t5-slide-bg-gradient" style="background: {{ $slide['bg_gradient'] }};"></div>
                    @endif

                    <div class="t5-slide-content">
                        <span class="t5-slide-tag"><span class="t5-tag-badge"></span> {{ $slide['tag'] }}</span>
                        <h1 class="t5-slide-title">{{ $slide['title'] }}</h1>
                        <p class="t5-slide-desc">{{ $slide['desc'] }}</p>

                        <div class="t5-slide-actions">
                            <a href="{{ $slide['link'] }}" class="t5-btn-primary">
                                <span>{{ $slide['cta'] }}</span>
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </a>
                            @if(!empty($config['wa']))
                                @php $waNum = preg_replace('/^(62|0)/', '', $config['wa']); @endphp
                                <a href="https://wa.me/62{{ $waNum }}" target="_blank" class="t5-btn-secondary">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                    </svg>
                                    <span>Tanya Admin</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Prev / Next Navigation Arrows --}}
        @if(count($slides) > 1)
            <button type="button" class="t5-slider-arrow t5-arrow-prev" onclick="prevT5Slide()" aria-label="Slide Sebelumnya">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>
            <button type="button" class="t5-slider-arrow t5-arrow-next" onclick="nextT5Slide()" aria-label="Slide Selanjutnya">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </button>

            {{-- Slider Indicators --}}
            <div class="t5-slider-dots">
                @foreach($slides as $index => $slide)
                    <button type="button" class="t5-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToT5Slide({{ $index }})" aria-label="Ke slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif
    </div>
</section>
