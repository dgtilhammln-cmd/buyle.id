<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel coupons menyimpan kode diskon.
     * Mendukung dua tipe: persentase (%) dan nominal tetap (Rp).
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            // Kode kupon yang diinput user (misal: WELCOME10, DISKON50K)
            $table->string('code', 50)->unique();

            // Tipe diskon: 'percentage' atau 'fixed'
            $table->enum('type', ['percentage', 'fixed'])->default('fixed');

            // Nilai diskon: persen (10 = 10%) atau nominal (50000 = Rp 50.000)
            $table->decimal('value', 15, 2);

            // Minimal total belanja untuk menggunakan kupon
            $table->decimal('min_purchase', 15, 2)->default(0);

            // Maksimal potongan harga (berguna untuk tipe percentage)
            $table->decimal('max_discount', 15, 2)->nullable();

            // Batas jumlah pemakaian total (null = tidak terbatas)
            $table->unsignedInteger('usage_limit')->nullable();

            // Jumlah pemakaian saat ini
            $table->unsignedInteger('used_count')->default(0);

            $table->boolean('is_active')->default(true);

            // Periode berlakunya kupon
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();

            $table->index(['code', 'is_active']);
            $table->index('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
