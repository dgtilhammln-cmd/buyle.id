<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

// Test PhantomJSCloud / Free Render Services
$services = [
    'phantom' => 'https://phantomjscloud.com/api/browser/v2/a-chk-passthrough/?request=' . urlencode(json_encode([
        'url' => $url,
        'renderType' => 'html',
    ])),
];

foreach ($services as $name => $sUrl) {
    echo "=== Testing $name ===\n";
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(15)->get($sUrl);
        echo "Status: " . $res->status() . " Length: " . strlen($res->body()) . "\n";
        $body = $res->body();
        if (str_contains($body, 'Cloudflare') || str_contains($body, 'Attention Required!')) {
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
}
