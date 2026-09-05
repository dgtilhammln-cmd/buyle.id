<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\PayoutRequest;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Creator Dashboard — ringkasan performa, produk, dan penjualan terkini.
     */
    public function dashboard()
    {
        $seller = auth()->user();

        // ── Statistik Produk ──────────────────────────────────────────────────
        $totalProducts  = Product::where('seller_id', $seller->id)->count();
        $activeProducts = Product::where('seller_id', $seller->id)->where('is_active', true)->count();

        // ── Statistik Penjualan ───────────────────────────────────────────────
        // Ambil semua order paid yang mengandung produk seller ini
        try {
            $sellerOrders = Order::whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
                ->whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
                ->with(['items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id))])
                ->get();

            $gmv = $sellerOrders->sum(fn($order) => $order->items->sum('subtotal'));
            $totalTransactions = $sellerOrders->count();
        } catch (\Exception $e) {
            $sellerOrders = collect();
            $gmv = 0;
            $totalTransactions = 0;
        }

        // ── Platform Fee & Saldo ──────────────────────────────────────────────
        // Model A: fee ditanggung buyer → seller menerima full GMV
        $platformFeeRate = 5.0; // 5% (hanya untuk display)
        $platformFee     = 0;   // tidak dipotong dari saldo seller

        $totalPayout = PayoutRequest::where('seller_id', $seller->id)
            ->whereIn('status', ['approved', 'processed'])
            ->sum('amount');

        // Saldo = GMV penuh dikurangi total yang sudah dicairkan
        $availableBalance = max(0, $gmv - $totalPayout);

        // ── Penjualan Terbaru (30 hari) ───────────────────────────────────────
        try {
            $recentSales = Order::with(['items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id))->with('product'), 'payment'])
                ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
                ->whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
                ->where('created_at', '>=', now()->subDays(30))
                ->latest()
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            $recentSales = collect();
        }

        // ── Produk Terbaru ───────────────────────────────────────────────────
        $recentProducts = Product::where('seller_id', $seller->id)
            ->with('category:id,name')
            ->latest()
            ->limit(5)
            ->get();

        return view('creator.dashboard', compact(
            'seller', 'gmv', 'platformFee', 'platformFeeRate',
            'totalPayout', 'availableBalance',
            'totalProducts', 'activeProducts',
            'totalTransactions', 'recentSales', 'recentProducts'
        ));
    }

    /**
     * Realtime stats JSON endpoint – polled by dashboard every 30s.
     */
    public function realtimeStats()
    {
        $seller = auth()->user();

        $totalProducts  = Product::where('seller_id', $seller->id)->count();
        $activeProducts = Product::where('seller_id', $seller->id)->where('is_active', true)->count();

        try {
            $sellerOrders = Order::whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
                ->whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
                ->with(['items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id))])
                ->get();

            $gmv = $sellerOrders->sum(fn($order) => $order->items->sum('subtotal'));
            $totalTransactions = $sellerOrders->count();
        } catch (\Exception $e) {
            $gmv = 0;
            $totalTransactions = 0;
        }

        $platformFeeRate = 5.0; // 5% (display only)
        $platformFee     = 0;   // tidak dipotong dari saldo seller

        $totalPayout = PayoutRequest::where('seller_id', $seller->id)
            ->whereIn('status', ['approved', 'processed'])
            ->sum('amount');

        $availableBalance = max(0, $gmv - $totalPayout);

        return response()->json([
            'gmv'               => $gmv,
            'available_balance' => $availableBalance,
            'total_products'    => $totalProducts,
            'active_products'   => $activeProducts,
            'total_transactions'=> $totalTransactions,
            'total_views'       => ($totalProducts * 12) + 24,
            'timestamp'         => now()->format('H:i:s'),
        ]);
    }

    /**
     * Membership Seller - Halaman Pilihan Paket Membership & Fitur
     */
    public function membership()
    {
        return view('creator.membership');
    }
}
