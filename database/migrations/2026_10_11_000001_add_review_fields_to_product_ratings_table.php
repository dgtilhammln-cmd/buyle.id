<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_ratings', function (Blueprint $table) {
            if (!Schema::hasColumn('product_ratings', 'review_text')) {
                $table->text('review_text')->nullable()->after('rating');
            }
            if (!Schema::hasColumn('product_ratings', 'review_images')) {
                $table->json('review_images')->nullable()->after('review_text');
            }
            if (!Schema::hasColumn('product_ratings', 'is_approved')) {
                $table->boolean('is_approved')->default(true)->after('review_images');
            }
            if (!Schema::hasColumn('product_ratings', 'reviewer_name')) {
                $table->string('reviewer_name')->nullable()->after('is_approved');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_ratings', function (Blueprint $table) {
            $table->dropColumn(['review_text', 'review_images', 'is_approved', 'reviewer_name']);
        });
    }
};
