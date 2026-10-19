<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Configured App ID: " . App\Services\MetaConfigService::getAppId() . "\n";
echo "Configured App Secret: " . substr(App\Services\MetaConfigService::getAppSecret(), 0, 6) . "...\n";
echo "Configured Graph Version: " . App\Services\MetaConfigService::getGraphVersion() . "\n";
