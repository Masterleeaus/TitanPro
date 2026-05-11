<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Plugins;

use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\AIChatProController;
use App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanZero\AIChatProSettingsController;
use App\Extensions\TitanOperator\System\ZeroCore\Console\Commands\TitanZero\HealthCheck;
use App\Extensions\TitanOperator\System\ZeroCore\Console\Commands\TitanZero\ListPlugins;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Contracts\AbstractPlugin;
use Illuminate\Routing\Router;

/**
 * CoreChatPlugin
 *
 * The foundation plugin — always enabled.
 * Provides the main chat UI, guest mode, streaming, and image generation tool.
 * Cannot be disabled (enabled() always returns true).
 */
class CoreChatPlugin extends AbstractPlugin
{
    public function id(): string    { return 'tzc-core-chat'; }
    public function label(): string { return 'Core Chat'; }
    public function enabled(): bool { return true; } // always on

    public function routes(Router $router): void
    {
        // Admin settings
        $router->middleware(['web', 'auth', 'admin'])
            ->prefix('dashboard/admin/chat')
            ->name('admin.tzc.')
            ->group(function () use ($router) {
                $router->get('settings',  [AIChatProSettingsController::class, 'index'])->name('settings');
                $router->post('settings', [AIChatProSettingsController::class, 'update'])->name('settings.store');
            });

        // Authenticated chat
        $router->middleware(['web', 'auth'])
            ->prefix('dashboard/user/chat')
            ->name('user.tzc.')
            ->group(function () use ($router) {
                $router->get('/{slug?}',  [AIChatProController::class, 'index'])->name('index');
                $router->post('start',    [AIChatProController::class, 'startNewChat'])->name('start');
                $router->post('guest-start', [AIChatProController::class, 'startNewGuestChat'])->name('guest.start');
            });

        // Public / guest
        $router->middleware('web')
            ->get('/chat/{slug?}', [AIChatProController::class, 'index'])
            ->name('tzc.public.chat');
    }

    public function migrations(): ?string
    {
        return base_path('database/migrations/titanzero_chat');
    }

    public function views(): array
    {
        return ['titanzero-chat' => resource_path('views/default/panel/user/titanzero')];
    }

    public function commands(): array
    {
        return [
            ListPlugins::class,
            HealthCheck::class,
        ];
    }

    public function settings(): array
    {
        return [
            'ai_chat_pro_suggestions'             => 1,
            'ai_chat_pro_image_generation_feature' => 1,
            'ai_chat_pro_multi_model_feature'     => 0,
            'ai_chat_pro_default_screen'          => 'new',
            'ai_chat_display_type'                => 'menu',
            'guest_user_daily_message_limit'      => 10,
            'guest_user_bottom_text'              => '',
        ];
    }
}
