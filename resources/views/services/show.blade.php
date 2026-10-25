@extends('layouts.app')
@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

    <style>
        /* ══════════════════════════════════════
                                       PRODUCT SHOW — buyle.id (Shopee Style)
                                       Font: Montserrat
                                    ══════════════════════════════════════ */

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        .pd-page {
            font-family: 'Montserrat', sans-serif;
            background: #F5F5F5;
            /* Abu-abu shopee */
            min-height: 100vh;
            padding-top: 80px;
            color: #1E293B;
        }

        @media (max-width: 768px) {
            footer.cv-footer-v2 {
                display: none !important;
            }

            .pd-actions {
                display: none !important;
            }

            /* Hide inline buttons on mobile since sticky bottom bar handles checkout */
        }

        /* ── Accent Vars ── */
        .pd-page {
            --primary: #1eb349;
            --primary-hover: #16a34a;
            --primary-light: rgba(30, 179, 73, 0.1);
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --bg-light: #F8FAFC;
        }

        /* ─── BREADCRUMB ─── */
        .pd-breadcrumb {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.5rem 1.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            font-weight: 400;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pd-breadcrumb a {
            color: var(--text-muted);
            text-decoration: none;
            transition: 0.2s;
            white-space: nowrap;
        }

        .pd-breadcrumb a:hover {
            color: var(--primary);
        }

        .pd-breadcrumb span.cur {
            color: var(--text-main);
            font-weight: 500;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ─── CONTAINERS & GRID ─── */
        .pd-layout-grid {
            max-width: 1200px;
            margin: 0 auto 2rem;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 1.5rem;
            align-items: start;
        }

        @media (max-width: 992px) {
            .pd-layout-grid {
                grid-template-columns: 1fr;
            }
        }

        .pd-main-col {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            min-width: 0;
        }

        .pd-sidebar-col {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            position: sticky;
            top: 90px;
        }

        @media (max-width: 992px) {
            .pd-sidebar-col {
                position: static;
            }
        }

        .pd-container {
            width: 100%;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        /* ─── PRODUCT BLOCK ─── */
        .pd-product-block {
            display: flex;
            padding: 1.5rem;
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .pd-product-block {
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
            }
        }

        /* ── TICKET WAR COUNTDOWN (MINIMALIS LIGHT 1-BARIS TEMA BUYLE) ── */
        .ticket-war-light-bar {
            background: linear-gradient(135deg, rgba(30, 179, 73, 0.06), rgba(165, 207, 55, 0.06));
            border: 1.5px solid rgba(30, 179, 73, 0.3);
            border-radius: 12px;
            padding: 0.65rem 1rem;
            margin-top: 1.25rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            box-sizing: border-box;
        }

        .tw-light-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #166534;
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .tw-light-timer {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            flex-wrap: nowrap;
        }

        .tw-light-box {
            background: #FFFFFF;
            border: 1px solid rgba(30, 179, 73, 0.35);
            border-radius: 6px;
            padding: 0.2rem 0.45rem;
            min-width: 38px;
            text-align: center;
            display: inline-flex;
            align-items: baseline;
            justify-content: center;
            gap: 2px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .tw-light-val {
            font-family: 'Montserrat', monospace;
            font-size: 0.95rem;
            font-weight: 800;
            color: #15803D;
            line-height: 1;
        }

        .tw-light-lbl {
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748B;
            text-transform: lowercase;
        }

        .tw-light-sep {
            font-weight: 800;
            color: #16A34A;
            font-size: 0.85rem;
        }

        .pd-btn-locked-buyle {
            flex: 1;
            background: #F8FAFC;
            color: #64748B;
            border: 1.5px dashed #CBD5E1;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: not-allowed;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: inherit;
            transition: all 0.2s;
        }

        /* ── GALLERY (Left) ── */
        .pd-gallery {
            width: 360px;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .pd-gallery {
                width: 100%;
            }
        }

        .pd-gallery-main {
            width: 100%;
            aspect-ratio: 1/1;
            border-radius: 4px;
            overflow: hidden;
            background: var(--bg-light);
            margin-bottom: 0.75rem;
            position: relative;
        }

        .pd-gallery-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pd-tiktok-slide {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
        }

        .pd-tiktok-slide iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .pd-gallery-main .swiper-button-next,
        .pd-gallery-main .swiper-button-prev {
            width: 32px;
            height: 32px;
            background: rgba(0, 0, 0, 0.3);
            color: #fff;
            border-radius: 50%;
        }

        .pd-gallery-main .swiper-button-next::after,
        .pd-gallery-main .swiper-button-prev::after {
            font-size: 12px;
        }

        .pd-thumbs {
            display: flex;
            gap: 0.5rem;
        }

        .pd-thumb-item {
            width: 82px;
            height: 82px;
            border-radius: 4px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            opacity: 1;
            transition: 0.2s;
            flex-shrink: 0;
            background: var(--bg-light);
        }

        .pd-thumb-item:hover {
            border-color: var(--primary);
        }

        .pd-thumb-item.swiper-slide-thumb-active {
            border-color: var(--primary);
        }

        .pd-thumb-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── PRODUCT INFO (Right) ── */
        .pd-info {
            flex: 1;
            min-width: 0;
        }

        .pd-title {
            font-size: 1.25rem;
            font-weight: 500;
            color: var(--text-main);
            line-height: 1.4;
            margin: 0 0 0.5rem;
            word-wrap: break-word;
        }

        .pd-stats {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .pd-stars {
            display: flex;
            align-items: center;
            gap: 4px;
            color: var(--primary);
            font-weight: 500;
            border-bottom: 1px solid var(--primary);
            cursor: pointer;
        }

        .pd-stat-sep {
            width: 1px;
            height: 14px;
            background: var(--border);
        }

        .pd-stat-val {
            color: var(--text-main);
            font-weight: 500;
            border-bottom: 1px solid var(--text-main);
            cursor: pointer;
        }

        /* Price Box */
        .pd-price-box {
            background: linear-gradient(135deg, rgba(30, 179, 73, 0.04), rgba(165, 207, 55, 0.04));
            border-left: 3px solid var(--primary);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            border-radius: 0 8px 8px 0;
            flex-wrap: wrap;
        }

        .pd-price-old {
            font-size: 1rem;
            color: var(--text-muted);
            text-decoration: line-through;
            white-space: nowrap;
        }

        .pd-price-main {
            font-size: 1.6rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            white-space: nowrap;
        }

        .pd-discount {
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.25rem 0.65rem;
            border-radius: 99px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
            flex-shrink: 0;
        }

        @media (max-width: 640px) {
            .pd-price-box {
                padding: 1rem;
            }

            .pd-price-main {
                font-size: 1.35rem;
            }

            .pd-price-old {
                font-size: 0.9rem;
            }
        }

        /* Shipping & Attributes Row */
        .pd-attr-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .pd-attr-label {
            width: 110px;
            color: var(--text-muted);
            flex-shrink: 0;
            padding-top: 6px;
        }

        .pd-attr-content {
            flex: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            color: var(--text-main);
        }

        /* Qty */
        .pd-qty-ctrl {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--border);
            border-radius: 2px;
            overflow: hidden;
            background: #fff;
        }

        .pd-qty-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: #fff;
            font-size: 1.2rem;
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pd-qty-btn:hover {
            background: var(--bg-light);
        }

        .pd-qty-input {
            width: 50px;
            height: 32px;
            border: none;
            border-left: 1px solid var(--border);
            border-right: 1px solid var(--border);
            text-align: center;
            font-size: 0.95rem;
            font-weight: 400;
            font-family: inherit;
            color: var(--text-main);
        }

        .pd-stock {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-left: 1rem;
            align-self: center;
        }

        /* Actions */
        .pd-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .pd-btn {
            padding: 0 1.5rem;
            height: 48px;
            border-radius: 99px;
            font-size: 0.95rem;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: 0.2s;
            border: none;
            text-decoration: none;
        }

        .pd-btn-outline {
            background: var(--primary-light);
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .pd-btn-outline:hover {
            background: rgba(30, 179, 73, 0.15);
        }

        .pd-btn-primary {
            background: linear-gradient(135deg, #1eb349, #a5cf37);
            color: #fff;
            border: 1px solid transparent;
        }

        .pd-btn-primary:hover {
            opacity: 0.9;
            box-shadow: 0 4px 12px rgba(30, 179, 73, 0.2);
        }

        .pd-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }


        /* ─── SELLER BLOCK ─── */
        .pd-seller-block {
            display: flex;
            align-items: center;
            padding: 1.5rem;
        }

        @media (max-width: 768px) {
            .pd-seller-block {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }

        .pd-seller-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding-right: 2rem;
            border-right: 1px solid var(--border);
            min-width: 350px;
        }

        @media (max-width: 768px) {
            .pd-seller-left {
                border-right: none;
                padding-right: 0;
                min-width: 100%;
                border-bottom: 1px solid var(--border);
                padding-bottom: 1rem;
            }
        }

        .pd-seller-ava {
            width: 78px;
            height: 78px;
            border-radius: 50%;
            object-fit: cover;
            background: #fff;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 500;
            color: var(--primary);
            flex-shrink: 0;
        }

        .pd-seller-info {
            flex: 1;
        }

        .pd-seller-name {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 0.25rem;
        }

        .pd-seller-sub {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .pd-seller-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .pd-seller-btn {
            padding: 0.35rem 0.75rem;
            border-radius: 99px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            transition: 0.2s;
        }

        .pd-seller-btn-outline {
            background: var(--primary-light);
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .pd-seller-btn-outline:hover {
            background: rgba(30, 179, 73, 0.15);
        }

        .pd-seller-btn-gray {
            background: #fff;
            color: var(--text-muted);
            border: 1px solid var(--border);
        }

        .pd-seller-btn-gray:hover {
            background: var(--bg-light);
            color: var(--text-main);
        }

        .pd-seller-right {
            flex: 1;
            padding-left: 2rem;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .pd-seller-right {
                padding-left: 0;
                grid-template-columns: repeat(2, 1fr);
                width: 100%;
            }
        }

        .pd-seller-stat {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .pd-seller-stat span {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.95rem;
        }


        /* ─── DETAILS BLOCK ─── */
        .pd-details-block {
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .pd-details-block {
                padding: 1.5rem 1rem;
            }
        }

        .pd-section-title {
            background: linear-gradient(135deg, rgba(30, 179, 73, 0.06), rgba(165, 207, 55, 0.04));
            border-left: 3px solid var(--primary);
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-radius: 0 8px 8px 0;
        }

        /* Specs Table */
        .pd-specs {
            width: 100%;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .pd-specs td {
            padding: 0.5rem 0;
        }

        .pd-specs td:first-child {
            width: 150px;
            color: var(--text-muted);
        }

        .pd-specs td:last-child {
            color: var(--text-main);
        }

        @media (max-width: 640px) {
            .pd-specs td:first-child {
                width: 110px;
                font-size: 0.82rem;
            }

            .pd-specs td:last-child {
                font-size: 0.85rem;
            }
        }

        /* ─── TABS COMPONENT (Minimalist & Anti-Collision) ─── */
        .pd-tabs-header {
            display: flex;
            align-items: stretch;
            border-bottom: 1.5px solid var(--border);
            background: #f8fafc;
            overflow-x: auto;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }

        .pd-tabs-header::-webkit-scrollbar {
            display: none;
        }

        .pd-tab-btn {
            flex: 1 1 0;
            min-width: 0;
            padding: 0.95rem 0.5rem;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--text-muted);
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .pd-tab-btn:hover {
            color: var(--primary);
            background: rgba(30, 179, 73, 0.03);
        }

        .pd-tab-btn.active {
            color: var(--text-main) !important;
            border-bottom: 3px solid var(--primary) !important;
            font-weight: 700;
            background: #ffffff;
        }

        .pd-tab-badge {
            background: #e2e8f0;
            color: #475569;
            padding: 2px 7px;
            border-radius: 99px;
            font-size: 0.72rem;
            font-weight: 700;
            line-height: 1.2;
            transition: all 0.2s;
        }

        .pd-tab-btn.active .pd-tab-badge {
            background: rgba(30, 179, 73, 0.15);
            color: var(--primary);
        }

        @media (max-width: 640px) {
            .pd-tab-btn {
                padding: 0.85rem 0.35rem;
                font-size: 0.8rem;
                gap: 4px;
            }

            .pd-tab-badge {
                padding: 1px 6px;
                font-size: 0.68rem;
            }
        }

        .pd-tab-body {
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .pd-tab-body {
                padding: 1.25rem 1rem;
            }
        }

        /* ─── SEO CALLOUT (Disembunyikan di Mobile) ─── */
        .pd-seo-callout {
            font-size: 0.875rem;
            color: #334155;
            line-height: 1.6;
            margin-bottom: 1.25rem;
            padding: 0.85rem 1.15rem;
            background: linear-gradient(135deg, #f0fdf4, #e8f5e9);
            border-left: 4px solid #1eb349;
            border-radius: 0 10px 10px 0;
        }

        @media (max-width: 768px) {
            .pd-seo-callout {
                display: none !important;
            }
        }

        /* ─── SECURITY NOTICE ─── */
        .pd-security-notice {
            font-size: 0.825rem;
            color: #475569;
            line-height: 1.6;
            margin-top: 1.5rem;
            padding: 0.75rem 1rem;
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 10px;
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
        }

        @media (max-width: 640px) {
            .pd-security-notice {
                font-size: 0.78rem;
                padding: 0.65rem 0.85rem;
                gap: 0.5rem;
            }
        }

        /* Description Content — Prose Typography */
        .pd-desc-content {
            font-size: 0.95rem;
            color: var(--text-main);
            line-height: 1.9;
        }

        /* Headings inside description */
        .pd-desc-content h1,
        .pd-desc-content h2,
        .pd-desc-content h3,
        .pd-desc-content h4 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 1.75rem 0 0.6rem;
            line-height: 1.4;
            letter-spacing: -0.01em;
        }

        .pd-desc-content h1 {
            font-size: 1.2rem;
        }

        .pd-desc-content h2 {
            font-size: 1.1rem;
        }

        .pd-desc-content h3 {
            font-size: 1rem;
        }

        /* Paragraphs */
        .pd-desc-content p {
            margin: 0 0 1rem;
            color: #334155;
            line-height: 1.85;
        }

        /* Lists */
        .pd-desc-content ul,
        .pd-desc-content ol {
            margin: 0.5rem 0 1.25rem 1.5rem;
            padding: 0;
        }

        .pd-desc-content li {
            margin-bottom: 0.4rem;
            line-height: 1.7;
            color: #334155;
        }

        /* Strong */
        .pd-desc-content strong {
            color: var(--text-main);
            font-weight: 700;
        }

        /* Links inside description */
        .pd-desc-content a {
            color: var(--primary);
            text-decoration: underline;
            text-decoration-color: rgba(30, 179, 73, 0.3);
        }

        /* First heading no top margin */
        .pd-desc-content h1:first-child,
        .pd-desc-content h2:first-child,
        .pd-desc-content h3:first-child {
            margin-top: 0;
        }


        /* ─── MOBILE STICKY BAR ─── */
        .pd-sticky-bar {
            display: none;
        }

        @media (max-width: 768px) {
            .pd-page {
                padding-top: 0;
            }

            .pd-actions {
                display: none;
            }

            .pd-sticky-bar {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: #fff;
                padding: 0.75rem 1rem env(safe-area-inset-bottom, 0px);
                padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px));
                border-top: 1px solid var(--border);
                box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.08);
                z-index: 200;
            }

            .pd-sticky-bar .pd-btn {
                flex: 1;
                height: 46px;
                font-size: 0.9rem;
                border-radius: 12px;
            }

            .pd-breadcrumb {
                padding: 1rem 1rem 0.5rem;
            }

            /* push content up so sticky bar doesn't overlap */
            .pd-page {
                padding-bottom: 80px;
            }

            /* move report button above sticky bar */
            .buyle-report-btn {
                bottom: 80px !important;
                left: 12px !important;
            }
        }


        /* ─── RELATED (MATCHING HOMEPAGE 100%) ─── */
        .pd-related-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0F172A;
            margin-bottom: 1rem;
            font-family: 'Montserrat', sans-serif;
        }

        .swipe-cards-container {
            display: flex;
            gap: 1.1rem;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding: 0.25rem 0.25rem 0.85rem;
            scrollbar-width: thin;
        }

        .swipe-cards-container::-webkit-scrollbar {
            height: 4px;
        }

        .swipe-cards-container::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 99px;
        }

        .article-card-swipe-item {
            flex: 0 0 240px;
            width: 240px;
            scroll-snap-align: start;
        }

        @media (min-width: 1024px) {
            .article-card-swipe-item {
                flex: 0 0 calc(20% - 0.88rem);
                width: calc(20% - 0.88rem);
            }
        }

        .article-card-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08) !important;
        }

        /* ─── REVIEWS TAB STYLES ─── */
        .pd-reviews-summary-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.5rem;
            display: grid;
            grid-template-columns: 220px 1fr 220px;
            gap: 1.5rem;
            align-items: center;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.5rem;
        }

        @media (max-width: 900px) {
            .pd-reviews-summary-card {
                grid-template-columns: 1fr;
                gap: 1.25rem;
                padding: 1.25rem;
            }
        }

        .pd-score-big-box {
            text-align: center;
            padding: 0.5rem;
            border-right: 1px solid var(--border);
        }

        @media (max-width: 900px) {
            .pd-score-big-box {
                border-right: none;
                border-bottom: 1px solid var(--border);
                padding-bottom: 1rem;
            }
        }

        .pd-score-big {
            font-size: 2.75rem;
            font-weight: 800;
            color: #0F172A;
            line-height: 1;
            margin-bottom: 0.35rem;
        }

        .pd-stars-row {
            color: #F59E0B;
            font-size: 1.15rem;
            margin-bottom: 0.35rem;
            letter-spacing: 2px;
        }

        .pd-score-sub {
            font-size: 0.8rem;
            color: #64748B;
            font-weight: 500;
        }

        .pd-progress-bars {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .pd-bar-row {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.8rem;
        }

        .pd-bar-label {
            width: 32px;
            color: #475569;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .pd-bar-track {
            flex: 1;
            height: 8px;
            background: #E2E8F0;
            border-radius: 99px;
            overflow: hidden;
            position: relative;
        }

        .pd-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #1EB349, #A5CF37);
            border-radius: 99px;
            transition: width 0.4s ease;
        }

        .pd-bar-count {
            width: 30px;
            text-align: right;
            color: #64748B;
            font-size: 0.75rem;
        }

        .pd-reviews-action-box {
            text-align: center;
            padding: 0.5rem;
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        @media (max-width: 900px) {
            .pd-reviews-action-box {
                border-left: none;
                border-top: 1px solid var(--border);
                padding-top: 1rem;
            }
        }

        /* Filter pills */
        .pd-review-filter-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .pd-filter-pill {
            padding: 0.45rem 0.95rem;
            border-radius: 99px;
            background: #F8FAFC;
            border: 1px solid var(--border);
            color: #475569;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .pd-filter-pill:hover {
            background: #F1F5F9;
            border-color: #CBD5E1;
        }

        .pd-filter-pill.active {
            background: #F0FDF4;
            border-color: #1EB349;
            color: #15803D;
            font-weight: 700;
        }

        /* Review Cards Feed */
        .pd-reviews-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .pd-review-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            transition: box-shadow 0.2s;
        }

        .pd-review-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        .pd-review-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .pd-reviewer-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pd-reviewer-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #1EB349;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .pd-reviewer-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 2px;
        }

        .pd-verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            color: #15803D;
            padding: 1px 7px;
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .pd-review-date {
            font-size: 0.78rem;
            color: #94A3B8;
        }

        .pd-review-stars {
            color: #F59E0B;
            font-size: 0.95rem;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .pd-review-text {
            font-size: 0.88rem;
            color: #334155;
            line-height: 1.7;
            margin-bottom: 0.85rem;
            white-space: pre-line;
        }

        .pd-review-media-wrap {
            margin-top: 0.5rem;
        }

        .pd-review-thumb-box {
            display: inline-block;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #E2E8F0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            background: #F8FAFC;
            max-width: 140px;
        }

        .pd-review-thumb-box:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        .pd-review-thumb-box img {
            display: block;
            width: 130px;
            height: 130px;
            object-fit: cover;
        }

        .pd-thumb-overlay-hint {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(15, 23, 42, 0.65);
            color: #fff;
            font-size: 0.68rem;
            font-weight: 600;
            text-align: center;
            padding: 3px;
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
        }

        /* In-tab write review card */
        .pd-write-review-card {
            background: #F8FAFC;
            border: 1.5px solid #BBF7D0;
            border-radius: 18px;
            padding: 1.5rem;
            margin-bottom: 1.75rem;
            box-shadow: 0 4px 16px rgba(30, 179, 73, 0.06);
            display: none;
            transition: all 0.3s;
        }

        /* ── Modern Interactive Rating Popup ── */
        .pd-rating-success-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            animation: rsmFadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pd-rating-success-modal-box {
            background: #ffffff;
            border-radius: 28px;
            padding: 2.5rem 2rem 2.25rem;
            max-width: 440px;
            width: 100%;
            text-align: center;
            position: relative;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.28), 0 0 0 1px rgba(226, 232, 240, 0.8);
            animation: rsmPopIn 0.32s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pd-rsm-close {
            position: absolute;
            top: 18px;
            right: 18px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #F1F5F9;
            border: none;
            font-size: 20px;
            line-height: 1;
            color: #64748B;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .pd-rsm-close:hover {
            background: #E2E8F0;
            color: #0F172A;
            transform: scale(1.08);
        }

        .pd-rsm-icon-wrap {
            width: 78px;
            height: 78px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1eb349 0%, #10b981 100%);
            margin: 0 auto 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 12px 30px rgba(30, 179, 73, 0.38);
            animation: rsmPulse 2.5s infinite;
        }

        .pd-rsm-stars {
            font-size: 1.85rem;
            color: #F59E0B;
            letter-spacing: 5px;
            margin-bottom: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2px;
        }

        .pd-rsm-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 0.5rem;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: -0.01em;
        }

        .pd-rsm-desc {
            font-size: 0.88rem;
            color: #64748B;
            line-height: 1.55;
            margin: 0 0 1.65rem;
        }

        .pd-rsm-btn-primary {
            background: linear-gradient(135deg, #1eb349 0%, #7db928 100%);
            color: #ffffff;
            border: none;
            border-radius: 99px;
            padding: 0.85rem 2rem;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            box-shadow: 0 8px 22px rgba(30, 179, 73, 0.32);
            transition: all 0.25s ease;
            font-family: 'Montserrat', sans-serif;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .pd-rsm-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(30, 179, 73, 0.42);
        }

        @keyframes rsmFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes rsmPopIn {
            from {
                opacity: 0;
                transform: scale(0.85) translateY(20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes rsmPulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }
    </style>

    <div class="pd-page">

        {{-- Breadcrumb --}}
        <div class="pd-breadcrumb">
            <a href="{{ route_locale('home') }}">Beranda</a>
            <span>></span>
            <a href="{{ route_locale('products') }}">Produk</a>
            <span>></span>
            <span class="cur">{{ Str::limit($service->name, 40) }}</span>
        </div>

        {{-- LAYOUT 3 KOLOM --}}
        <div class="pd-layout-grid">

            {{-- MAIN COLUMN (Kiri & Tengah) --}}
            @php
                $isTicket = ($service->product_type ?? '') === 'ticket';
                $isWarActive = $isTicket && !empty($service->ticket_war_start) && now()->lt($service->ticket_war_start);
                $warTimestamp = $isWarActive ? $service->ticket_war_start->timestamp * 1000 : 0;

                // Deteksi event expired — khusus tipe ticket
                $eventRaw = $service->event_date ?: (!empty($service->data_json['event_date']) ? $service->data_json['event_date'] : null);
                $isExpired = $isTicket && $eventRaw && \Carbon\Carbon::parse($eventRaw)->endOfDay()->isPast();
            @endphp
            <div class="pd-main-col">
                <div class="pd-container pd-product-block">
                    {{-- LEFT: Gallery --}}
                    <div class="pd-gallery"
                        style="{{ $isExpired ? 'filter: grayscale(100%); opacity: 0.85; position: relative;' : '' }}">
                        @php
                            $imgs = [];
                            if ($service->image)
                                $imgs[] = asset('storage/' . $service->image);
                            else
                                $imgs[] = asset('images/service-default.jpg');
                            if (is_array($service->gallery)) {
                                foreach ($service->gallery as $g)
                                    $imgs[] = asset('storage/' . $g);
                            }
                        @endphp

                        <div class="pd-gallery-main swiper" id="pd-swiper-main">
                            @if($isExpired)
                                <div
                                    style="position: absolute; inset: 0; z-index: 10; display: flex; align-items: center; justify-content: center; pointer-events: none; background: rgba(15,23,42,0.45); backdrop-filter: blur(2px);">
                                    <span
                                        style="background: rgba(15, 23, 42, 0.9); color: #fff; font-family: 'Montserrat', sans-serif; font-size: 0.9rem; font-weight: 800; letter-spacing: 0.08em; padding: 0.5rem 1.25rem; border-radius: 999px; border: 1.5px solid rgba(255,255,255,0.25); box-shadow: 0 4px 15px rgba(0,0,0,0.4); text-transform: uppercase;">
                                        🏁 EVENT SELESAI
                                    </span>
                                </div>
                            @endif
                            <div class="swiper-wrapper">
                                @foreach($imgs as $img)
                                    <div class="swiper-slide">
                                        <a href="{{ $img }}" class="glightbox" data-gallery="product-gallery">
                                            <img src="{{ $img }}" alt="{{ $service->name }}" loading="lazy">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            @if(count($imgs) > 1)
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            @endif
                        </div>

                        @if(count($imgs) > 1)
                            <div class="swiper pd-thumbs" id="pd-swiper-thumbs">
                                <div class="swiper-wrapper">
                                    @foreach($imgs as $img)
                                        <div class="swiper-slide pd-thumb-item">
                                            <img src="{{ $img }}" alt="thumb" loading="lazy">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- CENTER: Info --}}
                    <div class="pd-info">
                        <h1 class="pd-title">{{ $service->name }}</h1>

                        <div class="pd-stats" style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                            @php
                                $effRating = $service->getEffectiveRating();
                                $reviewCnt = (int) $realReviewsCount;
                                $soldCnt = (int) ($service->sold_count ?? 0);
                            @endphp
                            {{-- Rating (Realtime dari DB jika ada ulasan/rating) --}}
                            @if($effRating !== null && $effRating > 0)
                                <a href="javascript:void(0)" onclick="goToReviewsTab()"
                                    style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.85rem; text-decoration:none; cursor:pointer;"
                                    title="Lihat Ulasan">
                                    <span style="color: #F59E0B; font-size: 1rem;">★</span>
                                    <span style="color: #0F172A; font-weight: 700;">{{ number_format($effRating, 1) }}</span>
                                    <span
                                        style="font-size: 0.8rem; color: #1eb349; font-weight:600; text-decoration:underline;">({{ $reviewCnt }}
                                        ulasan)</span>
                                </a>
                            @else
                                <a href="javascript:void(0)" onclick="goToReviewsTab()"
                                    style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.85rem; text-decoration:none; cursor:pointer;"
                                    title="Tulis Ulasan">
                                    <span style="color: #CBD5E1; font-size: 1rem;">★</span>
                                    <span style="color: #64748B; font-weight: 600;">0.0</span>
                                    <span
                                        style="font-size: 0.8rem; color: #1eb349; font-weight:600; text-decoration:underline;">(0)</span>
                                </a>
                            @endif
                            <span style="color: #CBD5E1;">|</span>
                            {{-- Terjual --}}
                            <div style="font-size: 0.85rem; color: #64748B;">
                                Terjual <span style="color: var(--text-main); font-weight: 700;">
                                    @if($soldCnt >= 1000)
                                        {{ number_format($soldCnt / 1000, 1, ',', '') }}RB
                                    @else
                                        {{ $soldCnt }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- PRICE --}}
                        @if($service->price > 0 || $service->sale_price !== null)
                            @php
                                $isFreeProduct = $service->sale_price !== null && (float) $service->sale_price <= 0;
                                $hasDiscount = !$isFreeProduct && $service->sale_price !== null && (float) $service->sale_price > 0 && (float) $service->sale_price < (float) $service->price;
                            @endphp
                            <div class="pd-price-box">
                                @if($isFreeProduct)
                                    {{-- GRATIS: harga normal dicoret, lalu GRATIS --}}
                                    <div class="pd-price-old">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                                    <div class="pd-price-main" style="color:#16a34a;">GRATIS</div>
                                    <span class="pd-discount">100% OFF</span>
                                @elseif($hasDiscount)
                                    <div class="pd-price-old">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                                    <div class="pd-price-main">Rp{{ number_format($service->sale_price, 0, ',', '.') }}</div>
                                    <span
                                        class="pd-discount">{{ round((($service->price - $service->sale_price) / $service->price) * 100) }}%
                                        OFF</span>
                                @else
                                    <div class="pd-price-main">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                                @endif
                            </div>

                            @if($service->product_type === 'ticket')
                                {{-- INFORMASI EVENT (KHUSUS TIKET) --}}
                                @if($service->event_date)
                                    <div class="pd-attr-row">
                                        <div class="pd-attr-label">Pelaksanaan</div>
                                        <div class="pd-attr-content">
                                            <div>
                                                <div
                                                    style="display:flex; align-items:center; gap:6px; color:var(--text-main); font-weight:700;">
                                                    <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2.2"
                                                        viewBox="0 0 24 24">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                        <line x1="16" y1="2" x2="16" y2="6" />
                                                        <line x1="8" y1="2" x2="8" y2="6" />
                                                        <line x1="3" y1="10" x2="21" y2="10" />
                                                    </svg>
                                                    <span>{{ \Carbon\Carbon::parse($service->event_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                                                    @if($service->event_time)
                                                        <span
                                                            style="font-size:0.75rem; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; padding:2px 8px; border-radius:999px; font-weight:600;">
                                                            {{ $service->event_time }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div style="font-size:0.8rem; color:var(--text-muted); margin-top:3px;">
                                                    Jadwal tanggal dan waktu berlangsungnya acara / event.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="pd-attr-row">
                                    <div class="pd-attr-label">Lokasi / Venue</div>
                                    <div class="pd-attr-content">
                                        <div>
                                            @if($service->event_type === 'online')
                                                <div
                                                    style="display:flex; align-items:center; flex-wrap:wrap; gap:6px; color:var(--text-main);">
                                                    <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2.2"
                                                        viewBox="0 0 24 24">
                                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2" />
                                                        <line x1="8" y1="21" x2="16" y2="21" />
                                                        <line x1="12" y1="17" x2="12" y2="21" />
                                                    </svg>
                                                    <b style="font-size:0.92rem;">Online Event via
                                                        {{ $service->event_platform ?: 'Meeting Room / Video Conference' }}</b>
                                                    <span
                                                        style="font-size:0.75rem; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:2px 8px; border-radius:999px; font-weight:600;">Online</span>
                                                </div>
                                                <div
                                                    style="font-size:0.8rem; color:var(--text-muted); margin-top:3px; display:flex; align-items:center; gap:5px;">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                                        viewBox="0 0 24 24">
                                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                                    </svg>
                                                    <span>Link / Tautan meeting akan dikirimkan otomatis ke tiket Anda setelah pemesanan
                                                        berhasil.</span>
                                                </div>
                                            @else
                                                <div
                                                    style="display:flex; align-items:center; flex-wrap:wrap; gap:6px; color:var(--text-main);">
                                                    <svg width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2.2"
                                                        viewBox="0 0 24 24">
                                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                                        <circle cx="12" cy="10" r="3" />
                                                    </svg>
                                                    <b
                                                        style="font-size:0.92rem;">{{ $service->event_location ?: 'Lokasi diumumkan oleh penyelenggara' }}</b>
                                                    <span
                                                        style="font-size:0.75rem; background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:2px 8px; border-radius:999px; font-weight:600;">Offline
                                                        Event</span>
                                                </div>

                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Pengiriman Digital (Khusus Produk Non-Tiket) --}}
                                <div class="pd-attr-row">
                                    <div class="pd-attr-label">Pengiriman</div>
                                    <div class="pd-attr-content">
                                        <div>
                                            <div style="display:flex; align-items:center; gap:4px; color:var(--text-main);">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="7 10 12 15 17 10" />
                                                    <line x1="12" y1="15" x2="12" y2="3" />
                                                </svg>
                                                <b>Akses Instan & Otomatis</b>
                                            </div>
                                            <div style="font-size:0.8rem; color:var(--text-muted); margin-top:2px;">Produk ini dapat
                                                langsung diakses/diunduh setelah pembayaran berhasil.</div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @php
                                $maxOrderLimit = null;
                                if (($service->product_type ?? $service->type) === 'ticket') {
                                    $maxOrderLimit = (int) ($service->max_tickets_per_order ?: 4);
                                } elseif (!empty($service->max_order)) {
                                    $maxOrderLimit = (int) $service->max_order;
                                }
                            @endphp

                            {{-- FLASH MESSAGE ALERTS --}}
                            @if(session('error'))
                                <div style="background:#fef2f2; border:1px solid #f87171; color:#991b1b; padding:0.75rem 1rem; border-radius:12px; margin-bottom:1rem; font-weight:600; font-size:0.85rem; display:flex; align-items:center; gap:0.5rem;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    <span>{{ session('error') }}</span>
                                </div>
                            @endif
                            @if(session('success'))
                                <div style="background:#f0fdf4; border:1px solid #4ade80; color:#166534; padding:0.75rem 1rem; border-radius:12px; margin-bottom:1rem; font-weight:600; font-size:0.85rem; display:flex; align-items:center; gap:0.5rem;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                    <span>{{ session('success') }}</span>
                                </div>
                            @endif

                            {{-- CART FORM --}}
                            <form id="pd-cart-form" action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" id="pd_action_input" value="buy">
                                <input type="hidden" name="product_id" value="{{ $service->id }}">
                                <input type="hidden" name="variant_value_id" id="pd_selected_variant_id" value="">

                                {{-- VARIANT SELECTOR (tampil jika ada varian) --}}
                                @if($service->variantOptions && $service->variantOptions->count() > 0)
                                    @php
                                        $hasVariants = true;
                                        $isFreeProduct = $service->sale_price !== null && (float) $service->sale_price <= 0;
                                        $basePrice = (!$isFreeProduct && $service->sale_price > 0 && $service->sale_price < $service->price)
                                            ? (float) $service->sale_price : (float) $service->price;
                                        $defaultProductImage = !empty($imgs[0]) ? $imgs[0] : ($service->image ? asset('storage/' . $service->image) : asset('images/service-default.jpg'));
                                    @endphp
                                    <div id="pd-variant-section" style="margin-bottom:1.5rem;">
                                        @foreach($service->variantOptions as $option)
                                            <div class="pd-attr-row" style="align-items:flex-start;">
                                                <div class="pd-attr-label" style="padding-top:4px;">{{ $option->name }}</div>
                                                <div class="pd-attr-content" style="flex-wrap:wrap; gap:0.5rem;">
                                                    @foreach($option->values as $val)
                                                        @php
                                                            $vImg = $val->image ? (\Illuminate\Support\Str::startsWith($val->image, 'http') ? $val->image : asset('storage/' . $val->image)) : null;
                                                        @endphp
                                                        <button type="button" class="pd-variant-pill" data-variant-id="{{ $val->id }}"
                                                            data-price="{{ $val->finalPrice() }}" 
                                                            data-price-formatted="Rp{{ number_format($val->finalPrice(), 0, ',', '.') }}"
                                                            data-stock="{{ $val->stock }}"
                                                            data-label="{{ $option->name }}: {{ $val->value }}" 
                                                            data-img="{{ $vImg ?: $defaultProductImage }}"
                                                            data-has-custom-img="{{ $vImg ? '1' : '0' }}"
                                                            @if($val->stock !== null && $val->stock <= 0) disabled style="opacity:0.4;cursor:not-allowed;" @endif
                                                            onclick="selectPdVariant(this)">
                                                            @if($vImg)
                                                                <img src="{{ $vImg }}" alt="{{ $val->value }}" style="width:20px;height:20px;border-radius:4px;object-fit:cover;vertical-align:middle;margin-right:4px;">
                                                            @endif
                                                            {{ $val->value }}
                                                            @if($val->price > 0 && $val->price != $basePrice)
                                                                @php $vDiff = $val->price - $basePrice; @endphp
                                                                <span
                                                                    style="font-size:0.7rem; opacity:0.75; margin-left:2px;">{{ $vDiff > 0 ? '+Rp' . number_format($vDiff, 0, ',', '.') : '-Rp' . number_format(abs($vDiff), 0, ',', '.') }}</span>
                                                            @endif
                                                            @if($val->stock !== null && $val->stock <= 0)
                                                                <span style="font-size:0.7rem; margin-left:2px;">(Habis)</span>
                                                            @endif
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                        <div id="pd-variant-error"
                                            style="display:none; color:#EF4444; font-size:0.82rem; margin-top:0.25rem; padding:0.4rem 0.75rem; background:#FEF2F2; border-radius:8px; border:1px solid #FCA5A5;">
                                            ⚠ Silakan pilih varian terlebih dahulu sebelum melanjutkan.
                                        </div>
                                        <div id="pd-variant-info"
                                            style="display:none; font-size:0.82rem; color:#1eb349; margin-top:0.35rem; font-weight:600;">
                                        </div>
                                    </div>
                                    <style>
                                        .pd-variant-pill {
                                            display: inline-flex;
                                            align-items: center;
                                            padding: 0.4rem 0.9rem;
                                            border: 1.5px solid var(--border, #E2E8F0);
                                            border-radius: 99px;
                                            background: #fff;
                                            color: var(--text-main, #0F172A);
                                            font-size: 0.83rem;
                                            font-weight: 500;
                                            font-family: inherit;
                                            cursor: pointer;
                                            transition: all 0.18s;
                                        }

                                        .pd-variant-pill:hover:not(:disabled) {
                                            border-color: var(--primary, #1eb349);
                                            color: var(--primary, #1eb349);
                                            background: rgba(30, 179, 73, 0.05);
                                        }

                                        .pd-variant-pill.selected {
                                            border-color: var(--primary, #1eb349);
                                            background: linear-gradient(135deg, rgba(30, 179, 73, 0.12), rgba(165, 207, 55, 0.08));
                                            color: var(--primary, #1eb349);
                                            font-weight: 700;
                                            box-shadow: 0 2px 8px rgba(30, 179, 73, 0.2);
                                        }
                                    </style>
                                @endif

                                <div class="pd-attr-row" style="align-items:center;">
                                    <div class="pd-attr-label">Kuantitas</div>
                                    <div class="pd-attr-content">
                                        <div class="pd-qty-ctrl">
                                            <button type="button" class="pd-qty-btn"
                                                onclick="stepPdQty(-1)">−</button>
                                            <input type="number" id="qty_input" class="pd-qty-input" name="qty"
                                                value="{{ $service->min_order ?? 1 }}" min="{{ $service->min_order ?? 1 }}"
                                                data-max-limit="{{ $maxOrderLimit ?? '' }}"
                                                @if($maxOrderLimit) max="{{ $maxOrderLimit }}"
                                                @elseif($service->type !== 'service' && !is_null($service->stock) && $service->stock > 0) max="{{ $service->stock }}" @endif
                                                @if($service->type !== 'service' && !is_null($service->stock) && $service->stock <= 0) disabled @endif>
                                            <button type="button" class="pd-qty-btn"
                                                onclick="stepPdQty(1)">+</button>
                                        </div>
                                        <div class="pd-stock" id="pd-stock-info">
                                            @if($service->type === 'service')
                                                Jasa / Layanan
                                            @elseif(is_null($service->stock))
                                                <span style="color:#10B981;">Stok Tersedia</span>
                                            @elseif($service->stock > 0)
                                                Sisa {{ $service->stock }} buah
                                            @else
                                                <span style="color:#EE4D2D;">Habis</span>
                                            @endif
                                            @if($maxOrderLimit)
                                                <span style="font-size:0.75rem; color:#64748b; margin-left:6px;">(Maks. {{ $maxOrderLimit }} per transaksi)</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- TICKET EXPIRED BANNER --}}
                                @if($isExpired)
                                    <div
                                        style="background:linear-gradient(135deg,#F1F5F9,#E2E8F0);border:1.5px solid #CBD5E1;border-radius:14px;padding:1rem 1.25rem;margin-top:1.5rem;display:flex;align-items:center;gap:0.75rem;">
                                        <span style="font-size:1.4rem;">🏁</span>
                                        <div>
                                            <div
                                                style="font-family:'Montserrat',sans-serif;font-size:0.88rem;font-weight:800;color:#64748B;margin-bottom:0.15rem;">
                                                Event Telah Berakhir</div>
                                            <div style="font-size:0.75rem;color:#94A3B8;font-weight:500;">Penjualan tiket untuk
                                                event ini sudah ditutup karena tanggal event telah berlalu.</div>
                                        </div>
                                    </div>
                                @endif

                                {{-- TICKET WAR REAL-TIME COUNTDOWN (MINIMALIS LIGHT 1-BARIS TEMA BUYLE) --}}
                                @if($isWarActive)
                                    <div id="ticketWarCard" class="ticket-war-light-bar">
                                        <div class="tw-light-left">
                                            <svg width="17" height="17" fill="none" stroke="#16a34a" stroke-width="2.2"
                                                viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12 6 12 12 16 14" />
                                            </svg>
                                            <span>Countdown:</span>
                                        </div>
                                        <div class="tw-light-timer">
                                            <div class="tw-light-box"><span id="warDays" class="tw-light-val">00</span><span
                                                    class="tw-light-lbl">h</span></div>
                                            <span class="tw-light-sep">:</span>
                                            <div class="tw-light-box"><span id="warHours" class="tw-light-val">00</span><span
                                                    class="tw-light-lbl">j</span></div>
                                            <span class="tw-light-sep">:</span>
                                            <div class="tw-light-box"><span id="warMins" class="tw-light-val">00</span><span
                                                    class="tw-light-lbl">m</span></div>
                                            <span class="tw-light-sep">:</span>
                                            <div class="tw-light-box"><span id="warSecs" class="tw-light-val">00</span><span
                                                    class="tw-light-lbl">d</span></div>
                                        </div>
                                    </div>
                                @endif

                                @if($isExpired)
                                    <div class="pd-actions" style="margin-top:1.25rem; display:flex; gap:0.75rem;">
                                        <button type="button" class="pd-btn"
                                            style="flex:1; background:#94A3B8; color:#fff; border-color:#94A3B8; cursor:not-allowed;"
                                            disabled>
                                            Event Selesai (Penjualan Ditutup)
                                        </button>
                                    </div>
                                @elseif($isWarActive)
                                    <div class="pd-actions" style="margin-top:1.25rem; display:flex; gap:0.75rem;">
                                        <button type="button" id="btnTicketWarLocked" class="pd-btn-locked-buyle" disabled>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"
                                                viewBox="0 0 24 24">
                                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                            </svg>
                                            <span id="warBtnLabel">Penjualan Dibuka
                                                {{ $service->ticket_war_start->isoFormat('D MMMM Y, HH:mm') }} WIB</span>
                                        </button>
                                    <div id="normalActions" style="display: none; gap:0.75rem; flex:1;">
                                            <button type="button" id="pd-btn-cart" class="pd-btn pd-btn-outline" style="flex:1;"
                                                onclick="submitPdCart('cart')">
                                                Tambah Keranjang
                                            </button>
                                            <button type="button" id="pd-btn-buy" class="pd-btn pd-btn-primary" style="flex:1;"
                                                onclick="submitPdCart('buy')">
                                                Beli Sekarang
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="pd-actions" style="margin-top:1.5rem; display:flex; gap:0.75rem;">
                                        <button type="button" id="pd-btn-cart" class="pd-btn pd-btn-outline" style="flex:1;"
                                            @if($service->type !== 'service' && !is_null($service->stock) && $service->stock <= 0)
                                            disabled @endif onclick="submitPdCart('cart')">
                                            Tambah Keranjang
                                        </button>
                                        <button type="button" id="pd-btn-buy" class="pd-btn pd-btn-primary" style="flex:1;"
                                            @if($service->type !== 'service' && !is_null($service->stock) && $service->stock <= 0)
                                            disabled @endif onclick="submitPdCart('buy')">
                                            Beli Sekarang
                                        </button>
                                    </div>
                                @endif
                            </form>

                            {{-- POPUP INTERAKTIF PILIH VARIASI (COMPACT, MINIMALIS, MOBILE-FRIENDLY BOTTOM-SHEET) --}}
                            @if($service->variantOptions && $service->variantOptions->count() > 0)
                            <div id="pd-variant-modal" class="buyle-variant-modal" style="display:none;" aria-hidden="true">
                                <div class="bvm-backdrop" onclick="closePdVariantModal()"></div>
                                <div class="bvm-sheet">
                                    {{-- Drag handle pill (mobile) --}}
                                    <div class="bvm-handle-bar">
                                        <span class="bvm-handle"></span>
                                    </div>

                                    {{-- Header: Gambar Variasi / Produk + Harga + Stok + Tombol Tutup --}}
                                    <div class="bvm-header">
                                        <div class="bvm-thumb-wrap">
                                            <img id="bvm-thumb-img" src="{{ $defaultProductImage }}" alt="{{ $service->name }}" class="bvm-thumb">
                                            <span id="bvm-thumb-badge" class="bvm-thumb-badge" style="display:none;">Foto Variasi</span>
                                        </div>
                                        <div class="bvm-header-info">
                                            <div class="bvm-price" id="bvm-price-display">Rp{{ number_format($basePrice, 0, ',', '.') }}</div>
                                            <div class="bvm-stock" id="bvm-stock-display">
                                                @if(is_null($service->stock))
                                                    <span style="color:#10b981;">• Stok Tersedia</span>
                                                @elseif($service->stock > 0)
                                                    <span>• Sisa {{ $service->stock }} buah</span>
                                                @else
                                                    <span style="color:#ef4444;">• Stok Habis</span>
                                                @endif
                                            </div>
                                            <div class="bvm-selected-label" id="bvm-selected-label">
                                                Silakan pilih opsi variasi
                                            </div>
                                        </div>
                                        <button type="button" class="bvm-close-btn" onclick="closePdVariantModal()" aria-label="Tutup">
                                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Body: List Grup Variasi & Kuantitas --}}
                                    <div class="bvm-body">
                                        @foreach($service->variantOptions as $option)
                                            <div class="bvm-group">
                                                <div class="bvm-group-title">{{ $option->name }}</div>
                                                <div class="bvm-pills">
                                                    @foreach($option->values as $val)
                                                        @php
                                                            $valImg = $val->image ? (\Illuminate\Support\Str::startsWith($val->image, 'http') ? $val->image : asset('storage/' . $val->image)) : null;
                                                            $valFinalPrice = $val->finalPrice();
                                                            $isValOutOfStock = ($val->stock !== null && $val->stock <= 0);
                                                        @endphp
                                                        <button type="button" 
                                                            class="bvm-pill" 
                                                            data-variant-id="{{ $val->id }}"
                                                            data-price="{{ $valFinalPrice }}"
                                                            data-price-formatted="Rp{{ number_format($valFinalPrice, 0, ',', '.') }}"
                                                            data-stock="{{ $val->stock }}"
                                                            data-label="{{ $option->name }}: {{ $val->value }}"
                                                            data-img="{{ $valImg ?: $defaultProductImage }}"
                                                            data-has-custom-img="{{ $valImg ? '1' : '0' }}"
                                                            @if($isValOutOfStock) disabled @endif
                                                            onclick="selectBvmVariant(this)">
                                                            @if($valImg)
                                                                <img src="{{ $valImg }}" alt="{{ $val->value }}" class="bvm-pill-img">
                                                            @endif
                                                            <span class="bvm-pill-text">{{ $val->value }}</span>
                                                            @if($val->price > 0 && $val->price != $basePrice)
                                                                @php $bvmValDiff = $val->price - $basePrice; @endphp
                                                                <span class="bvm-pill-diff">{{ $bvmValDiff > 0 ? '+Rp' . number_format($bvmValDiff, 0, ',', '.') : '-Rp' . number_format(abs($bvmValDiff), 0, ',', '.') }}</span>
                                                            @endif
                                                            @if($isValOutOfStock)
                                                                <span class="bvm-pill-diff" style="color:#ef4444;">(Habis)</span>
                                                            @endif
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach

                                        {{-- Selector Kuantitas di dalam Modal --}}
                                        <div class="bvm-qty-row">
                                            <div>
                                                <div class="bvm-qty-label">Kuantitas</div>
                                                <div class="bvm-qty-sub" id="bvm-qty-sub">
                                                    Jumlah yang akan dipesan
                                                    @if($maxOrderLimit)
                                                        <span style="color:#16a34a; font-weight:600;">(Maks. {{ $maxOrderLimit }} per order)</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="bvm-qty-ctrl">
                                                <button type="button" class="bvm-qty-btn" onclick="stepBvmQty(-1)">−</button>
                                                <input type="number" id="bvm-qty-input" class="bvm-qty-input" value="1" min="1" data-max-limit="{{ $maxOrderLimit ?? '' }}" readonly>
                                                <button type="button" class="bvm-qty-btn" onclick="stepBvmQty(1)">+</button>
                                            </div>
                                        </div>

                                        <div id="bvm-error" class="bvm-error" style="display:none;">
                                            ⚠ Silakan pilih salah satu variasi terlebih dahulu.
                                        </div>
                                    </div>

                                    {{-- Footer Tombol Beli / Konfirmasi (Style sama persis Button Daftar Navbar) --}}
                                    <div class="bvm-footer">
                                        <button type="button" id="bvm-submit-btn" class="bvm-btn-primary" onclick="confirmBvmSubmit()">
                                            <span id="bvm-submit-label">Beli Sekarang</span>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path d="M5 12h14M12 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <style>
                                /* ── COMPACT MINIMALIS & MOBILE FRIENDLY VARIANT MODAL ── */
                                .buyle-variant-modal {
                                    position: fixed;
                                    inset: 0;
                                    z-index: 999999;
                                    display: flex;
                                    align-items: flex-end;
                                    justify-content: center;
                                    visibility: hidden;
                                    opacity: 0;
                                    transition: opacity 0.24s ease, visibility 0.24s ease;
                                }
                                .buyle-variant-modal.active {
                                    visibility: visible;
                                    opacity: 1;
                                }
                                .bvm-backdrop {
                                    position: absolute;
                                    inset: 0;
                                    background: rgba(15, 23, 42, 0.62);
                                    backdrop-filter: blur(4px);
                                    -webkit-backdrop-filter: blur(4px);
                                }
                                .bvm-sheet {
                                    position: relative;
                                    width: 100%;
                                    max-width: 480px;
                                    background: #ffffff;
                                    border-radius: 24px 24px 0 0;
                                    box-shadow: 0 -12px 48px rgba(0, 0, 0, 0.22);
                                    display: flex;
                                    flex-direction: column;
                                    max-height: 86vh;
                                    transform: translateY(100%);
                                    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
                                    z-index: 1;
                                    overflow: hidden;
                                }
                                .buyle-variant-modal.active .bvm-sheet {
                                    transform: translateY(0);
                                }

                                @media (min-width: 640px) {
                                    .buyle-variant-modal {
                                        align-items: center;
                                        padding: 1.5rem;
                                    }
                                    .bvm-sheet {
                                        border-radius: 20px;
                                        transform: scale(0.95);
                                        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
                                    }
                                    .buyle-variant-modal.active .bvm-sheet {
                                        transform: scale(1);
                                    }
                                    .bvm-handle-bar {
                                        display: none !important;
                                    }
                                }

                                .bvm-handle-bar {
                                    display: flex;
                                    justify-content: center;
                                    padding-top: 10px;
                                    padding-bottom: 4px;
                                }
                                .bvm-handle {
                                    width: 38px;
                                    height: 4px;
                                    background: #cbd5e1;
                                    border-radius: 99px;
                                }

                                .bvm-header {
                                    display: flex;
                                    align-items: center;
                                    gap: 0.85rem;
                                    padding: 0.85rem 1.25rem;
                                    border-bottom: 1px solid #f1f5f9;
                                    position: relative;
                                }
                                .bvm-thumb-wrap {
                                    width: 70px;
                                    height: 70px;
                                    border-radius: 12px;
                                    overflow: hidden;
                                    background: #f8fafc;
                                    border: 1.5px solid #e2e8f0;
                                    flex-shrink: 0;
                                    position: relative;
                                }
                                .bvm-thumb {
                                    width: 100%;
                                    height: 100%;
                                    object-fit: cover;
                                    transition: opacity 0.2s ease;
                                }
                                .bvm-thumb-badge {
                                    position: absolute;
                                    bottom: 2px;
                                    right: 2px;
                                    background: rgba(15, 23, 42, 0.8);
                                    color: #fff;
                                    font-size: 0.58rem;
                                    font-weight: 700;
                                    padding: 1px 4px;
                                    border-radius: 4px;
                                    letter-spacing: 0.02em;
                                }
                                .bvm-header-info {
                                    flex: 1;
                                    min-width: 0;
                                }
                                .bvm-price {
                                    font-family: 'Montserrat', sans-serif;
                                    font-size: 1.25rem;
                                    font-weight: 800;
                                    color: #1eb349;
                                    line-height: 1.2;
                                }
                                .bvm-stock {
                                    font-size: 0.78rem;
                                    font-weight: 600;
                                    color: #64748b;
                                    margin-top: 3px;
                                }
                                .bvm-selected-label {
                                    font-size: 0.78rem;
                                    color: #0f172a;
                                    font-weight: 600;
                                    margin-top: 3px;
                                    white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                }
                                .bvm-close-btn {
                                    width: 32px;
                                    height: 32px;
                                    border-radius: 50%;
                                    border: none;
                                    background: #f1f5f9;
                                    color: #64748b;
                                    cursor: pointer;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    transition: all 0.15s;
                                    flex-shrink: 0;
                                }
                                .bvm-close-btn:hover {
                                    background: #e2e8f0;
                                    color: #0f172a;
                                }

                                .bvm-body {
                                    padding: 1rem 1.25rem;
                                    overflow-y: auto;
                                    -webkit-overflow-scrolling: touch;
                                    max-height: calc(86vh - 180px);
                                }
                                .bvm-group {
                                    margin-bottom: 1.1rem;
                                }
                                .bvm-group-title {
                                    font-size: 0.78rem;
                                    font-weight: 700;
                                    color: #475569;
                                    margin-bottom: 0.55rem;
                                    text-transform: uppercase;
                                    letter-spacing: 0.04em;
                                }
                                .bvm-pills {
                                    display: flex;
                                    flex-wrap: wrap;
                                    gap: 0.5rem;
                                }
                                .bvm-pill {
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 0.45rem;
                                    padding: 0.45rem 0.85rem;
                                    border: 1.5px solid #e2e8f0;
                                    border-radius: 12px;
                                    background: #ffffff;
                                    color: #0f172a;
                                    font-size: 0.82rem;
                                    font-weight: 600;
                                    cursor: pointer;
                                    transition: all 0.16s ease;
                                    user-select: none;
                                }
                                .bvm-pill:hover:not(:disabled) {
                                    border-color: #1eb349;
                                    color: #1eb349;
                                    background: rgba(30, 179, 73, 0.04);
                                }
                                .bvm-pill.selected {
                                    border-color: #1eb349;
                                    background: linear-gradient(135deg, rgba(30, 179, 73, 0.12), rgba(165, 207, 55, 0.08));
                                    color: #15803d;
                                    box-shadow: 0 2px 8px rgba(30, 179, 73, 0.2);
                                }
                                .bvm-pill:disabled {
                                    opacity: 0.4;
                                    cursor: not-allowed;
                                    background: #f8fafc;
                                    border-style: dashed;
                                }
                                .bvm-pill-img {
                                    width: 22px;
                                    height: 22px;
                                    border-radius: 6px;
                                    object-fit: cover;
                                    border: 1px solid #e2e8f0;
                                    flex-shrink: 0;
                                }
                                .bvm-pill-diff {
                                    font-size: 0.7rem;
                                    opacity: 0.8;
                                }

                                .bvm-qty-row {
                                    display: flex;
                                    align-items: center;
                                    justify-content: space-between;
                                    padding-top: 0.85rem;
                                    margin-top: 0.5rem;
                                    border-top: 1px solid #f1f5f9;
                                }
                                .bvm-qty-label {
                                    font-size: 0.82rem;
                                    font-weight: 700;
                                    color: #0f172a;
                                }
                                .bvm-qty-sub {
                                    font-size: 0.72rem;
                                    color: #94a3b8;
                                }
                                .bvm-qty-ctrl {
                                    display: inline-flex;
                                    align-items: center;
                                    border: 1.5px solid #e2e8f0;
                                    border-radius: 10px;
                                    overflow: hidden;
                                    background: #fff;
                                }
                                .bvm-qty-btn {
                                    width: 32px;
                                    height: 32px;
                                    border: none;
                                    background: #f8fafc;
                                    color: #0f172a;
                                    font-size: 1rem;
                                    font-weight: 700;
                                    cursor: pointer;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    transition: background 0.15s;
                                }
                                .bvm-qty-btn:hover {
                                    background: #e2e8f0;
                                }
                                .bvm-qty-input {
                                    width: 44px;
                                    height: 32px;
                                    border: none;
                                    text-align: center;
                                    font-size: 0.88rem;
                                    font-weight: 700;
                                    color: #0f172a;
                                    background: #fff;
                                    pointer-events: none;
                                }

                                .bvm-error {
                                    color: #ef4444;
                                    font-size: 0.78rem;
                                    background: #fef2f2;
                                    border: 1px solid #fca5a5;
                                    border-radius: 8px;
                                    padding: 0.45rem 0.75rem;
                                    margin-top: 0.75rem;
                                }
                                .bvm-footer {
                                    padding: 0.85rem 1.25rem 1.15rem;
                                    border-top: 1px solid #f1f5f9;
                                    background: #ffffff;
                                }
                                .bvm-btn-primary {
                                    width: 100%;
                                    padding: 0.8rem 1.5rem;
                                    border-radius: 999px;
                                    border: none;
                                    background: linear-gradient(135deg, #1eb349, #a5cf37);
                                    color: #ffffff;
                                    font-family: 'Montserrat', sans-serif;
                                    font-size: 0.92rem;
                                    font-weight: 700;
                                    cursor: pointer;
                                    box-shadow: 0 4px 14px rgba(30, 179, 73, 0.35);
                                    transition: all 0.2s ease;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 0.5rem;
                                    letter-spacing: 0.02em;
                                }
                                .bvm-btn-primary:hover {
                                    box-shadow: 0 6px 20px rgba(30, 179, 73, 0.45);
                                    transform: translateY(-1px);
                                }
                                .bvm-btn-primary:active {
                                    transform: translateY(0);
                                }
                            </style>
                            @endif

                            <script>
                                (function () {
                                    var hasVariants = {{ ($service->variantOptions && $service->variantOptions->count() > 0) ? 'true' : 'false' }};
                                    window._pdCurrentAction = 'buy';

                                    window.stepPdQty = function(delta) {
                                        var input = document.getElementById('qty_input');
                                        if (!input) return;
                                        var current = parseInt(input.value) || 1;
                                        var min = parseInt(input.min) || 1;
                                        var maxLimit = parseInt(input.dataset.maxLimit || '') || null;
                                        var maxAttr = parseInt(input.max) || null;
                                        var maxVal = maxLimit;
                                        if (maxAttr && (!maxVal || maxAttr < maxVal)) maxVal = maxAttr;

                                        var next = current + delta;
                                        if (next < min) next = min;
                                        if (maxVal && next > maxVal) next = maxVal;
                                        input.value = next;
                                        var bvmQty = document.getElementById('bvm-qty-input');
                                        if (bvmQty) bvmQty.value = next;
                                    };

                                    window.openPdVariantModal = function(action) {
                                        var modal = document.getElementById('pd-variant-modal');
                                        if (!modal) return;
                                        window._pdCurrentAction = action || 'buy';

                                        var submitLabel = document.getElementById('bvm-submit-label');
                                        if (submitLabel) {
                                            submitLabel.textContent = (window._pdCurrentAction === 'cart') ? 'Tambah Keranjang' : 'Beli Sekarang';
                                        }

                                        // Sync quantity from main page, honoring max limit
                                        var mainQty = document.getElementById('qty_input');
                                        var modalQty = document.getElementById('bvm-qty-input');
                                        if (mainQty && modalQty) {
                                            var qVal = parseInt(mainQty.value) || 1;
                                            var maxLimit = parseInt(modalQty.dataset.maxLimit || mainQty.dataset.maxLimit || '') || null;
                                            if (maxLimit && qVal > maxLimit) qVal = maxLimit;
                                            modalQty.value = qVal;
                                            mainQty.value = qVal;
                                        }

                                        // Auto-select active or first available variant
                                        var currentVid = document.getElementById('pd_selected_variant_id').value;
                                        var activePill = currentVid ? modal.querySelector('.bvm-pill[data-variant-id="' + currentVid + '"]') : null;
                                        if (!activePill) {
                                            activePill = modal.querySelector('.bvm-pill:not(:disabled)');
                                        }
                                        if (activePill) {
                                            selectBvmVariant(activePill, false);
                                        }

                                        modal.style.display = 'flex';
                                        requestAnimationFrame(function() {
                                            modal.classList.add('active');
                                            document.body.style.overflow = 'hidden';
                                        });
                                    };

                                    window.closePdVariantModal = function() {
                                        var modal = document.getElementById('pd-variant-modal');
                                        if (!modal) return;
                                        modal.classList.remove('active');
                                        document.body.style.overflow = '';
                                        setTimeout(function() {
                                            if (!modal.classList.contains('active')) {
                                                modal.style.display = 'none';
                                            }
                                        }, 280);
                                    };

                                    window.selectBvmVariant = function(btn, syncToPage) {
                                        if (typeof syncToPage === 'undefined') syncToPage = true;
                                        if (btn.disabled) return;
                                        var group = btn.closest('.bvm-pills');
                                        if (group) {
                                            group.querySelectorAll('.bvm-pill').forEach(function(p) { p.classList.remove('selected'); });
                                        }
                                        btn.classList.add('selected');

                                        var vid = btn.dataset.variantId;
                                        var price = parseFloat(btn.dataset.price) || 0;
                                        var priceFormatted = btn.dataset.priceFormatted;
                                        var stock = btn.dataset.stock;
                                        var label = btn.dataset.label;
                                        var imgUrl = btn.dataset.img;
                                        var hasCustomImg = btn.dataset.hasCustomImg === '1';

                                        // Update modal image preview
                                        var thumbImg = document.getElementById('bvm-thumb-img');
                                        var thumbBadge = document.getElementById('bvm-thumb-badge');
                                        if (thumbImg && imgUrl) {
                                            thumbImg.src = imgUrl;
                                        }
                                        if (thumbBadge) {
                                            thumbBadge.style.display = hasCustomImg ? 'block' : 'none';
                                        }

                                        // Update modal price
                                        var priceDisplay = document.getElementById('bvm-price-display');
                                        if (priceDisplay && priceFormatted) {
                                            priceDisplay.textContent = priceFormatted;
                                        }

                                        // Update modal stock info
                                        var stockDisplay = document.getElementById('bvm-stock-display');
                                        if (stockDisplay) {
                                            if (stock === '' || stock === null) {
                                                stockDisplay.innerHTML = '<span style="color:#10b981;">• Stok Tersedia</span>';
                                            } else {
                                                var sNum = parseInt(stock);
                                                if (sNum > 0) {
                                                    stockDisplay.innerHTML = '<span style="color:#10b981;">• Sisa ' + sNum + ' buah</span>';
                                                } else {
                                                    stockDisplay.innerHTML = '<span style="color:#ef4444;">• Stok Habis</span>';
                                                }
                                            }
                                        }

                                        // Update label info
                                        var labelDisplay = document.getElementById('bvm-selected-label');
                                        if (labelDisplay) {
                                            labelDisplay.innerHTML = '<span style="color:#15803d;font-weight:700;">✓ Dipilih:</span> ' + label;
                                        }

                                        // Hide error in modal
                                        var err = document.getElementById('bvm-error');
                                        if (err) err.style.display = 'none';

                                        // Sync to main form
                                        document.getElementById('pd_selected_variant_id').value = vid;

                                        // Sync to main page
                                        if (syncToPage) {
                                            var pagePill = document.querySelector('.pd-variant-pill[data-variant-id="' + vid + '"]');
                                            if (pagePill) {
                                                var parent = pagePill.closest('.pd-attr-content');
                                                if (parent) {
                                                    parent.querySelectorAll('.pd-variant-pill').forEach(function(p) { p.classList.remove('selected'); });
                                                }
                                                pagePill.classList.add('selected');
                                            }

                                            var pagePriceEl = document.querySelector('.pd-price-main');
                                            if (pagePriceEl && price > 0) {
                                                pagePriceEl.textContent = 'Rp' + price.toLocaleString('id-ID');
                                            }

                                            var pageStockEl = document.getElementById('pd-stock-info');
                                            if (pageStockEl) {
                                                if (stock === '' || stock === null) {
                                                    pageStockEl.innerHTML = '<span style="color:#10B981;">Stok Tersedia</span>';
                                                } else {
                                                    var sNum = parseInt(stock);
                                                    pageStockEl.innerHTML = (sNum > 0) ? ('Sisa ' + sNum + ' buah') : '<span style="color:#EE4D2D;">Habis</span>';
                                                }
                                            }

                                            var infoEl = document.getElementById('pd-variant-info');
                                            if (infoEl) {
                                                infoEl.style.display = 'block';
                                                infoEl.textContent = '✓ Dipilih: ' + label;
                                            }
                                            var pageErr = document.getElementById('pd-variant-error');
                                            if (pageErr) pageErr.style.display = 'none';
                                        }
                                    };

                                    window.stepBvmQty = function(delta) {
                                        var input = document.getElementById('bvm-qty-input');
                                        var mainQty = document.getElementById('qty_input');
                                        if (!input) return;
                                        var current = parseInt(input.value) || 1;
                                        var next = current + delta;
                                        if (next < 1) next = 1;

                                        var maxLimit = parseInt(input.dataset.maxLimit || (mainQty ? mainQty.dataset.maxLimit : '')) || null;
                                        if (maxLimit && next > maxLimit) {
                                            next = maxLimit;
                                        }

                                        var selectedPill = document.querySelector('.bvm-pill.selected');
                                        if (selectedPill && selectedPill.dataset.stock) {
                                            var maxStock = parseInt(selectedPill.dataset.stock);
                                            if (!isNaN(maxStock) && maxStock > 0 && next > maxStock) {
                                                next = maxStock;
                                            }
                                        }

                                        input.value = next;
                                        if (mainQty) mainQty.value = next;
                                    };

                                    window.confirmBvmSubmit = function() {
                                        var vidEl = document.getElementById('pd_selected_variant_id');
                                        var vid = vidEl ? vidEl.value : '';
                                        if (!vid) {
                                            var firstPill = document.querySelector('.bvm-pill:not(:disabled)');
                                            if (firstPill) {
                                                selectBvmVariant(firstPill, true);
                                                vid = firstPill.dataset.variantId;
                                            }
                                        }
                                        if (!vid) {
                                            var err = document.getElementById('bvm-error');
                                            if (err) {
                                                err.style.display = 'block';
                                                err.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                                            }
                                            return false;
                                        }

                                        // Sync qty
                                        var modalQty = document.getElementById('bvm-qty-input');
                                        var mainQty = document.getElementById('qty_input');
                                        if (modalQty && mainQty) {
                                            mainQty.value = modalQty.value;
                                        }

                                        var action = window._pdCurrentAction || 'buy';
                                        var actionInput = document.getElementById('pd_action_input');
                                        if (actionInput) {
                                            actionInput.value = action;
                                        } else {
                                            var actEl = document.createElement('input');
                                            actEl.type = 'hidden';
                                            actEl.name = 'action';
                                            actEl.id = 'pd_action_input';
                                            actEl.value = action;
                                            document.getElementById('pd-cart-form').appendChild(actEl);
                                        }

                                        closePdVariantModal();
                                        document.getElementById('pd-cart-form').submit();
                                    };

                                    window.selectPdVariant = function (btn) {
                                        var vid = btn.dataset.variantId;
                                        var modalPill = document.querySelector('.bvm-pill[data-variant-id="' + vid + '"]');
                                        if (modalPill) {
                                            selectBvmVariant(modalPill, true);
                                        } else {
                                            var parent = btn.closest('.pd-attr-content');
                                            parent.querySelectorAll('.pd-variant-pill').forEach(function (p) {
                                                p.classList.remove('selected');
                                            });
                                            btn.classList.add('selected');
                                            document.getElementById('pd_selected_variant_id').value = vid;
                                        }
                                    };

                                    window.submitPdCart = function (action) {
                                        if (hasVariants) {
                                            // Pop up the interactive variant selector
                                            openPdVariantModal(action);
                                            return false;
                                        }
                                        var actionInput = document.getElementById('pd_action_input');
                                        if (actionInput) {
                                            actionInput.value = action;
                                        }
                                        document.getElementById('pd-cart-form').submit();
                                    };

                                    document.addEventListener('keydown', function(e) {
                                        if (e.key === 'Escape') closePdVariantModal();
                                    });
                                })();
                            </script>
                            @if($isWarActive)
                                <script>
                                    (function () {
                                        const targetTime = {{ $warTimestamp }};
                                        function updateWarCountdown() {
                                            const now = Date.now();
                                            const diff = targetTime - now;

                                            if (diff <= 0) {
                                                const warCard = document.getElementById('ticketWarCard');
                                                if (warCard) {
                                                    warCard.style.transition = 'all 0.4s ease';
                                                    warCard.style.opacity = '0';
                                                    setTimeout(() => warCard.style.display = 'none', 400);
                                                }

                                                const lockedBtn = document.getElementById('btnTicketWarLocked');
                                                const normalActions = document.getElementById('normalActions');

                                                if (lockedBtn) lockedBtn.style.display = 'none';
                                                if (normalActions) normalActions.style.display = 'flex';

                                                try {
                                                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                                                    if (AudioCtx) {
                                                        const ctx = new AudioCtx();
                                                        const osc = ctx.createOscillator();
                                                        const gain = ctx.createGain();
                                                        osc.type = 'sine';
                                                        osc.frequency.setValueAtTime(880, ctx.currentTime);
                                                        gain.gain.setValueAtTime(0.3, ctx.currentTime);
                                                        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
                                                        osc.connect(gain); gain.connect(ctx.destination);
                                                        osc.start(); osc.stop(ctx.currentTime + 0.4);
                                                    }
                                                } catch (e) { }

                                                return;
                                            }

                                            const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                                            const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                            const s = Math.floor((diff % (1000 * 60)) / 1000);

                                            const dEl = document.getElementById('warDays');
                                            const hEl = document.getElementById('warHours');
                                            const mEl = document.getElementById('warMins');
                                            const sEl = document.getElementById('warSecs');

                                            if (dEl) dEl.textContent = String(d).padStart(2, '0');
                                            if (hEl) hEl.textContent = String(h).padStart(2, '0');
                                            if (mEl) mEl.textContent = String(m).padStart(2, '0');
                                            if (sEl) sEl.textContent = String(s).padStart(2, '0');

                                            setTimeout(updateWarCountdown, 1000);
                                        }
                                        updateWarCountdown();
                                    })();
                                </script>
                            @endif

                            <style>
                                @keyframes shake {

                                    0%,
                                    100% {
                                        transform: translateX(0);
                                    }

                                    20% {
                                        transform: translateX(-6px);
                                    }

                                    40% {
                                        transform: translateX(6px);
                                    }

                                    60% {
                                        transform: translateX(-4px);
                                    }

                                    80% {
                                        transform: translateX(4px);
                                    }
                                }
                            </style>
                        @else
                            <div class="pd-price-box">
                                <div class="pd-price-main">Konsultasi Penawaran</div>
                            </div>
                            <a href="javascript:void(0)" onclick="openOrderModal('Produk: {{ addslashes($service->name) }}')"
                                class="pd-btn pd-btn-primary" style="margin-top:2rem; width:100%;">
                                Tanya via WhatsApp
                            </a>
                        @endif
                    </div>
                </div>

                {{-- TABS --}}
                <div class="pd-container pd-tabs-block" style="padding:0;">
                    <div class="pd-tabs-header">
                        <button id="tabBtn-desc" class="pd-tab-btn active" onclick="switchTab('desc')">Deskripsi</button>
                        <button id="tabBtn-reviews" class="pd-tab-btn" onclick="switchTab('reviews')">
                            Ulasan
                            <span id="tabReviewsBadge" class="pd-tab-badge">{{ $realReviewsCount }}</span>
                        </button>
                        <button id="tabBtn-creator" class="pd-tab-btn" onclick="switchTab('creator')">Creator</button>
                    </div>
                    <div class="pd-tab-body">
                        <div id="tab-desc" class="pd-tab-content" style="display:block;">
                            @if(is_array($service->specifications) && count($service->specifications) > 0)
                                <table class="pd-specs">
                                    @foreach($service->specifications as $spec)
                                        <tr>
                                            <td>{{ $spec['key'] }}</td>
                                            <td>{{ $spec['value'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                            <div class="pd-desc-content">
                                {{-- SEO Prefix Paragraph (Khusus desktop, otomatis tersembunyi di mobile) --}}
                                @php
                                    $catName = $service->subCategory?->name ?? $service->category?->name ?? $service->type_label ?? 'Produk Digital';
                                @endphp
                                <div class="pd-seo-callout">
                                    Cari berbagai macam pilihan <strong>{{ $catName }}</strong> termurah dan terpercaya
                                    hanya di
                                    <a href="https://buyle.id" title="BUYLE.ID"><strong>BUYLE.ID</strong></a>.
                                </div>

                                @if($service->short_desc)
                                    <p><b>{{ $service->short_desc }}</b></p>
                                @endif
                                {!! $service->description ?? 'Belum ada deskripsi mendetail.' !!}

                                {{-- Video Produk (Minimalist YouTube / TikTok Embed) --}}
                                @include('partials.product_video', ['videoUrl' => $service->tiktok_video_url])

                                {{-- Poster Landscape Banner (og_image) --}}
                                @if(!empty($service->og_image))
                                    <div
                                        style="margin-top: 1.5rem; margin-bottom: 1.5rem; width: 100%; aspect-ratio: 16/9; border-radius: 14px; overflow: hidden; background: #F1F5F9; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                                        <img src="{{ asset('storage/' . $service->og_image) }}" alt="{{ $service->name }}"
                                            style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                    </div>
                                @endif

                                {{-- Security Notice Paragraph --}}
                                <p class="pd-security-notice">
                                    <svg width="18" height="18" fill="none" stroke="#D97706" stroke-width="2"
                                        viewBox="0 0 24 24" style="flex-shrink:0; margin-top:2px;">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                    <span>
                                        <strong style="color:#B45309;">Transaksi Aman:</strong> Selalu lakukan pembayaran
                                        melalui platform resmi <strong>buyle.id</strong> demi keamanan transaksi Anda.
                                    </span>
                                </p>
                            </div>
                        </div>

                        {{-- TAB RATING & ULASAN --}}
                        <div id="tab-reviews" class="pd-tab-content" style="display:none;">

                            {{-- SUMMARY CARD --}}
                            @php
                                $hasRealScore = ($realReviewsAvg !== null && $realReviewsAvg > 0);
                            @endphp
                            <div class="pd-reviews-summary-card">
                                <div class="pd-score-big-box">
                                    <div class="pd-score-big" id="reviewsSummaryScore">
                                        {{ $hasRealScore ? number_format($realReviewsAvg, 1) : '0.0' }}
                                    </div>
                                    <div class="pd-stars-row" id="reviewsSummaryStars">
                                        @if($hasRealScore)
                                            @php
                                                $roundedStar = (int) round($realReviewsAvg);
                                            @endphp
                                            @for($s = 1; $s <= 5; $s++)
                                                @if($s <= $roundedStar)
                                                    <span style="color:#F59E0B;">★</span>
                                                @else
                                                    <span style="color:#CBD5E1;">★</span>
                                                @endif
                                            @endfor
                                        @else
                                            <span style="color:#CBD5E1;">★★★★★</span>
                                        @endif
                                    </div>
                                    <div class="pd-score-sub"><span id="reviewsSummaryTotal">{{ $realReviewsCount }}</span>
                                        Ulasan Pembeli</div>
                                </div>

                                <div class="pd-progress-bars">
                                    @for($star = 5; $star >= 1; $star--)
                                        <div class="pd-bar-row">
                                            <div class="pd-bar-label">{{ $star }} ★</div>
                                            <div class="pd-bar-track">
                                                <div class="pd-bar-fill" id="barFill-{{ $star }}" style="width: 0%;"></div>
                                            </div>
                                            <div class="pd-bar-count" id="barCount-{{ $star }}">0</div>
                                        </div>
                                    @endfor
                                </div>

                                <div class="pd-reviews-action-box" id="pdReviewsActionBox">
                                    @if($canReview)
                                        <button type="button" onclick="toggleWriteReviewForm()" class="pd-btn pd-btn-primary"
                                            style="width:100%;height:42px;font-size:0.85rem;border-radius:99px;font-weight:700;">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2"
                                                viewBox="0 0 24 24">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            <span>{{ $userReview ? 'Edit Ulasan' : 'Tulis Ulasan' }}</span>
                                        </button>
                                        @if($userReview)
                                            <div
                                                style="font-size:0.75rem;color:#15803d;font-weight:600;margin-top:5px;display:flex;align-items:center;justify-content:center;gap:4px;flex-wrap:wrap;">
                                                <span>✓ Anda sudah mengulas ({{ $userReview->rating }}★)</span>
                                                <span
                                                    style="color:#d97706;background:#fef3c7;padding:1px 6px;border-radius:6px;font-size:0.7rem;">Sisa
                                                    1x edit</span>
                                            </div>
                                        @endif
                                    @elseif($userReview)
                                        <div
                                            style="background:#F0FDF4;border:1.5px solid #BBF7D0;border-radius:14px;padding:0.75rem 0.85rem;text-align:center;">
                                            <div
                                                style="font-size:0.85rem;font-weight:800;color:#15803D;display:flex;align-items:center;justify-content:center;gap:6px;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#15803D">
                                                    <path
                                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                                </svg>
                                                <span>Ulasan Tersimpan ({{ $userReview->rating }}★)</span>
                                            </div>
                                            <div style="font-size:0.72rem;color:#64748B;margin-top:3px;font-weight:500;">
                                                Batas pengeditan ulasan tercapai (maksimal 1x edit).
                                            </div>
                                        </div>
                                    @elseif(auth()->check())
                                        <div
                                            style="background:#F1F5F9;border-radius:12px;padding:0.75rem;font-size:0.78rem;color:#64748B;line-height:1.4;">
                                            Hanya pembeli yang sudah menyelesaikan pesanan produk ini yang dapat memberikan
                                            rating & ulasan.
                                        </div>
                                    @else
                                        <a href="{{ route('login') }}" class="pd-btn pd-btn-outline"
                                            style="width:100%;height:38px;font-size:0.82rem;border-radius:99px;">
                                            Hanya pembeli yang sudah menyelesaikan pesanan produk ini yang dapat memberikan
                                            rating & ulasan.

                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- WRITE REVIEW FORM (COLLAPSIBLE FOR BUYERS) --}}
                            @if($canReview)
                                <div id="writeReviewCard" class="pd-write-review-card">
                                    <div
                                        style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;border-bottom:1px solid #DCFCE7;padding-bottom:0.75rem;">
                                        <h4
                                            style="margin:0;font-size:1.05rem;font-weight:800;color:#0F172A;display:flex;align-items:center;gap:6px;">
                                            <span style="color:#1EB349;">✍️</span>
                                            {{ $userReview ? 'Edit Rating & Ulasan (Kesempatan Terakhir)' : 'Tulis Rating & Ulasan Produk' }}
                                        </h4>
                                        <button type="button" onclick="toggleWriteReviewForm()"
                                            style="background:none;border:none;color:#64748B;font-size:1.25rem;cursor:pointer;">&times;</button>
                                    </div>

                                    {{-- Star Picker --}}
                                    <div style="margin-bottom:1rem;text-align:center;">
                                        <label
                                            style="display:block;font-size:0.85rem;font-weight:700;color:#334155;margin-bottom:6px;">Berapa
                                            bintang yang Anda berikan?</label>
                                        <div style="display:flex;justify-content:center;gap:8px;" id="tabStarContainer">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" class="tab-star-btn" data-star="{{ $i }}"
                                                    onclick="selectTabRating({{ $i }})" onmouseenter="previewTabRating({{ $i }})"
                                                    onmouseleave="resetPreviewTabRating()"
                                                    style="background:none;border:none;cursor:pointer;padding:4px;transition:transform 0.15s ease;">
                                                    <svg width="36" height="36"
                                                        fill="{{ ($userReview && $userReview->rating >= $i) ? '#f59e0b' : '#e2e8f0' }}"
                                                        stroke="{{ ($userReview && $userReview->rating >= $i) ? '#f59e0b' : '#cbd5e1' }}"
                                                        stroke-width="1.5" viewBox="0 0 24 24" class="tab-star-svg-{{ $i }}"
                                                        style="transition:all 0.2s ease;">
                                                        <polygon
                                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                                    </svg>
                                                </button>
                                            @endfor
                                        </div>
                                        <div id="tabRatingLabel"
                                            style="font-size:0.88rem;font-weight:700;color:#f59e0b;min-height:22px;margin-top:4px;">
                                            {{ $userReview ? ($userReview->rating . ' Bintang') : 'Pilih Bintang (1 - 5)' }}
                                        </div>
                                        <input type="hidden" id="tabSelectedRatingValue"
                                            value="{{ $userReview ? $userReview->rating : 0 }}">
                                    </div>

                                    {{-- Review Textarea --}}
                                    <div style="margin-bottom:1rem;">
                                        <label
                                            style="display:block;font-size:0.82rem;font-weight:700;color:#334155;margin-bottom:4px;">Ulasan
                                            Anda:</label>
                                        <textarea id="tabReviewText" rows="3"
                                            placeholder="Ceritakan kepuasan Anda mengenai produk, kecepatan pengerjaan, atau keramahan creator..."
                                            style="width:100%;box-sizing:border-box;border:1px solid #CBD5E1;border-radius:12px;padding:0.8rem;font-size:0.88rem;font-family:inherit;color:#0F172A;resize:none;outline:none;background:#fff;transition:border-color .2s;"
                                            onfocus="this.style.borderColor='#1EB349'"
                                            onblur="this.style.borderColor='#CBD5E1'">{{ $userReview?->review_text ?? '' }}</textarea>
                                    </div>

                                    {{-- Single Photo Upload with Max Compression (Max 1 Image) --}}
                                    <div style="margin-bottom:1.25rem;">
                                        <div
                                            style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                                            <label style="font-size:0.82rem;font-weight:700;color:#334155;">Upload Foto (Maks 1
                                                Foto):</label>
                                            <span
                                                style="font-size:0.74rem;color:#15803D;background:#DCFCE7;padding:2px 8px;border-radius:99px;font-weight:600;">⚡
                                                Otomatis Kompres Maksimal</span>
                                        </div>

                                        <div id="tabReviewUploadTrigger"
                                            onclick="document.getElementById('tabReviewPhotoInput').click()"
                                            style="border:2px dashed #CBD5E1;border-radius:12px;padding:0.85rem;text-align:center;cursor:pointer;background:#fff;transition:all .2s;"
                                            onmouseover="this.style.borderColor='#1EB349';this.style.background='#F0FDF4'"
                                            onmouseout="this.style.borderColor='#CBD5E1';this.style.background='#fff'">
                                            <div
                                                style="display:flex;align-items:center;justify-content:center;gap:6px;color:#475569;font-size:0.84rem;font-weight:600;">
                                                <svg width="20" height="20" fill="none" stroke="#1EB349" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                                    <polyline points="21 15 16 10 5 21" />
                                                </svg>
                                                <span>Pilih 1 Foto Bukti/Review</span>
                                            </div>
                                            <div style="font-size:0.74rem;color:#94A3B8;margin-top:2px;">Format JPG, PNG, atau
                                                WebP (akan langsung dikompresi)</div>
                                        </div>
                                        <input type="file" id="tabReviewPhotoInput" accept="image/*" style="display:none;"
                                            onchange="handleTabReviewPhoto(this)">

                                        {{-- Photo Preview Box --}}
                                        <div id="tabReviewPhotoPreviewWrap"
                                            style="{{ ($userReview && !empty($userReview->review_images)) ? 'display:block;' : 'display:none;' }}margin-top:10px;position:relative;width:90px;height:90px;border-radius:12px;overflow:hidden;border:1px solid #CBD5E1;">
                                            <img id="tabReviewPhotoPreview"
                                                src="{{ ($userReview && !empty($userReview->review_images)) ? asset('storage/' . $userReview->review_images[0]) : '' }}"
                                                alt="preview" style="width:100%;height:100%;object-fit:cover;">
                                            <button type="button" onclick="removeTabReviewPhoto()"
                                                style="position:absolute;top:3px;right:3px;background:rgba(220,38,38,0.85);color:#fff;border:none;border-radius:50%;width:22px;height:22px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:14px;line-height:1;">&times;</button>
                                        </div>
                                    </div>

                                    <div style="display:flex;gap:0.75rem;justify-content:flex-end;">
                                        <button type="button" onclick="toggleWriteReviewForm()" class="pd-btn pd-btn-outline"
                                            style="height:40px;font-size:0.85rem;padding:0 1.25rem;">Batal</button>
                                        <button type="button" id="btnSubmitTabReview" onclick="submitTabReview()"
                                            class="pd-btn pd-btn-primary"
                                            style="height:40px;font-size:0.85rem;padding:0 1.5rem;font-weight:700;">
                                            {{ $userReview ? 'Simpan Perubahan' : 'Kirim Ulasan' }}
                                        </button>
                                    </div>
                                </div>
                            @endif

                            {{-- FILTER PILLS --}}
                            <div class="pd-review-filter-group">
                                <button type="button" class="pd-filter-pill active"
                                    onclick="applyReviewFilter('all', this)">Semua (<span
                                        id="filterCountAll">{{ $realReviewsCount }}</span>)</button>
                                <button type="button" class="pd-filter-pill" onclick="applyReviewFilter('media', this)">📷
                                    Dengan Foto (<span id="filterCountMedia">0</span>)</button>
                                <button type="button" class="pd-filter-pill" onclick="applyReviewFilter('5', this)">5
                                    ★</button>
                                <button type="button" class="pd-filter-pill" onclick="applyReviewFilter('4', this)">4
                                    ★</button>
                                <button type="button" class="pd-filter-pill" onclick="applyReviewFilter('3', this)">3
                                    ★</button>
                                <button type="button" class="pd-filter-pill" onclick="applyReviewFilter('2', this)">2
                                    ★</button>
                                <button type="button" class="pd-filter-pill" onclick="applyReviewFilter('1', this)">1
                                    ★</button>
                            </div>

                            {{-- REVIEWS FEED CONTAINER --}}
                            <div id="reviewsListContainer" class="pd-reviews-list">
                                {{-- Skeleton Loading --}}
                                <div id="reviewsLoadingSkeleton"
                                    style="display:none;padding:2rem;text-align:center;color:#64748B;">
                                    <div
                                        style="display:inline-block;width:32px;height:32px;border:3px solid #E2E8F0;border-top-color:#1EB349;border-radius:50%;animation:spin 0.8s linear infinite;">
                                    </div>
                                    <div style="margin-top:0.75rem;font-size:0.85rem;font-weight:600;">Memuat ulasan...
                                    </div>
                                </div>

                                {{-- Empty state --}}
                                <div id="reviewsEmptyState"
                                    style="display:none;text-align:center;padding:3rem 1.5rem;background:#F8FAFC;border-radius:16px;border:1px dashed #CBD5E1;">
                                    <div style="font-size:2.5rem;margin-bottom:0.5rem;">💬</div>
                                    <h4 style="margin:0 0 0.35rem;font-size:1.05rem;font-weight:700;color:#0F172A;">Belum
                                        Ada Ulasan</h4>
                                    <p style="margin:0;font-size:0.85rem;color:#64748B;">Belum ada ulasan yang sesuai dengan
                                        filter ini.</p>
                                </div>

                                <div id="reviewsListFeed" class="pd-reviews-list"></div>
                            </div>

                            {{-- LOAD MORE BUTTON (PAGINATION LAZY LOAD) --}}
                            <div id="reviewsLoadMoreWrap" style="text-align:center;margin-top:1.5rem;display:none;">
                                <button type="button" id="btnLoadMoreReviews" onclick="loadMoreReviews()"
                                    class="pd-btn pd-btn-outline"
                                    style="height:42px;font-size:0.88rem;padding:0 2rem;border-radius:99px;font-weight:600;">
                                    Muat Lebih Banyak Ulasan
                                </button>
                            </div>

                            {{-- Sentinel for Infinite Scroll / Lazy Load --}}
                            <div id="reviewsScrollSentinel" style="height:10px;"></div>

                        </div>
                        <div id="tab-creator" class="pd-tab-content" style="display:none;">
                            @if(isset($service->seller) && $service->seller)
                                @php $seller = $service->seller;
                                $cp = $seller->creatorProfile; @endphp
                                <div style="display:flex; gap:1.5rem; align-items:flex-start;">
                                    @if($seller->avatar)
                                        <img src="{{ asset('storage/' . $seller->avatar) }}" alt="{{ $seller->name }}"
                                            style="width:80px;height:80px;border-radius:50%;object-fit:cover;">
                                    @else
                                        <div
                                            style="width:80px;height:80px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:#64748b;font-weight:600;">
                                            {{ strtoupper(substr($seller->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h3 style="margin:0 0 0.5rem; font-size:1.2rem;">
                                            {{ optional($cp)->store_name ?: $seller->name }}
                                        </h3>
                                        <p
                                            style="margin:0 0 1rem; color:var(--text-muted); font-size:0.95rem; line-height:1.6;">
                                            {{ optional($cp)->store_description ?: 'Creator ini belum menuliskan deskripsi profilnya.' }}
                                        </p>
                                        <a href="{{ route('store.show', optional($cp)->store_slug ?? '#') }}"
                                            class="pd-btn pd-btn-outline" style="height:38px;font-size:0.85rem;">Kunjungi Profil
                                            Lengkap</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Creator Card --}}
            <div class="pd-sidebar-col">
                @php 
                                                                                                                                                                                                        $seller = $service->seller;
                    $cp = $seller?->creatorProfile;
                    $displaySellerName = optional($cp)->store_name ?: ($seller?->name ?? ($settings['site_name'] ?? 'buyle.id Official'));
                    $displayPhone = $seller?->phone ?? ($wa?->phone_number ?? '');
                    $bannerUrl = optional($cp)->store_banner_1 ? asset('storage/' . $cp->store_banner_1) : null;
                @endphp
                <div class="pd-creator-card"
                    style="background:#fff; border-radius:24px; overflow:hidden; border: 1.5px solid var(--border); box-shadow:0 8px 30px rgba(0,0,0,0.06);">
                    @php
                        $isVerified = $cp ? $cp->isVerified() : false;
                        $tierData = $cp ? $cp->getTierInfo() : ['badge' => 'Perintis', 'header_bg' => 'linear-gradient(135deg, #475569 0%, #64748b 100%)', 'icon_svg' => ''];
                        $ratingStats = $cp ? $cp->getRatingStats() : ['rating' => null, 'count' => 0];
                    @endphp
                    <!-- Banner Top Header matching reference design -->
                    <div
                        style="width:100%; height:44px; background:{{ $tierData['header_bg'] ?? 'linear-gradient(135deg, #475569 0%, #64748b 100%)' }}; padding: 0 1rem; display: flex; align-items: center; justify-content: {{ $isVerified ? 'space-between' : 'flex-end' }};">
                        @if($isVerified)
                            <div
                                style="display: flex; align-items: center; gap: 5px; color: #ffffff; font-weight: 700; font-size: 0.75rem; white-space: nowrap;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="#facc15" stroke="#facc15"
                                    stroke-width="1">
                                    <path
                                        d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-5.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                                </svg>
                                <span>Verified Creator</span>
                            </div>
                        @endif
                        <div
                            style="display: flex; align-items: center; gap: 5px; color: #ffffff; font-weight: 700; font-size: 0.75rem; white-space: nowrap;">
                            {!! $tierData['icon_svg'] ?? '<svg width="14" height="14" viewBox="0 0 24 24" fill="#facc15"><path d="M12 2l2.4 5.3 5.8.5-4.4 3.9 1.3 5.6-5.1-3-5.1 3 1.3-5.6-4.4-3.9 5.8-.5z"/></svg>' !!}
                            <span>{{ $tierData['badge'] }}</span>
                        </div>
                    </div>

                    <!-- Avatar & Rating Pill: overlap HANYA SEDIKIT di bawah bar -->
                    <div style="margin-top:-18px; display:flex; justify-content:center; position:relative; z-index:2;">
                        <div style="position:relative; display:inline-block;">
                            @if($seller?->avatar)
                                <img src="{{ asset('storage/' . $seller->avatar) }}"
                                    style="width:72px; height:72px; border-radius:50%; border:3px solid #fff; object-fit:cover; background:#fff; box-shadow:0 4px 16px rgba(0,0,0,0.12); display:block;">
                            @else
                                <div
                                    style="width:72px; height:72px; border-radius:50%; border:3px solid #fff; display:flex; align-items:center; justify-content:center; font-size:1.4rem; font-weight:800; color:#334155; background:#f1f5f9; box-shadow:0 4px 16px rgba(0,0,0,0.12);">
                                    {{ strtoupper(substr($displaySellerName, 0, 2)) }}
                                </div>
                            @endif
                            {{-- Rating pill badge top-right of avatar --}}
                            @if(!empty($ratingStats['rating']))
                                <div
                                    style="position:absolute; top:-4px; right:-20px; background:{{ $tierData['header_bg'] ?? 'linear-gradient(135deg, #475569, #64748b)' }}; color:#fff; font-size:0.73rem; font-weight:800; padding:0.2rem 0.55rem; border-radius:999px; display:flex; align-items:center; gap:3px; box-shadow:0 2px 8px rgba(0,0,0,0.18); white-space:nowrap;">
                                    <span>{{ $ratingStats['rating'] }}</span>
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="#facc15" stroke="#facc15">
                                        <path d="M12 2l2.4 5.3 5.8.5-4.4 3.9 1.3 5.6-5.1-3-5.1 3 1.3-5.6-4.4-3.9 5.8-.5z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div style="text-align:center; padding: 0.85rem 1.25rem 1.25rem;">
                        <div
                            style="font-size:1.25rem; font-weight:800; color:var(--text-main); margin-bottom:0.25rem; font-family: var(--font, 'Montserrat', sans-serif); display:inline-flex; align-items:center; justify-content:center; gap:0.4rem;">
                            <span>{{ $displaySellerName }}</span>
                            @if($isVerified)
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="#0D9488" title="Verified Creator">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                            @endif
                        </div>

                        {{-- Bio / Deskripsi Creator --}}
                        <div
                            style="font-size:0.85rem; color:var(--text-muted); line-height:1.4; margin-bottom:0.75rem; text-align:center;">
                            {{ optional($cp)->store_description ?: 'Digital Agency Pengusaha Indonesia' }}
                        </div>

                        {{-- Status Keaktifan Realtime --}}
                        <div style="font-size:0.8rem; margin-top:0.75rem; margin-bottom:1.25rem;">
                            @if($seller && $seller->last_seen_at && $seller->last_seen_at->gt(now()->subMinutes(10)))
                                <span
                                    style="color: #1eb349; font-weight: 600; display:inline-flex; align-items:center; gap:5px;">
                                    <span
                                        style="width:8px; height:8px; background:#1eb349; border-radius:50%; display:inline-block;"></span>
                                    Online
                                </span>
                            @else
                                <span style="color: var(--text-muted);">
                                    Aktif
                                    {{ ($seller && $seller->last_seen_at) ? $seller->last_seen_at->diffForHumans() : 'baru saja' }}
                                </span>
                            @endif
                        </div>

                        <a href="{{ $cp?->store_slug ? route('store.show', $cp->store_slug) : (isset($sellerUrl) ? $sellerUrl : '#') }}"
                            class="pd-btn pd-btn-primary"
                            style="width:100%; height:44px; border-radius:99px; font-size:0.85rem; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(30,179,73,0.25);">
                            Lihat Profil Creator
                        </a>
                    </div>
                </div>

                {{-- BANNER IKLAN SIDEBAR (Mendukung Rasio 4:3, 3:4 & Gambar Utuh) --}}
                @php
                    $ad1Img = $settings['ad_product_sidebar_1_image'] ?? null;
                    $ad1Url = $settings['ad_product_sidebar_1_url'] ?? null;
                    $ad2Img = $settings['ad_product_sidebar_2_image'] ?? null;
                    $ad2Url = $settings['ad_product_sidebar_2_url'] ?? null;
                    $waContactUrl = isset($wa) && $wa->phone_number ? 'https://wa.me/' . $wa->phone_number . '?text=' . urlencode('Halo Admin buyle.id, saya tertarik untuk pasang iklan banner di halaman produk.') : route('contact');
                @endphp

                {{-- Banner 1 (Atas) --}}
                @if(!empty($ad1Img))
                    <div
                        style="border-radius:16px; overflow:hidden; border:1.5px solid var(--border); background:#fff; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                        <a href="{{ $ad1Url ?: 'javascript:void(0)' }}" {{ $ad1Url ? 'target="_blank" rel="noopener noreferrer"' : '' }} style="display:block; width:100%; overflow:hidden;">
                            <img src="{{ asset('storage/' . $ad1Img) }}" alt="Iklan Banner 1"
                                style="width:100%; height:auto; display:block; object-fit:contain; transition:transform 0.3s;"
                                onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        </a>
                    </div>
                @else
                    <a href="{{ $waContactUrl }}" target="_blank"
                        style="display:flex; flex-direction:column; align-items:center; justify-content:center; width:100%; min-height:180px; border-radius:16px; border:2px dashed #CBD5E1; background:#F8FAFC; text-decoration:none; color:#64748B; padding:1.25rem 1rem; text-align:center; transition:all 0.2s;"
                        onmouseover="this.style.borderColor='var(--primary)';this.style.background='#F0FDF4';this.style.color='var(--primary)'"
                        onmouseout="this.style.borderColor='#CBD5E1';this.style.background='#F8FAFC';this.style.color='#64748B'">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                            style="margin-bottom:0.4rem;">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <line x1="3" y1="9" x2="21" y2="9" />
                            <line x1="9" y1="21" x2="9" y2="9" />
                        </svg>
                        <span style="font-size:0.8rem; font-weight:700;">Space Iklan Tersedia</span>
                        <span style="font-size:0.7rem; color:#94A3B8; margin-top:2px;">Pasang Banner Iklan Disini
                            &rsaquo;</span>
                    </a>
                @endif

                {{-- Banner 2 (Bawah) --}}
                @if(!empty($ad2Img))
                    <div
                        style="border-radius:16px; overflow:hidden; border:1.5px solid var(--border); background:#fff; box-shadow:0 4px 15px rgba(0,0,0,0.03);">
                        <a href="{{ $ad2Url ?: 'javascript:void(0)' }}" {{ $ad2Url ? 'target="_blank" rel="noopener noreferrer"' : '' }} style="display:block; width:100%; overflow:hidden;">
                            <img src="{{ asset('storage/' . $ad2Img) }}" alt="Iklan Banner 2"
                                style="width:100%; height:auto; display:block; object-fit:contain; transition:transform 0.3s;"
                                onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                        </a>
                    </div>
                @else
                    <a href="{{ $waContactUrl }}" target="_blank"
                        style="display:flex; flex-direction:column; align-items:center; justify-content:center; width:100%; min-height:180px; border-radius:16px; border:2px dashed #CBD5E1; background:#F8FAFC; text-decoration:none; color:#64748B; padding:1.25rem 1rem; text-align:center; transition:all 0.2s;"
                        onmouseover="this.style.borderColor='var(--primary)';this.style.background='#F0FDF4';this.style.color='var(--primary)'"
                        onmouseout="this.style.borderColor='#CBD5E1';this.style.background='#F8FAFC';this.style.color='#64748B'">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"
                            style="margin-bottom:0.4rem;">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <line x1="3" y1="9" x2="21" y2="9" />
                            <line x1="9" y1="21" x2="9" y2="9" />
                        </svg>
                        <span style="font-size:0.8rem; font-weight:700;">Space Iklan Tersedia</span>
                        <span style="font-size:0.7rem; color:#94A3B8; margin-top:2px;">Pasang Banner Iklan Disini
                            &rsaquo;</span>
                    </a>
                @endif
            </div>

        </div>

        {{-- MOBILE STICKY BAR --}}
        @if($service->price > 0)
            <div class="pd-sticky-bar">
                <button type="button" onclick="submitPdCart('cart')" class="pd-btn pd-btn-outline"
                    @if($service->type !== 'service' && !is_null($service->stock) && $service->stock <= 0) disabled @endif>
                    Keranjang
                </button>
                <button type="button" onclick="submitPdCart('buy')" class="pd-btn pd-btn-primary"
                    @if($service->type !== 'service' && !is_null($service->stock) && $service->stock <= 0) disabled @endif>
                    Beli Sekarang
                </button>
            </div>
        @endif

        {{-- 4. RELATED (Lainnya dari [namacreator]) --}}
        @if(isset($related) && $related->count() > 0)
            <div style="max-width:1200px; margin: 0 auto 4rem; padding: 0 1rem;">
                @php
                    $creatorName = optional($service->seller->creatorProfile)->store_name ?: ($service->seller->name ?? ($sellerName ?? 'Creator'));
                @endphp
                <div
                    style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
                    <h2 class="pd-related-title" style="margin:0; font-size: 1.1rem; font-weight: 900; color: #0F172A;">
                        Lainnya dari {{ $creatorName }}
                    </h2>
                    <a href="{{ $cp?->store_slug ? route('store.show', $cp->store_slug) : (isset($sellerUrl) ? $sellerUrl : '#') }}"
                        style="font-size: 0.85rem; font-weight: 700; color: #1eb349; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                        Lihat Semua <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path d="M5 12h14m-7-7l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="swipe-cards-container">
                    @foreach($related as $r)
                        @php
                            $rPrice = $r->sale_price > 0 && $r->sale_price < $r->price ? $r->sale_price : $r->effective_price;
                            $rOrigPrice = $r->sale_price > 0 && $r->sale_price < $r->price ? $r->price : null;
                            $rHasDiscount = !empty($rOrigPrice) && $rOrigPrice > $rPrice;
                            $rDiscountPct = $rHasDiscount ? round((($rOrigPrice - $rPrice) / $rOrigPrice) * 100) : 0;
                            $rImage = $r->image ? asset('storage/' . $r->image) : asset('images/buyle-og.png');
                            $rSellerCp = $r->seller?->creatorProfile;
                            $rIsVerified = $rSellerCp ? (bool) $rSellerCp->is_verified : false;
                            $rRatingVal = $r->getEffectiveRating();
                        @endphp
                        <a href="{{ route('products.show', $r->slug) }}"
                            style="text-decoration: none; color: inherit; display: flex; flex-direction: column;"
                            class="article-card-swipe-item">
                            <div style="background: #ffffff; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 0.75rem; display: flex; flex-direction: column; height: 100%; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.02);"
                                class="article-card-box">

                                {{-- Image 1:1 Aspect Ratio --}}
                                <div
                                    style="position: relative; width: 100%; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; background: #F1F5F9;">
                                    <img src="{{ $rImage }}" alt="{{ $r->name }}"
                                        style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s ease;"
                                        class="article-banner-img">

                                    {{-- Discount Badge Top-Left --}}
                                    @if($rHasDiscount)
                                        <div
                                            style="position: absolute; top: 8px; left: 8px; background: #EF4444; color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 8px; font-family: 'Montserrat', sans-serif; z-index: 2; box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);">
                                            -{{ $rDiscountPct }}%
                                        </div>
                                    @endif
                                </div>

                                {{-- Card Body --}}
                                <div
                                    style="padding: 0.85rem 0.25rem 0.25rem; display: flex; flex-direction: column; flex: 1; justify-content: space-between;">
                                    <div>
                                        {{-- Rating & Verified Badge --}}
                                        <div
                                            style="display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; font-weight: 700; color: #1E293B; margin-bottom: 0.35rem;">
                                            @if($rRatingVal !== null && $rRatingVal > 0)
                                                <div style="display: flex; align-items: center; gap: 0.3rem;">
                                                    <span style="color: #F59E0B; font-size: 0.95rem;">★</span>
                                                    <span
                                                        style="font-family: 'Montserrat', sans-serif;">{{ number_format($rRatingVal, 1) }}</span>
                                                </div>
                                            @else
                                                <div
                                                    style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #94A3B8; font-weight: 600;">
                                                    <span style="color: #CBD5E1; font-size: 0.85rem;">★</span>
                                                    <span>0.0</span>
                                                </div>
                                            @endif
                                            @if($rIsVerified)
                                                <div
                                                    style="display: inline-flex; align-items: center; gap: 0.25rem; color: #0D9488; font-size: 0.78rem; font-weight: 700;">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                        viewBox="0 0 24 24">
                                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                        <polyline points="22 4 12 14.01 9 11.01" />
                                                    </svg>
                                                    Verified
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Title --}}
                                        <h3
                                            style="font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 800; color: #0F172A; margin: 0 0 0.4rem; line-height: 1.35; height: 2.7em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                            {{ $r->name }}
                                        </h3>
                                    </div>

                                    {{-- Footer Separator & Price CTA --}}
                                    <div>
                                        <div style="border-top: 1px solid #F1F5F9; margin: 0.65rem 0 0.75rem;"></div>
                                        <div
                                            style="display: flex; align-items: flex-end; justify-content: space-between; gap: 0.35rem;">
                                            <div style="white-space: nowrap; min-width: 0; flex: 1;">
                                                @if($rHasDiscount)
                                                    <span
                                                        style="font-size: 0.68rem; color: #94A3B8; text-decoration: line-through; display: block; font-weight: 500; white-space: nowrap;">
                                                        Rp{{ number_format($rOrigPrice, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                <span
                                                    style="font-family: 'Montserrat', sans-serif; font-size: 0.92rem; font-weight: 800; color: #16a34a; white-space: nowrap; display: block;">
                                                    @if($rPrice > 0)
                                                        Rp{{ number_format($rPrice, 0, ',', '.') }}
                                                    @else
                                                        <span style="color: #1eb349;">GRATIS</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <span
                                                style="background: linear-gradient(135deg, #1eb349, #7db928); color: #ffffff; padding: 0.35rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2rem; box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3); flex-shrink: 0; white-space: nowrap;"
                                                class="retarget-btn-cta">
                                                Lihat <svg width="11" height="11" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path d="M5 12h14m-7-7l7 7-7 7" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- 5. OTHER RELATED PRODUCTS (Produk Terkait Lainnya dari Creator Lain) --}}
        @if(isset($otherRelated) && $otherRelated->count() > 0)
            <div style="max-width:1200px; margin: 0 auto 4rem; padding: 0 1rem;">
                <div
                    style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
                    <h2 class="pd-related-title" style="margin:0; font-size: 1.1rem; font-weight: 900; color: #0F172A;">
                        Produk Terkait Lainnya
                    </h2>
                    <a href="{{ route('products') }}"
                        style="font-size: 0.85rem; font-weight: 700; color: #1eb349; text-decoration: none; display: inline-flex; align-items: center; gap: 0.3rem;">
                        Lihat Semua <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path d="M5 12h14m-7-7l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="swipe-cards-container">
                    @foreach($otherRelated as $r)
                        @php
                            $rPrice = $r->sale_price > 0 && $r->sale_price < $r->price ? $r->sale_price : $r->effective_price;
                            $rOrigPrice = $r->sale_price > 0 && $r->sale_price < $r->price ? $r->price : null;
                            $rHasDiscount = !empty($rOrigPrice) && $rOrigPrice > $rPrice;
                            $rDiscountPct = $rHasDiscount ? round((($rOrigPrice - $rPrice) / $rOrigPrice) * 100) : 0;
                            $rImage = $r->image ? asset('storage/' . $r->image) : asset('images/buyle-og.png');
                            $rSellerCp = $r->seller?->creatorProfile;
                            $rIsVerified = $rSellerCp ? (bool) $rSellerCp->is_verified : false;
                            $rRatingVal = $r->getEffectiveRating();
                        @endphp
                        <a href="{{ route('products.show', $r->slug) }}"
                            style="text-decoration: none; color: inherit; display: flex; flex-direction: column;"
                            class="article-card-swipe-item">
                            <div style="background: #ffffff; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 0.75rem; display: flex; flex-direction: column; height: 100%; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.02);"
                                class="article-card-box">

                                {{-- Image 1:1 Aspect Ratio --}}
                                <div
                                    style="position: relative; width: 100%; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; background: #F1F5F9;">
                                    <img src="{{ $rImage }}" alt="{{ $r->name }}"
                                        style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s ease;"
                                        class="article-banner-img">

                                    {{-- Discount Badge Top-Left --}}
                                    @if($rHasDiscount)
                                        <div
                                            style="position: absolute; top: 8px; left: 8px; background: #EF4444; color: #ffffff; font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.5rem; border-radius: 8px; font-family: 'Montserrat', sans-serif; z-index: 2; box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);">
                                            -{{ $rDiscountPct }}%
                                        </div>
                                    @endif
                                </div>

                                {{-- Card Body --}}
                                <div
                                    style="padding: 0.85rem 0.25rem 0.25rem; display: flex; flex-direction: column; flex: 1; justify-content: space-between;">
                                    <div>
                                        {{-- Rating & Verified Badge --}}
                                        <div
                                            style="display: flex; align-items: center; justify-content: space-between; font-size: 0.82rem; font-weight: 700; color: #1E293B; margin-bottom: 0.35rem;">
                                            @if($rRatingVal !== null && $rRatingVal > 0)
                                                <div style="display: flex; align-items: center; gap: 0.3rem;">
                                                    <span style="color: #F59E0B; font-size: 0.95rem;">★</span>
                                                    <span
                                                        style="font-family: 'Montserrat', sans-serif;">{{ number_format($rRatingVal, 1) }}</span>
                                                </div>
                                            @else
                                                <div
                                                    style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #94A3B8; font-weight: 600;">
                                                    <span style="color: #CBD5E1; font-size: 0.85rem;">★</span>
                                                    <span>0.0</span>
                                                </div>
                                            @endif
                                            @if($rIsVerified)
                                                <div
                                                    style="display: inline-flex; align-items: center; gap: 0.25rem; color: #0D9488; font-size: 0.78rem; font-weight: 700;">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                                                        viewBox="0 0 24 24">
                                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                        <polyline points="22 4 12 14.01 9 11.01" />
                                                    </svg>
                                                    Verified
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Title --}}
                                        <h3
                                            style="font-family: 'Montserrat', sans-serif; font-size: 0.88rem; font-weight: 800; color: #0F172A; margin: 0 0 0.4rem; line-height: 1.35; height: 2.7em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                            {{ $r->name }}
                                        </h3>
                                    </div>

                                    {{-- Footer Separator & Price CTA --}}
                                    <div>
                                        <div style="border-top: 1px solid #F1F5F9; margin: 0.65rem 0 0.75rem;"></div>
                                        <div
                                            style="display: flex; align-items: flex-end; justify-content: space-between; gap: 0.35rem;">
                                            <div style="white-space: nowrap; min-width: 0; flex: 1;">
                                                @if($rHasDiscount)
                                                    <span
                                                        style="font-size: 0.68rem; color: #94A3B8; text-decoration: line-through; display: block; font-weight: 500; white-space: nowrap;">
                                                        Rp{{ number_format($rOrigPrice, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                <span
                                                    style="font-family: 'Montserrat', sans-serif; font-size: 0.92rem; font-weight: 800; color: #16a34a; white-space: nowrap; display: block;">
                                                    @if($rPrice > 0)
                                                        Rp{{ number_format($rPrice, 0, ',', '.') }}
                                                    @else
                                                        <span style="color: #1eb349;">GRATIS</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <span
                                                style="background: linear-gradient(135deg, #1eb349, #7db928); color: #ffffff; padding: 0.35rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.2rem; box-shadow: 0 4px 12px rgba(30, 179, 73, 0.3); flex-shrink: 0; white-space: nowrap;"
                                                class="retarget-btn-cta">
                                                Lihat <svg width="11" height="11" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path d="M5 12h14m-7-7l7 7-7 7" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    @php
        $isCreatorOwner = auth()->check() && (
            auth()->id() == ($service->seller_id ?? 0) ||
            auth()->id() == ($service->user_id ?? 0) ||
            (auth()->user()->role === 'admin' || auth()->user()->is_admin)
        );
    @endphp
    <script>
        const isCreatorOwner = {{ $isCreatorOwner ? 'true' : 'false' }};
        let currentReviewPage = 1;
        let currentReviewFilter = 'all';
        let hasMoreReviews = false;
        let isLoadingReviews = false;
        let reviewsInitialized = false;
        let gLightboxInstance = null;
        let tabSelectedStar = {{ $userReview ? $userReview->rating : 0 }};
        let tabReviewCompressedBase64 = null;

        const starLabels = {
            1: '1 Bintang — Sangat Kecewa 😞',
            2: '2 Bintang — Kurang Puas 🙁',
            3: '3 Bintang — Cukup Baik 😐',
            4: '4 Bintang — Puas 😊',
            5: '5 Bintang — Sangat Puas! 🤩'
        };

        function switchTab(tab) {
            const descTab = document.getElementById('tab-desc');
            const reviewsTab = document.getElementById('tab-reviews');
            const creatorTab = document.getElementById('tab-creator');

            const btnDesc = document.getElementById('tabBtn-desc');
            const btnReviews = document.getElementById('tabBtn-reviews');
            const btnCreator = document.getElementById('tabBtn-creator');

            // Reset all tabs
            if (descTab) descTab.style.display = 'none';
            if (reviewsTab) reviewsTab.style.display = 'none';
            if (creatorTab) creatorTab.style.display = 'none';

            [btnDesc, btnReviews, btnCreator].forEach(btn => {
                if (btn) {
                    btn.classList.remove('active');
                    btn.style.color = '';
                    btn.style.borderBottom = '';
                    btn.style.background = '';
                }
            });

            if (tab === 'desc') {
                if (descTab) descTab.style.display = 'block';
                if (btnDesc) btnDesc.classList.add('active');
            } else if (tab === 'reviews') {
                if (reviewsTab) reviewsTab.style.display = 'block';
                if (btnReviews) btnReviews.classList.add('active');
                if (!reviewsInitialized) {
                    fetchReviews(1, 'all', false);
                }
            } else if (tab === 'creator') {
                if (creatorTab) creatorTab.style.display = 'block';
                if (btnCreator) btnCreator.classList.add('active');
            }
        }

        function goToReviewsTab() {
            switchTab('reviews');
            const tabEl = document.getElementById('tabBtn-reviews');
            if (tabEl) {
                tabEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        function applyReviewFilter(filter, el) {
            currentReviewFilter = filter;
            currentReviewPage = 1;

            document.querySelectorAll('.pd-filter-pill').forEach(btn => btn.classList.remove('active'));
            if (el) el.classList.add('active');

            fetchReviews(1, filter, false);
        }

        function fetchReviews(page, filter, append) {
            if (isLoadingReviews) return;
            isLoadingReviews = true;

            const skeleton = document.getElementById('reviewsLoadingSkeleton');
            const emptyState = document.getElementById('reviewsEmptyState');
            const feed = document.getElementById('reviewsListFeed');
            const loadMoreWrap = document.getElementById('reviewsLoadMoreWrap');
            const btnLoadMore = document.getElementById('btnLoadMoreReviews');

            if (!append) {
                if (skeleton) skeleton.style.display = 'block';
                if (emptyState) emptyState.style.display = 'none';
                if (feed) feed.innerHTML = '';
                if (loadMoreWrap) loadMoreWrap.style.display = 'none';
            } else {
                if (btnLoadMore) {
                    btnLoadMore.disabled = true;
                    btnLoadMore.textContent = 'Memuat...';
                }
            }

            const url = `{{ route('products.reviews.index', $service->slug) }}?page=${page}&filter=${filter}&per_page=6`;

            fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(res => res.json())
                .then(data => {
                    if (skeleton) skeleton.style.display = 'none';
                    isLoadingReviews = false;
                    reviewsInitialized = true;

                    if (data.success) {
                        currentReviewPage = data.current_page;
                        hasMoreReviews = data.has_more;

                        // Update summary stats
                        if (data.stats) {
                            const stats = data.stats;
                            const scoreEl = document.getElementById('reviewsSummaryScore');
                            const starsEl = document.getElementById('reviewsSummaryStars');
                            const totalEl = document.getElementById('reviewsSummaryTotal');
                            const badgeEl = document.getElementById('tabReviewsBadge');
                            const filterAll = document.getElementById('filterCountAll');
                            const filterMedia = document.getElementById('filterCountMedia');

                            if (scoreEl) {
                                scoreEl.textContent = (stats.average > 0) ? Number(stats.average).toFixed(1) : '0.0';
                            }
                            if (starsEl) {
                                let starsHtml = '';
                                const rounded = Math.round(Number(stats.average) || 0);
                                for (let s = 1; s <= 5; s++) {
                                    if (rounded > 0 && s <= rounded) {
                                        starsHtml += '<span style="color:#F59E0B;">★</span>';
                                    } else {
                                        starsHtml += '<span style="color:#CBD5E1;">★</span>';
                                    }
                                }
                                starsEl.innerHTML = starsHtml;
                            }
                            if (totalEl) totalEl.textContent = stats.total_count;
                            if (badgeEl) badgeEl.textContent = stats.total_count;
                            if (filterAll) filterAll.textContent = stats.total_count;
                            if (filterMedia) filterMedia.textContent = stats.with_media_count;

                            // Progress breakdown
                            if (stats.breakdown && stats.total_count > 0) {
                                for (let s = 1; s <= 5; s++) {
                                    const count = stats.breakdown[s] || 0;
                                    const pct = Math.round((count / stats.total_count) * 100);
                                    const fill = document.getElementById(`barFill-${s}`);
                                    const countTxt = document.getElementById(`barCount-${s}`);
                                    if (fill) fill.style.width = pct + '%';
                                    if (countTxt) countTxt.textContent = count;
                                }
                            }
                        }

                        // Render reviews
                        if (data.data && data.data.length > 0) {
                            if (emptyState) emptyState.style.display = 'none';
                            let html = '';
                            data.data.forEach(rev => {
                                html += buildReviewCardHtml(rev);
                            });

                            if (append && feed) {
                                feed.insertAdjacentHTML('beforeend', html);
                            } else if (feed) {
                                feed.innerHTML = html;
                            }

                            // Refresh lightbox for clickable photos
                            initReviewLightbox();

                            // Load more button visibility
                            if (loadMoreWrap) {
                                loadMoreWrap.style.display = data.has_more ? 'block' : 'none';
                            }
                            if (btnLoadMore) {
                                btnLoadMore.disabled = false;
                                btnLoadMore.textContent = 'Muat Lebih Banyak Ulasan';
                            }
                        } else {
                            if (!append && emptyState) {
                                emptyState.style.display = 'block';
                            }
                            if (loadMoreWrap) loadMoreWrap.style.display = 'none';
                        }
                    }
                })
                .catch(() => {
                    isLoadingReviews = false;
                    if (skeleton) skeleton.style.display = 'none';
                    if (btnLoadMore) {
                        btnLoadMore.disabled = false;
                        btnLoadMore.textContent = 'Coba Lagi';
                    }
                });
        }

        function loadMoreReviews() {
            if (!hasMoreReviews || isLoadingReviews) return;
            fetchReviews(currentReviewPage + 1, currentReviewFilter, true);
        }

        function buildReviewCardHtml(rev) {
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                starsHtml += (i <= rev.rating) ? '★' : '<span style="color:#CBD5E1;">★</span>';
            }

            const initial = (rev.display_name || 'U').charAt(0).toUpperCase();
            const avatarHtml = rev.avatar_url
                ? `<img src="${rev.avatar_url}" alt="${rev.display_name}" class="pd-reviewer-avatar">`
                : `<div class="pd-reviewer-avatar">${initial}</div>`;

            let imageHtml = '';
            if (rev.review_images && rev.review_images.length > 0) {
                const imgUrl = rev.review_images[0];
                imageHtml = `
                                                <div class="pd-review-media-wrap">
                                                    <a href="${imgUrl}" class="pd-review-thumb-box review-lightbox-img" data-gallery="review-gallery" title="Foto dari ${rev.display_name}">
                                                        <img src="${imgUrl}" alt="Foto ulasan" loading="lazy">
                                                        <div class="pd-thumb-overlay-hint">
                                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                                                            <span>Klik Perbesar</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            `;
            }

            const reviewText = rev.review_text ? `<div class="pd-review-text">${escapeHtml(rev.review_text)}</div>` : '';

            // Seller Reply Box (jika sudah ada balasan)
            let replyBoxHtml = '';
            if (rev.seller_reply) {
                replyBoxHtml = `
                                                <div class="pd-seller-reply-box" id="sellerReplyWrap-${rev.id}" style="margin-top:1rem; padding:0.85rem 1rem; background:#F8FAFC; border-left:3.5px solid #1EB349; border-radius:0 12px 12px 0; border-top:1px solid #E2E8F0; border-right:1px solid #E2E8F0; border-bottom:1px solid #E2E8F0;">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.35rem; gap:0.5rem; flex-wrap:wrap;">
                                                        <div style="display:flex; align-items:center; gap:6px;">
                                                            <span style="font-weight:700; font-size:0.82rem; color:#1EB349; display:inline-flex; align-items:center; gap:4px;">
                                                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h10a8 8 0 0 1 8 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                                Respon Penjual
                                                            </span>
                                                            <span style="font-size:0.72rem; color:#94A3B8;">${rev.seller_replied_at || ''}</span>
                                                        </div>
                                                        ${isCreatorOwner ? `<button type="button" onclick="openReplyForm(${rev.id})" style="background:none; border:none; color:#15803D; font-size:0.75rem; font-weight:600; cursor:pointer; text-decoration:underline;">Ubah Balasan</button>` : ''}
                                                    </div>
                                                    <div class="pd-seller-reply-text" id="sellerReplyText-${rev.id}" style="font-size:0.85rem; color:#334155; line-height:1.6; white-space:pre-line;">${escapeHtml(rev.seller_reply)}</div>
                                                </div>
                                            `;
            }

            // Tombol Balas (jika belum ada balasan & yang melihat adalah Creator pemilik produk)
            let actionBtnHtml = '';
            if (isCreatorOwner && !rev.seller_reply) {
                actionBtnHtml = `
                                                <div class="pd-creator-reply-action" id="replyActionBtnWrap-${rev.id}" style="margin-top:0.85rem;">
                                                    <button type="button" onclick="openReplyForm(${rev.id})" style="background:#F0FDF4; border:1px solid #BBF7D0; color:#15803D; font-size:0.78rem; font-weight:700; padding:0.35rem 0.85rem; border-radius:99px; cursor:pointer; display:inline-flex; align-items:center; gap:5px; transition:all 0.2s;">
                                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                                        <span>Balas Ulasan</span>
                                                    </button>
                                                </div>
                                            `;
            }

            // Form Balas Ulasan (interaktif, max 200 kata)
            let replyFormHtml = '';
            if (isCreatorOwner) {
                replyFormHtml = `
                                                <div class="pd-reply-form-wrap" id="replyFormWrap-${rev.id}" style="display:none; margin-top:0.85rem; padding:1rem; background:#F8FAFC; border:1px solid #CBD5E1; border-radius:12px;">
                                                    <div style="font-size:0.82rem; font-weight:700; color:#0F172A; margin-bottom:0.4rem; display:flex; align-items:center; justify-content:space-between;">
                                                        <span>Balas Ulasan sebagai Penjual</span>
                                                        <span id="replyWordCount-${rev.id}" style="font-size:0.75rem; color:#64748B; font-weight:600;">0 / 200 kata</span>
                                                    </div>
                                                    <textarea id="replyTextarea-${rev.id}" rows="3" placeholder="Tulis balasan atau terima kasih untuk pembeli (maksimal 200 kata)..." style="width:100%; border:1px solid #CBD5E1; border-radius:8px; padding:0.6rem; font-size:0.85rem; font-family:inherit; resize:vertical; background:#fff; outline:none;" oninput="countReplyWords(${rev.id})">${rev.seller_reply ? escapeHtml(rev.seller_reply) : ''}</textarea>
                                                    <div style="display:flex; justify-content:flex-end; gap:0.5rem; margin-top:0.6rem;">
                                                        <button type="button" onclick="closeReplyForm(${rev.id})" style="padding:0.4rem 0.85rem; font-size:0.78rem; border-radius:99px; border:1px solid #CBD5E1; background:#fff; color:#64748B; cursor:pointer;">Batal</button>
                                                        <button type="button" id="btnSubmitReply-${rev.id}" onclick="submitReply(${rev.id})" style="padding:0.4rem 1rem; font-size:0.78rem; font-weight:700; border-radius:99px; border:none; background:linear-gradient(135deg, #1eb349 0%, #a5cf37 100%); color:#fff; cursor:pointer; box-shadow:0 2px 8px rgba(30,179,73,0.3);">Kirim Balasan</button>
                                                    </div>
                                                </div>
                                            `;
            }

            return `
                                            <div class="pd-review-card" id="review-item-${rev.id}">
                                                <div class="pd-review-header">
                                                    <div class="pd-reviewer-left">
                                                        ${avatarHtml}
                                                        <div>
                                                            <div class="pd-reviewer-name">${escapeHtml(rev.display_name)}</div>
                                                        </div>
                                                    </div>
                                                    <div class="pd-review-date">${rev.created_at} (${rev.date})</div>
                                                </div>
                                                <div class="pd-review-stars">${starsHtml}</div>
                                                ${reviewText}
                                                ${imageHtml}
                                                ${replyBoxHtml}
                                                ${actionBtnHtml}
                                                ${replyFormHtml}
                                            </div>
                                        `;
        }

        function countReplyWords(reviewId) {
            const textarea = document.getElementById(`replyTextarea-${reviewId}`);
            const counter = document.getElementById(`replyWordCount-${reviewId}`);
            const btn = document.getElementById(`btnSubmitReply-${reviewId}`);
            if (!textarea || !counter) return;

            const text = textarea.value.trim();
            const words = text ? text.split(/\s+/).filter(Boolean) : [];
            const count = words.length;

            counter.textContent = `${count} / 200 kata`;
            if (count > 200) {
                counter.style.color = '#ef4444';
                if (btn) btn.disabled = true;
            } else {
                counter.style.color = '#64748b';
                if (btn) btn.disabled = false;
            }
        }

        function openReplyForm(reviewId) {
            const form = document.getElementById(`replyFormWrap-${reviewId}`);
            const actionBtn = document.getElementById(`replyActionBtnWrap-${reviewId}`);
            if (form) {
                form.style.display = 'block';
                countReplyWords(reviewId);
                const textarea = document.getElementById(`replyTextarea-${reviewId}`);
                if (textarea) textarea.focus();
            }
            if (actionBtn) actionBtn.style.display = 'none';
        }

        function closeReplyForm(reviewId) {
            const form = document.getElementById(`replyFormWrap-${reviewId}`);
            const actionBtn = document.getElementById(`replyActionBtnWrap-${reviewId}`);
            if (form) form.style.display = 'none';
            if (actionBtn) actionBtn.style.display = 'block';
        }

        function submitReply(reviewId) {
            const textarea = document.getElementById(`replyTextarea-${reviewId}`);
            const btn = document.getElementById(`btnSubmitReply-${reviewId}`);
            if (!textarea || !btn) return;

            const reply = textarea.value.trim();
            if (!reply) {
                alert('Tuliskan balasan ulasan terlebih dahulu.');
                return;
            }

            const words = reply.split(/\s+/).filter(Boolean);
            if (words.length > 200) {
                alert('Balasan ulasan maksimal 200 kata (saat ini: ' + words.length + ' kata).');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Mengirim...';

            fetch(`/produk/reviews/${reviewId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ reply: reply })
            })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    btn.textContent = 'Kirim Balasan';

                    if (data.success) {
                        closeReplyForm(reviewId);
                        let replyBox = document.getElementById(`sellerReplyWrap-${reviewId}`);
                        if (replyBox) {
                            const textEl = document.getElementById(`sellerReplyText-${reviewId}`);
                            if (textEl) textEl.textContent = data.seller_reply;
                            replyBox.style.display = 'block';
                        } else {
                            const card = document.getElementById(`review-item-${reviewId}`);
                            const html = `
                                                        <div class="pd-seller-reply-box" id="sellerReplyWrap-${reviewId}" style="margin-top:1rem; padding:0.85rem 1rem; background:#F8FAFC; border-left:3.5px solid #1EB349; border-radius:0 12px 12px 0; border-top:1px solid #E2E8F0; border-right:1px solid #E2E8F0; border-bottom:1px solid #E2E8F0;">
                                                            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.35rem; gap:0.5rem; flex-wrap:wrap;">
                                                                <div style="display:flex; align-items:center; gap:6px;">
                                                                    <span style="font-weight:700; font-size:0.82rem; color:#1EB349; display:inline-flex; align-items:center; gap:4px;">
                                                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 10h10a8 8 0 0 1 8 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                                        Respon Penjual
                                                                    </span>
                                                                    <span style="font-size:0.72rem; color:#94A3B8;">${data.seller_replied_at || 'Baru saja'}</span>
                                                                </div>
                                                                <button type="button" onclick="openReplyForm(${reviewId})" style="background:none; border:none; color:#15803D; font-size:0.75rem; font-weight:600; cursor:pointer; text-decoration:underline;">Ubah Balasan</button>
                                                            </div>
                                                            <div class="pd-seller-reply-text" id="sellerReplyText-${reviewId}" style="font-size:0.85rem; color:#334155; line-height:1.6; white-space:pre-line;">${escapeHtml(data.seller_reply)}</div>
                                                        </div>
                                                    `;
                            const formWrap = document.getElementById(`replyFormWrap-${reviewId}`);
                            if (formWrap) {
                                formWrap.insertAdjacentHTML('beforebegin', html);
                            } else if (card) {
                                card.insertAdjacentHTML('beforeend', html);
                            }
                        }
                        const actionBtn = document.getElementById(`replyActionBtnWrap-${reviewId}`);
                        if (actionBtn) actionBtn.style.display = 'none';
                    } else {
                        alert(data.message || 'Gagal mengirim balasan.');
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.textContent = 'Kirim Balasan';
                    alert('Terjadi kesalahan jaringan.');
                });
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        function initReviewLightbox() {
            if (typeof GLightbox !== 'undefined') {
                GLightbox({
                    selector: '.review-lightbox-img',
                    touchNavigation: true,
                    loop: false,
                    zoomable: true
                });
            }
        }

        // Write review interactions in tab
        function toggleWriteReviewForm() {
            const card = document.getElementById('writeReviewCard');
            if (!card) return;
            if (card.style.display === 'none' || card.style.display === '') {
                card.style.display = 'block';
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                card.style.display = 'none';
            }
        }

        function selectTabRating(val) {
            tabSelectedStar = val;
            const input = document.getElementById('tabSelectedRatingValue');
            if (input) input.value = val;
            updateTabStarDisplay(val);
            const label = document.getElementById('tabRatingLabel');
            if (label) label.textContent = starLabels[val] || (val + ' Bintang');
        }

        function previewTabRating(val) {
            updateTabStarDisplay(val);
            const label = document.getElementById('tabRatingLabel');
            if (label) label.textContent = starLabels[val] || (val + ' Bintang');
        }

        function resetPreviewTabRating() {
            updateTabStarDisplay(tabSelectedStar);
            const label = document.getElementById('tabRatingLabel');
            if (label) label.textContent = starLabels[tabSelectedStar] || 'Pilih Bintang (1 - 5)';
        }

        function updateTabStarDisplay(val) {
            for (let i = 1; i <= 5; i++) {
                const svg = document.querySelector(`.tab-star-svg-${i}`);
                if (svg) {
                    if (i <= val) {
                        svg.setAttribute('fill', '#f59e0b');
                        svg.setAttribute('stroke', '#f59e0b');
                    } else {
                        svg.setAttribute('fill', '#e2e8f0');
                        svg.setAttribute('stroke', '#cbd5e1');
                    }
                }
            }
        }

        // Canvas MAX Compression for Tab (Max 1 Image)
        function handleTabReviewPhoto(input) {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];

            if (!file.type.match(/^image\//i)) {
                alert('Silakan pilih file gambar (JPG, PNG, WebP).');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = new Image();
                img.onload = function () {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    const maxDim = 600;

                    if (width > maxDim || height > maxDim) {
                        if (width > height) {
                            height = Math.round((height * maxDim) / width);
                            width = maxDim;
                        } else {
                            width = Math.round((width * maxDim) / height);
                            height = maxDim;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    let mimeType = 'image/jpeg';
                    try {
                        const test = canvas.toDataURL('image/webp', 0.5);
                        if (test && test.indexOf('data:image/webp') === 0) {
                            mimeType = 'image/webp';
                        }
                    } catch (err) { }

                    // Auto-compress low quality (max 600px, WebP/JPEG 0.5: ~15KB - 30KB)
                    tabReviewCompressedBase64 = canvas.toDataURL(mimeType, 0.5);

                    document.getElementById('tabReviewPhotoPreview').src = tabReviewCompressedBase64;
                    document.getElementById('tabReviewPhotoPreviewWrap').style.display = 'block';
                    document.getElementById('tabReviewUploadTrigger').style.display = 'none';
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        function removeTabReviewPhoto() {
            tabReviewCompressedBase64 = null;
            const fileInput = document.getElementById('tabReviewPhotoInput');
            if (fileInput) fileInput.value = '';
            const preview = document.getElementById('tabReviewPhotoPreview');
            if (preview) preview.src = '';
            const wrap = document.getElementById('tabReviewPhotoPreviewWrap');
            if (wrap) wrap.style.display = 'none';
            const trigger = document.getElementById('tabReviewUploadTrigger');
            if (trigger) trigger.style.display = 'block';
        }

        function showReviewInteractiveModal(opts) {
            const modal = document.getElementById('reviewInteractiveModal');
            if (!modal) return;
            const isSuccess = opts.success !== false;
            const iconWrap = document.getElementById('rsmIconWrap');
            const iconSuccess = document.getElementById('rsmIconSuccess');
            const iconError = document.getElementById('rsmIconError');
            const starsEl = document.getElementById('rsmStars');
            const titleEl = document.getElementById('rsmTitle');
            const descEl = document.getElementById('rsmMessage');
            const primaryBtn = document.getElementById('rsmPrimaryBtn');

            if (isSuccess) {
                if (iconWrap) {
                    iconWrap.style.background = 'linear-gradient(135deg, #1eb349 0%, #10b981 100%)';
                    iconWrap.style.boxShadow = '0 12px 30px rgba(30, 179, 73, 0.38)';
                }
                if (iconSuccess) iconSuccess.style.display = 'block';
                if (iconError) iconError.style.display = 'none';

                let starsHtml = '';
                const starCount = Math.max(1, Math.min(5, parseInt(opts.rating || 5)));
                for (let i = 1; i <= 5; i++) {
                    starsHtml += (i <= starCount) ? '★' : '<span style="color:#CBD5E1;">★</span>';
                }
                if (starsEl) {
                    starsEl.style.display = 'flex';
                    starsEl.innerHTML = starsHtml;
                }

                if (titleEl) titleEl.textContent = opts.title || 'Ulasan Berhasil Disimpan! 🎉';
                if (descEl) descEl.textContent = opts.message || 'Terima kasih atas ulasan & rating bintang Anda.';
                if (primaryBtn) primaryBtn.innerHTML = '<span>Lihat Ulasan Saya</span><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
            } else {
                if (iconWrap) {
                    iconWrap.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                    iconWrap.style.boxShadow = '0 12px 30px rgba(239, 68, 68, 0.38)';
                }
                if (iconSuccess) iconSuccess.style.display = 'none';
                if (iconError) iconError.style.display = 'block';
                if (starsEl) starsEl.style.display = 'none';
                if (titleEl) titleEl.textContent = opts.title || 'Perhatian';
                if (descEl) descEl.textContent = opts.message || 'Gagal memproses ulasan.';
                if (primaryBtn) primaryBtn.innerHTML = '<span>Tutup</span>';
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeReviewSuccessModal() {
            const modal = document.getElementById('reviewInteractiveModal');
            if (modal) modal.style.display = 'none';
            document.body.style.overflow = '';
            // Smooth scroll to reviews tab
            const reviewsTabBtn = document.getElementById('tab-btn-reviews');
            if (reviewsTabBtn) reviewsTabBtn.click();
            const listFeed = document.getElementById('reviewsListFeed');
            if (listFeed) {
                listFeed.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        function handleReviewModalOverlayClick(e) {
            if (e.target && e.target.id === 'reviewInteractiveModal') {
                closeReviewSuccessModal();
            }
        }

        function submitTabReview() {
            const val = parseInt(document.getElementById('tabSelectedRatingValue')?.value || 0);
            const reviewText = (document.getElementById('tabReviewText')?.value || '').trim();

            if (!val || val < 1) {
                showReviewInteractiveModal({
                    success: false,
                    title: 'Pilih Rating Bintang',
                    message: 'Silakan pilih rating bintang terlebih dahulu (1 - 5 bintang) sebelum mengirim ulasan.'
                });
                return;
            }

            const btn = document.getElementById('btnSubmitTabReview');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const payload = {
                product_id: {{ $service->id }},
                rating: val,
                review_text: reviewText
            };

            if (tabReviewCompressedBase64) {
                payload.review_images_base64 = [tabReviewCompressedBase64];
            }

            fetch('{{ route("account.orders.rating") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload)
            })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    btn.textContent = '{{ $userReview ? "Simpan Perubahan" : "Kirim Ulasan" }}';

                    if (data.success) {
                        showReviewInteractiveModal({
                            success: true,
                            rating: val,
                            title: 'Ulasan Berhasil Disimpan! 🎉',
                            message: data.message || `Terima kasih! Ulasan & rating ${val}★ Anda berhasil disimpan.`
                        });

                        const formCard = document.getElementById('writeReviewCard');
                        if (formCard) formCard.style.display = 'none';

                        const actionBox = document.getElementById('pdReviewsActionBox');
                        if (actionBox) {
                            @if($userReview)
                                actionBox.innerHTML = `
                                                                                        <div style="background:#F0FDF4;border:1.5px solid #BBF7D0;border-radius:14px;padding:0.75rem 0.85rem;text-align:center;">
                                                                                            <div style="font-size:0.85rem;font-weight:800;color:#15803D;display:flex;align-items:center;justify-content:center;gap:6px;">
                                                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="#15803D"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                                                                                <span>Ulasan Tersimpan (${val}★)</span>
                                                                                            </div>
                                                                                            <div style="font-size:0.72rem;color:#64748B;margin-top:3px;font-weight:500;">
                                                                                                Batas pengeditan ulasan tercapai (maksimal 1x edit).
                                                                                            </div>
                                                                                        </div>
                                                                                    `;
                            @else
                                actionBox.innerHTML = `
                                                                                        <button type="button" onclick="toggleWriteReviewForm()" class="pd-btn pd-btn-primary"
                                                                                            style="width:100%;height:42px;font-size:0.85rem;border-radius:99px;font-weight:700;">
                                                                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                                                            </svg>
                                                                                            <span>Edit Ulasan</span>
                                                                                        </button>
                                                                                        <div style="font-size:0.75rem;color:#15803d;font-weight:600;margin-top:5px;display:flex;align-items:center;justify-content:center;gap:4px;flex-wrap:wrap;">
                                                                                            <span>✓ Anda sudah mengulas (${val}★)</span>
                                                                                            <span style="color:#d97706;background:#fef3c7;padding:1px 6px;border-radius:6px;font-size:0.7rem;">Sisa 1x edit</span>
                                                                                        </div>
                                                                                    `;
                            @endif
                                                    }

                        // Refresh feed
                        fetchReviews(1, 'all', false);
                    } else {
                        showReviewInteractiveModal({
                            success: false,
                            title: 'Pemberitahuan',
                            message: data.message || 'Gagal menyimpan ulasan.'
                        });
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.textContent = '{{ $userReview ? "Simpan Perubahan" : "Kirim Ulasan" }}';
                    showReviewInteractiveModal({
                        success: false,
                        title: 'Gangguan Koneksi',
                        message: 'Terjadi kesalahan koneksi. Silakan periksa jaringan dan coba lagi.'
                    });
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });

            // Initialize IntersectionObserver for Reviews Infinite Scroll / Lazy Load
            const sentinel = document.getElementById('reviewsScrollSentinel');
            if (sentinel && 'IntersectionObserver' in window) {
                const observer = new IntersectionObserver(function (entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && hasMoreReviews && !isLoadingReviews) {
                            loadMoreReviews();
                        }
                    });
                }, { rootMargin: '250px' });
                observer.observe(sentinel);
            }

            // Auto-resolve city/province name from ID if name is not yet saved in DB
            const locBox = document.getElementById('creator-location-box');
            if (locBox) {
                const provId = locBox.getAttribute('data-prov-id');
                const cityId = locBox.getAttribute('data-city-id');
                const provName = locBox.getAttribute('data-prov-name');
                const cityName = locBox.getAttribute('data-city-name');
                const locText = document.getElementById('creator-location-text');

                if ((!provName || !cityName) && (provId || cityId)) {
                    const apiBase = "https://www.emsifa.com/api-wilayah-indonesia/api";
                    let cName = cityName || '', pName = provName || '';

                    const promises = [];
                    if (provId && !pName) {
                        promises.push(fetch(`${apiBase}/provinces.json`).then(r => r.json()).then(provinces => {
                            const found = provinces.find(p => p.id == provId);
                            if (found) pName = found.name;
                        }).catch(() => { }));
                    }
                    if (provId && cityId && !cName) {
                        promises.push(fetch(`${apiBase}/regencies/${provId}.json`).then(r => r.json()).then(cities => {
                            const found = cities.find(c => c.id == cityId);
                            if (found) cName = found.name;
                        }).catch(() => { }));
                    }

                    Promise.all(promises).then(() => {
                        if (cName && pName) locText.textContent = `${cName}, ${pName}`;
                        else if (cName) locText.textContent = cName;
                        else if (pName) locText.textContent = pName;
                    });
                }
            }

            var thumbsEl = document.getElementById('pd-swiper-thumbs');
            if (thumbsEl) {
                var swiperThumbs = new Swiper('#pd-swiper-thumbs', {
                    spaceBetween: 8, slidesPerView: 'auto',
                    freeMode: true, watchSlidesProgress: true,
                });
                new Swiper('#pd-swiper-main', {
                    spaceBetween: 0,
                    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                    thumbs: { swiper: swiperThumbs },
                });
            } else {
                var mainEl = document.getElementById('pd-swiper-main');
                if (mainEl) new Swiper('#pd-swiper-main', { spaceBetween: 0 });
            }
        });
    </script>

    {{-- SMART RETARGETING TRACKER --}}
    <script>
        (function () {
            try {
                const prodData = {
                    id: {{ (int) $service->id }},
                    name: {!! json_encode($service->name) !!},
                    slug: {!! json_encode($service->slug) !!},
                    category_id: {{ (int) ($service->product_category_id ?? 0) }},
                    seller_id: {{ (int) ($service->seller_id ?? 0) }},
                    timestamp: Date.now()
                };

                let history = JSON.parse(localStorage.getItem('buyle_viewed_history') || '[]');
                history = history.filter(item => item && item.id !== prodData.id);
                history.unshift(prodData);
                if (history.length > 20) history = history.slice(0, 20);
                localStorage.setItem('buyle_viewed_history', JSON.stringify(history));

                let catMap = JSON.parse(localStorage.getItem('buyle_cat_interests') || '{}');
                if (prodData.category_id) {
                    catMap[prodData.category_id] = (catMap[prodData.category_id] || 0) + 1;
                    localStorage.setItem('buyle_cat_interests', JSON.stringify(catMap));
                }

                let sellerMap = JSON.parse(localStorage.getItem('buyle_seller_interests') || '{}');
                if (prodData.seller_id) {
                    sellerMap[prodData.seller_id] = (sellerMap[prodData.seller_id] || 0) + 1;
                    localStorage.setItem('buyle_seller_interests', JSON.stringify(sellerMap));
                }
            } catch (e) { }
        })();
    </script>

    @include('partials.report_modal', ['reportType' => 'product', 'targetName' => $service->name])

    {{-- Interactive Review Success / Error Popup Modal --}}
    <div id="reviewInteractiveModal" class="pd-rating-success-modal-overlay" style="display:none;"
        onclick="handleReviewModalOverlayClick(event)">
        <div class="pd-rating-success-modal-box">
            <button type="button" onclick="closeReviewSuccessModal()" class="pd-rsm-close"
                aria-label="Tutup">&times;</button>
            <div class="pd-rsm-icon-wrap" id="rsmIconWrap">
                <svg id="rsmIconSuccess" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <svg id="rsmIconError" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div class="pd-rsm-stars" id="rsmStars">
                ★★★★★
            </div>
            <h3 class="pd-rsm-title" id="rsmTitle">Ulasan Berhasil Disimpan! 🎉</h3>
            <p class="pd-rsm-desc" id="rsmMessage">
                Terima kasih! Ulasan & rating Anda telah berhasil disimpan.
            </p>
            <div class="pd-rsm-actions">
                <button type="button" onclick="closeReviewSuccessModal()" class="pd-rsm-btn-primary" id="rsmPrimaryBtn">
                    <span>Lihat Ulasan Saya</span>
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        </div>
@endsection