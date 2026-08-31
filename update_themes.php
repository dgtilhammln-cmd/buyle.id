<?php
$themes = ['theme1', 'theme2', 'theme3', 'theme4'];
$dir = __DIR__ . '/resources/views/bio/';

foreach ($themes as $theme) {
    $file = $dir . $theme . '.blade.php';
    if (!file_exists($file)) continue;

    $content = file_get_contents($file);

    // 1. Add Embed Map before Footer
    $footerSearch = "{{-- Footer --}}";
    if (strpos($content, $footerSearch) !== false) {
        $mapHtml = <<<BLADE
    {{-- Embed Map / Lokasi --}}
    @if(!empty(\$config['embed_location']))
    <span class="section-label fade-up" style="animation-delay:0.6s">Lokasi Kami</span>
    <div class="map-container fade-up" style="animation-delay:0.65s; padding: 0 var(--side); margin-bottom: 20px;">
        <div style="border-radius:16px; overflow:hidden; border:1px solid var(--glass-border); background:var(--card-bg, rgba(255,255,255,0.05));">
            {!! \$config['embed_location'] !!}
        </div>
    </div>
    @endif

    {{-- Footer --}}
BLADE;
        // only replace if not already added
        if (strpos($content, 'Embed Map / Lokasi') === false) {
            $content = str_replace($footerSearch, $mapHtml, $content);
        }
    }

    // 2. Add inline CSS to override based on $config variables
    // We will append a <style> block right before </head> to override colors if they exist
    $cssInject = <<<BLADE
    <style>
        /* Custom Colors Override */
        @if(!empty(\$config['color_bg'])) body { background: {{ \$config['color_bg'] }} !important; } @endif
        @if(!empty(\$config['color_text'])) 
            body, .profile-name, .btn-title, .prod-title { color: {{ \$config['color_text'] }} !important; } 
            .profile-bio, .btn-sub, .section-label { color: {{ \$config['color_text'] }} !important; opacity: 0.7; }
        @endif
        @if(!empty(\$config['color_accent']))
            :root { --accent: {{ \$config['color_accent'] }} !important; }
            .prod-price { color: {{ \$config['color_accent'] }} !important; }
            .social-icon:hover { background: {{ \$config['color_accent'] }} !important; color: #000 !important; }
        @endif
        @if(!empty(\$config['color_card']))
            .prod-card, .video-card { background: {{ \$config['color_card'] }} !important; }
            .badge, .social-icon { background: {{ \$config['color_card'] }} !important; }
        @endif
        @if(!empty(\$config['color_btn']))
            .glass-btn { background: {{ \$config['color_btn'] }} !important; border-color: transparent !important; }
        @endif
        @if(!empty(\$config['color_btn_text']))
            .glass-btn .btn-title, .glass-btn .btn-sub, .glass-btn i { color: {{ \$config['color_btn_text'] }} !important; }
        @endif

        /* Fix map iframe responsive */
        .map-container iframe { width: 100% !important; max-width: 100%; display: block; border: none !important; }
    </style>
</head>
BLADE;
    
    if (strpos($content, 'Custom Colors Override') === false) {
        $content = str_replace('</head>', $cssInject, $content);
    }

    file_put_contents($file, $content);
    echo "Updated $theme\n";
}
