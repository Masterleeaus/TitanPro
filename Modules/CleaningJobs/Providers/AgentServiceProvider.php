<?php

namespace Modules\CleaningJobs\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * AgentServiceProvider registers TitanWork AI agent actions and tools.
 *
 * The blueprint requires this provider to expose module-specific agent
 * functionality, such as voice commands or action handlers. Currently
 * implemented as a placeholder.
 */
class AgentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register agent tools, prompts and policies here
    }

    public function boot(): void
    {
        // Boot any agent-related services
    }
}