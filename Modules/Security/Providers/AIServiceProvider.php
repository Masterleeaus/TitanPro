<?php

namespace Modules\Security\Providers;

use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $path = module_path('Security', 'Config/ai.php');
        if (file_exists($path)) {
            $this->mergeConfigFrom($path, 'security_ai');
        }
    }
}
