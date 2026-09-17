<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('promo_sections', 'product_type_filter')) {
                $table->string('product_type_filter', 50)->nullable()->after('category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('promo_sections', function (Blueprint $table) {
            $table->dropColumn('product_type_filter');
        });
    }
};
