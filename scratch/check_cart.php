<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CreatorBioBlock;
use App\Models\Product;
use App\Models\Cart;

echo "=== CARTS ===\n";
foreach (Cart::with('product')->get() as $c) {
    echo "Cart ID: {$c->id} | Product ID: {$c->product_id} | Product Name: " . ($c->product->name ?? 'N/A') . " | product_type: " . ($c->product->product_type ?? 'N/A') . " | type: " . ($c->product->type ?? 'N/A') . "\n";
}

echo "\n=== BIO BLOCKS ===\n";
foreach (CreatorBioBlock::all() as $b) {
    echo "Block ID: {$b->id} | Title: {$b->title} | Type: {$b->type} | Data: " . json_encode($b->data_json) . "\n";
}

echo "\n=== ALL PRODUCTS IN DB ===\n";
foreach (Product::all() as $p) {
    echo "Product ID: {$p->id} | Name: {$p->name} | product_type: '{$p->product_type}' | type: '{$p->type}'\n";
}
