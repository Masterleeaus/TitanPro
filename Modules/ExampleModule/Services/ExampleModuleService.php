<?php
namespace Modules\ExampleModule\Services;
use Modules\ExampleModule\Models\ExampleRecord;use Modules\ExampleModule\Services\Contracts\ExampleModuleServiceContract;
class ExampleModuleService implements ExampleModuleServiceContract{public function listForCompany(int $companyId,int $perPage=25){return ExampleRecord::query()->forCompany($companyId)->latest()->paginate($perPage);}}
