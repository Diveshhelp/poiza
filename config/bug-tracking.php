<?php

// config/bug-tracking.php
return [
    /*
    |--------------------------------------------------------------------------
    | Bug Tracking Configuration
    |--------------------------------------------------------------------------
    */

    'pagination' => [
        'per_page' => env('BUG_TRACKING_PER_PAGE', 15),
    ],

    'file_upload' => [
        'max_size' => env('BUG_TRACKING_MAX_FILE_SIZE', 5120), // KB
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'storage_disk' => env('BUG_TRACKING_STORAGE_DISK', 'public'),
        'storage_path' => 'bug-images',
    ],

    'cache' => [
        'ttl' => env('BUG_TRACKING_CACHE_TTL', 300), // seconds
        'enabled' => env('BUG_TRACKING_CACHE_ENABLED', true),
    ],

    'notifications' => [
        'enabled' => env('BUG_TRACKING_NOTIFICATIONS_ENABLED', true),
        'channels' => ['mail', 'database'],
        'events' => [
            'bug_created' => true,
            'bug_updated' => true,
            'bug_assigned' => true,
            'status_changed' => true,
            'comment_added' => true,
        ],
    ],

    'status_workflow' => [
        'Draft' => ['Ready for work'],
        'Ready for work' => ['In progress', 'Draft'],
        'In progress' => ['Attention required', 'Deployed', 'Ready for work'],
        'Attention required' => ['In progress', 'Ready for work'],
        'Deployed' => ['Done', 'Attention required'],
        'Done' => [], // Final state
    ],

    'client_status_workflow' => [
        'Created' => ['In Review'],
        'In Review' => ['In Development', 'Created'],
        'In Development' => ['In Testing', 'In Review'],
        'In Testing' => ['Done', 'Ready for check', 'In Development'],
        'Ready for check' => ['Done', 'In Development'],
        'Done' => [], // Final state
    ],
];