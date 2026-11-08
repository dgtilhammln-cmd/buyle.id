@extends('layouts.app')

@section('content')

    <style>
        /* ══════════════════════════════════════════════════════════════════════
           BUYLE TIX — DEDICATED TICKETING MARKETPLACE
           Theme: Plus Jakarta Sans & Montserrat, Clean Modern Aesthetic
           100% Exact Match to Creative Agency Reference (Image 2)
        ══════════════════════════════════════════════════════════════════════ */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800&display=swap');

        .buyle-tix-page {
            font-family: 'Plus Jakarta Sans', 'Montserrat', sans-serif !important;
            background: #ffffff;
            min-height: 100vh;
            padding-top: 5.5rem;
            padding-bottom: 5rem;
            color: #0F172A;
        }

        .buyle-tix-page * {
            font-family: 'Plus Jakarta Sans', 'Montserrat', sans-serif;
            box-sizing: border-box;
        }

        .tix-container {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        /* ══════════════════════════════════════════════════════════════════════
           1. HERO DARK CARD (EXACT MATCH TO REFERENCE IMAGE 2)
           Deep Dark Forest Green Ambient Gradient, Non-Bold Sleek Typography,
           Nested 1920x600 Banner Frame
        ══════════════════════════════════════════════════════════════════════ */
        .tix-hero-dark-card {
            background: radial-gradient(circle at 85% 15%, rgba(45, 212, 191, 0.16) 0%, transparent 45%),
                        radial-gradient(circle at 15% 25%, rgba(29, 179, 73, 0.14) 0%, transparent 50%),
                        linear-gradient(165deg, #071412 0%, #0d241f 42%, #05100e 100%);
            border-radius: 32px;
            padding: 3.25rem 3rem 2.75rem;
            margin-bottom: 0;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.18);
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        @media (max-width: 900px) {
            .tix-hero-dark-card {
                padding: 2rem 1.4rem 1.75rem;
                border-radius: 24px;
            }
        }

        @media (max-width: 600px) {
            .tix-hero-dark-card {
                padding: 1.5rem 1rem 1.25rem;
                border-radius: 18px;
            }
        }

        /* Top Row: Left Headline vs Right Desc & Stats */
        .tix-hero-top-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 2.5rem;
            margin-bottom: 2.5rem;
        }

        @media (max-width: 900px) {
            .tix-hero-top-row {
                flex-direction: column;
                gap: 1.25rem;
                margin-bottom: 1.75rem;
            }
        }

        .tix-hero-left {
            flex: 1;
            min-width: 0;
        }

        /* Headline: Clean Regular/Medium Weight, NOT Heavy Bold */
        .tix-hero-heading {
            margin: 0;
            line-height: 1.12;
            color: #ffffff;
            letter-spacing: -0.015em;
        }

        .tix-hero-line1 {
            font-size: clamp(2rem, 4.2vw, 3.5rem);
            font-weight: 400; /* Regular weight matching Image 2 */
            display: block;
            margin-bottom: 0.35rem;
            color: #ffffff;
        }

        .tix-highlight-text {
            color: #2dd4bf;
            font-weight: 500;
        }

        .tix-hero-line2 {
            display: flex;
            align-items: center;
            gap: 1.1rem;
            flex-wrap: wrap;
        }

        .tix-hero-badge-est {
            display: inline-flex;
            flex-direction: column;
            line-height: 1.1;
            color: #94A3B8;
            font-size: 0.68rem;
            font-weight: 400;
            letter-spacing: 0.04em;
        }

        .tix-hero-badge-est strong {
            font-size: 1.15rem;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0;
        }

        .tix-hero-agency-txt {
            font-size: clamp(1.75rem, 3.8vw, 3.2rem);
            font-weight: 600; /* Medium-semi weight, not heavy bold */
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        @media (max-width: 600px) {
            .tix-hero-line1 { font-size: 1.75rem; }
            .tix-hero-agency-txt { font-size: 1.55rem; }
            .tix-hero-badge-est strong { font-size: 0.95rem; }
        }

        /* Right Description & Stats (Clean & Light) */
        .tix-hero-right {
            max-width: 440px;
            display: flex;
            flex-direction: column;
            gap: 1.35rem;
        }

        @media (max-width: 900px) {
            .tix-hero-right {
                max-width: 100%;
                width: 100%;
            }
        }

        .tix-hero-desc {
            font-size: 0.88rem;
            line-height: 1.6;
            color: #cbd5e1;
            margin: 0;
            font-weight: 400;
        }

        .tix-hero-stats-wrap {
            display: flex;
            align-items: center;
            gap: 1.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
        }

        @media (max-width: 600px) {
            .tix-hero-stats-wrap {
                gap: 1rem;
                justify-content: space-between;
            }
        }

        .tix-hero-stat-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .tix-stat-num {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .tix-stat-plus {
            color: #2dd4bf;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .tix-stat-lbl {
            font-size: 0.7rem;
            color: #94A3B8;
            font-weight: 400;
            white-space: nowrap;
        }

        .tix-hero-stat-sep {
            width: 1px;
            height: 28px;
            background: rgba(255, 255, 255, 0.12);
        }

        /* Banner Frame Inside Hero Card (Image 2 Video/Banner Frame) */
        .tix-hero-banner-frame {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            background: #0f172a;
        }

        .tix-carousel-container {
            position: relative;
            width: 100%;
        }

        .tix-carousel-viewport {
            overflow: hidden;
            position: relative;
            width: 100%;
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
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0F172A;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
            z-index: 10;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            outline: none;
        }

        .tix-nav-btn:hover {
            background: #F8FAFC;
            transform: translateY(-50%) scale(1.08);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        .tix-nav-btn.prev { left: 14px; }
        .tix-nav-btn.next { right: 14px; }

        @media (max-width: 768px) {
            .tix-nav-btn {
                width: 32px;
                height: 32px;
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
            position: absolute;
            bottom: 12px;
            left: 0;
            right: 0;
            z-index: 5;
        }

        .tix-dot {
            height: 4px;
            width: 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            padding: 0;
        }

        .tix-dot.active {
            width: 32px;
            background: #2dd4bf;
            box-shadow: 0 0 10px rgba(45, 212, 191, 0.5);
        }

        /* Clean Banner Placeholder Box */
        .tix-banner-placeholder-box {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #071a17 0%, #0d2823 50%, #051412 100%);
            color: #ffffff;
            text-align: center;
            padding: 1.5rem;
            position: relative;
        }

        .tix-ph-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(45, 212, 191, 0.15);
            border: 1.5px solid rgba(45, 212, 191, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.65rem;
            color: #2dd4bf;
        }

        .tix-ph-badge {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #2dd4bf;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .tix-ph-title {
            font-size: clamp(0.95rem, 1.8vw, 1.25rem);
            font-weight: 600;
            margin: 0 0 0.35rem;
            color: #ffffff;
        }

        .tix-ph-sub {
            font-size: 0.78rem;
            color: #94a3b8;
            margin: 0 0 0.75rem;
            max-width: 460px;
            line-height: 1.4;
            font-weight: 400;
        }

        .tix-ph-hint {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            font-weight: 600;
            color: #cbd5e1;
            background: rgba(255, 255, 255, 0.08);
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* ══════════════════════════════════════════════════════════════════════
           2. MEDIA PARTNERS BAR (ON CLEAN WHITE BACKGROUND — 100% IMAGE 2)
           White background, Centered, Gray Logos, Mobile Friendly
        ══════════════════════════════════════════════════════════════════════ */
        .tix-partners-white-section {
            background: #ffffff;
            padding: 2.25rem 1rem 2.25rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .tix-partners-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4rem;
            flex-wrap: wrap;
            width: 100%;
            max-width: 900px;
        }

        @media (max-width: 768px) {
            .tix-partners-row {
                gap: 2rem;
            }
            .tix-partners-white-section {
                padding: 1.5rem 0.75rem 1.75rem;
            }
        }

        .tix-partner-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            opacity: 0.7;
            transition: opacity 0.2s ease, transform 0.2s ease;
            text-decoration: none;
            color: #64748B;
            max-height: 32px;
        }

        .tix-partner-item:hover {
            opacity: 1;
            transform: translateY(-2px);
            color: #0F172A;
        }

        .tix-partner-logo-img {
            max-height: 28px;
            max-width: 120px;
            object-fit: contain;
            filter: grayscale(100%);
            transition: filter 0.2s ease;
        }

        .tix-partner-item:hover .tix-partner-logo-img {
            filter: grayscale(0%);
        }

        .tix-partner-text-brand {
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: #64748B;
            text-transform: capitalize;
        }

        .tix-partner-item:hover .tix-partner-text-brand {
            color: #0F172A;
        }

        /* ══════════════════════════════════════════════════════════════════════
           3. SEARCH & QUICK FILTER BAR
        ══════════════════════════════════════════════════════════════════════ */
        .tix-search-bar-wrap {
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            border-radius: 16px;
            padding: 0.65rem 0.85rem;
            margin-bottom: 2.5rem;
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
            font-weight: 500;
            background: transparent;
        }

        .tix-search-input::placeholder {
            color: #94A3B8;
            font-weight: 400;
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
            font-weight: 600;
            text-decoration: none;
            color: #475569;
            background: #F8FAFC;
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
           4. RESPONSIVE EVENT CARDS GRID (100% HOMEPAGE STYLE)
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
           5. SKELETON SHIMMER & LAZY LOAD STYLES
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
            {{-- Hero Skeleton Box --}}
            <div class="tix-skeleton" style="width: 100%; height: 420px; border-radius: 32px; margin-bottom: 2rem;"></div>

            {{-- 3 Logo Skeleton Bar --}}
            <div style="display: flex; justify-content: center; gap: 3rem; margin-bottom: 2rem;">
                <div class="tix-skeleton" style="width: 100px; height: 24px; border-radius: 6px;"></div>
                <div class="tix-skeleton" style="width: 100px; height: 24px; border-radius: 6px;"></div>
                <div class="tix-skeleton" style="width: 100px; height: 24px; border-radius: 6px;"></div>
            </div>

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

            {{-- ══ 1. HERO DARK CARD (100% IDENTICAL STYLE REFERENSI IMAGE 2) ══ --}}
            <section class="tix-hero-dark-card">

                {{-- Top Row: Heading on Left & Description / Stats on Right --}}
                <div class="tix-hero-top-row">
                    <div class="tix-hero-left">
                        <h1 class="tix-hero-heading">
                            <span class="tix-hero-line1">
                                We Are <span class="tix-highlight-text">Creative</span>
                            </span>
                            <span class="tix-hero-line2">
                                <span class="tix-hero-badge-est">
                                    <span>Establish</span>
                                    <strong>2026</strong>
                                </span>
                                <span class="tix-hero-agency-txt">Digital Ticketing</span>
                            </span>
                        </h1>
                    </div>

                    <div class="tix-hero-right">
                        <p class="tix-hero-desc">
                            we are a premier ticketing marketplace with verified organizers, instant QR code check-in gate, and secure e-tickets for top concerts and events
                        </p>
                        <div class="tix-hero-stats-wrap">
                            <div class="tix-hero-stat-item">
                                <div class="tix-stat-num">
                                    <span>100%</span>
                                    <span class="tix-stat-plus">+</span>
                                </div>
                                <div class="tix-stat-lbl">Official E-Tickets</div>
                            </div>
                            <div class="tix-hero-stat-sep"></div>
                            <div class="tix-hero-stat-item">
                                <div class="tix-stat-num">
                                    <span>24/7</span>
                                    <span class="tix-stat-plus">+</span>
                                </div>
                                <div class="tix-stat-lbl">Gate Scanner Live</div>
                            </div>
                            <div class="tix-hero-stat-sep"></div>
                            <div class="tix-hero-stat-item">
                                <div class="tix-stat-num">
                                    <span>0%</span>
                                    <span class="tix-stat-plus">+</span>
                                </div>
                                <div class="tix-stat-lbl">Hidden Fees</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Center: Nested 1920x600 Pure Banner Frame (Image 2 Style) --}}
                <div class="tix-hero-banner-frame">
                    <div class="tix-carousel-container" id="tixCarousel">
                        <div class="tix-carousel-viewport">
                            <div class="tix-carousel-track" id="tixCarouselTrack">
                                @foreach($banners as $index => $banner)
                                    <a href="{{ $banner['url'] }}" class="tix-slide">
                                        @if(!empty($banner['image']))
                                            {{-- Clean Pure 1920x600 Image (No Crop, No Text Overlaid on Faces) --}}
                                            <img src="{{ $banner['image'] }}" alt="{{ $banner['title'] }}" class="tix-slide-img">
                                        @else
                                            {{-- Clean Modern SVG Landscape Placeholder --}}
                                            <div class="tix-banner-placeholder-box">
                                                <div class="tix-ph-icon">
                                                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/></svg>
                                                </div>
                                                <span class="tix-ph-badge">BANNER BUYLE TIX (1920 X 600)</span>
                                                <h3 class="tix-ph-title">{{ $banner['title'] }}</h3>
                                                <p class="tix-ph-sub">{{ $banner['description'] ?: 'Upload gambar banner 1920x600 Anda di Admin Panel > Manajemen Banner.' }}</p>
                                                <span class="tix-ph-hint">Upload Gambar di Admin Panel</span>
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                            </div>

                            {{-- Prev & Next Nav Buttons --}}
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

                            {{-- Bottom Pill Dash Indicators --}}
                            <div class="tix-carousel-dots" id="tixCarouselDots">
                                @foreach($banners as $index => $banner)
                                    <button type="button" class="tix-dot {{ $index === 0 ? 'active' : '' }}" onclick="tixGoToSlide({{ $index }})" aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            {{-- ══ 2. 3 MEDIA PARTNER LOGOS (ON WHITE BACKGROUND — EXACT MATCH IMAGE 2) ══ --}}
            @php
                $partner1Name = \App\Models\Setting::get('tix_partner_1_name', 'Spotify');
                $partner1Logo = \App\Models\Setting::get('tix_partner_1_logo');
                $partner1Url  = \App\Models\Setting::get('tix_partner_1_url', '#');

                $partner2Name = \App\Models\Setting::get('tix_partner_2_name', 'Google');
                $partner2Logo = \App\Models\Setting::get('tix_partner_2_logo');
                $partner2Url  = \App\Models\Setting::get('tix_partner_2_url', '#');

                $partner3Name = \App\Models\Setting::get('tix_partner_3_name', 'YouTube');
                $partner3Logo = \App\Models\Setting::get('tix_partner_3_logo');
                $partner3Url  = \App\Models\Setting::get('tix_partner_3_url', '#');

                $partners = [
                    ['name' => $partner1Name, 'logo' => $partner1Logo, 'url' => $partner1Url],
                    ['name' => $partner2Name, 'logo' => $partner2Logo, 'url' => $partner2Url],
                    ['name' => $partner3Name, 'logo' => $partner3Logo, 'url' => $partner3Url],
                ];
            @endphp

            <div class="tix-partners-white-section">
                <div class="tix-partners-row">
                    @foreach($partners as $p)
                        <a href="{{ $p['url'] ?: '#' }}" target="{{ $p['url'] && $p['url'] !== '#' ? '_blank' : '_self' }}" class="tix-partner-item" title="{{ $p['name'] }}">
                            @if(!empty($p['logo']))
                                <img src="{{ asset('storage/' . $p['logo']) }}" alt="{{ $p['name'] }}" class="tix-partner-logo-img">
                            @else
                                @if(stripos($p['name'], 'spotify') !== false)
                                    <svg class="tix-partner-svg" height="26" viewBox="0 0 120 36" fill="currentColor">
                                        <path d="M18 0C8.06 0 0 8.06 0 18s8.06 18 18 18 18-8.06 18-18S27.94 0 18 0zm8.26 25.96c-.33.53-.99.7-1.52.37-4.17-2.54-9.42-3.12-15.6-1.71-.6.14-1.2-.24-1.34-.84-.14-.6.24-1.2.84-1.34 6.77-1.54 12.59-.88 17.25 1.99.53.33.7.99.37 1.53zm2.2-4.88c-.41.67-1.29.89-1.96.47-5.26-3.23-13.28-4.17-19.5-2.28-.75.23-1.55-.19-1.78-.94-.23-.75.19-1.55.94-1.78 7.1-2.15 15.93-1.1 21.83 2.57.67.41.89 1.29.47 1.96zm.19-5.12C22.42 12.23 12.16 11.89 6.27 13.68c-.96.29-1.99-.25-2.28-1.21-.29-.96.25-1.99 1.21-2.28 6.79-2.06 18.12-1.66 25.29 2.59.86.51 1.14 1.63.63 2.49-.51.86-1.63 1.14-2.47.69z"/>
                                        <text x="44" y="25" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700" font-size="20" fill="currentColor">Spotify</text>
                                    </svg>
                                @elseif(stripos($p['name'], 'google') !== false)
                                    <svg class="tix-partner-svg" height="24" viewBox="0 0 110 32" fill="currentColor">
                                        <text x="0" y="24" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700" font-size="24" letter-spacing="-0.03em" fill="currentColor">Google</text>
                                    </svg>
                                @elseif(stripos($p['name'], 'youtube') !== false)
                                    <svg class="tix-partner-svg" height="24" viewBox="0 0 130 32" fill="currentColor">
                                        <path d="M31.2 3.5C30.8 2 29.6.8 28.1.4 25.6 0 16 0 16 0S6.4 0 3.9.4C2.4.8 1.2 2 .8 3.5 0 6 0 12.5 0 12.5s0 6.5.8 9c.4 1.5 1.6 2.7 3.1 3.1 2.5.4 12.1.4 12.1.4s9.6 0 12.1-.4c1.5-.4 2.7-1.6 3.1-3.1.8-2.5.8-9 .8-9s0-6.5-.8-9zM12.8 17.8V7.2l9.3 5.3-9.3 5.3z"/>
                                        <text x="38" y="22" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700" font-size="20" letter-spacing="-0.02em" fill="currentColor">YouTube</text>
                                    </svg>
                                @else
                                    <span class="tix-partner-text-brand">{{ $p['name'] }}</span>
                                @endif
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- ══ 3. SEARCH & QUICK FILTER BAR ══ --}}
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

            {{-- ══ 4. SECTION: TIKET EVENT & KONSER (EXACT MATCH HOMEPAGE SECTION) ══ --}}
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

            {{-- ══ 5. SECTION: EVENT BERAKHIR ══ --}}
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

            {{-- ══ 6. PROMOTER & CREATOR CTA BANNER ══ --}}
            <div class="tix-cta-box">
                <div>
                    <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.72rem; font-weight: 700; background: rgba(30, 179, 73, 0.18); color: #4ADE80; padding: 4px 12px; border-radius: 999px; text-transform: uppercase; margin-bottom: 0.75rem;">
                        Penyelenggara Event Official
                    </span>
                    <h3 style="font-size: clamp(1.15rem, 2.5vw, 1.55rem); font-weight: 700; color: #ffffff; margin: 0 0 0.5rem;">
                        Punya Konser, Seminar, atau Event Sendiri?
                    </h3>
                    <p style="font-size: 0.85rem; color: #CBD5E1; margin: 0; line-height: 1.5; max-width: 640px;">
                        Jual tiket gratis maupun berbayar secara mandiri di Buyle.id. Nikmati penerbitan e-tiket otomatis via WhatsApp/Email, sistem Gate Scanner panitia live kamera, serta penarikan dana cepat.
                    </p>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; flex-shrink: 0;">
                    <a href="{{ url('/creator/products?from=ticketing') }}" style="display: inline-flex; align-items: center; gap: 0.45rem; background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); color: #ffffff; font-size: 0.85rem; font-weight: 700; padding: 0.75rem 1.45rem; border-radius: 999px; text-decoration: none; box-shadow: 0 6px 20px rgba(30, 179, 73, 0.35); white-space: nowrap;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        <span>Buat Tiket Event</span>
                    </a>
                    <a href="{{ route('ticketing') }}" style="display: inline-flex; align-items: center; gap: 0.45rem; background: rgba(255, 255, 255, 0.08); border: 1.5px solid rgba(255, 255, 255, 0.2); color: #ffffff; font-size: 0.85rem; font-weight: 600; padding: 0.75rem 1.25rem; border-radius: 999px; text-decoration: none; white-space: nowrap;">
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
