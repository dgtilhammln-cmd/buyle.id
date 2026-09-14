<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$couriers = \App\Models\Courier::all();
echo "Couriers count: " . $couriers->count() . "\n";
foreach ($couriers as $c) {
    echo "ID: {$c->id} | Name: {$c->name} | Code: {$c->code} | Active: {$c->is_active}\n";
}
