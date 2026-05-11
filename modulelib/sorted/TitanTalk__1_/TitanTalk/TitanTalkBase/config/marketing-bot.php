<?php

return [
    'version'              => 1.1,
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
];
