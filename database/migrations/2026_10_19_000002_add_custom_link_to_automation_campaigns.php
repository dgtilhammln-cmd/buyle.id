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
        if (Schema::hasTable('automation_campaigns') && !Schema::hasColumn('automation_campaigns', 'custom_link')) {
            Schema::table('automation_campaigns', function (Blueprint $table) {
                $table->text('custom_link')->nullable()->after('product_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('automation_campaigns') && Schema::hasColumn('automation_campaigns', 'custom_link')) {
            Schema::table('automation_campaigns', function (Blueprint $table) {
                $table->dropColumn('custom_link');
            });
        }
    }
};
