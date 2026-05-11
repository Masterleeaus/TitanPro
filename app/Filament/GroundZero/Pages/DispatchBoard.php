<?php

namespace App\Filament\GroundZero\Pages;

use App\Models\Job;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class DispatchBoard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';

    protected static ?string $navigationLabel = 'Dispatch Board';

    protected static ?string $title = 'Dispatch Board';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.groundzero.pages.dispatch-board';

    public function getActiveJobs(): Collection
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return collect();
        }

        return Job::query()
            ->where('organization_id', $organizationId)
            ->whereIn('status', [
                Job::STATUS_SCHEDULED,
                Job::STATUS_ASSIGNED,
                Job::STATUS_EN_ROUTE,
                Job::STATUS_ARRIVED,
                Job::STATUS_IN_PROGRESS,
                Job::STATUS_QUALITY_CHECK,
            ])
            ->with(['customer', 'assignedTechnician', 'jobType', 'property'])
            ->orderBy('scheduled_at')
            ->get();
    }

    public function getUnassignedJobs(): Collection
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return collect();
        }

        return Job::query()
            ->where('organization_id', $organizationId)
            ->where('status', Job::STATUS_SCHEDULED)
            ->whereNull('assigned_to')
            ->with(['customer', 'jobType', 'property'])
            ->orderBy('scheduled_at')
            ->get();
    }

    public function getTechnicians(): Collection
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return collect();
        }

        return User::query()
            ->where('organization_id', $organizationId)
            ->whereHas('roles', fn ($q) => $q->where('name', 'technician'))
            ->orderBy('name')
            ->get();
    }
}
