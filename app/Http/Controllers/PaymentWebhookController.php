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

        // Support DOMAIN-xxx for Domain Orders
        if (\Illuminate\Support\Str::startsWith($rawOrderId, 'DOMAIN-')) {
            return $this->handleDomainWebhook($payload, $rawOrderId);
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
            $alreadyProcessed = false;
            DB::transaction(function () use ($order, $payload, &$alreadyProcessed) {
                $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();
                if (!$lockedOrder) return;

                if ($lockedOrder->status === OrderStatus::Confirmed) {
                    $alreadyProcessed = true;
                    return;
                }

                if ($lockedOrder->payment) {
                    $lockedOrder->payment->update([
                        'status'                  => PaymentStatus::Success,
                        'paid_at'                 => now(),
                        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                        'method'                  => $payload['payment_type'] ?? null,
                        'raw_response'            => $payload,
                    ]);
                }

                $lockedOrder->update([
                    'status' => OrderStatus::Confirmed,
                ]);
            });

            if (!$alreadyProcessed) {

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

    /**
     * Handle Webhook Notifikasi Pembelian Domain Creator dari Midtrans
     */
    protected function handleDomainWebhook(array $payload, string $rawOrderId): Response
    {
        // Validasi Signature Key SHA-512
        $serverKey = \App\Models\Setting::get('midtrans_server_key') ?: config('midtrans.server_key');
        $signature = hash('sha512',
            ($payload['order_id'] ?? '') .
            ($payload['status_code'] ?? '') .
            ($payload['gross_amount'] ?? '') .
            $serverKey
        );

        if ($signature !== ($payload['signature_key'] ?? '')) {
            Log::warning('[Webhook Domain] Invalid Midtrans signature', ['order' => $rawOrderId]);
            return response('OK', 200);
        }

        // Parse DomainOrder ID
        $parts = explode('-', $rawOrderId);
        $domainOrderId = isset($parts[1]) ? (int)$parts[1] : 0;
        $domainOrder = \App\Models\DomainOrder::with('user')->find($domainOrderId);

        if (!$domainOrder) {
            Log::warning("[Webhook Domain] DomainOrder not found ID: {$domainOrderId}");
            return response('OK', 200);
        }

        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus       = $payload['fraud_status'] ?? '';

        $isPaid = (
            ($transactionStatus === 'capture' && $fraudStatus === 'accept') ||
            $transactionStatus === 'settlement'
        );

        if ($isPaid) {
            if ($domainOrder->status !== 'paid') {
                $domainOrder->update([
                    'status'                  => 'paid',
                    'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                ]);

                $user = $domainOrder->user;
                $formattedAmount = 'Rp ' . number_format($domainOrder->amount, 0, ',', '.');

                // 1. Kirim Email Bukti Pembelian Domain ke Creator
                if ($user && !empty($user->email)) {
                    try {
                        \Illuminate\Support\Facades\Mail::html("
                            <div style='font-family:sans-serif; max-width:600px; margin:0 auto; padding:20px; border:1px solid #e2e8f0; border-radius:12px;'>
                                <h2 style='color:#166534;'>Konfirmasi Pembelian Custom Domain</h2>
                                <p>Halo <strong>{$user->name}</strong>,</p>
                                <p>Pembayaran untuk pembelian custom domain <strong>{$domainOrder->domain_name}</strong> sebesar <strong>{$formattedAmount}</strong> telah BERHASIL diterima.</p>
                                <div style='background:#f0fdf4; padding:15px; border-radius:8px; margin:15px 0;'>
                                    <strong>Rincian Pesanan Domain:</strong><br>
                                    • Domain: <strong>{$domainOrder->domain_name}</strong><br>
                                    • Total Pembayaran: <strong>{$formattedAmount}</strong><br>
                                    • Status: <span style='color:#166534; font-weight:bold;'>LUNAS (Paid)</span><br>
                                    • Transaction ID: {$domainOrder->midtrans_transaction_id}
                                </div>
                                <p>Tim Admin Buyle.id akan segera memproses pendaftaran & pemetaan DNS domain Anda ke halaman Link in Bio toko Anda.</p>
                                <hr style='border:none; border-top:1px solid #e2e8f0; margin:20px 0;'>
                                <p style='font-size:12px; color:#64748b;'>Buyle.id - Digital Creator Center</p>
                            </div>
                        ", function ($message) use ($user, $domainOrder) {
                            $message->to($user->email, $user->name)
                                    ->subject("Bukti Pembayaran Custom Domain: {$domainOrder->domain_name} - Buyle.id");
                        });
                    } catch (\Throwable $e) {
                        Log::error('[Webhook Domain] Fail sending email to creator: ' . $e->getMessage());
                    }
                }

                // 2. Kirim Email Notifikasi Alert ke Admin
                $adminEmail = \App\Models\Setting::get('admin_notification_email') ?: 'dgtilhammln@gmail.com';
                if (!empty($adminEmail)) {
                    try {
                        \Illuminate\Support\Facades\Mail::html("
                            <div style='font-family:sans-serif; max-width:600px; margin:0 auto; padding:20px; border:1px solid #cbd5e1; border-radius:12px;'>
                                <h2 style='color:#0f172a;'>🚨 Notifikasi Pembelian Domain Baru!</h2>
                                <p>Creator <strong>{$user->name}</strong> ({$user->email}) telah MELUNASI pembelian custom domain:</p>
                                <div style='background:#f8fafc; border:1.5px solid #cbd5e1; padding:15px; border-radius:8px; margin:15px 0;'>
                                    • <strong>Nama Domain:</strong> {$domainOrder->domain_name}<br>
                                    • <strong>Creator:</strong> {$user->name} (#{$user->id})<br>
                                    • <strong>Nominal:</strong> {$formattedAmount}<br>
                                    • <strong>Midtrans ID:</strong> {$domainOrder->midtrans_transaction_id}<br>
                                    • <strong>Waktu:</strong> " . now()->format('d M Y H:i:s') . "
                                </div>
                                <p>Silakan buka dashboard admin di <a href='" . route('admin.creator-resources.show', $user->id) . "'>Admin Creator Resources</a> untuk mendaftarkan / memetakan domain ini.</p>
                            </div>
                        ", function ($message) use ($adminEmail, $domainOrder) {
                            $message->to($adminEmail)
                                    ->subject("🚨 TRANSAKSI DOMAIN BARU: {$domainOrder->domain_name}");
                        });
                    } catch (\Throwable $e) {
                        Log::error('[Webhook Domain] Fail sending email to admin: ' . $e->getMessage());
                    }
                }
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $domainOrder->update(['status' => 'cancelled']);
        }

        return response('OK', 200);
    }
}
