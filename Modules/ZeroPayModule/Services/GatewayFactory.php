<?php

namespace Modules\ZeroPayModule\Services;

use Modules\ZeroPayModule\Services\Contracts\GatewayContract;

class GatewayFactory
{
    public function __construct(protected GatewayRegistry $registry) {}

    public function make(string $gateway): GatewayContract
    {
        return $this->registry->resolve($gateway);
    }

    public function supported(): array
    {
        return $this->registry->available();
    }
}
