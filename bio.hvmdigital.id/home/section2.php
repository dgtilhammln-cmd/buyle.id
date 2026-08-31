<style>
    /* --- INSIGHTS ELITE SECTION --- */
    .insights-v2 {
        padding: 160px 0;
        background-color: var(--dark-bg);
        position: relative;
        overflow: hidden;
    }

    .insights-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
    }

    /* --- CHART VISUAL (GANAS ANIMATION) --- */
    .visual-analytics {
        position: relative;
        padding: 40px;
        background: linear-gradient(135deg, rgba(255,255,255,0.02) 0%, transparent 100%);
        border-radius: 40px;
        border: 1px solid var(--border);
        overflow: hidden;
        /* Efek Glow di belakang chart */
        box-shadow: inset 0 0 50px rgba(161, 255, 90, 0.03);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
    }

    .live-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: var(--neon);
        border-radius: 50%;
        margin-right: 8px;
        box-shadow: 0 0 15px var(--neon);
        animation: pulseNeon 2s infinite;
    }

    @keyframes pulseNeon {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.5); opacity: 0.5; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* SVG Wave Animation Engine */
    .wave-container {
        width: 100%;
        height: 200px;
        overflow: visible;
    }

    .main-wave {
        fill: none;
        stroke: var(--neon);
        stroke-width: 5;
        stroke-linecap: round;
        stroke-dasharray: 1000;
        stroke-dashoffset: 1000;
        filter: drop-shadow(0 0 15px rgba(161, 255, 90, 0.5));
    }

    /* Animasi menggambar wave saat section terlihat */
    .reveal-visible .main-wave {
        animation: drawWave 3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @keyframes drawWave {
        to { stroke-dashoffset: 0; }
    }

    /* Efek Area di bawah wave */
    .wave-fill {
        fill: url(#waveGradient);
        opacity: 0;
        transition: opacity 1.5s ease 1s;
    }
    .reveal-visible .wave-fill { opacity: 0.15; }

    /* --- TEXT & NUMBERS --- */
    .insight-title {
        font-size: clamp(32px, 5vw, 56px);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -2px;
        color: #fff;
    }

    .insight-title span {
        background: linear-gradient(to right, var(--neon), #4efdc4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .insight-desc {
        color: #94a3b8;
        font-size: 18px;
        line-height: 1.7;
        margin: 30px 0 50px;
        max-width: 500px;
    }

    /* Staggered Number Animation */
    .stat-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .stat-box {
        border-left: 2px solid var(--border);
        padding-left: 20px;
        transition: var(--transition-smooth);
    }

    .stat-box:hover {
        border-left-color: var(--neon);
        transform: translateX(10px);
    }

    .stat-number {
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 5px;
        display: block;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 700;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    /* --- MOBILE OPTIMIZATION --- */
    @media (max-width: 992px) {
        .insights-v2 { padding: 100px 20px; }
        .insights-grid { grid-template-columns: 1fr; gap: 60px; text-align: center; }
        .insight-desc { margin: 30px auto 50px; }
        .stat-row { justify-content: center; }
        .stat-box { border-left: none; border-top: 1px solid var(--border); padding: 20px 0 0; }
        .stat-box:hover { transform: translateY(-5px); }
    }
</style>

<section class="insights-v2 reveal-section">
    <!-- Background Decor -->
    <div style="position: absolute; top: 0; right: 0; width: 400px; height: 400px; background: radial-gradient(circle, rgba(161, 255, 90, 0.02) 0%, transparent 70%); pointer-events: none;"></div>

    <div class="container">
        <div class="insights-grid">
            
            <!-- Left: Chart Visual -->
            <div class="visual-analytics">
                <div class="chart-header">
                    <div>
                        <span style="font-weight: 800; font-size: 10px; color: #4b5563; letter-spacing: 2px; text-transform: uppercase;">Engagement Metrics</span>
                    </div>
                    <div style="display: flex; align-items: center;">
                        <span class="live-dot"></span>
                        <span style="font-weight: 900; font-size: 10px; color: var(--neon); letter-spacing: 1px;">SYSTEM LIVE</span>
                    </div>
                </div>

                <div class="wave-container">
                    <svg viewBox="0 0 400 150" preserveAspectRatio="none" style="width: 100%; height: 100%;">
                        <defs>
                            <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:var(--neon); stop-opacity:1" />
                                <stop offset="100%" style="stop-color:var(--neon); stop-opacity:0" />
                            </linearGradient>
                        </defs>
                        <!-- Area Fill -->
                        <path class="wave-fill" d="M0,120 C50,100 80,140 150,80 C220,20 280,120 350,60 C380,40 400,80 400,80 L400,150 L0,150 Z" />
                        <!-- Line Path -->
                        <path class="main-wave" d="M0,120 C50,100 80,140 150,80 C220,20 280,120 350,60 C380,40 400,80 400,80" />
                    </svg>
                </div>

                <div style="margin-top: 30px; display: flex; gap: 40px; border-top: 1px solid var(--border); padding-top: 20px; opacity: 0.5;">
                    <div style="font-size: 10px; font-weight: 700;">AVG. CTR: <span class="text-neon">24.8%</span></div>
                    <div style="font-size: 10px; font-weight: 700;">RETENTION: <span class="text-neon">92.1%</span></div>
                </div>
            </div>

            <!-- Right: Text Content -->
            <div class="insight-text">
                <h2 class="insight-title">
                    Mudah Dalam <br>
                    <span>Genggaman.</span>
                </h2>
                <p class="insight-desc">
                    Pantau ekosistem digital Anda dalam satu pusat kendali. Kami menyajikan data bukan hanya angka, tapi intelijen yang mendorong pertumbuhan profil Anda.
                </p>
                
                <div class="stat-row">
                    <div class="stat-box">
                        <span class="stat-number text-neon">100%</span>
                        <span class="stat-label">Privacy Protected</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number text-neon">Real-time</span>
                        <span class="stat-label">Cloud Sync</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    /**
     * Logic khusus untuk mentrigger animasi wave 
     * ketika section masuk ke layar (Intersection Observer)
     */
    const insightSection = document.querySelector('.insights-v2');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal-visible');
            }
        });
    }, { threshold: 0.3 });

    observer.observe(insightSection);
</script>