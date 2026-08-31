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
            $table->string('bio_role', 50)->nullable()->after('store_slug'); // content_creator, affiliator, business
            $table->string('bio_theme', 50)->nullable()->after('bio_role'); // theme1, theme2, etc
            $table->json('bio_config')->nullable()->after('bio_theme'); // extra config for styling/features
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            //
        });
    }
};
