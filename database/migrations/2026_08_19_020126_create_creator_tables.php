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
        Schema::create('creator_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Store Identity
            $table->string('store_name')->nullable();
            $table->string('store_slug')->unique()->nullable();
            $table->text('store_description')->nullable();
            
            // Address for Profile Completeness
            $table->string('address')->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('subdistrict_id')->nullable();
            
            // SEO Meta Data
            $table->string('meta_title')->nullable();
            $table->string('meta_desc')->nullable();
            $table->text('meta_keywords')->nullable();
            
            $table->timestamps();
        });

        Schema::create('creator_product_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Seorang seller tidak boleh punya nama grup ganda
            $table->unique(['seller_id', 'slug']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('file_type')->nullable()->after('product_type');
            $table->foreignId('creator_group_id')->nullable()->after('product_category_id')->constrained('creator_product_groups')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['creator_group_id']);
            $table->dropColumn(['file_type', 'creator_group_id']);
        });

        Schema::dropIfExists('creator_product_groups');
        Schema::dropIfExists('creator_profiles');
    }
};
