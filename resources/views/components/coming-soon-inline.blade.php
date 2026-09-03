{{-- ============================================================
COMING SOON INLINE — Light theme, fits inside white page sections
Target: 26 Oktober 2026, 08:00 WIB
============================================================ --}}
<style>
    .csi-wrap {
        width: 100%;
        padding: 3rem 1rem 3.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        background: #f9fefb;
        border-radius: 24px;
        border: 1.5px dashed #d1fae5;
    }

    .csi-bg-dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle, rgba(30, 179, 73, 0.07) 1px, transparent 1px);
        background-size: 28px 28px;
        pointer-events: none;
        z-index: 0;
    }

    .csi-inner {
        position: relative;
        z-index: 1;
    }

    .csi-icon-wrap {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        border-radius: 20px;
        background: linear-gradient(135deg, #e8faf0, #d1fae5);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(30, 179, 73, 0.12);
    }

    .csi-headline {
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(1.4rem, 4vw, 2rem);
        font-weight: 900;
        color: #0b120c;
        letter-spacing: -0.025em;
        margin-bottom: 0.6rem;
        line-height: 1.2;
    }

    .csi-headline span {
        background: linear-gradient(90deg, #1eb349, #a5cf37);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .csi-sub {
        font-size: 0.9rem;
        color: #64748b;
        margin-bottom: 2rem;
        line-height: 1.65;
        max-width: 460px;
        margin-left: auto;
        margin-right: auto;
    }

    /* --- Countdown --- */
    .csi-countdown {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
        align-items: center;
    }

    .csi-unit {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.1rem 1rem 0.85rem;
        min-width: 75px;
        flex: 1 1 65px;
        max-width: 100px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .csi-unit:hover {
        border-color: #1eb349;
        box-shadow: 0 4px 16px rgba(30, 179, 73, 0.1);
    }

    .csi-num {
        display: block;
        font-size: clamp(1.6rem, 4vw, 2.2rem);
        font-weight: 900;
        color: #0b120c;
        line-height: 1;
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.02em;
        margin-bottom: 0.35rem;
        transition: transform 0.15s;
    }

    .csi-num.csi-flip {
        animation: csi-flip 0.22s ease;
    }

    @keyframes csi-flip {
        0% {
            transform: translateY(-6px);
            opacity: 0;
        }

        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .csi-label {
        font-size: 0.62rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.09em;
    }

    .csi-sep {
        font-size: 2rem;
        font-weight: 900;
        color: #d1fae5;
        align-self: center;
        margin-top: -0.4rem;
        flex-shrink: 0;
    }

    /* --- Progress --- */
    .csi-progress-wrap {
        max-width: 360px;
        margin: 0 auto 2rem;
    }

    .csi-progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.7rem;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }

    .csi-progress-bar {
        height: 5px;
        background: #e2e8f0;
        border-radius: 999px;
        overflow: hidden;
    }

    .csi-progress-fill {
        height: 100%;
        border-radius: 999px;
        background: linear-gradient(90deg, #1eb349, #a5cf37);
        transition: width 0.5s ease;
    }

    /* --- Features --- */
    .csi-features {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .csi-feat {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.45rem 0.85rem;
        font-size: 0.75rem;
        color: #475569;
        font-weight: 600;
    }

    .csi-feat svg {
        color: #1eb349;
        flex-shrink: 0;
    }

    @media (max-width: 480px) {
        .csi-unit {
            min-width: 58px;
            padding: 0.9rem 0.6rem 0.7rem;
        }

        .csi-sep {
            font-size: 1.4rem;
        }

        .csi-countdown {
            gap: 0.4rem;
        }
    }
</style>

<div class="csi-wrap">
    <div class="csi-bg-dots"></div>
    <div class="csi-inner">

        {{-- Icon --}}
        <div class="csi-icon-wrap">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5z" stroke="#1eb349" stroke-width="2" stroke-linejoin="round" />
                <path d="M2 17l10 5 10-5" stroke="#1eb349" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M2 12l10 5 10-5" stroke="#a5cf37" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </div>

        {{-- Headline --}}
        <h2 class="csi-headline">
            Marketplace Segera <span>Launching!</span>
        </h2>
        <p class="csi-sub">
            Kami sedang menyiapkan ribuan produk pilihan dari kreator terbaik Indonesia.
            Hadir <strong style="color:#1eb349;">26 Oktober 2026, 08:00 WIB</strong>.
        </p>

        {{-- Countdown --}}
        <div class="csi-countdown">
            <div class="csi-unit">
                <span class="csi-num" id="csi-days">--</span>
                <span class="csi-label">Hari</span>
            </div>
            <div class="csi-sep">:</div>
            <div class="csi-unit">
                <span class="csi-num" id="csi-hours">--</span>
                <span class="csi-label">Jam</span>
            </div>
            <div class="csi-sep">:</div>
            <div class="csi-unit">
                <span class="csi-num" id="csi-mins">--</span>
                <span class="csi-label">Menit</span>
            </div>
            <div class="csi-sep">:</div>
            <div class="csi-unit">
                <span class="csi-num" id="csi-secs">--</span>
                <span class="csi-label">Detik</span>
            </div>
        </div>

        {{-- Progress --}}
        <div class="csi-progress-wrap">
            <div class="csi-progress-label">
                <span>Progress Persiapan</span>
                <span id="csi-pct">0%</span>
            </div>
            <div class="csi-progress-bar">
                <div class="csi-progress-fill" id="csi-fill" style="width:0%"></div>
            </div>
        </div>

        {{-- Features --}}
        <div class="csi-features">
            <div class="csi-feat">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                Ribuan Kreator
            </div>
            <div class="csi-feat">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="2" y="7" width="20" height="14" rx="2" />
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2" />
                </svg>
                Produk Digital
            </div>
            <div class="csi-feat">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                Transaksi Aman
            </div>
            <div class="csi-feat">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                    <polyline points="17 6 23 6 23 12" />
                </svg>
                Affiliate & Link in Bio
            </div>
        </div>

    </div>
</div>

<script>
    (function () {
        var target = new Date('2026-10-26T08:00:00+07:00').getTime();
        var start = new Date('2026-09-01T00:00:00+07:00').getTime();
        function pad(n) { return n < 10 ? '0' + n : '' + n; }
        function tick(id, val) {
            var el = document.getElementById(id);
            if (!el) return;
            var v = pad(val);
            if (el.textContent !== v) {
                el.classList.remove('csi-flip');
                void el.offsetWidth;
                el.classList.add('csi-flip');
                el.textContent = v;
            }
        }
        function update() {
            var now = Date.now();
            var diff = target - now;
            if (diff <= 0) {
                ['csi-days', 'csi-hours', 'csi-mins', 'csi-secs'].forEach(function (id) {
                    var el = document.getElementById(id);
                    if (el) el.textContent = '00';
                });
                var f = document.getElementById('csi-fill');
                var p = document.getElementById('csi-pct');
                if (f) f.style.width = '100%';
                if (p) p.textContent = '100%';
                return;
            }
            tick('csi-days', Math.floor(diff / 86400000));
            tick('csi-hours', Math.floor((diff % 86400000) / 3600000));
            tick('csi-mins', Math.floor((diff % 3600000) / 60000));
            tick('csi-secs', Math.floor((diff % 60000) / 1000));
            var total = target - start;
            var elapsed = now - start;
            var progress = Math.min(100, Math.max(0, Math.round((elapsed / total) * 100)));
            var fill = document.getElementById('csi-fill');
            var pctEl = document.getElementById('csi-pct');
            if (fill) fill.style.width = progress + '%';
            if (pctEl) pctEl.textContent = progress + '%';
        }
        update();
        setInterval(update, 1000);
    })();
</script>