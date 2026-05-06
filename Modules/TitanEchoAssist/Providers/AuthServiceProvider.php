<?php

namespace Modules\TitanChatbot\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\TitanChatbot\Models\Chatbot;
use Modules\TitanChatbot\Policies\ChatbotPolicy;

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
