<?php

declare(strict_types=1);

use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanRuntime\TitanRuntimeApiController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanRuntime\TitanRuntimeSurfaceBootController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanRuntime\TitanRuntimeSurfaceController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\TitanZeroApiController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\TitanZeroAuditController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\TitanZeroController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\AIChatProFoldersController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\TitanZeroProposalController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\TitanZeroSurfaceController;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support\ZeroConfig;
use Illuminate\Support\Facades\Route;

Route::middleware(ZeroConfig::routeMiddleware())
    ->prefix(ZeroConfig::routePrefix())
    ->name(ZeroConfig::routeName())
    ->group(function (): void {
        Route::get('/', [TitanZeroController::class, 'index'])->name('index');
        Route::get('/boss', [TitanZeroSurfaceController::class, 'boss'])->name('boss.index');
        Route::get('/go', [TitanZeroSurfaceController::class, 'go'])->name('go.index');
        Route::get('/dispatch', [TitanZeroSurfaceController::class, 'dispatch'])->name('dispatch.index');
        Route::get('/qc', [TitanZeroSurfaceController::class, 'qc'])->name('qc.index');
        Route::get('/proposals', [TitanZeroProposalController::class, 'index'])->name('proposals.index');
        Route::post('/proposals/{proposal}', [TitanZeroProposalController::class, 'update'])->name('proposals.update');
        Route::get('/audit', [TitanZeroAuditController::class, 'index'])->name('audit.index');
    });

Route::middleware(ZeroConfig::apiMiddleware())
    ->prefix(ZeroConfig::apiPrefix())
    ->name(ZeroConfig::apiName())
    ->group(function (): void {
        Route::get('/status', [TitanZeroApiController::class, 'status'])->name('status');
        Route::post('/think', [TitanZeroApiController::class, 'think'])->name('think');
        Route::post('/chat', [TitanZeroApiController::class, 'chat'])->name('chat');
        Route::get('/tools', [TitanZeroApiController::class, 'tools'])->name('tools');
        Route::get('/preview', [TitanZeroApiController::class, 'preview'])->name('preview');
        Route::get('/preview/{surface}', [TitanZeroApiController::class, 'surfacePreview'])->name('preview.surface');
        Route::get('/timeline', [TitanZeroApiController::class, 'timeline'])->name('timeline');
        Route::post('/update-writing', [TitanZeroApiController::class, 'updateWriting'])->name('update-writing');
        Route::post('/pwa/bootstrap', [TitanZeroApiController::class, 'pwaBootstrap'])->name('pwa.bootstrap');
        Route::post('/pwa/handshake', [TitanZeroApiController::class, 'pwaHandshake'])->name('pwa.handshake');
        Route::post('/signals/ingest', [TitanZeroApiController::class, 'ingestSignals'])->name('signals.ingest');
        Route::post('/pwa/blobs/ingest', [TitanZeroApiController::class, 'pwaBlobIngest'])->name('pwa.blobs.ingest');
    });

Route::middleware(['web', 'auth', 'updateUserActivity'])
    ->prefix('dashboard/user/titanzero')
    ->name('dashboard.user.titanzero.')
    ->group(function (): void {
        Route::get('/folders', [AIChatProFoldersController::class, 'getFolders'])->name('folders.index');
        Route::post('/folders', [AIChatProFoldersController::class, 'store'])->name('folders.store');
        Route::put('/folders/{id}', [AIChatProFoldersController::class, 'update'])->name('folders.update');
        Route::delete('/folders/{id}', [AIChatProFoldersController::class, 'destroy'])->name('folders.destroy');
        Route::get('/chats', [AIChatProFoldersController::class, 'getChats'])->name('chats.index');
        Route::post('/chats/{chatId}/move-to-folder', [AIChatProFoldersController::class, 'moveChat'])->name('chats.move');
    });

Route::middleware(['web', 'auth', 'updateUserActivity'])
    ->prefix('dashboard/user/ai-chat-pro')
    ->name('dashboard.user.ai-chat-pro.')
    ->group(function (): void {
        Route::get('/folders', fn () => redirect()->route('dashboard.user.titanzero.folders.index'))->name('folders.index');
        Route::post('/folders', [AIChatProFoldersController::class, 'store'])->name('folders.store');
        Route::put('/folders/{id}', [AIChatProFoldersController::class, 'update'])->name('folders.update');
        Route::delete('/folders/{id}', [AIChatProFoldersController::class, 'destroy'])->name('folders.destroy');
        Route::get('/chats', fn () => redirect()->route('dashboard.user.titanzero.chats.index'))->name('chats.index');
        Route::post('/chats/{chatId}/move-to-folder', [AIChatProFoldersController::class, 'moveChat'])->name('chats.move');
    });

Route::middleware(['web', 'auth', 'updateUserActivity'])
    ->prefix('dashboard/user/titan-runtime')
    ->group(function (): void {
        Route::get('/dispatch', [TitanRuntimeSurfaceController::class, 'dispatch'])->name('dashboard.user.titan-runtime.dispatch');
        Route::get('/qc', [TitanRuntimeSurfaceController::class, 'qc'])->name('dashboard.user.titan-runtime.qc');
        Route::get('/boss', [TitanRuntimeSurfaceController::class, 'boss'])->name('dashboard.user.titan-runtime.boss');
        Route::get('/go', [TitanRuntimeSurfaceController::class, 'go'])->name('dashboard.user.titan-runtime.go');
        Route::get('/boot', [TitanRuntimeSurfaceBootController::class, 'boot'])->name('dashboard.user.titan-runtime.boot');
        Route::post('/api/dispatch', [TitanRuntimeApiController::class, 'dispatch'])->name('dashboard.user.titan-runtime.api.dispatch');
    });
