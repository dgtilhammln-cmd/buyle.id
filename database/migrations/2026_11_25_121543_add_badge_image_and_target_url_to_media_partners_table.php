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
        Schema::table('media_partners', function (Blueprint $table) {
            if (!Schema::hasColumn('media_partners', 'badge_image')) {
                $table->string('badge_image')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('media_partners', 'target_url')) {
                $table->string('target_url', 1000)->nullable()->after('badge_image');
            }
            if (!Schema::hasColumn('media_partners', 'show_on_bio')) {
                $table->boolean('show_on_bio')->default(true)->after('target_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_partners', function (Blueprint $table) {
            $table->dropColumn(['badge_image', 'target_url', 'show_on_bio']);
        });
    }
};
