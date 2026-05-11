<?php

use App\Models\Job;
use App\Models\Payment;
use App\Models\User;
use Modules\TitanRewind\Models\RewindAction;
use Modules\TitanRewind\Models\RewindCase;
use Modules\TitanRewind\Models\RewindEvent;
use Modules\TitanRewind\Models\RewindFix;

return [
    'tracked_models' => [
        Job::class,
        Payment::class,
        User::class,
    ],
    'ignored_models' => [
        RewindCase::class,
        RewindEvent::class,
        RewindFix::class,
        RewindAction::class,
    ],
    'suggestion_similarity' => [
        'min_matches' => 1,
    ],
    'allowlisted_target_tables' => [
        'titan_rewind_cases',
    ],
];
