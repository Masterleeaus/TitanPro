<?php

namespace Modules\InstantAds\Actions;

class CreateBatchVariantsAction
{
    /**
     * @param  array<string, mixed>  $input
     * @return array{variants: array<int, array{prompt:string, index:int}>}
     */
    public function execute(array $input): array
    {
        $prompt = trim((string) ($input['prompt'] ?? ''));
        $count = max(1, min(8, (int) ($input['count'] ?? 4)));

        $variants = [];

        for ($i = 1; $i <= $count; $i++) {
            $variants[] = [
                'prompt' => sprintf('%s (variant %d)', $prompt, $i),
                'index' => $i,
            ];
        }

        return ['variants' => $variants];
    }
}
