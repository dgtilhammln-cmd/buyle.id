<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_projects', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->string('meta_title')->nullable()->after('alt_text');
            $table->text('meta_desc')->nullable()->after('meta_title');
            $table->string('og_image')->nullable()->after('meta_desc');
            $table->string('tags')->nullable()->after('og_image'); // comma separated
            $table->boolean('is_featured')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_projects', function (Blueprint $table) {
            $table->dropColumn(['slug','meta_title','meta_desc','og_image','tags','is_featured']);
        });
    }
};
