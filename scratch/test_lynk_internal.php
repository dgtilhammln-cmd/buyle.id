<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

// Test Lynk.id API endpoints
$endpoints = [
    'https://api.lynkid.my.id/v1/product/PZbVe7P',
    'https://api.lynkid.my.id/v1/p/PZbVe7P',
    'https://lynk.id/api/product/PZbVe7P',
    'https://lynk.id/api/v1/product/PZbVe7P',
    'https://lynk.id/mindiw/PZbVe7P.json',
];

foreach ($endpoints as $ep) {
    echo "=== Testing $ep ===\n";
    try {
        $res = \Illuminate\Support\Facades\Http::timeout(5)->get($ep);
        echo "Status: " . $res->status() . " Body: " . substr($res->body(), 0, 300) . "\n\n";
    } catch (\Throwable $e) {
        echo "ERR: " . $e->getMessage() . "\n\n";
    }
}
