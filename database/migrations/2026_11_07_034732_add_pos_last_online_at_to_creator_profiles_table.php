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
            if (!Schema::hasColumn('creator_profiles', 'pos_last_online_at')) {
                $table->timestamp('pos_last_online_at')->nullable()->after('pos_fee_payer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('creator_profiles', 'pos_last_online_at')) {
                $table->dropColumn('pos_last_online_at');
            }
        });
    }
};
