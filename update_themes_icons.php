<?php

$dir = __DIR__ . '/resources/views/bio/';
$themes = ['theme1.blade.php', 'theme2.blade.php', 'theme3.blade.php', 'theme4.blade.php'];

foreach ($themes as $theme) {
    $file = $dir . $theme;
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // Add Phosphor Icons to <head>
    if (strpos($content, '@phosphor-icons/web') === false) {
        $content = str_replace('</head>', "    <script src=\"https://unpkg.com/@phosphor-icons/web\"></script>\n</head>", $content);
    }

    // Update btn-icon to support icon_class
    // Original:
    // @if(!empty($block->data_json['image']))
    //     <img src="{{ Str::startsWith($block->data_json['image'], 'http') ? $block->data_json['image'] : asset('storage/'.$block->data_json['image']) }}" alt="{{ $block->title }}" ...
    
    // We will replace it with:
    // @if(!empty($block->data_json['icon_class']))
    //     <i class="{{ $block->data_json['icon_class'] }}" style="font-size:24px;"></i>
    // @elseif(!empty($block->data_json['image']))
    //     ...

    if (strpos($content, '$block->data_json[\'icon_class\']') === false) {
        $search = "@if(!empty(\$block->data_json['image']))";
        $replace = "@if(!empty(\$block->data_json['icon_class']))\n                    <i class=\"{{ \$block->data_json['icon_class'] }}\" style=\"font-size:24px;\"></i>\n                @elseif(!empty(\$block->data_json['image']))";
        $content = str_replace($search, $replace, $content);
    }

    file_put_contents($file, $content);
}
echo "Themes updated for Phosphor Icons.\n";
