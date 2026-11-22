<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel wallets (Buku Besar Akun Buyle Pay)
        if (!Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->decimal('balance', 15, 2)->default(0.00);
                $table->decimal('escrow_balance', 15, 2)->default(0.00);
                $table->decimal('locked_balance', 15, 2)->default(0.00);
                $table->enum('status', ['active', 'frozen', 'suspended'])->default('active');
                $table->enum('tier', ['tier1', 'tier2'])->default('tier1');
                $table->timestamps();

                $table->index('status');
                $table->index('tier');
            });
        }

        // 2. Tabel wallet_mutations (Buku Besar Transaksi Double-Entry)
        if (!Schema::hasTable('wallet_mutations')) {
            Schema::create('wallet_mutations', function (Blueprint $table) {
                $table->id();
                $table->string('reference_id', 64)->unique();
                $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->enum('type', ['credit', 'debit']);
                $table->decimal('amount', 15, 2);
                $table->decimal('balance_before', 15, 2);
                $table->decimal('balance_after', 15, 2);
                $table->enum('category', [
                    'topup',
                    'transfer_in',
                    'transfer_out',
                    'order_payment',
                    'order_revenue',
                    'withdrawal',
                    'refund',
                    'fee'
                ]);
                $table->string('description', 255);
                $table->json('metadata')->nullable();
                $table->string('idempotency_key', 100)->nullable()->unique();
                $table->timestamp('created_at')->useCurrent();

                $table->index(['wallet_id', 'created_at']);
                $table->index(['user_id', 'created_at']);
                $table->index('category');
            });
        }

        // 3. Tabel wallet_topups (Riwayat Pengisian Saldo Buyle Pay)
        if (!Schema::hasTable('wallet_topups')) {
            Schema::create('wallet_topups', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_code', 50)->unique();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
                $table->decimal('amount', 15, 2);
                $table->decimal('admin_fee', 15, 2)->default(0.00);
                $table->decimal('total_amount', 15, 2);
                $table->string('payment_method', 50);
                $table->string('payment_gateway', 50)->default('midtrans');
                $table->string('payment_ref', 100)->nullable();
                $table->enum('status', ['pending', 'paid', 'expired', 'failed'])->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'status']);
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_topups');
        Schema::dropIfExists('wallet_mutations');
        Schema::dropIfExists('wallets');
    }
};
