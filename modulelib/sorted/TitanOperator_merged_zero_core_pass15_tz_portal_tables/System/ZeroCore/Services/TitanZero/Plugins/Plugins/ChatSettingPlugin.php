<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins;

use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\ChatCategoryController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\ChatTemplateController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\ChatbotController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\ChatbotTrainingController;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts\AbstractPlugin;
use Illuminate\Routing\Router;

/**
 * ChatSettingPlugin
 *
 * Chat category/template CRUD, chatbot management,
 * and RAG training (PDF, QA, text, URL crawl).
 * Always enabled — operators need these to configure the system.
 */
class ChatSettingPlugin extends AbstractPlugin
{
    public function id(): string    { return 'tzc-chat-settings'; }
    public function label(): string { return 'Chat Settings & Chatbot Training'; }

    public function routes(Router $router): void
    {
        $router->middleware(['web', 'auth'])
            ->prefix('dashboard/user/chat/settings')
            ->name('user.tzc.settings.')
            ->group(function () use ($router) {
                // Categories
                $router->apiResource('categories', ChatCategoryController::class);

                // Templates
                $router->apiResource('templates', ChatTemplateController::class);

                // Chatbots
                $router->apiResource('chatbots', ChatbotController::class);

                // Training
                $router->prefix('training/{chatbot}')->name('training.')->group(function () use ($router) {
                    $router->post('qa',              [ChatbotTrainingController::class, 'qa'])->name('qa');
                    $router->post('text',            [ChatbotTrainingController::class, 'text'])->name('text');
                    $router->post('pdf',             [ChatbotTrainingController::class, 'uploadPdf'])->name('pdf');
                    $router->get('websites',         [ChatbotTrainingController::class, 'getWebSites'])->name('websites.get');
                    $router->post('websites',        [ChatbotTrainingController::class, 'postWebSites'])->name('websites.post');
                    $router->post('run',             [ChatbotTrainingController::class, 'training'])->name('run');
                    $router->delete('item/{item}',   [ChatbotTrainingController::class, 'deleteItem'])->name('item.delete');
                });
            });
    }
}
