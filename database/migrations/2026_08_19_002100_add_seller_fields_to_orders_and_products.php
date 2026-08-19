<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan platform_fee & buyer_id ke orders (seller_id sudah ada dari migration sebelumnya)
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('platform_fee', 15, 2)->default(0)->after('total');
            $table->decimal('seller_amount', 15, 2)->default(0)->after('platform_fee');
        });

        // Tambahkan file_path & snap_token ke payments (untuk digital delivery)
        Schema::table('payments', function (Blueprint $table) {
            $table->string('snap_token', 512)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['platform_fee', 'seller_amount']);
        });
    }
};
