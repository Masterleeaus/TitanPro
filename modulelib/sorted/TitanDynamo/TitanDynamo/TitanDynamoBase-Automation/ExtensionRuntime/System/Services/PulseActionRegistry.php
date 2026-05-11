<?php
namespace App\Extensions\TitanPulse\System\Services;
use App\Extensions\TitanPulse\System\Services\Actions\CreateSuggestionAction;
use App\Extensions\TitanPulse\System\Services\Actions\QueuePendingActionAction;
use App\Extensions\TitanPulse\System\Services\Actions\RunAnalysisAction;
class PulseActionRegistry {
    public function all(): array { return ['create_suggestion'=>app(CreateSuggestionAction::class),'queue_pending_action'=>app(QueuePendingActionAction::class),'run_analysis'=>app(RunAnalysisAction::class)]; }
    public function get(string $actionType): ?object { return $this->all()[$actionType] ?? null; }
}
