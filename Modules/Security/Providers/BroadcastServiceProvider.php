<?php

namespace Modules\Security\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $channels = module_path('Security', 'Routes/channels.php');
        if (file_exists($channels)) {
            Broadcast::routes(['middleware' => ['web', 'auth']]);
            require $channels;
        }
    }
}
