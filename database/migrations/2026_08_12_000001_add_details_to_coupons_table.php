<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            // Kategori voucher: 'product', 'shipping', 'member', 'event', 'referral'
            $table->string('category', 50)->default('product')->after('code');
            // Deskripsi singkat untuk ditampilkan di UI
            $table->string('description', 255)->nullable()->after('category');
            // Teks badge (misal: "Baru", "Terbatas", "Gratis Ongkir")
            $table->string('badge', 50)->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['category', 'description', 'badge']);
        });
    }
};
