@php
    $servicesEnabled = $config['services_enabled'] ?? 1;
@endphp

@if($servicesEnabled)
    @php
        $eyebrow     = !empty($config['services_eyebrow']) ? $config['services_eyebrow'] : 'MISSION';
        $headline    = !empty($config['services_headline']) ? $config['services_headline'] : "We've orchestrated Intelligence.";
        $description = !empty($config['services_description']) ? $config['services_description'] : 'Metafore brings clarity, not complexity - uniting every agent into one adaptive system that learns, acts, and evolves across your enterprise.';
        $btnText     = !empty($config['services_btn_text']) ? $config['services_btn_text'] : 'Explore More';
        $btnLink     = !empty($config['services_btn_link']) ? $config['services_btn_link'] : '#services-section';

        $defaultServices = [
            [
                'number' => '01.',
                'title'  => 'Amplify Intelligence',
                'desc'   => 'Coordinate your entire organization through orchestrated agents that ensure precision, compliance, and efficiency everywhere you operate.',
                'image'  => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=800&q=80',
                'icon'   => 'lightning',
            ],
            [
                'number' => '02.',
                'title'  => 'Command Global Operations',
                'desc'   => 'Coordinate your entire organization through orchestrated agents that ensure precision, compliance, and efficiency everywhere you operate.',
                'image'  => 'https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?auto=format&fit=crop&w=800&q=80',
                'icon'   => 'globe',
            ],
            [
                'number' => '03.',
                'title'  => 'Eliminate Silos',
                'desc'   => 'Break down data barriers and connect cross-functional teams with seamless real-time data sync and automated workflows.',
                'image'  => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                'icon'   => 'link',
            ],
            [
                'number' => '04.',
                'title'  => 'Scale with Clarity',
                'desc'   => 'Empower your business growth with actionable analytics, transparent reporting, and scalable cloud infrastructure.',
                'image'  => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80',
                'icon'   => 'chart',
            ],
        ];

        // Fetch all service products from database
        $dbServices = collect();
        if (isset($products) && is_iterable($products)) {
            $dbServices = collect($products)->filter(function($p) {
                $pType = strtolower($p->product_type ?? '');
                if (empty($pType)) {
                    $bData = is_array($p->data_json ?? null) ? $p->data_json : (json_decode($p->data_json ?? '[]', true) ?: []);
                    $pType = strtolower($bData['product_type'] ?? '');
                }
                return in_array($pType, ['service', 'jasa', 'layanan']);
            });
        }

        if ($dbServices->isEmpty() && isset($profile->id)) {
            $realProds = \App\Models\Product::where('seller_id', $profile->id)
                ->whereIn('product_type', ['service', 'jasa', 'layanan'])
                ->latest()
                ->get();
            if ($realProds->count() > 0) {
                $dbServices = $realProds;
            }
        }

        $servicesList = [];
        $idx = 1;
        if ($dbServices->count() > 0) {
            foreach ($dbServices as $sp) {
                $bData = is_array($sp->data_json ?? null) ? $sp->data_json : (json_decode($sp->data_json ?? '[]', true) ?: []);
                $sTitle = $sp->title ?? $sp->name ?? ($bData['title'] ?? 'Layanan Jasa');
                $sDesc = $sp->description ?? ($bData['description'] ?? '');
                
                $img = null;
                if (!empty($sp->image_url)) {
                    $img = $sp->image_url;
                } elseif (!empty($bData['images'][0])) {
                    $img = asset('storage/' . $bData['images'][0]);
                } elseif (!empty($sp->image)) {
                    $img = asset('storage/' . $sp->image);
                } else {
                    $img = $defaultServices[($idx - 1) % 4]['image'];
                }

                $linkedProd = !empty($bData['product_id']) ? \App\Models\Product::find($bData['product_id']) : null;
                $prodIdentifier = !empty($linkedProd->slug)
                    ? $linkedProd->slug
                    : (!empty($bData['slug']) 
                        ? $bData['slug'] 
                        : (!empty($sp->slug) 
                            ? $sp->slug 
                            : (\Illuminate\Support\Str::slug($sTitle) ?: $sp->id)));

                if (!empty($profile->custom_domain)) {
                    $sLink = 'https://' . rtrim($profile->custom_domain, '/') . '/produk/' . $prodIdentifier;
                } else {
                    $sLink = url(($profile->store_slug ?? 'creator') . '/produk/' . $prodIdentifier);
                }

                $servicesList[] = [
                    'number' => sprintf('%02d.', $idx),
                    'title'  => $sTitle,
                    'desc'   => $sDesc,
                    'image'  => $img,
                    'link'   => $sLink,
                    'icon'   => $defaultServices[($idx - 1) % 4]['icon']
                ];
                $idx++;
            }
        }

        // Fill remaining slots up to 4 items so 4 cards are always displayed
        while (count($servicesList) < 4) {
            $defIndex = count($servicesList);
            $item = $defaultServices[$defIndex % 4];
            $item['number'] = sprintf('%02d.', count($servicesList) + 1);
            $servicesList[] = $item;
        }
    @endphp

    <style>
        .t5-services-wrapper {
            position: relative;
            background-color: #fcfdfe;
            background-image: radial-gradient(#e2e8f0 1.2px, transparent 1.2px);
            background-size: 28px 28px;
            padding: 5rem 1.5rem;
            font-family: 'Montserrat', sans-serif;
            color: #0f172a;
            overflow: hidden;
        }

        .t5-services-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* HEADER ROW */
        .t5-services-header {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 2.5rem;
            align-items: flex-end;
            margin-bottom: 3.5rem;
        }

        .t5-services-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.85rem;
            background: rgba(226, 232, 240, 0.6);
            border: 1px solid #cbd5e1;
            border-radius: 999px;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #0d9488;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .t5-services-headline {
            font-size: 2.3rem;
            font-weight: 700;
            line-height: 1.2;
            color: #0f172a;
            letter-spacing: -0.03em;
        }

        .t5-services-headline .accent-word {
            color: #0d9488;
        }

        .t5-services-right {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            align-items: flex-start;
        }

        .t5-services-desc {
            font-size: 0.85rem;
            line-height: 1.65;
            color: #64748b;
            font-weight: 400;
        }

        .t5-services-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.75rem;
            background: #18181b;
            color: #ffffff;
            border-radius: 999px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(24, 24, 27, 0.2);
            transition: all 0.3s ease;
        }

        .t5-services-btn:hover {
            background: #09090b;
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(24, 24, 27, 0.3);
            color: #ffffff;
        }

        .t5-services-nav-wrap {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .t5-nav-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1.5px solid #cbd5e1;
            background: #ffffff;
            color: #0f172a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .t5-nav-btn:hover {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
            transform: scale(1.06);
        }

        /* CARDS GRID & SWIPE CAROUSEL */
        .t5-services-grid {
            display: flex;
            gap: 1.25rem;
            align-items: stretch;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding: 0.5rem 0.25rem 1.5rem 0.25rem;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        .t5-services-grid::-webkit-scrollbar {
            height: 6px;
        }
        .t5-services-grid::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 999px;
        }
        .t5-services-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }
        .t5-services-grid::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .t5-service-card {
            flex: 0 0 calc(25% - 0.95rem);
            min-width: 270px;
            scroll-snap-align: start;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 1.75rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 420px;
            transition: all 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
            overflow: hidden;
            text-decoration: none;
        }

        .t5-service-card:hover,
        .t5-service-card.active {
            border-color: #cbd5e1;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.09);
            transform: translateY(-6px);
        }

        /* Number Top */
        .t5-service-number {
            font-size: 3.5rem;
            font-weight: 300;
            color: #cbd5e1;
            line-height: 1;
            letter-spacing: -0.04em;
            transition: all 0.4s ease;
            margin-bottom: 1rem;
        }

        .t5-service-card:hover .t5-service-number {
            color: #94a3b8;
            transform: scale(0.95);
        }

        /* Image Box (Reveals on Hover) */
        .t5-service-img-wrap {
            width: 100%;
            height: 0;
            opacity: 0;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            margin-bottom: 0;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .t5-service-card:hover .t5-service-img-wrap,
        .t5-service-card.active .t5-service-img-wrap {
            height: 180px;
            opacity: 1;
            margin-bottom: 1.25rem;
        }

        .t5-service-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .t5-service-card:hover .t5-service-img-wrap img {
            transform: scale(1.05);
        }

        /* Content Box */
        .t5-service-body {
            margin-top: auto;
        }

        .t5-service-icon-badge {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            margin-bottom: 0.85rem;
            transition: all 0.3s ease;
        }

        .t5-service-card:hover .t5-service-icon-badge {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
        }

        .t5-service-title {
            font-size: 1.2rem;
            font-weight: 700;
            line-height: 1.3;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 0;
            transition: color 0.3s ease;
        }

        /* Description (Reveals / Fades in on Hover) */
        .t5-service-desc {
            font-size: 0.78rem;
            line-height: 1.6;
            color: #64748b;
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.45s cubic-bezier(0.16, 1, 0.3, 1);
            margin-top: 0;
            font-weight: 400;
        }

        .t5-service-card:hover .t5-service-desc,
        .t5-service-card.active .t5-service-desc {
            max-height: 120px;
            opacity: 1;
            margin-top: 0.75rem;
        }

        @media (max-width: 1024px) {
            .t5-service-card {
                flex: 0 0 calc(50% - 0.65rem);
            }
        }

        @media (max-width: 640px) {
            .t5-services-header {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .t5-services-headline {
                font-size: 1.65rem;
            }
            .t5-service-card {
                flex: 0 0 85%;
                min-height: auto;
            }
            .t5-service-img-wrap {
                height: 160px !important;
                opacity: 1 !important;
                margin-bottom: 1.25rem !important;
            }
            .t5-service-desc {
                max-height: 200px !important;
                opacity: 1 !important;
                margin-top: 0.75rem !important;
            }
        }
    </style>

    <section class="t5-services-wrapper" id="services-section">
        <div class="t5-services-container">
            {{-- HEADER --}}
            <div class="t5-services-header">
                <div>
                    <h2 class="t5-services-headline">{!! preg_replace('/(\b\w+\b)$/', '<span class="accent-word">$1</span>', e($headline)) !!}</h2>
                </div>
                <div class="t5-services-right">
                    <p class="t5-services-desc">{{ $description }}</p>
                    <div style="display:flex; align-items:center; justify-content:space-between; width:100%; gap:1rem; flex-wrap:wrap;">
                        @if(!empty($btnText))
                            <a href="{{ $btnLink }}" class="t5-services-btn">{{ $btnText }}</a>
                        @endif

                        @if(count($servicesList) > 4)
                            <div class="t5-services-nav-wrap">
                                <button type="button" class="t5-nav-btn" onclick="scrollT5Services('left')" aria-label="Swipe Left">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
                                </button>
                                <button type="button" class="t5-nav-btn" onclick="scrollT5Services('right')" aria-label="Swipe Right">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- CARDS GRID WITH HOVER ANIMATION & SWIPE SUPPORT --}}
            <div class="t5-services-grid">
                @foreach($servicesList as $idx => $srv)
                    <a href="{{ $srv['link'] }}" class="t5-service-card {{ $idx === 1 ? 'active' : '' }}">
                        <div class="t5-service-number">{{ $srv['number'] }}</div>
                        
                        <div class="t5-service-img-wrap">
                            <img src="{{ $srv['image'] }}" alt="{{ $srv['title'] }}" loading="lazy">
                        </div>

                        <div class="t5-service-body">
                            <div class="t5-service-icon-badge">
                                @if(($srv['icon'] ?? '') === 'globe')
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                @elseif(($srv['icon'] ?? '') === 'link')
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                @elseif(($srv['icon'] ?? '') === 'chart')
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 12L16 8"/><path d="M12 6v6h6"/></svg>
                                @else
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                @endif
                            </div>
                            <h3 class="t5-service-title">{{ $srv['title'] }}</h3>
                            <p class="t5-service-desc">{{ $srv['desc'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        function scrollT5Services(dir) {
            var el = document.querySelector('.t5-services-grid');
            if (!el) return;
            var amount = el.clientWidth * 0.75;
            el.scrollBy({ left: dir === 'left' ? -amount : amount, behavior: 'smooth' });
        }
    </script>
@endif
