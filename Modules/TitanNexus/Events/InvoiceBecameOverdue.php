<?php
namespace Modules\TitanNexus\Events;
class InvoiceBecameOverdue { public function __construct(public array $payload = []) {} }
