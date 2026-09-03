<?php
$files = glob(__DIR__ . '/../storage/app/public/settings/*.{webp,png,jpg,jpeg}', GLOB_BRACE);
foreach ($files as $f) {
    $info = @getimagesize($f);
    if ($info) {
        $basename = basename($f);
        echo "$basename: {$info[0]}x{$info[1]} ({$info['mime']})\n";
    }
}
