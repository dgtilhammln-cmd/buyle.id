<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$logo = App\Models\Setting::get('logo');
echo "Logo setting: " . var_export($logo, true) . PHP_EOL;

if ($logo) {
    $fullPath = storage_path('app/public/' . $logo);
    echo "Full path: " . $fullPath . PHP_EOL;
    if (file_exists($fullPath)) {
        echo "File exists! Size: " . filesize($fullPath) . " bytes" . PHP_EOL;
        $info = getimagesize($fullPath);
        echo "Dimensions: " . $info[0] . "x" . $info[1] . " Mime: " . $info['mime'] . PHP_EOL;
    } else {
        echo "File does NOT exist at path." . PHP_EOL;
    }
}
