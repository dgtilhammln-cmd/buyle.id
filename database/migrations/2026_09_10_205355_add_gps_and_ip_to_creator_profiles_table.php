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
            if (!Schema::hasColumn('creator_profiles', 'latitude')) {
                $table->string('latitude', 50)->nullable()->after('city_name');
            }
            if (!Schema::hasColumn('creator_profiles', 'longitude')) {
                $table->string('longitude', 50)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('creator_profiles', 'detected_ip')) {
                $table->string('detected_ip', 50)->nullable()->after('longitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('creator_profiles', 'latitude')) {
                $table->dropColumn(['latitude', 'longitude', 'detected_ip']);
            }
        });
    }
};
