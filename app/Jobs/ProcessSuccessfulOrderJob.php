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

        // 1. Auto-Generate atau Temukan Akun Buyer
        $user = User::firstOrCreate(
            ['email' => $this->buyerEmail],
            [
                'name'     => $this->buyerName,
                'phone'    => $this->buyerPhone,
                'password' => Hash::make(Str::random(12)),
                'role'     => 'buyer',
            ]
        );

        $wasNewlyCreated = $user->wasRecentlyCreated;

        // 2. Tautkan order ke buyer dan update status ke 'confirmed'
        $order->update([
            'user_id' => $user->id,
            'status'  => \App\Enums\OrderStatus::Confirmed,
        ]);

        // Generate E-Ticket Pass jika terdapat produk tipe ticket
        try {
            \App\Models\TicketPass::generateForOrder($order);
        } catch (\Throwable $e) {
            Log::warning("Gagal generate TicketPass for order #{$order->id}: " . $e->getMessage());
        }

        // 3. Generate Magic Login Token (password reset token dipakai ulang)
        $loginToken = Str::random(64);
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => \Hash::make($loginToken), 'created_at' => now()]
        );

        $magicLoginUrl = route('buyer.magic.login', [
            'token' => $loginToken,
            'email' => $user->email,
        ]);

        // 4. Kirim Email Notifikasi ke Buyer (Akses Produk)
        try {
            $user->notify(new OrderPaidNotification($order, $magicLoginUrl, $wasNewlyCreated));
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim OrderPaidNotification to buyer: " . $e->getMessage());
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

        Log::info("ProcessSuccessfulOrderJob: Order #{$order->order_number} berhasil diproses untuk user {$user->email}.");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("ProcessSuccessfulOrderJob FAILED for order #{$this->orderId}: " . $exception->getMessage());
    }
}
