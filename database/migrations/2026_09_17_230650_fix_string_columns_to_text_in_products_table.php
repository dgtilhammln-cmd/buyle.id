<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Fix: Ubah kolom meta_keywords dari VARCHAR(255) menjadi TEXT di tabel products.
 * 
 * Penyebab error "SQLSTATE[22001]: String data, right truncated: 1406 Data too long for column":
 * - Kolom meta_keywords dibuat sebagai string(255) di migration lama (2026_05)
 * - Konten SEO produk digital/ebook bisa ribuan karakter
 * - Migration 2026_08_22 tidak bisa update karena hasColumn() mendeteksi sudah ada
 *
 * Fix: Ubah ke TEXT (65,535 chars) — cukup untuk konten SEO panjang manapun.
 * Juga fix kolom tags jika ada.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Cek dan ubah di tabel 'products' (alias tabel services)
        $tableToCheck = Schema::hasTable('products') ? 'products' : 'services';

        Schema::table($tableToCheck, function (Blueprint $table) use ($tableToCheck) {
            // Fix meta_keywords: string(255) → text
            if (Schema::hasColumn($tableToCheck, 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->change();
            }

            // Fix tags: jika string(255), ubah ke text
            if (Schema::hasColumn($tableToCheck, 'tags')) {
                $table->text('tags')->nullable()->change();
            }

            // Fix meta_title jika terlalu pendek (default 255 mungkin kurang)
            if (Schema::hasColumn($tableToCheck, 'meta_title')) {
                $table->string('meta_title', 500)->nullable()->change();
            }

            // Fix brochure path - bisa panjang
            if (Schema::hasColumn($tableToCheck, 'brochure')) {
                $table->string('brochure', 1000)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        $tableToCheck = Schema::hasTable('products') ? 'products' : 'services';

        Schema::table($tableToCheck, function (Blueprint $table) use ($tableToCheck) {
            if (Schema::hasColumn($tableToCheck, 'meta_keywords')) {
                $table->string('meta_keywords', 255)->nullable()->change();
            }
            if (Schema::hasColumn($tableToCheck, 'tags')) {
                $table->string('tags', 255)->nullable()->change();
            }
            if (Schema::hasColumn($tableToCheck, 'meta_title')) {
                $table->string('meta_title', 255)->nullable()->change();
            }
            if (Schema::hasColumn($tableToCheck, 'brochure')) {
                $table->string('brochure', 255)->nullable()->change();
            }
        });
    }
};
