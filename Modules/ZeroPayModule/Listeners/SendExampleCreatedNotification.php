<?php

namespace Modules\ZeroPayModule\Listeners;

use Modules\ZeroPayModule\Events\ExampleCreated;

class SendExampleCreatedNotification
{
    public function handle(ExampleCreated $event): void {}
}
