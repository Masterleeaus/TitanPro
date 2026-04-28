<?php

namespace Modules\CRMCore\DTOs;

class DealToProjectData
{
    public function __construct(
        public int $dealId,
        public ?int $clientId = null,
        public ?string $serviceInterest = null,
        public array $overrides = [],
    ) {
    }
}
