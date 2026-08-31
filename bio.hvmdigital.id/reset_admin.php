<?php
require 'config.php';

// DATA LOGIN BARU
$email = 'dgt.ilhammln@gmail.com';
$password = 'Ilhammaulana23';
$role = 'admin';

// Enkripsi Password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // 1. Cek apakah email sudah terdaftar
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // SKENARIO A: User sudah ada -> UPDATE Password & Role
        $sql = "UPDATE users SET password = ?, role = ? WHERE email = ?";
        $pdo->prepare($sql)->execute([$hashed_password, $role, $email]);
        echo "<h1 style='color:green'>SUKSES! Akun Diperbarui.</h1>";
        echo "Silahkan Login:<br>Email: $email<br>Pass: $password";
    } else {
        // SKENARIO B: User belum ada -> BUAT BARU
        $username = 'admin_master'; // Username default
        $fullname = 'Super Admin';
        
        $sql = "INSERT INTO users (fullname, username, email, password, role) VALUES (?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$fullname, $username, $email, $hashed_password, $role]);
        echo "<h1 style='color:green'>SUKSES! Admin Baru Dibuat.</h1>";
        echo "Silahkan Login:<br>Email: $email<br>Pass: $password";
    }

} catch (Exception $e) {
    echo "<h1 style='color:red'>ERROR: " . $e->getMessage() . "</h1>";
}
?>