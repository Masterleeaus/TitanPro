<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix(config('calling-agent.routes.admin_prefix', 'admin/calling-agent'))
    ->name('admin.calling-agent.')
    ->group(function () {
        // Admin routes can be added here as needed.
    });