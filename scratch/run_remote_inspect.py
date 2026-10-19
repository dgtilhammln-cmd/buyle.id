import subprocess
import os

php_code = """
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();

echo "=== CAMPAIGNS ===\\n";
foreach(App\\Models\\AutomationCampaign::all() as $c) {
    echo "ID: {$c->id} | Name: {$c->name} | TargetType: {$c->target_type} | TargetMediaID: '{$c->target_media_id}' | Active: {$c->is_active}\\n";
    echo "Keywords: " . json_encode($c->keywords) . "\\n";
    echo "----------------------------------------\\n";
}

echo "\\n=== RECENT LOGS ===\\n";
foreach(App\\Models\\CampaignLog::latest()->take(10)->get() as $l) {
    echo "ID: {$l->id} | CampID: {$l->campaign_id} | Sender: @{$l->sender_username} | MediaID: '{$l->media_id}' | Comment: '{$l->comment_text}' | Status: {$l->status}\\n";
    echo "PublicReply: '{$l->public_reply_sent}'\\n";
    echo "Error: {$l->error_message}\\n";
    echo "----------------------------------------\\n";
}
"""

cmd = [
    os.path.abspath('plink.exe'),
    '-hostkey', 'SHA256:LeBsjJUGP0sny5IZPPNzyPZ+8B5EgCqYMGYVifqicZE',
    '-P', '65002',
    '-pw', 'Ilhammaulana23',
    'u664715641@46.202.186.86',
    'cd domains/buyle.id/public_html && php'
]

p = subprocess.Popen(cmd, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
stdout, stderr = p.communicate(input=php_code, timeout=20)

print("EXIT:", p.returncode)
print("STDOUT:\n", stdout)
if stderr:
    print("STDERR:\n", stderr)
