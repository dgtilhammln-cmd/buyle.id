@extends('layouts.app')

@php
    $bio = $author->currentTranslation()?->bio;
    $idBio = $author->translations->firstWhere('locale', 'id')?->bio;
    $metaDesc = Str::limit(strip_tags($bio ?? $idBio ?? 'Author at buyle.id'), 150);
@endphp

@section('seo_title', $author->name . ' - Author Profile | buyle.id')
@section('seo_desc', $metaDesc)
@section('og_image', $author->photo ? asset('storage/'.$author->photo) : asset('img/logo.png'))

{{-- Structured Data (AEO/SEO) --}}
@section('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "ProfilePage",
  "mainEntity": {
    "@@type": "Person",
    "name": "{{ $author->name }}",
    "description": "{{ $metaDesc }}",
    "image": "{{ $author->photo ? asset('storage/'.$author->photo) : asset('img/logo.png') }}"
    @if(!empty($author->social_links))
    ,"sameAs": [
        @foreach(array_filter($author->social_links) as $url)
            "{{ $url }}"{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
    @endif
  }
}
</script>
@endsection

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
    --c-accent:  #0EA5E9;
    --c-accent-hover: #0284C7;
    --font:      'Montserrat', sans-serif;
    --ease:      cubic-bezier(0.22, 1, 0.36, 1);
}
body { background: var(--c-bg); font-family: var(--font); color: var(--c-text); }

/* ════ HERO (AUTHOR PROFILE) ════ */
.sv-hero-premium {
    position: relative; padding: 10rem 1.5rem 6rem;
    background: var(--c-surface); overflow: hidden;
    border-bottom: 1px solid var(--c-border);
}
.sv-hero-premium::before {
    content:''; position:absolute; top:-150px; right:-100px;
    width:500px; height:500px; border-radius:50%;
    background:radial-gradient(circle,rgba(14,165,233,0.06) 0%,transparent 70%);
    pointer-events:none;
}
.sv-hero-premium::after {
    content:''; position:absolute; bottom:-150px; left:-100px;
    width:600px; height:600px; border-radius:50%;
    background:radial-gradient(circle,rgba(14,165,233,0.04) 0%,transparent 70%);
    pointer-events:none;
}
.sv-hero-inner {
    max-width:800px; margin:0 auto;
    position:relative; z-index:2; text-align:center;
}

/* Breadcrumb */
.sv-breadcrumb {
    display:flex; align-items:center; justify-content:center;
    gap:0.5rem; font-size:0.75rem; font-weight:500;
    color:var(--c-muted); margin-bottom:3rem; font-family:var(--font);
}
.sv-breadcrumb a { color:var(--c-muted); text-decoration:none; transition:color 0.2s; }
.sv-breadcrumb a:hover { color:var(--c-accent); }
.sv-breadcrumb-sep { font-size:0.6rem; color:var(--c-muted); opacity:0.5; }
.sv-breadcrumb-current { color:var(--c-text); font-weight:600; }

/* Profile Elements */
.author-photo-lg {
    width: 140px; height: 140px; border-radius: 50%;
    object-fit: cover; border: 4px solid #fff;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    margin: 0 auto 1.5rem;
    background: #E2E8F0;
}
.sv-label {
    display:inline-flex; align-items:center; justify-content:center;
    gap:0.5rem; font-size:0.75rem; font-weight:700;
    letter-spacing:0.15em; text-transform:uppercase;
    color:var(--c-accent); margin-bottom:1rem; font-family:var(--font);
}
.sv-title {
    font-size:clamp(2.5rem,4vw,3.5rem); font-weight:700; color:var(--c-text);
    line-height:1.15; letter-spacing:-0.03em; font-family:var(--font);
    margin-bottom:1.5rem;
}
.sv-intro {
    font-size:1.1rem; font-weight:400; color:var(--c-muted);
    line-height:1.75; font-family:var(--font);
    margin:0 auto 2rem; max-width:650px;
}
.author-socials {
    display: flex; gap: 1rem; justify-content: center; align-items: center;
}
.author-social-link {
    display: flex; align-items: center; justify-content: center;
    width: 44px; height: 44px; border-radius: 50%;
    background: #fff; border: 1.5px solid var(--c-border);
    color: var(--c-muted); transition: all 0.3s var(--ease);
}
.author-social-link:hover {
    background: var(--c-accent); color: #fff; border-color: var(--c-accent);
    transform: translateY(-3px); box-shadow: 0 10px 20px rgba(14,165,233,0.2);
}

/* ════ ARTICLES LISTING ════ */
.ar-section {
    max-width:1200px; margin:0 auto; padding:4rem 1.5rem 6rem;
}
.section-header {
    display: flex; justify-content: space-between; align-items: flex-end;
    margin-bottom: 2.5rem; border-bottom: 2px solid var(--c-surface); padding-bottom: 1.5rem;
}
.section-title {
    font-size: 1.75rem; font-weight: 700; color: var(--c-text); margin:0;
}
.section-count {
    font-size: 0.9rem; font-weight: 600; color: var(--c-muted);
    background: var(--c-surface); padding: 0.4rem 1rem; border-radius: 20px;
}

/* ════ ARTICLE CARDS ════ */
.ar-grid {
    display:grid; grid-template-columns:repeat(3,1fr); gap:2rem;
}
.ar-card {
    background:var(--c-card); border:1.5px solid var(--c-border);
    border-radius:20px; overflow:hidden; display:flex; flex-direction:column;
    text-decoration:none !important; transition:all 0.35s var(--ease);
}
.ar-card:hover {
    transform:translateY(-5px); border-color:var(--c-accent);
    box-shadow:0 20px 40px rgba(0,0,0,0.06);
}
.ar-card-img {
    width:100%; aspect-ratio:16/9; position:relative; overflow:hidden;
}
.ar-card-img img {
    width:100%; height:100%; object-fit:cover; transition:transform 0.6s var(--ease);
}
.ar-card:hover .ar-card-img img { transform:scale(1.05); }
.ar-card-body { padding:1.75rem; display:flex; flex-direction:column; flex:1; }
.ar-card-meta {
    display:flex; align-items:center; gap:1rem; font-size:0.75rem; font-weight:600;
    color:var(--c-muted); margin-bottom:1rem; text-transform:uppercase; letter-spacing:0.05em;
}
.ar-card-cat { color:var(--c-accent); }
.ar-card-title {
    font-size:1.25rem; font-weight:700; color:var(--c-text);
    line-height:1.4; margin:0 0 1rem; transition:color 0.2s;
    display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}
.ar-card:hover .ar-card-title { color:var(--c-accent); }
.ar-card-desc {
    font-size:0.95rem; color:var(--c-muted); line-height:1.6;
    margin:0 0 1.5rem; flex:1; display:-webkit-box;
    -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden;
}
.ar-card-read {
    display:flex; align-items:center; gap:0.5rem; font-size:0.8rem;
    font-weight:700; color:var(--c-text); text-transform:uppercase;
    letter-spacing:0.05em; transition:color 0.2s; margin-top:auto;
}
.ar-card:hover .ar-card-read { color:var(--c-accent); }

/* Pagination */
.sv-pagination { display:flex; justify-content:center; margin-top:4rem; }

@media(max-width:992px){ .ar-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:768px){
    .ar-grid { grid-template-columns:1fr; }
    .sv-hero-premium { padding: 8rem 1.5rem 4rem; }
    .sv-title { font-size: 2rem; }
}
</style>

{{-- HERO SECTION --}}
<section class="sv-hero-premium">
    <div class="sv-hero-inner">
        {{-- Breadcrumb --}}
        <div class="sv-breadcrumb">
            <a href="{{ url("/{$locale}/") }}">Home</a>
            <span class="sv-breadcrumb-sep">/</span>
            <a href="{{ url("/{$locale}/articles") }}">Articles</a>
            <span class="sv-breadcrumb-sep">/</span>
            <span class="sv-breadcrumb-current">{{ $author->name }}</span>
        </div>

        {{-- Author Info --}}
        @if($author->photo)
            <img src="{{ asset('storage/'.$author->photo) }}" alt="{{ $author->name }}" class="author-photo-lg">
        @else
            <div class="author-photo-lg" style="display:flex;align-items:center;justify-content:center;color:#94A3B8;">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
        @endif
        
        <div class="sv-label">Author Profile</div>
        <h1 class="sv-title">{{ $author->name }}</h1>
        
        @if($bio || $idBio)
            <div class="sv-intro">
                {{ $bio ?? $idBio }}
            </div>
        @endif

        @if(!empty($author->social_links))
        <div class="author-socials">
            @if(!empty($author->social_links['linkedin']))
            <a href="{{ $author->social_links['linkedin'] }}" target="_blank" rel="noopener noreferrer" class="author-social-link" title="LinkedIn Profile">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
            </a>
            @endif
            @if(!empty($author->social_links['twitter']))
            <a href="{{ $author->social_links['twitter'] }}" target="_blank" rel="noopener noreferrer" class="author-social-link" title="X / Twitter Profile">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.008 3.97H5.078z"/></svg>
            </a>
            @endif
        </div>
        @endif
    </div>
</section>

{{-- ARTICLES LIST --}}
<section class="ar-section">
    <div class="section-header">
        <h2 class="section-title">Published Articles</h2>
        <div class="section-count">{{ $articles->total() }} Articles</div>
    </div>

    @if($articles->count() > 0)
        <div class="ar-grid">
            @foreach($articles as $a)
                @php
                    $at = $a->translate($locale);
                @endphp
                <a href="{{ url("/{$locale}/articles/{$a->slug}") }}" class="ar-card">
                    <div class="ar-card-img">
                        <img src="{{ $a->image_url }}" alt="{{ $at?->title ?? $a->title }}">
                    </div>
                    <div class="ar-card-body">
                        <div class="ar-card-meta">
                            <span class="ar-card-cat">{{ $a->category ?? 'News' }}</span>
                            <span>•</span>
                            <span>{{ $a->created_at->format('M d, Y') }}</span>
                        </div>
                        <h3 class="ar-card-title">{{ $at?->title ?? $a->title }}</h3>
                        <p class="ar-card-desc">{{ Str::limit(strip_tags($at?->content ?? $a->content), 120) }}</p>
                        <div class="ar-card-read">
                            Read Article
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($articles->hasPages())
        <div class="sv-pagination">
            {{ $articles->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    @else
        <div style="text-align:center; padding:4rem 0; background:var(--c-surface); border-radius:20px; border:2px dashed var(--c-border);">
            <svg width="48" height="48" fill="none" stroke="var(--c-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:1rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            <h3 style="font-size:1.25rem;color:var(--c-text);margin:0 0 .5rem;">No Articles Found</h3>
            <p style="color:var(--c-muted);margin:0;">This author hasn't published any articles yet.</p>
        </div>
    @endif
</section>

@endsection
