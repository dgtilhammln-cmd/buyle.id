@extends('layouts.app')

@section('content')

    <style>
        /* ══════════════════════════════════════════════
           BUYLE TICKETING PLATFORM — SINGLE MASTER SECTION
           ══════════════════════════════════════════════ */
        :root {
            --tk-accent: #1eb349;
            --tk-accent-light: #a5cf37;
            --tk-dark: #0F172A;
            --tk-muted: #64748B;
            --tk-border: #E2E8F0;
            --font: 'Montserrat', sans-serif;
        }

        .buyle-ticketing-section {
            padding: 8rem 1.5rem 6.5rem;
            background: #FFFFFF;
            position: relative;
            overflow: hidden;
            font-family: var(--font);
        }

        /* Subtle Ambient Glow */
        .buyle-ticketing-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 1000px;
            height: 520px;
            background: radial-gradient(ellipse at center, rgba(30, 179, 73, 0.06) 0%, rgba(165, 207, 55, 0.03) 40%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .buyle-ticketing-container {
            max-width: 1220px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* ── Breadcrumb (Persis Style Tentang Kami) ── */
        .sv-breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748B;
            margin-bottom: 2rem;
            font-family: var(--font);
        }

        .sv-breadcrumb a {
            color: #64748B;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .sv-breadcrumb a:hover {
            color: #1eb349;
        }

        .sv-breadcrumb-sep {
            font-size: 0.6rem;
            color: #94A3B8;
            opacity: 0.6;
        }

        .sv-breadcrumb-current {
            color: #0F172A;
            font-weight: 700;
        }

        /* ── Header Intro ── */
        .ticket-header {
            text-align: center;
            max-width: 860px;
            margin: 0 auto 3.75rem;
        }

        .ticket-sublabel {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #16a34a;
            background: #F0FDF4;
            border: 1px solid rgba(30, 179, 73, 0.25);
            padding: 0.35rem 1rem;
            border-radius: 999px;
            margin-bottom: 1.25rem;
        }

        .ticket-title {
            font-size: clamp(2.2rem, 4.5vw, 3.4rem);
            font-weight: 800;
            color: #0F172A;
            line-height: 1.18;
            letter-spacing: -0.03em;
            margin: 0 0 1.25rem;
        }

        .ticket-title .accent-dot {
            color: #1eb349;
        }

        .ticket-desc {
            font-size: 1.05rem;
            color: #64748B;
            line-height: 1.7;
            margin: 0 auto 2.25rem;
            max-width: 740px;
            font-weight: 400;
        }

        /* ── Action Buttons ── */
        .ticket-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .ticket-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            background: linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
            color: #FFFFFF;
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
            padding: 0.85rem 1.75rem;
            border-radius: 999px;
            box-shadow: 0 10px 25px rgba(30, 179, 73, 0.28);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .ticket-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(30, 179, 73, 0.4);
            color: #FFFFFF;
        }

        .ticket-btn-secondary {
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

        .ticket-btn-secondary:hover {
            background: #FFFFFF;
            border-color: #CBD5E1;
            color: #0F172A;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        /* ── Grid 6 Feature Cards (Style Sesuai Referensi Infographic) ── */
        .ticket-features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem 2.25rem;
            margin-top: 4.5rem;
        }

        .ticket-card-outer {
            position: relative;
            padding: 6px;
        }

        /* Corner L-Shaped Ribbon Frame */
        .ticket-corner-svg {
            position: absolute;
            width: 100px;
            height: 100px;
            pointer-events: none;
            z-index: 1;
            transition: transform 0.35s ease;
        }

        .corner-top-left {
            top: 0;
            left: 0;
        }

        .corner-top-right {
            top: 0;
            right: 0;
        }

        .corner-bottom-left {
            bottom: 0;
            left: 0;
        }

        .corner-bottom-right {
            bottom: 0;
            right: 0;
        }

        .ticket-card-outer:hover .ticket-corner-svg {
            transform: scale(1.04);
        }

        /* Card Inner Box */
        .ticket-card-inner {
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

        .ticket-card-outer:hover .ticket-card-inner {
            transform: translateY(-4px);
            box-shadow: 0 20px 45px rgba(30, 179, 73, 0.12), 0 2px 8px rgba(0, 0, 0, 0.04);
            border-color: rgba(30, 179, 73, 0.35);
        }

        /* Giant Outlined Number */
        .ticket-num-col {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 95px;
        }

        .ticket-big-num {
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

        .ticket-card-outer:hover .ticket-big-num {
            -webkit-text-stroke-color: #16a34a;
            transform: scale(1.08);
        }

        /* Content Text */
        .ticket-body-col {
            flex: 1;
            min-width: 0;
        }

        .ticket-module-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 0.55rem;
            letter-spacing: -0.015em;
            line-height: 1.35;
        }

        .ticket-module-desc {
            font-size: 0.9rem;
            color: #64748B;
            line-height: 1.6;
            margin: 0;
            font-weight: 400;
        }

        /* Circular Icon Badge with Gradient Sesuai Button Daftar */
        .ticket-badge-col {
            display: flex;
            align-items: flex-start;
            flex-shrink: 0;
            align-self: flex-start;
        }

        .ticket-circle-badge {
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

        .ticket-card-outer:hover .ticket-circle-badge {
            transform: scale(1.12);
            box-shadow: 0 10px 24px rgba(30, 179, 73, 0.45);
        }

        /* Layout Alternating: Even Card has Badge on Left, Number on Right */
        .card-layout-even {
            justify-content: space-between;
        }

        /* ── Bottom Highlights Bar ── */
        .ticket-highlights-bar {
            margin-top: 4.5rem;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            padding: 1.5rem 2rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            align-items: center;
        }

        .ticket-highlight-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .ticket-highlight-icon {
            width: 40px;
            height: 40px;
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

        .ticket-highlight-content h4 {
            font-size: 0.9rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 0.15rem;
        }

        .ticket-highlight-content p {
            font-size: 0.78rem;
            color: #64748B;
            margin: 0;
            line-height: 1.35;
        }

        /* ── Responsive Breakpoints ── */
        @media (max-width: 991px) {
            .ticket-features-grid {
                grid-template-columns: 1fr;
                gap: 1.75rem;
            }
            .ticket-card-inner {
                padding: 1.75rem 1.5rem;
            }
            .ticket-highlights-bar {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }

        @media (max-width: 640px) {
            .buyle-ticketing-section {
                padding: 6.5rem 1.25rem 4.5rem;
            }
            .ticket-header {
                margin-bottom: 2.75rem;
            }
            .ticket-title {
                font-size: 2rem;
            }
            .ticket-desc {
                font-size: 0.95rem;
            }
            .ticket-card-inner {
                padding: 1.5rem 1.25rem;
                gap: 1rem;
            }
            .ticket-num-col {
                width: 70px;
            }
            .ticket-big-num {
                font-size: 4.5rem;
            }
            .ticket-circle-badge {
                width: 38px;
                height: 38px;
            }
            .ticket-circle-badge svg {
                width: 18px;
                height: 18px;
            }
            .ticket-highlights-bar {
                grid-template-columns: 1fr;
                padding: 1.25rem 1.25rem;
                gap: 1rem;
            }
        }
    </style>

    {{-- Shared SVG Defs for Button Daftar Gradient --}}
    <svg width="0" height="0" style="position:absolute; width:0; height:0; overflow:hidden;" aria-hidden="true">
        <defs>
            <linearGradient id="btnDaftarGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#1eb349" />
                <stop offset="100%" stop-color="#a5cf37" />
            </linearGradient>
        </defs>
    </svg>

    {{-- SEO Schema JSON-LD --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "BreadcrumbList",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "Beranda",
              "item": "{{ route_locale('home') }}"
            },
            {
              "@type": "ListItem",
              "position": 2,
              "name": "Ticketing",
              "item": "{{ route('ticketing') }}"
            }
          ]
        },
        {
          "@type": "SoftwareApplication",
          "name": "Buyle.id E-Ticketing Platform",
          "applicationCategory": "BusinessApplication",
          "operatingSystem": "All Web Browsers",
          "description": "Platform e-ticketing terlengkap di Indonesia untuk konser, webinar, seminar, dan event. Dilengkapi QR code E-Tiket PDF, sistem pembayaran aman escrow, dan scanner gate panitia.",
          "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "IDR"
          }
        }
      ]
    }
    </script>

    {{-- ════ SINGLE TICKETING MASTER SECTION ════ --}}
    <section class="buyle-ticketing-section" id="ticketing-platform">
        <div class="buyle-ticketing-container">

            {{-- Breadcrumb (Sama dengan Tentang Kami) --}}
            <nav class="sv-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route_locale('home') }}">Beranda</a>
                <span class="sv-breadcrumb-sep">/</span>
                <span class="sv-breadcrumb-current">Ticketing</span>
            </nav>

            {{-- Header Intro --}}
            <div class="ticket-header" data-aos="fade-up">
                <div class="ticket-sublabel">
                    <span>E-TICKETING PLATFORM</span>
                </div>
                <h1 class="ticket-title">
                    Solusi Tiket Event &amp; Konser Digital Terlengkap<span class="accent-dot">.</span>
                </h1>
                <p class="ticket-desc">
                    Platform e-ticketing all-in-one untuk kreator, promotor, dan event organizer. Dari pembuatan tiket online, pembayaran instan QRIS &amp; Virtual Account, pengiriman E-Tiket QR PDF otomatis, hingga validasi check-in kru gate secara realtime.
                </p>

                {{-- Action CTA --}}
                <div class="ticket-actions">
                    <a href="{{ route('creator.onboarding') }}" class="ticket-btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"></path>
                            <line x1="13" y1="5" x2="13" y2="19" stroke-dasharray="2"></line>
                        </svg>
                        <span>Mulai Buat Tiket Event</span>
                    </a>
                    <a href="{{ route('products') }}" class="ticket-btn-secondary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <span>Jelajahi Event &amp; Tiket</span>
                    </a>
                </div>
            </div>

            {{-- 6 Feature Cards Grid (Sesuai Referensi Infographic) --}}
            <div class="ticket-features-grid">

                {{-- Card 1: Top-Left Frame | Number Left | Badge Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="50">
                    <svg class="ticket-corner-svg corner-top-left" viewBox="0 0 100 100" fill="none">
                        <path d="M 90 7 H 26 A 19 19 0 0 0 7 26 V 90" stroke="url(#btnDaftarGradient)" stroke-width="12" stroke-linecap="round"/>
                    </svg>
                    <div class="ticket-card-inner card-layout-odd">
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">1</span>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">Kustomisasi Event &amp; Kuota</h2>
                            <p class="ticket-module-desc">
                                Atur tiket gratis atau berbayar dengan kuota otomatis, batas waktu penjualan, form peserta kustom, dan multi-tier (Early Bird, VIP, Presale, OTS).
                            </p>
                        </div>
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="Kustomisasi Event">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Top-Right Frame | Badge Left | Number Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="100">
                    <svg class="ticket-corner-svg corner-top-right" viewBox="0 0 100 100" fill="none">
                        <path d="M 10 7 H 74 A 19 19 0 0 1 93 26 V 90" stroke="url(#btnDaftarGradient)" stroke-width="12" stroke-linecap="round"/>
                    </svg>
                    <div class="ticket-card-inner card-layout-even">
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="E-Tiket Otomatis">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="12" y1="18" x2="12" y2="12"></line>
                                    <line x1="9" y1="15" x2="15" y2="15"></line>
                                </svg>
                            </div>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">E-Tiket QR PDF Otomatis</h2>
                            <p class="ticket-module-desc">
                                File E-Tiket PDF resmi ber-QR Code unik terkirim otomatis ke Email dan WhatsApp pembeli tepat setelah transaksi pembayaran terverifikasi.
                            </p>
                        </div>
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">2</span>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Top-Left Frame | Number Left | Badge Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="150">
                    <svg class="ticket-corner-svg corner-top-left" viewBox="0 0 100 100" fill="none">
                        <path d="M 90 7 H 26 A 19 19 0 0 0 7 26 V 90" stroke="url(#btnDaftarGradient)" stroke-width="12" stroke-linecap="round"/>
                    </svg>
                    <div class="ticket-card-inner card-layout-odd">
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">3</span>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">Gate Scanner Bebas Alat Sewa</h2>
                            <p class="ticket-module-desc">
                                Panitia validasi tiket langsung lewat browser kamera smartphone tanpa perlu sewa barcode scanner mahal. Respon kilat &lt;0.2 detik bebas antrean.
                            </p>
                        </div>
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="Gate Scanner">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                                    <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                                    <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                                    <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                                    <rect x="7" y="7" width="10" height="10" rx="1"></rect>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Top-Right Frame | Badge Left | Number Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="200">
                    <svg class="ticket-corner-svg corner-top-right" viewBox="0 0 100 100" fill="none">
                        <path d="M 10 7 H 74 A 19 19 0 0 1 93 26 V 90" stroke="url(#btnDaftarGradient)" stroke-width="12" stroke-linecap="round"/>
                    </svg>
                    <div class="ticket-card-inner card-layout-even">
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="Multi-Gate PIN">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">Multi-Gate &amp; Akses Kru PIN</h2>
                            <p class="ticket-module-desc">
                                Bagi tugas scan ke puluhan kru lapangan di berbagai pintu masuk cukup dengan otorisasi PIN rahasia. Kuota check-in tersinkronisasi realtime antar gate.
                            </p>
                        </div>
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">4</span>
                        </div>
                    </div>
                </div>

                {{-- Card 5: Bottom-Left Frame | Number Left | Badge Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="250">
                    <svg class="ticket-corner-svg corner-bottom-left" viewBox="0 0 100 100" fill="none">
                        <path d="M 90 93 H 26 A 19 19 0 0 1 7 74 V 10" stroke="url(#btnDaftarGradient)" stroke-width="12" stroke-linecap="round"/>
                    </svg>
                    <div class="ticket-card-inner card-layout-odd">
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">5</span>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">Multi-Payment &amp; Escrow Aman</h2>
                            <p class="ticket-module-desc">
                                Mendukung pembayaran QRIS dari semua m-banking &amp; e-wallet, Virtual Account (BCA, Mandiri, BRI, BNI), dan kartu debit. Seluruh perputaran dana terlindungi escrow aman.
                            </p>
                        </div>
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="Multi-Payment">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 6: Bottom-Right Frame | Badge Left | Number Right --}}
                <div class="ticket-card-outer" data-aos="fade-up" data-aos-delay="300">
                    <svg class="ticket-corner-svg corner-bottom-right" viewBox="0 0 100 100" fill="none">
                        <path d="M 10 93 H 74 A 19 19 0 0 0 93 74 V 10" stroke="url(#btnDaftarGradient)" stroke-width="12" stroke-linecap="round"/>
                    </svg>
                    <div class="ticket-card-inner card-layout-even">
                        <div class="ticket-badge-col">
                            <div class="ticket-circle-badge" title="Statistik & Rekap">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                        </div>
                        <div class="ticket-body-col">
                            <h2 class="ticket-module-title">Statistik Penjualan &amp; Rekap Peserta</h2>
                            <p class="ticket-module-desc">
                                Pantau grafik penjualan tiket harian, total omzet, sisa kuota, serta unduh data rekap peserta (CSV/Excel) lengkap kapan saja dari dashboard.
                            </p>
                        </div>
                        <div class="ticket-num-col">
                            <span class="ticket-big-num">6</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Highlights & Keamanan Bar --}}
            <div class="ticket-highlights-bar" data-aos="fade-up" data-aos-delay="350">
                <div class="ticket-highlight-item">
                    <div class="ticket-highlight-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <div class="ticket-highlight-content">
                        <h4>100% Anti-Duplikasi</h4>
                        <p>Tiket hanya bisa di-scan 1 kali.</p>
                    </div>
                </div>

                <div class="ticket-highlight-item">
                    <div class="ticket-highlight-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <div class="ticket-highlight-content">
                        <h4>Check-in &lt;0.2 Detik</h4>
                        <p>Bebas antrean panjang di pintu gate.</p>
                    </div>
                </div>

                <div class="ticket-highlight-item">
                    <div class="ticket-highlight-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="ticket-highlight-content">
                        <h4>Proteksi Escrow</h4>
                        <p>Pencairan dana langsung ke rekening.</p>
                    </div>
                </div>

                <div class="ticket-highlight-item">
                    <div class="ticket-highlight-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <div class="ticket-highlight-content">
                        <h4>Multi-Payment</h4>
                        <p>QRIS, VA, dan E-Wallet terintegrasi.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
