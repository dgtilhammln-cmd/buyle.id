<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

echo "=== AllOrigins JSON GET ===\n";
try {
    $res = \Illuminate\Support\Facades\Http::timeout(10)->get('https://api.allorigins.win/get', ['url' => $url]);
    echo "Status: " . $res->status() . "\n";
    $contents = $res->json('contents', '');
    echo "Length: " . strlen($contents) . "\n";
    if (str_contains($contents, 'Cloudflare')) {
        echo "Cloudflare blocked Allorigins\n";
    } else {
        echo "SUCCESS: " . substr(strip_tags($contents), 0, 300) . "\n";
    }
} catch (\Throwable $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}

echo "\n=== Google Search Scrape ===\n";
try {
    $searchUrl = 'https://www.google.com/search?q=' . urlencode('site:lynk.id/mindiw/PZbVe7P OR "PZbVe7P"');
    $res = \Illuminate\Support\Facades\Http::timeout(10)->withHeaders([
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
    ])->get($searchUrl);
    echo "Status: " . $res->status() . "\n";
    $html = $res->body();
    if (preg_match('/<h3[^>]*>(.*?)<\/h3>/is', $html, $m)) {
        echo "Google Title: " . strip_tags($m[1]) . "\n";
    }
    if (preg_match('/<div[^>]*class=["\'][^"\']*(?:VwiC3b|yXK7lf)[^"\']*["\'][^>]*>(.*?)<\/div>/is', $html, $m)) {
        echo "Google Snippet: " . strip_tags($m[1]) . "\n";
    }
} catch (\Throwable $e) {
        echo "ERR: " . $e->getMessage() . "\n";
}
