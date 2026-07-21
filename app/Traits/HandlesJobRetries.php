<?php

namespace App\Traits;

use App\Models\ProductAiContentItem;
use Illuminate\Support\Facades\Log;
use Throwable;

trait HandlesJobRetries
{
    protected function shouldRetryJob(Throwable $e): bool
    {
        // Always capture exception in Sentry, even if we're going to retry
        \Sentry\captureException($e);
        
        // Log detailed information about the retry attempt
        Log::error("Job failed and evaluating retry", [
            'job' => get_class($this),
            'attempt' => $this->attempts(),
            'max_tries' => $this->tries,
            'error' => $e->getMessage(),
            'stack_trace' => $e->getTraceAsString(),
            'product_ai_content_response_id' => $this->productAiContentResponseId ?? null
        ]);

        // Check if we already have a successful response
        $existingResponse = ProductAiContentItem::where('product_ai_content_response_id', $this->productAiContentResponseId)
            ->where(function($query) {
                $query->where('xml_data', '!=', '')
                      ->orWhereNotNull('response')
                      ->whereNotIn('response', ['', '[]', 'null']);
            })
            ->first();

        if ($existingResponse) {
            Log::info("Skipping retry as successful response already exists", [
                'job' => get_class($this),
                'product_ai_content_response_id' => $this->productAiContentResponseId,
                'response_type' => !empty($existingResponse->xml_data) ? 'xml_data' : 'response'
            ]);
            return false;
        }

        // Define retryable exceptions
        $retryableExceptions = [
            'Rate limit exceeded',
            'Service temporarily unavailable',
            'Connection timed out',
            'Gateway timeout',
            'Too many requests',
            'Overloaded',
            'Operation timed out',
            'Cache data missing', // Added to handle cache failures
            'anthropic-api' // Added to catch Claude API specific errors
        ];

        // Check if the error is retryable
        $shouldRetry = false;
        foreach ($retryableExceptions as $retryableError) {
            if (stripos($e->getMessage(), $retryableError) !== false) {
                $shouldRetry = true;
                break;
            }
        }

        // Only retry if we haven't exceeded max attempts
        if ($shouldRetry && $this->attempts() < $this->tries) {
            Log::info("Retrying job", [
                'job' => get_class($this),
                'attempt' => $this->attempts(),
                'max_tries' => $this->tries,
                'product_ai_content_response_id' => $this->productAiContentResponseId,
                'error' => $e->getMessage(),
                'next_retry_delay' => $this->getBackoffTime()
            ]);
            return true;
        }

        return false;
    }

    protected function getBackoffTime(): int
    {
        $attempt = $this->attempts();
        return $this->backoff[$attempt - 1] ?? end($this->backoff);
    }
} 