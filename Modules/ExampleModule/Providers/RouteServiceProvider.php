<?php
namespace Modules\ExampleModule\Providers;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;use Illuminate\Support\Facades\Route;
class RouteServiceProvider extends ServiceProvider{public function boot():void{$this->routes(function(){Route::middleware(['web','auth'])->prefix('example-module')->name('example-module.')->group(__DIR__.'/../Routes/web.php');Route::middleware(['api','auth:sanctum'])->prefix('api/example-module')->name('api.example-module.')->group(__DIR__.'/../Routes/api.php');Route::middleware(['web','auth'])->prefix('admin/example-module')->name('admin.example-module.')->group(__DIR__.'/../Routes/admin.php');});}}
