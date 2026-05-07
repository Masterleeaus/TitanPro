<?php

/*
|--------------------------------------------------------------------------
| Billing Meters & Plan Limits
|--------------------------------------------------------------------------
|
| Define per-meter plan limits here. A null value means unlimited.
| meter_key => [ plan => limit_count | null ]
|
| Usage:
|   Route::post('/jobs', ...)->middleware('titan.billing.limit:cleaning_jobs');
|
*/

return [

    'meters' => [

        'cleaning_jobs' => [
            'limits' => [
                'starter' => 250,
                'growth'  => 1000,
                'pro'     => null,
            ],
        ],

        'voice_seconds' => [
            'limits' => [
                'starter' => null,
                'growth'  => null,
                'pro'     => null,
            ],
        ],

        'campaigns' => [
            'limits' => [
                'starter' => 5,
                'growth'  => 25,
                'pro'     => null,
            ],
        ],

        'messages_per_month' => [
            'limits' => [
                'starter' => 1000,
                'growth'  => 10000,
                'pro'     => null,
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Approaching Threshold
    |--------------------------------------------------------------------------
    |
    | Decimal fraction (0.0–1.0) of the limit at which UsageLimitApproaching
    | is fired. 0.8 means the event fires when usage reaches 80% of the limit.
    |
    */
    'approaching_threshold' => 0.8,

];
