{{-- ════════════════════════════════════════════════════════════════
     THEME 5 — 3D COVERFLOW GALLERY SECTION (STRICTLY THEME 5)
     buyle.id | PT Hiranatha / HVM Digital
════════════════════════════════════════════════════════════════ --}}
@php
    $galleryEnabled = $config['gallery_enabled'] ?? 1;
@endphp

@if($galleryEnabled)
    @php
        $gEyebrow   = !empty($config['gallery_eyebrow']) ? $config['gallery_eyebrow'] : 'Dokumentasi & Portofolio';
        $gHeadline  = !empty($config['gallery_headline']) ? $config['gallery_headline'] : 'Popular Beach Holiday Destinations for 2026/2027';
        $gSectionDesc = !empty($config['gallery_description']) ? $config['gallery_description'] : 'Browse our most popular beach destinations for UK travellers';

        $items = [];
        for ($i = 1; $i <= 10; $i++) {
            $img      = $config["gallery_{$i}_image"] ?? null;
            $title    = $config["gallery_{$i}_title"] ?? null;
            $subtitle = $config["gallery_{$i}_subtitle"] ?? null;
            $desc     = $config["gallery_{$i}_desc"] ?? null;
            $link     = $config["gallery_{$i}_link"] ?? null;

            // Default samples if first 5 items are completely empty
            if (empty($img) && empty($title)) {
                $samples = [
                    1 => [
                        'title' => 'Indonesia',
                        'subtitle' => 'Click to learn more',
                        'desc' => "Indonesia's tropical paradise featuring pristine beaches, lush rice terraces, and crystal turquoise waters in Bali & Raja Ampat.",
                        'img' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800&q=80',
                        'flag' => '🇮🇩'
                    ],
                    2 => [
                        'title' => 'Italy',
                        'subtitle' => 'Click to learn more',
                        'desc' => "Sardinia's white sand, the Amalfi Coast, and Sicily's golden shores. Plan your Italy beach holiday with flights, hotels, weather, and travel tips.",
                        'img' => 'https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=800&q=80',
                        'flag' => '🇮🇹'
                    ],
                    3 => [
                        'title' => 'Croatia',
                        'subtitle' => 'Click to learn more',
                        'desc' => "Explore Croatia's stunning Dalmatian Coast with historic stone villages, crystal clear Adriatic sea, and secluded islands.",
                        'img' => 'https://images.unsplash.com/photo-1555990538-1e428c0373e3?auto=format&fit=crop&w=800&q=80',
                        'flag' => '🇭🇷'
                    ],
                    4 => [
                        'title' => 'Malta',
                        'subtitle' => 'Click to learn more',
                        'desc' => "Malta offers sunny Mediterranean beaches, rich 7,000-year history, and vibrant coastal resorts surrounded by clear azure waters.",
                        'img' => 'https://images.unsplash.com/photo-1516483638261-f4dbaf036963?auto=format&fit=crop&w=800&q=80',
                        'flag' => '🇲🇹'
                    ],
                    5 => [
                        'title' => 'Mauritius',
                        'subtitle' => 'Click to learn more',
                        'desc' => "Mauritius features powder-soft white sand, coral-filled lagoons, and luxury island retreats set against dramatic mountain backdrops.",
                        'img' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=800&q=80',
                        'flag' => '🇲🇺'
                    ]
                ];
                if (isset($samples[$i])) {
                    $items[] = [
                        'id' => $i,
                        'title' => $samples[$i]['title'],
                        'subtitle' => $samples[$i]['subtitle'],
                        'desc' => $samples[$i]['desc'],
                        'img' => $samples[$i]['img'],
                        'link' => '#',
                        'flag' => $samples[$i]['flag']
                    ];
                }
            } elseif (!empty($img) || !empty($title)) {
                $items[] = [
                    'id' => $i,
                    'title' => $title ?: "Dokumentasi #{$i}",
                    'subtitle' => $subtitle ?: 'Lihat detail',
                    'desc' => $desc ?: $gSectionDesc,
                    'img' => $img ? asset('storage/' . $img) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                    'link' => $link ?: '#',
                    'flag' => '📍'
                ];
            }
        }
    @endphp

    @if(count($items) > 0)
        <style>
            /* ═════════════════════════════════════════
               TEMA 5 — 3D COVERFLOW GALLERY STYLES
            ═════════════════════════════════════════ */
            .t5-gallery-section {
                max-width: 1200px;
                margin: 4.5rem auto;
                padding: 0 1.5rem;
                font-family: 'Montserrat', sans-serif;
                color: #0f172a;
                position: relative;
            }

            .t5-gallery-header {
                text-align: center;
                max-width: 760px;
                margin: 0 auto 3rem auto;
            }

            .t5-gallery-eyebrow {
                font-size: 0.82rem;
                font-weight: 700;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                color: #1eb349;
                margin-bottom: 0.65rem;
                display: block;
            }

            .t5-gallery-headline {
                font-size: clamp(1.6rem, 3.8vw, 2.5rem);
                font-weight: 800;
                line-height: 1.25;
                color: #0f172a;
                letter-spacing: -0.03em;
                margin-bottom: 0.85rem;
            }

            .t5-gallery-headline span {
                color: #1eb349;
                position: relative;
            }

            .t5-gallery-subdesc {
                font-size: 0.95rem;
                color: #64748b;
                line-height: 1.6;
            }

            /* Carousel Container Wrapper */
            .t5-gallery-wrapper {
                position: relative;
                width: 100%;
                margin: 0 auto;
                padding: 2rem 0;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .t5-gallery-stage-wrap {
                position: relative;
                width: 100%;
                max-width: 1050px;
                height: 380px;
                display: flex;
                align-items: center;
                justify-content: center;
                perspective: 1200px;
                user-select: none;
            }

            /* Nav Buttons */
            .t5-gallery-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(8px);
                border: 1px solid #e2e8f0;
                color: #0f172a;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 30;
                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .t5-gallery-nav:hover {
                background: #1eb349;
                color: #ffffff;
                border-color: #1eb349;
                transform: translateY(-50%) scale(1.1);
                box-shadow: 0 8px 20px rgba(30, 179, 73, 0.3);
            }

            .t5-gallery-nav.prev { left: 10px; }
            .t5-gallery-nav.next { right: 10px; }

            /* 3D Track */
            .t5-gallery-track {
                position: relative;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Individual Card Item */
            .t5-gallery-card {
                position: absolute;
                width: clamp(230px, 32vw, 290px);
                height: clamp(300px, 42vw, 360px);
                border-radius: 20px;
                overflow: hidden;
                cursor: pointer;
                background: #0f172a;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
                transition: transform 0.55s cubic-bezier(0.25, 1, 0.3, 1),
                            opacity 0.55s ease,
                            box-shadow 0.3s ease,
                            border-color 0.3s ease;
                border: 2px solid transparent;
            }

            .t5-gallery-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 0.6s ease;
            }

            /* Gradient Overlay & Content inside Card */
            .t5-gallery-card-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(0, 0, 0, 0) 35%, rgba(15, 23, 42, 0.88) 100%);
                padding: 1.25rem;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                color: #ffffff;
                transition: background 0.3s ease;
            }

            .t5-gallery-card-flag {
                font-size: 1.1rem;
                margin-right: 0.4rem;
                vertical-align: middle;
            }

            .t5-gallery-card-title {
                font-size: 1.15rem;
                font-weight: 800;
                color: #ffffff;
                line-height: 1.2;
                margin-bottom: 0.25rem;
                display: flex;
                align-items: center;
            }

            .t5-gallery-card-sub {
                font-size: 0.75rem;
                font-weight: 500;
                color: rgba(255, 255, 255, 0.82);
                margin: 0;
                transition: color 0.3s;
            }

            /* ── Card Positioning Classes for 3D Stack ── */
            /* Center Active Card */
            .t5-gallery-card.is-center {
                transform: translateX(0) scale(1.12) translateY(-8px);
                z-index: 20;
                opacity: 1;
                box-shadow: 0 22px 50px rgba(0, 0, 0, 0.22);
                border-color: rgba(255, 255, 255, 0.4);
            }

            .t5-gallery-card.is-center:hover {
                transform: translateX(0) scale(1.16) translateY(-14px);
                border-color: #1eb349;
                box-shadow: 0 28px 60px rgba(30, 179, 73, 0.35);
            }

            .t5-gallery-card.is-center img {
                transform: scale(1.04);
            }

            /* Left 1 */
            .t5-gallery-card.is-left-1 {
                transform: translateX(-160px) rotateY(14deg) scale(0.92) translateY(0);
                z-index: 12;
                opacity: 0.85;
            }
            .t5-gallery-card.is-left-1:hover {
                opacity: 0.95;
                transform: translateX(-155px) rotateY(10deg) scale(0.96) translateY(-4px);
            }

            /* Left 2 */
            .t5-gallery-card.is-left-2 {
                transform: translateX(-300px) rotateY(24deg) scale(0.78) translateY(10px);
                z-index: 6;
                opacity: 0.55;
            }
            .t5-gallery-card.is-left-2:hover {
                opacity: 0.75;
                transform: translateX(-290px) rotateY(18deg) scale(0.83);
            }

            /* Right 1 */
            .t5-gallery-card.is-right-1 {
                transform: translateX(160px) rotateY(-14deg) scale(0.92) translateY(0);
                z-index: 12;
                opacity: 0.85;
            }
            .t5-gallery-card.is-right-1:hover {
                opacity: 0.95;
                transform: translateX(155px) rotateY(-10deg) scale(0.96) translateY(-4px);
            }

            /* Right 2 */
            .t5-gallery-card.is-right-2 {
                transform: translateX(300px) rotateY(-24deg) scale(0.78) translateY(10px);
                z-index: 6;
                opacity: 0.55;
            }
            .t5-gallery-card.is-right-2:hover {
                opacity: 0.75;
                transform: translateX(290px) rotateY(-18deg) scale(0.83);
            }

            /* Hidden items */
            .t5-gallery-card.is-hidden {
                transform: translateX(0) scale(0.5);
                z-index: 1;
                opacity: 0;
                pointer-events: none;
            }

            /* Description text below active card */
            .t5-gallery-active-desc {
                max-width: 620px;
                margin: 2rem auto 1.5rem auto;
                text-align: center;
                font-size: 0.95rem;
                color: #475569;
                line-height: 1.65;
                min-height: 52px;
                transition: opacity 0.3s ease;
            }

            /* Pagination Dots */
            .t5-gallery-dots {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .t5-gallery-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #cbd5e1;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .t5-gallery-dot.active {
                width: 24px;
                border-radius: 12px;
                background: linear-gradient(135deg, #1eb349, #a5cf37);
                box-shadow: 0 2px 8px rgba(30, 179, 73, 0.4);
            }

            /* Mobile Responsive Styles */
            @media (max-width: 768px) {
                .t5-gallery-stage-wrap {
                    height: 330px;
                }

                .t5-gallery-card {
                    width: 210px;
                    height: 290px;
                    border-radius: 16px;
                }

                .t5-gallery-card.is-left-1 {
                    transform: translateX(-95px) rotateY(10deg) scale(0.88);
                    opacity: 0.75;
                }

                .t5-gallery-card.is-right-1 {
                    transform: translateX(95px) rotateY(-10deg) scale(0.88);
                    opacity: 0.75;
                }

                .t5-gallery-card.is-left-2 {
                    transform: translateX(-160px) rotateY(18deg) scale(0.7);
                    opacity: 0.35;
                }

                .t5-gallery-card.is-right-2 {
                    transform: translateX(160px) rotateY(-18deg) scale(0.7);
                    opacity: 0.35;
                }

                .t5-gallery-nav.prev { left: 0px; }
                .t5-gallery-nav.next { right: 0px; }
            }

            @media (max-width: 480px) {
                .t5-gallery-stage-wrap {
                    height: 300px;
                }

                .t5-gallery-card {
                    width: 190px;
                    height: 270px;
                }

                .t5-gallery-card.is-left-1 {
                    transform: translateX(-65px) scale(0.82);
                    opacity: 0.6;
                }

                .t5-gallery-card.is-right-1 {
                    transform: translateX(65px) scale(0.82);
                    opacity: 0.6;
                }

                .t5-gallery-card.is-left-2, .t5-gallery-card.is-right-2 {
                    opacity: 0;
                }
            }
        </style>

        <section class="t5-gallery-section" id="t5-gallery-section">
            {{-- Header --}}
            <div class="t5-gallery-header">
                <span class="t5-gallery-eyebrow">{{ $gEyebrow }}</span>
                <h2 class="t5-gallery-headline">
                    @php
                        // Highlight phrase in headline if space exists
                        $words = explode(' ', e($gHeadline));
                        if (count($words) > 3) {
                            $highlightIndex = count($words) - 2;
                            $words[$highlightIndex] = '<span>' . $words[$highlightIndex] . '</span>';
                            $formattedHeadline = implode(' ', $words);
                        } else {
                            $formattedHeadline = e($gHeadline);
                        }
                    @endphp
                    {!! $formattedHeadline !!}
                </h2>
                <p class="t5-gallery-subdesc">{{ $gSectionDesc }}</p>
            </div>

            {{-- 3D Coverflow Stage --}}
            <div class="t5-gallery-wrapper">
                <div class="t5-gallery-stage-wrap" id="t5GalleryStage">
                    <button type="button" class="t5-gallery-nav prev" id="t5GalleryPrev" aria-label="Previous Slide">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M15 18l-6-6 6-6"/>
                        </svg>
                    </button>

                    <div class="t5-gallery-track" id="t5GalleryTrack">
                        @foreach($items as $index => $item)
                            <div class="t5-gallery-card" data-index="{{ $index }}" data-desc="{{ e($item['desc']) }}" data-link="{{ $item['link'] }}">
                                <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}" loading="lazy">
                                <div class="t5-gallery-card-overlay">
                                    <div class="t5-gallery-card-title">
                                        <span class="t5-gallery-card-flag">{{ $item['flag'] }}</span>
                                        {{ $item['title'] }}
                                    </div>
                                    <p class="t5-gallery-card-sub">{{ $item['subtitle'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="t5-gallery-nav next" id="t5GalleryNext" aria-label="Next Slide">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M9 18l6-6-6-6"/>
                        </svg>
                    </button>
                </div>

                {{-- Active Card Description Text --}}
                <div class="t5-gallery-active-desc" id="t5GalleryDesc">
                    {{ $items[0]['desc'] ?? '' }}
                </div>

                {{-- Pagination Dots --}}
                <div class="t5-gallery-dots" id="t5GalleryDots">
                    @foreach($items as $index => $item)
                        <span class="t5-gallery-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
                    @endforeach
                </div>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cards = Array.from(document.querySelectorAll('.t5-gallery-card'));
                const dots = Array.from(document.querySelectorAll('.t5-gallery-dot'));
                const descEl = document.getElementById('t5GalleryDesc');
                const prevBtn = document.getElementById('t5GalleryPrev');
                const nextBtn = document.getElementById('t5GalleryNext');
                const stage = document.getElementById('t5GalleryStage');

                if (!cards.length) return;

                let currentIndex = 0;
                const total = cards.length;

                function updateCarousel() {
                    cards.forEach((card, i) => {
                        // Reset all positioning classes
                        card.className = 't5-gallery-card';

                        // Calculate relative position from currentIndex with wrapping
                        let diff = i - currentIndex;
                        if (diff < -Math.floor(total / 2)) diff += total;
                        if (diff > Math.floor(total / 2)) diff -= total;

                        if (diff === 0) {
                            card.classList.add('is-center');
                        } else if (diff === -1 || (currentIndex === 0 && i === total - 1)) {
                            card.classList.add('is-left-1');
                        } else if (diff === -2 || (currentIndex === 1 && i === total - 1) || (currentIndex === 0 && i === total - 2)) {
                            card.classList.add('is-left-2');
                        } else if (diff === 1 || (currentIndex === total - 1 && i === 0)) {
                            card.classList.add('is-right-1');
                        } else if (diff === 2 || (currentIndex === total - 1 && i === 1) || (currentIndex === total - 2 && i === 0)) {
                            card.classList.add('is-right-2');
                        } else {
                            card.classList.add('is-hidden');
                        }
                    });

                    // Update active dot
                    dots.forEach((dot, i) => {
                        dot.classList.toggle('active', i === currentIndex);
                    });

                    // Update active description with smooth fade
                    if (descEl && cards[currentIndex]) {
                        descEl.style.opacity = '0';
                        setTimeout(() => {
                            descEl.textContent = cards[currentIndex].getAttribute('data-desc') || '';
                            descEl.style.opacity = '1';
                        }, 150);
                    }
                }

                function nextSlide() {
                    currentIndex = (currentIndex + 1) % total;
                    updateCarousel();
                }

                function prevSlide() {
                    currentIndex = (currentIndex - 1 + total) % total;
                    updateCarousel();
                }

                if (nextBtn) nextBtn.addEventListener('click', nextSlide);
                if (prevBtn) prevBtn.addEventListener('click', prevSlide);

                // Click card interaction
                cards.forEach((card, i) => {
                    card.addEventListener('click', function(e) {
                        if (i === currentIndex) {
                            const link = card.getAttribute('data-link');
                            if (link && link !== '#' && link !== 'javascript:void(0)') {
                                window.location.href = link;
                            }
                        } else {
                            currentIndex = i;
                            updateCarousel();
                        }
                    });
                });

                // Click dot interaction
                dots.forEach((dot, i) => {
                    dot.addEventListener('click', () => {
                        currentIndex = i;
                        updateCarousel();
                    });
                });

                // Touch Swipe Support for Mobile
                let touchStartX = 0;
                let touchEndX = 0;

                if (stage) {
                    stage.addEventListener('touchstart', (e) => {
                        touchStartX = e.changedTouches[0].screenX;
                    }, { passive: true });

                    stage.addEventListener('touchend', (e) => {
                        touchEndX = e.changedTouches[0].screenX;
                        if (touchStartX - touchEndX > 45) {
                            nextSlide(); // Swipe left
                        } else if (touchEndX - touchStartX > 45) {
                            prevSlide(); // Swipe right
                        }
                    }, { passive: true });
                }

                // Initial setup
                updateCarousel();
            });
        </script>
    @endif
@endif
