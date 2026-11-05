<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('creator_profiles', 'pos_store_open')) {
                $table->string('pos_store_open', 10)->nullable()->after('pos_fee_payer');   // e.g. "08:00"
            }
            if (!Schema::hasColumn('creator_profiles', 'pos_store_close')) {
                $table->string('pos_store_close', 10)->nullable()->after('pos_store_open');  // e.g. "22:00"
            }
            if (!Schema::hasColumn('creator_profiles', 'pos_maps_link')) {
                $table->text('pos_maps_link')->nullable()->after('pos_store_close');
            }
            if (!Schema::hasColumn('creator_profiles', 'pos_accent_color')) {
                $table->string('pos_accent_color', 50)->nullable()->after('pos_maps_link');
            }
            if (!Schema::hasColumn('creator_profiles', 'pos_banner')) {
                $table->string('pos_banner')->nullable()->after('pos_accent_color');
            }
            if (!Schema::hasColumn('creator_profiles', 'pos_storefront_enabled')) {
                $table->boolean('pos_storefront_enabled')->default(false)->after('pos_banner');
            }
        });
    }

    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            $cols = ['pos_store_open', 'pos_store_close', 'pos_maps_link', 'pos_accent_color', 'pos_banner', 'pos_storefront_enabled'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('creator_profiles', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

