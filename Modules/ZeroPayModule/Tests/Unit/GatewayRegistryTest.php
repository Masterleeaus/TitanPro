<?php

namespace Modules\ZeroPayModule\Tests\Unit;

use Modules\ZeroPayModule\Adapters\DefaultGatewayAdapter;
use Modules\ZeroPayModule\Exceptions\GatewayNotFoundException;
use Modules\ZeroPayModule\Services\GatewayRegistry;
use PHPUnit\Framework\TestCase;

class GatewayRegistryTest extends TestCase
{
    public function test_it_resolves_registered_gateway_by_name(): void
    {
        $registry = new GatewayRegistry;
        $adapter = new DefaultGatewayAdapter;
        $registry->register('default', $adapter);

        $this->assertSame($adapter, $registry->resolve('default'));
    }

    public function test_it_lists_only_available_gateways(): void
    {
        $registry = new GatewayRegistry;
        $registry->register('default', new DefaultGatewayAdapter);

        $this->assertSame(['default'], $registry->available());
    }

    public function test_it_throws_for_unknown_gateway_name(): void
    {
        $registry = new GatewayRegistry;

        $this->expectException(GatewayNotFoundException::class);
        $this->expectExceptionMessage('Gateway adapter not found: missing-gateway');

        $registry->resolve('missing-gateway');
    }
}
