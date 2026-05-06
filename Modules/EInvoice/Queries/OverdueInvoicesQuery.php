<?php
namespace Modules\EInvoice\Queries;
use Illuminate\Database\Eloquent\Builder;
use Modules\EInvoice\Entities\Invoice;
class OverdueInvoicesQuery
{
    public function query(?int $companyId = null): Builder
    { $query = Invoice::query()->whereNotIn('status',['paid','void','cancelled'])->whereDate('due_date','<',now()->toDateString()); if ($companyId) { $query->where('company_id',$companyId); } return $query; }
}
