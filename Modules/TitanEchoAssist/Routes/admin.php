<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanEchoAssist\Http\Controllers\Admin\TitanChatbotAdminController;

Route::middleware(['web', 'auth'])
    ->prefix(config('titan-chatbot.admin_prefix', 'admin/titan-chatbot'))
    ->name('admin.titan-chatbot.')
    ->group(function (): void {
        Route::get('/', [TitanChatbotAdminController::class, 'index'])->name('index');
    });
