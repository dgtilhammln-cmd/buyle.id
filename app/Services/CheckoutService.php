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
use App\Models\Setting;
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

        // Proteksi: Creator tidak boleh melakukan checkout / membeli produk milik sendiri
        foreach ($items as $cartItem) {
            if ($cartItem->product) {
                $sellerId = $cartItem->product->seller_id;
                if ($sellerId && (int)$sellerId === (int)$user->id) {
                    throw new Exception('Sistem mendeteksi Anda mencoba membeli produk Anda sendiri ("' . $cartItem->product->name . '"). Creator tidak diperbolehkan melakukan checkout produk milik sendiri.');
                }
            }
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

        // Baca rate fee dari Setting (dapat diubah di Admin → Fee & Biaya)
        $platformFeeRate = (float) (Setting::get('platform_fee_rate', 5)) / 100;
        $adminFeeRate    = (float) (Setting::get('admin_fee_rate', 5))    / 100;

        // Hitung Platform Fee — biaya layanan platform, ditanggung buyer
        $platformFee = 0;
        foreach ($items as $cartItem) {
            if ($cartItem->product) {
                $platformFee += round($cartItem->subtotal * $platformFeeRate, 2);
            }
        }
        $platformFee = round($platformFee, 0);

        // Hitung Admin Fee — biaya administrasi, ditanggung buyer
        $adminFee = 0;
        foreach ($items as $cartItem) {
            if ($cartItem->product) {
                $adminFee += round($cartItem->subtotal * $adminFeeRate, 2);
            }
        }
        $adminFee = round($adminFee, 0);

        $total = max(0, $subtotal + $platformFee + $adminFee + $shippingCost - $discount);


        $notes = $data['notes'] ?? null;

        // Form Alamat Pengiriman untuk produk barang & Makanan/Jasa
        $shippingAddressArray = [];

        if ($summary['has_food_or_service']) {
            $fnbType = $data['fnb_service_type'] ?? 'delivery';
            $fnbReceiver = $data['guest_name'] ?? $user->name ?? 'Pembeli';
            $fnbPhone    = $data['guest_phone'] ?? $user->phone ?? '';

            if ($fnbType === 'dine_in') {
                $tableNo = !empty($data['fnb_table_number']) ? $data['fnb_table_number'] : 'Meja Kasir / Bebas';
                $shippingAddressArray = [
                    'receiver_name' => $fnbReceiver,
                    'phone'         => $fnbPhone,
                    'address'       => '🍽️ Makan di Tempat (Dine-In / Antar ke Meja) — No. Meja/Area: ' . $tableNo,
                    'label'         => 'Dine-In',
                ];
            } elseif ($fnbType === 'takeaway') {
                $pickupTime = !empty($data['fnb_pickup_time']) ? $data['fnb_pickup_time'] : 'Segera Diproses';
                $shippingAddressArray = [
                    'receiver_name' => $fnbReceiver,
                    'phone'         => $fnbPhone,
                    'address'       => '🛍️ Takeaway / Ambil Sendiri di Toko — Estimasi Jam: ' . $pickupTime,
                    'label'         => 'Takeaway',
                ];
            } else {
                $deliveryAddr = !empty($data['new_address_full']) ? $data['new_address_full'] : ($data['notes'] ?? 'Delivery Alamat');
                $shippingAddressArray = [
                    'receiver_name' => $fnbReceiver,
                    'phone'         => $fnbPhone,
                    'address'       => '🛵 Delivery / Antar ke Alamat: ' . $deliveryAddr,
                    'label'         => 'Delivery',
                ];
            }
        } elseif ($summary['has_physical_product']) {
            if (!empty($data['address_id']) && $data['address_id'] !== 'new') {
                $addrObj = Address::find($data['address_id']);
                if ($addrObj) {
                    $shippingAddressArray = [
                        'receiver_name' => $addrObj->receiver_name,
                        'phone'         => $addrObj->phone,
                        'province'      => $addrObj->province,
                        'city'          => $addrObj->city,
                        'district'      => $addrObj->district,
                        'postal_code'   => $addrObj->postal_code,
                        'address'       => $addrObj->address,
                        'label'         => $addrObj->label,
                    ];
                }
            }
            
            if (empty($shippingAddressArray)) {
                $shippingAddressArray = [
                    'receiver_name' => $data['new_address_receiver'] ?? ($data['guest_name'] ?? $user->name ?? ''),
                    'phone'         => $data['new_address_phone'] ?? ($data['guest_phone'] ?? $user->phone ?? ''),
                    'province'      => $data['new_address_province'] ?? '',
                    'city'          => $data['new_address_city'] ?? '',
                    'district'      => $data['new_address_district'] ?? '',
                    'postal_code'   => $data['new_address_postal'] ?? '',
                    'address'       => $data['new_address_full'] ?? '',
                    'label'         => $data['new_address_label'] ?? 'Rumah',
                ];

                if ($user->id) {
                    try {
                        Address::create([
                            'user_id'       => $user->id,
                            'label'         => $shippingAddressArray['label'],
                            'receiver_name' => $shippingAddressArray['receiver_name'],
                            'phone'         => $shippingAddressArray['phone'],
                            'province'      => $shippingAddressArray['province'],
                            'city'          => $shippingAddressArray['city'],
                            'district'      => $shippingAddressArray['district'],
                            'postal_code'   => $shippingAddressArray['postal_code'],
                            'address'       => $shippingAddressArray['address'],
                            'is_default'    => ($user->addresses()->count() === 0),
                        ]);
                    } catch (\Exception $ex) {
                        \Log::warning('Failed saving user address: ' . $ex->getMessage());
                    }
                }
            }
        } else {
            $shippingAddressArray = [
                'receiver_name' => $data['guest_name'] ?? ($user->name ?? 'Pembeli'),
                'phone'         => $data['guest_phone'] ?? ($user->phone ?? ''),
                'address'       => 'Pengiriman Otomatis via Email (Produk Digital / Tiket / Jasa Digital)',
                'label'         => 'Digital Direct Access',
            ];
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
                'platform_fee'     => $platformFee,
                'admin_fee'        => $adminFee,
                'discount'         => $discount,
                'total'            => $total,
                'shipping_address' => $shippingAddressArray,
                'notes'            => $notes,
                'utm_source'       => session('utm_source'),
                'utm_medium'       => session('utm_medium'),
                'utm_campaign'     => session('utm_campaign'),
                'utm_term'         => session('utm_term'),
                'utm_content'      => session('utm_content'),
            ]);

            // 2. Buat Order Items & Kurangi Stok secara aman dari Race Condition
            foreach ($items as $cartItem) {
                // Kunci baris produk di database untuk mencegah overselling / race condition
                $product = \App\Models\Product::where('id', $cartItem->product_id)->lockForUpdate()->first();
                if (!$product) {
                    throw new Exception("Produk \"{$cartItem->product_name}\" tidak ditemukan.");
                }

                // Proteksi race condition pada stok tiket & produk
                if (in_array($product->product_type, ['ticket', 'physical', 'external_link']) || $product->type === 'product') {
                    if ($product->stock !== null) {
                        if ((int)$product->stock < $cartItem->qty) {
                            throw new Exception("Stok produk \"{$product->name}\" tidak mencukupi (Sisa: {$product->stock}).");
                        }
                        $product->decrement('stock', $cartItem->qty);
                    }
                }
                $product->increment('sold_count', $cartItem->qty);

                OrderItem::create([
                    'order_id'         => $order->id,
                    'product_id'       => $product->id,
                    'variant_value_id' => $cartItem->variant_value_id,
                    'product_name'     => $product->name,
                    'variant_name'     => $cartItem->variantValue?->value,
                    'price'            => $cartItem->unit_price,
                    'qty'              => $cartItem->qty,
                    'subtotal'         => $cartItem->subtotal,
                ]);
            }

            // 3. Buat Shipment jika ada pengiriman fisik
            if ($summary['has_physical_product'] && !empty($data['courier_name'])) {
                Shipment::create([
                    'order_id'        => $order->id,
                    'courier_name'    => strtoupper($data['courier_name']),
                    'courier_service' => strtoupper($data['courier_service'] ?? 'REG'),
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
        $midtransOrderId = $payload['order_id'] ?? null;
        if (!$midtransOrderId) return false;

        // Format baru: 'BUYLE-{order.id}' → lookup by DB id
        if (str_starts_with($midtransOrderId, 'BUYLE-')) {
            $orderId = (int) str_replace('BUYLE-', '', $midtransOrderId);
            $order = Order::find($orderId);
        } else {
            // Format lama: order_number langsung (fallback)
            $order = Order::where('order_number', $midtransOrderId)->first();
        }

        if (!$order) return false;

        $payment = $order->payment;
        if (!$payment) return false;

        // Validasi Signature Key (SHA512)
        $serverKey = \App\Models\Setting::get('midtrans_server_key') ?: config('midtrans.server_key');
        $signatureKey = hash("sha512", $midtransOrderId . $payload['status_code'] . $payload['gross_amount'] . $serverKey);
        
        if ($signatureKey !== ($payload['signature_key'] ?? '')) {
            \Log::warning('Midtrans Invalid Signature Key', ['midtrans_order_id' => $midtransOrderId]);
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

            // Generate TicketPass jika terdapat produk bertipe ticket
            foreach ($order->items as $item) {
                if ($item->product && $item->product->product_type === 'ticket') {
                    for ($i = 0; $i < $item->qty; $i++) {
                        \App\Models\TicketPass::create([
                            'order_id'      => $order->id,
                            'order_item_id' => $item->id,
                            'product_id'    => $item->product_id,
                            'user_id'       => $order->user_id,
                            'seller_id'     => $item->product->seller_id,
                            'holder_name'   => $order->user?->name ?? 'Pemegang Tiket',
                            'holder_email'  => $order->user?->email,
                            'holder_phone'  => $order->user?->phone,
                            'status'        => 'valid',
                        ]);
                    }
                }
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
