@extends('layouts.app')
@section('title', 'Pusat Bantuan & FAQ | buyle.id — Digital Creator Marketplace')
@section('meta_description', 'Pusat bantuan dan FAQ. Temukan jawaban untuk pertanyaan umum seputar pembelian, penjualan produk digital, lisensi, dan layanan di buyle.id.')
@section('content')

<style>
/* ═══════════════════════════════════════
   DESIGN TOKENS — seragam dengan /about & /products & /gallery
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
    --font:      'Montserrat', sans-serif;
    --ease:      cubic-bezier(0.22, 1, 0.36, 1);
}
body { background: var(--c-bg); font-family: var(--font); color: var(--c-text); }

/* ════ HERO — identik dengan /about /products /gallery ════ */
.sv-hero-premium {
    position: relative; padding: 9rem 1.5rem 5rem;
    background: var(--c-surface); overflow: hidden;
    border-bottom: 1px solid var(--c-border);
}
.sv-hero-premium::before {
    content:''; position:absolute; top:-150px; right:-100px;
    width:500px; height:500px; border-radius:50%;
    background:radial-gradient(circle,rgba(30,179,73,0.06) 0%,transparent 70%);
    pointer-events:none;
}
.sv-hero-premium::after {
    content:''; position:absolute; bottom:-150px; left:-100px;
    width:600px; height:600px; border-radius:50%;
    background:radial-gradient(circle,rgba(30,179,73,0.04) 0%,transparent 70%);
    pointer-events:none;
}
.sv-hero-inner {
    max-width:1200px; margin:0 auto;
    position:relative; z-index:2; text-align:center;
}
/* Breadcrumb — identik */
.sv-breadcrumb {
    display:flex; align-items:center; justify-content:center;
    gap:0.5rem; font-size:0.75rem; font-weight:500;
    color:var(--c-muted); margin-bottom:2.5rem; font-family:var(--font);
}
.sv-breadcrumb a { color:var(--c-muted); text-decoration:none; transition:color 0.2s; }
.sv-breadcrumb a:hover { color:var(--c-accent); }
.sv-breadcrumb-sep { font-size:0.6rem; color:var(--c-muted); opacity:0.5; }
.sv-breadcrumb-current { color:var(--c-text); font-weight:600; }
/* Label — identik */
.sv-label {
    display:inline-flex; align-items:center; justify-content:center;
    gap:0.5rem; font-size:0.75rem; font-weight:700;
    letter-spacing:0.15em; text-transform:uppercase;
    color:var(--c-muted); margin-bottom:1.25rem; font-family:var(--font);
}
.sv-label::before {
    content:''; display:block; width:5px; height:5px;
    background:var(--c-accent); border-radius:50%;
}
.sv-title {
    font-size:clamp(2rem,4vw,3.5rem); font-weight:500; color:var(--c-text);
    line-height:1.15; letter-spacing:-0.03em; font-family:var(--font);
    margin-bottom:1.5rem; max-width:800px; margin-left:auto; margin-right:auto;
}
.sv-intro {
    margin:0 auto; max-width:650px; font-size:1rem;
    font-weight:400; color:var(--c-muted); line-height:1.7; font-family:var(--font);
}

/* ════ MAIN LAYOUT ════ */
.ar-section {
    max-width:1200px; margin:0 auto;
    padding:4rem 1.5rem 6rem;
    display:grid;
    grid-template-columns: 1fr 320px;
    gap:3rem;
    align-items:start;
}

/* ════ ARTICLE CARDS ════ */
.ar-grid {
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:1.75rem;
}
.ar-card {
    background:var(--c-card);
    border:1.5px solid var(--c-border);
    border-radius:20px;
    overflow:hidden;
    display:flex;
    flex-direction:column;
    text-decoration:none !important;
    transition:all 0.35s var(--ease);
}
.ar-card * { text-decoration:none !important; }
.ar-card:hover {
    border-color:var(--c-accent);
    transform:translateY(-6px);
    box-shadow:0 20px 40px rgba(30,179,73,0.09);
}
.ar-card-img {
    width:100%; aspect-ratio:16/10;
    overflow:hidden; position:relative;
    background:var(--c-surface);
}
.ar-card-img img {
    width:100%; height:100%; object-fit:cover;
    transition:transform 0.55s var(--ease);
    display:block;
}
.ar-card:hover .ar-card-img img { transform:scale(1.07); }
.ar-card-badge {
    position:absolute; top:1rem; left:1rem;
    background:rgba(15,23,42,0.82);
    backdrop-filter:blur(6px);
    color:#fff; font-size:0.65rem; font-weight:700;
    letter-spacing:0.1em; text-transform:uppercase;
    padding:0.3rem 0.75rem; border-radius:50px;
    font-family:var(--font);
}
.ar-card-body {
    padding:1.5rem;
    display:flex; flex-direction:column; flex-grow:1;
}
.ar-card-meta {
    display:flex; align-items:center; gap:0.5rem;
    font-size:0.75rem; color:var(--c-muted);
    font-family:var(--font); margin-bottom:0.75rem;
    flex-wrap:wrap;
}
.ar-card-meta-sep { opacity:0.4; }
.ar-card-title {
    font-size:1.0625rem; font-weight:700; color:var(--c-text);
    line-height:1.4; margin-bottom:0.6rem; font-family:var(--font);
    display:-webkit-box; -webkit-line-clamp:2;
    -webkit-box-orient:vertical; overflow:hidden;
}
.ar-card-excerpt {
    font-size:0.875rem; color:var(--c-muted);
    line-height:1.65; font-family:var(--font);
    display:-webkit-box; -webkit-line-clamp:3;
    -webkit-box-orient:vertical; overflow:hidden;
    flex-grow:1; margin-bottom:1.25rem;
}
.ar-card-footer {
    display:flex; align-items:center;
    justify-content:space-between;
    border-top:1px solid var(--c-border); padding-top:1rem;
    font-size:0.8125rem; font-weight:600;
}
.ar-card-date { color:#94A3B8; }
.ar-card-read {
    color:var(--c-accent);
    display:flex; align-items:center; gap:0.35rem;
}
.ar-card-read svg { transition:transform 0.25s; }
.ar-card:hover .ar-card-read svg { transform:translateX(4px); }

/* ════ PAGINATION ════ */
.ar-pagination { margin-top:2.5rem; }
.ar-pagination nav { display:flex; justify-content:center; gap:0.5rem; flex-wrap:wrap; }
.ar-pagination .page-item .page-link {
    display:inline-flex; align-items:center; justify-content:center;
    width:38px; height:38px; border-radius:10px;
    border:1.5px solid var(--c-border);
    background:var(--c-card); color:var(--c-text);
    font-size:0.875rem; font-weight:600; font-family:var(--font);
    transition:all 0.2s; text-decoration:none !important;
}
.ar-pagination .page-item.active .page-link,
.ar-pagination .page-item .page-link:hover {
    background:var(--c-accent); border-color:var(--c-accent); color:#fff;
}

/* ════ SIDEBAR ════ */
.ar-sidebar {
    position:sticky; top:6rem;
    display:flex; flex-direction:column; gap:1.5rem;
}
.ar-sidebar-card {
    background:var(--c-card);
    border:1.5px solid var(--c-border);
    border-radius:16px; padding:1.5rem;
    transition:border-color 0.3s;
}
.ar-sidebar-card:hover { border-color:rgba(30,179,73,0.3); }
.ar-sidebar-title {
    font-size:0.7rem; font-weight:700;
    text-transform:uppercase; letter-spacing:0.15em;
    color:var(--c-accent); margin-bottom:1.25rem;
    padding-bottom:0.75rem; border-bottom:1px solid var(--c-border);
    display:flex; align-items:center; gap:0.5rem;
    font-family:var(--font);
}
.ar-sidebar-title::before {
    content:''; width:4px; height:4px;
    background:var(--c-accent); border-radius:50%;
}
.ar-cat-link {
    display:flex; align-items:center; justify-content:space-between;
    padding:0.625rem 0;
    border-bottom:1px solid var(--c-border);
    text-decoration:none !important; color:var(--c-muted);
    font-size:0.875rem; font-family:var(--font);
    transition:color 0.2s;
}
.ar-cat-link:last-child { border-bottom:none; }
.ar-cat-link:hover { color:var(--c-accent); }
.ar-popular-item {
    display:flex; gap:0.875rem; padding:0.875rem 0;
    border-bottom:1px solid var(--c-border);
    text-decoration:none !important; align-items:flex-start;
}
.ar-popular-item:last-child { border-bottom:none; padding-bottom:0; }
.ar-popular-num {
    font-size:1.5rem; font-weight:900;
    color:var(--c-border); line-height:1; flex-shrink:0;
    font-family:var(--font);
}
.ar-popular-title {
    font-size:0.875rem; font-weight:600;
    color:var(--c-text); line-height:1.4;
    transition:color 0.2s; font-family:var(--font);
}
.ar-popular-item:hover .ar-popular-title { color:var(--c-accent); }
.ar-popular-views { font-size:0.7rem; color:var(--c-muted); margin-top:0.2rem; }

/* ════ EMPTY STATE ════ */
.ar-empty {
    grid-column: 1/-1; text-align:center;
    padding:5rem 1.5rem; color:var(--c-muted);
    font-family:var(--font);
}

/* ════ CTA ════ */
.sv-cta-premium {
    background:var(--c-surface); padding:5rem 1.5rem;
    border-top:1px solid var(--c-border);
    position:relative; overflow:hidden;
}
.sv-cta-glow {
    position:absolute; top:50%; left:50%;
    transform:translate(-50%,-50%);
    width:800px; height:800px;
    background:radial-gradient(circle,rgba(30,179,73,0.06) 0%,transparent 60%);
    pointer-events:none;
}
.sv-cta-inner { max-width:1000px; margin:0 auto; text-align:center; position:relative; z-index:1; }
.sv-cta-h2 {
    font-size:clamp(1.75rem,3vw,2.5rem); font-weight:500;
    line-height:1.2; letter-spacing:-0.02em;
    color:var(--c-text); margin-bottom:1rem; font-family:var(--font);
}
.sv-cta-sub {
    font-size:1rem; color:var(--c-muted); max-width:600px;
    margin:0 auto 2.5rem; line-height:1.6; font-family:var(--font);
}
.sv-cta-btns { display:flex; gap:1rem; justify-content:center; flex-wrap:wrap; }
.btn-primary-v2 {
    display:inline-flex; align-items:center; gap:0.5rem;
    background:var(--c-accent); color:#fff; font-family:var(--font);
    font-size:0.9375rem; font-weight:600; padding:1rem 2rem;
    border-radius:50px; border:none; cursor:pointer;
    text-decoration:none !important; transition:all 0.3s;
    box-shadow:0 8px 20px rgba(30,179,73,0.25);
}
.btn-primary-v2:hover { background:var(--c-accent-hover); transform:translateY(-2px); color:#fff !important; }
.btn-outline-v2 {
    display:inline-flex; align-items:center; gap:0.5rem;
    background:transparent; color:var(--c-text); font-family:var(--font);
    font-size:0.9375rem; font-weight:600; padding:1rem 2rem;
    border-radius:50px; border:1.5px solid var(--c-border);
    cursor:pointer; text-decoration:none !important; transition:all 0.3s;
}
.btn-outline-v2:hover { border-color:var(--c-text); color:var(--c-text) !important; }

/* ════ RESPONSIVE ════ */
@media (max-width:1024px) {
    .ar-section { grid-template-columns:1fr; gap:2rem; }
    .ar-sidebar { position:static; }
}
@media (max-width:640px) {
    .sv-hero-premium { padding:7rem 1rem 4rem; }
    .ar-section { padding:3rem 1rem 4rem; }
    .ar-grid { grid-template-columns:1fr; gap:1.25rem; }
    .sv-cta-premium { padding:4rem 1rem; }
    .sv-cta-btns { flex-direction:column; width:100%; }
    .btn-primary-v2, .btn-outline-v2 { width:100%; justify-content:center; }
}
</style>

{{-- ════ HERO ════ --}}
<section class="sv-hero-premium">
    <div class="sv-hero-inner" data-aos="fade-up">
        <nav class="sv-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route_locale('home') }}">Beranda</a>
            <span class="sv-breadcrumb-sep">/</span>
            <span class="sv-breadcrumb-current">FAQ</span>
        </nav>
        <div class="sv-label">Pusat Bantuan</div>
        <h1 class="sv-title">
            Pertanyaan Umum<br>
            (FAQ)
        </h1>
        <p class="sv-intro">
            Temukan jawaban cepat untuk pertanyaan seputar cara bertransaksi,
            menjadi kreator, lisensi produk, dan informasi layanan lainnya di buyle.id.
        </p>
    </div>
</section>

{{-- ════ MAIN ════ --}}
<div class="ar-section">

    {{-- Faqs Grid --}}
    <div>
        @if($faqs->count())
        <div class="ar-grid">
            @foreach($faqs as $i => $faq)
            <a href="{{ route_locale('faqs.show', $faq->slug) }}"
               class="ar-card"
               data-aos="fade-up"
               data-aos-delay="{{ ($i % 2) * 80 }}">

                <div class="ar-card-img">
                    @if($faq->image)
                        <img src="{{ $faq->image_url }}"
                             alt="{{ $faq->alt_text ?? $faq->title }}"
                             loading="{{ $i < 4 ? 'eager' : 'lazy' }}">
                    @else
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,#E2E8F0,#CBD5E1);display:flex;align-items:center;justify-content:center;flex-direction:column;gap:0.5rem;color:#94A3B8;">
                            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            <span style="font-size:0.7rem;font-weight:600;font-family:var(--font);">FAQ</span>
                        </div>
                    @endif
                    @if($faq->category)
                    <div class="ar-card-badge">{{ $faq->category }}</div>
                    @endif
                </div>

                <div class="ar-card-body">
                    <div class="ar-card-meta">
                        <span>{{ $faq->formatted_date }}</span>
                        <span class="ar-card-meta-sep">·</span>
                        <span>{{ $faq->read_time }} mnt baca</span>
                    </div>
                    <h2 class="ar-card-title">{{ $faq->title }}</h2>
                    <p class="ar-card-excerpt">{{ $faq->excerpt }}</p>
                    <div class="ar-card-footer">
                        <span class="ar-card-date">{{ \Carbon\Carbon::parse($faq->published_at)->format('d M Y') }}</span>
                        <span class="ar-card-read">
                            Baca
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="ar-pagination">{{ $faqs->links() }}</div>

        @else
        <div class="ar-empty" data-aos="fade-up">
            <svg width="52" height="52" fill="none" stroke="#CBD5E1" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 1rem;display:block;">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
            </svg>
            <h3 style="font-size:1.25rem;font-weight:600;margin-bottom:0.5rem;">Belum Ada FAQ</h3>
            <p>FAQ akan segera hadir. Pantau terus!</p>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <aside class="ar-sidebar">
        @if($categories->count())
        <div class="ar-sidebar-card" data-aos="fade-up">
            <div class="ar-sidebar-title">Kategori</div>
            @foreach($categories as $cat)
            <a href="{{ route_locale('faqs') }}?category={{ $cat }}" class="ar-cat-link">
                {{ $cat }}
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            @endforeach
        </div>
        @endif

        @if($popular->count())
        <div class="ar-sidebar-card" data-aos="fade-up" data-aos-delay="100">
            <div class="ar-sidebar-title">FAQ Populer</div>
            @foreach($popular as $i => $p)
            <a href="{{ route_locale('faqs.show', $p->slug) }}" class="ar-popular-item">
                <span class="ar-popular-num">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                <div>
                    <div class="ar-popular-title">{{ Str::limit($p->title, 60) }}</div>
                    <div class="ar-popular-views">{{ number_format($p->views) }} views</div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        {{-- CTA Mini --}}
        <div class="ar-sidebar-card" style="background:var(--c-accent);border-color:var(--c-accent);" data-aos="fade-up" data-aos-delay="200">
            <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:rgba(255,255,255,0.8);margin-bottom:0.75rem;font-family:var(--font);">Belanja Sekarang</div>
            <h3 style="font-size:1.125rem;font-weight:700;color:#fff;margin-bottom:0.75rem;line-height:1.3;font-family:var(--font);">Cari Produk Digital?</h3>
            <p style="font-size:0.8125rem;color:rgba(255,255,255,0.85);line-height:1.6;margin-bottom:1.25rem;font-family:var(--font);">Temukan ribuan produk digital dan layanan jasa profesional dari kreator terbaik di buyle.id.</p>
            <a href="{{ route_locale('products') }}" style="display:inline-flex;align-items:center;gap:0.5rem;background:#fff;color:var(--c-accent);font-size:0.8125rem;font-weight:700;padding:0.75rem 1.5rem;border-radius:50px;text-decoration:none !important;font-family:var(--font);transition:opacity 0.2s;" onmouseover="this.style.opacity=0.9" onmouseout="this.style.opacity=1">
                Lihat Produk
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
    </aside>
</div>



@endsection


