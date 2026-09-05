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
        $rawOrderId = $payload['order_id'] ?? null;

        if (!$rawOrderId) {
            return response('OK', 200);
        }

        // Support BUYLE-{id}, ORD-xxx, or numeric ID
        $order = null;
        if (\Illuminate\Support\Str::startsWith($rawOrderId, 'BUYLE-')) {
            $id = (int) str_replace('BUYLE-', '', $rawOrderId);
            $order = Order::with('payment')->find($id);
        }
        if (!$order) {
            $order = Order::with('payment')->where('order_number', $rawOrderId)->first();
        }
        if (!$order && is_numeric($rawOrderId)) {
            $order = Order::with('payment')->find((int) $rawOrderId);
        }

        if (!$order) {
            Log::warning("[Webhook] Order not found for Midtrans notification", ['order_id' => $rawOrderId]);
            return response('OK', 200);
        }

        // Validasi Signature Key SHA-512
        $serverKey  = \App\Models\Setting::get('midtrans_server_key') ?: config('midtrans.server_key');
        $signature  = hash('sha512',
            ($payload['order_id'] ?? '') .
            ($payload['status_code'] ?? '') .
            ($payload['gross_amount'] ?? '') .
            $serverKey
        );

        if ($signature !== ($payload['signature_key'] ?? '')) {
            Log::warning('[Webhook] Invalid Midtrans signature', ['order' => $rawOrderId]);
            // Tetap 200 agar Midtrans tidak retry
            return response('OK', 200);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus       = $payload['fraud_status'] ?? '';

        $isPaid = (
            ($transactionStatus === 'capture' && $fraudStatus === 'accept') ||
            $transactionStatus === 'settlement'
        );

        if ($isPaid) {
            if ($order->payment) {
                DB::transaction(function () use ($order, $payload) {
                    $order->payment->update([
                        'status'                  => PaymentStatus::Success,
                        'paid_at'                 => now(),
                        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                        'method'                  => $payload['payment_type'] ?? null,
                        'raw_response'            => $payload,
                    ]);
                    $order->update([
                        'status' => OrderStatus::Confirmed,
                    ]);
                });

                // Run job synchronously so emails & user assignment happen immediately
                try {
                    ProcessSuccessfulOrderJob::dispatchSync(
                        orderId:    $order->id,
                        buyerEmail: $payload['custom_field1'] ?? ($order->user?->email ?? ''),
                        buyerName:  $payload['custom_field2'] ?? ($order->user?->name ?? 'Pembeli'),
                        buyerPhone: $payload['custom_field3'] ?? ($order->user?->phone ?? ''),
                    );
                } catch (\Throwable $e) {
                    Log::error('[Webhook] ProcessSuccessfulOrderJob sync error: ' . $e->getMessage());
                }
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            if ($order->payment) {
                $order->payment->update([
                    'status'       => $transactionStatus === 'expire' ? PaymentStatus::Expired : PaymentStatus::Failed,
                    'raw_response' => $payload,
                ]);
                if ($order->canBeCancelled()) {
                    $order->update(['status' => OrderStatus::Cancelled]);
                }
            }
        }

        return response('OK', 200);
    }
}
