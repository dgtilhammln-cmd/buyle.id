<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel orders adalah pusat dari sistem e-commerce.
     * Menggunakan soft delete untuk keamanan data historis.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Format: ORD-20260713-0001 (unik, dibuat secara programatik)
            $table->string('order_number', 30)->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Status pesanan menggunakan Enum (didefinisikan di App\Enums\OrderStatus)
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
                'refunded',
            ])->default('pending');

            // Kalkulasi harga
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            // Alamat pengiriman disimpan sebagai JSON snapshot
            // agar tidak terpengaruh jika user mengubah alamatnya
            $table->json('shipping_address');

            // Catatan dari pembeli
            $table->text('notes')->nullable();

            $table->timestamps();

            // Soft delete untuk keamanan data historis transaksi
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index('order_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
