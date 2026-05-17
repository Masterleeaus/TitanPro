<?php

/**
 * TitanZeroChat — Core Module Configuration
 *
 * Runtime values are stored in the `settings` DB table and read via
 * Helper::setting($key, $default). This file defines the canonical key
 * names and their defaults for reference and config publishing.
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Display & Routing
    |--------------------------------------------------------------------------
    */
    'display_type'   => env('TZC_DISPLAY_TYPE', 'menu'),
    'default_screen' => env('TZC_DEFAULT_SCREEN', 'new'), // new | last | pinned
    'frontend_slug'  => 'chat',

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    | Keys map to setting() keys in the DB.
    */
    'features' => [
        'suggestions'      => 'ai_chat_pro_suggestions',
        'image_generation' => 'ai_chat_pro_image_generation_feature',
        'canvas'           => 'ai_chat_pro_canvas',
        'multi_model'      => 'ai_chat_pro_multi_model_feature',
        'file_chat'        => 'chatpro_file_chat_allowed',
        'temp_chat'        => 'chatpro-temp-chat-allowed',
        'folders'          => true,
        'memory'           => true,
        'share'            => true,
        'webchat'          => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Guest / Temp Chat
    |--------------------------------------------------------------------------
    */
    'guest' => [
        'daily_message_limit_key' => 'guest_user_daily_message_limit',
        'bottom_text_key'         => 'guest_user_bottom_text',
        'session_ttl_hours'       => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | Memory
    |--------------------------------------------------------------------------
    */
    'memory' => [
        'guest_cleanup_days' => 90,
    ],

    /*
    |--------------------------------------------------------------------------
    | File Chat
    |--------------------------------------------------------------------------
    */
    'file_chat' => [
        'allowed_mimes' => ['pdf', 'doc', 'docx', 'txt', 'csv', 'xlsx', 'xls'],
        'max_size_mb'   => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Webchat
    |--------------------------------------------------------------------------
    */
    'webchat' => [
        'embedding_model'   => 'text-embedding-ada-002',
        'realtime_search'   => env('TZC_WEBCHAT_REALTIME_SEARCH', false),
        'search_provider'   => env('TZC_WEBCHAT_SEARCH_PROVIDER', 'serper'),
        'max_crawl_pages'   => 10,
        'similarity_cutoff' => 0.7,
        'domain_restrict'   => env('TZC_WEBCHAT_DOMAIN_RESTRICT', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Canvas
    |--------------------------------------------------------------------------
    */
    'canvas' => [
        'min_pane_px' => 415,
        'ai_actions'  => [
            'rewrite', 'summarize', 'make_longer', 'make_shorter',
            'improve', 'fix_grammar', 'simplify', 'translate',
            'change_style', 'change_tone',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders
    |--------------------------------------------------------------------------
    */
    'folders' => [
        'chat_types' => ['chatpro', 'chatpro-temp', 'chatPro'],
        'per_page'   => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Chatbot Training
    |--------------------------------------------------------------------------
    */
    'training' => [
        'types'           => ['qa', 'text', 'pdf', 'url'],
        'status_waiting'  => 'waiting',
        'status_trained'  => 'trained',
        'allowed_uploads' => ['pdf', 'xls', 'xlsx', 'csv'],
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Provider Layer (TitanZeroChat AIClientFactory)
    |--------------------------------------------------------------------------
    */
    'ai' => [
        'default_provider' => env('TZC_AI_PROVIDER', 'openai'),
        'providers' => [
            'openai' => [
                'api_key'     => env('OPENAI_API_KEY'),
                'chat_model'  => env('TZC_OPENAI_CHAT_MODEL', 'gpt-4o-mini'),
                'embed_model' => env('TZC_OPENAI_EMBED_MODEL', 'text-embedding-ada-002'),
            ],
            'anthropic' => [
                'api_key' => env('ANTHROPIC_API_KEY'),
                'model'   => env('TZC_ANTHROPIC_MODEL', 'claude-sonnet-4-6'),
            ],
        ],
        'pgvector' => [
            'enabled'    => env('TZC_PGVECTOR_ENABLED', false),
            'dimensions' => env('TZC_EMBED_DIMENSIONS', 1536),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Third-Party Plugin Auto-Discovery
    |--------------------------------------------------------------------------
    */
    'plugins' => [
        // \App\Modules\TitanZeroVoice\TzVoicePlugin::class,
    ],
];
