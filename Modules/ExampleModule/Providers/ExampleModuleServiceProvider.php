<?php
namespace Modules\ExampleModule\Providers;
use Illuminate\Support\ServiceProvider;
class ExampleModuleServiceProvider extends ServiceProvider{public function register():void{$this->mergeConfigFrom(__DIR__.'/../Config/module.php','example-module');} public function boot():void{$this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');$this->loadViewsFrom(__DIR__.'/../Resources/views','example-module');$this->loadTranslationsFrom(__DIR__.'/../Resources/lang','example-module');}}
