<?php
namespace Modules\TitanNexus\Events;
class PaymentReceived { public function __construct(public array $payload = []) {} }
