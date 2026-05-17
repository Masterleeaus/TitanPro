<?php

namespace App\Extensions\ProductPhotography\Services;

class ImageRenderWorker
{
    public function render(array $payload): array
    {
        return [
            'status' => 'queued',
            'engine' => $payload['engine'] ?? 'productphotography_quotemaker_mvp',
            'note' => 'Render worker stub ready for AI engine connection.',
        ];
    }
}
