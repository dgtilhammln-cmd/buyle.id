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
        Schema::table('promo_sections', function (Blueprint $table) {
            $table->dateTime('start_time')->nullable()->after('view_all_url');
            $table->dateTime('end_time')->nullable()->after('start_time');
            $table->string('bg_color_1')->nullable()->after('end_time');
            $table->string('bg_color_2')->nullable()->after('bg_color_1');
            $table->string('logo')->nullable()->after('bg_color_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_sections', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time', 'bg_color_1', 'bg_color_2', 'logo']);
        });
    }
};
