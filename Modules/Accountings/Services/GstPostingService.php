<?php
namespace Modules\Accountings\Services;
class GstPostingService { public function snapshot(iterable $invoices=[]): array { $tax=collect($invoices)->sum(fn($invoice)=>(float)data_get($invoice,'tax_total',0)); return ['gst_payable_estimate'=>round($tax,2),'source'=>'invoice_tax_totals']; } }
