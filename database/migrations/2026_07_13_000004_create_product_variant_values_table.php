<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel product_variant_values menyimpan nilai dari setiap opsi varian,
     * misal: "CV-45", "CV-60" untuk opsi "Ukuran".
     */
    public function up(): void
    {
        Schema::create('product_variant_values', function (Blueprint $table) {
            $table->id();

            // FK ke product_variant_options
            $table->foreignId('variant_option_id')
                ->constrained('product_variant_options')
                ->cascadeOnDelete();

            // Nilai varian, misal: "CV-45", "Merah", "S/M/L/XL"
            $table->string('value');

            // Penyesuaian harga dari harga dasar produk (bisa negatif)
            $table->decimal('price_adjustment', 15, 2)->default(0);

            // Stok khusus untuk varian ini (jika null, pakai stok produk utama)
            $table->unsignedInteger('stock')->nullable();

            // SKU khusus untuk varian ini
            $table->string('sku')->nullable()->unique();

            $table->timestamps();

            $table->index('variant_option_id');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_values');
    }
};
