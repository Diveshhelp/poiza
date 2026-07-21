<?php

namespace App\Services;

use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Config;

class SmtpService
{
    public function loadSettings()
    {
        $settings = SmtpSetting::first();
        
        if ($settings) {
            Config::set('mail.mailers.smtp.host', $settings->mail_host);
            Config::set('mail.mailers.smtp.port', $settings->mail_port);
            Config::set('mail.mailers.smtp.encryption', $settings->mail_encryption);
            Config::set('mail.mailers.smtp.username', $settings->mail_username);
            Config::set('mail.mailers.smtp.password', $settings->mail_password);
            Config::set('mail.from.address', $settings->mail_from_address);
            Config::set('mail.from.name', $settings->mail_from_name);
            
            // Set default mailer to smtp
            Config::set('mail.default', 'smtp');
        }
    }
}
