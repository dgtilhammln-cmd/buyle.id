<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            // Bio link click tracking
            $table->unsignedBigInteger('bio_block_id')->nullable()->after('device_type')->index();
            $table->unsignedBigInteger('bio_creator_id')->nullable()->after('bio_block_id')->index();
            // UTM params captured on the bio page
            $table->string('utm_source')->nullable()->after('bio_creator_id');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('utm_content')->nullable()->after('utm_campaign');
        });
    }

    public function down(): void
    {
        Schema::table('analytics_events', function (Blueprint $table) {
            $table->dropColumn(['bio_block_id', 'bio_creator_id', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content']);
        });
    }
};
