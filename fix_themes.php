<?php

$themes = [
    'theme1' => 'https://placehold.co/400x400/1a1a1a/444444?text=No+Image',
    'theme2' => 'https://placehold.co/400x400/f8fafc/cbd5e1?text=No+Image',
    'theme3' => 'https://placehold.co/400x400/1e293b/334155?text=No+Image',
    'theme4' => 'https://placehold.co/400x400/ffffff/e2e8f0?text=No+Image',
];

$dir = __DIR__ . '/resources/views/bio/';

foreach ($themes as $theme => $fallback) {
    $file = $dir . $theme . '.blade.php';
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // Fix head
    if (strpos($content, '<meta name="referrer" content="no-referrer">') === false) {
        $content = str_replace('<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">', "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\">\n    <meta name=\"referrer\" content=\"no-referrer\">", $content);
    }
    
    // Fix broken <head><meta name=" referrer... from powershell
    $content = preg_replace('/<head><meta name=" referrer.*?>/', '<head>', $content);

    // Add onerror to affiliate images
    // It's the one that has alt="Product"
    if (strpos($content, 'alt="Product" onerror=') === false) {
        $content = str_replace('alt="Product"', 'alt="Product" onerror="this.src=\''.$fallback.'\'"', $content);
    }

    file_put_contents($file, $content);
}
echo "Done\n";
