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
            if (!Schema::hasColumn('creator_profiles', 'pos_cashless_mode')) {
                $table->string('pos_cashless_mode')->default('snap_midtrans')->after('pos_tables');
            }
            if (!Schema::hasColumn('creator_profiles', 'pos_qris_image')) {
                $table->string('pos_qris_image')->nullable()->after('pos_cashless_mode');
            }
            if (!Schema::hasColumn('creator_profiles', 'pos_fee_payer')) {
                $table->string('pos_fee_payer')->default('seller')->after('pos_qris_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('creator_profiles', 'pos_cashless_mode')) {
                $table->dropColumn('pos_cashless_mode');
            }
            if (Schema::hasColumn('creator_profiles', 'pos_qris_image')) {
                $table->dropColumn('pos_qris_image');
            }
            if (Schema::hasColumn('creator_profiles', 'pos_fee_payer')) {
                $table->dropColumn('pos_fee_payer');
            }
        });
    }
};

