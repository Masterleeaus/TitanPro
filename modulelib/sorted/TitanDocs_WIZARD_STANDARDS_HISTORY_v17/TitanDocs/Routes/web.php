<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanDocs\Http\Controllers\AiTemplateController;
use Modules\TitanDocs\Http\Controllers\GeneratorWizardController;

/*
|--------------------------------------------------------------------------
| Titan Docs Routes
|--------------------------------------------------------------------------
| We register BOTH:
|  - New canonical names: titan.docs.*
|  - Legacy names used by older TitanDocs menu/listeners: titan-docs.*
| This prevents sidebar/menu regressions while we standardize.
*/

Route::middleware([
        'web',
        'auth',
        'check-company-package',
        'auto-logout',
        'multi-company-select',
        'email_verified',
    ])
    ->prefix('account/titan/docs')
    ->group(function () {

        // Canonical
        Route::get('/', [AiTemplateController::class, 'index'])->name('titan.docs.index');
        Route::get('/generator', [AiTemplateController::class, 'index'])->name('titan.docs.create');
        // Wizard (multi-step)
        Route::get('/wizard', [GeneratorWizardController::class, 'start'])->name('titan.docs.generator.start');
        Route::get('/wizard/{session}/step/{step}', [GeneratorWizardController::class, 'step'])->name('titan.docs.generator.step');
        Route::post('/wizard/{session}/step/{step}', [GeneratorWizardController::class, 'save'])->name('titan.docs.generator.save');
        Route::get('/wizard/{session}/review', [GeneratorWizardController::class, 'review'])->name('titan.docs.generator.review');
        Route::post('/wizard/{session}/complete', [GeneratorWizardController::class, 'complete'])->name('titan.docs.generator.complete');


        Route::get('/history/docs', [AiTemplateController::class, 'historyDocs'])->name('titan.docs.history.docs');
        Route::get('/history/swms', [AiTemplateController::class, 'historySwms'])->name('titan.docs.history.swms');
        Route::get('/history', [AiTemplateController::class, 'historyDocs'])->name('titan.docs.history');

        Route::get('/document/{id}', [AiTemplateController::class, 'show'])->name('titan.docs.document.view');
        Route::get('/document/{id}/edit', [AiTemplateController::class, 'edit'])->name('titan.docs.document.edit');
        Route::post('/document/{id}', [AiTemplateController::class, 'update'])->name('titan.docs.document.update');

        Route::post('/process', [AiTemplateController::class, 'AiGenerate'])->name('titan.docs.process');
        Route::post('/regenerate', [AiTemplateController::class, 'regenerate_response'])->name('titan.docs.regenerate');
        Route::get('/export/response/{id}', [AiTemplateController::class, 'exportresponsecontent'])->name('titan.docs.export.response');
        Route::get('/export/all/{id}', [AiTemplateController::class, 'exportallresponsecontent'])->name('titan.docs.export.all');

        // Legacy (compat)
        Route::get('/', [AiTemplateController::class, 'index'])->name('titan-docs.index');
        Route::get('/', [AiTemplateController::class, 'index'])->name('titan-docs.document.index');
        Route::get('/history', [AiTemplateController::class, 'history'])->name('titan-docs.document.history');

        // Additional legacy endpoints observed in older routes
        Route::post('/process', [AiTemplateController::class, 'AiGenerate'])->name('titan-docs.document.process');
    });
