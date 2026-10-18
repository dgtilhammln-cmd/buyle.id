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
        // 1. Tambah kolom pos_balance di creator_profiles jika belum ada
        if (Schema::hasTable('creator_profiles') && !Schema::hasColumn('creator_profiles', 'pos_balance')) {
            Schema::table('creator_profiles', function (Blueprint $table) {
                $table->decimal('pos_balance', 14, 2)->default(0.00)->after('is_verified');
            });
        }

        // 2. Buat tabel pos_deposits untuk mencatat riwayat top-up saldo deposit kasir
        if (!Schema::hasTable('pos_deposits')) {
            Schema::create('pos_deposits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('creator_id')->constrained('creator_profiles')->onDelete('cascade');
                $table->string('deposit_number', 64)->unique();
                $table->decimal('amount', 14, 2);
                $table->string('payment_method', 32)->default('qris');
                $table->string('status', 32)->default('pending'); // pending, success, failed, expired
                $table->string('snap_token', 255)->nullable();
                $table->string('payment_ref', 128)->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->index(['creator_id', 'status']);
                $table->index('deposit_number');
            });
        }

        // 3. Buat tabel pos_balance_mutations untuk buku besar mutasi saldo deposit (topup & potongan fee)
        if (!Schema::hasTable('pos_balance_mutations')) {
            Schema::create('pos_balance_mutations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('creator_id')->constrained('creator_profiles')->onDelete('cascade');
                $table->string('type', 32); // topup, fee_deduction, adjustment
                $table->decimal('amount', 14, 2);
                $table->decimal('balance_before', 14, 2);
                $table->decimal('balance_after', 14, 2);
                $table->unsignedBigInteger('order_id')->nullable();
                $table->string('reference_number', 64)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['creator_id', 'type']);
                $table->index('order_id');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_balance_mutations');
        Schema::dropIfExists('pos_deposits');

        if (Schema::hasTable('creator_profiles') && Schema::hasColumn('creator_profiles', 'pos_balance')) {
            Schema::table('creator_profiles', function (Blueprint $table) {
                $table->dropColumn('pos_balance');
            });
        }
    }
};
