<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

echo "--- MIDTRANS DB SETTINGS ---\n";
echo "Server Key: " . Setting::get('midtrans_server_key') . "\n";
echo "Client Key: " . Setting::get('midtrans_client_key') . "\n";
echo "Is Production: " . Setting::get('midtrans_is_production') . "\n";

echo "--- MIDTRANS ENV CONFIG ---\n";
echo "Server Key ENV: " . config('midtrans.server_key') . "\n";
echo "Client Key ENV: " . config('midtrans.client_key') . "\n";
echo "Is Production ENV: " . (config('midtrans.is_production') ? 'true' : 'false') . "\n";
