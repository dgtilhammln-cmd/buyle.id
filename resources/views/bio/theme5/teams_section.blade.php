{{-- ════════════════════════════════════════════════════════════════
     THEME 5 — TEAMS / TIM KAMI CAROUSEL SECTION (STRICTLY THEME 5)
     buyle.id | HVM Digital
════════════════════════════════════════════════════════════════ --}}
@php
    $teamsEnabled = $config['teams_enabled'] ?? 1;
@endphp

@if($teamsEnabled)
    @php
        $tHeadline   = !empty($config['teams_headline']) ? $config['teams_headline'] : 'Temui Tim Profesional Kami';
        $tDesc       = !empty($config['teams_description']) ? $config['teams_description'] : 'Talenta terbaik kami yang berdedikasi tinggi untuk memberikan hasil luar biasa.';

        $teams = [];
        $hasCustomMember = false;
        for ($i = 1; $i <= 10; $i++) {
            if (!empty($config["team_member_{$i}_name"]) || !empty($config["team_member_{$i}_photo"])) {
                $hasCustomMember = true;
                break;
            }
        }

        for ($i = 1; $i <= 10; $i++) {
            $photo     = $config["team_member_{$i}_photo"] ?? null;
            $name      = $config["team_member_{$i}_name"] ?? null;
            $position  = $config["team_member_{$i}_position"] ?? null;
            $bio       = $config["team_member_{$i}_bio"] ?? null;
            $gradStart = $config["team_member_{$i}_grad_start"] ?? '#0052D4';
            $gradEnd   = $config["team_member_{$i}_grad_end"] ?? '#4364F7';

            if ($hasCustomMember) {
                if (!empty($name) || !empty($photo)) {
                    $teams[] = [
                        'id' => $i,
                        'name' => $name ?: "Anggota Tim #{$i}",
                        'position' => $position ?: 'Spesialis',
                        'bio' => $bio ?: 'Berdedikasi untuk memberikan layanan profesional terbaik untuk Anda.',
                        'photo' => $photo ? asset('storage/' . $photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=800&q=80',
                        'grad_start' => $gradStart,
                        'grad_end' => $gradEnd,
                    ];
                }
            } else {
                // Default samples if all slots are empty
                $samples = [
                    1 => [
                        'name' => 'Manuel Ravier',
                        'position' => 'Performance Coach',
                        'bio' => 'As CEO and co-founder, Manuel leads the overall vision and strategic direction, specializing in leadership performance and growth.',
                        'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=800&q=80',
                    ],
                    2 => [
                        'name' => 'David Sequiera',
                        'position' => 'Recovery Specialist',
                        'bio' => 'As co-founder, David manages operations and client recovery systems, ensuring all workflows run seamlessly and deliver high-impact results.',
                        'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=800&q=80',
                    ],
                    3 => [
                        'name' => 'Sarah Jenkins',
                        'position' => 'Head of Creative',
                        'bio' => 'Sarah shapes the visual identity and brand experience, crafting compelling aesthetic designs that connect deeply with audiences worldwide.',
                        'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80',
                    ],
                    4 => [
                        'name' => 'Marcus Vance',
                        'position' => 'Tech Lead & Developer',
                        'bio' => 'Marcus oversees system architecture and software engineering, building scalable, high-performance web applications and digital tools.',
                        'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=800&q=80',
                    ]
                ];
                if (isset($samples[$i])) {
                    $teams[] = [
                        'id' => $i,
                        'name' => $samples[$i]['name'],
                        'position' => $samples[$i]['position'],
                        'bio' => $samples[$i]['bio'],
                        'photo' => $samples[$i]['photo'],
                        'grad_start' => $gradStart,
                        'grad_end' => $gradEnd,
                    ];
                }
            }
        }
    @endphp

    @if(count($teams) > 0)
        <style>
            /* ═════════════════════════════════════════
               TEMA 5 — TEAMS SECTION STYLES
            ═════════════════════════════════════════ */
            .t5-teams-section {
                max-width: 1200px;
                margin: 4.5rem auto;
                padding: 0 1.5rem;
                font-family: 'Montserrat', sans-serif;
                color: #0f172a;
                position: relative;
            }

            .t5-teams-header {
                text-align: center;
                max-width: 760px;
                margin: 0 auto 2.5rem auto;
            }

            .t5-teams-headline {
                font-size: clamp(1.35rem, 2.8vw, 1.75rem);
                font-weight: 700;
                line-height: 1.3;
                color: #0f172a;
                letter-spacing: -0.02em;
                margin-bottom: 0.6rem;
                text-wrap: balance;
            }

            .t5-teams-desc {
                font-size: 0.88rem;
                color: #64748b;
                line-height: 1.6;
            }

            /* CAROUSEL WRAPPER & NAV BUTTONS */
            .t5-teams-carousel-wrap {
                position: relative;
                width: 100%;
            }

            .t5-teams-track {
                display: flex;
                gap: 1.25rem;
                overflow-x: auto;
                scroll-behavior: smooth;
                scroll-snap-type: x mandatory;
                padding: 0.75rem 0.25rem 1.5rem 0.25rem;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .t5-teams-track::-webkit-scrollbar {
                display: none;
            }

            /* CARD DESIGN (DESKTOP: 4 CARDS PER ROW) */
            .t5-team-card {
                flex: 0 0 calc((100% - (3 * 1.25rem)) / 4);
                min-width: 240px;
                aspect-ratio: 3 / 4;
                border-radius: 20px;
                overflow: hidden;
                position: relative;
                scroll-snap-align: start;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
                transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1);
                cursor: pointer;
                user-select: none;
                background: #0f172a;
            }

            .t5-team-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 16px 40px rgba(15, 23, 42, 0.16);
            }

            /* PHOTO & BASE OVERLAY */
            .t5-team-photo-wrap {
                width: 100%;
                height: 100%;
                position: absolute;
                inset: 0;
            }

            .t5-team-photo {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .t5-team-card:hover .t5-team-photo {
                transform: scale(1.05);
            }

            .t5-team-base-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.4) 45%, transparent 100%);
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
                padding: 1.25rem 1.1rem;
                color: #ffffff;
                transition: opacity 0.35s ease;
            }

            .t5-team-base-name {
                font-size: 1.1rem;
                font-weight: 700;
                line-height: 1.25;
                color: #ffffff;
                letter-spacing: -0.01em;
                margin-bottom: 0.2rem;
            }

            .t5-team-base-pos {
                font-size: 0.78rem;
                font-weight: 500;
                color: rgba(255, 255, 255, 0.85);
                line-height: 1.3;
            }

            /* HOVER STATE GRADIENT CARD WITH PATTERN (CARD 2 IN REFERENCE) */
            .t5-team-hover-overlay {
                position: absolute;
                inset: 0;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 1.4rem 1.25rem;
                color: #ffffff;
                box-sizing: border-box;
                z-index: 2;
            }

            /* PATTERN GRID OVERLAY */
            .t5-team-pattern {
                position: absolute;
                inset: 0;
                background-image: 
                    linear-gradient(to right, rgba(255, 255, 255, 0.08) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
                background-size: 20px 20px;
                pointer-events: none;
                z-index: 0;
            }

            /* PATTERN CROSSHAIR / CORNER ACCENTS */
            .t5-team-hover-overlay::before {
                content: '';
                position: absolute;
                top: 1rem;
                right: 1rem;
                width: 30px;
                height: 30px;
                background: radial-gradient(circle, rgba(255,255,255,0.3) 1.5px, transparent 1.5px);
                background-size: 8px 8px;
                opacity: 0.6;
            }

            .t5-team-card:hover .t5-team-hover-overlay,
            .t5-team-card.is-active .t5-team-hover-overlay {
                opacity: 1;
                pointer-events: auto;
            }

            .t5-team-hover-top {
                position: relative;
                z-index: 1;
            }

            .t5-team-hover-name {
                font-size: 1.25rem;
                font-weight: 700;
                line-height: 1.2;
                color: #ffffff;
                letter-spacing: -0.01em;
                margin-bottom: 0.25rem;
            }

            .t5-team-hover-pos {
                font-size: 0.8rem;
                font-weight: 500;
                color: rgba(255, 255, 255, 0.85);
            }

            .t5-team-hover-bio {
                position: relative;
                z-index: 1;
                font-size: 0.8rem;
                line-height: 1.55;
                color: rgba(255, 255, 255, 0.95);
                font-weight: 400;
                display: -webkit-box;
                -webkit-line-clamp: 6;
                -webkit-box-orient: vertical;
                overflow: hidden;
                margin-top: auto;
            }

            /* CIRCULAR NAV BUTTONS */
            .t5-teams-nav-btn {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 44px;
                height: 44px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(8px);
                border: 1px solid rgba(226, 232, 240, 0.8);
                color: #0f172a;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 10;
                box-shadow: 0 4px 16px rgba(15, 23, 42, 0.12);
                transition: all 0.25s ease;
            }

            .t5-teams-nav-btn:hover {
                background: #1eb349;
                color: #ffffff;
                border-color: #1eb349;
                box-shadow: 0 6px 20px rgba(30, 179, 73, 0.35);
                transform: translateY(-50%) scale(1.08);
            }

            .t5-teams-prev {
                left: -18px;
            }

            .t5-teams-next {
                right: -18px;
            }

            /* RESPONSIVE BREAKPOINTS (MOBILE FRIENDLY & COMPACT) */
            @media (max-width: 991px) {
                .t5-team-card {
                    flex: 0 0 calc((100% - 1.25rem) / 2);
                    min-width: 220px;
                }
                .t5-teams-prev { left: -8px; }
                .t5-teams-next { right: -8px; }
            }

            @media (max-width: 576px) {
                .t5-teams-section {
                    margin: 3rem auto;
                    padding: 0 1rem;
                }
                .t5-teams-header {
                    margin-bottom: 1.75rem;
                }
                .t5-team-card {
                    flex: 0 0 calc(78% - 0.5rem);
                    min-width: 210px;
                    aspect-ratio: 3 / 4;
                    border-radius: 16px;
                }
                .t5-teams-track {
                    gap: 0.85rem;
                    padding-bottom: 1rem;
                }
                .t5-teams-nav-btn {
                    width: 36px;
                    height: 36px;
                }
                .t5-teams-prev { left: -4px; }
                .t5-teams-next { right: -4px; }
            }
        </style>

        <section class="t5-teams-section">
            <div class="t5-teams-header">
                <h2 class="t5-teams-headline">{{ $tHeadline }}</h2>
                @if(!empty($tDesc))
                    <p class="t5-teams-desc">{{ $tDesc }}</p>
                @endif
            </div>

            <div class="t5-teams-carousel-wrap">
                <button type="button" class="t5-teams-nav-btn t5-teams-prev" onclick="scrollT5Teams('left')" aria-label="Sebelumnya">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>

                <div class="t5-teams-track" id="t5TeamsTrack">
                    @foreach($teams as $tItem)
                        <div class="t5-team-card" onclick="toggleT5TeamCard(this)">
                            <div class="t5-team-photo-wrap">
                                <img src="{{ $tItem['photo'] }}" alt="{{ $tItem['name'] }}" loading="lazy" class="t5-team-photo" draggable="false">
                            </div>
                            
                            {{-- Normal Bottom Label --}}
                            <div class="t5-team-base-overlay">
                                <div class="t5-team-base-name">{{ $tItem['name'] }}</div>
                                <div class="t5-team-base-pos">{{ $tItem['position'] }}</div>
                            </div>

                            {{-- Hover / Touch Active Gradient Card --}}
                            <div class="t5-team-hover-overlay" style="background: linear-gradient(135deg, {{ $tItem['grad_start'] }} 0%, {{ $tItem['grad_end'] }} 100%);">
                                <div class="t5-team-pattern"></div>
                                
                                <div class="t5-team-hover-top">
                                    <div class="t5-team-hover-name">{{ $tItem['name'] }}</div>
                                    <div class="t5-team-hover-pos">{{ $tItem['position'] }}</div>
                                </div>

                                <div class="t5-team-hover-bio">
                                    {{ \Illuminate\Support\Str::words($tItem['bio'], 30, '...') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="t5-teams-nav-btn t5-teams-next" onclick="scrollT5Teams('right')" aria-label="Berikutnya">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </section>

        <script>
            function scrollT5Teams(dir) {
                var track = document.getElementById('t5TeamsTrack');
                if (!track) return;
                var scrollAmount = track.clientWidth * 0.75;
                if (dir === 'left') {
                    track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                } else {
                    track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                }
            }

            function toggleT5TeamCard(cardEl) {
                // For touch screens: toggle active state on click
                var isAlreadyActive = cardEl.classList.contains('is-active');
                document.querySelectorAll('.t5-team-card').forEach(function(c) {
                    c.classList.remove('is-active');
                });
                if (!isAlreadyActive) {
                    cardEl.classList.add('is-active');
                }
            }
        </script>
    @endif
@endif
