@extends('layouts.app')

@section('content')

    <style>
        /* ══════════════════════════════════════════════════════════════════════
           BUYLE TIX — DEDICATED TICKETING MARKETPLACE
           Theme: Montserrat, Modern Clean White + Emerald Green (#1eb349 / #16a34a)
           Exact Match to Homepage "Tiket Event & Konser" Section
        ══════════════════════════════════════════════════════════════════════ */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

        .buyle-tix-page {
            font-family: 'Montserrat', sans-serif !important;
            background: #F8FAFC;
            min-height: 100vh;
            padding-top: 5.5rem;
            padding-bottom: 5rem;
            color: #0F172A;
        }

        .buyle-tix-page * {
            font-family: 'Montserrat', sans-serif;
            box-sizing: border-box;
        }

        .tix-container {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        /* ══════════════════════════════════════════════════════════════════════
           1. TOP BANNER CAROUSEL (1920 X 600 LANDSCAPE PURE IMAGE SLIDER)
        ══════════════════════════════════════════════════════════════════════ */
        .tix-banner-section {
            margin-bottom: 2.25rem;
            position: relative;
        }

        .tix-carousel-container {
            position: relative;
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
        }

        .tix-carousel-viewport {
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06), 0 2px 8px rgba(0, 0, 0, 0.03);
            border: 1px solid #E2E8F0;
            background: #0F172A;
            position: relative;
        }

        .tix-carousel-track {
            display: flex;
            transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            width: 100%;
        }

        .tix-slide {
            flex: 0 0 100%;
            width: 100%;
            position: relative;
            aspect-ratio: 1920 / 600;
            min-height: 180px;
            max-height: 420px;
            overflow: hidden;
            text-decoration: none;
            color: #ffffff;
            display: block;
            background: #0F172A;
        }

        @media (max-width: 768px) {
            .tix-slide {
                aspect-ratio: 16 / 9;
                min-height: 160px;
            }
        }

        .tix-slide-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Nav Prev / Next Circular Buttons */
        .tix-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0F172A;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
            z-index: 10;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            outline: none;
        }

        .tix-nav-btn:hover {
            background: #F8FAFC;
            transform: translateY(-50%) scale(1.08);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .tix-nav-btn.prev { left: -16px; }
        .tix-nav-btn.next { right: -16px; }

        @media (max-width: 768px) {
            .tix-nav-btn {
                width: 34px;
                height: 34px;
            }
            .tix-nav-btn.prev { left: 8px; }
            .tix-nav-btn.next { right: 8px; }
        }

        /* Carousel Dash Indicators */
        .tix-carousel-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 0.85rem;
        }

        .tix-dot {
            height: 5px;
            width: 18px;
            border-radius: 999px;
            background: #CBD5E1;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            padding: 0;
        }

        .tix-dot.active {
            width: 36px;
            background: #1eb349;
        }

        /* ══════════════════════════════════════════════════════════════════════
           2. SEARCH & QUICK FILTER BAR
        ══════════════════════════════════════════════════════════════════════ */
        .tix-search-bar-wrap {
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            border-radius: 16px;
            padding: 0.65rem 0.85rem;
            margin-bottom: 2.25rem;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.025);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .tix-search-form {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex: 1;
            min-width: 240px;
        }

        .tix-search-input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 0.88rem;
            color: #0F172A;
            font-weight: 600;
            background: transparent;
        }

        .tix-search-input::placeholder {
            color: #94A3B8;
            font-weight: 500;
        }

        .tix-filter-chips {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            flex-wrap: wrap;
        }

        .tix-chip {
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 700;
            text-decoration: none;
            color: #475569;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            transition: all 0.15s ease;
        }

        .tix-chip:hover {
            background: #E2E8F0;
            color: #0F172A;
        }

        .tix-chip.active {
            background: #1eb349;
            color: #ffffff;
            border-color: #1eb349;
            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.28);
        }

        /* ══════════════════════════════════════════════════════════════════════
           3. RESPONSIVE EVENT CARDS GRID (EXACT HOMEPAGE CARD STYLES)
        ══════════════════════════════════════════════════════════════════════ */
        .tix-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 2.75rem;
        }

        @media (max-width: 1140px) {
            .tix-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }
        }

        @media (max-width: 780px) {
            .tix-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.85rem;
            }
        }

        @media (max-width: 500px) {
            .tix-grid {
                grid-template-columns: 1fr;
                gap: 0.85rem;
            }
        }

        .article-card-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-color: #CBD5E1;
        }

        .article-card-box:hover .article-banner-img {
            transform: scale(1.04);
        }

        /* Empty State */
        .tix-empty-card {
            background: #ffffff;
            border: 1.5px dashed #CBD5E1;
            border-radius: 16px;
            padding: 3rem 1.5rem;
            text-align: center;
            color: #64748B;
            grid-column: 1 / -1;
        }

        /* ══════════════════════════════════════════════════════════════════════
           4. SKELETON SHIMMER & LAZY LOAD STYLES
        ══════════════════════════════════════════════════════════════════════ */
        @keyframes tixShimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .tix-skeleton {
            background: linear-gradient(90deg, #F1F5F9 25%, #E2E8F0 37%, #F1F5F9 63%);
            background-size: 400% 100%;
            animation: tixShimmer 1.4s ease infinite;
            border-radius: 10px;
        }

        .tix-skeleton-card {
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            border-radius: 16px;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            pointer-events: none;
        }

        /* Promoter Footer Box */
        .tix-cta-box {
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            border-radius: 20px;
            padding: 2.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-top: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .tix-cta-box {
                flex-direction: column;
                align-items: flex-start;
                padding: 1.5rem;
            }
        }
    </style>

    <div class="buyle-tix-page">

        {{-- ══ SKELETON SHIMMER OVERLAY (Smooth Initial Render) ══ --}}
        <div id="tixInitialSkeleton" class="tix-container">
            {{-- Top Banner Skeleton (1920x600) --}}
            <div class="tix-skeleton" style="width: 100%; aspect-ratio: 1920/600; min-height: 180px; max-height: 420px; border-radius: 20px; margin-bottom: 2.25rem;"></div>

            {{-- Search Bar Skeleton --}}
            <div class="tix-skeleton" style="width: 100%; height: 56px; border-radius: 16px; margin-bottom: 2.25rem;"></div>

            {{-- Section Title Skeleton --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div class="tix-skeleton" style="width: 220px; height: 26px; border-radius: 8px;"></div>
                <div class="tix-skeleton" style="width: 130px; height: 20px; border-radius: 8px;"></div>
            </div>

            {{-- 4 Responsive Card Skeletons --}}
            <div class="tix-grid">
                @for($i = 0; $i < 4; $i++)
                    <div class="tix-skeleton-card">
                        <div class="tix-skeleton" style="width: 100%; aspect-ratio: 16/9; border-radius: 12px; margin-bottom: 0.85rem;"></div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <div class="tix-skeleton" style="width: 45px; height: 16px; border-radius: 4px;"></div>
                            <div class="tix-skeleton" style="width: 65px; height: 16px; border-radius: 4px;"></div>
                        </div>
                        <div class="tix-skeleton" style="width: 88%; height: 18px; margin-bottom: 0.35rem; border-radius: 6px;"></div>
                        <div class="tix-skeleton" style="width: 60%; height: 16px; margin-bottom: 0.6rem; border-radius: 6px;"></div>
                        <div class="tix-skeleton" style="width: 110px; height: 14px; margin-bottom: 0.3rem; border-radius: 4px;"></div>
                        <div class="tix-skeleton" style="width: 90px; height: 14px; margin-bottom: 0.8rem; border-radius: 4px;"></div>
                        <div style="border-top: 1px solid #F1F5F9; padding-top: 0.65rem; display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                            <div class="tix-skeleton" style="width: 70px; height: 20px; border-radius: 6px;"></div>
                            <div class="tix-skeleton" style="width: 75px; height: 28px; border-radius: 99px;"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- ══ REAL CONTENT CONTAINER ══ --}}
        <div id="tixRealContent" class="tix-container" style="display: none; opacity: 0; transition: opacity 0.3s ease;">

            {{-- ══ 1. TOP CAROUSEL BANNER (1920 X 600 PURE IMAGE SLIDES / SEEDED PLACEHOLDERS) ══ --}}
            <section class="tix-banner-section">
                <div class="tix-carousel-container" id="tixCarousel">
                    <div class="tix-carousel-viewport">
                        <div class="tix-carousel-track" id="tixCarouselTrack">
                            @foreach($banners as $index => $banner)
                                <a href="{{ $banner['url'] }}" class="tix-slide">
                                    @if(!empty($banner['image']))
                                        {{-- Pure Uploaded 1920x600 Image Banner (No text overlay) --}}
                                        <img src="{{ $banner['image'] }}" alt="{{ $banner['title'] }}" class="tix-slide-img">
                                    @else
                                        {{-- Clean SVG Landscape Placeholder --}}
                                        <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #064E3B 100%); color: #ffffff; text-align: center; padding: 1.5rem; position: relative;">
                                            <div style="width: 52px; height: 52px; border-radius: 50%; background: rgba(30, 179, 73, 0.18); border: 1.5px solid rgba(30, 179, 73, 0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 0.65rem; color: #4ADE80;">
                                                <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/></svg>
                                            </div>
                                            <span style="font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; color: #4ADE80; text-transform: uppercase; margin-bottom: 0.25rem;">
                                                BUYLE TIX BANNER (1920 X 600 PX)
                                            </span>
                                            <h3 style="font-size: clamp(1rem, 2vw, 1.35rem); font-weight: 800; margin: 0 0 0.35rem; color: #ffffff;">
                                                {{ $banner['title'] }}
                                            </h3>
                                            <p style="font-size: 0.8rem; color: #94A3B8; margin: 0 0 0.75rem; max-width: 480px; line-height: 1.4;">
                                                {{ $banner['description'] ?: 'Upload banner promosi Anda melalui Admin Panel > Manajemen Banner.' }}
                                            </p>
                                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.72rem; font-weight: 700; color: #CBD5E1; background: rgba(255,255,255,0.08); padding: 0.35rem 0.85rem; border-radius: 999px; border: 1px solid rgba(255,255,255,0.15);">
                                                Upload Gambar 1920x600 di Admin Panel
                                            </span>
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>

                        {{-- Nav Prev / Next Circular Buttons --}}
                        <button type="button" class="tix-nav-btn prev" onclick="tixPrevSlide()" aria-label="Slide Sebelumnya">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <button type="button" class="tix-nav-btn next" onclick="tixNextSlide()" aria-label="Slide Selanjutnya">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>

                    {{-- Bottom Pill Dash Indicators --}}
                    <div class="tix-carousel-dots" id="tixCarouselDots">
                        @foreach($banners as $index => $banner)
                            <button type="button" class="tix-dot {{ $index === 0 ? 'active' : '' }}" onclick="tixGoToSlide({{ $index }})" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- ══ 2. SEARCH & QUICK FILTER BAR ══ --}}
            <div class="tix-search-bar-wrap">
                <form action="{{ route('tix.index') }}" method="GET" class="tix-search-form">
                    <svg width="18" height="18" fill="none" stroke="#64748B" stroke-width="2.2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama konser, seminar, festival, kota atau artis..." class="tix-search-input">
                    @if($type)
                        <input type="hidden" name="type" value="{{ $type }}">
                    @endif
                </form>

                <div class="tix-filter-chips">
                    <a href="{{ route('tix.index', array_filter(['q' => $search])) }}" class="tix-chip {{ empty($type) ? 'active' : '' }}">
                        Semua Event
                    </a>
                    <a href="{{ route('tix.index', array_filter(['q' => $search, 'type' => 'offline'])) }}" class="tix-chip {{ $type === 'offline' ? 'active' : '' }}">
                        📍 Event Offline
                    </a>
                    <a href="{{ route('tix.index', array_filter(['q' => $search, 'type' => 'online'])) }}" class="tix-chip {{ $type === 'online' ? 'active' : '' }}">
                        💻 Webinar / Online
                    </a>
                </div>
            </div>

            {{-- ══ 3. SECTION: TIKET EVENT & KONSER (EXACT MATCH HOMEPAGE SECTION) ══ --}}
            <section style="margin-bottom: 2.75rem;" id="event-terdekat">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                    <h2 style="font-family: 'Montserrat', sans-serif; font-size: 1.35rem; font-weight: 800; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        Tiket Event & Konser
                    </h2>
                    <a href="{{ route('tix.index') }}" style="font-size: 0.85rem; font-weight: 700; color: #1eb349; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                        Lihat Semua Tiket
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M5 12h14m-7-7l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                {{-- Cards Grid Container --}}
                <div class="tix-grid" id="upcomingGrid">
                    @if(count($upcomingEvents) > 0)
                        @include('tix.partials.event_cards', ['events' => $upcomingEvents, 'is_past' => false])
                    @else
                        <div class="tix-empty-card">
                            <div style="width: 52px; height: 52px; border-radius: 50%; background: #F1F5F9; display: inline-flex; align-items: center; justify-content: center; color: #94A3B8; margin-bottom: 0.75rem;">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/></svg>
                            </div>
                            <h4 style="margin: 0 0 0.35rem; font-size: 1rem; font-weight: 700; color: #0F172A;">Belum Ada Tiket Terdekat</h4>
                            <p style="margin: 0; font-size: 0.8rem; color: #64748B;">Nantikan pengumuman jadwal konser & event menarik lainnya segera!</p>
                        </div>
                    @endif
                </div>

                {{-- Infinite Scroll Skeleton Placeholders (4 cards matching grid) --}}
                <div class="tix-grid" id="infiniteScrollSkeleton" style="display: none; margin-top: -1.75rem; margin-bottom: 1.5rem;">
                    @for($i = 0; $i < 4; $i++)
                        <div class="tix-skeleton-card">
                            <div class="tix-skeleton" style="width: 100%; aspect-ratio: 16/9; border-radius: 12px; margin-bottom: 0.85rem;"></div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <div class="tix-skeleton" style="width: 45px; height: 16px; border-radius: 4px;"></div>
                                <div class="tix-skeleton" style="width: 65px; height: 16px; border-radius: 4px;"></div>
                            </div>
                            <div class="tix-skeleton" style="width: 88%; height: 18px; margin-bottom: 0.35rem; border-radius: 6px;"></div>
                            <div class="tix-skeleton" style="width: 60%; height: 16px; margin-bottom: 0.6rem; border-radius: 6px;"></div>
                            <div class="tix-skeleton" style="width: 110px; height: 14px; margin-bottom: 0.3rem; border-radius: 4px;"></div>
                            <div class="tix-skeleton" style="width: 90px; height: 14px; margin-bottom: 0.8rem; border-radius: 4px;"></div>
                            <div style="border-top: 1px solid #F1F5F9; padding-top: 0.65rem; display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                                <div class="tix-skeleton" style="width: 70px; height: 20px; border-radius: 6px;"></div>
                                <div class="tix-skeleton" style="width: 75px; height: 28px; border-radius: 99px;"></div>
                            </div>
                        </div>
                    @endfor
                </div>

                {{-- Infinite Scroll Sentinel and Status Feedback --}}
                <div id="infiniteScrollSentinel" style="height: 20px;"></div>
                <div id="infiniteScrollStatus" style="text-align: center; padding: 0.5rem 0 1.5rem; font-size: 0.84rem; font-weight: 700; color: #94A3B8; display: none;">
                    <span id="infiniteScrollStatusText"></span>
                </div>
            </section>

            {{-- ══ 4. SECTION: EVENT BERAKHIR ══ --}}
            <section style="margin-bottom: 2.75rem;" id="event-berakhir">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                    <div>
                        <h2 style="font-family: 'Montserrat', sans-serif; font-size: 1.35rem; font-weight: 800; color: #0F172A; margin: 0;">
                            Event Berakhir
                        </h2>
                    </div>
                    <span style="font-size: 0.82rem; font-weight: 600; color: #94A3B8;">
                        Arsip Kegiatan & Dokumentasi
                    </span>
                </div>

                <div class="tix-grid">
                    @if(count($pastEvents) > 0)
                        @include('tix.partials.event_cards', ['events' => $pastEvents, 'is_past' => true])
                    @else
                        <div class="tix-empty-card">
                            <div style="width: 52px; height: 52px; border-radius: 50%; background: #F1F5F9; display: inline-flex; align-items: center; justify-content: center; color: #94A3B8; margin-bottom: 0.75rem;">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </div>
                            <h4 style="margin: 0 0 0.35rem; font-size: 1rem; font-weight: 700; color: #0F172A;">Belum Ada Arsip Event Berakhir</h4>
                            <p style="margin: 0; font-size: 0.8rem; color: #64748B;">Semua tiket event yang terdaftar saat ini masih berstatus aktif dan siap dipesan.</p>
                        </div>
                    @endif
                </div>
            </section>

            {{-- ══ 5. PROMOTER & CREATOR CTA BANNER ══ --}}
            <div class="tix-cta-box">
                <div>
                    <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.72rem; font-weight: 800; background: rgba(30, 179, 73, 0.18); color: #4ADE80; padding: 4px 12px; border-radius: 999px; text-transform: uppercase; margin-bottom: 0.75rem;">
                        Penyelenggara Event Official
                    </span>
                    <h3 style="font-size: clamp(1.15rem, 2.5vw, 1.55rem); font-weight: 800; color: #ffffff; margin: 0 0 0.5rem;">
                        Punya Konser, Seminar, atau Event Sendiri?
                    </h3>
                    <p style="font-size: 0.85rem; color: #CBD5E1; margin: 0; line-height: 1.5; max-width: 640px;">
                        Jual tiket gratis maupun berbayar secara mandiri di Buyle.id. Nikmati penerbitan e-tiket otomatis via WhatsApp/Email, sistem Gate Scanner panitia live kamera, serta penarikan dana cepat.
                    </p>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; flex-shrink: 0;">
                    <a href="{{ url('/creator/products?from=ticketing') }}" style="display: inline-flex; align-items: center; gap: 0.45rem; background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); color: #ffffff; font-size: 0.85rem; font-weight: 800; padding: 0.75rem 1.45rem; border-radius: 999px; text-decoration: none; box-shadow: 0 6px 20px rgba(30, 179, 73, 0.35); white-space: nowrap;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Buat Tiket Event</span>
                    </a>
                    <a href="{{ route('ticketing') }}" style="display: inline-flex; align-items: center; gap: 0.45rem; background: rgba(255, 255, 255, 0.08); border: 1.5px solid rgba(255, 255, 255, 0.2); color: #ffffff; font-size: 0.85rem; font-weight: 700; padding: 0.75rem 1.25rem; border-radius: 999px; text-decoration: none; white-space: nowrap;">
                        <span>Pelajari Fitur</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- ══ JAVASCRIPT CONTROLLERS: CAROUSEL, SKELETON TRANSITION, & INFINITE SCROLL ══ --}}
    <script>
        let currentTixSlide = 0;
        const tixSlideCount = {{ count($banners) }};
        let tixAutoPlayTimer = null;

        function tixUpdateCarousel() {
            const track = document.getElementById('tixCarouselTrack');
            if (track) {
                track.style.transform = `translateX(-${currentTixSlide * 100}%)`;
            }
            const dots = document.querySelectorAll('#tixCarouselDots .tix-dot');
            dots.forEach((dot, idx) => {
                if (idx === currentTixSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        function tixNextSlide() {
            if (tixSlideCount <= 1) return;
            currentTixSlide = (currentTixSlide + 1) % tixSlideCount;
            tixUpdateCarousel();
            tixResetTimer();
        }

        function tixPrevSlide() {
            if (tixSlideCount <= 1) return;
            currentTixSlide = (currentTixSlide - 1 + tixSlideCount) % tixSlideCount;
            tixUpdateCarousel();
            tixResetTimer();
        }

        function tixGoToSlide(index) {
            currentTixSlide = index;
            tixUpdateCarousel();
            tixResetTimer();
        }

        function tixStartTimer() {
            if (tixSlideCount > 1) {
                tixAutoPlayTimer = setInterval(tixNextSlide, 5000);
            }
        }

        function tixResetTimer() {
            clearInterval(tixAutoPlayTimer);
            tixStartTimer();
        }

        // Infinite Scroll Setup
        let tixCurrentPage = 1;
        let tixHasMore = {{ $hasMoreUpcoming ? 'true' : 'false' }};
        let tixIsLoading = false;
        const tixSearchQuery = @json($search);
        const tixEventType = @json($type);

        function tixLoadMore() {
            if (!tixHasMore || tixIsLoading) return;
            tixIsLoading = true;

            const skeleton = document.getElementById('infiniteScrollSkeleton');
            const statusBox = document.getElementById('infiniteScrollStatus');
            const statusText = document.getElementById('infiniteScrollStatusText');

            if (skeleton) skeleton.style.display = 'grid';
            if (statusBox && statusText) {
                statusText.textContent = 'Memuat lebih banyak event...';
                statusBox.style.display = 'block';
            }

            const nextPage = tixCurrentPage + 1;
            let url = new URL('{{ route("tix.index") }}', window.location.origin);
            url.searchParams.set('page', nextPage);
            if (tixSearchQuery) url.searchParams.set('q', tixSearchQuery);
            if (tixEventType) url.searchParams.set('type', tixEventType);

            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.html && data.html.trim().length > 0) {
                    const grid = document.getElementById('upcomingGrid');
                    if (grid) {
                        grid.insertAdjacentHTML('beforeend', data.html);
                    }
                    tixCurrentPage = data.current_page;
                    tixHasMore = data.has_more;
                } else {
                    tixHasMore = false;
                }

                if (!tixHasMore && statusBox && statusText) {
                    statusText.textContent = 'Semua tiket event telah dimuat ✨';
                    statusBox.style.display = 'block';
                } else if (statusBox) {
                    statusBox.style.display = 'none';
                }
            })
            .catch(err => {
                console.error('Error loading tix items:', err);
                if (statusBox && statusText) {
                    statusText.textContent = 'Gagal memuat tiket tambahan.';
                }
            })
            .finally(() => {
                tixIsLoading = false;
                if (skeleton) skeleton.style.display = 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Smooth skeleton transition to real content
            setTimeout(function() {
                const skeleton = document.getElementById('tixInitialSkeleton');
                const content = document.getElementById('tixRealContent');
                if (skeleton && content) {
                    skeleton.style.display = 'none';
                    content.style.display = 'block';
                    setTimeout(() => { content.style.opacity = '1'; }, 20);
                }
            }, 250);

            // Start Carousel
            tixStartTimer();

            const carousel = document.getElementById('tixCarousel');
            if (carousel) {
                carousel.addEventListener('mouseenter', () => clearInterval(tixAutoPlayTimer));
                carousel.addEventListener('mouseleave', () => tixStartTimer());

                // Touch swipe support on mobile
                let touchStartX = 0;
                let touchEndX = 0;
                carousel.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });

                carousel.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    if (touchStartX - touchEndX > 50) {
                        tixNextSlide();
                    } else if (touchEndX - touchStartX > 50) {
                        tixPrevSlide();
                    }
                }, { passive: true });
            }

            // Setup IntersectionObserver for Infinite Scroll
            if ('IntersectionObserver' in window) {
                const sentinel = document.getElementById('infiniteScrollSentinel');
                if (sentinel) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting && tixHasMore && !tixIsLoading) {
                                tixLoadMore();
                            }
                        });
                    }, {
                        rootMargin: '250px',
                        threshold: 0.1
                    });
                    observer.observe(sentinel);
                }
            }
        });
    </script>

@endsection
