<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\User;
use App\Models\Address;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutService
{
    protected CartService $cartService;
    protected MidtransService $midtransService;

    public function __construct(CartService $cartService, MidtransService $midtransService)
    {
        $this->cartService = $cartService;
        $this->midtransService = $midtransService;
    }

    /**
     * Memproses checkout: validasi, buat order, item, payment, kurangi stok, kosongkan cart.
     */
    public function processCheckout(array $data, User $user): Order
    {
        $summary = $this->cartService->getSummary();
        $items = $summary['items'];

        if ($items->isEmpty()) {
            throw new Exception('Keranjang belanja Anda kosong.');
        }

        // Kalkulasi harga
        $subtotal = $summary['subtotal'];
        $shippingCost = $data['shipping_cost'] ?? 0;
        $discount = 0;
        $coupon = null;

        // Validasi dan hitung diskon kupon
        if (!empty($data['coupon_code'])) {
            $coupon = Coupon::byCode($data['coupon_code'])->first();
            if ($coupon) {
                $coupon->validate($subtotal);
                
                // Cek pemakaian oleh user
                $used = CouponUsage::where('coupon_id', $coupon->id)
                                   ->where('user_id', $user->id)
                                   ->exists();
                if ($used) {
                    throw new Exception('Anda sudah pernah menggunakan kupon ini.');
                }

                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                throw new Exception('Kode kupon tidak valid.');
            }
        }

        $total = max(0, $subtotal + $shippingCost - $discount);

        // Siapkan snapshot alamat pengiriman
        $shippingSnapshot = [];
        if ($summary['has_physical_product']) {
            if (!empty($data['address_id']) && $data['address_id'] !== 'new') {
                $address = Address::where('user_id', $user->id)->findOrFail($data['address_id']);
                $shippingSnapshot = $address->toShippingSnapshot();
            } else {
                // Buat alamat baru untuk user ini
                $address = Address::create([
                    'user_id'       => $user->id,
                    'label'         => 'Rumah', // Default
                    'receiver_name' => $data['new_address_receiver'],
                    'phone'         => $data['new_address_phone'],
                    'province'      => $data['new_address_province'],
                    'city'          => $data['new_address_city'],
                    'district'      => $data['new_address_district'],
                    'postal_code'   => $data['new_address_postal'],
                    'full_address'  => $data['new_address_full'],
                    'latitude'      => $data['new_address_lat'] ?? null,
                    'longitude'     => $data['new_address_lng'] ?? null,
                    'is_default'    => $user->addresses()->count() === 0 ? true : false,
                ]);
                $shippingSnapshot = $address->toShippingSnapshot();
            }
        }

        // Jika ada produk jasa, tambahkan alamat pengerjaan ke notes atau struktur khusus
        $notes = $data['notes'] ?? null;
        if ($summary['has_service'] && !empty($data['service_address'])) {
            $serviceNote = "Alamat Pengerjaan Jasa:\n" . $data['service_address'];
            $notes = $notes ? $notes . "\n\n" . $serviceNote : $serviceNote;
        }

        DB::beginTransaction();

        try {
            // 1. Buat Order
            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'user_id'          => $user->id,
                'status'           => OrderStatus::Pending,
                'subtotal'         => $subtotal,
                'shipping_cost'    => $shippingCost,
                'discount'         => $discount,
                'total'            => $total,
                'shipping_address' => $shippingSnapshot,
                'notes'            => $notes,
            ]);

            // 2. Buat Order Items & Kurangi Stok
            foreach ($items as $cartItem) {
                $product = $cartItem->product;

                // Kurangi stok (akan dilewati jika tipe service di dalam method)
                $product->reduceStock($cartItem->qty);
                $product->addSoldCount($cartItem->qty);

                OrderItem::create([
                    'order_id'         => $order->id,
                    'product_id'       => $cartItem->product_id,
                    'variant_value_id' => $cartItem->variant_value_id,
                    'product_name'     => $product->name,
                    'variant_name'     => $cartItem->variantValue?->value,
                    'price'            => $cartItem->unit_price,
                    'qty'              => $cartItem->qty,
                    'subtotal'         => $cartItem->subtotal,
                ]);
            }

            // 3. Buat Pengiriman (Shipment)
            if ($summary['has_physical_product'] && !empty($data['courier_name'])) {
                Shipment::create([
                    'order_id'        => $order->id,
                    'courier_name'    => $data['courier_name'],
                    'courier_service' => $data['courier_service'] ?? null,
                    'status'          => \App\Enums\ShipmentStatus::Pending,
                ]);
            }

            // 4. Catat Pemakaian Kupon
            if ($coupon && $discount > 0) {
                CouponUsage::create([
                    'coupon_id'       => $coupon->id,
                    'order_id'        => $order->id,
                    'user_id'         => $user->id,
                    'discount_amount' => $discount,
                ]);
                $coupon->increment('used_count');
            }

            // 5. Kosongkan Cart
            $this->cartService->emptyCart();

            // 5. Minta Snap Token dari Midtrans
            $snapToken = $this->midtransService->createSnapToken($order);

            if (!$snapToken) {
                throw new Exception('Gagal mendapatkan token pembayaran dari Midtrans.');
            }

            // 6. Buat Record Payment
            Payment::create([
                'order_id'       => $order->id,
                'payment_number' => Payment::generatePaymentNumber(),
                'gateway'        => 'midtrans',
                'status'         => PaymentStatus::Pending,
                'amount'         => $total,
                'midtrans_token' => $snapToken,
                'expired_at'     => now()->addDay(), // default Midtrans 24 jam
            ]);

            DB::commit();

            return $order;

        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle webhook callback dari Midtrans
     */
    public function handleCallback(array $payload): bool
    {
        $orderNumber = $payload['order_id'] ?? null;
        if (!$orderNumber) return false;

        $order = Order::where('order_number', $orderNumber)->first();
        if (!$order) return false;

        $payment = $order->payment;
        if (!$payment) return false;

        // Validasi Signature Key (SHA512)
        $serverKey = config('midtrans.server_key');
        $signatureKey = hash("sha512", $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . $serverKey);
        
        if ($signatureKey !== ($payload['signature_key'] ?? '')) {
            \Log::warning('Midtrans Invalid Signature Key', ['order' => $orderNumber]);
            return false;
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? '';

        DB::beginTransaction();

        try {
            $payment->update([
                'raw_response'            => $payload,
                'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                'method'                  => $payload['payment_type'] ?? null,
            ]);

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'challenge') {
                    // Pending review
                    $payment->update(['status' => PaymentStatus::Pending]);
                } else {
                    $payment->update([
                        'status'  => PaymentStatus::Success,
                        'paid_at' => now()
                    ]);
                    $this->markOrderConfirmed($order);
                }
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $status = ($transactionStatus == 'expire') ? PaymentStatus::Expired : PaymentStatus::Failed;
                $payment->update(['status' => $status]);
                $this->markOrderCancelled($order);
            } else if ($transactionStatus == 'pending') {
                $payment->update(['status' => PaymentStatus::Pending]);
            }

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Midtrans Callback Process Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Set order menjadi Confirmed, dispatch event, dan buat Shipment jika ada produk fisik.
     */
    protected function markOrderConfirmed(Order $order): void
    {
        if ($order->status !== OrderStatus::Confirmed) {
            $order->update(['status' => OrderStatus::Confirmed]);
            
            // Cek apakah ada produk fisik untuk membuat shipment
            $hasPhysical = false;
            foreach ($order->items as $item) {
                if ($item->product && $item->product->type !== 'service') {
                    $hasPhysical = true;
                    break;
                }
            }

            if ($hasPhysical) {
                Shipment::firstOrCreate([
                    'order_id' => $order->id
                ], [
                    'courier_name' => 'Internal / Ekspedisi',
                    'status'       => \App\Enums\ShipmentStatus::Pending,
                ]);
            }

            event(new \App\Events\OrderStatusUpdated($order));
        }
    }

    /**
     * Set order menjadi Cancelled dan kembalikan stok.
     */
    protected function markOrderCancelled(Order $order): void
    {
        if ($order->canBeCancelled()) {
            $order->update(['status' => OrderStatus::Cancelled]);
            
            // Kembalikan stok
            foreach ($order->items as $item) {
                if ($item->product && $item->product->type === 'product') {
                    $item->product->increment('stock', $item->qty);
                    $item->product->decrement('sold_count', $item->qty);
                }
            }

            event(new \App\Events\OrderStatusUpdated($order));
        }
    }
}
