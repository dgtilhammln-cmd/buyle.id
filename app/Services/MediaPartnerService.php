<?php

namespace App\Services;

use App\Models\MediaPartner;
use App\Models\MediaPartnerCommissionLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MediaPartnerService
{
    /**
     * Catat komisi Media Partner jika creator (penjual tiket) terafiliasi dengan Media Partner.
     * Sifat atribusi: Seumur Hidup (Lifetime) & Otomatis.
     */
    public static function recordOrderCommission(Order $order): void
    {
        $order->loadMissing(['items.product.seller']);

        foreach ($order->items as $item) {
            $product = $item->product;
            if (!$product) {
                continue;
            }

            // Dapatkan seller / creator
            $creator = $product->seller;
            if (!$creator) {
                $creator = User::find($product->seller_id ?? $item->seller_id);
            }

            if (!$creator || empty($creator->media_partner_id)) {
                continue;
            }

            $mediaPartner = MediaPartner::find($creator->media_partner_id);
            if (!$mediaPartner || !$mediaPartner->is_active) {
                continue;
            }

            // Cek apakah komisi untuk item ini sudah pernah dicatat (anti-duplikasi)
            $existingLog = MediaPartnerCommissionLog::where('order_id', $order->id)
                ->where('order_item_id', $item->id)
                ->first();

            if ($existingLog) {
                continue;
            }

            $commissionRate = (float) $mediaPartner->commission_rate;
            $grossAmount    = (float) $item->subtotal;
            $commissionAmount = round(($grossAmount * ($commissionRate / 100)), 2);

            if ($commissionAmount <= 0) {
                continue;
            }

            DB::transaction(function () use ($mediaPartner, $creator, $order, $item, $product, $commissionRate, $grossAmount, $commissionAmount) {
                MediaPartnerCommissionLog::create([
                    'media_partner_id'  => $mediaPartner->id,
                    'creator_id'        => $creator->id,
                    'order_id'          => $order->id,
                    'order_item_id'     => $item->id,
                    'event_name'        => $product->name,
                    'ticket_price'      => $item->unit_price,
                    'quantity'          => $item->qty,
                    'gross_amount'      => $grossAmount,
                    'commission_rate'   => $commissionRate,
                    'commission_amount' => $commissionAmount,
                    'status'            => 'credited',
                    'notes'             => "Komisi {$commissionRate}% penjualan tiket oleh {$creator->name}",
                ]);

                // Update saldo & akumulasi komisi Media Partner
                $mediaPartner->increment('total_commission_earned', $commissionAmount);
                $mediaPartner->increment('balance', $commissionAmount);
            });

            Log::info("Komisi Media Partner dicatat: {$mediaPartner->name} (+Rp {$commissionAmount}) untuk Order #{$order->id} dari creator #{$creator->id}");
        }
    }

    /**
     * Catat pencairan komisi ke rekening Media Partner
     */
    public static function processPayout(MediaPartner $partner, float $amount, ?string $referenceNumber = null, ?string $notes = null): void
    {
        if ($amount <= 0 || $amount > $partner->balance) {
            throw new \InvalidArgumentException('Nominal pencairan tidak valid atau melebihi saldo tersedia.');
        }

        DB::transaction(function () use ($partner, $amount, $referenceNumber, $notes) {
            $partner->decrement('balance', $amount);
            $partner->increment('total_commission_paid', $amount);

            // Update log komisi yang statusnya credited menjadi paid
            $pendingLogs = MediaPartnerCommissionLog::where('media_partner_id', $partner->id)
                ->where('status', 'credited')
                ->orderBy('created_at', 'asc')
                ->get();

            $remainingAmount = $amount;
            foreach ($pendingLogs as $log) {
                if ($remainingAmount <= 0) {
                    break;
                }
                $log->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                    'notes'   => ($log->notes ? $log->notes . ' | ' : '') . "Dicairkan via Ref: {$referenceNumber}",
                ]);
                $remainingAmount -= (float) $log->commission_amount;
            }
        });
    }
}
