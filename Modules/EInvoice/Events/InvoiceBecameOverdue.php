<?php
namespace Modules\EInvoice\Events;
use Modules\EInvoice\Entities\Invoice;
class InvoiceBecameOverdue { public function __construct(public Invoice $invoice, public array $context = []) {} }
