<?php

namespace App\Services;

class SpintaxService
{
    /**
     * Parse template Spintax dan ganti variabel dinamis
     *
     * @param string $template Contoh: "{Halo|Hai|Hello} kak {first_name}, ini link produknya: {link_product}"
     * @param array $variables ['first_name' => 'Budi', 'link_product' => '...', 'post_title' => '...']
     * @return string
     */
    public static function parse(string $template, array $variables = []): string
    {
        // 1. Ganti variabel dinamis
        $parsed = $template;
        foreach ($variables as $key => $val) {
            $parsed = str_ireplace('{' . $key . '}', (string) $val, $parsed);
        }

        // 2. Evaluasi Spintax regex: {opsi1|opsi2|opsi3}
        $parsed = preg_replace_callback('/\{([^{}]+)\}/', function ($matches) {
            $options = explode('|', $matches[1]);
            return $options[array_rand($options)];
        }, $parsed);

        return trim($parsed);
    }

    /**
     * Pilih satu varian pesan acak dari daftar array varian spintax
     */
    public static function pickAndParse(array $variants, array $variables = []): string
    {
        if (empty($variants)) {
            return "Halo kak {first_name}! Link produk buyle.id: {link_product}";
        }

        // Filter varian yang tidak kosong
        $cleanVariants = array_values(array_filter($variants, fn($v) => !empty(trim((string)$v))));

        if (empty($cleanVariants)) {
            $cleanVariants = ["Halo kak {first_name}! Link produk buyle.id: {link_product}"];
        }

        $chosen = $cleanVariants[array_rand($cleanVariants)];

        return self::parse($chosen, $variables);
    }
}
