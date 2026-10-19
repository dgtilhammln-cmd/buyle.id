<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$accs = App\Models\InstagramAccount::all(['id', 'user_id', 'username', 'page_id', 'instagram_id', 'is_connected']);
echo json_encode($accs->toArray(), JSON_PRETTY_PRINT);
