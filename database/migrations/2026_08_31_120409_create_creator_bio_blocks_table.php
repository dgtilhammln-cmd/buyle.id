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
        Schema::create('creator_bio_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('creator_profiles')->onDelete('cascade');
            $table->string('type', 50); // link, pdf, tiktok, affiliate, buyle_product
            $table->string('title', 150);
            $table->text('url')->nullable();
            $table->json('data_json')->nullable(); // extra config: image, thumbnail, product_id, etc.
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creator_bio_blocks');
    }
};
