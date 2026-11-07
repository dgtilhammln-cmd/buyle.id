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
        $directPosSales = 0;
        $upcomingEventsInEscrow = [];

        foreach ($sellerOrders as $order) {
            $paidAt = $order->payment?->paid_at ?? $order->created_at;
            $orderPaidTime = Carbon::parse($paidAt);
            $isPos = ($order->source === 'pos');

            // Proteksi Finansial Platform:
            // Pembayaran Tunai (Cash), Transfer Langsung ke Toko, atau QRIS Statis Toko:
            // Uang diterima LANGSUNG secara fisik oleh merchant / masuk ke rekening toko sendiri.
            // Buyle Platform TIDAK memegang dana ini, sehingga DILARANG masuk ke Saldo Penarikan Buyle!
            $isPosDirect = false;
            if ($isPos) {
                $addr = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
                $posMethod = strtolower($addr['payment_method'] ?? ($order->payment?->method ?? 'cash'));
                $posGateway = strtolower($order->payment?->gateway ?? '');

                if (in_array($posMethod, ['cash', 'transfer']) || in_array($posGateway, ['qris_static', 'direct']) || ($posMethod === 'qris' && $posGateway !== 'midtrans')) {
                    $isPosDirect = true;
                }
            }

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

                    // TIKET EVENT: Langsung cair tanpa escrow / holding, bisa ditarik kapanpun
                    if ($productType === 'ticket') {
                        $clearedGmv += $sellerEarnings;
                    } else {
                        if ($isPosDirect) {
                            // Tunai & QRIS Statis POS: Uang sudah dipegang fisik oleh seller, tidak masuk saldo penarikan Buyle
                            $directPosSales += $sellerEarnings;
                        } elseif ($isPos || $orderPaidTime->lte($ecommerceThreshold)) {
                            // Midtrans PG POS / Online cleared: masuk saldo penarikan
                            $clearedGmv += $sellerEarnings;
                        } else {
                            $standardHoldBalance += $sellerEarnings;
                        }
                    }
                }

                // B. PENDAPATAN RESELLER (Margin White Label)
                if ((int)$item->reseller_id === (int)$seller->id) {
                    $resellerEarnings = (float) ($item->reseller_margin > 0 ? $item->reseller_margin : $item->subtotal);
                    if ($isPosDirect) {
                        $directPosSales += $resellerEarnings;
                    } elseif ($isPos || $orderPaidTime->lte($ecommerceThreshold)) {
                        $clearedGmv += $resellerEarnings;
                    } else {
                        $standardHoldBalance += $resellerEarnings;
                    }
                }

                // C. PENDAPATAN AFFILIATOR (Komisi Rekomendasi Toko Bio)
                if ((int)$item->affiliate_id === (int)$seller->id && (float)($item->affiliate_commission_amount ?? 0) > 0) {
                    $affiliateCommission = (float) $item->affiliate_commission_amount;
                    if ($isPosDirect) {
                        $directPosSales += $affiliateCommission;
                    } elseif ($isPos || $orderPaidTime->lte($ecommerceThreshold)) {
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
        $totalGmv = $clearedGmv + $totalHoldBalance + $directPosSales;

        return [
            'gmv'                    => $totalGmv,
            'cleared_gmv'            => $clearedGmv,
            'available_balance'      => $availableBalance,
            'direct_pos_sales'       => $directPosSales,
            'standard_hold_balance'  => $standardHoldBalance,
            'ticket_escrow_balance'  => $ticketEscrowBalance,
            'total_hold_balance'     => $totalHoldBalance,
            'total_payout'           => $totalPayout,
            'upcoming_events'        => array_values($upcomingEventsInEscrow),
        ];
    }
}
