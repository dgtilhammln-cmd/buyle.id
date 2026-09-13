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
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('creator_profiles', 'last_menu_scan_at')) {
                $table->timestamp('last_menu_scan_at')->nullable()->after('bio_config');
            }
            if (!Schema::hasColumn('creator_profiles', 'monthly_scan_count')) {
                $table->unsignedInteger('monthly_scan_count')->default(0)->after('last_menu_scan_at');
            }
            if (!Schema::hasColumn('creator_profiles', 'scan_count_reset_at')) {
                $table->timestamp('scan_count_reset_at')->nullable()->after('monthly_scan_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            $table->dropColumn(['last_menu_scan_at', 'monthly_scan_count', 'scan_count_reset_at']);
        });
    }
};
