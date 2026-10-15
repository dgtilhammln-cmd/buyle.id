<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderPaidNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ProcessSuccessfulOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60; // retry setelah 60 detik

    public function __construct(
        protected int $orderId,
        protected string $buyerEmail,
        protected string $buyerName,
        protected string $buyerPhone = '',
    ) {}

    public function handle(): void
    {
        $order = Order::with(['items.product.seller'])->find($this->orderId);

        if (!$order) {
            Log::warning("ProcessSuccessfulOrderJob: Order #{$this->orderId} tidak ditemukan.");
            return;
        }

        // 1. Cari akun User yang sudah ada — JANGAN buat baru untuk guest
        $user = User::where('email', $this->buyerEmail)->first();

        // 2. Tentukan status berdasarkan jenis produk
        $order->load('items.product');
        $allDigital = $order->items->every(function ($item) {
            $type = $item->product?->product_type ?? $item->product?->type ?? '';
            return in_array($type, ['external_link', 'digital', 'file']);
        });

        $newStatus = $allDigital
            ? \App\Enums\OrderStatus::Delivered
            : \App\Enums\OrderStatus::Confirmed;

        // Update status & link user_id jika user ditemukan dan belum terlink
        $updateData = ['status' => $newStatus];
        if ($user && !$order->user_id) {
            $updateData['user_id'] = $user->id;
        }
        $order->update($updateData);

        // Generate E-Ticket Pass jika terdapat produk tipe ticket
        try {
            \App\Models\TicketPass::generateForOrder($order);
        } catch (\Throwable $e) {
            Log::warning("Gagal generate TicketPass for order #{$order->id}: " . $e->getMessage());
        }

        // 3. Generate Magic Login URL (hanya jika user punya akun)
        $magicLoginUrl = route('account.orders');
        if ($user) {
            $loginToken = Str::random(64);
            \DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => Hash::make($loginToken), 'created_at' => now()]
            );
            $magicLoginUrl = route('buyer.magic.login', [
                'token' => $loginToken,
                'email' => $user->email,
            ]);
        }

        // 4. Kirim Email Notifikasi ke Buyer
        try {
            if ($user) {
                // User terdaftar → kirim via User notifiable
                $user->notify(new OrderPaidNotification($order, $magicLoginUrl, false));
            } else {
                // Guest → kirim langsung ke email tanpa akun
                $guestEmail = $order->customer_email ?? $this->buyerEmail;
                if ($guestEmail) {
                    Notification::route('mail', $guestEmail)
                        ->notify(new OrderPaidNotification($order, $magicLoginUrl, false));
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim OrderPaidNotification ke {$this->buyerEmail}: " . $e->getMessage());
        }

        // 5. Kirim Email Notifikasi Order Masuk ke Seller
        try {
            $sellerIds = [];
            foreach ($order->items as $item) {
                if ($item->product && $item->product->seller && $item->product->seller->user) {
                    $sellerUser = $item->product->seller->user;
                    if (!in_array($sellerUser->id, $sellerIds)) {
                        $sellerIds[] = $sellerUser->id;
                        $sellerUser->notify(new \App\Notifications\SellerNewOrderNotification($order));
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim SellerNewOrderNotification: " . $e->getMessage());
        }

        $recipientEmail = $user?->email ?? $this->buyerEmail;
        Log::info("ProcessSuccessfulOrderJob: Order #{$order->order_number} berhasil diproses untuk {$recipientEmail}.");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("ProcessSuccessfulOrderJob FAILED for order #{$this->orderId}: " . $exception->getMessage());
    }
}
