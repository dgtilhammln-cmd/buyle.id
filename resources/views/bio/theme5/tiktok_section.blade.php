{{-- ════════════════════════════════════════════════════════════════
     THEME 5 — 3D TIKTOK / REELS PHONE CAROUSEL (STRICTLY THEME 5)
     buyle.id | HVM Digital
════════════════════════════════════════════════════════════════ --}}
@php
    $tiktokEnabled = $config['tiktok_section_enabled'] ?? 1;
@endphp

@if($tiktokEnabled)
    @php
        $tHeadline   = !empty($config['tiktok_section_headline']) ? $config['tiktok_section_headline'] : 'Find mental health insights & video reels';
        $tSectionDesc = !empty($config['tiktok_section_description']) ? $config['tiktok_section_description'] : 'Tonton kumpulan video singkat pilihan dan konten edukasi menarik kami.';
        $tBtnText    = !empty($config['tiktok_section_btn_text']) ? $config['tiktok_section_btn_text'] : 'View all';
        $tBtnLink    = !empty($config['tiktok_section_btn_link']) ? $config['tiktok_section_btn_link'] : '#';

        $videos = [];
        for ($i = 1; $i <= 6; $i++) {
            $url    = $config["tiktok_video_{$i}_url"] ?? null;
            $title  = $config["tiktok_video_{$i}_title"] ?? null;
            $author = $config["tiktok_video_{$i}_author"] ?? null;
            $thumb  = $config["tiktok_video_{$i}_thumb"] ?? null;

            // Default samples if first 4 items are completely empty
            if (empty($url) && empty($title)) {
                $samples = [
                    1 => [
                        'title' => 'Top 10 tips to reduce Stress',
                        'author' => 'Dr. Jenni Jacobsen',
                        'thumb' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80',
                        'url' => 'https://www.tiktok.com'
                    ],
                    2 => [
                        'title' => 'How to choose a therapist?',
                        'author' => 'Calmery Studio',
                        'thumb' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=800&q=80',
                        'url' => 'https://www.tiktok.com'
                    ],
                    3 => [
                        'title' => 'Interview with CEO Olffi',
                        'author' => 'Calmery Media',
                        'thumb' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80',
                        'url' => 'https://www.tiktok.com'
                    ],
                    4 => [
                        'title' => 'Financial Freedom & Wellness',
                        'author' => 'Mindset Daily',
                        'thumb' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=800&q=80',
                        'url' => 'https://www.tiktok.com'
                    ]
                ];
                if (isset($samples[$i])) {
                    $videos[] = [
                        'id' => $i,
                        'title' => $samples[$i]['title'],
                        'author' => $samples[$i]['author'],
                        'thumb' => $samples[$i]['thumb'],
                        'url' => $samples[$i]['url']
                    ];
                }
            } elseif (!empty($url) || !empty($title)) {
                $videos[] = [
                    'id' => $i,
                    'title' => $title ?: "TikTok Video #{$i}",
                    'author' => $author ?: 'Video Konten',
                    'thumb' => $thumb ? asset('storage/' . $thumb) : '',
                    'url' => $url ?: '#'
                ];
            }
        }
    @endphp

    @if(count($videos) > 0)
        <style>
            /* ═════════════════════════════════════════
               TEMA 5 — 3D TIKTOK REEL CAROUSEL STYLES
            ═════════════════════════════════════════ */
            .t5-tiktok-section {
                max-width: 1200px;
                margin: 4.5rem auto;
                padding: 0 1.5rem;
                font-family: 'Montserrat', sans-serif;
                color: #0f172a;
                position: relative;
            }

            .t5-tiktok-header {
                text-align: center;
                max-width: 760px;
                margin: 0 auto 3rem auto;
            }

            .t5-tiktok-headline {
                font-size: clamp(1.75rem, 4vw, 2.6rem);
                font-weight: 800;
                line-height: 1.25;
                color: #0f172a;
                letter-spacing: -0.03em;
                margin-bottom: 0.85rem;
            }

            .t5-tiktok-subdesc {
                font-size: 0.98rem;
                color: #64748b;
                line-height: 1.6;
                margin: 0;
            }

            /* Carousel Stage Wrapper */
            .t5-tiktok-wrapper {
                position: relative;
                width: 100%;
                margin: 0 auto;
                padding: 1.5rem 0;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .t5-tiktok-stage-wrap {
                position: relative;
                width: 100%;
                max-width: 1050px;
                height: 480px;
                display: flex;
                align-items: center;
                justify-content: center;
                perspective: 1300px;
                user-select: none;
            }

            /* Nav Buttons */
            .t5-tiktok-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(8px);
                border: 1px solid #e2e8f0;
                color: #0f172a;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 40;
                box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .t5-tiktok-nav:hover {
                background: #0f172a;
                color: #ffffff;
                border-color: #0f172a;
                transform: translateY(-50%) scale(1.1);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            }

            .t5-tiktok-nav.prev { left: 10px; }
            .t5-tiktok-nav.next { right: 10px; }

            /* 3D Track */
            .t5-tiktok-track {
                position: relative;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Video Card Item Base */
            .t5-tiktok-card {
                position: absolute;
                width: clamp(230px, 30vw, 270px);
                height: clamp(380px, 48vw, 450px);
                border-radius: 28px;
                overflow: hidden;
                cursor: pointer;
                background: #0f172a;
                box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
                transition: transform 0.55s cubic-bezier(0.25, 1, 0.3, 1),
                            opacity 0.55s ease,
                            box-shadow 0.35s ease;
                border: 2px solid transparent;
            }

            .t5-tiktok-card-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 0.6s ease;
                background-size: cover;
                background-position: center;
            }

            /* Gradient Overlay inside Reel Card */
            .t5-tiktok-card-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(180deg, rgba(15, 23, 42, 0.5) 0%, rgba(15, 23, 42, 0.1) 40%, rgba(15, 23, 42, 0.85) 100%);
                padding: 1.5rem 1.25rem;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                color: #ffffff;
                text-align: center;
            }

            .t5-tiktok-card-head-text {
                font-size: 1.05rem;
                font-weight: 700;
                color: #ffffff;
                line-height: 1.35;
                text-shadow: 0 2px 6px rgba(0,0,0,0.4);
                margin-bottom: 0.2rem;
            }

            .t5-tiktok-card-author-text {
                font-size: 0.78rem;
                font-weight: 500;
                color: rgba(255, 255, 255, 0.82);
            }

            /* Play Button Icon */
            .t5-tiktok-play-btn {
                width: 52px;
                height: 52px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.95);
                color: #0f172a;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: auto;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .t5-tiktok-play-btn svg {
                margin-left: 3px;
            }

            .t5-tiktok-card:hover .t5-tiktok-play-btn {
                transform: scale(1.15);
                background: #ffffff;
                color: #1eb349;
                box-shadow: 0 12px 30px rgba(30, 179, 73, 0.4);
            }

            /* Bottom Action Button inside Phone Mockup */
            .t5-tiktok-phone-btn {
                margin-top: auto;
                width: 100%;
                padding: 0.65rem 1rem;
                background: rgba(255, 255, 255, 0.92);
                color: #0f172a !important;
                border-radius: 50px;
                font-size: 0.82rem;
                font-weight: 700;
                text-decoration: none !important;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
                transition: all 0.25s ease;
                border: 1px solid rgba(255, 255, 255, 0.6);
            }

            .t5-tiktok-phone-btn:hover {
                background: #ffffff;
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            }

            /* ── PHONE FRAME MOCKUP (CENTER ITEM) ── */
            .t5-tiktok-card.is-center {
                width: clamp(240px, 32vw, 280px);
                height: clamp(400px, 50vw, 470px);
                transform: translateX(0) scale(1.08) translateY(-6px);
                z-index: 25;
                opacity: 1;
                border-radius: 38px;
                border: 8px solid #0f172a;
                box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            }

            /* Phone Dynamic Island Notch */
            .t5-tiktok-card.is-center::before {
                content: '';
                position: absolute;
                top: 8px;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 18px;
                background: #0f172a;
                border-radius: 20px;
                z-index: 30;
            }

            .t5-tiktok-card.is-center:hover {
                transform: translateX(0) scale(1.12) translateY(-10px);
                box-shadow: 0 30px 70px rgba(0, 0, 0, 0.4);
            }

            /* Left 1 */
            .t5-tiktok-card.is-left-1 {
                transform: translateX(-165px) rotateY(16deg) scale(0.88) translateY(0);
                z-index: 15;
                opacity: 0.85;
            }
            .t5-tiktok-card.is-left-1:hover {
                opacity: 0.95;
                transform: translateX(-160px) rotateY(12deg) scale(0.92);
            }

            /* Left 2 */
            .t5-tiktok-card.is-left-2 {
                transform: translateX(-310px) rotateY(26deg) scale(0.74) translateY(10px);
                z-index: 8;
                opacity: 0.5;
            }

            /* Right 1 */
            .t5-tiktok-card.is-right-1 {
                transform: translateX(165px) rotateY(-16deg) scale(0.88) translateY(0);
                z-index: 15;
                opacity: 0.85;
            }
            .t5-tiktok-card.is-right-1:hover {
                opacity: 0.95;
                transform: translateX(160px) rotateY(-12deg) scale(0.92);
            }

            /* Right 2 */
            .t5-tiktok-card.is-right-2 {
                transform: translateX(310px) rotateY(-26deg) scale(0.74) translateY(10px);
                z-index: 8;
                opacity: 0.5;
            }

            .t5-tiktok-card.is-hidden {
                transform: translateX(0) scale(0.5);
                z-index: 1;
                opacity: 0;
                pointer-events: none;
            }

            /* Pagination Dots */
            .t5-tiktok-dots {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                margin-top: 1.5rem;
            }

            .t5-tiktok-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #cbd5e1;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .t5-tiktok-dot.active {
                width: 24px;
                border-radius: 12px;
                background: #0f172a;
                box-shadow: 0 2px 8px rgba(15, 23, 42, 0.3);
            }

            /* ── TIKTOK EMBED VIDEO MODAL ── */
            .t5-tiktok-modal {
                position: fixed;
                inset: 0;
                z-index: 99999;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
                opacity: 0;
                visibility: hidden;
                transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .t5-tiktok-modal.is-open {
                opacity: 1;
                visibility: visible;
            }

            .t5-tiktok-modal-backdrop {
                position: absolute;
                inset: 0;
                background: rgba(15, 23, 42, 0.85);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }

            .t5-tiktok-modal-content {
                position: relative;
                z-index: 2;
                background: #000000;
                border-radius: 20px;
                width: 100%;
                max-width: 420px;
                height: 80vh;
                max-height: 720px;
                overflow: hidden;
                box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
                transform: scale(0.92) translateY(20px);
                transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .t5-tiktok-modal.is-open .t5-tiktok-modal-content {
                transform: scale(1) translateY(0);
            }

            .t5-tiktok-modal-close {
                position: absolute;
                top: 0.75rem;
                right: 0.75rem;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.25);
                color: #ffffff;
                border: none;
                font-size: 1.3rem;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 20;
                transition: all 0.2s ease;
            }

            .t5-tiktok-modal-close:hover {
                background: #ef4444;
                transform: scale(1.1);
            }

            .t5-tiktok-iframe-container {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .t5-tiktok-iframe-container iframe {
                width: 100%;
                height: 100%;
                border: none;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .t5-tiktok-stage-wrap {
                    height: 420px;
                }
                .t5-tiktok-card.is-center {
                    width: 220px;
                    height: 380px;
                    border-width: 6px;
                    border-radius: 28px;
                }
                .t5-tiktok-card.is-left-1 {
                    transform: translateX(-100px) rotateY(12deg) scale(0.82);
                }
                .t5-tiktok-card.is-right-1 {
                    transform: translateX(100px) rotateY(-12deg) scale(0.82);
                }
                .t5-tiktok-card.is-left-2, .t5-tiktok-card.is-right-2 {
                    opacity: 0.25;
                }
            }

            @media (max-width: 480px) {
                .t5-tiktok-stage-wrap {
                    height: 370px;
                }
                .t5-tiktok-card.is-center {
                    width: 200px;
                    height: 340px;
                }
                .t5-tiktok-card.is-left-1 {
                    transform: translateX(-65px) scale(0.78);
                    opacity: 0.5;
                }
                .t5-tiktok-card.is-right-1 {
                    transform: translateX(65px) scale(0.78);
                    opacity: 0.5;
                }
                .t5-tiktok-card.is-left-2, .t5-tiktok-card.is-right-2 {
                    opacity: 0;
                }
            }
        </style>

        <section class="t5-tiktok-section" id="t5-tiktok-section">
            {{-- Header --}}
            <div class="t5-tiktok-header">
                <h2 class="t5-tiktok-headline">{{ $tHeadline }}</h2>
                <p class="t5-tiktok-subdesc">{{ $tSectionDesc }}</p>
            </div>

            {{-- 3D Reel Phone Stage --}}
            <div class="t5-tiktok-wrapper">
                <div class="t5-tiktok-stage-wrap" id="t5TiktokStage">
                    <button type="button" class="t5-tiktok-nav prev" id="t5TiktokPrev" aria-label="Previous Video">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M15 18l-6-6 6-6"/>
                        </svg>
                    </button>

                    <div class="t5-tiktok-track" id="t5TiktokTrack">
                        @foreach($videos as $index => $v)
                            <div class="t5-tiktok-card" 
                                 data-index="{{ $index }}" 
                                 data-url="{{ $v['url'] }}"
                                 data-title="{{ e($v['title']) }}"
                                 data-author="{{ e($v['author']) }}"
                                 data-thumb="{{ $v['thumb'] }}">
                                
                                <div class="t5-tiktok-card-img" style="{{ $v['thumb'] ? "background-image: url('{$v['thumb']}');" : "background: #0f172a;" }}"></div>

                                <div class="t5-tiktok-card-overlay">
                                    <div>
                                        <div class="t5-tiktok-card-head-text">{{ $v['title'] }}</div>
                                        <div class="t5-tiktok-card-author-text">{{ $v['author'] }}</div>
                                    </div>

                                    <div class="t5-tiktok-play-btn" title="Putar Video">
                                        <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>

                                    <a href="{{ !empty($tBtnLink) && $tBtnLink !== '#' ? $tBtnLink : ($v['url'] ?: '#') }}" 
                                       target="_blank" rel="noopener" 
                                       class="t5-tiktok-phone-btn" 
                                       onclick="event.stopPropagation();">
                                        {{ $tBtnText }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="t5-tiktok-nav next" id="t5TiktokNext" aria-label="Next Video">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M9 18l6-6-6-6"/>
                        </svg>
                    </button>
                </div>

                {{-- Pagination Dots --}}
                <div class="t5-tiktok-dots" id="t5TiktokDots">
                    @foreach($videos as $index => $v)
                        <span class="t5-tiktok-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- TikTok Player Modal --}}
        <div class="t5-tiktok-modal" id="t5TiktokModal">
            <div class="t5-tiktok-modal-backdrop" id="t5TiktokModalBackdrop"></div>
            <div class="t5-tiktok-modal-content">
                <button type="button" class="t5-tiktok-modal-close" id="t5TiktokModalClose" aria-label="Tutup">&times;</button>
                <div class="t5-tiktok-iframe-container" id="t5TiktokModalIframeWrap">
                    {{-- Embedded iframe dynamically injected here --}}
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cards = Array.from(document.querySelectorAll('.t5-tiktok-card'));
                const dots = Array.from(document.querySelectorAll('.t5-tiktok-dot'));
                const prevBtn = document.getElementById('t5TiktokPrev');
                const nextBtn = document.getElementById('t5TiktokNext');
                const stage = document.getElementById('t5TiktokStage');

                const modal = document.getElementById('t5TiktokModal');
                const modalBackdrop = document.getElementById('t5TiktokModalBackdrop');
                const modalClose = document.getElementById('t5TiktokModalClose');
                const iframeWrap = document.getElementById('t5TiktokModalIframeWrap');

                if (!cards.length) return;

                let currentIndex = 0;
                const total = cards.length;

                // Auto fetch TikTok oEmbed thumbnails & titles for URLs if no custom thumb set (Same as Tema 1!)
                cards.forEach(card => {
                    const url = card.getAttribute('data-url');
                    const hasCustomThumb = card.getAttribute('data-thumb');

                    if (url && url.includes('tiktok.com') && !hasCustomThumb) {
                        fetch(`https://www.tiktok.com/oembed?url=${encodeURIComponent(url)}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data) {
                                    const imgEl = card.querySelector('.t5-tiktok-card-img');
                                    const titleEl = card.querySelector('.t5-tiktok-card-head-text');
                                    const authorEl = card.querySelector('.t5-tiktok-card-author-text');

                                    if (data.thumbnail_url && imgEl) {
                                        imgEl.style.backgroundImage = `url('${data.thumbnail_url}')`;
                                        card.setAttribute('data-thumb', data.thumbnail_url);
                                    }
                                    if (data.title && titleEl && !card.getAttribute('data-title-custom')) {
                                        titleEl.textContent = data.title.length > 50 ? data.title.substring(0, 50) + '...' : data.title;
                                    }
                                    if (data.author_name && authorEl && !card.getAttribute('data-author-custom')) {
                                        authorEl.textContent = '@' + data.author_name;
                                    }
                                }
                            })
                            .catch(err => {});
                    }
                });

                function updateCarousel() {
                    cards.forEach((card, i) => {
                        card.className = 't5-tiktok-card';

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

                    dots.forEach((dot, i) => {
                        dot.classList.toggle('active', i === currentIndex);
                    });
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

                // Play TikTok Video Modal
                function openVideoModal(url) {
                    if (!modal || !url) return;

                    // Extract Video ID from TikTok URL
                    let embedUrl = url;
                    if (url.includes('tiktok.com')) {
                        let matches = url.match(/video\/(\d+)/);
                        if (matches && matches[1]) {
                            embedUrl = `https://www.tiktok.com/embed/v2/${matches[1]}`;
                        } else {
                            embedUrl = `https://www.tiktok.com/embed/v2/?url=${encodeURIComponent(url)}`;
                        }
                    }

                    if (iframeWrap) {
                        iframeWrap.innerHTML = `<iframe src="${embedUrl}" allowfullscreen allow="autoplay"></iframe>`;
                    }
                    modal.classList.add('is-open');
                    document.body.style.overflow = 'hidden';
                }

                function closeVideoModal() {
                    if (!modal) return;
                    modal.classList.remove('is-open');
                    if (iframeWrap) iframeWrap.innerHTML = '';
                    document.body.style.overflow = '';
                }

                if (modalClose) modalClose.addEventListener('click', closeVideoModal);
                if (modalBackdrop) modalBackdrop.addEventListener('click', closeVideoModal);

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal && modal.classList.contains('is-open')) {
                        closeVideoModal();
                    }
                });

                // Click card interaction
                cards.forEach((card, i) => {
                    card.addEventListener('click', function(e) {
                        if (i === currentIndex) {
                            const url = card.getAttribute('data-url');
                            if (url && url !== '#') {
                                openVideoModal(url);
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

                // Touch Swipe Support
                let touchStartX = 0;
                let touchEndX = 0;

                if (stage) {
                    stage.addEventListener('touchstart', (e) => {
                        touchStartX = e.changedTouches[0].screenX;
                    }, { passive: true });

                    stage.addEventListener('touchend', (e) => {
                        touchEndX = e.changedTouches[0].screenX;
                        if (touchStartX - touchEndX > 45) {
                            nextSlide();
                        } else if (touchEndX - touchStartX > 45) {
                            prevSlide();
                        }
                    }, { passive: true });
                }

                updateCarousel();
            });
        </script>
    @endif
@endif
