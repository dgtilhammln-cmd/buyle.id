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
        Schema::table('products', function (Blueprint $table) {
            $table->string('pricing_type', 30)->default('fixed')->after('sale_price');
            $table->decimal('pwyw_min_price', 12, 2)->nullable()->after('pricing_type');
            $table->decimal('pwyw_suggested_price', 12, 2)->nullable()->after('pwyw_min_price');
            $table->string('traktir_unit_name', 50)->nullable()->default('Kopi')->after('pwyw_suggested_price');
            $table->string('traktir_unit_icon', 50)->nullable()->default('coffee')->after('traktir_unit_name');
            $table->decimal('traktir_unit_price', 12, 2)->nullable()->after('traktir_unit_icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'pricing_type',
                'pwyw_min_price',
                'pwyw_suggested_price',
                'traktir_unit_name',
                'traktir_unit_icon',
                'traktir_unit_price',
            ]);
        });
    }
};
