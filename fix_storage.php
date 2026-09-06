<?php
/**
 * buyle.id — Storage Symlink Fix Script
 * Upload file ini ke public_html/ di Hostinger File Manager,
 * lalu akses via browser: https://buyle.id/fix_storage.php?key=buyleid2024fix
 *
 * HAPUS file ini setelah selesai digunakan!
 */

$SECRET_KEY = 'buyleid2024fix';

if (!isset($_GET['key']) || $_GET['key'] !== $SECRET_KEY) {
    http_response_code(403);
    die('<b>403 Forbidden</b>. Missing or invalid key.');
}

echo '<pre style="font-family:monospace; font-size:13px; padding:20px;">';
echo "=== buyle.id Storage Symlink Fix ===\n\n";

// Determine paths
$publicHtmlPath = __DIR__; // /home/u664715641/domains/buyle.id/public_html/public_html
$appRoot = dirname($publicHtmlPath); // /home/u664715641/domains/buyle.id/public_html
$storageSrc = $appRoot . '/storage/app/public';

echo "App Root    : $appRoot\n";
echo "Public Path : $publicHtmlPath\n";
echo "Storage Src : $storageSrc\n\n";

// Check storage source exists
if (!is_dir($storageSrc)) {
    echo "ERROR: Storage source directory does not exist: $storageSrc\n";
    echo "Creating it...\n";
    @mkdir($storageSrc, 0775, true);
    if (is_dir($storageSrc)) {
        echo "OK: Created $storageSrc\n";
    } else {
        echo "FAILED: Could not create directory. Check permissions.\n";
    }
} else {
    echo "OK: Storage source exists\n";
}

// Fix symlink in public_html/storage (where web server serves from)
$linkPath = $publicHtmlPath . '/storage';

echo "\n--- Fixing: $linkPath ---\n";
if (is_link($linkPath)) {
    $current = readlink($linkPath);
    echo "Existing symlink -> $current\n";
    if ($current === $storageSrc) {
        echo "OK: Symlink already correct!\n";
    } else {
        echo "Wrong target. Removing...\n";
        unlink($linkPath);
        if (symlink($storageSrc, $linkPath)) {
            echo "OK: Symlink recreated -> $storageSrc\n";
        } else {
            echo "FAILED: Could not create symlink!\n";
        }
    }
} elseif (is_dir($linkPath)) {
    echo "Found a real directory (not symlink). Checking if empty...\n";
    $contents = array_diff(scandir($linkPath), ['.', '..']);
    if (empty($contents)) {
        rmdir($linkPath);
        echo "Removed empty directory.\n";
        if (symlink($storageSrc, $linkPath)) {
            echo "OK: Symlink created -> $storageSrc\n";
        } else {
            echo "FAILED: Could not create symlink!\n";
        }
    } else {
        echo "Directory is NOT empty. Contents:\n";
        foreach ($contents as $item) {
            echo "  - $item\n";
        }
        echo "Cannot auto-fix. Please manually delete $linkPath and re-run.\n";
    }
} elseif (!file_exists($linkPath)) {
    echo "No existing link/dir. Creating symlink...\n";
    if (symlink($storageSrc, $linkPath)) {
        echo "OK: Symlink created -> $storageSrc\n";
    } else {
        echo "FAILED: Could not create symlink!\n";
    }
}

// Verify
echo "\n--- Verification ---\n";
$testFile = $storageSrc . '/test_write_' . time() . '.txt';
if (@file_put_contents($testFile, 'test') !== false) {
    $urlPath = '/storage/test_write_' . basename($testFile);
    echo "OK: Storage is writable.\n";
    echo "Test URL: https://buyle.id$urlPath (should return 200)\n";
    @unlink($testFile);
} else {
    echo "WARNING: Storage is not writable!\n";
    echo "Run: chmod 775 $storageSrc\n";
}

// List a few files to confirm
echo "\n--- Sample files in storage ---\n";
$dirs = ['settings', 'avatars', 'hero_slides'];
foreach ($dirs as $dir) {
    $dirPath = $storageSrc . '/' . $dir;
    if (is_dir($dirPath)) {
        $files = array_diff(scandir($dirPath), ['.', '..']);
        echo "$dir/ : " . count($files) . " file(s)\n";
        $count = 0;
        foreach ($files as $f) {
            if ($count++ >= 3) { echo "  ...(more)\n"; break; }
            echo "  - $f\n";
        }
    } else {
        echo "$dir/ : NOT FOUND\n";
    }
}

echo "\n=== Done! HAPUS file ini segera dari server! ===\n";
echo '</pre>';
