<?php
$key  = 'f1Hirqhga122b8bcfa5daa1ekEib4jrW';
$url = 'https://rajaongkir.komerce.id/api/v1/destination/province';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $key, 'Accept: application/json']);
$res = curl_exec($ch);
echo "With Bearer Only: " . substr($res, 0, 100) . "\n";

curl_setopt($ch, CURLOPT_HTTPHEADER, ['key: ' . $key, 'Accept: application/json']);
$res2 = curl_exec($ch);
echo "With key Only: " . substr($res2, 0, 100) . "\n";
