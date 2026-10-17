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
        if (Schema::hasTable('carts')) {
            Schema::table('carts', function (Blueprint $table) {
                if (!Schema::hasColumn('carts', 'affiliate_id')) {
                    $table->foreignId('affiliate_id')
                        ->nullable()
                        ->after('variant_value_id')
                        ->constrained('users')
                        ->nullOnDelete();
                }
            });
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('order_items', 'affiliate_id')) {
                    $table->foreignId('affiliate_id')
                        ->nullable()
                        ->after('reseller_id')
                        ->constrained('users')
                        ->nullOnDelete();
                }

                if (!Schema::hasColumn('order_items', 'affiliate_commission_rate')) {
                    $table->decimal('affiliate_commission_rate', 5, 2)
                        ->default(0.00)
                        ->after('reseller_margin');
                }

                if (!Schema::hasColumn('order_items', 'affiliate_commission_amount')) {
                    $table->decimal('affiliate_commission_amount', 15, 2)
                        ->default(0.00)
                        ->after('affiliate_commission_rate');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('carts') && Schema::hasColumn('carts', 'affiliate_id')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropForeign(['affiliate_id']);
                $table->dropColumn(['affiliate_id']);
            });
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (Schema::hasColumn('order_items', 'affiliate_id')) {
                    $table->dropForeign(['affiliate_id']);
                    $table->dropColumn(['affiliate_id']);
                }
                $dropCols = [];
                if (Schema::hasColumn('order_items', 'affiliate_commission_rate')) {
                    $dropCols[] = 'affiliate_commission_rate';
                }
                if (Schema::hasColumn('order_items', 'affiliate_commission_amount')) {
                    $dropCols[] = 'affiliate_commission_amount';
                }
                if (!empty($dropCols)) {
                    $table->dropColumn($dropCols);
                }
            });
        }
    }
};
