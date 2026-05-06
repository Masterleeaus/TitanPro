<?php
namespace Modules\EInvoice\Tools;
use Modules\EInvoice\Actions\SuggestPaymentPlanAction;
use Modules\EInvoice\Entities\Invoice;
class SuggestPaymentPlanTool { public string $name='suggest_payment_plan'; public string $description='Suggests an instalment plan for an overdue invoice. Does not process payments.'; public function __construct(protected SuggestPaymentPlanAction $action) {} public function handle(Invoice $invoice,int $instalments=3): array { return $this->action->execute($invoice,$instalments); } }
