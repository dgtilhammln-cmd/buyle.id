<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Performance indexes untuk buyle.id — Prioritas 3: War Ticket Readiness.
 *
 * Dampak: Query daftar tiket (TIX page), lookup produk, dan order search
 * yang sebelumnya full-scan sekarang menggunakan index.
 * Sangat krusial saat ribuan orang membuka halaman event bersamaan.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── products table ────────────────────────────────────────────────────
        Schema::table('products', function (Blueprint $table) {
            // Index untuk query TixController: WHERE product_type='ticket' AND is_active=true
            if (!$this->indexExists('products', 'idx_products_type_active')) {
                $table->index(['product_type', 'is_active'], 'idx_products_type_active');
            }
            // Index untuk sorting/filter event_date
            if (!$this->indexExists('products', 'idx_products_event_date')) {
                $table->index(['event_date'], 'idx_products_event_date');
            }
            // Index untuk slug lookup (produk show page)
            if (!$this->indexExists('products', 'idx_products_slug')) {
                $table->index(['slug'], 'idx_products_slug');
            }
            // Index untuk seller_id (listing produk per seller)
            if (!$this->indexExists('products', 'idx_products_seller_active')) {
                $table->index(['seller_id', 'is_active'], 'idx_products_seller_active');
            }
        });

        // ── orders table ──────────────────────────────────────────────────────
        Schema::table('orders', function (Blueprint $table) {
            // Index untuk getLiveOrders: WHERE seller_id=X AND source IN (pos, storefront)
            if (!$this->indexExists('orders', 'idx_orders_seller_source')) {
                $table->index(['seller_id', 'source', 'id'], 'idx_orders_seller_source');
            }
            // Index untuk user-based lookup
            if (!$this->indexExists('orders', 'idx_orders_user_source')) {
                $table->index(['user_id', 'source'], 'idx_orders_user_source');
            }
            // Index untuk checkNewNotifications: WHERE created_at >= ?
            if (!$this->indexExists('orders', 'idx_orders_created_source')) {
                $table->index(['created_at', 'source'], 'idx_orders_created_source');
            }
        });

        // ── creator_profiles table ────────────────────────────────────────────
        Schema::table('creator_profiles', function (Blueprint $table) {
            if (!$this->indexExists('creator_profiles', 'idx_creator_profiles_user_id')) {
                $table->index(['user_id'], 'idx_creator_profiles_user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if ($this->indexExists('products', 'idx_products_type_active')) {
                $table->dropIndex('idx_products_type_active');
            }
            if ($this->indexExists('products', 'idx_products_event_date')) {
                $table->dropIndex('idx_products_event_date');
            }
            if ($this->indexExists('products', 'idx_products_slug')) {
                $table->dropIndex('idx_products_slug');
            }
            if ($this->indexExists('products', 'idx_products_seller_active')) {
                $table->dropIndex('idx_products_seller_active');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if ($this->indexExists('orders', 'idx_orders_seller_source')) {
                $table->dropIndex('idx_orders_seller_source');
            }
            if ($this->indexExists('orders', 'idx_orders_user_source')) {
                $table->dropIndex('idx_orders_user_source');
            }
            if ($this->indexExists('orders', 'idx_orders_created_source')) {
                $table->dropIndex('idx_orders_created_source');
            }
        });

        Schema::table('creator_profiles', function (Blueprint $table) {
            if ($this->indexExists('creator_profiles', 'idx_creator_profiles_user_id')) {
                $table->dropIndex('idx_creator_profiles_user_id');
            }
        });
    }

    /**
     * Cek apakah index sudah ada — aman untuk MySQL & SQLite (idempoten).
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            return !empty($indexes);
        }

        // SQLite: cek via sqlite_master
        if ($driver === 'sqlite') {
            $indexes = DB::select(
                "SELECT name FROM sqlite_master WHERE type='index' AND name=?",
                [$indexName]
            );
            return !empty($indexes);
        }

        // Fallback: cek via information_schema (PostgreSQL, dsb.)
        $count = DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->count();

        return $count > 0;
    }
};
