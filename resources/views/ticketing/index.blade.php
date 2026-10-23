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
            max-width: 1200px;
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
            background: linear-gradient(135deg, #1eb349 0%, #16a34a 100%);
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

        /* ── Grid 6 Feature Modules ── */
        .ticket-features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 4.5rem;
        }

        .ticket-card {
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 24px;
            padding: 2rem 1.75rem;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        .ticket-card:hover {
            border-color: rgba(30, 179, 73, 0.4);
            transform: translateY(-5px);
            box-shadow: 0 16px 36px rgba(30, 179, 73, 0.1);
        }

        .ticket-card-badge-num {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 2.75rem;
            font-weight: 800;
            color: #0F172A;
            opacity: 0.04;
            line-height: 1;
            pointer-events: none;
            user-select: none;
            transition: opacity 0.3s ease;
        }

        .ticket-card:hover .ticket-card-badge-num {
            opacity: 0.08;
            color: #1eb349;
        }

        .ticket-card-icon-wrap {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            background: #F0FDF4;
            border: 1.5px solid rgba(30, 179, 73, 0.2);
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .ticket-card:hover .ticket-card-icon-wrap {
            background: linear-gradient(135deg, #1eb349 0%, #16a34a 100%);
            color: #FFFFFF;
            border-color: #1eb349;
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(30, 179, 73, 0.25);
        }

        .ticket-card-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0F172A;
            margin: 0 0 0.75rem;
            line-height: 1.35;
            letter-spacing: -0.015em;
        }

        .ticket-card-desc {
            font-size: 0.92rem;
            color: #64748B;
            line-height: 1.65;
            margin: 0;
            font-weight: 400;
        }

        /* ── Bottom Highlights Bar ── */
        .ticket-highlights-bar {
            margin-top: 3.5rem;
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

        /* Responsive Breakpoints */
        @media (max-width: 1024px) {
            .ticket-features-grid {
                grid-template-columns: repeat(2, 1fr);
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
            .ticket-features-grid {
                grid-template-columns: 1fr;
                gap: 1.15rem;
                margin-top: 3rem;
            }
            .ticket-card {
                padding: 1.5rem 1.25rem;
            }
            .ticket-highlights-bar {
                grid-template-columns: 1fr;
                padding: 1.25rem 1.25rem;
                gap: 1rem;
            }
        }
    </style>

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

            {{-- 6 Feature Modules Grid --}}
            <div class="ticket-features-grid">

                {{-- Feature 1: Pembuatan Tiket & Kuota --}}
                <div class="ticket-card" data-aos="fade-up" data-aos-delay="50">
                    <div class="ticket-card-badge-num">01</div>
                    <div class="ticket-card-icon-wrap">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <h2 class="ticket-card-title">Kustomisasi Event &amp; Kuota Tiket</h2>
                    <p class="ticket-card-desc">
                        Buat tiket gratis atau berbayar dalam hitungan menit. Atur kategori tiket bertingkat (Early Bird, Presale, VIP, OTS), kuota otomatis, batas waktu order, dan formulir data peserta.
                    </p>
                </div>

                {{-- Feature 2: E-Tiket PDF Otomatis --}}
                <div class="ticket-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="ticket-card-badge-num">02</div>
                    <div class="ticket-card-icon-wrap">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="18" x2="12" y2="12"></line>
                            <line x1="9" y1="15" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <h2 class="ticket-card-title">E-Tiket Otomatis dengan QR Unik</h2>
                    <p class="ticket-card-desc">
                        Setiap transaksi sukses langsung menghasilkan file E-Tiket PDF resmi ber-QR Code terenkripsi yang dikirim otomatis ke WhatsApp dan Email pembeli untuk kemudahan cetak atau simpan.
                    </p>
                </div>

                {{-- Feature 3: Gate Scanner Panitia --}}
                <div class="ticket-card" data-aos="fade-up" data-aos-delay="150">
                    <div class="ticket-card-badge-num">03</div>
                    <div class="ticket-card-icon-wrap">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                            <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                            <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                            <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                            <rect x="7" y="7" width="10" height="10" rx="1"></rect>
                        </svg>
                    </div>
                    <h2 class="ticket-card-title">Gate Scanner Bebas Alat Khusus</h2>
                    <p class="ticket-card-desc">
                        Validasi tiket masuk langsung dari browser kamera smartphone atau webcam tanpa harus sewa barcode scanner mahal. Waktu pemindaian kilat &lt;0.2 detik dengan notifikasi audio visual instan.
                    </p>
                </div>

                {{-- Feature 4: Multi-Staff & Proteksi PIN --}}
                <div class="ticket-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="ticket-card-badge-num">04</div>
                    <div class="ticket-card-icon-wrap">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <h2 class="ticket-card-title">Multi-Gate &amp; Akses Kru PIN Aman</h2>
                    <p class="ticket-card-desc">
                        Delegasikan tugas scan ke puluhan panitia di berbagai gate (Gate VIP, Reguler, Kru) cukup dengan tautan rahasia dan otorisasi PIN per kru. Kuota check-in tersinkronisasi realtime.
                    </p>
                </div>

                {{-- Feature 5: Pembayaran Instan & Escrow --}}
                <div class="ticket-card" data-aos="fade-up" data-aos-delay="250">
                    <div class="ticket-card-badge-num">05</div>
                    <div class="ticket-card-icon-wrap">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                    </div>
                    <h2 class="ticket-card-title">Multi-Payment &amp; Proteksi Escrow</h2>
                    <p class="ticket-card-desc">
                        Mendukung pembayaran QRIS dari semua m-banking &amp; e-wallet, Virtual Account (BCA, Mandiri, BRI, BNI), serta kartu debit. Seluruh perputaran dana diamankan sistem escrow terpercaya.
                    </p>
                </div>

                {{-- Feature 6: Realtime Analytics & Export --}}
                <div class="ticket-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="ticket-card-badge-num">06</div>
                    <div class="ticket-card-icon-wrap">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                    </div>
                    <h2 class="ticket-card-title">Statistik Penjualan &amp; Rekap Peserta</h2>
                    <p class="ticket-card-desc">
                        Pantau performa penjualan tiket harian, total pendapatan, perbandingan kuota per kategori, dan unduh data rekap peserta lengkap ke dalam format Excel atau CSV kapan saja.
                    </p>
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
