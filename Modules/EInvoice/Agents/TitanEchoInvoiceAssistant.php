<?php
namespace Modules\EInvoice\Agents;
class TitanEchoInvoiceAssistant { public function name(): string { return 'TitanEcho Invoice Assistant'; } public function audience(): string { return 'customer_of_platform_user'; } public function purpose(): string { return 'External customer-facing invoice conversation spec. Runtime may live in customer portal/ZeroPay, not inside Titan Money payment execution.'; } }
