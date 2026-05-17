<?php

namespace Modules\ZeroPayModule\Services\Gateways;

use Illuminate\Container\Container;
use RuntimeException;

abstract class AbstractGateway
{
    public function __construct(protected ?array $config = null)
    {
        $this->config ??= $this->resolveGatewayConfig();
    }

    abstract protected function gatewayKey(): string;

    public function isAvailable(): bool
    {
        return (bool) ($this->config['enabled'] ?? false);
    }

    protected function gatewayConfig(): array
    {
        return $this->config ?? [];
    }

    protected function resolveGatewayConfig(): array
    {
        return (array) $this->configValue(
            'zeropay-module.gateways.'.$this->gatewayKey(),
            []
        );
    }

    protected function configValue(string $key, mixed $default = null): mixed
    {
        try {
            $container = Container::getInstance();

            if ($container !== null && $container->bound('config')) {
                return $container->make('config')->get($key, $default);
            }
        } catch (\Throwable) {
            return $default;
        }

        return $default;
    }

    protected function requireAvailability(): void
    {
        if (! $this->isAvailable()) {
            throw new RuntimeException(sprintf('%s gateway is disabled.', ucfirst($this->gatewayKey())));
        }
    }

    protected function amount(array $session): float
    {
        return (float) ($session['amount'] ?? 0);
    }

    protected function amountInMinorUnits(array $session): int
    {
        return (int) round($this->amount($session) * 100);
    }

    protected function currency(array $session, string $default = 'USD'): string
    {
        return strtoupper((string) ($session['currency'] ?? $default));
    }

    protected function reference(array $data, string $prefix): string
    {
        return (string) ($data['reference'] ?? $data['session_token'] ?? uniqid($prefix, true));
    }

    protected function metadata(array $session): array
    {
        $meta = $session['meta'] ?? [];

        return is_array($meta) ? $meta : [];
    }

    protected function statusFromMap(?string $value, array $map, string $default = 'pending'): string
    {
        if ($value === null) {
            return $default;
        }

        $normalized = strtoupper($value);

        return $map[$normalized] ?? $default;
    }

    protected function toArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        }

        $encoded = json_encode($value);

        if ($encoded === false) {
            return [];
        }

        $decoded = json_decode($encoded, true);

        return is_array($decoded) ? $decoded : [];
    }
}
