<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel carts mendukung dua mode:
     * 1. Guest cart → user_id NULL, diidentifikasi oleh session_id
     * 2. Authenticated cart → user_id diisi
     */
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // Bisa null untuk guest cart
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Identifier untuk guest cart (dari session Laravel)
            $table->string('session_id')->nullable();

            // FK ke tabel services (digunakan sebagai products)
            $table->foreignId('product_id')
                ->constrained('services')
                ->cascadeOnDelete();

            // FK ke varian yang dipilih (opsional)
            $table->foreignId('variant_value_id')
                ->nullable()
                ->constrained('product_variant_values')
                ->nullOnDelete();

            $table->unsignedSmallInteger('qty')->default(1);

            $table->timestamps();

            // Satu produk (+ varian) per cart entry per user/session
            $table->index(['user_id', 'product_id']);
            $table->index(['session_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
