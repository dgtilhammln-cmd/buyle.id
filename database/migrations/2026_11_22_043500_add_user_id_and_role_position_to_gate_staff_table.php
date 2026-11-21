<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gate_staff', function (Blueprint $table) {
            if (!Schema::hasColumn('gate_staff', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('creator_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('gate_staff', 'role_position')) {
                $table->string('role_position', 50)->default('Gate Scanner')->after('gate_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gate_staff', function (Blueprint $table) {
            if (Schema::hasColumn('gate_staff', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('gate_staff', 'role_position')) {
                $table->dropColumn('role_position');
            }
        });
    }
};
