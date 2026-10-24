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
        Schema::table('weddings', function (Blueprint $table) {
            $table->string('payment_status', 20)->default('paid')->after('theme'); // 'paid', 'pending', 'cancelled'
            $table->unsignedInteger('guest_limit')->default(150)->after('payment_status');
            $table->decimal('paid_amount', 12, 2)->default(250000)->after('guest_limit');
            $table->string('midtrans_order_id')->nullable()->after('paid_amount');
            $table->string('snap_token')->nullable()->after('midtrans_order_id');
            $table->timestamp('paid_at')->nullable()->after('snap_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'guest_limit',
                'paid_amount',
                'midtrans_order_id',
                'snap_token',
                'paid_at',
            ]);
        });
    }
};
