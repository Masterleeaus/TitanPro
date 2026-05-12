<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanDocs\Http\Controllers\TitanDocsController;

Route::get('/', [TitanDocsController::class, 'index'])->name('index');
Route::get('/create', [TitanDocsController::class, 'create'])->name('create');
Route::get('/history', [TitanDocsController::class, 'history'])->name('history');
