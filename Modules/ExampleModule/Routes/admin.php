<?php
use Illuminate\Support\Facades\Route;use Modules\ExampleModule\Http\Controllers\Admin\ExampleAdminController;
Route::get('/dashboard',[ExampleAdminController::class,'dashboard'])->name('dashboard');
