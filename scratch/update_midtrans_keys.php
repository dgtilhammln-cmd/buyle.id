<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

$serverKey = base64_decode('TWlkLXNlcnZlci1sTWh6VkNnT0RPYmNVUjZrdF9jNmJmNkY=');
$clientKey = base64_decode('TWlkLWNsaWVudC1ld0VlZTdtU2VfYS1hSmJ3');
$merchantId = base64_decode('TTgxMTQxMzk3NQ==');

// Update DB Settings
Setting::set('midtrans_merchant_id', $merchantId);
Setting::set('midtrans_client_key', $clientKey);
Setting::set('midtrans_server_key', $serverKey);
Setting::set('midtrans_is_production', '1');

echo "✅ Database settings updated successfully!\n";

// Update .env file if it exists
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $content = file_get_contents($envFile);
    
    $replacements = [
        'MIDTRANS_SERVER_KEY' => $serverKey,
        'MIDTRANS_CLIENT_KEY' => $clientKey,
        'MIDTRANS_IS_PRODUCTION' => 'true',
        'MIDTRANS_MERCHANT_ID' => $merchantId,
    ];

    foreach ($replacements as $key => $val) {
        if (preg_match("/^{$key}=.*/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$val}", $content);
        } else {
            $content .= "\n{$key}={$val}";
        }
    }

    file_put_contents($envFile, $content);
    echo "✅ .env file updated successfully!\n";
}
