<?php
use Illuminate\Support\Facades\Route;
Route::get('/titan-nexus', \Modules\TitanNexus\Http\Controllers\ControlPanelController::class)->name('titan-nexus.control');
