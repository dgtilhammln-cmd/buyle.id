<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Modifikasi tabel `services` yang digunakan sebagai tabel products.
     * Menambahkan kolom-kolom yang dibutuhkan untuk fitur e-commerce.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Harga & Stok
            $table->decimal('price', 15, 2)->default(0)->after('is_active');
            $table->decimal('sale_price', 15, 2)->nullable()->after('price');
            $table->unsignedInteger('stock')->default(0)->after('sale_price');
            $table->unsignedInteger('weight')->default(0)->comment('Berat dalam gram')->after('stock');

            // SKU
            $table->string('sku')->nullable()->unique()->after('weight');

            // Relasi ke kategori produk
            $table->foreignId('product_category_id')
                ->nullable()
                ->after('sku')
                ->constrained('product_categories')
                ->nullOnDelete();

            // Flags
            $table->boolean('is_featured')->default(false)->after('is_active');

            $table->index(['product_category_id', 'is_active']);
            $table->index('is_featured');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['product_category_id']);
            $table->dropIndex(['product_category_id', 'is_active']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['sku']);
            $table->dropColumn([
                'price',
                'sale_price',
                'stock',
                'weight',
                'sku',
                'product_category_id',
                'is_featured',
            ]);
        });
    }
};
