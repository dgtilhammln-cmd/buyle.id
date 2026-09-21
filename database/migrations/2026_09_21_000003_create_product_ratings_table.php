<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('product_ratings')) {
            Schema::create('product_ratings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
                $table->tinyInteger('rating')->default(5);
                $table->timestamps();

                $table->unique(['product_id', 'user_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_ratings');
    }
};
