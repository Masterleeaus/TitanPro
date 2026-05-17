<?php

namespace Modules\ZeroPayModule\Events;

use Modules\ZeroPayModule\Models\ZeroPaySession;

class ExampleCreated
{
    public function __construct(public readonly ZeroPaySession $record) {}
}
