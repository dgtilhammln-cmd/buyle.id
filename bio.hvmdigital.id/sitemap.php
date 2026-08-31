<?php
/**
 * ==============================================================================
 * HVM DIGITAL - INTELLIGENT DYNAMIC SITEMAP v1.0
 * ==============================================================================
 * Path        : /public_html/sitemap.php
 * Standards   : Sitemaps.org Protocol, Google SEO Elite
 * Logic       : Dynamic user profile discovery
 * ==============================================================================
 */

require_once __DIR__ . '/config.php';

// Set header sebagai XML agar dibaca sebagai file sitemap asli oleh Google
header("Content-Type: application/xml; charset=utf-8");

$base_url = "https://bio.hvmdigital.id/";

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" 
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
            xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;

/**
 * --- 1. STATIC PAGES (Halaman Utama) ---
 */
$static_pages = [
    ''          => '1.0', // Home
    'login'     => '0.8', // Login
    'register'  => '0.9', // Register
];

foreach ($static_pages as $page => $priority) {
    echo '  <url>' . PHP_EOL;
    echo '    <loc>' . $base_url . $page . '</loc>' . PHP_EOL;
    echo '    <lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
    echo '    <changefreq>daily</changefreq>' . PHP_EOL;
    echo '    <priority>' . $priority . '</priority>' . PHP_EOL;
    echo '  </url>' . PHP_EOL;
}

/**
 * --- 2. DYNAMIC USER PROFILES ---
 * Mengambil semua username dari database yang berstatus aktif
 */
try {
    // Sesuaikan query dengan nama tabel dan kolom database Anda
    $stmt = $pdo->query("SELECT username, last_active FROM users WHERE status = 'active' ORDER BY last_active DESC");
    
    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Gunakan last_active jika ada, jika tidak gunakan tanggal hari ini
        $lastmod = (!empty($user['last_active'])) ? date('Y-m-d', strtotime($user['last_active'])) : date('Y-m-d');
        
        echo '  <url>' . PHP_EOL;
        echo '    <loc>' . $base_url . htmlspecialchars($user['username']) . '</loc>' . PHP_EOL;
        echo '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
        echo '    <changefreq>weekly</changefreq>' . PHP_EOL;
        echo '    <priority>0.7</priority>' . PHP_EOL;
        echo '  </url>' . PHP_EOL;
    }
} catch (PDOException $e) {
    // Jika error, sitemap tetap valid dengan halaman statis saja
}

echo '</urlset>';