<?php
$host = 'localhost';
$db   = 'u664715641_BIO';
$user = 'u664715641_BIO';
$pass = '#Ilhammaulana23'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>