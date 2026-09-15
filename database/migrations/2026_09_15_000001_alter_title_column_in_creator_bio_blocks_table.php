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
        // Use Laravel Schema builder for cross-DB compatibility (SQLite + MySQL)
        Schema::table('creator_bio_blocks', function (Blueprint $table) {
            $table->string('title', 500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creator_bio_blocks', function (Blueprint $table) {
            $table->string('title', 150)->nullable()->change();
        });
    }
};
