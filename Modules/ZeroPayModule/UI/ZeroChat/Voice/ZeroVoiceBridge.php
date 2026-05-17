<?php

namespace Modules\ZeroPayModule\UI\ZeroChat\Voice;

class ZeroVoiceBridge
{
    public function transcriptToMessage(string $transcript): array
    {
        return ['assistant' => 'Zero', 'input_type' => 'voice', 'text' => $transcript];
    }
}
