<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel gate_staff
        if (!Schema::hasTable('gate_staff')) {
            Schema::create('gate_staff', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('creator_id')->constrained('users')->cascadeOnDelete();
                $table->string('name', 100);
                $table->tinyInteger('gate_number')->unsigned()->default(1);
                $table->string('pin', 255); // bcrypt hashed 6-digit pin
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['product_id', 'is_active']);
            });
        }

        // 2. Tambah kolom atribusi scan ke ticket_passes
        Schema::table('ticket_passes', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket_passes', 'scanned_by_staff_name')) {
                $table->string('scanned_by_staff_name', 100)->nullable()->after('checked_in_by');
            }
            if (!Schema::hasColumn('ticket_passes', 'scanned_at_gate')) {
                $table->tinyInteger('scanned_at_gate')->unsigned()->nullable()->after('scanned_by_staff_name');
            }
            // Upgrade holder_nik ke TEXT agar bisa menyimpan encrypted string panjang
            if (Schema::hasColumn('ticket_passes', 'holder_nik')) {
                $table->text('holder_nik')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gate_staff');

        Schema::table('ticket_passes', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_passes', 'scanned_by_staff_name')) {
                $table->dropColumn('scanned_by_staff_name');
            }
            if (Schema::hasColumn('ticket_passes', 'scanned_at_gate')) {
                $table->dropColumn('scanned_at_gate');
            }
        });
    }
};
