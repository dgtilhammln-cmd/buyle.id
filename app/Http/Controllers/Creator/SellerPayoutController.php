<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use Illuminate\Http\Request;

class SellerPayoutController extends Controller
{
    /**
     * Halaman pengaturan payout + riwayat request pencairan.
     */
    public function settings()
    {
        $seller = auth()->user();

        $requests = PayoutRequest::where('seller_id', $seller->id)
            ->latest()
            ->simplePaginate(10);

        return view('creator.payout.settings', compact('seller', 'requests'));
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

        // TODO: Validasi bahwa amount <= available balance (panggil SellerController balance logic)

        PayoutRequest::create([
            'seller_id'           => $seller->id,
            'amount'              => $validated['amount'],
            'bank_name'           => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_name'   => $validated['bank_account_name'],
            'notes'               => $validated['notes'] ?? null,
            'status'              => 'pending',
        ]);

        return back()->with('success', 'Request pencairan saldo berhasil diajukan. Admin akan memproses dalam 1-3 hari kerja.');
    }
}
