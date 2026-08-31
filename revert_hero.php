<?php
$themes = ['theme1', 'theme2', 'theme3', 'theme4'];
$dir = __DIR__ . '/resources/views/bio/';

foreach ($themes as $theme) {
    $file = $dir . $theme . '.blade.php';
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // 1. Revert Hero back to simple Cover
    $heroPattern = '/\{\{-- Hero \/ Cover Area --\}\}.*?\{\{-- Profile Header --\}\}/s';
    $coverHtml = <<<BLADE
    {{-- Cover --}}
    <div class="cover-area" @if(!empty(\$config['cover'])) style="background-image:url('{{ asset('storage/'.\$config['cover']) }}');" @endif></div>

    {{-- Profile Header --}}
BLADE;
    $content = preg_replace($heroPattern, $coverHtml, $content);

    // 2. Add Image Blocks rendering
    // We will place it right before Custom Links (or alongside them).
    // The user wants it to be reorderable. Currently, we render them by group:
    // $linkBlocks = $blocks->whereIn('type', ['link','pdf','image']);
    // Wait, the themes currently group blocks by type instead of respecting the `order` column!
    // $tiktokBlocks = $blocks->where('type', 'tiktok');
    // $linkBlocks = $blocks->whereIn('type', ['link','pdf']);
    // If the user wants to reorder it ("diatur urutannya setelah link sebelum atau lainnya"), the theme needs to render blocks by their `order`!
    // But modifying the entire theme layout to render by `order` is a huge change because each block type has a different section label and UI.
    // Let's check how `theme1.blade.php` renders blocks.
    
    file_put_contents($file, $content);
    echo "Restored Cover in $theme\n";
}
