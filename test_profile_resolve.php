<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$svc = new App\Services\SocialMediaDownloaderService();
$res = $svc->resolve('https://www.instagram.com/ilhamm.ln/');

echo "Success: " . ($res['success'] ? 'YES' : 'NO') . "\n";
echo "Type: " . ($res['type'] ?? '') . "\n";
echo "Title: " . ($res['title'] ?? '') . "\n";
echo "Is Profile Feed: " . (!empty($res['is_profile_feed']) ? 'YES' : 'NO') . "\n";
echo "Posts Count: " . count($res['posts'] ?? []) . "\n";
if (!empty($res['posts'][0])) {
    echo "First post thumb: " . substr($res['posts'][0]['thumb'] ?? '', 0, 50) . "...\n";
    echo "First post video: " . ($res['posts'][0]['is_video'] ? 'YES' : 'NO') . "\n";
}

