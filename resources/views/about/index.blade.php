@extends('layouts.app')

@section('content')

    <style>
        /* ── PREMIUM STYLING ADAPTED FROM HOMEPAGE ── */
        :root {
            --cv-bg: #ffffff;
            --cv-surface: #f8fafc;
            --cv-surface-dark: #f1f5f9;
            --cv-border: #e2e8f0;
            --cv-text: #0f172a;
            --cv-muted: #64748b;
            --cv-accent: #1eb349;
            --cv-accent-green: #10b981;
            --c-muted: #64748B;
            --c-accent: #1eb349;
            --c-text: #0F172A;
            --font: 'Montserrat', sans-serif;
        }

        body {
            background: var(--cv-bg);
            color: var(--cv-text);
        }

        .cv-section-pad {
            padding: 6rem 1.5rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ═══════════════════════════════════════
       PAGE HERO — 100% sama dengan /products
    ═══════════════════════════════════════ */
        .sv-hero-premium {
            position: relative;
            padding: 9rem 1.5rem 5rem;
            background: #F8FAFC;
            overflow: hidden;
            border-bottom: 1px solid #E2E8F0;
        }

        .sv-hero-premium::before {
            content: '';
            position: absolute;
            top: -150px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(30, 179, 73, 0.06) 0%, transparent 70%);
            pointer-events: none;
            border-radius: 50%;
        }

        .sv-hero-premium::after {
            content: '';
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(30, 179, 73, 0.04) 0%, transparent 70%);
            pointer-events: none;
            border-radius: 50%;
        }

        .sv-hero-inner {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .sv-label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #64748B;
            margin-bottom: 1.25rem;
            font-family: var(--font);
        }

        .sv-label::before {
            content: '';
            display: block;
            width: 5px;
            height: 5px;
            background: #1eb349;
            border-radius: 50%;
        }

        .sv-title {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 500;
            color: #0F172A;
            line-height: 1.15;
            letter-spacing: -0.03em;
            font-family: var(--font);
            margin-bottom: 1.5rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .sv-intro {
            margin: 0 auto;
            max-width: 650px;
            font-size: 1rem;
            font-weight: 400;
            color: #64748B;
            line-height: 1.7;
            font-family: var(--font);
        }

        @media (max-width: 640px) {
            .sv-hero-premium {
                padding: 7rem 1rem 4rem;
            }
        }

        /* breadcrumb */
        .sv-breadcrumb {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--c-muted);
            margin-bottom: 2.5rem;
            font-family: var(--font);
        }

        .sv-breadcrumb a {
            color: var(--c-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .sv-breadcrumb a:hover {
            color: var(--c-accent);
        }

        .sv-breadcrumb-sep {
            font-size: 0.6rem;
            color: var(--c-muted);
            opacity: 0.5;
        }

        .sv-breadcrumb-current {
            color: var(--c-text);
            font-weight: 600;
        }

        .cv-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--cv-muted);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .cv-label::before {
            content: '';
            width: 4px;
            height: 4px;
            background: var(--cv-accent);
            border-radius: 50%;
        }

        .cv-heading {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 500;
            line-height: 1.15;
            letter-spacing: -0.03em;
            color: var(--cv-text);
        }

        .cv-heading strong {
            font-weight: 600;
        }

        .cv-heading .ab-icon-blue,
        .cv-heading .ab-icon-green {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1em;
            height: 1em;
            border-radius: 50%;
            vertical-align: middle;
            margin: 0 0.1em;
            transform: translateY(-0.1em);
        }

        .cv-heading .ab-icon-blue {
            background: var(--cv-accent);
            color: #fff;
            padding: 0.2em;
        }

        .cv-heading .ab-icon-green {
            background: var(--cv-accent-green);
            color: #fff;
            padding: 0.2em;
        }

        .cv-desc {
            font-size: 1rem;
            color: var(--cv-muted);
            line-height: 1.7;
            margin-top: 1.5rem;
        }

        /* ── CARDS GRID ── */
        .cv-cards-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1.5rem;
            margin-top: 4rem;
        }

        @media (min-width: 768px) {
            .cv-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .cv-cards-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .cv-card {
            border-radius: 24px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            min-height: 320px;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .cv-card:hover {
            transform: translateY(-5px);
        }

        .cv-card-gray {
            background: var(--cv-surface-dark);
            border: 1px solid var(--cv-border);
        }

        .cv-card-accent {
            background: var(--cv-accent);
            color: #fff;
        }

        .cv-card-image {
            padding: 2rem;
        }

        .cv-card-image .cv-card-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        .cv-card-image .cv-card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 60%);
            z-index: 1;
        }

        .cv-card-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .cv-card-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--cv-muted);
            margin-bottom: 1rem;
        }

        .cv-card-accent .cv-card-label {
            color: rgba(255, 255, 255, 0.9);
        }

        .cv-card-value {
            font-size: 3rem;
            font-weight: 400;
            line-height: 1;
            color: var(--cv-text);
            letter-spacing: -0.05em;
        }

        .cv-card-accent .cv-card-value,
        .cv-card-image .cv-card-value {
            color: #fff;
        }

        .cv-card-desc {
            font-size: 0.95rem;
            line-height: 1.5;
            color: #475569;
            font-weight: 400;
            margin-top: auto;
        }

        .cv-card-accent .cv-card-desc,
        .cv-card-image .cv-card-desc {
            color: rgba(255, 255, 255, 0.9);
        }

        .cv-card-bg-pattern {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            opacity: 0.6;
        }

        .cv-chip {
            position: absolute;
            background: #ffffff;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #94a3b8;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            white-space: nowrap;
        }

        .cv-card-gray .cv-card-content.push-bottom {
            margin-top: auto;
        }

        /* Marquee */
        .cv-clients-bar {
            background: var(--cv-surface);
            border-top: 1px solid var(--cv-border);
            border-bottom: 1px solid var(--cv-border);
            padding: 2.5rem 0;
            overflow: hidden;
            margin-top: 0;
        }

        .cv-marquee-container {
            width: 100%;
            overflow: hidden;
            position: relative;
            display: flex;
        }

        .cv-marquee-container::before,
        .cv-marquee-container::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 150px;
            z-index: 2;
            pointer-events: none;
        }

        .cv-marquee-container::before {
            left: 0;
            background: linear-gradient(to right, var(--cv-surface), transparent);
        }

        .cv-marquee-container::after {
            right: 0;
            background: linear-gradient(to left, var(--cv-surface), transparent);
        }

        @keyframes scrollLeft {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .cv-marquee-track {
            display: flex;
            gap: 1.5rem;
            padding: 0 1rem;
            width: max-content;
            animation: scrollLeft 30s linear infinite;
        }

        .cv-marquee-container:hover .cv-marquee-track {
            animation-play-state: paused;
        }

        .cv-client-chip {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--cv-bg);
            border: 1px solid var(--cv-border);
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--cv-text);
            white-space: nowrap;
        }

        .cv-client-chip::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--cv-accent);
            flex-shrink: 0;
        }
    </style>

    {{-- ════ 1. HERO — sama persis dengan /products ════ --}}
    <section class="sv-hero-premium">
        <div class="sv-hero-inner" data-aos="fade-up">

            {{-- Breadcrumb — 100% identik dengan /products --}}
            <nav class="sv-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route_locale('home') }}">Beranda</a>
                <span class="sv-breadcrumb-sep">/</span>
                <span class="sv-breadcrumb-current">Tentang Kami</span>
            </nav>

            <div class="sv-label">Profil Platform</div>
            <h1 class="sv-title">
                Marketplace Digital Creator<br>
                Terpercaya di Indonesia
            </h1>
            <p class="sv-intro">
                buyle.id adalah platform marketplace yang menghubungkan para kreator berbakat
                dengan pembeli yang mencari produk digital, aset kreatif, dan layanan jasa profesional berkualitas.
            </p>
        </div>
    </section>

    {{-- ════ 2. ABOUT CARDS ════ --}}
    <section style="padding: 5rem 1.5rem; background: #ffffff;">
        <div class="container">
            <div class="cv-cards-grid">
                <div class="cv-card cv-card-gray" data-aos="fade-up">
                    <div class="cv-card-bg-pattern">
                        <span class="cv-chip" style="top:10%;left:5%;">Produk Digital</span>
                        <span class="cv-chip" style="top:15%;left:45%;">Instan Download</span>
                        <span class="cv-chip" style="top:12%;left:75%;">Terverifikasi</span>
                        <span class="cv-chip" style="top:35%;left:15%;">Aman</span>
                        <span class="cv-chip" style="top:38%;left:50%;">Terpercaya</span>
                        <span class="cv-chip" style="top:60%;left:5%;">Kreator Lokal</span>
                        <span class="cv-chip" style="top:65%;left:40%;">Berkualitas</span>
                        <span class="cv-chip" style="top:62%;left:75%;">Garansi</span>
                    </div>
                    <div class="cv-card-content push-bottom">
                        <div class="cv-card-label">Kategori Produk</div>
                        <div class="cv-card-value">50+</div>
                    </div>
                </div>

                <div class="cv-card cv-card-accent" data-aos="fade-up" data-aos-delay="100">
                    <div class="cv-card-content">
                        <div class="cv-card-label">Komitmen Platform</div>
                        <div class="cv-card-value">100%</div>
                        <div class="cv-card-desc">Transaksi aman, produk terverifikasi, dan akses instan setelah pembayaran
                            → "Jaminan kepuasan pembeli setiap saat."</div>
                    </div>
                </div>

                <div class="cv-card cv-card-image" data-aos="fade-up" data-aos-delay="200">
                    @php $aboutImgFallback = !empty($settings['logo']) ? asset('storage/' . $settings['logo']) : asset('images/logo.png'); @endphp
                    <img src="{{ !empty($settings['about_image']) ? asset('storage/' . $settings['about_image']) : $aboutImgFallback }}"
                        alt="Tim buyle.id" class="cv-card-img" loading="lazy">
                    <div class="cv-card-overlay"></div>
                    <div class="cv-card-content" style="justify-content: flex-end;">
                        <div class="cv-card-value">1K+</div>
                        <div class="cv-card-desc">Kreator aktif dari seluruh Indonesia yang menjual karya dan layanan
                            terbaik mereka di platform buyle.id.</div>
                    </div>
                </div>

                <div class="cv-card cv-card-gray" data-aos="fade-up" data-aos-delay="300">
                    <div class="cv-card-content">
                        <div class="cv-card-label">Transaksi Terlayani</div>
                        <div class="cv-card-value">10k+</div>
                        <div class="cv-card-desc" style="margin-top:auto;">Transaksi produk digital yang berhasil
                            diselesaikan dengan aman dan instan.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════ 3. VISI & MISI ════ --}}
    <section style="padding: 5rem 1.5rem; background: var(--cv-surface); border-top: 1px solid var(--cv-border);">
        <div class="container">
            <div style="text-align:center; max-width:800px; margin:0 auto 3rem;">
                <div class="cv-adv-section-label" style="justify-content:center;">PROFIL</div>
                <h2 class="cv-adv-section-title" style="text-align:center;">Visi &amp; Misi Kami</h2>
            </div>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px,1fr)); gap:1.5rem;">
                <div class="cv-card cv-card-gray" style="min-height:auto;" data-aos="fade-up">
                    <div class="cv-card-content">
                        <div class="cv-card-label" style="color:var(--cv-accent); font-weight:700;">Visi Platform</div>
                        <h3 style="font-size:1.5rem; font-weight:600; color:var(--cv-text); margin-bottom:1rem;">Menjadi
                            Pelopor</h3>
                        <p style="font-size:0.9375rem; color:#475569; line-height:1.7; margin:0;">
                            {{ $settings['visi'] ?? 'Menjadi digital creator marketplace terdepan di Indonesia yang menghubungkan kreator berbakat dengan pembeli yang mencari karya dan layanan digital berkualitas.' }}
                        </p>
                    </div>
                </div>
                <div class="cv-card cv-card-gray" style="min-height:auto;" data-aos="fade-up" data-aos-delay="100">
                    <div class="cv-card-content">
                        <div class="cv-card-label" style="color:var(--cv-accent); font-weight:700;">Misi Platform</div>
                        <h3 style="font-size:1.5rem; font-weight:600; color:var(--cv-text); margin-bottom:1rem;">Ekosistem
                            Digital</h3>
                        <p style="font-size:0.9375rem; color:#475569; line-height:1.7; margin:0;">
                            {{ $settings['misi'] ?? 'Membangun ekosistem marketplace yang adil, aman, dan menguntungkan bagi kreator dan pembeli; mendorong pertumbuhan ekonomi digital Indonesia.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════ INJECT STYLES AND SECTIONS FROM HOME ════ --}}
    <style>
        /* ── KEUNGGULAN ───────────────────────────── */
        .cv-adv-premium {
            background: #ffffff;
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }

        .cv-adv-premium::before {
            content: '';
            position: absolute;
            top: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(30, 179, 73, 0.04) 0%, transparent 70%);
            pointer-events: none;
        }

        .cv-adv-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Header row: label + title left, CTA right */
        .cv-adv-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 2rem;
            margin-bottom: 3.5rem;
            flex-wrap: wrap;
        }

        .cv-adv-section-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #64748B;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .cv-adv-section-label::before {
            content: '';
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #1eb349;
        }

        .cv-adv-section-title {
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 500;
            color: #0F172A;
            line-height: 1.15;
            letter-spacing: -0.025em;
        }

        /* Premium 7-card grid — matches about section */
        .cv-adv-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }

        /* Special first card: full-height accent (like about card-2) */
        .cv-adv-card-v2 {
            background: #F1F5F9;
            border-radius: 22px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 240px;
            transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.3s;
        }

        .cv-adv-card-v2:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(30, 179, 73, 0.1);
        }

        .cv-adv-card-v2.accent {
            background: #1eb349;
        }

        .cv-adv-card-v2.accent-dark {
            background: #0F172A;
        }

        .cv-adv-card-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            flex-shrink: 0;
        }

        .cv-adv-card-icon-wrap.blue-bg {
            background: #dcfce7;
            color: #1eb349;
        }

        .cv-adv-card-icon-wrap.white-bg {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .cv-adv-card-icon-wrap.dark-bg {
            background: rgba(255, 255, 255, 0.06);
            color: #a5cf37;
        }

        .cv-adv-card-num {
            font-size: 2.75rem;
            font-weight: 400;
            line-height: 1;
            letter-spacing: -0.04em;
            color: #0F172A;
            margin-bottom: 0.5rem;
        }

        .cv-adv-card-num.white {
            color: #fff;
        }

        .cv-adv-card-num.blue {
            color: #a5cf37;
        }

        .cv-adv-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #0F172A;
            margin-bottom: 0.5rem;
        }

        .cv-adv-card-title.white {
            color: #fff;
        }

        .cv-adv-card-title.light {
            color: rgba(255, 255, 255, 0.9);
        }

        .cv-adv-card-desc {
            font-size: 0.8125rem;
            line-height: 1.65;
            color: #64748B;
            margin-top: auto;
        }

        .cv-adv-card-desc.white {
            color: rgba(255, 255, 255, 0.75);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .cv-adv-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .cv-adv-premium {
                padding: 3.5rem 0;
            }

            .cv-adv-cards {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .cv-adv-card-v2 {
                min-height: 180px;
            }
        }

        /* ── APLIKASI ─────────────────────────────── */
        .cv-apps-premium {
            background: #F8FAFC;
            padding: 5rem 0;
        }

        .cv-apps-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .cv-apps-header {
            max-width: 600px;
            margin-bottom: 3rem;
        }

        /* Horizontal scroll row of app cards */
        .cv-apps-grid-v2 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }

        .cv-app-card-v2 {
            background: #ffffff;
            border: 1.5px solid #E2E8F0;
            border-radius: 20px;
            padding: 1.75rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .cv-app-card-v2:hover {
            border-color: #1eb349;
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(30, 179, 73, 0.1);
        }

        .cv-app-icon-circle {
            width: 50px;
            height: 50px;
            background: #F0F9FF;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1eb349;
            flex-shrink: 0;
            transition: all 0.3s;
        }

        .cv-app-card-v2:hover .cv-app-icon-circle {
            background: #1eb349;
            color: #fff;
        }

        .cv-app-card-title-v2 {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #0F172A;
            margin: 0;
        }

        .cv-app-card-desc-v2 {
            font-size: 0.8125rem;
            color: #64748B;
            line-height: 1.65;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .cv-apps-grid-v2 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .cv-apps-premium {
                padding: 3.5rem 0;
            }

            .cv-apps-grid-v2 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
    <section class="cv-adv-premium" id="keunggulan">
        <div class="cv-adv-inner">
            {{-- Section Header --}}
            <div class="cv-adv-header">
                <div>
                    <div class="cv-adv-section-label">KEUNGGULAN</div>
                    <h2 class="cv-adv-section-title">Mengapa Pilih<br>buyle.id?</h2>
                </div>
                <p style="max-width:320px;font-size:0.875rem;color:#64748B;line-height:1.65;text-align:right;">
                    Platform yang dirancang khusus untuk ekosistem digital creator Indonesia, terpercaya oleh ribuan kreator
                    digital dan pembeli.
                </p>
            </div>

            {{-- Premium Cards Grid --}}
            <div class="cv-adv-cards">

                {{-- Card 1: E-Ticketing & Event Pass — Accent Green --}}
                <div class="cv-adv-card-v2 accent" data-aos="fade-up" data-aos-delay="0">
                    <div class="cv-adv-card-icon-wrap white-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z" />
                            <path d="M13 5v2m0 4v2m0 4v2" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-num white">100%</div>
                    <div class="cv-adv-card-title white">E-Ticketing & Event Pass</div>
                    <div class="cv-adv-card-desc white">Sistem tiket digital konser, workshop, webinar, hingga tempat wisata. Dilengkapi QR Code scan cepat anti-duplikasi & dashboard check-in gate staff.</div>
                </div>

                {{-- Card 2: Web & Bio Link Builder — Gray --}}
                <div class="cv-adv-card-v2" data-aos="fade-up" data-aos-delay="80">
                    <div class="cv-adv-card-icon-wrap blue-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <path d="M3 9h18M9 21V9" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-num">⚡</div>
                    <div class="cv-adv-card-title">Web & Bio Link Builder</div>
                    <div class="cv-adv-card-desc">Bikin landing page dan bio link toko online kustom instan tanpa koding. Dukungan kustom domain, tema estetik, formulir order, dan analitik pengunjung.</div>
                </div>

                {{-- Card 3: Whitelabel & Jual Produk Orang (Affiliate / Reseller) — Gray --}}
                <div class="cv-adv-card-v2" data-aos="fade-up" data-aos-delay="160">
                    <div class="cv-adv-card-icon-wrap blue-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="m17 2 4 4-4 4" />
                            <path d="M3 11v-1a4 4 0 0 1 4-4h14" />
                            <path d="m7 22-4-4 4-4" />
                            <path d="M21 13v1a4 4 0 0 1-4 4H3" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-num">Margin Bebas</div>
                    <div class="cv-adv-card-title">Whitelabel & Jual Produk Mitra</div>
                    <div class="cv-adv-card-desc">Mulai bisnis tanpa modal produk sendiri. Jual produk digital kreator lain dengan harga Anda sendiri (whitelabel) atau dapatkan komisi afiliasi otomatis.</div>
                </div>

                {{-- Card 4: Transaksi Aman Escrow — Dark --}}
                <div class="cv-adv-card-v2 accent-dark" data-aos="fade-up" data-aos-delay="240">
                    <div class="cv-adv-card-icon-wrap dark-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-num blue">100%</div>
                    <div class="cv-adv-card-title light">Transaksi Terlindungi Escrow</div>
                    <div class="cv-adv-card-desc white">Dana pembeli tersimpan aman di rekening penampung (escrow) sampai produk diterima. Kreator dapat mencairkan saldo kapan saja tanpa risiko.</div>
                </div>

                {{-- Card 5: Marketplace Produk Digital --}}
                <div class="cv-adv-card-v2" data-aos="fade-up" data-aos-delay="0">
                    <div class="cv-adv-card-icon-wrap blue-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" y1="15" x2="12" y2="3" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-title" style="margin-top:auto;">Produk Digital & Akses Instan</div>
                    <div class="cv-adv-card-desc">Template Canva & Notion, source code aplikasi, desain grafis, e-book, hingga course. File langsung terkirim dan bisa diunduh detik itu juga.</div>
                </div>

                {{-- Card 6: Jasa Kreator & Sesi 1-on-1 --}}
                <div class="cv-adv-card-v2" data-aos="fade-up" data-aos-delay="80">
                    <div class="cv-adv-card-icon-wrap blue-bg">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div class="cv-adv-card-title" style="margin-top:auto;">Jasa Kreator & Sesi 1-on-1</div>
                    <div class="cv-adv-card-desc">Booking jasa freelance profesional dan sesi konsultasi privat dengan kreator favorit lewat kalender jadwal tersinkronisasi dan brief terorganisir.</div>
                </div>

                {{-- Card 7: Payment Gateway & Payout — spans 2 columns --}}
                <div class="cv-adv-card-v2" data-aos="fade-up" data-aos-delay="160"
                    style="grid-column: span 2; flex-direction: row; gap: 2rem; align-items: center;">
                    <div class="cv-adv-card-icon-wrap blue-bg" style="flex-shrink:0; width:60px; height:60px;">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <div class="cv-adv-card-title" style="font-size:1.125rem; margin-bottom:0.5rem;">Pembayaran Terlengkap & Payout Cepat</div>
                        <div class="cv-adv-card-desc">Mendukung pembayaran via QRIS semua bank/e-wallet (GoPay, OVO, DANA, ShopeePay), Virtual Account, dan Kartu Kredit. Pencairan saldo mudah dan transparan.</div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @if($testimonials->count())
        <style>
            /* ── GALERI ─────────────────────────────── */
            .cv-gallery-premium {
                background: #ffffff;
                padding: 5rem 0;
            }

            .cv-gallery-inner {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 1.5rem;
            }

            .cv-gallery-header {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                gap: 2rem;
                margin-bottom: 3.5rem;
                flex-wrap: wrap;
            }

            .cv-gallery-grid-v2 {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }

            .cv-gallery-card-v2 {
                position: relative;
                border-radius: 20px;
                overflow: hidden;
                aspect-ratio: 4/3;
                display: block;
                background: #F1F5F9;
            }

            .cv-gallery-img-v2 {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
            }

            .cv-gallery-card-v2:hover .cv-gallery-img-v2 {
                transform: scale(1.08);
            }

            .cv-gallery-overlay-v2 {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0) 60%);
                display: flex;
                align-items: flex-end;
                padding: 1.5rem;
                transition: background 0.3s;
            }

            .cv-gallery-card-v2:hover .cv-gallery-overlay-v2 {
                background: linear-gradient(to top, rgba(30, 179, 73, 0.9) 0%, rgba(15, 23, 42, 0) 70%);
            }

            .cv-gallery-meta-v2 {
                color: #fff;
                transform: translateY(10px);
                transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            }

            .cv-gallery-card-v2:hover .cv-gallery-meta-v2 {
                transform: translateY(0);
            }

            .cv-gallery-title-v2 {
                font-size: 1.125rem;
                font-weight: 600;
                margin-bottom: 0.25rem;
            }

            .cv-gallery-client-v2 {
                font-size: 0.8125rem;
                color: rgba(255, 255, 255, 0.75);
            }

            /* ── TESTIMONI ──────────────────────────── */
            .cv-testi-premium {
                background: #F8FAFC;
                padding: 5rem 0;
                position: relative;
                overflow: hidden;
            }

            .cv-testi-premium::before {
                content: '';
                position: absolute;
                bottom: -200px;
                left: -200px;
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(30, 179, 73, 0.04) 0%, transparent 70%);
                pointer-events: none;
            }

            .cv-testi-inner {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 1.5rem;
            }

            .cv-testi-grid-v2 {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }

            .cv-testi-card-v2 {
                background: #ffffff;
                border: 1.5px solid #E2E8F0;
                border-radius: 20px;
                padding: 2.25rem;
                position: relative;
                transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
            }

            .cv-testi-card-v2:hover {
                border-color: #1eb349;
                transform: translateY(-6px);
                box-shadow: 0 16px 40px rgba(30, 179, 73, 0.1);
            }

            .cv-testi-quote-icon {
                position: absolute;
                top: 1.5rem;
                right: 1.5rem;
                color: #F1F5F9;
                width: 48px;
                height: 48px;
                transition: color 0.3s;
            }

            .cv-testi-card-v2:hover .cv-testi-quote-icon {
                color: #dcfce7;
            }

            .cv-testi-stars-v2 {
                display: flex;
                gap: 0.25rem;
                color: #F59E0B;
                margin-bottom: 1.25rem;
            }

            .cv-testi-text-v2 {
                font-size: 0.9375rem;
                line-height: 1.7;
                color: #475569;
                margin-bottom: 2rem;
                position: relative;
                z-index: 1;
            }

            .cv-testi-author-row {
                display: flex;
                align-items: center;
                gap: 1rem;
                border-top: 1px solid #F1F5F9;
                padding-top: 1.25rem;
            }

            .cv-testi-avatar-v2 {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: #F1F5F9;
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.125rem;
                font-weight: 600;
                object-fit: cover;
            }

            .cv-testi-name-v2 {
                font-size: 0.9375rem;
                font-weight: 700;
                color: #0F172A;
                margin-bottom: 0.15rem;
            }

            .cv-testi-pos-v2 {
                font-size: 0.75rem;
                color: #64748B;
            }

            /* Responsive */
            @media (max-width: 1024px) {
                .cv-gallery-grid-v2 {
                    grid-template-columns: repeat(2, 1fr);
                }

                .cv-testi-grid-v2 {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 640px) {

                .cv-gallery-premium,
                .cv-testi-premium {
                    padding: 3.5rem 0;
                }

                .cv-gallery-grid-v2,
                .cv-testi-grid-v2 {
                    grid-template-columns: 1fr;
                }
            }
        </style>
        <section class="cv-testi-premium" id="testimoni">
            <div class="cv-testi-inner">
                <div style="text-align:center; max-width:600px; margin:0 auto 3.5rem;">
                    <div class="cv-adv-section-label" style="justify-content:center;">TESTIMONI KLIEN</div>
                    <h2 class="cv-adv-section-title" style="margin-top:0.75rem;">Kata Mereka yang<br>Sudah Menggunakan</h2>
                </div>

                <div class="cv-testi-grid-v2">
                    @foreach($testimonials->take(3) as $i => $testi)
                        <div class="cv-testi-card-v2" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                            <svg class="cv-testi-quote-icon" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                            <div class="cv-testi-stars-v2">
                                @for($s = 0; $s < ($testi->rating ?? 5); $s++)
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="cv-testi-text-v2">"{{ $testi->content }}"</p>
                            <div class="cv-testi-author-row">
                                <img src="{{ $testi->photo_url }}" alt="{{ $testi->name }}" class="cv-testi-avatar-v2">
                                <div>
                                    <div class="cv-testi-name-v2">{{ $testi->name }}</div>
                                    <div class="cv-testi-pos-v2">{{ $testi->position }} — {{ $testi->company }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- MARQUEE KLIEN --}}
    @if($clients->count())
        <section class="cv-clients-bar">
            <div class="container" style="margin-bottom:1.5rem; text-align:center;">
                <div class="cv-label">Klien Aktif</div>
                <h2 class="cv-heading" style="font-size:clamp(1.5rem,2.5vw,2rem);">Dipercaya oleh Perusahaan Terkemuka</h2>
            </div>
            <div class="cv-marquee-container">
                <div class="cv-marquee-track">
                    @foreach([1, 2] as $loopGroup)
                        @foreach($clients as $c)
                            @if($c->logo)
                                <div class="cv-client-logo-card">
                                    <img src="{{ asset('storage/' . $c->logo) }}" alt="{{ $c->name }}" class="cv-client-logo-img"
                                        title="{{ $c->name }}">
                                </div>
                            @else
                                <div class="cv-client-chip-v2">{{ $c->name }}</div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
