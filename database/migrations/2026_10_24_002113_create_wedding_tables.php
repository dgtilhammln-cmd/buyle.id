<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Table: weddings (Data Acara & Pasangan Mempelai)
        Schema::create('weddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('slug')->unique();
            
            // Mempelai Pria
            $table->string('groom_name')->nullable();
            $table->string('groom_nickname')->nullable();
            $table->string('groom_parents')->nullable();
            $table->string('groom_photo')->nullable();
            $table->string('groom_instagram')->nullable();

            // Mempelai Wanita
            $table->string('bride_name')->nullable();
            $table->string('bride_nickname')->nullable();
            $table->string('bride_parents')->nullable();
            $table->string('bride_photo')->nullable();
            $table->string('bride_instagram')->nullable();

            // Hero & Acara
            $table->string('cover_photo')->nullable();
            $table->string('event_title')->default('The Wedding of');
            $table->dateTime('event_date')->nullable();
            $table->string('event_location_name')->nullable();
            $table->text('event_address')->nullable();
            $table->text('event_map_url')->nullable();
            $table->decimal('event_latitude', 10, 7)->nullable();
            $table->decimal('event_longitude', 10, 7)->nullable();

            // Resepsi & Akad Detail
            $table->dateTime('ceremony_date')->nullable(); // Waktu akad/pemberkatan
            $table->string('ceremony_location_name')->nullable();
            $table->text('ceremony_address')->nullable();

            // Keamanan Buku Tamu (Reception PIN)
            $table->string('reception_pin', 10)->default('1234');

            // Amplop Digital
            $table->boolean('digital_envelope_enabled')->default(true);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('qris_image')->nullable();

            // Cerita & Multimedia
            $table->string('music_url')->nullable();
            $table->string('theme')->default('classic_gold');
            $table->json('data_json')->nullable(); // Galeri foto, rundown, live stream
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // 2. Table: wedding_guests (Buku Tamu Digital & QR Check-in)
        Schema::create('wedding_guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained('weddings')->onDelete('cascade');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('group_name')->nullable(); // 'Keluarga', 'Teman Kerja', 'VIP', dll.
            $table->string('qr_code', 32)->unique();
            $table->integer('pax')->default(1);
            $table->boolean('is_checked_in')->default(false);
            $table->timestamp('checked_in_at')->nullable();
            $table->string('checked_in_by')->nullable(); // 'QR Camera', 'Manual Search'
            $table->integer('actual_pax')->default(1);
            $table->text('notes')->nullable(); // e.g. "Hadir bersama istri & anak"
            $table->timestamps();

            $table->index(['wedding_id', 'name']);
            $table->index(['wedding_id', 'is_checked_in']);
        });

        // 3. Table: wedding_wishes (Ucapan Doa & InstaStory Wish Card)
        Schema::create('wedding_wishes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained('weddings')->onDelete('cascade');
            $table->foreignId('guest_id')->nullable()->constrained('wedding_guests')->nullOnDelete();
            $table->string('sender_name');
            $table->string('sender_city')->nullable();
            $table->text('message');
            $table->string('attendance_status')->default('hadir'); // 'hadir', 'ragu', 'tidak_hadir'
            $table->string('sender_photo')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->index(['wedding_id', 'is_approved']);
        });

        // 4. Table: wedding_gifts (Amplop Digital & Transaksi)
        Schema::create('wedding_gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_id')->constrained('weddings')->onDelete('cascade');
            $table->foreignId('guest_id')->nullable()->constrained('wedding_guests')->nullOnDelete();
            $table->string('sender_name');
            $table->decimal('amount', 12, 2);
            $table->text('message')->nullable();
            $table->string('payment_method')->nullable(); // 'qris', 'bank_transfer', 'manual'
            $table->string('payment_status')->default('pending'); // 'pending', 'settlement', 'expire', 'failed'
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('snap_token')->nullable();
            $table->timestamps();

            $table->index(['wedding_id', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_gifts');
        Schema::dropIfExists('wedding_wishes');
        Schema::dropIfExists('wedding_guests');
        Schema::dropIfExists('weddings');
    }
};
