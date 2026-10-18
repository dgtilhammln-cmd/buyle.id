<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\Order;
use App\Models\CreatorProfile;
use App\Models\PosBalanceMutation;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

        $profile = CreatorProfile::where('user_id', $seller->id)->first();
        $posBalance = (float) ($profile->pos_balance ?? 0);

        $balanceData = \App\Services\SellerBalanceService::calculateBalance($seller);
        $gmv                 = $balanceData['gmv'];
        $clearedGmv          = $balanceData['cleared_gmv'];
        $holdBalance         = $balanceData['total_hold_balance'];
        $standardHoldBalance = $balanceData['standard_hold_balance'];
        $ticketEscrowBalance = $balanceData['ticket_escrow_balance'];
        $totalPayout         = $balanceData['total_payout'];
        $availableBalance    = $balanceData['available_balance'];
        $upcomingEvents      = $balanceData['upcoming_events'];

        $requests = PayoutRequest::where('seller_id', $seller->id)
            ->latest()
            ->paginate(15);

        return view('creator.payout', compact(
            'seller',
            'profile',
            'posBalance',
            'gmv',
            'clearedGmv',
            'holdBalance',
            'standardHoldBalance',
            'ticketEscrowBalance',
            'upcomingEvents',
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

        // Hitung ulang saldo siap ditarik (server-side validasi dengan mitigasi anti-fraud tiket event)
        $balanceData      = \App\Services\SellerBalanceService::calculateBalance($seller);
        $availableBalance = $balanceData['available_balance'];

        if ($validated['amount'] > $availableBalance) {
            $msg = 'Jumlah penarikan melebihi saldo yang tersedia (Rp ' . number_format($availableBalance, 0, ',', '.') . ').';
            if ($balanceData['ticket_escrow_balance'] > 0) {
                $msg .= ' Catatan: Saldo penjualan tiket event (Rp ' . number_format($balanceData['ticket_escrow_balance'], 0, ',', '.') . ') masih berada dalam masa Escrow dan otomatis cair H+3 setelah acara sukses terselenggara.';
            }
            return back()->with('error', $msg);
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

    /**
     * Pindahkan saldo toko yang siap ditarik ke Saldo Deposit Kasir POS secara instan & aman.
     * Mencegah double spend: langsung tercatat di payout_requests (status: processed)
     * dan mengkredit pos_balance + mencatat PosBalanceMutation.
     */
    public function transferToPosDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5000',
        ], [
            'amount.required' => 'Nominal transfer wajib diisi.',
            'amount.numeric'  => 'Nominal harus berupa angka yang valid.',
            'amount.min'      => 'Minimal pemindahan saldo ke deposit POS adalah Rp 5.000.',
        ]);

        $seller = auth()->user();
        $amount = (float) $request->amount;

        try {
            return DB::transaction(function () use ($seller, $amount) {
                // 1. Kunci profil creator
                $profile = CreatorProfile::where('user_id', $seller->id)->lockForUpdate()->first();
                if (!$profile) {
                    return back()->with('error', 'Profil toko Anda belum dikonfigurasi.');
                }

                // 2. Hitung saldo tersedia secara realtime (anti-race condition)
                $balanceData = \App\Services\SellerBalanceService::calculateBalance($seller);
                $availableBalance = (float) $balanceData['available_balance'];

                if ($amount > $availableBalance) {
                    $msg = 'Saldo Siap Ditarik Anda tidak mencukupi. Tersedia: Rp ' . number_format($availableBalance, 0, ',', '.') . ', diminta: Rp ' . number_format($amount, 0, ',', '.');
                    if (request()->wantsJson() || request()->ajax()) {
                        return response()->json(['success' => false, 'message' => $msg], 422);
                    }
                    return back()->with('error', $msg);
                }

                // 3. Catat di tabel PayoutRequest dengan status PROCESSED
                // Ini OTOMATIS memotong $availableBalance pada SellerBalanceService detik ini juga!
                $payout = PayoutRequest::create([
                    'seller_id'           => $seller->id,
                    'amount'              => $amount,
                    'admin_fee'           => 0,
                    'net_amount'          => $amount,
                    'status'              => 'processed',
                    'bank_name'           => 'Deposit Kasir POS (Transfer Internal)',
                    'bank_account_number' => 'POS-DEPOSIT',
                    'bank_account_name'   => $profile->store_name ?? $seller->name,
                    'notes'               => 'Pengalihan Saldo Toko ke Deposit Kasir POS',
                    'admin_notes'         => 'Transfer internal instan via Saldo Toko',
                    'processed_by'        => $seller->id,
                    'processed_at'        => now(),
                ]);

                // 4. Tambahkan saldo deposit POS di CreatorProfile
                $balanceBefore = (float) ($profile->pos_balance ?? 0);
                $balanceAfter  = $balanceBefore + $amount;
                $profile->update(['pos_balance' => $balanceAfter]);

                // 5. Catat mutasi deposit POS di PosBalanceMutation
                PosBalanceMutation::create([
                    'creator_id'       => $profile->id,
                    'type'             => 'topup',
                    'amount'           => $amount,
                    'balance_before'   => $balanceBefore,
                    'balance_after'    => $balanceAfter,
                    'reference_number' => 'TRF-POS-' . strtoupper(Str::random(8)),
                    'notes'            => 'Top-up dari Saldo Toko Siap Ditarik (Ref: PR-' . $payout->id . ')',
                ]);

                Log::info('Transfer Saldo Toko ke Deposit POS Sukses', [
                    'seller_id'      => $seller->id,
                    'amount'         => $amount,
                    'payout_id'      => $payout->id,
                    'deposit_before' => $balanceBefore,
                    'deposit_after'  => $balanceAfter,
                ]);

                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success'           => true,
                        'message'           => 'Berhasil memindahkan Rp ' . number_format($amount, 0, ',', '.') . ' ke Deposit Kasir POS!',
                        'new_pos_balance'   => $balanceAfter,
                        'available_balance' => max(0, $availableBalance - $amount),
                    ]);
                }

                return back()->with('success', 'Berhasil memindahkan Rp ' . number_format($amount, 0, ',', '.') . ' ke Deposit Kasir POS! Saldo siap ditarik telah terpotong dan deposit POS telah bertambah.');
            });
        } catch (\Exception $e) {
            Log::error('Gagal transfer saldo ke deposit POS: ' . $e->getMessage());
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Gagal memproses pemindahan saldo: ' . $e->getMessage());
        }
    }
}
