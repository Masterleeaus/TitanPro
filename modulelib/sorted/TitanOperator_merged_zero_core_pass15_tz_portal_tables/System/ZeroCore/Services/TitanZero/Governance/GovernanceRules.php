<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Governance;

class GovernanceRules
{
    public function evaluate(array $resolved, array $payload = []): array
    {
        $requestedState = data_get($resolved, 'signal.state', 'signal');
        $nodeTrust = data_get($resolved, 'signal.trust_weight', 'standard');
        $risk = strtolower((string) data_get($payload, 'risk', data_get($resolved, 'risk', 'low')));

        $allowed = ! in_array($risk, ['critical'], true);
        $zeroApproval = $allowed ? 'approved' : 'deferred';
        $bosApproval = $nodeTrust === 'restricted' ? 'review_required' : 'approved';

        $auditEvent = $allowed ? 'zero.signal.processing' : 'zero.signal.rejected';

        return [
            'requested_state' => $requestedState,
            'zero_approval' => $zeroApproval,
            'bos_approval' => $bosApproval,
            'next_state' => $allowed ? 'processing' : 'signal',
            'final_state' => $allowed && $bosApproval === 'approved' ? 'processed' : 'processing',
            'execution' => 'none',
            'audit_event' => $auditEvent,
            'notes' => [
                'Titan Zero governs signals; it does not execute domain actions.',
                'Processed requires downstream approval after Zero stabilization.',
            ],
        ];
    }
}
