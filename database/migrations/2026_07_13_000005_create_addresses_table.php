<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel addresses menyimpan buku alamat pengiriman milik user.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Label alamat: "Rumah", "Kantor", "Gudang", dll.
            $table->string('label', 50)->default('Rumah');

            // Informasi penerima
            $table->string('receiver_name');
            $table->string('phone', 20);

            // Informasi wilayah (menggunakan data Raja Ongkir / Rajaongkir)
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('postal_code', 10);

            // Alamat lengkap (nomor rumah, RT/RW, nama jalan, dll.)
            $table->text('full_address');

            // Hanya satu alamat per user yang boleh is_default = true
            $table->boolean('is_default')->default(false);

            $table->timestamps();

            $table->index(['user_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
