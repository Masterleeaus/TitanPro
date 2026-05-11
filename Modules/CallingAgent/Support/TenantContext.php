<?php

namespace Modules\CallingAgent\Support;

use App\Models\Organization;
use App\Tenancy\CurrentTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Tenant context helper for the CallingAgent module.
 *
 * In a multi-tenant application this class can be extended to resolve
 * the current tenant based on request context or other heuristics. The
 * default implementation returns null, allowing callers to gracefully
 * handle the absence of a tenant.
 */
class TenantContext
{
    private const REQUEST_KEY = 'calling_agent.tenant_id';

    private static ?int $fallbackTenantId = null;

    /**
     * Resolve the current tenant model or return null if none.
     *
     * @return Model|null
     */
    public static function tenant(): ?Model
    {
        $tenantId = self::id();

        if ($tenantId === null) {
            return null;
        }

        return Organization::query()->find($tenantId);
    }

    public static function id(?array $payload = null): ?int
    {
        $request = self::request();

        if ($request?->attributes->has(self::REQUEST_KEY)) {
            return self::toInt($request->attributes->get(self::REQUEST_KEY));
        }

        if ($request === null && self::$fallbackTenantId !== null) {
            return self::$fallbackTenantId;
        }

        $resolved = self::resolveFromRequest($request)
            ?? self::resolveFromPayload($payload ?? $request?->all() ?? [])
            ?? app(CurrentTenant::class)->id();

        if ($resolved !== null) {
            self::setTenantId($resolved);
        }

        return $resolved;
    }

    public static function setTenantId(?int $tenantId): void
    {
        $request = self::request();

        if ($request) {
            $request->attributes->set(self::REQUEST_KEY, $tenantId);
        }

        self::$fallbackTenantId = $tenantId;
    }

    public static function clear(): void
    {
        $request = self::request();

        if ($request) {
            $request->attributes->remove(self::REQUEST_KEY);
        }

        self::$fallbackTenantId = null;
    }

    public static function applyTo(array $attributes, ?int $tenantId = null): array
    {
        $tenantId ??= self::id($attributes);

        if ($tenantId !== null && ! array_key_exists('tenant_id', $attributes)) {
            $attributes['tenant_id'] = $tenantId;
        }

        return $attributes;
    }

    public static function scopedEventId(string $eventId, ?int $tenantId = null): string
    {
        $tenantId ??= self::id();

        return $tenantId === null ? $eventId : $tenantId.':'.$eventId;
    }

    private static function resolveFromRequest(?Request $request): ?int
    {
        if (! $request) {
            return null;
        }

        $candidates = [
            $request->user()?->organization_id,
            $request->route('tenant_id'),
            $request->route('organization_id'),
            $request->input('tenant_id'),
            $request->input('organization_id'),
            $request->header('X-Tenant-ID'),
            $request->hasSession() ? $request->session()->get('organization_id') : null,
        ];

        foreach ($candidates as $candidate) {
            $tenantId = self::toInt($candidate);

            if ($tenantId !== null) {
                return $tenantId;
            }
        }

        return null;
    }

    private static function resolveFromPayload(array $payload): ?int
    {
        foreach (['tenant_id', 'organization_id'] as $key) {
            $tenantId = self::toInt($payload[$key] ?? null);

            if ($tenantId !== null) {
                return $tenantId;
            }
        }

        foreach (['calling_agent_id', 'agent_id'] as $key) {
            $agentId = self::toInt($payload[$key] ?? null);

            if ($agentId !== null) {
                $tenantId = self::queryValue('calling_agents', 'id', $agentId, 'tenant_id');

                if ($tenantId !== null) {
                    return $tenantId;
                }
            }
        }

        foreach (['CallSid', 'call_sid', 'MessageSid', 'SmsSid', 'message_sid'] as $key) {
            $identifier = $payload[$key] ?? null;

            if (! is_string($identifier) || trim($identifier) === '') {
                continue;
            }

            foreach ([
                ['calling_agent_calls', 'call_sid'],
                ['calling_agent_messages', 'message_sid'],
            ] as [$table, $column]) {
                $tenantId = self::queryValue($table, $column, $identifier, 'tenant_id');

                if ($tenantId !== null) {
                    return $tenantId;
                }
            }
        }

        foreach (['To', 'to', 'From', 'from'] as $key) {
            $number = self::normalizeAddress($payload[$key] ?? null);

            if ($number === null) {
                continue;
            }

            foreach ([
                ['calling_agent_phone_numbers', 'number'],
                ['calling_agents', 'phone_number'],
                ['calling_agent_caller_profiles', 'phone'],
            ] as [$table, $column]) {
                $tenantId = self::queryValue($table, $column, $number, 'tenant_id');

                if ($tenantId !== null) {
                    return $tenantId;
                }
            }
        }

        return null;
    }

    private static function queryValue(string $table, string $column, mixed $value, string $select): ?int
    {
        try {
            return self::toInt(
                DB::table($table)->where($column, $value)->value($select),
            );
        } catch (\Throwable) {
            return null;
        }
    }

    private static function normalizeAddress(mixed $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        return preg_replace('/^(sms|whatsapp):/i', '', trim($value)) ?: null;
    }

    private static function toInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            return null;
        }

        return (int) $value;
    }

    private static function request(): ?Request
    {
        return app()->bound('request') ? app('request') : null;
    }
}
