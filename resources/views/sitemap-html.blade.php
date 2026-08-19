<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sitemap — buyle.id</title>
<meta name="robots" content="noindex, follow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --bg:       #ffffff;
        --surface:  #f8fafc;
        --border:   #E2E8F0;
        --text:     #0F172A;
        --muted:    #64748B;
        --accent:   #0EA5E9;
        --accent2:  #0284C7;
        --dark:     #0F172A;
        --yellow:   #F5A623;
        --font:     'Montserrat', sans-serif;
    }

    body {
        font-family: var(--font);
        background: var(--bg);
        color: var(--text);
        -webkit-font-smoothing: antialiased;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* HERO HEADER */
    .sm-hero {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 5rem 1.5rem 4rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .sm-hero::before {
        content: '';
        position: absolute;
        top: -120px; right: -80px;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(14,165,233,.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .sm-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -60px;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(245,166,35,.04) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .sm-hero-inner { position: relative; z-index: 2; max-width: 700px; margin: 0 auto; }
    .sm-hero-badge {
        display: inline-flex; align-items: center; gap: .5rem;
        background: rgba(14,165,233,.1); border: 1px solid rgba(14,165,233,.2);
        color: var(--accent); font-size: .7rem; font-weight: 700;
        letter-spacing: .12em; text-transform: uppercase;
        padding: .4rem 1rem; border-radius: 50px; margin-bottom: 1.5rem;
    }
    .sm-hero h1 {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 500;
        color: var(--text);
        line-height: 1.15;
        letter-spacing: -.03em;
        margin-bottom: 1rem;
    }
    .sm-hero p {
        font-size: 1rem;
        color: var(--muted);
        line-height: 1.7;
    }
    .sm-hero-actions {
        display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap;
        margin-top: 2rem;
    }
    .sm-btn {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .6rem 1.25rem; border-radius: 8px;
        font-size: .8rem; font-weight: 600;
        text-decoration: none; transition: all .25s;
        font-family: var(--font);
    }
    .sm-btn-primary { background: var(--accent); color: #fff; }
    .sm-btn-primary:hover { background: var(--accent2); transform: translateY(-2px); }
    .sm-btn-outline { background: var(--bg); color: var(--text); border: 1.5px solid var(--border); }
    .sm-btn-outline:hover { border-color: var(--accent); color: var(--accent); }

    /* MAIN CONTENT */
    .sm-main {
        max-width: 1100px;
        margin: 0 auto;
        padding: 3rem 1.5rem 5rem;
        flex: 1;
        width: 100%;
    }

    /* STATS BAR */
    .sm-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 3rem;
    }
    .sm-stat {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
    }
    .sm-stat-num {
        font-size: 2rem;
        font-weight: 700;
        color: var(--accent);
        letter-spacing: -.04em;
    }
    .sm-stat-label {
        font-size: .75rem;
        color: var(--muted);
        font-weight: 600;
        margin-top: .25rem;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    /* GROUP */
    .sm-group {
        margin-bottom: 2.5rem;
    }
    .sm-group-header {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1rem;
        padding-bottom: .75rem;
        border-bottom: 1px solid var(--border);
    }
    .sm-group-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .sm-group-icon.blue { background: #EFF6FF; color: var(--accent); }
    .sm-group-icon.green { background: #F0FDF4; color: #16A34A; }
    .sm-group-icon.orange { background: #FFF7ED; color: #EA580C; }
    .sm-group-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text);
    }
    .sm-group-count {
        margin-left: auto;
        font-size: .7rem;
        font-weight: 700;
        color: var(--muted);
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 50px;
        padding: .2rem .7rem;
    }

    /* URL LIST */
    .sm-list {
        display: grid;
        gap: .5rem;
    }
    .sm-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        text-decoration: none;
        color: var(--text);
        transition: all .25s;
        position: relative;
        overflow: hidden;
    }
    .sm-item:hover {
        border-color: var(--accent);
        background: var(--surface);
        transform: translateX(4px);
    }
    .sm-item-priority {
        flex-shrink: 0;
        width: 36px; height: 36px;
        border-radius: 8px;
        background: rgba(14,165,233,.1);
        color: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: .7rem;
        font-weight: 700;
        font-family: var(--font);
    }
    .sm-item-body { flex: 1; min-width: 0; }
    .sm-item-label {
        font-size: .875rem;
        font-weight: 600;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: .15rem;
    }
    .sm-item-url {
        font-size: .75rem;
        color: var(--muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .sm-item-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: .25rem;
        flex-shrink: 0;
    }
    .sm-item-freq {
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--muted);
    }
    .sm-item-date {
        font-size: .65rem;
        color: var(--muted);
    }
    .sm-item-arrow {
        color: var(--border);
        flex-shrink: 0;
        transition: color .25s;
    }
    .sm-item:hover .sm-item-arrow { color: var(--accent); }

    /* XML BANNER */
    .sm-xml-banner {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        border-radius: 16px;
        padding: 1.75rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        margin-top: 3rem;
        flex-wrap: wrap;
    }
    .sm-xml-banner-text h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: .25rem;
    }
    .sm-xml-banner-text p {
        font-size: .8rem;
        color: rgba(255,255,255,.45);
    }
    .sm-xml-code {
        font-family: 'Courier New', monospace;
        font-size: .75rem;
        background: rgba(14,165,233,.15);
        border: 1px solid rgba(14,165,233,.3);
        color: #38BDF8;
        padding: .5rem 1rem;
        border-radius: 8px;
        word-break: break-all;
    }

    /* FOOTER */
    .sm-footer {
        text-align: center;
        padding: 2rem 1.5rem;
        border-top: 1px solid var(--border);
        font-size: .75rem;
        color: var(--muted);
    }

    /* RESPONSIVE */
    @media (max-width: 640px) {
        .sm-stats { grid-template-columns: 1fr 1fr; }
        .sm-stats .sm-stat:last-child { grid-column: span 2; }
        .sm-item-meta { display: none; }
        .sm-hero { padding: 4rem 1.25rem 3rem; }
    }
    @media (max-width: 400px) {
        .sm-stats { grid-template-columns: 1fr; }
        .sm-stats .sm-stat:last-child { grid-column: 1; }
    }
</style>
</head>
<body>

<div class="sm-hero">
    <div class="sm-hero-inner">
        <div class="sm-hero-badge">
            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            SITEMAP
        </div>
        <h1>Peta Situs<br>buyle.id</h1>
        <p>Semua halaman, produk, dan artikel yang tersedia di website<br>buyle.id untuk navigasi dan mesin pencari.</p>
        <div class="sm-hero-actions">
            <a href="/" class="sm-btn sm-btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Ke Beranda
            </a>
            <a href="/sitemap.xml" class="sm-btn sm-btn-outline" target="_blank">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Lihat XML
            </a>
        </div>
    </div>
</div>

<div class="sm-main">

    {{-- STATS --}}
    <div class="sm-stats">
        <div class="sm-stat">
            <div class="sm-stat-num">{{ count($staticPages) }}</div>
            <div class="sm-stat-label">Halaman Utama</div>
        </div>
        <div class="sm-stat">
            <div class="sm-stat-num">{{ count($serviceUrls) }}</div>
            <div class="sm-stat-label">Produk</div>
        </div>
        <div class="sm-stat">
            <div class="sm-stat-num">{{ count($articleUrls) }}</div>
            <div class="sm-stat-label">Artikel</div>
        </div>
    </div>

    {{-- HALAMAN UTAMA --}}
    <div class="sm-group">
        <div class="sm-group-header">
            <div class="sm-group-icon blue">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
            </div>
            <div class="sm-group-title">Halaman Utama</div>
            <div class="sm-group-count">{{ count($staticPages) }} URL</div>
        </div>
        <div class="sm-list">
            @foreach($staticPages as $page)
            <a href="{{ $page['url'] }}" class="sm-item">
                <div class="sm-item-priority">{{ $page['priority'] }}</div>
                <div class="sm-item-body">
                    <div class="sm-item-label">{{ $page['label'] }}</div>
                    <div class="sm-item-url">{{ $page['url'] }}</div>
                </div>
                <div class="sm-item-meta">
                    <span class="sm-item-freq">{{ $page['changefreq'] }}</span>
                    <span class="sm-item-date">{{ $page['lastmod'] }}</span>
                </div>
                <svg class="sm-item-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            @endforeach
        </div>
    </div>

    {{-- PRODUK --}}
    @if(count($serviceUrls))
    <div class="sm-group">
        <div class="sm-group-header">
            <div class="sm-group-icon green">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
            </div>
            <div class="sm-group-title">Produk buyle.id</div>
            <div class="sm-group-count">{{ count($serviceUrls) }} URL</div>
        </div>
        <div class="sm-list">
            @foreach($serviceUrls as $page)
            <a href="{{ $page['url'] }}" class="sm-item">
                <div class="sm-item-priority">{{ $page['priority'] }}</div>
                <div class="sm-item-body">
                    <div class="sm-item-label">{{ $page['label'] }}</div>
                    <div class="sm-item-url">{{ $page['url'] }}</div>
                </div>
                <div class="sm-item-meta">
                    <span class="sm-item-freq">{{ $page['changefreq'] }}</span>
                    <span class="sm-item-date">{{ $page['lastmod'] }}</span>
                </div>
                <svg class="sm-item-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ARTIKEL --}}
    @if(count($articleUrls))
    <div class="sm-group">
        <div class="sm-group-header">
            <div class="sm-group-icon orange">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div class="sm-group-title">Artikel & Blog</div>
            <div class="sm-group-count">{{ count($articleUrls) }} URL</div>
        </div>
        <div class="sm-list">
            @foreach($articleUrls as $page)
            <a href="{{ $page['url'] }}" class="sm-item">
                <div class="sm-item-priority">{{ $page['priority'] }}</div>
                <div class="sm-item-body">
                    <div class="sm-item-label">{{ $page['label'] }}</div>
                    <div class="sm-item-url">{{ $page['url'] }}</div>
                </div>
                <div class="sm-item-meta">
                    <span class="sm-item-freq">{{ $page['changefreq'] }}</span>
                    <span class="sm-item-date">{{ $page['lastmod'] }}</span>
                </div>
                <svg class="sm-item-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- AUTHORS --}}
    @if(isset($authorUrls) && count($authorUrls))
    <div class="sm-group">
        <div class="sm-group-header">
            <div class="sm-group-icon" style="background:#F5F3FF;color:#8B5CF6;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div class="sm-group-title">Penulis & Kontributor</div>
            <div class="sm-group-count">{{ count($authorUrls) }} URL</div>
        </div>
        <div class="sm-list">
            @foreach($authorUrls as $page)
            <a href="{{ $page['url'] }}" class="sm-item">
                <div class="sm-item-priority">{{ $page['priority'] }}</div>
                <div class="sm-item-body">
                    <div class="sm-item-label">{{ $page['label'] }}</div>
                    <div class="sm-item-url">{{ $page['url'] }}</div>
                </div>
                <div class="sm-item-meta">
                    <span class="sm-item-freq">{{ $page['changefreq'] }}</span>
                    <span class="sm-item-date">{{ $page['lastmod'] }}</span>
                </div>
                <svg class="sm-item-arrow" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- XML BANNER --}}
    <div class="sm-xml-banner">
        <div class="sm-xml-banner-text">
            <h3>XML Sitemap untuk Google</h3>
            <p>Daftarkan URL ini ke Google Search Console untuk indexing lebih cepat.</p>
        </div>
        <div class="sm-xml-code">{{ url('/sitemap.xml') }}</div>
    </div>

</div>

<div class="sm-footer">
    <p style="margin-bottom: 0.5rem;">Total <strong>{{ count($urls) }}</strong> URL terdaftar dalam sitemap buyle.id &bull; Diperbarui otomatis setiap ada konten baru.</p>
    <p>SEO by <a href="https://hvmdigital.com" target="_blank" rel="noopener noreferrer" style="color:var(--text); font-weight:600; text-decoration:none;">HVM Digital</a></p>
</div>

</body>
</html>
