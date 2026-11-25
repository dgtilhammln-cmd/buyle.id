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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'tax_name')) {
                $table->string('tax_name')->nullable()->after('custom_fields');
            }
            if (!Schema::hasColumn('products', 'tax_rate')) {
                $table->decimal('tax_rate', 5, 2)->default(0)->after('tax_name');
            }
            if (!Schema::hasColumn('products', 'tax_enabled')) {
                $table->boolean('tax_enabled')->default(false)->after('tax_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('products', 'tax_name')) $cols[] = 'tax_name';
            if (Schema::hasColumn('products', 'tax_rate')) $cols[] = 'tax_rate';
            if (Schema::hasColumn('products', 'tax_enabled')) $cols[] = 'tax_enabled';
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
