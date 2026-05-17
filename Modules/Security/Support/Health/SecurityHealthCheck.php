<?php

namespace Modules\Security\Support\Health;

use Modules\Security\Support\Diagnostics\SecurityModuleDiagnostic;

class SecurityHealthCheck
{
    public function __construct(private readonly SecurityModuleDiagnostic $diagnostic)
    {
    }

    public function __invoke(): array
    {
        $report = $this->diagnostic->report();

        return [
            'name' => 'security-module',
            'status' => $report['status'],
            'checks' => [
                'tables' => $report['tables'],
                'routes' => $report['routes'],
                'config' => $report['config'],
            ],
            'recommendations' => $report['recommendations'],
        ];
    }
}
