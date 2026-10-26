<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom pengaturan alamat dan nomor telepon toko khusus struk POS
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('creator_profiles', 'pos_store_address')) {
                $table->text('pos_store_address')->nullable()->after('pos_logo');
            }
            if (!Schema::hasColumn('creator_profiles', 'pos_store_phone')) {
                $table->string('pos_store_phone', 50)->nullable()->after('pos_store_address');
            }
        });

        // 2. Tambahkan kolom relasi kasir & jam shift ke tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'parent_seller_id')) {
                $table->foreignId('parent_seller_id')->nullable()->after('role')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'shift_hours')) {
                $table->string('shift_hours', 100)->nullable()->after('parent_seller_id');
            }
            if (!Schema::hasColumn('users', 'cashier_role_title')) {
                $table->string('cashier_role_title', 100)->nullable()->after('shift_hours');
            }
        });

        // 3. Tambahkan kolom pencatatan kasir ke tabel orders
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'cashier_id')) {
                $table->foreignId('cashier_id')->nullable()->after('seller_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'cashier_name')) {
                $table->string('cashier_name', 100)->nullable()->after('cashier_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('creator_profiles', 'pos_store_address')) {
                $table->dropColumn('pos_store_address');
            }
            if (Schema::hasColumn('creator_profiles', 'pos_store_phone')) {
                $table->dropColumn('pos_store_phone');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'parent_seller_id')) {
                $table->dropForeign(['parent_seller_id']);
                $table->dropColumn('parent_seller_id');
            }
            if (Schema::hasColumn('users', 'shift_hours')) {
                $table->dropColumn('shift_hours');
            }
            if (Schema::hasColumn('users', 'cashier_role_title')) {
                $table->dropColumn('cashier_role_title');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'cashier_id')) {
                $table->dropForeign(['cashier_id']);
                $table->dropColumn('cashier_id');
            }
            if (Schema::hasColumn('orders', 'cashier_name')) {
                $table->dropColumn('cashier_name');
            }
        });
    }
};
