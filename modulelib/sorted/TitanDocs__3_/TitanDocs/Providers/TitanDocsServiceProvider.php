<?php

namespace Modules\TitanDocs\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class TitanDocsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'titandocs');

        Route::middleware(['web','auth'])
            ->prefix('account/titan/docs')
            ->name('titan.docs.')
            ->group(__DIR__ . '/../routes/account.php');
    }
}
