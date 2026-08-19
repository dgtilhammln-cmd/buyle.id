<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel order_items menyimpan snapshot detail produk saat pembelian.
     * product_name dan variant_name disimpan terpisah untuk mencegah data
     * rusak jika produk asli dihapus/diubah di kemudian hari.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // FK ke produk asli (nullable jika produk sudah dihapus)
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('services')
                ->nullOnDelete();

            // FK ke varian yang dipilih (nullable)
            $table->foreignId('variant_value_id')
                ->nullable()
                ->constrained('product_variant_values')
                ->nullOnDelete();

            // Snapshot nama produk & varian saat order dibuat
            $table->string('product_name');
            $table->string('variant_name')->nullable();

            // Harga satuan saat order dibuat (tidak mengikuti perubahan harga)
            $table->decimal('price', 15, 2);
            $table->unsignedSmallInteger('qty');
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
