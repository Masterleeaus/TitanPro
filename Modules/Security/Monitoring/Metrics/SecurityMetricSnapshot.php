<?php

namespace Modules\Security\Monitoring\Metrics;

use Modules\Security\Contracts\Services\SecurityModuleServiceInterface;

class SecurityMetricSnapshot
{
    public function __construct(private readonly SecurityModuleServiceInterface $security)
    {
    }

    public function toArray(): array
    {
        return [
            'module' => 'security',
            'captured_at' => now()->toISOString(),
            'status' => $this->security->status(),
            'health' => $this->security->health(),
        ];
    }
}
