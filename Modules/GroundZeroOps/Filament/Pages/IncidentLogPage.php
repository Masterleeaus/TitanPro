<?php

namespace Modules\GroundZeroOps\Filament\Pages;

use Filament\Pages\Page;
use Modules\GroundZeroOps\Actions\LogIncidentAction;

class IncidentLogPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationLabel = 'Incident Log';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 40;
    protected static string $view = 'groundzeroops::pages.incidentlogpage';

    public function getTitle(): string
    {
        return 'Incident Log';
    }

    /**
     * @param  array<string, mixed>  $details
     */
    public function logIncident(array $details, ?int $jobId = null, string $severity = 'medium'): void
    {
        app(LogIncidentAction::class)->execute(
            reportedBy: (int) (auth()->id() ?? 0),
            details: $details,
            jobId: $jobId,
            severity: $severity,
            actorId: (int) (auth()->id() ?? 0),
            companyId: (int) (auth()->user()?->company_id ?? 0),
        );
    }
}
