<?php

namespace Modules\TitanRewind\Actions;

use Illuminate\Support\Facades\Event;
use Modules\TitanRewind\Events\RewindApproved;
use Modules\TitanRewind\Models\RewindFix;
use Modules\TitanRewind\Services\RewindAuditService;
use Modules\TitanRewind\Services\RewindFixService;

class ApproveRewindAction
{
    public function __construct(
        private readonly RewindFixService $rewindFixService,
        private readonly RewindAuditService $rewindAuditService,
    ) {}

    public function execute(RewindFix $fix, array $actor = []): RewindFix
    {
        $this->assertActorBelongsToFixCompany($fix, $actor);

        $approved = $this->rewindFixService->confirmFix($fix, [
            'type' => (string) ($actor['type'] ?? 'user'),
            'id' => $actor['id'] ?? null,
        ]);

        $this->rewindAuditService->appendEvent([
            'company_id' => $approved->company_id,
            'case' => $approved->rewindCase,
            'title' => 'Rewind approved',
            'severity' => 'high',
            'source_type' => 'rewind_action',
            'source_id' => (string) $approved->id,
            'event_type' => 'rewind_approved',
            'entity_type' => RewindFix::class,
            'entity_id' => (string) $approved->id,
            'actor_type' => (string) ($actor['type'] ?? 'user'),
            'actor_id' => $actor['id'] ?? null,
            'payload_json' => [
                'fix_id' => $approved->id,
                'status' => $approved->status,
            ],
            'created_at' => now(),
        ]);

        Event::dispatch(new RewindApproved($approved, $actor));

        return $approved;
    }

    private function assertActorBelongsToFixCompany(RewindFix $fix, array $actor): void
    {
        $actorCompanyId = $actor['company_id'] ?? auth()->user()?->company_id;

        if ($actorCompanyId !== null && (int) $actorCompanyId !== (int) $fix->company_id) {
            throw new \RuntimeException('Actor is outside the rewind fix tenant boundary.');
        }
    }
}
