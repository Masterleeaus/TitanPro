<?php
namespace Modules\EInvoice\Agents;
use Modules\EInvoice\Tools\ExplainInvoiceTool;
use Modules\EInvoice\Tools\GenerateLateFollowupTool;
use Modules\EInvoice\Tools\InvoiceRiskScoreTool;
use Modules\EInvoice\Tools\SuggestPaymentPlanTool;
use Modules\Accountings\Tools\ForecastReceivableVelocityTool;
use Modules\Accountings\Tools\PostInvoiceJournalTool;
use Modules\Accountings\Tools\SuggestJournalCorrectionTool;
class MoneyAgent { public function name(): string { return 'TitanZero Finance'; } public function goal(): string { return 'Help platform users with invoicing, accounting, receivables, statements, GST hints, and late-invoice follow-up. It may assist payment conversations but must not execute payments.'; } public function audience(): string { return 'platform_user'; } public function domains(): array { return ['invoice_lifecycle','late_invoice_followup','customer_statements','accounting_handoff','gst_reporting','receivables_forecasting','zeropay_handoff']; } public function tools(): array { return [ExplainInvoiceTool::class,GenerateLateFollowupTool::class,SuggestPaymentPlanTool::class,InvoiceRiskScoreTool::class,PostInvoiceJournalTool::class,SuggestJournalCorrectionTool::class,ForecastReceivableVelocityTool::class]; } }
