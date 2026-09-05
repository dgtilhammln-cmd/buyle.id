<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;
use App\Services\MailConfigService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

// Set DB settings to port 465 SSL
Setting::set('mail_host', 'smtp.hostinger.com');
Setting::set('mail_port', '465');
Setting::set('mail_username', 'hai@buyle.id');
Setting::set('mail_password', '#Ilhammaulana23');
Setting::set('mail_encryption', 'ssl');

MailConfigService::apply();

echo "--- Testing MailConfigService Port 465 SSL ---\n";
echo "Default Mailer: " . Config::get('mail.default') . "\n";
echo "Host: " . Config::get('mail.mailers.smtp.host') . "\n";
echo "Port: " . Config::get('mail.mailers.smtp.port') . "\n";
echo "Username: " . Config::get('mail.mailers.smtp.username') . "\n";
echo "Scheme: " . Config::get('mail.mailers.smtp.scheme') . "\n";
echo "Encryption: " . Config::get('mail.mailers.smtp.encryption') . "\n";

try {
    Mail::raw("Tes Email dari Buyle.id via Hostinger SMTP (Port 465 SSL)", function ($message) {
        $message->to("dgtilhammln@gmail.com")
                ->subject("✅ Tes Email SMTP Hostinger Port 465");
    });
    echo "\nSUCCESS: Email sent successfully via Port 465 SSL!\n";
} catch (\Throwable $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
