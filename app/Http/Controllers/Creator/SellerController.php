<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PayoutRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    /**
     * Creator Dashboard — ringkasan GMV, saldo, dan penjualan terkini.
     */
    public function dashboard()
    {
        $seller = auth()->user();

        // Ambil semua order yang mengandung produk milik seller ini
        $sellerOrders = Order::whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
            ->whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
            ->with(['items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id))])
            ->get();

        // Total GMV seller
        $gmv = $sellerOrders->sum(fn($order) =>
            $order->items->sum('subtotal')
        );

        // Platform Fee (default 10% — bisa dikonfigurasi di settings)
        $platformFeeRate = (float) config('marketplace.platform_fee_rate', 10);
        $platformFee     = $gmv * ($platformFeeRate / 100);

        // Total yang sudah dicairkan
        $totalPayout = PayoutRequest::where('seller_id', $seller->id)
            ->whereIn('status', ['approved', 'processed'])
            ->sum('amount');

        // Saldo bersih yang bisa dicairkan
        $availableBalance = max(0, ($gmv - $platformFee) - $totalPayout);

        // Penjualan 30 hari terakhir
        $recentSales = Order::with(['items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id)), 'payment'])
            ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
            ->whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
            ->where('created_at', '>=', now()->subDays(30))
            ->latestFirst()
            ->simplePaginate(10);

        return view('creator.dashboard', compact(
            'seller', 'gmv', 'platformFee', 'totalPayout', 'availableBalance',
            'recentSales', 'platformFeeRate'
        ));
    }
}
