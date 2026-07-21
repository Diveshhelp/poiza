<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExcludeTestingDomains implements ValidationRule
{
    protected array $testingDomains = [
       // Temporary email services
        'mailinator.com',
        'mailinator.net',
        'mailtrap.io',
        'yopmail.com',
        'temp-mail.org',
        'guerrillamail.com',
        'mailnesia.com',
        'fakeinbox.com',
        'sharklasers.com',
        'getairmail.com',
        '10minutemail.com',
        'tempmail.net',
        'tempinbox.com',
        'throwawaymail.com',
        'getnada.com',
        'mailsac.com',
        'inboxkitten.com',
        'testmail.app',
        
        // Development domains
        'test.example.com',
        'dev.example.com',
        'staging.example.com',
        'sandbox.example.com',
        'fake.email',
        'dummy.com',
        
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domain = explode('@', $value)[1] ?? '';
        
        if (in_array($domain, $this->testingDomains)) {
            $fail('The :attribute cannot use a testing email domain.');
        }
    }
}