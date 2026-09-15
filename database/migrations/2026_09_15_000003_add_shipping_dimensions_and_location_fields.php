<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add length, width, height, volume to products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'length')) {
                $table->integer('length')->nullable()->default(0)->after('weight');
            }
            if (!Schema::hasColumn('products', 'width')) {
                $table->integer('width')->nullable()->default(0)->after('length');
            }
            if (!Schema::hasColumn('products', 'height')) {
                $table->integer('height')->nullable()->default(0)->after('width');
            }
            if (!Schema::hasColumn('products', 'volume')) {
                $table->integer('volume')->nullable()->default(0)->after('height');
            }
        });

        // 2. Add subdistrict_name, village_name, postal_code to creator_profiles table
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('creator_profiles', 'subdistrict_name')) {
                $table->string('subdistrict_name')->nullable()->after('subdistrict_id');
            }
            if (!Schema::hasColumn('creator_profiles', 'village_name')) {
                $table->string('village_name')->nullable()->after('subdistrict_name');
            }
            if (!Schema::hasColumn('creator_profiles', 'postal_code')) {
                $table->string('postal_code', 20)->nullable()->after('village_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['length', 'width', 'height', 'volume']);
        });

        Schema::table('creator_profiles', function (Blueprint $table) {
            $table->dropColumn(['subdistrict_name', 'village_name', 'postal_code']);
        });
    }
};
