<?php

/**
 * Titan AI — Manifest Settings, Agent/Tool/Guardrail Defaults
 *
 * This file controls the AI surface of the Titan platform: which provider is
 * active by default, which feature surfaces are enabled, agent definitions,
 * tool registry defaults, and guardrail policies.
 *
 * Provider connection details (API keys, base URLs, timeouts) live in
 * config/titan-model-runtime.php.
 *
 * Required keys validated by TitanCoreServiceProvider::boot():
 *   - titan-ai.default_provider
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | The provider key from config/titan-model-runtime.php that Titan will use
    | when no specific provider is requested by a module or agent.
    |
    | Supported out of the box: "openai", "anthropic", "titanai"
    |
    */

    'default_provider' => env('TITAN_AI_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | Feature Toggles
    |--------------------------------------------------------------------------
    |
    | Enables or disables top-level AI surfaces platform-wide. Individual
    | modules may impose further restrictions via their own manifests.
    |
    */

    'features' => [
        'chat'       => env('TITAN_AI_CHAT', true),
        'embeddings' => env('TITAN_AI_EMBEDDINGS', true),
        'vision'     => env('TITAN_AI_VISION', false),
        'tts'        => env('TITAN_AI_TTS', false),
        'agents'     => env('TITAN_AI_AGENTS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Guardrails / Model Policies
    |--------------------------------------------------------------------------
    |
    | Default list of models that any tenant may use without an explicit override.
    | Per-tenant overrides are keyed by tenant (company) ID.
    |
    */

    'guardrails' => [
        'default_allow' => ['gpt-4o-mini', 'gpt-3.5-turbo'],
        'overrides'     => [
            // 1 => ['gpt-4o', 'gpt-4o-mini', 'gpt-3.5-turbo'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Agent Defaults
    |--------------------------------------------------------------------------
    |
    | Knowledge-base collections and specialist agent definitions used by
    | TitanZero and domain agents. Keys must stay stable after first deploy.
    |
    */

    'agents' => [

        'general_collection_key' => env('TITAN_ZERO_GENERAL_KB', 'kb_general_cleaning'),

        'default_collections' => [
            'kb_general_cleaning' => [
                'title'      => 'General Cleaning Knowledge',
                'scope_type' => 'general',
                'agent_slug' => null,
                'meta'       => ['purpose' => 'Broad cleaning + ops knowledge for Titan Zero routing and general answers'],
            ],
            'kb_agent_quote' => [
                'title'      => 'Quote Agent Knowledge',
                'scope_type' => 'agent',
                'agent_slug' => 'quote_agent',
                'meta'       => ['topic' => 'quoting'],
            ],
            'kb_agent_dispatch' => [
                'title'      => 'Dispatch Agent Knowledge',
                'scope_type' => 'agent',
                'agent_slug' => 'dispatch_agent',
                'meta'       => ['topic' => 'dispatch'],
            ],
            'kb_agent_configuration' => [
                'title'      => 'Configuration Agent Knowledge',
                'scope_type' => 'agent',
                'agent_slug' => 'configuration_agent',
                'meta'       => ['topic' => 'configuration'],
            ],
            'kb_agent_compliance' => [
                'title'      => 'Compliance Agent Knowledge',
                'scope_type' => 'agent',
                'agent_slug' => 'compliance_agent',
                'meta'       => ['topic' => 'compliance'],
            ],
        ],

        'default_agents' => [
            'quote_agent' => [
                'title'             => 'Quoting Assistant',
                'description'       => 'Specialist agent focused on quoting rules, pricebook logic, and quoting SOPs.',
                'kb_collection_key' => 'kb_agent_quote',
                'meta'              => [
                    'output'               => 'quote_proposal',
                    'requires_confirmation' => true,
                    'forbidden_topics'     => ['dispatch', 'rostering', 'HR'],
                ],
            ],
            'dispatch_agent' => [
                'title'             => 'Dispatch Assistant',
                'description'       => 'Specialist agent focused on dispatch, rostering, capacity and escalation rules.',
                'kb_collection_key' => 'kb_agent_dispatch',
                'meta'              => [
                    'output'               => 'dispatch_plan',
                    'requires_confirmation' => true,
                    'forbidden_topics'     => ['pricing', 'quoting'],
                ],
            ],
            'configuration_agent' => [
                'title'             => 'Configuration Assistant',
                'description'       => 'Specialist agent focused on onboarding and configuration SOPs and checklists.',
                'kb_collection_key' => 'kb_agent_configuration',
                'meta'              => [
                    'output'               => 'configuration_plan',
                    'requires_confirmation' => false,
                ],
            ],
            'compliance_agent' => [
                'title'             => 'Compliance Assistant',
                'description'       => 'Specialist agent focused on compliance, SWMS, chemical handling, and incident playbooks.',
                'kb_collection_key' => 'kb_agent_compliance',
                'meta'              => [
                    'output'               => 'compliance_guidance',
                    'requires_confirmation' => false,
                    'must_cite'            => true,
                ],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Tool Registry Defaults
    |--------------------------------------------------------------------------
    |
    | Maps tool slugs to their handler classes. Modules may extend this at
    | runtime via TitanCoreServiceProvider.
    |
    */

    'tools' => [
        'registry' => [
            // 'calendar.create_booking' => Modules\TitanCore\Tools\CalendarCreateBookingTool::class,
            // 'crm.create_lead'         => Modules\TitanCore\Tools\CrmCreateLeadTool::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | Permission slugs exposed by the AI system. Used to seed the permission
    | table and enforce role-based access to AI features.
    |
    */

    'permissions' => [
        'manage_ai'          => 'Manage Titan Core settings',
        'manage_ai_prompts'  => 'Create/edit prompts and packs',
        'publish_ai_prompts' => 'Publish/rollback prompt versions',
        'manage_ai_kb'       => 'Manage Knowledge Library',
        'ingest_ai_kb'       => 'Ingest Knowledge content',
        'use_ai_features'    => 'Use Create with AI features',
        'view_ai_usage'      => 'View AI usage dashboard',
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit / Logging
    |--------------------------------------------------------------------------
    |
    | When enabled, AI calls and tool invocations are written to the audit log
    | so every action can be traced to a tenant and user.
    |
    */

    'audit' => [
        'enabled' => env('TITAN_AI_AUDIT', true),
    ],

];
