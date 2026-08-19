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
        Schema::table('services', function (Blueprint $table) {
            // Kolom type membedakan produk fisik dan jasa
            $table->enum('type', ['product', 'service'])->default('product')->after('name');

            $table->unsignedInteger('sold_count')->default(0)->after('stock');
            $table->unsignedInteger('views_count')->default(0)->after('sold_count');
            
            $table->string('unit', 50)->nullable()->after('weight'); // misal: pcs, set, jam, paket
            $table->unsignedInteger('min_order')->default(1)->after('unit');
            $table->unsignedInteger('max_order')->nullable()->after('min_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'sold_count',
                'views_count',
                'unit',
                'min_order',
                'max_order'
            ]);
        });
    }
};
