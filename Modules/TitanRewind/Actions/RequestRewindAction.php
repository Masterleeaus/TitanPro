<?php

namespace Modules\TitanRewind\Actions;

use Illuminate\Support\Facades\Event;
use Modules\TitanRewind\Events\RewindRequested;
use Modules\TitanRewind\Models\RewindCase;
use Modules\TitanRewind\Models\RewindFix;
use Modules\TitanRewind\Services\RewindAuditService;
use Modules\TitanRewind\Services\RewindFixService;

class RequestRewindAction
{
    public function __construct(
        private readonly RewindFixService $rewindFixService,
        private readonly RewindAuditService $rewindAuditService,
    ) {}

    public function execute(RewindCase $case, array $proposal, array $actor = []): RewindFix
    {
        $this->assertActorBelongsToCaseCompany($case->company_id, $actor);

        $fix = $this->rewindFixService->proposeFix($case, $proposal, [
            'type' => (string) ($actor['type'] ?? 'user'),
            'id' => $actor['id'] ?? null,
        ], true);

        $this->rewindAuditService->appendEvent([
            'company_id' => $case->company_id,
            'case' => $case,
            'title' => 'Rewind requested',
            'severity' => 'high',
            'source_type' => 'rewind_action',
            'source_id' => (string) $fix->id,
            'event_type' => 'rewind_requested',
            'entity_type' => RewindFix::class,
            'entity_id' => (string) $fix->id,
            'actor_type' => (string) ($actor['type'] ?? 'user'),
            'actor_id' => $actor['id'] ?? null,
            'payload_json' => [
                'fix_id' => $fix->id,
                'proposal' => $proposal,
            ],
            'created_at' => now(),
        ]);

        Event::dispatch(new RewindRequested($fix, $actor));

        return $fix;
    }

    private function assertActorBelongsToCaseCompany(int $companyId, array $actor): void
    {
        $actorCompanyId = $actor['company_id'] ?? auth()->user()?->company_id;

        if ($actorCompanyId !== null && (int) $actorCompanyId !== $companyId) {
            throw new \RuntimeException('Actor is outside the rewind case tenant boundary.');
        }
    }
}
