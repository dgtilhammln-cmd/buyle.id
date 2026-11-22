<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletMutation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletLedgerService
{
    /**
     * Dapatkan dompet user, buat baru secara otomatis jika belum ada.
     */
    public static function getOrCreateWallet(User $user): Wallet
    {
        $wallet = Wallet::where('user_id', $user->id)->first();
        if ($wallet) {
            return $wallet;
        }

        return Wallet::create([
            'user_id'        => $user->id,
            'balance'        => 0.00,
            'escrow_balance' => 0.00,
            'locked_balance' => 0.00,
            'status'         => 'active',
            'tier'           => 'tier1',
        ]);
    }

    /**
     * Catat mutasi double-entry transaksi transfer antar pengguna Buyle Pay.
     * Bersifat atomik, idempoten, dan mencatat balance_before & balance_after.
     */
    public static function recordTransfer(
        User $sender,
        User $receiver,
        float $amount,
        ?string $notes = null,
        ?string $idempotencyKey = null,
        ?string $transferCode = null
    ): array {
        return DB::transaction(function () use ($sender, $receiver, $amount, $notes, $idempotencyKey, $transferCode) {
            $senderWallet = Wallet::where('user_id', $sender->id)->lockForUpdate()->first();
            if (!$senderWallet) {
                $senderWallet = self::getOrCreateWallet($sender);
                $senderWallet = Wallet::where('id', $senderWallet->id)->lockForUpdate()->first();
            }

            $receiverWallet = Wallet::where('user_id', $receiver->id)->lockForUpdate()->first();
            if (!$receiverWallet) {
                $receiverWallet = self::getOrCreateWallet($receiver);
                $receiverWallet = Wallet::where('id', $receiverWallet->id)->lockForUpdate()->first();
            }

            // Hitung mutasi pengirim (Debit)
            $senderBefore = (float) $senderWallet->balance;
            $senderAfter  = max(0, $senderBefore - $amount);
            $senderWallet->update(['balance' => $senderAfter]);

            $debitRef = 'MUT-OUT-' . strtoupper(Str::random(10)) . '-' . time();
            $debitMutation = WalletMutation::create([
                'reference_id'    => $debitRef,
                'wallet_id'       => $senderWallet->id,
                'user_id'         => $sender->id,
                'type'            => 'debit',
                'amount'          => $amount,
                'balance_before'  => $senderBefore,
                'balance_after'   => $senderAfter,
                'category'        => 'transfer_out',
                'description'     => 'Transfer saldo ke @' . $receiver->username . ($notes ? ': ' . $notes : ''),
                'metadata'        => [
                    'transfer_code'     => $transferCode,
                    'receiver_id'       => $receiver->id,
                    'receiver_username' => $receiver->username,
                    'notes'             => $notes,
                ],
                'idempotency_key' => $idempotencyKey ? "deb_{$idempotencyKey}" : null,
                'created_at'      => now(),
            ]);

            // Hitung mutasi penerima (Credit)
            $receiverBefore = (float) $receiverWallet->balance;
            $receiverAfter  = $receiverBefore + $amount;
            $receiverWallet->update(['balance' => $receiverAfter]);

            $creditRef = 'MUT-IN-' . strtoupper(Str::random(10)) . '-' . time();
            $creditMutation = WalletMutation::create([
                'reference_id'    => $creditRef,
                'wallet_id'       => $receiverWallet->id,
                'user_id'         => $receiver->id,
                'type'            => 'credit',
                'amount'          => $amount,
                'balance_before'  => $receiverBefore,
                'balance_after'   => $receiverAfter,
                'category'        => 'transfer_in',
                'description'     => 'Terima transfer saldo dari @' . $sender->username . ($notes ? ': ' . $notes : ''),
                'metadata'        => [
                    'transfer_code'   => $transferCode,
                    'sender_id'       => $sender->id,
                    'sender_username' => $sender->username,
                    'notes'           => $notes,
                ],
                'idempotency_key' => $idempotencyKey ? "crd_{$idempotencyKey}" : null,
                'created_at'      => now(),
            ]);

            return [
                'sender_mutation'   => $debitMutation,
                'receiver_mutation' => $creditMutation,
            ];
        });
    }
}
