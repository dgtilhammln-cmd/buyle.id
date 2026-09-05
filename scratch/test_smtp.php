<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Setting;
use App\Services\MailConfigService;

// Test Port 587 TLS
Setting::set('mail_mailer', 'smtp', 'text', 'email');
Setting::set('mail_host', 'smtp.hostinger.com', 'text', 'email');
Setting::set('mail_port', '587', 'text', 'email');
Setting::set('mail_username', 'hai@buyle.id', 'text', 'email');
Setting::set('mail_password', '#Ilhammaulana23', 'text', 'email');
Setting::set('mail_encryption', 'tls', 'text', 'email');
Setting::set('mail_from_address', 'hai@buyle.id', 'text', 'email');
Setting::set('mail_from_name', 'buyle.id', 'text', 'email');

MailConfigService::apply();

echo "Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Port: " . config('mail.mailers.smtp.port') . "\n";
echo "User: " . config('mail.mailers.smtp.username') . "\n";
echo "Enc:  " . config('mail.mailers.smtp.encryption') . "\n";

try {
    \Illuminate\Support\Facades\Mail::raw("Tes koneksi Hostinger Port 587 TLS", function ($m) {
        $m->to('hai@buyle.id')->subject('✅ Tes Hostinger 587 TLS');
    });
    echo "\nRESULT: SUCCESS! Email sent via Port 587 TLS!\n";
} catch (\Throwable $e) {
    echo "\nRESULT: ERROR -> " . $e->getMessage() . "\n";
}
