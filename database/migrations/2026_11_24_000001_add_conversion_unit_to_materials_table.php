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
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                if (!Schema::hasColumn('materials', 'purchase_unit')) {
                    $table->string('purchase_unit')->nullable()->after('unit');
                }
                if (!Schema::hasColumn('materials', 'conversion_qty')) {
                    $table->decimal('conversion_qty', 12, 2)->default(1)->after('purchase_unit');
                }
                if (!Schema::hasColumn('materials', 'purchase_cost')) {
                    $table->decimal('purchase_cost', 14, 2)->nullable()->after('conversion_qty');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('materials')) {
            Schema::table('materials', function (Blueprint $table) {
                if (Schema::hasColumn('materials', 'purchase_cost')) {
                    $table->dropColumn('purchase_cost');
                }
                if (Schema::hasColumn('materials', 'conversion_qty')) {
                    $table->dropColumn('conversion_qty');
                }
                if (Schema::hasColumn('materials', 'purchase_unit')) {
                    $table->dropColumn('purchase_unit');
                }
            });
        }
    }
};
