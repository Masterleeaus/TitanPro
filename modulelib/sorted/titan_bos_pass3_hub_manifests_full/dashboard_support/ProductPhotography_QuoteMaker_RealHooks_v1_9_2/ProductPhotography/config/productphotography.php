<?php

return [
    'surface_name' => 'QuoteMaker',
    'route_prefix' => 'quotemaker',
    'route_name_prefix' => 'dashboard.user.quotemaker.',
    'menu_path_fallbacks' => [
        'index' => 'dashboard.user.quotemaker.index',
        'gallery' => 'dashboard.user.quotemaker.gallery',
    ],
    'visual_modes' => [
        'quote_preview'  => 'Quote Preview',
        'before_after'   => 'Before / After Concept',
        'proposal_cover' => 'Proposal Cover Graphic',
        'scope_visual'   => 'Scope Illustration',
    ],
    'package_tiers' => [
        'basic'    => 'Basic',
        'standard' => 'Standard',
        'premium'  => 'Premium',
    ],
];
