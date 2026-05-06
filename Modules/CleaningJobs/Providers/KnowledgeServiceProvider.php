<?php

namespace Modules\CleaningJobs\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * KnowledgeServiceProvider registers knowledge ingestion surfaces for TitanWork.
 *
 * Knowledge bases allow the module to attach internal documentation, SOPs,
 * and other reference materials to the AI tools. This provider is a
 * placeholder awaiting further implementation.
 */
class KnowledgeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register knowledge sources or ingestion services
    }

    public function boot(): void
    {
        // Boot any knowledge-related services
    }
}