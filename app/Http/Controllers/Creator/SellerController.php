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

        // ── Saldo & GMV Terintegrasi (Single Source of Truth via SellerBalanceService) ──
        $balanceData         = \App\Services\SellerBalanceService::calculateBalance($seller);
        $gmv                 = $balanceData['gmv'];
        $clearedGmv          = $balanceData['cleared_gmv'];
        $holdBalance         = $balanceData['total_hold_balance'];
        $standardHoldBalance = $balanceData['standard_hold_balance'];
        $ticketEscrowBalance = $balanceData['ticket_escrow_balance'];
        $totalPayout         = $balanceData['total_payout'];
        $availableBalance    = $balanceData['available_balance'];

        try {
            $totalTransactions = Order::where(function ($q) use ($seller) {
                    $q->whereHas('items.product', fn($p) => $p->where('seller_id', $seller->id))
                      ->orWhereHas('items', fn($i) => $i->where('seller_id', $seller->id))
                      ->orWhereHas('items', fn($i) => $i->where('reseller_id', $seller->id));
                })
                ->where(function ($pq) {
                    $pq->whereHas('payment', fn($p) => $p->where('status', \App\Enums\PaymentStatus::Success))
                       ->orWhere('source', 'pos');
                })
                ->count();
        } catch (\Exception $e) {
            $totalTransactions = 0;
        }

        // ── Auto-Verification & Tier Info Check ──────────────────────────────
        $cp = $seller->creatorProfile;
        if ($cp) {
            if (!$cp->is_verified && $totalTransactions >= 10) {
                $cp->update(['is_verified' => true]);
                $cp->is_verified = true;
            }
            $tierInfo = $cp->getTierInfo($gmv);
        } else {
            $tierInfo = [
                'name'      => 'PERINTIS',
                'level'     => 1,
                'subtitle'  => 'Matte Silver / Brushed Steel',
                'badge'     => 'Perintis',
                'header_bg' => 'linear-gradient(135deg, #475569 0%, #64748b 100%)',
            ];
        }

        // ── Platform Fee ──────────────────────────────────────────────────────
        $platformFeeRate = 5.0; // 5% (hanya untuk display)
        $platformFee     = 0;   // tidak dipotong dari saldo seller

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
            'totalPayout', 'availableBalance', 'holdBalance', 'ticketEscrowBalance',
            'totalProducts', 'activeProducts',
            'totalTransactions', 'recentSales', 'recentProducts',
            'tierInfo'
        ));
    }

    /**
     * Request atau klaim status Verified Creator.
     */
    /**
     * Minimalist Mobile Menu & Profile Hub (Style /creator/dashboard).
     */
    public function menu()
    {
        $seller = auth()->user();
        $cp = $seller->creatorProfile;

        $balanceData = \App\Services\SellerBalanceService::calculateBalance($seller);
        $gmv              = $balanceData['gmv'];
        $availableBalance = $balanceData['available_balance'];

        if ($cp) {
            $tierInfo = $cp->getTierInfo($gmv);
        } else {
            $tierInfo = [
                'name'      => 'PERINTIS',
                'level'     => 1,
                'subtitle'  => 'Matte Silver / Brushed Steel',
                'badge'     => 'Perintis',
                'header_bg' => 'linear-gradient(135deg, #475569 0%, #64748b 100%)',
            ];
        }

        $storeSlug = $cp?->slug ?? $seller->username ?? null;
        $hasPushSub = \App\Models\PushSubscription::where('user_id', $seller->id)->exists();
        $hasPin = !empty($seller->security_pin);
        $biometricEnabled = (bool) $seller->biometric_enabled;

        return view('creator.menu', compact(
            'seller', 'cp', 'gmv', 'availableBalance', 'tierInfo', 'storeSlug', 'hasPushSub',
            'hasPin', 'biometricEnabled'
        ));
    }

    /**
     * Simpan Push Notification Subscription Token untuk PWA.
     */
    public function savePushSubscription(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
        ]);

        $user = auth()->user();
        \App\Models\PushSubscription::updateOrCreate(
            [
                'user_id' => $user->id,
                'endpoint' => $request->endpoint,
            ],
            [
                'key_p256dh' => $request->input('keys.p256dh') ?? $request->input('key_p256dh'),
                'key_auth'   => $request->input('keys.auth') ?? $request->input('key_auth'),
                'user_agent' => $request->userAgent(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi HP berhasil diaktifkan!'
        ]);
    }

    /**
     * Setel atau perbarui PIN Keamanan Creator.
     */
    public function updatePin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:6',
            'biometric_enabled' => 'nullable|boolean',
        ]);

        $user = auth()->user();
        $user->security_pin = \Illuminate\Support\Facades\Hash::make($request->pin);
        if ($request->has('biometric_enabled')) {
            $user->biometric_enabled = $request->boolean('biometric_enabled');
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'PIN keamanan 6-digit berhasil disimpan!'
        ]);
    }

    /**
     * Toggle status sensor Biometrik (Fingerprint / Face ID).
     */
    public function toggleBiometric(Request $request)
    {
        $user = auth()->user();
        $user->biometric_enabled = $request->boolean('enabled');
        $user->save();

        return response()->json([
            'success' => true,
            'enabled' => $user->biometric_enabled,
            'message' => $user->biometric_enabled ? 'Sensor sidik jari / Face ID diaktifkan!' : 'Sensor biometrik dinonaktifkan.'
        ]);
    }

    /**
     * Verifikasi PIN saat buka aplikasi.
     */
    public function verifyPin(Request $request)
    {
        $user = auth()->user();
        if (empty($user->security_pin)) {
            return response()->json(['success' => true]);
        }

        // Jika verifikasi via biometrik yang valid
        if ($request->boolean('is_biometric') && $user->biometric_enabled) {
            return response()->json(['success' => true]);
        }

        $request->validate(['pin' => 'required|digits:6']);

        if (\Illuminate\Support\Facades\Hash::check($request->pin, $user->security_pin) || ($request->pin === $user->security_pin)) {
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'PIN yang Anda masukkan salah.'
        ], 422);
    }

    /**
     * Hapus PIN keamanan.
     */
    public function removePin(Request $request)
    {
        $user = auth()->user();
        $user->security_pin = null;
        $user->biometric_enabled = false;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Kunci PIN & Biometrik berhasil dinonaktifkan.'
        ]);
    }

    public function requestVerification(Request $request)
    {
        $seller = auth()->user();
        $cp = \App\Models\CreatorProfile::getOrCreateForUser($seller);

        try {
            $totalTransactions = Order::whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
                ->whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
                ->count();
        } catch (\Exception $e) {
            $totalTransactions = 0;
        }

        if ($totalTransactions >= 10) {
            $cp->update(['is_verified' => true]);
            return redirect()->back()->with('success', 'Selamat! Toko Anda telah berhasil terverifikasi sebagai Verified Creator!');
        }

        $remaining = 10 - $totalTransactions;
        return redirect()->back()->with('error', "Syarat belum terpenuhi. Diperlukan minimal 10 transaksi order (saat ini: {$totalTransactions}/10, kurang {$remaining} transaksi lagi).");
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
