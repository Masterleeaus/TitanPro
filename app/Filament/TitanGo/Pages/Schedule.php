<?php

namespace App\Filament\TitanGo\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Modules\TitanGoField\Models\FieldJob;

class Schedule extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanGo';

    protected static ?string $navigationLabel = 'Schedule';

    protected static ?string $title = 'Schedule';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'schedule';

    protected string $view = 'filament.titango.pages.schedule';

    protected function getViewData(): array
    {
        $user = auth()->user();
        $companyId = $user?->company_id ?? $user?->organization_id;

        if (! $user || ! $companyId || ! class_exists(FieldJob::class)) {
            return ['days' => collect(), 'calendar' => collect(), 'currentDay' => now()];
        }

        $jobs = FieldJob::query()
            ->where('company_id', $companyId)
            ->where(function ($query) use ($user) {
                $query->whereNull('technician_id')->orWhere('technician_id', $user->id);
            })
            ->whereBetween('scheduled_start', [now()->startOfDay(), now()->addDays(14)->endOfDay()])
            ->whereNotIn('status', [FieldJob::STATUS_CANCELLED])
            ->orderBy('scheduled_start')
            ->get(['id', 'reference', 'status', 'priority', 'description', 'scheduled_start', 'scheduled_end', 'technician_id']);

        return [
            'days' => $jobs->groupBy(fn (FieldJob $job): string => $job->scheduled_start?->format('D, d M') ?? 'Unscheduled'),
            'calendar' => $this->buildCalendar($jobs),
            'currentDay' => now(),
        ];
    }

    /** @param \Illuminate\Support\Collection<int, \Modules\TitanGoField\Models\FieldJob> $jobs */
    protected function buildCalendar(Collection $jobs): Collection
    {
        return collect(range(0, 13))->map(function (int $offset) use ($jobs): array {
            $day = now()->startOfDay()->addDays($offset);
            $dayJobs = $jobs->filter(fn (FieldJob $job): bool => $job->scheduled_start?->isSameDay($day));

            return [
                'date' => $day,
                'label' => $day->format('D'),
                'number' => $day->format('d'),
                'jobs_count' => $dayJobs->count(),
                'late_count' => $dayJobs->filter(fn (FieldJob $job): bool => $job->scheduled_start?->isPast() && ! $job->isCompleted())->count(),
            ];
        });
    }
}
