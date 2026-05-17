<?php

use App\Http\Controllers\Technician\JobController as TechnicianJobController;
use App\Http\Controllers\Technician\LocationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::middleware(['auth', 'role:technician'])
    ->prefix('technician')
    ->name('technician.')
    ->group(function () {
        Route::get('/jobs/today', [TechnicianJobController::class, 'today'])
            ->name('jobs.today');
        Route::get('/jobs/{job}', [TechnicianJobController::class, 'apiShow'])
            ->name('jobs.show');
        Route::patch('/jobs/{job}/status', [TechnicianJobController::class, 'updateStatus'])
            ->name('jobs.status');
        Route::patch('/jobs/{job}/notes', [TechnicianJobController::class, 'updateNotes'])
            ->name('jobs.notes');
        Route::patch('/jobs/{job}/customer-notes', [TechnicianJobController::class, 'updateCustomerNotes'])
            ->name('jobs.customer-notes');
        Route::patch('/jobs/{job}/checklist/{item}', [TechnicianJobController::class, 'toggleChecklistItem'])
            ->name('jobs.checklist.toggle');
        Route::post('/jobs/{job}/photos', [TechnicianJobController::class, 'uploadPhoto'])
            ->name('jobs.photos.store');
        Route::delete('/jobs/{job}/photos/{attachment}', [TechnicianJobController::class, 'deletePhoto'])
            ->name('jobs.photos.destroy');
        Route::post('/jobs/{job}/line-items', [TechnicianJobController::class, 'addLineItem'])
            ->name('jobs.line-items.store');
        Route::patch('/jobs/{job}/line-items/{lineItem}', [TechnicianJobController::class, 'updateLineItem'])
            ->name('jobs.line-items.update');
        Route::delete('/jobs/{job}/line-items/{lineItem}', [TechnicianJobController::class, 'deleteLineItem'])
            ->name('jobs.line-items.destroy');
        Route::get('/catalog', [TechnicianJobController::class, 'catalogItems'])
            ->name('catalog.index');
        Route::post('/location', [LocationController::class, 'store'])
            ->middleware('throttle:60,1')
            ->name('location.store');
    });

/**
 * --------------------------------------------------------------------------
 * Titan Zero UI fallback
 * --------------------------------------------------------------------------
 *
 * Provide a safe API endpoint for the Business OS chat.  When the real
 * TitanZero module is not installed this route returns a simple JSON
 * response so the assistant does not error on submission.  Adjust the
 * middleware to match your API authentication requirements.  When the
 * TitanZero package is installed this stub can be replaced by the
 * module’s own route definitions.
 */
Route::post('/titan/zero/generate-ui', function (Request $request) {
    return response()->json([
        'message' => 'Titan Zero UI endpoint is online.',
        'reply' => 'Titan Zero is connected to the Business OS shell.',
        'parts' => [],
        'widgets' => [],
        'thread' => null,
        'meta' => ['suggestions' => []],
    ]);
})->middleware(['auth']);

/**
 * --------------------------------------------------------------------------
 * Titan thread history stub
 * --------------------------------------------------------------------------
 *
 * Returns the messages and widgets for an existing chat thread.  Replace
 * with a real implementation when the TitanZero module is available.
 */
Route::get('/titan/threads/{threadId}', function (string $threadId) {
    return response()->json([
        'messages' => [],
        'widgets'  => [],
    ]);
})->middleware(['auth']);

/**
 * --------------------------------------------------------------------------
 * Titan suggestion chips stub
 * --------------------------------------------------------------------------
 *
 * Returns context-aware suggestion chips for the chat composer.  Replace
 * with a real implementation that reads the current thread context.
 */
Route::get('/titan/suggestions', function (Request $request) {
    return response()->json([
        'suggestions' => [
            'Open app',
            'Search workspace',
            'Explain this screen',
            'Show recent activity',
            'Help me navigate',
        ],
    ]);
})->middleware(['auth']);
