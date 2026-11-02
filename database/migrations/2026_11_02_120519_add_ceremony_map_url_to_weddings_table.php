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
        Schema::table('weddings', function (Blueprint $table) {
            if (!Schema::hasColumn('weddings', 'ceremony_map_url')) {
                $table->text('ceremony_map_url')->nullable()->after('ceremony_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weddings', function (Blueprint $table) {
            if (Schema::hasColumn('weddings', 'ceremony_map_url')) {
                $table->dropColumn('ceremony_map_url');
            }
        });
    }
};
