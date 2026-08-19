<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = \App\Models\Order::whereHas('shipment', function($q) {
    $q->where('tracking_number', 'JY1241520391');
})->first();

if (!$order) {
    $order = \App\Models\Order::whereHas('shipment', function($q) {
        $q->whereNotNull('tracking_number');
    })->latest()->first();
}

if(!$order) die("No order with tracking");
echo "Order: " . $order->order_number . "\n";
echo "AWB: " . $order->shipment->tracking_number . "\n";
echo "Courier: " . $order->shipment->courier_name . "\n";

$rawCourier = strtolower(trim($order->shipment->courier_name));
$courierCodeMap = [
    'j&t'       => 'jnt',
    'j&t express' => 'jnt',
    'jnt'       => 'jnt',
    'jne'       => 'jne',
    'sicepat'   => 'sicepat',
    'anteraja'  => 'anteraja',
    'pos'       => 'pos',
    'tiki'      => 'tiki',
    'wahana'    => 'wahana',
    'sap'       => 'sap',
    'lion'      => 'lion',
    'ninja'     => 'ninjaxpress',
    'ninjaxpress' => 'ninjaxpress',
];
$courierCode = $courierCodeMap[$rawCourier] ?? $rawCourier;
echo "Mapped Courier: " . $courierCode . "\n";

$apiKey = \App\Models\Setting::get('shipping_delivery_api_key') ?: \App\Models\Setting::get('rajaongkir_api_key');
$mode = \App\Models\Setting::get('komerce_mode', 'sandbox');
$isLive = ($mode === 'live');

echo "Mode: $mode | isLive: " . ($isLive ? 'true' : 'false') . "\n";
echo "API Key: " . substr($apiKey, 0, 5) . "...\n";

if ($isLive) {
    $baseUrl = 'https://api.collaborator.komerce.id';
    $trackUrl = $baseUrl . '/order/api/v1/orders/history-airway-bill';
    $response = \Illuminate\Support\Facades\Http::withoutVerifying()
        ->timeout(10)
        ->withHeaders(['x-api-key' => $apiKey, 'Accept' => 'application/json'])
        ->get($trackUrl, [
            'awb' => $order->shipment->tracking_number,
            'courier' => $courierCode,
        ]);
} else {
    $baseUrl = 'https://rajaongkir.komerce.id/api/v1';
    $trackUrl = $baseUrl . '/track/waybill?awb=' . urlencode($order->shipment->tracking_number) . '&courier=' . urlencode($courierCode);
    $response = \Illuminate\Support\Facades\Http::withoutVerifying()
        ->timeout(10)
        ->withHeaders(['key' => $apiKey, 'Accept' => 'application/json', 'Content-Type' => 'application/x-www-form-urlencoded'])
        ->post($trackUrl);
}

echo "Status: " . $response->status() . "\n";
echo "Response: " . substr($response->body(), 0, 300) . "\n";
