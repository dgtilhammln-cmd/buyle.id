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
        Schema::table('product_variant_values', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variant_values', 'image')) {
                $table->string('image')->nullable()->after('value');
            }
            if (!Schema::hasColumn('product_variant_values', 'price')) {
                $table->decimal('price', 15, 2)->nullable()->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variant_values', function (Blueprint $table) {
            if (Schema::hasColumn('product_variant_values', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('product_variant_values', 'price')) {
                $table->dropColumn('price');
            }
        });
    }
};
