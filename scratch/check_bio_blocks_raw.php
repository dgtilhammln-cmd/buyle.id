<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$blocks = \DB::table('creator_bio_blocks')->get();
foreach ($blocks as $b) {
    $data = json_decode($b->data_json ?? '{}', true);
    echo "ID: {$b->id} | Type: {$b->type} | Title: " . ($b->title ?? ($data['title'] ?? '')) . " | Category in JSON: " . ($data['category'] ?? 'N/A') . " | Product ID: " . ($data['product_id'] ?? 'N/A') . "\n";
}
