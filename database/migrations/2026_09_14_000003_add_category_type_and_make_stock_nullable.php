<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE products MODIFY stock INT NULL DEFAULT NULL");
        } catch (\Throwable $e) {
            // Ignore if unsupported DB engine
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE products MODIFY stock INT NOT NULL DEFAULT 0");
        } catch (\Throwable $e) {
            // Ignore
        }
    }
};
