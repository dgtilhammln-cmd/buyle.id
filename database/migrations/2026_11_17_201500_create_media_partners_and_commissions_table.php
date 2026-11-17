<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('media_partners')) {
            Schema::create('media_partners', function (Blueprint $table) {
                $table->id();
                $table->string('name', 120);
                $table->string('code', 60)->unique();
                $table->string('pic_name', 100)->nullable();
                $table->string('pic_phone', 30)->nullable();
                $table->string('pic_email', 150)->nullable();
                $table->decimal('commission_rate', 5, 2)->default(2.00);
                $table->string('bank_name', 100)->nullable();
                $table->string('bank_account_number', 50)->nullable();
                $table->string('bank_account_name', 100)->nullable();
                $table->decimal('total_commission_earned', 15, 2)->default(0);
                $table->decimal('total_commission_paid', 15, 2)->default(0);
                $table->decimal('balance', 15, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('media_partner_commission_logs')) {
            Schema::create('media_partner_commission_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('media_partner_id')->constrained('media_partners')->cascadeOnDelete();
                $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('order_item_id')->nullable()->constrained('order_items')->nullOnDelete();
                $table->string('event_name')->nullable();
                $table->decimal('ticket_price', 15, 2)->default(0);
                $table->integer('quantity')->default(1);
                $table->decimal('gross_amount', 15, 2)->default(0);
                $table->decimal('commission_rate', 5, 2)->default(0);
                $table->decimal('commission_amount', 15, 2)->default(0);
                $table->string('status', 30)->default('credited');
                $table->timestamp('paid_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index(['media_partner_id', 'status']);
                $table->index(['creator_id', 'media_partner_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('media_partner_commission_logs');
        Schema::dropIfExists('media_partners');
    }
};
