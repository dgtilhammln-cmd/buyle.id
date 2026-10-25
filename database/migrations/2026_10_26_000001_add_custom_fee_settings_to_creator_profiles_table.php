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
        Schema::table('creator_profiles', function (Blueprint $table) {
            $table->boolean('has_custom_fee')->default(false)->after('pos_balance');

            // Custom Fee POS
            $table->string('custom_pos_platform_fee_type', 15)->nullable()->comment('percent, fixed, default');
            $table->decimal('custom_pos_platform_fee_value', 12, 2)->nullable();
            $table->string('custom_pos_admin_fee_type', 15)->nullable()->comment('percent, fixed, default');
            $table->decimal('custom_pos_admin_fee_value', 12, 2)->nullable();

            // Custom Fee Ticketing
            $table->string('custom_ticket_platform_fee_type', 15)->nullable()->comment('percent, fixed, default');
            $table->decimal('custom_ticket_platform_fee_value', 12, 2)->nullable();
            $table->string('custom_ticket_admin_fee_type', 20)->nullable()->comment('fixed_per_qty, percent, default');
            $table->decimal('custom_ticket_admin_fee_value', 12, 2)->nullable();

            // Notes
            $table->text('custom_fee_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'has_custom_fee',
                'custom_pos_platform_fee_type',
                'custom_pos_platform_fee_value',
                'custom_pos_admin_fee_type',
                'custom_pos_admin_fee_value',
                'custom_ticket_platform_fee_type',
                'custom_ticket_platform_fee_value',
                'custom_ticket_admin_fee_type',
                'custom_ticket_admin_fee_value',
                'custom_fee_notes',
            ]);
        });
    }
};
