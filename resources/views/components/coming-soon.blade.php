{{-- ============================================================
     COMING SOON — Countdown Page Component
     Usage: @include('components.coming-soon')
     Target: 26 Oktober 2026, 08:00 WIB
     ============================================================ --}}
<style>
    /* ---------- Reset & Base ---------- */
    .cs-wrapper * { box-sizing: border-box; margin: 0; padding: 0; }
    .cs-wrapper {
        min-height: 100vh;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(160deg, #040e05 0%, #0b1f0d 40%, #08170a 100%);
        position: relative;
        overflow: hidden;
        padding: 2rem 1rem 3rem;
        font-family: 'Inter', 'Montserrat', system-ui, sans-serif;
    }

    /* ---------- Animated particles ---------- */
    .cs-particles {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
        z-index: 0;
    }
    .cs-dot {
        position: absolute;
        border-radius: 50%;
        background: #1eb349;
        opacity: 0;
        animation: cs-float linear infinite;
    }

    @keyframes cs-float {
        0%   { transform: translateY(110vh) scale(0); opacity: 0; }
        10%  { opacity: 0.25; }
        90%  { opacity: 0.08; }
        100% { transform: translateY(-10vh) scale(1.2); opacity: 0; }
    }

    /* ---------- Glow rings ---------- */
    .cs-glow {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .cs-glow-1 {
        width: 600px; height: 600px;
        top: -200px; left: -200px;
        background: radial-gradient(circle, rgba(30,179,73,0.12) 0%, transparent 70%);
        animation: cs-pulse 8s ease-in-out infinite;
    }
    .cs-glow-2 {
        width: 500px; height: 500px;
        bottom: -150px; right: -150px;
        background: radial-gradient(circle, rgba(165,207,55,0.10) 0%, transparent 70%);
        animation: cs-pulse 10s ease-in-out 3s infinite;
    }

    @keyframes cs-pulse {
        0%, 100% { transform: scale(1); opacity: 0.7; }
        50%       { transform: scale(1.15); opacity: 1; }
    }

    /* ---------- Grid background ---------- */
    .cs-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(30,179,73,0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(30,179,73,0.04) 1px, transparent 1px);
        background-size: 60px 60px;
        z-index: 0;
        mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, black 40%, transparent 100%);
        -webkit-mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, black 40%, transparent 100%);
    }

    /* ---------- Content ---------- */
    .cs-content {
        position: relative;
        z-index: 1;
        text-align: center;
        width: 100%;
        max-width: 700px;
    }

    /* Badge */
    .cs-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(30,179,73,0.12);
        border: 1px solid rgba(30,179,73,0.3);
        border-radius: 999px;
        padding: 0.4rem 1.1rem;
        font-size: 0.72rem;
        font-weight: 700;
        color: #5dda81;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 2rem;
        backdrop-filter: blur(8px);
    }
    .cs-badge-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #1eb349;
        animation: cs-blink 1.4s ease-in-out infinite;
        flex-shrink: 0;
    }
    @keyframes cs-blink {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: 0.3; transform: scale(0.7); }
    }

    /* Logo */
    .cs-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .cs-logo-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #1eb349, #a5cf37);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 20px rgba(30,179,73,0.4);
    }
    .cs-logo-text {
        font-size: 1.9rem;
        font-weight: 900;
        color: #fff;
        letter-spacing: -0.03em;
    }
    .cs-logo-text span { color: #1eb349; }

    /* Headline */
    .cs-headline {
        font-size: clamp(2rem, 6vw, 3.5rem);
        font-weight: 900;
        color: #ffffff;
        line-height: 1.1;
        letter-spacing: -0.03em;
        margin-bottom: 1rem;
    }
    .cs-headline .cs-hl-green {
        background: linear-gradient(90deg, #1eb349, #a5cf37);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Subtext */
    .cs-sub {
        font-size: clamp(0.9rem, 2.5vw, 1.05rem);
        color: rgba(255,255,255,0.55);
        line-height: 1.65;
        margin-bottom: 2.5rem;
        max-width: 520px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ---------- Countdown ---------- */
    .cs-countdown {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 2.5rem;
    }
    .cs-unit {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(30,179,73,0.18);
        border-radius: 20px;
        padding: 1.5rem 1.25rem 1.1rem;
        min-width: 90px;
        flex: 1 1 80px;
        max-width: 120px;
        backdrop-filter: blur(16px);
        position: relative;
        overflow: hidden;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .cs-unit::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(30,179,73,0.5), transparent);
    }
    .cs-unit:hover {
        border-color: rgba(30,179,73,0.4);
        box-shadow: 0 0 20px rgba(30,179,73,0.1);
    }
    .cs-num {
        font-size: clamp(2rem, 5vw, 2.8rem);
        font-weight: 900;
        color: #fff;
        line-height: 1;
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.02em;
        display: block;
        margin-bottom: 0.4rem;
        transition: transform 0.15s;
    }
    .cs-num.flip { animation: cs-flip 0.25s ease; }
    @keyframes cs-flip {
        0%   { transform: translateY(-8px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    .cs-label {
        font-size: 0.68rem;
        font-weight: 700;
        color: rgba(255,255,255,0.4);
        text-transform: uppercase;
        letter-spacing: 0.09em;
    }
    .cs-sep {
        font-size: 2.5rem;
        font-weight: 900;
        color: rgba(30,179,73,0.4);
        align-self: center;
        margin-top: -0.5rem;
        animation: cs-blink 1.4s ease-in-out infinite;
        flex-shrink: 0;
    }

    /* ---------- Progress bar ---------- */
    .cs-progress-wrap {
        width: 100%;
        max-width: 420px;
        margin: 0 auto 2.5rem;
    }
    .cs-progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.72rem;
        color: rgba(255,255,255,0.4);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .cs-progress-bar {
        height: 6px;
        background: rgba(255,255,255,0.08);
        border-radius: 999px;
        overflow: hidden;
    }
    .cs-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #1eb349, #a5cf37);
        box-shadow: 0 0 10px rgba(30,179,73,0.5);
        transition: width 0.5s ease;
    }

    /* ---------- CTA Features ---------- */
    .cs-features {
        display: flex;
        gap: 1.25rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 2.5rem;
    }
    .cs-feat {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        padding: 0.55rem 1rem;
        font-size: 0.78rem;
        color: rgba(255,255,255,0.65);
        font-weight: 600;
    }
    .cs-feat svg { color: #1eb349; flex-shrink: 0; }

    /* ---------- Footer note ---------- */
    .cs-footer-note {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.25);
        letter-spacing: 0.03em;
    }
    .cs-footer-note a {
        color: #1eb349;
        text-decoration: none;
        font-weight: 700;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 600px) {
        .cs-unit { min-width: 70px; padding: 1.1rem 0.75rem 0.85rem; }
        .cs-sep { font-size: 1.8rem; }
        .cs-countdown { gap: 0.6rem; }
    }
</style>

<div class="cs-wrapper">
    {{-- Animated background elements --}}
    <div class="cs-grid"></div>
    <div class="cs-glow cs-glow-1"></div>
    <div class="cs-glow cs-glow-2"></div>
    <div class="cs-particles" id="csParticles"></div>

    <div class="cs-content">

        {{-- Badge --}}
        <div class="cs-badge">
            <span class="cs-badge-dot"></span>
            Segera Hadir &bull; 26 Oktober 2026
        </div>

        {{-- Logo --}}
        <div class="cs-logo">
            <div class="cs-logo-icon">
                <svg width="28" height="28" fill="none" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5z" stroke="#fff" stroke-width="1.8" stroke-linejoin="round"/>
                    <path d="M2 17l10 5 10-5" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12l10 5 10-5" stroke="rgba(255,255,255,0.5)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span class="cs-logo-text">buyle<span>.id</span></span>
        </div>

        {{-- Headline --}}
        <h1 class="cs-headline">
            Platform Kreator<br>
            <span class="cs-hl-green">Terbaik Indonesia</span>
        </h1>

        <p class="cs-sub">
            Kami sedang mempersiapkan pengalaman belanja & kreator terbaik untuk Anda.
            Marketplace buyle.id akan segera hadir dan siap melayani jutaan transaksi.
        </p>

        {{-- Countdown --}}
        <div class="cs-countdown">
            <div class="cs-unit">
                <span class="cs-num" id="cs-days">--</span>
                <span class="cs-label">Hari</span>
            </div>
            <div class="cs-sep">:</div>
            <div class="cs-unit">
                <span class="cs-num" id="cs-hours">--</span>
                <span class="cs-label">Jam</span>
            </div>
            <div class="cs-sep">:</div>
            <div class="cs-unit">
                <span class="cs-num" id="cs-mins">--</span>
                <span class="cs-label">Menit</span>
            </div>
            <div class="cs-sep">:</div>
            <div class="cs-unit">
                <span class="cs-num" id="cs-secs">--</span>
                <span class="cs-label">Detik</span>
            </div>
        </div>

        {{-- Progress bar --}}
        <div class="cs-progress-wrap">
            <div class="cs-progress-label">
                <span>Progress Persiapan</span>
                <span id="cs-pct">0%</span>
            </div>
            <div class="cs-progress-bar">
                <div class="cs-progress-fill" id="cs-fill" style="width:0%"></div>
            </div>
        </div>

        {{-- Features --}}
        <div class="cs-features">
            <div class="cs-feat">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Ribuan Kreator
            </div>
            <div class="cs-feat">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                    <line x1="12" y1="12" x2="12" y2="16"/>
                    <line x1="10" y1="14" x2="14" y2="14"/>
                </svg>
                Produk Digital
            </div>
            <div class="cs-feat">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Transaksi Aman
            </div>
            <div class="cs-feat">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                    <polyline points="17 6 23 6 23 12"/>
                </svg>
                Link in Bio + Affiliate
            </div>
        </div>

        {{-- Footer note --}}
        <p class="cs-footer-note">
            Sudah punya akun kreator?
            <a href="{{ route('creator.dashboard') }}">Masuk ke Dashboard</a>
            &nbsp;&bull;&nbsp;
            <a href="{{ route('home') }}">Beranda</a>
        </p>

    </div>
</div>

<script>
(function() {
    // Target: 26 Oktober 2026 08:00 WIB (UTC+7)
    var target = new Date('2026-10-26T08:00:00+07:00').getTime();
    // Start: approx Sep 2026 (project start)
    var start = new Date('2026-09-01T00:00:00+07:00').getTime();

    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function tick(elId, val) {
        var el = document.getElementById(elId);
        if (!el) return;
        var v = pad(val);
        if (el.textContent !== v) {
            el.classList.remove('flip');
            void el.offsetWidth; // reflow
            el.classList.add('flip');
            el.textContent = v;
        }
    }

    function update() {
        var now = Date.now();
        var diff = target - now;

        if (diff <= 0) {
            ['cs-days','cs-hours','cs-mins','cs-secs'].forEach(function(id) {
                document.getElementById(id).textContent = '00';
            });
            var fill = document.getElementById('cs-fill');
            var pct  = document.getElementById('cs-pct');
            if (fill) fill.style.width = '100%';
            if (pct)  pct.textContent   = '100%';
            return;
        }

        var days  = Math.floor(diff / 86400000);
        var hours = Math.floor((diff % 86400000) / 3600000);
        var mins  = Math.floor((diff % 3600000)  / 60000);
        var secs  = Math.floor((diff % 60000)    / 1000);

        tick('cs-days',  days);
        tick('cs-hours', hours);
        tick('cs-mins',  mins);
        tick('cs-secs',  secs);

        // Progress 0-100% between start and target
        var total    = target - start;
        var elapsed  = now - start;
        var progress = Math.min(100, Math.max(0, Math.round((elapsed / total) * 100)));
        var fill = document.getElementById('cs-fill');
        var pctEl = document.getElementById('cs-pct');
        if (fill)  fill.style.width = progress + '%';
        if (pctEl) pctEl.textContent = progress + '%';
    }

    update();
    setInterval(update, 1000);

    // Generate particles
    var container = document.getElementById('csParticles');
    if (container) {
        for (var i = 0; i < 22; i++) {
            var dot = document.createElement('div');
            dot.className = 'cs-dot';
            var size = Math.random() * 6 + 3;
            dot.style.width  = size + 'px';
            dot.style.height = size + 'px';
            dot.style.left   = Math.random() * 100 + 'vw';
            dot.style.bottom = '-20px';
            dot.style.animationDuration  = (Math.random() * 14 + 10) + 's';
            dot.style.animationDelay     = (Math.random() * 12) + 's';
            container.appendChild(dot);
        }
    }
})();
</script>
