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
     * Menghitung rincian saldo creator secara REAL-TIME:
     * - Seluruh pendapatan (Marketplace, Produk Fisik, Digital, Tiket Event, POS Payment Gateway)
     *   langsung cair (cleared) seketika tanpa masa tunggu/hold reguler 3 hari.
     * - Transaksi Kasir POS Tunai / QRIS Statis Toko: Langsung dipegang merchant (direct_pos_sales).
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

        $clearedGmv = 0;
        $directPosSales = 0;

        foreach ($sellerOrders as $order) {
            $isPos = ($order->source === 'pos');

            // Proteksi Finansial Platform:
            // Pembayaran Tunai (Cash), Transfer Langsung ke Toko, atau QRIS Statis Toko:
            // Uang diterima LANGSUNG secara fisik oleh merchant / masuk ke rekening toko sendiri.
            // Buyle Platform TIDAK memegang dana ini, sehingga tidak masuk ke Saldo Penarikan Buyle.
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

                    if ($isPosDirect) {
                        // Tunai & QRIS Statis POS: Uang sudah dipegang fisik oleh seller
                        $directPosSales += $sellerEarnings;
                    } else {
                        // Real-time masuk ke saldo penarikan tanpa penahanan 3 hari
                        $clearedGmv += $sellerEarnings;
                    }
                }

                // B. PENDAPATAN RESELLER (Margin White Label)
                if ((int)$item->reseller_id === (int)$seller->id) {
                    $resellerEarnings = (float) ($item->reseller_margin > 0 ? $item->reseller_margin : $item->subtotal);
                    if ($isPosDirect) {
                        $directPosSales += $resellerEarnings;
                    } else {
                        $clearedGmv += $resellerEarnings;
                    }
                }

                // C. PENDAPATAN AFFILIATOR (Komisi Rekomendasi Toko Bio)
                if ((int)$item->affiliate_id === (int)$seller->id && (float)($item->affiliate_commission_amount ?? 0) > 0) {
                    $affiliateCommission = (float) $item->affiliate_commission_amount;
                    if ($isPosDirect) {
                        $directPosSales += $affiliateCommission;
                    } else {
                        $clearedGmv += $affiliateCommission;
                    }
                }
            }
        }

        $totalPayout = (float) PayoutRequest::where('seller_id', $seller->id)
            ->whereIn('status', ['pending', 'approved', 'processed'])
            ->sum('amount');

        $availableBalance = max(0, $clearedGmv - $totalPayout);
        $totalGmv = $clearedGmv + $directPosSales;

        return [
            'gmv'                    => $totalGmv,
            'cleared_gmv'            => $clearedGmv,
            'available_balance'      => $availableBalance,
            'direct_pos_sales'       => $directPosSales,
            'standard_hold_balance'  => 0,
            'ticket_escrow_balance'  => 0,
            'total_hold_balance'     => 0,
            'total_payout'           => $totalPayout,
            'upcoming_events'        => [],
        ];
    }
}
