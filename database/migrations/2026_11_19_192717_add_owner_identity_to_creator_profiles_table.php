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
            if (!Schema::hasColumn('creator_profiles', 'owner_name')) {
                $table->string('owner_name', 150)->nullable()->after('store_name');
            }
            if (!Schema::hasColumn('creator_profiles', 'age')) {
                $table->unsignedInteger('age')->nullable()->after('owner_name');
            }
            if (!Schema::hasColumn('creator_profiles', 'occupation_status')) {
                $table->string('occupation_status', 60)->nullable()->after('age');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            $table->dropColumn(['owner_name', 'age', 'occupation_status']);
        });
    }
};
