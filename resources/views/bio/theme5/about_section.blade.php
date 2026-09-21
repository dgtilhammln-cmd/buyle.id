@php
    $aboutEnabled = $config['about_enabled'] ?? 1;
@endphp

@if($aboutEnabled)
    @php
        $eyebrow     = !empty($config['about_eyebrow']) ? $config['about_eyebrow'] : 'Real strategies. Real results.';
        $headline    = !empty($config['about_headline']) ? $config['about_headline'] : 'We believe success comes from strategy, not guesswork. Approach combines deep market insight.';
        $description = !empty($config['about_description']) ? $config['about_description'] : 'We focus on creating real, data-driven strategies that deliver measurable results. Every campaign is built on research, insight, and clear objectives—ensuring your marketing.';
        $btnText     = !empty($config['about_btn_text']) ? $config['about_btn_text'] : 'LEARN MORE';
        $btnLink     = !empty($config['about_btn_link']) ? $config['about_btn_link'] : '#products-section';

        $card1Title  = !empty($config['about_card1_title']) ? $config['about_card1_title'] : 'Helping businesses connect, convert, and scale digitally.';
        $card1Desc   = !empty($config['about_card1_desc']) ? $config['about_card1_desc'] : 'We ensure every marketing drives real results increased traffic and engagement to higher and revenue';
        
        $card1Checks = [];
        for ($i = 1; $i <= 5; $i++) {
            $val = $config["about_card1_check{$i}"] ?? null;
            if ($val !== null && trim($val) !== '') {
                $card1Checks[] = trim($val);
            }
        }
        if (empty($card1Checks)) {
            $card1Checks = ['SEO & Search Visibility', 'Data-Driven Strategy'];
        }

        $card1Logo = !empty($config['about_card1_logo_image']) ? asset('storage/' . $config['about_card1_logo_image']) : null;
        $card1BgImg = !empty($config['about_card1_bg_image']) ? asset('storage/' . $config['about_card1_bg_image']) : null;
        $card1Color1 = !empty($config['about_card1_bg_color1']) ? $config['about_card1_bg_color1'] : '#09090b';
        $card1Color2 = !empty($config['about_card1_bg_color2']) ? $config['about_card1_bg_color2'] : '#312e81';
        $card1Opacity = isset($config['about_card1_bg_opacity']) ? ((float)$config['about_card1_bg_opacity'] / 100) : 0.3;

        $card2Img = !empty($config['about_card2_image'])
            ? asset('storage/' . $config['about_card2_image'])
            : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80';

        $card3Stat     = !empty($config['about_card3_stat']) ? $config['about_card3_stat'] : '63%';
        $card3Title    = !empty($config['about_card3_title']) ? $config['about_card3_title'] : 'Business develop growth';
        $card3Desc     = !empty($config['about_card3_desc']) ? $config['about_card3_desc'] : 'We help brands increase visibility, engage the right audience, and convert leads into loyal customers.';
        $card3BtnText  = !empty($config['about_card3_btn_text']) ? $config['about_card3_btn_text'] : 'GET STARTED';
        $card3BtnLink  = !empty($config['about_card3_btn_link']) ? $config['about_card3_btn_link'] : '#products-section';
        $card3BtnBg    = !empty($config['about_card3_btn_bg']) ? $config['about_card3_btn_bg'] : '#1d4ed8';
        $card3BtnColor = !empty($config['about_card3_btn_color']) ? $config['about_card3_btn_color'] : '#ffffff';
    @endphp

    <style>
        .t5-about-section {
            max-width: 1200px;
            margin: 3.5rem auto;
            padding: 0 1.5rem;
            font-family: 'Montserrat', sans-serif;
            color: #0f172a;
        }

        .t5-about-header {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 2.5rem;
            align-items: flex-start;
            margin-bottom: 2.5rem;
        }

        .t5-about-eyebrow {
            font-size: 0.85rem;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 0.6rem;
            letter-spacing: -0.01em;
        }
        .t5-about-eyebrow em {
            font-style: italic;
            font-weight: 700;
            color: #0f172a;
        }

        .t5-about-headline {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.25;
            color: #0f172a;
            letter-spacing: -0.03em;
        }

        .t5-about-right {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            align-items: flex-start;
            padding-top: 0.5rem;
        }

        .t5-about-desc {
            font-size: 0.88rem;
            line-height: 1.6;
            color: #64748b;
            font-weight: 400;
        }

        .t5-about-learn-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.1rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #475569;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }

        .t5-about-learn-btn:hover {
            border-color: #1eb349;
            color: #15803d;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30,179,73,0.15);
        }

        .t5-about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.5rem;
        }

        /* CARD 1: DARK GRADIENT */
        .t5-about-card1 {
            background: linear-gradient(135deg, {{ $card1Color1 }} 0%, {{ $card1Color2 }} 100%);
            border-radius: 20px;
            padding: 2rem;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 340px;
            box-shadow: 0 12px 30px rgba(15,23,42,0.12);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .t5-card1-bg-img {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
            opacity: {{ $card1Opacity }};
            z-index: -1;
            pointer-events: none;
        }

        .t5-card1-content {
            position: relative;
            z-index: 2;
        }

        .t5-card1-logo-img {
            max-height: 48px;
            max-width: 160px;
            object-fit: contain;
            margin-bottom: 1.5rem;
            display: block;
        }

        .t5-card1-icon {
            width: 44px;
            height: 44px;
            color: #a3e635;
            margin-bottom: 1.5rem;
        }

        .t5-card1-title {
            font-size: 1.25rem;
            font-weight: 600;
            line-height: 1.35;
            color: #ffffff;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }

        .t5-card1-desc {
            font-size: 0.78rem;
            line-height: 1.55;
            color: rgba(255,255,255,0.8);
            margin-bottom: 1.5rem;
            font-weight: 300;
        }

        .t5-card1-checks {
            display: flex;
            align-items: center;
            gap: 0.75rem 1rem;
            flex-wrap: wrap;
            margin-top: auto;
            padding-top: 1rem;
            position: relative;
            z-index: 2;
        }

        .t5-card1-check-item {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #ffffff;
        }

        .t5-card1-check-item svg {
            color: #a3e635;
            flex-shrink: 0;
        }

        /* CARD 2: IMAGE */
        .t5-about-card2 {
            border-radius: 20px;
            overflow: hidden;
            min-height: 340px;
            box-shadow: 0 12px 30px rgba(15,23,42,0.08);
            background: #e2e8f0;
        }

        .t5-about-card2 img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .t5-about-card2:hover img {
            transform: scale(1.03);
        }

        /* CARD 3: STAT & CTA */
        .t5-about-card3 {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            min-height: 340px;
            box-shadow: 0 12px 30px rgba(15,23,42,0.04);
        }

        .t5-card3-stat {
            font-size: 3.2rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
            letter-spacing: -0.04em;
            margin-bottom: 0.35rem;
        }

        .t5-card3-title {
            font-size: 0.95rem;
            font-weight: 500;
            color: #475569;
            margin-bottom: 1.5rem;
        }

        .t5-card3-desc {
            font-size: 0.8rem;
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 1.5rem;
            font-weight: 400;
        }

        .t5-card3-btn-wrap {
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .t5-card3-main-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.65rem 1.4rem;
            background: {{ $card3BtnBg }};
            color: {{ $card3BtnColor }};
            border-radius: 999px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .t5-card3-main-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .t5-card3-arrow-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: {{ $card3BtnBg }};
            color: {{ $card3BtnColor }};
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .t5-card3-arrow-btn:hover {
            opacity: 0.9;
            transform: scale(1.08);
        }

        @media (max-width: 900px) {
            .t5-about-header {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
            .t5-about-headline {
                font-size: 1.5rem;
            }
            .t5-about-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }
            .t5-about-card1, .t5-about-card2, .t5-about-card3 {
                min-height: auto;
            }
            .t5-about-card2 {
                height: 260px;
            }
        }
    </style>

    <section class="t5-about-section" id="about-section">
        <div class="t5-about-header">
            <div>
                <div class="t5-about-eyebrow">{!! $eyebrow !!}</div>
                <h2 class="t5-about-headline">{{ $headline }}</h2>
            </div>
            <div class="t5-about-right">
                <p class="t5-about-desc">{{ $description }}</p>
                @if(!empty($btnText))
                    <a href="{{ $btnLink }}" class="t5-about-learn-btn">
                        <span>{{ $btnText }}</span>
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <div class="t5-about-grid">
            {{-- CARD 1: DARK GRADIENT --}}
            <div class="t5-about-card1">
                @if(!empty($card1BgImg))
                    <img src="{{ $card1BgImg }}" class="t5-card1-bg-img" alt="Background Card 1">
                @endif
                <div class="t5-card1-content">
                    @if(!empty($card1Logo))
                        <img src="{{ $card1Logo }}" class="t5-card1-logo-img" alt="Logo">
                    @else
                        <svg class="t5-card1-icon" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="16" cy="16" r="8" fill="#a3e635"/>
                            <circle cx="32" cy="32" r="8" fill="#a3e635"/>
                            <circle cx="32" cy="16" r="4" fill="#a3e635"/>
                            <circle cx="16" cy="32" r="4" fill="#a3e635"/>
                        </svg>
                    @endif
                    <h3 class="t5-card1-title">{{ $card1Title }}</h3>
                    <p class="t5-card1-desc">{{ $card1Desc }}</p>
                </div>
                <div class="t5-card1-checks">
                    @foreach($card1Checks as $checkItem)
                        <span class="t5-card1-check-item">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            {{ $checkItem }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- CARD 2: IMAGE --}}
            <div class="t5-about-card2">
                <img src="{{ $card2Img }}" alt="About Image" loading="lazy">
            </div>

            {{-- CARD 3: STAT & CTA --}}
            <div class="t5-about-card3">
                <div class="t5-card3-stat">{{ $card3Stat }}</div>
                <div class="t5-card3-title">{{ $card3Title }}</div>
                <p class="t5-card3-desc">{{ $card3Desc }}</p>
                <div class="t5-card3-btn-wrap">
                    @if(!empty($card3BtnText))
                        <a href="{{ $card3BtnLink }}" class="t5-card3-main-btn">{{ $card3BtnText }}</a>
                        <a href="{{ $card3BtnLink }}" class="t5-card3-arrow-btn">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif

