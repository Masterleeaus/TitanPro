<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Registry;

class TitanToolRegistry
{
    public function all(): array
    {
        return [
            [
                'key' => 'zero_command_surface',
                'label' => 'Zero Command Surface',
                'route' => 'dashboard.user.titanzero.index',
                'group' => 'chat',
                'risk' => 'low',
                'approval_required' => false,
            ],
            [
                'key' => 'zero_signal_preview',
                'label' => 'Zero Signal Preview',
                'route' => 'dashboard.user.titanzero.api.preview',
                'group' => 'chat',
                'risk' => 'low',
                'approval_required' => false,
            ],
            [
                'key' => 'zero_voice_surface',
                'label' => 'Zero Voice Surface',
                'route' => 'dashboard.user.titanzero.api.status',
                'group' => 'voice',
                'risk' => 'medium',
                'approval_required' => true,
            ],
            [
                'key' => 'proposal_queue',
                'label' => 'Proposal Review Queue',
                'route' => 'dashboard.user.titanzero.proposals.index',
                'group' => 'governance',
                'risk' => 'low',
                'approval_required' => false,
            ],
            [
                'key' => 'rewind_guard',
                'label' => 'Rewind Guard',
                'route' => 'dashboard.user.titanzero.audit.index',
                'group' => 'governance',
                'risk' => 'medium',
                'approval_required' => true,
            ],
        ];
    }

    public function grouped(): array
    {
        return collect($this->all())->groupBy('group')->toArray();
    }
}
