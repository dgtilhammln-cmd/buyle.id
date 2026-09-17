<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

App\Models\Setting::clearCache();
App\Models\Setting::set('logo', 'settings/logo.png', 'image');

echo "SUCCESS! Setting logo updated to: " . App\Models\Setting::get('logo') . PHP_EOL;
