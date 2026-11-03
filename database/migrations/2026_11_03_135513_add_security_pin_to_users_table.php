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
            if (!Schema::hasColumn('users', 'security_pin')) {
                $table->string('security_pin')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'biometric_enabled')) {
                $table->boolean('biometric_enabled')->default(false)->after('security_pin');
            }
            if (!Schema::hasColumn('users', 'biometric_credential_id')) {
                $table->text('biometric_credential_id')->nullable()->after('biometric_enabled');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['security_pin', 'biometric_enabled', 'biometric_credential_id']);
        });
    }
};
