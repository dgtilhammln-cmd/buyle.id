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
        // 1. Master Bahan Baku
        if (!Schema::hasTable('materials')) {
            Schema::create('materials', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('seller_id')->index();
                $table->string('name');
                $table->string('code')->nullable();
                $table->string('category')->default('kering'); // kering, cair_dingin, kemasan, bumbu, dll
                $table->string('unit')->default('gram'); // gram, ml, pcs, kg, liter, lembar
                $table->decimal('stock', 14, 2)->default(0);
                $table->decimal('min_stock', 14, 2)->default(0);
                $table->decimal('cost_per_unit', 14, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // 2. Resep Menu (BOM)
        if (!Schema::hasTable('product_recipes')) {
            Schema::create('product_recipes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id')->index();
                $table->unsignedBigInteger('material_id')->index();
                $table->decimal('quantity', 12, 2)->default(1);
                $table->string('notes')->nullable();
                $table->timestamps();

                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            });
        }

        // 3. Buku Besar Mutasi Stok Bahan
        if (!Schema::hasTable('material_mutations')) {
            Schema::create('material_mutations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('seller_id')->index();
                $table->unsignedBigInteger('material_id')->index();
                $table->string('type'); // in_purchase, out_pos_sale, adjustment_waste, adjustment_opname
                $table->decimal('amount', 14, 2);
                $table->decimal('stock_before', 14, 2)->default(0);
                $table->decimal('stock_after', 14, 2)->default(0);
                $table->decimal('unit_cost', 14, 2)->nullable();
                $table->unsignedBigInteger('order_id')->nullable()->index();
                $table->string('reference_note')->nullable();
                $table->timestamps();

                $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_mutations');
        Schema::dropIfExists('product_recipes');
        Schema::dropIfExists('materials');
    }
};
