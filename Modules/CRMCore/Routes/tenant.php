<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', static fn () => response('crmcore-tenant-ok'))
    ->name('health');
