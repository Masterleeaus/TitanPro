<?php

return [
    'default_provider' => env('TITANNEXUS_VOICE_PROVIDER', 'bland'),

    'bland' => [
        'base_url' => env('BLAND_AI_BASE_URL', 'https://api.bland.ai'),
        'api_key' => env('BLAND_AI_API_KEY'),
        'encrypted_key' => env('BLAND_AI_ENCRYPTED_KEY'),
        'voice' => env('BLAND_AI_VOICE'),
        'max_duration' => (int) env('BLAND_AI_MAX_DURATION', 12),
        'temperature' => (float) env('BLAND_AI_TEMPERATURE', 0.5),
    ],

    'vapi' => [
        'base_url' => env('VAPI_AI_BASE_URL', 'https://api.vapi.ai'),
        'api_key' => env('VAPI_AI_API_KEY'),
        'assistant_id' => env('VAPI_AI_ASSISTANT_ID'),
        'phone_number_id' => env('VAPI_AI_PHONE_NUMBER_ID'),
    ],

    'outbound' => [
        'system_prompt' => env('TITANNEXUS_VOICE_OUTBOUND_PROMPT', 'Call the lead, confirm the service requirement, qualify the opportunity, and request the next booking step.'),
        'first_sentence' => env('TITANNEXUS_VOICE_FIRST_SENTENCE', 'Hi, this is a quick call about your cleaning service request.'),
    ],
];
