<?php
use Illuminate\Support\Facades\Route;use Modules\TitanNexus\Http\Controllers\Admin\ExampleAdminController;
Route::get('/dashboard',[ExampleAdminController::class,'dashboard'])->name('dashboard');
