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
        Schema::table('product_categories', function (Blueprint $table) {
            $table->string('icon_type')->default('icon')->after('description'); // 'icon' or 'upload'
            $table->string('icon_value')->nullable()->after('icon_type');
            $table->string('badge_color')->nullable()->after('badge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['icon_type', 'icon_value', 'badge_color']);
        });
    }
};
