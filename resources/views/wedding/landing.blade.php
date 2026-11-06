@extends('layouts.app')

@section('content')

    <style>
        /* ══════════════════════════════════════════════
           BUYLE WEDDING PLATFORM & SMART GUEST BOOK
           Theme matching https://buyle.id/ticketing
           ══════════════════════════════════════════════ */
        :root {
            --wd-accent: #1eb349;
            --wd-accent-light: #a5cf37;
            --wd-dark: #0F172A;
            --wd-muted: #64748B;
            --wd-border: #E2E8F0;
            --font: 'Montserrat', sans-serif;
        }

        /* ── SECTION 1: HERO & INTRO ── */
        .buyle-wedding-hero-section {
            padding: 8rem 1.5rem 5rem;
            background: #FFFFFF;
            position: relative;
            overflow: hidden;
            font-family: var(--font);
        }

        /* Subtle Ambient Glow */
        .buyle-wedding-hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 1000px;
            height: 520px;
            background: radial-gradient(ellipse at center, rgba(30, 179, 73, 0.07) 0%, rgba(165, 207, 55, 0.03) 40%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .buyle-wedding-container {
            max-width: 1220px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* Breadcrumb (Persis Style Ticketing & Tentang Kami) */
        .wd-breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748B;
            margin-bottom: 2.25rem;
            font-family: var(--font);
        }

        .wd-breadcrumb a {
            color: #64748B;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .wd-breadcrumb a:hover {
            color: #1eb349;
        }

        .wd-breadcrumb-sep {
            font-size: 0.6rem;
            color: #94A3B8;
            opacity: 0.6;
        }

        .wd-breadcrumb-current {
            color: #0F172A;
            font-weight: 700;
        }

        /* Badge Pill */
        .wd-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: #F0FDF4;
            color: #15803D;
            border: 1px solid #BBF7D0;
            padding: 0.4rem 1rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            margin-bottom: 1.25rem;
        }

        /* Header Intro */
        .wedding-header {
            text-align: center;
            max-width: 880px;
            margin: 0 auto 3.5rem;
        }

        .wedding-title {
            font-size: clamp(2.1rem, 4.2vw, 3.3rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.18;
            letter-spacing: -0.03em;
            margin: 0 0 1.25rem;
        }

        .wedding-title .accent-dot {
            color: #1eb349;
        }

        .wedding-desc {
            font-size: 1.05rem;
            color: #64748B;
            line-height: 1.7;
            margin: 0 auto 2.25rem;
            max-width: 760px;
            font-weight: 400;
        }

        /* Action Buttons */
        .wedding-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .wd-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #FFFFFF;
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
            padding: 0.85rem 1.85rem;
            border-radius: 999px;
            box-shadow: 0 10px 25px rgba(30, 179, 73, 0.28);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .wd-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(30, 179, 73, 0.4);
            color: #FFFFFF;
        }

        .wd-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            background: #F8FAFC;
            color: #334155;
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
            padding: 0.85rem 1.75rem;
            border-radius: 999px;
            border: 1.5px solid #E2E8F0;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .wd-btn-secondary:hover {
            background: #FFFFFF;
            border-color: #CBD5E1;
            color: #0F172A;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        /* Highlights Bar (4 Kolom Mobile Friendly) */
        .wedding-highlights-bar {
            margin-top: 4rem;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            padding: 1.5rem 2rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            align-items: center;
        }

        .wedding-highlight-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .wedding-highlight-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            color: #1eb349;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .wedding-highlight-content h4 {
            font-size: 0.9rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 0.15rem;
        }

        .wedding-highlight-content p {
            font-size: 0.78rem;
            color: #64748B;
            margin: 0;
            line-height: 1.35;
        }

        /* ── SECTION 2: MODULAR FEATURES & CTA ── */
        .buyle-wedding-features-section {
            padding: 5.5rem 1.5rem 7.5rem;
            background: #FAFAFA;
            border-top: 1px solid #F1F5F9;
            position: relative;
            overflow: hidden;
            font-family: var(--font);
        }

        .wedding-section-header {
            text-align: center;
            max-width: 780px;
            margin: 0 auto 3.75rem;
        }

        .wedding-section-title {
            font-size: clamp(1.9rem, 3.5vw, 2.7rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.25;
            letter-spacing: -0.025em;
            margin: 0 0 1rem;
        }

        .wedding-section-desc {
            font-size: 1rem;
            color: #64748B;
            line-height: 1.65;
            margin: 0;
        }

        /* Grid 6 Modular Feature Cards */
        .wedding-features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem 2.25rem;
            margin-bottom: 5rem;
        }

        .wedding-card-outer {
            position: relative;
            padding: 6px;
        }

        /* Corner L-Shaped Ribbon Frame */
        .wedding-corner-svg {
            position: absolute;
            width: 100px;
            height: 100px;
            pointer-events: none;
            z-index: 1;
            transition: transform 0.35s ease;
        }

        .corner-top-left { top: 0; left: 0; }
        .corner-top-right { top: 0; right: 0; }
        .corner-bottom-left { bottom: 0; left: 0; }
        .corner-bottom-right { bottom: 0; right: 0; }

        .wedding-card-outer:hover .wedding-corner-svg {
            transform: scale(1.04);
        }

        /* Card Inner Box */
        .wedding-card-inner {
            background: #FFFFFF;
            border-radius: 22px;
            box-shadow: 0 10px 32px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.03);
            border: 1.5px solid rgba(226, 232, 240, 0.85);
            padding: 2.25rem 2rem;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 1.75rem;
            min-height: 200px;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .wedding-card-outer:hover .wedding-card-inner {
            transform: translateY(-4px);
            box-shadow: 0 20px 45px rgba(30, 179, 73, 0.12), 0 2px 8px rgba(0, 0, 0, 0.04);
            border-color: rgba(30, 179, 73, 0.35);
        }

        /* Giant Outlined Number */
        .wedding-num-col {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 95px;
        }

        .wedding-big-num {
            font-size: clamp(5.2rem, 6.5vw, 6.8rem);
            font-weight: 800;
            line-height: 0.82;
            color: #FFFFFF;
            -webkit-text-stroke: 2px #1eb349;
            text-shadow: 0 0 1px #1eb349;
            font-family: var(--font);
            user-select: none;
            letter-spacing: -0.04em;
            transition: transform 0.35s ease, -webkit-text-stroke-color 0.35s ease;
        }

        .wedding-card-outer:hover .wedding-big-num {
            -webkit-text-stroke-color: #16a34a;
            transform: scale(1.08);
        }

        .wedding-body-col {
            flex: 1;
            min-width: 0;
        }

        .wedding-module-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 0.55rem;
            letter-spacing: -0.015em;
            line-height: 1.35;
        }

        .wedding-module-desc {
            font-size: 0.9rem;
            color: #64748B;
            line-height: 1.6;
            margin: 0;
            font-weight: 400;
        }

        /* Circular Icon Badge */
        .wedding-badge-col {
            display: flex;
            align-items: flex-start;
            flex-shrink: 0;
            align-self: flex-start;
        }

        .wedding-circle-badge {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(30, 179, 73, 0.35);
            flex-shrink: 0;
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }

        .wedding-card-outer:hover .wedding-circle-badge {
            transform: scale(1.12);
            box-shadow: 0 10px 24px rgba(30, 179, 73, 0.45);
        }

        /* ── SECTION 2 CTA BANNER: COBA LANGSUNG ── */
        .wedding-trial-banner {
            background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);
            border: 1.5px solid #E2E8F0;
            border-radius: 28px;
            padding: 3.5rem 2.5rem;
            box-shadow: 0 16px 45px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
            text-align: center;
            max-width: 980px;
            margin: 0 auto;
        }

        .wedding-trial-banner::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle at top right, rgba(30, 179, 73, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .trial-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: #F0FDF4;
            color: #15803D;
            border: 1px solid #BBF7D0;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 1.25rem;
        }

        .trial-title {
            font-size: clamp(1.8rem, 3.2vw, 2.5rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.25;
            letter-spacing: -0.025em;
            margin: 0 0 1rem;
        }

        .trial-desc {
            font-size: 1.02rem;
            color: #64748B;
            line-height: 1.65;
            max-width: 680px;
            margin: 0 auto 2.25rem;
        }

        .trial-btn-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #FFFFFF;
            font-size: 1.02rem;
            font-weight: 800;
            text-decoration: none;
            padding: 1.05rem 2.5rem;
            border-radius: 999px;
            box-shadow: 0 12px 30px rgba(30, 179, 73, 0.35);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .trial-btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 36px rgba(30, 179, 73, 0.45);
            color: #FFFFFF;
        }

        /* ── RESPONSIVE BREAKPOINTS (100% Mobile Friendly) ── */
        @media (max-width: 1024px) {
            .wedding-features-grid {
                gap: 1.75rem;
            }
        }

        @media (max-width: 768px) {
            .buyle-wedding-hero-section {
                padding: 6.5rem 1.25rem 4rem;
            }

            .buyle-wedding-features-section {
                padding: 4.5rem 1.25rem 5.5rem;
            }

            .wedding-features-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                margin-bottom: 3.5rem;
            }

            .wedding-card-inner {
                padding: 1.75rem 1.5rem;
            }

            .wedding-highlights-bar {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
                padding: 1.25rem;
            }

            .wedding-trial-banner {
                padding: 2.5rem 1.5rem;
                border-radius: 22px;
            }

            .trial-btn-cta {
                width: 100%;
                padding: 1rem 1.5rem;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 640px) {
            .wedding-title {
                font-size: 1.95rem;
            }

            .wedding-desc {
                font-size: 0.95rem;
            }

            .wedding-highlights-bar {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .wedding-card-inner {
                padding: 1.4rem 1.15rem;
                gap: 1rem;
            }

            .wedding-num-col {
                width: 65px;
            }

            .wedding-big-num {
                font-size: 4.2rem;
            }

            .wedding-module-title {
                font-size: 1.05rem;
            }

            .wedding-module-desc {
                font-size: 0.85rem;
            }

            .wedding-actions {
                flex-direction: column;
                width: 100%;
            }

            .wd-btn-primary, .wd-btn-secondary {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    {{-- Shared SVG Gradients --}}
    <svg width="0" height="0" style="position:absolute;visibility:hidden;">
        <defs>
            <linearGradient id="weddingAccentGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#1eb349" />
                <stop offset="100%" stop-color="#a5cf37" />
            </linearGradient>
        </defs>
    </svg>

    <!-- ══════════════════════════════════════════════
         SECTION 1: HERO & PLATFORM INTRODUCTION
         ══════════════════════════════════════════════ -->
    <section class="buyle-wedding-hero-section">
        <div class="buyle-wedding-container">
            
            {{-- Breadcrumb Persis Style Ticketing & Tentang Kami --}}
            <nav class="wd-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span class="wd-breadcrumb-sep">/</span>
                <span class="wd-breadcrumb-current">Wedding Platform</span>
            </nav>

            {{-- Header Intro --}}
            <div class="wedding-header">
                <div class="wd-badge-pill">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    <span>Buyle Wedding &amp; Smart Guest Book</span>
                </div>

                <h1 class="wedding-title">
                    Solusi Undangan Digital &amp; Smart Guest Book Terlengkap<span class="accent-dot">.</span>
                </h1>

                <p class="wedding-desc">
                    Hadirkan momen pernikahan modern yang anggun dan berkesan. Kelola ribuan tamu undangan, QR Check-in cepat tanpa antrean meja resepsi, RSVP interaktif, amplop digital QRIS, hingga buku tamu digital real-time dalam satu platform terpadu.
                </p>

                <div class="wedding-actions">
                    <a href="https://buyle.id/creator/wedding" class="wd-btn-primary">
                        <span>Buat Undangan Sekarang</span>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>

                    <a href="#fitur-wedding" class="wd-btn-secondary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                        <span>Lihat Fitur Unggulan</span>
                    </a>
                </div>
            </div>

            {{-- 4 Quick Highlights Bar --}}
            <div class="wedding-highlights-bar">
                <div class="wedding-highlight-item">
                    <div class="wedding-highlight-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <rect x="7" y="7" width="3" height="3"/>
                            <rect x="14" y="7" width="3" height="3"/>
                            <rect x="7" y="14" width="3" height="3"/>
                        </svg>
                    </div>
                    <div class="wedding-highlight-content">
                        <h4>Smart QR Check-In</h4>
                        <p>Validasi 1 detik tanpa antrean</p>
                    </div>
                </div>

                <div class="wedding-highlight-item">
                    <div class="wedding-highlight-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="2" y="5" width="20" height="14" rx="2"/>
                            <line x1="2" y1="10" x2="22" y2="10"/>
                        </svg>
                    </div>
                    <div class="wedding-highlight-content">
                        <h4>Amplop Digital QRIS</h4>
                        <p>Transfer bank &amp; QRIS instan</p>
                    </div>
                </div>

                <div class="wedding-highlight-item">
                    <div class="wedding-highlight-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <div class="wedding-highlight-content">
                        <h4>RSVP &amp; Tamu Khusus</h4>
                        <p>Kepastian porsi katering akurat</p>
                    </div>
                </div>

                <div class="wedding-highlight-item">
                    <div class="wedding-highlight-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                    </div>
                    <div class="wedding-highlight-content">
                        <h4>Web Photobooth 9:16</h4>
                        <p>Frame eksklusif siap InstaStory</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ══════════════════════════════════════════════
         SECTION 2: MODULAR FEATURES & COBA LANGSUNG CTA
         ══════════════════════════════════════════════ -->
    <section class="buyle-wedding-features-section" id="fitur-wedding">
        <div class="buyle-wedding-container">

            {{-- Header Section 2 --}}
            <div class="wedding-section-header">
                <div class="wd-badge-pill">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <span>Fitur Unggulan Resepsi</span>
                </div>

                <h2 class="wedding-section-title">
                    Teknologi Modern untuk Hari Bahagia Anda<span class="accent-dot">.</span>
                </h2>

                <p class="wedding-section-desc">
                    Semua fitur dirancang khusus dengan standar kenyamanan tinggi untuk kedua mempelai, keluarga besar, penerima tamu, dan seluruh kerabat undangan.
                </p>
            </div>

            {{-- 6 Modular Feature Cards (Persis Style Infographic Ticketing) --}}
            <div class="wedding-features-grid">

                {{-- CARD 1 --}}
                <div class="wedding-card-outer">
                    <svg class="wedding-corner-svg corner-top-left" viewBox="0 0 100 100" fill="none">
                        <path d="M 90 7 H 26 A 19 19 0 0 0 7 26 V 90" stroke="url(#weddingAccentGradient)" stroke-width="12" stroke-linecap="round" />
                    </svg>
                    <div class="wedding-card-inner">
                        <div class="wedding-num-col">
                            <span class="wedding-big-num">1</span>
                        </div>
                        <div class="wedding-body-col">
                            <h3 class="wedding-module-title">Smart QR Check-In Resepsi</h3>
                            <p class="wedding-module-desc">
                                Tamu cukup menunjukkan QR Code di smartphone. Petugas meja penerima tamu memindai dalam 1 detik tanpa antrean panjang, status kehadiran langsung tercatat.
                            </p>
                        </div>
                        <div class="wedding-badge-col">
                            <div class="wedding-circle-badge" title="QR Check-In">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 2 --}}
                <div class="wedding-card-outer">
                    <svg class="wedding-corner-svg corner-top-right" viewBox="0 0 100 100" fill="none">
                        <path d="M 10 7 H 74 A 19 19 0 0 1 93 26 V 90" stroke="url(#weddingAccentGradient)" stroke-width="12" stroke-linecap="round" />
                    </svg>
                    <div class="wedding-card-inner">
                        <div class="wedding-num-col">
                            <span class="wedding-big-num">2</span>
                        </div>
                        <div class="wedding-body-col">
                            <h3 class="wedding-module-title">Amplop Digital &amp; QRIS Otomatis</h3>
                            <p class="wedding-module-desc">
                                Dukungan transfer bank dan QRIS instan bagi tamu yang ingin berbagi tanda kasih secara praktis, aman, dan langsung masuk ke rekening pribadi Anda.
                            </p>
                        </div>
                        <div class="wedding-badge-col">
                            <div class="wedding-circle-badge" title="Amplop Digital">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                    <line x1="2" y1="10" x2="22" y2="10"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 3 --}}
                <div class="wedding-card-outer">
                    <svg class="wedding-corner-svg corner-bottom-left" viewBox="0 0 100 100" fill="none">
                        <path d="M 90 93 H 26 A 19 19 0 0 1 7 74 V 10" stroke="url(#weddingAccentGradient)" stroke-width="12" stroke-linecap="round" />
                    </svg>
                    <div class="wedding-card-inner">
                        <div class="wedding-num-col">
                            <span class="wedding-big-num">3</span>
                        </div>
                        <div class="wedding-body-col">
                            <h3 class="wedding-module-title">RSVP &amp; Konfirmasi Tamu Interaktif</h3>
                            <p class="wedding-module-desc">
                                Tamu dapat mengonfirmasi kepastian hadir beserta jumlah orang yang dibawa. Mempelai memperoleh data kepastian katering dan kapasitas gedung yang akurat.
                            </p>
                        </div>
                        <div class="wedding-badge-col">
                            <div class="wedding-circle-badge" title="RSVP Tamu">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="8.5" cy="7" r="4"></circle>
                                    <polyline points="17 11 19 13 23 9"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 4 --}}
                <div class="wedding-card-outer">
                    <svg class="wedding-corner-svg corner-bottom-right" viewBox="0 0 100 100" fill="none">
                        <path d="M 10 93 H 74 A 19 19 0 0 0 93 74 V 10" stroke="url(#weddingAccentGradient)" stroke-width="12" stroke-linecap="round" />
                    </svg>
                    <div class="wedding-card-inner">
                        <div class="wedding-num-col">
                            <span class="wedding-big-num">4</span>
                        </div>
                        <div class="wedding-body-col">
                            <h3 class="wedding-module-title">Anti-Nakal Protection &amp; Nama Eksklusif</h3>
                            <p class="wedding-module-desc">
                                Verifikasi nama tamu database mencegah modifikasi link oleh pihak tidak berwenang. Setiap undangan terasa personal dan eksklusif untuk penerima.
                            </p>
                        </div>
                        <div class="wedding-badge-col">
                            <div class="wedding-circle-badge" title="Anti-Nakal Protection">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 5 --}}
                <div class="wedding-card-outer">
                    <svg class="wedding-corner-svg corner-top-left" viewBox="0 0 100 100" fill="none">
                        <path d="M 90 7 H 26 A 19 19 0 0 0 7 26 V 90" stroke="url(#weddingAccentGradient)" stroke-width="12" stroke-linecap="round" />
                    </svg>
                    <div class="wedding-card-inner">
                        <div class="wedding-num-col">
                            <span class="wedding-big-num">5</span>
                        </div>
                        <div class="wedding-body-col">
                            <h3 class="wedding-module-title">Buku Tamu Digital &amp; Ucapan Live</h3>
                            <p class="wedding-module-desc">
                                Ucapan doa restu dan pesan hangat tamu tampil live secara estetik di layar resepsi, lengkap dengan ekspor data kehadiran tamu format Excel atau CSV.
                            </p>
                        </div>
                        <div class="wedding-badge-col">
                            <div class="wedding-circle-badge" title="Buku Tamu Digital">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 6 --}}
                <div class="wedding-card-outer">
                    <svg class="wedding-corner-svg corner-top-right" viewBox="0 0 100 100" fill="none">
                        <path d="M 10 7 H 74 A 19 19 0 0 1 93 26 V 90" stroke="url(#weddingAccentGradient)" stroke-width="12" stroke-linecap="round" />
                    </svg>
                    <div class="wedding-card-inner">
                        <div class="wedding-num-col">
                            <span class="wedding-big-num">6</span>
                        </div>
                        <div class="wedding-body-col">
                            <h3 class="wedding-module-title">Web Photobooth InstaStory 9:16</h3>
                            <p class="wedding-module-desc">
                                Tamu di venue resepsi cukup scan barcode untuk membuka kamera selfie berbingkai tema pernikahan Anda, langsung siap bagikan ke Instagram Story.
                            </p>
                        </div>
                        <div class="wedding-badge-col">
                            <div class="wedding-circle-badge" title="Web Photobooth">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── SECTION 2 CTA BANNER: COBA LANGSUNG KE /creator/wedding ── --}}
            <div class="wedding-trial-banner">
                <div class="trial-badge">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                    </svg>
                    <span>Coba Sekarang Tanpa Komitmen</span>
                </div>

                <h3 class="trial-title">
                    Mulai Rancang Undangan Impian Anda Hari Ini<span class="accent-dot">.</span>
                </h3>

                <p class="trial-desc">
                    Akses dashboard Creator sekarang untuk mencoba langsung pembuatan undangan digital, manajemen daftar tamu, simulasi QR check-in meja resepsi, hingga amplop digital.
                </p>

                <a href="https://buyle.id/creator/wedding" class="trial-btn-cta">
                    <span>Coba Langsung di Creator Hub</span>
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>

        </div>
    </section>

@endsection
