<?php

namespace Modules\InstantAds\AI\Tools;

use Modules\InstantAds\Actions\CreateBatchVariantsAction;
use Modules\InstantAds\Support\Scopes\ScopedByCompany;

class CreateBatchVariantsTool
{
    public function __construct(private readonly CreateBatchVariantsAction $action) {}

    /**
     * @param  array<string, mixed>  $input
     * @return array{variants: array}
     */
    public function execute(array $input): array
    {
        if (ScopedByCompany::resolveCompanyId() === null) {
            throw new \RuntimeException('InstantAds AI tools require authenticated tenant context.');
        }

        return $this->action->execute($input);
    }
}
