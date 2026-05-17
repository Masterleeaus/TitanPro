<?php

namespace Modules\GroundZeroOps\Support\DTOs;

class DispatchSuggestion
{
    public function __construct(
        public readonly int $technicianId,
        public readonly int $openDispatchCount,
        public readonly int $rank,
    ) {}

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'technician_id' => $this->technicianId,
            'open_dispatch_count' => $this->openDispatchCount,
            'rank' => $this->rank,
        ];
    }
}
