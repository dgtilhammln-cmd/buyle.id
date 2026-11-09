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
            padding-top: 5.75rem;
            padding-bottom: 5rem;
            color: #0F172A;
            overflow-x: hidden;
        }

        @media (max-width: 991px) {
            .buyle-tix-page {
                padding-top: 0.35rem !important; /* Body already has padding-top: 130px !important from mobile navbar */
            }

            .tix-hero-banner-section {
                margin-top: 0 !important;
                margin-bottom: 1.25rem !important;
            }

            .tix-orbit-carousel-wrap {
                padding: 0 !important;
            }
        }

        @media (max-width: 600px) {
            .buyle-tix-page {
                padding-top: 0.25rem !important;
            }

            .tix-hero-banner-section {
                margin-top: 0 !important;
                margin-bottom: 1rem !important;
            }

            .tix-orbit-carousel-wrap {
                padding: 0 !important;
            }
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

        @media (max-width: 600px) {
            .tix-container {
                padding: 0 1rem;
            }
        }

        /* ══════════════════════════════════════════════════════════════════════
                   1. HERO SPLIT SECTION (EXACT MATCH TO REFERENCE IMAGE 2)
                   Top Half: Dark Forest Green Card
                   Bottom Half: Pure White Background with Overlapping Banner & Logos
                ══════════════════════════════════════════════════════════════════════ */
        /* ══════════════════════════════════════════════════════════════════════
           1. 3D ORBIT / STACKED BANNER CAROUSEL (EXACT IMAGE 2 STYLE)
           Centered active card + peeking sibling cards on left & right
        ══════════════════════════════════════════════════════════════════════ */
        .tix-hero-banner-section {
            width: 100%;
            margin-top: 1.25rem;
            margin-bottom: 2rem;
            position: relative;
        }

        .tix-orbit-carousel-wrap {
            position: relative;
            width: 100%;
            overflow: hidden;
            padding: 0.75rem 0 0.5rem;
        }

        .tix-orbit-viewport {
            position: relative;
            width: 100%;
            overflow: visible;
        }

        .tix-orbit-stage {
            position: relative;
            width: 100%;
            aspect-ratio: 1920 / 600;
            perspective: 1200px;
        }

        @media (max-width: 768px) {
            .tix-orbit-stage {
                aspect-ratio: 1920 / 600;
            }
        }

        .tix-orbit-card {
            position: absolute;
            top: 0;
            left: 50%;
            width: 90%;
            max-width: 1200px;
            height: 100%;
            border-radius: 20px;
            overflow: hidden;
            background: #0f172a;
            transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1), opacity 0.5s cubic-bezier(0.25, 1, 0.5, 1), filter 0.5s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.5s ease;
            cursor: pointer;
            user-select: none;
            -webkit-user-select: none;
            will-change: transform, opacity;
        }

        @media (max-width: 768px) {
            .tix-orbit-card {
                width: 92%;
                border-radius: 14px;
            }
        }

        /* Card States (Image 2 Stack Effect) */
        .tix-orbit-card.is-active {
            transform: translateX(-50%) scale(1);
            z-index: 10;
            opacity: 1;
            filter: none;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.18);
            pointer-events: auto;
        }

        .tix-orbit-card.is-prev {
            transform: translateX(calc(-50% - 72%)) scale(0.85);
            z-index: 3;
            opacity: 0.5;
            filter: brightness(0.8);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            pointer-events: auto;
        }

        .tix-orbit-card.is-next {
            transform: translateX(calc(-50% + 72%)) scale(0.85);
            z-index: 3;
            opacity: 0.5;
            filter: brightness(0.8);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            pointer-events: auto;
        }

        .tix-orbit-card.is-hidden {
            transform: translateX(-50%) scale(0.75);
            z-index: 1;
            opacity: 0;
            pointer-events: none;
        }

        /* Single Banner Mode (When count <= 1: Polosan, Balanced & Centered, Exact 1920x600 Ratio) */
        .tix-orbit-carousel-wrap.single-banner {
            padding: 0;
        }

        .tix-orbit-carousel-wrap.single-banner .tix-orbit-stage {
            max-width: 1080px;
            margin: 0 auto;
            padding: 0 1.25rem;
            aspect-ratio: 1920 / 600;
            perspective: none;
        }

        .tix-orbit-carousel-wrap.single-banner .tix-orbit-card {
            width: 100% !important;
            max-width: 100% !important;
            position: relative !important;
            left: 0 !important;
            top: 0 !important;
            transform: none !important;
            border-radius: 20px;
            box-shadow: 0 14px 38px rgba(0, 0, 0, 0.12) !important;
            opacity: 1 !important;
            filter: none !important;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .tix-orbit-carousel-wrap.single-banner .tix-orbit-stage {
                padding: 0 1rem;
                aspect-ratio: 1920 / 600;
            }

            .tix-orbit-carousel-wrap.single-banner .tix-orbit-card {
                border-radius: 14px;
            }
        }

        .tix-orbit-card-inner {
            display: block;
            width: 100%;
            height: 100%;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .tix-orbit-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center;
            display: block;
        }

        /* Nav Circular Prev / Next Buttons */
        .tix-orbit-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.18);
            z-index: 20;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), background 0.2s ease, box-shadow 0.2s ease;
            outline: none;
            padding: 0;
        }

        .tix-orbit-btn:hover {
            background: #f8fafc;
            transform: translateY(-50%) scale(1.08);
            box-shadow: 0 8px 26px rgba(0, 0, 0, 0.24);
        }

        .tix-orbit-btn.prev {
            left: calc(50% - (min(78vw, 1080px) / 2) + 16px);
        }

        .tix-orbit-btn.next {
            right: calc(50% - (min(78vw, 1080px) / 2) + 16px);
        }

        @media (max-width: 768px) {
            .tix-orbit-btn {
                width: 34px;
                height: 34px;
            }

            .tix-orbit-btn.prev {
                left: calc(50% - 43% + 8px);
            }

            .tix-orbit-btn.next {
                right: calc(50% - 43% + 8px);
            }
        }

        /* Orbit Dots Indicator */
        .tix-orbit-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 16px;
            margin-bottom: 0.25rem;
        }

        .tix-orbit-dot {
            height: 7px;
            width: 7px;
            border-radius: 50%;
            background: #cbd5e1;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            border: none;
            padding: 0;
        }

        .tix-orbit-dot.active {
            width: 26px;
            border-radius: 999px;
            background: #1eb349;
            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.35);
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
            background: transparent;
            border: none;
            padding-top: 1.75rem;
            padding-bottom: 2.25rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @media (max-width: 900px) {
            .tix-partners-white-section {
                padding-top: 1.5rem;
                padding-bottom: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .tix-partners-white-section {
                padding-top: 1rem;
                padding-bottom: 1.15rem;
            }
        }

        .tix-partners-row {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 2.25rem;
            flex-wrap: wrap;
            max-width: 100%;
        }

        .tix-partners-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .tix-partners-logos {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 3.5rem;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .tix-partners-row {
                gap: 1.25rem;
            }

            .tix-partners-logos {
                gap: 2rem;
            }

            .tix-partners-label {
                font-size: 0.7rem;
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

        @media (max-width: 600px) {
            .tix-search-bar-wrap {
                padding: 0.5rem 0.65rem;
                gap: 0.5rem;
                margin-bottom: 1.75rem;
            }

            .tix-filter-chips {
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                width: 100%;
                gap: 0.35rem;
                padding-bottom: 2px;
            }

            .tix-filter-chips::-webkit-scrollbar {
                display: none;
            }

            .tix-chip {
                padding: 0.28rem 0.65rem;
                font-size: 0.72rem;
                white-space: nowrap;
                flex-shrink: 0;
                gap: 4px;
            }

            .tix-chip svg {
                width: 12px;
                height: 12px;
            }
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
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
            line-height: 1.4;
            outline: none;
        }

        .tix-chip:hover {
            background: #E2E8F0;
            color: #0F172A;
        }

        .tix-chip.active {
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
        }

        .tix-chip.active:hover {
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(30, 179, 73, 0.45);
        }

        /* Section Header & Title / Reset Link */
        .tix-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .tix-section-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .tix-reset-btn {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1eb349;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .tix-section-sub {
            font-size: 0.82rem;
            font-weight: 600;
            color: #94A3B8;
            white-space: nowrap;
            flex-shrink: 0;
        }

        @media (max-width: 600px) {
            .tix-section-header {
                gap: 0.5rem;
                margin-bottom: 1rem;
            }

            .tix-section-title {
                font-size: 0.95rem;
                gap: 0.3rem;
            }

            .tix-reset-btn {
                font-size: 0.7rem;
                gap: 0.2rem;
            }

            .tix-reset-btn svg {
                width: 12px;
                height: 12px;
            }

            .tix-section-sub {
                font-size: 0.68rem;
            }
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
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .tix-skeleton {
            background: linear-gradient(90deg, #f1f5f9 0%, #e2e8f0 50%, #f1f5f9 100%);
            background-size: 200% 100%;
            animation: tixShimmer 1.5s infinite;
            border-radius: 10px;
        }

        .tix-skeleton-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 0.75rem;
            display: flex;
            flex-direction: column;
            pointer-events: none;
        }

        /* 3D Orbit Carousel Skeleton (Matching Latest Coverflow Layout) */
        .tix-orbit-skel-wrap {
            position: relative;
            width: 100%;
            overflow: hidden;
            padding: 0.75rem 0 0.5rem;
        }

        .tix-orbit-skel-stage {
            position: relative;
            width: 100%;
            aspect-ratio: 1920 / 600;
        }

        @media (max-width: 768px) {
            .tix-orbit-skel-stage {
                aspect-ratio: 1920 / 600;
            }
        }

        .tix-orbit-skel-card {
            position: absolute;
            top: 0;
            left: 50%;
            width: 78%;
            max-width: 1080px;
            height: 100%;
            border-radius: 20px;
        }

        @media (max-width: 768px) {
            .tix-orbit-skel-card {
                width: 86%;
                border-radius: 14px;
            }
        }

        .tix-orbit-skel-card.active {
            transform: translateX(-50%) scale(1);
            z-index: 2;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.06);
        }

        .tix-orbit-skel-card.prev {
            transform: translateX(calc(-50% - 58%)) scale(0.88);
            z-index: 1;
            opacity: 0.4;
        }

        .tix-orbit-skel-card.next {
            transform: translateX(calc(-50% + 58%)) scale(0.88);
            z-index: 1;
            opacity: 0.4;
        }

        .tix-orbit-skel-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 16px;
            margin-bottom: 0.25rem;
        }

        /* Promoter Footer Box (Light Theme, Montserrat Not Gendut, Mobile Friendly) */
        .tix-cta-box {
            background: linear-gradient(135deg, #F0FDF4 0%, #FFFFFF 45%, #F8FAFC 100%);
            border: 1.5px solid #E2E8F0;
            border-radius: 24px;
            padding: 2.25rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            color: #0F172A;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03), 0 2px 8px rgba(30, 179, 73, 0.04);
            margin-top: 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
            font-family: 'Montserrat', sans-serif !important;
        }

        .tix-cta-content {
            flex: 1;
            min-width: 0;
        }

        .tix-cta-title {
            font-family: 'Montserrat', sans-serif !important;
            font-size: clamp(1.15rem, 2.2vw, 1.45rem);
            font-weight: 600 !important;
            letter-spacing: -0.015em;
            color: #0F172A;
            margin: 0 0 0.45rem;
            line-height: 1.35;
        }

        .tix-cta-desc {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.85rem;
            font-weight: 400 !important;
            color: #64748B;
            margin: 0;
            line-height: 1.6;
            max-width: 650px;
        }

        .tix-cta-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
            flex-wrap: wrap;
        }

        .tix-btn-join {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            background: linear-gradient(135deg, #1eb349 0%, #16a34a 100%);
            color: #ffffff !important;
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.85rem;
            font-weight: 600 !important;
            padding: 0.78rem 1.65rem;
            border-radius: 999px;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(30, 179, 73, 0.32);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
            border: 1px solid transparent;
        }

        .tix-btn-join:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(30, 179, 73, 0.42);
            color: #ffffff !important;
        }

        .tix-btn-learn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            background: #ffffff;
            border: 1.5px solid #CBD5E1;
            color: #334155 !important;
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.85rem;
            font-weight: 500 !important;
            padding: 0.75rem 1.35rem;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .tix-btn-learn:hover {
            border-color: #1eb349;
            color: #1eb349 !important;
            background: #F0FDF4;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .tix-cta-box {
                flex-direction: column;
                align-items: flex-start;
                padding: 1.5rem 1.25rem;
                gap: 1.25rem;
                border-radius: 18px;
            }

            .tix-cta-actions {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 0.65rem;
            }

            .tix-btn-join,
            .tix-btn-learn {
                width: 100%;
                padding: 0.75rem 1.25rem;
                text-align: center;
            }
        }
    </style>

    <div class="buyle-tix-page">

        {{-- ══ SKELETON SHIMMER OVERLAY (Smooth Initial Render) ══ --}}
        <div id="tixInitialSkeleton">
            {{-- 3D Carousel Orbit Skeleton (Center card + Left & Right peek cards) --}}
            <div class="tix-orbit-skel-wrap">
                <div class="tix-orbit-skel-stage">
                    <div class="tix-orbit-skel-card prev tix-skeleton"></div>
                    <div class="tix-orbit-skel-card active tix-skeleton"></div>
                    <div class="tix-orbit-skel-card next tix-skeleton"></div>
                </div>
                {{-- Skeleton Dots --}}
                <div class="tix-orbit-skel-dots">
                    <div class="tix-skeleton" style="width: 24px; height: 7px; border-radius: 999px;"></div>
                    <div class="tix-skeleton" style="width: 7px; height: 7px; border-radius: 50%;"></div>
                    <div class="tix-skeleton" style="width: 7px; height: 7px; border-radius: 50%;"></div>
                    <div class="tix-skeleton" style="width: 7px; height: 7px; border-radius: 50%;"></div>
                </div>
            </div>

            <div class="tix-container">
                {{-- Media Partner Skeleton --}}
            <div style="display: flex; justify-content: center; align-items: center; gap: 2rem; margin: 1.5rem 0 2rem; flex-wrap: wrap;">
                <div class="tix-skeleton" style="width: 140px; height: 16px; border-radius: 6px;"></div>
                <div style="display: flex; gap: 2rem; align-items: center;">
                    <div class="tix-skeleton" style="width: 90px; height: 26px; border-radius: 6px;"></div>
                    <div class="tix-skeleton" style="width: 80px; height: 24px; border-radius: 6px;"></div>
                    <div class="tix-skeleton" style="width: 95px; height: 24px; border-radius: 6px;"></div>
                </div>
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
                        <div class="tix-skeleton"
                            style="width: 100%; aspect-ratio: 16/9; border-radius: 12px; margin-bottom: 0.85rem;"></div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <div class="tix-skeleton" style="width: 45px; height: 16px; border-radius: 4px;"></div>
                            <div class="tix-skeleton" style="width: 65px; height: 16px; border-radius: 4px;"></div>
                        </div>
                        <div class="tix-skeleton" style="width: 88%; height: 18px; margin-bottom: 0.35rem; border-radius: 6px;">
                        </div>
                        <div class="tix-skeleton" style="width: 60%; height: 16px; margin-bottom: 0.6rem; border-radius: 6px;">
                        </div>
                        <div class="tix-skeleton"
                            style="width: 110px; height: 14px; margin-bottom: 0.3rem; border-radius: 4px;"></div>
                        <div class="tix-skeleton" style="width: 90px; height: 14px; margin-bottom: 0.8rem; border-radius: 4px;">
                        </div>
                        <div
                            style="border-top: 1px solid #F1F5F9; padding-top: 0.65rem; display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                            <div class="tix-skeleton" style="width: 70px; height: 20px; border-radius: 6px;"></div>
                            <div class="tix-skeleton" style="width: 75px; height: 28px; border-radius: 99px;"></div>
                        </div>
                    </div>
                @endfor
            </div>
            </div>
        </div>

        {{-- ══ REAL CONTENT CONTAINER ══ --}}
        <div id="tixRealContent" style="display: none; opacity: 0; transition: opacity 0.3s ease;">

            {{-- ══ 1. 3D ORBIT / STACKED BANNER SECTION (EXACT IMAGE 2 STYLE) ══ --}}
            <div class="tix-hero-banner-section">
                <div class="tix-orbit-carousel-wrap {{ count($banners) <= 1 ? 'single-banner' : '' }}" id="tixOrbitCarousel">
                    <div class="tix-orbit-viewport">
                        <div class="tix-orbit-stage" id="tixOrbitStage">
                            @foreach($banners as $index => $banner)
                                <div class="tix-orbit-card {{ $index === 0 ? 'is-active' : ($index === 1 ? 'is-next' : ($index === count($banners) - 1 ? 'is-prev' : 'is-hidden')) }}" data-index="{{ $index }}">
                                    <a href="{{ $banner['url'] }}" class="tix-orbit-card-inner">
                                        @if(!empty($banner['image']))
                                            {{-- Clean Pure 1920x600 Image (No Text Overlaid on Faces) --}}
                                            <img src="{{ $banner['image'] }}" alt="{{ $banner['title'] }}" class="tix-orbit-img" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                        @else
                                            {{-- Clean Modern SVG Landscape Placeholder --}}
                                            <div class="tix-banner-placeholder-box">
                                                <div class="tix-ph-icon">
                                                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" />
                                                    </svg>
                                                </div>
                                                <span class="tix-ph-badge">BANNER BUYLE TIX (1920 X 600)</span>
                                                <h3 class="tix-ph-title">{{ $banner['title'] }}</h3>
                                                <p class="tix-ph-sub">
                                                    {{ $banner['description'] ?: 'Upload gambar banner 1920x600 Anda di Admin Panel > Manajemen Banner.' }}
                                                </p>
                                                <span class="tix-ph-hint">Upload Gambar di Admin Panel</span>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        @if(count($banners) > 1)
                            {{-- Circular Navigation Buttons (Only shown if banners > 1) --}}
                            <button type="button" class="tix-orbit-btn prev" onclick="tixPrevOrbitSlide()" aria-label="Slide Sebelumnya">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </button>
                            <button type="button" class="tix-orbit-btn next" onclick="tixNextOrbitSlide()" aria-label="Slide Selanjutnya">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </button>
                        @endif
                    </div>

                    @if(count($banners) > 1)
                        {{-- Orbit Dots Indicator (Only shown if banners > 1) --}}
                        <div class="tix-orbit-dots" id="tixOrbitDots">
                            @foreach($banners as $index => $banner)
                                <button type="button" class="tix-orbit-dot {{ $index === 0 ? 'active' : '' }}"
                                    onclick="tixGoToOrbitSlide({{ $index }})" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="tix-container">
                {{-- ══ 2. OFFICIAL MEDIA PARTNERS ROW (Directly follows Banner) ══ --}}
            @php
                $partner1Name = \App\Models\Setting::get('tix_partner_1_name', 'Spotify');
                $partner1Logo = \App\Models\Setting::get('tix_partner_1_logo');
                $partner1Url = \App\Models\Setting::get('tix_partner_1_url', '#');

                $partner2Name = \App\Models\Setting::get('tix_partner_2_name', 'Google');
                $partner2Logo = \App\Models\Setting::get('tix_partner_2_logo');
                $partner2Url = \App\Models\Setting::get('tix_partner_2_url', '#');

                $partner3Name = \App\Models\Setting::get('tix_partner_3_name', 'YouTube');
                $partner3Logo = \App\Models\Setting::get('tix_partner_3_logo');
                $partner3Url = \App\Models\Setting::get('tix_partner_3_url', '#');

                $partners = [
                    ['name' => $partner1Name, 'logo' => $partner1Logo, 'url' => $partner1Url],
                    ['name' => $partner2Name, 'logo' => $partner2Logo, 'url' => $partner2Url],
                    ['name' => $partner3Name, 'logo' => $partner3Logo, 'url' => $partner3Url],
                ];
            @endphp

            <div class="tix-partners-white-section">
                <div class="tix-partners-row">
                    <span class="tix-partners-label">Official Media Partner:</span>
                    <div class="tix-partners-logos">
                        @foreach($partners as $p)
                            <a href="{{ $p['url'] ?: '#' }}" target="{{ $p['url'] && $p['url'] !== '#' ? '_blank' : '_self' }}"
                                class="tix-partner-item" title="{{ $p['name'] }}">
                                @if(!empty($p['logo']))
                                    <img src="{{ asset('storage/' . $p['logo']) }}" alt="{{ $p['name'] }}"
                                        class="tix-partner-logo-img">
                                @else
                                    @if(stripos($p['name'], 'spotify') !== false)
                                        <svg class="tix-partner-svg" height="26" viewBox="0 0 120 36" fill="currentColor">
                                            <path
                                                d="M18 0C8.06 0 0 8.06 0 18s8.06 18 18 18 18-8.06 18-18S27.94 0 18 0zm8.26 25.96c-.33.53-.99.7-1.52.37-4.17-2.54-9.42-3.12-15.6-1.71-.6.14-1.2-.24-1.34-.84-.14-.6.24-1.2.84-1.34 6.77-1.54 12.59-.88 17.25 1.99.53.33.7.99.37 1.53zm2.2-4.88c-.41.67-1.29.89-1.96.47-5.26-3.23-13.28-4.17-19.5-2.28-.75.23-1.55-.19-1.78-.94-.23-.75.19-1.55.94-1.78 7.1-2.15 15.93-1.1 21.83 2.57.67.41.89 1.29.47 1.96zm.19-5.12C22.42 12.23 12.16 11.89 6.27 13.68c-.96.29-1.99-.25-2.28-1.21-.29-.96.25-1.99 1.21-2.28 6.79-2.06 18.12-1.66 25.29 2.59.86.51 1.14 1.63.63 2.49-.51.86-1.63 1.14-2.47.69z" />
                                            <text x="44" y="25" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700"
                                                font-size="20" fill="currentColor">Spotify</text>
                                        </svg>
                                    @elseif(stripos($p['name'], 'google') !== false)
                                        <svg class="tix-partner-svg" height="24" viewBox="0 0 110 32" fill="currentColor">
                                            <text x="0" y="24" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700"
                                                font-size="24" letter-spacing="-0.03em" fill="currentColor">Google</text>
                                        </svg>
                                    @elseif(stripos($p['name'], 'youtube') !== false)
                                        <svg class="tix-partner-svg" height="24" viewBox="0 0 130 32" fill="currentColor">
                                            <path
                                                d="M31.2 3.5C30.8 2 29.6.8 28.1.4 25.6 0 16 0 16 0S6.4 0 3.9.4C2.4.8 1.2 2 .8 3.5 0 6 0 12.5 0 12.5s0 6.5.8 9c.4 1.5 1.6 2.7 3.1 3.1 2.5.4 12.1.4 12.1.4s9.6 0 12.1-.4c1.5-.4 2.7-1.6 3.1-3.1.8-2.5.8-9 .8-9s0-6.5-.8-9zM12.8 17.8V7.2l9.3 5.3-9.3 5.3z" />
                                            <text x="38" y="22" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700"
                                                font-size="20" letter-spacing="-0.02em" fill="currentColor">YouTube</text>
                                        </svg>
                                    @else
                                        <span class="tix-partner-text-brand">{{ $p['name'] }}</span>
                                    @endif
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ══ 3. SEARCH & QUICK FILTER BAR (REALTIME AJAX, NO URL PARAMS) ══ --}}
            <div class="tix-search-bar-wrap">
                <form id="tixSearchForm" action="javascript:void(0)" class="tix-search-form" onsubmit="return false;">
                    <svg width="18" height="18" fill="none" stroke="#64748B" stroke-width="2.2" viewBox="0 0 24 24"
                        style="flex-shrink: 0;">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="tixSearchInput" name="q" value=""
                        placeholder="Cari nama konser, seminar, festival, kota atau artis..." class="tix-search-input"
                        autocomplete="off">
                    <button type="button" id="tixSearchClearBtn"
                        style="display: none; background: none; border: none; cursor: pointer; color: #94A3B8; padding: 4px; align-items: center;"
                        onclick="tixClearSearch()" title="Hapus pencarian">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </form>

                <div class="tix-filter-chips">
                    <button type="button" class="tix-chip active" data-type="" onclick="tixFilterType('')">
                        Semua Event
                    </button>
                    <button type="button" class="tix-chip" data-type="offline" onclick="tixFilterType('offline')">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"
                            style="flex-shrink: 0;">
                            <path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span>Event Offline</span>
                    </button>
                    <button type="button" class="tix-chip" data-type="online" onclick="tixFilterType('online')">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"
                            style="flex-shrink: 0;">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                        <span>Webinar / Online</span>
                    </button>
                </div>
            </div>

            {{-- ══ 4. SECTION: TIKET EVENT & KONSER (DYNAMIC REALTIME RESULTS) ══ --}}
            <section style="margin-bottom: 2.75rem;" id="event-terdekat">
                <div class="tix-section-header">
                    <h2 id="tixSectionTitle" class="tix-section-title">
                        Tiket Event & Konser
                    </h2>
                    <a href="javascript:void(0)" id="tixResetBtn" onclick="tixResetAll()" class="tix-reset-btn">
                        <span id="tixResetBtnText">Lihat Semua Tiket</span>
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path d="M5 12h14m-7-7l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                {{-- Cards Grid Container --}}
                <div class="tix-grid" id="upcomingGrid" style="transition: opacity 0.2s ease;">
                    @if(count($upcomingEvents) > 0)
                        @include('tix.partials.event_cards', ['events' => $upcomingEvents, 'is_past' => false])
                    @else
                        <div class="tix-empty-card">
                            <div
                                style="width: 52px; height: 52px; border-radius: 50%; background: #F1F5F9; display: inline-flex; align-items: center; justify-content: center; color: #94A3B8; margin-bottom: 0.75rem;">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" />
                                </svg>
                            </div>
                            <h4 style="margin: 0 0 0.35rem; font-size: 1rem; font-weight: 700; color: #0F172A;">Belum Ada Tiket
                                Terdekat</h4>
                            <p style="margin: 0; font-size: 0.8rem; color: #64748B;">Nantikan pengumuman jadwal konser & event
                                menarik lainnya segera!</p>
                        </div>
                    @endif
                </div>

                {{-- Infinite Scroll Skeleton Placeholders (4 cards matching grid) --}}
                <div class="tix-grid" id="infiniteScrollSkeleton"
                    style="display: none; margin-top: -1.75rem; margin-bottom: 1.5rem;">
                    @for($i = 0; $i < 4; $i++)
                        <div class="tix-skeleton-card">
                            <div class="tix-skeleton"
                                style="width: 100%; aspect-ratio: 16/9; border-radius: 12px; margin-bottom: 0.85rem;"></div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <div class="tix-skeleton" style="width: 45px; height: 16px; border-radius: 4px;"></div>
                                <div class="tix-skeleton" style="width: 65px; height: 16px; border-radius: 4px;"></div>
                            </div>
                            <div class="tix-skeleton"
                                style="width: 88%; height: 18px; margin-bottom: 0.35rem; border-radius: 6px;"></div>
                            <div class="tix-skeleton"
                                style="width: 60%; height: 16px; margin-bottom: 0.6rem; border-radius: 6px;"></div>
                            <div class="tix-skeleton"
                                style="width: 110px; height: 14px; margin-bottom: 0.3rem; border-radius: 4px;"></div>
                            <div class="tix-skeleton"
                                style="width: 90px; height: 14px; margin-bottom: 0.8rem; border-radius: 4px;"></div>
                            <div
                                style="border-top: 1px solid #F1F5F9; padding-top: 0.65rem; display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                                <div class="tix-skeleton" style="width: 70px; height: 20px; border-radius: 6px;"></div>
                                <div class="tix-skeleton" style="width: 75px; height: 28px; border-radius: 99px;"></div>
                            </div>
                        </div>
                    @endfor
                </div>

                {{-- Infinite Scroll Sentinel and Status Feedback --}}
                <div id="infiniteScrollSentinel" style="height: 20px;"></div>
                <div id="infiniteScrollStatus"
                    style="text-align: center; padding: 0.5rem 0 1.5rem; font-size: 0.84rem; font-weight: 700; color: #94A3B8; display: none;">
                    <span id="infiniteScrollStatusText"></span>
                </div>
            </section>

            {{-- ══ 5. SECTION: EVENT BERAKHIR (Hanya tampil jika ada event berakhir) ══ --}}
            @if(count($pastEvents) > 0)
                <section style="margin-bottom: 2.75rem;" id="event-berakhir">
                    <div class="tix-section-header">
                        <div>
                            <h2 class="tix-section-title">
                                Event Berakhir
                            </h2>
                        </div>
                    </div>

                    <div class="tix-grid">
                        @include('tix.partials.event_cards', ['events' => $pastEvents, 'is_past' => true])
                    </div>
                </section>
            @endif

            {{-- ══ 6. PROMOTER & CREATOR CTA BANNER (LIGHT THEME) ══ --}}
            <div class="tix-cta-box">
                <div class="tix-cta-content">
                    <h3 class="tix-cta-title">
                        Punya Konser, Seminar, atau Event Sendiri?
                    </h3>
                    <p class="tix-cta-desc">
                        Jual tiket gratis maupun berbayar secara mandiri di Buyle.id. Nikmati penerbitan e-tiket otomatis
                        via WhatsApp/Email, sistem Gate Scanner panitia live kamera, serta penarikan dana cepat.
                    </p>
                </div>
                <div class="tix-cta-actions">
                    <a href="{{ url('/register') }}" class="tix-btn-join">
                        <span>Join Now</span>
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                    <a href="{{ route('ticketing') }}" class="tix-btn-learn">
                        <span>Pelajari Fitur</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

    {{-- ══ JAVASCRIPT CONTROLLERS: CAROUSEL, SKELETON TRANSITION, & INFINITE SCROLL ══ --}}
    <script>
        let currentOrbitSlide = 0;
        const orbitSlideCount = {{ count($banners) }};
        let orbitAutoPlayTimer = null;

        function tixUpdateOrbitCarousel() {
            const cards = document.querySelectorAll('.tix-orbit-card');
            if (!cards.length) return;

            if (orbitSlideCount <= 1) {
                cards[0]?.classList.add('is-active');
                return;
            }

            cards.forEach((card, idx) => {
                card.classList.remove('is-active', 'is-prev', 'is-next', 'is-hidden');
                if (idx === currentOrbitSlide) {
                    card.classList.add('is-active');
                } else if (idx === (currentOrbitSlide - 1 + orbitSlideCount) % orbitSlideCount) {
                    card.classList.add('is-prev');
                } else if (idx === (currentOrbitSlide + 1) % orbitSlideCount) {
                    card.classList.add('is-next');
                } else {
                    card.classList.add('is-hidden');
                }
            });

            const dots = document.querySelectorAll('#tixOrbitDots .tix-orbit-dot');
            dots.forEach((dot, idx) => {
                if (idx === currentOrbitSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        function tixNextOrbitSlide() {
            if (orbitSlideCount <= 1) return;
            currentOrbitSlide = (currentOrbitSlide + 1) % orbitSlideCount;
            tixUpdateOrbitCarousel();
            tixResetOrbitTimer();
        }

        function tixPrevOrbitSlide() {
            if (orbitSlideCount <= 1) return;
            currentOrbitSlide = (currentOrbitSlide - 1 + orbitSlideCount) % orbitSlideCount;
            tixUpdateOrbitCarousel();
            tixResetOrbitTimer();
        }

        function tixGoToOrbitSlide(index) {
            currentOrbitSlide = index;
            tixUpdateOrbitCarousel();
            tixResetOrbitTimer();
        }

        function tixStartOrbitTimer() {
            if (orbitSlideCount > 1) {
                orbitAutoPlayTimer = setInterval(tixNextOrbitSlide, 5000);
            }
        }

        function tixResetOrbitTimer() {
            clearInterval(orbitAutoPlayTimer);
            tixStartOrbitTimer();
        }

        // Infinite Scroll & Realtime Search Setup
        let tixCurrentPage = 1;
        let tixHasMore = {{ $hasMoreUpcoming ? 'true' : 'false' }};
        let tixIsLoading = false;
        let tixSearchQuery = '';
        let tixEventType = '';
        let tixSearchTimeout = null;

        function escapeHtml(str) {
            return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function tixTriggerSearch(resetPage = true) {
            if (resetPage) {
                tixCurrentPage = 1;
                tixHasMore = true;
            }

            const grid = document.getElementById('upcomingGrid');
            const skeleton = document.getElementById('infiniteScrollSkeleton');
            const statusBox = document.getElementById('infiniteScrollStatus');
            const statusText = document.getElementById('infiniteScrollStatusText');
            const titleEl = document.getElementById('tixSectionTitle');
            const resetBtnText = document.getElementById('tixResetBtnText');
            const pastSection = document.getElementById('event-berakhir');
            const clearBtn = document.getElementById('tixSearchClearBtn');

            // Toggle clear button inside input
            if (clearBtn) {
                clearBtn.style.display = tixSearchQuery ? 'inline-flex' : 'none';
            }

            // Realtime Section Title & UI State Switching
            if (titleEl) {
                if (tixSearchQuery.trim().length > 0) {
                    titleEl.innerHTML = `Hasil Pencarian: <span style="color: #1eb349; font-weight: 700; margin-left: 4px;">"${escapeHtml(tixSearchQuery)}"</span>`;
                    if (resetBtnText) resetBtnText.textContent = 'Reset Pencarian';
                    if (pastSection) pastSection.style.display = 'none';
                } else if (tixEventType === 'offline') {
                    titleEl.textContent = 'Tiket Event Offline';
                    if (resetBtnText) resetBtnText.textContent = 'Lihat Semua Tiket';
                    if (pastSection) pastSection.style.display = 'none';
                } else if (tixEventType === 'online') {
                    titleEl.textContent = 'Tiket Webinar / Online';
                    if (resetBtnText) resetBtnText.textContent = 'Lihat Semua Tiket';
                    if (pastSection) pastSection.style.display = 'none';
                } else {
                    titleEl.textContent = 'Tiket Event & Konser';
                    if (resetBtnText) resetBtnText.textContent = 'Lihat Semua Tiket';
                    if (pastSection) pastSection.style.display = 'block';
                }
            }

            // Build request URL without affecting the browser URL
            let url = new URL('{{ route("tix.index") }}', window.location.origin);
            url.searchParams.set('page', tixCurrentPage);
            if (tixSearchQuery) url.searchParams.set('q', tixSearchQuery);
            if (tixEventType) url.searchParams.set('type', tixEventType);

            tixIsLoading = true;
            if (resetPage && grid) {
                grid.style.opacity = '0.55';
            }

            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (resetPage && grid) {
                        grid.innerHTML = '';
                        grid.style.opacity = '1';
                    }

                    if (data.html && data.html.trim().length > 0) {
                        if (grid) {
                            grid.insertAdjacentHTML('beforeend', data.html);
                        }
                        tixCurrentPage = data.current_page;
                        tixHasMore = data.has_more;
                    } else if (resetPage) {
                        // Clean Empty State
                        tixHasMore = false;
                        if (grid) {
                            grid.innerHTML = `
                                    <div class="tix-empty-card">
                                        <div style="width: 52px; height: 52px; border-radius: 50%; background: #F1F5F9; display: inline-flex; align-items: center; justify-content: center; color: #94A3B8; margin-bottom: 0.75rem;">
                                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        </div>
                                        <h4 style="margin: 0 0 0.35rem; font-size: 1rem; font-weight: 700; color: #0F172A;">Tidak Ada Tiket Ditemukan</h4>
                                        <p style="margin: 0; font-size: 0.8rem; color: #64748B;">Coba kata kunci lain atau klik kategori Semua Event.</p>
                                    </div>
                                `;
                        }
                    } else {
                        tixHasMore = false;
                    }

                    if (!tixHasMore && statusBox && statusText && tixCurrentPage > 1) {
                        statusText.textContent = 'Semua tiket event telah dimuat ✨';
                        statusBox.style.display = 'block';
                    } else if (statusBox) {
                        statusBox.style.display = 'none';
                    }
                })
                .catch(err => {
                    console.error('Error in tix search:', err);
                    if (grid) grid.style.opacity = '1';
                })
                .finally(() => {
                    tixIsLoading = false;
                    if (skeleton) skeleton.style.display = 'none';
                });
        }

        function tixFilterType(type) {
            tixEventType = type;
            document.querySelectorAll('.tix-filter-chips .tix-chip').forEach(chip => {
                if (chip.getAttribute('data-type') === type) {
                    chip.classList.add('active');
                } else {
                    chip.classList.remove('active');
                }
            });
            tixTriggerSearch(true);
        }

        function tixClearSearch() {
            const input = document.getElementById('tixSearchInput');
            if (input) input.value = '';
            tixSearchQuery = '';
            tixTriggerSearch(true);
        }

        function tixResetAll() {
            const input = document.getElementById('tixSearchInput');
            if (input) input.value = '';
            tixSearchQuery = '';
            tixEventType = '';
            document.querySelectorAll('.tix-filter-chips .tix-chip').forEach(chip => {
                if (chip.getAttribute('data-type') === '') {
                    chip.classList.add('active');
                } else {
                    chip.classList.remove('active');
                }
            });
            tixTriggerSearch(true);
        }

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
            setTimeout(function () {
                const skeleton = document.getElementById('tixInitialSkeleton');
                const content = document.getElementById('tixRealContent');
                if (skeleton && content) {
                    skeleton.style.display = 'none';
                    content.style.display = 'block';
                    setTimeout(() => { content.style.opacity = '1'; }, 20);
                }
            }, 250);

            // Start 3D Orbit Carousel
            tixUpdateOrbitCarousel();
            tixStartOrbitTimer();

            const carousel = document.getElementById('tixOrbitCarousel');
            if (carousel && orbitSlideCount > 1) {
                carousel.addEventListener('mouseenter', () => clearInterval(orbitAutoPlayTimer));
                carousel.addEventListener('mouseleave', () => tixStartOrbitTimer());
            }

            // Click on peeking prev/next card to slide it to center
            document.querySelectorAll('.tix-orbit-card').forEach((card) => {
                card.addEventListener('click', function(e) {
                    if (this.classList.contains('is-prev')) {
                        e.preventDefault();
                        tixPrevOrbitSlide();
                    } else if (this.classList.contains('is-next')) {
                        e.preventDefault();
                        tixNextOrbitSlide();
                    }
                });
            });

            // Touch swipe gesture support on mobile
            const orbitStage = document.getElementById('tixOrbitStage');
            if (orbitStage && orbitSlideCount > 1) {
                let touchStartX = 0;
                let touchEndX = 0;
                orbitStage.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });
                orbitStage.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    if (touchStartX - touchEndX > 45) {
                        tixNextOrbitSlide();
                    } else if (touchEndX - touchStartX > 45) {
                        tixPrevOrbitSlide();
                    }
                }, { passive: true });
            }

            // Bind Realtime Search Input (Debounced 250ms)
            const searchInput = document.getElementById('tixSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(tixSearchTimeout);
                    tixSearchQuery = this.value.trim();
                    tixSearchTimeout = setTimeout(() => {
                        tixTriggerSearch(true);
                    }, 250);
                });
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