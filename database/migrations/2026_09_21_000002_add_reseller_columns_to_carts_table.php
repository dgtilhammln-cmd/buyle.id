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
        Schema::table('carts', function (Blueprint $table) {
            if (!Schema::hasColumn('carts', 'reseller_id')) {
                $table->foreignId('reseller_id')->nullable()->after('variant_value_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('carts', 'custom_price')) {
                $table->decimal('custom_price', 15, 2)->nullable()->after('reseller_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['reseller_id']);
            $table->dropColumn(['reseller_id', 'custom_price']);
        });
    }
};
