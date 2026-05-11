<?php

return [
    'chat_features_config' => env('TITANZERO_CHAT_CONFIG', 'titanzerochat'),

    'name' => 'Titan Zero',
    'slug' => 'titanzero',
    'route' => [
        'prefix' => 'dashboard/user/titanzero',
        'name' => 'dashboard.user.titanzero.',
        'middleware' => ['web', 'auth', 'updateUserActivity'],
    ],
    'api' => [
        'prefix' => 'dashboard/user/titanzero/api',
        'name' => 'dashboard.user.titanzero.api.',
        'middleware' => ['web', 'auth', 'updateUserActivity'],
    ],
    'team_column' => 'team_id',
    'company_column' => 'company_id',
    'proposal_table' => 'tz_zero_proposals',
    'proposal_action_table' => 'tz_zero_proposal_actions',
    'audit_table' => 'tz_zero_audit_logs',
    'decision_table' => 'tz_zero_decisions',
    'rewind_table' => 'tz_zero_rewind_links',
    'memory_tables' => [
        'sessions' => 'tz_zero_sessions',
        'context_snapshots' => 'tz_zero_context_snapshots',
        'learning_deltas' => 'tz_zero_learning_deltas',
        'site_memory' => 'tz_zero_site_memory',
        'job_memory' => 'tz_zero_job_memory',
    ],
    'tri_core' => [
        'weights' => [
            'logic' => 50,
            'creator' => 20,
            'finance' => 20,
            'alien' => 10,
        ],
        'risk_bias' => [
            'low' => ['logic' => 45, 'creator' => 25, 'finance' => 20, 'alien' => 10],
            'medium' => ['logic' => 50, 'creator' => 20, 'finance' => 20, 'alien' => 10],
            'high' => ['logic' => 60, 'creator' => 10, 'finance' => 20, 'alien' => 10],
            'critical' => ['logic' => 65, 'creator' => 5, 'finance' => 20, 'alien' => 10],
        ],
    ],
    'signal_registry' => [
        'zero.proposal.created' => ['stage' => 'signal', 'direction' => 'inbound'],
        'zero.signal.zero_approved' => ['stage' => 'zero_approved', 'direction' => 'internal'],
        'zero.signal.processing' => ['stage' => 'processing', 'direction' => 'internal'],
        'zero.signal.bos_approved' => ['stage' => 'bos_approved', 'direction' => 'internal'],
        'zero.signal.processed' => ['stage' => 'processed', 'direction' => 'outbound'],
        'zero.node.handshake' => ['stage' => 'signal', 'direction' => 'federation'],
    ],
    'signal_sources' => [
        'governance' => [
            'tz_zero_proposals',
            'tz_zero_proposal_actions',
            'tz_zero_audit_logs',
            'tz_zero_decisions',
            'tz_zero_rewind_links',
        ],
        'memory' => [
            'tz_zero_sessions',
            'tz_zero_context_snapshots',
            'tz_zero_learning_deltas',
        ],
        'signals' => [
            'tz_signal_events',
            'tz_signal_subscriptions',
            'tz_zero_node_trust',
        ],
    ],
    'tool_groups' => [
        'chat' => ['zero_command_surface', 'zero_signal_preview'],
        'voice' => ['zero_voice_surface'],
        'governance' => ['proposal_queue', 'audit_timeline', 'rewind_guard'],
    ],
    'signal_stage_model' => [
        'states' => [
            'process',
            'signal',
            'zero_approved',
            'processing',
            'bos_approved',
            'processed',
            'rejected',
        ],
        'proposal_statuses' => [
            'initial' => 'process',
            'review_queue' => 'signal',
            'zero_approved' => 'approved',
            'processing' => 'processing',
            'bos_approved' => 'approved',
            'processed' => 'processed',
            'rejected' => 'rejected',
        ],
    ],
    'federation' => [
        'node_origin_default' => 'server',
        'trust_default' => 'standard',
        'dual_approval_required' => true,
        'handshake_signal' => 'zero.node.handshake',
        'supported_origins' => ['server', 'device', 'pwa', 'bridge'],
    ],
    'readiness' => [
        'chat' => 85,
        'memory' => 70,
        'voice' => 65,
        'governance' => 75,
        'audit' => 80,
    ],
];
