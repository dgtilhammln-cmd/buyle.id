<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Jobs\ProcessSuccessfulOrderJob;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * PaymentWebhookController
 *
 * Menerima notifikasi dari Payment Gateway (Midtrans).
 * WAJIB membalas HTTP 200 dalam < 1 detik, lalu push ke Queue.
 */
class PaymentWebhookController extends Controller
{
    /**
     * Endpoint webhook Midtrans.
     * Route: POST /payment/webhook (dikecualikan dari CSRF)
     */
    public function midtrans(Request $request): Response
    {
        $payload = $request->all();
        $orderNumber = $payload['order_id'] ?? null;

        // Jawab 200 SEGERA agar tidak timeout
        // Proses berat dilakukan di queue background
        if (!$orderNumber) {
            return response('OK', 200);
        }

        // Validasi Signature Key SHA-512
        $serverKey  = config('midtrans.server_key');
        $signature  = hash('sha512',
            ($payload['order_id'] ?? '') .
            ($payload['status_code'] ?? '') .
            ($payload['gross_amount'] ?? '') .
            $serverKey
        );

        if ($signature !== ($payload['signature_key'] ?? '')) {
            Log::warning('[Webhook] Invalid Midtrans signature', ['order' => $orderNumber]);
            // Tetap 200 agar Midtrans tidak retry, tapi kita log & ignore
            return response('OK', 200);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus       = $payload['fraud_status'] ?? '';

        $isPaid = (
            ($transactionStatus === 'capture' && $fraudStatus === 'accept') ||
            $transactionStatus === 'settlement'
        );

        if ($isPaid) {
            // Update payment record secara sync (cepat, DB write saja)
            $order = Order::with('payment')->where('order_number', $orderNumber)->first();

            if ($order && $order->payment) {
                DB::transaction(function () use ($order, $payload) {
                    $order->payment->update([
                        'status'                  => PaymentStatus::Success,
                        'paid_at'                 => now(),
                        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                        'method'                  => $payload['payment_type'] ?? null,
                        'raw_response'            => $payload,
                    ]);
                });

                // Dispatch Job ke Queue — semua logika berat di background
                ProcessSuccessfulOrderJob::dispatch(
                    orderId:    $order->id,
                    buyerEmail: $payload['custom_field1'] ?? ($order->user?->email ?? ''),
                    buyerName:  $payload['custom_field2'] ?? ($order->user?->name ?? 'Pembeli'),
                    buyerPhone: $payload['custom_field3'] ?? ($order->user?->phone ?? ''),
                );
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order = Order::with('payment')->where('order_number', $orderNumber)->first();
            if ($order && $order->payment) {
                $order->payment->update([
                    'status'       => $transactionStatus === 'expire' ? PaymentStatus::Expired : PaymentStatus::Failed,
                    'raw_response' => $payload,
                ]);
                if ($order->canBeCancelled()) {
                    $order->update(['status' => OrderStatus::Cancelled]);
                }
            }
        }

        // Selalu return 200 agar Midtrans tidak retry berulang
        return response('OK', 200);
    }
}
