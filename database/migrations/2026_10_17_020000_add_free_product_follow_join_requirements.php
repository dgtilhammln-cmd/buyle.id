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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('require_follow_join')->default(false)->after('max_tickets_per_order');
            $table->string('social_follow_url', 500)->nullable()->after('require_follow_join');
            $table->string('community_join_url', 500)->nullable()->after('social_follow_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['require_follow_join', 'social_follow_url', 'community_join_url']);
        });
    }
};
