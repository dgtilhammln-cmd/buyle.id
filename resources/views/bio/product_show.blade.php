<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $block->title }} — {{ $config['name'] ?? $username }} | buyle.id</title>
    <meta name="description" content="{{ Str::limit($block->data_json['description'] ?? 'Produk dari '.$username, 160) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    @php
        $bg      = $config['color_bg']       ?? ($theme === 'theme2' || $theme === 'theme4' ? '#ffffff' : '#0b120c');
        $text    = $config['color_text']     ?? ($theme === 'theme2' || $theme === 'theme4' ? '#0b120c' : '#ffffff');
        $btnBg   = $config['color_btn']      ?? ($theme === 'theme3' ? '#8b5cf6' : '#1eb349');
        $btnText = $config['color_btn_text'] ?? '#ffffff';
        $accent  = $config['color_accent']   ?? ($theme === 'theme3' ? '#a855f7' : '#1eb349');
        $card    = $config['color_card']     ?? ($theme === 'theme2' || $theme === 'theme4' ? '#f8fafc' : '#1a231b');
        $isDark  = in_array($theme, ['theme1','theme3']);
        $glass   = $isDark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.04)';
        $border  = $isDark ? 'rgba(255,255,255,0.12)' : 'rgba(0,0,0,0.1)';
    @endphp

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: {{ $bg }}; color: {{ $text }}; font-family: 'Montserrat', sans-serif; min-height: 100vh; }
        .page-wrap { max-width: 480px; margin: 0 auto; padding: 0 0 80px; }

        .back-bar { display: flex; align-items: center; gap: 0.5rem; padding: 1rem 1.25rem; }
        .back-btn { display: flex; align-items: center; gap: 0.4rem; color: {{ $text }}; text-decoration: none; font-size: 0.82rem; font-weight: 700; opacity: 0.7; transition: opacity 0.2s; }
        .back-btn:hover { opacity: 1; }

        .slider-container { position: relative; overflow: hidden; background: {{ $card }}; }
        .slider-track { display: flex; transition: transform 0.4s ease; }
        .slide { min-width: 100%; aspect-ratio: 1/1; }
        .slide img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .slide-placeholder { width: 100%; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; background: {{ $card }}; }
        .slider-dots { display: flex; justify-content: center; gap: 6px; padding: 0.75rem 0; }
        .slider-dot { width: 6px; height: 6px; border-radius: 50%; background: {{ $text }}; opacity: 0.3; cursor: pointer; transition: all 0.2s; }
        .slider-dot.active { opacity: 1; width: 18px; border-radius: 3px; background: {{ $accent }}; }
        .slider-prev, .slider-next { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.4); color: #fff; border: none; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 10; }
        .slider-prev { left: 10px; }
        .slider-next { right: 10px; }

        .prod-info-box { padding: 1.5rem 1.25rem; }
        .prod-name { font-size: 1.35rem; font-weight: 900; line-height: 1.25; color: {{ $text }}; }
        .prod-price { font-size: 1.5rem; font-weight: 900; color: {{ $accent }}; margin-top: 0.5rem; }
        .prod-desc { font-size: 0.85rem; line-height: 1.7; color: {{ $text }}; opacity: 0.75; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid {{ $border }}; white-space: pre-wrap; }

        .seller-card { margin: 0 1.25rem 1.5rem; background: {{ $glass }}; border: 1px solid {{ $border }}; border-radius: 16px; padding: 1rem; display: flex; align-items: center; gap: 0.85rem; }
        .seller-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid {{ $accent }}; flex-shrink: 0; }
        .seller-avatar-placeholder { width: 42px; height: 42px; border-radius: 50%; background: {{ $accent }}; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.1rem; color: #000; flex-shrink: 0; }
        .seller-info { flex: 1; min-width: 0; }
        .seller-name { font-weight: 800; font-size: 0.88rem; color: {{ $text }}; }
        .seller-label { font-size: 0.72rem; color: {{ $text }}; opacity: 0.6; }

        .cta-bar { position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; max-width: 480px; padding: 1rem 1.25rem; background: {{ $bg }}; border-top: 1px solid {{ $border }}; z-index: 100; }
        .btn-buy { display: block; width: 100%; padding: 1rem; border-radius: 999px; background: {{ $btnBg }}; color: {{ $btnText }}; font-family: 'Montserrat', sans-serif; font-size: 1rem; font-weight: 800; text-align: center; text-decoration: none; border: none; cursor: pointer; transition: transform 0.15s; }
        .btn-buy:hover { transform: scale(1.02); }
    </style>
</head>
<body>
<div class="page-wrap">
    <div class="back-bar">
        <a href="{{ route('bio.public', $username) }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Kembali ke Profil
        </a>
    </div>

    @php
        $images = $block->data_json['images'] ?? [];
        $price  = $block->data_json['price'] ?? 0;
        $paymentMethod = $block->data_json['payment_method'] ?? 'wa';
        $waText = $block->data_json['wa_text'] ?? '';
        $waNumber = $config['wa'] ?? '';
        $waMessage = 'Halo, saya mendapatkan nomor dari buyle.id. ' . ($waText ?: 'Saya tertarik dengan produk *'.$block->title.'* (Rp '.number_format($price,0,',','.').'). Apakah masih tersedia?');
    @endphp

    <div class="slider-container" id="sliderContainer">
        @if(count($images) > 0)
            @if(count($images) > 1)
                <button class="slider-prev" onclick="slideMove(-1)"><i class="fas fa-chevron-left" style="font-size:12px;"></i></button>
                <button class="slider-next" onclick="slideMove(1)"><i class="fas fa-chevron-right" style="font-size:12px;"></i></button>
            @endif
            <div class="slider-track" id="sliderTrack">
                @foreach($images as $img)
                <div class="slide"><img src="{{ asset('storage/'.$img) }}" alt="{{ $block->title }}"></div>
                @endforeach
            </div>
            @if(count($images) > 1)
            <div class="slider-dots" id="sliderDots">
                @foreach($images as $i => $img)
                <div class="slider-dot {{ $i === 0 ? 'active' : '' }}" onclick="slideTo({{ $i }})"></div>
                @endforeach
            </div>
            @endif
        @else
            <div class="slide-placeholder"><i class="fas fa-image" style="font-size:4rem;opacity:0.2;"></i></div>
        @endif
    </div>

    <div class="prod-info-box">
        <div class="prod-name">{{ $block->title }}</div>
        <div class="prod-price">Rp {{ number_format($price, 0, ',', '.') }}</div>
        @if(!empty($block->data_json['description']))
        <div class="prod-desc">{{ $block->data_json['description'] }}</div>
        @endif
    </div>

    <div class="seller-card">
        @if(!empty($config['avatar']))
            <img src="{{ asset('storage/'.$config['avatar']) }}" alt="{{ $config['name'] ?? $username }}" class="seller-avatar">
        @else
            <div class="seller-avatar-placeholder">{{ strtoupper(substr($config['name'] ?? $username, 0, 1)) }}</div>
        @endif
        <div class="seller-info">
            <div class="seller-name">{{ $config['name'] ?? $username }}</div>
            <div class="seller-label">Penjual di buyle.id</div>
        </div>
        <a href="{{ route('bio.public', $username) }}" style="font-size:0.75rem; font-weight:700; color:{{ $accent }}; text-decoration:none;">Lihat Profil →</a>
    </div>
</div>

<div class="cta-bar">
    @if($paymentMethod === 'wa' && $waNumber)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}?text={{ urlencode($waMessage) }}" target="_blank" class="btn-buy">
            <i class="fab fa-whatsapp"></i> Beli via WhatsApp
        </a>
    @elseif($block->url)
        <a href="{{ $block->url }}" target="_blank" class="btn-buy">
            <i class="fas fa-shopping-cart"></i> Beli Sekarang
        </a>
    @else
        <a href="{{ route('bio.public', $username) }}" class="btn-buy">
            <i class="fas fa-arrow-left"></i> Kembali ke Profil
        </a>
    @endif
</div>

<script>
let currentSlide = 0;
const totalSlides = {{ count($images) }};
function slideTo(index) {
    currentSlide = Math.max(0, Math.min(index, totalSlides - 1));
    const track = document.getElementById('sliderTrack');
    if (track) track.style.transform = `translateX(-${currentSlide * 100}%)`;
    document.querySelectorAll('.slider-dot').forEach((d, i) => d.classList.toggle('active', i === currentSlide));
}
function slideMove(dir) { slideTo(currentSlide + dir); }
let touchStartX = 0;
const container = document.getElementById('sliderContainer');
if (container) {
    container.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; });
    container.addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].screenX;
        if (Math.abs(diff) > 40) slideMove(diff > 0 ? 1 : -1);
    });
}
</script>
</body>
</html>