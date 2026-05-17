<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support;

class ZeroNavigation
{
    public static function items(): array
    {
        return [
            [
                'key' => 'overview',
                'label' => 'Overview',
                'route' => ZeroConfig::routeName() . 'index',
                'description' => 'Core readiness, signal envelope, and capability surface.',
            ],
            [
                'key' => 'boss',
                'label' => 'Titan Boss',
                'route' => ZeroConfig::routeName() . 'boss.index',
                'description' => 'Owner command centre for jobs, risk, evidence, and approvals.',
            ],
            [
                'key' => 'go',
                'label' => 'Titan Go',
                'route' => ZeroConfig::routeName() . 'go.index',
                'description' => 'Field execution surface for today\'s work, checklists, and voice flow.',
            ],
            [
                'key' => 'proposals',
                'label' => 'Proposals',
                'route' => ZeroConfig::routeName() . 'proposals.index',
                'description' => 'Approval-first queue for proposed actions.',
            ],
            [
                'key' => 'audit',
                'label' => 'Audit',
                'route' => ZeroConfig::routeName() . 'audit.index',
                'description' => 'Immutable breadcrumbs for Zero events and review flow.',
            ],
        ];
    }
}
