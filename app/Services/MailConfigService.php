<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class MailConfigService
{
    /**
     * Apply dynamic mail configuration from database settings or defaults.
     */
    public static function apply(): void
    {
        try {
            $host       = Setting::get('mail_host', env('MAIL_HOST', 'smtp.hostinger.com'));
            $port       = (int) Setting::get('mail_port', env('MAIL_PORT', 465));
            $username   = Setting::get('mail_username', env('MAIL_USERNAME', 'hai@buyle.id'));
            $password   = Setting::get('mail_password', env('MAIL_PASSWORD', '#Ilhammaulana23'));
            $encryption = strtolower(Setting::get('mail_encryption', env('MAIL_ENCRYPTION', 'ssl')));
            $fromAddr   = Setting::get('mail_from_address', env('MAIL_FROM_ADDRESS', 'hai@buyle.id'));
            $fromName   = Setting::get('mail_from_name', env('MAIL_FROM_NAME', 'buyle.id'));
            $mailer     = Setting::get('mail_mailer', env('MAIL_MAILER', 'smtp'));

            Config::set('mail.default', $mailer);
            Config::set('mail.mailers.smtp.transport', 'smtp');
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', $port);
            Config::set('mail.mailers.smtp.username', $username);
            Config::set('mail.mailers.smtp.password', $password);
            
            if ($encryption === 'ssl' || $port == 465) {
                Config::set('mail.mailers.smtp.scheme', 'smtps');
                Config::set('mail.mailers.smtp.encryption', 'ssl');
            } else {
                Config::set('mail.mailers.smtp.scheme', null);
                Config::set('mail.mailers.smtp.encryption', $encryption);
            }

            $sslOptions = [
                'allow_self_signed' => true,
                'verify_peer'       => false,
                'verify_peer_name'  => false,
            ];

            Config::set('mail.mailers.smtp.stream', ['ssl' => $sslOptions]);
            Config::set('mail.mailers.smtp.context', ['ssl' => $sslOptions]);

            Config::set('mail.from.address', $fromAddr ?: $username);
            Config::set('mail.from.name', $fromName);

            // Purge cached mailer instance so new dynamic config takes effect immediately
            if (class_exists(\Illuminate\Support\Facades\Mail::class)) {
                try {
                    \Illuminate\Support\Facades\Mail::purge();
                } catch (\Throwable $t) {}
            }
        } catch (\Throwable $e) {
            Log::warning('MailConfigService apply failed: ' . $e->getMessage());
        }
    }

    /**
     * Update .env file with new mail settings if writable.
     */
    public static function updateEnv(array $data): void
    {
        $envFile = base_path('.env');
        if (!file_exists($envFile) || !is_writable($envFile)) {
            return;
        }

        $envContent = file_get_contents($envFile);
        $keyMap = [
            'mail_mailer'       => 'MAIL_MAILER',
            'mail_host'         => 'MAIL_HOST',
            'mail_port'         => 'MAIL_PORT',
            'mail_username'     => 'MAIL_USERNAME',
            'mail_password'     => 'MAIL_PASSWORD',
            'mail_encryption'   => 'MAIL_ENCRYPTION',
            'mail_from_address' => 'MAIL_FROM_ADDRESS',
            'mail_from_name'    => 'MAIL_FROM_NAME',
        ];

        foreach ($keyMap as $settingKey => $envKey) {
            if (isset($data[$settingKey])) {
                $val = $data[$settingKey];
                if (preg_match('/\s|#|\$|&/', $val)) {
                    $val = '"' . addslashes($val) . '"';
                }
                
                if (preg_match("/^{$envKey}=.*/m", $envContent)) {
                    $envContent = preg_replace("/^{$envKey}=.*/m", "{$envKey}={$val}", $envContent);
                } else {
                    $envContent .= "\n{$envKey}={$val}";
                }
            }
        }

        @file_put_contents($envFile, $envContent);
    }
}
