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
                ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
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
                // Pastikan item produk milik seller ini
                if ($item->product && $item->product->seller_id == $seller->id) {
                    $subtotal = (float) $item->subtotal;

                    if ($item->product->product_type === 'ticket') {
                        // TIKET EVENT: Holding sampai H+3 pasca tanggal acara
                        $eventDate = $item->product->event_date 
                            ? Carbon::parse($item->product->event_date)->endOfDay() 
                            : null;

                        $clearanceDate = $eventDate 
                            ? $eventDate->copy()->addDays(3) 
                            : $orderPaidTime->copy()->addDays(30);

                        if ($now->gte($clearanceDate)) {
                            // Event sudah selesai lebih dari 3 hari -> Saldo cair
                            $clearedGmv += $subtotal;
                        } else {
                            // Masih ditahan di Escrow demi keamanan pembeli & proteksi event bodong
                            $ticketEscrowBalance += $subtotal;
                            $pId = $item->product->id;
                            if (!isset($upcomingEventsInEscrow[$pId])) {
                                $upcomingEventsInEscrow[$pId] = [
                                    'id'             => $item->product->id,
                                    'name'           => $item->product->name,
                                    'event_date'     => $item->product->event_date ? $item->product->event_date->format('d M Y') : 'Belum ditentukan',
                                    'clearance_date' => $clearanceDate->format('d M Y, H:i'),
                                    'amount'         => 0,
                                ];
                            }
                            $upcomingEventsInEscrow[$pId]['amount'] += $subtotal;
                        }
                    } else {
                        // PRODUK FISIK / DIGITAL / JASA: Holding standar 72 jam (3 hari)
                        if ($orderPaidTime->lte($ecommerceThreshold)) {
                            $clearedGmv += $subtotal;
                        } else {
                            $standardHoldBalance += $subtotal;
                        }
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
