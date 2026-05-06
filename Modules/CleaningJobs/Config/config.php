<?php
return [
    // Module display name for TitanWork (formerly CleaningJobs)
    'name' => 'TitanWork',
    'models' => [
        'user' => App\Models\User::class,
        'client' => App\Models\Client::class,
        'project' => App\Models\Project::class,
        'task' => App\Models\Task::class,
    ],
    'permissions' => [
        // Permissions are namespaced under titanwork for clarity
        'titanwork.view',
        'titanwork.create',
        'titanwork.update',
        'titanwork.delete',
        'titanwork.settings',
    ],
    'api_auth' => true, // wrap API routes in 'auth' middleware if true
    'webhook_url' => env('CLEANING_JOBS_WEBHOOK_URL', ''),
    'webhook_retries' => env('CLEANING_JOBS_WEBHOOK_RETRIES', 3),
    'webhook_backoff_seconds' => env('CLEANING_JOBS_WEBHOOK_BACKOFF', 5),
];

// Converted module key: titanwork; route prefix: titanwork.
