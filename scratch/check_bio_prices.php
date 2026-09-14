<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Environment info
echo "DB Connection: " . config('database.default') . "\n";
echo "DB Database: " . config('database.connections.' . config('database.default') . '.database') . "\n";
echo "APP_URL: " . config('app.url') . "\n\n";

// Check all tables
echo "=== Tables available ===\n";
$tables = DB::select('SHOW TABLES');
foreach ($tables as $t) {
    $v = array_values((array)$t)[0];
    echo $v . "\n";
}
