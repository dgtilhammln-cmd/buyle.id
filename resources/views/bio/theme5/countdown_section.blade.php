@php
    $cdEnabled = $config['countdown_enabled'] ?? 0;
    if (!$cdEnabled) return;

    $now = \Carbon\Carbon::now();
    $eventQueue = [];

    if (isset($products) && $products->count()) {
        foreach ($products as $p) {
            $pType = strtolower($p->product_type ?? '');
            $bData = is_array($p->data_json ?? null) ? $p->data_json : (json_decode($p->data_json ?? '[]', true) ?: []);
            if (empty($pType))
                $pType = strtolower($bData['product_type'] ?? $bData['category'] ?? '');
            $isTicket = in_array($pType, ['ticket', 'tiket', 'tiket event', 'event', 'ticket_event']) || !empty($p->event_date);
            if (!$isTicket || empty($p->event_date)) continue;
            if (isset($p->is_active) && !$p->is_active) continue;

            try {
                $evDate = $p->event_date instanceof \Carbon\Carbon ? $p->event_date->copy() : \Carbon\Carbon::parse($p->event_date);
                if (!empty($p->event_time)) {
                    $tParts = explode(':', trim($p->event_time));
                    if (count($tParts) >= 2) {
                        $evDate->setTime((int)$tParts[0], (int)$tParts[1], (int)($tParts[2] ?? 0));
                    }
                }
            } catch (\Exception $e) {
                continue;
            }

            // Cek ticket_war_start (waktu mulai war tiket)
            $warStart = null;
            if (!empty($p->ticket_war_start)) {
                try {
                    $warStart = $p->ticket_war_start instanceof \Carbon\Carbon ? $p->ticket_war_start->copy() : \Carbon\Carbon::parse($p->ticket_war_start);
                } catch (\Exception $e) {}
            }

            // Jika Hari-H event sudah lewat, lewati
            if ($evDate->isPast()) {
                continue;
            }

            // Logika Fase:
            // Fase 'war'   : jika ada ticket_war_start dan masih di masa depan (tiket belum rilis)
            // Fase 'event' : jika ticket_war_start sudah lewat atau tidak ada war tiket (menuju Hari-H)
            $phase = ($warStart && $warStart->isFuture()) ? 'war' : 'event';
            $activeTarget = ($phase === 'war') ? $warStart : $evDate;

            $slug = $p->slug ?? null;
            $ctaLink = $slug
                ? url('/' . ($profile->store_slug ?? '') . '/produk/' . $slug)
                : url('/' . ($profile->store_slug ?? '') . '/produk');

            $eventQueue[] = [
                'id'                   => $p->id,
                'name'                 => $p->name ?? $p->title ?? 'Event Spesial',
                'location'             => $p->event_location ?? null,
                'price'                => $p->price ?? 0,
                'event_date_formatted' => $evDate->translatedFormat('d F Y'),
                'event_time_formatted' => !empty($p->event_time) ? $p->event_time : '',
                'war_start_formatted'  => $warStart ? $warStart->translatedFormat('d M Y, H:i') . ' WIB' : '',
                'cta_link'             => $ctaLink,
                'phase'                => $phase,
                'target_iso'           => $activeTarget->toISOString(),
                'war_iso'              => $warStart ? $warStart->toISOString() : null,
                'event_iso'            => $evDate->toISOString(),
                'ev_timestamp'         => $evDate->timestamp,
                'target_timestamp'     => $activeTarget->timestamp,
            ];
        }
    }

    if (empty($eventQueue)) return;

    // Urutkan event berdasarkan waktu event Hari-H terdekat
    usort($eventQueue, function($a, $b) {
        return $a['ev_timestamp'] <=> $b['ev_timestamp'];
    });

    $activeEv = $eventQueue[0];
    $isWarPhase = ($activeEv['phase'] === 'war');

    // Default Headline & Subheadline berdasarkan fase
    $defaultWarHeadline    = 'War Tiket Segera Dimulai!';
    $defaultWarSubheadline = 'Penjualan tiket dibuka pada ' . $activeEv['war_start_formatted'];
    $defaultEvHeadline     = 'Event Akan Segera Dimulai!';
    $defaultEvSubheadline  = 'Dapatkan tiket Anda sebelum kehabisan.';

    $cdHeadline = !empty($config['countdown_headline'])
        ? $config['countdown_headline']
        : ($isWarPhase ? $defaultWarHeadline : $defaultEvHeadline);

    $cdSubheadline = !empty($config['countdown_subheadline'])
        ? $config['countdown_subheadline']
        : ($isWarPhase ? $defaultWarSubheadline : $defaultEvSubheadline);

    $cdCtaEnabled = $config['countdown_cta_enabled'] ?? 1;
    $defaultCtaText = $isWarPhase ? 'Nantikan War Tiket' : 'Beli Tiket Sekarang';
    $cdCtaText = !empty($config['countdown_cta_text']) ? $config['countdown_cta_text'] : $defaultCtaText;

    // Kustomisasi Warna
    $cdBg1        = $config['countdown_bg_color1'] ?? ($config['footer_bg_color1'] ?? '#09121a');
    $cdBg2        = $config['countdown_bg_color2'] ?? ($config['footer_bg_color2'] ?? '#064e3b');
    $cdTitleColor = $config['countdown_title_color'] ?? '#ffffff';
    $cdSubColor   = $config['countdown_subtitle_color'] ?? 'rgba(255,255,255,0.7)';
    $cdNameColor  = $config['countdown_event_name_color'] ?? '#4ade80';
    $cdMetaColor  = $config['countdown_meta_color'] ?? 'rgba(255,255,255,0.6)';
    $cdBoxBg      = $config['countdown_box_bg'] ?? 'rgba(255,255,255,0.08)';
    $cdNumColor   = $config['countdown_num_color'] ?? '#ffffff';
    $cdCtaBg      = $config['countdown_cta_bg'] ?? 'linear-gradient(135deg, #1db349 0%, #84ce00 100%)';
    $cdCtaColor   = $config['countdown_cta_color'] ?? '#ffffff';
@endphp

<section id="t5-countdown-section" class="t5-countdown-wrap">
    <style>
        .t5-countdown-wrap {
            padding: 4rem 1rem;
            background: linear-gradient(135deg, {{ $cdBg1 }} 0%, {{ $cdBg2 }} 100%);
            position: relative;
            overflow: hidden;
        }

        .t5-countdown-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(29, 179, 73, 0.15) 0%, transparent 70%),
                radial-gradient(ellipse at 80% 50%, rgba(132, 206, 0, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .t5-cd-container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            text-align: center;
        }


        .t5-cd-headline {
            font-size: clamp(1.5rem, 4vw, 2.4rem);
            font-weight: 800;
            color: {{ $cdTitleColor }};
            margin: 0 0 0.6rem;
            line-height: 1.2;
        }

        .t5-cd-subheadline {
            font-size: 1rem;
            color: {{ $cdSubColor }};
            margin: 0 0 0.85rem;
            line-height: 1.5;
        }

        .t5-cd-event-name {
            font-size: 1.15rem;
            font-weight: 700;
            color: {{ $cdNameColor }};
            margin-bottom: 0.4rem;
        }

        .t5-cd-event-meta {
            font-size: 0.82rem;
            color: {{ $cdMetaColor }};
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
            background: {{ $cdBoxBg }};
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 16px;
            padding: clamp(0.75rem, 2vw, 1.25rem) clamp(0.75rem, 2.5vw, 1.5rem);
            min-width: clamp(60px, 15vw, 90px);
            backdrop-filter: blur(8px);
            transition: transform 0.15s ease;
        }

        .t5-cd-box:hover {
            transform: translateY(-2px);
        }

        .t5-cd-num {
            font-size: clamp(1.6rem, 5vw, 3rem);
            font-weight: 900;
            color: {{ $cdNumColor }};
            line-height: 1;
            font-variant-numeric: tabular-nums;
            font-family: 'Montserrat', 'Inter', sans-serif;
            transition: transform 0.15s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .t5-cd-num.flip {
            transform: scale(1.08);
        }

        .t5-cd-label {
            font-size: clamp(0.6rem, 1.5vw, 0.72rem);
            font-weight: 600;
            color: {{ $cdSubColor }};
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 0.35rem;
        }

        .t5-cd-separator {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            font-weight: 900;
            color: {{ $cdSubColor }};
            opacity: 0.5;
            align-self: center;
            padding-bottom: 0.75rem;
            animation: t5cdBlink 1s infinite;
        }
        @keyframes t5cdBlink {
            0%,100% { opacity: 0.6; }
            50% { opacity: 0.2; }
        }

        /* CTA Button */
        .t5-cd-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: {{ $cdCtaBg }};
            color: {{ $cdCtaColor }} !important;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.9rem 2.2rem;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(29,179,73,0.35);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .t5-cd-cta:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 12px 35px rgba(29, 179, 73, 0.5);
            color: {{ $cdCtaColor }} !important;
            text-decoration: none;
        }

        .t5-cd-cta svg {
            flex-shrink: 0;
        }

        /* Finished state */
        .t5-cd-finished {
            font-size: 1.5rem;
            font-weight: 800;
            color: {{ $cdNameColor }};
            padding: 1.5rem 0;
        }

        @media (max-width: 480px) {
            .t5-cd-timer { gap: 0.35rem; }
            .t5-cd-separator { font-size: 1.2rem; }
        }
    </style>

    <div class="t5-cd-container">
        {{-- Headline --}}
        <h2 class="t5-cd-headline" id="t5CdHeadline">{{ $cdHeadline }}</h2>
        <p class="t5-cd-subheadline" id="t5CdSubheadline">{{ $cdSubheadline }}</p>

        {{-- Event Name & Meta --}}
        <div class="t5-cd-event-name" id="t5CdEventName">{{ $activeEv['name'] }}</div>
        <div class="t5-cd-event-meta" id="t5CdEventMeta">
            <span id="t5CdDateWrap">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <span id="t5CdDate">{{ $activeEv['event_date_formatted'] }}@if($activeEv['event_time_formatted']) · {{ $activeEv['event_time_formatted'] }}@endif</span>
            </span>
            @if($activeEv['location'])
            <span id="t5CdLocWrap">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span id="t5CdLoc">{{ $activeEv['location'] }}</span>
            </span>
            @endif
            @if($activeEv['price'] > 0)
            <span id="t5CdPriceWrap">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/></svg>
                <span id="t5CdPrice">Rp {{ number_format($activeEv['price'], 0, ',', '.') }}</span>
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
        <div class="t5-cd-finished" id="t5CdFinished" style="display:none;">Event Telah Dimulai!</div>

        {{-- CTA Button --}}
        @if($cdCtaEnabled)
        <a href="{{ $activeEv['cta_link'] }}" class="t5-cd-cta" id="t5CdCta">
            <span id="t5CdCtaText">{{ $cdCtaText }}</span>
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        @endif
    </div>
</section>

<script>
(function(){
    var queue = @json($eventQueue);
    var currentIndex = 0;
    var currentTarget = null;
    var timerInterval = null;

    var elD = document.getElementById('t5CdDays');
    var elH = document.getElementById('t5CdHours');
    var elM = document.getElementById('t5CdMinutes');
    var elS = document.getElementById('t5CdSeconds');
    var elTimer     = document.getElementById('t5CdTimer');
    var elFinish    = document.getElementById('t5CdFinished');
    var elHeadline  = document.getElementById('t5CdHeadline');
    var elSub       = document.getElementById('t5CdSubheadline');
    var elEventName = document.getElementById('t5CdEventName');
    var elDate      = document.getElementById('t5CdDate');
    var elLoc       = document.getElementById('t5CdLoc');
    var elPrice     = document.getElementById('t5CdPrice');
    var elCta       = document.getElementById('t5CdCta');
    var elCtaText   = document.getElementById('t5CdCtaText');

    function pad(n){ return String(n).padStart(2,'0'); }

        function flipAnim(el, newVal) {
            if (el && el.textContent !== newVal) {
                el.textContent = newVal;
                el.classList.remove('flip');
                void el.offsetWidth;
                el.classList.add('flip');
                setTimeout(function () { el.classList.remove('flip'); }, 200);
            }
        }

    function initCurrentEvent() {
        if (!queue || currentIndex >= queue.length) {
            if (elTimer)  elTimer.style.display  = 'none';
            if (elFinish) elFinish.style.display = 'block';
            return false;
        }

        var ev = queue[currentIndex];
        var now = Date.now();

        // Cek jika event memiliki war ticket di masa depan
        if (ev.war_iso) {
            var warTime = new Date(ev.war_iso).getTime();
            if (warTime > now) {
                ev.phase = 'war';
                currentTarget = warTime;
                if (elHeadline)  elHeadline.textContent  = 'War Tiket Segera Dimulai!';
                if (elSub)       elSub.textContent       = 'Penjualan tiket dibuka pada ' + ev.war_start_formatted;
                if (elCtaText)   elCtaText.textContent   = 'Nantikan War Tiket';
                updateEventDom(ev);
                return true;
            }
        }

        // Jika war ticket sudah lewat atau tidak ada, cek Hari-H
        var evTime = new Date(ev.event_iso).getTime();
        if (evTime > now) {
            ev.phase = 'event';
            currentTarget = evTime;
            if (elHeadline)  elHeadline.textContent  = 'Event Akan Segera Dimulai!';
            if (elSub)       elSub.textContent       = 'Dapatkan tiket Anda sebelum kehabisan.';
            if (elCtaText)   elCtaText.textContent   = 'Beli Tiket Sekarang';
            updateEventDom(ev);
            return true;
        }

        // Jika Hari-H sudah lewat, pindah ke event berikutnya di queue
        currentIndex++;
        return initCurrentEvent();
    }

    function updateEventDom(ev) {
        if (elEventName) elEventName.textContent = ev.name;
        if (elDate) {
            elDate.textContent = ev.event_date_formatted + (ev.event_time_formatted ? ' · ' + ev.event_time_formatted : '');
        }
        if (elLoc && ev.location) elLoc.textContent = ev.location;
        if (elPrice && ev.price > 0) elPrice.textContent = 'Rp ' + Number(ev.price).toLocaleString('id-ID');
        if (elCta && ev.cta_link) elCta.href = ev.cta_link;
        if (elTimer) elTimer.style.display = 'flex';
        if (elFinish) elFinish.style.display = 'none';
    }

    function tick() {
        if (!currentTarget) {
            if (!initCurrentEvent()) return;
        }

        var now  = Date.now();
        var diff = currentTarget - now;

        if (diff <= 0) {
            // Target tercapai!
            var ev = queue[currentIndex];
            if (ev && ev.phase === 'war') {
                // War tiket baru saja dibuka! Beralih langsung ke Hari-H event ini
                ev.phase = 'event';
                currentTarget = new Date(ev.event_iso).getTime();
                if (elHeadline)  elHeadline.textContent  = 'Event Akan Segera Dimulai!';
                if (elSub)       elSub.textContent       = 'War tiket telah dibuka! Dapatkan tiket Anda.';
                if (elCtaText)   elCtaText.textContent   = 'Beli Tiket Sekarang';
                return;
            } else {
                // Hari-H event ini selesai, beralih ke event berikutnya
                currentIndex++;
                currentTarget = null;
                if (!initCurrentEvent()) return;
            }
        }

        var d = Math.floor(diff / 86400000);
        var h = Math.floor((diff % 86400000) / 3600000);
        var m = Math.floor((diff % 3600000)  / 60000);
        var s = Math.floor((diff % 60000)    / 1000);

        flipAnim(elD, pad(d));
        flipAnim(elH, pad(h));
        flipAnim(elM, pad(m));
        flipAnim(elS, pad(s));
    }

    if (initCurrentEvent()) {
        tick();
        timerInterval = setInterval(tick, 1000);
    }
})();
</script>