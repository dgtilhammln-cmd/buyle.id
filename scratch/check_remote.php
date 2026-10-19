<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "LIVE_APP_ID: " . App\Services\MetaConfigService::getAppId() . "\n";
echo "LIVE_APP_SECRET: " . substr(App\Services\MetaConfigService::getAppSecret(), 0, 6) . "...\n";
foreach(App\Models\InstagramAccount::all() as $a) {
    echo "ACC: user=" . $a->username . " | token_len=" . strlen($a->access_token) . " | token_prefix=" . substr($a->access_token, 0, 10) . " | page=" . $a->page_id . " | ig=" . $a->instagram_business_id . "\n";
}
foreach(App\Models\CampaignLog::latest()->take(3)->get() as $l) {
    echo "LOG: status=" . $l->status . " | err=" . $l->error_message . " | comment=" . $l->comment_text . "\n";
}
