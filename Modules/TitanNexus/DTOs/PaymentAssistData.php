<?php
namespace Modules\TitanNexus\DTOs;
class PaymentAssistData { public function __construct(public mixed $tenant_id, public mixed $invoice_id, public mixed $payment_method, public mixed $amount, public mixed $payment_link, public mixed $approval_required) {} }
