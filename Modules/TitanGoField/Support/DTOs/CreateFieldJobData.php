<?php

namespace Modules\TitanGoField\Support\DTOs;

class CreateFieldJobData
{
    public function __construct(
        public readonly int $companyId,
        public readonly int $actorId,
        public readonly ?int $typeId = null,
        public readonly ?int $clientId = null,
        public readonly ?int $technicianId = null,
        public readonly ?string $priority = 'normal',
        public readonly ?string $description = null,
        public readonly ?string $notes = null,
        public readonly mixed $scheduledStart = null,
        public readonly mixed $scheduledEnd = null,
        public readonly mixed $dueAt = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            companyId:     (int) $data['company_id'],
            actorId:       (int) $data['actor_id'],
            typeId:        isset($data['type_id']) ? (int) $data['type_id'] : null,
            clientId:      isset($data['client_id']) ? (int) $data['client_id'] : null,
            technicianId:  isset($data['technician_id']) ? (int) $data['technician_id'] : null,
            priority:      $data['priority'] ?? 'normal',
            description:   $data['description'] ?? null,
            notes:         $data['notes'] ?? null,
            scheduledStart: $data['scheduled_start'] ?? null,
            scheduledEnd:  $data['scheduled_end'] ?? null,
            dueAt:         $data['due_at'] ?? null,
        );
    }
}
