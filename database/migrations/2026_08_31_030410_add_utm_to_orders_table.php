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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'utm_source')) {
                $table->string('utm_source')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('orders', 'utm_medium')) {
                $table->string('utm_medium')->nullable()->after('utm_source');
            }
            if (!Schema::hasColumn('orders', 'utm_campaign')) {
                $table->string('utm_campaign')->nullable()->after('utm_medium');
            }
            if (!Schema::hasColumn('orders', 'utm_term')) {
                $table->string('utm_term')->nullable()->after('utm_campaign');
            }
            if (!Schema::hasColumn('orders', 'utm_content')) {
                $table->string('utm_content')->nullable()->after('utm_term');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content']);
        });
    }
};
