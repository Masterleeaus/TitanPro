<?php

namespace Modules\TitanProAdmin\Events;

class TenantSuspended
{
    public function __construct(
        public readonly int $tenantId,
        public readonly int|string|null $actorId = null,
    ) {}
}
