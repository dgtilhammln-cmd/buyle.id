<?php
require 'vendor/autoload.php';

echo "Testing /api/v1/destination/city\n";
$ch = curl_init('https://api.collaborator.komerce.id/api/v1/destination/city');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['key: f1Hirqhga122b8bcfa5daa1ekEib4jrW']);
echo substr(curl_exec($ch), 0, 300) . "\n\n";

echo "Testing /starter/city\n";
$ch2 = curl_init('https://api.collaborator.komerce.id/starter/city');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_HTTPHEADER, ['key: f1Hirqhga122b8bcfa5daa1ekEib4jrW']);
echo substr(curl_exec($ch2), 0, 300) . "\n\n";

echo "Testing /v1/destination/city\n";
$ch3 = curl_init('https://api.collaborator.komerce.id/v1/destination/city');
curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch3, CURLOPT_HTTPHEADER, ['key: f1Hirqhga122b8bcfa5daa1ekEib4jrW']);
echo substr(curl_exec($ch3), 0, 300) . "\n\n";
