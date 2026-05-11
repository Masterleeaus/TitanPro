<?php

return [
    'version'              => 1.2,
    'notification_enabled' => env('TITANTALK_NOTIFICATION_ENABLED', true),
    'assistant_enabled'    => env('TITANTALK_ASSISTANT_ENABLED', true),
    'default_role_pack'    => env('TITANTALK_DEFAULT_ROLE_PACK', 'titantalk.reception'),
    'max_reply_chars'      => (int) env('TITANTALK_MAX_REPLY_CHARS', 1500),
    'intent_tools'         => [
        'booking'       => ['booking.availability', 'lead.create', 'booking.create'],
        'quote'         => ['lead.create', 'quote.prepare', 'customer.lookup'],
        'support'       => ['ticket.create', 'knowledge.search', 'ticket.reply'],
        'complaint'     => ['ticket.create', 'service.issue.log', 'human.handoff'],
        'invoice'       => ['invoice.lookup', 'invoice.send_copy', 'payment.status'],
        'reschedule'    => ['booking.lookup', 'booking.reschedule'],
        'cancel'        => ['booking.lookup', 'booking.cancel'],
        'human_handoff' => ['human.handoff'],
        'general'       => ['knowledge.search', 'customer.lookup'],
    ],
    'tools_enabled'        => env('TITANTALK_TOOLS_ENABLED', true),
    'tool_bridge'          => env('TITANTALK_TOOL_BRIDGE', 'predix'),
    'command_confidence_threshold' => (float) env('TITANTALK_COMMAND_CONFIDENCE_THRESHOLD', 0.72),
    'voice_wake_names'     => array_values(array_filter(array_map('trim', explode(',', (string) env('TITANTALK_VOICE_WAKE_NAMES', 'zero'))))),
    'copilot_enabled'      => env('TITANTALK_COPILOT_ENABLED', true),
    'copilot_summary_window'=> (int) env('TITANTALK_COPILOT_SUMMARY_WINDOW', 12),
    'context_history_window'=> (int) env('TITANTALK_CONTEXT_HISTORY_WINDOW', 10),
    'context_include_signals'=> env('TITANTALK_CONTEXT_INCLUDE_SIGNALS', true),

    'analytics_window_days' => (int) env('TITANTALK_ANALYTICS_WINDOW_DAYS', 14),
    'blocked_commands' => array_values(array_filter(array_map('trim', explode(',', (string) env('TITANTALK_BLOCKED_COMMANDS', ''))))),
    'workflow_enabled' => env('TITANTALK_WORKFLOW_ENABLED', true),
    'memory_persistence_days' => (int) env('TITANTALK_MEMORY_PERSISTENCE_DAYS', 90),

    'command_risk_map' => [
        'booking.create' => 'medium',
        'booking.reschedule' => 'high',
        'booking.cancel' => 'high',
        'invoice.lookup' => 'low',
        'invoice.send_copy' => 'medium',
        'ticket.create' => 'low',
        'ticket.reply' => 'medium',
        'human.handoff' => 'low',
    ],
];
