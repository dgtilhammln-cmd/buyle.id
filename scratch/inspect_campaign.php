<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== AUTOMATION CAMPAIGNS ===\n";
foreach (\App\Models\AutomationCampaign::all() as $c) {
    echo "ID: {$c->id} | Name: {$c->name} | TargetType: {$c->target_type} | TargetMediaID: '{$c->target_media_id}' | Active: {$c->is_active}\n";
    echo "Keywords: " . json_encode($c->keywords) . "\n";
    echo "----------------------------------------\n";
}

echo "\n=== RECENT CAMPAIGN LOGS ===\n";
foreach (\App\Models\CampaignLog::latest()->take(10)->get() as $l) {
    echo "ID: {$l->id} | CampID: {$l->campaign_id} | Sender: @{$l->sender_username} | MediaID: '{$l->media_id}' | Comment: '{$l->comment_text}' | Status: {$l->status}\n";
    echo "Error: {$l->error_message}\n";
    echo "----------------------------------------\n";
}
