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
            $port       = Setting::get('mail_port', env('MAIL_PORT', 465));
            $username   = Setting::get('mail_username', env('MAIL_USERNAME', 'hai@buylee.id'));
            $password   = Setting::get('mail_password', env('MAIL_PASSWORD', '#Ilhammaulana23'));
            $encryption = Setting::get('mail_encryption', env('MAIL_ENCRYPTION', 'ssl'));
            $fromAddr   = Setting::get('mail_from_address', env('MAIL_FROM_ADDRESS', 'hai@buylee.id'));
            $fromName   = Setting::get('mail_from_name', env('MAIL_FROM_NAME', 'buyle.id'));
            $mailer     = Setting::get('mail_mailer', env('MAIL_MAILER', 'smtp'));

            Config::set('mail.default', $mailer);
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', (int) $port);
            Config::set('mail.mailers.smtp.username', $username);
            Config::set('mail.mailers.smtp.password', $password);
            Config::set('mail.mailers.smtp.encryption', $encryption);
            Config::set('mail.from.address', $fromAddr);
            Config::set('mail.from.name', $fromName);
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
