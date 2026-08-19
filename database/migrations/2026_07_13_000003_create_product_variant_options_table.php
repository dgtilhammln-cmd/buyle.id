<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel product_variant_options menyimpan nama opsi varian,
     * misal: "Ukuran", "Warna", "Material".
     */
    public function up(): void
    {
        Schema::create('product_variant_options', function (Blueprint $table) {
            $table->id();

            // FK ke tabel services (digunakan sebagai products)
            $table->foreignId('product_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // Nama opsi, misal: "Ukuran", "Warna"
            $table->string('name');

            $table->timestamps();

            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_options');
    }
};
