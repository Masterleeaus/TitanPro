<?php
namespace Modules\TitanNexus\Notifications;
class JobStatusNotification { public function __construct(public array $payload = []) {} public function toArray(): array { return $this->payload; } }
