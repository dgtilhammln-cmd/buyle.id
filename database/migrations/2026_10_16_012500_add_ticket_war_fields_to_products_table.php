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
            if (!Schema::hasColumn('products', 'ticket_war_start')) {
                $table->dateTime('ticket_war_start')->nullable()->after('event_type');
            }
            if (!Schema::hasColumn('products', 'max_tickets_per_order')) {
                $table->integer('max_tickets_per_order')->nullable()->default(4)->after('ticket_war_start');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'ticket_war_start')) {
                $table->dropColumn('ticket_war_start');
            }
            if (Schema::hasColumn('products', 'max_tickets_per_order')) {
                $table->dropColumn('max_tickets_per_order');
            }
        });
    }
};
