<?php
namespace Modules\TitanNexus\Jobs;
class DetectOverdueInvoicesJob { public function __construct(public array $payload = []) {} public function handle(): void {} }
