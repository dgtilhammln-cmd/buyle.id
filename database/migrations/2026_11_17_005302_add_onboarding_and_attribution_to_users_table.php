<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_persona', 50)->nullable()->after('role'); // digital_product, fnb, event_creator, wedding, content_creator, b2b_corporate
            $table->string('referral_source_type', 50)->nullable()->after('business_persona'); // social_media, media_partner, search_engine, friend_referral, other
            $table->string('referral_source_name', 100)->nullable()->after('referral_source_type'); // Instagram, TikTok, etc.
            $table->string('media_partner_name', 100)->nullable()->after('referral_source_name'); // Nama Media Partner
            $table->unsignedBigInteger('media_partner_id')->nullable()->after('media_partner_name');
            $table->string('referral_code', 50)->nullable()->after('media_partner_id');
            $table->string('primary_goal', 50)->nullable()->after('referral_code'); // products, bio, pos, ticketing, wedding
            $table->timestamp('onboarding_completed_at')->nullable()->after('primary_goal');
        });

        // Set existing users to completed so only new users see the onboarding wizard
        DB::table('users')
            ->whereNull('onboarding_completed_at')
            ->update(['onboarding_completed_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'business_persona',
                'referral_source_type',
                'referral_source_name',
                'media_partner_name',
                'media_partner_id',
                'referral_code',
                'primary_goal',
                'onboarding_completed_at',
            ]);
        });
    }
};
