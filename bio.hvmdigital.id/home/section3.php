<style>
    /* --- CTA BENTO ELITE SECTION --- */
    .cta-v2 {
        padding: 120px 0;
        background-color: var(--dark-bg);
        position: relative;
    }

    /* Grid Utama Bento */
    .cta-bento-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 25px;
        align-items: stretch;
    }

    /* --- MODULE 1: MAIN ACTION --- */
    .cta-main-box {
        background: linear-gradient(145deg, #050d0a 0%, #020b09 100%);
        border: 1px solid var(--border);
        border-radius: 45px;
        padding: 60px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0 40px 100px rgba(0,0,0,0.4);
    }

    .cta-main-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 2px;
        background: linear-gradient(90deg, transparent, var(--neon), transparent);
    }

    .cta-tag {
        font-size: 12px;
        font-weight: 800;
        color: var(--neon);
        letter-spacing: 3px;
        text-transform: uppercase;
        margin-bottom: 25px;
        display: block;
    }

    .cta-v2 h2 {
        font-size: clamp(32px, 4vw, 52px);
        font-weight: 800;
        line-height: 1.1;
        color: #fff;
        letter-spacing: -2px;
        margin-bottom: 25px;
    }

    .cta-v2 p {
        font-size: 18px;
        color: #94a3b8;
        line-height: 1.6;
        margin-bottom: 40px;
        max-width: 500px;
    }

    /* --- MODULE 2: BENEFITS GRID (BENTO STYLE) --- */
    .cta-benefits-stack {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .benefit-card-lux {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border);
        border-radius: 30px;
        padding: 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: var(--transition-smooth);
        backdrop-filter: blur(10px);
    }

    .benefit-card-lux:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: var(--neon);
        transform: scale(1.03) translateX(10px);
    }

    .benefit-card-lux i {
        width: 50px;
        height: 50px;
        background: var(--dark-bg);
        border: 1px solid var(--border);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon);
        font-size: 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .benefit-info b {
        display: block;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    /* --- CTA BUTTON (SUPER GLOW) --- */
    .btn-cta-v2 {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        background: var(--neon);
        color: #000;
        padding: 22px 50px;
        border-radius: 20px;
        font-weight: 900;
        font-size: 15px;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        transition: var(--transition-smooth);
        width: fit-content;
        box-shadow: 0 20px 40px rgba(161, 255, 90, 0.2);
        position: relative;
        overflow: hidden;
    }

    .btn-cta-v2::after {
        content: '';
        position: absolute;
        top: -50%; left: -150%;
        width: 50%; height: 200%;
        background: rgba(255,255,255,0.4);
        transform: rotate(25deg);
        animation: ctaShine 4s infinite;
    }

    @keyframes ctaShine {
        0% { left: -150%; }
        20% { left: 150%; }
        100% { left: 150%; }
    }

    .btn-cta-v2:hover {
        transform: translateY(-5px) scale(1.05);
        background: #fff;
        box-shadow: 0 30px 60px rgba(161, 255, 90, 0.4);
    }

    .privacy-hint {
        margin-top: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #4b5563;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* --- MOBILE RESPONSIVE --- */
    @media (max-width: 1100px) {
        .cta-bento-grid { grid-template-columns: 1fr; }
        .cta-main-box { padding: 40px; }
        .btn-cta-v2 { width: 100%; }
    }

    @media (max-width: 600px) {
        .cta-v2 h2 { font-size: 32px; }
        .cta-main-box { padding: 40px 25px; border-radius: 35px; }
        .benefit-card-lux { padding: 20px; border-radius: 25px; }
    }
</style>

<section class="cta-v2 reveal-section">
    <!-- Ambient Background Glow -->
    <div style="position: absolute; bottom: 0; left: 50%; width: 100%; height: 100%; background: radial-gradient(circle at bottom, rgba(161, 255, 90, 0.03) 0%, transparent 70%); pointer-events: none;"></div>

    <div class="container">
        <div class="cta-bento-grid">
            
            <!-- Module 1: Content & Action -->
            <div class="cta-main-box">
                <span class="cta-tag">Mulai Sekarang — 100% Gratis</span>
                <h2>Siap Maksimalkan <br> <span class="text-neon">Personal Branding</span> Anda?</h2>
                <p>
                    Kelola semua tautan dalam satu halaman eksklusif. Jadilah lebih profesional, terpantau, dan berkelas bersama Bio by HVM Digital.
                </p>

                <a href="/register" class="btn-cta-v2">
                    Buat Gratis Sekarang!
                    <i class="fas fa-arrow-right"></i>
                </a>

                <div class="privacy-hint">
                    <i class="fas fa-shield-check" style="color: var(--neon);"></i>
                    Aman & Privasi Terjaga
                </div>
            </div>

            <!-- Module 2: Benefits Stack (Independent Cards) -->
            <div class="cta-benefits-stack">
                <div class="benefit-card-lux">
                    <i class="fas fa-link"></i>
                    <div class="benefit-info">
                        <b>Link Bio Premium</b>
                    </div>
                </div>
                <div class="benefit-card-lux">
                    <i class="fas fa-chart-pie"></i>
                    <div class="benefit-info">
                        <b>Analitik Real-time</b>
                    </div>
                </div>
                <div class="benefit-card-lux">
                    <i class="fas fa-bolt"></i>
                    <div class="benefit-info">
                        <b>Loading Super Cepat</b>
                    </div>
                </div>
                <div class="benefit-card-lux">
                    <i class="fas fa-infinity"></i>
                    <div class="benefit-info">
                        <b>Akses Selamanya</b>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>