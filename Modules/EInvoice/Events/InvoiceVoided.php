<?php
namespace Modules\EInvoice\Events;
use Modules\EInvoice\Entities\Invoice;
class InvoiceVoided { public function __construct(public Invoice $invoice, public array $context = []) {} }
