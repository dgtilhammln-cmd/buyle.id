<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'ticket_war_duration_hours')) {
                // Durasi periode war tiket dalam jam (default 3 jam).
                // Setelah war_start + durasi, antrean otomatis NONAKTIF & pembelian langsung bisa dilakukan.
                $table->unsignedTinyInteger('ticket_war_duration_hours')->default(3)->after('ticket_war_start');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'ticket_war_duration_hours')) {
                $table->dropColumn('ticket_war_duration_hours');
            }
        });
    }
};
