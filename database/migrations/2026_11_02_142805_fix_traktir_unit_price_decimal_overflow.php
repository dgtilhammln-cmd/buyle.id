<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fix traktir_unit_price yang corrupt akibat bug preg_replace('/[^\d]/', '', "10000.00")
 * yang menghasilkan "1000000" (kali 100) saat kolom decimal:2 di-serialize ke old().
 *
 * Logika fix: nilai > 1.000.000 (1 juta) sangat tidak wajar untuk harga per satuan traktir.
 * Kita bagi dengan 100 untuk produk traktir yang harganya di atas threshold ini,
 * namun HANYA jika hasilnya masuk akal (di atas 1000).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Ambil semua produk traktir dengan harga > 1 juta (kemungkinan corrupt)
        $corruptProducts = DB::table('products')
            ->where('pricing_type', 'traktir')
            ->where('traktir_unit_price', '>', 1_000_000)
            ->get(['id', 'traktir_unit_price', 'price']);

        foreach ($corruptProducts as $product) {
            $originalPrice = (float) $product->traktir_unit_price;
            $fixedPrice    = (int) round($originalPrice / 100);

            // Hanya fix jika hasilnya reasonable (antara 1.000 - 10.000.000)
            if ($fixedPrice >= 1000 && $fixedPrice <= 10_000_000) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update([
                        'traktir_unit_price' => $fixedPrice,
                        'price'              => $fixedPrice, // sync price field juga
                    ]);
            }
        }
    }

    public function down(): void
    {
        // Tidak bisa di-rollback dengan aman karena kita tidak tahu nilai original mana yang memang tinggi
    }
};
