<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

$proxies = [
    'yacdn' => 'https://yacdn.org/serve/' . $url,
    'jsonp' => 'https://jsonp.afeld.me/?url=' . urlencode($url),
    'cors_workers' => 'https://test.cors.workers.dev/?' . $url,
    'cors_eu' => 'https://cors.eu.org/' . $url,
    'htmldriven' => 'https://cors-proxy.htmldriven.com/?url=' . urlencode($url),
    'bridged' => 'https://cors.bridged.cc/' . $url,
    'raw_githack' => 'https://raw.githack.com/',
    'cloudflare_worker_1' => 'https://cors-anywhere.workers.dev/?' . $url,
    'cloudflare_worker_2' => 'https://proxy.cors.sh/' . $url,
];

foreach ($proxies as $name => $pUrl) {
    echo "=== Testing $name ($pUrl) ===\n";
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(8)->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        ])->get($pUrl);

        echo "Status: " . $res->status() . " Length: " . strlen($res->body()) . "\n";
        $body = $res->body();
        if (str_contains($body, 'Cloudflare') || str_contains($body, 'Attention Required!')) {
            echo "Cloudflare blocked\n";
        } else {
            echo "SUCCESS SAMPLE: " . substr(strip_tags($body), 0, 300) . "\n";
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
