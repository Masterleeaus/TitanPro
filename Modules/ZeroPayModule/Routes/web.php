<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ZeroPayModule Web Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and use the "web"
| middleware group. Add module-specific web routes here.
|
*/

Route::get('/', function () {
    return view('zeropay-module::index');
})->name('index');
