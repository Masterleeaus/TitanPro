<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support;

class FederationHandshake
{
    public static function build(array $payload = []): array
    {
        $meta = NodeTrust::metadata($payload);

        return [
            'signal_key' => config('titan_operator.zero.federation.handshake_signal', 'zero.node.handshake'),
            'node_id' => $meta['node_id'] ?: 'unknown-node',
            'node_origin' => $meta['node_origin'],
            'node_trust' => $meta['node_trust'],
            'supported_origins' => config('titan_operator.zero.federation.supported_origins', ['server']),
        ];
    }
}
