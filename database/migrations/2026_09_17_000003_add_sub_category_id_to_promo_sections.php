<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('promo_sections', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable();
            }
            if (!Schema::hasColumn('promo_sections', 'sub_category_id')) {
                // Nullable FK ke product_sub_categories, opsional
                $table->unsignedBigInteger('sub_category_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('promo_sections', function (Blueprint $table) {
            if (Schema::hasColumn('promo_sections', 'category_id')) {
                $table->dropColumn('category_id');
            }
            if (Schema::hasColumn('promo_sections', 'sub_category_id')) {
                $table->dropColumn('sub_category_id');
            }
        });
    }
};
