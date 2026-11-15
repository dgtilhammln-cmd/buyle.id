@php
    // Cari produk tipe tiket event terdekat yang event_date >= hari ini
    $countdownEvent = null;
    $now = \Carbon\Carbon::now();
    if (isset($products) && $products->count()) {
        $eventProducts = $products->filter(function($p) use ($now) {
            $pType = strtolower($p->product_type ?? '');
            $bData = is_array($p->data_json ?? null) ? $p->data_json : (json_decode($p->data_json ?? '[]', true) ?: []);
            if (empty($pType)) $pType = strtolower($bData['product_type'] ?? $bData['category'] ?? '');
            $isTicket = in_array($pType, ['ticket', 'tiket', 'tiket event', 'event', 'ticket_event']) || !empty($p->event_date);
            if (!$isTicket) return false;
            if (empty($p->event_date)) return false;
            try {
                $evDate = $p->event_date instanceof \Carbon\Carbon ? $p->event_date : \Carbon\Carbon::parse($p->event_date);
                if (!empty($p->event_time)) {
                    $tParts = explode(':', trim($p->event_time));
                    if (count($tParts) >= 2) $evDate = $evDate->copy()->setTime((int)$tParts[0], (int)$tParts[1], (int)($tParts[2] ?? 0));
                }
                return $evDate->isFuture() || $evDate->isToday();
            } catch (\Exception $e) { return false; }
        });
        $countdownEvent = $eventProducts->sortBy(function($p) {
            try {
                $evDate = $p->event_date instanceof \Carbon\Carbon ? $p->event_date : \Carbon\Carbon::parse($p->event_date);
                return $evDate->timestamp;
            } catch (\Exception $e) { return PHP_INT_MAX; }
        })->first();
    }

    if (!$countdownEvent) return; // Tidak tampilkan jika tidak ada event

    $cdEnabled = $config['countdown_enabled'] ?? 0;
    if (!$cdEnabled) return; // Tidak tampilkan jika dinonaktifkan

    $cdHeadline    = $config['countdown_headline']    ?? 'Event Akan Segera Dimulai!';
    $cdSubheadline = $config['countdown_subheadline'] ?? 'Dapatkan tiket Anda sebelum kehabisan.';
    $cdCtaEnabled  = $config['countdown_cta_enabled'] ?? 1;
    $cdCtaText     = $config['countdown_cta_text']    ?? 'Beli Tiket Sekarang';

    try {
        $evDateObj = $countdownEvent->event_date instanceof \Carbon\Carbon
            ? $countdownEvent->event_date->copy()
            : \Carbon\Carbon::parse($countdownEvent->event_date);

        if (!empty($countdownEvent->event_time)) {
            try {
                $timeParts = explode(':', trim($countdownEvent->event_time));
                if (count($timeParts) >= 2) {
                    $evDateObj->setTime((int)$timeParts[0], (int)$timeParts[1], (int)($timeParts[2] ?? 0));
                }
            } catch (\Exception $e) {}
        }

        $eventDateTime = $evDateObj->toISOString(); // For JS
        $eventDateFormatted = $evDateObj->translatedFormat('d F Y');
        $eventTimeFormatted = !empty($countdownEvent->event_time) ? $countdownEvent->event_time : '';
    } catch (\Exception $e) {
        return;
    }

    $eventName  = $countdownEvent->name ?? $countdownEvent->title ?? 'Event Spesial';
    $eventSlug  = $countdownEvent->slug ?? null;
    $eventImg   = $countdownEvent->image_url ?? null;
    $eventLoc   = $countdownEvent->event_location ?? null;
    $eventPrice = $countdownEvent->price ?? 0;

    $ctaLink = $eventSlug
        ? url('/' . ($profile->store_slug ?? '') . '/produk/' . $eventSlug)
        : url('/' . ($profile->store_slug ?? '') . '/produk');

    // Colors from config
    $accentBg  = $config['color_btn'] ?? '#1db349';
    $accentTxt = $config['color_btn_text'] ?? '#ffffff';
    $cardBg    = $config['color_card'] ?? '#ffffff';
    $textColor = $config['color_text'] ?? '#0f172a';
@endphp

{{-- ═══════════════════════════════════════════════════════
     COUNTDOWN EVENT SECTION — TEMA 5
     ═══════════════════════════════════════════════════════ --}}
<section id="t5-countdown-section" class="t5-countdown-wrap">
    <style>
        .t5-countdown-wrap {
            padding: 4rem 1rem;
            background: linear-gradient(135deg, {{ $config['footer_bg_color1'] ?? '#09121a' }} 0%, {{ $config['footer_bg_color2'] ?? '#064e3b' }} 100%);
            position: relative;
            overflow: hidden;
        }
        .t5-countdown-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(29,179,73,0.15) 0%, transparent 70%),
                        radial-gradient(ellipse at 80% 50%, rgba(132,206,0,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        .t5-cd-container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            text-align: center;
        }
        .t5-cd-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(29,179,73,0.2);
            border: 1px solid rgba(29,179,73,0.4);
            border-radius: 50px;
            padding: 0.35rem 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #4ade80;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 1.25rem;
        }
        .t5-cd-badge-dot {
            width: 7px; height: 7px;
            background: #4ade80;
            border-radius: 50%;
            animation: t5cdBlink 1s infinite;
        }
        @keyframes t5cdBlink {
            0%,100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .t5-cd-headline {
            font-size: clamp(1.5rem, 4vw, 2.4rem);
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 0.6rem;
            line-height: 1.2;
        }
        .t5-cd-subheadline {
            font-size: 1rem;
            color: rgba(255,255,255,0.7);
            margin: 0 0 0.75rem;
        }
        .t5-cd-event-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #4ade80;
            margin-bottom: 0.4rem;
        }
        .t5-cd-event-meta {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.55);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .t5-cd-event-meta span {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Countdown Timer Boxes */
        .t5-cd-timer {
            display: flex;
            justify-content: center;
            gap: clamp(0.5rem, 2vw, 1.25rem);
            margin-bottom: 2.5rem;
            flex-wrap: nowrap;
        }
        .t5-cd-box {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 16px;
            padding: clamp(0.75rem, 2vw, 1.25rem) clamp(0.75rem, 2.5vw, 1.5rem);
            min-width: clamp(60px, 15vw, 90px);
            backdrop-filter: blur(8px);
            transition: transform 0.1s;
        }
        .t5-cd-box:hover { transform: translateY(-2px); }
        .t5-cd-num {
            font-size: clamp(1.6rem, 5vw, 3rem);
            font-weight: 900;
            color: #ffffff;
            line-height: 1;
            font-variant-numeric: tabular-nums;
            font-family: 'Montserrat', 'Inter', sans-serif;
            transition: transform 0.15s cubic-bezier(0.34,1.56,0.64,1);
        }
        .t5-cd-num.flip {
            transform: scale(1.08);
        }
        .t5-cd-label {
            font-size: clamp(0.6rem, 1.5vw, 0.72rem);
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 0.3rem;
        }
        .t5-cd-separator {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            font-weight: 900;
            color: rgba(255,255,255,0.4);
            align-self: center;
            padding-bottom: 0.75rem;
            animation: t5cdBlink 1s infinite;
        }

        /* CTA Button */
        .t5-cd-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #1db349 0%, #84ce00 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.9rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(29,179,73,0.4);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .t5-cd-cta:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 12px 35px rgba(29,179,73,0.5);
            color: #ffffff;
            text-decoration: none;
        }
        .t5-cd-cta svg { flex-shrink: 0; }

        /* Finished state */
        .t5-cd-finished {
            font-size: 1.5rem;
            font-weight: 800;
            color: #4ade80;
            padding: 1.5rem 0;
        }

        @media (max-width: 480px) {
            .t5-cd-timer { gap: 0.4rem; }
            .t5-cd-separator { font-size: 1.2rem; }
        }
    </style>

    <div class="t5-cd-container">
        {{-- Badge --}}
        <div class="t5-cd-badge">
            <span class="t5-cd-badge-dot"></span>
            Coming Soon
        </div>

        {{-- Headline --}}
        <h2 class="t5-cd-headline">{{ $cdHeadline }}</h2>
        <p class="t5-cd-subheadline">{{ $cdSubheadline }}</p>

        {{-- Event Name & Meta --}}
        <div class="t5-cd-event-name">{{ $eventName }}</div>
        <div class="t5-cd-event-meta">
            <span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                {{ $eventDateFormatted }}@if($eventTimeFormatted) · {{ $eventTimeFormatted }}@endif
            </span>
            @if($eventLoc)
            <span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $eventLoc }}
            </span>
            @endif
            @if($eventPrice > 0)
            <span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/></svg>
                Rp {{ number_format($eventPrice, 0, ',', '.') }}
            </span>
            @endif
        </div>

        {{-- Countdown Timer --}}
        <div class="t5-cd-timer" id="t5CdTimer">
            <div class="t5-cd-box">
                <div class="t5-cd-num" id="t5CdDays">00</div>
                <div class="t5-cd-label">Hari</div>
            </div>
            <div class="t5-cd-separator">:</div>
            <div class="t5-cd-box">
                <div class="t5-cd-num" id="t5CdHours">00</div>
                <div class="t5-cd-label">Jam</div>
            </div>
            <div class="t5-cd-separator">:</div>
            <div class="t5-cd-box">
                <div class="t5-cd-num" id="t5CdMinutes">00</div>
                <div class="t5-cd-label">Menit</div>
            </div>
            <div class="t5-cd-separator">:</div>
            <div class="t5-cd-box">
                <div class="t5-cd-num" id="t5CdSeconds">00</div>
                <div class="t5-cd-label">Detik</div>
            </div>
        </div>
        <div class="t5-cd-finished" id="t5CdFinished" style="display:none;">🎉 Event Telah Dimulai!</div>

        {{-- CTA Button --}}
        @if($cdCtaEnabled)
        <a href="{{ $ctaLink }}" class="t5-cd-cta" id="t5CdCta">
            {{ $cdCtaText }}
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        @endif
    </div>
</section>

<script>
(function(){
    var target = new Date("{{ $eventDateTime }}").getTime();
    function pad(n){ return String(n).padStart(2,'0'); }

    var elD = document.getElementById('t5CdDays');
    var elH = document.getElementById('t5CdHours');
    var elM = document.getElementById('t5CdMinutes');
    var elS = document.getElementById('t5CdSeconds');
    var elTimer   = document.getElementById('t5CdTimer');
    var elFinish  = document.getElementById('t5CdFinished');
    var elCta     = document.getElementById('t5CdCta');

    function flipAnim(el, newVal) {
        if (el && el.textContent !== newVal) {
            el.textContent = newVal;
            el.classList.remove('flip');
            void el.offsetWidth;
            el.classList.add('flip');
            setTimeout(function(){ el.classList.remove('flip'); }, 200);
        }
    }

    function tick() {
        var now  = Date.now();
        var diff = target - now;
        if (diff <= 0) {
            if (elTimer)  elTimer.style.display  = 'none';
            if (elFinish) elFinish.style.display = 'block';
            return;
        }
        var d = Math.floor(diff / 86400000);
        var h = Math.floor((diff % 86400000) / 3600000);
        var m = Math.floor((diff % 3600000)  / 60000);
        var s = Math.floor((diff % 60000)    / 1000);
        flipAnim(elD, pad(d));
        flipAnim(elH, pad(h));
        flipAnim(elM, pad(m));
        flipAnim(elS, pad(s));
        setTimeout(tick, 1000);
    }
    tick();
})();
</script>
