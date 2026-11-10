<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Services\TicketWarQueueService;

class ExpireUnpaidTicketOrders extends Command
{
    protected $signature   = 'tickets:expire-unpaid {--minutes=15 : Menit batas order belum bayar}';
    protected $description = 'Batalkan order tiket yang belum dibayar lebih dari batas waktu dan kembalikan stoknya.';

    public function handle(TicketWarQueueService $warService): int
    {
        $minutes   = (int) $this->option('minutes');
        $threshold = now()->subMinutes($minutes);

        // Cari order tiket yang statusnya Pending dan melewati batas waktu
        $expiredOrders = \App\Models\Order::where('status', OrderStatus::Pending)
            ->where('created_at', '<', $threshold)
            ->whereHas('items.product', function ($q) {
                $q->where('product_type', 'ticket');
            })
            ->whereHas('payment', function ($q) {
                $q->where('status', PaymentStatus::Pending)
                    ->orWhereNull('status');
            })
            ->with(['items.product', 'items.variantValue', 'payment'])
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('[Ticket Expiry] Tidak ada order kedaluwarsa. Done.');
            return Command::SUCCESS;
        }

        $expiredCount = 0;

        foreach ($expiredOrders as $order) {
            DB::beginTransaction();
            try {
                // Batalkan order & payment
                $order->update(['status' => OrderStatus::Cancelled]);
                if ($order->payment) {
                    $order->payment->update(['status' => PaymentStatus::Expired]);
                }

                // Kembalikan stok produk & variasi
                foreach ($order->items as $item) {
                    if ($item->product && $item->product->stock !== null) {
                        $item->product->increment('stock', $item->qty);
                        $item->product->decrement('sold_count', $item->qty);
                    }

                    if ($item->variant_value_id) {
                        $variantVal = \App\Models\ProductVariantValue::find($item->variant_value_id);
                        if ($variantVal && $variantVal->stock !== null) {
                            $variantVal->increment('stock', $item->qty);
                        }
                    }

                    // Bebaskan slot war queue per produk tiket
                    if ($item->product && !empty($item->product->ticket_war_start)) {
                        $qId = \Illuminate\Support\Facades\Cache::pull("order_war_queue_{$order->id}_{$item->product_id}");
                        $warService->releaseSlot($item->product->id, $qId);
                    }
                }

                DB::commit();
                $expiredCount++;

                $this->line("  ✓ Order #{$order->order_number} ({$order->customer_email}) dibatalkan — stok dikembalikan.");
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error("  ✗ Gagal batalkan order #{$order->order_number}: " . $e->getMessage());
            }
        }

        $this->info("[Ticket Expiry] Selesai. {$expiredCount} order tiket kedaluwarsa dibatalkan & stok dikembalikan.");
        return Command::SUCCESS;
    }
}

