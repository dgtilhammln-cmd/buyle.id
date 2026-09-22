<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
            }
            if (!Schema::hasColumn('leads', 'seller_id')) {
                $table->unsignedBigInteger('seller_id')->nullable()->after('user_id')->index();
            }
            if (!Schema::hasColumn('leads', 'city')) {
                $table->string('city')->nullable()->after('company');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (Schema::hasColumn('leads', 'user_id')) {
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('leads', 'seller_id')) {
                $table->dropColumn('seller_id');
            }
            if (Schema::hasColumn('leads', 'city')) {
                $table->dropColumn('city');
            }
        });
    }
};
