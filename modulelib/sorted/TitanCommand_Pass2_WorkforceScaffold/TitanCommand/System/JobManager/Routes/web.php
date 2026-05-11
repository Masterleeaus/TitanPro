<?php

namespace App\Extensions\TitanCommand\System\JobManager\Routes;

use Illuminate\Support\Facades\Route;





Route::prefix('jobmanager')->name('jobmanager.')->group(function () {
    Route::get('/', fn () => response('JobManager routes OK', 200))->name('index');
});
