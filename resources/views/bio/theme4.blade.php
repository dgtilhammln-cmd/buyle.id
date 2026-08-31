<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>{{ $seoTitle }}</title>
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
        {{ !empty($config['ig']) ? '"https://instagram.com/'.ltrim($config['ig'],'@').'",' : '' }}
        {{ !empty($config['tiktok']) ? '"https://tiktok.com/@'.ltrim($config['tiktok'],'@').'",' : '' }}
        {{ !empty($config['youtube']) ? '"'.$config['youtube'].'",' : '' }}
        "{{ $canonical }}"
      ]
    }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <style>
        :root { --accent: #1eb349; --glass: #fff; --glass-border: #f1f5f9; --text: #1e293b; --text-sub: #64748b; --side: 24px; }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #f8fafc; color: var(--text); font-family: 'Montserrat', sans-serif; min-height: 100vh; }
        .container { max-width: 500px; margin: 0 auto; padding-bottom: 60px; }

        /* Cover & Avatar */
        .cover-area { height: 160px; background: linear-gradient(135deg, #e2e8f0, #f1f5f9); background-size: cover; background-position: center; position: relative; }
        .profile-container { text-align: center; position: relative; margin-top: -50px; padding: 0 var(--side); }
        .avatar-wrap { width: 100px; height: 100px; border-radius: 50%; border: 4px solid #f8fafc; background: #fff; overflow: hidden; display: inline-block; margin-bottom: 0.75rem; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .avatar-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .profile-name { font-size: 1.35rem; font-weight: 900; color: var(--text); letter-spacing: -0.02em; }
        .profile-bio { font-size: 0.82rem; color: var(--text-sub); margin: 0.4rem 0; line-height: 1.5; max-width: 340px; margin: 0.4rem auto; }
        .badges { display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap; margin-top: 0.6rem; }
        .badge { background: #fff; border: 1px solid var(--glass-border); border-radius: 8px; padding: 0.3rem 0.75rem; font-size: 0.72rem; color: var(--text-sub); display: flex; align-items: center; gap: 0.3rem; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }

        /* Social Icons Row */
        .social-row { display: flex; justify-content: center; gap: 0.75rem; padding: 1.25rem var(--side) 0; }
        .social-icon { width: 44px; height: 44px; border-radius: 12px; background: #fff; border: 1px solid var(--glass-border); display: flex; align-items: center; justify-content: center; color: var(--text); text-decoration: none; font-size: 1.1rem; transition: all 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .social-icon:hover { background: var(--accent); color: #fff; border-color: var(--accent); transform: translateY(-2px); }

        /* Section */
        .section-label { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin: 1.75rem var(--side) 0.75rem; display: block; }

        /* Blocks */
        .link-stack { padding: 0 var(--side); }
        .glass-btn { display: flex; align-items: center; gap: 0.85rem; background: #fff; border: 1px solid var(--glass-border); border-radius: 14px; padding: 14px 16px; text-decoration: none; color: var(--text); transition: all 0.2s; margin-bottom: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .glass-btn:hover { border-color: #cbd5e1; transform: translateY(-2px); box-shadow: 0 10px 15px rgba(0,0,0,0.05); }
        .glass-btn .btn-icon { width: 40px; height: 40px; border-radius: 10px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
        .glass-btn .btn-icon img { width: 100%; height: 100%; object-fit: cover; }
        .glass-btn .btn-body { flex: 1; min-width: 0; }
        .glass-btn .btn-title { font-size: 0.88rem; font-weight: 700; color: var(--text); }
        .glass-btn .btn-sub { font-size: 0.7rem; color: var(--text-sub); margin-top: 2px; }
        .glass-btn .btn-arrow { color: #cbd5e1; flex-shrink: 0; }

        /* Affiliate card */
        .aff-card-pub { display: flex; align-items: center; gap: 0.85rem; background: #fff; border: 1px solid var(--glass-border); border-radius: 14px; padding: 12px; text-decoration: none; color: var(--text); transition: all 0.2s; margin-bottom: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .aff-card-pub:hover { border-color: #cbd5e1; transform: translateY(-2px); box-shadow: 0 10px 15px rgba(0,0,0,0.05); }
        .aff-card-pub img { width: 56px; height: 56px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
        .aff-price { font-size: 0.78rem; color: var(--accent); font-weight: 700; margin-top: 0.2rem; }

        /* TikTok Slider */
        .slider-wrap { display: flex; gap: 12px; overflow-x: auto; padding: 0 var(--side) 1rem; scrollbar-width: none; scroll-snap-type: x mandatory; }
        .slider-wrap::-webkit-scrollbar { display: none; }
        .video-card { width: 130px; height: 200px; border-radius: 14px; overflow: hidden; flex-shrink: 0; position: relative; scroll-snap-align: start; background: #fff; border: 1px solid var(--glass-border); box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .video-card img { width: 100%; height: 100%; object-fit: cover; }
        .video-card::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 50%); }
        .video-card .tt-icon { position: absolute; top: 10px; left: 10px; z-index: 2; color: #fff; }
        .video-card .watch-label { position: absolute; bottom: 10px; left: 10px; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; z-index: 2; color: #fff; }

        /* Footer */
        .footer-bio { text-align: center; margin-top: 3rem; padding-bottom: 2rem; }
        .footer-bio a { color: #94a3b8; text-decoration: none; font-size: 10px; font-weight: 700; letter-spacing: 1px; }
        .footer-bio a:hover { color: var(--text); }

        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.6s ease forwards; opacity: 0; }
    </style>
</head>
<body>

<div class="container">

    {{-- Cover --}}
    <div class="cover-area" @if(!empty($config['cover'])) style="background-image:url('{{ asset('storage/'.$config['cover']) }}');" @endif></div>

    {{-- Profile Header --}}
    <div class="profile-container fade-up" style="animation-delay:0.1s">
        <div class="avatar-wrap">
            @if(!empty($config['avatar']))
                <img src="{{ asset('storage/'.$config['avatar']) }}" alt="{{ $config['name'] ?? '' }}">
            @else
                <div style="width:100%;height:100%;background:linear-gradient(135deg,#1eb349,#a5cf37);display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:900;color:#fff;">{{ strtoupper(substr($config['name'] ?? $username, 0, 1)) }}</div>
            @endif
        </div>
        <div class="profile-name">{{ $config['name'] ?? $profile->store_name ?? $username }}</div>
        @if(!empty($config['bio']))
        <div class="profile-bio">{{ $config['bio'] }}</div>
        @endif
        <div class="badges">
            @php $roleMap = ['content_creator'=>'Content Creator','affiliator'=>'Affiliator','business'=>'Business']; @endphp
            <div class="badge"><i class="fas fa-briefcase" style="font-size:9px;"></i> {{ $roleMap[$profile->bio_role] ?? $profile->bio_role }}</div>
            @if(!empty($config['location']))
            <div class="badge"><i class="fas fa-map-marker-alt" style="font-size:9px;"></i> {{ $config['location'] }}</div>
            @endif
        </div>
    </div>

    {{-- Social Icons --}}
    @if(!empty($config['wa']) || !empty($config['ig']) || !empty($config['tiktok']) || !empty($config['youtube']))
    <div class="social-row fade-up" style="animation-delay:0.2s">
        @if(!empty($config['wa'])) <a href="https://wa.me/{{ $config['wa'] }}" target="_blank" class="social-icon"><i class="fab fa-whatsapp"></i></a> @endif
        @if(!empty($config['ig'])) <a href="https://instagram.com/{{ ltrim($config['ig'],'@') }}" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a> @endif
        @if(!empty($config['tiktok'])) <a href="https://tiktok.com/@{{ ltrim($config['tiktok'],'@') }}" target="_blank" class="social-icon"><i class="fab fa-tiktok"></i></a> @endif
        @if(!empty($config['youtube'])) <a href="{{ $config['youtube'] }}" target="_blank" class="social-icon"><i class="fab fa-youtube"></i></a> @endif
    </div>
    @endif

    @php
        $linkBlocks    = $blocks->whereIn('type', ['link','pdf']);
        $tiktokBlocks  = $blocks->where('type', 'tiktok');
        $affBlocks     = $blocks->whereIn('type', ['shopee','affiliate']);
        $buyleBlocks   = $blocks->where('type', 'buyle_product');
    @endphp

    {{-- TikTok Slider --}}
    @if($tiktokBlocks->isNotEmpty())
    <span class="section-label fade-up" style="animation-delay:0.25s">Highlights</span>
    <div class="slider-wrap fade-up" style="animation-delay:0.3s">
        @foreach($tiktokBlocks as $b)
        <a href="{{ $b->url }}" target="_blank" class="video-card tt-fetch" data-url="{{ $b->url }}">
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
        <a href="{{ $block->url }}" target="_blank" class="glass-btn fade-up" style="animation-delay:{{ 0.35 + $i * 0.05 }}s">
            <div class="btn-icon">
                @if(!empty($block->data_json['image']))
                    <img src="{{ Str::startsWith($block->data_json['image'], 'http') ? $block->data_json['image'] : asset('storage/'.$block->data_json['image']) }}" alt="">
                @elseif($block->type==='pdf')
                    <i class="fas fa-file-pdf" style="color:#ef4444;"></i>
                @else
                    <i class="fas fa-link" style="color:#94a3b8;"></i>
                @endif
            </div>
            <div class="btn-body">
                <div class="btn-title">{{ $block->title }}</div>
                @if(!empty($block->data_json['description'])) <div class="btn-sub">{{ Str::limit($block->data_json['description'], 50) }}</div> @endif
            </div>
            <div class="btn-arrow"><i class="fas fa-chevron-right" style="font-size:11px;"></i></div>
        </a>
        @endforeach
    </div>
    @endif

    {{-- Affiliate / Shopee Products --}}
    @if($affBlocks->isNotEmpty())
    <span class="section-label fade-up" style="animation-delay:0.4s">Produk Rekomendasi</span>
    <div class="link-stack fade-up" style="animation-delay:0.45s">
        @foreach($affBlocks as $block)
        <a href="{{ $block->url }}" target="_blank" class="aff-card-pub">
            @if(!empty($block->data_json['image']))
            <img src="{{ $block->data_json['image'] }}" alt="{{ $block->title }}" onerror="this.style.display='none'">
            @endif
            <div style="flex:1; min-width:0;">
                <div style="font-weight:700; font-size:0.85rem; line-height:1.3;">{{ $block->title }}</div>
                <div class="aff-price">Lihat di Shopee →</div>
            </div>
        </a>
        @endforeach
    </div>
    @endif

    {{-- Buyle Products --}}
    @if($buyleBlocks->isNotEmpty())
    <span class="section-label fade-up" style="animation-delay:0.5s">Produk Digital Saya</span>
    <div class="link-stack">
        @foreach($buyleBlocks as $i => $block)
        @php $prod = $products[$block->data_json['product_id'] ?? 0] ?? null; @endphp
        @if($prod)
        <a href="{{ $block->url }}" target="_blank" class="aff-card-pub fade-up" style="animation-delay:{{ 0.55 + $i * 0.05 }}s">
            @if($prod->image)
            <img src="{{ asset('storage/'.$prod->image) }}" alt="{{ $prod->name }}">
            @endif
            <div style="flex:1; min-width:0;">
                <div style="font-weight:700; font-size:0.85rem; line-height:1.3;">{{ $prod->name }}</div>
                <div class="aff-price">Rp {{ number_format($prod->price, 0, ',', '.') }}</div>
            </div>
            <i class="fas fa-chevron-right" style="color:#cbd5e1; font-size:11px; flex-shrink:0;"></i>
        </a>
        @endif
        @endforeach
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
        if(!url) return;
        fetch(`https://www.tiktok.com/oembed?url=${encodeURIComponent(url)}`)
            .then(r => r.json())
            .then(data => {
                if(data.thumbnail_url) { img.src = data.thumbnail_url; img.style.opacity = '1'; }
            })
            .catch(() => { img.src = 'https://placehold.co/130x200/111/fff?text=TikTok'; img.style.opacity = '1'; });
    });
});
</script>
</body>
</html>
