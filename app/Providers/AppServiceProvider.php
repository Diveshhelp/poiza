<?php

namespace App\Providers;

use App\Models\BugMaster;
use App\Observers\BugMasterObserver;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Blade;
use App\View\Components\PrimaryButton;

class AppServiceProvider extends ServiceProvider

{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('process-eans', function ($job) {
            return Limit::perMinute(25); 
        });
        
        Blade::components([
            'primary-button' => PrimaryButton::class,
        ]);

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Important : Verify Email Address')
                ->line('Please click the button below to verify your email address.')
                ->action('Get verified', $url)
                ->line('If you did not create an account, no further action is required.');
        });

        BugMaster::observe(BugMasterObserver::class);
        
        // Blade::component('secondary-button', \App\View\Components\SecondaryButton::class);
    }
}