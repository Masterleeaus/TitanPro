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

Route::middleware(['web', 'auth'])
    ->prefix('aidocument')
    ->group(function () {

        Route::post('/store', [AiTemplateController::class, 'store'])->name('aidocument.document.store');
        Route::get('/', [AiTemplateController::class, 'index'])->name('titan.docs.index');
        Route::get('/', [AiTemplateController::class, 'index'])->name('titan-docs.index');
        Route::get('/', [AiTemplateController::class, 'index'])->name('titan-docs.document.index');
        Route::get('/', [AiTemplateController::class, 'index'])->name('aidocument.index');
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

        Route::match(['get', 'post'], '/show/{doc_id}/{id}', [AiTemplateController::class, 'show'])->name('titan.docs.document.view');
        Route::match(['get', 'post'], '/show/{doc_id}/{id}', [AiTemplateController::class, 'show'])->name('titan-docs.document.show');
        Route::match(['get', 'post'], '/show/{doc_id}/{id}', [AiTemplateController::class, 'show'])->name('aidocument.document.show');
        Route::match(['get', 'post'], '/edit/document/{doc_id}/{id}', [AiTemplateController::class, 'edit'])->name('titan.docs.document.edit');
        Route::match(['get', 'post'], '/edit/document/{doc_id}/{id}', [AiTemplateController::class, 'edit'])->name('aidocument.document.edit');
        Route::post('/document/{id}', [AiTemplateController::class, 'update'])->name('titan.docs.document.update');
        Route::post('/save', [AiTemplateController::class, 'save'])->name('aidocument.document.save');
        Route::delete('/delete/history/document/{id}', [AiTemplateController::class, 'destroy'])->name('aidocument.document.destroy');

        Route::post('/process', [AiTemplateController::class, 'AiGenerate'])->name('titan.docs.process');
        Route::post('/process', [AiTemplateController::class, 'AiGenerate'])->name('titan-docs.document.process');
        Route::post('/process', [AiTemplateController::class, 'AiGenerate'])->name('aidocument.document.process');
        Route::post('/regenerate', [AiTemplateController::class, 'regenerate_response'])->name('titan.docs.regenerate');
        Route::post('/regenerate/response', [AiTemplateController::class, 'regenerate_response'])->name('aidocument.document.regenerate.response');
        Route::get('/export/response/{id}', [AiTemplateController::class, 'exportresponsecontent'])->name('titan.docs.export.response');
        Route::get('/export/all/{id}', [AiTemplateController::class, 'exportallresponsecontent'])->name('titan.docs.export.all');
        Route::post('/exportresponsecontent', [AiTemplateController::class, 'exportresponsecontent'])->name('aidocument.document.export.response');
        Route::post('/exportallresponsecontent', [AiTemplateController::class, 'exportallresponsecontent'])->name('aidocument.document.export.allresponse');
    });
