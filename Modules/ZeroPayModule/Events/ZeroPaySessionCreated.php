<?php

namespace Modules\ZeroPayModule\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ZeroPaySessionCreated
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  array<string, mixed>  $sessionData
     */
    public function __construct(
        public readonly array $sessionData
    ) {}
}
