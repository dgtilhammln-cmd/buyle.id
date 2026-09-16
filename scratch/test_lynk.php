<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

echo "=== Testing Microlink API ===\n";
try {
    $res = \Illuminate\Support\Facades\Http::timeout(15)->get('https://api.microlink.io', [
        'url'  => $url,
        'meta' => 'true',
    ]);
    echo "Status: " . $res->status() . "\n";
    print_r($res->json());
} catch (\Throwable $e) {
    echo "Microlink ERR: " . $e->getMessage() . "\n";
}

echo "\n=== Testing Jina AI Reader (r.jina.ai) ===\n";
try {
    $res = \Illuminate\Support\Facades\Http::timeout(15)->withHeaders([
        'Accept' => 'application/json',
    ])->get('https://r.jina.ai/' . $url);
    echo "Status: " . $res->status() . "\n";
    $json = $res->json();
    echo "Title: " . ($json['data']['title'] ?? 'NONE') . "\n";
    echo "Description sample: " . substr($json['data']['description'] ?? '', 0, 200) . "\n";
    echo "Content sample: " . substr($json['data']['content'] ?? '', 0, 300) . "\n";
} catch (\Throwable $e) {
    echo "Jina ERR: " . $e->getMessage() . "\n";
}
