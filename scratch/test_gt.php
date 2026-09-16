<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';
$gtUrl = 'https://translate.google.com/translate?sl=auto&tl=en&u=' . urlencode($url);

echo "Fetching via Google Translate...\n";
try {
    $res = \Illuminate\Support\Facades\Http::timeout(10)->withHeaders([
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
    ])->get($gtUrl);

    echo "Status: " . $res->status() . " Length: " . strlen($res->body()) . "\n";
    $html = $res->body();
    
    // look for title / og tags in GT response
    if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
        echo "Title: " . trim(strip_tags($m[1])) . "\n";
    }
    if (preg_match_all('/https?:\/\/cdn\.lynkid\.my\.id\/[^\s"\']+/i', $html, $imgs)) {
        echo "Found Lynk CDN Images: " . implode(', ', array_unique($imgs[0])) . "\n";
    }
} catch (\Throwable $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
