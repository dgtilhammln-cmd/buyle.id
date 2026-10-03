<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            $table->boolean('is_exclusive')->default(false)->after('store_slug')
                  ->comment('Jika true, creator dapat mengatur rating & sold_count produk secara manual');
        });
    }

    public function down(): void
    {
        Schema::table('creator_profiles', function (Blueprint $table) {
            $table->dropColumn('is_exclusive');
        });
    }
};
