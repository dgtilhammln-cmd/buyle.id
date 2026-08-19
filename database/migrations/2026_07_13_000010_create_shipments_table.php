<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel shipments menyimpan informasi pengiriman per order.
     * One-to-one dengan orders.
     */
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();

            // One-to-one dengan orders
            $table->foreignId('order_id')
                ->unique()
                ->constrained('orders')
                ->cascadeOnDelete();

            // Informasi kurir (misal: JNE, SiCepat, Anteraja)
            $table->string('courier_name', 50);
            $table->string('courier_service', 50)->nullable(); // misal: REG, YES, OKE

            // Nomor resi pengiriman
            $table->string('tracking_number', 100)->nullable()->unique();

            // Status pengiriman
            $table->enum('status', [
                'pending',
                'picked_up',
                'in_transit',
                'delivered',
                'returned',
            ])->default('pending');

            // Estimasi tanggal pengiriman
            $table->date('estimated_delivery')->nullable();

            // Waktu milestone pengiriman
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            // Raw data tracking dari API kurir (untuk menampilkan riwayat pengiriman)
            $table->json('raw_tracking')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
