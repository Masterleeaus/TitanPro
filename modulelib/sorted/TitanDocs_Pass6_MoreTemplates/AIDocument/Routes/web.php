<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Route;
use Modules\AIDocument\Http\Controllers\AiTemplateController;
Route::group(['middleware' => 'PlanModuleCheck:AIDocument'], function ()
    {
        
		Route::prefix('aidocument')->group(function() {
		    Route::post('/setting/store', [AiTemplateController::class, 'setting'])->name('titan-docs.setting.store')->middleware(['auth']);
		    Route::any('/', [AiTemplateController::class, 'index'])->name('titan-docs.index')->middleware(['auth']);
            Route::any('/store', [AiTemplateController::class, 'store'])->name('titan-docs.document.store')->middleware(['auth']);
            Route::any('/show/{doc_id}/{id}/', [AiTemplateController::class, 'show'])->name('titan-docs.document.show')->middleware(['auth']);
            Route::any('/process', [AiTemplateController::class, 'AiGenerate'])->name('titan-docs.document.process')->middleware(['auth']);
            Route::any('/regenerate/response', [AiTemplateController::class, 'regenerate_response'])->name('titan-docs.document.regenerate.response')->middleware(['auth']);
            Route::any('exportallresponsecontent', [AiTemplateController::class, 'exportallresponsecontent'])->name('titan-docs.document.export.allresponse')->middleware(['auth']);
            Route::any('exportresponsecontent', [AiTemplateController::class, 'exportresponsecontent'])->name('titan-docs.document.export.response')->middleware(['auth']);
            Route::any('/edit/document/{doc_id}/{id}/', [AiTemplateController::class, 'edit'])->name('titan-docs.document.edit')->middleware(['auth']);
            Route::any('/save', [AiTemplateController::class, 'save'])->name('titan-docs.document.save')->middleware(['auth']);
            Route::any('delete/history/document/{id}', [AiTemplateController::class, 'destroy'])->name('titan-docs.document.destroy')->middleware(['auth']);
            Route::any('history/', [AiTemplateController::class, 'history'])->name('titan-docs.document.history')->middleware(['auth']);

		});
	});

