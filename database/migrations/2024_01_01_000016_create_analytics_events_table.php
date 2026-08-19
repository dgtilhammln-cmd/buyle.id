<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            // event_type: pageview, wa_click, phone_click, email_click
            $table->string('event_type')->index();
            $table->string('page_url', 1000)->nullable();
            $table->string('page_title')->nullable();
            $table->string('referrer', 1000)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable(); // IPv4 + IPv6
            $table->string('country')->nullable();
            $table->string('device_type')->nullable(); // mobile, tablet, desktop
            $table->timestamp('created_at')->useCurrent()->index();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
