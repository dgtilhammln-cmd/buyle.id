@extends('layouts.app')

@section('content')

    <style>
        /* ══════════════════════════════════════════════════════════════════════
           BUYLE TIX — DEDICATED TICKETING MARKETPLACE
           Theme: Montserrat, Modern Clean White + Emerald #1eb349 / #16a34a
        ══════════════════════════════════════════════════════════════════════ */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tix-green: #16a34a;
            --tix-green-light: #22c55e;
            --tix-green-gradient: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            --tix-dark: #0f172a;
            --tix-muted: #64748b;
            --tix-border: #e2e8f0;
            --tix-bg: #f8fafc;
        }

        .buyle-tix-page {
            font-family: 'Montserrat', sans-serif !important;
            background: #f8fafc;
            min-height: 100vh;
            padding-top: 5.5rem;
            padding-bottom: 5rem;
            color: #0f172a;
            overflow-x: hidden;
        }

        .buyle-tix-page * {
            font-family: 'Montserrat', sans-serif;
            box-sizing: border-box;
        }

        .tix-container {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* ══════════════════════════════════════════════════════════════════════
           1. TOP BANNER CAROUSEL (1920 x 600 LANDSCAPE — EXACT STYLE IMAGE 1)
        ══════════════════════════════════════════════════════════════════════ */
        .tix-banner-section {
            margin-bottom: 2.25rem;
            position: relative;
        }

        .tix-carousel-container {
            position: relative;
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
        }

        .tix-carousel-viewport {
            overflow: hidden;
            border-radius: 24px;
            box-shadow: 0 14px 40px rgba(0, 0, 0, 0.08), 0 2px 10px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(226, 232, 240, 0.8);
            background: #0f172a;
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
            min-height: 220px;
            max-height: 440px;
            overflow: hidden;
            text-decoration: none;
            color: #ffffff;
            display: flex;
            align-items: center;
        }

        @media (max-width: 768px) {
            .tix-slide {
                aspect-ratio: 16 / 8.5;
                min-height: 195px;
            }
        }

        @media (max-width: 480px) {
            .tix-slide {
                aspect-ratio: 16 / 10;
                min-height: 185px;
            }
        }

        /* Retro synth / concert stage mesh overlay */
        .tix-slide-bg-grid {
            position: absolute;
            inset: 0;
            background-size: 32px 32px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            opacity: 0.6;
            pointer-events: none;
        }

        .tix-slide-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }

        .tix-slide-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.72) 48%, rgba(15, 23, 42, 0.88) 100%);
            z-index: 2;
        }

        .tix-slide-content {
            position: relative;
            z-index: 3;
            width: 100%;
            height: 100%;
            padding: 1.5rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .tix-slide-content {
                padding: 1rem 1.25rem;
                flex-direction: column;
                justify-content: center;
                align-items: flex-start;
                gap: 0.6rem;
            }
        }

        .tix-slide-left {
            max-width: 65%;
            min-width: 0;
        }

        @media (max-width: 768px) {
            .tix-slide-left {
                max-width: 100%;
            }
        }

        .tix-slide-presenter {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #94a3b8;
            margin-bottom: 0.4rem;
        }

        @media (max-width: 640px) {
            .tix-slide-presenter {
                font-size: 0.65rem;
                margin-bottom: 0.2rem;
            }
        }

        .tix-slide-title {
            font-size: clamp(1.2rem, 3.2vw, 2.4rem);
            font-weight: 900;
            color: #ffffff;
            line-height: 1.15;
            margin: 0 0 0.5rem 0;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            text-shadow: 0 4px 18px rgba(0, 0, 0, 0.4);
        }

        .tix-slide-sub {
            font-size: clamp(0.72rem, 1.2vw, 0.95rem);
            color: #cbd5e1;
            font-weight: 600;
            line-height: 1.4;
            margin: 0;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
        }

        .tix-slide-right {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .tix-slide-right {
                text-align: left;
                align-items: flex-start;
                flex-direction: row;
                gap: 0.75rem;
                width: 100%;
                justify-content: space-between;
            }
        }

        .tix-slide-loc-tag {
            font-size: 1.1rem;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 0.04em;
            line-height: 1.2;
            text-transform: uppercase;
        }

        @media (max-width: 640px) {
            .tix-slide-loc-tag {
                font-size: 0.85rem;
            }
        }

        .tix-slide-date-tag {
            font-size: 1rem;
            font-weight: 800;
            color: #38bdf8;
            letter-spacing: 0.02em;
        }

        @media (max-width: 640px) {
            .tix-slide-date-tag {
                font-size: 0.8rem;
            }
        }

        .tix-slide-cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 800;
            padding: 0.55rem 1.2rem;
            border-radius: 999px;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.4);
            margin-top: 0.35rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            transition: all 0.2s ease;
        }

        @media (max-width: 640px) {
            .tix-slide-cta-btn {
                padding: 0.4rem 0.85rem;
                font-size: 0.74rem;
                margin-top: 0;
            }
        }

        /* Carousel Navigation Buttons (Identical to Image 1) */
        .tix-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            z-index: 10;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            outline: none;
        }

        .tix-nav-btn:hover {
            background: #f8fafc;
            transform: translateY(-50%) scale(1.08);
            box-shadow: 0 8px 26px rgba(0, 0, 0, 0.2);
        }

        .tix-nav-btn.prev {
            left: -18px;
        }

        .tix-nav-btn.next {
            right: -18px;
        }

        @media (max-width: 768px) {
            .tix-nav-btn {
                width: 36px;
                height: 36px;
            }
            .tix-nav-btn.prev { left: 8px; }
            .tix-nav-btn.next { right: 8px; }
        }

        /* Carousel Dash Indicators (Image 1 Style) */
        .tix-carousel-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 1rem;
        }

        .tix-dot {
            height: 5px;
            width: 18px;
            border-radius: 999px;
            background: #cbd5e1;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            padding: 0;
        }

        .tix-dot.active {
            width: 38px;
            background: #eab308; /* Gold dash matching image 1 */
        }

        /* ══════════════════════════════════════════════════════════════════════
           2. SEARCH & QUICK FILTER BAR
        ══════════════════════════════════════════════════════════════════════ */
        .tix-search-bar-wrap {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 18px;
            padding: 0.65rem 0.9rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
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
            color: #0f172a;
            font-weight: 600;
            background: transparent;
        }

        .tix-search-input::placeholder {
            color: #94a3b8;
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
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            transition: all 0.15s ease;
        }

        .tix-chip:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .tix-chip.active {
            background: #16a34a;
            color: #ffffff;
            border-color: #16a34a;
            box-shadow: 0 2px 8px rgba(22, 163, 74, 0.25);
        }

        /* ══════════════════════════════════════════════════════════════════════
           3. SECTION HEADER (100% IDENTICAL TO IMAGE 2)
        ══════════════════════════════════════════════════════════════════════ */
        .tix-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            padding-bottom: 0.25rem;
        }

        .tix-section-title-wrap {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .tix-section-title {
            font-size: 1.28rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.01em;
            line-height: 1.2;
        }

        @media (max-width: 640px) {
            .tix-section-title {
                font-size: 1.1rem;
            }
        }

        .tix-section-link {
            font-size: 0.84rem;
            font-weight: 700;
            color: #16a34a;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.15s ease;
        }

        .tix-section-link:hover {
            color: #15803d;
            transform: translateX(2px);
        }

        /* ══════════════════════════════════════════════════════════════════════
           4. RESPONSIVE EVENT CARDS GRID (ANTI-BESAR, ANTI-NABRAK, ANTI-RUSAK)
        ══════════════════════════════════════════════════════════════════════ */
        .tix-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 1180px) {
            .tix-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.1rem;
            }
        }

        @media (max-width: 820px) {
            .tix-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.95rem;
            }
        }

        @media (max-width: 520px) {
            .tix-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        /* ══════════════════════════════════════════════════════════════════════
           5. EVENT CARD COMPONENT (100% IDENTICAL TO IMAGE 2)
        ══════════════════════════════════════════════════════════════════════ */
        .tix-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            padding: 1rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.025);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s;
            position: relative;
            overflow: hidden;
        }

        .tix-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        /* Card Image Container */
        .tix-card-media {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 14px;
            overflow: hidden;
            background: #0f172a;
            margin-bottom: 0.85rem;
            flex-shrink: 0;
            text-decoration: none;
            display: block;
        }

        .tix-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .tix-card:hover .tix-card-img {
            transform: scale(1.04);
        }

        /* Top Left Green Badge (EVENT / OFFLINE) */
        .tix-media-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #16a34a;
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 3px 9px;
            border-radius: 6px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
            z-index: 2;
        }

        .tix-media-badge.past {
            background: #64748b;
        }

        /* Body Info Container */
        .tix-card-body {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
        }

        /* Rating & Verified Badge Row */
        .tix-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.45rem;
        }

        .tix-rating {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #94a3b8;
        }

        .tix-rating .star {
            color: #94a3b8;
            font-size: 0.95rem;
        }

        .tix-verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.72rem;
            font-weight: 800;
            color: #0d9488; /* Teal verified text */
            letter-spacing: 0.01em;
        }

        .tix-verified-badge svg {
            color: #0d9488;
        }

        /* Event Title (2 Lines clamped) */
        .tix-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.35;
            margin: 0 0 0.4rem 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 2.65em;
        }

        .tix-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .tix-title a:hover {
            color: #16a34a;
        }

        /* Creator / Organizer Name (Explicitly requested by user) */
        .tix-organizer {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.76rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.45rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tix-organizer-avatar {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #dcfce7;
            color: #166534;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.62rem;
            font-weight: 800;
            flex-shrink: 0;
            overflow: hidden;
        }

        .tix-organizer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .tix-organizer-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Date & Location Lines */
        .tix-info-line {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 0.35rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tix-info-line.location {
            margin-bottom: 0.95rem;
        }

        .tix-info-icon {
            font-size: 0.85rem;
            line-height: 1;
            flex-shrink: 0;
        }

        /* Card Bottom Row: Price & Lihat Button (Identical to Image 2) */
        .tix-footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-top: auto;
            padding-top: 0.6rem;
            border-top: 1px dashed #f1f5f9;
        }

        .tix-price {
            font-size: 1.15rem;
            font-weight: 900;
            color: #16a34a;
            line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .tix-price.past {
            color: #64748b;
        }

        .tix-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            background: #22c55e; /* Image 2 green pill button */
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 0.45rem 1.15rem;
            border-radius: 999px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.32);
            transition: all 0.18s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
            border: none;
        }

        .tix-btn:hover {
            background: #16a34a;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
            color: #ffffff;
        }

        .tix-btn.past {
            background: #f1f5f9;
            color: #475569;
            box-shadow: none;
        }

        .tix-btn.past:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* Empty State */
        .tix-empty-card {
            background: #ffffff;
            border: 1.5px dashed #cbd5e1;
            border-radius: 20px;
            padding: 3rem 1.5rem;
            text-align: center;
            color: #64748b;
            grid-column: 1 / -1;
        }

        /* ══════════════════════════════════════════════════════════════════════
           6. PROMOTER CTA BANNER (Footer Showcase)
        ══════════════════════════════════════════════════════════════════════ */
        .tix-cta-box {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 24px;
            padding: 2.25rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            color: #ffffff;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.12);
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

        .tix-cta-box::after {
            content: '';
            position: absolute;
            right: -60px;
            bottom: -60px;
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.25) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
    </style>

    <div class="buyle-tix-page">
        <div class="tix-container">

            {{-- ══ 1. TOP CAROUSEL BANNER (1920 X 600 LANDSCAPE — STYLE IMAGE 1) ══ --}}
            <section class="tix-banner-section">
                <div class="tix-carousel-container" id="tixCarousel">
                    <div class="tix-carousel-viewport">
                        <div class="tix-carousel-track" id="tixCarouselTrack">
                            @foreach($banners as $index => $banner)
                                <a href="{{ $banner['url'] }}" class="tix-slide" style="{{ isset($banner['bg_gradient']) ? 'background:' . $banner['bg_gradient'] . ';' : 'background:#0f172a;' }}">
                                    <div class="tix-slide-bg-grid"></div>

                                    @if(!empty($banner['image']))
                                        <img src="{{ $banner['image'] }}" alt="{{ $banner['title'] }}" class="tix-slide-img">
                                        <div class="tix-slide-overlay"></div>
                                    @endif

                                    <div class="tix-slide-content">
                                        {{-- Left: Event Title & Headliner --}}
                                        <div class="tix-slide-left">
                                            <div class="tix-slide-presenter">
                                                <span>{{ $banner['organizer'] }}</span>
                                                <span style="opacity:0.6;">• PROUDLY PRESENT</span>
                                            </div>
                                            <h1 class="tix-slide-title">
                                                {{ $banner['title'] }}
                                            </h1>
                                            @if(!empty($banner['subtitle']))
                                                <p class="tix-slide-sub">
                                                    {{ $banner['subtitle'] }}
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Right: Location & Date & CTA --}}
                                        <div class="tix-slide-right">
                                            <div>
                                                <div class="tix-slide-loc-tag">{{ $banner['location'] }}</div>
                                                <div class="tix-slide-date-tag">{{ $banner['date'] }}</div>
                                            </div>
                                            <span class="tix-slide-cta-btn">
                                                Beli Tiket
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    <polyline points="12 5 19 12 12 19"></polyline>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Nav Prev / Next Circular Buttons (Image 1 Style) --}}
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

                    {{-- Bottom Pill Dash Indicators (Image 1 Style) --}}
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
                    <svg width="18" height="18" fill="none" stroke="#64748b" stroke-width="2.2" viewBox="0 0 24 24">
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

            {{-- ══ 3. SECTION: EVENT TERDEKAT (100% IDENTIK DENGAN IMAGE 2) ══ --}}
            <section style="margin-bottom: 2.5rem;" id="event-terdekat">
                <div class="tix-section-header">
                    <div class="tix-section-title-wrap">
                        <h2 class="tix-section-title">Event Terdekat</h2>
                    </div>
                    <a href="{{ route('tix.index') }}" class="tix-section-link">
                        Lihat Semua Tiket
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>

                <div class="tix-grid">
                    @forelse($upcomingEvents as $event)
                        @php
                            $imgSrc = $event->featured_image ? asset('storage/' . $event->featured_image) : ($event->image ? asset('storage/' . $event->image) : asset('images/service-default.jpg'));
                            $organizerName = $event->seller ? ($event->seller->creatorProfile->display_name ?? $event->seller->name) : 'Official Organizer';
                            $organizerAvatar = $event->seller?->avatar ? asset('storage/' . $event->seller->avatar) : null;
                            $price = $event->sale_price ?: $event->price;
                            $badgeLabel = strtoupper($event->event_type ?: 'EVENT');
                        @endphp
                        <div class="tix-card">
                            {{-- Image Container with Top-Left Badge --}}
                            <a href="{{ route('products.show', $event->slug) }}" class="tix-card-media">
                                <img src="{{ $imgSrc }}" alt="{{ $event->name }}" class="tix-card-img" loading="lazy">
                                <span class="tix-media-badge">{{ $badgeLabel }}</span>
                            </a>

                            <div class="tix-card-body">
                                {{-- Rating & Verified Row (Image 2) --}}
                                <div class="tix-meta-row">
                                    <div class="tix-rating">
                                        <span class="star">★</span>
                                        <span>{{ number_format($event->rating ?: 5.0, 1) }}</span>
                                    </div>
                                    <div class="tix-verified-badge">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span>Verified</span>
                                    </div>
                                </div>

                                {{-- Event Title --}}
                                <h3 class="tix-title">
                                    <a href="{{ route('products.show', $event->slug) }}" title="{{ $event->name }}">{{ $event->name }}</a>
                                </h3>

                                {{-- Creator / Organizer (User Requirement) --}}
                                <div class="tix-organizer" title="Penyelenggara: {{ $organizerName }}">
                                    <span class="tix-organizer-avatar">
                                        @if($organizerAvatar)
                                            <img src="{{ $organizerAvatar }}" alt="{{ $organizerName }}">
                                        @else
                                            {{ strtoupper(substr($organizerName, 0, 1)) }}
                                        @endif
                                    </span>
                                    <span class="tix-organizer-name">{{ $organizerName }}</span>
                                </div>

                                {{-- Date --}}
                                <div class="tix-info-line">
                                    <span class="tix-info-icon">📅</span>
                                    <span>{{ $event->event_date ? $event->event_date->translatedFormat('d F Y') : 'Jadwal Segera Diumumkan' }}</span>
                                </div>

                                {{-- Location --}}
                                <div class="tix-info-line location">
                                    <span class="tix-info-icon">📍</span>
                                    <span>{{ $event->event_location ?: ($event->event_type === 'online' ? 'Online / Webinar' : 'Indonesia') }}</span>
                                </div>

                                {{-- Bottom Row: Price & Lihat Button (Image 2) --}}
                                <div class="tix-footer-row">
                                    <div class="tix-price">
                                        @if($price > 0)
                                            Rp{{ number_format($price, 0, ',', '.') }}
                                        @else
                                            <span style="color:#16a34a;">Gratis</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('products.show', $event->slug) }}" class="tix-btn">
                                        <span>Lihat</span>
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="tix-empty-card">
                            <div style="width:52px; height:52px; border-radius:50%; background:#f1f5f9; display:inline-flex; align-items:center; justify-content:center; color:#94a3b8; margin-bottom:0.75rem;">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/></svg>
                            </div>
                            <h4 style="margin:0 0 0.35rem 0; font-size:1rem; font-weight:700; color:#0f172a;">Belum Ada Tiket Terdekat</h4>
                            <p style="margin:0; font-size:0.8rem; color:#64748b;">Nantikan pengumuman jadwal konser & event menarik lainnya segera!</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- ══ 4. SECTION: EVENT BERAKHIR (100% IDENTIK DENGAN IMAGE 2) ══ --}}
            <section style="margin-bottom: 2.5rem;" id="event-berakhir">
                <div class="tix-section-header">
                    <div class="tix-section-title-wrap">
                        <h2 class="tix-section-title">Event Berakhir</h2>
                    </div>
                    <span style="font-size:0.82rem; font-weight:600; color:#94a3b8;">
                        Arsip Kegiatan & Dokumentasi
                    </span>
                </div>

                <div class="tix-grid">
                    @forelse($pastEvents as $event)
                        @php
                            $imgSrc = $event->featured_image ? asset('storage/' . $event->featured_image) : ($event->image ? asset('storage/' . $event->image) : asset('images/service-default.jpg'));
                            $organizerName = $event->seller ? ($event->seller->creatorProfile->display_name ?? $event->seller->name) : 'Official Organizer';
                            $organizerAvatar = $event->seller?->avatar ? asset('storage/' . $event->seller->avatar) : null;
                            $price = $event->sale_price ?: $event->price;
                        @endphp
                        <div class="tix-card" style="opacity: 0.9;">
                            {{-- Image Container with Past Badge --}}
                            <a href="{{ route('products.show', $event->slug) }}" class="tix-card-media">
                                <img src="{{ $imgSrc }}" alt="{{ $event->name }}" class="tix-card-img" style="filter: grayscale(20%);" loading="lazy">
                                <span class="tix-media-badge past">BERAKHIR</span>
                            </a>

                            <div class="tix-card-body">
                                {{-- Rating & Verified Row --}}
                                <div class="tix-meta-row">
                                    <div class="tix-rating">
                                        <span class="star">★</span>
                                        <span>{{ number_format($event->rating ?: 5.0, 1) }}</span>
                                    </div>
                                    <div class="tix-verified-badge" style="color:#64748b;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span>Selesai</span>
                                    </div>
                                </div>

                                {{-- Event Title --}}
                                <h3 class="tix-title">
                                    <a href="{{ route('products.show', $event->slug) }}" title="{{ $event->name }}">{{ $event->name }}</a>
                                </h3>

                                {{-- Creator / Organizer --}}
                                <div class="tix-organizer">
                                    <span class="tix-organizer-avatar">
                                        @if($organizerAvatar)
                                            <img src="{{ $organizerAvatar }}" alt="{{ $organizerName }}">
                                        @else
                                            {{ strtoupper(substr($organizerName, 0, 1)) }}
                                        @endif
                                    </span>
                                    <span class="tix-organizer-name">{{ $organizerName }}</span>
                                </div>

                                {{-- Date --}}
                                <div class="tix-info-line">
                                    <span class="tix-info-icon">📅</span>
                                    <span>{{ $event->event_date ? $event->event_date->translatedFormat('d F Y') : 'Event Telah Selesai' }}</span>
                                </div>

                                {{-- Location --}}
                                <div class="tix-info-line location">
                                    <span class="tix-info-icon">📍</span>
                                    <span>{{ $event->event_location ?: ($event->event_type === 'online' ? 'Online / Webinar' : 'Indonesia') }}</span>
                                </div>

                                {{-- Bottom Row: Historical Price & Detail Button --}}
                                <div class="tix-footer-row">
                                    <div class="tix-price past">
                                        @if($price > 0)
                                            Rp{{ number_format($price, 0, ',', '.') }}
                                        @else
                                            <span>Gratis</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('products.show', $event->slug) }}" class="tix-btn past">
                                        <span>Detail</span>
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="tix-empty-card">
                            <div style="width:52px; height:52px; border-radius:50%; background:#f1f5f9; display:inline-flex; align-items:center; justify-content:center; color:#94a3b8; margin-bottom:0.75rem;">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </div>
                            <h4 style="margin:0 0 0.35rem 0; font-size:1rem; font-weight:700; color:#0f172a;">Belum Ada Arsip Event Berakhir</h4>
                            <p style="margin:0; font-size:0.8rem; color:#64748b;">Semua tiket event yang terdaftar saat ini masih berstatus aktif dan siap dipesan.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- ══ 5. PROMOTER & CREATOR CTA BANNER ══ --}}
            <div class="tix-cta-box">
                <div>
                    <span style="display:inline-flex; align-items:center; gap:6px; font-size:0.75rem; font-weight:800; background:rgba(34,197,94,0.18); color:#4ade80; padding:4px 12px; border-radius:999px; text-transform:uppercase; margin-bottom:0.75rem;">
                        Penyelenggara Event Official
                    </span>
                    <h3 style="font-size:clamp(1.2rem, 2.5vw, 1.6rem); font-weight:800; color:#ffffff; margin:0 0 0.5rem 0;">
                        Punya Konser, Seminar, atau Event Sendiri?
                    </h3>
                    <p style="font-size:0.85rem; color:#cbd5e1; margin:0; line-height:1.5; max-width:640px;">
                        Jual tiket gratis maupun berbayar secara mandiri di Buyle.id. Nikmati penerbitan e-tiket otomatis via WhatsApp/Email, sistem Gate Scanner panitia live kamera, serta penarikan dana cepat.
                    </p>
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; flex-shrink:0;">
                    <a href="{{ url('/creator/products?from=ticketing') }}" style="display:inline-flex; align-items:center; gap:0.45rem; background:linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); color:#ffffff; font-size:0.85rem; font-weight:800; padding:0.75rem 1.45rem; border-radius:999px; text-decoration:none; box-shadow:0 6px 20px rgba(30,179,73,0.35); white-space:nowrap;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Buat Tiket Event</span>
                    </a>
                    <a href="{{ route('ticketing') }}" style="display:inline-flex; align-items:center; gap:0.45rem; background:rgba(255,255,255,0.08); border:1.5px solid rgba(255,255,255,0.2); color:#ffffff; font-size:0.85rem; font-weight:700; padding:0.75rem 1.25rem; border-radius:999px; text-decoration:none; white-space:nowrap;">
                        <span>Pelajari Fitur</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- ══ JAVASCRIPT CAROUSEL CONTROLLER (SWIPE, AUTOPLAY & ARROWS) ══ --}}
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
            tixAutoPlayTimer = setInterval(tixNextSlide, 6000);
        }

        function tixResetTimer() {
            clearInterval(tixAutoPlayTimer);
            tixStartTimer();
        }

        document.addEventListener('DOMContentLoaded', function () {
            tixStartTimer();

            // Pause on hover
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
        });
    </script>

@endsection

