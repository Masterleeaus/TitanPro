<?php

namespace Modules\TitanRewind\Filament\Pages;

use Filament\Pages\Page;
use Modules\TitanRewind\Actions\ApplyRewindAction;
use Modules\TitanRewind\Actions\ApproveRewindAction;
use Modules\TitanRewind\Actions\RequestRewindAction;
use Modules\TitanRewind\Models\RewindCase;
use Modules\TitanRewind\Models\RewindFix;

class RewindRequestPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Rewind';

    protected static ?string $navigationLabel = 'Rewind Requests';

    protected static ?int $navigationSort = 12;

    public function requestRewind(RewindCase $case, array $proposal, array $actor = []): RewindFix
    {
        return app(RequestRewindAction::class)->execute($case, $proposal, $actor);
    }

    public function approveRequest(RewindFix $fix, array $actor = []): RewindFix
    {
        return app(ApproveRewindAction::class)->execute($fix, $actor);
    }

    public function applyApprovedRequest(RewindFix $fix, array $actor = []): RewindFix
    {
        return app(ApplyRewindAction::class)->execute($fix, $actor);
    }
}
