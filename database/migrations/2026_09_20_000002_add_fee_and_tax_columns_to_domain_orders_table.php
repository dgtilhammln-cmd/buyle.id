<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('domain_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('domain_orders', 'base_amount')) {
                $table->decimal('base_amount', 12, 2)->default(0)->after('extension');
            }
            if (!Schema::hasColumn('domain_orders', 'platform_fee')) {
                $table->decimal('platform_fee', 12, 2)->default(0)->after('base_amount');
            }
            if (!Schema::hasColumn('domain_orders', 'admin_fee')) {
                $table->decimal('admin_fee', 12, 2)->default(0)->after('platform_fee');
            }
            if (!Schema::hasColumn('domain_orders', 'tax_amount')) {
                $table->decimal('tax_amount', 12, 2)->default(0)->after('admin_fee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('domain_orders', function (Blueprint $table) {
            $table->dropColumn(['base_amount', 'platform_fee', 'admin_fee', 'tax_amount']);
        });
    }
};
