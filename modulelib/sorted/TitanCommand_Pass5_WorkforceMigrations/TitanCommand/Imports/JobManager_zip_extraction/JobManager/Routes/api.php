<?php

namespace Modules\JobManager\Routes;

use Illuminate\Support\Facades\Route;


namespace ModulesJobManagerRoutes;


use Illuminate\Support\Facades\Route;

Route::prefix('jobmanager')->name('jobmanager.api.')->group(function () {
    Route::get('/health', fn () => response()->json(['ok' => true]))->name('health');
});
