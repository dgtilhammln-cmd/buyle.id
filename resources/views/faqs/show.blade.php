@extends('layouts.app')
@section('content')

<style>
/* ═══════════════════════════════════════
   DESIGN TOKENS — seragam dengan /about /products /gallery /faqs
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
*, *::before, *::after { box-sizing: border-box; }
body { background: var(--c-bg); font-family: var(--font); color: var(--c-text); -webkit-font-smoothing: antialiased; }
img { display: block; }
a { text-decoration: none; color: inherit; }

/* ════ HERO — identik dengan semua page lain ════ */
.sv-hero-premium {
    position: relative;
    padding: 9rem 1.5rem 4rem;
    background: var(--c-surface);
    overflow: hidden;
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
    max-width: 860px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

/* Breadcrumb — 100% identik */
.sv-breadcrumb {
    display:flex; align-items:center; gap:0.5rem;
    font-size:0.75rem; font-weight:500;
    color:var(--c-muted); margin-bottom:2rem; font-family:var(--font);
    flex-wrap: wrap;
}
.sv-breadcrumb a { color:var(--c-muted); text-decoration:none; transition:color 0.2s; }
.sv-breadcrumb a:hover { color:var(--c-accent); }
.sv-breadcrumb-sep { font-size:0.6rem; color:var(--c-muted); opacity:0.5; }
.sv-breadcrumb-current { color:var(--c-text); font-weight:600; }

/* Category badge */
.ar-cat-badge {
    display:inline-flex; align-items:center; gap:0.375rem;
    font-size:0.65rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase;
    background:rgba(30,179,73,0.08); color:var(--c-accent);
    padding:0.3rem 0.875rem; border-radius:50px;
    border:1px solid rgba(30,179,73,0.15);
    font-family:var(--font); margin-bottom:1.25rem;
}

/* Meta row */
.ar-meta-row {
    display:flex; align-items:center; gap:0.625rem;
    font-size:0.8rem; color:var(--c-muted);
    font-family:var(--font); margin-bottom:1.5rem;
    flex-wrap:wrap;
}
.ar-meta-sep { opacity:0.4; }

/* Hero title */
.ar-hero-title {
    font-size:clamp(1.75rem,4vw,2.75rem);
    font-weight:700; line-height:1.25;
    letter-spacing:-0.02em; color:var(--c-text);
    margin-bottom:1.25rem; font-family:var(--font);
}

/* Hero excerpt */
.ar-hero-excerpt {
    font-size:1rem; font-weight:400;
    color:var(--c-muted); line-height:1.75;
    max-width:720px;
}

/* Author row */
.ar-author-row {
    display:flex; align-items:center; gap:0.875rem;
    margin-top:2rem; padding-top:2rem;
    border-top:1px solid var(--c-border);
}
.ar-author-avatar {
    width:42px; height:42px;
    background:var(--c-accent);
    display:flex; align-items:center; justify-content:center;
    border-radius:50%; font-weight:800; color:#fff;
    font-size:1rem; flex-shrink:0; font-family:var(--font);
}
.ar-author-name {
    font-size:0.875rem; font-weight:700; color:var(--c-text);
    font-family:var(--font); margin-bottom:0.125rem;
}
.ar-author-company {
    font-size:0.75rem; color:var(--c-muted); font-family:var(--font);
}

/* ════ FEATURED IMAGE ════ */
.ar-featured-img {
    max-width: 960px;
    margin: 0 auto;
    padding: 0 1.5rem;
}
.ar-featured-img-wrap {
    width: 100%;
    aspect-ratio: 16 / 9;  /* Wajib landscape 1280x720 */
    overflow: hidden;
    border-radius: 16px;
    border: 1px solid var(--c-border);
    margin-top: 2.5rem;
    background: var(--c-surface);
}
.ar-featured-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;       /* Potong otomatis, isi penuh */
    object-position: center; /* Fokus ke tengah gambar */
    display: block;
}

/* ════ BODY LAYOUT ════ */
.ar-body-section {
    padding: 3.5rem 1.5rem 5rem;
    max-width: 860px;
    margin: 0 auto;
}

/* ════ TOC — elegant like FAQ ════ */
.ar-toc {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: 16px;
    padding: 0;
    margin-bottom: 2.5rem;
    overflow: hidden;
    transition: border-color 0.3s;
}
.ar-toc:hover { border-color: rgba(30,179,73,0.3); }
.ar-toc-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.125rem 1.5rem;
    border-bottom: 1px solid var(--c-border);
    cursor: pointer; user-select: none;
}
.ar-toc-title {
    font-size:0.7rem; font-weight:700; text-transform:uppercase;
    letter-spacing:0.15em; color:var(--c-accent);
    display:flex; align-items:center; gap:0.5rem;
    font-family:var(--font); margin:0;
}
.ar-toc-title::before {
    content:''; width:4px; height:4px; background:var(--c-accent); border-radius:50%;
}
.ar-toc-body { padding: 1.25rem 1.5rem; }
.ar-toc ol {
    margin:0; padding-left:1.25rem;
    display:flex; flex-direction:column; gap:0.5rem;
}
.ar-toc li { list-style:decimal; }
.ar-toc a {
    font-size:0.875rem; font-weight:500; color:var(--c-muted);
    text-decoration:none; transition:color 0.2s; font-family:var(--font);
    display:flex; align-items:center; gap:0.375rem;
}
.ar-toc a:hover { color:var(--c-accent); }

/* ════ ARTICLE CONTENT ════ */
.ar-content {
    font-size:0.9625rem; line-height:1.9;
    color:#334155; font-family:var(--font);
}
.ar-content h2 {
    font-size:1.375rem; font-weight:700; color:var(--c-text);
    margin:2.75rem 0 1rem; letter-spacing:-0.02em;
    padding-top:0.5rem;
}
.ar-content h3 {
    font-size:1.1rem; font-weight:700; color:var(--c-text);
    margin:2rem 0 0.875rem; letter-spacing:-0.01em;
}
.ar-content p { margin:0 0 1.375rem; }
.ar-content ul, .ar-content ol {
    margin:0 0 1.375rem; padding-left:1.5rem;
}
.ar-content li { margin-bottom:0.4rem; }
.ar-content a { color:var(--c-accent); text-decoration:underline; text-underline-offset:3px; }
.ar-content blockquote {
    border-left:3px solid var(--c-accent);
    padding:1rem 1.375rem;
    background:rgba(30,179,73,0.04);
    margin:2rem 0; border-radius:0 12px 12px 0;
    color:var(--c-muted); font-style:italic;
}
.ar-content code {
    background:rgba(30,179,73,0.06);
    border:1px solid rgba(30,179,73,0.15);
    padding:0.125rem 0.4rem; border-radius:4px;
    font-size:0.85em; color:var(--c-accent);
    font-family:'Fira Code', monospace;
}
.ar-content img {
    max-width:100%; border-radius:12px;
    margin:1.75rem 0; border:1px solid var(--c-border);
}
.ar-content strong { color:var(--c-text); font-weight:700; }

/* ════ FAQ ════ */
.ar-faq { margin-top:3rem; padding-top:2.5rem; border-top:1px solid var(--c-border); }
.ar-faq-title {
    font-size:1.125rem; font-weight:700; color:var(--c-text);
    margin:0 0 1.5rem; display:flex; align-items:center; gap:0.625rem;
    letter-spacing:-0.015em; font-family:var(--font);
}
.ar-faq-list { display:flex; flex-direction:column; gap:0.625rem; }
.ar-faq-item {
    background:var(--c-surface);
    border:1.5px solid var(--c-border);
    border-radius:12px; overflow:hidden;
    transition:border-color 0.3s;
}
.ar-faq-item:hover { border-color:rgba(30,179,73,0.3); }
.ar-faq-item[open] { border-color:var(--c-accent); }
.ar-faq-item summary {
    padding:1rem 1.25rem;
    font-size:0.9rem; font-weight:600; color:var(--c-text);
    cursor:pointer; list-style:none;
    display:flex; justify-content:space-between; align-items:center;
    gap:1rem; user-select:none; font-family:var(--font);
}
.ar-faq-item summary::-webkit-details-marker { display:none; }
.ar-faq-chevron { flex-shrink:0; transition:transform 0.3s var(--ease); color:var(--c-accent); }
.ar-faq-item[open] .ar-faq-chevron { transform:rotate(180deg); }
.ar-faq-answer {
    padding:0.875rem 1.25rem 1.25rem;
    border-top:1px solid var(--c-border);
    font-size:0.875rem; color:var(--c-muted);
    line-height:1.75; font-family:var(--font);
}

/* ════ CTA INLINE ════ */
.ar-cta-box {
    margin-top:3rem;
    background:linear-gradient(135deg,#1eb349,#16a34a);
    padding:2.5rem 2rem; border-radius:20px;
    text-align:center; position:relative; overflow:hidden;
}
.ar-cta-box::before {
    content:''; position:absolute; top:-60px; right:-60px;
    width:200px; height:200px;
    background:radial-gradient(circle,rgba(255,255,255,0.1) 0%,transparent 70%);
    pointer-events:none;
}
.ar-cta-box h3 {
    font-size:1.25rem; font-weight:700; color:#fff;
    margin-bottom:0.75rem; font-family:var(--font);
    position:relative; z-index:1;
}
.ar-cta-box p {
    font-size:0.9rem; color:rgba(255,255,255,0.85);
    margin:0 0 1.5rem; line-height:1.6;
    position:relative; z-index:1; font-family:var(--font);
}
.ar-cta-btn {
    display:inline-flex; align-items:center; gap:0.5rem;
    background:#fff; color:var(--c-accent);
    font-size:0.9rem; font-weight:700; padding:0.875rem 2rem;
    border-radius:50px; border:none; cursor:pointer;
    text-decoration:none !important; transition:all 0.3s;
    font-family:var(--font); position:relative; z-index:1;
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
}
.ar-cta-btn:hover { transform:translateY(-2px); box-shadow:0 12px 25px rgba(0,0,0,0.2); }

/* ════ TAGS & SHARE ════ */
.ar-tags {
    margin-top:2.5rem; padding-top:2rem;
    border-top:1px solid var(--c-border);
    display:flex; gap:0.5rem; flex-wrap:wrap; align-items:center;
}
.ar-tags-label {
    font-size:0.7rem; font-weight:700; color:var(--c-muted);
    text-transform:uppercase; letter-spacing:0.14em; font-family:var(--font);
}
.ar-tag {
    font-size:0.75rem; font-weight:500;
    background:var(--c-surface); border:1.5px solid var(--c-border);
    color:var(--c-muted); padding:0.3rem 0.875rem; border-radius:50px;
    transition:all 0.2s; font-family:var(--font);
}
.ar-tag:hover { border-color:var(--c-accent); color:var(--c-accent); }
.ar-share {
    margin-top:2rem; padding-top:2rem;
    border-top:1px solid var(--c-border);
    display:flex; align-items:center; gap:0.875rem; flex-wrap:wrap;
}
.ar-share-label {
    font-size:0.75rem; font-weight:700; color:var(--c-muted);
    text-transform:uppercase; letter-spacing:0.1em; font-family:var(--font);
}
.ar-share-btn {
    display:inline-flex; align-items:center; gap:0.4rem;
    font-size:0.8rem; font-weight:600;
    border:1.5px solid; padding:0.45rem 0.875rem;
    border-radius:50px; cursor:pointer; text-decoration:none !important;
    transition:all 0.25s; font-family:var(--font); white-space:nowrap;
}
.ar-share-btn:hover { opacity:0.8; transform:translateY(-1px); }

/* ════ RELATED faqs.════ */
.ar-related {
    padding:4.5rem 1.5rem;
    background:var(--c-surface);
    border-top:1px solid var(--c-border);
}
.ar-related-inner { max-width:1200px; margin:0 auto; }
.ar-related-header {
    display:flex; align-items:flex-end; justify-content:space-between;
    flex-wrap:wrap; gap:1rem; margin-bottom:2.5rem;
}
.ar-related-label {
    font-size:0.75rem; font-weight:700; letter-spacing:0.15em;
    text-transform:uppercase; color:var(--c-muted);
    display:flex; align-items:center; gap:0.5rem; margin-bottom:0.75rem;
    font-family:var(--font);
}
.ar-related-label::before {
    content:''; width:4px; height:4px; background:var(--c-accent); border-radius:50%;
}
.ar-related-title {
    font-size:clamp(1.5rem,2.5vw,2.25rem); font-weight:500;
    color:var(--c-text); letter-spacing:-0.025em; font-family:var(--font);
}
.ar-related-link {
    display:inline-flex; align-items:center; gap:0.375rem;
    font-size:0.875rem; font-weight:600; color:var(--c-muted);
    border:1.5px solid var(--c-border); border-radius:50px;
    padding:0.625rem 1.25rem; transition:all 0.25s;
    font-family:var(--font); white-space:nowrap;
}
.ar-related-link:hover { border-color:var(--c-text); color:var(--c-text); }
.ar-related-grid {
    display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem;
}
.ar-related-card {
    background:var(--c-card); border:1.5px solid var(--c-border);
    border-radius:20px; overflow:hidden; display:block;
    text-decoration:none !important; transition:all 0.35s var(--ease);
}
.ar-related-card:hover {
    border-color:var(--c-accent);
    transform:translateY(-5px);
    box-shadow:0 16px 40px rgba(30,179,73,0.09);
}
.ar-related-card-img { aspect-ratio:16/10; overflow:hidden; background:var(--c-surface); }
.ar-related-card-img img {
    width:100%; height:100%; object-fit:cover;
    transition:transform 0.55s var(--ease);
}
.ar-related-card:hover .ar-related-card-img img { transform:scale(1.07); }
.ar-related-card-body { padding:1.25rem; }
.ar-related-cat {
    font-size:0.62rem; font-weight:700; letter-spacing:0.1em;
    text-transform:uppercase; color:var(--c-accent);
    margin-bottom:0.5rem; font-family:var(--font);
}
.ar-related-card-title {
    font-size:0.9375rem; font-weight:700; color:var(--c-text);
    line-height:1.45; font-family:var(--font);
    display:-webkit-box; -webkit-line-clamp:2;
    -webkit-box-orient:vertical; overflow:hidden;
}

/* ════ RESPONSIVE ════ */
@media (max-width:860px) {
    .ar-related-grid { grid-template-columns:repeat(2,1fr); }
}
@media (max-width:640px) {
    .sv-hero-premium { padding:7rem 1rem 3rem; }
    .ar-body-section { padding:2.5rem 1rem 4rem; }
    .ar-related { padding:3rem 1rem; }
    .ar-related-grid { grid-template-columns:1fr; }
    .ar-related-header { flex-direction:column; align-items:flex-start; }
    .ar-featured-img { padding:0 1rem; }
    .ar-featured-img-wrap { border-radius:12px; margin-top:1.5rem; }
    .ar-cta-box { padding:2rem 1.25rem; }
}
</style>

{{-- ════ HERO ════ --}}
<section class="sv-hero-premium">
    <div class="sv-hero-inner">

        {{-- Breadcrumb --}}
        <nav class="sv-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route_locale('home') }}">Beranda</a>
            <span class="sv-breadcrumb-sep">/</span>
            <a href="{{ $seo['canonical'] ?? route_locale('faqs') }}">FAQ</a>
            <span class="sv-breadcrumb-sep">/</span>
            <span class="sv-breadcrumb-current">{{ Str::limit($trans?->title ?? $faq->slug, 50) }}</span>
        </nav>

        @if($faq->category)
        <div class="ar-cat-badge">{{ $faq->category }}</div>
        @endif

        <div class="ar-meta-row">
            <span>{{ $faq->formatted_date }}</span>
            <span class="ar-meta-sep">·</span>
            <span>{{ $readTime ?? $faq->read_time }} menit baca</span>
            <span class="ar-meta-sep">·</span>
            <span>{{ number_format($faq->views) }} views</span>
        </div>

        <h1 class="ar-hero-title">{{ $trans?->title ?? $faq->slug }}</h1>
        <p class="ar-hero-excerpt">{{ $trans?->excerpt }}</p>


    </div>
</section>

{{-- ════ FEATURED IMAGE — Landscape 16:9 (1280×720) ════ --}}
@if($faq->image)
<div class="ar-featured-img">
    <div class="ar-featured-img-wrap">
        <img src="{{ asset('storage/' . $faq->image) }}"
             alt="{{ $trans?->thumbnail_alt ?? ($faq->alt_text ?? ($trans?->title ?? $faq->title)) }}"
             loading="eager">
    </div>
</div>
@endif

{{-- ════ ARTICLE BODY ════ --}}
<section class="ar-body-section">

    {{-- TOC — elegant premium style ════ --}}
    @if($faq->show_toc && count($faq->toc) > 0)
    <details class="ar-toc" open>
        <summary class="ar-toc-header" style="list-style:none; outline:none;">
            <div class="ar-toc-title">
                Daftar Isi
            </div>
            <svg style="color:var(--c-accent); flex-shrink:0; transition:transform 0.3s;" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </summary>
        <div class="ar-toc-body">
            <ol>
                @foreach($faq->toc as $item)
                <li style="padding-left:{{ ($item['level'] - 2) * 0.875 }}rem;">
                    <a href="#{{ $item['id'] }}">{{ $item['text'] }}</a>
                </li>
                @endforeach
            </ol>
        </div>
    </details>
    @endif

    {{-- Faq Content --}}
    <faq class="ar-content" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
        @if(!empty($trans?->featured_snippet))
        <div class="featured-snippet-box" style="padding:1.5rem; background:#F8FAFC; border-left:4px solid var(--c-accent); border-radius:8px; margin-bottom:2rem;">
            <strong style="color:var(--c-accent); font-size:0.85rem; text-transform:uppercase; letter-spacing:0.05em; display:block; margin-bottom:0.5rem;">{{ $locale === 'ar' ? 'ملخص سريع' : ($locale === 'en' ? 'Quick Summary' : ($locale === 'ko' ? '요약' : 'Ringkasan Cepat')) }}</strong>
            <span style="font-size:1.05rem; line-height:1.6; color:#0F172A; font-weight:500;">{{ $trans->featured_snippet }}</span>
        </div>
        @endif
        @php
            // Inject TOC IDs into headings
            $renderedContent = preg_replace_callback('/<h([23])([^>]*)>(.*?)<\/h[23]>/i', function($m) {
                $text = strip_tags($m[3]);
                $id   = \Illuminate\Support\Str::slug($text);
                if (str_contains($m[2], 'id=')) return $m[0];
                return "<h{$m[1]} id=\"{$id}\"{$m[2]}>{$m[3]}</h{$m[1]}>";
            }, $trans?->content ?? '');
        @endphp
        {!! $renderedContent !!}
    </faq>


    {{-- Internal Link CTA to Products --}}
    <div style="position:relative; overflow:hidden; background:linear-gradient(135deg, #ffffff 0%, #F8FAFC 100%); border:1px solid #E2E8F0; padding:2rem 2.5rem; margin:2.5rem 0; border-radius:16px; box-shadow:0 12px 32px rgba(15,23,42,0.04); display:flex; flex-direction:column; gap:1.25rem;">
        <div style="position:absolute; top:-50px; right:-50px; width:150px; height:150px; background:radial-gradient(circle, rgba(30,179,73,0.1) 0%, transparent 70%); border-radius:50%; pointer-events:none;"></div>
        <div style="position:absolute; bottom:-30px; right:10%; width:100px; height:100px; background:radial-gradient(circle, rgba(30,179,73,0.05) 0%, transparent 70%); border-radius:50%; pointer-events:none;"></div>
        
        <div style="display:flex; align-items:center; gap:1rem;">
            <div style="width:42px; height:42px; border-radius:12px; background:rgba(30,179,73,0.08); color:#1eb349; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div style="margin:0; font-size:1.15rem; color:#0F172A; font-weight:700; letter-spacing:-0.01em;">Eksplorasi Katalog Produk Digital Kami</div>
        </div>
        
        <p style="margin:0; color:#475569; font-size:0.95rem; line-height:1.65; max-width:95%;">Temukan ribuan karya kreator dan produk digital terbaik di buyle.id. Dukung pembuat karya dan tingkatkan produktivitas Anda sekarang juga.</p>
        
        <a href="{{ route_locale('products') }}" style="align-self:flex-start; margin-top:0.25rem; display:inline-flex; align-items:center; gap:0.5rem; background:#1eb349; color:#fff; padding:0.75rem 1.75rem; border-radius:50px; font-weight:600; text-decoration:none; font-size:0.925rem; transition:all 0.3s; box-shadow:0 4px 12px rgba(30,179,73,0.2);" onmouseover="this.style.background='#16a34a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(30,179,73,0.3)';" onmouseout="this.style.background='#1eb349'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(30,179,73,0.2)';">
            Lihat Katalog Produk
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- FAQ --}}
    @php $faqFaqs = $trans?->faqs ?? []; @endphp
    @if(!empty($faqFaqs))
    <div class="ar-faq">
        <h2 class="ar-faq-title">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--c-accent);">
                <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01"/>
            </svg>
            Pertanyaan Umum (FAQ)
        </h2>
        <div class="ar-faq-list">
            @foreach($faqFaqs as $faq)
            @if(!empty($faq['q']))
            <details class="ar-faq-item">
                <summary>
                    {{ $faq['q'] }}
                    <svg class="ar-faq-chevron" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </summary>
                <div class="ar-faq-answer">{{ $faq['a'] ?? '' }}</div>
            </details>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- CTA Box --}}
    @php $faqCta = $trans?->cta_button ?? null; @endphp
    @if($faqCta && !empty($faqCta['text']))
    @php
        $cta = $faqCta;
        $ctaWa = \App\Models\WaSetting::primary();
    @endphp
    <div class="ar-cta-box">
        <h3>Cari Produk Digital Berkualitas?</h3>
        <p>Tingkatkan produktivitas dan kreativitas Anda dengan produk digital unggulan dari para kreator terbaik di buyle.id.</p>
        <a href="{{ route_locale('products') }}" class="ar-cta-btn" style="display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            Lihat Semua Produk
        </a>
    </div>
    @endif

    {{-- Tags --}}
    @if($faq->tags)
    <div class="ar-tags">
        <span class="ar-tags-label">Tags:</span>
        @foreach($faq->tags as $tag)
        <span class="ar-tag">{{ $tag }}</span>
        @endforeach
    </div>
    @endif

    {{-- Share — all social media --}}
    <div class="ar-share">
        <span class="ar-share-label">Bagikan:</span>

        {{-- Share --}}
    @php
        $shareUrl   = urlencode($seo['canonical'] ?? url()->current());
        $shareTitle = urlencode($trans?->title ?? $faq->slug);
    @endphp
        <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener" class="ar-share-btn" style="color:#25D366;background:rgba(37,211,102,0.08);border-color:rgba(37,211,102,0.25);">
            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            WA
        </a>

        {{-- Facebook --}}
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="ar-share-btn" style="color:#1877F2;background:rgba(24,119,242,0.08);border-color:rgba(24,119,242,0.25);">
            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
            FB
        </a>

        {{-- Twitter / X --}}
        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" class="ar-share-btn" style="color:#000;background:rgba(0,0,0,0.05);border-color:rgba(0,0,0,0.12);">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.259 5.632zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            X
        </a>

        {{-- LinkedIn --}}
        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" class="ar-share-btn" style="color:#0A66C2;background:rgba(10,102,194,0.08);border-color:rgba(10,102,194,0.25);">
            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
            LinkedIn
        </a>

        {{-- Telegram --}}
        <a href="https://t.me/share/url?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener" class="ar-share-btn" style="color:#229ED9;background:rgba(34,158,217,0.08);border-color:rgba(34,158,217,0.25);">
            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
            Telegram
        </a>

        {{-- Copy Link --}}
        <button id="copyBtn" class="ar-share-btn" style="color:var(--c-muted);background:var(--c-surface);border-color:var(--c-border); cursor:pointer;"
                onclick="navigator.clipboard.writeText(window.location.href);this.innerHTML='<svg width=&quot;14&quot; height=&quot;14&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;2&quot; viewBox=&quot;0 0 24 24&quot;><polyline points=&quot;20 6 9 17 4 12&quot;/></svg> Tersalin!';setTimeout(()=>this.innerHTML='<svg width=&quot;14&quot; height=&quot;14&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; stroke-width=&quot;2&quot; viewBox=&quot;0 0 24 24&quot;><path d=&quot;M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71&quot;/><path d=&quot;M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71&quot;/></svg> Salin Link',2000)">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
            Salin Link
        </button>
    </div>

</section>

{{-- ════ KEUNGGULAN buyle.id ════ --}}
<style>
.ar-why { background:#fff; padding:5rem 0; }
.ar-why-inner { max-width:1200px; margin:0 auto; padding:0 1.5rem; }
.ar-why-header { text-align:center; margin-bottom:3rem; }
.ar-why-label { font-size:0.72rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:#1eb349; display:flex; align-items:center; justify-content:center; gap:0.5rem; margin-bottom:0.75rem; }
.ar-why-title { font-size:clamp(1.75rem,3.5vw,2.75rem); font-weight:600; color:#0F172A; line-height:1.2; letter-spacing:-0.025em; }
.ar-why-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; }
.ar-why-card { background:#F8FAFC; border:1.5px solid #E2E8F0; border-radius:20px; padding:1.75rem; display:flex; flex-direction:column; gap:0.75rem; transition:all 0.3s; }
.ar-why-card:hover { border-color:#1eb349; transform:translateY(-5px); box-shadow:0 16px 40px rgba(30,179,73,0.09); }
.ar-why-icon { width:46px; height:46px; background:#f0fdf4; border-radius:13px; display:flex; align-items:center; justify-content:center; color:#1eb349; }
.ar-why-card-title { font-size:0.9375rem; font-weight:700; color:#0F172A; }
.ar-why-card-desc { font-size:0.8125rem; color:#64748B; line-height:1.65; }
.ar-why-cta { text-align:center; margin-top:2.5rem; }
@media(max-width:768px) { .ar-why-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:480px) { .ar-why-grid { grid-template-columns:1fr; } }
</style>
<section class="ar-why">
    <div class="ar-why-inner">
        <div class="ar-why-header">
            <div class="ar-why-label">Mengapa buyle.id</div>
            <h2 class="ar-why-title">Platform Produk Digital<br>Terpercaya di Indonesia</h2>
        </div>
        <div class="ar-why-grid">
            <div class="ar-why-card" data-aos="fade-up">
                <div class="ar-why-icon"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <div class="ar-why-card-title">Produk Terkurasi</div>
                <div class="ar-why-card-desc">Setiap produk digital yang dipublikasikan telah dipastikan orisinalitas dan standar kualitas terbaiknya.</div>
            </div>
            <div class="ar-why-card" data-aos="fade-up" data-aos-delay="80">
                <div class="ar-why-icon"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
                <div class="ar-why-card-title">Akses Instan</div>
                <div class="ar-why-card-desc">Akses dan unduh produk secara instan dan otomatis tepat setelah pembayaran Anda berhasil.</div>
            </div>
            <div class="ar-why-card" data-aos="fade-up" data-aos-delay="160">
                <div class="ar-why-icon"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></div>
                <div class="ar-why-card-title">Karya Kreator Pilihan</div>
                <div class="ar-why-card-desc">Dukung para kreator lokal dengan karya terbaik. Harga transparan dan langsung mendanai pembuatnya.</div>
            </div>
            <div class="ar-why-card" data-aos="fade-up" data-aos-delay="240">
                <div class="ar-why-icon"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div>
                <div class="ar-why-card-title">Layanan CS Responsif</div>
                <div class="ar-why-card-desc">Tim customer service kami siap membantu via WhatsApp, cepat dan ramah setiap hari.</div>
            </div>
        </div>
        <div class="ar-why-cta">
            <a href="{{ route_locale('products') }}" style="display:inline-flex;align-items:center;gap:0.5rem;background:#1eb349;color:#fff;padding:0.8rem 2rem;border-radius:50px;font-weight:700;font-size:0.9rem;text-decoration:none;box-shadow:0 4px 12px rgba(30,179,73,0.2);transition:all 0.3s;" onmouseover="this.style.background='#16a34a';this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#1eb349';this.style.transform='translateY(0)'">
                Belanja Sekarang
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ════ RELATED faqs.════ --}}
@if($related->count())
<section class="ar-related">
    <div class="ar-related-inner">
        <div class="ar-related-header">
            <div>
                <div class="ar-related-label">Baca Juga</div>
                <h2 class="ar-related-title">FAQ Terkait</h2>
            </div>
            <a href="{{ route_locale('faqs') }}" class="ar-related-link">
                Semua FAQ
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>

        <div class="ar-related-grid">
            @foreach($related as $r)
            <a href="{{ route_locale('faqs.show', $r->slug) }}" class="ar-related-card" data-aos="fade-up">
                <div class="ar-related-card-img">
                    @if($r->image)
                        <img src="{{ asset('storage/' . $r->image) }}" alt="{{ $r->title }}" loading="lazy">
                    @else
                        <div style="width:100%;height:100%;min-height:160px;background:linear-gradient(135deg,#E2E8F0,#CBD5E1);display:flex;align-items:center;justify-content:center;">
                            <svg width="28" height="28" fill="none" stroke="#94A3B8" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        </div>
                    @endif
                </div>
                <div class="ar-related-card-body">
                    @if($r->category)
                    <div class="ar-related-cat">{{ $r->category }}</div>
                    @endif
                    <h3 class="ar-related-card-title">{{ $r->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection


