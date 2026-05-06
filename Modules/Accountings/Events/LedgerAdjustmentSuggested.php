<?php
namespace Modules\Accountings\Events;
class LedgerAdjustmentSuggested { public function __construct(public array $payload = []) {} }
