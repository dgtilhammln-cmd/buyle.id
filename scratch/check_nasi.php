<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$products = \App\Models\Product::with('category')->get();
foreach ($products as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Type: {$p->product_type} | Category: " . ($p->category ? $p->category->name : 'NONE') . "\n";
}
