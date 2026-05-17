<?php
namespace Modules\ExampleModule\Actions;

use Modules\ExampleModule\Models\ExampleRecord;

class LookupExampleRecordAction
{
    public function execute(int|string $id, int|string $companyId): ?ExampleRecord
    {
        return ExampleRecord::query()->where('company_id', $companyId)->whereKey($id)->first();
    }
}
