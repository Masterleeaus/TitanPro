<?php
namespace Modules\TitanNexus\DTOs;
class InvoiceFollowupData { public function __construct(public mixed $tenant_id, public mixed $invoice_id, public mixed $customer_name, public mixed $amount_due, public mixed $days_overdue, public mixed $channel, public mixed $tone, public mixed $payment_link) {} }
