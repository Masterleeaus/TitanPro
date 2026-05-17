<?php

namespace Modules\ExampleModule\Listeners;

class SendExampleCreatedNotification
{
    public function handle(\Modules\ExampleModule\Events\ExampleCreated $event): void {}
}
