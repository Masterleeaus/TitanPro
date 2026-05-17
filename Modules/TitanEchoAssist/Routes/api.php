<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanEchoAssist\Http\Controllers\Api\ConversationController;
use Modules\TitanEchoAssist\Http\Controllers\Api\ChatbotPortalController;
use Modules\TitanEchoAssist\Http\Controllers\Api\PortalHomeController;
use Modules\TitanEchoAssist\Http\Controllers\Api\PortalWorkDataController;
use Modules\TitanEchoAssist\Http\Controllers\TitanChatbotController;
use Modules\TitanEchoAssist\Http\Controllers\Api\ModuleAgentController;
use Modules\TitanEchoAssist\Http\Controllers\Api\TitanChatbotApiController;

Route::middleware(['api'])->prefix(config('titan-chatbot.api_prefix', 'api/titan-chatbot'))->name('api.titan-chatbot.')->group(function () {
    Route::get('/health', [TitanChatbotController::class, 'health'])->name('health');
    Route::get('/', [TitanChatbotApiController::class, 'index'])->name('index');
    Route::get('/module-agent/tools', [ModuleAgentController::class, 'tools'])->name('module-agent.tools');
    Route::post('/module-agent/tools/{tool}', [ModuleAgentController::class, 'invoke'])->name('module-agent.invoke');
});

Route::middleware(['api'])->prefix('api/chatbots')->name('api.chatbots.')->group(function () {
    Route::post('/{id}/message', [ConversationController::class, 'sendMessage'])->name('message');
    Route::post('/{id}/voice',   [ConversationController::class, 'sendVoice'])->name('voice');
    Route::get('/{id}/history',  [ConversationController::class, 'history'])->name('history');
    Route::post('/{id}/train',   [ConversationController::class, 'train'])->name('train');
});

Route::middleware(['api'])->prefix('api/v2/chatbot')->name('api.v2.chatbot.')->group(function () {
    Route::get('/{uuid}/session/{sessionId}/portal/menu', [ChatbotPortalController::class, 'menu'])->name('portal.menu');
    Route::get('/{uuid}/session/{sessionId}/portal/home', PortalHomeController::class)->name('portal.home');
    Route::get('/{uuid}/session/{sessionId}/portal/work-data', PortalWorkDataController::class)->name('portal.work-data');
});
