<?php

namespace Modules\Security\Monitoring\Health;

use Modules\Security\Contracts\Services\OperationalReadinessServiceInterface;

class SecurityReadinessProbe
{
    public function __construct(private readonly OperationalReadinessServiceInterface $readiness)
    {
    }

    public function __invoke(): array
    {
        return $this->readiness->report();
    }
}
