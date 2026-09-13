<?php
function getInstagramThumbnail($url) {
    if (!preg_match('/instagram\.com\/(?:reel|reels|p)\/([A-Za-z0-9_-]+)/i', $url, $m)) {
        return null;
    }
    $shortcode = $m[1];
    $embedUrl = "https://www.instagram.com/p/{$shortcode}/embed/captioned/";

    $ch = curl_init($embedUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Mobile/15E148 Safari/604.1');
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    $res = curl_exec($ch);
    curl_close($ch);

    if (!$res) return null;

    if (preg_match_all('#https?:\\\\?/\\\\?/[^"\'\s>]*(?:fbcdn|scontent|cdninstagram)[^"\'\s>]*\.jpg[^"\'\s>]*#i', $res, $matches)) {
        // Priority 1: Clips / Post image (t51.82787-15 or CLIPS or cover_frame or s320x320 or s640x640)
        foreach ($matches[0] as $match) {
            $clean = stripslashes(html_entity_decode($match));
            if (strpos($clean, 'profile_pic') === false && strpos($clean, 's100x100') === false) {
                if (strpos($clean, 't51.82787-15') !== false || strpos($clean, 'CLIPS') !== false || strpos($clean, 'cover_frame') !== false || strpos($clean, 's320x320') !== false || strpos($clean, 's640x640') !== false) {
                    return $clean;
                }
            }
        }
        // Priority 2: Any non-profile image
        foreach ($matches[0] as $match) {
            $clean = stripslashes(html_entity_decode($match));
            if (strpos($clean, 'profile_pic') === false && strpos($clean, 's100x100') === false && strpos($clean, 'rsrc.php') === false) {
                return $clean;
            }
        }
    }

    return null;
}

$testUrl = 'https://www.instagram.com/p/DQYz91MkZ0I/';
$thumb = getInstagramThumbnail($testUrl);
echo "RESULT THUMBNAIL: " . ($thumb ?? 'NULL') . "\n";
