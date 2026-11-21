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
        if (!Schema::hasColumn('products', 'require_screenshot_proof')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('require_screenshot_proof')->default(false)->after('require_follow_join');
            });
        }

        if (!Schema::hasColumn('orders', 'claim_proof')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('claim_proof', 500)->nullable()->after('notes');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'require_screenshot_proof')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('require_screenshot_proof');
            });
        }

        if (Schema::hasColumn('orders', 'claim_proof')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('claim_proof');
            });
        }
    }
};
