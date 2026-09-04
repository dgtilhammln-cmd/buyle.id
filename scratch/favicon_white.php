<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Load logo webp
$logoPath = storage_path('app/public/settings/U59U4H4NkA2Wn424.webp');
$src = imagecreatefromwebp($logoPath);
$sw = imagesx($src);
$sh = imagesy($src);

echo "Logo: {$sw}x{$sh}\n";

// The buyle.id icon occupies approximately left 40% of the logo
// Let's isolate just the "D" icon symbol from the left
// We'll crop the left square portion (height x height)
$iconSize = $sh; // Square crop from left: 131x131
$srcX = 0;
$srcY = 0;

// Create a 512x512 output with white background (renders cleanly in GSC)
$size = 512;
$canvas = imagecreatetruecolor($size, $size);

// WHITE background — looks clean in GSC, search results, browser tabs
$white = imagecolorallocate($canvas, 255, 255, 255);
imagefilledrectangle($canvas, 0, 0, $size, $size, $white);

// Center the icon with padding
$padding = 60;
$targetBox = $size - ($padding * 2);
imagecopyresampled($canvas, $src, $padding, $padding, $srcX, $srcY, $targetBox, $targetBox, $iconSize, $iconSize);

imagedestroy($src);

// Save
$outPath = storage_path('app/public/settings/favicon.png');
imagepng($canvas, $outPath, 9);
imagedestroy($canvas);

echo "Saved: $outPath\n";

// Copy to all locations
$targets = [
    public_path('favicon.ico'),
    public_path('favicon.png'),
    base_path('public_html/favicon.ico'),
    base_path('public_html/favicon.png'),
];
foreach ($targets as $t) {
    copy($outPath, $t);
    echo "Copied: $t\n";
}

// Update DB
\App\Models\Setting::clearCache();
\App\Models\Setting::set('favicon', 'settings/favicon.png', 'image');
echo "DB updated.\n";
