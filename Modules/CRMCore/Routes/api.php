<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', static fn () => response()->json(['status' => 'crmcore-api-ok']))
    ->name('health');
