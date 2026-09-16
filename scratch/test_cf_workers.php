<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

$workerProxies = [
    'https://cors.workers.dev/?' . $url,
    'https://cors-proxy.workers.dev/?' . $url,
    'https://proxy.cors.sh/' . $url,
    'https://cors-anywhere.herokuapp.com/' . $url,
    'https://api.allorigins.win/raw?url=' . urlencode($url),
];

foreach ($workerProxies as $pUrl) {
    echo "=== Testing Worker Proxy: $pUrl ===\n";
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(10)->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        ])->get($pUrl);

        echo "Status: " . $res->status() . " Length: " . strlen($res->body()) . "\n";
        $body = $res->body();
        if (str_contains($body, 'Cloudflare') && str_contains($body, 'Attention Required!')) {
            echo "Cloudflare blocked\n";
        } else {
            echo "SUCCESS SAMPLE: " . substr(strip_tags($body), 0, 400) . "\n";
            if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $body, $m)) {
                echo "TITLE: " . $m[1] . "\n";
            }
            if (preg_match_all('/https?:\/\/cdn\.lynkid\.my\.id\/[^\s"\']+/i', $body, $m)) {
                echo "IMAGES: " . implode(', ', array_unique($m[0])) . "\n";
            }
        }
    } catch (\Throwable $e) {
        echo "ERR: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
