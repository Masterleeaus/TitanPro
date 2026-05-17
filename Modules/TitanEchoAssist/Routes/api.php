<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanEchoAssist\Http\Controllers\Api\ConversationController;
use Modules\TitanEchoAssist\Http\Controllers\Api\Portal\PortalActionController;
use Modules\TitanEchoAssist\Http\Controllers\Api\Portal\PortalBookingController;
use Modules\TitanEchoAssist\Http\Controllers\Api\Portal\PortalConversationController;
use Modules\TitanEchoAssist\Http\Controllers\Api\Portal\PortalDocumentController;
use Modules\TitanEchoAssist\Http\Controllers\Api\Portal\PortalFeedbackController;
use Modules\TitanEchoAssist\Http\Controllers\Api\Portal\PortalHomeController;
use Modules\TitanEchoAssist\Http\Controllers\Api\Portal\PortalNotificationController;
use Modules\TitanEchoAssist\Http\Controllers\Api\Portal\PortalRecurringController;
use Modules\TitanEchoAssist\Http\Controllers\Api\Portal\PortalSessionController;
use Modules\TitanEchoAssist\Http\Controllers\Api\Portal\PortalSiteProfileController;
use Modules\TitanEchoAssist\Http\Controllers\Api\Portal\PortalWorkDataController;
use Modules\TitanEchoAssist\Http\Controllers\TitanChatbotController;
use Modules\TitanEchoAssist\Http\Controllers\Api\ModuleAgentController;
use Modules\TitanEchoAssist\Http\Controllers\Api\TitanChatbotApiController;
use Modules\TitanEchoAssist\Http\Middleware\EnsureTitanChatbotEnabled;
use Modules\TitanEchoAssist\Http\Middleware\ValidatePortalSessionToken;

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

Route::middleware(['api', EnsureTitanChatbotEnabled::class])
    ->prefix('api/v2/chatbot')
    ->name('api.v2.chatbot.')
    ->group(function () {
        Route::get('/{chatbot:uuid}', [PortalSessionController::class, 'show'])->name('portal.show');

        Route::middleware(['throttle:60,1', ValidatePortalSessionToken::class])
            ->prefix('/{chatbot:uuid}/session/{sessionId}')
            ->name('portal.')
            ->group(function () {
                Route::post('/conversation', [PortalConversationController::class, 'store'])->name('conversation.store');
                Route::get('/conversation/{id}/messages', [PortalConversationController::class, 'messages'])->name('conversation.messages');
                Route::post('/conversation/{id}/file', [PortalConversationController::class, 'file'])->name('conversation.file');
                Route::post('/send-email', [PortalConversationController::class, 'sendEmail'])->name('send-email');
                Route::post('/review', [PortalConversationController::class, 'review'])->name('review');

                Route::get('/portal/home', [PortalHomeController::class, 'home'])->name('home');
                Route::get('/portal/menu', [PortalHomeController::class, 'menu'])->name('menu');
                Route::get('/portal/dashboard', [PortalHomeController::class, 'dashboard'])->name('dashboard');

                Route::get('/portal/bookings', [PortalBookingController::class, 'index'])->name('bookings.index');
                Route::post('/portal/bookings', [PortalBookingController::class, 'store'])->name('bookings.store');

                Route::get('/portal/visits', [PortalWorkDataController::class, 'visits'])->name('visits.index');
                Route::get('/portal/invoices', [PortalWorkDataController::class, 'invoices'])->name('invoices.index');
                Route::get('/portal/documents', [PortalWorkDataController::class, 'documents'])->name('work-data.documents.index');
                Route::get('/portal/issues', [PortalWorkDataController::class, 'issues'])->name('issues.index');
                Route::get('/portal/service-jobs/{id}/checklists', [PortalWorkDataController::class, 'checklists'])->name('service-jobs.checklists');
                Route::get('/portal/service-jobs/{id}/timeline', [PortalWorkDataController::class, 'timeline'])->name('service-jobs.timeline');
                Route::get('/portal/invoices/{id}/pay', [PortalWorkDataController::class, 'payInvoice'])->name('invoices.pay');

                Route::get('/portal/actions', [PortalActionController::class, 'index'])->name('actions.index');
                Route::post('/portal/actions', [PortalActionController::class, 'store'])->name('actions.store');
                Route::post('/portal/recurring/{customerId}', [PortalRecurringController::class, 'update'])->name('recurring.update');

                Route::get('/portal/notifications/{customerId}', [PortalNotificationController::class, 'index'])->name('notifications.index');
                Route::post('/portal/notifications/{id}/read', [PortalNotificationController::class, 'markRead'])->name('notifications.read');

                Route::post('/portal/site-profiles', [PortalSiteProfileController::class, 'store'])->name('site-profiles.store');
                Route::get('/portal/sites/{siteId}', [PortalSiteProfileController::class, 'show'])->name('sites.show');
                Route::post('/portal/feedback', [PortalFeedbackController::class, 'store'])->name('feedback.store');
                Route::post('/portal/documents', [PortalDocumentController::class, 'store'])->name('documents.store');
                Route::get('/portal/documents/{customerId}', [PortalDocumentController::class, 'index'])->name('documents.index');
            });
    });
