<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$blocks = \App\Models\CreatorBioBlock::all();
foreach ($blocks as $b) {
    $data = $b->data_json ?? [];
    $title = $b->title ?? ($data['title'] ?? 'Untitled');
    $cat = $data['category'] ?? 'N/A';
    $pId = $data['product_id'] ?? 'N/A';
    echo "Block ID: {$b->id} | Type: {$b->type} | Title: {$title} | category in data_json: '{$cat}' | product_id: {$pId}\n";
}
