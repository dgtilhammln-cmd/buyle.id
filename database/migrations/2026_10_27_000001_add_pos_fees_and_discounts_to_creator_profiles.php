<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('creator_profiles', 'pos_fees')) {
                $table->json('pos_fees')->nullable()->after('pos_store_phone');
            }
            if (!Schema::hasColumn('creator_profiles', 'pos_discounts')) {
                $table->json('pos_discounts')->nullable()->after('pos_fees');
            }
        });
    }

    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('creator_profiles', 'pos_fees')) {
                $table->dropColumn('pos_fees');
            }
            if (Schema::hasColumn('creator_profiles', 'pos_discounts')) {
                $table->dropColumn('pos_discounts');
            }
        });
    }
};
