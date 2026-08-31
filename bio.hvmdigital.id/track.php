<?php
// FILE: track.php
require 'config.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['username']) && isset($data['type'])) {
    $username = $data['username'];
    $type = $data['type'];

    // Ambil ID User
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        $uid = $user['id'];
        // Simpan ke database
        $stmt = $pdo->prepare("INSERT INTO analytics (user_id, event_type) VALUES (?, ?)");
        $stmt->execute([$uid, $type]);
    }
}
?>