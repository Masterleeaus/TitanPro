<?php

namespace Modules\InstantAds\AI\Tools;

use Modules\InstantAds\Actions\GenerateAdImageAction;
use Modules\InstantAds\Support\Scopes\ScopedByCompany;

class GenerateAdImageTool
{
    public function __construct(private readonly GenerateAdImageAction $action) {}

    /**
     * @param  array<string, mixed>  $input
     * @return array{url:string,provider:string,prompt:string}
     */
    public function execute(array $input): array
    {
        $companyId = ScopedByCompany::resolveCompanyId();

        if ($companyId === null) {
            throw new \RuntimeException('InstantAds AI tools require authenticated tenant context.');
        }

        $input['company_id'] = $companyId;

        return $this->action->execute($input);
    }
}
