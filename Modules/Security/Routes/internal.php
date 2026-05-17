<?php

use Illuminate\Support\Facades\Route;
use Modules\Security\Http\Controllers\API\SecurityModuleController;
use Modules\Security\Http\Controllers\Internal\SecurityStructureController;

Route::get('health', [SecurityModuleController::class, 'health'])->name('security.internal.health');

Route::get('structure', SecurityStructureController::class)->name('security.internal.structure');
