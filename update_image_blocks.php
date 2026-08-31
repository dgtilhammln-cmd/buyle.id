<?php
$themes = ['theme1', 'theme2', 'theme3', 'theme4'];
$dir = __DIR__ . '/resources/views/bio/';

foreach ($themes as $theme) {
    $file = $dir . $theme . '.blade.php';
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // 1. Update linkBlocks definition to include 'image'
    $content = str_replace("['link','pdf']", "['link','pdf','image']", $content);

    // 2. We need to find the foreach loop for $linkBlocks and inject the if-else
    // It usually looks like:
    // @foreach($linkBlocks as $b)
    // <a href="{{ $b->url }}" target="_blank" class="glass-btn">
    // ...
    // </a>
    // @endforeach
    
    // For theme1, theme2, theme3, theme4, the link block structure is slightly different (class name might be `glass-btn`, `neon-btn`, `minimal-btn`, `clean-btn`).
    // Let's use a regex to capture the loop content
    $pattern = '/@foreach\(\$linkBlocks as \$b\)(.*?)@endforeach/s';
    
    // We want to replace the inside of the foreach loop.
    $replacement = <<<BLADE
@foreach(\$linkBlocks as \$b)
            @if(\$b->type === 'image')
                <div class="image-block" style="margin-bottom:1rem; border-radius:16px; overflow:hidden;">
                    @if(\$b->url) <a href="{{ \$b->url }}" target="_blank"> @endif
                    <img src="{{ asset('storage/'.(\$b->data_json['image'] ?? '')) }}" style="width:100%; display:block; border-radius:16px;">
                    @if(\$b->url) </a> @endif
                </div>
            @else$1@endif
        @endforeach
BLADE;

    $content = preg_replace($pattern, $replacement, $content);
    
    file_put_contents($file, $content);
    echo "Updated Image Block rendering in $theme\n";
}
