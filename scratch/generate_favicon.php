<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$logoPath = storage_path('app/public/settings/U59U4H4NkA2Wn424.webp');
if (!file_exists($logoPath)) {
    echo "Logo file not found: $logoPath\n";
    exit(1);
}

$info = getimagesize($logoPath);
echo "Logo dimensions: {$info[0]}x{$info[1]}, mime: {$info['mime']}\n";

$img = imagecreatefromwebp($logoPath);
if (!$img) {
    echo "Failed to load logo image\n";
    exit(1);
}

$w = imagesx($img);
$h = imagesy($img);

// Create a 512x512 square canvas with transparent background
$size = max($w, $h);
$canvas = imagecreatetruecolor(512, 512);
imagealphablending($canvas, false);
imagesavealpha($canvas, true);
$transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
imagefilledrectangle($canvas, 0, 0, 512, 512, $transparent);

// Fit logo inside 512x512 canvas centered
$scale = 512 / max($w, $h);
$nw = (int)($w * $scale);
$nh = (int)($h * $scale);
$nx = (int)((512 - $nw) / 2);
$ny = (int)((512 - $nh) / 2);

imagealphablending($canvas, true);
imagecopyresampled($canvas, $img, $nx, $ny, 0, 0, $nw, $nh, $w, $h);

// Save as PNG
$pngPath = storage_path('app/public/settings/favicon.png');
imagealphablending($canvas, false);
imagesavealpha($canvas, true);
imagepng($canvas, $pngPath, 9);
imagedestroy($img);

echo "Created favicon at: $pngPath\n";

// Copy to public/favicon.ico, public/favicon.png, public_html/favicon.ico, public_html/favicon.png
$destinations = [
    public_path('favicon.ico'),
    public_path('favicon.png'),
    base_path('public_html/favicon.ico'),
    base_path('public_html/favicon.png'),
];

foreach ($destinations as $dest) {
    $dir = dirname($dest);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    copy($pngPath, $dest);
    echo "Copied to: $dest\n";
}

// Update DB setting for favicon
\App\Models\Setting::clearCache();
\App\Models\Setting::set('favicon', 'settings/favicon.png', 'image');
echo "Updated DB Setting 'favicon' to 'settings/favicon.png'\n";
