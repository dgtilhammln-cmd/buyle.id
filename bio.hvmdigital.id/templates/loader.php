<?php
// Ambil data tema dari DB (Pastikan kolomnya bernama 'theme' di tabel users)
$theme = isset($user['theme']) ? $user['theme'] : 'theme1';

// Fitur Preview Dashboard
if (isset($_GET['preview']) && !empty($_GET['preview'])) {
    $theme = $_GET['preview'];
}

// Whitelist semua tema agar bisa tayang di live web
$allowed = ['theme1','theme2','theme3pro','theme4pro','theme5pro','theme6pro','theme7pro','theme8pro','theme9pro','theme10pro'];

if (!in_array($theme, $allowed)) {
    $theme = 'theme1';
}

$path = __DIR__ . '/' . $theme . '/index.php';
if (file_exists($path)) {
    require $path;
} else {
    require __DIR__ . '/theme1/index.php';
}