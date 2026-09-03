<!DOCTYPE html>
<html lang="id">

<head>
    <meta name=\referrer\ content=\no-referrer\>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="referrer" content="no-referrer">

        @php
        $roleTitleMap = [
            'content_creator' => 'Content Creator',
            'affiliator'      => 'Affiliator',
            'business'        => 'Business',
        ];
        $roleTitle = $roleTitleMap[$profile->bio_role ?? ''] ?? 'Creator';
        $bioName   = $config['name'] ?? $profile->store_name ?? $username;
        $pageTitle = $bioName . ' - ' . $roleTitle . ' | buyle.id';
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonical }}">

    <meta property="og:type" content="profile">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:site_name" content="buyle.id">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Person",
      "name": "{{ addslashes($config['name'] ?? $profile->store_name ?? $username) }}",
      "url": "{{ $canonical }}",
      "image": "{{ $ogImage }}",
      "description": "{{ addslashes($seoDesc) }}",
      "address": { "@type": "PostalAddress", "addressLocality": "{{ addslashes($config['location'] ?? 'Indonesia') }}", "addressCountry": "ID" },
      "sameAs": [
        {{ !empty($config['ig']) ? '"https://instagram.com/' . ltrim($config['ig'], '@') . '",' : '' }}
        {{ !empty($config['tiktok']) ? '"https://tiktok.com/@' . ltrim($config['tiktok'], '@') . '",' : '' }}
        {{ !empty($config['youtube']) ? '"' . $config['youtube'] . '",' : '' }}
        "{{ $canonical }}"
      ]
    }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        @php
        $favSetting = \App\Models\Setting::get('favicon');
        if ($favSetting) {
            $favPath = ltrim($favSetting, '/');
            $favicon = asset('storage/' . $favPath);
            $favType = str_ends_with($favPath, '.png') ? 'image/png' : (str_ends_with($favPath, '.svg') ? 'image/svg+xml' : 'image/x-icon');
        } else {
            $favicon = asset('favicon.ico');
            $favType = 'image/x-icon';
        }
    @endphp
    <link rel="icon" type="{{ $favType }}" href="{{ $favicon }}">
    <link rel="shortcut icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">

    <style>
        :root {
            --accent: #fff;
            --glass: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.25);
            --side: 24px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: linear-gradient(135deg, #a5cf37, #1eb349);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            background-attachment: fixed;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding-bottom: 60px;
        }

        /* Cover & Avatar */
        .cover-area {
            height: 160px;
            background: rgba(0, 0, 0, 0.1);
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .profile-container {
            text-align: center;
            position: relative;
            margin-top: -50px;
            padding: 0 var(--side);
        }

        .avatar-wrap {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.8);
            background: #fff;
            overflow: hidden;
            display: inline-block;
            margin-bottom: 0.75rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .avatar-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name { font-size: 1.2rem !important; font-weight: 700 !important; margin-top: 0.65rem; color: var(--text); font-family: 'Montserrat', sans-serif; }

        .profile-bio {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0.4rem 0;
            line-height: 1.5;
            max-width: 340px;
            margin: 0.4rem auto;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
        }

        .badges {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 0.6rem;
        }

        .badge {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 999px;
            padding: 0.25rem 0.75rem;
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        /* Social Icons Row */
        .social-row {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            padding: 1.25rem var(--side) 0;
        }

        .social-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.2s;
        }

        .social-icon:hover {
            background: var(--accent);
            color: #000;
        }

        /* Section */
        .section-label {
            font-size: 10px;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 1.75rem var(--side) 0.75rem;
            display: block;
        }

        /* Blocks */
        .link-stack {
            padding: 0 var(--side);
            margin-bottom: 20px;
        }

        .glass-btn {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 14px 16px;
            text-decoration: none;
            color: #fff;
            transition: all 0.2s;
            margin-bottom: 10px;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .glass-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.02);
        }

        .glass-btn .btn-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .glass-btn .btn-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .glass-btn .btn-body {
            flex: 1;
            min-width: 0;
        }

        .glass-btn .btn-title {
            font-size: 0.88rem;
            font-weight: 800;
            color: #fff;
        }

        .glass-btn .btn-sub {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 2px;
        }

        .glass-btn .btn-arrow {
            color: rgba(255, 255, 255, 0.6);
            flex-shrink: 0;
        }

        /* Product Grid (1:1 Ratio, 2 Columns) */
        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 0 var(--side);
            margin-bottom: 20px;
        }

        .prod-card {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            overflow: hidden;
            text-decoration: none;
            color: #fff;
            display: flex;
            flex-direction: column;
            transition: all 0.2s;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .prod-card:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
        }

        .prod-img-wrap {
            width: 100%;
            padding-top: 100%;
            position: relative;
            background: rgba(0, 0, 0, 0.1);
        }

        .prod-img-wrap img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .prod-info {
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .prod-title {
            font-weight: 700;
            font-size: 0.75rem;
            line-height: 1.4;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .prod-price {
            font-size: 0.75rem;
            color: var(--accent);
            font-weight: 800;
            margin-top: auto;
        }

        /* TikTok Slider */
        .slider-wrap {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding: 0 var(--side) 1rem;
            scrollbar-width: none;
            scroll-snap-type: x mandatory;
        }

        .slider-wrap::-webkit-scrollbar {
            display: none;
        }

        .video-card {
            width: 130px;
            height: 200px;
            border-radius: 14px;
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
            scroll-snap-align: start;
            background: #111;
            border: 1px solid var(--glass-border);
        }

        .video-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 60%);
        }

        .video-card .tt-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
        }

        .video-card .watch-label {
            position: absolute;
            bottom: 10px;
            left: 10px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 2;
        }

        /* Footer */
        .footer-bio {
            text-align: center;
            margin-top: 3rem;
            padding-bottom: 2rem;
        }

        .footer-bio a {
            color: rgba(255, 255, 255, 0.3);
            text-decoration: none;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .footer-bio a:hover {
            color: var(--accent);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.6s ease forwards;
            opacity: 0;
        }
    </style>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        /* Custom Colors Override */
        @if(!empty($config['color_bg']))
            body {
                background:
                    {{ $config['color_bg'] }}
                    !important;
            }

        @endif

        @if(!empty($config['color_text']))
            body, .profile-name, .btn-title, .prod-title, .prod-price, .social-icon {
                color:
                    {{ $config['color_text'] }}
                    !important;
            }

            .profile-bio,
            .btn-sub,
            .section-label {
                color:
                    {{ $config['color_text'] }}
                    !important;
                opacity: 0.75;
            }

        @endif

        @if(!empty($config['color_accent']))
            :root {
                --accent:
                    {{ $config['color_accent'] }}
                    !important;
            }

        @endif

        @if(!empty($config['color_card']))
            .prod-card, .video-card, .badge {
                background:
                    {{ $config['color_card'] }}
                    !important;
            }

        @endif

        @if(!empty($config['color_btn']))
            .glass-btn, .social-icon {
                background:
                    {{ $config['color_btn'] }}
                    !important;
                border-color: transparent !important;
            }

        @endif

        @if(!empty($config['color_btn_text']))
            .glass-btn .btn-title, .glass-btn .btn-sub, .glass-btn i, .social-icon i {
                color:
                    {{ $config['color_btn_text'] }}
                    !important;
            }

        @endif

                .prod-number {
            position: absolute;
            top: 8px;
            left: 8px;
            background: var(--accent);
            color: #000;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 4px;
            z-index: 10;
        }

        /* Fix map iframe responsive */
        .map-container iframe {
            width: 100% !important;
            max-width: 100%;
            display: block;
            border: none !important;
        }
    </style>
</head>

<body>

    <div class="container">

        {{-- Cover --}}
        <div class="cover-area" @if(!empty($config['cover']))
        style="background-image:url('{{ asset('storage/' . $config['cover']) }}');" @endif></div>

        {{-- Profile Header --}}
        <div class="profile-container fade-up" style="animation-delay:0.1s">
            <div class="avatar-wrap">
                @if(!empty($config['avatar']))
                    <img src="{{ asset('storage/' . $config['avatar']) }}" alt="{{ $config['name'] ?? '' }}">
                @else
                    <div
                        style="width:100%;height:100%;background:linear-gradient(135deg,#1eb349,#a5cf37);display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:900;color:#fff;">
                        {{ strtoupper(substr($config['name'] ?? $username, 0, 1)) }}</div>
                @endif
            </div>
            <h1 class="profile-name">{{ $config['name'] ?? $profile->store_name ?? $username }}</h1>
            @if(!empty($config['bio']))
                <div class="profile-bio">{{ $config['bio'] }}</div>
            @endif
                        @if(!empty($config['location']))
                <div class="badges">
                    <div class="badge"><i class="fas fa-map-marker-alt" style="font-size:9px;"></i>
                        {{ $config['location'] }}</div>
                </div>
            @endif
        </div>

        {{-- Social Icons --}}
        @if(!empty($config['wa']) || !empty($config['ig']) || !empty($config['tiktok']) || !empty($config['youtube']))
            <div class="social-row fade-up" style="animation-delay:0.2s">
                @if(!empty($config['wa'])) <a href="https://wa.me/{{ $config['wa'] }}" target="_blank"
                class="social-icon"><i class="fab fa-whatsapp"></i></a> @endif
                @if(!empty($config['ig'])) <a href="https://instagram.com/{{ ltrim($config['ig'], '@') }}" target="_blank"
                class="social-icon"><i class="fab fa-instagram"></i></a> @endif
                @if(!empty($config['tiktok'])) <a href="https://tiktok.com/@{{ ltrim($config['tiktok'],'@') }}"
                target="_blank" class="social-icon"><i class="fab fa-tiktok"></i></a> @endif
                @if(!empty($config['youtube'])) <a href="{{ $config['youtube'] }}" target="_blank" class="social-icon"><i
                class="fab fa-youtube"></i></a> @endif
            </div>
        @endif

        {{-- Realtime Global Search Box --}}
        <div class="search-box-wrap fade-up" style="animation-delay:0.22s; margin: 1.25rem var(--side, 1rem) 0.5rem;">
            <div style="position:relative; width:100%;">
                <input type="text" id="bioSearchInput" placeholder="Cari produk {{ $config['name'] ?? $profile->store_name ?? $username }}" 
                    onkeyup="filterBioItems(this.value)"
                    style="width:100%; padding:0.75rem 1rem 0.75rem 2.6rem; border-radius:999px; background:var(--glass, rgba(255,255,255,0.08)); border:1px solid var(--glass-border, rgba(255,255,255,0.15)); color:var(--text, #fff); font-family:'Montserrat',sans-serif; font-size:0.82rem; outline:none; transition:all 0.2s;">
                <i class="fas fa-search" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); opacity:0.5; font-size:0.85rem; color:var(--text, #fff);"></i>
            </div>
        </div>

        <script>
        function filterBioItems(query) {
            const q = query.toLowerCase().trim();
            document.querySelectorAll('.search-item').forEach(item => {
                const text = (item.innerText + ' ' + (item.dataset.title || '') + ' ' + (item.dataset.url || '')).toLowerCase();
                if (!q || text.includes(q)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        </script>

        @php
            $linkBlocks = $blocks->whereIn('type', ['link', 'pdf', 'image']);
            $tiktokBlocks = $blocks->where('type', 'tiktok');
            $affBlocks = $blocks->whereIn('type', ['shopee', 'affiliate'])->sortByDesc('created_at')->values();
            $buyleBlocks = $blocks->where('type', 'buyle_product');
            $customProdBlocks = $blocks->where('type', 'custom_product');
        @endphp

        {{-- TikTok Slider --}}
        @if($tiktokBlocks->isNotEmpty())
            <span class="section-label fade-up" style="animation-delay:0.25s">Highlights</span>
            <div class="slider-wrap fade-up" style="animation-delay:0.3s">
                @foreach($tiktokBlocks as $b)
                    <a href="{{ $b->url }}" target="_blank" class="video-card tt-fetch search-item" data-title="TikTok Video" data-url="{{ $b->url }}">
                        <img src="" alt="TikTok" class="tt-thumb" style="opacity:0; transition:opacity 0.3s;">
                        <span class="tt-icon"><i class="fab fa-tiktok" style="font-size:16px;"></i></span>
                        <span class="watch-label">Watch Video</span>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Custom Links / Buttons --}}
        @if($linkBlocks->isNotEmpty())
            <span class="section-label fade-up" style="animation-delay:0.3s">Links</span>
            <div class="link-stack">
                @foreach($linkBlocks as $i => $block)
                    <a href="{{ $block->url }}" target="_blank" class="glass-btn fade-up search-item" data-title="{{ $block->title }}"
                        style="animation-delay:{{ 0.35 + $i * 0.05 }}s">
                        <div class="btn-icon">
                            @if(!empty($block->data_json['icon_class']))
                                <i class="{{ $block->data_json['icon_class'] }}" style="font-size:24px;"></i>
                            @elseif(!empty($block->data_json['image']))
                                <img src="{{ Str::startsWith($block->data_json['image'], 'http') ? $block->data_json['image'] : asset('storage/' . $block->data_json['image']) }}"
                                    alt="">
                            @elseif($block->type === 'pdf')
                                <i class="fas fa-file-pdf" style="color:#fff;"></i>
                            @else
                                <i class="fas fa-link" style="color:rgba(255,255,255,0.8);"></i>
                            @endif
                        </div>
                        <div class="btn-body">
                            <div class="btn-title">{{ $block->title }}</div>
                            @if(!empty($block->data_json['description']))
                            <div class="btn-sub">{{ Str::limit($block->data_json['description'], 50) }}</div> @endif
                        </div>
                        <div class="btn-arrow"><i class="fas fa-chevron-right" style="font-size:11px;"></i></div>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Affiliate / Shopee Products --}}
        @if($affBlocks->isNotEmpty())
            <span class="section-label fade-up" style="animation-delay:0.4s">Produk Rekomendasi</span>
            <div class="product-grid fade-up" style="animation-delay:0.45s">
                @foreach($affBlocks as $index => $block)
                    <a href="{{ $block->url }}" target="_blank" class="prod-card search-item" data-title="{{ $block->title }}">
                        <div class="prod-img-wrap">
                            <div class="prod-number">{{ sprintf('%02d', $index + 1) }}</div>

                            @if(!empty($block->data_json['icon_class']))
                                <i class="{{ $block->data_json['icon_class'] }}" style="font-size:24px;"></i>
                            @elseif(!empty($block->data_json['image']))
                                <img src="{{ Str::startsWith($block->data_json['image'], 'http') ? $block->data_json['image'] : asset('storage/' . $block->data_json['image']) }}"
                                    alt="{{ $block->title }}"
                                    onerror="this.src='https://placehold.co/400x400/222/555?text=Product'">
                            @else
                                <img src="https://placehold.co/400x400/222/555?text=Product" alt="No Image">
                            @endif
                        </div>
                        <div class="prod-info">
                            <h3 class="prod-title">{{ $block->title }}</h3>
                            <div class="prod-price"> →</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

                {{-- Custom Physical / UMKM Products --}}
        @if($customProdBlocks->isNotEmpty())
            <span class="section-label fade-up" style="animation-delay:0.48s">Produk {{ $config['name'] ?? $profile->store_name ?? $username }}</span>
            <div class="product-grid fade-up" style="animation-delay:0.52s">
                @foreach($customProdBlocks as $i => $block)
                    @php 
                        $imgs = $block->data_json['images'] ?? []; 
                        $img = !empty($imgs[0]) ? asset('storage/' . $imgs[0]) : (!empty($block->data_json['image']) ? asset('storage/' . $block->data_json['image']) : 'https://placehold.co/400x400/222/555?text=Product');
                        $prodUrl = route('bio.product.show', [$username, $block->data_json['slug'] ?? $block->id]);
                        $price = $block->data_json['price'] ?? 0;
                        $origPrice = $block->data_json['original_price'] ?? null;
                    @endphp
                    <a href="{{ $prodUrl }}" class="prod-card search-item" data-title="{{ $block->title }} {{ $price }} {{ $config['wa'] ?? '' }}">
                        <div class="prod-img-wrap">
                            <img src="{{ $img }}" alt="{{ $block->title }}">
                        </div>
                        <div class="prod-info">
                            <h3 class="prod-title">{{ $block->title }}</h3>
                            <div class="prod-price">
                                @if(!empty($origPrice) && $origPrice > $price)
                                    <span style="text-decoration:line-through; opacity:0.5; font-size:0.75rem; margin-right:0.2rem;">Rp {{ number_format($origPrice, 0, ',', '.') }}</span>
                                @endif
                                Rp {{ number_format($price, 0, ',', '.') }} IDR
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Buyle Products --}}
        @if($buyleBlocks->isNotEmpty())
            <span class="section-label fade-up" style="animation-delay:0.5s">Produk Digital Saya</span>
            <div class="product-grid">
                @foreach($buyleBlocks as $i => $block)
                    @php $prod = $products[$block->data_json['product_id'] ?? 0] ?? null; @endphp
                    @if($prod)
                        <a href="{{ $block->url }}" target="_blank" class="prod-card fade-up search-item" data-title="{{ $prod->name }}"
                            style="animation-delay:{{ 0.55 + $i * 0.05 }}s">
                            <div class="prod-img-wrap">

                                @if($prod->image)
                                    <img src="{{ asset('storage/' . $prod->image) }}" alt="{{ $prod->name }}">
                                @else
                                    <img src="https://placehold.co/400x400/222/555?text=Product" alt="No Image">
                                @endif
                            </div>
                            <div class="prod-info">
                                <h3 class="prod-title">{{ $prod->name }}</h3>
                                <div class="prod-price">Rp {{ number_format($prod->price, 0, ',', '.') }}</div>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Embed Map / Lokasi --}}
        @if(!empty($config['embed_location']))
            <span class="section-label fade-up" style="animation-delay:0.6s">Lokasi Kami</span>
            <div class="map-container fade-up" style="animation-delay:0.65s; padding: 0 var(--side); margin-bottom: 20px;">
                <div
                    style="border-radius:16px; overflow:hidden; border:1px solid var(--glass-border); background:var(--card-bg, rgba(255,255,255,0.05));">
                    {!! $config['embed_location'] !!}
                </div>
            </div>
        @endif

        {{-- Footer --}}
        <div class="footer-bio">
            <a href="{{ url('/') }}" target="_blank">Powered by buyle.id</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // TikTok Thumbnail Fetcher via oEmbed
            document.querySelectorAll('.tt-fetch').forEach(card => {
                const url = card.dataset.url;
                const img = card.querySelector('.tt-thumb');
                if (!url) return;
                fetch(`https://www.tiktok.com/oembed?url=${encodeURIComponent(url)}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.thumbnail_url) { img.src = data.thumbnail_url; img.style.opacity = '1'; }
                    })
                    .catch(() => { img.src = 'https://placehold.co/130x200/111/fff?text=TikTok'; img.style.opacity = '1'; });
            });
        });
    </script>
</body>

</html>