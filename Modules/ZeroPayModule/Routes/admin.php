<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ZeroPayModule Admin Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and are prefixed
| with /admin/zeropay. Add module-specific admin routes here.
|
*/

Route::get('/', function () {
    return view('zeropay-module::admin.index');
})->name('index');
