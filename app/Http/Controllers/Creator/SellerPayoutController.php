<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\Order;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;

class SellerPayoutController extends Controller
{
    /**
     * Halaman pengaturan payout + riwayat request pencairan.
     */
    public function settings()
    {
        $seller = auth()->user();

        // Hitung GMV & Saldo
        // Model: Platform fee ditanggung buyer (bukan dipotong dari seller)
        // Seller menerima full subtotal dari setiap order
        $platformFeeRate = 5; // 5% (untuk ditampilkan ke seller)
        try {
            $sellerOrders = Order::with(['items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id))])
                ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
                ->whereHas('payment', fn($q) => $q->where('status', PaymentStatus::Success))
                ->get();
            $gmv = $sellerOrders->sum(fn($order) => $order->items->sum('subtotal'));
        } catch (\Exception $e) {
            $gmv = 0;
        }

        // Platform fee sudah dibayar buyer, tidak dipotong dari saldo seller
        $platformFee = 0; // untuk display only (buyer sudah bayar terpisah)

        $totalPayout = PayoutRequest::where('seller_id', $seller->id)
            ->whereIn('status', ['approved', 'processed'])
            ->sum('amount');

        // Saldo bersih = GMV penuh - total yang sudah dicairkan
        $availableBalance = max(0, $gmv - $totalPayout);

        $requests = PayoutRequest::where('seller_id', $seller->id)
            ->latest()
            ->paginate(15);

        return view('creator.payout', compact('seller', 'gmv', 'platformFee', 'platformFeeRate', 'totalPayout', 'availableBalance', 'requests'));
    }

    /**
     * Ajukan request pencairan saldo ke admin.
     */
    public function requestPayout(Request $request)
    {
        $validated = $request->validate([
            'amount'              => 'required|numeric|min:50000',
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name'   => 'required|string|max:100',
            'notes'               => 'nullable|string|max:500',
        ]);

        $seller = auth()->user();

        // Cek tidak ada payout pending
        $hasPending = PayoutRequest::where('seller_id', $seller->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'Anda masih memiliki request pencairan yang sedang diproses.');
        }

        $adminFee = 5000;
        $netAmount = max(0, $validated['amount'] - $adminFee);

        // Update detail bank seller untuk penarikan selanjutnya
        $seller->update([
            'bank_name'           => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_name'   => $validated['bank_account_name'],
        ]);

        PayoutRequest::create([
            'seller_id'           => $seller->id,
            'amount'              => $validated['amount'],
            'admin_fee'           => $adminFee,
            'net_amount'          => $netAmount,
            'bank_name'           => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_name'   => $validated['bank_account_name'],
            'notes'               => $validated['notes'] ?? null,
            'status'              => 'pending',
        ]);

        return back()->with('success', 'Request pencairan saldo berhasil diajukan! Admin fee Rp 5.000 telah dipotong dari jumlah pencairan.');
    }

    /**
     * Simpan / update informasi rekening bank seller.
     */
    public function updateBank(Request $request)
    {
        $validated = $request->validate([
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name'   => 'required|string|max:100',
        ]);

        auth()->user()->update([
            'bank_name'           => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_name'   => $validated['bank_account_name'],
        ]);

        return back()->with('success', 'Informasi rekening bank berhasil diperbarui!');
    }
}
