<?php
namespace Modules\ExampleModule\Providers;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;use Modules\ExampleModule\Models\ExampleRecord;use Modules\ExampleModule\Policies\ExampleRecordPolicy;
class AuthServiceProvider extends ServiceProvider{protected $policies=[ExampleRecord::class=>ExampleRecordPolicy::class];}
