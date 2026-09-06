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
} else {
    out("WARNING: Storage not writable - run: chmod 775 $storageSrc");
}

// List files
out("\n--- Checking storage directories ---");
foreach (['settings', 'avatars', 'hero_slides', 'products'] as $dir) {
    $p = $storageSrc . '/' . $dir;
    if (is_dir($p)) {
        $files = array_diff(scandir($p), ['.', '..']);
        out("$dir/ : " . count($files) . " file(s)");
    } else {
        out("$dir/ : NOT FOUND");
    }
}

out("\n=== Done! ===");

if (!$isCli) {
    echo '</pre>';
}
