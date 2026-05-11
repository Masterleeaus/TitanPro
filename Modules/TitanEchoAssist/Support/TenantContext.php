<?php

namespace Modules\TitanEchoAssist\Support;

class TenantContext
{
    public static function tenantId(): int|string|null
    {
        if (function_exists('tenant') && tenant()) {
            return tenant('id') ?? (tenant()->id ?? null);
        }

        if (function_exists('auth') && auth()->check()) {
            $user = auth()->user();
            return $user->tenant_id ?? $user->team_id ?? null;
        }

        return null;
    }

    /** @param array<string, mixed> $attributes */
    public static function apply(array $attributes): array
    {
        $tenantId = self::tenantId();
        if ($tenantId !== null && ! array_key_exists('tenant_id', $attributes)) {
            $attributes['tenant_id'] = $tenantId;
        }

        return $attributes;
    }
}
