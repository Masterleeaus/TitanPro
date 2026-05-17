<?php

namespace App\Filament\TitanGo\Pages;

use Filament\Pages\Page;
use Modules\TitanGoField\Models\FieldJob;

class Jobs extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanGo';

    protected static ?string $navigationLabel = 'My Jobs';

    protected static ?string $title = 'My Jobs';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'jobs';

    protected string $view = 'filament.titango.pages.jobs';

    protected function getViewData(): array
    {
        $user = auth()->user();
        $companyId = $user?->company_id ?? $user?->organization_id;

        if (! $user || ! $companyId || ! class_exists(FieldJob::class)) {
            return ['jobs' => collect(), 'counts' => ['today' => 0, 'active' => 0, 'completed' => 0]];
        }

        $base = FieldJob::query()
            ->where('company_id', $companyId)
            ->where(function ($query) use ($user) {
                $query->whereNull('technician_id')->orWhere('technician_id', $user->id);
            });

        $activeStatuses = [FieldJob::STATUS_PENDING, FieldJob::STATUS_APPROVED, FieldJob::STATUS_SCHEDULED, FieldJob::STATUS_IN_PROGRESS, FieldJob::STATUS_ON_HOLD];

        return [
            'counts' => [
                'today' => (clone $base)->whereDate('scheduled_start', today())->count(),
                'active' => (clone $base)->whereIn('status', $activeStatuses)->count(),
                'completed' => (clone $base)->where('status', FieldJob::STATUS_COMPLETED)->count(),
            ],
            'jobs' => (clone $base)
                ->whereIn('status', $activeStatuses)
                ->orderByRaw('scheduled_start IS NULL')
                ->orderBy('scheduled_start')
                ->limit(30)
                ->get(['id', 'reference', 'status', 'priority', 'description', 'scheduled_start', 'scheduled_end']),
        ];
    }
}
