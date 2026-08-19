@extends('layouts.app')
@section('title', 'Halaman Tidak Ditemukan — 404 | buyle.id')
@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --c-bg:     #ffffff;
    --c-surface:#F8FAFC;
    --c-border: #E2E8F0;
    --c-text:   #0F172A;
    --c-muted:  #64748B;
    --c-accent: #1eb349;
    --c-accent-hover: #16a34a;
    --font:     'Montserrat', sans-serif;
}
body { background: var(--c-bg); font-family: var(--font); }

.err-page {
    min-height: 80vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 6rem 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    background: var(--c-surface);
}
.err-page::before {
    content: '';
    position: absolute;
    top: -150px; right: -100px;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(30,179,73,0.06) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.err-page::after {
    content: '';
    position: absolute;
    bottom: -150px; left: -100px;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(30,179,73,0.04) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}
.err-inner { position: relative; z-index: 2; max-width: 600px; }

/* Breadcrumb */
.err-breadcrumb {
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    font-size: 0.75rem; font-weight: 500; color: var(--c-muted);
    margin-bottom: 3rem; font-family: var(--font);
}
.err-breadcrumb a { color: var(--c-muted); text-decoration: none; transition: color 0.2s; }
.err-breadcrumb a:hover { color: var(--c-accent); }
.err-breadcrumb-sep { font-size: 0.6rem; opacity: 0.5; }
.err-breadcrumb-current { color: var(--c-text); font-weight: 600; }

/* Code */
.err-code {
    font-size: clamp(5rem, 15vw, 9rem);
    font-weight: 800;
    letter-spacing: -0.05em;
    line-height: 1;
    background: linear-gradient(135deg, #1eb349, #16a34a);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1.5rem;
    font-family: var(--font);
}

.err-label {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em;
    text-transform: uppercase; color: var(--c-muted);
    margin-bottom: 1rem;
}
.err-label::before {
    content: ''; display: block; width: 5px; height: 5px;
    background: var(--c-accent); border-radius: 50%;
}

.err-title {
    font-size: clamp(1.5rem, 4vw, 2.25rem);
    font-weight: 700; color: var(--c-text);
    line-height: 1.2; letter-spacing: -0.02em;
    margin-bottom: 1rem; font-family: var(--font);
}
.err-desc {
    font-size: 1rem; color: var(--c-muted); line-height: 1.7;
    margin-bottom: 2.5rem; font-family: var(--font);
}
.err-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.err-btn-primary {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: var(--c-accent); color: #fff; font-family: var(--font);
    font-size: 0.9375rem; font-weight: 600; padding: 0.875rem 2rem;
    border-radius: 50px; text-decoration: none !important;
    transition: all 0.3s; box-shadow: 0 8px 20px rgba(30,179,73,0.25);
}
.err-btn-primary:hover { background: var(--c-accent-hover); transform: translateY(-2px); color: #fff !important; }
.err-btn-outline {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: transparent; color: var(--c-text); font-family: var(--font);
    font-size: 0.9375rem; font-weight: 600; padding: 0.875rem 2rem;
    border-radius: 50px; border: 1.5px solid var(--c-border); text-decoration: none !important;
    transition: all 0.3s;
}
.err-btn-outline:hover { border-color: var(--c-accent); color: var(--c-accent) !important; }

/* Illustration */
.err-illustration {
    margin-bottom: 2rem;
    opacity: 0.15;
}
</style>

<section class="err-page">
    <div class="err-inner">

        {{-- Breadcrumb --}}
        <nav class="err-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route_locale('home') }}">Beranda</a>
            <span class="err-breadcrumb-sep">›</span>
            <span class="err-breadcrumb-current">Error 404</span>
        </nav>

        {{-- Icon --}}
        <svg class="err-illustration" width="120" height="120" fill="none" stroke="#1eb349" stroke-width="1" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            <line x1="11" y1="8" x2="11" y2="12"/><circle cx="11" cy="15" r=".5" fill="#1eb349"/>
        </svg>

        <div class="err-code">404</div>
        <div class="err-label">Halaman Tidak Ditemukan</div>
        <h1 class="err-title">Ups! Halaman ini<br>tidak ada.</h1>
        <p class="err-desc">
            Halaman yang Anda cari mungkin telah dihapus, dipindahkan,
            atau memang tidak pernah ada. Coba kembali ke beranda.
        </p>

        <div class="err-btns">
            <a href="{{ route_locale('home') }}" class="err-btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Kembali ke Beranda
            </a>
            <a href="{{ route_locale('contact') }}" class="err-btn-outline">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Hubungi Kami
            </a>
        </div>

    </div>
</section>

@endsection
