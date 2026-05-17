<?php

return [
    'prefix' => 'graphql',
    'schemas' => [
        'default' => [
            'query' => [],
            'mutation' => [],
        ],
        'titantalk' => [
            'query' => [],
            'mutation' => [],
            'subscription' => [],
            'schema' => base_path('Modules/TitanTalk/GraphQL/schema.graphql'),
        ],
    ],
    'types' => [],
];

