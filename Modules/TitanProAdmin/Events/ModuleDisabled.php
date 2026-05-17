<?php

namespace Modules\TitanProAdmin\Events;

class ModuleDisabled
{
    public function __construct(
        public readonly int $tenantId,
        public readonly string $moduleName,
        public readonly int|string|null $actorId = null,
    ) {}
}
