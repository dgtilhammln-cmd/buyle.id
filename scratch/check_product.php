<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = \App\Models\Product::with('category')->get();

echo "ALL PRODUCTS IN DATABASE:\n";
foreach ($products as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | product_type: {$p->product_type} | type: {$p->type} | Category: " . ($p->category->name ?? 'NULL') . "\n";
}
