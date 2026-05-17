<?php

namespace Modules\ExampleModule\UI\Cards;

class ZeroOperatorCard
{
    public string $visibleName = 'Zero';
    public string $agent = 'demo.agent';
    public bool $voiceEnabled = true;

    public function capabilities(): array
    {
        return ['chat', 'voice', 'actions', 'citations', 'structured_ui', 'channel_fallbacks'];
    }
}
