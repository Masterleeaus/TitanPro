<?php

namespace App\Filament\TitanGo\Widgets;

use App\Models\Job;
use Filament\Widgets\Widget;

class ActiveJobsWidget extends Widget
{
    protected string $view = 'filament.titango.widgets.active-jobs-widget';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 1,
    ];

    protected static ?int $sort = 3;

    protected function getViewData(): array
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return ['jobs' => collect()];
        }

        $jobs = Job::query()
            ->where('organization_id', $organizationId)
            ->whereIn('status', [
                Job::STATUS_SCHEDULED,
                Job::STATUS_ASSIGNED,
                Job::STATUS_EN_ROUTE,
                Job::STATUS_IN_PROGRESS,
            ])
            ->with(['assignedTechnician:id,name', 'customer:id,first_name,last_name,business_name'])
            ->orderBy('scheduled_at')
            ->limit(15)
            ->get(['id', 'title', 'status', 'assigned_to', 'customer_id', 'scheduled_at']);

        return ['jobs' => $jobs];
    }
}
