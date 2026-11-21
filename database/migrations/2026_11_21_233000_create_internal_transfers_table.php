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
        if (!Schema::hasTable('internal_transfers')) {
            Schema::create('internal_transfers', function (Blueprint $table) {
                $table->id();
                $table->string('transfer_code', 50)->unique();
                $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
                $table->decimal('amount', 15, 2);
                $table->string('notes', 255)->nullable();
                $table->enum('status', ['success', 'failed', 'reversed'])->default('success');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_transfers');
    }
};
