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
        Schema::table('wedding_wishes', function (Blueprint $table) {
            if (!Schema::hasColumn('wedding_wishes', 'envelope_amount')) {
                $table->decimal('envelope_amount', 12, 2)->default(0)->after('attendance_status');
            }
            if (!Schema::hasColumn('wedding_wishes', 'payment_status')) {
                $table->string('payment_status', 50)->nullable()->after('envelope_amount');
            }
            if (!Schema::hasColumn('wedding_wishes', 'midtrans_order_id')) {
                $table->string('midtrans_order_id', 100)->nullable()->after('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wedding_wishes', function (Blueprint $table) {
            $table->dropColumn(['envelope_amount', 'payment_status', 'midtrans_order_id']);
        });
    }
};
