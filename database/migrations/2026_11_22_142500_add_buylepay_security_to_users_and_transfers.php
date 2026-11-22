<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Implementasi keamanan Buyle Pay sesuai buylepay.md:
     * - Kolom brute-force PIN: pin_attempts, pin_locked_until
     * - Kolom cool-down transfer setelah reset PIN: transfer_cooldown_until
     * - Idempotency key di internal_transfers: cegah double transfer
     */
    public function up(): void
    {
        // 1. Kolom keamanan PIN & transfer cooldown di tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pin_attempts')) {
                $table->tinyInteger('pin_attempts')->unsigned()->default(0)->after('pos_pin')
                    ->comment('Jumlah percobaan PIN salah berturut-turut');
            }
            if (!Schema::hasColumn('users', 'pin_locked_until')) {
                $table->timestamp('pin_locked_until')->nullable()->after('pin_attempts')
                    ->comment('Waktu sampai akun terkunci brute-force (null = tidak terkunci)');
            }
            if (!Schema::hasColumn('users', 'transfer_cooldown_until')) {
                $table->timestamp('transfer_cooldown_until')->nullable()->after('pin_locked_until')
                    ->comment('Waktu cooldown transfer setelah reset PIN (null = bebas transfer)');
            }
        });

        // 2. Kolom idempotency_key di tabel internal_transfers
        Schema::table('internal_transfers', function (Blueprint $table) {
            if (!Schema::hasColumn('internal_transfers', 'idempotency_key')) {
                $table->string('idempotency_key', 100)->nullable()->unique()->after('transfer_code')
                    ->comment('Kunci idempotensi dari frontend untuk mencegah double transfer');
            }
            if (!Schema::hasColumn('internal_transfers', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('status')
                    ->comment('IP address pengirim saat transaksi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = ['pin_attempts', 'pin_locked_until', 'transfer_cooldown_until'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('internal_transfers', function (Blueprint $table) {
            if (Schema::hasColumn('internal_transfers', 'idempotency_key')) {
                $table->dropColumn('idempotency_key');
            }
            if (Schema::hasColumn('internal_transfers', 'ip_address')) {
                $table->dropColumn('ip_address');
            }
        });
    }
};
