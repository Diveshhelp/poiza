<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait RateLimited
{
    protected function getRateLimitKey(): string
    {
        return 'claude_api_rate_limit';
    }

    protected function rateLimitAvailable(): bool
    {
        $key = $this->getRateLimitKey();
        $currentRequests = (int) Redis::get($key) ?? 0;
        
        // Adjust these values based on Claude AI's rate limits
        $maxRequests = 50; // requests
        $perSeconds = 60; // per minute
        
        if ($currentRequests >= $maxRequests) {
            return false;
        }
        
        Redis::incr($key);
        if ($currentRequests === 0) {
            Redis::expire($key, $perSeconds);
        }
        
        return true;
    }

    protected function waitForRateLimit(): void
    {
        while (!$this->rateLimitAvailable()) {
            sleep(1);
        }
    }
} 