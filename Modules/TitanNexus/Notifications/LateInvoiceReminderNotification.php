<?php
namespace Modules\TitanNexus\Notifications;
class LateInvoiceReminderNotification { public function __construct(public array $payload = []) {} public function toArray(): array { return $this->payload; } }
