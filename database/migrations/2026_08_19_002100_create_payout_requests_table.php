<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payout_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();

            // Jumlah yang dicairkan
            $table->decimal('amount', 15, 2);

            // Status: pending, approved, rejected, processed
            $table->enum('status', ['pending', 'approved', 'rejected', 'processed'])->default('pending');

            // Info bank/ewallet seller
            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->string('bank_account_name');

            // Catatan dari seller atau admin
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();

            // Siapa admin yang approve
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            $table->index(['seller_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
    }
};
