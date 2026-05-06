<?php
namespace Modules\TitanNexus\Jobs;
class ReconcilePaymentJob { public function __construct(public array $payload = []) {} public function handle(): void {} }
