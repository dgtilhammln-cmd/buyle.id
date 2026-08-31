<?php
require 'config.php';
$pass = password_hash('Ilhammaulana23', PASSWORD_DEFAULT);
$pdo->query("TRUNCATE TABLE admins");
$stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES ('ilhammaulana', ?)");
$stmt->execute([$pass]);
echo "Admin ilhammaulana berhasil dibuat!";
?>