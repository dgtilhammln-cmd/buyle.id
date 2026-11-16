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

            // Poster Landscape (16:9)
            $posterImg = null;
            if (!empty($p->og_image)) {
                $posterImg = \Illuminate\Support\Str::startsWith($p->og_image, 'http') ? $p->og_image : asset('storage/' . $p->og_image);
            } elseif (!empty($p->image)) {
                $posterImg = \Illuminate\Support\Str::startsWith($p->image, 'http') ? $p->image : asset('storage/' . $p->image);
            } elseif (!empty($p->image_url)) {
                $posterImg = $p->image_url;
            } else {
                $posterImg = asset('images/buyle-placeholder.svg');
            }

            $hasDiscount = $p->sale_price && $p->sale_price < $p->price;
            $effectivePrice = $hasDiscount ? $p->sale_price : $p->price;
            $origPrice = $hasDiscount ? $p->price : null;
            $discountPercent = $hasDiscount ? round((($p->price - $p->sale_price) / $p->price) * 100) : 0;
            $ratingVal = method_exists($p, 'getEffectiveRating') ? $p->getEffectiveRating() : null;
            $isVerified = isset($profile) && method_exists($profile, 'isVerified') ? $profile->isVerified() : false;

            $eventQueue[] = [
                'id'                   => $p->id,
                'name'                 => $p->name ?? $p->title ?? 'Event Spesial',
                'location'             => $p->event_location ?? null,
                'price'                => $p->price ?? 0,
                'poster_url'           => $posterImg,
                'has_discount'         => $hasDiscount,
                'effective_price'      => $effectivePrice,
                'effective_price_fmt'  => 'Rp ' . number_format($effectivePrice, 0, ',', '.'),
                'orig_price'           => $origPrice,
                'orig_price_fmt'       => $origPrice ? 'Rp ' . number_format($origPrice, 0, ',', '.') : '',
                'discount_percent'     => $discountPercent,
                'rating'               => $ratingVal,
                'is_verified'          => $isVerified,
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
            padding: 3.5rem 1rem 4rem;
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
            max-width: 760px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .t5-cd-headline {
            font-size: clamp(1.4rem, 3.5vw, 2.2rem);
            font-weight: 800;
            color: {{ $cdTitleColor }};
            margin: 0 0 0.5rem;
            line-height: 1.25;
            letter-spacing: -0.01em;
        }

        .t5-cd-subheadline {
            font-size: 0.95rem;
            color: {{ $cdSubColor }};
            margin: 0 0 1.5rem;
            line-height: 1.5;
        }

        /* Unified Event Showcase Card */
        .t5-cd-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: left;
        }

        .t5-cd-card:hover {
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.5);
            border-color: rgba(255, 255, 255, 0.22);
        }

        .t5-cd-poster-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            background: #0f172a;
            overflow: hidden;
            display: block;
        }

        .t5-cd-poster-img {
            width: 100%;
            height: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .t5-cd-card:hover .t5-cd-poster-img {
            transform: scale(1.03);
        }

        .t5-cd-poster-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0.5) 100%);
            pointer-events: none;
        }

        .t5-cd-badge-discount {
            position: absolute;
            top: 1rem;
            left: 1rem;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 800;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
            z-index: 2;
        }

        .t5-cd-poster-pills {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            z-index: 2;
        }

        .t5-cd-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            backdrop-filter: blur(8px);
        }

        .t5-cd-pill-rating {
            background: rgba(15, 23, 42, 0.8);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .t5-cd-pill-verified {
            background: rgba(16, 185, 129, 0.85);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .t5-cd-card-body {
            padding: 1.75rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .t5-cd-event-name {
            font-size: clamp(1.2rem, 3vw, 1.6rem);
            font-weight: 800;
            color: {{ $cdNameColor }};
            margin: 0 0 0.5rem;
            line-height: 1.3;
        }

        .t5-cd-event-name a {
            color: inherit;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .t5-cd-event-name a:hover {
            opacity: 0.85;
        }

        .t5-cd-event-meta {
            font-size: 0.85rem;
            color: {{ $cdMetaColor }};
            margin-bottom: 1.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.15rem;
            flex-wrap: wrap;
        }

        .t5-cd-event-meta span {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .t5-cd-price-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.08);
            padding: 0.3rem 0.85rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .t5-cd-price-old {
            font-size: 0.78rem;
            text-decoration: line-through;
            color: rgba(255, 255, 255, 0.5);
        }

        .t5-cd-price-current {
            font-size: 0.95rem;
            font-weight: 800;
            color: #4ade80;
        }

        /* Countdown Timer Boxes */
        .t5-cd-timer {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: clamp(0.5rem, 2vw, 1.25rem);
            margin-bottom: 2rem;
            width: 100%;
            flex-wrap: nowrap;
        }

        .t5-cd-box {
            background: {{ $cdBoxBg }};
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 16px;
            padding: clamp(0.75rem, 2vw, 1.25rem) clamp(0.75rem, 2.5vw, 1.5rem);
            min-width: clamp(58px, 14vw, 86px);
            backdrop-filter: blur(8px);
            transition: transform 0.15s ease;
        }

        .t5-cd-box:hover {
            transform: translateY(-2px);
        }

        .t5-cd-num {
            font-size: clamp(1.5rem, 4.5vw, 2.6rem);
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
            font-size: clamp(0.6rem, 1.4vw, 0.72rem);
            font-weight: 600;
            color: {{ $cdSubColor }};
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 0.35rem;
        }

        .t5-cd-separator {
            font-size: clamp(1.4rem, 3.5vw, 2.2rem);
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
        .t5-cd-cta-wrap {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .t5-cd-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: {{ $cdCtaBg }};
            color: {{ $cdCtaColor }} !important;
            font-weight: 700;
            font-size: 0.98rem;
            padding: 0.85rem 2.2rem;
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
            font-size: 1.4rem;
            font-weight: 800;
            color: {{ $cdNameColor }};
            padding: 1.5rem 0;
        }

        @media (max-width: 640px) {
            .t5-countdown-wrap {
                padding: 2.5rem 0.85rem 3rem;
            }
            .t5-cd-card {
                border-radius: 18px;
            }
            .t5-cd-card-body {
                padding: 1.25rem 1rem;
            }
            .t5-cd-event-meta {
                gap: 0.65rem;
                font-size: 0.78rem;
                margin-bottom: 1.25rem;
            }
            .t5-cd-timer {
                gap: 0.25rem;
                margin-bottom: 1.4rem;
            }
            .t5-cd-separator {
                font-size: 1.1rem;
                padding-bottom: 0.5rem;
            }
            .t5-cd-box {
                padding: 0.65rem 0.5rem;
                border-radius: 12px;
                min-width: 52px;
            }
            .t5-cd-num {
                font-size: 1.4rem;
            }
            .t5-cd-cta {
                width: 100%;
                justify-content: center;
                padding: 0.85rem 1.25rem;
            }
        }
    </style>

    <div class="t5-cd-container">
        {{-- Headline & Subheadline --}}
        <h2 class="t5-cd-headline" id="t5CdHeadline">{{ $cdHeadline }}</h2>
        <p class="t5-cd-subheadline" id="t5CdSubheadline">{{ $cdSubheadline }}</p>

        {{-- Unified Event Showcase Card --}}
        <div class="t5-cd-card">
            {{-- Poster Landscape 16:9 Image --}}
            <a href="{{ $activeEv['cta_link'] }}" class="t5-cd-poster-wrap" id="t5CdPosterLink" aria-label="{{ $activeEv['name'] }}">
                <img src="{{ $activeEv['poster_url'] }}" alt="{{ $activeEv['name'] }}" id="t5CdPosterImg" class="t5-cd-poster-img" loading="lazy"
                    onerror="this.onerror=null;this.src='{{ asset('images/buyle-placeholder.svg') }}';">
                <div class="t5-cd-poster-overlay"></div>

                <span class="t5-cd-badge-discount" id="t5CdDiscountBadge" style="{{ $activeEv['has_discount'] ? '' : 'display:none;' }}">
                    -{{ $activeEv['discount_percent'] }}%
                </span>

                <div class="t5-cd-poster-pills">
                    <span class="t5-cd-pill t5-cd-pill-rating" id="t5CdRatingPill" style="{{ ($activeEv['rating'] !== null && $activeEv['rating'] > 0) ? '' : 'display:none;' }}">
                        <svg width="12" height="12" fill="#f59e0b" stroke="#f59e0b" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span id="t5CdRatingNum">{{ number_format($activeEv['rating'] ?? 0, 1) }}</span>
                    </span>
                    @if($activeEv['is_verified'])
                        <span class="t5-cd-pill t5-cd-pill-verified">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Verified
                        </span>
                    @endif
                </div>
            </a>

            {{-- Card Body --}}
            <div class="t5-cd-card-body">
                <h3 class="t5-cd-event-name" id="t5CdEventName">
                    <a href="{{ $activeEv['cta_link'] }}" id="t5CdEventNameLink">{{ $activeEv['name'] }}</a>
                </h3>

                {{-- Event Meta (Date, Location, Price) --}}
                <div class="t5-cd-event-meta" id="t5CdEventMeta">
                    <span id="t5CdDateWrap">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span id="t5CdDate">{{ $activeEv['event_date_formatted'] }}@if($activeEv['event_time_formatted']) · {{ $activeEv['event_time_formatted'] }}@endif</span>
                    </span>
                    @if($activeEv['location'])
                        <span id="t5CdLocWrap">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span id="t5CdLoc">{{ $activeEv['location'] }}</span>
                        </span>
                    @endif
                    <span id="t5CdPriceWrap" class="t5-cd-price-badge">
                        <span class="t5-cd-price-old" id="t5CdPriceOld" style="{{ ($activeEv['has_discount'] && $activeEv['orig_price']) ? '' : 'display:none;' }}">
                            {{ $activeEv['orig_price_fmt'] }}
                        </span>
                        <span class="t5-cd-price-current" id="t5CdPrice">{{ $activeEv['effective_price_fmt'] }}</span>
                    </span>
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
                    <div class="t5-cd-cta-wrap">
                        <a href="{{ $activeEv['cta_link'] }}" class="t5-cd-cta" id="t5CdCta">
                            <span id="t5CdCtaText">{{ $cdCtaText }}</span>
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
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
    var elEventName = document.getElementById('t5CdEventNameLink');
    var elPosterImg = document.getElementById('t5CdPosterImg');
    var elPosterLink = document.getElementById('t5CdPosterLink');
    var elDiscountBadge = document.getElementById('t5CdDiscountBadge');
    var elRatingPill = document.getElementById('t5CdRatingPill');
    var elRatingNum = document.getElementById('t5CdRatingNum');
    var elDate      = document.getElementById('t5CdDate');
    var elLoc       = document.getElementById('t5CdLoc');
    var elLocWrap   = document.getElementById('t5CdLocWrap');
    var elPrice     = document.getElementById('t5CdPrice');
    var elPriceOld  = document.getElementById('t5CdPriceOld');
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
        if (elEventName) {
            elEventName.textContent = ev.name;
            if (ev.cta_link) elEventName.href = ev.cta_link;
        }
        if (elPosterImg && ev.poster_url) elPosterImg.src = ev.poster_url;
        if (elPosterLink && ev.cta_link) elPosterLink.href = ev.cta_link;

        if (elDiscountBadge) {
            if (ev.has_discount && ev.discount_percent > 0) {
                elDiscountBadge.textContent = '-' + ev.discount_percent + '%';
                elDiscountBadge.style.display = 'inline-block';
            } else {
                elDiscountBadge.style.display = 'none';
            }
        }
        if (elRatingPill) {
            if (ev.rating && ev.rating > 0) {
                elRatingPill.style.display = 'inline-flex';
                if (elRatingNum) elRatingNum.textContent = Number(ev.rating).toFixed(1);
            } else {
                elRatingPill.style.display = 'none';
            }
        }
        if (elDate) {
            elDate.textContent = ev.event_date_formatted + (ev.event_time_formatted ? ' · ' + ev.event_time_formatted : '');
        }
        if (elLocWrap) {
            if (ev.location) {
                elLocWrap.style.display = 'inline-flex';
                if (elLoc) elLoc.textContent = ev.location;
            } else {
                elLocWrap.style.display = 'none';
            }
        }
        if (elPrice && ev.effective_price_fmt) {
            elPrice.textContent = ev.effective_price_fmt;
        }
        if (elPriceOld) {
            if (ev.has_discount && ev.orig_price_fmt) {
                elPriceOld.textContent = ev.orig_price_fmt;
                elPriceOld.style.display = 'inline';
            } else {
                elPriceOld.style.display = 'none';
            }
        }
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
