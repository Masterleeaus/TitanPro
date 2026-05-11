<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Voice;

use App\Domains\Marketplace\Contracts\UninstallExtensionServiceProviderInterface;
use App\Extensions\TitanOperator\System\Voice\Http\Controllers\AvatarController;
use App\Extensions\TitanOperator\System\Voice\Http\Controllers\TitanOperatorVoiceController;
use App\Extensions\TitanOperator\System\Voice\Http\Controllers\TitanOperatorVoiceEmbbedController;
use App\Extensions\TitanOperator\System\Voice\Http\Controllers\TitanOperatorVoiceHistoryController;
use App\Extensions\TitanOperator\System\Voice\Http\Controllers\TitanOperatorVoiceTrainController;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Author: MagicAI Team <info@liquid-themes.com>
 *
 * @note When you create a new service provider, make sure to add it to the "MarketplaceServiceProvider". Otherwise, your Laravel application won’t recognize this provider, and the related functions won’t work properly.
 * @note If you want to perform a specific action when an extension is uninstalled, you can use the UninstallExtensionServiceProviderInterface. By implementing this interface, you can define custom operations that will be triggered during the uninstallation of the extension.
 */
class TitanOperatorVoiceServiceProvider extends ServiceProvider implements UninstallExtensionServiceProviderInterface
{
    public function register()
    {
        $this->registerConfig();
    }

    public function boot(Kernel $kernel): void
    {
        $this->registerTranslations()
            ->registerViews()
            ->registerRoutes()
            ->registerMigrations()
            ->publishAssets();

    }

    public function registerConfig(): static
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/titan_operator-voice.php', 'titan_operator_voice');

        return $this;
    }

    public function publishAssets(): static
    {
        $this->publishes([
            __DIR__ . '/../resources/assets/js'     => public_path('vendor/titan-operator-voice/js'),
            __DIR__ . '/../resources/assets/images' => public_path('vendor/titan-operator-voice/images'),
        ], 'extension');

        return $this;
    }

    protected function registerTranslations(): static
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'titan_operator_voice');

        return $this;
    }

    public function registerViews(): static
    {
        $this->loadViewsFrom([__DIR__ . '/../resources/views'], 'titan_operator_voice');

        return $this;
    }

    public function registerMigrations(): static
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        return $this;
    }

    private function registerRoutes(): static
    {
        $this->router()
            ->group([
                'middleware' => 'web',
            ], function (Router $router) {
                $router
                    ->controller(TitanOperatorVoiceController::class)
                    ->group(function (Router $router) {
                        $router->get('titan_operator-voice/{uuid}/frame', 'frame')->name('titan_operator-voice.frame');
                        $router->post('titan_operator-voice/checkVoiceBalance', 'checkVoiceBalance')->name('titan_operator-voice.checkVoiceBalance');
                    });
            })
            ->group([
                'middleware' => ['web', 'auth'],
                'prefix'	    => 'dashboard/user/titan-operator-voice',
                'as'  		     => 'dashboard.titan_operator_voice.',
                'controller' => TitanOperatorVoiceController::class,
            ], function (Router $router) {
                $router->get('', 'index')->name('index');
                $router->post('store', 'store')->name('store');
                $router->put('update', 'update')->name('update');
                $router->delete('delete', 'delete')->name('delete');

                $router->group([
                    'prefix' 		   => 'train',
                    'as' 			      => 'train.',
                    'controller' 	=> TitanOperatorVoiceTrainController::class,
                ], function (Router $router) {
                    $router->get('data', 'trainData')->name('data');
                    $router->delete('delete', 'delete')->name('delete');
                    $router->post('generate', 'generateEmbedding')->name('generate');

                    $router->post('file', 'trainFile')->name('file');
                    $router->post('text', 'trainText')->name('text');
                    $router->post('url', 'trainUrl')->name('url');
                });

                $router->group([
                    'prefix' 		   => 'conversation',
                    'as' 			      => 'conversation.',
                ], function (Router $router) {
                    $router->get('with-paginate', [TitanOperatorVoiceHistoryController::class, 'loadConversationWithPaginate'])->name('with.paginate');
                });
            })
            ->group([
                'prefix'         => 'api/v2/titan-operator-voice',
                'as'             => 'api.v2.titan_operator_voice.',
            ], function (Router $router) {
                $router->get('{uuid}', [TitanOperatorVoiceEmbbedController::class, 'index'])->name('index');
                $router->post('{uuid}/store-conversation', [TitanOperatorVoiceHistoryController::class, 'storeConversation'])->name('store-conversation');
            })
            ->group([
                'middleware' => ['web', 'auth'],
            ], function (Router $router) {
                $router->post('dashboard/user/titan-operator-voice/avatar/upload', AvatarController::class)
                    ->name('dashboard.titan_operator_voice.upload.avatar');
            });

        return $this;
    }

    private function router(): Router|Route
    {
        return $this->app['router'];
    }

    public static function uninstall(): void
    {
        // TODO: Implement uninstall() method.
    }
}
