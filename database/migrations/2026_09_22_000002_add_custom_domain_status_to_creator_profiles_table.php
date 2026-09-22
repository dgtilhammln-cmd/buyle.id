<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('creator_profiles', 'custom_domain_status')) {
                $table->string('custom_domain_status', 30)->default('pending')->after('custom_domain');
            }
        });
    }

    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('creator_profiles', 'custom_domain_status')) {
                $table->dropColumn('custom_domain_status');
            }
        });
    }
};
