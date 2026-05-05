<?php
namespace Modules\Accountings\Actions;
class PostGstForInvoiceAction { public function execute(object|array $invoice): array { return ['invoice_id'=>data_get($invoice,'id'),'gst_amount'=>(float)data_get($invoice,'tax_total',0),'account'=>'gst_payable']; } }
