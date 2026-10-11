<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_ratings', function (Blueprint $table) {
            if (!Schema::hasColumn('product_ratings', 'seller_reply')) {
                $table->text('seller_reply')->nullable()->after('reviewer_name');
            }
            if (!Schema::hasColumn('product_ratings', 'seller_replied_at')) {
                $table->timestamp('seller_replied_at')->nullable()->after('seller_reply');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_ratings', function (Blueprint $table) {
            if (Schema::hasColumn('product_ratings', 'seller_reply')) {
                $table->dropColumn('seller_reply');
            }
            if (Schema::hasColumn('product_ratings', 'seller_replied_at')) {
                $table->dropColumn('seller_replied_at');
            }
        });
    }
};
