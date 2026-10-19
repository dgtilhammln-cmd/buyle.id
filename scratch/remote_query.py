import paramiko
import sys

HOST = '46.202.186.86'
PORT = 65002
USER = 'u664715641'
PASS = 'Ilhammaulana23'

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

transport = paramiko.Transport((HOST, PORT))
try:
    transport.connect()
    try:
        transport.auth_password(USER, PASS)
    except:
        def handler(title, instructions, prompts):
            return [PASS for _ in prompts]
        transport.auth_interactive(USER, handler)

    client = paramiko.SSHClient()
    client._transport = transport

    cmd = "cd domains/buyle.id/public_html && php -r \"" + php_code.replace('"', '\\"').replace('\n', ' ') + "\""
    stdin, stdout, stderr = client.exec_command(cmd, timeout=30)
    out = stdout.read().decode('utf-8', errors='ignore')
    err = stderr.read().decode('utf-8', errors='ignore')
    print("OUTPUT:\n" + out)
    if err:
        print("ERR:\n" + err)
finally:
    transport.close()
