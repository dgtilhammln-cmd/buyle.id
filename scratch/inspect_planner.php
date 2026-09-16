<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRODUCTS ===\n";
foreach (App\Models\Product::orderBy('id', 'desc')->take(10)->get() as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | product_type: {$p->product_type} | Price: {$p->price}\n";
}

echo "\n=== BIO BLOCKS ===\n";
foreach (App\Models\CreatorBioBlock::orderBy('id', 'desc')->take(15)->get() as $b) {
    echo "ID: {$b->id} | Type: {$b->type} | Title: {$b->title} | Data: " . json_encode($b->data_json) . "\n";
}
