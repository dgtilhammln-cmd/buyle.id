<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix products in DB where title contains digital keywords but product_type is makanan or physical
        $digitalKeywords = [
            'planner', 'spreadsheet', 'ebook', 'template', 'course', 'academy',
            'masterclass', 'guide', 'workbook', 'pdf', 'excel', 'canva',
            'consultation', 'consult', 'rate card', 'notion', 'digital', 'access', 'link'
        ];

        foreach ($digitalKeywords as $kw) {
            DB::table('products')
                ->whereIn('product_type', ['makanan', 'physical', 'food', 'fnb'])
                ->where(DB::raw('LOWER(name)'), 'LIKE', '%' . $kw . '%')
                ->update(['product_type' => 'external_link']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
