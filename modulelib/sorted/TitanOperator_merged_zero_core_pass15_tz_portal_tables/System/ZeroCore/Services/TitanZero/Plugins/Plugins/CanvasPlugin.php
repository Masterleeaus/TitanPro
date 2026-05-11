<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins;

use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\CanvasController;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts\AbstractPlugin;
use Illuminate\Routing\Router;

/**
 * CanvasPlugin
 *
 * Tiptap split-pane AI writing canvas with 10 AI actions.
 * Gated by 'ai_chat_pro_canvas' setting.
 */
class CanvasPlugin extends AbstractPlugin
{
    public function id(): string    { return 'tzc-canvas'; }
    public function label(): string { return 'AI Canvas Editor'; }

    public function enabled(): bool
    {
        return (int) \Helper::setting('ai_chat_pro_canvas', 1) === 1;
    }

    public function routes(Router $router): void
    {
        $router->middleware(['web', 'auth'])
            ->prefix('dashboard/user/chat/canvas')
            ->name('user.tzc.canvas.')
            ->group(function () use ($router) {
                $router->post('content', [CanvasController::class, 'storeContent'])->name('content.store');
                $router->post('title',   [CanvasController::class, 'saveTitle'])->name('title.save');
            });
    }

    public function assets(): array
    {
        return [
            'titanzero-chat-assets' => [
                __DIR__ . '/../../../Resources/js'  => public_path('vendor/titanzero-chat/js'),
                __DIR__ . '/../../../Resources/css' => public_path('vendor/titanzero-chat/css'),
            ],
        ];
    }

    public function settings(): array
    {
        return ['ai_chat_pro_canvas' => 1];
    }
}
