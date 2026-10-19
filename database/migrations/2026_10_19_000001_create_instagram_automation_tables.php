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
        // 1. Akun Instagram Bisnis/Kreator
        Schema::create('instagram_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('instagram_business_id')->nullable()->index();
            $table->string('page_id')->nullable();
            $table->string('username')->nullable()->index();
            $table->string('name')->nullable();
            $table->text('profile_picture_url')->nullable();
            $table->text('access_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->string('account_type')->default('BUSINESS');
            $table->unsignedInteger('daily_dm_limit')->default(200);
            $table->unsignedInteger('daily_dm_count')->default(0);
            $table->timestamp('last_dm_sent_at')->nullable();
            $table->boolean('is_connected')->default(true);
            $table->json('raw_metadata')->nullable();
            $table->timestamps();
        });

        // 2. Campaign Automation
        Schema::create('automation_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('instagram_account_id')->nullable()->constrained('instagram_accounts')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('name');
            $table->string('target_type')->default('all'); // 'all' atau 'specific'
            $table->string('target_media_id')->nullable()->index();
            $table->text('target_media_caption')->nullable();
            $table->text('target_media_url')->nullable();
            $table->string('trigger_type')->default('contains'); // 'any', 'exact', 'contains'
            $table->json('trigger_keywords')->nullable();
            $table->json('spintax_variants')->nullable(); // Minimal 3 variasi pesan
            $table->boolean('auto_comment_enabled')->default(false);
            $table->json('auto_comment_variants')->nullable();
            $table->unsignedTinyInteger('delay_seconds')->default(5); // 3 s/d 8 detik
            $table->boolean('auto_follow_check')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('total_comments_detected')->default(0);
            $table->unsignedInteger('total_dms_sent')->default(0);
            $table->unsignedInteger('total_clicks')->default(0);
            $table->unsignedInteger('total_orders')->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->timestamps();
        });

        // 3. Short Link Checkout Dinamis
        Schema::create('automation_short_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained('automation_campaigns')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('code')->unique()->index();
            $table->text('target_url');
            $table->unsignedInteger('clicks_count')->default(0);
            $table->unsignedInteger('orders_count')->default(0);
            $table->decimal('revenue', 15, 2)->default(0);
            $table->timestamps();
        });

        // 4. Log Aktivitas Realtime
        Schema::create('campaign_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained('automation_campaigns')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('sender_ig_id')->index();
            $table->string('sender_username')->nullable();
            $table->string('comment_id')->nullable()->index();
            $table->string('media_id')->nullable();
            $table->text('comment_text')->nullable();
            $table->text('dm_text_sent')->nullable();
            $table->text('public_reply_sent')->nullable();
            $table->string('status')->default('success'); // 'success', 'failed', 'rate_limited', 'queued'
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_logs');
        Schema::dropIfExists('automation_short_links');
        Schema::dropIfExists('automation_campaigns');
        Schema::dropIfExists('instagram_accounts');
    }
};
