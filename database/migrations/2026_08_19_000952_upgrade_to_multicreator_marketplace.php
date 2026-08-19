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
        // Modifikasi tabel users
        Schema::table('users', function (Blueprint $table) {
            // Untuk SQLite, kita tidak bisa memakai ENUM. Kita biarkan role sebagai string, 
            // karena validasi akan ditangani di level aplikasi/middleware.
            // DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'seller', 'buyer') DEFAULT 'buyer'");
            
            // Index untuk email (biasanya sudah ada, tapi kita pastikan)
            $table->index('email');
        });

        // Rename services ke products
        Schema::rename('services', 'products');

        // Modifikasi tabel products
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('product_type', ['file_upload', 'external_link'])->default('external_link');
            $table->string('digital_resource')->nullable();

            // Index
            $table->index(['seller_id', 'is_active']);
        });

        // Modifikasi tabel orders
        Schema::table('orders', function (Blueprint $table) {
            // Asumsi user_id adalah buyer_id, kita tambahkan seller_id
            $table->foreignId('seller_id')->nullable()->constrained('users')->nullOnDelete();

            // Index untuk buyer_id (user_id), seller_id, status
            $table->index(['user_id', 'seller_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropIndex(['user_id', 'seller_id', 'status']);
            $table->dropColumn('seller_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropIndex(['seller_id', 'is_active']);
            $table->dropColumn(['seller_id', 'product_type', 'digital_resource']);
        });

        Schema::rename('products', 'services');

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            // DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(255) DEFAULT 'admin'");
        });
    }
};
