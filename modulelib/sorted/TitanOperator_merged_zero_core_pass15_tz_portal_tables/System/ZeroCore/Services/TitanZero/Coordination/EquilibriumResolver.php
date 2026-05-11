<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Coordination;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support\FederationHandshake;

class EquilibriumResolver
{
    public function resolve(array $coordination, array $envelope, array $payload = []): array
    {
        $risk = $coordination['risk'] ?? 'low';
        $baseline = $coordination['baseline'] ?? [];
        $dominantCore = $this->dominantCore($coordination['weights'] ?? []);

        $handshake = FederationHandshake::build($payload);

        return [
            'equilibrium' => [
                'dominant_core' => $dominantCore,
                'personality_constant' => 'titan_zero',
                'mode' => $risk === 'critical' ? 'defer' : 'stabilize',
            ],
            'signal' => [
                'state' => 'signal',
                'approval_path' => ['zero', 'bos', 'next_core'],
                'node_origin' => data_get($payload, 'node_origin', 'server'),
                'trust_weight' => data_get($payload, 'node_trust', 'standard'),
                'registry_key' => 'zero.proposal.created',
                'registry_stage' => 'signal',
                'handshake' => $handshake,
            ],
            'narrative' => $baseline['narrative'] ?? 'Titan Zero stabilized the candidate outputs into one governed signal.',
            'visualisation' => $baseline['visualisation'] ?? [],
            'financial' => $baseline['financial'] ?? [],
        ];
    }

    protected function dominantCore(array $weights): string
    {
        if ($weights === []) {
            return 'logic';
        }

        arsort($weights);

        return (string) array_key_first($weights);
    }
}
