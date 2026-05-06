<?php
namespace Modules\Accountings\Actions;
use Modules\Accountings\Services\ReceivableAgingService;
class RecalculateReceivableAgingAction { public function __construct(protected ReceivableAgingService $aging) {} public function execute(iterable $invoices): array { return $this->aging->buckets($invoices); } }
