<?php

namespace App\Filament\TitanGo\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Modules\TitanGoField\Models\FieldJob;

class CleanerDashboardWidget extends Widget
{
    protected string $view = 'filament.titango.widgets.cleaner-dashboard-widget';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 1;

    public string $locationEndpoint = '';
    public string $missionPackEndpoint = '';
    public string $helpEndpoint = '';
    public string $unsafeSiteEndpoint = '';
    public string $liveStatusEndpoint = '';
    public string $syncEndpoint = '';
    public string $copilotEndpoint = '';
    public string $cardsEndpoint = '';
    public array $stats = ['today' => 0, 'open' => 0, 'in_progress' => 0, 'due_next' => 0, 'late' => 0];
    public Collection $jobs;

    public function mount(): void
    {
        $this->locationEndpoint = $this->endpoint('titango.location.store', '/titango/location');
        $this->missionPackEndpoint = $this->endpoint('titango_field.ai.mission-pack', '/titango/field/ai/mission-pack');
        $this->helpEndpoint = $this->endpoint('titango.safety.help', '/titango/safety/help');
        $this->unsafeSiteEndpoint = $this->endpoint('titango.safety.unsafe-site', '/titango/safety/unsafe-site');
        $this->liveStatusEndpoint = $this->endpoint('titango.live-status', '/titango/live-status');
        $this->syncEndpoint = $this->endpoint('titango.sync', '/titango/sync');
        $this->copilotEndpoint = $this->endpoint('titango_field.ai.command', '/titango/field/ai/command');
        $this->cardsEndpoint = $this->endpoint('titango.runtime.cards', '/titango/runtime/cards');
        $this->jobs = collect();
        $this->refreshData();
    }

    public function refreshData(): void
    {
        $user = auth()->user();
        $companyId = $user?->company_id ?? $user?->organization_id;
        if ($user === null || $companyId === null || ! class_exists(FieldJob::class)) {
            $this->jobs = collect();
            $this->stats = ['today' => 0, 'open' => 0, 'in_progress' => 0, 'due_next' => 0, 'late' => 0];
            return;
        }
        $this->jobs = FieldJob::query()->where('company_id', $companyId)->where(function ($query) use ($user) {$query->whereNull('technician_id')->orWhere('technician_id', $user->id);})->whereNotIn('status', [FieldJob::STATUS_COMPLETED, FieldJob::STATUS_CANCELLED])->orderByRaw('scheduled_start IS NULL')->orderBy('scheduled_start')->limit(6)->get(['id', 'reference', 'status', 'priority', 'description', 'notes', 'scheduled_start', 'scheduled_end', 'technician_id']);
        $this->stats = ['today' => $this->jobs->filter(fn (FieldJob $job): bool => $job->scheduled_start?->isToday())->count(), 'open' => $this->jobs->count(), 'in_progress' => $this->jobs->where('status', FieldJob::STATUS_IN_PROGRESS)->count(), 'due_next' => $this->jobs->filter(fn (FieldJob $job): bool => $job->scheduled_start?->between(now(), now()->addHours(2)))->count(), 'late' => $this->jobs->filter(fn (FieldJob $job): bool => $job->scheduled_start?->isPast() && ! $job->isCompleted())->count()];
    }

    protected function getViewData(): array
    {
        return ['stats'=>$this->stats, 'jobs'=>$this->jobs ?? collect(), 'locationEndpoint'=>$this->locationEndpoint, 'missionPackEndpoint'=>$this->missionPackEndpoint, 'helpEndpoint'=>$this->helpEndpoint, 'unsafeSiteEndpoint'=>$this->unsafeSiteEndpoint, 'liveStatusEndpoint'=>$this->liveStatusEndpoint, 'syncEndpoint'=>$this->syncEndpoint, 'copilotEndpoint'=>$this->copilotEndpoint, 'cardsEndpoint'=>$this->cardsEndpoint];
    }

    protected function endpoint(string $name, string $fallback): string
    {
        return Route::has($name) ? route($name, absolute: false) : url($fallback);
    }
}
