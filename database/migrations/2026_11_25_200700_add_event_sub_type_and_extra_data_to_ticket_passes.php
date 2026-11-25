<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ticket_passes', function (Blueprint $table) {
            // Field tambahan per jenis event (lari: divisi, jersey; seminar: institusi; dll)
            $table->json('extra_data')->nullable()->after('holder_nik')
                  ->comment('JSON: data tambahan checkout per sub-type event');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_passes', function (Blueprint $table) {
            $table->dropColumn('extra_data');
        });
    }
};
