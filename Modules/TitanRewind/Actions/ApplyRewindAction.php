<?php

namespace Modules\TitanRewind\Actions;

use Illuminate\Support\Facades\Event;
use Modules\TitanRewind\Events\RewindApplied;
use Modules\TitanRewind\Models\RewindEvent;
use Modules\TitanRewind\Models\RewindFix;
use Modules\TitanRewind\Services\RewindAuditService;
use Modules\TitanRewind\Services\RewindFixService;

class ApplyRewindAction
{
    public function __construct(
        private readonly RewindFixService $rewindFixService,
        private readonly RewindAuditService $rewindAuditService,
    ) {}

    public function execute(RewindFix $fix, array $actor = []): RewindFix
    {
        $this->assertActorBelongsToFixCompany($fix, $actor);
        $this->assertApprovalGate($fix);

        $applied = $this->rewindFixService->applyFix($fix, [
            'type' => (string) ($actor['type'] ?? 'user'),
            'id' => $actor['id'] ?? null,
        ]);

        $this->rewindAuditService->appendEvent([
            'company_id' => $applied->company_id,
            'case' => $applied->rewindCase,
            'title' => 'Rewind applied',
            'severity' => $applied->status === 'applied' ? 'high' : 'medium',
            'source_type' => 'rewind_action',
            'source_id' => (string) $applied->id,
            'event_type' => 'rewind_applied',
            'entity_type' => RewindFix::class,
            'entity_id' => (string) $applied->id,
            'actor_type' => (string) ($actor['type'] ?? 'user'),
            'actor_id' => $actor['id'] ?? null,
            'payload_json' => [
                'fix_id' => $applied->id,
                'status' => $applied->status,
                'result' => $applied->result_json,
            ],
            'created_at' => now(),
        ]);

        Event::dispatch(new RewindApplied($applied, $actor));

        return $applied;
    }

    private function assertApprovalGate(RewindFix $fix): void
    {
        $approved = RewindEvent::query()
            ->where('company_id', $fix->company_id)
            ->where('case_id', $fix->case_id)
            ->where('event_type', 'rewind_approved')
            ->exists();

        if (! $approved) {
            throw new \RuntimeException('ApplyRewindAction requires a prior RewindApproved event.');
        }
    }

    private function assertActorBelongsToFixCompany(RewindFix $fix, array $actor): void
    {
        $actorCompanyId = $actor['company_id'] ?? auth()->user()?->company_id;

        if ($actorCompanyId === null || (int) $actorCompanyId !== (int) $fix->company_id) {
            throw new \RuntimeException('Actor company mismatch for rewind apply.');
        }
    }
}
