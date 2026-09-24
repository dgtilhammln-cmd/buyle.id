@extends('layouts.app')
@section('title', 'Halaman Tidak Ditemukan — 404 | buyle.id')
@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>
:root {
    --c-bg:        #ffffff;
    --c-surface:   #F8FAFC;
    --c-border:    #E2E8F0;
    --c-text:      #0F172A;
    --c-muted:     #64748B;
    --c-accent:    #1eb349;
    --c-gradient:  linear-gradient(135deg, #1eb349 0%, #a5cf37 100%);
    --font:        'Montserrat', sans-serif;
}

body { background: var(--c-bg); font-family: var(--font); }

.err-page {
    min-height: 85vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 5rem 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    background: radial-gradient(circle at 50% 30%, #f0fdf4 0%, #f8fafc 70%);
}

/* Floating ambient background circles */
.err-orb-1 {
    position: absolute;
    top: -100px;
    right: -100px;
    width: 450px;
    height: 450px;
    background: radial-gradient(circle, rgba(30,179,73,0.12) 0%, rgba(165,207,55,0.02) 70%);
    border-radius: 50%;
    animation: orbFloat 8s infinite alternate ease-in-out;
    pointer-events: none;
}
.err-orb-2 {
    position: absolute;
    bottom: -120px;
    left: -120px;
    width: 550px;
    height: 550px;
    background: radial-gradient(circle, rgba(165,207,55,0.12) 0%, rgba(30,179,73,0.02) 70%);
    border-radius: 50%;
    animation: orbFloat 10s infinite alternate-reverse ease-in-out;
    pointer-events: none;
}

@keyframes orbFloat {
    0% { transform: translateY(0) scale(1); }
    100% { transform: translateY(-30px) scale(1.08); }
}

.err-inner {
    position: relative;
    z-index: 2;
    max-width: 640px;
    width: 100%;
}

/* Breadcrumb */
.err-breadcrumb {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--c-muted);
    margin-bottom: 2rem;
    padding: 0.4rem 1rem;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(8px);
    border-radius: 999px;
    border: 1px solid var(--c-border);
}
.err-breadcrumb a { color: var(--c-muted); text-decoration: none; transition: color 0.2s; }
.err-breadcrumb a:hover { color: var(--c-accent); }
.err-breadcrumb-sep { font-size: 0.65rem; opacity: 0.5; }
.err-breadcrumb-current { color: var(--c-text); font-weight: 600; }

/* Interactive 404 Visual Hero */
.err-hero-visual {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    perspective: 1000px;
}

.err-404-digit {
    font-size: clamp(6rem, 18vw, 10rem);
    font-weight: 900;
    line-height: 1;
    letter-spacing: -0.06em;
    background: var(--c-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 10px 30px rgba(30,179,73,0.15);
    display: inline-block;
    animation: digitPulse 4s infinite ease-in-out;
}

.err-404-center {
    width: clamp(70px, 16vw, 120px);
    height: clamp(70px, 16vw, 120px);
    border-radius: 50%;
    background: var(--c-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    box-shadow: 0 16px 35px rgba(30, 179, 73, 0.35);
    animation: centerFloat 3.5s infinite ease-in-out;
    margin: 0 0.25rem;
    cursor: pointer;
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.err-404-center:hover {
    transform: scale(1.15) rotate(15deg);
}

.err-404-center i {
    font-size: clamp(2.5rem, 6vw, 4rem);
}

@keyframes centerFloat {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-14px) rotate(6deg); }
}

@keyframes digitPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

/* Badge Label */
.err-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #15803d;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    padding: 0.4rem 1rem;
    border-radius: 999px;
    margin-bottom: 1.25rem;
}

.err-badge-dot {
    width: 7px;
    height: 7px;
    background: #1eb349;
    border-radius: 50%;
    box-shadow: 0 0 8px #1eb349;
    animation: dotBlink 1.5s infinite;
}

@keyframes dotBlink {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.8); }
}

/* Titles */
.err-title {
    font-size: clamp(1.75rem, 5vw, 2.5rem);
    font-weight: 800;
    color: var(--c-text);
    line-height: 1.25;
    letter-spacing: -0.03em;
    margin-bottom: 1rem;
}

.err-desc {
    font-size: 1.05rem;
    color: var(--c-muted);
    line-height: 1.65;
    margin-bottom: 2.5rem;
    max-width: 520px;
    margin-left: auto;
    margin-right: auto;
}

/* Action Buttons */
.err-btns {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.err-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    background: var(--c-gradient);
    color: #ffffff !important;
    font-family: var(--font);
    font-size: 0.95rem;
    font-weight: 700;
    padding: 0.9rem 2.2rem;
    border-radius: 50px;
    text-decoration: none !important;
    box-shadow: 0 10px 25px rgba(30, 179, 73, 0.35);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    border: none;
    cursor: pointer;
}

.err-btn-primary:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 14px 35px rgba(30, 179, 73, 0.45);
    color: #ffffff !important;
}

.err-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    background: #ffffff;
    color: var(--c-text) !important;
    font-family: var(--font);
    font-size: 0.95rem;
    font-weight: 600;
    padding: 0.9rem 2rem;
    border-radius: 50px;
    border: 1.5px solid var(--c-border);
    text-decoration: none !important;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}

.err-btn-secondary:hover {
    border-color: var(--c-accent);
    color: var(--c-accent) !important;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(30,179,73,0.12);
}

@media (max-width: 640px) {
    .err-btns {
        flex-direction: column;
        width: 100%;
    }
    .err-btn-primary, .err-btn-secondary {
        width: 100%;
        justify-content: center;
    }
}
</style>

<section class="err-page">
    <div class="err-orb-1"></div>
    <div class="err-orb-2"></div>

    <div class="err-inner">

        {{-- Breadcrumb --}}
        <nav class="err-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route_locale('home') }}">Beranda</a>
            <span class="err-breadcrumb-sep">›</span>
            <span class="err-breadcrumb-current">Halaman Tidak Ditemukan</span>
        </nav>

        {{-- Interactive 404 Visual Hero --}}
        <div class="err-hero-visual" id="errInteractiveHero">
            <span class="err-404-digit">4</span>
            <div class="err-404-center" title="Klik untuk efek interaktif!">
                <i class="ph-bold ph-compass-rose"></i>
            </div>
            <span class="err-404-digit">4</span>
        </div>

        <div class="err-badge">
            <span class="err-badge-dot"></span>
            Halaman Tidak Ditemukan
        </div>

        <h1 class="err-title">Ups! Halaman ini<br>tidak ada.</h1>
        <p class="err-desc">
            Halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau alamat URL yang Anda masukkan tidak sesuai.
        </p>

        <div class="err-btns">
            <a href="{{ route_locale('home') }}" class="err-btn-primary">
                <i class="ph-bold ph-house" style="font-size: 1.25rem;"></i>
                Kembali ke Beranda
            </a>
            <a href="{{ route_locale('contact') }}" class="err-btn-secondary">
                <i class="ph-bold ph-paper-plane-tilt" style="font-size: 1.25rem; color: #1eb349;"></i>
                Hubungi Kami
            </a>
        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hero = document.getElementById('errInteractiveHero');
        const icon = hero ? hero.querySelector('.err-404-center') : null;

        if (hero && icon) {
            // Interactive tilt on mousemove
            document.addEventListener('mousemove', function(e) {
                const x = (e.clientX / window.innerWidth - 0.5) * 20;
                const y = (e.clientY / window.innerHeight - 0.5) * 20;
                hero.style.transform = `translate3d(${x * 0.5}px, ${y * 0.5}px, 0)`;
            });

            // Interactive spin on click
            icon.addEventListener('click', function() {
                icon.style.transition = 'transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1)';
                icon.style.transform = 'scale(1.3) rotate(360deg)';
                setTimeout(() => {
                    icon.style.transform = 'none';
                }, 800);
            });
        }
    });
</script>

@endsection
