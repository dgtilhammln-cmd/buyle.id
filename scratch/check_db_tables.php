<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = \DB::select('SHOW TABLES');
foreach ($tables as $t) {
    foreach ($t as $k => $v) {
        if (str_contains($v, 'bio') || str_contains($v, 'block') || str_contains($v, 'product')) {
            echo "Table: {$v}\n";
        }
    }
}
