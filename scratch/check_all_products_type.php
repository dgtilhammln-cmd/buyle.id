<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = \App\Models\Product::all();
foreach ($products as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | product_type: '{$p->product_type}' | CatID: {$p->product_category_id}\n";
}
