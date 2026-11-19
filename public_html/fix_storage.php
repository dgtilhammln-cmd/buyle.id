<?php
/**
 * buyle.id — Storage Symlink Fix Script
 * Bisa dijalankan via:
 *   - CLI: php public_html/fix_storage.php
 *   - Browser: https://buyle.id/fix_storage.php?key=buyleid2024fix
 */

$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    $SECRET_KEY = 'buyleid2024fix';
    if (!isset($_GET['key']) || $_GET['key'] !== $SECRET_KEY) {
        http_response_code(403);
        die('<b>403 Forbidden</b>');
    }
    echo '<pre style="font-family:monospace;padding:20px;">';
}

function out($msg) {
    echo $msg . "\n";
}

out("=== buyle.id Storage Symlink Fix ===\n");

// Resolve app root (one level up from public_html/)
$publicHtmlDir = __DIR__; // /home/.../domains/buyle.id/public_html/public_html
$appRoot       = dirname($publicHtmlDir); // /home/.../domains/buyle.id/public_html
$storageSrc    = $appRoot . '/storage/app/public';
$linkPath      = $publicHtmlDir . '/storage';

out("App Root    : $appRoot");
out("Storage Src : $storageSrc");
out("Link Path   : $linkPath\n");

// Ensure storage/app/public exists
if (!is_dir($storageSrc)) {
    out("Creating missing directory: $storageSrc");
    @mkdir($storageSrc, 0775, true);
}

// Remove existing link or directory
if (is_link($linkPath)) {
    $cur = readlink($linkPath);
    if ($cur === $storageSrc) {
        out("OK: Symlink already correct -> $storageSrc");
        goto done;
    }
    out("Removing wrong symlink (was -> $cur)");
    unlink($linkPath);
} elseif (is_dir($linkPath)) {
    out("Found real directory at $linkPath, removing...");
    // Remove recursively
    $cmd = 'rm -rf ' . escapeshellarg($linkPath);
    system($cmd, $ret);
    if ($ret !== 0) {
        out("ERROR: Could not remove directory. Try manually deleting $linkPath");
        exit(1);
    }
}

// Create symlink
if (symlink($storageSrc, $linkPath)) {
    out("OK: Symlink created -> $storageSrc");
} else {
    out("ERROR: symlink() failed. Trying system ln -s...");
    system("ln -s " . escapeshellarg($storageSrc) . " " . escapeshellarg($linkPath), $ret);
    if ($ret === 0) {
        out("OK: ln -s succeeded");
    } else {
        out("FAILED: Could not create symlink!");
        exit(1);
    }
}

done:
// Verify writable
$testFile = $storageSrc . '/.write_test';
if (@file_put_contents($testFile, 'ok') !== false) {
    @unlink($testFile);
    out("OK: Storage is writable");
}

// Sync public/images to public_html/images
$appImagesDir = $appRoot . '/public/images';
$publicHtmlImagesDir = $publicHtmlDir . '/images';
if (is_dir($appImagesDir)) {
    if (!is_dir($publicHtmlImagesDir)) {
        @mkdir($publicHtmlImagesDir, 0755, true);
    }
    foreach (scandir($appImagesDir) as $imgFile) {
        if ($imgFile !== '.' && $imgFile !== '..') {
            @copy($appImagesDir . '/' . $imgFile, $publicHtmlImagesDir . '/' . $imgFile);
        }
    }
    out("OK: Synced public/images to public_html/images");
}

// List files
out("\n--- Checking storage directories ---");
foreach (['settings', 'avatars', 'hero_slides', 'products'] as $dir) {
    $p = $storageSrc . '/' . $dir;
    if (is_dir($p)) {
        $files = array_diff(scandir($p), ['.', '..']);
        out("$dir/ : " . count($files) . " file(s)");
        if ($dir === 'products') {
            out("Sample products files: " . implode(', ', array_slice($files, 0, 10)));
        }
    } else {
        out("$dir/ : NOT FOUND");
    }
}

// Check recent products from DB
out("\n--- Checking DB products image paths ---");
try {
    require_once $appRoot . '/vendor/autoload.php';
    $app = require_once $appRoot . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $dbProds = \App\Models\Product::whereNull('image')->orWhere('image', '')->latest()->limit(20)->get(['id', 'name', 'seller_id', 'image', 'gallery', 'og_image', 'product_type']);
    out("Products with NULL or empty image: " . count($dbProds));
    foreach ($dbProds as $dp) {
        // Check if there is bio block with this name or product_id
        $bb = \App\Models\CreatorBioBlock::where('title', $dp->name)
            ->orWhere('data_json->product_id', $dp->id)
            ->first();
        $bbImg = 'NO_BLOCK';
        if ($bb) {
            $data = $bb->data_json ?? [];
            $bbImg = json_encode(array_filter([
                'image' => $data['image'] ?? null,
                'images' => $data['images'] ?? null,
                'thumbnail' => $data['thumbnail'] ?? null,
                'scraped_image' => $data['scraped_image'] ?? null,
            ]));
        }
        $gallery = json_encode($dp->gallery);
        $og = $dp->og_image ?? 'NULL';
        out("ID: {$dp->id} | {$dp->name} | BioBlockImg: $bbImg | Gallery: $gallery | OG: $og");
    }
} catch (\Throwable $e) {
    out("DB check failed: " . $e->getMessage());
}

// Reset OPcache and clear compiled view files
out("\n--- Clearing view & OPcache cache ---");
if (function_exists('opcache_reset')) {
    if (@opcache_reset()) {
        out("OK: OPcache reset successfully");
    } else {
        out("NOTICE: opcache_reset() called");
    }
}
$viewsDir = $appRoot . '/storage/framework/views';
if (is_dir($viewsDir)) {
    $files = glob($viewsDir . '/*.php');
    $cnt = 0;
    if ($files) {
        foreach ($files as $f) {
            if (@unlink($f)) $cnt++;
        }
    }
    out("OK: Cleared $cnt compiled view file(s) from storage/framework/views");
}

out("\n=== Done! ===");

if (!$isCli) {
    echo '</pre>';
}
