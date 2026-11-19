<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix product images stored with 'services/' prefix (old seeder data).
     * These files do not exist on the server, so we set them to NULL
     * so the product card falls back to a placeholder image.
     */
    public function up(): void
    {
        DB::table('products')
            ->where('image', 'like', 'services/%')
            ->update(['image' => null]);
    }

    public function down(): void
    {
        // No rollback — the original paths pointed to non-existent files anyway
    }
};
