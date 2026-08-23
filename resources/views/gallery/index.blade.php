@extends('layouts.app')
@section('content')

<style>
/* ═══════════════════════════════════════
   DESIGN TOKENS — seragam dengan /about & /products
═══════════════════════════════════════ */
:root {
    --c-bg:      #ffffff;
    --c-surface: #F8FAFC;
    --c-card:    #ffffff;
    --c-border:  #E2E8F0;
    --c-text:    #0F172A;
    --c-muted:   #64748B;
    --c-accent:  #1eb349;
    --c-accent-hover: #16a34a;
    --c-white:   #ffffff;
    --radius-sm: 8px;
    --radius-md: 14px;
    --radius-lg: 20px;
    --font:      'Montserrat', sans-serif;
    --ease:      cubic-bezier(0.22, 1, 0.36, 1);
}

body { background-color: var(--c-bg); font-family: var(--font); }

/* ═══════════════════════════════════════
   PAGE HERO — 100% sama dengan /about & /products
═══════════════════════════════════════ */
.sv-hero-premium {
    position: relative;
    padding: 9rem 1.5rem 5rem;
    background: var(--c-surface);
    overflow: hidden;
    border-bottom: 1px solid var(--c-border);
}
.sv-hero-premium::before {
    content: '';
    position: absolute;
    top: -150px; right: -100px;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(30,179,73,0.06) 0%, transparent 70%);
    pointer-events: none;
    border-radius: 50%;
}
.sv-hero-premium::after {
    content: '';
    position: absolute;
    bottom: -150px; left: -100px;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(30,179,73,0.04) 0%, transparent 70%);
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

/* Breadcrumb — identik dengan /about & /products */
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
.sv-breadcrumb a:hover { color: var(--c-accent); }
.sv-breadcrumb-sep { font-size: 0.6rem; color: var(--c-muted); opacity: 0.5; }
.sv-breadcrumb-current { color: var(--c-text); font-weight: 600; }

/* Label — identik */
.sv-label {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--c-muted);
    margin-bottom: 1.25rem;
    font-family: var(--font);
}
.sv-label::before {
    content: '';
    display: block;
    width: 5px; height: 5px;
    background: var(--c-accent);
    border-radius: 50%;
}

/* Title & Intro — identik */
.sv-title {
    font-size: clamp(2rem, 4vw, 3.5rem);
    font-weight: 500;
    color: var(--c-text);
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
    color: var(--c-muted);
    line-height: 1.7;
    font-family: var(--font);
}

/* ═══════════════════════════════════════
   FILTER TABS
═══════════════════════════════════════ */
.gl-filters {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    flex-wrap: wrap;
    margin-bottom: 3rem;
    padding: 0 0.25rem;
}
.gl-filter-btn {
    padding: 0.5rem 1.25rem;
    font-size: 0.8125rem;
    font-weight: 600;
    font-family: var(--font);
    border: 1.5px solid var(--c-border);
    background: var(--c-card);
    color: var(--c-muted);
    cursor: pointer;
    border-radius: 50px;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.gl-filter-btn:hover,
.gl-filter-btn.active {
    background: var(--c-accent);
    border-color: var(--c-accent);
    color: #fff;
    box-shadow: 0 4px 14px rgba(30,179,73,0.25);
}

/* ═══════════════════════════════════════
   GALLERY GRID
═══════════════════════════════════════ */
.gl-section {
    padding: 4rem 1.5rem 6rem;
    max-width: 1200px;
    margin: 0 auto;
}
.gl-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
}

/* Gallery Card */
.gl-card {
    position: relative;
    border-radius: var(--radius-lg);
    overflow: hidden;
    aspect-ratio: 4/3;
    display: block;
    background: var(--c-surface);
    text-decoration: none !important;
    transition: transform 0.4s var(--ease), box-shadow 0.4s var(--ease);
}
.gl-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 50px rgba(30,179,73,0.15);
}
.gl-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s var(--ease);
    display: block;
}
.gl-card:hover img {
    transform: scale(1.08);
}
.gl-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.1) 55%, transparent 100%);
    display: flex;
    align-items: flex-end;
    padding: 1.5rem;
    transition: background 0.35s ease;
}
.gl-card:hover .gl-card-overlay {
    background: linear-gradient(to top, rgba(30,179,73,0.88) 0%, rgba(30,179,73,0.15) 55%, transparent 100%);
}
.gl-card-meta {
    transform: translateY(8px);
    transition: transform 0.35s var(--ease);
    width: 100%;
}
.gl-card:hover .gl-card-meta { transform: translateY(0); }
.gl-card-cat {
    font-size: 0.625rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.8);
    margin-bottom: 0.3rem;
    font-family: var(--font);
}
.gl-card-title {
    font-size: 0.9375rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.35;
    margin-bottom: 0.2rem;
    font-family: var(--font);
}
.gl-card-client {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.7);
    font-family: var(--font);
}
.gl-card-location {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.55);
    margin-top: 0.125rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-family: var(--font);
}
.gl-card-cta {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    font-size: 0.7rem;
    font-weight: 600;
    color: rgba(255,255,255,0.9);
    margin-top: 0.75rem;
    font-family: var(--font);
}
.gl-card-cta svg { transition: transform 0.2s; }
.gl-card:hover .gl-card-cta svg { transform: translateX(4px); }

/* Placeholder */
.gl-card-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #E2E8F0, #CBD5E1);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: #94A3B8;
    font-family: var(--font);
    font-size: 0.75rem;
    font-weight: 500;
}

/* Empty State */
.gl-empty {
    text-align: center;
    padding: 5rem 1.5rem;
    color: var(--c-muted);
    font-family: var(--font);
}
.gl-empty-icon {
    display: block;
    margin: 0 auto 1.25rem;
    opacity: 0.3;
}

/* ═══════════════════════════════════════
   CTA — sama dengan /products
═══════════════════════════════════════ */
.sv-cta-premium {
    background: var(--c-surface);
    padding: 5rem 1.5rem;
    border-top: 1px solid var(--c-border);
    position: relative;
    overflow: hidden;
}
.sv-cta-glow {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 800px; height: 800px;
    background: radial-gradient(circle, rgba(30,179,73,0.06) 0%, transparent 60%);
    pointer-events: none;
}
.sv-cta-inner {
    max-width: 1000px;
    margin: 0 auto;
    text-align: center;
    position: relative;
    z-index: 1;
}
.sv-cta-h2 {
    font-size: clamp(1.75rem, 3vw, 2.5rem);
    font-weight: 500;
    line-height: 1.2;
    letter-spacing: -0.02em;
    color: var(--c-text);
    margin-bottom: 1rem;
    font-family: var(--font);
}
.sv-cta-sub {
    font-size: 1rem;
    font-weight: 400;
    color: var(--c-muted);
    max-width: 600px;
    margin: 0 auto 2.5rem;
    line-height: 1.6;
    font-family: var(--font);
}
.sv-cta-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.btn-primary-v2 {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: var(--c-accent); color: #fff; font-family: var(--font);
    font-size: 0.9375rem; font-weight: 600; padding: 1rem 2rem;
    border-radius: 50px; border: none; cursor: pointer; text-decoration: none !important;
    transition: all 0.3s; box-shadow: 0 8px 20px rgba(30,179,73,0.25);
}
.btn-primary-v2:hover { background: var(--c-accent-hover); transform: translateY(-2px); color: #fff !important; }
.btn-outline-v2 {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: transparent; color: var(--c-text); font-family: var(--font);
    font-size: 0.9375rem; font-weight: 600; padding: 1rem 2rem;
    border-radius: 50px; border: 1.5px solid var(--c-border); cursor: pointer;
    text-decoration: none !important; transition: all 0.3s;
}
.btn-outline-v2:hover { border-color: var(--c-text); color: var(--c-text) !important; }

/* ═══════════════════════════════════════
   RESPONSIVE — MOBILE FRIENDLY
═══════════════════════════════════════ */
@media (max-width: 1024px) {
    .gl-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
    .sv-hero-premium { padding: 7rem 1rem 4rem; }
    .gl-section { padding: 3rem 1rem 4rem; }
    .gl-grid { grid-template-columns: 1fr; gap: 1rem; }
    .sv-cta-premium { padding: 4rem 1rem; }
    .sv-cta-btns { flex-direction: column; width: 100%; }
    .btn-primary-v2, .btn-outline-v2 { width: 100%; justify-content: center; }
    .gl-filters { gap: 0.5rem; }
    .gl-filter-btn { font-size: 0.75rem; padding: 0.45rem 1rem; }
}
@media (min-width: 641px) and (max-width: 1023px) {
    .gl-card { aspect-ratio: 16/10; }
}
</style>

{{-- ════ HERO — identik dengan /about & /products ════ --}}
<section class="sv-hero-premium">
    <div class="sv-hero-inner" data-aos="fade-up">

        {{-- Breadcrumb --}}
        <nav class="sv-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route_locale('home') }}">Beranda</a>
            <span class="sv-breadcrumb-sep">/</span>
            <span class="sv-breadcrumb-current">Katalog Kreator</span>
        </nav>

        <div class="sv-label">Karya & Portofolio</div>
        <h1 class="sv-title">
            Karya Terbaik<br>
            Para Kreator Digital
        </h1>
        <p class="sv-intro">
            Temukan inspirasi dari portofolio dan karya terbaik para kreator di buyle.id —
            produk digital, desain, template, dan aset kreatif berkualitas.
        </p>
    </div>
</section>

{{-- ════ GALLERY GRID ════ --}}
<section class="gl-section">

    {{-- Category Filter --}}
    @if($categories->count())
    <div class="gl-filters" id="cat-filters">
        <button class="gl-filter-btn active" data-filter="all" onclick="filterGallery('all', this)">
            Semua
        </button>
        @foreach($categories as $cat)
        <button class="gl-filter-btn" data-filter="{{ $cat }}" onclick="filterGallery('{{ $cat }}', this)">
            {{ $cat }}
        </button>
        @endforeach
    </div>
    @endif

    {{-- Grid --}}
    @if($gallery->isNotEmpty())
    <div class="gl-grid" id="gallery-grid">
        @foreach($gallery as $i => $item)
        <a href="{{ $item->image_url }}"
           class="gl-card gallery-item glightbox"
           data-gallery="buyle.id-gallery"
           data-title="{{ $item->title }}"
           data-description="{{ $item->client ? 'Klien: '.$item->client : '' }}"
           data-category="{{ $item->category }}"
           data-aos="fade-up"
           data-aos-delay="{{ ($i % 3) * 80 }}">

            @if($item->image)
                <img src="{{ $item->image_url }}"
                     alt="{{ $item->alt_text ?: $item->title }}"
                     loading="{{ $i < 6 ? 'eager' : 'lazy' }}">
            @else
                <div class="gl-card-placeholder">
                    <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                    Upload foto di Admin
                </div>
            @endif

            <div class="gl-card-overlay">
                <div class="gl-card-meta">
                    @if($item->category)
                    <div class="gl-card-cat">{{ $item->category }}</div>
                    @endif
                    <div class="gl-card-title">{{ $item->title }}</div>
                    @if($item->client)
                    <div class="gl-card-client">{{ $item->client }}</div>
                    @endif
                    @if($item->location)
                    <div class="gl-card-location">
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        {{ $item->location }}
                    </div>
                    @endif
                    <div class="gl-card-cta">
                        Lihat Detail
                        <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    @else
    <div class="gl-empty" data-aos="fade-up">
        <div style="
            background: #F0FDF4;
            border: 1.5px solid #BBF7D0;
            border-radius: 20px;
            padding: 3rem 2.5rem;
            max-width: 520px;
            margin: 0 auto;
            text-align: center;
        ">
            <div style="
                width: 64px; height: 64px;
                background: #DCFCE7;
                border-radius: 50%;
                display: flex; align-items: center; justify-content: center;
                margin: 0 auto 1.5rem;
            ">
                <svg width="28" height="28" fill="none" stroke="#1eb349" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                </svg>
            </div>
            <h3 style="font-size:1.25rem; font-weight:700; color:#166534; margin-bottom:0.75rem; font-family:'Montserrat',sans-serif;">
                Katalog Sedang Diperbarui
            </h3>
            <p style="font-size:0.9rem; color:#15803D; line-height:1.7; margin-bottom:1.5rem; font-family:'Montserrat',sans-serif;">
                Karya dan portofolio kreator sedang dalam proses penambahan.
                Segera hadir! Sementara itu, hubungi kami untuk informasi lebih lanjut.
            </p>
            <a href="{{ route_locale('contact') }}" style="
                display: inline-flex; align-items: center; gap: 0.5rem;
                background: #1eb349; color: #fff; font-family: 'Montserrat', sans-serif;
                font-size: 0.875rem; font-weight: 600; padding: 0.75rem 1.75rem;
                border-radius: 50px; text-decoration: none; transition: all 0.3s;
            ">Hubungi Kami
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
    </div>
    @endif
</section>

{{-- ════ CTA ════ --}}
<section class="sv-cta-premium" data-aos="fade-up">
    <div class="sv-cta-glow"></div>
    <div class="sv-cta-inner">
        <div class="sv-label" style="margin-bottom:1rem;">Jadi Kreator?</div>
        <h2 class="sv-cta-h2">Tampilkan Karya Anda<br>di buyle.id dan Mulai Berjualan</h2>
        <p class="sv-cta-sub">
            Daftarkan diri sebagai kreator, unggah produk digital Anda,
            dan jangkau ribuan pembeli di seluruh Indonesia.
        </p>
        <div class="sv-cta-btns">
            <a href="{{ route('creator.onboarding') }}" class="btn-primary-v2">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                Daftar sebagai Kreator
            </a>
            <a href="{{ route_locale('contact') }}" class="btn-outline-v2">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Hubungi Kami
            </a>
        </div>
    </div>
</section>

@include('components.lightbox-assets')

<script>

function filterGallery(cat, btn) {
    document.querySelectorAll('#cat-filters .gl-filter-btn').forEach(b => {
        b.classList.remove('active');
    });
    btn.classList.add('active');
    document.querySelectorAll('.gallery-item').forEach(item => {
        const show = cat === 'all' || item.dataset.category === cat;
        item.style.display = show ? 'block' : 'none';
    });
}
</script>

@endsection
