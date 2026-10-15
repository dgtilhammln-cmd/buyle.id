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
            if (!Schema::hasColumn('products', 'gate_scanner_pin')) {
                $table->string('gate_scanner_pin', 20)->nullable()->after('gate_scanner_enabled');
            }
            if (!Schema::hasColumn('products', 'gate_open_time')) {
                $table->string('gate_open_time', 10)->nullable()->after('gate_scanner_pin');
            }
            if (!Schema::hasColumn('products', 'gate_close_time')) {
                $table->string('gate_close_time', 10)->nullable()->after('gate_open_time');
            }
            if (!Schema::hasColumn('products', 'gate_bypass_time')) {
                $table->boolean('gate_bypass_time')->default(false)->after('gate_close_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'gate_bypass_time')) {
                $table->dropColumn('gate_bypass_time');
            }
            if (Schema::hasColumn('products', 'gate_close_time')) {
                $table->dropColumn('gate_close_time');
            }
            if (Schema::hasColumn('products', 'gate_open_time')) {
                $table->dropColumn('gate_open_time');
            }
        });
    }
};
