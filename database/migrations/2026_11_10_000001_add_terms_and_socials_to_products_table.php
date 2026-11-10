<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'terms_conditions')) {
                $table->longText('terms_conditions')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'instagram_username')) {
                $table->string('instagram_username', 100)->nullable()->after('event_platform');
            }
            if (!Schema::hasColumn('products', 'tiktok_username')) {
                $table->string('tiktok_username', 100)->nullable()->after('instagram_username');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['terms_conditions', 'instagram_username', 'tiktok_username']);
        });
    }
};
