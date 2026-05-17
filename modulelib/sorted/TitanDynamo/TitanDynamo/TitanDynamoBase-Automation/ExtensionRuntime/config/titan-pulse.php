<?php

return [
    'enabled' => env('TITAN_PULSE_ENABLED', true),
    'default_limit' => (int) env('TITAN_PULSE_DEFAULT_LIMIT', 200),
    'schedule_every_minutes' => (int) env('TITAN_PULSE_SCHEDULE_MINUTES', 5),
    'cooldown_seconds_default' => (int) env('TITAN_PULSE_COOLDOWN_SECONDS', 300),
    'built_in_packs' => [
        'CleaningOpsPack',
        'QualityRetentionPack',
        'MarketingPack',
        'FinancePack',
        'TrustPack',
    ],
];
