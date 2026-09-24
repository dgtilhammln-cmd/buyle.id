{{-- ════════════════════════════════════════════════════════════════
     THEME 5 — CLIENT LOGOS / BRAND PARTNERS SECTION (STRICTLY THEME 5)
     buyle.id | HVM Digital
 ════════════════════════════════════════════════════════════════ --}}
@php
    $logosEnabled = $config['client_logos_enabled'] ?? 1;
@endphp

@if($logosEnabled)
    @php
        $headline = !empty($config['client_logos_headline']) 
            ? $config['client_logos_headline'] 
            : 'Dipercaya Oleh Brand & Mitra Terkemuka';

        $logos = [];
        for ($i = 1; $i <= 10; $i++) {
            $img  = $config["client_logo_{$i}_image"] ?? null;
            $name = $config["client_logo_{$i}_name"] ?? null;
            $link = $config["client_logo_{$i}_link"] ?? null;

            if (!empty($img) || !empty($name)) {
                $logos[] = [
                    'id'    => $i,
                    'name'  => $name ?: "Client Partner #{$i}",
                    'image' => $img ? asset('storage/' . $img) : null,
                    'link'  => $link ?: '#'
                ];
            }
        }

        // Default sample client logos if all slots are empty
        if (empty($logos)) {
            $samples = [
                ['name' => 'TechCorp Solutions', 'icon' => '⚡', 'bg' => '#3b82f6'],
                ['name' => 'Apex Digital', 'icon' => '🚀', 'bg' => '#10b981'],
                ['name' => 'Nexus Media', 'icon' => '🌐', 'bg' => '#8b5cf6'],
                ['name' => 'Starlight Media', 'icon' => '⭐', 'bg' => '#f59e0b'],
                ['name' => 'Vanguard Global', 'icon' => '🛡️', 'bg' => '#ec4899'],
                ['name' => 'Horizon Studio', 'icon' => '🏔️', 'bg' => '#06b6d4'],
            ];
            foreach ($samples as $idx => $s) {
                $logos[] = [
                    'id' => $idx + 1,
                    'name' => $s['name'],
                    'image' => null,
                    'sample_icon' => $s['icon'],
                    'sample_bg' => $s['bg'],
                    'link' => '#'
                ];
            }
        }
    @endphp

    @if(count($logos) > 0)
        <style>
            /* ═════════════════════════════════════════
               TEMA 5 — CLIENT LOGOS SECTION STYLES
            ═════════════════════════════════════════ */
            .t5-logos-section {
                max-width: 1200px;
                margin: 2.5rem auto 3.5rem auto;
                padding: 0 1.5rem;
                font-family: 'Montserrat', sans-serif;
                color: #0f172a;
                position: relative;
            }

            .t5-logos-header {
                text-align: center;
                max-width: 680px;
                margin: 0 auto 2rem auto;
            }

            .t5-logos-headline {
                font-size: clamp(1.35rem, 2.8vw, 1.75rem);
                font-weight: 700;
                line-height: 1.3;
                color: #0f172a;
                letter-spacing: -0.02em;
                margin-bottom: 0.5rem;
                text-wrap: balance;
                margin-left: auto;
                margin-right: auto;
            }

            /* Marquee Track Container */
            .t5-logos-container {
                position: relative;
                width: 100%;
                overflow: hidden;
                padding: 0.75rem 0;
                /* Soft fade gradient edges */
                mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
                -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
            }

            .t5-logos-track {
                display: flex;
                align-items: center;
                gap: 1.5rem;
                width: max-content;
                animation: t5LogosScroll 28s linear infinite;
            }

            .t5-logos-track:hover {
                animation-play-state: paused;
            }

            @keyframes t5LogosScroll {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }

            /* Individual Logo Card */
            .t5-logo-card {
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1.4rem;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 16px;
                box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
                text-decoration: none !important;
                color: #0f172a;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                flex-shrink: 0;
                user-select: none;
                filter: grayscale(100%);
                opacity: 0.75;
            }

            .t5-logo-card:hover {
                filter: grayscale(0%);
                opacity: 1;
                transform: translateY(-3px) scale(1.04);
                border-color: #1eb349;
                box-shadow: 0 10px 25px rgba(30, 179, 73, 0.15);
            }

            .t5-logo-img {
                height: 34px;
                max-width: 140px;
                object-fit: contain;
                display: block;
            }

            .t5-logo-icon-badge {
                width: 32px;
                height: 32px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                color: #ffffff;
                flex-shrink: 0;
            }

            .t5-logo-name {
                font-size: 0.88rem;
                font-weight: 600;
                color: #334155;
                white-space: nowrap;
                letter-spacing: -0.01em;
            }

            /* Responsive Adjustments */
            @media (max-width: 768px) {
                .t5-logos-section {
                    margin: 2rem auto 2.5rem auto;
                }
                .t5-logos-track {
                    gap: 1rem;
                    animation-duration: 20s;
                }
                .t5-logo-card {
                    padding: 0.6rem 1.1rem;
                    border-radius: 14px;
                }
                .t5-logo-img {
                    height: 28px;
                    max-width: 110px;
                }
                .t5-logo-name {
                    font-size: 0.82rem;
                }
            }
        </style>

        <section class="t5-logos-section" id="t5-logos-section">
            {{-- Header --}}
            <div class="t5-logos-header">
                <h2 class="t5-logos-headline">{{ $headline }}</h2>
            </div>

            {{-- Ticker Marquee Track --}}
            <div class="t5-logos-container">
                <div class="t5-logos-track">
                    {{-- Loop twice for infinite seamless scroll --}}
                    @for ($repeat = 0; $repeat < 2; $repeat++)
                        @foreach($logos as $logo)
                            @php
                                $hasUrl = !empty($logo['link']) && $logo['link'] !== '#';
                                $tag = $hasUrl ? 'a' : 'div';
                                $hrefAttr = $hasUrl ? "href='{$logo['link']}' target='_blank' rel='noopener'" : "";
                            @endphp

                            <{!! $tag !!} {!! $hrefAttr !!} class="t5-logo-card" title="{{ $logo['name'] }}">
                                @if(!empty($logo['image']))
                                    <img src="{{ $logo['image'] }}" alt="{{ $logo['name'] }}" class="t5-logo-img" loading="lazy" draggable="false">
                                @elseif(!empty($logo['sample_icon']))
                                    <div class="t5-logo-icon-badge" style="background: {{ $logo['sample_bg'] }};">
                                        {{ $logo['sample_icon'] }}
                                    </div>
                                @endif
                                <span class="t5-logo-name">{{ $logo['name'] }}</span>
                            </{!! $tag !!}>
                        @endforeach
                    @endfor
                </div>
            </div>
        </section>
    @endif
@endif
