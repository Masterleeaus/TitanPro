<?php
namespace Modules\Accountings\Queries;
use Illuminate\Support\Facades\DB;
class ReceivablesExposureQuery
{
    public function totals(?int $companyId = null): array
    { $query = DB::table('invoices')->selectRaw('status, SUM(total) as total')->groupBy('status'); if ($companyId) { $query->where('company_id',$companyId); } return $query->pluck('total','status')->toArray(); }
}
