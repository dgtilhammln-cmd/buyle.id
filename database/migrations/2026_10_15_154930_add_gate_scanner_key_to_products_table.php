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
            if (!Schema::hasColumn('products', 'gate_scanner_key')) {
                $table->string('gate_scanner_key', 64)->nullable()->unique()->after('event_type');
            }
            if (!Schema::hasColumn('products', 'gate_scanner_enabled')) {
                $table->boolean('gate_scanner_enabled')->default(true)->after('gate_scanner_key');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'gate_scanner_enabled')) {
                $table->dropColumn('gate_scanner_enabled');
            }
            if (Schema::hasColumn('products', 'gate_scanner_key')) {
                $table->dropColumn('gate_scanner_key');
            }
        });
    }
};
