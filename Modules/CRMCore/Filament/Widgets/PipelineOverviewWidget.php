<?php

namespace Modules\CRMCore\Filament\Widgets;

use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\Lead;
use App\Models\Job;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PipelineOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Leads', Lead::query()->count()),
            Stat::make('Deals', Deal::query()->count()),
            Stat::make('Jobs', Job::query()->count()),
        ];
    }
}
