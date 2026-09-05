<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\MailConfigService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

echo "--- Testing MailConfigService ---\n";
MailConfigService::apply();

echo "Default Mailer: " . Config::get('mail.default') . "\n";
echo "Host: " . Config::get('mail.mailers.smtp.host') . "\n";
echo "Port: " . Config::get('mail.mailers.smtp.port') . "\n";
echo "Username: " . Config::get('mail.mailers.smtp.username') . "\n";
echo "Scheme: " . Config::get('mail.mailers.smtp.scheme') . "\n";
echo "Encryption: " . Config::get('mail.mailers.smtp.encryption') . "\n";

try {
    Mail::purge();
    Mail::raw("Tes Email dari Buyle.id via Hostinger SMTP", function ($message) {
        $message->to("dgtilhammln@gmail.com")
                ->subject("Tes Email SMTP Hostinger");
    });
    echo "\nSUCCESS: Email sent successfully!\n";
} catch (\Throwable $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
