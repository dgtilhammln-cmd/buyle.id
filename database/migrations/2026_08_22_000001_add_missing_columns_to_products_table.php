<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom-kolom yang ada di SQLite lokal tapi belum ada di MySQL production.
 * Semua kolom dicek dengan hasColumn() agar migration ini idempoten (aman dijalankan ulang).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Kolom gallery (JSON array path gambar)
            if (!Schema::hasColumn('products', 'gallery')) {
                $table->json('gallery')->nullable()->after('image');
            }

            // Kolom untuk brochure
            if (!Schema::hasColumn('products', 'brochure')) {
                $table->string('brochure')->nullable()->after('gallery');
            }

            // Spesifikasi produk (JSON)
            if (!Schema::hasColumn('products', 'specifications')) {
                $table->json('specifications')->nullable()->after('brochure');
            }

            // FAQ produk (JSON)
            if (!Schema::hasColumn('products', 'faqs')) {
                $table->json('faqs')->nullable()->after('specifications');
            }

            // Meta keywords untuk SEO
            if (!Schema::hasColumn('products', 'meta_keywords')) {
                $table->text('meta_keywords')->nullable()->after('meta_desc');
            }

            // Rating produk
            if (!Schema::hasColumn('products', 'rating')) {
                $table->decimal('rating', 3, 1)->default(0)->after('max_order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = ['gallery', 'brochure', 'specifications', 'faqs', 'meta_keywords', 'rating'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
