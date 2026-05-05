<?php
namespace Modules\TitanNexus\Services;
use Modules\TitanNexus\Models\LeadRecord;use Modules\TitanNexus\Services\Contracts\TitanNexusServiceContract;
class TitanNexusService implements TitanNexusServiceContract{public function listForCompany(int $companyId,int $perPage=25){return LeadRecord::query()->forCompany($companyId)->latest()->paginate($perPage);}}
