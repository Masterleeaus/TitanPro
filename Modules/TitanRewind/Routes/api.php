<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanRewind\Http\Controllers\Api\AuditTrailController;

Route::get('/health', static fn () => response()->json(['status' => 'titan-rewind-api-ok']))->name('health');
Route::get('/audit-trail', AuditTrailController::class)->name('audit-trail.index');
