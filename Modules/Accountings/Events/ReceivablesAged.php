<?php
namespace Modules\Accountings\Events;
class ReceivablesAged { public function __construct(public array $payload = []) {} }
