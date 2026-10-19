<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$shortcode = 'DGWV_ZCSzK_';
$mediaId = App\Jobs\ProcessInstagramCommentJob::shortcodeToMediaId($shortcode);
$back = App\Jobs\ProcessInstagramCommentJob::mediaIdToShortcode($mediaId);

echo "Shortcode: $shortcode\n";
echo "Media ID: $mediaId\n";
echo "Back: $back\n";
echo ($shortcode === $back ? "VALIDATION PASSED!\n" : "FAIL\n");
