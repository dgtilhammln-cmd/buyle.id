<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usp_items', function (Blueprint $table) {
            $table->id();
            $table->string('icon_type')->default('emoji'); // 'emoji' | 'upload'
            $table->string('icon_value')->nullable(); // emoji char or storage path
            $table->string('label');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usp_items');
    }
};
