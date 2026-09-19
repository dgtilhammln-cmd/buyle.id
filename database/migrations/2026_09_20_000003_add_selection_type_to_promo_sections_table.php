<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('promo_sections', 'selection_type')) {
                $table->string('selection_type', 50)->default('manual')->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('promo_sections', function (Blueprint $table) {
            $table->dropColumn('selection_type');
        });
    }
};
