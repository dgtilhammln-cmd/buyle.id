<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

$apis = [
    'jsonlink' => 'https://jsonlink.io/api/extract?url=' . urlencode($url),
    'metatags' => 'https://metatags.io/api/scrape?url=' . urlencode($url),
    'dub' => 'https://dub.co/api/metatags?url=' . urlencode($url),
    'unfurler' => 'https://unfurl.io/api/v1/unfurl?url=' . urlencode($url),
    'open-graph' => 'https://og-image-scraper.vercel.app/api/scrape?url=' . urlencode($url),
];

foreach ($apis as $name => $apiUrl) {
    echo "=== Testing $name ===\n";
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(10)->get($apiUrl);
        echo "Status: " . $res->status() . "\n";
        echo "Body: " . substr($res->body(), 0, 500) . "\n\n";
    } catch (\Throwable $e) {
        echo "ERR: " . $e->getMessage() . "\n\n";
    }
}
