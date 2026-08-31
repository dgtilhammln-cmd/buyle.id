<?php
$themes = ['theme1', 'theme2', 'theme3', 'theme4'];
$dir = __DIR__ . '/resources/views/bio/';

foreach ($themes as $theme) {
    $file = $dir . $theme . '.blade.php';
    if (!file_exists($file)) continue;

    $content = file_get_contents($file);

    // 1. Add Hero Image before Profile Header
    $profileHeaderSearch = "{{-- Profile Header --}}";
    if (strpos($content, $profileHeaderSearch) !== false && strpos($content, 'Hero Banner') === false) {
        $heroHtml = <<<BLADE
    {{-- Hero Banner --}}
    @if(!empty(\$config['hero']))
    <div class="hero-banner" style="width:100%; margin-bottom:-40px; position:relative; z-index:0;">
        <img src="{{ asset('storage/'.\$config['hero']) }}" alt="Hero" style="width:100%; height:{{ \$config['hero_size'] ?? 200 }}px; object-fit:cover; display:block;">
    </div>
    @endif

    {{-- Profile Header --}}
BLADE;
        $content = str_replace($profileHeaderSearch, $heroHtml, $content);
    }

    file_put_contents($file, $content);
    echo "Updated Hero in $theme\n";
}
