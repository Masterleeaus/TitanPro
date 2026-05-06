<?php
namespace Modules\EInvoice\Services;
use Modules\EInvoice\Actions\CreateInvoiceAction;
use Modules\EInvoice\Actions\SendInvoiceAction;
class JobInvoiceAutomationService {
 public function __construct(protected CreateInvoiceAction $createInvoice, protected SendInvoiceAction $sendInvoice) {}
 public function onJobCompleted(object|array $job,array $context=[]): array {
  $result=$this->createInvoice->execute(['job_id'=>data_get($job,'id'),'customer_id'=>data_get($job,'customer_id'),'source'=>'job_completed']+$context);
  $invoice=is_array($result) ? ($result['invoice'] ?? null) : $result;
  return $invoice ? $this->sendInvoice->execute($invoice,['source'=>'job_completed']) : ['status'=>'invoice_creation_failed'];
 }
}
