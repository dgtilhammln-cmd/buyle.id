<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$squares = ['0dZNAPkj6eM4JV0j.webp', 'HqOnVWg0hugddYVC.webp', 'hd35Q2VWrEAPkAM7.webp', 'yOlgVghpCK9YKb2s.webp', 'ZDKvl7ZkVW7ifrC2.webp'];
foreach ($squares as $s) {
    $p = storage_path('app/public/settings/' . $s);
    if (file_exists($p)) {
        echo "$s size: " . filesize($p) . " bytes\n";
    }
}
