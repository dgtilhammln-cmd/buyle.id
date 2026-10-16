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
        if ((empty($seller->bank_name) || empty($seller->bank_account_number)) && PayoutRequest::where('seller_id', $seller->id)->exists()) {
            $lastPayout = PayoutRequest::where('seller_id', $seller->id)->latest()->first();
            if ($lastPayout && $lastPayout->bank_name) {
                $seller->update([
                    'bank_name'           => $lastPayout->bank_name,
                    'bank_account_number' => $lastPayout->bank_account_number,
                    'bank_account_name'   => $lastPayout->bank_account_name,
                ]);
                $seller->refresh();
            }
        }
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

    public function requestPayout(Request $request)
    {
        $seller = auth()->user();
        $hasSaved = !empty($seller->bank_name) && !empty($seller->bank_account_number) && !empty($seller->bank_account_name);

        $rules = [
            'amount' => 'required|numeric|min:50000',
        ];

        // Validasi bank hanya jika belum ada rekening tersimpan atau form mengirim bank baru
        if (!$hasSaved || $request->filled('bank_name')) {
            $rules['bank_name']           = 'required|string|max:100';
            $rules['bank_account_number'] = 'required|string|max:50';
            $rules['bank_account_name']   = 'required|string|max:100';
        }

        $validated = $request->validate($rules);

        $bankName          = $validated['bank_name'] ?? $seller->bank_name;
        $bankAccountNumber = $validated['bank_account_number'] ?? $seller->bank_account_number;
        $bankAccountName   = $validated['bank_account_name'] ?? $seller->bank_account_name;

        if (!$bankName || !$bankAccountNumber || !$bankAccountName) {
            return back()->with('error', 'Silakan pilih atau daftarkan rekening tujuan terlebih dahulu.');
        }

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

        // Simpan info rekening ke profil seller jika baru diinput
        if (!empty($validated['bank_name'])) {
            $seller->update([
                'bank_name'           => $bankName,
                'bank_account_number' => $bankAccountNumber,
                'bank_account_name'   => $bankAccountName,
            ]);
        }

        PayoutRequest::create([
            'seller_id'           => $seller->id,
            'amount'              => $validated['amount'],
            'admin_fee'           => $adminFee,
            'net_amount'          => $netAmount,
            'bank_name'           => $bankName,
            'bank_account_number' => $bankAccountNumber,
            'bank_account_name'   => $bankAccountName,
            'status'              => 'pending',
        ]);

        // Kirim notifikasi email konfirmasi ke creator
        try {
            $seller->notify(new \App\Notifications\PayoutRequestedNotification($netAmount, $bankName, $bankAccountNumber));
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
