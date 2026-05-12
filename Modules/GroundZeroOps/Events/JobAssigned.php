<?php

namespace Modules\GroundZeroOps\Events;

class JobAssigned
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public readonly int $companyId,
        public readonly ?int $actorId,
        public readonly int $sourceId,
        public readonly array $payload,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function signalEnvelope(): array
    {
        return [
            'signal_name' => 'JobAssigned',
            'company_id' => $this->companyId,
            'actor_id' => $this->actorId,
            'source_type' => 'dispatch',
            'source_id' => $this->sourceId,
            'occurred_at' => now()->toIso8601String(),
            'payload' => $this->payload,
            'risk_level' => 'low',
        ];
    }
}
