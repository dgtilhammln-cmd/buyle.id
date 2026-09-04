<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom event/tiket pada produk
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'event_date')) {
                $table->date('event_date')->nullable()->after('product_type');
            }
            if (!Schema::hasColumn('products', 'event_time')) {
                $table->string('event_time')->nullable()->after('event_date');
            }
            if (!Schema::hasColumn('products', 'event_location')) {
                $table->text('event_location')->nullable()->after('event_time');
            }
            if (!Schema::hasColumn('products', 'event_type')) {
                $table->string('event_type')->default('offline')->after('event_location'); // offline, online
            }
        });

        // 2. Tambah platform_fee & payment_gateway_fee pada orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'platform_fee')) {
                $table->decimal('platform_fee', 15, 2)->default(0)->after('shipping_cost');
            }
            if (!Schema::hasColumn('orders', 'payment_gateway_fee')) {
                $table->decimal('payment_gateway_fee', 15, 2)->default(0)->after('platform_fee');
            }
        });

        // 3. Tambah admin_fee & net_amount pada payout_requests
        Schema::table('payout_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('payout_requests', 'admin_fee')) {
                $table->decimal('admin_fee', 15, 2)->default(5000.00)->after('amount');
            }
            if (!Schema::hasColumn('payout_requests', 'net_amount')) {
                $table->decimal('net_amount', 15, 2)->default(0.00)->after('admin_fee');
            }
        });

        // 4. Buat tabel ticket_passes
        if (!Schema::hasTable('ticket_passes')) {
            Schema::create('ticket_passes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('order_item_id')->nullable()->constrained('order_items')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Buyer
                $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete(); // Organizer

                $table->string('ticket_code', 40)->unique();
                $table->string('qr_token', 100)->unique();

                $table->string('holder_name');
                $table->string('holder_email')->nullable();
                $table->string('holder_phone')->nullable();
                $table->string('holder_nik')->nullable();

                $table->enum('status', ['valid', 'used', 'cancelled'])->default('valid');
                $table->timestamp('checked_in_at')->nullable();
                $table->foreignId('checked_in_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();

                $table->index(['ticket_code', 'status']);
                $table->index('qr_token');
                $table->index(['seller_id', 'product_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_passes');

        Schema::table('payout_requests', function (Blueprint $table) {
            $table->dropColumn(['admin_fee', 'net_amount']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['platform_fee', 'payment_gateway_fee']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['event_date', 'event_time', 'event_location', 'event_type']);
        });
    }
};
