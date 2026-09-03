<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$logoPath = storage_path('app/public/settings/U59U4H4NkA2Wn424.webp');
$src = imagecreatefromwebp($logoPath);
$sw = imagesx($src);
$sh = imagesy($src);

// The icon is in the left portion (roughly x: 0 to 125, y: 0 to 131)
// Let's find bounding box of non-transparent / non-white pixels in the left half
$minX = $sw; $minY = $sh; $maxX = 0; $maxY = 0;

for ($x = 0; $x < (int)($sw * 0.4); $x++) {
    for ($y = 0; $y < $sh; $y++) {
        $rgba = imagecolorat($src, $x, $y);
        $alpha = ($rgba >> 24) & 0x7F;
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;
        
        // If not completely transparent and not background white
        if ($alpha < 120 && !($r > 245 && $g > 245 && $b > 245)) {
            if ($x < $minX) $minX = $x;
            if ($y < $minY) $minY = $y;
            if ($x > $maxX) $maxX = $x;
            if ($y > $maxY) $maxY = $y;
        }
    }
}

echo "Icon Bounding Box: minX=$minX, minY=$minY, maxX=$maxX, maxY=$maxY\n";
$iconW = $maxX - $minX + 1;
$iconH = $maxY - $minY + 1;
echo "Icon dimensions: {$iconW}x{$iconH}\n";

// Create 512x512 square canvas with transparent background
$size = 512;
$canvas = imagecreatetruecolor($size, $size);
imagealphablending($canvas, false);
imagesavealpha($canvas, true);
$trans = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
imagefilledrectangle($canvas, 0, 0, $size, $size, $trans);

// Scale icon to fit 400x400 centered inside 512x512 canvas (padding = 56px)
$targetBox = 400;
$scale = $targetBox / max($iconW, $iconH);
$dw = (int)($iconW * $scale);
$dh = (int)($iconH * $scale);
$dx = (int)(($size - $dw) / 2);
$dy = (int)(($size - $dh) / 2);

imagealphablending($canvas, true);
imagecopyresampled($canvas, $src, $dx, $dy, $minX, $minY, $dw, $dh, $iconW, $iconH);

// Save clean PNG
$favPng = storage_path('app/public/settings/favicon.png');
imagealphablending($canvas, false);
imagesavealpha($canvas, true);
imagepng($canvas, $favPng, 9);
imagedestroy($src);
imagedestroy($canvas);

echo "Saved cropped transparent favicon to: $favPng\n";

// Copy to all locations
$locations = [
    public_path('favicon.ico'),
    public_path('favicon.png'),
    base_path('public_html/favicon.ico'),
    base_path('public_html/favicon.png'),
];

foreach ($locations as $loc) {
    $dir = dirname($loc);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    copy($favPng, $loc);
    echo "Copied to $loc\n";
}

// Update DB setting
\App\Models\Setting::clearCache();
\App\Models\Setting::set('favicon', 'settings/favicon.png', 'image');
echo "Updated DB Setting 'favicon' = 'settings/favicon.png'\n";
