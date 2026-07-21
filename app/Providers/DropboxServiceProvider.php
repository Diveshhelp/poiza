<?php

namespace App\Providers;

use App\Services\DropboxTokenService;
use Illuminate\Support\ServiceProvider;
use Spatie\Dropbox\Client as DropboxClient;

class DropboxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DropboxClient::class, function ($app) {
            $tokenService = $app->make(DropboxTokenService::class);
            return new DropboxClient($tokenService->getAccessToken());
        });
    }
}