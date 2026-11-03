<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\PayoutRequest;
use App\Enums\PaymentStatus;
use App\Enums\OrderStatus;
use Carbon\Carbon;

class SellerBalanceService
{
    /**
     * Menghitung rincian saldo creator dengan mitigasi risiko anti-fraud:
     * - Produk Fisik / Digital / Jasa: Holding 72 jam (3 hari) dari waktu pembayaran.
     * - Tiket Event: Holding Escrow hingga H+3 SETELAH EVENT DILAKSANAKAN (Post-Event Settlement)
     *   untuk mencegah risiko event bodong / pencairan fiktif sebelum acara terselenggara.
     * - Transaksi Kasir POS: Otomatis cair instan (cleared) tanpa masa tunggu 72 jam.
     */
    public static function calculateBalance(User $seller): array
    {
        try {
            $sellerOrders = Order::with(['items.product', 'payment'])
                ->where(function ($q) use ($seller) {
                    $q->whereHas('items.product', fn($p) => $p->where('seller_id', $seller->id))
                      ->orWhereHas('items', fn($i) => $i->where('seller_id', $seller->id))
                      ->orWhereHas('items', fn($i) => $i->where('reseller_id', $seller->id))
                      ->orWhereHas('items', fn($i) => $i->where('affiliate_id', $seller->id));
                })
                ->where(function ($pq) {
                    $pq->whereHas('payment', fn($p) => $p->where('status', PaymentStatus::Success))
                       ->orWhere(function ($posQ) {
                           $posQ->where('source', 'pos')
                                ->whereIn('status', [
                                    OrderStatus::Confirmed,
                                    OrderStatus::Processing,
                                    OrderStatus::Shipped,
                                    OrderStatus::Delivered,
                                ]);
                       });
                })
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
            $isPos = ($order->source === 'pos');

            foreach ($order->items as $item) {
                // A. PENDAPATAN SELLER (Pemilik Asli Produk)
                $isOwner = ($item->product && (int)$item->product->seller_id === (int)$seller->id)
                        || ((int)$item->seller_id === (int)$seller->id);

                if ($isOwner) {
                    // Hitung earnings yang valid
                    if ($item->creator_earnings !== null && (float)$item->creator_earnings > 0) {
                        $sellerEarnings = (float) $item->creator_earnings;
                    } else {
                        $sellerEarnings = max(0, (float) ($item->subtotal 
                            - (float)($item->affiliate_commission_amount ?? 0) 
                            - (float)($item->reseller_margin ?? 0)));
                    }

                    $productType = $item->product?->product_type;

                    if ($productType === 'ticket') {
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
                            $pId = $item->product ? $item->product->id : ('item_' . $item->id);
                            if (!isset($upcomingEventsInEscrow[$pId])) {
                                $upcomingEventsInEscrow[$pId] = [
                                    'id'             => $pId,
                                    'name'           => $item->product_name ?? 'Tiket Event',
                                    'title'          => $item->product_name ?? 'Tiket Event',
                                    'event_date'     => $item->product && $item->product->event_date ? $item->product->event_date->format('d M Y') : 'Belum ditentukan',
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
                        // KASIR POS: Cair instan tanpa holding 72 jam
                        // PRODUK FISIK / DIGITAL / JASA: Holding standar 72 jam (3 hari)
                        if ($isPos || $orderPaidTime->lte($ecommerceThreshold)) {
                            $clearedGmv += $sellerEarnings;
                        } else {
                            $standardHoldBalance += $sellerEarnings;
                        }
                    }
                }

                // B. PENDAPATAN RESELLER (Margin White Label)
                if ((int)$item->reseller_id === (int)$seller->id) {
                    $resellerEarnings = (float) ($item->reseller_margin > 0 ? $item->reseller_margin : $item->subtotal);
                    if ($isPos || $orderPaidTime->lte($ecommerceThreshold)) {
                        $clearedGmv += $resellerEarnings;
                    } else {
                        $standardHoldBalance += $resellerEarnings;
                    }
                }

                // C. PENDAPATAN AFFILIATOR (Komisi Rekomendasi Toko Bio)
                if ((int)$item->affiliate_id === (int)$seller->id && (float)($item->affiliate_commission_amount ?? 0) > 0) {
                    $affiliateCommission = (float) $item->affiliate_commission_amount;
                    if ($isPos || $orderPaidTime->lte($ecommerceThreshold)) {
                        $clearedGmv += $affiliateCommission;
                    } else {
                        $standardHoldBalance += $affiliateCommission;
                    }
                }
            }
        }

        $totalPayout = (float) PayoutRequest::where('seller_id', $seller->id)
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
