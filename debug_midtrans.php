<?php
define('LARAVEL_START', microtime(true));
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$skey = \App\Models\Setting::get('midtrans_server_key');
$ckey = \App\Models\Setting::get('midtrans_client_key');
$prod = \App\Models\Setting::get('midtrans_is_production');
echo 'Server key prefix: ' . substr($skey, 0, 15) . PHP_EOL;
echo 'Client key prefix: ' . substr($ckey, 0, 15) . PHP_EOL;
echo 'Is Production: ' . $prod . PHP_EOL;

\Midtrans\Config::$serverKey    = $skey;
\Midtrans\Config::$clientKey    = $ckey;
\Midtrans\Config::$isProduction = (bool)(int)$prod;
\Midtrans\Config::$isSanitized  = true;
\Midtrans\Config::$is3ds        = true;

$params = [
    'transaction_details' => [
        'order_id'     => 'DOMAIN-TEST-' . time(),
        'gross_amount' => 532889,
    ],
    'customer_details' => [
        'first_name' => 'Test Creator',
        'email'      => 'test@buyle.id',
    ],
    'item_details' => [[
        'id'       => 'DOM-TEST',
        'price'    => 532889,
        'quantity' => 1,
        'name'     => 'Custom Domain: test.co.id',
    ]],
];

try {
    $tok = \Midtrans\Snap::getSnapToken($params);
    echo 'TOKEN OK: ' . $tok . PHP_EOL;
} catch (\Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
