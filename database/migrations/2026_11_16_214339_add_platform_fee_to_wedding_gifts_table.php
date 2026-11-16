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
        Schema::table('wedding_gifts', function (Blueprint $table) {
            // Platform fee Buyle.id Rp 5.000 per transaksi amplop digital
            $table->unsignedInteger('platform_fee')->default(0)->after('amount');
            // Gross amount yang dibayar pembeli (amount + platform_fee)
            $table->unsignedInteger('gross_amount')->default(0)->after('platform_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wedding_gifts', function (Blueprint $table) {
            $table->dropColumn(['platform_fee', 'gross_amount']);
        });
    }
};
