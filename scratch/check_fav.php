<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$fav = \App\Models\Setting::get('favicon');
$logo = \App\Models\Setting::get('logo');

echo "Favicon setting: " . var_export($fav, true) . PHP_EOL;
echo "Logo setting: " . var_export($logo, true) . PHP_EOL;
