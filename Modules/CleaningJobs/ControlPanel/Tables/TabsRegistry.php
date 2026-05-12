<?php

namespace Modules\CleaningJobs\ControlPanel\Tables;

use Modules\CleaningJobs\ControlPanel\Tables\Providers\JobsTableProvider;
use Modules\CleaningJobs\ControlPanel\Tables\Providers\RequestsTableProvider;

class TabsRegistry
{
    public static function getTabs(): array
    {
        return [
            [
                'key' => 'jobs',
                'label' => 'Jobs',
                'provider' => JobsTableProvider::class,
            ],
            [
                'key' => 'requests',
                'label' => 'Requests',
                'provider' => RequestsTableProvider::class,
            ],
        ];
    }
}
