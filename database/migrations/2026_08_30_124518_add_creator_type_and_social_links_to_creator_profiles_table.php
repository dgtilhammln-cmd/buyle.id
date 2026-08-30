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
            if (!Schema::hasColumn('creator_profiles', 'creator_type')) {
                $table->string('creator_type')->nullable()->after('store_description');
            }
            if (!Schema::hasColumn('creator_profiles', 'social_links')) {
                $table->json('social_links')->nullable()->after('creator_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('creator_profiles', 'social_links')) {
                $table->dropColumn('social_links');
            }
            if (Schema::hasColumn('creator_profiles', 'creator_type')) {
                $table->dropColumn('creator_type');
            }
        });
    }
};
