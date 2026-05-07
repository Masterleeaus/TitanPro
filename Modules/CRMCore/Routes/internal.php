<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', static fn () => response('crmcore-internal-ok'))
    ->name('health');
