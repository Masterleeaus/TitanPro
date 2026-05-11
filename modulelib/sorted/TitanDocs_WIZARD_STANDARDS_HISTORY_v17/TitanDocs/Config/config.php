<?php

return [
    'name' => 'TitanDocs',

    // Titan Core integration settings for Titan Docs
    'titan_core' => [
        'default_model' => 'gpt-5-mini',

        // Profile definitions for different document types
        'profiles' => [
            'swms' => [
                'model' => 'gpt-5-mini',
                'system_prompt' => 'You are a construction safety and compliance assistant. You draft Safe Work Method Statements (SWMS) and site safety documents that align with good practice and are easy for trades to follow. Do not claim that documents are legally compliant or certified. Always remind the user that they must review and customise the SWMS for their specific site and activities.',
                'max_tokens' => 1200,
            ],
            'office' => [
                'model' => 'gpt-5-mini',
                'system_prompt' => 'You are a professional construction industry document assistant. You prepare clear, plain-English letters and site documents for clients, builders and supervisors. Do not give legal advice or claim that documents are legally compliant.',
                'max_tokens' => 800,
            ],
        ],

        // Map AiTemplateCategory names to profiles
        'category_profiles' => [
            'SWMS - Working at Heights'   => 'swms',
            'SWMS - Electrical'           => 'swms',
            'SWMS - Excavation'           => 'swms',
            'SWMS - Roofing'              => 'swms',
            'SWMS - Concreting'           => 'swms',
            'SWMS - Plumbing'             => 'swms',
            'SWMS - Carpentry / Framing'  => 'swms',
            'SWMS - Scaffolding & Edge Protection' => 'swms',
            'SWMS - Traffic Management' => 'swms',
            'SWMS - Confined Spaces' => 'swms',
            'SWMS - Demolition / Strip-out' => 'swms',
            // All other categories will fall back to the "office" profile.
        ],

        // Optional future queue usage
        'queue' => [
            'use_queue' => false,
            'connection' => null,
            'queue' => null,
        ],
    ],
];
