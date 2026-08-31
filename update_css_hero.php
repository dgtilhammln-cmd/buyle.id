<?php
$themes = ['theme1', 'theme2', 'theme3', 'theme4'];
$dir = __DIR__ . '/resources/views/bio/';

foreach ($themes as $theme) {
    $file = $dir . $theme . '.blade.php';
    if (!file_exists($file)) continue;

    $content = file_get_contents($file);

    // 1. Fix Custom Colors CSS
    $oldCss = <<<BLADE
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
BLADE;

    $newCss = <<<BLADE
        /* Custom Colors Override */
        @if(!empty(\$config['color_bg'])) body { background: {{ \$config['color_bg'] }} !important; } @endif
        @if(!empty(\$config['color_text'])) 
            body, .profile-name, .btn-title, .prod-title, .prod-price, .social-icon { color: {{ \$config['color_text'] }} !important; } 
            .profile-bio, .btn-sub, .section-label { color: {{ \$config['color_text'] }} !important; opacity: 0.75; }
        @endif
        @if(!empty(\$config['color_accent']))
            :root { --accent: {{ \$config['color_accent'] }} !important; }
        @endif
        @if(!empty(\$config['color_card']))
            .prod-card, .video-card, .badge { background: {{ \$config['color_card'] }} !important; }
        @endif
        @if(!empty(\$config['color_btn']))
            .glass-btn, .social-icon { background: {{ \$config['color_btn'] }} !important; border-color: transparent !important; }
        @endif
        @if(!empty(\$config['color_btn_text']))
            .glass-btn .btn-title, .glass-btn .btn-sub, .glass-btn i, .social-icon i { color: {{ \$config['color_btn_text'] }} !important; }
        @endif

        /* Fix map iframe responsive */
        .map-container iframe { width: 100% !important; max-width: 100%; display: block; border: none !important; }
BLADE;
    
    // Replace old CSS with new CSS
    $content = str_replace($oldCss, $newCss, $content);

    // 2. Fix the Hero vs Cover Area
    // The previous script added both:
    // {{-- Cover --}}
    // <div class="cover-area" ...></div>
    //
    //     {{-- Hero Banner --}}
    //     @if(!empty($config['hero']))
    //     ...
    
    // Let's find this section and replace it with a clean if/else
    
    $heroCoverPattern = '/\{\{-- Cover --\}\}.*?\{\{-- Profile Header --\}\}/s';
    
    $newHeroCover = <<<BLADE
{{-- Hero / Cover Area --}}
    @if(!empty(\$config['hero']))
        <div class="hero-banner" style="width:100%; margin-bottom:-50px; position:relative; z-index:0;">
            <img src="{{ asset('storage/'.\$config['hero']) }}" alt="Hero" style="width:100%; height:{{ \$config['hero_size'] ?? 200 }}px; object-fit:cover; display:block;">
        </div>
    @else
        <div class="cover-area" @if(!empty(\$config['cover'])) style="background-image:url('{{ asset('storage/'.\$config['cover']) }}');" @endif></div>
    @endif

    {{-- Profile Header --}}
BLADE;

    $content = preg_replace($heroCoverPattern, $newHeroCover, $content);

    file_put_contents($file, $content);
    echo "Updated CSS & Hero in $theme\n";
}
