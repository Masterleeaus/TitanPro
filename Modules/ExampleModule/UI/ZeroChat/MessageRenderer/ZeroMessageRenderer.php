<?php

namespace Modules\ExampleModule\UI\ZeroChat\MessageRenderer;

class ZeroMessageRenderer
{
    public function render(array $message, string $channel = 'filament'): array
    {
        return [
            'assistant' => 'Zero',
            'channel' => $channel,
            'blocks' => $message['blocks'] ?? [],
            'fallback' => $message['fallback'] ?? ($message['text'] ?? ''),
        ];
    }
}
