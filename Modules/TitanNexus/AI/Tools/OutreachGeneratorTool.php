<?php

namespace Modules\TitanNexus\AI\Tools;

class OutreachGeneratorTool
{
    public function describe(): string
    {
        return 'Generates editable outreach and follow-up copy from tenant context.';
    }

    public function handle(array $payload = []): array
    {
        return [
            'status' => 'draft',
            'tool' => __CLASS__,
            'input' => $payload,
            'approval_required' => true,
        ];
    }
}
