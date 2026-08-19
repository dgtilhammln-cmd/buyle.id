<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('product')->nullable();  // Produk yang diminati
            $table->text('message')->nullable();
            $table->string('source')->default('website'); // website, wa_button, etc
            $table->string('page_url')->nullable();       // Dari halaman mana
            $table->string('ip_address')->nullable();
            $table->string('device_type')->nullable();
            $table->string('status')->default('new');     // new, contacted, closed
            $table->text('notes')->nullable();            // Admin notes
            $table->string('wa_number')->nullable();      // WA number yang diarahkan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
