<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Sub-type event: menentukan preset checkout fields tambahan
            $table->string('event_sub_type', 30)->nullable()->after('event_type')
                  ->comment('concert|run|seminar|sport|trip|webinar|festival|other');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('event_sub_type');
        });
    }
};
