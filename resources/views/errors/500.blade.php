@extends('layouts.app')
@section('title', 'Server Error — 500 | buyle.id')
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
    min-height: 80vh; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 6rem 1.5rem; text-align: center;
    position: relative; overflow: hidden; background: var(--c-surface);
}
.err-page::before {
    content: ''; position: absolute; top: -150px; right: -100px;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(239,68,68,0.05) 0%, transparent 70%);
    border-radius: 50%; pointer-events: none;
}
.err-page::after {
    content: ''; position: absolute; bottom: -150px; left: -100px;
    width: 600px; height: 600px;
    background: radial-gradient(circle, rgba(30,179,73,0.04) 0%, transparent 70%);
    border-radius: 50%; pointer-events: none;
}
.err-inner { position: relative; z-index: 2; max-width: 600px; }
.err-breadcrumb {
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    font-size: 0.75rem; font-weight: 500; color: #64748B;
    margin-bottom: 3rem; font-family: 'Montserrat', sans-serif;
}
.err-breadcrumb a { color: #64748B; text-decoration: none; transition: color 0.2s; }
.err-breadcrumb a:hover { color: #1eb349; }
.err-breadcrumb-sep { font-size: 0.6rem; opacity: 0.5; }
.err-breadcrumb-current { color: #0F172A; font-weight: 600; }
.err-code {
    font-size: clamp(5rem, 15vw, 9rem); font-weight: 800;
    letter-spacing: -0.05em; line-height: 1;
    background: linear-gradient(135deg, #EF4444, #DC2626);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; margin-bottom: 1.5rem; font-family: 'Montserrat', sans-serif;
}
.err-label {
    display: inline-flex; align-items: center; gap: 0.5rem;
    font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em;
    text-transform: uppercase; color: #64748B; margin-bottom: 1rem;
}
.err-label::before { content: ''; display: block; width: 5px; height: 5px; background: #EF4444; border-radius: 50%; }
.err-title {
    font-size: clamp(1.5rem, 4vw, 2.25rem); font-weight: 700; color: #0F172A;
    line-height: 1.2; letter-spacing: -0.02em; margin-bottom: 1rem; font-family: 'Montserrat', sans-serif;
}
.err-desc { font-size: 1rem; color: #64748B; line-height: 1.7; margin-bottom: 2.5rem; font-family: 'Montserrat', sans-serif; }
.err-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
.err-btn-primary {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: #1eb349; color: #fff; font-family: 'Montserrat', sans-serif;
    font-size: 0.9375rem; font-weight: 600; padding: 0.875rem 2rem;
    border-radius: 50px; text-decoration: none !important; transition: all 0.3s;
    box-shadow: 0 8px 20px rgba(30,179,73,0.25);
}
.err-btn-primary:hover { background: #16a34a; transform: translateY(-2px); color: #fff !important; }
.err-btn-outline {
    display: inline-flex; align-items: center; gap: 0.5rem; background: transparent;
    color: #0F172A; font-family: 'Montserrat', sans-serif; font-size: 0.9375rem;
    font-weight: 600; padding: 0.875rem 2rem; border-radius: 50px;
    border: 1.5px solid #E2E8F0; text-decoration: none !important; transition: all 0.3s;
}
.err-btn-outline:hover { border-color: #1eb349; color: #1eb349 !important; }
.err-illustration { margin-bottom: 2rem; opacity: 0.12; }
</style>

<section class="err-page">
    <div class="err-inner">

        <nav class="err-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route_locale('home') }}">Beranda</a>
            <span class="err-breadcrumb-sep">›</span>
            <span class="err-breadcrumb-current">Error 500</span>
        </nav>

        <svg class="err-illustration" width="120" height="120" fill="none" stroke="#EF4444" stroke-width="1" viewBox="0 0 24 24">
            <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/><circle cx="12" cy="17" r=".5" fill="#EF4444"/>
        </svg>

        <div class="err-code">500</div>
        <div class="err-label">Server Error</div>
        <h1 class="err-title">Terjadi Kesalahan<br>pada Server.</h1>
        <p class="err-desc">
            Maaf, server sedang mengalami gangguan sementara.
            Tim kami sedang menangani masalah ini. Silakan coba lagi beberapa saat.
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
                Laporkan Masalah
            </a>
        </div>

    </div>
</section>

@endsection
