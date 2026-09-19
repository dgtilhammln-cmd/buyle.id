<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('domain_orders')) {
            Schema::create('domain_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('domain_name', 150);
                $table->string('extension', 30);
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('status', 30)->default('pending'); // pending, paid, cancelled
                $table->string('snap_token', 255)->nullable();
                $table->string('midtrans_transaction_id', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('domain_orders');
    }
};
