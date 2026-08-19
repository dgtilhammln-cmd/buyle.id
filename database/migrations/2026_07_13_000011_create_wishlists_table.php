<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel wishlists menyimpan produk favorit milik user.
     * Unique constraint memastikan satu produk hanya bisa
     * masuk wishlist satu user sebanyak satu kali.
     */
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // FK ke tabel services (digunakan sebagai products)
            $table->foreignId('product_id')
                ->constrained('services')
                ->cascadeOnDelete();

            $table->timestamps();

            // Satu produk hanya bisa ada sekali per user di wishlist
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
