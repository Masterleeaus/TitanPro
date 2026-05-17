<?php

namespace Modules\ZeroPayModule\Exceptions;

use InvalidArgumentException;

class GatewayNotFoundException extends InvalidArgumentException
{
    public static function forName(string $name): self
    {
        return new self("Gateway adapter not found: {$name}");
    }
}
