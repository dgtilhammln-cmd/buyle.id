<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\Order;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SellerPayoutController extends Controller
{
    /**
     * Halaman pengaturan payout + riwayat request pencairan.
     */
    public function settings()
    {
        $seller = auth()->user();
        $platformFeeRate = 5;

        try {
            $sellerOrders = Order::with(['items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id))])
                ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
                ->whereHas('payment', fn($q) => $q->where('status', PaymentStatus::Success))
                ->get();
        } catch (\Exception $e) {
            $sellerOrders = collect();
        }

        // Holding period: 3x24 jam (72 jam) setelah transaksi
        $threshold = Carbon::now()->subHours(72);

        // Saldo yang SUDAH lewat 72 jam (siap ditarik)
        $clearedOrders = $sellerOrders->filter(function ($order) use ($threshold) {
            $paidAt = $order->payment?->paid_at ?? $order->created_at;
            return Carbon::parse($paidAt)->lte($threshold);
        });
        $clearedGmv = $clearedOrders->sum(fn($order) => $order->items->sum('subtotal'));

        // Saldo yang MASIH dalam 72 jam (sedang di-hold)
        $holdingOrders = $sellerOrders->filter(function ($order) use ($threshold) {
            $paidAt = $order->payment?->paid_at ?? $order->created_at;
            return Carbon::parse($paidAt)->gt($threshold);
        });
        $holdBalance = $holdingOrders->sum(fn($order) => $order->items->sum('subtotal'));

        // GMV total
        $gmv = $sellerOrders->sum(fn($order) => $order->items->sum('subtotal'));

        $totalPayout = PayoutRequest::where('seller_id', $seller->id)
            ->whereIn('status', ['pending', 'approved', 'processed'])
            ->sum('amount');

        $availableBalance = max(0, $clearedGmv - $totalPayout);

        $requests = PayoutRequest::where('seller_id', $seller->id)
            ->latest()
            ->paginate(15);

        return view('creator.payout', compact(
            'seller',
            'gmv',
            'clearedGmv',
            'holdBalance',
            'platformFeeRate',
            'totalPayout',
            'availableBalance',
            'requests'
        ));
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
        ]);

        $seller = auth()->user();

        // Hitung ulang saldo siap ditarik (server-side validasi)
        try {
            $sellerOrders = Order::with(['items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id))])
                ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
                ->whereHas('payment', fn($q) => $q->where('status', PaymentStatus::Success))
                ->get();
            $threshold = Carbon::now()->subHours(72);
            $clearedGmv = $sellerOrders->filter(function ($order) use ($threshold) {
                $paidAt = $order->payment?->paid_at ?? $order->created_at;
                return Carbon::parse($paidAt)->lte($threshold);
            })->sum(fn($order) => $order->items->sum('subtotal'));
        } catch (\Exception $e) {
            $clearedGmv = 0;
        }

        $totalPayout = PayoutRequest::where('seller_id', $seller->id)
            ->whereIn('status', ['pending', 'approved', 'processed'])
            ->sum('amount');
        $availableBalance = max(0, $clearedGmv - $totalPayout);

        if ($validated['amount'] > $availableBalance) {
            return back()->with('error', 'Jumlah penarikan melebihi saldo yang tersedia (Rp ' . number_format($availableBalance, 0, ',', '.') . ').');
        }

        // Cek tidak ada payout pending
        $hasPending = PayoutRequest::where('seller_id', $seller->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->with('error', 'Masih ada permintaan pencairan yang sedang diproses. Tunggu hingga selesai sebelum mengajukan yang baru.');
        }

        $adminFee = 0;
        $netAmount = $validated['amount'];

        // Simpan info rekening ke profil seller
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
            'status'              => 'pending',
        ]);

        // Kirim notifikasi email konfirmasi ke creator
        try {
            $seller->notify(new \App\Notifications\PayoutRequestedNotification($netAmount, $validated['bank_name'], $validated['bank_account_number']));
        } catch (\Throwable $e) {
            \Log::warning('Gagal kirim notifikasi PayoutRequested: ' . $e->getMessage());
        }

        return back()->with('success', 'Permintaan pencairan Rp ' . number_format($validated['amount'], 0, ',', '.') . ' berhasil diajukan. Dana 100% utuh tanpa potongan akan dikirim dalam 1-3 hari kerja.');
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

        return back()->with('success', 'Informasi rekening berhasil disimpan.');
    }
}
