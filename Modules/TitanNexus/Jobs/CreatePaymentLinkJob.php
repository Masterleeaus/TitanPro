<?php
namespace Modules\TitanNexus\Jobs;
class CreatePaymentLinkJob { public function __construct(public array $payload = []) {} public function handle(): void {} }
