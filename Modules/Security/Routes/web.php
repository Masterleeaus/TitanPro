<?php

use Illuminate\Support\Facades\Route;
use Modules\Security\Http\Controllers\SecurityController;
use Modules\Security\Http\Controllers\SecurityWPController;
use Modules\Security\Http\Controllers\TrInOutPermitPermissionController;
use Modules\Security\Http\Controllers\WorkPermitsController;
use Modules\Security\Http\Controllers\WorkPermitsFileController;
use Modules\Security\Http\Controllers\CardAccessController;
use Modules\Security\Http\Controllers\CleanerController;


// ---- merged from Security ----
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

Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {
    Route::get('security-transfer/download/{id}', [SecurityController::class, 'download'])->name('security-transfer.download');
    Route::get('security-transfer/export', [SecurityController::class, 'export'])->name('security-transfer.export');
    Route::get('security-transfer/validate/{id}', [SecurityController::class, 'validateData'])->name('security-transfer.validate');
    Route::post('security-transfer/validated/{id}', [SecurityController::class, 'processValidatedData'])->name('security-transfer.validated');
    Route::resource('security-transfer', SecurityController::class);

    Route::get('security-workpermit/download/{id}', [SecurityWPController::class, 'download'])->name('security-workpermit.download');
    Route::get('security-workpermit/export', [SecurityWPController::class, 'export'])->name('security-workpermit.export');
    Route::get('security-workpermit/validate/{id}', [SecurityWPController::class, 'validateData'])->name('security-workpermit.validate');
    Route::post('security-workpermit/validated/{id}', [SecurityWPController::class, 'processValidatedData'])->name('security-workpermit.validated');
    Route::resource('security-workpermit', SecurityWPController::class);
});

// ---- merged from TrInOutPermit ----
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

Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {
    Route::get('trinoutpermit/download/{id}', [TrInOutPermitPermissionController::class, 'download'])->name('trinoutpermit.download');
    Route::get('trinoutpermit/export', [TrInOutPermitPermissionController::class, 'export'])->name('trinoutpermit.export');
    Route::post('trinoutpermit/apply-quick-action', [TrInOutPermitPermissionController::class, 'applyQuickAction'])->name('trinoutpermit.apply_quick_action');
    Route::get('trinoutpermit/client', [TrInOutPermitPermissionController::class, 'client'])->name('trinoutpermit.client');
    Route::get('trinoutpermit/approved/{id}', [TrInOutPermitPermissionController::class, 'approved'])->name('trinoutpermit.approved');
    Route::get('trinoutpermit/approved_bm/{id}', [TrInOutPermitPermissionController::class, 'approved_bm'])->name('trinoutpermit.approved_bm');
    Route::get('trinoutpermit/validate/{id}', [TrInOutPermitPermissionController::class, 'validateData'])->name('trinoutpermit.validate');
    Route::post('trinoutpermit/validated/{id}', [TrInOutPermitPermissionController::class, 'processValidatedData'])->name('trinoutpermit.validated');
    Route::resource('trinoutpermit', TrInOutPermitPermissionController::class);
});

// ---- merged from TrWorkPermits ----
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

Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {
    Route::get('work-permits/download/{id}', [WorkPermitsController::class, 'download'])->name('work-permits.download');
    Route::get('work-permits/export', [WorkPermitsController::class, 'export'])->name('work-permits.export');
    Route::post('work-permits/apply-quick-action', [WorkPermitsController::class, 'applyQuickAction'])->name('work-permits.apply_quick_action');
    Route::get('work-permits/client', [WorkPermitsController::class, 'client'])->name('work-permits.client');
    Route::get('work-permits/approved/{id}', [WorkPermitsController::class, 'approved'])->name('work-permits.approved');
    Route::get('work-permits/approved_bm/{id}', [WorkPermitsController::class, 'approved_bm'])->name('work-permits.approved_bm');
    Route::get('work-permits/validate/{id}', [WorkPermitsController::class, 'validateData'])->name('work-permits.validate');
    Route::post('work-permits/validated/{id}', [WorkPermitsController::class, 'processValidatedData'])->name('work-permits.validated');
    Route::resource('work-permits', WorkPermitsController::class);
    Route::post('work-permits-file/multiple-upload', [WorkPermitsFileController::class, 'storeMultiple'])->middleware('throttle:security-upload')->name('work-permits-file.multiple_upload');
    Route::post('work-permits-file', [WorkPermitsFileController::class, 'store'])->middleware('throttle:security-upload')->name('work-permits-file.store');
    Route::resource('work-permits-file', WorkPermitsFileController::class)->except(['store']);
});

// ---- merged from TrAccessCard ----
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

Route::group(['middleware' => 'auth', 'prefix' => 'account'], function () {
    Route::get('card-access/download/{id}', [CardAccessController::class, 'download'])->name('card-access.download');
    Route::get('card-access/export', [CardAccessController::class, 'export'])->name('card-access.export');
    Route::post('card-access/apply-quick-action', [CardAccessController::class, 'applyQuickAction'])->name('card-access.apply_quick_action');
    Route::get('card-access/client', [CardAccessController::class, 'client'])->name('card-access.client');
    Route::resource('card-access', CardAccessController::class);
});


// ---- cleaners-first operations ----
Route::group(['middleware' => 'auth', 'prefix' => 'account/security', 'as' => 'security.'], function () {
    Route::get('cleaners', [CleanerController::class, 'index'])->name('cleaners.index');
    Route::post('cleaners', [CleanerController::class, 'store'])->name('cleaners.store');
    Route::post('cleaners/{cleaner}/approve', [CleanerController::class, 'approve'])->name('cleaners.approve');
    Route::post('cleaners/{cleaner}/decision', [CleanerController::class, 'decision'])->name('cleaners.decision');
    Route::post('cleaners/{cleaner}/check-in', [CleanerController::class, 'checkIn'])->name('cleaners.check_in');
    Route::post('cleaners/{cleaner}/check-out', [CleanerController::class, 'checkOut'])->name('cleaners.check_out');
});
