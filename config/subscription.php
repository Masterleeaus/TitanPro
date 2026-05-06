<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trial Duration
    |--------------------------------------------------------------------------
    |
    | The number of days a new organisation receives as a free trial.
    | Override via the TRIAL_DAYS environment variable without redeploying.
    |
    */

    'trial_days' => (int) env('TRIAL_DAYS', 14),

];
