<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRODUCTS TABLE ===\n";
foreach (\App\Models\Product::all() as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | product_type: '{$p->product_type}' | type: '{$p->type}' | category_id: {$p->product_category_id}\n";
}

echo "\n=== CREATOR BIO BLOCKS TABLE ===\n";
foreach (\App\Models\CreatorBioBlock::all() as $b) {
    echo "ID: {$b->id} | Title: {$b->title} | Type: {$b->type} | Category: " . ($b->data_json['category'] ?? 'N/A') . "\n";
}
