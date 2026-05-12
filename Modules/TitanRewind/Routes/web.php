<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', static fn () => response('titan-rewind-web-ok'))->name('health');
