<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$counts = \App\Models\Product::select('product_type', \DB::raw('count(*) as total'))
    ->groupBy('product_type')
    ->get();
print_r($counts->toArray());
