<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PayoutRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class BuyerOrderController extends Controller
{
    /**
     * Daftar semua order milik buyer yang sedang login.
     */
    public function index()
    {
        $orders = Order::with(['items.product', 'payment'])
            ->where('user_id', auth()->id())
            ->latestFirst()
            ->simplePaginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    /**
     * Detail order + tombol akses produk digital.
     */
    public function show(Order $order)
    {
        // Pastikan order milik buyer ini
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $order->load(['items.product', 'payment']);

        return view('buyer.orders.show', compact('order'));
    }

    /**
     * Akses produk digital (link-only).
     *
     * Verifikasi kepemilikan order PAID → redirect ke external link seller.
     * buyle.id tidak menyimpan file — semua produk digital berupa link.
     */
    public function accessProduct(Request $request, int $productId): RedirectResponse
    {
        // 1. Verifikasi kepemilikan order dengan status PAID
        $hasAccess = Order::where('user_id', auth()->id())
            ->where('status', \App\Enums\OrderStatus::Confirmed)
            ->whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
            ->whereHas('items', fn($q) => $q->where('product_id', $productId))
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke produk ini. Pastikan pembayaran sudah berhasil.');
        }

        $product = \App\Models\Product::findOrFail($productId);

        if (empty($product->digital_resource)) {
            // Link belum diset oleh seller — tampilkan pesan yang informatif
            return back()->with('warning', "Seller sedang mempersiapkan link akses untuk produk \"{$product->name}\". Silakan cek kembali dalam beberapa saat.");
        }

        // 2. Log akses untuk audit trail
        \Log::info('Digital link accessed', [
            'user_id'    => auth()->id(),
            'product_id' => $productId,
            'product'    => $product->name,
            'domain'     => parse_url($product->digital_resource, PHP_URL_HOST),
        ]);

        // 3. Redirect ke external link (buka di tab baru dilakukan via blade/JS)
        return redirect($product->digital_resource);
    }

    /**
     * Buyer Dashboard — ringkasan pembelian.
     */
    public function dashboard()
    {
        $recentOrders = Order::with(['items.product', 'payment'])
            ->where('user_id', auth()->id())
            ->latestFirst()
            ->limit(5)
            ->get();

        $totalSpent = Order::where('user_id', auth()->id())
            ->whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
            ->sum('total');

        return view('buyer.dashboard', compact('recentOrders', 'totalSpent'));
    }
}
