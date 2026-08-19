<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Exception;

class MidtransService
{
    public function __construct()
    {
        // Read keys from DB settings (admin panel) with .env fallback
        $serverKey  = \App\Models\Setting::get('midtrans_server_key')  ?: config('midtrans.server_key');
        $clientKey  = \App\Models\Setting::get('midtrans_client_key')  ?: config('midtrans.client_key');
        $isProd     = \App\Models\Setting::get('midtrans_is_production') ?? config('midtrans.is_production');

        Config::$serverKey    = $serverKey;
        Config::$clientKey    = $clientKey;
        Config::$isProduction = (bool)(int)$isProd;
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Membuat Snap Token untuk pesanan.
     */
    public function createSnapToken(Order $order): ?string
    {
        // Load items
        $order->loadMissing('items.product');

        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id'       => $item->product_id ? (string) $item->product_id : 'ITEM-' . $item->id,
                'price'    => (int) $item->price,
                'quantity' => (int) $item->qty,
                'name'     => substr($item->full_name, 0, 50),
            ];
        }

        // Jika ada ongkir
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $order->shipping_cost,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];
        }

        // Jika ada diskon
        if ($order->discount > 0) {
            $itemDetails[] = [
                'id'       => 'DISCOUNT',
                'price'    => -((int) $order->discount),
                'quantity' => 1,
                'name'     => 'Diskon',
            ];
        }

        $customerDetails = [
            'first_name' => $order->user->name,
            'email'      => $order->user->email,
        ];

        if (!empty($order->shipping_address)) {
            $customerDetails['phone'] = $order->shipping_address['phone'] ?? '';
            $customerDetails['shipping_address'] = [
                'first_name'   => $order->shipping_address['receiver_name'] ?? '',
                'phone'        => $order->shipping_address['phone'] ?? '',
                'address'      => $order->shipping_address['full_address'] ?? '',
                'city'         => $order->shipping_address['city'] ?? '',
                'postal_code'  => $order->shipping_address['postal_code'] ?? '',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => $customerDetails,
            'item_details'     => $itemDetails,
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (Exception $e) {
            \Log::error('Midtrans Snap Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Membatalkan transaksi di Midtrans.
     */
    public function cancelTransaction(string $orderNumber): bool
    {
        try {
            Transaction::cancel($orderNumber);
            return true;
        } catch (Exception $e) {
            \Log::error('Midtrans Cancel Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cek status transaksi di Midtrans.
     */
    public function getTransactionStatus(string $orderNumber)
    {
        try {
            return Transaction::status($orderNumber);
        } catch (Exception $e) {
            \Log::error('Midtrans Status Error: ' . $e->getMessage());
            return null;
        }
    }
}
