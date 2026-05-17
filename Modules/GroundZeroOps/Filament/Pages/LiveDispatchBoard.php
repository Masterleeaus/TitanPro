<?php

namespace Modules\GroundZeroOps\Filament\Pages;

use Filament\Pages\Page;
use Modules\GroundZeroOps\Actions\AssignJobAction;

class LiveDispatchBoard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Live Dispatch Board';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'groundzeroops::pages.livedispatchboard';

    public function getTitle(): string
    {
        return 'Live Dispatch Board';
    }

    public function assignJob(int $jobId, int $technicianId): void
    {
        app(AssignJobAction::class)->execute(
            jobId: $jobId,
            technicianId: $technicianId,
            actorId: (int) (auth()->id() ?? 0),
            companyId: (int) (auth()->user()?->company_id ?? 0),
        );
    }
}
