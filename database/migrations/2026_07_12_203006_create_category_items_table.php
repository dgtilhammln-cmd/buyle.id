<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_items', function (Blueprint $table) {
            $table->id();
            $table->string('icon_type')->default('emoji'); // 'emoji' | 'upload'
            $table->string('icon_value')->nullable(); // emoji char or storage path
            $table->string('name');
            $table->string('url')->nullable(); // link to filter/page
            $table->string('badge')->nullable(); // e.g. "Baru", "Hot"
            $table->string('badge_color')->nullable(); // hex color for badge bg
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_items');
    }
};
