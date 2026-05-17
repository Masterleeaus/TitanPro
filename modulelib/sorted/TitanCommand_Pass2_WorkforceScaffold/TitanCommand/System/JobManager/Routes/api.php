<?php

namespace App\Extensions\TitanCommand\System\JobManager\Routes;

use Illuminate\Support\Facades\Route;





Route::prefix('jobmanager')->name('jobmanager.api.')->group(function () {
    Route::get('/health', fn () => response()->json(['ok' => true]))->name('health');
});
