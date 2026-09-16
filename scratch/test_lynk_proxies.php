<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

$providers = [
    '2allorigins' => 'https://api.allorigins.win/raw?url=' . urlencode($url),
    'codetabs' => 'https://api.codetabs.com/v1/proxy?quest=' . urlencode($url),
    'corsproxy' => 'https://corsproxy.io/?' . urlencode($url),
    'thingproxy' => 'https://thingproxy.freeboard.io/fetch/' . $url,
    'google_translate' => 'https://translate.google.com/translate?sl=auto&tl=en&u=' . urlencode($url),
    'jina' => 'https://r.jina.ai/' . $url,
    'wayback' => 'http://archive.org/wayback/available?url=' . urlencode($url),
    'google_webcache' => 'https://webcache.googleusercontent.com/search?q=cache:' . urlencode($url),
];

foreach ($providers as $name => $pUrl) {
    echo "=== Testing $name ($pUrl) ===\n";
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(10)->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        ])->get($pUrl);

        echo "Status: " . $res->status() . " Length: " . strlen($res->body()) . "\n";
        $body = $res->body();
        if (str_contains($body, 'Cloudflare') || str_contains($body, 'Attention Required')) {
            echo "BLOCKED by Cloudflare\n";
        } else {
            echo "SUCCESS SAMPLE: " . substr(strip_tags($body), 0, 300) . "\n";
            // Check for title or images
            if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $body, $m)) {
                echo "TITLE: " . $m[1] . "\n";
            }
        }
    } catch (\Throwable $e) {
        echo "ERR: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
