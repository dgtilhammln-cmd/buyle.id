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
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'seller_id')) {
                $table->foreignId('seller_id')->nullable()->after('product_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('order_items', 'reseller_id')) {
                $table->foreignId('reseller_id')->nullable()->after('seller_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('order_items', 'base_whitelabel_price')) {
                $table->decimal('base_whitelabel_price', 15, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('order_items', 'reseller_margin')) {
                $table->decimal('reseller_margin', 15, 2)->default(0)->after('base_whitelabel_price');
            }
            if (!Schema::hasColumn('order_items', 'creator_earnings')) {
                $table->decimal('creator_earnings', 15, 2)->default(0)->after('reseller_margin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropForeign(['reseller_id']);
            $table->dropColumn(['seller_id', 'reseller_id', 'base_whitelabel_price', 'reseller_margin', 'creator_earnings']);
        });
    }
};
