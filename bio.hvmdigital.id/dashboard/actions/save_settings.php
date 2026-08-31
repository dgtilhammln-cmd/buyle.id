<?php
/**
 * ==============================================================================
 * HVM STUDIO - SAVE SETTINGS v6.5 (INTELLIGENT ELITE ENGINE)
 * ==============================================================================
 * Connection  : public_html/uploads/
 * Features    : WebP Converter, Auto-Cleanup, 10 Buttons (FIXED), 10 TikTok Slots
 * Logic       : Deep Data Integrity & Performance Optimized
 * ==============================================================================
 */

session_start();
ob_start();

require_once __DIR__ . '/../../config.php';

// --- PROTEKSI AKSES ---
if (!isset($_SESSION['user_id'])) { 
    header("Location: /login"); 
    exit; 
}

$uid = $_SESSION['user_id'];

// Ambil data user lama untuk referensi file fisik (Penting untuk pembersihan)
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$uid]);
$me = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $is_premium = ($me['role'] == 'premium' || $me['role'] == 'admin');
        
        // TENTUKAN PATH ABSOLUT SERVER (public_html/uploads/)
        $uploadDir = realpath(__DIR__ . '/../../uploads/') . '/';

        /**
         * --- HELPER 1: INTELLIGENT UPLOAD (WEBP CONVERTER) ---
         * Mengubah file lama (JPG/PNG) menjadi WebP secara otomatis & menghapus yang lama
         */
        function intelligentUpload($file, $prefix, $oldFile, $dir) { 
            if(!empty($file['name']) && $file['error'] === UPLOAD_ERR_OK) { 
                
                $tmpPath = $file['tmp_name'];
                $info = getimagesize($tmpPath);
                
                if (!$info) return null;

                // Nama unik berakhiran .webp
                $newName = $prefix . '_' . time() . '_' . bin2hex(random_bytes(2)) . '.webp'; 
                $targetPath = $dir . $newName;

                // Konversi berdasarkan tipe asal
                switch ($info['mime']) {
                    case 'image/jpeg': $image = imagecreatefromjpeg($tmpPath); break;
                    case 'image/png':  $image = imagecreatefrompng($tmpPath); break;
                    case 'image/webp': $image = imagecreatefromwebp($tmpPath); break;
                    case 'image/gif':  $image = imagecreatefromgif($tmpPath); break;
                    default: return null;
                }

                // Kompresi WebP (80% Quality - Standar Industri)
                if (imagewebp($image, $targetPath, 80)) {
                    imagedestroy($image);
                    
                    // --- AUTO CLEANUP ---
                    // Hapus file fisik lama jika ada agar server tidak penuh
                    if(!empty($oldFile) && file_exists($dir . $oldFile)) { 
                        @unlink($dir . $oldFile); 
                    }
                    return $newName;
                }
                imagedestroy($image);
            } 
            return null; 
        }
        
        /**
         * --- HELPER 2: TIKTOK OEMBED FETCH ---
         */
        function fetchTikTokThumbnail($url) { 
            if(empty($url)) return ''; 
            $url = trim($url);
            $json = @file_get_contents("https://www.tiktok.com/oembed?url=" . urlencode($url)); 
            if($json){
                $data = json_decode($json, true); 
                return $data['thumbnail_url'] ?? ''; 
            }
            return '';
        }

        // --- 1. LOGIKA HAPUS FILE (VIA TOMBOL X) ---
        if (isset($_POST['delete_file']) && is_array($_POST['delete_file'])) {
            foreach ($_POST['delete_file'] as $field) {
                // Mapping nama kolom gambar
                $column = $field . "_img"; 
                if ($field == 'profile') $column = 'profile_pic';
                if ($field == 'cover') $column = 'cover_pic';
                if (strpos($field, 'exp') !== false) $column = $field . "_img";

                if (isset($me[$column]) && !empty($me[$column])) {
                    $fileToDelete = $me[$column];
                    if (file_exists($uploadDir . $fileToDelete)) { 
                        @unlink($uploadDir . $fileToDelete); 
                    }
                    $pdo->prepare("UPDATE users SET $column = NULL WHERE id = ?")->execute([$uid]);
                }
            }
        }

        // --- 2. UPDATE IDENTITAS & TEMA ---
        $theme = $_POST['theme'] ?? $me['theme'];
        if (strpos($theme, 'pro') !== false && !$is_premium) { $theme = 'theme1'; }

        $sql_id = "UPDATE users SET 
                fullname=?, nickname=?, role_display=?, location=?, 
                drive_title=?, drive_url=?, ig_user=?, tt_user=?, wa_number=?, 
                theme=? WHERE id=?";
        
        $pdo->prepare($sql_id)->execute([
            $_POST['fullname'] ?? $me['fullname'], 
            $_POST['nickname'] ?? $me['nickname'], 
            $_POST['role_user'] ?? $me['role_display'], 
            $_POST['location'] ?? $me['location'],
            $_POST['drive_title'] ?? $me['drive_title'], 
            $_POST['drive_url'] ?? $me['drive_url'], 
            $_POST['ig_user'] ?? $me['ig_user'], 
            $_POST['tt_user'] ?? $me['tt_user'], 
            $_POST['wa_number'] ?? $me['wa_number'],
            $theme, 
            $uid
        ]);

        // --- 3. UPDATE EXPERIENCE (PRO 1-5) ---
        for($e=1; $e<=5; $e++) {
            if($is_premium) {
                $e_title = $_POST["exp{$e}_title"] ?? '';
                $e_desc = $_POST["exp{$e}_desc"] ?? '';
                
                $pdo->prepare("UPDATE users SET exp{$e}_title=?, exp{$e}_desc=? WHERE id=?")
                    ->execute([$e_title, $e_desc, $uid]);
                
                if($n = intelligentUpload($_FILES["exp{$e}_img"] ?? null, "exp$e", $me["exp{$e}_img"] ?? '', $uploadDir)) {
                    $pdo->prepare("UPDATE users SET exp{$e}_img=? WHERE id=?")->execute([$n, $uid]);
                }
            }
        }

        // --- 4. UPDATE TIKTOK (FREE 3, PRO 10) ---
        $tt_limit = $is_premium ? 10 : 3;
        for ($t=1; $t<=10; $t++) {
            if ($t <= $tt_limit && isset($_POST["tiktok_vid$t"])) {
                $new_tt_url = trim($_POST["tiktok_vid$t"]);
                $pdo->prepare("UPDATE users SET tiktok_vid$t=? WHERE id=?")->execute([$new_tt_url, $uid]);

                // Hanya fetch thumb jika link berubah (Hemat performa)
                if (!empty($new_tt_url) && $new_tt_url !== $me["tiktok_vid$t"]) {
                    $thumb = fetchTikTokThumbnail($new_tt_url);
                    if ($thumb) {
                        $pdo->prepare("UPDATE users SET tiktok_thumb$t=? WHERE id=?")->execute([$thumb, $uid]);
                    }
                }
            }
        }

        // --- 5. UPDATE BUTTONS (WORKS 100%) ---
        // Menangani 10 slot tombol. Jika role free, hanya proses 2 slot.
        $btn_limit = $is_premium ? 10 : 2;
        for ($i=1; $i<=10; $i++) {
            if ($i <= $btn_limit && isset($_POST["btn{$i}_text"])) {
                $b_text = $_POST["btn{$i}_text"];
                $b_desc = $_POST["btn{$i}_desc"] ?? ''; // Deskripsi ditangkap di sini
                $b_url  = $_POST["btn{$i}_url"] ?? '';

                // UPDATE DATA TEKS & URL
                $pdo->prepare("UPDATE users SET btn{$i}_text=?, btn{$i}_desc=?, btn{$i}_url=? WHERE id=?")
                    ->execute([$b_text, $b_desc, $b_url, $uid]);
                
                // UPLOAD ICON BUTTON (AUTO-WEBP)
                if($n = intelligentUpload($_FILES["btn{$i}_img"] ?? null, "b$i", $me["btn{$i}_img"] ?? '', $uploadDir)) {
                    $pdo->prepare("UPDATE users SET btn{$i}_img=? WHERE id=?")->execute([$n, $uid]);
                }
            }
        }

        // --- 6. UPDATE PROFILE & COVER (ULTRA OPTIMIZED) ---
        if($n = intelligentUpload($_FILES['profile_pic'] ?? null, 'pro', $me['profile_pic'] ?? '', $uploadDir)) {
            $pdo->prepare("UPDATE users SET profile_pic=? WHERE id=?")->execute([$n, $uid]);
        }
        if($n = intelligentUpload($_FILES['cover_pic'] ?? null, 'cov', $me['cover_pic'] ?? '', $uploadDir)) {
            $pdo->prepare("UPDATE users SET cover_pic=? WHERE id=?")->execute([$n, $uid]);
        }

        // --- SELESAI ---
        $target = $_POST['redirect'] ?? 'settings';
        ob_clean(); 
        header("Location: /dashboard?view=$target&saved=1&success=1");
        exit;

    } catch (Exception $e) { 
        ob_clean();
        die("Critical Engine Error: " . $e->getMessage()); 
    }
}