<?php
namespace Modules\TitanNexus\Events;
class LateInvoiceFollowupDue { public function __construct(public array $payload = []) {} }
