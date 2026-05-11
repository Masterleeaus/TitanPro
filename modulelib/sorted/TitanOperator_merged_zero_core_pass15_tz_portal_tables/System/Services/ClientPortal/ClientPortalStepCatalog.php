<?php

namespace App\Extensions\TitanOperator\System\Services\ClientPortal;

use Illuminate\Support\Collection;

class ClientPortalStepCatalog
{
    public function steps(): Collection
    {
        return collect([
            [
                'key' => 'configure',
                'title' => 'Configure',
                'summary' => 'Name the portal, set welcome copy, and choose the response style.',
                'view' => 'titan_operator::default.panel.user.client-portal.build.steps.configure',
            ],
            [
                'key' => 'customize',
                'title' => 'Customize',
                'summary' => 'Apply branding, colors, avatar selection, and client-facing voice.',
                'view' => 'titan_operator::default.panel.user.client-portal.build.steps.customize',
            ],
            [
                'key' => 'train',
                'title' => 'Train',
                'summary' => 'Load website, PDF, text, and Q&A sources into the existing chatbot training flow.',
                'view' => 'titan_operator::default.panel.user.client-portal.build.steps.train',
            ],
            [
                'key' => 'embed',
                'title' => 'Embed',
                'summary' => 'Preview the live widget, launch the runtime shell, and copy install-safe embed code.',
                'view' => 'titan_operator::default.panel.user.client-portal.build.steps.embed',
            ],
            [
                'key' => 'channel',
                'title' => 'Channel',
                'summary' => 'Turn on channels and route the finished build into the operator inbox.',
                'view' => 'titan_operator::default.panel.user.client-portal.build.steps.channel',
            ],
        ]);
    }
}
