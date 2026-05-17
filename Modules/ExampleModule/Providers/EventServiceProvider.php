<?php
namespace Modules\ExampleModule\Providers;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;use Modules\ExampleModule\Events\ExampleCreated;use Modules\ExampleModule\Listeners\SendExampleCreatedNotification;
class EventServiceProvider extends ServiceProvider{protected $listen=[ExampleCreated::class=>[SendExampleCreatedNotification::class]];}
