<?php

use Illuminate\Support\Facades\Route;
use App\Extensions\TitanCommand\System\Http\Controllers\JobsController;

/**
 * TITAN COMMAND — JOBS ROUTES
 * Prefix: /dashboard/user/command
 * Name:   dashboard.user.command.*
 */

Route::prefix('jobs')->name('jobs.')->group(function () {

    // A) Jobs list & core CRUD
    Route::get('/',                [JobsController::class, 'index'])->name('index');
    Route::get('/create',          [JobsController::class, 'create'])->name('create');
    Route::post('/',               [JobsController::class, 'store'])->name('store');
    Route::get('/{job}',           [JobsController::class, 'show'])->name('show');
    Route::get('/{job}/edit',      [JobsController::class, 'edit'])->name('edit');
    Route::put('/{job}',           [JobsController::class, 'update'])->name('update');
    Route::post('/{job}/archive',  [JobsController::class, 'archive'])->name('archive');
    Route::post('/{job}/restore',  [JobsController::class, 'restore'])->name('restore');

    // B) Dispatch & schedule
    Route::get('/dispatch',            [JobsController::class, 'dispatch'])->name('dispatch');
    Route::post('/{job}/assign',       [JobsController::class, 'assign'])->name('assign');
    Route::post('/{job}/unassign',     [JobsController::class, 'unassign'])->name('unassign');
    Route::get('/{job}/schedule',      [JobsController::class, 'scheduleView'])->name('schedule.view');
    Route::post('/{job}/schedule',     [JobsController::class, 'scheduleSet'])->name('schedule.set');
    Route::post('/{job}/reschedule',   [JobsController::class, 'reschedule'])->name('reschedule');

    // C) Job timeline / events
    Route::get('/{job}/timeline',      [JobsController::class, 'timeline'])->name('timeline');
    Route::post('/{job}/events',       [JobsController::class, 'addEvent'])->name('events.store');

    // D) Tasks & sub-items
    Route::get('/{job}/tasks',                     [JobsController::class, 'tasks'])->name('tasks.index');
    Route::post('/{job}/tasks',                    [JobsController::class, 'taskStore'])->name('tasks.store');
    Route::put('/{job}/tasks/{task}',              [JobsController::class, 'taskUpdate'])->name('tasks.update');
    Route::post('/{job}/tasks/{task}/complete',    [JobsController::class, 'taskComplete'])->name('tasks.complete');
    Route::post('/{job}/tasks/{task}/reopen',      [JobsController::class, 'taskReopen'])->name('tasks.reopen');

    // E) Checklists
    Route::get('/{job}/checklists',                                 [JobsController::class, 'checklists'])->name('checklists.index');
    Route::post('/{job}/checklists',                                [JobsController::class, 'checklistStore'])->name('checklists.store');
    Route::get('/{job}/checklists/{checklist}',                     [JobsController::class, 'checklistShow'])->name('checklists.show');
    Route::post('/{job}/checklists/{checklist}/items',              [JobsController::class, 'checklistItemStore'])->name('checklists.items.store');
    Route::post('/{job}/checklists/{checklist}/items/{item}/check', [JobsController::class, 'checklistItemCheck'])->name('checklists.items.check');
    Route::post('/{job}/checklists/{checklist}/complete',           [JobsController::class, 'checklistComplete'])->name('checklists.complete');

    // F) Parts / materials
    Route::get('/{job}/parts',                 [JobsController::class, 'parts'])->name('parts.index');
    Route::post('/{job}/parts',                [JobsController::class, 'partStore'])->name('parts.store');
    Route::put('/{job}/parts/{part}',          [JobsController::class, 'partUpdate'])->name('parts.update');
    Route::post('/{job}/parts/{part}/use',     [JobsController::class, 'partUse'])->name('parts.use');
    Route::post('/{job}/parts/{part}/return',  [JobsController::class, 'partReturn'])->name('parts.return');

    // G) Assets & permits
    Route::get('/{job}/assets',                 [JobsController::class, 'assets'])->name('assets.index');
    Route::post('/{job}/assets/link',           [JobsController::class, 'assetLink'])->name('assets.link');
    Route::post('/{job}/assets/unlink',         [JobsController::class, 'assetUnlink'])->name('assets.unlink');
    Route::get('/{job}/permits',                [JobsController::class, 'permits'])->name('permits.index');
    Route::post('/{job}/permits/link',          [JobsController::class, 'permitLink'])->name('permits.link');
    Route::post('/{job}/permits/unlink',        [JobsController::class, 'permitUnlink'])->name('permits.unlink');

    // H) Inspections
    Route::get('/{job}/inspections',                             [JobsController::class, 'inspections'])->name('inspections.index');
    Route::post('/{job}/inspections',                            [JobsController::class, 'inspectionStore'])->name('inspections.store');
    Route::get('/{job}/inspections/{inspection}',                [JobsController::class, 'inspectionShow'])->name('inspections.show');
    Route::post('/{job}/inspections/{inspection}/items',         [JobsController::class, 'inspectionItemStore'])->name('inspections.items.store');
    Route::post('/{job}/inspections/{inspection}/submit',        [JobsController::class, 'inspectionSubmit'])->name('inspections.submit');

    // I) Evidence / proof
    Route::get('/{job}/evidence',                 [JobsController::class, 'evidence'])->name('evidence.index');
    Route::post('/{job}/evidence',                [JobsController::class, 'evidenceStore'])->name('evidence.store');
    Route::post('/{job}/signoff',                 [JobsController::class, 'signoff'])->name('signoff');
    Route::get('/{job}/proof-pack',               [JobsController::class, 'proofPack'])->name('proofpack.view');
    Route::post('/{job}/proof-pack/export',       [JobsController::class, 'proofPackExport'])->name('proofpack.export');

    // K) Settings (UI)
    Route::get('/settings', [JobsController::class, 'settings'])->name('settings');

});

// J) Reports
Route::prefix('jobs/reports')->name('jobs.reports.')->group(function () {
    Route::get('/',              [JobsController::class, 'reports'])->name('index');
    Route::get('/performance',   [JobsController::class, 'reportsPerformance'])->name('performance');
    Route::get('/compliance',    [JobsController::class, 'reportsCompliance'])->name('compliance');
});
