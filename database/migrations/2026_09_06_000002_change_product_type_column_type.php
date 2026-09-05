<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ubah product_type dari ENUM ke VARCHAR(100) agar mendukung tipe 'ticket', 'external_link', 'file_upload', dll.
     */
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE `products` MODIFY COLUMN `product_type` VARCHAR(100) NULL DEFAULT 'external_link'");
        } catch (\Throwable $e) {
            // Fallback for non-MySQL or drivers that don't support raw ALTER
            Schema::table('products', function (Blueprint $table) {
                $table->string('product_type', 100)->nullable()->default('external_link')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `products` MODIFY COLUMN `product_type` VARCHAR(100) NULL DEFAULT 'external_link'");
        } catch (\Throwable $e) {
            //
        }
    }
};
