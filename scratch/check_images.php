<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$products = Product::whereNotNull('image')->take(20)->get(['id', 'name', 'image']);
foreach ($products as $p) {
    echo "ID {$p->id}: {$p->name} => [{$p->image}]\n";
}
