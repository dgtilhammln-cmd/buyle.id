<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'slug') && !$this->hasIndex('services', 'services_slug_index'))
                $table->index('slug');
            if (Schema::hasColumn('services', 'is_active') && !$this->hasIndex('services', 'services_is_active_order_index'))
                $table->index(['is_active', 'order']);
        });

        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'slug') && !$this->hasIndex('articles', 'articles_slug_index'))
                $table->index('slug');
            if (Schema::hasColumn('articles', 'is_published') && !$this->hasIndex('articles', 'articles_is_published_created_at_index'))
                $table->index(['is_published', 'created_at']);
        });

        Schema::table('gallery_projects', function (Blueprint $table) {
            if (Schema::hasColumn('gallery_projects', 'slug') && !$this->hasIndex('gallery_projects', 'gallery_projects_slug_index'))
                $table->index('slug');
            if (Schema::hasColumn('gallery_projects', 'is_active') && !$this->hasIndex('gallery_projects', 'gallery_projects_is_active_order_index'))
                $table->index(['is_active', 'order']);
        });

        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'is_active') && !$this->hasIndex('clients', 'clients_is_active_order_index'))
                $table->index(['is_active', 'order']);
        });

        Schema::table('testimonials', function (Blueprint $table) {
            if (Schema::hasColumn('testimonials', 'is_active') && !$this->hasIndex('testimonials', 'testimonials_is_active_order_index'))
                $table->index(['is_active', 'order']);
        });

        Schema::table('hero_slides', function (Blueprint $table) {
            if (Schema::hasColumn('hero_slides', 'is_active') && !$this->hasIndex('hero_slides', 'hero_slides_is_active_order_index'))
                $table->index(['is_active', 'order']);
        });

        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'key') && !$this->hasIndex('settings', 'settings_key_index'))
                $table->index('key');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropIndexIfExists('services_slug_index');
            $table->dropIndexIfExists('services_is_active_order_index');
        });
        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndexIfExists('articles_slug_index');
            $table->dropIndexIfExists('articles_is_published_created_at_index');
        });
        Schema::table('gallery_projects', function (Blueprint $table) {
            $table->dropIndexIfExists('gallery_projects_slug_index');
            $table->dropIndexIfExists('gallery_projects_is_active_order_index');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndexIfExists('clients_is_active_order_index');
        });
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndexIfExists('testimonials_is_active_order_index');
        });
        Schema::table('hero_slides', function (Blueprint $table) {
            $table->dropIndexIfExists('hero_slides_is_active_order_index');
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndexIfExists('settings_key_index');
        });
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        try {
            $result = \Illuminate\Support\Facades\DB::select(
                "SELECT INDEX_NAME FROM information_schema.STATISTICS 
                 WHERE TABLE_SCHEMA = DATABASE() 
                 AND TABLE_NAME = ? AND INDEX_NAME = ?",
                [$table, $indexName]
            );
            return count($result) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
};
