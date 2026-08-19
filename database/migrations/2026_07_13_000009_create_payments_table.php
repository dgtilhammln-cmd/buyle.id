<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel payments menyimpan detail pembayaran per order.
     * Dirancang untuk integrasi dengan payment gateway Midtrans.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // One-to-one dengan orders
            $table->foreignId('order_id')
                ->unique()
                ->constrained('orders')
                ->cascadeOnDelete();

            // Nomor pembayaran internal (misal: PAY-20260713-0001)
            $table->string('payment_number', 30)->unique();

            // Metode pembayaran yang dipilih user (transfer, qris, dll.)
            $table->string('method', 50)->nullable();

            // Gateway yang digunakan (midtrans, xendit, dll.)
            $table->string('gateway', 50)->default('midtrans');

            // Status pembayaran
            $table->enum('status', [
                'pending',
                'success',
                'failed',
                'expired',
                'refunded',
            ])->default('pending');

            $table->decimal('amount', 15, 2);

            // Data spesifik Midtrans
            $table->string('midtrans_transaction_id')->nullable()->unique();
            $table->text('midtrans_token')->nullable();
            $table->text('midtrans_redirect_url')->nullable();

            // Waktu pembayaran dan kedaluwarsa
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            // Raw response dari Midtrans (untuk audit & debugging)
            $table->json('raw_response')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('payment_number');
            $table->index('midtrans_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
