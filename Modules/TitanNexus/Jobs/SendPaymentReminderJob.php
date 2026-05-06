<?php
namespace Modules\TitanNexus\Jobs;
class SendPaymentReminderJob { public function __construct(public array $payload = []) {} public function handle(): void {} }
