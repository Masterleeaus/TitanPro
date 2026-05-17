<?php
use Illuminate\Support\Facades\Route;
use Modules\TitanTalk\Http\Controllers\ChannelController;

// Channel Admin
Route::middleware(['web','auth'])->prefix('aiconverse')->group(function () {
  Route::get('/channels', [ChannelController::class, 'index'])->name('titantalk.channels.index');
  Route::get('/channels/create', [ChannelController::class, 'create'])->name('titantalk.channels.create');
  Route::post('/channels', [ChannelController::class, 'store'])->name('titantalk.channels.store');
  Route::get('/channels/{id}/edit', [ChannelController::class, 'edit'])->name('titantalk.channels.edit');
  Route::put('/channels/{id}', [ChannelController::class, 'update'])->name('titantalk.channels.update');
  Route::delete('/channels/{id}', [ChannelController::class, 'destroy'])->name('titantalk.channels.delete');
});


