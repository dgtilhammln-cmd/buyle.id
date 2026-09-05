<?php
// Script diagnosa koneksi & pengiriman SMTP Hostinger
header('Content-Type: text/plain; charset=utf-8');

$host = 'smtp.hostinger.com';
$port = 465; // SSL
$user = 'hai@buyle.id';
$pass = '#Ilhammaulana23';
$to   = isset($_GET['to']) ? $_GET['to'] : 'dgtilhammln@gmail.com';

echo "=== DIAGNOSA SMTP HOSTINGER (buyle.id) ===\n";
echo "Host: $host\nPort: $port\nUser: $user\nTo: $to\n\n";

$socket = @fsockopen("ssl://{$host}", $port, $errno, $errstr, 15);
if (!$socket) {
    echo "❌ KONEKSI SOCKET GAGAL: $errstr ($errno)\n";
    exit;
}

echo "✅ Socket Terhubung ke ssl://{$host}:{$port}\n";

function readResponse($socket) {
    $res = '';
    while ($line = fgets($socket, 512)) {
        $res .= $line;
        if (substr($line, 3, 1) == ' ') break;
    }
    return $res;
}

function sendCmd($socket, $cmd) {
    fputs($socket, $cmd . "\r\n");
    return readResponse($socket);
}

echo "SERVER: " . readResponse($socket);
echo "EHLO: " . sendCmd($socket, "EHLO buyle.id");
echo "AUTH: " . sendCmd($socket, "AUTH LOGIN");
echo "USER: " . sendCmd($socket, base64_encode($user));
echo "PASS: " . sendCmd($socket, base64_encode($pass));
echo "MAIL FROM: " . sendCmd($socket, "MAIL FROM: <{$user}>");
echo "RCPT TO: " . sendCmd($socket, "RCPT TO: <{$to}>");
echo "DATA: " . sendCmd($socket, "DATA");

$headers  = "From: buyle.id <{$user}>\r\n";
$headers .= "Reply-To: {$user}\r\n";
$headers .= "To: <{$to}>\r\n";
$headers .= "Subject: Tes Diagnosa SMTP buyle.id\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "\r\n";
$body = "Ini adalah email uji coba langsung dari server buyle.id.\nDikirim pada: " . date('Y-m-d H:i:s');

echo "SEND DATA: " . sendCmd($socket, $headers . $body . "\r\n.");
echo "QUIT: " . sendCmd($socket, "QUIT");
fclose($socket);

echo "\n=== PROSES DIAGNOSA SELESAI ===";
