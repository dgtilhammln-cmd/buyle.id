<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\User;
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

        $freePayoutSlots = (int) ($seller->free_payout_slots ?? 3);

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
            'freePayoutSlots',
            'requests'
        ));
    }

    public function requestPayout(Request $request)
    {
        $seller = auth()->user();

        // Wajib set PIN keamanan sebelum penarikan saldo
        if (empty($seller->security_pin) && empty($seller->pos_pin)) {
            return redirect()->route('creator.menu')->with('error', 'Anda wajib mengatur PIN keamanan terlebih dahulu sebelum melakukan penarikan saldo.');
        }

        // Cek masa pendinginan (Cool-down) setelah reset PIN (buylepay.md 7.5)
        if ($seller->transfer_cooldown_until && now()->lt(Carbon::parse($seller->transfer_cooldown_until))) {
            $formattedTime = Carbon::parse($seller->transfer_cooldown_until)->isoFormat('D MMMM Y, HH:mm');
            return back()->with('error', "Akun Anda dalam masa pendinginan keamanan hingga {$formattedTime} WIB. Penarikan dana dibekukan sementara.");
        }

        // Cek apakah PIN terkunci karena salah 3x (buylepay.md 7.4)
        if ($seller->pin_locked_until && now()->lt(Carbon::parse($seller->pin_locked_until))) {
            $minutes = now()->diffInMinutes(Carbon::parse($seller->pin_locked_until)) + 1;
            return back()->with('error', "Akses transaksi Anda sedang dikunci sementara karena kesalahan PIN. Coba lagi dalam {$minutes} menit.");
        }

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

        // DB Transaction + Pessimistic Lock (buylepay.md 7.1 — anti double withdrawal)
        return DB::transaction(function () use ($seller, $validated, $bankName, $bankAccountNumber, $bankAccountName) {
            $lockedSeller = User::where('id', $seller->id)->lockForUpdate()->firstOrFail();

            // Hitung ulang saldo siap ditarik setelah row lock diperoleh
            $balanceData      = \App\Services\SellerBalanceService::calculateBalance($lockedSeller);
            $availableBalance = (float) $balanceData['available_balance'];

            if ($validated['amount'] > $availableBalance) {
                return back()->with('error', 'Jumlah penarikan melebihi saldo yang tersedia (Rp ' . number_format($availableBalance, 0, ',', '.') . '). Minimal penarikan Rp 50.000.');
            }

            // Cek tidak ada payout pending (dengan lock)
            $hasPending = PayoutRequest::where('seller_id', $lockedSeller->id)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->exists();

            if ($hasPending) {
                return back()->with('error', 'Masih ada permintaan pencairan yang sedang diproses. Tunggu hingga selesai sebelum mengajukan yang baru.');
            }

            // Logika Biaya Admin: Gratis jika memiliki kuota free_payout_slots, selain itu Rp 5.000
            $freeSlots = (int) ($lockedSeller->free_payout_slots ?? 0);
            $adminFee  = $freeSlots > 0 ? 0 : 5000;
            $netAmount = max(0, $validated['amount'] - $adminFee);

            // Simpan info rekening ke profil seller jika baru diinput
            if (!empty($validated['bank_name'])) {
                $lockedSeller->update([
                    'bank_name'           => $bankName,
                    'bank_account_number' => $bankAccountNumber,
                    'bank_account_name'   => $bankAccountName,
                ]);
            }

            PayoutRequest::create([
                'seller_id'           => $lockedSeller->id,
                'amount'              => $validated['amount'],
                'admin_fee'           => $adminFee,
                'net_amount'          => $netAmount,
                'bank_name'           => $bankName,
                'bank_account_number' => $bankAccountNumber,
                'bank_account_name'   => $bankAccountName,
                'status'              => 'pending',
            ]);

            if ($freeSlots > 0) {
                $lockedSeller->decrement('free_payout_slots');
                $remainingSlots = (int) $lockedSeller->fresh()->free_payout_slots;
                $successMsg = 'Permintaan pencairan Rp ' . number_format($validated['amount'], 0, ',', '.') . ' berhasil diajukan tanpa potongan biaya admin (Sisa kuota bebas biaya: ' . $remainingSlots . 'x). Dana akan dikirim dalam 1-3 hari kerja.';
            } else {
                $successMsg = 'Permintaan pencairan Rp ' . number_format($validated['amount'], 0, ',', '.') . ' berhasil diajukan dengan biaya admin Rp 5.000 (Dana bersih diterima: Rp ' . number_format($netAmount, 0, ',', '.') . '). Dana akan dikirim dalam 1-3 hari kerja.';
            }

            // Kirim notifikasi email konfirmasi ke creator
            try {
                $lockedSeller->notify(new \App\Notifications\PayoutRequestedNotification($netAmount, $bankName, $bankAccountNumber));
            } catch (\Throwable $e) {
                \Log::warning('Gagal kirim notifikasi PayoutRequested: ' . $e->getMessage());
            }

            // Kirim notifikasi alert real-time ke semua Admin
            try {
                $admins = \App\Models\User::whereIn('role', ['admin', 'superadmin'])->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\AdminPayoutAlertNotification(
                        $lockedSeller,
                        $netAmount,
                        $bankName,
                        $bankAccountNumber,
                        $bankAccountName
                    ));
                }
            } catch (\Throwable $e) {
                \Log::warning('Gagal kirim notifikasi payout ke Admin: ' . $e->getMessage());
            }

            return back()->with('success', $successMsg);
        });
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
     * Pindahkan saldo Store yang siap ditarik ke Saldo Deposit Kasir POS secara instan & aman.
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

        // Cek masa pendinginan (Cool-down) setelah reset PIN (buylepay.md 7.5)
        if ($seller->transfer_cooldown_until && now()->lt(Carbon::parse($seller->transfer_cooldown_until))) {
            $formattedTime = Carbon::parse($seller->transfer_cooldown_until)->isoFormat('D MMMM Y, HH:mm');
            $msg = "Akun Anda dalam masa pendinginan keamanan hingga {$formattedTime} WIB. Pemindahan saldo dibekukan sementara.";
            return request()->wantsJson() ? response()->json(['success' => false, 'message' => $msg], 403) : back()->with('error', $msg);
        }

        // Cek apakah PIN terkunci karena salah 3x (buylepay.md 7.4)
        if ($seller->pin_locked_until && now()->lt(Carbon::parse($seller->pin_locked_until))) {
            $minutes = now()->diffInMinutes(Carbon::parse($seller->pin_locked_until)) + 1;
            $msg = "Akses transaksi Anda sedang dikunci sementara karena kesalahan PIN. Coba lagi dalam {$minutes} menit.";
            return request()->wantsJson() ? response()->json(['success' => false, 'message' => $msg], 423) : back()->with('error', $msg);
        }

        try {
            return DB::transaction(function () use ($seller, $amount) {
                // 1. Kunci profil creator
                $profile = CreatorProfile::where('user_id', $seller->id)->lockForUpdate()->first();
                if (!$profile) {
                    return back()->with('error', 'Profil Anda belum dikonfigurasi.');
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
                    'notes'               => 'Pengalihan Saldo Store ke Deposit Kasir POS',
                    'admin_notes'         => 'Transfer internal instan via Saldo Store',
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
                    'notes'            => 'Top-up dari Saldo Store Siap Ditarik (Ref: PR-' . $payout->id . ')',
                ]);

                Log::info('Transfer Saldo Store ke Deposit POS Sukses', [
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
