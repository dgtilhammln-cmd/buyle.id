<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

$endpoints = [
    'google_mobile_translate' => 'https://translate.google.com/m?sl=auto&tl=id&q=' . urlencode($url),
    'noembed' => 'https://noembed.com/embed?url=' . urlencode($url),
    'iframely' => 'https://iframe.ly/api/oembed?api_key=free&url=' . urlencode($url),
    'wayback_raw' => 'https://web.archive.org/web/20260000000000id_/' . $url,
    'archive_is' => 'https://archive.is/latest/' . $url,
    'jina_search' => 'https://s.jina.ai/' . urlencode($url),
    'scrapedkit' => 'https://api.scraperapi.com?api_key=test&url=' . urlencode($url),
    'api_microlink_prerender' => 'https://api.microlink.io?url=' . urlencode($url) . '&prerender=true',
];

foreach ($endpoints as $name => $ep) {
    echo "=== Testing $name ($ep) ===\n";
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(10)->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        ])->get($ep);

        echo "Status: " . $res->status() . " Length: " . strlen($res->body()) . "\n";
        $body = $res->body();
        if (str_contains($body, 'Cloudflare') || str_contains($body, 'Attention Required!')) {
            echo "Cloudflare blocked\n";
        } else {
            echo "SUCCESS SAMPLE: " . substr(strip_tags($body), 0, 300) . "\n";
            if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $body, $m)) {
                echo "TITLE: " . $m[1] . "\n";
            }
        }
    } catch (\Throwable $e) {
        echo "ERR: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
