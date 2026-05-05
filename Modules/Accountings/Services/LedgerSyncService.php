<?php
namespace Modules\Accountings\Services;
class LedgerSyncService {
 public function postInvoiceSent(object|array $invoice,array $context=[]): array { return ['type'=>'invoice_sent','invoice_id'=>data_get($invoice,'id'),'lines'=>[['side'=>'debit','account'=>'accounts_receivable','amount'=>data_get($invoice,'total',0)],['side'=>'credit','account'=>'revenue','amount'=>data_get($invoice,'subtotal',data_get($invoice,'total',0))],['side'=>'credit','account'=>'gst_payable','amount'=>data_get($invoice,'tax_total',0)]],'context'=>$context]; }
 public function postWriteOff(object|array $invoice,array $context=[]): array { $amount=data_get($invoice,'balance_due',data_get($invoice,'total',0)); return ['type'=>'write_off','invoice_id'=>data_get($invoice,'id'),'lines'=>[['side'=>'debit','account'=>'bad_debt_expense','amount'=>$amount],['side'=>'credit','account'=>'accounts_receivable','amount'=>$amount]],'context'=>$context]; }
}
