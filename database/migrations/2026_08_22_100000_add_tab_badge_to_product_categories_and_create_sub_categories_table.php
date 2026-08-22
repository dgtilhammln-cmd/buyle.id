<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom tab & badge ke product_categories
        Schema::table('product_categories', function (Blueprint $table) {
            $table->string('tab')->default('produk')->after('slug'); // 'produk' | 'jasa'
            $table->string('badge')->nullable()->after('tab');       // 'terpopuler' | 'naik-daun' | null
        });

        // Buat tabel sub-kategori baru
        Schema::create('product_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('contoh_produk')->nullable();
            $table->unsignedSmallInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category_id', 'order']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sub_categories');

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['tab', 'badge']);
        });
    }
};
