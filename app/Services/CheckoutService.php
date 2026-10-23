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
    public function processCheckout(array $data, ?User $user): Order
    {
        $summary = $this->cartService->getSummary();
        $items = $summary['items'];

        if ($items->isEmpty()) {
            throw new Exception('Keranjang belanja Anda kosong.');
        }

        // Proteksi: Creator tidak boleh melakukan checkout / membeli produk milik sendiri
        if ($user) {
            foreach ($items as $cartItem) {
                if ($cartItem->product) {
                    $sellerId = $cartItem->product->seller_id;
                    if ($sellerId && (int)$sellerId === (int)$user->id) {
                        throw new Exception('Sistem mendeteksi Anda mencoba membeli produk Anda sendiri ("' . $cartItem->product->name . '"). Creator tidak diperbolehkan melakukan checkout produk milik sendiri.');
                    }
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

        // Konstanta fee khusus produk tipe tiket
        $ticketAdminFeePerQty = 5000;   // Rp 5.000 flat per tiket
        $ticketPlatformFeeRate = 0.05;  // 5% dari harga tiket

        // Hitung Platform Fee & Admin Fee — per item, berbeda skema untuk ticket vs produk lain
        $platformFee = 0;
        $adminFee    = 0;
        foreach ($items as $cartItem) {
            if (!$cartItem->product) {
                continue;
            }
            $productType = $cartItem->product->product_type ?? $cartItem->product->type ?? '';
            if ($productType === 'ticket') {
                // Skema tiket: admin fee Rp 5.000/tiket (flat), platform fee 5% dari subtotal
                $platformFee += round($cartItem->subtotal * $ticketPlatformFeeRate, 2);
                $adminFee    += $ticketAdminFeePerQty * $cartItem->qty;
            } else {
                // Skema umum: persentase dari subtotal per produk
                $platformFee += round($cartItem->subtotal * $platformFeeRate, 2);
                $adminFee    += round($cartItem->subtotal * $adminFeeRate, 2);
            }
        }
        $platformFee = round($platformFee, 0);
        $adminFee    = round($adminFee, 0);

        $total = max(0, $subtotal + $platformFee + $adminFee + $shippingCost - $discount);


        $notes = $data['notes'] ?? null;

        // Form Alamat Pengiriman untuk produk barang & Makanan/Jasa
        $shippingAddressArray = [];

        if ($summary['has_food_or_service']) {
            $fnbType = $data['fnb_service_type'] ?? 'delivery';
            $fnbReceiver = $data['guest_name'] ?? $user?->name ?? 'Pembeli';
            $fnbPhone    = $data['guest_phone'] ?? $user?->phone ?? '';

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
                    'receiver_name' => $data['new_address_receiver'] ?? ($data['guest_name'] ?? $user?->name ?? ''),
                    'phone'         => $data['new_address_phone'] ?? ($data['guest_phone'] ?? $user?->phone ?? ''),
                    'province'      => $data['new_address_province'] ?? '',
                    'city'          => $data['new_address_city'] ?? '',
                    'district'      => $data['new_address_district'] ?? '',
                    'postal_code'   => $data['new_address_postal'] ?? '',
                    'address'       => $data['new_address_full'] ?? '',
                    'label'         => $data['new_address_label'] ?? 'Rumah',
                ];

                if ($user && $user->id) {
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
                'receiver_name' => $data['guest_name'] ?? ($user?->name ?? 'Pembeli'),
                'phone'         => $data['guest_phone'] ?? ($user?->phone ?? ''),
                'address'       => 'Pengiriman Otomatis via Email (Produk Digital / Tiket / Jasa Digital)',
                'label'         => 'Digital Direct Access',
            ];
        }

        // Simpan input nama-nama pemegang tiket (ticket_holders) ke snapshot order
        $ticketHoldersInput = $data['ticket_holders'] ?? request()->input('ticket_holders', []);
        if (!empty($ticketHoldersInput)) {
            $shippingAddressArray['ticket_holders'] = $ticketHoldersInput;
        }

        // Simpan input NIK pemegang tiket (ticket_niks) ke snapshot order
        $ticketNiksInput = $data['ticket_niks'] ?? request()->input('ticket_niks', []);
        if (!empty($ticketNiksInput)) {
            $shippingAddressArray['ticket_niks'] = $ticketNiksInput;
        }

        DB::beginTransaction();

        try {
            // 1. Buat Order
            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'user_id'          => $user?->id ?? null,
                'customer_email'   => $user?->email ?? ($data['guest_email'] ?? null),
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
                'utm_campaign'     => session('utm_campaign') ?: (session('ig_automation_campaign_id') ? 'ig_camp_' . session('ig_automation_campaign_id') : null),
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

                // Proteksi Ticket War: Penjualan belum dibuka
                if ($product->product_type === 'ticket' && !empty($product->ticket_war_start)) {
                    if (now()->lt($product->ticket_war_start)) {
                        throw new Exception("Penjualan tiket \"{$product->name}\" baru akan dibuka pada " . $product->ticket_war_start->format('d M Y, H:i') . " WIB.");
                    }
                }

                // Proteksi Anti-Bot & Calo: Batasan maksimal tiket per transaksi
                if ($product->product_type === 'ticket') {
                    $maxPerOrder = $product->max_tickets_per_order ?: 4;
                    if ($cartItem->qty > $maxPerOrder) {
                        throw new Exception("Maksimal pembelian tiket \"{$product->name}\" adalah {$maxPerOrder} tiket per transaksi.");
                    }
                }

                // Proteksi race condition pada stok tiket & produk (Atomic Safe Decrement)
                if (in_array($product->product_type, ['ticket', 'physical', 'external_link']) || $product->type === 'product') {
                    if ($product->stock !== null) {
                        $affected = \App\Models\Product::where('id', $product->id)
                            ->where('stock', '>=', $cartItem->qty)
                            ->decrement('stock', $cartItem->qty);

                        if ($affected === 0) {
                            $latestStock = (int) \App\Models\Product::where('id', $product->id)->value('stock');
                            if ($latestStock === 0) {
                                throw new Exception("Maaf, kuota tiket \"{$product->name}\" baru saja habis terjual!");
                            } else {
                                throw new Exception("Stok tiket \"{$product->name}\" tidak mencukupi (Tersisa: {$latestStock}).");
                            }
                        }
                    }
                }
                
                // Proteksi race condition pada stok variasi jika dipilih
                if ($cartItem->variant_value_id) {
                    $variantVal = \App\Models\ProductVariantValue::where('id', $cartItem->variant_value_id)->lockForUpdate()->first();
                    if ($variantVal && $variantVal->stock !== null) {
                        if ((int)$variantVal->stock < $cartItem->qty) {
                            throw new Exception("Stok variasi \"{$variantVal->value}\" tidak mencukupi (Sisa: {$variantVal->stock}).");
                        }
                        $variantVal->decrement('stock', $cartItem->qty);
                    }
                }

                $product->increment('sold_count', $cartItem->qty);

                // Margin Split Calculation (White Label & Reseller)
                $sellerId = $product->seller_id;
                $resellerId = null;
                $baseWhitelabelPrice = null;
                $resellerMargin = 0;
                $creatorEarnings = $cartItem->subtotal;

                if (!empty($cartItem->reseller_id) && (int)$cartItem->reseller_id !== (int)$sellerId) {
                    $resellerId = (int)$cartItem->reseller_id;
                    $baseWhitelabelPrice = $product->sale_price ?: ($product->whitelabel_price ?: $product->price);
                    $resellerMargin = max(0, ($cartItem->unit_price - $baseWhitelabelPrice) * $cartItem->qty);
                    $creatorEarnings = $baseWhitelabelPrice * $cartItem->qty;
                }

                // Affiliate Split Calculation (Afiliasi Toko Bio)
                $affiliateId = null;
                $affiliateCommissionRate = 0.00;
                $affiliateCommissionAmount = 0.00;

                // Resolusi: dari keranjang -> fallback ke session -> fallback ke cookie
                $candidateAffId = $cartItem->affiliate_id 
                    ?? session('buyle_aff_' . $product->id) 
                    ?? request()->cookie('buyle_aff_' . $product->id);

                if ($candidateAffId && (int)$candidateAffId !== (int)$sellerId) {
                    // Proteksi Anti Self-Referral: pembeli bukan affiliator itu sendiri
                    if (!$user || (int)$user->id !== (int)$candidateAffId) {
                        $rate = (float)($product->affiliate_commission_rate ?? 0);
                        if ($rate > 0) {
                            $affiliateId = (int)$candidateAffId;
                            $affiliateCommissionRate = $rate;
                            $affiliateCommissionAmount = round(($cartItem->subtotal * $rate) / 100, 2);
                            // Potong komisi affiliate dari creator earnings agar tidak minus/double bayar
                            $creatorEarnings = max(0, $creatorEarnings - $affiliateCommissionAmount);
                        }
                    }
                }

                $variantLabel = null;
                if ($cartItem->variantValue) {
                    $optName = $cartItem->variantValue->option?->name;
                    $variantLabel = $optName ? "{$optName}: {$cartItem->variantValue->value}" : $cartItem->variantValue->value;
                }

                OrderItem::create([
                    'order_id'                    => $order->id,
                    'product_id'                  => $product->id,
                    'variant_value_id'            => $cartItem->variant_value_id,
                    'seller_id'                   => $sellerId,
                    'reseller_id'                 => $resellerId,
                    'affiliate_id'                => $affiliateId,
                    'product_name'                => $product->name,
                    'variant_name'                => $variantLabel ?? $cartItem->variantValue?->value,
                    'price'                       => $cartItem->unit_price,
                    'base_whitelabel_price'       => $baseWhitelabelPrice,
                    'reseller_margin'             => $resellerMargin,
                    'creator_earnings'            => $creatorEarnings,
                    'affiliate_commission_rate'   => $affiliateCommissionRate,
                    'affiliate_commission_amount' => $affiliateCommissionAmount,
                    'qty'                         => $cartItem->qty,
                    'subtotal'                    => $cartItem->subtotal,
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
                    'user_id'         => $user?->id ?? null,
                    'discount_amount' => $discount,
                ]);
                $coupon->increment('used_count');
            }

            // 5. Kosongkan Cart
            $this->cartService->emptyCart();

            // =========================================================
            // GRATIS / Rp 0 → Bypass Midtrans, langsung Confirmed
            // =========================================================
            if ($total <= 0) {
                Payment::create([
                    'order_id'       => $order->id,
                    'payment_number' => Payment::generatePaymentNumber(),
                    'gateway'        => 'free',
                    'status'         => PaymentStatus::Success,
                    'amount'         => 0,
                    'paid_at'        => now(),
                ]);

                // Trigger post-order logic (status confirmed, tiket, shipment)
                $this->markOrderConfirmed($order);

                DB::commit();

                // Kirim email notifikasi ke buyer
                $buyerEmail = $user?->email ?? $order->customer_email ?? ($data['guest_email'] ?? null);
                $buyerName  = $user?->name ?? ($order->shipping_address['receiver_name'] ?? ($data['guest_name'] ?? 'Pembeli'));
                $buyerPhone = $user?->phone ?? ($order->shipping_address['phone'] ?? ($data['guest_phone'] ?? ''));
                if ($buyerEmail) {
                    try {
                        \App\Jobs\ProcessSuccessfulOrderJob::dispatchSync(
                            orderId:    $order->id,
                            buyerEmail: $buyerEmail,
                            buyerName:  $buyerName,
                            buyerPhone: $buyerPhone,
                        );
                    } catch (\Throwable $e) {
                        \Log::error("ProcessSuccessfulOrderJob (free order) error: " . $e->getMessage());
                    }
                }

                return $order->fresh();
            }


            // 6. Minta Snap Token dari Midtrans (hanya jika total > 0)
            $snapToken = $this->midtransService->createSnapToken($order);

            if (!$snapToken) {
                throw new Exception('Gagal mendapatkan token pembayaran dari Midtrans.');
            }

            // 7. Buat Record Payment (Midtrans)
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
            $ticketHoldersSnapshot = $order->shipping_address['ticket_holders'] ?? [];
            $ticketNiksSnapshot    = $order->shipping_address['ticket_niks'] ?? [];

            foreach ($order->items as $item) {
                if ($item->product && $item->product->product_type === 'ticket') {
                    $holdersForProduct = $ticketHoldersSnapshot[$item->product_id] ?? [];
                    $niksForProduct    = $ticketNiksSnapshot[$item->product_id] ?? [];

                    for ($i = 0; $i < $item->qty; $i++) {
                        $customHolder = !empty($holdersForProduct[$i]) ? trim($holdersForProduct[$i]) : null;
                        $customNik    = !empty($niksForProduct[$i]) ? trim($niksForProduct[$i]) : null;

                        \App\Models\TicketPass::create([
                            'order_id'      => $order->id,
                            'order_item_id' => $item->id,
                            'product_id'    => $item->product_id,
                            'user_id'       => $order->user_id,
                            'seller_id'     => $item->product->seller_id,
                            'holder_name'   => $customHolder ?: ($order->user?->name ?? ($order->shipping_address['receiver_name'] ?? 'Pemegang Tiket')),
                            'holder_email'  => $order->user?->email ?? $order->customer_email,
                            'holder_phone'  => $order->user?->phone ?? ($order->shipping_address['phone'] ?? null),
                            'holder_nik'    => $customNik,
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
