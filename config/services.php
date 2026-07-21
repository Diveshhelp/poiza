<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'claude_ai' => [
        'api_key' => env('CLAUDE_AI_API_KEY'),
        'api_url' => env('CLAUDE_API_ENDPOINT', 'https://api.anthropic.com/v1/messages'),
    ],

    'shoppingscraper' => [
        'api_key' => env('SHOPPINGSCRAPER_API_KEY'),
        'api_endpoint'=> env('SHOPPINGSCRAPER_API_ENDPOINT'),
    ],
    'dropbox' => [
        'token' => env('DROPBOX_TOKEN'),
        'app_key' => env('DROPBOX_APP_KEY'),
        'app_secret' => env('DROPBOX_APP_SECRET'),
        'refresh_token' => env('DROPBOX_REFRESH_TOKEN'),
        'client_id'=>env('DROPBOX_APP_KEY'),
        'redirect_url'=>env('DROPBOX_REDIRECT_URL')
    ],

];
