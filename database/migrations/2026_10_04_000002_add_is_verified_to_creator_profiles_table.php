<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('creator_profiles', 'is_verified')) {
                $table->boolean('is_verified')->default(false)->after('is_exclusive');
            }
        });
    }

    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('creator_profiles', 'is_verified')) {
                $table->dropColumn('is_verified');
            }
        });
    }
};
