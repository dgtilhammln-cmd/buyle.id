<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik', 50)->nullable()->after('phone');
            }
        });

        // Backfill: jika ada user yang punya riwayat tiket dengan NIK, otomatis isi ke users.nik
        try {
            if (Schema::hasTable('ticket_passes')) {
                $tickets = DB::table('ticket_passes')
                    ->whereNotNull('user_id')
                    ->whereNotNull('holder_nik')
                    ->where('holder_nik', '!=', '')
                    ->orderBy('id', 'desc')
                    ->get();

                foreach ($tickets as $t) {
                    $cleanNik = $t->holder_nik;
                    if (str_contains($cleanNik, ':')) {
                        [, $cleanNik] = explode(':', $cleanNik, 2);
                        $cleanNik = trim($cleanNik);
                    }
                    if (!empty($cleanNik)) {
                        DB::table('users')
                            ->where('id', $t->user_id)
                            ->whereNull('nik')
                            ->update(['nik' => $cleanNik]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore if ticket_passes doesn't exist yet or fails
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'nik')) {
                $table->dropColumn('nik');
            }
        });
    }
};
