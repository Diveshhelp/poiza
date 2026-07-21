<?php

namespace App\Providers;

use App\Services\SmtpService;
use Illuminate\Support\ServiceProvider;

class SmtpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SmtpService::class, function ($app) {
            return new SmtpService();
        });
    }

    public function boot(): void
    {
        try {
            $smtpService = $this->app->make(SmtpService::class);
            $smtpService->loadSettings();
        } catch (\Exception $e) {
            // Log the error but continue
            logger()->error('Failed to load SMTP settings: ' . $e->getMessage());
        }
    }
}
