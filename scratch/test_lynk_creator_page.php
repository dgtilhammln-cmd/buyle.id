<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw';

echo "=== Testing Creator Page: $url ===\n";
try {
    $res = \Illuminate\Support\Facades\Http::timeout(10)->withHeaders([
        'User-Agent' => 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
    ])->get($url);

    echo "Status: " . $res->status() . " Length: " . strlen($res->body()) . "\n";
    $body = $res->body();
    if (str_contains($body, 'Cloudflare')) {
        echo "Cloudflare blocked creator page too\n";
    } else {
        echo "SUCCESS! Creator page sample: " . substr(strip_tags($body), 0, 500) . "\n";
        file_put_contents(__DIR__ . '/mindiw_page.html', $body);
    }
} catch (\Throwable $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
