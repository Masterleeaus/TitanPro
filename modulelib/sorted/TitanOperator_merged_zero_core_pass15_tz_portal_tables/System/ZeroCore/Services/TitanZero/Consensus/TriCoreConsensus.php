<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Consensus;

class TriCoreConsensus
{
    public function evaluate(string $intent, array $envelope, array $options = []): array
    {
        $risk = $options['risk'] ?? $this->inferRisk($intent);
        $weights = config('titan_operator.zero.tri_core.risk_bias.' . $risk, config('titan_operator.zero.tri_core.weights', []));

        return [
            'intent' => $intent,
            'risk' => $risk,
            'weights' => $weights,
            'narrative' => $this->narrative($intent, $envelope),
            'visualisation' => $this->visualisation($intent, $envelope),
            'financial' => $this->financial($envelope),
            'decision' => $risk === 'critical' ? 'defer' : ($risk === 'high' ? 'review' : 'propose'),
        ];
    }

    protected function inferRisk(string $intent): string
    {
        $intent = strtolower($intent);

        return str_contains($intent, 'delete') || str_contains($intent, 'payment') || str_contains($intent, 'invoice')
            ? 'high'
            : (str_contains($intent, 'customer') || str_contains($intent, 'voice') ? 'medium' : 'low');
    }

    protected function narrative(string $intent, array $envelope): string
    {
        return sprintf(
            'Titan Zero sees intent "%s" with %d proposals, %d audit events, and %d rewind links in tz scope.',
            $intent,
            (int) data_get($envelope, 'governance.proposal_count', 0),
            (int) data_get($envelope, 'governance.audit_count', 0),
            (int) data_get($envelope, 'governance.rewind_count', 0),
        );
    }

    protected function visualisation(string $intent, array $envelope): array
    {
        return [
            'surface' => 'command-center',
            'cards' => [
                ['label' => 'Proposals', 'value' => data_get($envelope, 'governance.proposal_count', 0)],
                ['label' => 'Audit Events', 'value' => data_get($envelope, 'governance.audit_count', 0)],
                ['label' => 'Rewind Links', 'value' => data_get($envelope, 'governance.rewind_count', 0)],
            ],
            'intent' => $intent,
        ];
    }

    protected function financial(array $envelope): array
    {
        return [
            'decision_count' => (int) data_get($envelope, 'governance.decision_count', 0),
            'learning_delta_count' => (int) data_get($envelope, 'memory.learning_delta_count', 0),
        ];
    }
}
