<?php

namespace App\Filament\GroundZero\Pages;

use App\Models\Job;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class CalendarPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';

    protected static ?string $navigationLabel = 'Calendar';

    protected static ?string $title = 'Job Calendar';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.groundzero.pages.calendar';

    public function getUpcomingJobs(): Collection
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return collect();
        }

        return Job::query()
            ->where('organization_id', $organizationId)
            ->whereNotIn('status', [Job::STATUS_CANCELLED, Job::STATUS_PAID])
            ->where('scheduled_at', '>=', now()->startOfDay())
            ->where('scheduled_at', '<=', now()->addDays(30))
            ->with(['customer', 'assignedTechnician', 'jobType'])
            ->orderBy('scheduled_at')
            ->get();
    }
}
