<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\CheckoutApiController;
use Illuminate\Http\Request;

$controller = new CheckoutApiController();

$couriersToTest = ['jne', 'jnt', 'sicepat', 'pos', 'tiki', 'custom'];

echo "=== TESTING CHECKOUT API COURIER COSTS ===\n";

foreach ($couriersToTest as $c) {
    $req = new Request([
        'destination' => 304,
        'weight'      => 1000,
        'courier'     => $c
    ]);
    
    $res = $controller->cost($req);
    echo "Courier '{$c}': Status {$res->getStatusCode()} -> Body: " . json_encode($res->getData()) . "\n\n";
}
