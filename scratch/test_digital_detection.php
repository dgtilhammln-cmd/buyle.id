<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\CartService;
use App\Models\Product;

$cartService = new CartService();

// Create temporary dummy product if needed
$dummyDigitalProduct = new Product([
    'name' => 'E-testGo CBT Digital - Aplikasi Ujian Online Gratis',
    'product_type' => 'external_link',
    'type' => 'product',
]);

// Test keywords
$pName = strtolower($dummyDigitalProduct->name);
$digitalKeywords = ['digital', 'cbt', 'aplikasi', 'app', 'e-book', 'ebook', 'pdf', 'course', 'kursus', 'webinar', 'tiket', 'ticket', 'voucher', 'lisensi', 'license', 'software', 'membership', 'akun', 'script', 'source code', 'template', 'file', 'download', 'e-learning'];

$isDigital = false;
foreach ($digitalKeywords as $kw) {
    if (str_contains($pName, $kw)) {
        $isDigital = true;
        echo "Matched keyword: '$kw'\n";
    }
}

echo "Product: '{$dummyDigitalProduct->name}' -> Is Digital: " . ($isDigital ? "YES" : "NO") . "\n";
