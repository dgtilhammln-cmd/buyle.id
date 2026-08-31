<?php
/**
 * bio.hvmdigital.id - MAIN ROUTER v2.5
 * Fitur: Multi-Theme, Live Preview, Premium Error Handler
 */

// 1. Inisialisasi Database & Session
if (!file_exists('config.php')) {
    die("Error: File config.php tidak ditemukan.");
}
require 'config.php';
session_start();

// 2. Ambil Route dari URL
$route = isset($_GET['route']) ? rtrim($_GET['route'], '/') : '';
$parts = explode('/', $route); 
$main_route = isset($parts[0]) ? $parts[0] : ''; 

// =======================================================
// ROUTING SYSTEM
// =======================================================

// A. HOME PAGE
if ($route == '' || $route == 'home') {
    if (file_exists('home/index.php')) {
        require 'home/index.php';
    } else {
        header("Location: /login"); // Jika home belum siap, lempar ke login
        exit;
    }
} 

// B. AUTHENTICATION (Login, Register, Logout)
elseif ($route == 'login' || $route == 'register' || $route == 'logout') {
    if (file_exists('dashboard/auth.php')) {
        require 'dashboard/auth.php';
    } else {
        $_SERVER['REDIRECT_STATUS'] = 500;
        include 'error.php';
        exit;
    }
} 

// C. ADMIN CENTER
elseif ($main_route == 'admincenter') {
    if (file_exists('admin/index.php')) {
        require 'admin/index.php';
    } else {
        $_SERVER['REDIRECT_STATUS'] = 403;
        include 'error.php';
        exit;
    }
}

// D. DASHBOARD USER & ANALYTICS
elseif ($main_route == 'dashboard') {
    if (file_exists('dashboard/index.php')) {
        require 'dashboard/index.php';
    } else {
        $_SERVER['REDIRECT_STATUS'] = 500;
        include 'error.php';
        exit;
    }
} 

// E. USER PROFILE (bio.hvmdigital.id/username)
else {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$route]);
        $user = $stmt->fetch();

        if ($user) {
            $theme = !empty($user['theme']) ? $user['theme'] : 'theme1';

            if (isset($_GET['theme']) && !empty($_GET['theme'])) {
                $requested_theme = $_GET['theme'];
                if (file_exists(__DIR__ . "/templates/$requested_theme/index.php")) {
                    $theme = $requested_theme;
                }
            }
            
            $themeFile = __DIR__ . "/templates/$theme/index.php";

            if (file_exists($themeFile)) {
                require $themeFile;
            } else {
                require __DIR__ . '/templates/theme1/index.php';
            }

        } else {
            // =======================================================
            // F. FIX: 404 NOT FOUND PREMIUM (Pasti Terpanggil)
            // =======================================================
            http_response_code(404);
            $_SERVER['REDIRECT_STATUS'] = 404; 
            
            // Menggunakan __DIR__ agar jalur file absolut ke public_html/error.php
            $error_path = __DIR__ . '/error.php';

            if (file_exists($error_path)) {
                include $error_path;
            } else {
                // Jika file error.php benar-benar tidak ada di folder
                die("<style>body{background:#020b09;color:#fff;display:flex;justify-content:center;align-items:center;height:100vh;font-family:sans-serif;}</style>
                    <div><h1>404</h1><p>File error.php tidak ditemukan di root.</p></div>");
            }
            exit;
        }
    } catch (Exception $e) {
        // G. 500 SERVER ERROR
        http_response_code(500);
        $_SERVER['REDIRECT_STATUS'] = 500;
        if (file_exists(__DIR__ . '/error.php')) {
            include __DIR__ . '/error.php';
        } else {
            die("Critical Database Error.");
        }
        exit;
    }
}