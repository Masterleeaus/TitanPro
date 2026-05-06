<?php
return [
    'name' => 'TitanNexus',
    'alias' => 'titan-nexus',
    'table_prefix' => 'example_',
    'tenant_column' => 'company_id',
    'features' => [
        'api' => true,
        'filament' => true,
        'imports' => true,
        'exports' => true,
        'signals' => true,
        'ai_native' => true,
        'module_agent' => true,
        'knowledge_pack' => true,
    ],
    'ai' => [
        'core_runtime' => 'TitanCore',
        'system_supervisor' => 'TitanZero',
        'agent_owner' => 'TitanAgents',
        'agent_manifest' => base_path('Modules/TitanNexus/Agents/ModuleAgent/agent.manifest.json'),
        'indexing_manifest' => base_path('Modules/TitanNexus/AI/Indexing/indexing.manifest.json'),
    ],
];
