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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'free_payout_slots')) {
                $table->unsignedInteger('free_payout_slots')->default(3)->after('is_active');
            }
            if (!Schema::hasColumn('users', 'login_streak_count')) {
                $table->unsignedInteger('login_streak_count')->default(1)->after('free_payout_slots');
            }
            if (!Schema::hasColumn('users', 'last_login_streak_at')) {
                $table->date('last_login_streak_at')->nullable()->after('login_streak_count');
            }
            if (!Schema::hasColumn('users', 'streak_claimed_at')) {
                $table->timestamp('streak_claimed_at')->nullable()->after('last_login_streak_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('users', 'free_payout_slots')) $cols[] = 'free_payout_slots';
            if (Schema::hasColumn('users', 'login_streak_count')) $cols[] = 'login_streak_count';
            if (Schema::hasColumn('users', 'last_login_streak_at')) $cols[] = 'last_login_streak_at';
            if (Schema::hasColumn('users', 'streak_claimed_at')) $cols[] = 'streak_claimed_at';
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
