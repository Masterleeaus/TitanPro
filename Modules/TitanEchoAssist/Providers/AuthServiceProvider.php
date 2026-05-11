<?php

namespace Modules\TitanEchoAssist\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Policies\ChatbotPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /** @var array<class-string, class-string> */
    protected $policies = [
        Chatbot::class => ChatbotPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
