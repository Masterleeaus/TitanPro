<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Registry;

class SurfaceRegistry
{
    public function all(): array
    {
        return [
            'boss' => [
                'key' => 'boss',
                'label' => 'Boss Surface',
                'route' => 'dashboard.user.titan-runtime.boss',
                'view' => 'default.panel.user.titanzero.index',
                'hub' => 'work',
            ],
            'go' => [
                'key' => 'go',
                'label' => 'Go Surface',
                'route' => 'dashboard.user.titan-runtime.go',
                'view' => 'default.panel.user.titanzero.partials.pwa-panel',
                'hub' => 'field',
            ],
            'dispatch' => [
                'key' => 'dispatch',
                'label' => 'Dispatch Surface',
                'route' => 'dashboard.user.titan-runtime.dispatch',
                'view' => 'default.panel.user.titanzero.dispatch',
                'hub' => 'dispatch',
            ],
            'qc' => [
                'key' => 'qc',
                'label' => 'QC Surface',
                'route' => 'dashboard.user.titan-runtime.qc',
                'view' => 'default.panel.user.titanzero.qc',
                'hub' => 'qc',
            ],
        ];
    }

    public function get(string $key): array
    {
        return $this->all()[$key] ?? $this->all()['boss'];
    }
}
