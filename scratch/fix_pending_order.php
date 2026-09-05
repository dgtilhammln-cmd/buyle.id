<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Services\MidtransService;

$order = Order::with(['payment', 'user'])->where('order_number', 'ORD-20260905-0002')->first();

if (!$order) {
    echo "❌ Order ORD-20260905-0002 not found.\n";
    exit;
}

echo "Order Number: {$order->order_number}\n";
echo "Current Order Status: {$order->status->value}\n";
echo "Current Payment Status: " . ($order->payment ? $order->payment->status->value : 'No payment record') . "\n";

$midtrans = app(MidtransService::class);
$midtransOrderId = 'BUYLE-' . $order->id;
echo "Checking Midtrans status for order_id: {$midtransOrderId}...\n";

$status = $midtrans->getTransactionStatus($midtransOrderId);
if (!$status) {
    echo "Checking Midtrans status for order_number: {$order->order_number}...\n";
    $status = $midtrans->getTransactionStatus($order->order_number);
}

if ($status) {
    echo "Midtrans Status Response: " . json_encode($status) . "\n";
} else {
    echo "⚠️ Midtrans status check returned null (No transaction in Midtrans for this ID).\n";
}
