<?php
return [
    'module'           => 'TitanChatbot',
    'area'             => 'ai',
    'enabled'          => true,

    /*
    |--------------------------------------------------------------------------
    | Active provider
    |--------------------------------------------------------------------------
    | One of: openai | anthropic | gemini
    */
    'provider'         => env('TITAN_CHATBOT_PROVIDER', env('TITAN_CHATBOT_AI_PROVIDER', 'openai')),

    /*
    |--------------------------------------------------------------------------
    | Per-provider configuration
    |--------------------------------------------------------------------------
    */
    'openai' => [
        'key'        => env('OPENAI_API_KEY'),
        'model'      => env('OPENAI_MODEL', env('TITAN_CHATBOT_MODEL', 'gpt-4o-mini')),
        'max_tokens' => (int) env('TITAN_CHATBOT_MAX_TOKENS', 4096),
    ],

    'anthropic' => [
        'key'        => env('ANTHROPIC_API_KEY'),
        'model'      => env('ANTHROPIC_MODEL', 'claude-3-haiku-20240307'),
        'max_tokens' => (int) env('ANTHROPIC_MAX_TOKENS', 4096),
    ],

    'gemini' => [
        'key'        => env('GEMINI_API_KEY'),
        'model'      => env('GEMINI_MODEL', 'gemini-pro'),
        'max_tokens' => (int) env('GEMINI_MAX_TOKENS', 4096),
    ],

    /*
    |--------------------------------------------------------------------------
    | Legacy / shared settings (kept for backwards compatibility)
    |--------------------------------------------------------------------------
    */
    'openai_api_key'   => env('OPENAI_API_KEY'),
    'model'            => env('TITAN_CHATBOT_MODEL', 'gpt-4o-mini'),
    'embeddings_model' => env('TITAN_CHATBOT_EMBEDDINGS_MODEL', 'text-embedding-3-small'),
    'temperature'      => (float) env('TITAN_CHATBOT_TEMPERATURE', 0.7),
    'rag_chunks_limit' => (int) env('TITAN_CHATBOT_RAG_CHUNKS', 5),
    'memory_limit'     => (int) env('TITAN_CHATBOT_MEMORY_LIMIT', 20),
    'fallback_message' => env('TITAN_CHATBOT_FALLBACK', "I'm sorry, I can't answer that right now. Please try again shortly."),
];
