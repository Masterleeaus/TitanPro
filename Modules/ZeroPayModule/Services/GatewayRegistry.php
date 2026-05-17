<?php

namespace Modules\ZeroPayModule\Services;

use Illuminate\Container\Container;
use Modules\ZeroPayModule\Exceptions\GatewayNotFoundException;
use Modules\ZeroPayModule\Services\Contracts\GatewayContract;

class GatewayRegistry
{
    /**
     * @var array<string, GatewayContract>
     */
    protected array $gateways = [];

    /** @var array<string, string> */
    protected const SETTING_MAP = [
        'payid' => 'gateway_payid_enabled',
        'bank_transfer' => 'gateway_bank_transfer_enabled',
        'stripe' => 'gateway_stripe_enabled',
        'paypal' => 'gateway_paypal_enabled',
        'cryptomus' => 'gateway_cryptomus_enabled',
        'cash' => 'gateway_cash_enabled',
    ];

    public function __construct(protected ?object $settings = null) {}

    public function register(string $name, GatewayContract $gateway): void
    {
        $this->gateways[strtolower($name)] = $gateway;
    }

    public function resolve(string $name): GatewayContract
    {
        $key = strtolower($name);

        if (! isset($this->gateways[$key])) {
            throw GatewayNotFoundException::forName($name);
        }

        return $this->gateways[$key];
    }

    public function isAvailable(string $gateway): bool
    {
        $key = strtolower($gateway);
        $property = self::SETTING_MAP[$key] ?? null;

        if ($property !== null && $this->settings !== null) {
            return (bool) ($this->settings->{$property} ?? false);
        }

        if (isset($this->gateways[$key])) {
            return $this->gateways[$key]->isAvailable();
        }

        return (bool) $this->configValue("zeropay-module.gateways.{$key}.enabled", false);
    }

    /**
     * @return array<int, string>
     */
    public function available(): array
    {
        $available = array_keys($this->gateways);

        if ($available === []) {
            $available = $this->all();
        }

        return array_values(array_filter($available, fn (string $name): bool => $this->isAvailable($name)));
    }

    /**
     * @return array<int, string>
     */
    public function all(): array
    {
        $names = array_unique(array_merge(array_keys(self::SETTING_MAP), array_keys($this->gateways)));
        sort($names);

        return $names;
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
}
