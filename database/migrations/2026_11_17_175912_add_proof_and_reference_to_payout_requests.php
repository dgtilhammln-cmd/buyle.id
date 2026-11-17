<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payout_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('payout_requests', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('admin_notes');
            }
            if (!Schema::hasColumn('payout_requests', 'proof_of_transfer')) {
                $table->string('proof_of_transfer')->nullable()->after('reference_number')
                    ->comment('Path bukti transfer (gambar, auto-compressed)');
            }
            if (!Schema::hasColumn('payout_requests', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('processed_at')
                    ->comment('Tanggal persetujuan otomatis saat admin klik ACC');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payout_requests', function (Blueprint $table) {
            $table->dropColumn(['reference_number', 'proof_of_transfer', 'approved_at']);
        });
    }
};
