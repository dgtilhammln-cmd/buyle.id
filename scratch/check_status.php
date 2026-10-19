<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== AUTOMATION CAMPAIGNS ===\n";
foreach (\App\Models\AutomationCampaign::all() as $c) {
    echo "ID: {$c->id} | Name: {$c->name} | Target: {$c->target_type} | Media: {$c->target_media_id} | Keywords: {$c->trigger_keywords} | Active: {$c->is_active}\n";
}

echo "\n=== INSTAGRAM ACCOUNTS ===\n";
foreach (\App\Models\InstagramAccount::all() as $a) {
    echo "ID: {$a->id} | User: {$a->username} | IG ID: {$a->instagram_business_id} | Page ID: {$a->page_id} | Connected: {$a->is_connected}\n";
    echo "Token preview: " . substr($a->access_token, 0, 15) . "...\n";
}

echo "\n=== LATEST CAMPAIGN LOGS ===\n";
foreach (\App\Models\CampaignLog::latest()->take(10)->get() as $l) {
    echo "Time: {$l->created_at} | Sender: @{$l->sender_username} | Status: {$l->status} | Error: {$l->error_message} | Comment: {$l->comment_text}\n";
}
