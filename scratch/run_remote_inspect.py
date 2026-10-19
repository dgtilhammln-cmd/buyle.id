import subprocess

script = """<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();

echo "APP_ID: " . App\\Services\\MetaConfigService::getAppId() . "\n";
echo "APP_SECRET: " . substr(App\\Services\\MetaConfigService::getAppSecret(), 0, 8) . "...\n";
foreach(App\\Models\\InstagramAccount::all() as $a) {
    echo "USER: " . $a->username . " | TOKEN_PREFIX: " . substr($a->access_token, 0, 15) . "... | LEN: " . strlen($a->access_token) . " | PAGE: " . $a->page_id . " | IG_ID: " . $a->instagram_business_id . "\n";
}
foreach(App\\Models\\CampaignLog::latest()->take(5)->get() as $l) {
    echo "LOG: status=" . $l->status . " | err=" . $l->error_message . " | comment=" . $l->comment_text . "\n";
}
"""

p = subprocess.Popen(
    ['ssh', '-p', '65002', 'u664715641@46.202.186.86', 'cd domains/buyle.id/public_html && php'],
    stdin=subprocess.PIPE,
    stdout=subprocess.PIPE,
    stderr=subprocess.PIPE,
    text=True
)
out, err = p.communicate(input=script)
print("OUT:\n", out)
if err:
    print("ERR:\n", err)
