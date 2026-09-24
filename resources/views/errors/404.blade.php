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
    padding: 4rem 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    background: radial-gradient(circle at 50% 30%, #f0fdf4 0%, #f8fafc 75%);
}

/* Floating ambient background circles */
.err-orb-1 {
    position: absolute;
    top: -120px;
    right: -120px;
    width: 480px;
    height: 480px;
    background: radial-gradient(circle, rgba(30,179,73,0.12) 0%, rgba(165,207,55,0.02) 70%);
    border-radius: 50%;
    animation: orbFloat 8s infinite alternate ease-in-out;
    pointer-events: none;
}
.err-orb-2 {
    position: absolute;
    bottom: -140px;
    left: -140px;
    width: 580px;
    height: 580px;
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
    max-width: 680px;
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
    padding: 0.4rem 1.1rem;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(8px);
    border-radius: 999px;
    border: 1px solid var(--c-border);
}
.err-breadcrumb a { color: var(--c-muted); text-decoration: none; transition: color 0.2s; }
.err-breadcrumb a:hover { color: var(--c-accent); }
.err-breadcrumb-sep { font-size: 0.65rem; opacity: 0.5; }
.err-breadcrumb-current { color: var(--c-text); font-weight: 600; }

/* ── ANIMATED DISCONNECTED CABLE & PLUG VECTOR HERO ── */
.err-cable-wrapper {
    position: relative;
    width: 100%;
    max-width: 520px;
    margin: 0 auto 1.25rem auto;
    cursor: pointer;
    user-select: none;
}

.err-cable-svg {
    width: 100%;
    height: auto;
    display: block;
    overflow: visible;
}

/* Cable Left/Right animation */
.err-plug-left {
    animation: plugLeftMove 3.5s infinite ease-in-out;
    transform-origin: left center;
}
.err-plug-right {
    animation: plugRightMove 3.5s infinite ease-in-out;
    transform-origin: right center;
}

@keyframes plugLeftMove {
    0%, 100% { transform: translateX(0); }
    50% { transform: translateX(-12px); }
}

@keyframes plugRightMove {
    0%, 100% { transform: translateX(0); }
    50% { transform: translateX(12px); }
}

/* Electric Sparks animation */
.err-sparks {
    animation: sparkPulse 1.2s infinite ease-in-out;
    transform-origin: center center;
}

@keyframes sparkPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.3; transform: scale(0.75); }
}

.err-spark-line {
    animation: sparkFlicker 0.6s infinite alternate ease-in-out;
}

@keyframes sparkFlicker {
    0% { opacity: 0.4; }
    100% { opacity: 1; stroke-width: 4px; }
}

/* 404 Bold Text */
.err-404-text {
    font-size: clamp(4.5rem, 14vw, 7.5rem);
    font-weight: 900;
    line-height: 1;
    letter-spacing: -0.05em;
    margin-bottom: 0.5rem;
    position: relative;
    display: inline-block;
    background: var(--c-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 10px 25px rgba(30, 179, 73, 0.2);
}

.err-404-shadow {
    width: 140px;
    height: 12px;
    background: radial-gradient(ellipse at center, rgba(15, 23, 42, 0.15) 0%, transparent 70%);
    margin: -10px auto 1.5rem auto;
    border-radius: 50%;
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
    margin-bottom: 0.85rem;
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

        {{-- Animated Cable & Plug Vector Illustration --}}
        <div class="err-cable-wrapper" id="errCableWrapper" title="Klik untuk menghubungkan kabel!">
            <svg class="err-cable-svg" viewBox="0 0 520 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="plugGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#1eb349" />
                        <stop offset="100%" stop-color="#a5cf37" />
                    </linearGradient>
                    <linearGradient id="cableGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#0F172A" />
                        <stop offset="100%" stop-color="#334155" />
                    </linearGradient>
                    <filter id="sparkGlow" x="-30%" y="-30%" width="160%" height="160%">
                        <feGaussianBlur stdDeviation="3" result="blur" />
                        <feComposite in="SourceGraphic" in2="blur" operator="over" />
                    </filter>
                </defs>

                <!-- Left Cable & Socket Plug -->
                <g class="err-plug-left" id="plugLeftGroup">
                    <!-- Left Cable Curve -->
                    <path d="M 0,70 Q 120,55 195,70" stroke="url(#cableGrad)" stroke-width="6" stroke-linecap="round"/>
                    <path d="M 0,70 Q 120,55 195,70" stroke="#1eb349" stroke-width="2" stroke-linecap="round" opacity="0.7"/>
                    
                    <!-- Strain Relief -->
                    <rect x="185" y="62" width="16" height="16" rx="4" fill="#0F172A"/>
                    <rect x="195" y="60" width="8" height="20" rx="3" fill="#334155"/>
                    
                    <!-- Socket Body (Female Plug) -->
                    <rect x="200" y="42" width="45" height="56" rx="10" fill="#0F172A"/>
                    <rect x="205" y="47" width="35" height="46" rx="7" fill="url(#plugGrad)"/>
                    
                    <!-- Socket Grip Ridges -->
                    <line x1="214" y1="54" x2="214" y2="86" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.85"/>
                    <line x1="222" y1="54" x2="222" y2="86" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.85"/>
                    <line x1="230" y1="54" x2="230" y2="86" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.85"/>
                    
                    <!-- Socket Collar Ring -->
                    <rect x="242" y="38" width="12" height="64" rx="5" fill="#0F172A"/>
                </g>

                <!-- Electric Spark Burst in the middle gap -->
                <g class="err-sparks" id="sparkGroup" filter="url(#sparkGlow)">
                    <!-- Radiating Spark Rays -->
                    <line class="err-spark-line" x1="260" y1="32" x2="260" y2="12" stroke="#a5cf37" stroke-width="3.5" stroke-linecap="round"/>
                    <line class="err-spark-line" x1="244" y1="38" x2="234" y2="24" stroke="#1eb349" stroke-width="3" stroke-linecap="round"/>
                    <line class="err-spark-line" x1="276" y1="38" x2="286" y2="24" stroke="#1eb349" stroke-width="3" stroke-linecap="round"/>
                    
                    <line class="err-spark-line" x1="260" y1="108" x2="260" y2="128" stroke="#a5cf37" stroke-width="3.5" stroke-linecap="round"/>
                    <line class="err-spark-line" x1="244" y1="102" x2="234" y2="116" stroke="#1eb349" stroke-width="3" stroke-linecap="round"/>
                    <line class="err-spark-line" x1="276" y1="102" x2="286" y2="116" stroke="#1eb349" stroke-width="3" stroke-linecap="round"/>

                    <!-- Center Electric Spark Bolt -->
                    <path d="M 258,56 L 264,67 L 256,73 L 263,84" stroke="#a5cf37" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                </g>

                <!-- Right Cable & Male Plug -->
                <g class="err-plug-right" id="plugRightGroup">
                    <!-- Male Prongs -->
                    <rect x="264" y="52" width="18" height="9" rx="3" fill="#0F172A"/>
                    <rect x="264" y="79" width="18" height="9" rx="3" fill="#0F172A"/>
                    <rect x="266" y="54" width="14" height="5" rx="1.5" fill="#a5cf37"/>
                    <rect x="266" y="81" width="14" height="5" rx="1.5" fill="#a5cf37"/>
                    
                    <!-- Plug Collar Ring -->
                    <rect x="280" y="38" width="12" height="64" rx="5" fill="#0F172A"/>
                    
                    <!-- Plug Main Body -->
                    <rect x="290" y="42" width="45" height="56" rx="10" fill="#0F172A"/>
                    <rect x="295" y="47" width="35" height="46" rx="7" fill="url(#plugGrad)"/>
                    
                    <!-- Plug Grip Ridges -->
                    <line x1="304" y1="54" x2="304" y2="86" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.85"/>
                    <line x1="312" y1="54" x2="312" y2="86" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.85"/>
                    <line x1="320" y1="54" x2="320" y2="86" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.85"/>
                    
                    <!-- Strain Relief -->
                    <rect x="330" y="60" width="8" height="20" rx="3" fill="#334155"/>
                    <rect x="334" y="62" width="16" height="16" rx="4" fill="#0F172A"/>
                    
                    <!-- Right Cable Curve -->
                    <path d="M 345,70 Q 400,85 520,70" stroke="url(#cableGrad)" stroke-width="6" stroke-linecap="round"/>
                    <path d="M 345,70 Q 400,85 520,70" stroke="#1eb349" stroke-width="2" stroke-linecap="round" opacity="0.7"/>
                </g>
            </svg>
        </div>

        <!-- Bold 404 Text -->
        <div class="err-404-text">404</div>
        <div class="err-404-shadow"></div>

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
        const wrapper = document.getElementById('errCableWrapper');
        const leftPlug = document.getElementById('plugLeftGroup');
        const rightPlug = document.getElementById('plugRightGroup');
        const sparks = document.getElementById('sparkGroup');

        if (wrapper && leftPlug && rightPlug) {
            // Interactive mouse move parallax
            document.addEventListener('mousemove', function(e) {
                const x = (e.clientX / window.innerWidth - 0.5) * 16;
                const y = (e.clientY / window.innerHeight - 0.5) * 16;
                wrapper.style.transform = `translate3d(${x * 0.4}px, ${y * 0.4}px, 0)`;
            });

            // Interactive plug connect/spark flash on click
            wrapper.addEventListener('click', function() {
                leftPlug.style.transition = 'transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                rightPlug.style.transition = 'transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                sparks.style.transition = 'all 0.25s ease';

                // Snap together
                leftPlug.style.transform = 'translateX(18px)';
                rightPlug.style.transform = 'translateX(-18px)';
                sparks.style.opacity = '1';
                sparks.style.transform = 'scale(1.5)';

                // Release back after 450ms
                setTimeout(() => {
                    leftPlug.style.transform = 'none';
                    rightPlug.style.transform = 'none';
                    sparks.style.opacity = '0.7';
                    sparks.style.transform = 'none';
                }, 450);
            });
        }
    });
</script>

@endsection
