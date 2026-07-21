<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ean',
        'shopping_scraper_start_time',
        'shopping_scraper_response',
        'shopping_scraper_end_time',
        'manage_cache_start_time',
        'cached_shopping_result',
        'manage_cache_end_time',
        'merge_response_start_time',
        'merge_request_prompt',
        'merge_response',
        'merge_response_end_time',
        'product_type_start_time',
        'product_type_prompt',
        'product_type_response',
        'product_type_end_time',
        'ai_content_start_time',
        'ai_content_prompt',
        'ai_content_response',
        'ai_content_end_time',
        'error_message',
        'job_started_at',
        'job_completed_at',
        'product_ai_content_response_id',
        'product_ai_content_request_id',
        'product_ai_content_id',
        'source'
    ];

    // Define relationships
    public function productAiContentResponse()
    {
        return $this->belongsTo(ProductAiContentResponses::class);
    }

    public function productAiContentRequest()
    {
        return $this->belongsTo(ProductAiContentRequests::class);
    }
    public function retryLog()
    {
        return $this->hasMany(RetryErrorLog::class,  'product_ai_content_response_id','product_ai_content_response_id');
    }
}
