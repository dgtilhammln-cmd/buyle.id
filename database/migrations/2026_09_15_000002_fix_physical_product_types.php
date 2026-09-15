<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix creator_bio_blocks: change type to 'custom_product' for physical items
        $blocks = DB::table('creator_bio_blocks')->get();
        foreach ($blocks as $block) {
            $data = json_decode($block->data_json, true) ?: [];
            $cat = strtolower(trim($data['category'] ?? ''));
            $title = strtolower($block->title ?? '');
            
            $hasWeightOrSku = !empty($data['weight']) || !empty($data['sku']) || !empty($data['length']);
            $isPhysical = in_array($cat, ['makanan', 'barang', 'jasa', 'lainnya', 'kuliner', 'fisik', 'umkm']) ||
                          $hasWeightOrSku ||
                          preg_match('/(es|nasi|teh|kopi|jus|sirup|air|soto|bakso|mie|ayam|bebek|daging|ikan|kerupuk|lumpia|kasur|samsung|promo|sepatu|baju|celana|stop.?kontak|kabel|colokan)/i', $title);
            
            if ($isPhysical && $block->type === 'buyle_product') {
                DB::table('creator_bio_blocks')->where('id', $block->id)->update([
                    'type' => 'custom_product'
                ]);
            }
        }

        // 2. Fix products: set product_type to 'makanan' or 'physical' for physical items
        $products = DB::table('products')->get();
        foreach ($products as $prod) {
            $name = strtolower($prod->name ?? '');
            $hasPhysicalAttrs = (!empty($prod->weight) && $prod->weight > 0) || !empty($prod->sku) || !empty($prod->length);
            if ($hasPhysicalAttrs || preg_match('/(es|nasi|teh|kopi|jus|sirup|air|soto|bakso|mie|ayam|bebek|daging|ikan|kerupuk|lumpia|kasur|samsung|promo|sepatu|baju|celana|stop.?kontak|kabel|colokan)/i', $name)) {
                $newType = preg_match('/(es|nasi|teh|kopi|jus|sirup|air|soto|bakso|mie|ayam|bebek|daging|ikan|kerupuk|lumpia)/i', $name) ? 'makanan' : 'physical';
                DB::table('products')->where('id', $prod->id)->update([
                    'product_type' => $newType
                ]);
            }
        }
    }

    public function down(): void
    {
    }
};
