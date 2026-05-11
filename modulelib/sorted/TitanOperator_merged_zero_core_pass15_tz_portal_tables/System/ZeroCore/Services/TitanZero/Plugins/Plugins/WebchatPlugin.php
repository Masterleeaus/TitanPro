<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins;

use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\AIWebChatController;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts\AbstractPlugin;
use Illuminate\Routing\Router;

/**
 * WebchatPlugin
 *
 * Website-embedded chat with URL crawling, ada-002 embeddings,
 * and optional Serper/Perplexity realtime search.
 */
class WebchatPlugin extends AbstractPlugin
{
    public function id(): string    { return 'tzc-webchat'; }
    public function label(): string { return 'Webchat (URL Crawl + Embed)'; }

    public function routes(Router $router): void
    {
        $router->middleware(['web', 'auth'])
            ->prefix('dashboard/user/chat/webchat')
            ->name('user.tzc.webchat.')
            ->group(function () use ($router) {
                $router->get('/',                       [AIWebChatController::class, 'openAIGeneratorWorkbook'])->name('index');
                $router->get('area',                    [AIWebChatController::class, 'openChatAreaContainer'])->name('area');
                $router->post('start',                  [AIWebChatController::class, 'startNewChat'])->name('start');
                $router->get('stream',                  [AIWebChatController::class, 'chatStream'])->name('stream');
                $router->match(['get','post'], 'send',  [AIWebChatController::class, 'chatOutput'])->name('send');
            });
    }

    public function assets(): array
    {
        return [
            'titanzero-chat-assets' => [
                __DIR__ . '/../../../Resources/js/openai_webchat.js' => public_path('vendor/titanzero-chat/js/openai_webchat.js'),
            ],
        ];
    }

    public function settings(): array
    {
        return ['default_realtime' => 0];
    }
}
