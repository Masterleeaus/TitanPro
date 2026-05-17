<?php

namespace Modules\InstantAds\Support\DTOs;

class AdGenerationDTO
{
    public function __construct(
        public readonly string $prompt,
        public readonly string $model,
        public readonly int $imageCount,
        public readonly ?string $style,
        public readonly string $aspectRatio,
        public readonly ?int $companyId,
        public readonly ?int $userId,
    ) {}

    /**
     * @param  array<string, mixed>  $input
     */
    public static function fromArray(array $input, ?int $companyId = null, ?int $userId = null): self
    {
        return new self(
            prompt: (string) ($input['prompt'] ?? ''),
            model: (string) ($input['model'] ?? 'dall-e-3'),
            imageCount: max(1, (int) ($input['image_count'] ?? 1)),
            style: isset($input['style']) ? (string) $input['style'] : null,
            aspectRatio: (string) ($input['aspect_ratio'] ?? '1:1'),
            companyId: $companyId,
            userId: $userId,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toRecordParams(): array
    {
        return [
            'prompt' => $this->prompt,
            'model' => $this->model,
            'image_count' => $this->imageCount,
            'style' => $this->style,
            'aspect_ratio' => $this->aspectRatio,
        ];
    }
}
