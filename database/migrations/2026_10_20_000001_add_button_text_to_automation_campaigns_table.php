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
        Schema::table('automation_campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('automation_campaigns', 'button_text')) {
                $table->string('button_text', 50)->nullable()->default('Buka Akses 🚀')->after('custom_link');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('automation_campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('automation_campaigns', 'button_text')) {
                $table->dropColumn('button_text');
            }
        });
    }
};
