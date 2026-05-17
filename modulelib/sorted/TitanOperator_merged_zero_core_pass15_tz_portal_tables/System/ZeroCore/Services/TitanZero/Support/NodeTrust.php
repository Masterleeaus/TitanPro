<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support;

class NodeTrust
{
    public static function normalize(?string $trust): string
    {
        $trust = strtolower(trim((string) $trust));

        return match ($trust) {
            'high', 'trusted' => 'trusted',
            'restricted', 'low' => 'restricted',
            default => 'standard',
        };
    }

    public static function metadata(array $payload = []): array
    {
        return [
            'node_id' => data_get($payload, 'node_id'),
            'node_origin' => data_get($payload, 'node_origin', 'server'),
            'node_trust' => self::normalize(data_get($payload, 'node_trust')),
        ];
    }
}
