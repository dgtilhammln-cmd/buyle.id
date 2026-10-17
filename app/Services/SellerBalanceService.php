<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\PayoutRequest;
use App\Enums\PaymentStatus;
use Carbon\Carbon;

class SellerBalanceService
{
    /**
     * Menghitung rincian saldo creator dengan mitigasi risiko anti-fraud:
     * - Produk Fisik / Digital / Jasa: Holding 72 jam (3 hari) dari waktu pembayaran.
     * - Tiket Event: Holding Escrow hingga H+3 SETELAH EVENT DILAKSANAKAN (Post-Event Settlement)
     *   untuk mencegah risiko event bodong / pencairan fiktif sebelum acara terselenggara.
     */
    public static function calculateBalance(User $seller): array
    {
        try {
            $sellerOrders = Order::with(['items.product', 'payment'])
                ->where(function ($q) use ($seller) {
                    $q->whereHas('items.product', fn($p) => $p->where('seller_id', $seller->id))
                      ->orWhereHas('items', fn($i) => $i->where('affiliate_id', $seller->id));
                })
                ->whereHas('payment', fn($q) => $q->where('status', PaymentStatus::Success))
                ->get();
        } catch (\Throwable $e) {
            $sellerOrders = collect();
        }

        $now = Carbon::now();
        $ecommerceThreshold = $now->copy()->subHours(72);

        $clearedGmv = 0;
        $standardHoldBalance = 0;
        $ticketEscrowBalance = 0;
        $upcomingEventsInEscrow = [];

        foreach ($sellerOrders as $order) {
            $paidAt = $order->payment?->paid_at ?? $order->created_at;
            $orderPaidTime = Carbon::parse($paidAt);

            foreach ($order->items as $item) {
                // A. PENDAPATAN SELLER (Pemilik Asli Produk)
                if ($item->product && $item->product->seller_id == $seller->id) {
                    // Gunakan creator_earnings yang sudah dipotong komisi affiliate & reseller margin
                    $sellerEarnings = (float) ($item->creator_earnings ?? ($item->subtotal - ($item->affiliate_commission_amount ?? 0)));

                    if ($item->product->product_type === 'ticket') {
                        // TIKET EVENT: Holding sampai H+3 pasca tanggal acara
                        $eventDate = $item->product->event_date 
                            ? Carbon::parse($item->product->event_date)->endOfDay() 
                            : null;

                        $clearanceDate = $eventDate 
                            ? $eventDate->copy()->addDays(3) 
                            : $orderPaidTime->copy()->addDays(30);

                        if ($now->gte($clearanceDate)) {
                            $clearedGmv += $sellerEarnings;
                        } else {
                            $ticketEscrowBalance += $sellerEarnings;
                            $pId = $item->product->id;
                            if (!isset($upcomingEventsInEscrow[$pId])) {
                                $upcomingEventsInEscrow[$pId] = [
                                    'id'             => $item->product->id,
                                    'name'           => $item->product->name,
                                    'title'          => $item->product->name,
                                    'event_date'     => $item->product->event_date ? $item->product->event_date->format('d M Y') : 'Belum ditentukan',
                                    'clearance_date' => $clearanceDate->format('d M Y, H:i'),
                                    'release_date'   => $clearanceDate->format('d M Y, H:i'),
                                    'amount'         => 0,
                                    'escrow_amount'  => 0,
                                ];
                            }
                            $upcomingEventsInEscrow[$pId]['amount'] += $sellerEarnings;
                            $upcomingEventsInEscrow[$pId]['escrow_amount'] += $sellerEarnings;
                        }
                    } else {
                        // PRODUK FISIK / DIGITAL / JASA: Holding standar 72 jam (3 hari)
                        if ($orderPaidTime->lte($ecommerceThreshold)) {
                            $clearedGmv += $sellerEarnings;
                        } else {
                            $standardHoldBalance += $sellerEarnings;
                        }
                    }
                }

                // B. PENDAPATAN AFFILIATOR (Komisi Rekomendasi Toko Bio)
                if ($item->affiliate_id == $seller->id && (float)($item->affiliate_commission_amount ?? 0) > 0) {
                    $affiliateCommission = (float) $item->affiliate_commission_amount;
                    if ($orderPaidTime->lte($ecommerceThreshold)) {
                        $clearedGmv += $affiliateCommission;
                    } else {
                        $standardHoldBalance += $affiliateCommission;
                    }
                }
            }
        }

        $totalPayout = PayoutRequest::where('seller_id', $seller->id)
            ->whereIn('status', ['pending', 'approved', 'processed'])
            ->sum('amount');

        $availableBalance = max(0, $clearedGmv - $totalPayout);
        $totalHoldBalance = $standardHoldBalance + $ticketEscrowBalance;
        $totalGmv = $clearedGmv + $totalHoldBalance;

        return [
            'gmv'                    => $totalGmv,
            'cleared_gmv'            => $clearedGmv,
            'available_balance'      => $availableBalance,
            'standard_hold_balance'  => $standardHoldBalance,
            'ticket_escrow_balance'  => $ticketEscrowBalance,
            'total_hold_balance'     => $totalHoldBalance,
            'total_payout'           => $totalPayout,
            'upcoming_events'        => array_values($upcomingEventsInEscrow),
        ];
    }
}
