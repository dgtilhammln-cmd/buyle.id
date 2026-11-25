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
            if (!Schema::hasColumn('products', 'ots_enabled')) {
                $table->boolean('ots_enabled')->default(false)->after('tax_enabled');
            }
            if (!Schema::hasColumn('products', 'ots_price')) {
                $table->decimal('ots_price', 12, 2)->unsigned()->nullable()->after('ots_enabled');
            }
            if (!Schema::hasColumn('products', 'ots_quota')) {
                $table->unsignedInteger('ots_quota')->nullable()->after('ots_price');
            }
        });

        Schema::table('ticket_passes', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket_passes', 'is_ots')) {
                $table->boolean('is_ots')->default(false)->after('status')->index();
            }
            if (!Schema::hasColumn('ticket_passes', 'cashier_user_id')) {
                $table->unsignedBigInteger('cashier_user_id')->nullable()->after('is_ots')->index();
            }
            if (!Schema::hasColumn('ticket_passes', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('cashier_user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'ots_quota')) {
                $table->dropColumn('ots_quota');
            }
            if (Schema::hasColumn('products', 'ots_price')) {
                $table->dropColumn('ots_price');
            }
            if (Schema::hasColumn('products', 'ots_enabled')) {
                $table->dropColumn('ots_enabled');
            }
        });

        Schema::table('ticket_passes', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_passes', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('ticket_passes', 'cashier_user_id')) {
                $table->dropColumn('cashier_user_id');
            }
            if (Schema::hasColumn('ticket_passes', 'is_ots')) {
                $table->dropColumn('is_ots');
            }
        });
    }
};
