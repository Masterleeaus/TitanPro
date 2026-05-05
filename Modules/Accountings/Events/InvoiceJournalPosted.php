<?php
namespace Modules\Accountings\Events;
class InvoiceJournalPosted { public function __construct(public array $payload = []) {} }
