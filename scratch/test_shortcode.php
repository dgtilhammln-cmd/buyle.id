<?php

function shortcodeToMediaId(string $code): string {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
    $id = '0';
    for ($i = 0; $i < strlen($code); $i++) {
        $char = $code[$i];
        $val = strpos($alphabet, $char);
        if ($val === false) continue;
        $id = bcadd(bcmul($id, '64'), (string)$val);
    }
    return $id;
}

function mediaIdToShortcode(string $id): string {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
    $code = '';
    while (bccomp($id, '0') > 0) {
        $rem = bcmod($id, '64');
        $code = $alphabet[(int)$rem] . $code;
        $id = bcdiv($id, '64', 0);
    }
    return $code;
}

// Test with a known real post
$testShortcode = 'DGWV_ZCSzK_';
$id = shortcodeToMediaId($testShortcode);
$back = mediaIdToShortcode($id);

echo "Shortcode: $testShortcode\n";
echo "Media ID: $id\n";
echo "Back: $back\n";
echo ($testShortcode === $back ? "SUCCESS: 100% MATCH!" : "MISMATCH") . "\n";
