<?php

namespace Modules\InstantAds\Actions;

use Modules\InstantAds\Events\AdCreativeGenerated;
use Modules\InstantAds\Jobs\GenerateAdImageJob;
use Modules\InstantAds\Models\AdCreative;
use Modules\InstantAds\Support\DTOs\AdGenerationDTO;
use Modules\InstantAds\Support\Enums\GeneratorProvider;
use Modules\InstantAds\Support\Scopes\ScopedByCompany;

class GenerateAdImageAction
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function dispatch(array $params, ?int $userId, mixed $driver): int
    {
        $companyId = ScopedByCompany::resolveCompanyId() ?? (isset($params['company_id']) ? (int) $params['company_id'] : 1);
        $dto = AdGenerationDTO::fromArray($params, $companyId, $userId);

        $record = AdCreative::create([
            'company_id' => $companyId,
            'user_id' => $dto->userId,
            'guest_ip' => $dto->userId ? null : request()->ip(),
            'model' => $dto->model,
            'engine' => '',
            'prompt' => $dto->prompt,
            'params' => $dto->toRecordParams(),
            'status' => 'pending',
        ]);

        GenerateAdImageJob::dispatch($record->id, $userId, $driver);

        return (int) $record->id;
    }

    /**
     * @param  array<int, string>  $paths
     * @param  array<string, mixed>  $metadata
     */
    public function complete(AdCreative $record, array $paths, array $metadata = []): void
    {
        $record->markAsCompleted($paths, $metadata);
        $record->refresh();

        foreach ($paths as $path) {
            event(new AdCreativeGenerated(
                creative: $record,
                url: $path,
                provider: GeneratorProvider::fromModel($record->model)->value,
                prompt: $record->prompt,
                companyId: $record->company_id ? (int) $record->company_id : null,
            ));
        }
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{url:string,provider:string,prompt:string}
     */
    public function execute(array $input): array
    {
        $prompt = (string) ($input['prompt'] ?? '');
        $provider = GeneratorProvider::fromModel((string) ($input['model'] ?? 'dall-e-3'))->value;

        return [
            'url' => (string) ($input['url'] ?? ''),
            'provider' => $provider,
            'prompt' => $prompt,
        ];
    }
}
