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
        return app(RequestRewindAction::class)->execute($case, $proposal, $this->actorEnvelope($actor));
    }

    public function approveRequest(RewindFix $fix, array $actor = []): RewindFix
    {
        return app(ApproveRewindAction::class)->execute($fix, $this->actorEnvelope($actor));
    }

    public function applyApprovedRequest(RewindFix $fix, array $actor = []): RewindFix
    {
        return app(ApplyRewindAction::class)->execute($fix, $this->actorEnvelope($actor));
    }

    private function actorEnvelope(array $actor): array
    {
        $user = auth()->user();

        if ($user === null) {
            if ($actor === []) {
                throw new \RuntimeException('Authenticated actor context is required.');
            }

            return $actor;
        }

        if (isset($actor['id']) && (int) $actor['id'] !== (int) $user->id) {
            throw new \RuntimeException('Actor override is not allowed.');
        }

        if (isset($actor['company_id']) && (int) $actor['company_id'] !== (int) $user->company_id) {
            throw new \RuntimeException('Actor tenant override is not allowed.');
        }

        return [
            'type' => 'user',
            'id' => (int) $user->id,
            'company_id' => (int) $user->company_id,
        ];
    }
}
